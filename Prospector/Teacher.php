<?php include "../DB/db.php"?>
<?php include "../functions/functions.php"?>
<?php include "../vendor/autoload.php"?>

<?php $headerTitle="Prospector | Teacher"?>
<?php $cssFiles="<link rel='stylesheet' href='../assets/css/student_profile.css'/>"?>
<?php include "../includes/header.php"?>
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
				<a class="btn btn-light d-flex align-items-center" href="index.php">
					<div class="btn rounded-circle text-light bg-success me-2" style="width: 35px; height: 35px">
						1
					</div>
					<div class="text-light">Department</div>
				</a>
				<a class="btn btn-light d-flex align-items-center" href="Teacher.php">
					<div class="btn rounded-circle text-light bg-success me-2" style="width: 35px; height: 35px">
						2
					</div>
					<div class="text-light">Teachers</div>
				</a>
				<a class="btn btn-light d-flex align-items-center" href="EditClass.php">
					<div class="btn rounded-circle text-light bg-info me-2" style="width: 35px; height: 35px">
						3
					</div>
					<div class="text-light">Edit Class</div>
				</a>
				<a class="btn btn-light d-flex align-items-center" href="EditSubject.php">
					<div class="btn rounded-circle text-light bg-danger me-2" style="width: 35px; height: 35px">
						4
					</div>
					<div class="text-light">Edit Subject</div>
				</a>
			</section>
			<section id="allTeacher">
				<!-- Bascially there will be two loop first loop will take all the streams and second one take all teacher  from iterated stream loop -->
			<div class="table-responsive mt-5 mx-5">
				<table class="table table-striped table-hover table-bordered blurred-bg">
					<caption style="caption-side: top;">BSCIT</caption>
					<thead>
						<tr>
							<th>ID</th>
							<th>Name</th>
							<th>Class Teacher</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
							<tr>
								<td>1</td>
								<td>
									<h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject">Sneha</h5>
								</td>
								<td>
									<h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject">SEM5</h5>
								</td>
								<td>
									<div class="d-flex justify-content-center">
										<a href="" class="btn btn-success btn-sm">Assign</a>
										<a href="" class="ms-2 btn btn-primary btn-sm">Details</a>
										<a href="" class="ms-2 btn btn-danger btn-sm">Remove</a>
									</div>
								</td>
							</tr>
							<!-- this like comes at the end of the loop -->
							<tr>
								<td colspan="4">
									<a href="" class="btn btn-success">Add New Teacher</a>
								</td>
							</tr>
					</tbody>
				</table>
			</div>
			</section>
    </div>
</div>
<?php include "../includes/footer.php" ?>