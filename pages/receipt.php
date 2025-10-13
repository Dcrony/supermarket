<?php
session_start();
include("includes/db.php");

if (!isset($_GET['id'])) {
    die("No sale ID provided.");
}

$sale_id = intval($_GET['id']);

// Fetch sale
$sale = $conn->query("SELECT s.*, u.username AS cashier 
                      FROM sales s 
                      LEFT JOIN users u ON s.user_id = u.id 
                      WHERE s.id=$sale_id")->fetch_assoc();

// Fetch sale items
$items = $conn->query("SELECT si.*, p.name 
                       FROM sale_items si 
                       JOIN products p ON si.product_id = p.id 
                       WHERE si.sale_id=$sale_id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Receipt - Sale #<?= $sale['id']; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body { background:#f8f9fa; }
    .receipt-container {
      max-width: 600px;
      margin: 30px auto;
      background: white;
      padding: 20px;
      border-radius: 8px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    .receipt-header {
      text-align: center;
      margin-bottom: 20px;
    }
    .receipt-header h2 {
      font-weight: bold;
      margin: 0;
    }
    .table th, .table td { text-align: center; }
    @media print {
      .no-print { display: none; }
      body { background: white; }
      .receipt-container { box-shadow: none; border: none; }
    }
  </style>
</head>
<body>
  <div class="receipt-container">
    <div class="receipt-header">
      <h2>🛒 Supermarket Receipt</h2>
      <p>Sale ID: TXN<?= str_pad($sale['id'], 5, "0", STR_PAD_LEFT); ?></p>
      <p>Date: <?= $sale['created_at']; ?></p>
      <p>Sold By: <?= $sale['cashier'] ?? 'Unknown'; ?></p>
    </div>

    <table class="table table-bordered">
      <thead class="table-light">
        <tr>
          <th>Product</th>
          <th>Price</th>
          <th>Qty</th>
          <th>Subtotal</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $total = 0;
        while ($row = $items->fetch_assoc()) { 
          $subtotal = $row['price'] * $row['quantity'];
          $total += $subtotal;
        ?>
        <tr>
          <td><?= $row['name']; ?></td>
          <td>₦<?= number_format($row['price'], 2); ?></td>
          <td><?= $row['quantity']; ?></td>
          <td>₦<?= number_format($subtotal, 2); ?></td>
        </tr>
        <?php } ?>
        <tr>
          <th colspan="3" class="text-end">Total</th>
          <th>₦<?= number_format($total, 2); ?></th>
        </tr>
      </tbody>
    </table>

    <p class="text-center mt-4">✅ Thank you for shopping with us!</p>

    <div class="text-center no-print mt-3">
      <button onclick="window.print()" class="btn btn-primary">🖨 Print Receipt</button>
      <a href="pos.php" class="btn btn-secondary">← Back to POS</a>
    </div>
  </div>
</body>
</html>
