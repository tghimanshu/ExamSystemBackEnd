<?php include "../DB/db.php"?>
<?php include "../functions/functions.php"?>
<?php include "../vendor/autoload.php"?>

<?php
if(isset($_GET['edit']))
{
	$isEditing=true;
	$query=mysqli_query($con,"SELECT * FROM `subject`WHERE id=".$_GET['id']);
	$assignData=mysqli_fetch_assoc($query);
}
?>
<?php $headerTitle="Prospector | Edit Subject"?>
<?php $cssFiles="<link rel='stylesheet' href='../assets/css/student_profile.css'/>"?>
<?php include "../includes/header.php"?>
<?php ?>
<div class="app">
    <?php  include "../includes/navbar-Prospector_Student.php"?>
    <div class="container">
    <?php include "../includes/Categories_Prospector.php"?>
	<section id="allSubject">
		<?php ?>
		<!-- Division Wise data if any database made -->
		<div class="table-responsive mt-5 mx-5">
			<table class="table table-striped table-hover table-bordered blurred-bg">
				<caption style="caption-side: top;">BSCIT</caption>
				<thead>
					<tr>
						<th>Sr.No</th>
						<th>Subject Name</th>
						<th>Assign Teacher</th> 
						<!-- Caution : Add two teacher if two teacher assigned. -->
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php
					$srno=0;
					$subjectQuery=mysqli_query($con,"SELECT * FROM `subject`");	
					?>
					<?php
					while($subjectData=mysqli_fetch_assoc($query)):
						$subTeacherQuery=mysqli_query($con,"SELECT * FROM `teacher` WHERE id=".$subjectData['teacher_id']);
						while($teacherData=mysqli_fetch_array($subTeacherQuery)):
					?>
					<tr>
						<td><?php echo ++$srno;?></td>
						<td>
							<h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject"><?php echo $subjectData['name'];?></h5>
						</td>
						<td>
							<h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject"><?php echo $teacherData['name'];?></h5>
						</td>
						<td>
							<div class="d-flex justify-content-center">
								<a href="subject.php?edit=true&id=<?php echo $subjectData['id']?>" class="btn btn-success btn-sm">Edit</a>
								<a href="subject.php?delete=true&id=<?php echo $subjectData['id']?>" class="ms-2 btn btn-danger btn-sm">Remove</a>
							</div>
						</td>
					</tr>
					<?php endwhile;
					endwhile;
					?>
					<!-- this like comes at the end of the loop -->
					<tr>
					<td colspan="4">
							<form method="POST" action="subject.php" class="d-flex">
								<?php if (isset($isEditing)) {
									echo "<input type='hidden' name='id' value='" . $_GET['id'] . "' />";
								} ?>
								<div class="me-3">
									<input name="subject" type="text" class="form-control" placeholder="subject" aria-label="year" required value="<?php echo isset($isEditing) ? $editData['year'] : '' ?>" />
								</div>
								<div class="me-3">
									<?php $dquery = mysqli_query($con, "SELECT * FROM `departments`;"); ?>
									<select name="department_id" class="form-control">
										<?php while ($d = mysqli_fetch_assoc($dquery)) : ?>
											<option value="<?php echo $d['id'] ?>" <?php echo isset($isEditing) && $editData['department_id'] == $d['id'] ? 'selected' : '' ?>><?php echo $d['name'] ?></option>
										<?php endwhile; ?>
									</select>
								</div>
								<div class="me-3">
									<?php $dquery = mysqli_query($con, "SELECT * FROM `departments`;"); ?>
									<select name="teacher_id" class="form-control">
										<?php while ($d = mysqli_fetch_assoc($dquery)) : ?>
											<option value="<?php echo $d['id'] ?>" <?php echo isset($isEditing) && $editData['department_id'] == $d['id'] ? 'selected' : '' ?>><?php echo $d['name'] ?></option>
										<?php endwhile; ?>
									</select>
								</div>
								<div class="me-3">
									<button name="<?php echo isset($isEditing) ? 'editclass' : 'addclass'; ?>" type="submit" class="btn btn-success">
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