<?php
/**
* Register acf-widgets assets for admin area.
*/
// Block direct requests
if ( !defined('ABSPATH') ){
	die();	
}

// Register Text Domain
add_action( 'plugins_loaded', 'acfw_lang');
function acfw_lang(){
	load_plugin_textdomain('acfw', false, dirname(plugin_basename( __FILE__ ) ) .'/lang');
} // end acfw_lang()

// Register CSS
add_action('admin_enqueue_scripts', 'acfw_admin_css');
function acfw_admin_css(){
	wp_enqueue_style('acfw-css', acfw_plugins_url("/css/acf-widgets.css" , __FILE__), false, ACFW_VERSION, 'all');
	wp_enqueue_script('acfw-js', acfw_plugins_url("/js/acf-widgets.js" , __FILE__), 'jquery', ACFW_VERSION, false );
} // end acfw_admin_css()

// Register CPT
add_action('init', 'acfw_register_cpt');
function acfw_register_cpt() {
	$menu_location = 'themes.php';
	if ( defined('ACFW_LITE') || !current_user_can( 'manage_options' ) )
		$menu_location = false;
	
	register_post_type('acf-widgets', array(
		'label' => __('ACF Widgets', 'acfw'),
		'description' => __('Widgets created with the ACF Widgets Plugin.', 'acfw'),
		'public' => false,
		'show_ui' => true,
		'show_in_menu' => $menu_location,
		'capability_type' => 'post',
		'map_meta_cap' => true,
		'hierarchical' => false,
		'rewrite' => array('slug' => 'acf-widgets', 'with_front' => true),
		'query_var' => true,
		'exclude_from_search' => true,
		'menu_position' => 8,
		'menu_icon' => 'dashicons-screenoptions',
		'supports' => array('title', 'excerpt'),
		'labels' => array (
			'name' => __('Widgets', 'acfw'),
			'singular_name' => __('ACF Widget', 'acfw'),
			'menu_name' => __('Add New Widgets', 'acfw'),
			'add_new' => __('Add Widget', 'acfw'),
			'add_new_item' => __('Add New Widget', 'acfw'),
			'edit' => __('Edit', 'acfw'),
			'edit_item' => __('Edit Widget', 'acfw'),
			'new_item' => __('New Widget', 'acfw'),
			'view' => __('View Widget', 'acfw'),
			'view_item' => __('View Widget', 'acfw'),
			'search_items' => __('Search Widgets', 'acfw'),
			'not_found' => __('No Widgets Found', 'acfw'),
			'not_found_in_trash' => __('No Widgets Found in Trash', 'acfw'),
			'parent' => __('Parent Widget', 'acfw'),
		)
	) ); 
} // end acfw_register_cpt()

// Add How-To Metabox to Widgets CPT
add_action( 'add_meta_boxes', 'acfw_widget_meta_boxes' );
function acfw_widget_meta_boxes() {
	add_meta_box(
		'acfw-helper-text',
		__( 'Using ACF Widgets in Your Theme', 'acfw' ),
		'acfw_how_to_meta_box',
		'acf-widgets'
	);
}
// Display contents of the helper metabox
function acfw_how_to_meta_box( $post ) {
	$title = $post->post_title;
	if ($title == ''){
		_e('For more information, give your widget a title, then Publish or Update this page.', 'acfw');
	} else {
		echo "<p>Before you can use this widget, you will need to <a href='edit.php?post_type=acf-field-group'>add some custom fields</a> to it.</p>";
		echo "<p>Add a new field group and set the <i>Location</i> equal to: <b>Widget is equal to {$post->post_title}</b>.</p>";
		echo "<p>To show this widget in your theme, add a new template file to your theme directory named <strong>widget-{$post->post_name}.php</strong> or <strong>widget-{$post->ID}.php</strong> .</p>";
		echo "<p>You can show the values from your widgets in your templates by using the following syntax.</p>";
		echo "<code>&lt;?php the_field('YOUR_FIELD_NAME', \$acfw); ?&gt;</code>";
		echo "<p><a href='https://www.youtube.com/watch?v=YRfvqmSQG7o' target='_blank'>Watch Tutorial</a> or <a href='http://acfwidgets.com/support/'>Read More</a></p>";
	}
}



