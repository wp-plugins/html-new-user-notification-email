<?php
//Options page

/**
 * Plugin options markup
 */
function ab_plugin_options() {
    ?>
    <div class="wrap ab-html-notification-wrap">
        <h2><?php _e('Html User Notification', 'abs'); ?></h2>
        <h4><?php _e('Shortcode\'s', 'abs'); ?></h4>
        <ol>
            <li class="ab-shortcode">User Display name : [ab-display-name]</li>
            <li class="ab-shortcode">Username : [ab-user-login]</li>
            <li class="ab-shortcode">Password : [ab-user-password]</li>
            <li class="ab-shortcode">User email : [ab-user-email]</li>
        </ol>

        <form method="post" action="options.php">

            <?php settings_fields('ab-settings-group'); ?>
            <?php do_settings_sections('ab-settings-group'); ?>

            <table class="form-table">
                <tr valign="top">

                    <th scope="row"><?php _e('User Mail Content', 'abs'); ?></th>
                    <td><?php wp_editor(get_option('ab_user_mail_content'), 'ab_user_mail_content', ''); ?></td>                    
                    
                </tr>
                <tr valign="top">

                    <th scope="row"><?php _e('User Mail Subject', 'abs'); ?></th>
                    <td><input class="ab-mail-subject" type="text" name="ab_user_mail_subject" value="<?php echo get_option('ab_user_mail_subject'); ?>" /></td>
                    
                </tr>
                <tr valign="top">

                    <th scope="row"><?php _e('Admin Mail Content', 'abs'); ?></th>
                    <td><?php wp_editor(get_option('ab_admin_mail_content'), 'ab_admin_mail_content', ''); ?></td>                    

                </tr>
                <tr valign="top">

                    <th scope="row"><?php _e('Admin Mail Subject', 'abs'); ?></th>
                    <td><input class="ab-mail-subject" type="text" name="ab_user_admin_subject" value="<?php echo get_option('ab_user_admin_subject'); ?>" /></td>
                    
                </tr>
            </table>

            <?php submit_button(); ?>
        </form>

    </div> 
    <?php
}

//call register settings function
add_action('admin_init', 'ab_register_mysettings');

function ab_register_mysettings() {
    //register our settings
    register_setting('ab-settings-group', 'ab_user_mail_content');
    register_setting('ab-settings-group', 'ab_admin_mail_content');
    register_setting('ab-settings-group', 'ab_user_mail_subject');
    register_setting('ab-settings-group', 'ab_user_admin_subject');
}