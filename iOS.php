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
    	$deviceQuery = "SELECT deviceId FROM user WHERE username = '$username'";
    	$resultDQ = $conn->query($deviceQuery)->fetch_assoc();
    	$deviceId= $resultDQ['deviceId'];
    	echo '{"success":1, "deviceId":"'.$deviceId.'"}';
    }
    else
    {
    	echo '{"success":0,"error_message":"Invalid Username/Password"}';
    }
}
//aslkdjf