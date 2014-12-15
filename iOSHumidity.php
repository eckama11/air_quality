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
	$dateA = $_POST['date'];
	//$dateA = date('Y-m-d',$_POST['date']);
	$deviceIdA = $_POST['deviceId'];
	$result = $mysqli->query("SELECT DATE_FORMAT(timeInfo,'%h%i %p') as 'timeInfo', humidity FROM sensors WHERE DATE(timeInfo) = DATE('$dateA') AND impId='$deviceIdA'");
	if ($result->num_rows > 0)
    {	
    	//echo '{"success":1}';
    	$full = '{"success":1,"data":[';
		$inc = 1;
		while($resultSet = $result->fetch_assoc()){
			$str1 = '{"time": "';
			$str2 = $resultSet['timeInfo'];
			$str3 = '","humidity":"';
			$str4 = $resultSet['humidity'];
			$str5 = '"}';
			$full = $full.$str1.$str2.$str3.$str4.$str5;
			if (!$inc == $result->num_rows) {
				//add comma to result
				$full.= ",";
			}
			$inc++;
		}
		$end = ']}';
		$full = $full.$end;
		echo $full;
		//echo implode("<br/>",$time);
		$result->close();
	
    }
    else 
    {
    	echo '{"success":0}';
    }
    
}
