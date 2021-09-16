<nav class="navbar bg-primary navbar-dark py-2 justify-content-between">
    <div class="container">
        <h2>
            <a class="navbar-brand"><?php echo $_SESSION['name'] ?></a>
        </h2>
        <div>
            <a href="logout.php?student_id=<?php echo $_SESSION['student_id'] ?>" class="btn btn-danger">Logout</a>
        </div>
    </div>
</nav>