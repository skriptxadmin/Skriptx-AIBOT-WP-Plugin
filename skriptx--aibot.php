<?php
/**
 * Plugin Name: Skriptx AIBot
 * Plugin URI: https://aibot.skriptx.com
 * Description: Skriptx AIBot is an AI-powered WordPress plugin that helps you generate high-quality content, automate writing tasks, and enhance your website productivity using advanced AI models.
 * Version: 1.0.0
 * Author: Skriptx
 * Author URI: https://skriptx.com
 * Support URI: https://support.skriptx.com
 * Text Domain: skriptx-aibot
 * License: Properaitory
 */

if (! defined('ABSPATH')) {
    exit;
}

class AIBotPlugin
{
    public function __construct()
    {
        add_action('admin_menu', [$this, 'register_admin_menu']);
        add_action('admin_enqueue_scripts', [$this, 'enqueue_assets']);

        add_action('wp_head', [$this, 'inject_tags']);
    }

    /**
     * Register WP Admin Menu
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
     * Load CSS & JS only on AIBot page
     */
    public function enqueue_assets($hook)
    {
        if ($hook !== 'toplevel_page_aibot') {
            return;
        }

        wp_enqueue_style(
            'aibot-style',
            plugin_dir_url(__FILE__) . 'assets/css/style.css',
            [],
            '1.0.0'
        );

        wp_enqueue_script(
            'aibot-script',
            plugin_dir_url(__FILE__) . 'assets/js/script.js',
            ['jquery'],
            '1.0.0',
            true
        );
    }

    /**
     * Load Login Page
     */
    public function aibot_page()
    {
        include plugin_dir_path(__FILE__) . 'pages/aibot.php';
    }

    public function inject_tags()
    {
        $verify_key = get_option('aibot_verify_key', '');
        $live_key   = get_option('aibot_live_key', '');

        if (! empty($verify_key)) {

            echo sprintf(
                '<meta name="sb:verify-key" content="%s">' . PHP_EOL,
                esc_attr($verify_key)
            );
        }

        if (! empty($live_key)) {

            echo sprintf(
                '<script src="https://aibot.skriptx.com/widgets/aibot.js?key=%s"></script>' . PHP_EOL,
                urlencode($live_key)
            );
        }
    }
}

new AIBotPlugin();
