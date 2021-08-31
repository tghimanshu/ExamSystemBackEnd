<?php include "../DB/db.php" ?>
<?php include "../functions/functions.php" ?>
<?php include "../vendor/autoload.php" ?>

<?php

if (isset($_GET['delete'])) {
	$deleteQuery = mysqli_query($con, "DELETE FROM `departments` WHERE id = " . $_GET['id']) or die(mysqli_error($con));
}

if (isset($_GET['edit'])) {
	$isEditing = true;
	$editQuery = mysqli_query($con, "SELECT * FROM `departments` WHERE id = " . $_GET['id']);
	if (mysqli_num_rows($editQuery) == -1) {
		die("you are dumb");
	}
	$editData = mysqli_fetch_assoc($editQuery);
}

// if (isset($_POST['adddepartment'])) {
// 	$name = mysqli_escape_string($con, $_POST['name']);
// 	$slug = mysqli_escape_string($con, $_POST['slug']);
// 	$addQuery = mysqli_query($con, "INSERT INTO `departments` SET () VALUES (null, '$name', '$slug')") or die(mysqli_error($con));
// 	$successMessage = "Added Department SucessFully!!";
// }
if (isset($_POST['editdepartment'])) {
	$id = mysqli_escape_string($con, $_POST['id']);
	$name = mysqli_escape_string($con, $_POST['name']);
	$slug = mysqli_escape_string($con, $_POST['slug']);
	$addQuery = mysqli_query($con, "UPDATE `departments` SET `name` = '$name', `slug` = '$slug' WHERE id = $id") or die(mysqli_error($con));
	$successMessage = "Added Department SucessFully!!";
}

?>

<?php $headerTitle = "Prospector | Index" ?>
<?php $cssFiles = "<link rel='stylesheet' href='../assets/css/student_profile.css'/>" ?>
<?php include "../includes/header.php" ?>
<div class="app">
	<?php include "../includes/navbar-Prospector_Student.php" ?>
	<div class="container">
		<?php include "../includes/Categories_Prospector.php" ?>
		<section id="allDepartment">
			<!-- Bascially there will be two loop first loop will take all the streams and second one take all teacher  from iterated stream loop -->
			<?php
			if (isset($successMessage)) {
				echo "<div class='alert alert-success'>$successMessage</div>";
			}
			?>
			<div class="table-responsive mt-5 mx-5">
				<table class="table table-striped table-hover table-bordered blurred-bg">
					<thead>
						<tr>
							<th>Sr.No</th>
							<th>Department Name</th>
							<th>Slug</th>
							<th>Number students</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$query = mysqli_query($con, "SELECT * FROM `departments`");
						$srno = 0;
						?>
						<?php while ($row = mysqli_fetch_assoc($query)) : ?>
							<tr>
								<td><?php echo ++$srno; ?></td>
								<td>
									<h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject"><?php echo $row['name']; ?></h5>
								</td>
								<td>
									<h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject"><?php echo $row['slug']; ?></h5>
								</td>
								<td>
									<h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject">72</h5>
								</td>
								<td>
									<div class="d-flex justify-content-center">
										<a href="index.php?edit=true&id=<?php echo $row['id'] ?>" class="ms-2 btn btn-primary btn-sm">Edit</a>
										<a href="index.php?delete=true&id=<?php echo $row['id'] ?>" class="ms-2 btn btn-danger btn-sm">Remove</a>
									</div>
								</td>
							</tr>
						<?php endwhile; ?>
						<!-- this like comes at the end of the loop -->
						<tr>
							<td colspan="4">
								<form method="POST" action="index.php" class="d-flex">
									<?php if (isset($isEditing)) {
										echo "<input type='hidden' name='id' value='" . $_GET['id'] . "' />";
									} ?>
									<div class="me-3">
										<input name="name" type="text" class="form-control" placeholder="Name" aria-label="Name" required value="<?php echo isset($isEditing) ? $editData['name'] : '' ?>" />
									</div>
									<div class="me-3">
										<input name="slug" type="text" class="form-control" placeholder="Slug" aria-label="Name" required value="<?php echo isset($isEditing) ? $editData['slug'] : '' ?>" />
									</div>
									<div class="me-3">
										<button name="<?php echo isset($isEditing) ? 'editdepartment' : 'adddepartment'; ?>" type="submit" class="btn btn-success">
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
	</div>
</div>
<?php include "../includes/footer.php" ?>