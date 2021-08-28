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
$result = mysqli_query($con, "SELECT * FROM `answers` WHERE `paper_id` = $examId;") or die("Couldn't execute query:<br>");
$file_ending = "csv";


header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
header('Content-Disposition: attachment; filename=' . $filename . '.csv');

$sep = ",";

print "Sr no.,Roll No,First Name,Middel Name,Last Name,Marks Out of 50";
print "\n";
$srno = 0;
while ($row = mysqli_fetch_assoc($result)) {
    $data = '';
    $data .= ++$srno . $sep;
    $squery = mysqli_query($con, "SELECT * FROM `student` WHERE `id` = " . $row['student_id']);
    $student = mysqli_fetch_assoc($squery);
    $data .= $student['rollno'] . $sep;
    $name = explode(' ', $student['name']);
    $data .= $name[1] . $sep;
    $data .= $name[2] . $sep;
    $data .= $name[0] . $sep;
    $data .= getResult($row['student_id'], $row['paper_id']);
    print $data;
    print "\n";
}
