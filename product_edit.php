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

<h3>Edit Product</h3>
<form action="actions/product_edit.php" method="POST">
  <input type="hidden" name="id" value="<?= $product['id']; ?>">
  <div class="mb-3">
    <label>Product Name</label>
    <input type="text" name="name" class="form-control" value="<?= $product['name']; ?>" required>
  </div>
  <div class="mb-3">
    <label>Category</label>
    <input type="text" name="category" class="form-control" value="<?= $product['category']; ?>">
  </div>
  <div class="mb-3">
    <label>Price ($)</label>
    <input type="number" step="0.01" name="price" class="form-control" value="<?= $product['price']; ?>" required>
  </div>
  <div class="mb-3">
    <label>Stock</label>
    <input type="number" name="stock" class="form-control" value="<?= $product['stock']; ?>" required>
  </div>
  <button type="submit" class="btn btn-primary">Update</button>
  <a href="products.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include("includes/footer.php"); ?>
