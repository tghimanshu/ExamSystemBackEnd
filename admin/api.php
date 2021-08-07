<?php require "../db/db.php" ?>
<?php require "../functions/functions.php" ?>
<?php require "../vendor/autoload.php" ?>

<?php function examPapers($con)
{ ?>
    <div class="table-responsive mt-5 mx-5">
        <table class="table table-striped table-hover table-bordered blurred-bg">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <?php
            $query = mysqli_query($con, "SELECT * FROM `exampaper`");
            $srno = 0;
            ?>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                    <tr>
                        <td><?php echo ++$srno; ?></td>
                        <td>
                            <h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject">
                                <?php echo $row['Subject'] ?>
                            </h5>
                        </td>
                        <td>
                            <div class="d-flex justify-content-center">
                                <a href="index.php?examId=<?php echo $row['ID'] ?>" class="btn btn-success btn-sm">View</a>
                            </div>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    </div>
<?php } ?>

<?php function studentData($con)
{ ?>
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
                                $studentId = (int)$row['id'];
                                $paperId =  (int)$_GET['examId'];
                                $status = mysqli_query($con, "SELECT * FROM `answers` WHERE `student_id` = $studentId AND `paper_id` = $paperId;") or die(mysqli_error($con));

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

<?php } ?>

<?php function studentAnswers($con)
{ ?>
    <?php
    $query = mysqli_query($con, "SELECT * FROM `answers` WHERE `id` = " . $_GET['answerId']);
    $data = mysqli_fetch_assoc($query);
    $answers = json_decode(urldecode($data['answers']));
    $webcamImages = explode("~", $data['webcamImages']);
    ?>
    <div class="row mt-5 mx-5">
        <?php
        foreach ($webcamImages as $key => $value) {
        ?>
            <div class="col-2 mb-1"><img src="<?php echo $value ?>" alt="image" width="100%" height="auto" /></div>
        <?php
        }
        ?>
    </div>
    <div class="table-responsive mt-2 mx-5">
        <table class="table table-striped table-hover table-bordered blurred-bg">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>question</th>
                    <th>Answer</th>
                    <th>Correct Answer</th>
                </tr>
            </thead>
            <tbody>
                <?php
                $query = mysqli_query($con, "SELECT * FROM `exampaper` WHERE `ID` = " . $data['paper_id']);
                $paperData = mysqli_fetch_assoc($query);
                $q = json_decode(urldecode($paperData['Questions']), true);
                $a = json_decode(urldecode($paperData['answers']), true);
                $date = new DateTime($paperData['date']);
                $folderName = "/admin/uploads/" . $date->format('m-d-') . $paperData['Class'] . $paperData['Subject'];

                ?>
                <?php foreach ($answers as $key => $answer) { ?>
                    <tr class="<?php echo $a[$answer->qId]['answer'] != $answer->answer && $answer->answer != 0 ? 'bg-danger' : ($a[$answer->qId]['answer'] == $answer->answer ? 'bg-success' : ''); ?>">
                        <td><?php echo $key; ?></td>
                        <td><?php print_r(trim($q[$answer->qId]['question']) == "" ? "<img src='$folderName/" . ($answer->qId + 1) . ".jpg' width='100px' height='auto' />" : $q[$answer->qId]['question']) ?></td>
                        <td><?php print_r($answer->answer) ?></td>
                        <td><?php print_r($a[$answer->qId]['answer']) ?></td>
                    </tr>
                <?php } ?>
            </tbody>
        </table>
    </div>
<?php } ?>

<?php
if (isset($_GET['examId'])) {
    studentData($con);
} else if (isset($_GET['answerId'])) {
    studentAnswers($con);
} else {
    examPapers($con);
}
?>