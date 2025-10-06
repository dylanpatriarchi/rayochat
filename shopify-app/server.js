/**
 * RayoChat Shopify App Server
 * Main server file for the Shopify app
 */

const express = require('express');
const { join } = require('path');
const { readFileSync } = require('fs');
const cors = require('cors');
const helmet = require('helmet');
const compression = require('compression');
const morgan = require('morgan');
require('dotenv').config();

// Shopify imports
const { shopifyApi, LATEST_API_VERSION } = require('@shopify/shopify-api');
const { shopifyApp } = require('@shopify/shopify-app-express');
const { MemorySessionStorage } = require('@shopify/shopify-app-session-storage-memory');

// Local imports
const { setupDatabase } = require('./src/database');
const widgetRoutes = require('./src/routes/widget');
const adminRoutes = require('./src/routes/admin');
const webhookRoutes = require('./src/routes/webhooks');
const { logger } = require('./src/utils/logger');
const { validateEnv } = require('./src/utils/validation');

// Validate environment variables
validateEnv();

const PORT = process.env.PORT || 3000;
const isDev = process.env.NODE_ENV !== 'production';

// Initialize Shopify API
const shopify = shopifyApi({
  apiKey: process.env.SHOPIFY_API_KEY,
  apiSecret: process.env.SHOPIFY_API_SECRET,
  scopes: process.env.SHOPIFY_API_SCOPES.split(','),
  hostName: process.env.SHOPIFY_APP_URL.replace(/https?:\/\//, ''),
  apiVersion: LATEST_API_VERSION,
  isEmbeddedApp: true,
  logger: {
    level: process.env.LOG_LEVEL || 'info',
    httpRequests: isDev,
    timestamps: true
  }
});

// Initialize Shopify App
const app = express();

// Security middleware
app.use(helmet({
  contentSecurityPolicy: {
    directives: {
      defaultSrc: ["'self'"],
      scriptSrc: ["'self'", "'unsafe-inline'", "https://cdn.shopify.com"],
      styleSrc: ["'self'", "'unsafe-inline'", "https://cdn.shopify.com"],
      imgSrc: ["'self'", "data:", "https:"],
      connectSrc: ["'self'", "https://api.shopify.com", process.env.RAG_SERVICE_URL],
      frameSrc: ["'self'", "https://*.shopify.com"],
      frameAncestors: ["https://*.shopify.com", "https://admin.shopify.com"]
    }
  },
  crossOriginEmbedderPolicy: false
}));

// Basic middleware
app.use(compression());
app.use(morgan('combined', { stream: { write: message => logger.info(message.trim()) } }));
app.use(express.json({ limit: '10mb' }));
app.use(express.urlencoded({ extended: true, limit: '10mb' }));

// CORS for development
if (isDev) {
  app.use(cors({
    origin: [
      'https://admin.shopify.com',
      /https:\/\/.*\.shopify\.com$/,
      process.env.SHOPIFY_APP_URL
    ],
    credentials: true
  }));
}

// Initialize Shopify App Express
const shopifyApp = shopifyApp({
  api: shopify,
  auth: {
    path: '/api/auth',
    callbackPath: '/api/auth/callback'
  },
  webhooks: {
    path: '/api/webhooks'
  },
  sessionStorage: new MemorySessionStorage(),
  distribution: 'app',
  isEmbeddedApp: true
});

// Apply Shopify middleware
app.use(shopifyApp.config.auth.path, shopifyApp.auth.begin());
app.use(shopifyApp.config.auth.callbackPath, shopifyApp.auth.callback(), shopifyApp.redirectToShopifyOrAppRoot());
app.use(shopifyApp.config.webhooks.path, shopifyApp.processWebhooks({ webhookHandlers: {} }));

// Serve static files
app.use('/static', express.static(join(__dirname, 'public')));

// API Routes
app.use('/api/widget', widgetRoutes);
app.use('/api/admin', shopifyApp.validateAuthenticatedSession(), adminRoutes);
app.use('/api/webhooks', webhookRoutes);

// Health check endpoint
app.get('/health', (req, res) => {
  res.json({
    status: 'healthy',
    service: 'RayoChat Shopify App',
    version: '1.0.0',
    timestamp: new Date().toISOString(),
    environment: process.env.NODE_ENV
  });
});

// Main app route - serves the admin interface
app.get('/', shopifyApp.validateAuthenticatedSession(), async (req, res) => {
  try {
    const { shop, accessToken } = res.locals.shopify.session;
    
    // Get or create shop configuration
    const db = await setupDatabase();
    let shopConfig = await db.get('SELECT * FROM shop_configs WHERE shop = ?', [shop]);
    
    if (!shopConfig) {
      // Create default configuration
      await db.run(`
        INSERT INTO shop_configs (shop, access_token, widget_enabled, widget_title, welcome_message, widget_position, widget_color, created_at)
        VALUES (?, ?, ?, ?, ?, ?, ?, ?)
      `, [
        shop,
        accessToken,
        0, // disabled by default
        'AI Assistant',
        'Hi! How can I help you today?',
        'bottom-right',
        '#25D366',
        new Date().toISOString()
      ]);
      
      shopConfig = await db.get('SELECT * FROM shop_configs WHERE shop = ?', [shop]);
    }
    
    // Serve admin interface
    const html = readFileSync(join(__dirname, 'public/index.html'), 'utf8')
      .replace('{{SHOP}}', shop)
      .replace('{{CONFIG}}', JSON.stringify(shopConfig))
      .replace('{{API_KEY}}', process.env.SHOPIFY_API_KEY);
    
    res.send(html);
  } catch (error) {
    logger.error('Error loading admin interface:', error);
    res.status(500).send('Internal Server Error');
  }
});

// Widget injection endpoint (public)
app.get('/widget/:shop', async (req, res) => {
  try {
    const { shop } = req.params;
    
    // Get shop configuration
    const db = await setupDatabase();
    const shopConfig = await db.get('SELECT * FROM shop_configs WHERE shop = ? AND widget_enabled = 1', [shop]);
    
    if (!shopConfig || !shopConfig.api_key) {
      return res.status(404).send('// Widget not configured or disabled');
    }
    
    // Generate widget JavaScript
    const widgetJs = readFileSync(join(__dirname, 'public/widget.js'), 'utf8')
      .replace('{{SHOP}}', shop)
      .replace('{{CONFIG}}', JSON.stringify({
        title: shopConfig.widget_title,
        welcomeMessage: shopConfig.welcome_message,
        position: shopConfig.widget_position,
        color: shopConfig.widget_color,
        apiEndpoint: `${process.env.SHOPIFY_APP_URL}/api/widget/chat/${shop}`
      }));
    
    res.setHeader('Content-Type', 'application/javascript');
    res.setHeader('Cache-Control', 'public, max-age=3600'); // Cache for 1 hour
    res.send(widgetJs);
  } catch (error) {
    logger.error('Error serving widget:', error);
    res.status(500).send('// Error loading widget');
  }
});

// Error handling middleware
app.use((error, req, res, next) => {
  logger.error('Unhandled error:', error);
  
  if (res.headersSent) {
    return next(error);
  }
  
  res.status(500).json({
    success: false,
    error: 'Internal server error',
    message: isDev ? error.message : 'Something went wrong'
  });
});

// 404 handler
app.use((req, res) => {
  res.status(404).json({
    success: false,
    error: 'Not found',
    message: 'The requested resource was not found'
  });
});

// Initialize database and start server
async function startServer() {
  try {
    await setupDatabase();
    logger.info('Database initialized successfully');
    
    app.listen(PORT, () => {
      logger.info(`🚀 RayoChat Shopify App running on port ${PORT}`);
      logger.info(`📱 App URL: ${process.env.SHOPIFY_APP_URL}`);
      logger.info(`🔧 Environment: ${process.env.NODE_ENV}`);
      
      if (isDev) {
        logger.info('🔍 Development mode - CORS enabled');
        logger.info('💡 Don\'t forget to start ngrok: npm run ngrok');
      }
    });
  } catch (error) {
    logger.error('Failed to start server:', error);
    process.exit(1);
  }
}

// Graceful shutdown
process.on('SIGTERM', () => {
  logger.info('SIGTERM received, shutting down gracefully');
  process.exit(0);
});

process.on('SIGINT', () => {
  logger.info('SIGINT received, shutting down gracefully');
  process.exit(0);
});

// Start the server
startServer();
