<?php

require_once("creds.php");
$conn = new mysqli("localhost", $dbUsername, $dbPassword, $dbName);

if (!$conn) 
{
	die('Connect Error ' . $conn->errno . ': ' . $conn->error);    
} 

else 
{
	
	$deviceId = "2336c2eb6e4936ee";
	$humQuery = "Select DATE_FORMAT(timeInfo,'%r') as 'timeInfo', humidity FROM sensors WHERE DATE(timeInfo) = DATE(timeInfo) = DATE(NOW()) AND impId = '$deviceId'";
	//$rv[] = array('Time', 'Pressure');
	/* while ($row = $stmt->fetchObject()) {
		$rv[] = array(
				$row->timeInfo,
				(double)$row->pressure
			);
	} // while
	*/
	$resultDQ = $conn->query($humQuery)->fetch_assoc();
	echo '{"success":1, "hum":"'.$humQuery.'"}';
    }
}