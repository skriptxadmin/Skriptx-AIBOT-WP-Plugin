<?php

if ( ! defined( 'ABSPATH' ) ) exit;

/**
 * Handle form submission securely
 */
if (
    isset($_SERVER['REQUEST_METHOD']) &&
    $_SERVER['REQUEST_METHOD'] === 'POST'
) {

    if (
        ! isset($_POST['_wpnonce']) ||
        ! wp_verify_nonce(
            sanitize_text_field( wp_unslash($_POST['_wpnonce']) ),
            'aibot_save_keys'
        )
    ) {
        wp_die('Security check failed');
    }

    $aibot_verify_key = isset($_POST['verify_key'])
        ? sanitize_text_field( wp_unslash($_POST['verify_key']) )
        : '';

    $aibot_live_key = isset($_POST['live_key'])
        ? sanitize_text_field( wp_unslash($_POST['live_key']) )
        : '';

    update_option('aibot_verify_key', $aibot_verify_key);
    update_option('aibot_live_key', $aibot_live_key);
}

/**
 * Load saved values
 */
$aibot_verify_key = get_option('aibot_verify_key', '');
$aibot_live_key   = get_option('aibot_live_key', '');
?>

<div class="aibot-container">

    <div class="aibot-card">

        <h2>Activate AIBot</h2>

        <form method="post">

            <?php wp_nonce_field('aibot_save_keys'); ?>

            <div class="aibot-form-group">
                <label>Verify Key</label>
                <input type="text" name="verify_key" value="<?php echo esc_attr($aibot_verify_key); ?>">
            </div>

            <div class="aibot-form-group">
                <label>Live Key</label>
                <input type="text" name="live_key" value="<?php echo esc_attr($aibot_live_key); ?>">
            </div>

            <div class="clearfix"></div>

            <button type="submit" class="button button-primary float-end">
                Activate
            </button>

        </form>

        <div class="clearfix"></div>

        <p>
            Don't have keys?
            <a href="https://aibot.skriptx.com" target="_blank">
                Create account
            </a>
        </p>

    </div>

</div>