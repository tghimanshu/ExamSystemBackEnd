<?php require "../db/db.php" ?>
<?php require "../functions/functions.php" ?>
<?php require "../vendor/autoload.php" ?>
<?php
session_start();
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}
?>

<?php $headerTitle = "Add Paper | Admin"; ?>

<?php


if (isset($_POST['psubmit'])) {
	date_default_timezone_set("Asia/Kolkata");
	$date = new DateTime(mysqli_real_escape_string($con, $_POST['pdate']));
	$EndDate = new DateTime(mysqli_real_escape_string($con, $_POST['penddate']));
	$pdate = $date->format('Y-m-d H:i:s');
	$pEndDate = $EndDate->format('Y-m-d H:i:s');
	$pexamtype = mysqli_real_escape_string($con, $_POST['pexamtype']);
	$pname = mysqli_real_escape_string($con, $_POST['pname']);
	$psubject = mysqli_real_escape_string($con, $_POST['psubject']);
	$myQuestionsFile = storeUploadedFile($_FILES['pfile']);
	$myQuestionImagesFile = storeUploadedImages($_FILES['pimgfiles'], $date->format('m-d-') . $psubject . $pname);
	$questions = getQuestionsFromExcel($myQuestionsFile);
	$answers = getAnswersFromExcel($myQuestionsFile);
	$timee = $date->diff($EndDate);
	$timeElapsed = ($timee->h != 0 ? 60 * $timee->h : $timee->i) . ':' . $timee->s + 1;
	$pq = urlencode($questions);
	$pa = urlencode($answers);
	// for getting mock test last number
	$mockNumber =  mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `exampaper` WHERE `subject_id` = $psubject AND `exam_type` = 3 ORDER BY `name` DESC;"));
	$mockNumber = intval($mockNumber['name']);
	$mockNumber++;
	$mockNumber = $pname == '3' ? $mockNumber : $pname;
	$q = mysqli_query($con, "INSERT INTO `exampaper`(`exam_type`, `name`,`Questions`, `subject_id`,`date`,`endTime`, `answers`, `timeLimit`) VALUES ($pexamtype, '$mockNumber','$pq','$psubject', '$pdate','$pEndDate', '$pa', '$timeElapsed' )");
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
		<?php
		if (isset($_GET['subject_id'])) {
			$dquery = mysqli_query($con, "SELECT * FROM `subject` WHERE `id`=" . $_GET['subject_id']);
			$subject = mysqli_fetch_assoc($dquery);
		}
		?>
		<h1 class="text-center text-white mt-4">Add New <?php echo isset($_GET['subject_id']) ? $subject['name'] : "" ?> Question Paper</h1>
		<form action="addpaper.php" method="POST" enctype="multipart/form-data">
			<div class="input-group mb-2 <?php echo isset($_GET['subject_id']) ? 'd-none' : "" ?>">
				<label for="psubject" class="input-group-text">subject</label>
				<select name="psubject" class="form-control">
					<?php $cquery = mysqli_query($con, "SELECT * FROM `classes` WHERE `department_id` = " . $_SESSION['department_id']); ?>
					<?php while ($c = mysqli_fetch_assoc($cquery)) : ?>
						<optgroup label="<?php echo $c['year'] ?>">
							<?php $dquery = mysqli_query($con, "SELECT * FROM `subject` WHERE `class_id` = " . $c['id']); ?>
							<?php while ($d = mysqli_fetch_assoc($dquery)) : ?>
								<option value="<?php echo $d['id'] ?>" <?php echo isset($_GET['subject_id']) && $_GET['subject_id'] == $d['id'] ? "selected" : "" ?>><?php echo 'SEM ' . $d['sem'] . ' - ' . $d['name'] ?></option>
							<?php endwhile; ?>
						</optgroup>
					<?php endwhile; ?>
				</select>
			</div>
			<div class="input-group mb-2">
				<label for="pexamtype" class="input-group-text">exam type</label>
				<script>
					function isMock(e) {
						if (parseInt(e.value) === 3) {
							document.getElementById('paperName').style.display = 'none'
							document.getElementById('pname').value = '3';
						} else {
							document.getElementById('paperName').style.display = 'flex'
							document.getElementById('pname').value = '1';
						}
					}
				</script>
				<select name="pexamtype" class="form-control" onchange="isMock(this)">
					<option value="1">Regular</option>
					<option value="2">ATKT</option>
					<option value="3">Mock</option>
				</select>
			</div>
			<div class="input-group mb-2" id="paperName">
				<label for="pname" class="input-group-text">exam type</label>
				<select name="pname" class="form-control" id="pname">
					<option value="1">Internal</option>
					<option value="2">External</option>
					<option value="3" class="d-none"></option>
				</select>
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