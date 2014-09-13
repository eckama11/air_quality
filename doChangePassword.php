<?php
require_once("common.php");

// If the form was posted, verify the old password and update the password if the 2 new passwords match and are acceptable
$currentPassword = @$_POST['currentPassword'];
$newPassword1 = @$_POST['newPassword1'];
$newPassword2 = @$_POST['newPassword2'];

$rv = (Object)[];
try {
    if (!isset($loginSession))
        throw new Exception("You do not have sufficient access to perform this action");

    // Verify the current password is a match
    if ($loginSession->authenticatedUser->password != $currentPassword)
        throw new Exception("The current password was not correct");

    if ($newPassword1 != $newPassword2)
        throw new Exception("The new password and verify password do not match");

    // Perform password strength checking
    if (strlen($newPassword1) < 8)
        throw new Exception("The new password must be at least 8 characters long");

	// A higher "cost" is more secure but consumes more processing power
	$cost = 10;

	// Create a random salt
	$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

	// Prefix information about the hash so PHP knows how to verify it later.
	// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
	$salt = sprintf("$2a$%02d$", $cost) . $salt;

	// Hash the password with the salt
	$hash = crypt($newPassword1, $salt);
    // Update the user
    $loginSession->authenticatedUser->password = $newPassword1;
    
    $db->updateUser($loginSession->authenticatedUser);

    $rv->success = true;
} catch (Exception $ex) {
    $rv->error = $ex->getMessage();
} // try/catch

echo json_encode($rv);
