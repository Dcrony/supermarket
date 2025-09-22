<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
include("includes/header.php");
?>

<h3>Add Product</h3>
<form action="actions/product_add.php" method="POST">
  <div class="mb-3">
    <label>Product Name</label>
    <input type="text" name="name" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Category</label>
    <input type="text" name="category" class="form-control">
  </div>
  <div class="mb-3">
    <label>Price ($)</label>
    <input type="number" step="0.01" name="price" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Stock</label>
    <input type="number" name="stock" class="form-control" required>
  </div>
  <button type="submit" class="btn btn-success">Save</button>
  <a href="products.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include("includes/footer.php"); ?>
