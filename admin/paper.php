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

<?php

// * FOR SUBJECT
if (isset($_GET['subjectdelete'])) {
    $deleteQuery = mysqli_query($con, "DELETE FROM `subject` WHERE id = " . $_GET['id']) or die(mysqli_error($con));
    header("Location: class.php?class_id=" . $_GET['class_id']);
}

if (isset($_GET['subjectedit'])) {
    $isEditing = true;
    $editQuery = mysqli_query($con, "SELECT * FROM `subject` WHERE id = " . $_GET['id']);
    if (mysqli_num_rows($editQuery) == -1) {
        die("you are dumb");
    }
    $subjectEditData = mysqli_fetch_assoc($editQuery);
}

if (isset($_POST['addsubject'])) {
    $name = mysqli_escape_string($con, $_POST['name']);
    $id = mysqli_escape_string($con, $_POST['id']);
    $addQuery = mysqli_query($con, "INSERT INTO `subject` VALUES (null, '$name', '$id')") or die(mysqli_error($con));
    header("Location: class.php?class_id=" . $id);
}
if (isset($_POST['editsubject'])) {
    $id = mysqli_escape_string($con, $_POST['id']);
    $name = mysqli_escape_string($con, $_POST['name']);
    $addQuery = mysqli_query($con, "UPDATE `subject` SET `name` = '$name' WHERE id = $id") or die(mysqli_error($con));
    header("Location: class.php?class_id=" . $id);
}

?>

<?php $headerTitle = "Prospector | Index" ?>
<?php $cssFiles = "<link rel='stylesheet' href='../assets/css/student_profile.css'/>" ?>
<?php include "../includes/header.php" ?>
<div class="app">
    <?php include "../includes/navbar-admin.php" ?>
    <div class="container">
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
                            <th>name</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $query = mysqli_query($con, "SELECT * FROM `exampaper` WHERE `subject_id` = " . $_GET['subject_id']);
                        $srno = 0;
                        ?>
                        <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                            <tr>
                                <td><?php echo ++$srno; ?></td>
                                <td>
                                    <h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject"><?php echo $row['name']; ?></h5>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <!-- <a href="class.php?subjectedit=true&class_id=<?php echo $_GET['class_id'] ?>&id=<?php echo $row['id'] ?>" class="ms-2 btn btn-primary btn-sm">Edit</a> -->
                                        <a href="answers.php?id=<?php echo $row['id'] ?>" class="ms-2 btn btn-info btn-sm">View</a>
                                        <!-- <a href="class.php?subjectdelete=true&class_id=<?php echo $_GET['class_id'] ?>&id=<?php echo $row['id'] ?>" class="ms-2 btn btn-danger btn-sm">Remove</a> -->
                                    </div>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                        <!-- this like comes at the end of the loop -->
                        <tr>
                            <td colspan="4">
                                <a href="addpaper.php?subject_id=<?php echo $_GET['subject_id'] ?>" class="btn btn-success">Add Paper</a>
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>
    </div>
</div>
<?php include "../includes/footer.php" ?>