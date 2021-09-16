			<section id="allExams" class="row mt-4">
			    <?php while ($row = mysqli_fetch_assoc($query)) : ?>
			        <?php
                    $examQuery = mysqli_query($con, "SELECT * FROM `answers` WHERE student_id = " . $_SESSION['student_id'] . " AND paper_id = " . $row['id']);
                    $examData = mysqli_fetch_assoc($examQuery);
                    ?>
			        <div class="col-lg-3 col-md-6 col-12 mt-3 zoom px-2">
			            <div class="card">
			                <div class="card-header bg-primary">
			                    <h4 class="text-center text-light">
			                        <?php $subject = mysqli_fetch_assoc(mysqli_query($con, "SELECT * FROM `subject` WHERE `id` = " . $row['subject_id'])) ?>
			                        <?php echo $row['exam_type'] == 1 ? "Regular - " : ($row['exam_type'] == 2 ? "ATKT - " : "Mock - "); ?>
			                        <?php echo $row['exam_type'] == 3 ? $row['name'] : ($row['name'] == '1' ? "Internal" : "External"); ?>
			                        <?php echo ' | ' . $subject['name'] ?>
			                    </h4>
			                </div>
			                <div class="card-body">
			                    <?php
                                $date1 = date('d/m/y', strtotime($row['date']));
                                $time = date('H:i:s', strtotime($row['date']));
                                $end = date('H:i:s', strtotime($row['endTime']));
                                $currTime = new DateTime($timezone = "Asia/Kolkata");
                                $startTime = new DateTime($row['date'], new DateTimeZone("Asia/Kolkata"));
                                $endTime = new DateTime($row['endTime'], new DateTimeZone("Asia/Kolkata"));
                                $startTimeLeft = $currTime->diff($startTime);
                                $endTimeLeft = $currTime->diff($endTime);
                                ?>
			                    <h6>Date: <?php echo $date1 ?></h6>
			                    <h6>Time: <?php echo $time ?></h6>
			                    <h6>Expired: <?php echo $end ?></h6>
			                    <h6>Duration: <?php echo $endTime->diff($startTime)->format("%H:%I:%S") ?></h6>
			                    <h6>No of Questions: <?php print_r(count(json_decode(urldecode($row['Questions'])))) ?></h6>
			                    <hr />
			                    <a href="exam.php?id=<?php echo $row['id'] ?>" class="btn btn-primary d-block mx-auto <?php
                                                                                                                        echo $endTimeLeft->invert == '1' && $examData['submitted'] == '0' ? "" : ($startTimeLeft->invert == '0' ||
                                                                                                                            $endTimeLeft->invert == '1' ||
                                                                                                                            $examData['submitted'] == '1'
                                                                                                                            ? "disabled"
                                                                                                                            : "");
                                                                                                                        ?>">
			                        <?php
                                    echo isset($examData) ? ($examData['submitted'] == '1' ? "Completed" : "Resume") : ($endTimeLeft->invert == '1' ? "Expired" : "Start")
                                    ?>
			                    </a>
			                </div>
			            </div>
			        </div>
			    <?php endwhile; ?>
			</section>