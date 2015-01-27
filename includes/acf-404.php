<?php
// Block direct requests
if ( !defined('ABSPATH') ){
	die();	
}

$installed_plugins = array();
// check if ACF is installed
if ( is_multisite() ) :

	$network_plugins = get_site_option( 'active_sitewide_plugins', array() );
	$site_plugins = get_option( 'active_plugins', array() );
	$installed_plugins = array_merge($network_plugins, $site_plugins);

else :

	$installed_plugins = get_option( 'active_plugins', array() );

endif;

foreach($installed_plugins as $key => $value ){
	if ( strpos($key, 'acf.php') !== false || strpos($value, 'acf.php') !== false ){
		$acf_active = true;
		return;
	}
}


add_action('after_setup_theme', 'acf_check_404');
function acf_check_404(){
	global $acf;
	if ( !isset( $acf ) || intval(explode('.', $acf->settings['version'])[0]) < 5 ){
		add_action( 'admin_notices', 'acf_404' );
	}
}

// displays admin warning message
function acf_404() { ?>
    <div class="update-nag">
        <p><a href="http://advancedcustomfields.com">
        	<?php _e('ACF Widgets Requires ACF to function properly. Please activate ACF v5 to remove this message. If ACF is installed, make sure you are using v5 or later.', 'acfw'); ?>
        </a></p>
    </div>
<?php }