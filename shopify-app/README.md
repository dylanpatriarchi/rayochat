# RayoChat Shopify App

An intelligent AI chat widget for Shopify stores with seamless integration to the RayoChat RAG system.

## 🚀 Features

### 💬 Intelligent Chat Widget
- **AI-Powered**: Connects to RayoChat RAG service for intelligent responses
- **Modern Design**: iMessage-style interface with smooth animations
- **Responsive**: Works perfectly on desktop, tablet, and mobile
- **Customizable**: Configurable colors, position, and messages

### 🛡️ Secure & Robust
- **API Key Storage**: Securely stores API keys in Shopify app database
- **Input Validation**: Comprehensive validation with Joi
- **Rate Limiting**: Handled by RAG service guardrails
- **Error Handling**: Graceful error handling with user-friendly messages

### 📊 Analytics & Monitoring
- **Conversation Tracking**: Full conversation history and analytics
- **Event Tracking**: Widget interactions and performance metrics
- **Shopify Integration**: Leverages Shopify customer data
- **Real-time Monitoring**: Health checks and status monitoring

### ⚙️ Easy Management
- **Admin Dashboard**: Beautiful Polaris-based configuration interface
- **One-Click Installation**: Automatic theme code injection
- **Live Preview**: Real-time widget preview in admin
- **Webhook Support**: Automatic updates on store changes

## 📦 Installation

### Prerequisites
- Node.js 18+
- Shopify Partner Account
- RayoChat RAG service running
- ngrok (for development)

### Development Setup

1. **Clone and Install**
```bash
cd shopify-app
npm install
```

2. **Environment Configuration**
```bash
cp .env.example .env
# Edit .env with your Shopify app credentials
```

3. **Start Development Server**
```bash
npm run dev
```

4. **Expose with ngrok**
```bash
npm run ngrok
# Update SHOPIFY_APP_URL in .env with ngrok URL
```

### Docker Deployment

1. **Build and Run**
```bash
docker build -t rayochat-shopify .
docker run -p 3000:3000 --env-file .env rayochat-shopify
```

2. **Using Docker Compose**
```bash
docker-compose up -d
```

## 🔧 Configuration

### Environment Variables

```bash
# Shopify App Configuration
SHOPIFY_API_KEY=your_shopify_api_key
SHOPIFY_API_SECRET=your_shopify_api_secret
SHOPIFY_API_SCOPES=read_themes,write_themes,read_script_tags,write_script_tags
SHOPIFY_APP_URL=https://your-app-url.ngrok.io

# RayoChat Integration
RAG_SERVICE_URL=http://localhost:8002
RAG_SERVICE_INTERNAL_URL=http://rag:8000

# Security
SESSION_SECRET=your-32-character-secret
WEBHOOK_SECRET=your-webhook-secret
```

### Shopify App Setup

1. **Create Shopify App**
   - Go to Shopify Partners dashboard
   - Create new app
   - Set App URL to your ngrok/server URL
   - Set Allowed redirection URLs to `{APP_URL}/api/auth/callback`

2. **Configure Webhooks**
   - App uninstalled: `{APP_URL}/api/webhooks/app/uninstalled`
   - Shop update: `{APP_URL}/api/webhooks/shop/update`

3. **Set App Scopes**
   - `read_themes` - To inject widget code
   - `write_themes` - To modify theme files
   - `read_script_tags` - For script management
   - `write_script_tags` - For script injection

## 🎯 Usage

### For Merchants

1. **Install App**
   - Install from Shopify App Store or development URL
   - App will redirect to configuration dashboard

2. **Configure Widget**
   - Enter RayoChat API Key (format: `rc_s_...`)
   - Customize widget title, welcome message, colors
   - Choose widget position (bottom-right/left)
   - Enable widget on store

3. **Install Widget Code**
   - Go to Installation tab
   - Copy provided code
   - Paste in theme.liquid before `</head>`
   - Or use automatic injection (coming soon)

### For Customers

1. **Chat Interface**
   - Click chat button on store
   - Type questions in natural language
   - Receive AI-powered responses
   - Seamless mobile experience

## 🏗️ Architecture

### Backend Structure
```
src/
├── database/          # SQLite database management
├── routes/
│   ├── admin.js      # Admin dashboard API
│   ├── widget.js     # Widget chat API
│   └── webhooks.js   # Shopify webhooks
├── services/
│   └── rag.js        # RAG service integration
└── utils/
    ├── logger.js     # Logging utility
    └── validation.js # Input validation
```

### Database Schema
- **shop_configs**: Store configuration and API keys
- **conversations**: Track customer conversations
- **messages**: Store chat messages and responses
- **analytics**: Event tracking and metrics

### API Endpoints

