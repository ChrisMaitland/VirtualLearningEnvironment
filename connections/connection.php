<?php
include("password.php");

$user='cmaitland02';
$webserver='localhost';
$db='cmaitland02';

$conn=mysqli_connect($webserver, $user, $password, $db);

if(!$conn){
    die("Connection failed: " . mysqli_connect_error());
}