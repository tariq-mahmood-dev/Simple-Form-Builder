<?php 

/*
    Plugin Name: Simple Form Builder
    Description: A simple form builder, you can see submissions in admin, uses google recaptcha V2, uses Bootstrap 5.2.3
    Version: 1.0.0
    Requires at least: 6.2.2
    Requires PHP: 7.4
    Author: Tariq Mahmood
    Author URI: https://github.com/tariq-mahmood-dev
    License: GPLv2 or later
*/

define( 'SFB_PATH', dirname( __FILE__ ) );
define( 'SFB_ITEMS_PER_PAGE', '10');
define( 'SFB_ASSETS_VERSION', '1.0.13');

require_once(SFB_PATH.'/source/install.php');
require_once(SFB_PATH.'/source/register-menu.php');
require_once(SFB_PATH.'/source/load-assets.php');
require_once(SFB_PATH.'/source/ajax-requests.php');
require_once(SFB_PATH.'/source/helper.php');
require_once(SFB_PATH.'/source/crud.php');
require_once(SFB_PATH.'/source/render-form.php');


register_activation_hook(__FILE__, array( 'SimpleFormBuilder', 'install' ) );
add_action('init','sfb_register_session');


register_deactivation_hook( __FILE__, 'sfb_deactivate' );
function sfb_deactivate()
{
    global $wpdb;
   
    $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}sfb_values" );
    $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}sfb_submissions" );
    $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}sfb_fields" );
    $wpdb->query( "DROP TABLE IF EXISTS {$wpdb->prefix}sfb_forms" );
}


