<?php require "db/db.php" ?>
<?php require "functions/functions.php" ?>
<?php require "vendor/autoload.php" ?>
<?php

session_start();
if (!isset($_SESSION['email'])) {
    header("Location: login.php");
}
$equery = mysqli_query($con, "SELECT * FROM `answers` WHERE student_id = " . $_SESSION['student_id'] . " AND paper_id = " . $_GET['id']);
if (mysqli_num_rows($equery) !== 1) {
    mysqli_query($con, "INSERT INTO `answers` VALUES (null, " . $_SESSION['student_id'] . ", " . $_GET['id'] . ", '', '','0', '')");
}

$tquery = mysqli_query($con, "SELECT * FROM `answers` WHERE student_id = " . $_SESSION['student_id'] . " AND paper_id = " . $_GET['id']);
$myCurrPaper = mysqli_fetch_assoc($tquery);
if ($myCurrPaper['submitted'] === '1') {
    header("Location: index.php");
}

$query = mysqli_query($con, "SELECT * FROM `exampaper` WHERE ID = " . $_GET['id']);
$data = mysqli_fetch_assoc($query);
echo "<script>localStorage.removeItem('timeElapsed')</script>";

?>
<!-- PRAJAKTA -->
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>SARAF EXAM SYSTEM</title>
    <!-- BOOTSTRAP -->
    <link rel="stylesheet" href="assets/css/bootstrap.min.css" />
    <!-- CUSTOM STYLES -->
    <link rel="stylesheet" href="assets/css/style.css" />
    <link rel="stylesheet" href="assets/css/exam.css" />
</head>

<body>
    <div class="app">
        <div class="d-none" id="questions_fetched">
            <?php
            print_r(json_encode(urldecode($data['Questions'])));
            ?>
        </div>
        <div class="d-none" id="student_id">
            <?php
            print_r($_SESSION['student_id']);
            ?>
        </div>
        <div class="d-none" id="paper_id">
            <?php
            print_r($_GET['id']);
            ?>
        </div>
        <div class="d-none" id="timeElapsed"><?php echo (trim($myCurrPaper['timeElapsed'])); ?></div>
        <div class="d-none" id="answers"><?php echo urldecode($myCurrPaper['answers']); ?></div>
        <div class="d-none" id="imagesDir"><?php
                                            $date = new DateTime($data['date']);
                                            echo "/admin/uploads/" . $date->format('m-d-') . $data['Class'] . $data['Subject'] ?></div>
        <!-- NAVBAR -->
        <!-- questions & tracker  -->
        <nav class="navbar bg-primary navbar-dark py-2">
            <div class="container d-flex justify-content-between align-items-center">
                <h2>
                    <a class="navbar-brand">Dashboard</a>
                </h2>
                <div class="countdown"></div>
            </div>
        </nav>
        <div class="mt-5 mx-5">
            <div class="row">
                <div class="col-12 col-lg-9">
                    <div class="card">
                        <div class="card-body">
                            <div id="questions"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="card">
                        <div class="card-body px-5">
                            <div id="tracker" class="row"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Camera -->
        <div class="mx-5 mt-5">
            <video id="cameraStream"></video>
            <img src="" id="testingSS" />
        </div>
    </div>
    <div id="superErrorContainer"></div>
    <!-- <div id="superError"></div> -->
    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="assets/js/exam.js"></script>
</body>

</html>