<?php
class Content implements DB_ExposeSchema {

	public $path; // The path according to the Get request.
	public $content; // could this include some php?

	protected $properties = array();
	protected $valid_properties = array(
		'title' => 1,
		'menus' => 1,
		'content'=>1,
		'layout' => 1,
	);

	public function __construct($params){
		foreach ($params as $key => $value){
			$this->$key = $value;
		}
	}

	public function __set($param, $value){
		if (array_key_exists($param, $this->valid_properties) ){
			$this->properties[$param] = $value;
		}
	}

	public function __get($param){
		if ( array_key_exists($param, $this->valid_properties) && array_key_exists($param, $this->properties)){
			return $this->properties[$param];
		}
	}

	public function __isset($arg){
		return isset($this->properties[$arg]);
	}

	public function __unset($arg){
		unset($this->properties[$arg]);
	}

	public static function Get_Schema() {
		$schema = new DB_ObjectSchema('Content');
		$schema->addField('id', "int", array('not_null' => true, 'primary_key' => true, 'auto_increment' => true));
		$schema->addField('path', "varchar(255)", array('not_null' => true));
		$schema->addField('title', "varchar(255)", array());
		$schema->addField('content', "text", array('not_null' => true, 'default' => ''));
		$schema->addField('menu_item_id', "int", array());
		$schema->addField('revision', "int", array('auto_increment' => true));
		$schema->addField('published', "int", array()); /** TODO: Need to decide on conventions here. 1=live */
		$schema->addAuditFields();
		return $schema;
	}

	public static function Build_Schema() {
		return $this::Get_Schema().build();
	}




}
?>