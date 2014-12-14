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
	$email = $conn->real_escape_string($_POST['email']);
	$deviceId = $conn->real_escape_string($_POST['device']);

	$query = "SELECT id FROM user WHERE username = '$username'";
	
	if ($username == "" || $password == "" || $email == "" || $deviceId == "") {
		echo '{"success":0,"error_message":"All fields are required."}';
	}
	
	/* Select queries return a resultset */
	if ($result = $mysqli->query($query)) {

		/* free result set */
		$result->close();
		echo '{"success":0,"error_message":"Username already in use."}';
	}
    else
    {
    
    	$addUserQuery = "INSERT INTO user username, password, email, deviceId VALUES '$username', '$password', '$email', '$deviceId'";
    	$mysqli->query($addUserQuery);
    	$mysqli->close();
    	echo '{"success":1, "hum":"'.$deviceId.'"}';
    }
}