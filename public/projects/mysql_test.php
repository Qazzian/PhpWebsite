<?php

$table_prefix  = 'wp_';

$conn = mysqli_connect("localhost", "wp", "c9weUc4qcmGm9NZt", "qazzian");
if (empty($conn)) {
	die("mysqli_connect failed: " . mysqli_connect_error());
}
print "connected to " . mysqli_get_host_info($conn) . "\n";
mysqli_close($conn);


?>