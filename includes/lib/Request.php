<?php

/**
* Uses server global variables to get information about the request.
*/
class Request{

	/**
	*
	*/
	public function __construct(){


	}

	public function __get($param){
		if (isset($this->params->$param)){
			return $this->params->$param;
		}else if (method_exists($this, '_get_'.$param)){
			$method_name = '_get_'.$param;
			return $this->$method_name();
		}
		else return null;
	}

	public function _get_url(){
		$requested_uri = $_SERVER['REQUEST_URI'];
		$requested_uri = preg_replace('/\\.\\.+/', '', $requested_uri);
		$requested_uri = preg_replace('/\\/\\/+/', '', $requested_uri);$requested_uri.'</p>';



		return $requested_uri;
	}


}
?>