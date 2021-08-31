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

if (isset($_POST['submit'])) {
	$query = mysqli_query($con, "SELECT * FROM `exampaper` WHERE `id` = " . $_POST['id']) or die(mysqli_error($con));
	$data = mysqli_fetch_assoc($query);
	$question = json_decode(urldecode($data['Questions']), true);
	$answer = json_decode(urldecode($data['answers']), true);
	$question[$_POST['qId']]['question'] = mysqli_escape_string($con, $_POST['question']);
	$question[$_POST['qId']]['answer1'] = mysqli_escape_string($con, $_POST['o1']);
	$question[$_POST['qId']]['answer2'] = mysqli_escape_string($con, $_POST['o2']);
	$question[$_POST['qId']]['answer3'] = mysqli_escape_string($con, $_POST['o3']);
	$question[$_POST['qId']]['answer4'] = mysqli_escape_string($con, $_POST['o4']);
	$correctquestions = urlencode(json_encode($question));
	$answer[$_POST['qId']]['answer'] = mysqli_escape_string($con, $_POST['answer']);
	$correctanswers = urlencode(json_encode($answer));
	$updatequery = mysqli_query($con, "UPDATE `exampaper` SET `Questions` = '$correctquestions', `answers` = '$correctanswers' WHERE `id` = " . $_POST['id']) or die(mysqli_error($con));
	if ($updatequery) {
		header("Location: answers.php?id=" . $_POST['id']);
	}
}

?>

<?php

$headerTitle = "Answer Review | Admin";
$cssFiles = "<link rel='stylesheet' href='/assets/css/exam.css' /><link rel='stylesheet' href='/assets/css/exam1.css' />"

?>
<?php include "../includes/header.php" ?>

<div class="app">
	<?php function showQA($con)
	{ ?>
		<?php include "../includes/navbar-admin.php" ?>
		<div class="container mt-5">
			<?php
			$query = mysqli_query($con, "SELECT * FROM `exampaper` WHERE `id` = " . $_GET['id']) or die(mysqli_error($con));
			$data = mysqli_fetch_assoc($query);
			$date = new DateTime($data['date']);
			$q = json_decode(urldecode($data['Questions']), true);
			$a = json_decode(urldecode($data['answers']), true);
			$folderName = "/admin/uploads/" . $date->format('m-d-') . $data['subject_id'] . $data['name'];
			?>
			<?php if (isset($_GET['qId'])) : ?>
				<?php $qId = $_GET['qId']; ?>
				<form action="answers.php" method="POST">
					<input name="id" type="hidden" value="<?php echo $_GET['id'] ?>" />
					<input name="qId" type="hidden" value="<?php echo $_GET['qId'] ?>" />
					<input name="question" type="text" value="<?php echo $q[$qId]['question'] ?>" class="form-control mb-2" />
					<input required name="o1" type="text" value="<?php echo $q[$qId]['answer1'] ?>" class="form-control mb-2" />
					<input required name="o2" type="text" value="<?php echo $q[$qId]['answer2'] ?>" class="form-control mb-2" />
					<input required name="o3" type="text" value="<?php echo $q[$qId]['answer3'] ?>" class="form-control mb-2" />
					<input required name="o4" type="text" value="<?php echo $q[$qId]['answer4'] ?>" class="form-control mb-2" />
					<input required name="answer" type="number" value="<?php echo $a[$qId]['answer'] ?>" class="form-control mb-2" min="1" max="4" required />
					<button name="submit" type="submit" class="btn btn-success mb-4">Edit Question</button>
				</form>
			<?php endif; ?>
			<div class="table-responsive">
				<table class="table table-striped table-hover table-bordered blurred-bg">
					<thead>
						<tr>
							<th>Question</th>
							<th>A1</th>
							<th>A2</th>
							<th>A3</th>
							<th>A4</th>
							<th>Answer</th>
							<th>Actions</th>
						</tr>
					</thead>
					<tbody>
						<?php for ($i = 0; $i < count($q); $i++) { ?>
							<tr>
								<td class=""><?php echo trim($q[$i]['question']) == "" ? "<img src='$folderName/" . ($i + 1) . ".jpg' width='100px' height='auto' />" : $q[$i]['question'] ?></td>
								<td class=""><?php echo $q[$i]['answer1'] ?></td>
								<td class=""><?php echo $q[$i]['answer2'] ?></td>
								<td class=""><?php echo $q[$i]['answer3'] ?></td>
								<td class=""><?php echo $q[$i]['answer4'] ?></td>
								<td class=""><?php echo $a[$i]['answer'] ?></td>
								<td class="">
									<a href="answers.php?id=<?php echo $_GET['id'] ?>&qId=<?php echo $i; ?>" class="btn btn-success btn-sm">Edit</a>
								</td>
							</tr>
						<?php } ?>
					</tbody>
				</table>
			</div>
		</div>
	<?php } ?>
	<?php function showPapers($con)
	{ ?>
		<?php include "../includes/navbar-admin.php" ?>
		<div class="container mt-5">
			<div class="table-responsive">
				<table class="table table-striped table-hover table-bordered blurred-bg">
					<thead>
						<tr>
							<th class="ps-4 text-center">name</th>
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
										<?php echo $row['name'] ?>
									</h5>
								</td>
								<td>
									<div class="d-flex justify-content-center">
										<a class="btn btn-success btn-sm" href="answers.php?id=<?php echo $row['id'] ?>">View</a>
									</div>
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