<?php require "db/db.php" ?>
<?php require "vendor/autoload.php" ?>
<?php require "functions/functions.php" ?>
<?php

session_start();
if (!isset($_SESSION['username'])) {
	header("Location: login.php");
}

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
	<script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
</head>

<body>

	<div class="app">
		<?php include "includes/navbar-Prospector_Student.php" ?>
		<div class="container">
			<?php include "includes/Categories_Student.php" ?>
			<div id="main"></div>
		</div>
	</div>
	<script src=" ../ExamSystemFrontEnd/assets/js/bootstrap.min.js"></script>
	<script>
		$(document).ready(function() {
			function filterPapers(id) {
				$.ajax({
					method: "GET",
					url: "student_api.php?cat_id=" + id,
					success: function(data) {
						$('#main').html(data);
					}
				})
			}

			const urlSearchParams = new URLSearchParams(window.location.search);
			const params = Object.fromEntries(urlSearchParams.entries());
			let val = 2;
			if (urlSearchParams.get("category")) {
				switch (urlSearchParams.get("category")) {
					case "all":
						val = 1;
						break;
					case "yettostart":
						val = 2;
						break;
					case "resume":
						val = 3;
						break;
					case "completed":
						val = 4;
						break;
					case "expired":
						val = 5;
						break;

				}
			}
			setInterval(function() {
				filterPapers(val);
			}, 1000);
		});
	</script>
</body>

</html>