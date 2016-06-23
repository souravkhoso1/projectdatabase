<?php ob_start();
	session_start();
	
	$host = "localhost";
	$username = "root";
	$password = "";
	$database = "pannu";
	
	$conn = mysqli_connect($host,$username,$password,$database) or die("not connected to database");
	
?>