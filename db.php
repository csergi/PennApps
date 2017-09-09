<?php 
$host = 'localhost';  //  hostname
$db = 'pennapps';  //  databasename
$usr = 'dbuser';  //  username
$pass = '007ShakenNotStirred%';  //  passw
$dbh = new PDO("mysql:host=$host;dbname=$db", $usr, $pass);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
?>
