<?php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Supermarket POS - Smart Sales Solution</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
  <style>
    body {
      font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
      scroll-behavior: smooth;
    }
    .hero {
      background: linear-gradient(rgba(0,0,0,0.6), rgba(0,0,0,0.6)), url('img/supermarket.jpg') center/cover no-repeat;
      color: white;
      height: 100vh;
      display: flex;
      align-items: center;
      text-align: center;
    }
    .feature-icon {
      font-size: 2.5rem;
      color: #0d6efd;
    }
    .cta-section {
      background: #0d6efd;
      color: white;
      padding: 60px 0;
    }
    .navbar-dark .nav-link {
      color: #f8f9fa !important;
    }
    .navbar-dark .nav-link:hover {
      color: #0d6efd !important;
      background: #fff;
      border-radius: 4px;
      transition: 0.3s;
    }
  </style>
</head>
<body>

  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg navbar-dark bg-dark fixed-top shadow-sm">
    <div class="container">
      <a class="navbar-brand fw-bold" href="#">Supermarket POS</a>
      <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse" id="navbarNav">
        <ul class="navbar-nav ms-auto">
          <li class="nav-item"><a class="nav-link" href="#hero">Home</a></li>
          <li class="nav-item"><a class="nav-link" href="#features">Features</a></li>
          <li class="nav-item"><a class="btn btn-primary ms-2" href="login.php">Login</a></li>
        </ul>
      </div>
    </div>
  </nav>

  <!-- Hero Section -->
  <section class="hero" id="hero">
    <div class="container">
      <h1 class="display-4 fw-bold">Supermarket POS System</h1>
      <p class="lead mb-4">Streamline sales, manage inventory, and boost productivity with our powerful Point of Sale software.</p>
      <a href="login.php" class="btn btn-lg btn-primary">Get Started</a>
    </div>
  </section>

  <!-- Features Section -->
  <section class="container my-5" id="features">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Why Choose Our POS?</h2>
      <p class="text-muted">Built for supermarkets, trusted by cashiers and admins.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4 text-center">
        <div class="feature-icon mb-3">🛒</div>
        <h5>Fast Checkout</h5>
        <p>Reduce queues and process sales quickly with our intuitive interface.</p>
      </div>
      <div class="col-md-4 text-center">
        <div class="feature-icon mb-3">📦</div>
        <h5>Inventory Tracking</h5>
        <p>Stay on top of stock with real-time product updates and alerts.</p>
      </div>
      <div class="col-md-4 text-center">
        <div class="feature-icon mb-3">📊</div>
        <h5>Smart Reports</h5>
        <p>Access detailed sales reports and insights anytime, anywhere.</p>
      </div>
    </div>
  </section>

  <!-- Call-to-Action -->
  <section class="cta-section text-center" id="cta">
    <div class="container">
      <h2 class="fw-bold">Ready to Transform Your Supermarket?</h2>
      <p class="mb-4">Sign in and start managing sales and inventory like a pro.</p>
      <a href="login.php" class="btn btn-light btn-lg">Login to Dashboard</a>
    </div>
  </section>

  <!-- Footer -->
  <footer class="text-center py-4 bg-dark text-light">
    <p>&copy; <?= date("Y"); ?> Supermarket POS. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
