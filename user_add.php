<?php
session_start();
include("includes/header.php");
include("includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $password = md5($_POST['password']);
    $role = $_POST['role'];

    $conn->query("INSERT INTO users (name, username, email, password, role) 
                  VALUES ('$name','$username','$email','$password','$role')");
    header("Location: users.php");
    exit;
}
?>

<div class="card shadow-sm mx-auto mt-4" style="max-width:600px;">
  <div class="card-body">
    <h4 class="mb-3"><i class="bi bi-person-plus"></i> Add New Cashier</h4>
    <form method="POST">
      <div class="mb-3">
        <label class="form-label">Full Name</label>
        <input type="text" name="name" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Role</label>
        <select name="role" class="form-select">
          <option value="cashier">Cashier</option>
          <option value="admin">Admin</option>
        </select>
      </div>
      <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Register</button>
    </form>
  </div>
</div>

<?php include("includes/footer.php"); ?>
