<?php
# Set up page values
function get_my_content()
{
	global $GLOBAL;

	$content_obj = new Content(array(
	'title' => "Qazzian.co.uk",
	'menus' => array('Home' => 'home'), # Menu_name => selected_option
	'content' => <<<CONTENT
<article>
	<ul>
		<li><a href="/docs">API Documentation</a> - Documentation of some API's I use</li>
		<li><a href="/bananagrams">Bananagrams</a> - A web version of the populour Anagram game</li>
		<li><a href="/proj/learning_php">Learning PHP</a></li>
		<li><a href="/proj/pypulous">Pypulous</a> - Populous in Python</li>
	</ul>
</article>
<article>
	<div>Blog snippets</div><!-- use The Wordpress Loop -->
	<div>Latest from forum</div>
</article>
<article>
CONTENT
));
$content_obj->content .= "<h3>Test cgi includes</h3>\n" .
	exec('/var/www/cgi-bin/test.py') . "<br />" . exec('/var/www/cgi-bin/test.pl')
	. "</article>";
	return $content_obj;
}
return get_my_content();

?>