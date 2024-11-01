<?php
/**
 * Singleton
 * It is a singleton trait. It is used to make any class singleton.
 *
 * @package testing-elevated
 */

namespace Testing_Elevated\Includes\Traits;

/**
 * Testing_Elevated_Singleton trait.
 * It is a singleton trait. It is used to make any class singleton.
 */
trait Testing_Elevated_Singleton {

	/**
	 * Protected class constructor to prevent direct object creation
	 * Override this method in the class which implements this trait to do initialization stuff.
	 */
	protected function __construct() {
	}

	/**
	 * This method returns new or existing instance
	 * If instance is not set then it will create a new instance and return it.
	 *
	 * @return object Singleton instance of the class.
	 */
	final public static function get_instance(): object {

		/**
		 * Instance of the class.
		 *
		 * @var object|null $instance
		 */
		static $instance = null;

		// If instance is not set then create a new instance.
		if ( is_null( $instance ) ) {
			$instance = new self();
		}

		return $instance;
	}
}
