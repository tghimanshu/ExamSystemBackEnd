<?php require "../db/db.php" ?>
<?php require "../functions/functions.php" ?>
<?php require "../vendor/autoload.php" ?>

<?php

if (isset($_GET['mail'])) {
  $mquery = mysqli_query($con, "SELECT * FROM `student`;");
  while ($value = mysqli_fetch_assoc($mquery)) {
    $name = $value['name'];
    $rollno = $value['rollno'];
    $email = $value['email'];
    $phone = $value['phone'];
    $pass = $value['pwd'];
    sendMail($email, "SARAF COLLEGE - ACCOUNT CREDENTIALS", "
		<h1><center>Saraf College presents</center></h1>
		<h4>Your account credentials for your upcoming examination</h4>
		<b>Username/Email</b>: $email<br />
		<b>Password</b>: $pass<br />
		<br />
		<b>Login Link</b>: <a href='http://localhost/login.php?email=$email&pass=$pass'>Click here</a>
		<br />
		");
  }
}

?>

<?php
session_start();
if (!isset($_SESSION['username'])) {
  header("Location: login.php");
}
?>
<?php

if (isset($_GET['examId'])) {
  $headerTitle = "Students Info | Admin";
} else if (isset($_GET['answerId'])) {
  $headerTitle = "Student | Admin";
} else {
  $headerTitle = "Active Papers | Admin";
}

?>

<?php

if (isset($_GET['resumesid'])) {
  $sid = $_GET['resumesid'];
  $pid = $_GET['resumepid'];
  $resumetestquery = mysqli_query($con, "UPDATE `answers` SET `submitted` = 0, `attempts` = 0 WHERE `student_id` = $sid AND `paper_id` = $pid;") or die(mysqli_error($con));
  if ($resumetestquery) {
    header("Location: index.php?examId=$pid");
  }
}

?>

<?php

if (isset($_GET['allowLoginId'])) {
  $pid = $_GET['pid'];
  $allowLoginId = $_GET['allowLoginId'];
  $resumetestquery = mysqli_query($con, "UPDATE `student` SET `isLoggedIn` = 0 WHERE `id` = $allowLoginId;") or die(mysqli_error($con));
  if ($resumetestquery) {
    header("Location: index.php?examId=$pid");
  }
}

?>

<?php include "../includes/header.php" ?>

<div class="app">
  <?php include("../includes/navbar-admin.php") ?>

  <div id="main"></div>
  <div class="d-none"></div>

  <script>
    $(document).ready(function() {
      function getStudentData(id) {
        $.ajax({
          method: "GET",
          url: "api.php?examId=" + id,
          success: function(data) {
            $('#main').html(data);
          }
        })
      }

      function getExamPapers() {
        $.ajax({
          method: "GET",
          url: "api.php",
          success: function(data) {
            $('#main').html(data);
          }
        })
      }

      function getAnswers(id) {
        $.ajax({
          method: "GET",
          url: "api.php?answerId=" + id,
          success: function(data) {
            $('#main').html(data);
          }
        })
      }

      const urlSearchParams = new URLSearchParams(window.location.search);
      const params = Object.fromEntries(urlSearchParams.entries());
      if (urlSearchParams.get("answerId")) {
        getAnswers(urlSearchParams.get("answerId"));
        setInterval(function() {
          getAnswers(urlSearchParams.get("answerId"))
        }, 1000);
      } else if (urlSearchParams.get("examId")) {
        getStudentData(urlSearchParams.get("examId"));
        setInterval(function() {
          getStudentData(urlSearchParams.get("examId"))
        }, 1000);
      } else {
        getExamPapers();
        setInterval(function() {
          getExamPapers()
        }, 1000);
      }
    });
  </script>

  <?php include "../includes/footer.php" ?>