#### Admin API (Authenticated)
- `GET /api/admin/config` - Get shop configuration
- `POST /api/admin/config` - Update configuration
- `POST /api/admin/test-api-key` - Test RAG API key
- `GET /api/admin/analytics` - Get analytics data
- `GET /api/admin/conversations` - Get conversation history
- `GET /api/admin/widget-code` - Get installation code

#### Widget API (Public)
- `POST /api/widget/chat/:shop` - Send chat message
- `GET /api/widget/health/:shop` - Widget health check
- `POST /api/widget/conversation/start/:shop` - Start conversation
- `POST /api/widget/analytics/:shop` - Track events

#### Widget Injection
- `GET /widget/:shop` - Serve widget JavaScript

## 🔒 Security

### API Key Protection
- API keys stored encrypted in database
- Never exposed to frontend
- Validated format and tested before saving
- Secure proxy to RAG service

### Input Validation
- Joi schema validation for all inputs
- XSS protection with content sanitization
- CSRF protection with Shopify session validation
- Rate limiting via RAG service guardrails

### Shopify Integration
- Official Shopify API libraries
- Webhook signature verification
- Session-based authentication
- App Bridge integration

## 📊 Analytics

### Tracked Events
- `widget_loaded` - Widget script loaded
- `widget_opened` - Chat window opened
- `widget_closed` - Chat window closed
- `message_sent` - User sent message
- `message_received` - AI response received
- `message_error` - Message processing error
- `conversation_started` - New conversation
- `api_error` - API communication error

### Metrics Dashboard
- Total conversations and messages
- Average response time
- Widget usage patterns
- Error rates and types
- Customer engagement metrics

## 🚀 Deployment

### Production Checklist

1. **Environment**
   - Set `NODE_ENV=production`
   - Use secure session secrets
   - Configure proper database (PostgreSQL recommended)
   - Set up SSL/HTTPS

2. **Monitoring**
   - Health check endpoint: `/health`
   - Docker health checks configured
   - Log aggregation setup
   - Error tracking (Sentry recommended)

3. **Performance**
   - Enable compression middleware
   - Configure caching headers
   - Database connection pooling
   - CDN for static assets

### Scaling Considerations
- Horizontal scaling with load balancer
- Database clustering for high availability
- Redis for session storage in multi-instance setup
- Separate RAG service scaling

## 🛠️ Development

### Available Scripts
- `npm start` - Start production server
- `npm run dev` - Start development with nodemon
- `npm run ngrok` - Expose local server with ngrok
- `npm run docker:build` - Build Docker image
- `npm run docker:run` - Run Docker container

### Testing
```bash
# Test API key validation
curl -X POST http://localhost:3000/api/admin/test-api-key \
  -H "Content-Type: application/json" \
  -d '{"api_key": "rc_s_your_test_key_here"}'

# Test widget health
curl http://localhost:3000/api/widget/health/your-shop.myshopify.com
```

### Debugging
- Set `LOG_LEVEL=debug` for verbose logging
- Use browser dev tools for widget debugging
- Check `/health` endpoint for service status
- Monitor database with SQLite browser

## 🤝 Integration with RayoChat RAG

### API Communication
- Secure Bearer token authentication
- Automatic retry on temporary failures
- Timeout handling (30s default)
- Error mapping for user-friendly messages

### Guardrails Integration
- Input validation before sending to RAG
- Output filtering for safe responses
- Rate limiting enforcement
- Content policy compliance

### Analytics Sync
- Conversation data shared with RAG analytics
- Token usage tracking
- Performance metrics correlation
- Error pattern analysis

## 📝 Changelog

### v1.0.0 (Current)
- Initial release
- Full Shopify app integration
- Widget with iMessage-style design
- Admin dashboard with Polaris UI
- RAG service integration
- Analytics and conversation tracking
- Docker support
- Comprehensive documentation

## 🆘 Support

### Common Issues

1. **Widget Not Appearing**
   - Check API key configuration
   - Verify widget is enabled
   - Confirm installation code in theme
   - Check browser console for errors

2. **API Key Errors**
   - Verify format: `rc_s_[32 characters]`
   - Test connection to RAG service
   - Check RAG service status
   - Validate API key in RayoChat dashboard

3. **Installation Problems**
   - Ensure proper Shopify app permissions
   - Check ngrok URL accessibility
   - Verify webhook endpoints
   - Review server logs for errors

### Getting Help
- Check server logs: `docker logs rayochat-shopify`
- Test endpoints with curl/Postman
- Review Shopify app configuration
- Contact RayoChat support with logs

## 📄 License

Proprietary - RayoChat by Dylan Patriarchi

---

**Built with ❤️ for intelligent customer support**
