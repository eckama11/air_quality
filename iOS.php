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
    	echo "true";
    }
    else
    {
    	echo "False";
    }
}
//aslkdjf