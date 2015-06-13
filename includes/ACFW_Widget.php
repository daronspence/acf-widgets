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

    	if ( $this->display_titles() ) :

	    	$title = empty( $instance['title'] ) ? '' : $instance['title']; ?>

	    	<p>
				<label for="<?php echo $this->get_field_id( 'title' ); ?>"><span style="font-weight: bold;"><?php _e( 'Title' ); ?></span></label> 
				<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>">
	    	</p>

    	<?php endif;

    	global $wp_customize;

    	if ( isset($wp_customize) ) {
    		return; // bail early if we're in the customizer
    	} ?>

            <p class='acfw-no-acf'><?php _e( 'You have not added any fields to this widget yet.', 'acfw' ); ?>

                <br/><br/>

                <a href='post-new.php?post_type=acf-field-group'><?php _e( 'Add some now!', 'acfw' ); ?></a>

                <br/><br/>

                <?php _e( 'Make sure to set the location rules to:', 'acfw' ); ?>
                
                <b><?php _e( 'Widget : is equal to : ', 'acfw' ); echo $this->title; ?></b>

            </p>

            <script type='text/javascript'> acfw();</script>

        <?php
    }
    
    function update($new_instance, $old_instance) { 
    	$instance = $old_instance; 
		$instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( trim( $new_instance['title'] ) ) : '';

    	return $instance; 
    }
    
    function widget($args, $instance) {

		extract($args, EXTR_SKIP);
		
        echo $before_widget ;

        if ( ! empty( $instance['title'] ) && $this->display_titles() ) 
			echo $args['before_title'] . apply_filters( 'widget_title', $instance['title'] ). $args['after_title'];

        $acfw = 'widget_' . $widget_id ;

        $custom_template_dir = trailingslashit( apply_filters( 'acfw_custom_template_dir', get_template_directory_uri() ) );

       // var_dump( $custom_template_dir . "widget-{$this->slug}.php" );
        
        if ( file_exists( $custom_template_dir . "widget-{$this->slug}.php" ) ) {
            require( $custom_template_dir . "widget-{$this->slug}.php" );
        } elseif ( file_exists( $custom_template_dir . "widget-{$this->post_id}.php" ) ){
            require( $custom_template_dir . "widget-{$this->post_id}.php" );
        } elseif (locate_template("widget-{$this->slug}.php") != "") {
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

    /**
     * Check for filter to show/hide Widget Titles by default.
     * @return bool should titles be displayed?
     */
    public function display_titles() {

    	if ( apply_filters("show_acfw_titles" , false ) )
    		return true;
    	elseif ( apply_filters("hide_acfw_titles" , false ) )
    		return false;
    	elseif ( apply_filters( "show_acfw_title_{$this->slug}", false ) )
    		return true;
    	elseif ( apply_filters( "hide_acfw_title_{$this->slug}", false ) )
    		return false;
    	elseif ( apply_filters( "show_acfw_title_{$this->post_id}", false ) )
    		return true;
    	elseif ( apply_filters( "hide_acfw_title_{$this->post_id}", false ) )
    		return false;
    	else
    		return false;

    }       
}

// End of File
