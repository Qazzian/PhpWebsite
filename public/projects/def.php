<?php
# Set up page values
function get_my_content()
{
	global $GLOBAL;

$content_obj = new Content(array(
	'title' => "Projects",
	'main_section' => "Projects",
	'sub_section' => "projects",
	'sub_menu' => array('docs', 'bananagrams', 'php','Pypulous'),
	'layout' => 'main',
	'content' => <<<CONTENT
		<ul>
			<li><a href="/docs">API Documentation</a> - Documentation of som API's I use</li>
			<li><a href="/bananagrams">Bananagrams</a> - A web version of the populour Anagram game</li>
			<li><a href="/proj/learning_php">Learning PHP</a></li>
			<li><a href="/proj/pypulous">Pypulous</a> - Populous in Python</li>
		</ul>
CONTENT

));
#$GLOBAL['SiteContentManager']->registerContent($content_obj);
return $content_obj;
}
return get_my_content();
