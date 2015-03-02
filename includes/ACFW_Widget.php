<?php

class ACFW_Widget extends WP_Widget {

	var $title  = '';
	var $description = '';
	var $slug = '';
	var $id = 'acf_widget_'; // keep old name to preserve data
	var $classes = '';

	function __construct ( $params ) {

		$this->title = $params['title'];
		$this->description = $params['description'];
		$this->slug = $params['slug'];
		$this->id .= $params['id'];

		// Deprecated 
		$old_classname = $this->old_classname();

		parent::__construct(
			$this->id, // Base ID
			__( $this->title, 'acfw' ), // Name
			array( 
				'description' => __( $this->description, 'acfw' ), 
				'classname' => $this->id . ' ' . $old_classname . $this->classes, // class ID  + custom stuff
			) // Args
		);
      
    }

    function form($instance) {
    	global $wp_customize;
    	if ( isset($wp_customize) ) {
    		return;
    	}
		echo "<p class='acfw-no-acf'>You have not added any fields to this widget yet. 
		<br/><br/><a href=post-new.php?post_type=acf-field-group>Add some now!</a>
		<br/><br/> Make sure to set the location rules to: <b>Widget : is equal to : {$this->title} </b></p>";
		echo "<script type='text/javascript'> acfw(); </script>";
    }
    
    function update($new_instance, $old_instance) { $instance = $old_instance; return $instance; }
    
    function widget($args, $instance) {
		extract($args, EXTR_SKIP);
		
        echo $before_widget ;

        $acfw = 'widget_' . $widget_id ;
        
        if (locate_template("widget-{$this->slug}.php") != "") {
			require(locate_template("widget-{$this->slug}.php"));
		} elseif (locate_template("widget-{$this->id}.php") != "") {
			require(locate_template("widget-{$this->id}.php"));
		} else {
			echo "No template found for $widget_name ";
		}

        echo $after_widget ;
    } 

    /**
     * Returns CSS classname from ACFW < v1.4
     * @return string Old Classname
     * @deprecated since v1.4
     */
    private function old_classname(){

    	$old_classname = explode( '_' , $this->id );

		foreach ( $old_classname as $key => $value ){

			$old_classname[$key] = ucwords($value);
		
		}

		$old_classname = implode('_', $old_classname);

    	return $old_classname;
    }          
}

// End of File
