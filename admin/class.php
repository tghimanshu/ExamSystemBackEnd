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

// * FOR CLASS
if (isset($_GET['delete'])) {
    $deleteQuery = mysqli_query($con, "DELETE FROM `classes` WHERE id = " . $_GET['id']) or die(mysqli_error($con));
}

if (isset($_GET['edit'])) {
    $isEditing = true;
    $editQuery = mysqli_query($con, "SELECT * FROM `classes` WHERE id = " . $_GET['id']);
    if (mysqli_num_rows($editQuery) == -1) {
        die("you are dumb");
    }
    $editData = mysqli_fetch_assoc($editQuery);
}

if (isset($_POST['addclass'])) {
    $year = mysqli_escape_string($con, $_POST['year']);
    $division = mysqli_escape_string($con, $_POST['division']);
    $department_id = mysqli_escape_string($con, $_SESSION['department_id']);
    $addQuery = mysqli_query($con, "INSERT INTO `classes` VALUES (null, '$year', '$division', $department_id)") or die(mysqli_error($con));
    $successMessage = "Added Department SucessFully!!";
}
if (isset($_POST['editclass'])) {
    $id = mysqli_escape_string($con, $_POST['id']);
    $year = mysqli_escape_string($con, $_POST['year']);
    $division = mysqli_escape_string($con, $_POST['division']);
    $department_id = mysqli_escape_string($con, $_SESSION['department_id']);
    $addQuery = mysqli_query($con, "UPDATE `classes` SET `year` = '$year', `division` = '$division', `department_id` = $department_id WHERE id = $id") or die(mysqli_error($con));
    $successMessage = "Updated Department SucessFully!!";
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
    <?php include "../includes/navbar-Prospector_Student.php" ?>
    <?php if (isset($_GET['class_id'])) : ?>
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
                                <th>Subject Name</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($con, "SELECT * FROM `subject` WHERE `class_id` = " . $_GET['class_id']);
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
                                            <a href="class.php?subjectedit=true&class_id=<?php echo $_GET['class_id'] ?>&id=<?php echo $row['id'] ?>" class="ms-2 btn btn-primary btn-sm">Edit</a>
                                            <a href="paper.php?subject_id=<?php echo $row['id'] ?>" class="ms-2 btn btn-info btn-sm">View</a>
                                            <a href="class.php?subjectdelete=true&class_id=<?php echo $_GET['class_id'] ?>&id=<?php echo $row['id'] ?>" class="ms-2 btn btn-danger btn-sm">Remove</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <!-- this like comes at the end of the loop -->
                            <tr>
                                <td colspan="4">
                                    <form method="POST" action="class.php" class="d-flex">
                                        <input type='hidden' name='id' value="<?php echo $_GET['class_id'] ?> " />
                                        <div class="me-3">
                                            <input name="name" type="text" class="form-control" placeholder="Name" aria-label="Name" required value="<?php echo isset($isEditing) ? $subjectEditData['name'] : '' ?>" />
                                        </div>
                                        <div class="me-3">
                                            <button name="<?php echo isset($isEditing) ? 'editsubject' : 'addsubject'; ?>" type="submit" class="btn btn-success">
                                                <?php if (isset($isEditing)) {
                                                    echo "Update";
                                                } else {
                                                    echo "Add";
                                                } ?>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    <?php else : ?>
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
                                <th>Year</th>
                                <th>Division</th>
                                <th>Department</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $query = mysqli_query($con, "SELECT * FROM `classes` WHERE `department_id` = " . $_SESSION['department_id']);
                            $srno = 0;
                            ?>
                            <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                                <tr>
                                    <td><?php echo ++$srno; ?></td>
                                    <td>
                                        <h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject"><?php echo $row['year']; ?></h5>
                                    </td>
                                    <td>
                                        <h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject"><?php echo $row['division']; ?></h5>
                                    </td>
                                    <td>
                                        <h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject">
                                            <?php
                                            $dquery = mysqli_query($con, "SELECT * FROM `departments` WHERE id = " . $row['department_id']);
                                            $department = mysqli_fetch_assoc($dquery);
                                            ?>
                                            <?php echo $department['name']; ?>
                                        </h5>
                                    </td>
                                    <td>
                                        <div class="d-flex justify-content-center">
                                            <a href="class.php?edit=true&id=<?php echo $row['id'] ?>" class="ms-2 btn btn-primary btn-sm">Edit</a>
                                            <a href="class.php?class_id=<?php echo $row['id'] ?>" class="ms-2 btn btn-info btn-sm">View</a>
                                            <a href="student.php?class_id=<?php echo $row['id'] ?>" class="ms-2 btn btn-warning btn-sm">Students</a>
                                            <a href="class.php?delete=true&id=<?php echo $row['id'] ?>" class="ms-2 btn btn-danger btn-sm">Remove</a>
                                        </div>
                                    </td>
                                </tr>
                            <?php endwhile; ?>
                            <!-- this like comes at the end of the loop -->
                            <tr>
                                <td colspan="4">
                                    <form method="POST" action="class.php" class="d-flex">
                                        <?php if (isset($isEditing)) {
                                            echo "<input type='hidden' name='id' value='" . $_GET['id'] . "' />";
                                        } ?>
                                        <div class="me-3">
                                            <input name="year" type="text" class="form-control" placeholder="Year" aria-label="year" required value="<?php echo isset($isEditing) ? $editData['year'] : '' ?>" />
                                        </div>
                                        <div class="me-3">
                                            <input name="division" type="text" class="form-control" placeholder="division" aria-label="division" required value="<?php echo isset($isEditing) ? $editData['division'] : '' ?>" />
                                        </div>
                                        <div class="me-3">
                                            <button name="<?php echo isset($isEditing) ? 'editclass' : 'addclass'; ?>" type="submit" class="btn btn-success">
                                                <?php if (isset($isEditing)) {
                                                    echo "Update";
                                                } else {
                                                    echo "Add";
                                                } ?>
                                            </button>
                                        </div>
                                    </form>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </section>
        </div>
    <?php endif; ?>
</div>
<?php include "../includes/footer.php" ?>