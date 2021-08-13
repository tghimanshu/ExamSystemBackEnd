<?php require "../db/db.php" ?>
<?php require "../functions/functions.php" ?>
<?php require "../vendor/autoload.php" ?>
<?php
session_start();
if (!isset($_SESSION['email'])) {
	header("Location: login.php");
}
?>

<?php $headerTitle = "Add Paper | Admin"; ?>

<?php


if (isset($_POST['psubmit'])) {
	date_default_timezone_set("Asia/Kolkata");
	$pclass = mysqli_real_escape_string($con, $_POST['pclass']);
	$date = new DateTime(mysqli_real_escape_string($con, $_POST['pdate']));
	$EndDate = new DateTime(mysqli_real_escape_string($con, $_POST['penddate']));
	$pdate = $date->format('Y-m-d H:i:s');
	$pEndDate = $EndDate->format('Y-m-d H:i:s');
	$psubject = mysqli_real_escape_string($con, $_POST['psubject']);
	$myQuestionsFile = storeUploadedFile($_FILES['pfile']);
	$myQuestionImagesFile = storeUploadedImages($_FILES['pimgfiles'], $date->format('m-d-') . $pclass . $psubject);
	$questions = getQuestionsFromExcel($myQuestionsFile);
	$answers = getAnswersFromExcel($myQuestionsFile);
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

<?php include "../includes/header.php" ?>
<div class="app">
	<?php include "../includes/navbar-admin.php" ?>
	<div class="container blurred-bg px-5">
		<h1 class="text-center text-white mt-4">Add New Question Paper</h1>
		<form action="addpaper.php" method="POST" enctype="multipart/form-data">
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
			<label class="text-light">Upload Excel Sheet</label>
			<input required type="file" id="pfile" class="form-control" name="pfile" />
			<label class="text-light">Upload All Images</label>
			<input type="file" id="pimgfile" class="form-control mb-3" name="pimgfiles[]" multiple />
			<button type="submit" class="btn btn-success mb-3" name="psubmit">Add Paper</button>
		</form>
	</div>
</div>

<?php include "../includes/footer.php" ?>