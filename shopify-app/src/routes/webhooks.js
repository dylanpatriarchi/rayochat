/**
 * Webhook routes for Shopify events
 */

const express = require('express');
const router = express.Router();
const crypto = require('crypto');
const { logger } = require('../utils/logger');
const { getShopConfig, saveAnalytics } = require('../database');

/**
 * Verify webhook signature
 */
function verifyWebhook(req, res, next) {
  const hmac = req.get('X-Shopify-Hmac-Sha256');
  const body = req.body;
  const hash = crypto
    .createHmac('sha256', process.env.SHOPIFY_WEBHOOK_SECRET || process.env.SHOPIFY_API_SECRET)
    .update(body, 'utf8')
    .digest('base64');

  if (hash !== hmac) {
    logger.warn('Webhook signature verification failed');
    return res.status(401).send('Unauthorized');
  }

  next();
}

/**
 * App uninstalled webhook
 */
router.post('/app/uninstalled', verifyWebhook, async (req, res) => {
  try {
    const shop = req.get('X-Shopify-Shop-Domain');
    logger.info(`App uninstalled from shop: ${shop}`);
    
    // Track uninstall event
    await saveAnalytics(shop, 'app_uninstalled', {
      timestamp: new Date().toISOString()
    });
    
    // Here you could clean up shop data if needed
    // For now, we'll keep the data for potential reinstalls
    
    res.status(200).send('OK');
  } catch (error) {
    logger.error('Error handling app uninstall webhook:', error);
    res.status(500).send('Error');
  }
});

/**
 * Shop update webhook
 */
router.post('/shop/update', verifyWebhook, async (req, res) => {
  try {
    const shop = req.get('X-Shopify-Shop-Domain');
    const shopData = JSON.parse(req.body);
    
    logger.info(`Shop updated: ${shop}`);
    
    // Track shop update
    await saveAnalytics(shop, 'shop_updated', {
      shop_name: shopData.name,
      plan_name: shopData.plan_name,
      timestamp: new Date().toISOString()
    });
    
    res.status(200).send('OK');
  } catch (error) {
    logger.error('Error handling shop update webhook:', error);
    res.status(500).send('Error');
  }
});

/**
 * Customer created webhook (optional - for better customer tracking)
 */
router.post('/customers/create', verifyWebhook, async (req, res) => {
  try {
    const shop = req.get('X-Shopify-Shop-Domain');
    const customer = JSON.parse(req.body);
    
    logger.info(`New customer created in ${shop}: ${customer.email}`);
    
    // Track new customer
    await saveAnalytics(shop, 'customer_created', {
      customer_id: customer.id,
      customer_email: customer.email,
      timestamp: new Date().toISOString()
    });
    
    res.status(200).send('OK');
  } catch (error) {
    logger.error('Error handling customer create webhook:', error);
    res.status(500).send('Error');
  }
});

/**
 * Order created webhook (optional - for conversion tracking)
 */
router.post('/orders/create', verifyWebhook, async (req, res) => {
  try {
    const shop = req.get('X-Shopify-Shop-Domain');
    const order = JSON.parse(req.body);
    
    logger.info(`New order created in ${shop}: ${order.name}`);
    
    // Track order creation
    await saveAnalytics(shop, 'order_created', {
      order_id: order.id,
      order_name: order.name,
      customer_id: order.customer?.id,
      total_price: order.total_price,
      timestamp: new Date().toISOString()
    });
    
    res.status(200).send('OK');
  } catch (error) {
    logger.error('Error handling order create webhook:', error);
    res.status(500).send('Error');
  }
});

/**
 * Theme publish webhook (to remind about widget installation)
 */
router.post('/themes/publish', verifyWebhook, async (req, res) => {
  try {
    const shop = req.get('X-Shopify-Shop-Domain');
    const theme = JSON.parse(req.body);
    
    logger.info(`Theme published in ${shop}: ${theme.name}`);
    
    // Track theme change
    await saveAnalytics(shop, 'theme_published', {
      theme_id: theme.id,
      theme_name: theme.name,
      timestamp: new Date().toISOString()
    });
    
    // Here you could send a notification to the merchant
    // about reinstalling the widget code if needed
    
    res.status(200).send('OK');
  } catch (error) {
    logger.error('Error handling theme publish webhook:', error);
    res.status(500).send('Error');
  }
});

module.exports = router;
