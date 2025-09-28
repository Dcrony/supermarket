<?php 
include("includes/header.php"); 
include("includes/db.php");

// Ensure user is logged in
if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = $_SESSION['user_id'];
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();
?>

<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-primary text-white">
      <h5 class="mb-0"><i class="bi bi-person-lines-fill me-2"></i> My Profile</h5>
    </div>
    <div class="card-body">
      <div class="row align-items-center">
        
        <!-- Profile Picture -->
        <div class="col-md-4 text-center">
          <?php if (!empty($user['profile_pic'])): ?>
            <img src="uploads/profiles/<?= htmlspecialchars($user['profile_pic']); ?>" 
                 alt="Profile Picture" 
                 class="rounded-circle img-fluid border shadow-sm" 
                 style="width: 150px; height: 150px; object-fit: cover;">
          <?php else: ?>
            <i class="bi bi-person-circle display-1 text-secondary"></i>
          <?php endif; ?>
          <h5 class="mt-3"><?= htmlspecialchars($user['name'] ?? 'User'); ?></h5>
          <p class="text-muted mb-0"><?= ucfirst($user['role'] ?? 'cashier'); ?></p>
        </div>

        <!-- Profile Info -->
        <div class="col-md-8">
          <table class="table table-borderless">
            <tr>
              <th>Email:</th>
              <td><?= htmlspecialchars($user['email'] ?? 'N/A'); ?></td>
            </tr>
            <tr>
              <th>Username:</th>
              <td><?= htmlspecialchars($user['username'] ?? 'N/A'); ?></td>
            </tr>
            <tr>
              <th>Phone:</th>
              <td><?= htmlspecialchars($user['phone'] ?? 'N/A'); ?></td>
            </tr>
            <tr>
              <th>Address:</th>
              <td><?= htmlspecialchars($user['address'] ?? 'N/A'); ?></td>
            </tr>
            <tr>
              <th>Joined:</th>
              <td><?= date("d M Y", strtotime($user['created_at'] ?? 'now')); ?></td>
            </tr>
          </table>
          
          <!-- Action Buttons -->
          <a href="settings.php" class="btn btn-warning mt-3 me-2">
            <i class="bi bi-gear me-1"></i> Edit Profile
          </a>
          <a href="logout.php" class="btn btn-outline-danger mt-3">
            <i class="bi bi-box-arrow-right me-1"></i> Logout
          </a>
        </div>
      </div>
    </div>
  </div>
</div>

<?php include("includes/footer.php"); ?>
