<?php
session_start();
include("includes/db.php");
include("includes/header.php");

$id = $_GET['id'];
$cashier = $conn->query("SELECT * FROM users WHERE id=$id AND role='cashier'")->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];

    if (!empty($_POST['password'])) {
        $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET name=?, username=?, email=?, password=? WHERE id=?");
        $stmt->bind_param("ssssi", $name, $username, $email, $password, $id);
    } else {
        $stmt = $conn->prepare("UPDATE users SET name=?, username=?, email=? WHERE id=?");
        $stmt->bind_param("sssi", $name, $username, $email, $id);
    }

    if ($stmt->execute()) {
        header("Location: cashiers.php?updated=1");
        exit;
    } else {
        echo "<div class='alert alert-danger'>Error: " . $stmt->error . "</div>";
    }
}
?>

<h3>Edit Cashier</h3>
<form method="POST">
  <div class="mb-3">
    <label>Name</label>
    <input type="text" name="name" value="<?= $cashier['name']; ?>" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Username</label>
    <input type="text" name="username" value="<?= $cashier['username']; ?>" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>Email</label>
    <input type="email" name="email" value="<?= $cashier['email']; ?>" class="form-control" required>
  </div>
  <div class="mb-3">
    <label>New Password (leave blank to keep old)</label>
    <input type="password" name="password" class="form-control">
  </div>
  <button type="submit" class="btn btn-primary">Update</button>
  <a href="cashiers.php" class="btn btn-secondary">Cancel</a>
</form>

<?php include("includes/footer.php"); ?>
