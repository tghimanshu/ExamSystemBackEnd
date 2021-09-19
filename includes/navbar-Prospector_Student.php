<nav class="navbar bg-primary navbar-dark py-2 justify-content-between">
    <div class="container">
        <h2>
            <a class="navbar-brand"><?php echo "<b>Hey, ".explode(" ",$_SESSION['name'])[1]." !</b>" ?></a>
        </h2>
        <div>
            <a class="navbar-brand"><?php echo "<b><h3>".$_SESSION['student_department']." | ".$_SESSION['student_year']."</h3></b>&nbsp"?></a>
            <a href="logout.php?student_id=<?php echo $_SESSION['student_id'] ?>" class="btn btn-danger">Logout</a>
        </div>
    </div>
</nav>