<?php
$con = mysqli_connect("localhost", "root", "", "examsystem");
if (!$con) {
    die("Error Establishing while database connection");
}
$headerTitle = "";
$cssFiles = "";
