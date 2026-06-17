<?php
/**
 * Plugin Name: Skriptx AIBot
 * Plugin URI: https://aibot.skriptx.com
 * Description: Skriptx AIBot is an AI-powered WordPress plugin that helps you generate high-quality content, automate writing tasks, and enhance your website productivity using advanced AI models.
 * Version: 2.0.0
 * Author: Skriptx
 * Author URI: https://skriptx.com
 * Support URI: https://support.skriptx.com
 * Text Domain: skriptx-aibot
 * Stable tag: 2.0.0
 * License: GPLv2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

if ( ! defined('ABSPATH') ) {
    exit;
}

class AIBotPlugin
{
    private $version = "2.0.0";
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
        add_menu_page(
            'AIBot',
            'AIBot',
            'manage_options',
            'aibot',
            [$this, 'aibot_page'],
            'dashicons-admin-site-alt3',
            25
        );
    }

    /**
     * Admin assets
     */
    public function enqueue_assets($hook)
    {
        if ( $hook !== 'toplevel_page_aibot' ) {
            return;
        }

        wp_enqueue_style(
            'aibot-style',
            plugin_dir_url(__FILE__) . 'assets/css/style.css',
            [],
            '2.0.0'
        );

        wp_enqueue_script(
            'aibot-script',
            plugin_dir_url(__FILE__) . 'assets/js/script.js',
            ['jquery'],
            '2.0.0',
            true
        );
    }

    /**
     * Load admin page
     */
    public function aibot_page()
    {
        include plugin_dir_path(__FILE__) . 'pages/aibot.php';
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
        if ( ! empty($aibot_verify_key) ) {
            echo '<meta name="sb:verify-key" content="' . esc_attr($aibot_verify_key) . '">' . PHP_EOL;
        }

        /**
         * Store live key for frontend usage (safer approach)
         */
        if ( ! empty($aibot_live_key) ) {
            echo '<meta name="aibot:live-key" content="' . esc_attr($aibot_live_key) . '">' . PHP_EOL;
        }
    }

    /**
     * Proper WP enqueue for frontend widget
     */
    public function enqueue_frontend_widget()
    {
        $aibot_live_key = get_option('aibot_live_key', '');

        if ( empty($aibot_live_key) ) {
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