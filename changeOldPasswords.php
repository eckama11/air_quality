<?php
require_once("creds.php");
$con = new mysqli("localhost",$dbUsername,$dbPassword,$dbName);

if (!$con) {
    die("connection failed: " . $con->error);
}
unset($dbUsername,$dbPassword);
  
$hash = "password";
// A higher "cost" is more secure but consumes more processing power
$cost = 10;

// Create a random salt
$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

// Prefix information about the hash so PHP knows how to verify it later.
// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
$salt = sprintf("$2a$%02d$", $cost) . $salt;

// Hash the password with the salt
$password = crypt($hash, $salt);

$sql = 'UPDATE user SET password=? WHERE username="eckama" AND username="areis"';
$stmtUpdate = $con->prepare($sql);
	
$stmtUpdate->bind_param('s',$password);
$success = $stmtUpdate->execute();
if ($success) {
	echo "passwords changed";
}