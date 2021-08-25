<?php include "../DB/db.php" ?>
<?php include "../functions/functions.php" ?>
<?php include "../vendor/autoload.php" ?>

<?php $headerTitle = "Add Departments | Index" ?>
<?php $cssFiles = "<link rel='stylesheet' href='../assets/css/student_profile.css'/>" ?>
<?php include "../includes/header.php" ?>
<div class="app">
    <?php include "../includes/navbar-Prospector_Student.php" ?>
    <div class="container">
        <?php include "../includes/Categories_Prospector.php" ?>
        <section id="allDepartment">
            <!-- Bascially there will be two loop first loop will take all the streams and second one take all teacher  from iterated stream loop -->
            <div class="container blurred-bg" style="border-radius: 10px">
                <h1 class="text-center text-light mt-5">Add New Students</h1>
                <form action="addstudents.php" method="POST" enctype="multipart/form-data">
                    <div class="input-group mb-2">
                        <label for="pclass" class="input-group-text">Class</label>
                        <input required type="text" id="pclass" class="form-control" placeholder="Enter Class Name" name="pclass" />
                    </div>

                    <div class="input-group mb-2">
                        <label for="psubject" class="input-group-text">subject</label>
                        <input required type="text" id="psubject" class="form-control" placeholder="Enter Subject Name" name="psubject" />
                    </div>
                    <div class="input-group mb-2">
                        <label for="pdate" class="input-group-text">StartDate & time</label>
                        <input required type="datetime-local" id="pdate" class="form-control" name="pdate" />
                    </div>
                    <div class="input-group mb-2">
                        <label for="penddate" class="input-group-text">End Time</label>
                        <input required type="datetime-local" id="penddate" class="form-control" name="penddate" />
                    </div>
                </form>
            </div>
        </section>
    </div>
</div>
<?php include "../includes/footer.php" ?>