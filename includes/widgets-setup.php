<?php
/**
* Run db query for our CPT and additional parameters
*/	

// Block direct requests
if ( !defined('ABSPATH') ){
	die();	
}

// $GLOBALS['wp_widget_factory'] is created just before 'setup_theme' hook is called
// @see wp-settings.php
add_action('setup_theme', 'acfw_widget_factory_load', 0, 0);
function acfw_widget_factory_load() {
	$GLOBALS['wp_widget_factory'] = ACFW_Widget_Factory::get_instance();
}

function acfw_register_widget( $widget_class, $params ) {
	/** @var Tribe_WP_Widget_Factory $wp_widget_factory */
	global $wp_widget_factory;
	$wp_widget_factory->register( $widget_class, $params );
}


add_action('widgets_init', 'acfw_widgets');
function acfw_widgets(){

	$acfw_query = new WP_Query(array(
		'post_type' => 'acf-widgets',
		// shouldn't have more than 100 widgets...but just in case
		'posts_per_page' => apply_filters('acfw_query_count', 100),
		'post_status' => 'publish'
	));

	$results = $acfw_query->posts;
	
	foreach ($results as $result) :

		$params = array();
		
		$params['title'] = esc_attr($result->post_title);
		$params['description'] = esc_attr($result->post_excerpt);
		$params['slug'] = $result->post_name;
		$params['id'] = $result->ID;

		//var_dump($params);

		acfw_register_widget('ACFW_Widget', $params);

	endforeach;	

}

add_action('widgets_init', 'acfw_included_widgets');
function acfw_included_widgets(){

	$acfw_included_widgets = apply_filters( 'acfw_include_widgets', array() );

	if ( !empty($acfw_included_widgets) ) :

		foreach ( $acfw_included_widgets as $widget ):

			$params = array();
			
			$params['title'] = esc_attr($widget['title']);
			$params['description'] = esc_attr($widget['description']);
			$params['slug'] = $widget['slug'];
			$params['id'] = $widget['id'];
		
			acfw_register_widget('ACFW_Widget', $params);
		
		endforeach;
		
	endif;

}

// End of File