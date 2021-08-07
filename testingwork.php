<?php require "db/db.php" ?>
<?php require "functions/functions.php" ?>
<?php require "vendor/autoload.php" ?>

<?php include "includes/header.php" ?>

<div id="realTimeData"></div>

<script>
    $(document).ready(function() {
        function getStudentData() {
            $.ajax({
                method: "GET",
                url: "testing.php?examId=8",
                success: function(data) {
                    $('#realTimeData').html(data);
                }
            })
        }
        getStudentData();
        setInterval(function() {
            console.log("object")
            getStudentData()
        }, 1000);
    });
</script>
<?php include "includes/footer.php" ?>