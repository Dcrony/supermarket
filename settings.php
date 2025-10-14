<?php 
include("includes/header.php"); 
include("includes/db.php");

// Example: fetch logged-in user data
$user_id = $_SESSION['user_id'] ?? 1; 
$user = $conn->query("SELECT * FROM users WHERE id = $user_id")->fetch_assoc();

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $username = $_POST['username'];

    $conn->query("UPDATE users SET name='$name', email='$email', username='$username' WHERE id=$user_id");

    $_SESSION['username'] = $username; // update session
    echo "<script>alert('Profile updated successfully!'); window.location='settings.php';</script>";
}
?>

<div class="container mt-4">
  <div class="card shadow-sm">
    <div class="card-header bg-warning text-dark">
      <h5 class="mb-0"><i class="bi bi-gear me-2"></i> Settings</h5>
    </div>
    <div class="card-body">
      <form method="POST">
        <div class="mb-3">
          <label class="form-label">Full Name</label>
          <input type="text" name="name" class="form-control" value="<?= $user['name'] ?? ''; ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email Address</label>
          <input type="email" name="email" class="form-control" value="<?= $user['email'] ?? ''; ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" class="form-control" value="<?= $user['username'] ?? ''; ?>" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Password <small class="text-muted">(leave blank to keep current)</small></label>
          <input type="password" name="password" class="form-control">
        </div>
        <button type="submit" class="btn btn-success"><i class="bi bi-save me-1"></i> Save Changes</button>
      </form>
    </div>
  </div>
</div>

<?php include("includes/footer.php"); ?>
