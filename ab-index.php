<?php

/**
 * Plugin Name: Html New User Notification
 * Plugin URI: https://wordpress.org/plugins/html-new-user-notification-email/
 * Description: New user notification mail
 * Version: 1.0
 * Author: Abhishek Kumar
 * Author URI: http://github.com/abhishekfdd
 * License: GPLv2
 */
include plugin_dir_path(__FILE__) . '/admin/ab-options.php';

//Loading style
add_action('admin_init', 'ab_plugin_admin_styles');

function ab_plugin_admin_styles() {
    wp_register_style('abPluginStylesheet', plugins_url('css/ab-html-un.css', __FILE__));
    wp_enqueue_style('abPluginStylesheet');
}

/**
 * Calling settings page
 */
add_action('admin_menu', 'ab_plugin_menu');

function ab_plugin_menu() {
    add_options_page('Html New User Notification Options', 'Html User Notification', 'manage_options', 'ab-plugin-menu', 'ab_plugin_options');
}

/*
 * All the functions are in this file
 */

if (!function_exists('wp_new_user_notification')) {

    /**
     * Email login credentials to a newly-registered user.
     *
     * A new user registration notification is also sent to admin email.
     *
     * @param int    $user_id        User ID.
     * @param string $plaintext_pass Optional. The user's plaintext password. Default empty.
     */
    function wp_new_user_notification($user_id, $plaintext_pass = '') {
        $user = get_userdata($user_id);
        $subject_user = get_option('ab_user_mail_subject');
        $subject_admin = get_option('ab_user_admin_subject');

        //Shortcodes        
        $search = array("[ab-display-name]", "[ab-user-login]", "[ab-user-password]","[ab-user-email]" );
        $replace = array($user->display_name, $user->user_login, $plaintext_pass ,$user->user_email);

        // set content type to html
        add_filter('wp_mail_content_type', 'wpmail_content_type');

        //for admin
        ob_start();

        echo ab_filter_post_content(get_option('ab_admin_mail_content'));

        $message_admin_raw = ob_get_contents();
        $message_admin = str_replace($search, $replace, $message_admin_raw);
        
        ob_end_clean();
        
        @wp_mail(get_option('admin_email'),$subject_admin,$message_admin);
        
        if ( empty($plaintext_pass) ) {            
                // remove html content type
                remove_filter('wp_mail_content_type', 'wpmail_content_type');
		return;                
        }
        
        //for user
        ob_start();

        echo ab_filter_post_content(get_option('ab_user_mail_content'));

        $message_user_raw = ob_get_contents();
        $message_user = str_replace($search, $replace, $message_user_raw);

        ob_end_clean();

        wp_mail($user->user_email, $subject_user, $message_user);

        // remove html content type
        remove_filter('wp_mail_content_type', 'wpmail_content_type');
    }

}

/**
 * wpmail_content_type
 * allow html emails
 * @return string
 */
function wpmail_content_type() {

    return 'text/html';
}

/**
 * Apply wordpress post content filter on plain content. * 
 */
function ab_filter_post_content($content = '') {
    if (!empty($content)) {
        $content = apply_filters('the_content', $content);
        $filtered_content = str_replace(']]>', ']]&gt;', $content);
        return $filtered_content;
    } else {
        return $content;
    }
}
