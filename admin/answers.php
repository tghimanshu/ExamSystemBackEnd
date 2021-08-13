<?php require "../db/db.php" ?>
<?php require "../functions/functions.php" ?>
<?php require "../vendor/autoload.php" ?>
<?php

$headerTitle = "Answer Review | Admin";
$cssFiles = "<link rel='stylesheet' href='/assets/css/exam.css' /><link rel='stylesheet' href='/assets/css/exam1.css' />"

?>
<?php include "../includes/header.php" ?>

<div class="app">
	<?php function showQA($con)
	{ ?>
		<nav class="navbar py-2 ">
			<div class="container d-flex justify-content-between align-items-center">
				<h2>
					<a class="navbar-brand">All Papers</a>
				</h2>
				<div class="countdown"></div>
			</div>
		</nav>
		<div class="container mt-5">
			<div class="table-responsive">
				<table class="table table-bordered table-striped table-hover">
					<thead class="table-dark text-light">
						<tr>
							<th>Question</th>
							<th>A1</th>
							<th>A2</th>
							<th>A3</th>
							<th>A4</th>
							<th>Answer</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$query = mysqli_query($con, "SELECT * FROM `exampaper` WHERE `ID` = " . $_GET['id']);
						$data = mysqli_fetch_assoc($query);
						$date = new DateTime($data['date']);
						$q = json_decode(urldecode($data['Questions']), true);
						$a = json_decode(urldecode($data['answers']), true);
						$folderName = "/admin/uploads/" . $date->format('m-d-') . $data['Class'] . $data['Subject'];
						?>
						<?php for ($i = 0; $i < count($q); $i++) { ?>
							<tr>
								<td class="text-warning"><?php echo trim($q[$i]['question']) == "" ? "<img src='$folderName/" . ($i + 1) . ".jpg' width='100px' height='auto' />" : $q[$i]['question'] ?></td>
								<td class="text-warning"><?php echo $q[$i]['answer1'] ?></td>
								<td class="text-warning"><?php echo $q[$i]['answer2'] ?></td>
								<td class="text-warning"><?php echo $q[$i]['answer3'] ?></td>
								<td class="text-warning"><?php echo $q[$i]['answer4'] ?></td>
								<td class="text-center text-warning"><?php echo $a[$i]['answer'] ?></td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php } ?>
	<?php function showPapers($con)
	{ ?>
		<nav class="navbar bg-primary navbar-dark py-2 mb-5">
			<div class="container d-flex justify-content-between align-items-center">
				<h2>
					<a class="navbar-brand">All Papers</a>
				</h2>
				<div class="countdown"></div>
			</div>
		</nav>
		<div class="container">
			<div class="table-responsive">
				<table class="table table-striped table-hover">
					<thead class="table-dark">
						<tr>
							<th class="ps-4 text-center">Subject</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$query = mysqli_query($con, "SELECT * FROM `exampaper`");
						?>
						<?php while ($row = mysqli_fetch_assoc($query)) : ?>
							<tr>
								<td>
									<h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject">
										<?php echo $row['Subject'] ?>
									</h5>
								</td>
								<td>
									<div class="d-flex justify-content-center">
										<a class="btn btn-success btn-sm" href="answers.php?id=<?php echo $row['ID'] ?>">View</a>
								</td>
			</div>
			</tr>
		<?php endwhile; ?>
		</tbody>
		</table>
		</div>
</div>
<?php } ?>


<?php

if (isset($_GET['id'])) {
	showQA($con);
} else {
	showPapers($con);
}
?>
<?php include "../includes/footer.php" ?>