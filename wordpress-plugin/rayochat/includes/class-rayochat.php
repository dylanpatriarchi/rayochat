<?php
/**
 * Main RayoChat Class
 */

if (!defined('ABSPATH')) {
    exit;
}

class RayoChat {
    
    protected $admin;
    protected $public;
    
    public function __construct() {
        $this->load_dependencies();
    }
    
    private function load_dependencies() {
        $this->admin = new RayoChat_Admin();
        $this->public = new RayoChat_Public();
    }
    
    public function run() {
        // Admin hooks
        add_action('admin_menu', array($this->admin, 'add_admin_menu'));
        add_action('admin_init', array($this->admin, 'register_settings'));
        
        // Public hooks
        add_action('wp_footer', array($this->public, 'render_widget'));
        add_action('wp_enqueue_scripts', array($this->public, 'enqueue_scripts'));
    }
    
    public static function get_option($key, $default = '') {
        return get_option('rayochat_' . $key, $default);
    }
    
    public static function update_option($key, $value) {
        return update_option('rayochat_' . $key, $value);
    }
}
