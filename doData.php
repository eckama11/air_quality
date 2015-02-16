<?php
  require_once("creds.php");

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("not post request");
  }

  $con = new mysqli("localhost",$dbUsername,$dbPassword,$dbName);
  if (!$con) {
    die("connection to beta failed: " . $con->error);
  }
  unset($dbuser,$dbpass);
  
  $con2 = new mysqli("just115.justhost.com","islehar1_eckama","TlqoG^giL9P}7e#&*T","islehar1_air-quality");
  if (!$con2) {
    die("connection to production failed: " . $con2->error);
  }
  
  $imp = $con->real_escape_string($_POST['imp']);
  $newTemp = floatval($con->real_escape_string($_POST['newTemp']));
  $newHum = floatval($con->real_escape_string($_POST['newHum']));
  $pressure = floatval($con->real_escape_string($_POST['pressureA']));
  $altitude = floatval($con->real_escape_string($_POST['altitude']));
  $latitude = floatval($con->real_escape_string($_POST['latitude']));
  $longitude = floatval($con->real_escape_string($_POST['longitude']));
  $particles = floatval($con->real_escape_string($_POST['particles']));
  $time = date('Y-m-d H:i:s');

//USED FOR DEBUGGING
//echo "$imp\r\n$newTemp\r\n$newHum\r\n$pressure\r\n$altitude\r\n$latitude\r\n$longitude\r\n$particles\r\n$time";

  $query = "INSERT INTO `sensors`(impID, timeInfo, temperature, humidity, pressure, altitude, latitude, longitude, particles)
  values(?,?,?,?,?,?,?,?,?)";
  
  $stmt = $con->prepare($query);
  $stmt2 = $con2->prepare($query);
  
  $stmt->bind_param('ssddddddd',$imp, $time, $newTemp, $newHum, $pressure, $altitude, $latitude, $longitude, $particles);
  $stmt2->bind_param('ssddddddd',$imp, $time, $newTemp, $newHum, $pressure, $altitude, $latitude, $longitude, $particles);
 
  $res = $stmt->execute();
  $res2 = $stmt2->execute();
 
  if ( !$res ) {
    die("database insert on beta failed: " . $con->errno . ': ' . $con->error);
  }elseif( !$res2 ){
    die("database insert on production failed: " . $con2->errno . ': ' . $con2->error);
  }

  $con->close();
  $con2->close();
