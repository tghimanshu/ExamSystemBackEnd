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
    $department_id = mysqli_escape_string($con, $_SESSION['department_id']);
    $addQuery = mysqli_query($con, "INSERT INTO `classes` VALUES (null, '$year', $department_id)") or die(mysqli_error($con));
    $successMessage = "Added Department SucessFully!!";
}
if (isset($_POST['editclass'])) {
    $id = mysqli_escape_string($con, $_POST['id']);
    $year = mysqli_escape_string($con, $_POST['year']);
    $department_id = mysqli_escape_string($con, $_SESSION['department_id']);
    $addQuery = mysqli_query($con, "UPDATE `classes` SET `year` = '$year', `department_id` = $department_id WHERE id = $id") or die(mysqli_error($con));
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
    $sem = mysqli_escape_string($con, $_POST['sem']);
    $name = mysqli_escape_string($con, $_POST['name']);
    $class_id = mysqli_escape_string($con, $_POST['class_id']);
    $addQuery = mysqli_query($con, "INSERT INTO `subject` VALUES (null, $sem, '$name', '$class_id')") or die(mysqli_error($con));
    header("Location: class.php?class_id=" . $class_id);
}
if (isset($_POST['editsubject'])) {
    $id = mysqli_escape_string($con, $_POST['id']);
    $class_id = mysqli_escape_string($con, $_POST['class_id']);
    $name = mysqli_escape_string($con, $_POST['name']);
    $sem = mysqli_escape_string($con, $_POST['sem']);
    $addQuery = mysqli_query($con, "UPDATE `subject` SET `sem` = $sem, `name` = '$name' WHERE id = $id") or die(mysqli_error($con));
    header("Location: class.php?class_id=" . $class_id);
}

?>

<?php $headerTitle = "Prospector | Index" ?>
<?php $cssFiles = "<link rel='stylesheet' href='../assets/css/student_profile.css'/>" ?>
<?php include "../includes/header.php" ?>
<div class="app">
    <?php include "../includes/navbar-admin.php" ?>
    <?php if (isset($_GET['class_id'])) : ?>
        <div class="container">
            <!-- <?php include "../includes/Categories_Prospector.php" ?> -->
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
                                <th>Semester</th>
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
                                        <h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject">SEM <?php echo $row['sem']; ?></h5>
                                    </td>
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
                                        <input type='hidden' name='class_id' value="<?php echo $_GET['class_id'] ?> " />
                                        <input type='hidden' name='id' value="<?php echo isset($_GET['id']) ? $_GET['id'] : "" ?> " />
                                        <div class="me-3">
                                            <select name="sem" id="sem" class="form-control">
                                                <?php $class = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `classes` WHERE `id` = " . $_GET['class_id'])); ?>
                                                <?php if ($class['year'] == "FY") : ?>
                                                    <option value="1" <?php echo isset($isEditing) && $subjectEditData['sem'] ==  1 ? 'selected' : '' ?>>SEM 1</option>
                                                    <option value="2" <?php echo isset($isEditing) && $subjectEditData['sem'] ==  2 ? 'selected' : '' ?>>SEM 2</option>
                                                <?php elseif ($class['year'] == "SY") : ?>
                                                    <option value="3" <?php echo isset($isEditing) && $subjectEditData['sem'] ==  3 ? 'selected' : '' ?>>SEM 3</option>
                                                    <option value="4" <?php echo isset($isEditing) && $subjectEditData['sem'] ==  4 ? 'selected' : '' ?>>SEM 4</option>
                                                <?php elseif ($class['year'] == "TY") : ?>
                                                    <option value="5" <?php echo isset($isEditing) && $subjectEditData['sem'] ==  5 ? 'selected' : '' ?>>SEM 5</option>
                                                    <option value="6" <?php echo isset($isEditing) && $subjectEditData['sem'] ==  6 ? 'selected' : '' ?>>SEM 6</option>
                                                <?php endif; ?>

                                            </select>
                                        </div>
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
            <!-- <?php include "../includes/Categories_Prospector.php" ?> -->
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
                                            <select name="year" class="form-control">
                                                <option value="FY" <?php echo isset($isEditing) && $editData['year'] == "FY" ? "selected" : '' ?>>FY</option>
                                                <option value="SY" <?php echo isset($isEditing) && $editData['year'] == "SY" ? "selected" : '' ?>>SY</option>
                                                <option value="TY" <?php echo isset($isEditing) && $editData['year'] == "TY" ? "selected" : '' ?>>TY</option>
                                            </select>
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