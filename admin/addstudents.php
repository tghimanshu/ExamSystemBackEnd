<?php require "../db/db.php" ?>
<?php require "../functions/functions.php" ?>
<?php require "../vendor/autoload.php" ?>
<?php
session_start();
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}
?>
<?php

if (isset($_GET['delete'])) {
	$query = mysqli_query($con, "SELECT * FROM `student` WHERE class_id = " . $_GET['class_id']);
	while ($data = mysqli_fetch_assoc($query)) {
		mysqli_query($con, "DELETE FROM `student` WHERE id = " . $data['id']);
	}
	header("Location: student.php?class_id=" . $_GET['class_id']);
}

?>

<?php $headerTitle = "Add Students | Admin"; ?>

<?php


if (isset($_POST['psubmit'])) {
	$myQuestionsFile = storeUploadedFile($_FILES['pfile']);
	$studentData = getStudentsFromExcel($myQuestionsFile);
	foreach ($studentData as $key => $value) {
		$name = $value['name'];
		$rollno = $value['rollNo'];
		$email = $value['email'];
		$phone = $value['phone'];
		$pwd = $value['pass'];
		$class_id = $_POST['class_id'];
		mysqli_query($con, "INSERT INTO `student`(`name`, `rollno`, `class_id`,`phone`, `email`, `pwd`) VALUES ('$name', $rollno, $class_id, '$phone', '$email','$pwd')") or die(mysqli_error($con));
		// sendMail($email, "SARAF COLLEGE - ACCOUNT CREDENTIALS", "
		// <h1><center>Saraf College presents</center></h1>
		// <h4>Your account credentials for your upcoming examination</h4>
		// <b>Username/Email</b>: $email<br />
		// <b>Password</b>: $pwd<br />
		// <br />
		// <b>Login Link</b>: <a href='http://localhost/login.php?email=$email&pass=$pwd'>Click here</a>
		// <br />
		// ");
	}
	header("Location: student.php?class_id=" . $_POST['class_id']);
}

?>

<?php include "../includes/header.php" ?>
<div class="app">
	<?php include "../includes/navbar-admin.php" ?>
	<div class="container blurred-bg" style="border-radius: 10px">
		<h1 class="text-center text-light mt-5">Add New Students</h1>
		<form action="addstudents.php" method="POST" enctype="multipart/form-data">
			<div class="input-group mb-2 <?php echo isset($_GET['class_id']) ? 'd-none' : "" ?>">
				<label for="pclass" class="input-group-text">subject</label>
				<?php $dquery = mysqli_query($con, "SELECT * FROM `classes` WHERE `department_id` = " . $_SESSION['department_id']); ?>
				<select name="class_id" class="form-control">
					<?php while ($d = mysqli_fetch_assoc($dquery)) : ?>
						<option value="<?php echo $d['id'] ?>" <?php echo isset($_GET['class_id']) && $_GET['class_id'] == $d['id'] ? "selected" : "" ?>><?php echo $d['year'] ?></option>
					<?php endwhile; ?>
				</select>
			</div>
			<label>Upload Excel Sheet</label>
			<input required type="file" id="pfile" class="form-control mb-3" name="pfile" />
			<button type="submit" class="btn btn-success mb-3" name="psubmit">Add Students</button>
		</form>
	</div>
</div>


<?php include "../includes/footer.php" ?>