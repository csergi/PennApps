<?php
//Code for Cloud SQL
$dsn = getenv('MYSQL_DSN');
$user = getenv('MYSQL_USER');
$password = getenv('MYSQL_PASSWORD');
$pdo = new PDO($dsn, $user, $password);
$dbh->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
$dbh->setAttribute(PDO::ATTR_STRINGIFY_FETCHES, false);
?>
