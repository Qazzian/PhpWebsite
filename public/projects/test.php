<h1>Globals</h1>
<?php
# Print Global Variables
$my_super_globals = array('GET', 'POST', 'COOKIE', 'ENV', 'SERVER');
foreach ($my_super_globals as $my_global_name)
{
	print "<h2>$my_global_name</h2>";
	eval ('$global_var =& $_'.$my_global_name.';');
	?>
	<table>
	<?php

	foreach ($global_var as $key_name => $var)
	{
		print "<tr><td>$key_name</td><td>$var</td></tr>";
	}
	?>
	</table>
	<?php
}

# Print info
phpinfo();
?>