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
      background: #f9f9f9;
    }

    /* Navbar*/
    
    .navbar {
      transition: background 0.3s ease;
    }
    .navbar.scrolled {
      background: rgba(0,0,0,0.9) !important;
    }

    /* Hero */
    .hero {
      background: linear-gradient(rgba(0,0,0,0.55), rgba(0,0,0,0.55)), url('assets/img/down.jpg') center/cover no-repeat;
      color: white;
      height: 100vh;
      display: flex;
      align-items: center;
      text-align: center;
      animation: fadeIn 1.2s ease-in-out;
    }
    .hero h1 {
      font-size: 3rem;
      animation: slideUp 1s ease-in-out;
    }
    .hero p {
      animation: fadeIn 2s ease-in-out;
    }

    /* Features */
    .feature-card {
      background: white;
      border-radius: 10px;
      padding: 25px;
      box-shadow: 0 4px 10px rgba(0,0,0,0.08);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }
    .feature-card:hover {
      transform: translateY(-5px);
      box-shadow: 0 6px 16px rgba(0,0,0,0.12);
    }
    .feature-icon {
      font-size: 2.5rem;
      color: #0d6efd;
      margin-bottom: 15px;
      animation: popIn 1.5s ease;
    }

    /* CTA */
    .cta-section {
      background: #0d6efd;
      color: white;
      padding: 80px 0;
      animation: fadeInUp 1.2s ease-in-out;
    }

    /* Animations */
    @keyframes fadeIn {
      from {opacity: 0;}
      to {opacity: 1;}
    }
    @keyframes slideUp {
      from {transform: translateY(40px); opacity: 0;}
      to {transform: translateY(0); opacity: 1;}
    }
    @keyframes fadeInUp {
      from {opacity: 0; transform: translateY(30px);}
      to {opacity: 1; transform: translateY(0);}
    }
    @keyframes popIn {
      0% {transform: scale(0.7); opacity: 0;}
      100% {transform: scale(1); opacity: 1;}
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
      <h1 class="fw-bold">Supermarket POS System</h1>
      <p class="lead mb-4">Streamline sales, manage inventory, and boost productivity with our powerful Point of Sale software.</p>
      <a href="login.php" class="btn btn-lg btn-primary shadow">Get Started</a>
    </div>
  </section>

  <!-- Features Section -->
  <section class="container my-5" id="features">
    <div class="text-center mb-5">
      <h2 class="fw-bold">Why Choose Our POS?</h2>
      <p class="text-muted">Designed for supermarkets, trusted by cashiers and admins.</p>
    </div>
    <div class="row g-4">
      <div class="col-md-4">
        <div class="feature-card text-center">
          <div class="feature-icon">🛒</div>
          <h5>Fast Checkout</h5>
          <p>Process sales quickly and reduce queues with our simple, intuitive interface.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card text-center">
          <div class="feature-icon">📦</div>
          <h5>Inventory Tracking</h5>
          <p>Monitor stock levels in real-time and get alerts when products run low.</p>
        </div>
      </div>
      <div class="col-md-4">
        <div class="feature-card text-center">
          <div class="feature-icon">📊</div>
          <h5>Smart Reports</h5>
          <p>Access detailed sales reports and insights anytime to grow your business.</p>
        </div>
      </div>
    </div>
  </section>

  <!-- Call-to-Action -->
  <section class="cta-section text-center" id="cta">
    <div class="container">
      <h2 class="fw-bold">Ready to Transform Your Supermarket?</h2>
      <p class="mb-4">Sign in and start managing sales and inventory like a pro.</p>
      <a href="login.php" class="btn btn-light btn-lg shadow">Login to Dashboard</a>
    </div>
  </section>

  <!-- Footer -->
  <footer class="text-center py-4 bg-dark text-light">
    <p>&copy; <?= date("Y"); ?> Supermarket POS. All rights reserved.</p>
  </footer>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
  <script>
    // Navbar background change on scroll
    window.addEventListener('scroll', function() {
      const nav = document.querySelector('.navbar');
      nav.classList.toggle('scrolled', window.scrollY > 50);
    });
  </script>
</body>
</html>
