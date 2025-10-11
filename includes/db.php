<?php
$host = "db.pxxl.pro:54741";
$user = "user_39fed1c9";
$pass = "ec8d9705372712204071440740efb05e";
$db   = "db_ff3c4400";

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>
