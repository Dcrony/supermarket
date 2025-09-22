<?php
include("../includes/db.php");

$id = $_POST['id'];
$name = $_POST['name'];
$category = $_POST['category'];
$price = $_POST['price'];
$stock = $_POST['stock'];

$sql = "UPDATE products SET name='$name', category='$category', price='$price', stock='$stock' WHERE id=$id";

if ($conn->query($sql)) {
    header("Location: ../products.php");
} else {
    echo "Error: " . $conn->error;
}
?>
