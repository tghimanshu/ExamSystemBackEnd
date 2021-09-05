<?php
require "db/db.php";

session_start();

if (isset($_GET['student_id'])) {
    mysqli_query($con, "UPDATE `student` SET `isLoggedIn` = 0 WHERE `id` = " . $_GET['student_id']) or die(mysqli_error($con));
}

session_destroy();
header("Location: login.php");
