<?php

/**
 * A LayoutFactory is responsible for instantiating and passing on calls to a specific Layout object.
 * The Content manager should use this to fetch the layout object and start the building process.
 * Layout templates should use this to call methods on the layout object.
 * At the time that the layout template is included this is the only object that has a reliable handle
 * on the layout instance.
 */
class LayoutFactory{

	#
	# Internal static parameters
	#

	/** The layout object to use for printing */
	public static $current_layout = false;

	/**
	 * Static function to fetch the named layout object.
	 * Also keeps a handle on the last fetched Layout.
	 */
	public static function FetchLayout($layout_name){
		$layout_path = self::getLayoutDefinition($layout_name);

		self::$current_layout = include_once($layout_path);
		return self::$current_layout;
	}

	public static function getLayoutDir($layout_name){
		global $GLOBAL;
		$base_path = $GLOBAL['SiteConfig']->layout_path;
		if (!$layout_name && self::$current_layout) {
			$layout = self::$current_layout;
			$layout_name = $layout->name;
		}
		if (!$layout_name) { return; }
		$layout_path = $base_path. $layout_name;
		return $layout_path;
	}

	public static function getLayoutDefinition($name){
		return self::getLayoutDir($name).'/def.php';
	}

	/** Pass function calls on to the stored layout instance. */
	public static function __callStatic($name, $args) {
		if ( self::$current_layout ) {
			$layout = self::$current_layout;
			$out = $layout->$name($args);
		}
	}

	public function __get($name){
		if (self::$current_layout){
			$layout = self::$current_layout;
			return $layout->$name;
		}
	}

	public function __set($name, $value){
		if (self::$current_layout){
			$layout = self::$current_layout;
			return $layout->$name = $value;
		}
	}

	public function __isset($name){
		if (self::$current_layout){
			$layout = self::$current_layout;
			return isset($layout->$name);
		}
	}

	public function __unset($name){
		if (self::$current_layout){
			$layout = self::$current_layout;
			unset($layout->$name);
		}
	}

}

?>