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
  <h3><i class="bi bi-people-fill me-2"></i> Manage Cashiers</h3>
  <a href="user_add.php" class="btn btn-success"><i class="bi bi-plus-circle me-1"></i> Add Cashier</a>
</div>

<div class="table-responsive shadow-sm">
  <table class="table table-bordered table-striped align-middle">
    <thead class="table-dark">
      <tr>
        <th>ID</th>
        <th>Profile</th>
        <th>Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Address</th>
        <th>Created At</th>
        <th>Actions</th>
      </tr>
    </thead>
    <tbody>
      <?php while ($row = $result->fetch_assoc()) { ?>
        <tr>
          <td><?= $row['id']; ?></td>
          <td class="text-center">
            <?php if (!empty($row['profile_pic'])) { ?>
              <img src="uploads/profiles/<?= $row['profile_pic']; ?>" alt="profile" class="rounded-circle" width="40" height="40">
            <?php } else { ?>
              <i class="bi bi-person-circle fs-3 text-secondary"></i>
            <?php } ?>
          </td>
          <td><?= htmlspecialchars($row['name']); ?></td>
          <td><?= htmlspecialchars($row['username']); ?></td>
          <td><?= htmlspecialchars($row['email']); ?></td>
          <td><?= htmlspecialchars($row['phone'] ?? 'N/A'); ?></td>
          <td><?= htmlspecialchars($row['address'] ?? 'N/A'); ?></td>
          <td><?= date("d M Y", strtotime($row['created_at'])); ?></td>
          <td>
            <a href="user_edit.php?id=<?= $row['id']; ?>" class="btn btn-sm btn-primary">
              <i class="bi bi-pencil-square"></i> Edit
            </a>
            <a href="actions/cashier_delete.php?id=<?= $row['id']; ?>" 
               class="btn btn-sm btn-danger" 
               onclick="return confirm('Are you sure you want to delete this cashier?');">
              <i class="bi bi-trash"></i> Delete
            </a>
          </td>
        </tr>
      <?php } ?>
    </tbody>
  </table>
</div>

<?php include("includes/footer.php"); ?>
