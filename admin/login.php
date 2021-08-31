<?php require "../db/db.php" ?>
<?php require "../functions/functions.php" ?>
<?php require "../vendor/autoload.php" ?>

<?php

$headerTitle = "Login | Admin";
$cssFiles = "<link rel='stylesheet' href='../assets/css/student_login.css'/>";

if ((isset($_GET['email']) && $_GET['email'] == "") || (isset($_GET['pass']) && $_GET['pass'] == "")) {
  header("Location: login.php");
}
?>
<?php
session_start();
if (isset($_SESSION['username'])) {
  header("Location: index.php");
}

if (isset($_POST['submit'])) {
  $username = mysqli_escape_string($con, $_POST['username']);
  $pwd = mysqli_escape_string($con, $_POST['pwd']);
  $query = mysqli_query($con, "SELECT * FROM `departments` WHERE username = '$username' AND pwd = '$pwd';");
  if (!$query) {
    die(mysqli_error($con));
  } else {
    if (mysqli_num_rows($query) == 1) {
      $_SESSION['username'] = $username;
      $_SESSION['department_id'] = mysqli_fetch_assoc($query)['id'];
      header("Location: index.php");
    } else {
      $error = "Invalid Email or Password!";
    }
  }
}
?>
<!-- SONAL -->
<?php include "../includes/header.php" ?>
<section class="container-fluid">
  <section class="row justify-content-center">
    <section class="col-12 col-sm-6 col-md-4 row-3">
      <form action="login.php" method="POST" class="form-container">
        <div class="form-group">
          <h3 class="text-center font-weight-bold text-uppercase mb-4">Admin Login</h3>
          <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo $error ?></div>
          <?php } ?>
          <label for="username">User Name</label>
          <input type="text" required class="form-control mb-2" id="username" name="username" value="<?php echo isset($_GET['username']) ? $_GET['username'] : '' ?>" />
          <label for="pwd">Password</label>
          <input type="password" required class="form-control mb-2" id="pwd" name="pwd" value="<?php echo isset($_GET['pass']) ? $_GET['pass'] : '' ?>" />
        </div>
        <button type="submit" class="btn btn-success btn-block mt-2" name="submit">Login</button>
      </form>
    </section>
  </section>
</section>
<!-- SCRIPTS -->
<?php include "../includes/footer.php" ?>