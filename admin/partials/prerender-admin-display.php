<?php

/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @link        https://vorster.cloud/
 * @since      1.0.0
 *
 * @package    Prerender
 * @subpackage Prerender/admin/partials
 */
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->

<div class="wrap">
    <h1>Prerender.io Settings</h1>
    <hr>

    <form name="prerender" action="options.php" method="POST">
    <?php settings_fields($this->plugin_name); ?>
        <div class="form-group row">
            <label class="col-2">Prerender.io Enabled</label> 
            <div class="col-1">
                <div>
                    <label for="prerender-enable" class="switch">
                    <input name="prerender[prerender-enable]" id="prerender-enable" type="checkbox" value="1" <?php checked($prerenderEnable, 1); ?>> 
                    <span class="slider round"></span>
                    </label>
                </div>
            </div>
        </div>
        <div class="form-group row">
            <label for="prerender-token" class="col-2 col-form-label">Prerender.io Token</label>
            <div class="col-3">
                <input id="prerender-token" name="prerender[prerender-token]" value="<?php echo $prerenderToken; ?>" type="text" class="form-control" aria-describedby="prerender-tokenHelpBlock" required="required">
                <span id="prerender-tokenHelpBlock" class="form-text text-muted">
                Instruction to get Prerender.io token:
                <ul>
                <li>1. Go to <a href="https://prerender.io/" target="_blank">Prerender.io</a></li>
                <li>2. Sign up with your email address</li>
                <li>3. Agree to terms and verify email address</li>
                <li>4. Copy your token to plugin field</li>
                </ul>
                </span>
                <?php submit_button('Save changes', 'primary','submit', TRUE); ?>
            </div>
        </div>
    </form>
</div>