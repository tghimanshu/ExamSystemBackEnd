<?php require "db/db.php" ?>
<?php require "functions/functions.php" ?>
<?php require "vendor/autoload.php" ?>
<?php

function updateAnswer($con)
{
	$student_id = $_POST['studentId'];
	$paper_id = $_POST['paperId'];
	$answers = $_POST['answers'];
	$db_answers = urlencode(json_encode($answers));

	$query = mysqli_query($con, "UPDATE `answers` SET answers = '" . $db_answers . "' WHERE student_id = " . $student_id . " AND paper_id = " . $paper_id);
	print_r("success");
}
function updateTime($con)
{
	$student_id = $_POST['studentId'];
	$paper_id = $_POST['paperId'];
	$time = $_POST['timeElapsed'];
	$query = mysqli_query($con, "UPDATE `answers` SET timeElapsed = '" . $time . "' WHERE student_id = " . $student_id . " AND paper_id = " . $paper_id);
	print_r("success");
}
function submitExam($con)
{
	$student_id = $_POST['studentId'];
	$paper_id = $_POST['paperId'];
	$query = mysqli_query($con, "UPDATE `answers` SET submitted = 1 WHERE student_id = " . $student_id . " AND paper_id = " . $paper_id);
	print_r("success");
}
function postImage($con)
{
	$student_id = $_POST['studentId'];
	$paper_id = $_POST['paperId'];
	$dataurl = $_POST['dataurl'];
	$arr = [$dataurl];
	$dataurl = implode("~", $arr);
	$pastQuery = mysqli_query($con, "SELECT * FROM `answers` WHERE student_id = " . $student_id . " AND paper_id = " . $paper_id);
	$pastData = mysqli_fetch_assoc($pastQuery);
	if (trim($pastData['webcamImages']) == "") {
		$arr = [$dataurl];
		$dataurl = implode("~", $arr);
	} else {
		$arr = explode("~", $pastData['webcamImages']);
		if (count($arr) > 19) {
			array_shift($arr);
		}
		array_push($arr, $dataurl);
		$dataurl = implode("~", $arr);
	}
	$query = mysqli_query($con, "UPDATE `answers` SET webcamImages = '$dataurl' WHERE student_id = " . $student_id . " AND paper_id = " . $paper_id);
}

switch ($_POST['type']) {
	case "answer_update":
		updateAnswer($con);
		break;
	case "time_update":
		updateTime($con);
		break;
	case "submit_exam":
		submitExam($con);
		break;
	case "post_image":
		postImage($con);
		break;
	default:
		break;
}
