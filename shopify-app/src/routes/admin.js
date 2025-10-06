/**
 * Admin routes for Shopify app configuration
 */

const express = require('express');
const router = express.Router();
const { getShopConfig, updateShopConfig, getAnalytics, getConversationHistory } = require('../database');
const { validateWidgetConfig, validateApiKey } = require('../utils/validation');
const { logger } = require('../utils/logger');
const { testRagConnection } = require('../services/rag');

/**
 * Get shop configuration
 */
router.get('/config', async (req, res) => {
  try {
    const { shop } = res.locals.shopify.session;
    const config = await getShopConfig(shop);
    
    if (!config) {
      return res.status(404).json({
        success: false,
        error: 'Shop configuration not found'
      });
    }
    
    // Don't send sensitive data to frontend
    const safeConfig = {
      ...config,
      access_token: undefined,
      api_key: config.api_key ? '***' + config.api_key.slice(-4) : null
    };
    
    res.json({
      success: true,
      data: safeConfig
    });
  } catch (error) {
    logger.error('Error getting shop config:', error);
    res.status(500).json({
      success: false,
      error: 'Failed to get configuration'
    });
  }
});

/**
 * Update shop configuration
 */
router.post('/config', async (req, res) => {
  try {
    const { shop } = res.locals.shopify.session;
    const { error, value } = validateWidgetConfig(req.body);
    
    if (error) {
      return res.status(400).json({
        success: false,
        error: 'Validation error',
        details: error.details[0].message
      });
    }
    
    // Test API key if provided
    if (value.api_key) {
      const ragTest = await testRagConnection(value.api_key);
      if (!ragTest.success) {
        return res.status(400).json({
          success: false,
          error: 'Invalid API key or RAG service unavailable',
          details: ragTest.error
        });
      }
    }
    
    await updateShopConfig(shop, value);
    
    logger.info(`Shop config updated for ${shop}`);
    
    res.json({
      success: true,
      message: 'Configuration updated successfully'
    });
  } catch (error) {
    logger.error('Error updating shop config:', error);
    res.status(500).json({
      success: false,
      error: 'Failed to update configuration'
    });
  }
});

/**
 * Test API key
 */
router.post('/test-api-key', async (req, res) => {
  try {
    const { api_key } = req.body;
    
    // Validate format
    const validation = validateApiKey(api_key);
    if (!validation.isValid) {
      return res.status(400).json({
        success: false,
        error: validation.error
      });
    }
    
    // Test connection to RAG service
    const ragTest = await testRagConnection(api_key);
    
    res.json({
      success: ragTest.success,
      message: ragTest.success ? 'API key is valid' : 'API key test failed',
      error: ragTest.error
    });
  } catch (error) {
    logger.error('Error testing API key:', error);
    res.status(500).json({
      success: false,
      error: 'Failed to test API key'
    });
  }
});

/**
 * Get analytics data
 */
router.get('/analytics', async (req, res) => {
  try {
    const { shop } = res.locals.shopify.session;
    const { days = 30 } = req.query;
    
    const analytics = await getAnalytics(shop, parseInt(days));
    
    // Process analytics data for frontend
    const processedData = {
      totalConversations: 0,
      totalMessages: 0,
      averageResponseTime: 0,
      popularQuestions: [],
      dailyStats: []
    };
    
    // Group by date and event type
    const dailyData = {};
    analytics.forEach(row => {
      if (!dailyData[row.date]) {
        dailyData[row.date] = {};
      }
      dailyData[row.date][row.event_type] = row.count;
      
      // Count totals
      if (row.event_type === 'conversation_started') {
        processedData.totalConversations += row.count;
      } else if (row.event_type === 'message_sent') {
        processedData.totalMessages += row.count;
      }
    });
    
    // Convert to array for frontend
    processedData.dailyStats = Object.entries(dailyData).map(([date, events]) => ({
      date,
      ...events
    }));
    
    res.json({
      success: true,
      data: processedData
    });
  } catch (error) {
    logger.error('Error getting analytics:', error);
    res.status(500).json({
      success: false,
      error: 'Failed to get analytics'
    });
  }
});

/**
 * Get conversation history
 */
router.get('/conversations', async (req, res) => {
  try {
    const { shop } = res.locals.shopify.session;
    const { limit = 50 } = req.query;
    
    const conversations = await getConversationHistory(shop, parseInt(limit));
    
    // Group messages by conversation
    const groupedConversations = {};
    conversations.forEach(row => {
      if (!groupedConversations[row.id]) {
        groupedConversations[row.id] = {
          id: row.id,
          session_id: row.session_id,
          customer_id: row.customer_id,
          created_at: row.created_at,
          messages: []
        };
      }
      
      if (row.message) {
        groupedConversations[row.id].messages.push({
          message: row.message,
          response: row.response,
          created_at: row.message_created_at
        });
      }
    });
    
    const result = Object.values(groupedConversations);
    
    res.json({
      success: true,
      data: result
    });
  } catch (error) {
    logger.error('Error getting conversations:', error);
    res.status(500).json({
      success: false,
      error: 'Failed to get conversations'
    });
  }
});

/**
 * Get widget installation code
 */
router.get('/widget-code', async (req, res) => {
  try {
    const { shop } = res.locals.shopify.session;
    const config = await getShopConfig(shop);
    
    if (!config || !config.widget_enabled || !config.api_key) {
      return res.status(400).json({
        success: false,
        error: 'Widget not configured or disabled'
      });
    }
    
    const widgetCode = `<!-- RayoChat Widget -->
<script>
  (function() {
    var script = document.createElement('script');
    script.src = '${process.env.SHOPIFY_APP_URL}/widget/${shop}';
    script.async = true;
    document.head.appendChild(script);
  })();
</script>
<!-- End RayoChat Widget -->`;
    
    res.json({
      success: true,
      data: {
        code: widgetCode,
        instructions: [
          '1. Copy the code above',
          '2. Go to your Shopify Admin > Online Store > Themes',
          '3. Click "Actions" > "Edit code" on your active theme',
          '4. Open theme.liquid file',
          '5. Paste the code before the closing </head> tag',
          '6. Save the file',
          '7. The widget will appear on all pages of your store'
        ]
      }
    });
  } catch (error) {
    logger.error('Error getting widget code:', error);
    res.status(500).json({
      success: false,
      error: 'Failed to get widget code'
    });
  }
});

module.exports = router;
