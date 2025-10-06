/**
 * Environment validation utility
 */

const Joi = require('joi');

const envSchema = Joi.object({
  SHOPIFY_API_KEY: Joi.string().required(),
  SHOPIFY_API_SECRET: Joi.string().required(),
  SHOPIFY_API_SCOPES: Joi.string().required(),
  SHOPIFY_APP_URL: Joi.string().uri().required(),
  NODE_ENV: Joi.string().valid('development', 'production', 'test').default('development'),
  PORT: Joi.number().port().default(3000),
  SESSION_SECRET: Joi.string().min(32).required(),
  RAG_SERVICE_URL: Joi.string().uri().required(),
  DATABASE_URL: Joi.string().default('sqlite:./database.sqlite')
}).unknown();

function validateEnv() {
  const { error, value } = envSchema.validate(process.env);
  
  if (error) {
    throw new Error(`Environment validation error: ${error.details[0].message}`);
  }
  
  // Update process.env with validated values
  Object.assign(process.env, value);
}

/**
 * Validate API key format
 */
function validateApiKey(apiKey) {
  if (!apiKey) {
    return { isValid: false, error: 'API key is required' };
  }
  
  if (!apiKey.startsWith('rc_s_')) {
    return { isValid: false, error: 'API key must start with "rc_s_"' };
  }
  
  if (apiKey.length !== 37) { // rc_s_ + 32 chars
    return { isValid: false, error: 'API key must be 37 characters long' };
  }
  
  const keyPart = apiKey.substring(5);
  if (!/^[a-zA-Z0-9]{32}$/.test(keyPart)) {
    return { isValid: false, error: 'API key contains invalid characters' };
  }
  
  return { isValid: true };
}

/**
 * Validate widget configuration
 */
function validateWidgetConfig(config) {
  const schema = Joi.object({
    api_key: Joi.string().pattern(/^rc_s_[a-zA-Z0-9]{32}$/).required(),
    widget_enabled: Joi.boolean().default(false),
    widget_title: Joi.string().min(1).max(50).default('AI Assistant'),
    welcome_message: Joi.string().min(1).max(200).default('Hi! How can I help you today?'),
    widget_position: Joi.string().valid('bottom-right', 'bottom-left').default('bottom-right'),
    widget_color: Joi.string().pattern(/^#[0-9A-Fa-f]{6}$/).default('#25D366')
  });
  
  return schema.validate(config);
}

/**
 * Validate chat message
 */
function validateChatMessage(message) {
  const schema = Joi.object({
    message: Joi.string().min(1).max(1000).required(),
    session_id: Joi.string().min(1).max(100).required(),
    customer_id: Joi.string().max(100).optional()
  });
  
  return schema.validate(message);
}

module.exports = {
  validateEnv,
  validateApiKey,
  validateWidgetConfig,
  validateChatMessage
};
