<?php

class Config implements DB_ExposeSchema {

	const table_name = 'config';

	private static $instance;
	protected $properties = array();
	protected $config_file_properties = array();

	private function __construct() {
		$this->_init();
	}

	public static function fetch() {
		if (!isset(self::$instance)) {
			$c = __CLASS__;
			self::$instance = new $c;
		}
		return self::$instance;
	}

	private function _init() {
		$this->_init_paths();
		$this->_read_ini();
		$this->_init_content_defults();
		$this->_init_db();
	}

	private function _read_ini($filename=false){
		$path = $filename ? $filename : $this->lib_path.'site.ini';
		site_log("Ini file: $path");
		$this->config_file_properties = parse_ini_file($path, 1);
	}


	private function _init_paths() {
		$this->public_path = $_SERVER{'DOCUMENT_ROOT'};
		$this->base_path = $this->public_path .'/../';
		$this->inc_path = $this->base_path. 'includes/';
		$this->lib_path = $this->inc_path . 'lib/';
		$this->layout_path = $this->inc_path . 'layouts/';
	}

	private function _init_content_defults() {
		$this->default_layout = 'main';
		$this->site_name = 'Qazzian.co.uk';
	}

	/**
	 * Set up the database variables
	 * */
	private function _init_db() {
		$params = array('db_name', 'db_user', 'db_password', 'db_host', 'db_charset', 'db_collate');

		foreach ($params as $keyname) {
			site_log("Get config for $keyname");
			if (array_key_exists($keyname, $this->config_file_properties['database'])) {
				$this->$keyname = $this->config_file_properties['database'][$keyname];
			}
		}
	}

	public function __set($param, $value) {
		$this->properties[$param] = $value;
	}

	public function __get($param) {
		if ( array_key_exists($param, $this->properties) ) {
			return $this->properties[$param];
		}
		else {
// 			return $this->_db_get($param);
			return;
		}
	}

	private function _db_get($param) {
		global $GLOBAL;
		if ( isset($GLOBAL['DB'] ) ) {
			echo "hello";
			return;
			$db = $GLOBAL['DB'];
			$value = $db->get_var($db->prepare("SELECT value FROM $this->table_name WHERE field = %s LIMIT 1", $param));
		}
		return $value;
	}

	public function saveToDB($key, $value){
		global $GLOBAL;
		if ( isset($GLOBAL['DB'] ) ) {
			echo "hello";
			return;
			$db = $GLOBAL['DB'];
			$current_value = $db->get_var($db->prepare("SELECT value FROM $this->table_name WHERE field = %s LIMIT 1", $key));
			if ($current_value) {
				$db->update($this->table_name, $value, array(field => $key));
			}
			else {
				$db->insert($this->table_name, array(field => $key, value => $value));
			}
		}
		else {
			throw new DB_Error("Cannot save configuration. No Database configured.");
		}
	}

	public static function Get_Schema() {
		$schema = new DB_ObjectSchema('Config');
		$schema->addField('field', "varchar(255)", array('not_null' => true, 'primary_key' => true));
		$schema->addField('value', "varchar(255)", array());
		$schema->addAuditFields();
		return $schema;
	}

	public static function Build_Schema() {
		return $this::Get_Schema().toString();
	}

}
?>