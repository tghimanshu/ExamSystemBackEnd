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
}

?>

<?php include "../includes/header.php" ?>
<div class="app">
	<?php include "../includes/navbar-admin.php" ?>
	<div class="container blurred-bg" style="border-radius: 10px">
		<h1 class="text-center text-light mt-5">Add New Students</h1>
		<form action="addstudents.php" method="POST" enctype="multipart/form-data">
			<input type="hidden" name="class_id" value="<?php echo $_GET['class_id'] ?>" />
			<label>Upload Excel Sheet</label>
			<input required type="file" id="pfile" class="form-control mb-3" name="pfile" />
			<button type="submit" class="btn btn-success mb-3" name="psubmit">Add Students</button>
		</form>
	</div>
</div>


<?php include "../includes/footer.php" ?>