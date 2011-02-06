<?php

class Menu implements DB_ExposeSchema {

	private $id;
	public $name;
	private $items;

	private $selected_item_id;

	public static function loadMenu($menu_name){
		/**
		 * Load the file
		 * parse the values
		 * return the new object.
		 */
	}

	public function __construct($name, $items){
		$this->name = $name;
		$this->items = (array)$items;
	}

	public function addItem($menu_item){
		$this->items[] = $menu_item;
	}

	public function toString(){


	}

	public static function Get_Schema() {
		$schema = new DB_ObjectSchema('Menu');
		$schema->addField('id', "int", array('primary_key' => true, 'auto_increment' => true));
		$schema->addField('name', "varchar(255)", array('not_null' => true));
		$schema->addAuditFields();
		return $schema;
	}

	public static function Build_Schema() {
		return $this::Get_Schema().build();
	}






}

?>