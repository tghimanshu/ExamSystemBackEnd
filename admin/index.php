<?php require "../db/db.php" ?>
<?php require "../functions/functions.php" ?>
<?php require "../vendor/autoload.php" ?>

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
  <nav class="navbar navbar-dark py-2 justify-content-between">
    <div class="container">
      <h2>
        <a class="navbar-brand">Dashboard</a>
      </h2>
      <div>
        <a href="logout.php" class="btn btn-danger">Logout</a>
      </div>
    </div>
  </nav>
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