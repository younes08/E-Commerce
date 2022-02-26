<?php 
//connecting to the database
$localhost = "localhost";
$username = "root";
$password = "";
$db = "e-com";

$mysqli = new mysqli ($localhost, $username, $password, $db);
if ($mysqli->connect_error) {
    die("Connection failed: " . $mysqli->connect_error);
}