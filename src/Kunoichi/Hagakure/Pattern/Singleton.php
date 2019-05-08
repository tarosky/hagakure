<?php

namespace Kunoichi\Hagakure\Pattern;


/**
 * Singleton Pattern
 *
 * @package hagakure
 */
abstract class Singleton {
	
	/**
	 * @var static[]
	 */
	private static $instances = [];
	
	/**
	 * Constructor
	 */
	final private function __construct() {
		$this->init();
	}
	
	protected function init() {
		// Do something.
	}
	
	/**
	 * Get instance.
	 *
	 * @return static
	 */
	final public static function get_instance() {
		$class_name = get_called_class();
		if ( ! isset( self::$instances[ $class_name ] ) ) {
			self::$instances[ $class_name ] = new $class_name();
		}
		return self::$instances[ $class_name ];
	}
}
