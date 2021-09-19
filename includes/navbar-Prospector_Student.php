<nav class="navbar bg-primary navbar-dark py-2 justify-content-between">
    <div class="container">
        <h3>
            <a class="navbar-brand"><?php echo "Hey, ".explode(" ",$_SESSION['name'])[1]." !" ?></a>
            <h2><a class="navbar-brand"><?php echo "<b>".$_SESSION['student_department']." | ".$_SESSION['student_year']."</b>"?></a></h2>
        </h3>
        <div>
            <a href="logout.php?student_id=<?php echo $_SESSION['student_id'] ?>" class="btn btn-danger">Logout</a>
        </div>
    </div>
</nav>