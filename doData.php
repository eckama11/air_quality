<?php
  $dbuser="air_quality";
  $dbpass="air_quality";

  if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    die("not post request");
  }

  $con = new mysqli("localhost",$dbuser,$dbpass,'air_quality');
  if (!$con) {
    die("connection failed: " . $con->error);
  }
  unset($dbuser,$dbpass);

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
  $stmt->bind_param('ssddddddd',$imp, $time, $newTemp, $newHum, $pressure, $altitude, $latitude, $longitude, $particles);
  if ( !$stmt->execute() ) {
    die("database insert failed: " . $con->errno . ': ' . $con->error);
  }

  echo "\r\n".$newTemp;
  echo "\r\nsuccess";

  $con->close();
