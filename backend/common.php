<?php
	define('HOST', "localhost");
	define('USER', "geopicuser");
	define('PASSWORD', "");

	$conn = new mysqli(HOST, USER, PASSWORD);

	if ($conn->connect_error){
		die('Could not connect: '. $conn->connect_errno);
	}

?>