// ACFW Support Meta Box
add_action('add_meta_boxes_acf-widgets', 'acfw_support_meta_box');
function acfw_support_meta_box(){
	add_meta_box('acfw-support', __('Support', 'acfw'), 'acfw_support_meta_box_html', 'acf-widgets', 'side');
}
function acfw_support_meta_box_html(){ ?>
	<h4><?php _e('General Support'); ?></h4>
	<p><?php printf(__('Check out the official %s Support Forums %s', 'acfw'), '<a href="http://acfwidgets.com/support/">', '</a>'); ?></p>
	<hr />
	<h4><?php _e('Looking for Priority Support?', 'acfw'); ?></h4>
	<p><?php printf(__('Check out the %s Priority Support Forums %s', 'acfw'), '<a href="http://acfwidgets.com/support/forum/priority-support/">', '</a>'); ?></p>
<?php } // End Support Meta Box

// Add ACFW Options Page
add_action('admin_menu','acfw_menu_items');
add_action('network_admin_menu', 'acfw_menu_items');
function acfw_menu_items(){
	if ( !defined('ACFW_LITE') && current_user_can('manage_options') ){
		add_options_page( 'ACFW Options', 'ACFW Options', 'edit_posts', 'acfw-options', 'acfw_options_page' );
		if ( is_network_admin() )
			add_submenu_page( 'settings.php', 'ACFW Options', 'ACFW Options', 'edit_posts', 'acfw-options', 'acfw_options_page' );
	}
}
function acfw_options_page(){ 
	if(isset($_POST['_wpnonce'])){
		if ( wp_verify_nonce( $_POST['_wpnonce'], 'acfw_options_nonce' ) ){

			// LICENSE STUFF //
			if ( isset($_POST['deactivate']) ){
				acfw_deactivate_license();
				update_option( 'acfw_license_key', '');
			} elseif ( isset($_POST['activate']) ){ 
				$status = acfw_activate_license(trim($_POST['acfwlicensekey']));
				if ( $status->license != 'invalid' || $status->error == 'expired')
					update_option( 'acfw_license_key', trim($_POST['acfwlicensekey']) );
			} 

			// DEBUG STUFF //
			if ( isset($_POST['acfwdebug']) ){
				update_option( 'acfw_debug', $_POST['acfwdebug'] );
			} else {
				update_option( 'acfw_debug', 0 );
			}
		}
	}
	$key = get_option('acfw_license_key');
	if (!empty($key))
		acfw_check_license();

	$status = get_option('acfw_license_status');
	$count = get_option('acfw_license_count');

	?>
<div class="wrap">
	<h2>ACFW Options</h2>
	<form name="acfw-options" method="post" action="?page=<?php echo $_GET['page']; ?>&updated=1">
    <?php wp_nonce_field( 'acfw_options_nonce' ); ?>
		<div id="poststuff">
			<div id="post-body" class="metabox-holder columns-<?php echo 1 == get_current_screen()->get_columns() ? '1' : '2'; ?>">
				<div id="post-body-content">
					 <div class="postbox">
						<h3 class="hndle">License Key</h3>
						<div style="padding: 0 15px 15px;">
							<p>Enter your license key below.</p>
							<input type="text" name="acfwlicensekey" style="min-width: 100%;" value="<?php echo $key; ?>">
							<?php
							if ($status == 'failed'){
								$status = 'deactivated';
							}
							if ($status === 'deactivated' || $status === 'invalid'){
								echo '<p>While your license is either deactivated or invalid, you will not recieve any updates.</p>';
							}
							echo '<p>' . 'License Key: ' . "<span class='{$status}'>" . $status . '</span></p>';
							if( $count === '0' && ($status == 'expired' || $status == 'valid') ){
								echo '<p>You have no more licenses remaining. Consider <a href="http://acfwidgets.com/checkout/purchase-history/">deactivating some</a> or <a href="http://acfwidgets.com/checkout?edd_action=add_to_cart&download_id=13&edd_options[price_id]=3">purchasing a developers license</a>.</p>';
							} elseif ( $count == 'unlimited' && ($status == 'expired' || $status == 'valid') ){
								echo '<p>Thank you for purchasing a developer license! If you need help, check out the <a href="http://acfwidgets.com/support/forum/priority-support/" target="_blank">Priority Support Forums</a>.</p>';
							}
							if($status == 'expired'){
								echo '<p style="color: red;">Your license has expired.</p><p><a href="http://acfwidgets.com/checkout/?edd_license_key='. $key .'&download_id=13" class="button button-primary" target="_blank">Renew Your License & Get 20% Off</a></p>';
							} ?>
							<?php if (!empty($key) && $status != 'deactivated') : ?>
								<input type="submit" name="deactivate" value="Deactivate License" class="button">
							<?php else : ?>
								<input type="submit" name="activate" value="Activate License" class="button">
							<?php endif; ?>
						</div>
					</div>
					<div class="postbox">
						<h3 class="hndle">Template Debug</h3>
						<div style="padding: 15px;">
							<input type="checkbox" id="acfwdebug" name="acfwdebug" value="1" <?php checked( '1', get_option('acfw_debug'), 1 ); ?> /><label for="acfwdebug" style="vertical-align: top; padding-left: 10px;">Turn On Debugging</label>
							<p>View the filename of the required template on the front end of your site when the widget is currently active. Usually used for default WP or 3rd Party Widgets.</p>
						</div>
					</div>
				</div>
				<div id="postbox-container-1" class="postbox-container">
					<div class="postbox">
						<h3 class="hndle">Support</h3>
						<div style="padding: 0 15px 15px;">
							<h4><?php _e('General Support'); ?></h4>
							<p><?php printf(__('Check out the official %s Support Forums %s', 'acfw'), '<a href="http://acfwidgets.com/support/">', '</a>'); ?></p>
							<hr />
							<h4><?php _e('Looking for Priority Support?', 'acfw'); ?></h4>
							<p><?php printf(__('Check out the %s Priority Support Forums %s', 'acfw'), '<a href="http://acfwidgets.com/support/forum/priority-support/">', '</a>'); ?></p>
						</div>
					</div>
				</div>
			</div>
			<br class="clear">
			<input type="submit" name="save" value="Save Options" class="button button-primary button-large">
		</div>
	</form>
</div>
<?php } // end ACFW Options Page


