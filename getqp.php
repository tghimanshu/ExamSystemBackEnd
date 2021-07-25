<?php require "db/db.php" ?>
<?php require "functions/functions.php" ?>
<?php require "vendor/autoload.php" ?>
<?php

$query = mysqli_query($con, "SELECT * FROM `exampaper` WHERE ID = " . $_GET['id']);
$data = mysqli_fetch_assoc($query);
print_r(json_encode(urldecode($data['Questions'])));


?>