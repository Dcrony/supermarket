<?php 
include("includes/header.php"); 
include("includes/db.php");

// Example: fetch logged-in user data
$user_id = $_SESSION['user_id'] ?? 1; // fallback for demo
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
?>

<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i> My Profile</h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-4 text-center">
          <i class="bi bi-person-circle display-1 text-secondary"></i>
          <h5 class="mt-3"><?= $user['name'] ?? 'User'; ?></h5>
          <p class="text-muted"><?= $user['role'] ?? 'Cashier'; ?></p>
        </div>
        <div class="col-md-8">
          <table class="table table-borderless">
            <tr>
              <th>Email:</th>
              <td><?= $user['email'] ?? 'N/A'; ?></td>
            </tr>
            <tr>
              <th>Username:</th>
              <td><?= $user['username'] ?? 'N/A'; ?></td>
            </tr>
            <tr>
              <th>Joined:</th>
              <td><?= date("d M Y", strtotime($user['created_at'] ?? 'now')); ?></td>
            </tr>
          </table>
          <a href="settings.php" class="btn btn-warning mt-3">
            <i class="bi bi-gear me-1"></i> Edit Profile
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include("includes/footer.php"); ?>
