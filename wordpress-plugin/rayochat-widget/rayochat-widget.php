<?php
/**
 * Plugin Name: RayoChat Widget
 * Plugin URI: https://rayo.consulting
 * Description: Widget di chat AI intelligente per il tuo sito WordPress. Fornisce assistenza automatica ai visitatori utilizzando l'intelligenza artificiale.
 * Version: 1.0.0
 * Author: Dylan Patriarchi
 * Author URI: https://rayo.consulting
 * License: Proprietary
 * Text Domain: rayochat-widget
 * Domain Path: /languages
 * Requires at least: 5.0
 * Tested up to: 6.4
 * Requires PHP: 7.4
 */

// Prevent direct access
if (!defined('ABSPATH')) {
    exit;
}

// Define plugin constants
define('RAYOCHAT_PLUGIN_URL', plugin_dir_url(__FILE__));
define('RAYOCHAT_PLUGIN_PATH', plugin_dir_path(__FILE__));
define('RAYOCHAT_PLUGIN_VERSION', '1.0.0');
define('RAYOCHAT_API_URL', 'http://localhost:8002');

/**
 * Main RayoChat Widget Class
 */
class RayoChatWidget {
    
    /**
     * Constructor
     */
    public function __construct() {
        add_action('init', array($this, 'init'));
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'));
        add_action('wp_footer', array($this, 'render_chat_widget'));
        add_action('wp_ajax_rayochat_send_message', array($this, 'handle_chat_message'));
        add_action('wp_ajax_nopriv_rayochat_send_message', array($this, 'handle_chat_message'));
        
        // Admin hooks
        add_action('admin_menu', array($this, 'add_admin_menu'));
        add_action('admin_init', array($this, 'admin_init'));
        
        // Plugin activation/deactivation
        register_activation_hook(__FILE__, array($this, 'activate'));
        register_deactivation_hook(__FILE__, array($this, 'deactivate'));
    }
    
