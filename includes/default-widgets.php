<?php
/**
* Add ACFW support to widgets not created by ACFW
*/

// Block direct requests
if ( !defined('ABSPATH') ){
	die();	
}


add_filter('dynamic_sidebar_params', 'acfw_wp_defaults', 10);
function acfw_wp_defaults( $params ) {
	// get widget name and replace spaces w/ dashes
	$widget_name = strtolower($params[0]['widget_name']);
	$widget_name = preg_replace("/[\s-]+/", " ", $widget_name);
	$widget_name = preg_replace("/[\s_]/", "-", $widget_name);
	
	$widget_id = $params[0]['widget_id'];
	
	// if this is an ACF Widget, GET OUT OF THERE!
	if ( strpos($widget_id, 'acf_widget') !== false ){
		return $params;
	}
	
	// find template for default widgets
	$template = '';
	if (locate_template("widget-" . $widget_name . ".php") != "") {
		$acfw = 'widget_'.$widget_id;
		// store template contents
		ob_start();
			require(locate_template("widget-". $widget_name . ".php", false));
		$template = ob_get_clean();
	}
	if ( get_option('acfw_debug') && !defined('ACFW_LITE') ){
		$template .= "<br>Looking for template:<b> 'widget-" . $widget_name . ".php'</b><br>";
	}
	
	// Get desired widget position, else default to after widget.
	if ( function_exists('get_field') ) :
		$select =  get_field('acf_widgets_location', 'widget_' . $widget_id);
	else :
		$select = 'end';
	endif;
		
	// Attatch template to correct position
	$before = $params[0]['before_widget'];
	$after = $params[0]['after_title'];
	$end = $params[0]['after_widget'];
	
	if ($select == 'before'){
		$params[0]['before_widget'] = $before . $template;
	} elseif ($select == 'after'){
		$params[0]['after_title'] = $after . $template;
	} else {
		$params[0]['after_widget'] = $template . $end;
	}	
	return $params;
} // end acf_widgets_wp_defaults();


// Add ACFW to WP Widgets w/ before/after title
add_action('init', 'acfw_register_field_group');
function acfw_register_field_group(){	

	// vars
	$default_widgets = $GLOBALS['acfw_default_widgets'];
	// Render location for default widgets
	$df_widgets = acfw_location_rules($default_widgets, 'widget', '==', true);
	
	if ( function_exists('get_field') ):
	register_field_group(array (
		'key' => 'group_acfw_default_widget',
		'title' => 'ACF Widgets Location',
		'fields' => array (
			array (
				'key' => 'field_acfw_default_widget',
				'label' => __('Custom Field Location', 'acfw'),
				'name' => 'acf_widgets_location',
				'prefix' => '',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'choices' => array (
					'before' => __('Before Title', 'acfw'),
					'after' => __('After Title (title required)', 'acfw'),
					'end' => __('After Widget', 'acfw'),
				),
				'default_value' => array (
					'end',
				),
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'ajax' => 0,
				'placeholder' => '',
				'disabled' => 0,
				'readonly' => 0,
			),
		),
		// Add to Default Widgets
		'location' => $df_widgets,
		'menu_order' => -1,
		'position' => 'acf_after_title',
		'style' => 'default',
		'label_placement' => 'left',
		'instruction_placement' => 'field',
		'hide_on_screen' => '',
	));	
	endif;	
	
} // end acf_widgets_register_field_group()


// Add ACFW to non-WP widgets
add_action('init', 'acfw_other_widgets');
function acfw_other_widgets(){
	// vars
	$installed_widgets = $GLOBALS['wp_widget_factory']->widgets;
	$default_widgets = $GLOBALS['acfw_default_widgets'];
	
	// Remove Default & ACFW widgets
	$excluded_widgets = array();
	foreach($installed_widgets as $key => $value){
		if ( strpos($value->id_base, 'acf_widget') !== false ){
			array_push($excluded_widgets, $value->id_base);
		}
		foreach ($default_widgets as $dw){
			if ($value->id_base == $dw ){
				array_push($excluded_widgets, $value->id_base);
			}
		}
	}
	
	// Setup widgets for ACF to remove
	$removed_widgets = acfw_location_rules($excluded_widgets, 'widget', '!=');
	// Add back all other widgets
	$all = array( 'param' => 'widget', 'operator' => '==', 'value' => 'all' );
	array_push($removed_widgets[0], $all);

	// Register ACFW Field for all other Widgets
	if ( function_exists('get_field') ):
	register_field_group(array (
		'key' => 'group_acfw_other_widgets',
		'title' => 'Other Widgets',
		'fields' => array (
			array (
				'key' => 'field_acfw_other_widgets',
				'label' => __('Custom Fields Location'),
				'name' => 'acf_widgets_location',
				'prefix' => '',
				'type' => 'select',
				'instructions' => '',
				'required' => 0,
				'conditional_logic' => 0,
				'choices' => array (
					'before' => __('Before Widget'),
					'end' => __('After Widget'),
				),
				'default_value' => array (
					'end' => 'end',
				),
				'allow_null' => 0,
				'multiple' => 0,
				'ui' => 0,
				'ajax' => 0,
				'placeholder' => '',
				'disabled' => 0,
				'readonly' => 0,
			),
		),
		// All Widgets except ACFW & WP
		'location' => $removed_widgets , 
		'menu_order' => 0,
		'position' => 'normal',
		'style' => 'default',
		'label_placement' => 'top',
		'instruction_placement' => 'label',
		'hide_on_screen' => '',
	) );
	endif;

} // End acfw_other_widgets()

// End of File