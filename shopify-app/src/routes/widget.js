/**
 * Widget routes for chat functionality
 */

const express = require('express');
const router = express.Router();
const { getShopConfig, getOrCreateConversation, saveMessage, saveAnalytics } = require('../database');
const { validateChatMessage } = require('../utils/validation');
const { logger } = require('../utils/logger');
const { sendToRag } = require('../services/rag');

/**
 * Chat endpoint - handles messages from the widget
 */
router.post('/chat/:shop', async (req, res) => {
  const startTime = Date.now();
  
  try {
    const { shop } = req.params;
    const { error, value } = validateChatMessage(req.body);
    
    if (error) {
      return res.status(400).json({
        success: false,
        error: 'Invalid message format',
        details: error.details[0].message
      });
    }
    
    const { message, session_id, customer_id } = value;
    
    // Get shop configuration
    const shopConfig = await getShopConfig(shop);
    if (!shopConfig || !shopConfig.widget_enabled || !shopConfig.api_key) {
      return res.status(404).json({
        success: false,
        error: 'Widget not configured or disabled'
      });
    }
    
    // Get or create conversation
    const conversation = await getOrCreateConversation(shop, session_id, customer_id);
    
    // Track message event
    await saveAnalytics(shop, 'message_sent', {
      session_id,
      customer_id,
      message_length: message.length
    });
    
    // Send to RAG service
    const ragResponse = await sendToRag(shopConfig.api_key, message);
    
    if (!ragResponse.success) {
      // Save failed message
      await saveMessage(conversation.id, message, null, 0, Date.now() - startTime);
      
      // Track error
      await saveAnalytics(shop, 'message_error', {
        session_id,
        error: ragResponse.error
      });
      
      return res.status(500).json({
        success: false,
        error: ragResponse.error || 'Failed to get AI response'
      });
    }
    
    const responseTime = Date.now() - startTime;
    const tokensUsed = ragResponse.data?.tokens_used?.total || 0;
    
    // Save successful message and response
    await saveMessage(
      conversation.id, 
      message, 
      ragResponse.data.response, 
      tokensUsed, 
      responseTime
    );
    
    // Track successful response
    await saveAnalytics(shop, 'message_success', {
      session_id,
      customer_id,
      response_time: responseTime,
      tokens_used: tokensUsed,
      confidence: ragResponse.data.confidence
    });
    
    res.json({
      success: true,
      data: {
        response: ragResponse.data.response,
        confidence: ragResponse.data.confidence,
        sources: ragResponse.data.sources || [],
        timestamp: new Date().toISOString(),
        response_time: responseTime
      }
    });
    
  } catch (error) {
    logger.error('Error in chat endpoint:', error);
    
    // Track error
    try {
      const { shop } = req.params;
      const { session_id } = req.body;
      await saveAnalytics(shop, 'system_error', {
        session_id,
        error: error.message,
        endpoint: 'chat'
      });
    } catch (analyticsError) {
      logger.error('Error saving analytics:', analyticsError);
    }
    
    res.status(500).json({
      success: false,
      error: 'Internal server error'
    });
  }
});

/**
 * Widget health check
 */
router.get('/health/:shop', async (req, res) => {
  try {
    const { shop } = req.params;
    
    // Check shop configuration
    const shopConfig = await getShopConfig(shop);
    
    if (!shopConfig) {
      return res.status(404).json({
        success: false,
        error: 'Shop not found'
      });
    }
    
    const status = {
      widget_enabled: shopConfig.widget_enabled === 1,
      api_key_configured: !!shopConfig.api_key,
      shop: shop,
      timestamp: new Date().toISOString()
    };
    
    // Test RAG connection if API key is configured
    if (shopConfig.api_key) {
      const { testRagConnection } = require('../services/rag');
      const ragTest = await testRagConnection(shopConfig.api_key);
      status.rag_service = ragTest.success ? 'healthy' : 'unhealthy';
      status.rag_error = ragTest.error;
    }
    
    res.json({
      success: true,
      data: status
    });
    
  } catch (error) {
    logger.error('Error in widget health check:', error);
    res.status(500).json({
      success: false,
      error: 'Health check failed'
    });
  }
});

/**
 * Start conversation tracking
 */
router.post('/conversation/start/:shop', async (req, res) => {
  try {
    const { shop } = req.params;
    const { session_id, customer_id, page_url } = req.body;
    
    // Get shop configuration
    const shopConfig = await getShopConfig(shop);
    if (!shopConfig || !shopConfig.widget_enabled) {
      return res.status(404).json({
        success: false,
        error: 'Widget not enabled'
      });
    }
    
    // Create conversation
    const conversation = await getOrCreateConversation(shop, session_id, customer_id);
    
    // Track conversation start
    await saveAnalytics(shop, 'conversation_started', {
      session_id,
      customer_id,
      page_url,
      conversation_id: conversation.id
    });
    
    res.json({
      success: true,
      data: {
        conversation_id: conversation.id,
        welcome_message: shopConfig.welcome_message
      }
    });
    
  } catch (error) {
    logger.error('Error starting conversation:', error);
    res.status(500).json({
      success: false,
      error: 'Failed to start conversation'
    });
  }
});

/**
 * Widget analytics tracking
 */
router.post('/analytics/:shop', async (req, res) => {
  try {
    const { shop } = req.params;
    const { event_type, event_data } = req.body;
    
    if (!event_type) {
      return res.status(400).json({
        success: false,
        error: 'Event type is required'
      });
    }
    
    // Validate event types
    const allowedEvents = [
      'widget_loaded',
      'widget_opened',
      'widget_closed',
      'message_typed',
      'widget_error'
    ];
    
    if (!allowedEvents.includes(event_type)) {
      return res.status(400).json({
        success: false,
        error: 'Invalid event type'
      });
    }
    
    await saveAnalytics(shop, event_type, event_data);
    
    res.json({
      success: true,
      message: 'Event tracked successfully'
    });
    
  } catch (error) {
    logger.error('Error tracking analytics:', error);
    res.status(500).json({
      success: false,
      error: 'Failed to track event'
    });
  }
});

module.exports = router;
