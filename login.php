<?php require "db/db.php" ?>
<?php require "functions/functions.php" ?>
<?php require "vendor/autoload.php" ?>
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
      header("Location: index.php");
    } else {
      $error = "Invalid Email or Password!";
    }
  }
}

?>
<!-- SONAL -->
<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>SARAF EXAM SYSTEM</title>
  <!-- BOOTSTRAP -->
  <link rel="stylesheet" href="../ExamSystemFrontEnd/assets/css/bootstrap.min.css" />
  <!-- CUSTOM STYLES -->
  <link rel="stylesheet" href="assets/css/style.css" />
  <link rel="stylesheet" href="assets/css/student_login.css" />
</head>

<body>
  <div class="app bg-primary" style="height: 100vh">
    <div class="row justify-content-center align-items-center" style="height: 100%">
      <div class="col-3">
        <form action="login.php" method="POST" class="bg-light p-5">
          <h4 class="text-center mb-4">STUDENT LOGIN</h4>
          <?php if (isset($error)) { ?>
            <div class="alert alert-danger"><?php echo $error ?></div>
          <?php } ?>
          <label for="email">Email Address</label>
          <input type="email" required class="form-control mb-2" id="email" name="email" />
          <label for="pwd">Password</label>
          <input type="password" required class="form-control mb-2" id="pwd" name="pwd" />
          <button class="btn btn-success" type="submit" name="submit">
            Submit
          </button>
        </form>
      </div>
    </div>
  </div>
  <!-- SCRIPTS -->
  <script src="../ExamSystemFrontEnd/assets/js/bootstrap.min.js"></script>
</body>

</html>