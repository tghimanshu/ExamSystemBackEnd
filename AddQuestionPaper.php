<?php require "db/db.php" ?>
<?php require "functions/functions.php" ?>
<?php require "vendor/autoload.php" ?>
<?php

if (isset($_GET['psubmit'])) {
	$pname = mysqli_real_escape_string($con, $_GET['pname']);
	$pclass = mysqli_real_escape_string($con, $_GET['pclass']);
	$psubject = mysqli_real_escape_string($con, $_GET['psubject']);
	$questions = getQuestionsFromExcel();
	$answers = getAnswersFromExcel();
	$pq = urlencode($questions);
	$pa = urlencode($answers);
	$q = mysqli_query($con, "INSERT INTO `exampaper`(`Questions`, `Class`, `Subject`, `answers`) VALUES ('$pq','$pclass','$psubject','$pa')");
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
		<form action="AddQuestionPaper.php" method="GET">
			<div class="input-group mb-2">
				<label for="pname" class="input-group-text">Paper Name</label>
				<input required type="text" id="pname" class="form-control" placeholder="Enter Paper Name" name="pname" />
			</div>
			<div class="input-group mb-2">
				<label for="pclass" class="input-group-text">Class</label>
				<input required type="text" id="pclass" class="form-control" placeholder="Enter Class Name" name="pclass" />
			</div>

			<div class="input-group mb-2">
				<label for="psubject" class="input-group-text">subject</label>
				<input required type="text" id="psubject" class="form-control" placeholder="Enter Subject Name" name="psubject" />
			</div>
			<div class="input-group mb-2">
				<label for="pstart" class="input-group-text">StartDate & time</label>
				<input required type="datetime-local" id="pstart" class="form-control" name="datetime" />
			</div>
			<div class="input-group mb-2">
				<label for="pend" class="input-group-text">End Time</label>
				<input required type="time" id="pend" class="form-control" name="endtime" />
			</div>
			<h5 class="text-danger">Please rename your question paper to book file and keep it in the main directory</h5>
			<button type="submit" class="btn btn-success" name="psubmit">Add Paper</button>
		</form>
	</div>
	<script src="../ExamSystemFrontEnd/assets/js/bootstrap.min.js"></script>
</body>

</html>