<?php

$host = "localhost";
$username = "root";
$password = "";
$dbname = "national_server";

$conn = mysqli_connect($host, $username, $password, $dbname);

if(!$conn){
    die("Connection Failed " . mysqli_connect_error());
}
?>