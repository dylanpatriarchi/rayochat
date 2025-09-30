<?php
/**
 * Public-facing functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class RayoChat_Public {
    
    public function enqueue_scripts() {
        // Check if widget is enabled
        if (get_option('rayochat_enabled', '0') !== '1') {
            return;
        }
        
        // Enqueue Google Fonts
        wp_enqueue_style(
            'rayochat-fonts',
            'https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap',
            array(),
            RAYOCHAT_VERSION
        );
        
        // Enqueue widget styles
        wp_enqueue_style(
            'rayochat-widget',
            RAYOCHAT_PLUGIN_URL . 'public/css/widget.css',
            array(),
            RAYOCHAT_VERSION
        );
        
        // Enqueue widget script
        wp_enqueue_script(
            'rayochat-widget',
            RAYOCHAT_PLUGIN_URL . 'public/js/widget.js',
            array(),
            RAYOCHAT_VERSION,
            true
        );
        
        // Pass settings to JavaScript
        wp_localize_script('rayochat-widget', 'rayochatSettings', array(
            'apiKey' => get_option('rayochat_api_key', ''),
            'apiUrl' => get_option('rayochat_api_url', 'https://yourdomain.com/api/widget'),
            'position' => get_option('rayochat_position', 'bottom-right'),
            'primaryColor' => get_option('rayochat_primary_color', '#FF6B35'),
        ));
    }
    
    public function render_widget() {
        // Check if widget is enabled
        if (get_option('rayochat_enabled', '0') !== '1') {
            return;
        }
        
        // Check if API key is set
        $api_key = get_option('rayochat_api_key', '');
        if (empty($api_key)) {
            return;
        }
        
        // Render widget container
        echo '<div id="rayochat-widget-root"></div>';
    }
}
