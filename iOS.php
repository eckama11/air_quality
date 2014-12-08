<?php

require_once("creds.php");
$conn = new mysqli("localhost", $dbUsername, $dbPassword, $dbName);

/* check connection */
if (!$conn) 
{
	echo '{"success":0,"error_message":"Connection did not work."}';
	die('Connect Error ' . $conn->errno . ': ' . $conn->error);    
} 

else 
{
	$username =  "eckama";
	$password =	 "password";
	//$username = $conn->real_escape_string($_POST['username']);
	//$password = $conn->real_escape_string($_POST['password']);

	$query = "SELECT password FROM user WHERE username = '$username'";
	
	$result = $conn->query($query)->fetch_assoc();
    if(password_verify($password, $result['password'] ) )
    {
    	if ($stmt = $mysqli->prepare("SELECT deviceId FROM user WHERE username = ?")) 
    	{
			/* bind parameters for markers */
			$stmt->bind_param("ss", $username);

			/* execute query */
			$stmt->execute();

			/* bind result variables */
			$stmt->bind_result($id);

			/* fetch value */
			$stmt->fetch();

			/* close statement */
			$stmt->close();
		}

		/* close connection */
		$mysqli->close();
		
		if ($id) 
		{
			echo '{"success":1}';
		} 
		else 
		{
			echo '{"success":0,"error_message":"Invalid Username/Password"}';
		}
    }
    else
    {
    	echo '{"success":0,"error_message":$password}';
    }
}