<?php
$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

$dbh = new PDO("mysql:host=$server;dbname=$db", $username, $password, $db);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES , false);
$dbh->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
?>

