<?php

class ACFW_Widget extends WP_Widget {

	var $title  = '';
	var $description = '';
	var $slug = '';
	var $post_id = 0 ; 
	var $data_id = 'acf_widget_'; // keep old name to preserve data
	var $classes = '';

	function __construct ( $params ) {

		$this->title = $params['title'];
		$this->description = $params['description'];
		$this->slug = $params['slug'];
		$this->post_id = $params['id'];
		$this->data_id .= $this->post_id;

		// Deprecated 
		$old_classname = $this->old_classname();

		parent::__construct(
			$this->data_id,
			__( $this->title, 'acfw' ), // Name
			array( 
				'description' => __( $this->description, 'acfw' ), 
				'classname' => $this->data_id . ' ' . $old_classname . ' ' . $this->classes, // class ID  + custom stuff
			) // Args
		);
      
    }

    function form($instance) {

    	if ( $this->title_filter_exists() ) :

	    	$title = apply_filters( 'widget_title', empty( $instance['title'] ) ? '' : $instance['title'], $instance, $this->id_base ); ?>

	    	<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><span style="font-weight: bold;"><?php _e( 'Title' ); ?></span></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
	    	</p>

    	<?php endif;

    	global $wp_customize;
    	if ( isset($wp_customize) ) {
    		return;
    	}
		echo "<p class='acfw-no-acf'>You have not added any fields to this widget yet. 
		<br/><br/><a href=post-new.php?post_type=acf-field-group>Add some now!</a>
		<br/><br/> Make sure to set the location rules to: <b>Widget : is equal to : {$this->title} </b></p>";
		echo "<script type='text/javascript'> acfw(); </script>";
    }
    
    function update($new_instance, $old_instance) { 
    	$instance = $old_instance; 
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';

    	return $instance; 
    }
    
    function widget($args, $instance) {

		extract($args, EXTR_SKIP);
		
        echo $before_widget ;

        if ( ! empty( $instance['title'] ) && $this->title_filter_exists() ) 
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];
		elseif ( $this->title_filter_exists() ) 
			echo $args['before_title'] . apply_filters( 'widget_title', $this->title ). $args['after_title'];

        $acfw = 'widget_' . $widget_id ;
        
        if (locate_template("widget-{$this->slug}.php") != "") {
			require(locate_template("widget-{$this->slug}.php"));
		} elseif (locate_template("widget-{$this->post_id}.php") != "") {
			require(locate_template("widget-{$this->post_id}.php"));
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

    	$old_classname = explode( '_' , $this->data_id );

		foreach ( $old_classname as $key => $value ){

			$old_classname[$key] = ucwords($value);
		
		}

		$old_classname = implode('_', $old_classname);

    	return $old_classname;
    }   

    private function title_filter_exists() {

    	if ( apply_filters("show_acfw_titles" , false ) )
    		return true;
    	elseif ( apply_filters( "show_acfw_title_{$this->slug}", false ) )
    		return true;
    	elseif ( apply_filters( "show_acfw_title_{$this->post_id}", false ) )
    		return true;
    	else
    		return false;

    }       
}

// End of File
