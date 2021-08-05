<?php require "db/db.php" ?>
<?php require "functions/functions.php" ?>
<?php require "vendor/autoload.php" ?>

<?php

$headerTitle = "Login | Student";
$cssFiles = "<link rel='stylesheet' href='assets/css/student_login.css' />";

if ((isset($_GET['email']) && $_GET['email'] == "") || (isset($_GET['pass']) && $_GET['pass'] == "")) {
  header("Location: login.php");
}

?>

<?php
session_start();
if (isset($_SESSION['email'])) {
  header("Location: index.php");
}

if (isset($_POST['submit'])) {
  $email = mysqli_escape_string($con, $_POST['email']);
  $pwd = mysqli_escape_string($con, $_POST['pwd']);
  $query = mysqli_query($con, "SELECT * FROM `student` WHERE email = '$email' AND pwd = '$pwd';");
  if (!$query) {
    die(mysqli_error($con));
  } else {
    if (mysqli_num_rows($query) == 1) {
      $_SESSION['email'] = $email;
      $_SESSION['student_id'] = mysqli_fetch_assoc($query)['id'];
      header("Location: index.php");
    } else {
      $error = "Invalid Email or Password!";
    }
  }
}

?>
<?php include "includes/header.php" ?>

<section class="container-fluid">
  <section class="row justify-content-center">
    <section class="col-12 col-sm-6 col-md-4 row-3">
      <form action="login.php" method="POST" class="form-container">
        <div class="form-group">
          <h3 class="text-center font-weight-bold text-uppercase mb-4">Student Login</h3>
          <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo $error ?></div>
          <?php } ?>
          <label for="email">Email Address</label>
          <input type="email" required class="form-control mb-2" id="email" name="email" value="<?php echo isset($_GET['email'])? $_GET['email']:'' ?>" />
          <label for="pwd">Password</label>
          <input type="password" required class="form-control mb-2" id="pwd" name="pwd" value="<?php echo isset($_GET['pass'])? $_GET['pass']:'' ?>" />
        </div>
        <button type="submit" class="btn btn-success btn-block mt-2" name="submit">Login</button>
      </form>
    </section>
  </section>
</section>

<?php include "includes/footer.php" ?>