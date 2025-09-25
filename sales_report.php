<?php
session_start();
include("includes/db.php");
include("includes/header.php");

// Default filter: show all
$where = "";

// Check if filter is applied
if (isset($_GET['filter'])) {
    $filter = $_GET['filter'];

    if ($filter == "today") {
        $where = "WHERE DATE(s.created_at) = CURDATE()";
    } elseif ($filter == "week") {
        $where = "WHERE YEARWEEK(s.created_at, 1) = YEARWEEK(CURDATE(), 1)";
    } elseif ($filter == "month") {
        $where = "WHERE MONTH(s.created_at) = MONTH(CURDATE()) AND YEAR(s.created_at) = YEAR(CURDATE())";
    } elseif ($filter == "custom" && !empty($_GET['start_date']) && !empty($_GET['end_date'])) {
        $start = $conn->real_escape_string($_GET['start_date']);
        $end = $conn->real_escape_string($_GET['end_date']);
        $where = "WHERE DATE(s.created_at) BETWEEN '$start' AND '$end'";
    }
}

// Fetch sales
$sales = $conn->query("
    SELECT s.id, s.total, s.created_at, u.name AS cashier 
    FROM sales s 
    LEFT JOIN users u ON s.user_id = u.id 
    $where
    ORDER BY s.created_at DESC
");

// Best-selling products
$best_sellers = $conn->query("
    SELECT p.name, SUM(si.quantity) as total_sold 
    FROM sale_items si 
    JOIN products p ON si.product_id = p.id 
    JOIN sales s ON si.sale_id = s.id 
    $where
    GROUP BY si.product_id 
    ORDER BY total_sold DESC 
    LIMIT 5
");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Sales Report</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
  <style>
    body {
      background-color: #f8f9fa;
    }
    .page-header {
      background: linear-gradient(135deg, #0d6efd, #6610f2);
      color: white;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 30px;
    }
    .filter-box {
      background: white;
      padding: 15px;
      border-radius: 8px;
      box-shadow: 0 2px 5px rgba(0,0,0,0.1);
      margin-bottom: 20px;
    }
    .table thead {
      background-color: #343a40;
      color: #fff;
    }
  </style>
</head>
<body class="container-fluid">

  <!-- PAGE HEADER -->
  <div class="page-header shadow-sm">
    <h2 class="fw-bold mb-0"><i class="bi bi-graph-up me-2"></i> Sales Report</h2>
    <p class="mb-0">View and export transactions with advanced filters</p>
  </div>

  <!-- FILTER FORM -->
  <div class="filter-box">
    <form method="GET" class="row g-2 align-items-end">
      <div class="col-md-3">
        <label class="form-label">Filter</label>
        <select name="filter" class="form-select" onchange="this.form.submit()">
          <option value="">-- Select Filter --</option>
          <option value="today" <?= isset($_GET['filter']) && $_GET['filter']=="today" ? "selected" : "" ?>>Today</option>
          <option value="week" <?= isset($_GET['filter']) && $_GET['filter']=="week" ? "selected" : "" ?>>This Week</option>
          <option value="month" <?= isset($_GET['filter']) && $_GET['filter']=="month" ? "selected" : "" ?>>This Month</option>
          <option value="custom" <?= isset($_GET['filter']) && $_GET['filter']=="custom" ? "selected" : "" ?>>Custom</option>
        </select>
      </div>
      
      <?php if (isset($_GET['filter']) && $_GET['filter']=="custom") { ?>
        <div class="col-md-3">
          <label class="form-label">Start Date</label>
          <input type="date" name="start_date" value="<?= $_GET['start_date'] ?? '' ?>" class="form-control">
        </div>
        <div class="col-md-3">
          <label class="form-label">End Date</label>
          <input type="date" name="end_date" value="<?= $_GET['end_date'] ?? '' ?>" class="form-control">
        </div>
        <div class="col-md-3">
          <button type="submit" class="btn btn-primary w-100"><i class="bi bi-filter-circle me-1"></i> Apply</button>
        </div>
      <?php } ?>
    </form>
  </div>

  <!-- EXPORT BUTTONS -->
  <div class="d-flex gap-2 mb-3">
    <a href="export_pdf.php?filter=<?= $_GET['filter'] ?? '' ?>&start_date=<?= $_GET['start_date'] ?? '' ?>&end_date=<?= $_GET['end_date'] ?? '' ?>" 
       class="btn btn-danger"><i class="bi bi-file-earmark-pdf me-1"></i> Export to PDF</a>
  </div>

  <!-- SALES TABLE -->
  <div class="card shadow-sm mb-4">
    <div class="card-header bg-dark text-white">
      <h5 class="mb-0"><i class="bi bi-list-ul me-1"></i> All Transactions</h5>
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
          <?php while($row = $sales->fetch_assoc()) { ?>
            <tr>
              <td>#<?= $row['id']; ?></td>
              <td><?= $row['cashier'] ?? "Unknown"; ?></td>
              <td><span class="badge bg-success">₦<?= number_format($row['total'], 2); ?></span></td>
              <td><?= date("d M Y, h:i A", strtotime($row['created_at'])); ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

  <!-- BEST SELLERS -->
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0"><i class="bi bi-star-fill me-2"></i> Best Selling Products</h5>
    </div>
    <div class="card-body">
      <table class="table table-bordered align-middle">
        <thead class="table-light">
          <tr>
            <th>Product</th>
            <th>Quantity Sold</th>
          </tr>
        </thead>
        <tbody>
          <?php while($row = $best_sellers->fetch_assoc()) { ?>
            <tr>
              <td><?= $row['name']; ?></td>
              <td><span class="badge bg-info"><?= $row['total_sold']; ?></span></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>

<?php include("includes/footer.php"); ?>
