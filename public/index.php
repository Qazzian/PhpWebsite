<?php
# Set up page values
$include_path = $_SERVER['DOCUMENT_ROOT'] . '/../includes/';

function __autoload($class_name) {
	$include_path = $_SERVER['DOCUMENT_ROOT'] . '/../includes/';
	$class_name = preg_replace('/_/', DIRECTORY_SEPARATOR, $class_name);
	require_once $include_path . 'lib/' . $class_name . '.php';
}
$debug = 0;

function site_log($msg){
	global $debug;
	if ($debug){
		echo "<pre>$msg</pre>";
	}
}

$GLOBAL['SiteConfig'] = Config::fetch();
$GLOBAL['DB'] = QazzianDB::instantiate($GLOBAL['SiteConfig']);
$GLOBAL['Request'] = new Request();
$GLOBAL['SiteContentManager'] = new ContentManager();

# Set up page values
$default_content_obj = new Content(array(
	'title' => "404 -Qazzian.co.uk",
	'main_menu_section' => "Home",
	'sub_menu_section' => "home",
	'sub_menu' => array('home', 'about'),
	'content' => <<<CONTENT
<article>
	<h1>Content not found</h1>
</article>
<article>
	<div>Blog snippets</div>
	<div>Latest from forum</div>
</article>
<article>
CONTENT
));
$default_content_obj->content .= "<h3>Test cgi includes</h3>\n" .
	exec('/var/www/cgi-bin/test.py') . "<br />" . exec('/var/www/cgi-bin/test.pl')
	. "</article>";

$GLOBAL['SiteContentManager']->buildPage($GLOBAL['Request'], $default_content_obj);

?>