// Place ACFW after Appearance > Widgets
add_action('admin_menu', 'acfw_edit_admin_menu', 10);
function acfw_edit_admin_menu(){
	if ( !current_user_can('manage_options') )
		return;

	global $submenu;

	if ( empty( $submenu['themes.php'][7] ) ){
	    return; // return if the default menu structure has been modified
	}

	$widgets_postion[7] = $submenu['themes.php'][7]; // preserve key for widgets.php
	$widgets_postion[] = array_pop($submenu['themes.php']);
	// Splice and preserve keys
	acfw_array_splice_assoc($submenu['themes.php'], 2, 1, $widgets_postion);
} // end acfw_edit_admin_menu()

// Custom Columns For ACFW CPT
add_action( 'manage_acf-widgets_posts_custom_column' , 'acfw_custom_column', 10, 2 );
add_filter('manage_acf-widgets_posts_columns' , 'acfw_add_columns');
function acfw_custom_column($column, $post_id){
	$name = get_post( $post_id )->post_name;
	echo '<code>widget-' . $name . '.php</code>';
}
function acfw_add_columns($columns){
	unset($columns['date']);
	$columns['template'] = __('Theme Template', 'acfw');
	return $columns;
} // End custom columns

// ACFW CPT title
add_filter('enter_title_here', 'acfw_cpt_title');
function acfw_cpt_title( $title ){
	$screen = get_current_screen();
	if ('acf-widgets' == $screen->post_type){
		$title = 'Enter widget name';
	} return $title;
} // End acfw_cpt_title()

