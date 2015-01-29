<?php
/*
* HELPER FUNCTIONS
*/

/**
* @func acfw_location_rules
* Return an array of rules for use with register_field_group() 'location' key
* @param array $a
* @param str $param
* @param str $operator
* @param bool $extended
*/
function acfw_location_rules( array $a, $param, $op, $extended = false ){
	$return = array();
	foreach ($a as $b){
		$push = array(
			'param' => $param,
			'operator' => $op,
			'value' => $b
		);
		if ($extended){
			// wrap it to denote an "OR"
			$push = array($push);
		}
		$return[] = $push;
	}
	if (! $extended){
		// wrap it so we don't have to
		$return = array($return);	
	}
	return $return;
}

// Derived from http://php.net/manual/en/function.array-splice.php#111204
// Splice an array and preserve keys
function acfw_array_splice_assoc(&$input, $offset, $length, $replacement = array()) {
    $replacement = (array) $replacement;
    $key_indices = array_flip(array_keys($input));
    if (isset($input[$offset]) && is_string($offset)) {
            $offset = $key_indices[$offset];
    }
    if (isset($input[$length]) && is_string($length)) {
            $length = $key_indices[$length] - $offset;
    }

    $input = array_slice($input, 0, $offset, TRUE)
            + $replacement
            + array_slice($input, $offset + $length, NULL, TRUE); 
}

// TODO: Use this.
function acfw_settings($key, $value){
	$settings = get_option('acfw_settings');
	$settings[$key] = $value;
	update_option('acfw_settings', $settings);
}


function acfw_deactivate_license(){
	$api_params = array( 
		'edd_action'=> 'deactivate_license', 
		'license' 	=> get_option('acfw_license_key'), 
		'item_name' => urlencode( ACFW_ITEM_NAME ),
		'url'       => home_url()
	);
	$deactivate_response = wp_remote_get( add_query_arg( $api_params, ACFW_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );
	$license_data = json_decode( wp_remote_retrieve_body( $deactivate_response ) );
	update_option('acfw_license_status', $license_data->license );
	update_option('acfw_license_count', get_option('acfw_license_count') + 1);
}

function acfw_activate_license($lk){
	$api_params = array( 
		'edd_action'=> 'activate_license', 
		'license' 	=> $lk, 
		'item_name' => urlencode( ACFW_ITEM_NAME ), // the name of our product in EDD
		'url'       => home_url()
	);
	// Call the custom API.
	$response = wp_remote_get( add_query_arg( $api_params, ACFW_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

	if ( is_wp_error( $response ) )
		return false;

	// decode the license data
	$license_data = json_decode( wp_remote_retrieve_body( $response ) );

	if ($license_data->success == false ){
		update_option('acfw_license_status', 'invalid');
	}

	update_option('acfw_license_status', $license_data->license );
	update_option('acfw_license_count', $license_data->activations_left);

	return $license_data;
}

function acfw_check_license(){
	if ( defined( 'DOING_AJAX' ) && DOING_AJAX ){
		return;
	}
	$api_params = array( 
		'edd_action'=> 'check_license', 
		'license' 	=> get_option('acfw_license_key'), 
		'item_name' => urlencode( ACFW_ITEM_NAME ),
		'url'       => home_url(),
		'request_uri' => $_SERVER['REQUEST_URI'],
	);
	// Call the custom API.
	$response = wp_remote_get( add_query_arg( $api_params, ACFW_STORE_URL ), array( 'timeout' => 15, 'sslverify' => false ) );

	if ( is_wp_error( $response ) ){
		return false;
	}

	// decode the license data
	$license_data = json_decode( wp_remote_retrieve_body( $response ) );
	
	update_option('acfw_license_count', $license_data->activations_left);
	update_option('acfw_license_status', $license_data->license);
}

function acfw_plugins_url($dir, $rel){
	if ( defined('ACFW_INCLUDE') )
		return get_template_directory_uri() . apply_filters('acfw_dir', '/acf-widgets/') . 'includes' . $dir;
	else
		return plugins_url($dir, $rel);
}

function acfw_widgets_eval( $title, $description, $slug, $id){
	$name = 'Acf_Widget_'.$id;

	// TODO: Rewrite this without using eval()
	// Though I don't think it's possible...
	eval("
		class {$name} extends WP_Widget { 
			function {$name} () {
	            \$widget_ops = array(
	            				'classname' => ' {$name} ',
	                            'description' => ' {$description} ', 
	                        );
	            \$this->WP_Widget('{$name}', '{$title}', \$widget_ops );           
	        }
	        function form(\$instance) { 
				echo '<p class=\'acfw-no-acf\'>You have not added any fields to this widget yet. 
				<br/><br/><a href=post-new.php?post_type=acf-field-group>Add some now!</a>
				<br/><br/> Make sure to set the location rules to: <b>Widget : is equal to : {$title} </b></p><br/>';
				echo '<script type=text/javascript>acfw();</script>';
	        }
	        
	        function update(\$new_instance, \$old_instance) { \$instance = \$old_instance; return \$instance; }
	        
	        function widget(\$args, \$instance) {
				extract(\$args, EXTR_SKIP);
				
	            echo \$before_widget ;

	            \$acfw = 'widget_' . \$widget_id ;
	            
	            if (locate_template('widget-{$slug}.php') != '') {
					require(locate_template('widget-{$slug}.php'));
				} elseif (locate_template('widget-{$id}.php') != '') {
					require(locate_template('widget-{$id}.php'));
				} else {
					echo \"No template found for \$widget_name \";
				}

	            echo \$after_widget ;
	        }           
		}
		add_action('widgets_init' , 'acfw_register_{$name}');
		function acfw_register_{$name}(){ register_widget('{$name}'); }
	"); // end eval();

} // end acfw_widgets_eval();

function acfw_expired_notice(){
ob_start(); ?>
	<div class="update-nag">
        <p>Your copy of ACF Widgets is expired. Please <a href="options-general.php?page=acfw-options">renew your license</a> to continue recieving updates. <a href="?acfw-dismiss-expired=1" style="text-decoration: none;"><i class="dashicons dashicons-dismiss" style="font-size: inherit; padding-top: 3px;"></i>Dismiss</a></p>
    </div>
<?php echo ob_get_clean();
}


