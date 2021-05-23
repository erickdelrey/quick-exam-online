<header>
    <nav class="navbar navbar-expand-md bg-dark">
        <div class="container-fluid">
            <div class="collapse navbar-collapse" id="navbarCollapse">
                <ul class="navbar-nav me-auto mb-2 mb-md-0">
                    <li class="nav-item">
                        <a class="nav-link text-white" href="/quick-exam-online/dashboard.php">Home</a>
                    </li>
                    <?php
                        if ($_SESSION['userRoleLoggedIn'] == 'TEST_CREATOR') {
                            echo "<li class='nav-item'>
                                <a class='nav-link text-white' href='create-exam.php'>Create Exam</a>
                            </li>";
                        }
                    ?>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="https://docs.google.com/forms/d/e/1FAIpQLSfyKNM_ilcYKR4ZvEJx3_lyZuQ8tgA7xU5WrjXTkL4VlpME4A/viewform">Usability Survey</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-white" href="#">About</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link disabled" href="#" tabindex="-1" aria-disabled="true">Donate</a>
                    </li>
                </ul>
                <a class="btn btn-outline-secondary" href="logout.php">Logout</a>
            </div>
        </div>
    </nav>
</header>