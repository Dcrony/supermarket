<?php
session_start();
include("includes/db.php");

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $username = $_POST['username'];
    $password = md5($_POST['password']);

    $res = $conn->query("SELECT * FROM users WHERE username='$username' AND password='$password'");
    if ($res->num_rows > 0) {
        $user = $res->fetch_assoc();
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        header("Location: dashboard.php");
        exit;
    } else {
        $error = "Invalid username or password.";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Login | Supermarket POS</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      background: linear-gradient(135deg, #0d6efd, #6c63ff);
      height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      margin: 0;
    }
    .login-card {
      background: #fff;
      padding: 40px 30px;
      border-radius: 15px;
      box-shadow: 0 10px 25px rgba(0,0,0,0.1);
      width: 100%;
      max-width: 400px;
      animation: fadeIn 0.6s ease-in-out;
    }
    .login-card h3 {
      font-weight: 700;
      color: #0d6efd;
      text-align: center;
      margin-bottom: 25px;
    }
    .form-label {
      font-weight: 500;
      color: #333;
    }
    .btn-primary {
      background: #0d6efd;
      border: none;
      transition: all 0.3s ease;
    }
    .btn-primary:hover {
      background: #084298;
      transform: scale(1.02);
    }
    .text-danger {
      text-align: center;
      font-size: 0.9rem;
    }
    .brand {
      text-align: center;
      font-weight: bold;
      font-size: 1.2rem;
      color: #444;
      margin-bottom: 10px;
    }
    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }
    footer {
      text-align: center;
      color: #f8f9fa;
      font-size: 0.85rem;
      position: absolute;
      bottom: 15px;
      width: 100%;
    }
  </style>
</head>
<body>

  <div class="login-card">
    <div class="brand">🧾 Supermarket POS</div>
    <h3>Welcome Back</h3>

    <?php if (isset($error)) : ?>
      <div class="alert alert-danger text-center py-2"><?= $error; ?></div>
    <?php endif; ?>

    <form method="POST" novalidate>
      <div class="mb-3">
        <label class="form-label">Username</label>
        <input type="text" name="username" class="form-control form-control-lg" placeholder="Enter your username" required>
      </div>
      <div class="mb-3">
        <label class="form-label">Password</label>
        <input type="password" name="password" class="form-control form-control-lg" placeholder="Enter your password" required>
      </div>
      <div class="d-grid">
        <button type="submit" class="btn btn-primary btn-lg">Login</button>
      </div>
    </form>
  </div>

  <footer>&copy; <?= date("Y"); ?> Supermarket POS — Smart Sales Solution</footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
