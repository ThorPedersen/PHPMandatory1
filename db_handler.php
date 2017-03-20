<?php

$dbc = mysqli_connect('localhost', 'root', '', 'mandatory');

if(!$dbc) {
	echo "error: unable to connect to MySQL" . PHP_EOL;
	//remove debbugging error when in production
	echo "Debugging error" . mysqli_connect_error;
	exit;
}


?>