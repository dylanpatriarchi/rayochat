<?php
/**
 * Admin functionality
 */

if (!defined('ABSPATH')) {
    exit;
}

class RayoChat_Admin {
    
    public function add_admin_menu() {
        add_options_page(
            'RayoChat Settings',
            'RayoChat',
            'manage_options',
            'rayochat-settings',
            array($this, 'render_settings_page')
        );
    }
    
    public function register_settings() {
        register_setting('rayochat_settings', 'rayochat_api_key');
        register_setting('rayochat_settings', 'rayochat_api_url');
        register_setting('rayochat_settings', 'rayochat_position');
        register_setting('rayochat_settings', 'rayochat_primary_color');
        register_setting('rayochat_settings', 'rayochat_enabled');
    }
    
    public function render_settings_page() {
        // Check user capabilities
        if (!current_user_can('manage_options')) {
            return;
        }
        
        // Save settings
        if (isset($_POST['rayochat_save_settings'])) {
            check_admin_referer('rayochat_settings_action', 'rayochat_settings_nonce');
            
            update_option('rayochat_api_key', sanitize_text_field($_POST['rayochat_api_key']));
            update_option('rayochat_api_url', esc_url_raw($_POST['rayochat_api_url']));
            update_option('rayochat_position', sanitize_text_field($_POST['rayochat_position']));
            update_option('rayochat_primary_color', sanitize_hex_color($_POST['rayochat_primary_color']));
            update_option('rayochat_enabled', isset($_POST['rayochat_enabled']) ? '1' : '0');
            
            echo '<div class="notice notice-success"><p>Impostazioni salvate con successo!</p></div>';
        }
        
        // Get current settings
        $api_key = get_option('rayochat_api_key', '');
        $api_url = get_option('rayochat_api_url', 'https://yourdomain.com/api/widget');
        $position = get_option('rayochat_position', 'bottom-right');
        $primary_color = get_option('rayochat_primary_color', '#FF6B35');
        $enabled = get_option('rayochat_enabled', '0');
        
        ?>
        <div class="wrap">
            <h1>
                <img src="<?php echo RAYOCHAT_PLUGIN_URL; ?>admin/icon.png" 
                     alt="RayoChat" 
                     style="width: 30px; height: 30px; vertical-align: middle; margin-right: 10px;">
                Impostazioni RayoChat
            </h1>
            
            <form method="post" action="">
                <?php wp_nonce_field('rayochat_settings_action', 'rayochat_settings_nonce'); ?>
                
                <table class="form-table">
                    <tr>
                        <th scope="row">
                            <label for="rayochat_enabled">Abilita Widget</label>
                        </th>
                        <td>
                            <input type="checkbox" 
                                   id="rayochat_enabled" 
                                   name="rayochat_enabled" 
                                   value="1" 
                                   <?php checked($enabled, '1'); ?>>
                            <p class="description">Attiva o disattiva il widget sul tuo sito</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="rayochat_api_key">API Key *</label>
                        </th>
                        <td>
                            <input type="text" 
                                   id="rayochat_api_key" 
                                   name="rayochat_api_key" 
                                   value="<?php echo esc_attr($api_key); ?>" 
                                   class="regular-text"
                                   required>
                            <p class="description">
                                La tua API key di RayoChat. 
                                <a href="https://yourdomain.com/site-owner/api-key" target="_blank">
                                    Ottieni la tua API key
                                </a>
                            </p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="rayochat_api_url">URL API</label>
                        </th>
                        <td>
                            <input type="url" 
                                   id="rayochat_api_url" 
                                   name="rayochat_api_url" 
                                   value="<?php echo esc_url($api_url); ?>" 
                                   class="regular-text">
                            <p class="description">URL dell'API del backend RayoChat</p>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="rayochat_position">Posizione Widget</label>
                        </th>
                        <td>
                            <select id="rayochat_position" name="rayochat_position">
                                <option value="bottom-right" <?php selected($position, 'bottom-right'); ?>>
                                    In basso a destra
                                </option>
                                <option value="bottom-left" <?php selected($position, 'bottom-left'); ?>>
                                    In basso a sinistra
                                </option>
                            </select>
                        </td>
                    </tr>
                    
                    <tr>
                        <th scope="row">
                            <label for="rayochat_primary_color">Colore Primario</label>
                        </th>
                        <td>
                            <input type="color" 
                                   id="rayochat_primary_color" 
                                   name="rayochat_primary_color" 
                                   value="<?php echo esc_attr($primary_color); ?>">
                            <p class="description">Colore principale del widget (default: #FF6B35)</p>
                        </td>
                    </tr>
                </table>
                
                <p class="submit">
                    <button type="submit" 
                            name="rayochat_save_settings" 
                            class="button button-primary">
                        Salva Impostazioni
                    </button>
                </p>
            </form>
            
            <hr>
            
            <h2>Preview</h2>
            <p>Questa è un'anteprima di come apparirà il widget sul tuo sito.</p>
            <div style="position: relative; height: 400px; background: #f0f0f0; border: 1px solid #ddd; border-radius: 8px;">
                <p style="text-align: center; padding: 20px; color: #666;">
                    Il widget apparirà nell'angolo <?php echo $position === 'bottom-left' ? 'in basso a sinistra' : 'in basso a destra'; ?> 
                    del tuo sito con il colore primario <span style="color: <?php echo esc_attr($primary_color); ?>; font-weight: bold;"><?php echo esc_html($primary_color); ?></span>
                </p>
            </div>
        </div>
        <?php
    }
}
