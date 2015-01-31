<?php
// Block direct requests
if ( !defined('ABSPATH') )
	die();	

add_action('init', 'acf_check_404');
function acf_check_404(){
	if ( function_exists('acf') )
		$acf = acf();

	// not set or < v5 of ACF
	if ( !isset( $acf ) || version_compare($acf->settings['version'], '5.0', '<') ){
		add_action( 'admin_notices', 'acf_404' );
		add_action( 'network_admin_notices', 'acf_404' );
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