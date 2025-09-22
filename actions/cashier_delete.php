<?php
session_start();
include("../includes/db.php");

if ($_SESSION['role'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit;
}

$id = $_GET['id'];
$conn->query("DELETE FROM users WHERE id=$id AND role='cashier'");

header("Location: ../cashiers.php?deleted=1");
exit;
