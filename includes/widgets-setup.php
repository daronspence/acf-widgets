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
	
	global $wpdb;
	$results = $wpdb->get_results( 
		"
		SELECT ID, post_name, post_title, post_excerpt
		FROM $wpdb->posts 
		WHERE post_type = 'acf-widgets'
			AND post_status = 'publish'
		"
	);
	
	foreach ($results as $result) :
		
		$title = $result->post_title;
		$slug = $result->post_name;
		$description = $result->post_excerpt;
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
			
			$title = $widget['title'];
			$slug = $widget['slug'];
			$description = $widget['description'];
			$id = $widget['id'];
		
			acfw_widgets_eval($title, $description, $slug, $id);
		
		endforeach;
	endif;
} // End included widgets




// End of File