<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/admin/"><?php echo isset($headerTitle) ? explode(" | ", $headerTitle)[0] : "Dashbard"; ?></a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div>
            <a href="logout.php" class="btn btn-danger">Logout</a>
        </div>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-0 ms-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/admin">Home</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/class.php">Add Class & Subjects</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/addpaper.php">Add Paper</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/addstudents.php">Add Student</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="/admin/pastpapers.php">Past Papers</a>
                </li>
            </ul>
        </div>
    </div>
</nav>