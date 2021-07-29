<?php require "db/db.php" ?>
<?php require "functions/functions.php" ?>
<?php require "vendor/autoload.php" ?>
<?php

session_start();
if (!isset($_SESSION['email'])) {
	header("Location: login.php");
}

$query = mysqli_query($con, "SELECT * FROM exampaper");

?>
<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="UTF-8" />
	<meta http-equiv="X-UA-Compatible" content="IE=edge" />
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<title>Document</title>
	<link rel="stylesheet" href="assets/css/bootstrap.min.css" />
	<link rel="stylesheet" href="assets/css/style.css">
	<link rel="stylesheet" href="assets/css/student_profile.css">
</head>

<body>

	<div class="app">
		<nav class="navbar bg-primary navbar-dark py-2 justify-content-between">
			<div class="container">
				<h2>
					<a class="navbar-brand">Dashboard</a>
				</h2>
				<div>
					<a href="logout.php" class="btn btn-danger">Logout</a>
				</div>
			</div>
		</nav>
		<div class="container">
			<section id="allCategories" class="d-flex justify-content-between mt-3 tabs">
				<a class="btn btn-light d-flex align-items-center">
					<div class="btn rounded-circle text-light bg-success me-2" style="width: 35px; height: 35px">
						1
					</div>
					<div class="text-light">All</div>
				</a>
				<a class="btn btn-light d-flex align-items-center">
					<div class="btn rounded-circle text-light bg-success me-2" style="width: 35px; height: 35px">
						2
					</div>
					<div class="text-light">Yet to start</div>
				</a>
				<a class="btn btn-light d-flex align-items-center">
					<div class="btn rounded-circle text-light bg-info me-2" style="width: 35px; height: 35px">
						3
					</div>
					<div class="text-light">Resume</div>
				</a>
				<a class="btn btn-light d-flex align-items-center">
					<div class="btn rounded-circle text-light bg-danger me-2" style="width: 35px; height: 35px">
						4
					</div>
					<div class="text-light">Completed</div>
				</a>
				<a class="btn btn-dark d-flex align-items-center">
					<div class="btn rounded-circle text-light bg-dark me-2" style="width: 35px; height: 35px">
						5
					</div>
					<div class="text-light">Expired</div>
				</a>
			</section>
			<section id="allExams" class="row mt-4">
				<?php while ($row = mysqli_fetch_assoc($query)) : ?>
					<?php
					$examQuery = mysqli_query($con, "SELECT * FROM `answers` WHERE student_id = " . $_SESSION['student_id'] . " AND paper_id = " . $row['ID']);
					$examData = mysqli_fetch_assoc($examQuery);
					?>
					<div class="col-lg-3 col-md-6 col-12 mt-3 zoom">
						<div class="card">
							<div class="card-header bg-primary">
								<h4 class="text-center text-light"><?php echo $row['Subject'] ?></h4>
							</div>
							<div class="card-body">
								<?php
								$date1 = date('d/m/y', strtotime($row['date']));
								$time = date('H:i:s', strtotime($row['date']));
								$end = date('H:i:s', strtotime($row['endTime']));
								$currTime = new DateTime($timezone = "Asia/Kolkata");
								$startTime = new DateTime($row['date'], new DateTimeZone("Asia/Kolkata"));
								$startTimeLeft = $currTime->diff($startTime);
								?>
								<h6>Start: <?php echo $date1 ?></h6>
								<h6>Time: <?php echo $time ?></h6>
								<h6>Expired: <?php echo $end ?></h6>
								<h6>No of Questions: <?php print_r(count(json_decode(urldecode($row['Questions'])))) ?></h6>
								<hr />
								<a href="exam.php?id=<?php echo $row['ID'] ?>" class="btn btn-primary d-block mx-auto <?php echo $startTimeLeft->invert == '0' || $examData['submitted'] == '1' ? "disabled" : "" ?>">
									<?php
									echo isset($examData) ? ($examData['submitted'] == '1' ? "Completed" : "Resume") : "Start"
									?>
								</a>
							</div>
						</div>
					</div>
				<?php endwhile; ?>
			</section>
		</div>
	</div>
	<script src=" ../ExamSystemFrontEnd/assets/js/bootstrap.min.js"></script>
</body>

</html>