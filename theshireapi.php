<?php
defined( 'ABSPATH' ) OR exit;
/*
Plugin Name: Theshire.co API
Plugin URI:  
Description: Autopost rich links on theshire.co news board to your website with theshire.co  API.
Version:     1.4
Author:      Theshire.co
Author URI:  https://theshire.co/
Text Domain: theshire_api
Domain Path: /languages
License:     GPL2
 
{Plugin Name} is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 2 of the License, or any later version.
 
{Plugin Name} is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General Public License for more details.
 
You should have received a copy of the GNU General Public License along with {Plugin Name}. If not, see {License URI}.
*/

if ( is_admin() ) {
    // we are in admin mode
    require_once( dirname( __FILE__ ) . '/admin/settings_option_page.php' );
    require_once( dirname( __FILE__ ) . '/admin/view.php' );
    require_once( dirname( __FILE__ ) . '/savepost.php' );
}

global $theshire_db_version;
$theshire_db_version = '1.0';

add_action('admin_init', 'tsapi_theshireapi_redirect');

function tsapi_theshireapi_activate() {
    if ( ! current_user_can( 'activate_plugins' ) )
        return;
    $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
    check_admin_referer( "activate-plugin_{$plugin}" );

        add_option('tsapi_theshireapi_redirect', true);
        flush_rewrite_rules();
}

function tsapi_theshireapi_redirect() {
    if (get_option('tsapi_theshireapi_redirect', false)) {
        delete_option('tsapi_theshireapi_redirect');
        wp_redirect('admin.php?page=theshire_api');
    }
}


function tsapi_plugin_dependancy_check_activation() {
    global $wp_version;

    $php = '5.3';
    $wp  = '4.7';

    if ( version_compare( PHP_VERSION, $php, '<' ) ) {
        deactivate_plugins( basename( __FILE__ ) );
        wp_die(
            '<p>' .
            sprintf(
                __( 'This plugin can not be activated because it requires a PHP version greater than %1$s. Your PHP version can be updated by your hosting company.', 'my_plugin' ),
                $php
            )
            . '</p> <a href="' . admin_url( 'plugins.php' ) . '">' . __( 'go back', 'my_plugin' ) . '</a>'
        );
    }

    if ( version_compare( $wp_version, $wp, '<' ) ) {
        deactivate_plugins( basename( __FILE__ ) );
        wp_die(
            '<p>' .
            sprintf(
                __( 'This plugin can not be activated because it requires a WordPress version greater than %1$s. Please go to Dashboard &#9656; Updates to gran the latest version of WordPress .', 'my_plugin' ),
                $php
            )
            . '</p> <a href="' . admin_url( 'plugins.php' ) . '">' . __( 'go back', 'my_plugin' ) . '</a>'
        );
    }
}

function tsapi_theshire_plugin_deactivation() {
    if ( ! current_user_can( 'deactivate_plugins' ) )
        return;
    $plugin = isset( $_REQUEST['plugin'] ) ? $_REQUEST['plugin'] : '';
    check_admin_referer( "deactivate-plugin_{$plugin}" );

    delete_option('rewrite_rules');
}

register_activation_hook( __FILE__, 'tsapi_plugin_dependancy_check_activation' );
register_activation_hook(__FILE__, 'tsapi_theshireapi_activate');
register_deactivation_hook( __FILE__, 'tsapi_theshire_plugin_deactivation' );

