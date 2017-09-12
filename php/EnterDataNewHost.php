<?php
	$ConnectDB = new mysqli("127.0.0.1", "root", "root", "monitorizador");

	$ip_address = $_POST['add_ip_address'];
	$username 	= $_POST['add_username'];
	$password 	= $_POST['add_password'];

	$Query = "INSERT INTO host (ip_address, username, password) VALUES ('".$ip_address."','".$username."','".$password."');";

	if ($ConnectDB->query($Query))
		echo "OK";
	else 
		echo "Fail: ".$ConnectDB->connect_errno;

?>