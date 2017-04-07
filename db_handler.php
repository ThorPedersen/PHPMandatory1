<?php

//connects to db
$conn = mysqli_connect("localhost", "root", "", "thor_php_mandatory_1");

//check if connection fails
if(!$conn) {
	echo "error: unable to connect to MySQL" . PHP_EOL;
	//remove debbugging error when in production
	echo "Debugging error" . mysqli_connect_error;
	exit;
}


?>