// Filter Excerpt Title
add_filter( 'gettext', 'acfw_custom_excerpt_title' );
function acfw_custom_excerpt_title( $input ) {
    global $post_type;
    if ( is_admin() && 'acf-widgets' == $post_type && 'Excerpt' == $input ){
        return __( 'Widget Description', 'acfw' );
    }
    return $input;
} // End CPT title rewrite

// Update Slug When Saving CPT Title
add_filter( 'wp_insert_post_data', 'acfw_update_slug', 50, 2 );
function acfw_update_slug( $data, $postarr ) {
	if ( !in_array( $data['post_status'], array( 'draft', 'pending', 'auto-draft', 'revision' ) ) && $data['post_type'] == 'acf-widgets' ) {
		$data['post_name'] = sanitize_title( $data['post_title'] );
	}
	return $data;
} // end CPT slug rewrite

// Check license once on login
add_action('wp_login', 'acfw_login_check', 10, 2);
function acfw_login_check($login, $user){
	if ( $user->allcaps['update_plugins'] && !defined('ACFW_INCLUDE') ){
		acfw_check_license();
	}
} // end login license check

// Display ACFW notices
add_action( 'admin_init', 'acfw_admin_notices' );
function acfw_admin_notices(){
	if ( ( isset($_GET['page']) && $_GET['page'] == 'acfw-options' ) || defined('ACFW_INCLUDE') || defined('ACFW_LITE') )
		return;

	global $current_user;
	$user_id = $current_user->ID;
	$dismissed = get_user_meta($user_id, 'acfw_dismiss_expired');

	if ( !empty($dismissed) ) {
		if ( $dismissed[0] !== ACFW_VERSION ) // Show expired message if they updated the plugin
			delete_user_meta( $user_id, 'acfw_dismiss_expired' );
	}

	if ( isset($_GET['acfw-dismiss-expired']) && $_GET['acfw-dismiss-expired'] == '1' )
		update_user_meta( $user_id, 'acfw_dismiss_expired', ACFW_VERSION );
	
	if ( empty( $dismissed ) ){

		if ( get_option('acfw_license_status') == 'expired' && ! $dismissed && current_user_can('update_plugins') ){
			add_action('admin_notices', 'acfw_expired_notice');
			add_action('network_admin_notices', 'acfw_expired_notice');
		}
	
	}

	global $pagenow;
	if ( $pagenow == 'plugins.php' && !defined('ACFW_INCLUDE') ){
		add_action("after_plugin_row_" . plugin_basename(ACFW_FILE), 'acfw_plugins_page_info', 11, 3);
	}
} // End ACFW notices

function acfw_plugins_page_info(){
	$status = get_option('acfw_license_status');
	if ( $status == 'valid' )
		return;
	
	$wp_list_table = _get_list_table( 'WP_Plugins_List_Table' );
	$key = get_option('acfw_license_key');
	$acfw_message = '';

	if ( $status == 'expired' )
		$acfw_message .= 'Your ACFW license is expired. <a href="http://acfwidgets.com/checkout/?edd_license_key='. $key .'&download_id=13" target="_blank">Renew it now</a> to continue receiving updates &amp; support. Contact your web developer for more information.';
	if ( ($status == 'invalid' || $key == '') && !defined('ACFW_LITE') )
		$acfw_message .= 'It seems like there is a problem with your ACFW license. Check your options in <i>Settings &gt; ACFW Options</i>';
	
	echo "<tr class='plugin-update-tr'><td class='plugin-update' colspan='{$wp_list_table->get_column_count()}'><div style='background: #fcf3ef; padding: 5px 8px; border-left: 4px solid crimson;'><span class='dashicons dashicons-dismiss' style='color: crimson; margin-right: 13px;'></span>{$acfw_message}</div></td></tr>";
}


add_action('admin_head', 'acfw_lite_css');
function acfw_lite_css(){

	if ( defined('ACFW_LITE') )
		echo "<style> .acf-field[data-key*='acfw']{ display: none; } </style>";

}
// End of File