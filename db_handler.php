<?php
$conn = mysqli_connect("localhost", "root", "", "mandatory");

//check if connection fails
if(!$conn) {
	echo "error: unable to connect to MySQL" . PHP_EOL;
	//remove debbugging error when in production
	echo "Debugging error" . mysqli_connect_error;
	exit;
}


?>