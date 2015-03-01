<?php
/**
 * Put this stuff in your functions.php file if necessary.
 */


/**
 * include the plugin without "installing" it
 */
include_once( get_stylesheet_directory() . '/acfw/acf-widgets.php' ); 
// Enable include mode 
add_filter('acfw_include', '__return_true');

// Filter for ACFW include directory. Reletive to the theme root. Necessary for include mode.
add_filter('acfw_dir', 'acfw_directory');
function acfw_directory( $dir ){
	// default value is /acf-widgets/
	return '/acfw/';
}


/**
 * Enable LITE MODE
 */
add_filter('acfw_lite', '__return_true' ); // hides all admin screens but the plugin stays active if installed. Similar to ACF hide.



/**
 * Create your own widgets on the go to include with a theme
 * All key => value pairs are required.
 */
add_filter('acfw_include_widgets', 'add_include_widgets');
function add_include_widgets(){
	$acfw_widgets = array(
		array(
			'title' => 'Test Widget 1',
			'description' => 'A widget test from functions.php',
			'slug' => 'test-widget',
			'id' => 'Test_Widget',
		),
		array(
			'title' => 'Test Widget 2',
			'description' => 'A second widget test from functions.php',
			'slug' => 'test-widget-2',
			'id' => 'Test_Widget2',
		),
	);
	return $acfw_widgets;
}