<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h2>Welcome, <?= $_SESSION['role']; ?></h2>
  <ul>
    <li><a href="pos.php">Point of Sale (POS)</a></li>
    <li><a href="sales_report.php">Sales Report</a></li>
    <li><a href="products.php">Manage Products</a></li>
    <li><a href="logout.php">Logout</a></li>
  </ul>
</body>
</html>
