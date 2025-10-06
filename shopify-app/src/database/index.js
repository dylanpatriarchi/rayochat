/**
 * Database setup and management
 */

const sqlite3 = require('sqlite3').verbose();
const { open } = require('sqlite');
const path = require('path');
const { logger } = require('../utils/logger');

let db = null;

/**
 * Initialize database connection
 */
async function setupDatabase() {
  if (db) {
    return db;
  }

  try {
    const dbPath = process.env.DATABASE_URL?.replace('sqlite:', '') || path.join(__dirname, '../../data/database.sqlite');
    
    // Ensure directory exists
    const fs = require('fs');
    const dir = path.dirname(dbPath);
    if (!fs.existsSync(dir)) {
      fs.mkdirSync(dir, { recursive: true });
    }

    db = await open({
      filename: dbPath,
      driver: sqlite3.Database
    });

    // Enable foreign keys
    await db.exec('PRAGMA foreign_keys = ON');
    
    // Create tables
    await createTables();
    
    logger.info(`Database connected: ${dbPath}`);
    return db;
  } catch (error) {
    logger.error('Database setup failed:', error);
    throw error;
  }
}

/**
 * Create database tables
 */
async function createTables() {
  // Shop configurations table
  await db.exec(`
    CREATE TABLE IF NOT EXISTS shop_configs (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      shop TEXT UNIQUE NOT NULL,
      access_token TEXT NOT NULL,
      api_key TEXT,
      widget_enabled INTEGER DEFAULT 0,
      widget_title TEXT DEFAULT 'AI Assistant',
      welcome_message TEXT DEFAULT 'Hi! How can I help you today?',
      widget_position TEXT DEFAULT 'bottom-right',
      widget_color TEXT DEFAULT '#25D366',
      created_at TEXT NOT NULL,
      updated_at TEXT DEFAULT CURRENT_TIMESTAMP
    )
  `);

  // Chat conversations table
  await db.exec(`
    CREATE TABLE IF NOT EXISTS conversations (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      shop TEXT NOT NULL,
      session_id TEXT NOT NULL,
      customer_id TEXT,
      created_at TEXT NOT NULL,
      updated_at TEXT DEFAULT CURRENT_TIMESTAMP,
      FOREIGN KEY (shop) REFERENCES shop_configs(shop)
    )
  `);

  // Chat messages table
  await db.exec(`
    CREATE TABLE IF NOT EXISTS messages (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      conversation_id INTEGER NOT NULL,
      message TEXT NOT NULL,
      response TEXT,
      message_type TEXT DEFAULT 'user',
      tokens_used INTEGER DEFAULT 0,
      response_time INTEGER DEFAULT 0,
      created_at TEXT NOT NULL,
      FOREIGN KEY (conversation_id) REFERENCES conversations(id)
    )
  `);

  // Analytics table
  await db.exec(`
    CREATE TABLE IF NOT EXISTS analytics (
      id INTEGER PRIMARY KEY AUTOINCREMENT,
      shop TEXT NOT NULL,
      event_type TEXT NOT NULL,
      event_data TEXT,
      created_at TEXT NOT NULL,
      FOREIGN KEY (shop) REFERENCES shop_configs(shop)
    )
  `);

  // Create indexes for better performance
  await db.exec(`
    CREATE INDEX IF NOT EXISTS idx_shop_configs_shop ON shop_configs(shop);
    CREATE INDEX IF NOT EXISTS idx_conversations_shop ON conversations(shop);
    CREATE INDEX IF NOT EXISTS idx_conversations_session ON conversations(session_id);
    CREATE INDEX IF NOT EXISTS idx_messages_conversation ON messages(conversation_id);
    CREATE INDEX IF NOT EXISTS idx_analytics_shop ON analytics(shop);
    CREATE INDEX IF NOT EXISTS idx_analytics_created_at ON analytics(created_at);
  `);

  logger.info('Database tables created successfully');
}

/**
 * Get shop configuration
 */
async function getShopConfig(shop) {
  const db = await setupDatabase();
  return await db.get('SELECT * FROM shop_configs WHERE shop = ?', [shop]);
}

/**
 * Update shop configuration
 */
async function updateShopConfig(shop, config) {
  const db = await setupDatabase();
  const { api_key, widget_enabled, widget_title, welcome_message, widget_position, widget_color } = config;
  
  return await db.run(`
    UPDATE shop_configs 
    SET api_key = ?, widget_enabled = ?, widget_title = ?, welcome_message = ?, 
        widget_position = ?, widget_color = ?, updated_at = CURRENT_TIMESTAMP
    WHERE shop = ?
  `, [api_key, widget_enabled, widget_title, welcome_message, widget_position, widget_color, shop]);
}

/**
 * Create or get conversation
 */
async function getOrCreateConversation(shop, sessionId, customerId = null) {
  const db = await setupDatabase();
  
  // Try to get existing conversation
  let conversation = await db.get(
    'SELECT * FROM conversations WHERE shop = ? AND session_id = ? ORDER BY created_at DESC LIMIT 1',
    [shop, sessionId]
  );
  
  if (!conversation) {
    // Create new conversation
    const result = await db.run(
      'INSERT INTO conversations (shop, session_id, customer_id, created_at) VALUES (?, ?, ?, ?)',
      [shop, sessionId, customerId, new Date().toISOString()]
    );
    
    conversation = await db.get('SELECT * FROM conversations WHERE id = ?', [result.lastID]);
  }
  
  return conversation;
}

/**
 * Save message and response
 */
async function saveMessage(conversationId, message, response, tokensUsed = 0, responseTime = 0) {
  const db = await setupDatabase();
  
  return await db.run(`
    INSERT INTO messages (conversation_id, message, response, tokens_used, response_time, created_at)
    VALUES (?, ?, ?, ?, ?, ?)
  `, [conversationId, message, response, tokensUsed, responseTime, new Date().toISOString()]);
}

/**
 * Save analytics event
 */
async function saveAnalytics(shop, eventType, eventData = null) {
  const db = await setupDatabase();
  
  return await db.run(
    'INSERT INTO analytics (shop, event_type, event_data, created_at) VALUES (?, ?, ?, ?)',
    [shop, eventType, JSON.stringify(eventData), new Date().toISOString()]
  );
}

/**
 * Get analytics data
 */
async function getAnalytics(shop, days = 30) {
  const db = await setupDatabase();
  const since = new Date(Date.now() - days * 24 * 60 * 60 * 1000).toISOString();
  
  return await db.all(`
    SELECT event_type, COUNT(*) as count, DATE(created_at) as date
    FROM analytics 
    WHERE shop = ? AND created_at >= ?
    GROUP BY event_type, DATE(created_at)
    ORDER BY created_at DESC
  `, [shop, since]);
}

/**
 * Get conversation history
 */
async function getConversationHistory(shop, limit = 100) {
  const db = await setupDatabase();
  
  return await db.all(`
    SELECT c.*, m.message, m.response, m.created_at as message_created_at
    FROM conversations c
    LEFT JOIN messages m ON c.id = m.conversation_id
    WHERE c.shop = ?
    ORDER BY c.created_at DESC, m.created_at ASC
    LIMIT ?
  `, [shop, limit]);
}

/**
 * Close database connection
 */
async function closeDatabase() {
  if (db) {
    await db.close();
    db = null;
    logger.info('Database connection closed');
  }
}

module.exports = {
  setupDatabase,
  getShopConfig,
  updateShopConfig,
  getOrCreateConversation,
  saveMessage,
  saveAnalytics,
  getAnalytics,
  getConversationHistory,
  closeDatabase
};
