<?php require "../db/db.php" ?>
<?php require "../functions/functions.php" ?>
<?php require "../vendor/autoload.php" ?>

<?php
  session_start();
  if(!isset($_SESSION['email'])){
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
  $resumetestquery = mysqli_query($con, "UPDATE `answers` SET `submitted` = 0 WHERE `student_id` = $sid AND `paper_id` = $pid;") or die(mysqli_error($con));
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