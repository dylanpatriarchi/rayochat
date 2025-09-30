<?php
/**
 * Plugin Name: RayoChat AI Customer Care
 * Plugin URI: https://rayochat.com
 * Description: Integra RayoChat AI Customer Care nel tuo sito WordPress
 * Version: 1.0.0
 * Author: RayoChat
 * Author URI: https://rayochat.com
 * License: GPL2
 * Text Domain: rayochat
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('RAYOCHAT_VERSION', '1.0.0');
define('RAYOCHAT_PLUGIN_DIR', plugin_dir_path(__FILE__));
define('RAYOCHAT_PLUGIN_URL', plugin_dir_url(__FILE__));

// Include required files
require_once RAYOCHAT_PLUGIN_DIR . 'includes/class-rayochat.php';
require_once RAYOCHAT_PLUGIN_DIR . 'admin/class-rayochat-admin.php';
require_once RAYOCHAT_PLUGIN_DIR . 'public/class-rayochat-public.php';

// Initialize plugin
function rayochat_init() {
    $plugin = new RayoChat();
    $plugin->run();
}
add_action('plugins_loaded', 'rayochat_init');
