<?php include "../DB/db.php" ?>
<?php include "../functions/functions.php" ?>
<?php include "../vendor/autoload.php" ?>

<?php
// * SESSION
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}
?>


<?php $headerTitle = "Prospector | Index" ?>
<?php $cssFiles = "<link rel='stylesheet' href='../assets/css/student_profile.css'/>" ?>
<?php include "../includes/header.php" ?>
<div class="app">
    <?php include "../includes/navbar-Prospector_Student.php" ?>
    <div class="container">
        <?php include "../includes/Categories_Prospector.php" ?>
        <section id="allDepartment">
            <!-- Bascially there will be two loop first loop will take all the streams and second one take all teacher  from iterated stream loop -->
            <?php
            if (isset($successMessage)) {
                echo "<div class='alert alert-success'>$successMessage</div>";
            }
            ?>
            <div class="table-responsive mt-5 mx-5">
                <table class="table table-striped table-hover table-bordered blurred-bg">
                    <thead>
                        <tr>
                            <th>Sr.No</th>
                            <th>Roll No</th>
                            <th>Full Name</th>
                            <th>Email</th>
                            <th>Password</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($con, "SELECT * FROM `student` WHERE `class_id` = " . $_GET['class_id']);
                        $srno = 0;
                        ?>
                        <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                            <tr>
                                <td><?php echo ++$srno; ?></td>
                                <td>
                                    <h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject"><?php echo $row['rollno']; ?></h5>
                                </td>
                                <td>
                                    <h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject"><?php echo $row['name']; ?></h5>
                                </td>
                                <td>
                                    <h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject"><?php echo $row['email']; ?></h5>
                                </td>
                                <td>
                                    <h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject"><?php echo $row['pwd']; ?></h5>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <!-- this like comes at the end of the loop -->
                        <tr>
                            <td colspan="5">
                                <a href="addstudents.php?class_id=<?php echo $_GET['class_id'] ?>" class="btn btn-success">Add Student</a>
                                <a href="addstudents.php?delete=true&class_id=<?php echo $_GET['class_id'] ?>" class="btn btn-danger">Delete All Student</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<?php include "../includes/footer.php" ?>