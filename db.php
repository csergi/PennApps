<?php
$host = 'localhost';
$db = 'PennApps';
$usr = 'dbuser';
$pass = '007ShakenNotStirred%';
$dbh = new PDO("mysql:host=$host;dbname=$db", $usr, $pass);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES , false);
$dbh->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
?>

