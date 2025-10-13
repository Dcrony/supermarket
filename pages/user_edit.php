<?php 
session_start();
include("includes/db.php");
include("includes/header.php");

$id = $_GET['id'];
$cashier = $conn->query("SELECT * FROM users WHERE id=$id")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $role = $_POST['role'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];

    // Handle profile picture
    $profile_pic = $cashier['profile_pic']; // keep old picture by default
    if (!empty($_FILES['profile_pic']['name'])) {
        $targetDir = "uploads/";
        if (!is_dir($targetDir)) {
            mkdir($targetDir, 0777, true);
        }
        $filename = time() . "_" . basename($_FILES['profile_pic']['name']);
        $targetFile = $targetDir . $filename;

        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $targetFile)) {
            $profile_pic = $filename;
        }
    }

    // If password provided, update it
    if (!empty($_POST['password'])) {
        $password = md5($_POST['password']);
        $stmt = $conn->prepare("UPDATE users SET name=?, username=?, email=?, password=?, role=?, phone=?, address=?, profile_pic=? WHERE id=?");
        $stmt->bind_param("ssssssssi", $name, $username, $email, $password, $role, $phone, $address, $profile_pic, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name=?, username=?, email=?, role=?, phone=?, address=?, profile_pic=? WHERE id=?");
        $stmt->bind_param("sssssssi", $name, $username, $email, $role, $phone, $address, $profile_pic, $id);
    }

    if ($stmt->execute()) {
        header("Location: users.php?updated=1");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}
?>

<div class="container mt-4">
  <div class="card shadow-sm mx-auto" style="max-width:600px;">
    <div class="card-header bg-primary text-white">
      <h4 class="mb-0"><i class="bi bi-pencil-square me-2"></i> Edit User</h4>
    </div>
    <div class="card-body">
      <form method="POST" enctype="multipart/form-data">
        <div class="mb-3 text-center">
          <?php if (!empty($cashier['profile_pic'])) { ?>
            <img src="uploads/profiles/<?= $cashier['profile_pic']; ?>" alt="profile" class="rounded-circle mb-2" width="100" height="100">
          <?php } else { ?>
            <i class="bi bi-person-circle fs-1 text-secondary"></i>
          <?php } ?>
          <div>
            <input type="file" name="profile_pic" class="form-control mt-2">
          </div>
        </div>
        <div class="mb-3">
          <label class="form-label">Name</label>
          <input type="text" name="name" value="<?= $cashier['name']; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Username</label>
          <input type="text" name="username" value="<?= $cashier['username']; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Email</label>
          <input type="email" name="email" value="<?= $cashier['email']; ?>" class="form-control" required>
        </div>
        <div class="mb-3">
          <label class="form-label">Phone</label>
          <input type="text" name="phone" value="<?= $cashier['phone'] ?? ''; ?>" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Address</label>
          <textarea name="address" class="form-control"><?= $cashier['address'] ?? ''; ?></textarea>
        </div>
        <div class="mb-3">
          <label class="form-label">New Password (leave blank to keep old)</label>
          <input type="password" name="password" class="form-control">
        </div>
        <div class="mb-3">
          <label class="form-label">Role</label>
          <select name="role" class="form-select">
            <option value="cashier" <?= $cashier['role'] == 'cashier' ? 'selected' : '' ?>>Cashier</option>
            <option value="admin" <?= $cashier['role'] == 'admin' ? 'selected' : '' ?>>Admin</option>
          </select>
        </div>
        <button type="submit" class="btn btn-primary"><i class="bi bi-save me-1"></i> Update</button>
        <a href="users.php" class="btn btn-secondary">Cancel</a>
      </form>
    </div>
  </div>
</div>

<?php include("includes/footer.php"); ?>
