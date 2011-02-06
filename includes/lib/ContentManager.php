<?php
class ContentManager {

	public $path; // The path according to the Get request.
	public $content; // could this include some php?
	public $request;
	public $default_content;

	private $is_file = false; // Otherwise it's in the database.


	private $KNOWN_PATHS = array(
		'/' => 1,
		'/bananagrams' => 1,
		'/blog' => 1,
		'/docs' => 1,
		'/forum' => 1,
		'/projects' => 1
	);

	public function buildPage($request, $default_content){
		$this->request = $request;
		$this->default_content = $default_content;
		global $GLOBAL;

		$url = $this->request->url;
		$content = $this->getContent($url);
		if ($content)
		{
			$layout = $this->getLayoutFromContent($content);
			$layout->attach_content($content)->buildPage();

			#print $content->content;
		}
	}

	public function registerContent($content_obj){
		$this->content = $content_obj;
	}

	/**
	 * Checks if the url exists either in the file system or database.
	 * If it is found the the content object is returned.
	 * Otherwise a 404 Exception is raised.
	 */
	public function getContent($url) {
		site_log( "Url: $url\n");
		$pathname = parse_url($url, PHP_URL_PATH);
		site_log("parsed url: $pathname\n");
		$pathname = $this->_sanitise_path($pathname);
		site_log( "sanitized url: $pathname\n");
		$content_obj = false;
		try {
			if ($this->isPathValid($pathname)) {
				$content_obj = $this->loadContent($pathname);
			}
		}
		catch (Exceptions_FourOhFourException $e) {
			# TODO: Log exception.
		}
		catch (Exception $e) {
			echo "404";
		}
		if (!$content_obj) {
			$content_obj = $this->default_content;
		}
		return $content_obj;
	}

	private function _sanitise_path($pathname) {
		global $GLOBAL;
		$dir_name = dirname($pathname);
		$dir_name = preg_replace('/[^a-zA-Z0-9\/]+/', '',$dir_name );
		$filename = basename($pathname);
		$filename = preg_replace('/[^a-zA-Z0-9\.]/', '', $filename);

		$filepath = $GLOBAL['SiteConfig']->public_path . $dir_name . $filename;
		return $filepath;
	}

	public function isPathValid($pathname) {
		$filename = $pathname;
		if (file_exists($filename)){
			return true;
		}
		else {
			throw new Exceptions_FourOhFourException("Cannot find file $pathname");
		}
	}

	public function loadContent($pathname) {
		site_log("load content from $pathname\n");
		if ( is_dir($pathname) ){
			if (!preg_match('/\/$/',$pathname)){
				$pathname .= '/';
			}
			$pathname .= 'def.php'; # TODO: Store this in the config
			#TODO check for the '/' at end of the pathname
		}
		if (is_file($pathname)){
			// At this point the content handler returns a content object.
			$content_obj = require_once $pathname;
			if ($content_obj){
				$this->content = $content_obj;
			}
		}
		else{
			throw new Exceptions_FourOhFourException("File not found $pathname");
		}
		return $this->content;
	}

	public function getLayoutFromContent($content){
		$layout_name;
		global $GLOBAL;

		if (key_exists('layout', $content)){
			$layout_name = $content->layout_name;
		}
		else{
				$layout_name = $GLOBAL['SiteConfig']->default_layout;
		}
		$layout = LayoutFactory::FetchLayout($layout_name);
		return $layout;
	}

	private function fetchLayout($layout_name) {
		global $GLOBAL;
		$layout_path = $GLOBAL['SiteConfig']->layout_path . $layout_name;
		$layout = include($layout_path);
	}


	public function print_url() {
		print '<article><h3>Php stats</h3>';
		print '<p>Using php.</p>';
		print '<p>REQUEST_URI: '. $_SERVER['REQUEST_URI'] .'</p>';
		print '<p>DOCUMENT_ROOT: '. $_SERVER['DOCUMENT_ROOT'] .'</p>';

		$requested_uri = $_SERVER['REQUEST_URI'];
		$requested_uri = preg_replace('/\\.\\.+/', '', $requested_uri);
		$requested_uri = preg_replace('/\\/\\/+/', '', $requested_uri);
		print '<p>Document to serve:'.$_SERVER['DOCUMENT_ROOT'].$requested_uri.'</p>';

		$all_vars = get_defined_vars();
		print '<p>All Vars: ' . serialize($all_vars) . '</p>';
		print '</article>';

	}
}
?>