<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
include("includes/header.php");
include("includes/db.php");

// Fetch products
$result = $conn->query("SELECT * FROM products ORDER BY id DESC");
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Products</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .page-header {
      background: linear-gradient(135deg, #20c997, #0d6efd);
      color: white;
      padding: 20px;
      border-radius: 10px;
      margin-bottom: 30px;
    }
    .table thead {
      background-color: #343a40;
      color: white;
    }
    .action-btns .btn {
      margin-right: 5px;
    }
    .search-bar {
      max-width: 400px;
    }
  </style>
</head>
<body class="container-fluid">

  <!-- PAGE HEADER -->
  <div class="page-header shadow-sm d-flex justify-content-between align-items-center">
    <div>
      <h2 class="fw-bold mb-0"><i class="bi bi-box-seam me-2"></i> Product Inventory</h2>
      <p class="mb-0">Manage products, stock, and pricing</p>
    </div>
    <a href="product_add.php" class="btn btn-light fw-bold"><i class="bi bi-plus-circle me-1"></i> Add Product</a>
  </div>

  <!-- SEARCH BAR -->
  <div class="d-flex justify-content-between align-items-center mb-3">
    <form method="GET" class="d-flex search-bar">
      <input type="text" name="search" class="form-control me-2" placeholder="Search products...">
      <button class="btn btn-primary"><i class="bi bi-search"></i></button>
    </form>
  </div>

  <!-- PRODUCTS TABLE -->
  <div class="card shadow-sm">
    <div class="card-body p-0">
      <table class="table table-hover align-middle mb-0">
        <thead>
          <tr>
            <th>#ID</th>
            <th><i class="bi bi-tag"></i> Name</th>
            <th><i class="bi bi-grid"></i> Category</th>
            <th><i class="bi bi-cash-stack"></i> Price</th>
            <th><i class="bi bi-box"></i> Stock</th>
            <th><i class="bi bi-gear"></i> Actions</th>
          </tr>
        </thead>
        <tbody>
          <?php while ($row = $result->fetch_assoc()) { ?>
            <tr>
              <td><span class="badge bg-dark">#<?= $row['id']; ?></span></td>
              <td><?= $row['name']; ?></td>
              <td><span class="badge bg-info"><?= $row['category']; ?></span></td>
              <td><strong>₦<?= number_format($row['price'], 2); ?></strong></td>
              <td>
                <?php if ($row['stock'] > 10) { ?>
                  <span class="badge bg-success"><?= $row['stock']; ?> in stock</span>
                <?php } elseif ($row['stock'] > 0) { ?>
                  <span class="badge bg-warning text-dark"><?= $row['stock']; ?> low</span>
                <?php } else { ?>
                  <span class="badge bg-danger">Out of stock</span>
                <?php } ?>
              </td>
              <td class="action-btns">
                <a href="product_edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-primary">
                  <i class="bi bi-pencil-square"></i> Edit
                </a>
                <a href="actions/product_delete.php?id=<?= $row['id']; ?>" 
                   class="btn btn-sm btn-outline-danger" 
                   onclick="return confirm('Are you sure you want to delete this product?');">
                  <i class="bi bi-trash"></i> Delete
                </a>
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    </div>
  </div>

</body>
</html>

<?php include("includes/footer.php"); ?>
