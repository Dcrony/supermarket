<?php
session_start();
include("includes/header.php");
include("includes/db.php");

// Fetch products
$products = $conn->query("SELECT * FROM products ORDER BY name ASC");

// Initialize cart
if (!isset($_SESSION['cart'])) {
  $_SESSION['cart'] = [];
}

// 🛒 ADD TO CART
if (isset($_POST['add_to_cart'])) {
  $id = $_POST['product_id'];
  $qty = $_POST['quantity'];
  $product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();

  if ($product && $qty > 0) {
    $found = false;
    foreach ($_SESSION['cart'] as &$cartItem) {
      if ($cartItem['id'] == $id) {
        $cartItem['quantity'] += $qty;
        $found = true;
        break;
      }
    }
    if (!$found) {
      $_SESSION['cart'][] = [
        'id' => $product['id'],
        'name' => $product['name'],
        'price' => $product['price'],
        'quantity' => $qty
      ];
    }
  }
}

// ✏️ UPDATE CART
if (isset($_POST['update_cart'])) {
  foreach ($_POST['quantities'] as $index => $newQty) {
    if ($newQty > 0) {
      $_SESSION['cart'][$index]['quantity'] = $newQty;
    } else {
      unset($_SESSION['cart'][$index]);
    }
  }
  $_SESSION['cart'] = array_values($_SESSION['cart']);
}

// ❌ REMOVE ITEM
if (isset($_POST['remove_item'])) {
  $index = $_POST['remove_item'];
  if (isset($_SESSION['cart'][$index])) {
    unset($_SESSION['cart'][$index]);
    $_SESSION['cart'] = array_values($_SESSION['cart']);
  }
}

// 💳 CHECKOUT
if (isset($_POST['checkout']) && !empty($_SESSION['cart'])) {
  $total = 0;
  foreach ($_SESSION['cart'] as $item) {
    $total += $item['price'] * $item['quantity'];
  }

  $user_id = $_SESSION['user_id'] ?? null;
  $conn->query("INSERT INTO sales (total, user_id) VALUES ($total, " . ($user_id ? $user_id : "NULL") . ")");
  $sale_id = $conn->insert_id;

  foreach ($_SESSION['cart'] as $item) {
    $pid = $item['id'];
    $qty = $item['quantity'];
    $price = $item['price'];
    $conn->query("INSERT INTO sale_items (sale_id, product_id, quantity, price) VALUES ($sale_id, $pid, $qty, $price)");
    $conn->query("UPDATE products SET stock = stock - $qty WHERE id=$pid");
  }

  $_SESSION['last_sale_id'] = $sale_id;
  $_SESSION['cart'] = [];

  header("Location: receipt.php?id=" . $sale_id);
  exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>POS - Supermarket</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="container mt-4">
  <h2>Point of Sales (POS)</h2>

  <!-- Add to Cart -->
  <form method="POST" class="row g-3 mb-4 mt-4">
    <div class="col-md-6">
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
  <form method="POST" id="cartForm">
    <table class="table table-bordered align-middle">
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
            <td><?= htmlspecialchars($item['name']); ?></td>
            <td>₦<?= number_format($item['price'], 2) ?></td>
            <td>
              <input type="number" name="quantities[<?= $index ?>]" value="<?= $item['quantity'] ?>"
                     min="1" class="form-control qty-input" style="width:80px;">
            </td>
            <td>₦<?= number_format($subtotal, 2) ?></td>
            <td>
              <button type="submit" name="remove_item" value="<?= $index ?>" class="btn btn-danger btn-sm">
                Remove
              </button>
            </td>
          </tr>
        <?php } ?>
        <tr>
          <td colspan="4" class="text-end"><strong>Total:</strong></td>
          <td colspan="2"><strong>₦<?= number_format($grand_total, 2) ?></strong></td>
        </tr>
      </tbody>
    </table>

    <div class="mt-3">
      <button type="submit" name="checkout" class="btn btn-success">Checkout</button>
      <button type="submit" name="update_cart" id="updateBtn" class="btn btn-warning" style="display:none;">
        Update Cart
      </button>
    </div>
  </form>

  <script>
    // Show Update button only when quantity changes
    document.querySelectorAll(".qty-input").forEach(input => {
      input.addEventListener("change", () => {
        document.getElementById("updateBtn").style.display = "inline-block";
      });
    });
  </script>

  <?php if (isset($_SESSION['last_sale_id'])) { ?>
    <a href="receipt.php?id=<?= $_SESSION['last_sale_id']; ?>" target="_blank" class="btn btn-info mt-3">
      🧾 Print Last Receipt
    </a>
  <?php } ?>
</body>
</html>

<?php include("includes/footer.php"); ?>
