<?php include("includes/header.php"); ?>
<div class="row justify-content-center">
  <div class="col-md-4">
    <h3 class="text-center">Supermarket Login</h3>
    <form action="actions/login.php" method="POST">
      <div class="mb-3">
        <label>Email</label>
        <input type="email" name="email" class="form-control" required>
      </div>
      <div class="mb-3">
        <label>Password</label>
        <input type="password" name="password" class="form-control" required>
      </div>
      <button type="submit" class="btn btn-dark w-100">Login</button>
    </form>
  </div>
</div>
<?php include("includes/footer.php"); ?>
