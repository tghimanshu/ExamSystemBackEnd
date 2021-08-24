<?php include "../DB/db.php" ?>
<?php include "../functions/functions.php" ?>
<?php include "../vendor/autoload.php" ?>

<?php

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
    $department_id = mysqli_escape_string($con, $_POST['department_id']);
    $teacher_id = mysqli_escape_string($con, $_POST['teacher_id']);
    $addQuery = mysqli_query($con, "INSERT INTO `classes` VALUES (null, '$year', '$division', $department_id, $teacher_id)") or die(mysqli_error($con));
    $successMessage = "Added Department SucessFully!!";
}
if (isset($_POST['editclass'])) {
    $id = mysqli_escape_string($con, $_POST['id']);
    $year = mysqli_escape_string($con, $_POST['year']);
    $division = mysqli_escape_string($con, $_POST['division']);
    $department_id = mysqli_escape_string($con, $_POST['department_id']);
    $teacher_id = mysqli_escape_string($con, $_POST['teacher_id']);
    $addQuery = mysqli_query($con, "UPDATE `classes` SET `year` = '$year', `division` = '$division', `department_id` = $department_id, `teacher_id` = $teacher_id WHERE id = $id") or die(mysqli_error($con));
    $successMessage = "Added Department SucessFully!!";
}

?>

<?php $headerTitle = "Prospector | Index" ?>
<?php $cssFiles = "<link rel='stylesheet' href='../assets/css/student_profile.css'/>" ?>
<?php include "../includes/header.php" ?>
<div class="app">
    <nav class="navbar bg-primary navbar-dark py-2 justify-content-between">
        <div class="container">
            <h2>
                <a class="navbar-brand">Dashboard</a>
            </h2>
            <div>
                <a href="logout.php" class="btn btn-danger">Logout</a>
            </div>
        </div>
    </nav>
    <div class="container">
        <section id="allCategories" class="d-flex justify-content-between mt-3 tabs">
            <a class="btn btn-light d-flex align-items-center" href="index.php">
                <div class="btn rounded-circle text-light bg-success me-2" style="width: 35px; height: 35px">
                    1
                </div>
                <div class="text-light">Department</div>
            </a>
            <a class="btn btn-light d-flex align-items-center" href="Teacher.php">
                <div class="btn rounded-circle text-light bg-success me-2" style="width: 35px; height: 35px">
                    2
                </div>
                <div class="text-light">Teachers</div>
            </a>
            <a class="btn btn-light d-flex align-items-center" href="EditClass.php">
                <div class="btn rounded-circle text-light bg-info me-2" style="width: 35px; height: 35px">
                    3
                </div>
                <div class="text-light">Edit Class</div>
            </a>
            <a class="btn btn-light d-flex align-items-center" href="EditSubject.php">
                <div class="btn rounded-circle text-light bg-danger me-2" style="width: 35px; height: 35px">
                    4
                </div>
                <div class="text-light">Edit Subject</div>
            </a>
        </section>
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
                        $query = mysqli_query($con, "SELECT * FROM `classes`");
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
                                        <?php $dquery = mysqli_query($con, "SELECT * FROM `departments`;"); ?>
                                        <select name="department_id" class="form-control">
                                            <?php while ($d = mysqli_fetch_assoc($dquery)) : ?>
                                                <option value="<?php echo $d['id'] ?>" <?php echo isset($isEditing) && $editData['department_id'] == $d['id'] ? 'selected' : '' ?>><?php echo $d['name'] ?></option>
                                            <?php endwhile; ?>
                                        </select>
                                    </div>
                                    <div class="me-3">
                                        <?php $dquery = mysqli_query($con, "SELECT * FROM `departments`;"); ?>
                                        <select name="teacher_id" class="form-control">
                                            <?php while ($d = mysqli_fetch_assoc($dquery)) : ?>
                                                <option value="<?php echo $d['id'] ?>" <?php echo isset($isEditing) && $editData['department_id'] == $d['id'] ? 'selected' : '' ?>><?php echo $d['name'] ?></option>
                                            <?php endwhile; ?>
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
</div>
<?php include "../includes/footer.php" ?>