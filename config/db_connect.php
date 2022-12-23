<?php

$dbHost = $_SERVER['SERVER_NAME'];
$dbName = "nsbm_events";
$dbUser = "berry";
$dbPassword = "123456";


$dsn = "mysql:host=" . $dbHost . ";dbname=" . $dbName;

$pdo = new PDO($dsn, $dbUser, $dbPassword);

$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
$pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
