<?php
session_start();
include("includes/db.php");
include("includes/header.php");

// Only admin can access
if ($_SESSION['role'] !== 'admin') {
    header("Location: dashboard.php");
    exit;
}

// Fetch all cashiers
$result = $conn->query("SELECT * FROM users WHERE role='cashier' ORDER BY id DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3>Manage Cashiers</h3>
  <a href="user_add.php" class="btn btn-success">+ Add Cashier</a>
</div>

<table class="table table-bordered table-striped shadow-sm">
  <thead class="table-dark">
    <tr>
      <th>ID</th>
      <th>Name</th>
      <th>Username</th>
      <th>Email</th>
      <th>Created At</th>
      <th>Actions</th>
    </tr>
  </thead>
  <tbody>
    <?php while ($row = $result->fetch_assoc()) { ?>
      <tr>
        <td><?= $row['id']; ?></td>
        <td><?= $row['name']; ?></td>
        <td><?= $row['username']; ?></td>
        <td><?= $row['email']; ?></td>
        <td><?= $row['created_at']; ?></td>
        <td>
          <a href="user_edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-primary">Edit</a>
          <a href="actions/cashier_delete.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-danger" onclick="return confirm('Are you sure?');">Delete</a>
        </td>
      </tr>
    <?php } ?>
  </tbody>
</table>

<?php include("includes/footer.php"); ?>
