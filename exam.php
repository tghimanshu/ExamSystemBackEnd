<?php require "db/db.php" ?>
<?php require "functions/functions.php" ?>
<?php require "vendor/autoload.php" ?>
<?php

session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}
// checks if exists
$equery = mysqli_query($con, "SELECT * FROM `answers` WHERE student_id = " . $_SESSION['student_id'] . " AND paper_id = " . $_GET['id']);
// creates if it doesn't exist
if (mysqli_num_rows($equery) !== 1) {
    mysqli_query($con, "INSERT INTO `answers` VALUES (null, " . $_SESSION['student_id'] . ", " . $_GET['id'] . ", '', '','0', 0, '')");
}
// selects
$tquery = mysqli_query($con, "SELECT * FROM `answers` WHERE student_id = " . $_SESSION['student_id'] . " AND paper_id = " . $_GET['id']);
$myCurrPaper = mysqli_fetch_assoc($tquery);
if ($myCurrPaper['submitted'] === '1') {
    header("Location: index.php?catergory=completed");
}

$query = mysqli_query($con, "SELECT * FROM `exampaper` WHERE id = " . $_GET['id']);
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
                                            $subjectQuery = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `subject` WHERE `id`=" . $data['subject_id']));
                                            // echo "/admin/uploads/" .($data['exam_type']==1?"Regular":($data['exam_type']==2?"ATKT":"Mock")).$data['name']=='1'?"Internal":($data['name']=='2'?"External":$data["name"]). $subjectQuery['name']
                                            echo "/admin/uploads/" . $date->format('m-d-') . $data['subject_id'] . $data['name'];
                                            ?></div>

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
        <div class="mt-5 mx-2 mx-md-5">
            <div class="row">
                <div class="col-12 col-lg-9 mb-3">
                    <div class="card">
                        <div class="card-body">
                            <div id="questions"></div>
                        </div>
                    </div>
                </div>
                <div class="col-12 col-lg-3">
                    <div class="card">
                        <div class="card-body px-md-5">
                            <div id="tracker" class="row"></div>
                        </div>
                    </div>
                    <div class="card">
                        <div class="card-body px-md-5">
                            <div class="color-infos">
                                <div class="d-flex align-items-center">
                                    <div id="at-${i}" style="cursor:pointer;width: 20px;height: 20px;" class="anstab mb-1 badge bg-success">&nbsp;</div>
                                    <p class="text-white m-0 ms-3">Attempted</p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div id="at-${i}" style="cursor:pointer;width: 20px;height: 20px;" class="anstab mb-1 badge bg-primary">&nbsp;</div>
                                    <p class="text-white m-0 ms-3">Review</p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div id="at-${i}" style="cursor:pointer;width: 20px;height: 20px;" class="anstab mb-1 badge bg-info">&nbsp;</div>
                                    <p class="text-white m-0 ms-3">Attempted and Review</p>
                                </div>
                                <div class="d-flex align-items-center">
                                    <div id="at-${i}" style="cursor:pointer;width: 20px;height: 20px;" class="anstab mb-1 badge bg-light">&nbsp;</div>
                                    <p class="text-white m-0 ms-3">Unattempted</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Camera -->
        <div class="mx-2 mx-md-5 mt-5">
            <video id="cameraStream"></video>
            <img src="" id="testingSS" />
        </div>
    </div>
    <div id="superErrorContainer"></div>
    <!-- <div id="superError"></div> -->
    <!-- SCRIPTS -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="assets/js/bootstrap.min.js"></script>
    <script src="//cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="assets/js/exam.js"></script>
</body>

</html>