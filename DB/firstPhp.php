<?php
$con=mysqli_connect("localhost","root","","");
if(!$con){die("Error while establishing the network.");}
$query1=mysqli_query($con,"SELECT * FROM ");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="sytlesheet" href="../ExamSystemFrontEnd/assets/css/bootstrap.min.css"/>
</head>
<body>
    <div class="row">
        <?php while($row1=mysqli_fetch_assoc($query1)) :?>
            <div class="col-3 card">
                <div class="card-body">

                </div>
            </div>
        <?php endwhile; ?>
    </div>
    <script src="../ExamSystemFrontEnd/assets/js/bootstrap.min.js"></script>
</body>
</html>