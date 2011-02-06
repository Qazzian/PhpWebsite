<?php
/**
 * Ensure the database is set up correctly with the required tables etc.
 */

class DB_Setup {

	public function __construct(){
		$this->site_config = Config::fetch();
		$this->db = QazzianDB::instantiate($this->site_config);
		$this->schema = $this->db->getFullSchema();
		print "<pre>Full schema: ".var_export($this->schema, true)."</pre>";
	}

	private function _installDBFromFresh() {

	}



}

?>
