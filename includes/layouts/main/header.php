<?php
global $GLOBAL;
$layout = LayoutFactory::$current_layout;
$content = $layout->content;

?>
<!DOCTPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en" charset="utf-8">
<head>
	<meta http-equive="content-type" content="text/xhtml; charset=utf-8" />
	<title><?php echo $content->title ?></title>

	<!-- include css -->
	<link rel="stylesheet" href="/assets/css/qazzian.css" type="text/css" />

	<!-- include js -->

</head>
<body>

<!--
	Header.php
	This will be the banner that is shown on all pages
-->
	<div class="head">
	<pre>Header.php</pre>
		<h1><?php
			echo ($GLOBAL['SiteConfig']->site_name);
			echo ($content->title ? ' - ' . $content->title : "");
		?></h1>

		<div class="main_menu">
			<?php $layout->buildNextMenu(); ?>

		</div>
		<hr />
	</div>
	<div class="left_nav">
		<ul>
			<?php $layout->buildNextMenu(); ?>

		</ul>
	</div>
	<div class="content">
<?php $layout->printContent() ?>

	</div>
	<div class="foot">
		<hr />
	</div>
</body>
</html>



