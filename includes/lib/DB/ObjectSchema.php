<?php

class DB_ObjectSchema {

	public $table_name;
	public $fields;

	private $keys;
	private $uniques;
	private $primary_key;
	private $collate;

	public $pritty_print_schema = true;# or false;

	function __construct($name) {
		$this->table_name = $name;
		$this->fields = array();
		$this->keys = array();
		$this->pritty_print_schema = true;
	}

	/**
	 * For field values see the documentation for the Field class.
	 */
	function addField($name, $type='varchar(255)', $options=null) {
		$this->fields[] = $field = new DB_Field($name, $type, $options);
		if (isset($options['primary_key']) && $options['primary_key'] == true){ $this->primary_key = $name; }
		if (isset($options['key']) && $options['key'] == true){
			$this->keys[$name] = $name;
		}
		if (isset($this->options['unique']) && $this->options['unique'] === true){ $field_schema .= 'NOT NULL '; }

	}

	/**
	 * Adds an option to the table.
	 */
	function addOption($name, $value) {

	}

	/**
	 * Set the tables primary key.
	 * Returns the name of the old primary key.
	 */
	function setPrimaryKey($fieldname) {
		$oldkey = $this->primary_key;
		$this->primary_key = $fieldname;
		return $oldkey;
	}

	/**
	 * Add an indexed key to the table.
	 * $name = name of the key
	 * $fields = array of field names that make up the key.
	 *
	 * Use this for keys built from more than one field.
	 * Otherwise you can include keyed as an option to the field when you add it.
	 */
	function addKey($name, $fields) {

	}

	/**
	 * Adds standard auditing fields to the table schema.
	 * The fields are lastupdated, created, lastupdated_by, created_by
	 */
	function addAuditFields() {
		$this->addField('lastupdated', "timesstamp", array('default' => 'NULL', 'not_null' => true));
		$this->addField('created', "timesstamp", array('default' => 'NULL', 'not_null' => true));
		$this->addField('created_by', "int", array('not_null' => true));
		$this->addField('lastupdated_by', "int", array('not_null' => true));
		return $this;
	}

	function setCollate($collate) {
		$this->collate = $collate;
	}

/*
CREATE TABLE $wpdb->terms (
term_id bigint(20) NOT NULL auto_increment,
name varchar(200) NOT NULL default '',
slug varchar(200) NOT NULL default '',
term_group bigint(10) NOT NULL default 0,
PRIMARY KEY  (term_id),
UNIQUE KEY slug (slug),
KEY name (name)
) $charset_collate

*/

	function toString() {
		$query_string = "";
		if ($this->pritty_print_schema) { $query_string .= "\n";}
		$query_string .= "CREATE TABLE IF NOT EXISTS $this->table_name (";
		# for each field print name, type, options
		foreach ($this->fields as $field)
		{
			if ($this->pritty_print_schema) { $query_string .= "\n\t"; }
			$query_string .= $field->toString();
		}

		$query_string .= ")" . $this->collate;
		return $query_string;
	}
}


class DB_Field {
	public $name;
	public $type;

	/**
	 * Possible Options:
	 * primary_key => boolean, - used by the table. MySQL treats these as keys and unique
	 * key => boolean - used by the table
	 * not_null => boolean,
	 * default => value,
	 * unique => boolean,
	 * auto_increment => boolean
	 */
	public $options;

	function __construct($name, $type, $options) {
		$this->name = $name;
		$this->type = $type;
		$this->options = $options;
	}

	public function toString() {
		$field_schema = "$this->name $this->type";
		if (isset($this->options['not_null']) && $this->options['not_null'] === true) { $field_schema .= ' NOT NULL '; }
		if (isset($this->options['default']) && $this->options['default']) { $field_schema .= " default " . $this->options['default']; }
		if (isset($this->options['auto_increment']) && $this->options['auto_increment'] === true){ $field_schema .= ' auto_increment'; }
		if (isset($this->options['unique']) && $this->options['unique'] === true){ $field_schema .= ' UNIQUE KEY'; }
		if (isset($this->options['primary_key']) && $this->options['primary_key'] === true){ $field_schema .= ' PRIMARY KEY'; }
		if (isset($this->options['comment']) && $this->options['comment']){ $field_schema .= ' COMMENT ' . $this->options['comment']; }

		return $field_schema;
	}
}


?>
