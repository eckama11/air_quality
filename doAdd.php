<?php
if(!function_exists('password_hash')){
require("includes/password_hash.php.inc");
}

require_once("common.php");

// If the form was posted, verify the old password 
// and update the password if the 2 new passwords match and are acceptable
// check that username is not taken
$username = @$_POST['username'];
$email = @$_POST['email'];
$deviceId = @$_POST['device'];
$password1 = @$_POST['password1'];
$password2 = @$_POST['password2'];

$rv = (Object)[];
try {
	// Verify the username is unique
	if ($db->isUsernameInUse($username))
		throw new Exception("The username '$username' is already assigned to another user");

	// Verify the password is valid
	if ($password1 != $password2)
		throw new Exception("The password and verify password do not match");

	// Verify password is > 8 length	
	if (strlen($password1) < 8)
		throw new Exception("The new password must be at least 8 characters long");
	
	// id = 0 means no id given yet 
	//will be used in DBInterface to make new id
	$id = 0;
	$passwordHash = password_hash($password1, PASSWORD_DEFAULT);	
    // Create/update the user record
    $user1 = new User(
                $id, $username, $passwordHash, $email, $deviceId
            );        
            
    $user = $db->writeUser($user1);

    $rv->success = true;
} catch (Exception $ex) {
    $rv->error = $ex->getMessage();
} // try/catch

echo json_encode($rv);
