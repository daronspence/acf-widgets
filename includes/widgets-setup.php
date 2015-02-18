<?php
/**
* Run db query for our CPT and additional parameters
*/	

// Block direct requests
if ( !defined('ABSPATH') ){
	die();	
}

// Create widgets from acf-widgets posts
add_action('after_setup_theme', 'acfw_setup_classes');
function acfw_setup_classes(){
	
	$acfw_query = new WP_Query(array(
		'post_type' => 'acf-widgets',
		// shouldn't have more than 100 widgets...but just in case
		'posts_per_page' => apply_filters('acfw_query_count', 100),
		'post_status' => 'publish'
	));

	$results = $acfw_query->posts;
	
	foreach ($results as $result) :
		
		$title = esc_attr($result->post_title);
		$description = esc_attr($result->post_excerpt);
		$slug = $result->post_name;
		$id = $result->ID;

		acfw_widgets_eval($title, $description, $slug, $id);

	endforeach;	
} // end acfw_setup_classes()


//////// DO IT AGAIN //////////////
add_action('after_setup_theme', 'acfw_included_widgets');
function acfw_included_widgets(){
	$acfw_included_widgets = apply_filters( 'acfw_include_widgets', array() );
	if ( !empty($acfw_included_widgets) ) :
		foreach ( $acfw_included_widgets as $widget ):
			
			$title = esc_attr($widget['title']);
			$description = esc_attr($widget['description']);
			$slug = $widget['slug'];
			$id = $widget['id'];
		
			acfw_widgets_eval($title, $description, $slug, $id);
		
		endforeach;
	endif;
} // End included widgets

// End of File