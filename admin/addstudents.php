<?php require "../db/db.php" ?>
<?php require "../functions/functions.php" ?>
<?php require "../vendor/autoload.php" ?>

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
		$pass = $value['pass'];
		mysqli_query($con, "INSERT INTO `student`(`name`, `rollno`, `class`,`division`,`phone`, `email`, `pwd`) VALUES ('$name', $rollno, 'BSCIT', 'A', $phone, '$email','$pass')") or die(mysqli_error($con));
		sendMail($email, "SARAF COLLEGE - ACCOUNT CREDENTIALS", "
		<h1><center>Saraf College presents</center></h1>
		<h4>Your account credentials for your upcoming examination</h4>
		<b>Username/Email</b>: $email<br />
		<b>Password</b>: $pass<br />
		<br />
		<b>Login Link</b>: <a href='http://localhost/login.php?email=$email&pass=$pass'>Click here</a>
		<br />
		");
	}
	
}

?>

<?php include "../includes/header.php" ?>

<div class="container">
	<h1 class="text-center  mt-4">Add New Question Paper</h1>
	<form action="addstudents.php" method="POST" enctype="multipart/form-data">
		<label>Upload Excel Sheet</label>
		<input required type="file" id="pfile" class="form-control" name="pfile" />
		<h5 class="text-danger">Please rename your question paper to book file and keep it in the main directory</h5>
		<button type="submit" class="btn btn-success" name="psubmit">Add Paper</button>
	</form>
</div>

<?php include "../includes/footer.php" ?>