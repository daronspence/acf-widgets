<?php	
// Block direct requests
if ( !defined('ABSPATH') ){
	die();	
}
	
// add_action( 'acf/save_post', 'acfw_license_key', 20);
// function acfw_license_key(){
// 	if( isset( $_POST['acf']['field_license_key'] ) ) {
// 		$lk = trim($_POST['acf']['field_license_key']);

// 		$api_params = array( 
// 			'edd_action'=> 'activate_license', 
// 			'license' 	=> $lk, 
// 			'item_name' => urlencode( ACFW_ITEM_NAME ), // the name of our product in EDD
// 			'url'       => home_url()
// 		);
// 		// Call the custom API.
// 		$response = wp_remote_get( add_query_arg( $api_params, ACFW_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

// 		if ( is_wp_error( $response ) )
// 			return false;

// 		// decode the license data
// 		$license_data = json_decode( wp_remote_retrieve_body( $response ) );
// 		if ($license_data->success == '' ){
// 			update_option('acfw_license_status', 'invalid');
// 		}

// 		update_option('acfw_license_key_old', get_option('acfw_license_key') );
// 		update_option('acfw_license_key', $lk );
// 		update_option('acfw_license_status', $license_data->license );
// 	}
// 	delete_transient('acfw_license_check');
// } // end license test

// if ( false === ($acfw_license_check = get_transient('acfw_license_check') ) ){
// 	if ( get_option('acfw_license_key') == '' ){
// 		add_action('admin_init', 'acfw_deactivate_license');
// 	} elseif ( get_option('acfw_license_status') == 'invalid' || '' ){
// 		update_option('acfw_license_status', 'invalid');
// 		add_action('admin_init', 'acfw_deactivate_license');
// 	}
// 	set_transient( 'acfw_license_check', true, 60*5 );
// }


// function acfw_deactivate_license(){
// 	$api_params = array( 
// 		'edd_action'=> 'deactivate_license', 
// 		'license' 	=> get_option('acfw_license_key_old'), 
// 		'item_name' => urlencode( ACFW_ITEM_NAME ),
// 		'url'       => home_url()
// 	);
// 	$deactivate_response = wp_remote_get( add_query_arg( $api_params, ACFW_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );
// 	$license_data = json_decode( wp_remote_retrieve_body( $deactivate_response ) );
// 	update_option('acfw_license_status', $license_data->license );
// }

// function acfw_check_update(){
// 	$license = get_option('acfw_license_key');
// 	// Empty licenses and AJAX need not apply.
// 	if ( ($license == '') || ( defined( 'DOING_AJAX' ) && DOING_AJAX ) ){
// 		return;
// 	}
// 	$api_params = array( 
// 		'edd_action'=> 'check_license', 
// 		'license' 	=> get_option('acfw_license_key'), 
// 		'item_name' => urlencode( ACFW_ITEM_NAME ),
// 		'url'       => home_url(),
// 		'request_uri' => $_SERVER['REQUEST_URI'],
// 	);
// 	// Call the custom API.
// 	$response = wp_remote_get( add_query_arg( $api_params, ACFW_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

// 	if ( is_wp_error( $response ) ){
// 		return false;
// 	}

// 	// decode the license data
// 	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

// 	update_option('acfw_license_count', $license_data->activations_left);
// 	update_option('acfw_license_status', $license_data->license);




// 	// if($license_data->license == 'expired'){
// 	// 	update_option('acfw_license_active', 0);
// 	// } else {
// 	// 	update_option('acfw_license_active', 1);
// 	// }
// }
// add_action( 'admin_init', 'acfw_check_update' );


// add_action( "acf/render_field/type=text", function( $field ){
// 	if ($field['key'] == 'field_license_key'){
// 		$status = get_option('acfw_license_status');
// 		$key = get_option('acfw_license_key');
// 		$active = get_option('acfw_license_active' );
// 		$count = get_option('acfw_license_count');
// 		if ($status == 'failed'){
// 			$status = 'deactivated';
// 		}
// 		if ($status === 'deactivated' || $status === 'invalid'){
// 			echo '<p>While your license is either deactivated or invalid, you will not recieve any updates.</p>';
// 		}
// 		echo '<p>' . 'License Key: ' . "<span class='{$status}'>" . $status . '</span></p>';
// 		if( $count < 1 && $count != 'unlimited' ){
// 			echo '<p>You have no more licenses remaining. Consider <a href="http://acfwidgets.com/checkout/purchase-history/">deactivating some</a> or <a href="http://acfwidgets.com/checkout?edd_action=add_to_cart&download_id=13&edd_options[price_id]=3">purchasing a developers license</a>.</p>';
// 		}
// 		if(!$active){
// 			echo '<p style="color: red;">Your license has expired.</p><p><a href="http://acfwidgets.com/checkout/?edd_license_key='. $key .'&download_id=13" class="button button-primary" target="_blank">Renew Your License</a></p>';
// 		}
// 	}
// });



// add_action( 'init', 'acfw_license_field', 20);
// function acfw_license_field(){
// 	register_field_group(array (
// 		'key' => 'license_key',
// 		'title' => __('License Information', 'acfw'),
// 		'fields' => array (
// 			array (
// 				'key' => 'field_license_key',
// 				'label' => __('License Key', 'acfw'),
// 				'name' => 'acfw_license_key',
// 				'prefix' => '',
// 				'type' => 'text',
// 				'instructions' => __('Enter your license key below. To deactivate your license, simply remove your license and update this options page. Your old license will be deactivated automatically. Please note, an invalid license key will also deactivate your previous license.', 'acfw'),
// 				'required' => 0,
// 				'conditional_logic' => 0,
// 				'default_value' => '',
// 				'placeholder' => '',
// 				'prepend' => '',
// 				'append' => '',
// 				'maxlength' => '',
// 				'readonly' => 0,
// 				'disabled' => 0,
// 			),
// 		),
// 		'location' => array (
// 			array (
// 				array (
// 					'param' => 'options_page',
// 					'operator' => '==',
// 					'value' => 'acf-options-acfw-options',
// 				),
// 			),
// 		),
// 		'menu_order' => 0,
// 		'position' => 'normal',
// 		'style' => 'default',
// 		'label_placement' => 'top',
// 		'instruction_placement' => 'label',
// 		'hide_on_screen' => '',
// 	));
// }
// End of File