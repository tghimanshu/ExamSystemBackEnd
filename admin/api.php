<?php require "../db/db.php" ?>
<?php require "../functions/functions.php" ?>
<?php require "../vendor/autoload.php" ?>
<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
}
?>

<?php function examPapers($con)
{ ?>
    <div class="table-responsive mt-5 mx-5">
        <table class="table table-striped table-hover table-bordered blurred-bg">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Subject</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <?php
            $query = mysqli_query($con, "SELECT * FROM `exampaper` WHERE `subject_id` IN (SELECT id FROM `subject` WHERE `class_id` IN (SELECT id FROM `classes` WHERE department_id IN (SELECT id FROM `departments` WHERE id = " . $_SESSION['department_id'] . ")));");
            $srno = 0;
            ?>
            <tbody>
                <?php while ($row = mysqli_fetch_assoc($query)) : ?>
                    <?php
                    $currTime = new DateTime($timezone = "Asia/Kolkata");
                    $startTime = new DateTime($row['date'], new DateTimeZone("Asia/Kolkata"));
                    $endTime = new DateTime($row['endTime'], new DateTimeZone("Asia/Kolkata"));
                    $startTimeLeft = $currTime->diff($startTime);
                    $endTimeLeft = $currTime->diff($endTime);
                    ?>
                    <?php
                    if (
                        (($startTimeLeft->format("%i") < 29 && $startTimeLeft->invert == '0') || $startTimeLeft->invert == '1') &&
                        (($endTimeLeft->format('%i') < 29 && $endTimeLeft->invert == '1') || $endTimeLeft->invert == '0')
                    ) {
                    ?>

                        <tr>
                            <td>
                                <?php echo ++$srno; ?>
                            </td>
                            <td>
                                <h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject">
                                    <?php echo $row['exam_type'] == 1 ? "Regular - " : ($row['exam_type'] == 2 ? "ATKT - " : "Mock - "); ?>
                                    <?php echo $row['exam_type'] == 3 ? $row['name'] : ($row['name'] == '1' ? "Internal" : "External"); ?>
                                </h5>
                            </td>
                            <td>
                                <h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject">
                                    <?php $subject = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `subject` WHERE id = " . $row['subject_id'])); ?>
                                    <?php echo $subject['name'] ?>
                                </h5>
                            </td>
                            <td>
                                <div class="d-flex justify-content-center">
                                    <a href="index.php?examId=<?php echo $row['id'] ?>" class="btn btn-success btn-sm">View</a>
                                </div>
                            </td>
                        </tr>
                    <?php }
                    ?>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
    </div>
<?php } ?>

