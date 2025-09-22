<?php
session_start();
if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}
include("includes/header.php");
include("includes/db.php");

// Only admin can access
if ($_SESSION['role'] !== 'admin') {
    die("Access Denied.");
}

// Fetch users
$result = $conn->query("SELECT * FROM users ORDER BY id DESC");
?>

<div class="d-flex justify-content-between align-items-center mb-3">
  <h3><i class="bi bi-people-fill"></i> Manage Users</h3>
  <a href="user_add.php" class="btn btn-success"><i class="bi bi-person-plus"></i> Add Cashier</a>
</div>

<div class="card shadow-sm">
  <div class="card-body p-0">
    <table class="table table-hover align-middle mb-0">
      <thead class="table-dark">
        <tr>
          <th>#</th>
          <th>Name</th>
          <th>Username</th>
          <th>Email</th>
          <th>Role</th>
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
            <td><span class="badge bg-<?= $row['role']=='admin'?'primary':'info'; ?>"><?= ucfirst($row['role']); ?></span></td>
            <td>
              <a href="user_edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-primary"><i class="bi bi-pencil"></i></a>
              <a href="actions/user_delete.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this user?');"><i class="bi bi-trash"></i></a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
  </div>
</div>

<?php include("includes/footer.php"); ?>
