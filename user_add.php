<?php
session_start();
include("includes/header.php");
include("includes/db.php");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $name = $_POST['name'];
    $username = $_POST['username'];
    $email = $_POST['email'];
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    $password = md5($_POST['password']);
    $role = $_POST['role'];

    // Handle profile picture upload
    $profile_pic = NULL;
    if (!empty($_FILES['profile_pic']['name'])) {
        $target_dir = "uploads/profiles/";
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }
        $file_name = time() . "_" . basename($_FILES["profile_pic"]["name"]);
        $target_file = $target_dir . $file_name;

        if (move_uploaded_file($_FILES["profile_pic"]["tmp_name"], $target_file)) {
            $profile_pic = $file_name;
        }
    }

    $conn->query("INSERT INTO users (name, username, email, phone, address, password, role, profile_pic) 
                  VALUES ('$name','$username','$email','$phone','$address','$password','$role','$profile_pic')");
    header("Location: users.php");
    exit;
}
?>

<div class="card shadow-sm mx-auto mt-4" style="max-width:700px;">
  <div class="card-body">
    <h4 class="mb-3"><i class="bi bi-person-plus"></i> Add New User</h4>
    <form method="POST" enctype="multipart/form-data">
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
        <label class="form-label">Phone Number</label>
        <input type="text" name="phone" class="form-control">
      </div>
      <div class="mb-3">
        <label class="form-label">Address</label>
        <textarea name="address" class="form-control" rows="2"></textarea>
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
      <div class="mb-3">
        <label class="form-label">Profile Picture</label>
        <input type="file" name="profile_pic" class="form-control" accept="image/*">
      </div>
      <button type="submit" class="btn btn-success"><i class="bi bi-save"></i> Register</button>
    </form>
  </div>
</div>

<?php include("includes/footer.php"); ?>
