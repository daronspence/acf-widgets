<?php
/**
* Plugin Name: ACF Widgets
* Plugin URI: http://acfwidgets.com
* Description: A plugin to easily create widgets for use with ACF and add custom fields to any widget on your site.
* Version: 1.5.1
* Author: Daron Spence
* Author URI: http://daronspence.com
* Text Domain: acfw
* License: GPL2+
*/

// Block direct requests
if ( !defined('ABSPATH') ){
	die();
}

define( 'ACFW_VERSION', '1.5.1' );
define( 'ACFW_STORE_URL', 'http://acfwidgets.com' );
define( 'ACFW_ITEM_NAME', 'ACF Widgets' );
define( 'ACFW_FILE' , __FILE__ );

add_action('after_setup_theme', 'acfw_globals');
function acfw_globals(){
	if ( apply_filters( 'acfw_lite', false ) )
		define( 'ACFW_LITE', true );
	if ( apply_filters( 'acfw_include', false ) )
		define('ACFW_INCLUDE', true);
}

// Check to see if ACF is active
include_once('includes/acf-404.php');


$acfw_default_widgets = array('pages', 'calendar', 'archives', 'meta', 'search', 'text', 
	'categories', 'recent-posts', 'recent-comments', 'rss', 'tag_cloud', 'nav_menu');

include_once('includes/helper-functions.php');

include_once('includes/admin-setup.php');

require_once('includes/ACFW_Widget.php');

require_once('includes/ACFW_Widget_Factory.php');

include_once('includes/widgets-setup.php');

include_once('includes/default-widgets.php');


if( !class_exists( 'EDD_SL_Plugin_Updater' ) ) {
	// load our custom updater
	include( dirname( __FILE__ ) . '/EDD_SL_Plugin_Updater.php' );
}

function acfw_plugin_updater() {

	$license_key = trim( get_option( 'acfw_license_key' ) );

	// setup the updater
	$edd_updater = new EDD_SL_Plugin_Updater( ACFW_STORE_URL, __FILE__, array( 
			'version' 	=> ACFW_VERSION, 	// current version number
			'license' 	=> $license_key, 	// license key (used get_option above to retrieve from DB)
			'item_name' => ACFW_ITEM_NAME, 	// name of this plugin
			'author' 	=> 'Daron Spence',  // author of this plugin
			'url'		=> home_url()
		)
	);

}
if(!defined('ACFW_INCLUDE') && get_option('acfw_license_key') != ''){
	add_action( 'admin_init', 'acfw_plugin_updater', 0 );
}

register_activation_hook( __FILE__, 'acfw_activate' );
function acfw_activate(){
	$users = get_users('meta_key=acfw_dismiss_expired');
	foreach ($users as $user) {
		delete_user_meta( $user->id, 'acfw_dismiss_expired' );
	}
}


// End of File