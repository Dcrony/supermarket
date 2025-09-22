<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
include("includes/header.php");
include("includes/db.php");

$id = $_GET['id'];
$product = $conn->query("SELECT * FROM products WHERE id=$id")->fetch_assoc();
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Edit Product</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
  <style>
    .form-card {
      max-width: 600px;
      margin: auto;
    }
  </style>
</head>
<body class="container-fluid mt-1">

  <!-- Page Header -->
  <div class="d-flex justify-content-between align-items-center mb-4">
    <h3 class="fw-bold"><i class="bi bi-pencil-square me-2"></i>Edit Product</h3>
    <a href="products.php" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
  </div>

  <!-- Edit Product Form -->
  <div class="card shadow-sm form-card">
    <div class="card-body">
      <form action="actions/product_edit.php" method="POST">
        <input type="hidden" name="id" value="<?= $product['id']; ?>">

        <div class="mb-3">
          <label class="form-label"><i class="bi bi-tag"></i> Product Name</label>
          <input type="text" name="name" class="form-control" value="<?= $product['name']; ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label"><i class="bi bi-grid"></i> Category</label>
          <input type="text" name="category" class="form-control" value="<?= $product['category']; ?>">
        </div>

        <div class="mb-3">
          <label class="form-label"><i class="bi bi-cash-stack"></i> Price ($)</label>
          <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price']; ?>" required>
        </div>

        <div class="mb-3">
          <label class="form-label"><i class="bi bi-box"></i> Stock</label>
          <input type="number" name="stock" class="form-control" value="<?= $product['stock']; ?>" required>
        </div>

        <div class="d-flex justify-content-between">
          <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Update</button>
          <a href="products.php" class="btn btn-danger"><i class="bi bi-x-circle"></i> Cancel</a>
        </div>
      </form>
    </div>
  </div>

</body>
</html>

<?php include("includes/footer.php"); ?>
