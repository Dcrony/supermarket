<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

include("includes/header.php");
include("includes/db.php");

// Fetch products for dropdown
$products = $conn->query("SELECT * FROM products WHERE stock > 0 ORDER BY name ASC");

// Fetch recent sales
$recent_sales = $conn->query("
    SELECT s.id, s.total, s.created_at, u.name AS cashier 
    FROM sales s
    LEFT JOIN users u ON s.user_id = u.id
    ORDER BY s.created_at DESC
    LIMIT 10
");
?>

<div class="container-fluid mt-4">
  <h2 class="mb-4">Point of Sale (POS)</h2>

  <!-- Sales Form -->
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-primary text-white">New Sale</div>
    <div class="card-body">
      <form action="actions/sale_add.php" method="POST" id="saleForm">
        <div class="row g-3 align-items-end">
          <div class="col-md-4">
            <label for="product" class="form-label">Product</label>
            <select name="product_id" id="product" class="form-select" required>
              <option value="">-- Select Product --</option>
              <?php while($row = $products->fetch_assoc()) { ?>
                <option value="<?= $row['id']; ?>">
                  <?= $row['name']; ?> (₦<?= number_format($row['price'], 2); ?>)
                </option>
              <?php } ?>
            </select>
          </div>
          <div class="col-md-2">
            <label for="qty" class="form-label">Quantity</label>
            <input type="number" name="quantity" id="qty" class="form-control" min="1" required>
          </div>
          <div class="col-md-3">
            <label for="customer" class="form-label">Customer (optional)</label>
            <input type="text" name="customer" id="customer" class="form-control">
          </div>
          <div class="col-md-3">
            <button type="submit" class="btn btn-success w-100">Complete Sale</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Recent Sales -->
  <div class="card shadow-sm">
    <div class="card-header bg-dark text-white">Recent Sales</div>
    <div class="card-body">
      <table class="table table-striped table-hover align-middle">
        <thead class="table-light">
          <tr>
            <th>Sale ID</th>
            <th>Cashier</th>
            <th>Customer</th>
            <th>Total</th>
            <th>Date</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $recent_sales->fetch_assoc()) { ?>
            <tr>
              <td>#<?= $row['id']; ?></td>
              <td><?= $row['cashier'] ?? "Unknown"; ?></td>
              <td><?= $row['customer'] ?? "Walk-in"; ?></td>
              <td>₦<?= number_format($row['total'], 2); ?></td>
              <td><?= date("d M Y, h:i A", strtotime($row['created_at'])); ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>
</div>

<?php include("includes/footer.php"); ?>
