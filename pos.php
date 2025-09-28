<?php
session_start();
include("includes/header.php");

include("includes/db.php");

// Fetch products
$products = $conn->query("SELECT * FROM products ORDER BY name ASC");

// Initialize cart if not exists
if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

// Add to cart
if (isset($_POST['add_to_cart'])) {
    $id = $_POST['product_id'];
    $qty = $_POST['quantity'];

    // Fetch product details
    $product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

    if ($product && $qty > 0) {
        $_SESSION['cart'][] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $qty
        ];
    }
}

// Remove from cart
if (isset($_GET['remove'])) {
    $index = $_GET['remove'];
    if (isset($_SESSION['cart'][$index])) {
        unset($_SESSION['cart'][$index]);
        $_SESSION['cart'] = array_values($_SESSION['cart']); // reindex array
    }
}

// Checkout (Save Sale)
// Checkout (Save Sale)
if (isset($_POST['checkout']) && !empty($_SESSION['cart'])) {
    $total = 0;
    foreach ($_SESSION['cart'] as $item) {
        $total += $item['price'] * $item['quantity'];
    }

    // Save sale with logged-in cashier
    $user_id = $_SESSION['user_id'] ?? null;
    $conn->query("INSERT INTO sales (total, user_id) VALUES ($total, " . ($user_id ? $user_id : "NULL") . ")");
    $sale_id = $conn->insert_id;

    // Insert sale items
    foreach ($_SESSION['cart'] as $item) {
        $pid = $item['id'];
        $qty = $item['quantity'];
        $price = $item['price'];

        $conn->query("INSERT INTO sale_items (sale_id, product_id, quantity, price) 
                      VALUES ($sale_id, $pid, $qty, $price)");

        // Reduce stock
        $conn->query("UPDATE products SET stock = stock - $qty WHERE id=$pid");
    }

    // Save sale_id for receipt
    $_SESSION['last_sale_id'] = $sale_id;

    // Clear cart
    $_SESSION['cart'] = [];

    // Redirect to receipt page
    header("Location: receipt.php?id=" . $sale_id);
    exit;
}
$success = isset($_GET['success']) ? $_GET['success'] : '';

?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>POS - Supermarket</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="">
  <h2>Point of Sales (POS)</h2>

  <?php if (!empty($success)) echo "<div class='alert alert-success'>$success</div>"; ?>

  <!-- Add to Cart -->
  <form method="POST" class="row g-3 mb-4 mt-4">
    <div class=" col-md-6">
      <label class="form-label">Product</label>
      <select name="product_id" class="form-control" required>
        <option value="">-- Select Product --</option>
        <?php 
        $products->data_seek(0);
        while ($row = $products->fetch_assoc()) { ?>
          <option value="<?= $row['id'] ?>"><?= $row['name'] ?> (₦<?= $row['price'] ?>)</option>
        <?php } ?>
      </select>
    </div>
    <div class="col-md-3">
      <label class="form-label">Quantity</label>
      <input type="number" name="quantity" class="form-control" min="1" required>
    </div>
    
    <div class="col-md-3 d-flex align-items-end">
      <button type="submit" name="add_to_cart" class="btn btn-primary w-100">Add to Cart</button>
    </div>
  </form>

  <!-- Cart Table -->
  <h4>Cart</h4>
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>#</th>
        <th>Product</th>
        <th>Price</th>
        <th>Qty</th>
        <th>Subtotal</th>
        <th>Action</th>
      </tr>
    </thead>
    <tbody>
      <?php 
      $grand_total = 0;
      foreach ($_SESSION['cart'] as $index => $item) {
        $subtotal = $item['price'] * $item['quantity'];
        $grand_total += $subtotal;
      ?>
        <tr>
          <td><?= $index + 1 ?></td>
          <td><?= $item['name'] ?></td>
          <td>₦<?= number_format($item['price'], 2) ?></td>
          <td><?= $item['quantity'] ?></td>
          <td>₦<?= number_format($subtotal, 2) ?></td>
          <td>
            <a href="?remove=<?= $index ?>" class="btn btn-danger btn-sm">Remove</a>
          </td>
        </tr>
      <?php } ?>
      <tr>
        <td colspan="4" class="text-end"><strong>Total:</strong></td>
        <td colspan="2"><strong>₦<?= number_format($grand_total, 2) ?></strong></td>
      </tr>
    </tbody>
  </table>

  <form method="POST">
    <button type="submit" name="checkout" class="btn btn-success">Checkout</button>
    
  </form>
  <?php if (isset($_SESSION['last_sale_id'])) { ?>
    <a href="receipt.php?id=<?= $_SESSION['last_sale_id']; ?>" target="_blank" class="btn btn-info mt-3">
        🧾 Print Last Receipt
    </a>
<?php } ?>

</body>
</html>

<?php include("includes/footer.php"); ?>