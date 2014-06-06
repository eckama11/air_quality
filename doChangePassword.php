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

    // Update the user
    $loginSession->authenticatedUser->password = $newPassword1;
    
    $db->writeUser($loginSession->authenticatedUser);

    $rv->success = true;
} catch (Exception $ex) {
    $rv->error = $ex->getMessage();
} // try/catch

echo json_encode($rv);
