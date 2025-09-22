<?php
session_start();
include("includes/db.php");

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
</head>
<body class="container mt-4">
  <h2 class="mb-4">Sales Report</h2>

  <!-- FILTER FORM -->
  <form method="GET" class="mb-4 row g-2">
    <div class="col-auto">
      <select name="filter" class="form-select" onchange="this.form.submit()">
        <option value="">-- Select Filter --</option>
        <option value="today" <?= isset($_GET['filter']) && $_GET['filter']=="today" ? "selected" : "" ?>>Today</option>
        <option value="week" <?= isset($_GET['filter']) && $_GET['filter']=="week" ? "selected" : "" ?>>This Week</option>
        <option value="month" <?= isset($_GET['filter']) && $_GET['filter']=="month" ? "selected" : "" ?>>This Month</option>
        <option value="custom" <?= isset($_GET['filter']) && $_GET['filter']=="custom" ? "selected" : "" ?>>Custom</option>
      </select>
    </div>
    
    <?php if (isset($_GET['filter']) && $_GET['filter']=="custom") { ?>
      <div class="col-auto">
        <input type="date" name="start_date" value="<?= $_GET['start_date'] ?? '' ?>" class="form-control">
      </div>
      <div class="col-auto">
        <input type="date" name="end_date" value="<?= $_GET['end_date'] ?? '' ?>" class="form-control">
      </div>
      <div class="col-auto">
        <button type="submit" class="btn btn-primary">Apply</button>
      </div>
    <?php } ?>
  </form>

  <!-- SALES TABLE -->
  <h4>All Transactions</h4>
  <table class="table table-striped">
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
          <td><?= $row['id']; ?></td>
          <td><?= $row['cashier'] ?? "Unknown"; ?></td>
          <td>₦<?= number_format($row['total'], 2); ?></td>
          <td><?= $row['created_at']; ?></td>
        </tr>
      <?php } ?>

    </tbody>
  </table>
  <div class="mb-3">
  <a href="export_excel.php?filter=<?= $_GET['filter'] ?? '' ?>&start_date=<?= $_GET['start_date'] ?? '' ?>&end_date=<?= $_GET['end_date'] ?? '' ?>" class="btn btn-success">Export to Excel</a>
  <a href="export_pdf.php?filter=<?= $_GET['filter'] ?? '' ?>&start_date=<?= $_GET['start_date'] ?? '' ?>&end_date=<?= $_GET['end_date'] ?? '' ?>" class="btn btn-danger">Export to PDF</a>
</div>


  <!-- BEST SELLERS -->
  <h4 class="mt-5">Best Selling Products</h4>
  <table class="table table-bordered">
    <thead class="table-dark">
      <tr>
        <th>Product</th>
        <th>Quantity Sold</th>
      </tr>
    </thead>
    <tbody>
      <?php while($row = $best_sellers->fetch_assoc()) { ?>
        <tr>
          <td><?= $row['name']; ?></td>
          <td><?= $row['total_sold']; ?></td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</body>
</html>
