<?php
require_once("common.php");
$username = @$_POST['username'];
$password = @$_POST['password'];
$page = @$_POST['page'];

// A higher "cost" is more secure but consumes more processing power
$cost = 10;

// Create a random salt
$salt = strtr(base64_encode(mcrypt_create_iv(16, MCRYPT_DEV_URANDOM)), '+', '.');

// Prefix information about the hash so PHP knows how to verify it later.
// "$2a$" Means we're using the Blowfish algorithm. The following two digits are the cost parameter.
$salt = sprintf("$2a$%02d$", $cost) . $salt;

// Value:
// $2a$10$eImiTXuWVxfM37uY4JANjQ==

// Hash the password with the salt
$hash = crypt($password, $salt);

try {
    $session = $db->createLoginSession($username, $hash);
    session_id($session->sessionId);
    session_start();

    $rv = (Object)[ "redirect" => getLoginRedirect($session, $page) ];
} catch (Exception $ex) {
    $rv = (Object)[ "error" => $ex->getMessage() ];
}

header("Content-Type: application/json");
echo json_encode($rv);