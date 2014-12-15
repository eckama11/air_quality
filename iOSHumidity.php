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
	$date = date('Y-m-d');
	//$date = date('Y-m-d',$_POST['PostedTime']);
	$result = $mysqli->query("SELECT DATE_FORMAT(timeInfo,'%h%i %p') as 'timeInfo', humidity FROM sensors WHERE DATE(timeInfo) = DATE('$date') AND impId='$deviceId'");
	if ($result->num_rows > 0)
    {	
    	echo '{"success":0}';
    	
		$inc = 0;
		while($resultSet = $result->fetch_assoc()){
			$time[$inc] = $resultSet['timeInfo'];
			$humidity[$inc] = $resultSet['humidity'];
			//echo '{"success":1, "hum":"'.$deviceId.'"}'
			echo '{"time:""'.$time[$inc].'"}';
			echo '{"humidity:""'.$humidity[$inc].'"}';
			//echo "Result $inc: {$resultSet['timeInfo']} - {$resultSet['humidity']}<br/>\n";
			$inc++;
		}
		//echo implode("<br/>",$time);
		$result->close();
	
    }
    else 
    {
    	echo '{"success":0, "hum": null}';
    }
    
}
