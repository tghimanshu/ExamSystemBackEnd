<?php
$con=mysqli_connect("localhost","root","","Demo1");
if(!$con)
{
    echo "Error:";
    die("Error while establishing the server.");
} 
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../../ExamSystemFrontEnd/assets/css/bootstrap.min.css" />
</head>
<body>
<div class="container">
        <div class="row">
            <div class="col-3 body">
                <div class="card-body">
                    <h4>Name : <?php echo "Aditya"?></h4>
                    <a href="http://www.google.com" class="btn btn-success btn-lg">attempt</a>
                </div>
            </div>
        </div>
        <div class="table-responsive">
            <table>
                <caption>Student Info</caption>
                <thead>
                    <th>Name</th>
                    <th>core Java</th>
                    <th>Computer Graphics</th>
                    <th>Software Engineering</th>
                    <th>Embeded System</th>
                    <th>Computer Oriented Statistical Technique</th>
                    <th>Percentage</th>
                </thead>
                <?php $query=mysqli_query($con,"SELECT * FROM marksheet")?>
                <?php while($row=mysqli_fetch_assoc($query)):?>
                <tr>
                    <td><?php echo $row['Name'] ?></td>
                    <td><?php echo $row['CoreJava'] ?></td>
                    <td><?php echo $row['ComputerGraphics'] ?></td>
                    <td><?php echo $row['SoftwareEngineering'] ?></td>
                    <td><?php echo $row['EmbeddedSystem'] ?></td>
                    <td><?php echo $row['Cost'] ?></td>
                    <td><?php echo $row['Percentage'] ?></td>
                </tr>
                <?php endwhile;?>
                <a href="student.php?name=<?php echo $row['name']?>&task=performed"></a>
            </table>
        </div>
        <?php $query1=mysqli_query($con,"SELECT * From marksheet")?>
                <div class="row">
                    <?php while($row1=mysqli_fetch_assoc($query1)):?>)
                            <div class="col-3 card">
                                <div class="card-body">
                                <h4>Name: <?php echo $row1['Name'] ?></h4>
                                <t4>Core Java: <?php echo $row1['CoreJava'] ?></t4>
                                <t4>Computer Graphics: <?php echo $row1['ComputerGraphics'] ?></t4>
                                <t4>Software Engineering: <?php echo $row1['SoftwareEngineering'] ?></t4>
                                <t4>Embedded System: <?php echo $row1['EmbeddedSystem'] ?></t4>
                                <t4>Computer Oriented Statistical Technique: <?php echo $row1['Cost'] ?></t4>
                                <t4>Percentage: <?php echo $row1['Percentage'] ?></t4>
                                </div>
                            </div>
                    <?php endwhile;?>
                </div>
    </div>
    <input type="datetime-local" name="dateTime"/>
    <script src="../../ExamSystemFrontEnd/assets/js/bootstrap.min.js"></script>
</body>
</html>
