<?php 
include("includes/db.php");

// Fetch stats
$total_sales = $conn->query("SELECT COUNT(*) as count FROM sales")->fetch_assoc()['count'];
$total_products = $conn->query("SELECT COUNT(*) as count FROM products")->fetch_assoc()['count'];
$today_revenue = $conn->query("SELECT SUM(total) as total FROM sales WHERE DATE(created_at) = CURDATE()")->fetch_assoc()['total'] ?? 0;

// Best seller
$best_seller = $conn->query("
    SELECT p.name, SUM(si.quantity) as total_sold
    FROM sale_items si
    JOIN products p ON si.product_id = p.id
    GROUP BY si.product_id
    ORDER BY total_sold DESC
    LIMIT 1
")->fetch_assoc();

// Recent sales
$recent_sales = $conn->query("
    SELECT s.id, s.total, s.created_at, u.name 
    FROM sales s
    LEFT JOIN users u ON s.user_id = u.id
    ORDER BY s.created_at DESC
    LIMIT 5
");

// Low stock check (you can adjust the threshold, e.g., 5 items)
$low_stock = $conn->query("SELECT name, stock FROM products WHERE stock < 5 ORDER BY stock ASC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>POS Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .dashboard-header {
      background: linear-gradient(135deg, #0d6efd, #6610f2);
      color: white;
      padding: 30px 20px;
      border-radius: 10px;
      margin-bottom: 30px;
    }
    .card {
      border: none;
      border-radius: 12px;
      transition: transform 0.2s ease-in-out;
    }
    .card:hover {
      transform: translateY(-5px);
    }
    .stat-icon {
      font-size: 30px;
      opacity: 0.8;
    }
    .table thead {
      background-color: #343a40;
      color: #fff;
    }
  </style>
</head>
<body class="container-fluid">
  <?php include("includes/header.php"); ?>

  <!-- Dashboard Header -->
  <div class="dashboard-header text-center shadow-sm">
    <h1 class="fw-bold">Supermarket POS Dashboard</h1>
    <p class="mb-0">Manage sales, products, and reports at a glance</p>
  </div>

  <!-- Low Stock Alert -->
  <?php if ($low_stock->num_rows > 0) { ?>
    <div class="alert alert-warning shadow-sm">
      <h5 class="alert-heading"><i class="bi bi-exclamation-triangle-fill"></i> Low Stock Alert!</h5>
      <ul class="mb-0">
        <?php while ($row = $low_stock->fetch_assoc()) { ?>
          <li><strong><?= $row['name']; ?></strong> has only <span class="badge bg-danger"><?= $row['stock']; ?></span> left in stock.</li>
        <?php } ?>
      </ul>
    </div>
  <?php } ?>

  <!-- Stats Cards -->
  <div class="row g-4">
      <div class="col-md-3">
          <div class="card bg-primary text-white shadow-sm p-3">
              <div class="d-flex justify-content-between align-items-center">
                  <div>
                      <h6>Total Sales</h6>
                      <h3><?= $total_sales; ?></h3>
                  </div>
                  <i class="bi bi-receipt stat-icon"></i>
              </div>
          </div>
      </div>
      <div class="col-md-3">
          <div class="card bg-success text-white shadow-sm p-3">
              <div class="d-flex justify-content-between align-items-center">
                  <div>
                      <h6>Products</h6>
                      <h3><?= $total_products; ?></h3>
                  </div>
                  <i class="bi bi-box-seam stat-icon"></i>
              </div>
          </div>
      </div>
      <div class="col-md-3">
          <div class="card bg-warning text-dark shadow-sm p-3">
              <div class="d-flex justify-content-between align-items-center">
                  <div>
                      <h6>Best Seller</h6>
                      <h5><?= $best_seller['name'] ?? 'N/A'; ?></h5>
                      <small><?= $best_seller['total_sold'] ?? 0; ?> sold</small>
                  </div>
                  <i class="bi bi-star-fill stat-icon"></i>
              </div>
          </div>
      </div>
      <div class="col-md-3">
          <div class="card bg-danger text-white shadow-sm p-3">
              <div class="d-flex justify-content-between align-items-center">
                  <div>
                      <h6>Today’s Revenue</h6>
                      <h3>₦<?= number_format($today_revenue, 2); ?></h3>
                  </div>
                  <i class="bi bi-cash-stack stat-icon"></i>
              </div>
          </div>
      </div>
  </div>

  <!-- Recent Sales -->
  <div class="card mt-5 shadow-sm">
      <div class="card-header bg-dark text-white">
          <h5 class="mb-0">Recent Transactions</h5>
      </div>
      <div class="card-body">
          <table class="table table-hover align-middle">
              <thead>
                  <tr>
                      <th>Sale ID</th>
                      <th>Cashier</th>
                      <th>Total</th>
                      <th>Date</th>
                  </tr>
              </thead>
              <tbody>
                  <?php while($row = $recent_sales->fetch_assoc()) { ?>
                      <tr>
                          <td>#<?= $row['id']; ?></td>
                          <td><?= $row['name'] ?? "Unknown"; ?></td>
                          <td><span class="badge bg-success">₦<?= number_format($row['total'], 2); ?></span></td>
                          <td><?= date("d M Y, h:i A", strtotime($row['created_at'])); ?></td>
                      </tr>
                  <?php } ?>
              </tbody>
          </table>
      </div>
  </div>

</body>
</html>

<?php include("includes/footer.php"); ?>
