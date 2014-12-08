<?php

require_once("creds.php");
$conn = new mysqli("localhost", $dbUsername, $dbPassword, $dbName);

if (!$conn) 
{
	die('Connect Error ' . $conn->errno . ': ' . $conn->error);    
} 

else 
{
	$username = $conn->real_escape_string($_POST['username']);
	$password = $conn->real_escape_string($_POST['password']);

	$query = "SELECT password FROM user WHERE username = '$username'";
	
	$result = $conn->query($query)->fetch_assoc();
    if(password_verify($password, $result['password'] ) )
    {
    	echo '{"success":1 "temp":2 "hum":3 "pres":4 "part":5}';
    }
    else
    {
    	echo '{"success":0,"error_message":"Username and password do not match."}';
    }
}
//aslkdjf