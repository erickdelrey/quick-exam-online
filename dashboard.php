<?php
include("includes/config.php");
include("includes/classes/Exam.php");
$message = null;
$exam = null;
if (isset($_POST['searchExam'])) {
    $exam = new Exam($con);
    $exam->retrieveExam($_POST['viewExamID']);
    if ($exam->getExamID() == null) {
        $message = 'Exam not found.';
    } else if (empty($exam->getQuestions()) || $exam->getQuestions() == null) {
        $message = 'Exam has no questions yet.';
    }
}
if (isset($_POST['answerExam'])) {
    header("Location:answer-exam.php?examIDtoTake=" . $_POST['examIDToTake']);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Welcome to Quick Exam Online!</title>
    <link rel="icon" href="assets/images/logo-icon.png" sizes="32x32" type="image/png">
    <link rel="stylesheet" type="text/css" href="assets/css/common.css" />
    <?php include("includes/roboto.php") ?>
    <script type="text/javascript" src="lib/js/jquery.min.js"></script>
    <?php include("includes/bootstrap.php") ?>
</head>

<body>
    <main>
        <?php include("includes/header.php") ?>
        <div class="container py-4">
            <div class="mb-4 bg-light p-5 rounded">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-3">
                            <img class="width-230 rounded" src="assets/images/logo.png" alt="logo">
                        </div>
                        <div class="col-sm-9">
                            <h2 class="display-5 fw-bold">Welcome Test Taker!</h2>
                            <p class="fs-4">To get started, search for the exam using it's ID or choose from the list of exams available to you.</p>
                            <p>Don't forget to answer the <a href="#" target="_blank">Usability Survery.</a></p>
                            <blockquote class="blockquote text-right">
                                <p class="mb-0">A little knowledge is a dangerous thing.</p>
                                <footer class="blockquote-footer">Alexander Pope in <cite title="Source Title">An Essay on Criticism </cite></footer>
                            </blockquote>
                        </div>
                    </div>
                </div>
            </div>

            <div class="row align-items-md-stretch">
                <div class="col-md-4">
                    <div class="p-3 bg-light min-height-430 rounded">
                        <h3>Search for an exam</h3>
                        <form id="searchExamForm" method="POST" action="">
                            <div class="input-group mb-3">
                                <input type="text" placeholder="Enter Exam ID" id="viewExamID" name="viewExamID" class="form-control" />
                                <button type="submit" class="btn btn-secondary" name="searchExam" id="searchExam">Search</button>
                            </div>
                        </form>
                        </form>
                        <?php
                        if ($message == null && $exam != null && !empty($exam->getExamID())) {
                            echo "
                    <div>
                        <form method='POST' action='dashboard.php'>
                            <input type='hidden' name='examIDToTake' value='" . $exam->getExamID() . "'/>
                            <p>
                                <ul class='list-group'>
                                    <li class='list-group-item'>Exam Name: <b>" . $exam->getExamName() . "</b></li>
                                    <li class='list-group-item'>Exam ID: " . $exam->getExamID() . "</li>
                                    <li class='list-group-item'>Exam Type: " . $exam->getExamType() . "</li>
                                    <li class='list-group-item'>No. of Questions: " . $exam->getExamQuestionsCount() . "</li>
                                    <li class='list-group-item'>Created by: " . $exam->getCreator() . "</li>
                                </ul>
                            </p>
                            <p class='text-align-right'>
                                <button type='submit' class='btn btn-primary' name='answerExam'>Answer Exam</button>
                            </p>
                        </form>
                    </div>";
                        } else {
                            echo $message;
                        }
                        ?>



                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light min-height-430 rounded">
                        <h3>Available exams to take</h3>



                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light min-height-430 rounded">
                        <h3>Past exams you answered</h3>



                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include("includes/footer.php") ?>
</body>

</html>