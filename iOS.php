<?php

require_once("creds.php");
$conn = new mysqli("localhost", $dbUsername, $dbPassword, $dbName);

$username = "eckama";//$_POST['username'];
$password = "password";//$_POST['password'];

$query = "SELECT password FROM user WHERE username = '$username'";

if (!$conn) 
{
	die('Connect Error ' . $conn->errno . ': ' . $conn->error);    
} 

else 
{
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