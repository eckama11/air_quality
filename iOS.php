<?php

require_once("creds.php");
$conn = new mysqli("localhost", $dbUsername, $dbPassword, $dbName);

if (!$conn) 
{
	die('Connect Error ' . $conn->errno . ': ' . $conn->error);    
} 

else 
{
	$username = "eckama";//$conn->real_escape_string($_POST['username']);
	$password = "password";//$conn->real_escape_string($_POST['password']);

	$query = "SELECT password FROM user WHERE username = '$username'";
	
	$result = $conn->query($query)->fetch_assoc();
    if(password_verify($password, $result['password'] ) )
    {
    	echo '{"success":1, "hum":"3"}';
    }
    else
    {
    	echo '{"success":0,"error_message":"Invalid Username/Password"}';
    }
}
//aslkdjf