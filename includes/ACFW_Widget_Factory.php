<?php

class ACFW_Widget_Factory extends WP_Widget_Factory {
	/** @var ACFW_Widget_Factory */
	private static $instance = NULL;

	/**
	 * Extend register($widget_class) with ability to pass parameters into widgets
	 *
	 * @param string $widget_class Class of the new Widget
	 * @param array|null $params parameters to pass through to the widget
	 */
	function register($widget_class, $params = null) {
		$key = $widget_class;
		if ( !empty($params) ) {
			$key .= '_' . $params['id'];
		}
		$this->widgets[$key] = new $widget_class($params);
	}


	/**
	 * Get (and instantiate, if necessary) the instance of the class
	 * @static
	 * @return Shared_Sidebars
	 */
	public static function get_instance() {
		if ( !is_a( self::$instance, __CLASS__ ) ) {
			self::$instance = new self();
		}
		return self::$instance;
	}

	final public function __clone() {
		trigger_error( "No cloning allowed!", E_USER_ERROR );
	}

	final public function __sleep() {
		trigger_error( "No serialization allowed!", E_USER_ERROR );
	}

	public function __construct() {
		parent::__construct();
	}

}

// End of File