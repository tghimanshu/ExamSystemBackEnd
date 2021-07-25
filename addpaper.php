<?php require "db/db.php" ?>
<?php require "functions/functions.php" ?>
<?php require "vendor/autoload.php" ?>
<?php

if (isset($_GET['psubmit'])) {
	date_default_timezone_set("Asia/Kolkata");
	$pclass = mysqli_real_escape_string($con, $_GET['pclass']);
	$date = new DateTime(mysqli_real_escape_string($con, $_GET['pdate']));
	$EndDate = new DateTime(mysqli_real_escape_string($con, $_GET['penddate']));
	$pdate = $date->format('Y-m-d H:i:s');
	$pEndDate = $EndDate->format('Y-m-d H:i:s');
	$psubject = mysqli_real_escape_string($con, $_GET['psubject']);
	$questions = getQuestionsFromExcel();
	$answers = getAnswersFromExcel();
	$timee = $date->diff($EndDate);
	$timeElapsed = ($timee->h != 0 ? 60 * $timee->h : $timee->i) . ':' . $timee->s + 1;
	$pq = urlencode($questions);
	$pa = urlencode($answers);
	$q = mysqli_query($con, "INSERT INTO `exampaper`(`Questions`, `Class`, `Subject`,`date`,`endTime`, `answers`, `timeLimit`) VALUES ('$pq','$pclass','$psubject', '$pdate','$pEndDate', '$pa', '$timeElapsed' )");
	if (!$q) {
		echo "error";
		print_r(mysqli_error($con));
	} else {
		header("Location: index.php");
	}
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Add Question Paper</title>
	<link rel="stylesheet" href="../ExamSystemFrontEnd/assets/css/bootstrap.min.css" />
</head>

<body>
	<div class="container">
		<h1 class="text-center  mt-4">Add New Question Paper</h1>
		<form action="addpaper.php" method="GET">
			<div class="input-group mb-2">
				<label for="pclass" class="input-group-text">Class</label>
				<input required type="text" id="pclass" class="form-control" placeholder="Enter Class Name" name="pclass" />
			</div>

			<div class="input-group mb-2">
				<label for="psubject" class="input-group-text">subject</label>
				<input required type="text" id="psubject" class="form-control" placeholder="Enter Subject Name" name="psubject" />
			</div>
			<div class="input-group mb-2">
				<label for="pdate" class="input-group-text">StartDate & time</label>
				<input required type="datetime-local" id="pdate" class="form-control" name="pdate" />
			</div>
			<div class="input-group mb-2">
				<label for="penddate" class="input-group-text">End Time</label>
				<input required type="datetime-local" id="penddate" class="form-control" name="penddate" />
			</div>
			<h5 class="text-danger">Please rename your question paper to book file and keep it in the main directory</h5>
			<button type="submit" class="btn btn-success" name="psubmit">Add Paper</button>
		</form>
	</div>
	<script src="../ExamSystemFrontEnd/assets/js/bootstrap.min.js"></script>
</body>

</html>