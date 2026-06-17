<?php

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $verify_key = sanitize_text_field($_POST['verify_key']);
    $live_key   = sanitize_text_field($_POST['live_key']);

    update_option('aibot_verify_key', $verify_key);
    update_option('aibot_live_key', $live_key);
}

$verify_key = get_option('aibot_verify_key', '');
$live_key   = get_option('aibot_live_key', '');

?>

<div class="aibot-container">

    <div class="aibot-card">

        <h2>Activate AIBot</h2>

        <form method="post">

            <div class="aibot-form-group">
                <label>Verify Key</label>
                <input type="text" name="verify_key"  value="<?php echo esc_attr($verify_key); ?>">
            </div>

            <div class="aibot-form-group">
                <label>Live Key</label>
                <input type="text" name="live_key" value="<?php echo esc_attr($live_key); ?>">
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

