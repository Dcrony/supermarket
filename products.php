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

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Products</h3>
  <a href="product_add.php" class="btn btn-success">+ Add Product</a>
</div>

<table class="table table-bordered table-striped">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Category</th>
      <th>Price</th>
      <th>Stock</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()) { ?>
      <tr>
        <td><?= $row['id']; ?></td>
        <td><?= $row['name']; ?></td>
        <td><?= $row['category']; ?></td>
        <td>$<?= number_format($row['price'], 2); ?></td>
        <td><?= $row['stock']; ?></td>
        <td>
          <a href="product_edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
          <a href="actions/product_delete.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>

<?php include("includes/footer.php"); ?>
