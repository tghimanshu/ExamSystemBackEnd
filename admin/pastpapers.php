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

    <div id="main">
        <div class="table-responsive mt-5 mx-5">
            <table class="table table-striped table-hover table-bordered blurred-bg">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Subject</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <?php
                $query = mysqli_query($con, "SELECT * FROM `exampaper` WHERE `subject_id` IN (SELECT id FROM `subject` WHERE `class_id` IN (SELECT id FROM `classes` WHERE department_id IN (SELECT id FROM `departments` WHERE id = " . $_SESSION['department_id'] . ")));");
                $srno = 0;
                ?>
                <tbody>
                    <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                        <?php
                        $currTime = new DateTime($timezone = "Asia/Kolkata");
                        $startTime = new DateTime($row['date'], new DateTimeZone("Asia/Kolkata"));
                        $endTime = new DateTime($row['endTime'], new DateTimeZone("Asia/Kolkata"));
                        $startTimeLeft = $currTime->diff($startTime);
                        $endTimeLeft = $currTime->diff($endTime);
                        ?>

                        <tr>
                            <td><?php echo ++$srno; ?></td>
                            <td>
                                <h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject">
                                    <?php echo $row['exam_type'] == 1 ? "Regular - " : ($row['exam_type'] == 2 ? "ATKT - " : "Mock - "); ?>
                                    <?php echo $row['exam_type'] == 3 ? $row['name'] : ($row['name'] == '1' ? "Internal" : "External"); ?>
                                </h5>
                            </td>
                            <td>
                                <h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject">
                                    <?php $subject = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `subject` WHERE id = " . $row['subject_id'])); ?>
                                    <?php echo $subject['name'] ?>
                                </h5>
                            </td>
                            <td>
                                <h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject">
                                    <?php echo $startTimeLeft->invert == '0' && $endTimeLeft->invert == '0' ? "Yet To Start" : ""; ?>
                                    <?php echo $startTimeLeft->invert == '1' && $endTimeLeft->invert == '0' ? "OnGoing" : ""; ?>
                                    <?php echo $startTimeLeft->invert == '1' && $endTimeLeft->invert == '1' ? "Expired" : ""; ?>
                                </h5>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <a href="export.php?result=true&examId=<?php echo $row['id']; ?>" class="btn btn-primary btn-sm">Results</a>
                                    <a href="index.php?examId=<?php echo $row['id'] ?>" class="ms-2 btn btn-success btn-sm">View</a>
                                </div>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        </div>
    </div>
    <div class="d-none"></div>
    <?php include "../includes/footer.php" ?>