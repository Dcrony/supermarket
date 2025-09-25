<?php
session_start();
include("../includes/db.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $cashier_id = $_SESSION['user_id'] ?? 1; 
    $total = $_POST['total'];

    // Insert sale
    $conn->query("INSERT INTO sales (cashier_id, total) VALUES ('$cashier_id', '$total')");
    $sale_id = $conn->insert_id;

    // Insert sale items
    foreach ($_POST['product_id'] as $key => $product_id) {
        $qty = $_POST['quantity'][$key];

        $product = $conn->query("SELECT price, stock FROM products WHERE id=$product_id")->fetch_assoc();
        $price = $product['price'];
        $subtotal = $price * $qty;

        // Check stock
        if ($qty > $product['stock']) {
            die("Error: Not enough stock for product ID $product_id");
        }

        $conn->query("INSERT INTO sale_items (sale_id, product_id, quantity, price, subtotal) 
                      VALUES ('$sale_id', '$product_id', '$qty', '$price', '$subtotal')");

        // Reduce stock
        $conn->query("UPDATE products SET stock = stock - $qty WHERE id=$product_id");
    }

    header("Location: ../pos.php?success=1");
    exit;
}
?>
