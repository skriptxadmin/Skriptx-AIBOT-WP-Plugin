<?php
/**
 * Plugin Name: Skriptx AIBot
 * Plugin URI: https://aibot.skriptx.com
 * Description: Skriptx AIBot is an intelligent AI chatbot for WordPress websites that automatically reads and learns from website content. It trains itself on your pages and provides instant answers to customer questions, improving support and engagement without manual configuration.
 * Version: 3.0.0
 * Author: Skriptx
 * Author URI: https://skriptx.com
 * Support URI: https://support.skriptx.com
 * Text Domain: skriptx-aibot
 * Stable tag: 3.0.0
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if (! defined('ABSPATH')) {
    exit;
}

class AIBotPlugin
{
    private $version = "3.0.0";
    /**
     * Constructor
     */
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);

        add_action('wp_head', [$this, 'inject_tags']);
        add_action('wp_enqueue_scripts', [$this, 'enqueue_frontend_widget']);
    }

    /**
     * Register Admin Menu
     */
    public function register_admin_menu()
    {
        add_options_page(
            'AIBot',
            'AIBot',
            'manage_options',
            'aibot',
            [$this, 'aibot_page'],
        );
    }

    /**
     * Admin assets
     */
    public function enqueue_assets($hook)
    {
        if ($hook !== 'settings_page_aibot') {
            return;
        }

        wp_enqueue_style(
            'aibot-style',
            plugin_dir_url(__FILE__) . 'assets/css/style.css',
            [],
            '3.0.0'
        );

        wp_enqueue_script(
            'aibot-script',
            plugin_dir_url(__FILE__) . 'assets/js/script.js',
            ['jquery'],
            '3.0.0',
            true
        );
    }

    /**
     * Load admin page
     */
    public function aibot_page()
    {
        include plugin_dir_path(__FILE__) . 'pages/index.php';
    }

    /**
     * Inject meta + prepare frontend integration
     */
    public function inject_tags()
    {
        $aibot_verify_key = get_option('aibot_verify_key', '');
        $aibot_live_key   = get_option('aibot_live_key', '');

        /**
         * Verify meta tag
         */
        if (! empty($aibot_verify_key)) {
            echo '<meta name="sb:verify-key" content="' . esc_attr($aibot_verify_key) . '">' . PHP_EOL;
        }

        /**
         * Store live key for frontend usage (safer approach)
         */
        if (! empty($aibot_live_key)) {
            echo '<meta name="aibot:live-key" content="' . esc_attr($aibot_live_key) . '">' . PHP_EOL;
        }
    }

    /**
     * Proper WP enqueue for frontend widget
     */
    public function enqueue_frontend_widget()
    {
        $aibot_live_key = get_option('aibot_live_key', '');

        if (empty($aibot_live_key)) {
            return;
        }

        $aibot_script_url = add_query_arg(
            [
                'key' => $aibot_live_key,
            ],
            'https://aibot.skriptx.com/widgets/aibot.js'
        );

        wp_enqueue_script(
            'aibot-widget',
            esc_url_raw($aibot_script_url),
            [],
            $this->version,
            true,
        );
    }
}

new AIBotPlugin();
