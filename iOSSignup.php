<?php
if(!function_exists('password_hash')){
require("includes/password_hash.php.inc");
}

require_once("common.php");

$username = @$_POST['username'];
$email = @$_POST['email'];
$deviceId = @$_POST['device'];
$password1 = @$_POST['password'];

try {
	if ($db->isUsernameInUse($username)) {
		echo '{"success":0,"error_message":"Username Exist."}';
	}
	else {
		$id = 0;
		$passwordHash = password_hash($password1, PASSWORD_DEFAULT);
		$user1 = new User($id, $username, $passwordHash, $email, $deviceId); 
		$user = $db->writeUser($user1);
		echo '{"success":1}';
	}
}
catch (Exception $ex) {
	echo '{"success":0,"error_message":"Error happened."}';
}
