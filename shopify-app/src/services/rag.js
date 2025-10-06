/**
 * RAG service integration
 */

const axios = require('axios');
const { logger } = require('../utils/logger');

const RAG_SERVICE_URL = process.env.RAG_SERVICE_INTERNAL_URL || process.env.RAG_SERVICE_URL;
const REQUEST_TIMEOUT = 30000; // 30 seconds

/**
 * Send message to RAG service
 */
async function sendToRag(apiKey, message) {
  try {
    logger.debug(`Sending message to RAG service: ${message.substring(0, 50)}...`);
    
    const response = await axios.post(`${RAG_SERVICE_URL}/ask`, {
      message: message
    }, {
      headers: {
        'Authorization': `Bearer ${apiKey}`,
        'X-API-Key': apiKey,
        'Content-Type': 'application/json',
        'User-Agent': 'RayoChat-Shopify-App/1.0.0'
      },
      timeout: REQUEST_TIMEOUT
    });
    
    if (response.data && response.data.success) {
      logger.debug('RAG service response received successfully');
      return {
        success: true,
        data: response.data.data
      };
    } else {
      logger.warn('RAG service returned unsuccessful response:', response.data);
      return {
        success: false,
        error: response.data?.error || 'Unknown error from RAG service'
      };
    }
    
  } catch (error) {
    logger.error('Error calling RAG service:', error.message);
    
    if (error.code === 'ECONNREFUSED') {
      return {
        success: false,
        error: 'RAG service is not available. Please try again later.'
      };
    }
    
    if (error.code === 'ENOTFOUND') {
      return {
        success: false,
        error: 'RAG service endpoint not found. Please check configuration.'
      };
    }
    
    if (error.response) {
      // Server responded with error status
      const status = error.response.status;
      const data = error.response.data;
      
      if (status === 401 || status === 403) {
        return {
          success: false,
          error: 'Invalid API key or unauthorized access.'
        };
      }
      
      if (status === 429) {
        return {
          success: false,
          error: 'Rate limit exceeded. Please try again later.'
        };
      }
      
      if (status >= 500) {
        return {
          success: false,
          error: 'RAG service is experiencing issues. Please try again later.'
        };
      }
      
      return {
        success: false,
        error: data?.error || `RAG service error (${status})`
      };
    }
    
    if (error.code === 'ECONNABORTED') {
      return {
        success: false,
        error: 'Request timeout. The AI service took too long to respond.'
      };
    }
    
    return {
      success: false,
      error: 'Failed to communicate with AI service. Please try again.'
    };
  }
}

/**
 * Test RAG service connection
 */
async function testRagConnection(apiKey) {
  try {
    logger.debug('Testing RAG service connection');
    
    const response = await axios.get(`${RAG_SERVICE_URL}/health`, {
      headers: {
        'Authorization': `Bearer ${apiKey}`,
        'X-API-Key': apiKey,
        'User-Agent': 'RayoChat-Shopify-App/1.0.0'
      },
      timeout: 10000 // 10 seconds for health check
    });
    
    if (response.status === 200) {
      logger.debug('RAG service connection test successful');
      return {
        success: true,
        data: response.data
      };
    } else {
      return {
        success: false,
        error: `Health check failed with status ${response.status}`
      };
    }
    
  } catch (error) {
    logger.error('RAG service connection test failed:', error.message);
    
    if (error.response?.status === 401 || error.response?.status === 403) {
      return {
        success: false,
        error: 'Invalid API key'
      };
    }
    
    return {
      success: false,
      error: 'RAG service is not available'
    };
  }
}

/**
 * Get RAG service status
 */
async function getRagStatus() {
  try {
    const response = await axios.get(`${RAG_SERVICE_URL}/health`, {
      timeout: 5000,
      headers: {
        'User-Agent': 'RayoChat-Shopify-App/1.0.0'
      }
    });
    
    return {
      available: true,
      status: response.data,
      response_time: response.headers['x-response-time'] || 'unknown'
    };
    
  } catch (error) {
    return {
      available: false,
      error: error.message,
      last_check: new Date().toISOString()
    };
  }
}

module.exports = {
  sendToRag,
  testRagConnection,
  getRagStatus
};