<?php function studentData($con)
{ ?>
    <div class="container mt-4">
        <?php $class_id = mysqli_fetch_assoc(mysqli_query($con, "SELECT class_id from subject WHERE id in (SELECT subject_id from exampaper where id = " . $_GET['examId'] . ")")); ?>
        <a href="index.php?mail=true&class_id=<?php echo $class_id['class_id'] ?>" class="btn btn-success">Send Credentials Mail</a>
        <a href="export.php?result=true&examId=<?php echo $_GET['examId']; ?>" class="btn btn-primary">Export Results</a>
    </div>
    <div class="table-responsive mt-5 mx-5">
        <table class="table ta:q
        ble-striped table-hover table-bordered blurred-bg">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Status</th>
                    <th>Actions</th>
                    <th>Marks</th>
                </tr>
            </thead>
            <?php
            // $query = mysqli_query($con, "SELECT * FROM `student`");
            $query = mysqli_query($con, "SELECT * FROM student WHERE class_id IN (SELECT class_id from subject WHERE id in (SELECT subject_id from exampaper where id = " . $_GET['examId'] . "))");
            $srno = 0;
            ?>
            <tbody>
                <div class="text-white">
                </div>
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
                                    if ($studentStatus['submitted'] != 1) {
                                        if (isset($_SESSION['student_' . $srno])) {
                                            if ($_SESSION['student_' . $srno] == $studentStatus['timeElapsed']) {
                                                echo " (Paused)";
                                                $paused = true;
                                            }
                                        }
                                        $_SESSION['student_' . $srno] = $studentStatus['timeElapsed'];
                                    }
                                } else {
                                    unset($studentStatus);
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
                                    <button class="btn btn-success btn-sm" disabled>View</button>
                                <?php } ?>
                                <?php if (isset($studentStatus) && $studentStatus['submitted'] == 1) { ?>
                                    <a href="index.php?resumesid=<?php echo $row['id'] ?>&resumepid=<?php echo $_GET['examId'] ?>" class="ms-2 btn btn-danger btn-sm">Resume Test</a>
                                <?php } ?>
                                <?php if (isset($studentStatus) && isset($paused) && $row['isLoggedIn'] == 1) { ?>
                                    <a href="index.php?allowLoginId=<?php echo $row['id'] ?>&pid=<?php echo $_GET['examId'] ?>" class="ms-2 btn btn-sm btn-danger">Allow Login</a>
                                <?php } ?>
                            </div>
                        </td>
                        <td>
                            <h5 class="fw-bold d-flex align-items-center justify-content-center ps-4 subject">
                                <?php
                                $studentId = (int)$row['id'];
                                $paperId =  (int)$_GET['examId'];
                                $status = mysqli_query($con, "SELECT * FROM `answers` WHERE `student_id` = $studentId AND `paper_id` = $paperId;") or die(mysqli_error($con));
                                if (mysqli_num_rows($status) == 1) {
                                    $studentStatus = mysqli_fetch_assoc($status);
                                    echo getResult($studentId, $paperId);
                                } else {
                                    unset($studentStatus);
                                    echo "0";
                                }
                                ?>
                            </h5>
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
    $webcamImages = array_reverse(explode("~", $data['webcamImages']));
    ?>
    <?php if ($webcamImages[0] != "") : ?>
        <div class="row mt-5 mx-5 d-flex justify-content-center" style="max-height: 600px;overflow-y:auto">
            <?php
            foreach ($webcamImages as $key => $value) {
            ?>
                <div class="col-6 col-md-3 col-lg-2 mb-1"><img src="<?php echo $value ?>" alt="image" width="100%" height="auto" /></div>
            <?php
            }
            ?>
        </div>
    <?php endif; ?>
    <?php
    $query = mysqli_query($con, "SELECT * FROM `exampaper` WHERE `id` = " . $data['paper_id']);
    $paperData = mysqli_fetch_assoc($query);
    $q = json_decode(urldecode($paperData['Questions']), true);
    $a = json_decode(urldecode($paperData['answers']), true);
    $date = new DateTime($paperData['date']);
    //to get the subject name
    $subjectQuery = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `subject` WHERE `id`=" . $paperData['subject_id']));
    // providing examtype and examname
    $folderName = "/admin/uploads/" . $date->format('m-d-') . ($paperData['exam_type'] == 1 ? "Regular" : ($paperData['exam_type'] == 2 ? "ATKT" : "Mock")) . $paperData['name'] == '1' ? "Internal" : ($paperData['name'] == '2' ? "External" : $paperData["name"]) . $subjectQuery['name'];

    ?>
    <?php $marks = 0; ?>
    <?php
    if ($answers) {

        foreach ($answers as $key => $answer) { ?>
        <?php if ($a[$answer->qId]['answer'] != $answer->answer && $answer->answer != 0) {
                echo 'bg-danger';
            } else if ($a[$answer->qId]['answer'] == $answer->answer) {
                $marks++;
            }
        } ?>
    <?php } ?>
    <?php if ($answers) : ?>
        <div class="table-responsive mt-2 mx-5">
            <table class="table table-striped table-hover table-bordered blurred-bg">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>question</th>
                        <th>Answer <?php echo $marks; ?></th>
                        <th>Correct Answer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($answers as $key => $answer) { ?>
                        <tr class="<?php echo $a[$answer->qId]['answer'] != $answer->answer && $answer->answer != 0 ? 'bg-danger' : ($a[$answer->qId]['answer'] == $answer->answer ? 'bg-success' : ''); ?>">
                            <td><?php echo $key + 1; ?></td>
                            <td><?php print_r(trim($q[$answer->qId]['question']) == "" ? "<img src='$folderName/" . ($answer->qId + 1) . ".jpg' width='100px' height='auto' />" : $q[$answer->qId]['question']) ?></td>
                            <td><?php print_r($answer->answer) ?></td>
                            <td><?php print_r($a[$answer->qId]['answer']) ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    <?php else : ?>
        <div class="container">
            <h1 class="text-white text-center">Questions not yet finalized.</h1>
        </div>
    <?php endif; ?>
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