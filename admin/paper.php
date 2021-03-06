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
if (isset($_GET['delete'])) {
    $deleteQuery = mysqli_query($con, "DELETE FROM `exampaper` WHERE id = " . $_GET['id']) or die(mysqli_error($con));
    header("Location: paper.php?subject_id=" . $_GET['subject_id']);
}

if (isset($_GET['edit'])) {
    $isEditing = true;
    $editQuery = mysqli_query($con, "SELECT * FROM `exampaper` WHERE id = " . $_GET['id']);
    if (mysqli_num_rows($editQuery) == -1) {
        die("you are dumb");
    }
    $paperEditData = mysqli_fetch_assoc($editQuery);
}
if (isset($_POST['editpaper'])) {
    $id = mysqli_escape_string($con, $_POST['pid']);
    $pdate = mysqli_escape_string($con, $_POST['pdate']);
    $penddate = mysqli_escape_string($con, $_POST['penddate']);
    $addQuery = mysqli_query($con, "UPDATE `exampaper` SET `date` = '$pdate', `endTime` = '$penddate' WHERE id = $id") or die(mysqli_error($con));
    header("Location: pastpapers.php");
}

?>

<?php $headerTitle = "Papers | Index" ?>
<?php $cssFiles = "<link rel='stylesheet' href='../assets/css/student_profile.css'/>" ?>
<?php include "../includes/header.php" ?>
<div class="app">
    <?php include "../includes/navbar-admin.php" ?>
    <div class="container">
        <?php if (isset($isEditing)) : ?>
            <form action="paper.php" method="POST">
                <?php
                $startTime = new DateTime($paperEditData['date'], new DateTimeZone("Asia/Kolkata"));
                $endTime = new DateTime($paperEditData['endTime'], new DateTimeZone("Asia/Kolkata"));
                $arriveDate = date("c", strtotime($paperEditData['date']));
                $arriveEndDate = date("c", strtotime($paperEditData['endTime']));
                list($Date) = explode('+', $arriveDate);
                list($endDate) = explode('+', $arriveEndDate);
                $arriveDate = $Date;
                $arriveEndDate = $endDate;
                ?>
                <input type="hidden" value="<?php echo $paperEditData['id'] ?>" name="pid">
                <div class="input-group mb-2">
                    <label for="pdate" class="input-group-text">StartDate & time</label>
                    <input required type="datetime-local" id="pdate" class="form-control" name="pdate" value="<?php echo $arriveDate ?>" />
                </div>
                <div class="input-group mb-2">
                    <label for="penddate" class="input-group-text">End Time</label>
                    <input required type="datetime-local" id="penddate" class="form-control" name="penddate" value="<?php echo $arriveEndDate ?>" />
                </div>
                <button name="editpaper" type="submit" class="btn btn-success mb-4">Edit Paper</button>
            </form>
        <?php endif; ?>
    </div>
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
                                    <h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject">
                                        <?php echo $row['exam_type'] == 1 ? "Regular - " : ($row['exam_type'] == 2 ? "ATKT - " : "Mock - "); ?>
                                        <?php echo $row['exam_type'] == 3 ? $row['name'] : ($row['name'] == '1' ? "Internal" : "External"); ?>
                                    </h5>
                                </td>
                                <td>
                                    <div class="d-flex justify-content-center">
                                        <a href="paper.php?edit=true&subject_id=<?php echo $_GET['subject_id'] ?>&id=<?php echo $row['id'] ?>" class="ms-2 btn btn-primary btn-sm">Edit</a>
                                        <a href="answers.php?id=<?php echo $row['id'] ?>" class="ms-2 btn btn-info btn-sm">View</a>
                                        <a href="paper.php?delete=true&subject_id=<?php echo $_GET['subject_id'] ?>&id=<?php echo $row['id'] ?>" class="ms-2 btn btn-danger btn-sm">Remove</a>
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