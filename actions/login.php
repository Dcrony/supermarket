<?php
session_start();
include("../includes/db.php");

$email = $_POST['email'];
$password = md5($_POST['password']); // encrypt like we stored

$sql = "SELECT * FROM users WHERE email='$email' AND password='$password'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['name'] = $user['name'];
    $_SESSION['role'] = $user['role'];
    header("Location: ../dashboard.php");
} else {
    echo "<script>alert('Invalid email or password'); window.location='../login.php';</script>";
}
?>
