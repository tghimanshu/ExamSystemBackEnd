<?php require "db/db.php" ?>
<?php require "functions/functions.php" ?>
<?php require "vendor/autoload.php" ?>
<?php
if (isset($_GET['resumesid'])) {
    $sid = $_GET['resumesid'];
    $pid = $_GET['resumepid'];
    $resumetestquery = mysqli_query($con, "UPDATE `answers` SET `submitted` = 0 WHERE `student_id` = $sid AND `paper_id` = $pid;") or die(mysqli_error($con));
    if ($resumetestquery) {
        header("Location: index.php?examId=$pid");
    }
}
?>

<div class="table-responsive mt-5 mx-5">
    <table class="table table-striped table-hover table-bordered blurred-bg">
        <thead>
            <tr>
                <th>ID</th>
                <th>Name</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <?php
        $query = mysqli_query($con, "SELECT * FROM `student`");
        $srno = 0;
        ?>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                <tr>
                    <td><?php echo ++$srno; ?></td>
                    <td>
                        <h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject">
                            <?php echo $row['name'] ?>
                        </h5>
                    </td>
                    <td>
                        <h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject">
                            <?php
                            $status = mysqli_query($con, "SELECT * FROM `answers` WHERE `student_id` = " . $row['id'] . " AND `paper_id` = " . $_GET['examId'] . ";");
                            if (mysqli_num_rows($status) == 1) {
                                $studentStatus = mysqli_fetch_assoc($status);
                                echo $studentStatus['submitted'] == 1 ? "Completed" : "Attempting";
                            } else {
                                echo "Not Yet Started";
                            }
                            ?>
                        </h5>
                    </td>
                    <td>
                        <div class="d-flex justify-content-center">
                            <?php if (isset($studentStatus)) { ?>
                                <a href="index.php?answerId=<?php echo $studentStatus['id'] ?>" class="btn btn-success btn-sm">View</a>
                            <?php } else { ?>
                                <button href="#" class="btn btn-success btn-sm" disabled>View</button>
                            <?php } ?>
                            <?php if (isset($studentStatus) && $studentStatus['submitted'] == 1) { ?>
                                <a href="index.php?resumesid=<?php echo $row['id'] ?>&resumepid=<?php echo $_GET['examId'] ?>" class="ms-2 btn btn-danger btn-sm">Resume Test</a>
                            <?php } ?>
                        </div>
                    </td>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>
</div>