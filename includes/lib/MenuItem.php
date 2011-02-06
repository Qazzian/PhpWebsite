<?php

class MenuItem implements DB_ExposeSchema {

	public $id;
	public $name;
	public $link;
	public $selected;

	public function __construct($name, $link){
		$this->name = $name;
		$this->link = $link;
	}

	public static function Get_Schema() {
		$schema = new DB_ObjectSchema('MenuItem');
		$schema->addField('id', "int", array('primary_key' => true, 'auto_increment' => true));
		$schema->addField('menu_id', "int", array('not_null' => true));
		$schema->addField('name', "varchar(255)", array('not_null' => true));
		$schema->addField('link', "varchar(255)", array('not_null' => true)); /** hold an internal path or a URI */
		$schema->addAuditFields();
		return $schema;
	}

	public static function Build_Schema() {
		return $this::Get_Schema().build();
	}


}

?>