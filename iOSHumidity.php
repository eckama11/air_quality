<?php

require_once("creds.php");
$conn = new mysqli("localhost", $dbUsername, $dbPassword, $dbName);

if (!$conn) 
{
	die('Connect Error ' . $conn->errno . ': ' . $conn->error);    
} 

else 
{
	
    	$deviceQuery = "SELECT deviceId FROM user WHERE username = '$username'";
    	$resultDQ = $conn->query($deviceQuery)->fetch_assoc();
    	$deviceId= $resultDQ['deviceId'];
    	$sql = "SELECT DATE_FORMAT(timeInfo,'%r') as 'timeInfo', pressure FROM sensors WHERE DATE(timeInfo) = DATE(NOW()) ";
            "AND impId='$deviceId'";
    	$humQuery = "Select DATE_FORMAT(timeInfo,'%r') as 'timeInfo', humidity FROM sensors WHERE DATE(timeInfo) = AND impId = '$deviceId'";
    	//$rv[] = array('Time', 'Pressure');
    	/* while ($row = $stmt->fetchObject()) {
            $rv[] = array(
            		$row->timeInfo,
					(double)$row->pressure
                );
        } // while
        */
        
    	echo '{"success":1, "hum":"'.$humQuery.'"}';
    }
    else
    {
    	echo '{"success":0,"error_message":"Humidity Temps not available"}';
    }
}