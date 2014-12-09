<?php

require_once("creds.php");
$mysqli = new mysqli("localhost", $dbUsername, $dbPassword, $dbName);

if (!$mysqli) 
{
	die('Connect Error ' . $conn->errno . ': ' . $conn->error);    
} 

else 
{
	$deviceId = "2336c2eb6e4936ee";
	if ($result = $mysqli->query("SELECT DATE_FORMAT(timeInfo,'%r') as 'timeInfo', humidity FROM sensors WHERE DATE(timeInfo) = DATE(NOW()) ".
            "AND impId='$deviceId'"))
    {
	/*
		//$rv[] = array('Time', 'Pressure');
		 while ($row = $stmt->fetchObject()) {
			$rv[] = array(
					$row->timeInfo,
					(double)$row->pressure
				);
		} // while
		
	*/	
		$inc = 1;
		while($resultSet = $result->fetch_assoc()){
			echo "Result $inc: {$resultSet['timeInfo']} - {$resultSet['humidity']}<br/>\n";
			$inc++;
		}
		$resultSet->close();
		$result->close();
	
    }
    else 
    {
    	echo '{"success":0, "hum": null}';
    }
    
}
