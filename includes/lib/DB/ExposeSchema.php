<?php

interface DB_ExposeSchema {

	/**
	 * Must return an instance of  DB_ObjectSchema which represents this Object.
	 */
	public static function Get_Schema();

	public static function Build_Schema();

}


?>