    /**
     * Initialize plugin
     */
    public function init() {
        load_plugin_textdomain('rayochat-widget', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }
    
    /**
     * Enqueue scripts and styles
     */
    public function enqueue_scripts() {
        // Only load on frontend if API key is configured
        if (!is_admin() && $this->get_api_key()) {
            wp_enqueue_script(
                'rayochat-widget-js',
                RAYOCHAT_PLUGIN_URL . 'assets/js/rayochat-widget.js',
                array('jquery'),
                RAYOCHAT_PLUGIN_VERSION,
                true
            );
            
            wp_enqueue_style(
                'rayochat-widget-css',
                RAYOCHAT_PLUGIN_URL . 'assets/css/rayochat-widget.css',
                array(),
                RAYOCHAT_PLUGIN_VERSION
            );
            
            // Localize script with AJAX URL and nonce
            wp_localize_script('rayochat-widget-js', 'rayochat_ajax', array(
                'ajax_url' => admin_url('admin-ajax.php'),
                'nonce' => wp_create_nonce('rayochat_nonce'),
                'strings' => array(
                    'placeholder' => __('Scrivi il tuo messaggio...', 'rayochat-widget'),
                    'send' => __('Invia', 'rayochat-widget'),
                    'error' => __('Errore durante l\'invio del messaggio. Riprova.', 'rayochat-widget'),
                    'connecting' => __('Connessione in corso...', 'rayochat-widget'),
                    'welcome' => __('Ciao! Come posso aiutarti oggi?', 'rayochat-widget'),
                )
            ));
        }
    }
    
    /**
     * Render chat widget HTML
     */
    public function render_chat_widget() {
        if (is_admin() || !$this->get_api_key()) {
            return;
        }
        
        $widget_position = get_option('rayochat_widget_position', 'bottom-right');
        $widget_color = get_option('rayochat_widget_color', '#25D366'); // WhatsApp green
        
        ?>
        <div id="rayochat-widget" class="rayochat-widget-<?php echo esc_attr($widget_position); ?>">
            <!-- Chat Toggle Button -->
            <div id="rayochat-toggle" class="rayochat-toggle" style="background-color: <?php echo esc_attr($widget_color); ?>">
                <svg class="rayochat-icon-chat" viewBox="0 0 24 24" width="24" height="24">
                    <path fill="white" d="M20,2H4A2,2 0 0,0 2,4V22L6,18H20A2,2 0 0,0 22,16V4A2,2 0 0,0 20,2M6,9V7H18V9H6M14,11V13H6V11H14M18,15H6V17H18V15Z"/>
                </svg>
                <svg class="rayochat-icon-close" viewBox="0 0 24 24" width="24" height="24" style="display: none;">
                    <path fill="white" d="M19,6.41L17.59,5L12,10.59L6.41,5L5,6.41L10.59,12L5,17.59L6.41,19L12,13.41L17.59,19L19,17.59L13.41,12L19,6.41Z"/>
                </svg>
            </div>
            
            <!-- Chat Window -->
            <div id="rayochat-window" class="rayochat-window" style="display: none;">
                <!-- Chat Header -->
                <div class="rayochat-header">
                    <div class="rayochat-header-info">
                        <div class="rayochat-avatar">
                            <svg viewBox="0 0 24 24" width="32" height="32">
                                <path fill="white" d="M12,2A10,10 0 0,0 2,12A10,10 0 0,0 12,22A10,10 0 0,0 22,12A10,10 0 0,0 12,2M7.07,18.28C7.5,17.38 10.12,16.5 12,16.5C13.88,16.5 16.5,17.38 16.93,18.28C15.57,19.36 13.86,20 12,20C10.14,20 8.43,19.36 7.07,18.28M18.36,16.83C16.93,15.09 13.46,14.5 12,14.5C10.54,14.5 7.07,15.09 5.64,16.83C4.62,15.5 4,13.82 4,12C4,7.59 7.59,4 12,4C16.41,4 20,7.59 20,12C20,13.82 19.38,15.5 18.36,16.83M12,6C10.06,6 8.5,7.56 8.5,9.5C8.5,11.44 10.06,13 12,13C13.94,13 15.5,11.44 15.5,9.5C15.5,7.56 13.94,6 12,6M12,11A1.5,1.5 0 0,1 10.5,9.5A1.5,1.5 0 0,1 12,8A1.5,1.5 0 0,1 13.5,9.5A1.5,1.5 0 0,1 12,11Z"/>
                            </svg>
                        </div>
                        <div class="rayochat-header-text">
                            <div class="rayochat-title"><?php echo esc_html(get_option('rayochat_widget_title', 'Assistente AI')); ?></div>
                            <div class="rayochat-status">Online</div>
                        </div>
                    </div>
                    <button id="rayochat-minimize" class="rayochat-minimize">
                        <svg viewBox="0 0 24 24" width="20" height="20">
                            <path fill="currentColor" d="M19,13H5V11H19V13Z"/>
                        </svg>
                    </button>
                </div>
                
                <!-- Chat Messages -->
                <div id="rayochat-messages" class="rayochat-messages">
                    <div class="rayochat-message rayochat-message-bot">
                        <div class="rayochat-message-content">
                            <?php echo esc_html(get_option('rayochat_welcome_message', 'Ciao! Come posso aiutarti oggi?')); ?>
                        </div>
                        <div class="rayochat-message-time"><?php echo date('H:i'); ?></div>
                    </div>
                </div>
                
                <!-- Chat Input -->
                <div class="rayochat-input-container">
                    <div class="rayochat-input-wrapper">
                        <input type="text" id="rayochat-input" placeholder="<?php echo esc_attr__('Scrivi il tuo messaggio...', 'rayochat-widget'); ?>" maxlength="1000">
                        <button id="rayochat-send" class="rayochat-send-btn">
                            <svg viewBox="0 0 24 24" width="20" height="20">
                                <path fill="currentColor" d="M2,21L23,12L2,3V10L17,12L2,14V21Z"/>
                            </svg>
                        </button>
                    </div>
                </div>
                
                <!-- Typing Indicator -->
                <div id="rayochat-typing" class="rayochat-typing" style="display: none;">
                    <div class="rayochat-typing-content">
                        <div class="rayochat-typing-dots">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                        <span class="rayochat-typing-text">Sto scrivendo...</span>
                    </div>
                </div>
            </div>
        </div>
        <?php
    }
    
    /**
     * Handle AJAX chat message
     */
    public function handle_chat_message() {
        // Verify nonce
        if (!wp_verify_nonce($_POST['nonce'], 'rayochat_nonce')) {
            wp_die(__('Errore di sicurezza', 'rayochat-widget'));
        }
        
        $message = sanitize_text_field($_POST['message']);
        $api_key = $this->get_api_key();
        
        if (empty($message) || empty($api_key)) {
            wp_send_json_error(__('Messaggio o API key mancante', 'rayochat-widget'));
        }
        
        // Make API call to RAG service
        $response = $this->call_rag_api($message, $api_key);
        
        if ($response && isset($response['success']) && $response['success']) {
            wp_send_json_success(array(
                'message' => $response['data']['response'],
                'sources' => isset($response['data']['sources']) ? $response['data']['sources'] : array(),
                'timestamp' => current_time('H:i')
            ));
        } else {
            $error_message = isset($response['error']) ? $response['error'] : __('Errore durante la comunicazione con il servizio AI', 'rayochat-widget');
            wp_send_json_error($error_message);
        }
    }
    
    /**
     * Call RAG API
     */
    private function call_rag_api($message, $api_key) {
        $api_url = RAYOCHAT_API_URL . '/ask';
        
        $body = json_encode(array(
            'message' => $message
        ));
        
        $args = array(
            'body' => $body,
            'headers' => array(
                'Content-Type' => 'application/json',
                'Authorization' => 'Bearer ' . $api_key,
                'X-API-Key' => $api_key,
                'User-Agent' => 'RayoChat-WordPress-Plugin/' . RAYOCHAT_PLUGIN_VERSION
            ),
            'timeout' => 30,
            'method' => 'POST'
        );
        
        $response = wp_remote_post($api_url, $args);
        
        if (is_wp_error($response)) {
            error_log('RayoChat API Error: ' . $response->get_error_message());
            return false;
        }
        
        $body = wp_remote_retrieve_body($response);
        $data = json_decode($body, true);
        
        if (json_last_error() !== JSON_ERROR_NONE) {
            error_log('RayoChat JSON Error: ' . json_last_error_msg());
            return false;
        }
        
        return $data;
    }
    
    /**
     * Get API key from options
     */
    private function get_api_key() {
        return get_option('rayochat_api_key', '');
    }
    
    /**
     * Add admin menu
     */
    public function add_admin_menu() {
        add_options_page(
            __('RayoChat Widget', 'rayochat-widget'),
            __('RayoChat Widget', 'rayochat-widget'),
            'manage_options',
            'rayochat-widget',
            array($this, 'admin_page')
        );
    }
    
    /**
     * Initialize admin settings
     */
    public function admin_init() {
        register_setting('rayochat_settings', 'rayochat_api_key');
        register_setting('rayochat_settings', 'rayochat_widget_title');
        register_setting('rayochat_settings', 'rayochat_welcome_message');
        register_setting('rayochat_settings', 'rayochat_widget_position');
        register_setting('rayochat_settings', 'rayochat_widget_color');
        register_setting('rayochat_settings', 'rayochat_widget_enabled');
        
        add_settings_section(
            'rayochat_main_section',
            __('Configurazione Principale', 'rayochat-widget'),
            array($this, 'main_section_callback'),
            'rayochat_settings'
        );
        
        add_settings_field(
            'rayochat_api_key',
            __('API Key', 'rayochat-widget'),
            array($this, 'api_key_callback'),
            'rayochat_settings',
            'rayochat_main_section'
        );
        
        add_settings_field(
            'rayochat_widget_enabled',
            __('Abilita Widget', 'rayochat-widget'),
            array($this, 'widget_enabled_callback'),
            'rayochat_settings',
            'rayochat_main_section'
        );
        
        add_settings_section(
            'rayochat_appearance_section',
            __('Aspetto', 'rayochat-widget'),
            array($this, 'appearance_section_callback'),
            'rayochat_settings'
        );
        
        add_settings_field(
            'rayochat_widget_title',
            __('Titolo Widget', 'rayochat-widget'),
            array($this, 'widget_title_callback'),
            'rayochat_settings',
            'rayochat_appearance_section'
        );
        
        add_settings_field(
            'rayochat_welcome_message',
            __('Messaggio di Benvenuto', 'rayochat-widget'),
            array($this, 'welcome_message_callback'),
            'rayochat_settings',
            'rayochat_appearance_section'
        );
        
        add_settings_field(
            'rayochat_widget_position',
            __('Posizione Widget', 'rayochat-widget'),
            array($this, 'widget_position_callback'),
            'rayochat_settings',
            'rayochat_appearance_section'
        );
        
        add_settings_field(
            'rayochat_widget_color',
            __('Colore Widget', 'rayochat-widget'),
            array($this, 'widget_color_callback'),
            'rayochat_settings',
            'rayochat_appearance_section'
        );
    }
    
    /**
     * Admin page HTML
     */
    public function admin_page() {
        ?>
        <div class="wrap">
            <h1><?php echo esc_html(get_admin_page_title()); ?></h1>
            
            <div class="rayochat-admin-header">
                <h2><?php _e('Configurazione RayoChat Widget', 'rayochat-widget'); ?></h2>
                <p><?php _e('Configura il widget di chat AI per il tuo sito WordPress.', 'rayochat-widget'); ?></p>
            </div>
            
            <form method="post" action="options.php">
                <?php
                settings_fields('rayochat_settings');
                do_settings_sections('rayochat_settings');
                submit_button(__('Salva Impostazioni', 'rayochat-widget'));
                ?>
            </form>
            
            <div class="rayochat-admin-info">
                <h3><?php _e('Informazioni', 'rayochat-widget'); ?></h3>
                <p><?php _e('Per ottenere la tua API Key, accedi al pannello di amministrazione di RayoChat e crea un nuovo sito.', 'rayochat-widget'); ?></p>
                <p><?php _e('L\'API Key deve iniziare con "rc_s_" seguito da 32 caratteri alfanumerici.', 'rayochat-widget'); ?></p>
            </div>
        </div>
        
        <style>
        .rayochat-admin-header {
            background: #fff;
            border: 1px solid #ccd0d4;
            border-radius: 4px;
            padding: 20px;
            margin: 20px 0;
        }
        .rayochat-admin-info {
            background: #f0f6fc;
            border: 1px solid #c3d9ff;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .rayochat-admin-info h3 {
            margin-top: 0;
            color: #0073aa;
        }
        </style>
        <?php
    }
    
    /**
     * Settings section callbacks
     */
    public function main_section_callback() {
        echo '<p>' . __('Configura le impostazioni principali del widget di chat.', 'rayochat-widget') . '</p>';
    }
    
    public function appearance_section_callback() {
        echo '<p>' . __('Personalizza l\'aspetto del widget di chat.', 'rayochat-widget') . '</p>';
    }
    
    /**
     * Settings field callbacks
     */
    public function api_key_callback() {
        $api_key = get_option('rayochat_api_key', '');
        echo '<input type="text" id="rayochat_api_key" name="rayochat_api_key" value="' . esc_attr($api_key) . '" class="regular-text" placeholder="rc_s_..." />';
        echo '<p class="description">' . __('Inserisci la tua API Key di RayoChat.', 'rayochat-widget') . '</p>';
    }
    
    public function widget_enabled_callback() {
        $enabled = get_option('rayochat_widget_enabled', '1');
        echo '<input type="checkbox" id="rayochat_widget_enabled" name="rayochat_widget_enabled" value="1" ' . checked('1', $enabled, false) . ' />';
        echo '<label for="rayochat_widget_enabled">' . __('Abilita il widget di chat sul sito', 'rayochat-widget') . '</label>';
    }
    
    public function widget_title_callback() {
        $title = get_option('rayochat_widget_title', 'Assistente AI');
        echo '<input type="text" id="rayochat_widget_title" name="rayochat_widget_title" value="' . esc_attr($title) . '" class="regular-text" />';
        echo '<p class="description">' . __('Titolo mostrato nell\'header del widget.', 'rayochat-widget') . '</p>';
    }
    
    public function welcome_message_callback() {
        $message = get_option('rayochat_welcome_message', 'Ciao! Come posso aiutarti oggi?');
        echo '<textarea id="rayochat_welcome_message" name="rayochat_welcome_message" rows="3" class="large-text">' . esc_textarea($message) . '</textarea>';
        echo '<p class="description">' . __('Messaggio di benvenuto mostrato all\'apertura della chat.', 'rayochat-widget') . '</p>';
    }
    
    public function widget_position_callback() {
        $position = get_option('rayochat_widget_position', 'bottom-right');
        $positions = array(
            'bottom-right' => __('In basso a destra', 'rayochat-widget'),
            'bottom-left' => __('In basso a sinistra', 'rayochat-widget'),
        );
        
        echo '<select id="rayochat_widget_position" name="rayochat_widget_position">';
        foreach ($positions as $value => $label) {
            echo '<option value="' . esc_attr($value) . '" ' . selected($position, $value, false) . '>' . esc_html($label) . '</option>';
        }
        echo '</select>';
    }
    
    public function widget_color_callback() {
        $color = get_option('rayochat_widget_color', '#25D366');
        echo '<input type="color" id="rayochat_widget_color" name="rayochat_widget_color" value="' . esc_attr($color) . '" />';
        echo '<p class="description">' . __('Colore del pulsante del widget (default: verde WhatsApp).', 'rayochat-widget') . '</p>';
    }
    
    /**
     * Plugin activation
     */
    public function activate() {
        // Set default options
        add_option('rayochat_widget_title', 'Assistente AI');
        add_option('rayochat_welcome_message', 'Ciao! Come posso aiutarti oggi?');
        add_option('rayochat_widget_position', 'bottom-right');
        add_option('rayochat_widget_color', '#25D366');
        add_option('rayochat_widget_enabled', '1');
    }
    
    /**
     * Plugin deactivation
     */
    public function deactivate() {
        // Clean up if needed
    }
}

// Initialize the plugin
new RayoChatWidget();
