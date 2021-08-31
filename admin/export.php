<?php require "../db/db.php" ?>
<?php require "../functions/functions.php" ?>
<?php require "../vendor/autoload.php" ?>
<?php

if (isset($_GET['result'])) {
    $examId = $_GET['examId'];
} else {
    header('Location: /admin');
}

$filename = "results";         //File Name

//execute query 
// $result = mysqli_query($con, "SELECT * FROM `answers` WHERE `paper_id` = $examId;") or die("Couldn't execute query:<br>");
$students = mysqli_query($con, "SELECT * FROM `student` WHERE `class_id` IN (SELECT class_id from `subject` WHERE  id IN (SELECT subject_id FROM `exampaper` WHERE `id` = $examId));") or die("Couldn't execute query:<br>");
$pquery = mysqli_query($con, "SELECT * FROM `exampaper` WHERE `id` = $examId;");
$paper = mysqli_fetch_assoc($pquery);
$filename = $paper['name'] . " - " . mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `subject` WHERE `id` = " . $paper['subject_id']))['name'];
$file_ending = "csv";


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename=' . $filename . '.csv');

$sep = ",";

print "Sr no.,Roll No,First Name,Middel Name,Last Name,Marks Out of 50";
print "\n";
$srno = 0;
while ($student = mysqli_fetch_assoc($students)) {
    $data = '';
    $data .= ++$srno . $sep;
    // $squery = mysqli_query($con, "SELECT * FROM `answers` WHERE `paper_id` = $examId AND `student_id` = " . $student['id']);
    // $answer = mysqli_fetch_assoc($squery);
    $data .= $student['rollno'] . $sep;
    $name = explode(' ', $student['name']);
    $data .= $name[1] . $sep;
    $data .= $name[2] . $sep;
    $data .= $name[0] . $sep;
    $data .= getResult($student['id'], $examId);
    print $data;
    print "\n";
}
