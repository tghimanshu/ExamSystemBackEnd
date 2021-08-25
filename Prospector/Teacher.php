<?php include "../DB/db.php" ?>
<?php include "../functions/functions.php" ?>
<?php include "../vendor/autoload.php" ?>
<?php
if ((isset($_GET['edit'])) || (isset($_GET['details'])) ) {
	// cuz both thing require same query so keeping it together
	if(isset($_GET['details'])){
		$teacherDetail=true;
	}
	else{
		$isEditing = true;
	}
	$query = mysqli_query($con, "SELECT * FROM `teacher` WHERE id=" . $_GET['id']);
	$editData=mysqli_fetch_assoc($query);
}

if(isset($_GET['delete'])){
	mysqli_query($con,"DELETE FROM `teacher` WHERE id=".$_GET['id']) or die(mysqli_error($con));
}
if(isset($_POST['addteacher'])){
$name = mysqli_escape_string($con, $_POST['name']);
$contact = mysqli_escape_string($con, $_POST['contact']);
$email = mysqli_escape_string($con, $_POST['email']);
$query=mysqli_query($con,"INSERT into `teacher` VALUES(null,'$name','$email','admin','$contact')");
}
if(isset($_POST['editteacher'])){
	$name = mysqli_escape_string($con, $_POST['name']);
	$contact = mysqli_escape_string($con, $_POST['contact']);
	$email = mysqli_escape_string($con, $_POST['email']);
	$id=mysqli_escape_string($con,$_POST['id']);
	$addQuery = mysqli_query($con, "UPDATE `teacher` SET `name` = '$name', `email` = '$email', `contact` = $contact  WHERE id = $id") or die(mysqli_error($con));
}
?>
<?php $headerTitle = "Prospector | Teacher" ?>
<?php $cssFiles = "<link rel='stylesheet' href='../assets/css/student_profile.css'/>" ?>
<?php include "../includes/header.php" ?>
<div class="app">
	<?php include "../includes/navbar-Prospector_Student.php" ?>
	<div class="container">
		<?php include "../includes/Categories_Prospector.php" ?>
		<?php if(isset($teacherDetail)): ?>
			<section class="teacherDetail">
				<?php $teacherSubject=mysqli_query($con,"SELECT * FROM `subject` WHERE teacher_id=".$_GET['id']);
				while($rowSubject=mysqli_fetch_assoc($teacherSubject)):
					$srno=0;
					$teacherClass=mysqli_query($con,"SELECT * FROM `classes` WHERE id=".$rowSubject['class_id']);
					while($rowClass=mysqli_fetch_assoc($teacherClass)):
						$teacherDepartment=mysqli_query($con,"SELECT * FROM `departments` WHERE id=".$rowClass['department_id']);
						while($rowDepartment=mysqli_fetch_assoc($teacherDepartment)):?>
							<div class="table-responsive mt-5 mx-5">
								<table class="table table-striped table-hover table-bordered blurred-bg">
									<caption style="caption-side: top;"><?php echo $_GET['name']." | ".$rowDepartment['name']?></caption>
									<thead>
										<tr>
											<th>Sr.no</th>
											<th>Year</th>
											<th>Division</th>
											<th>Subject</th>
										</tr>
									</thead>
									<tbody>
										<tr>
											<td><?php echo ++$srno ?></td>
											<td>
												<h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject"><?php echo $rowClass['year'] ?></h5>
											</td>
											<td>
												<h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject"><?php echo $rowClass['division'] ?></h5>
											</td>
											<td>
												<h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject"><?php echo $rowSubject['name'] ?></h5>
											</td>
										</tr>
									</tbody>
								</table>
							</div>
						<?php endwhile; // outermost endwhile
						endwhile; //middlemost while end
				endwhile; //outermost while end?>
			</section>
		<?php else:?>
			<section id="allTeacher">
				<!-- Bascially there will be two loop first loop will take all the streams and second one take all teacher  from iterated stream loop -->
				<div class="table-responsive mt-5 mx-5">
					<table class="table table-striped table-hover table-bordered blurred-bg">
						<caption style="caption-side: top;">BSCIT</caption>
						<thead>
							<tr>
								<th>ID</th>
								<th>Name</th>
								<th>Contact</th>
								<th>email</th>
								<th>password</th>
								<th>Action</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$query = mysqli_query($con, "SELECT * FROM `teacher`");
							$srno = 0;
							while ($row = mysqli_fetch_assoc($query)) :
							?>
								<tr>
									<td><?php echo ++$srno ?></td>
									<td>
										<h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject"><?php echo $row['name'] ?></h5>
									</td>
									<td>
										<h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject"><?php echo $row['contact'] ?></h5>
									</td>
									<td>
										<h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject"><?php echo $row['email'] ?></h5>
									</td>
									<td>
										<h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject"><?php echo $row['password'] ?></h5>
									</td>
									<td>
										<div class="d-flex justify-content-center">
											<a href="Teacher.php?assign=true&id=<?php echo $row['id'] ?>" class="ms-2 btn btn-primary btn-sm">Assign</a>
											<a href="Teacher.php?edit=true&id=<?php echo $row['id'] ?>" class="ms-2 btn btn-primary btn-sm">Edit</a>
											<a href="Teacher.php?details=true&id=<?php echo $row['id'] ?>&name=<?php echo $row['name']?>" class="ms-2 btn btn-primary btn-sm">Details</a>
											<a href="Teacher.php?delete=true&id=<?php echo $row['id'] ?>" class="ms-2 btn btn-danger btn-sm">Delete</a>
										</div>
									</td>
								</tr>
							<?php endwhile; ?>
							<!-- this like comes at the end of the loop -->
							<tr>
								<td colspan="6">
									<form method="POST" action="Teacher.php" class="d-flex">
										<?php if (isset($isEditing)) {
											echo "<input type='hidden' name='id' value='" . $_GET['id'] . "' />";
										} ?>
										<div class="me-3">
											<input name="name" type="text" class="form-control" placeholder="name" aria-label="year" required value="<?php echo isset($isEditing) ? $editData['name'] : '' ?>" />
										</div>
										<div class="me-3">
											<input name="contact" type="text" class="form-control" placeholder="contact" aria-label="division" required value="<?php echo isset($isEditing) ? $editData['contact'] : '' ?>" />
										</div>
										<div class="me-3">
											<input name="email" type="text" class="form-control" placeholder="email" aria-label="division" required value="<?php echo isset($isEditing) ? $editData['email'] : '' ?>" />
										</div>
										<div class="me-3">
											<button name="<?php echo isset($isEditing) ? 'Edit Teacher' : 'Add Teacher'; ?>" type="submit" class="btn btn-success">
												<?php if (isset($isEditing)) {
													echo "Update";
												} else {
													echo "Add";
												} ?>
											</button>
										</div>
									</form>
								</td>
							</tr>
						</tbody>
					</table>
				</div>
			</section>
		<?php endif; ?>
	</div>
</div>
<?php include "../includes/footer.php" ?>