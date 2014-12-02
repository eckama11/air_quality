<?php
require_once("creds.php");
$db = new DBInterface("localhost", $dbName, $dbUsername, $dbPassword);

$username = @$_POST['username'];
$password = @$_POST['password'];

try {
    $session = $db->createLoginSession($username, $password);
    session_id($session->sessionId);
    session_start();

    $rv = (Object)[ "redirect" => getLoginRedirect($session, $page) ];
} catch (Exception $ex) {
    $rv = (Object)[ "error" => $ex->getMessage() ];
}

header("Content-Type: application/json");
echo json_encode($rv);