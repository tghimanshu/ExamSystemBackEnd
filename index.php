<?php require "db/db.php" ?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Document</title>
	<link rel="stylesheet" href="../ExamSystemFrontEnd/assets/css/bootstrap.min.css" />
</head>

<body>
	<div class="container">
		<h1>Student List</h1>
		<div class="table-responsive">
			<table class="table table-striped table-hover">
				<?php $query = mysqli_query($con, "SELECT * FROM `student`") ?>
				<thead>
					<tr>
						<th>id</th>
						<th>Name</th>
						<th>Roll No.</th>
						<th>Class</th>
						<th>Actions</th>
					</tr>
				</thead>
				<tbody>
					<?php while ($row = mysqli_fetch_assoc($query)) : ?>
						<tr>
							<td><?php echo $row['ID'] ?></td>
							<td><?php echo $row['Name'] ?></td>
							<td><?php echo $row['Rollno.'] ?></td>
							<td><?php echo $row['Class'] ?></td>
							<td>
								<a href="student.php?id=<?php echo $row['ID'] ?>" class="btn btn-success">View</a>
								<a href="student.php?id=<?php echo $row['ID'] ?>&task=edit" class="btn btn-primary">Edit</a>
								<a class="btn btn-danger">Delete</a>
							</td>
						</tr>
					<?php endwhile; ?>
				</tbody>
			</table>
		</div>
		<h1>SHIVANI KA PROFIE PAGE</h1>
		<?php $query2 = mysqli_query($con, "SELECT * FROM `student`") ?>
		<div class="row">
			<?php while ($row = mysqli_fetch_assoc($query2)) : ?>
				<div class="col-3 card">
					<div class="card-body">
						<h4>Name: <?php echo $row['Name'] ?></h4>
						<h4>Roll NO: <?php echo $row['Rollno.'] ?></h4>
						<a href="exam.php?id=<?php echo $row['ID'] ?>" class="btn btn-success btn-lg">Attempt Exam</a>
					</div>
				</div>
			<?php endwhile; ?>
		</div>
	</div>
	<script src="../ExamSystemFrontEnd/assets/js/bootstrap.min.js"></script>
</body>

</html>