<?php
include("includes/config.php");
include("includes/classes/Exam.php");
include("includes/classes/ExamResult.php");
if (isset($_SESSION['userLoggedIn'])) {
    $loggedInUserID = $_SESSION['userLoggedIn'];
    $loggedInUsername = $_SESSION['usernameLoggedIn'];
    $loggedInUserRole = $_SESSION['userRoleLoggedIn'];

    $message = null;
    $exam = null;
    if (isset($_POST['searchExam'])) {
        $exam = new Exam($con);
        $exam->retrieveExam($_POST['viewExamID']);
        $questions = $exam->getQuestions($exam->getExamID());
        if ($exam->getExamID() == null) {
            $message = 'Exam not found.';
        } else if ($questions == null || empty($questions)) {
            $message = 'Exam has no questions yet.';
        } else {
        $examResult = new ExamResult($con);
        $examResult->retrieveExamResult($loggedInUserID, $exam->getExamID());
        }
    }
    if (isset($_POST['answerExam'])) {
        header("Location:answer-exam.php?examIDtoTake=" . $_POST['examIDToTake']);
    }
} else {
    header("Location:index.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Welcome to Quick Exam Online!</title>
    <link rel="icon" href="assets/images/logo-icon.png" sizes="32x32" type="image/png">
    <?php include("includes/roboto.php") ?>
    <?php include("includes/bootstrap.php") ?>
    <link rel="stylesheet" type="text/css" href="assets/css/common.css" />
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
                            <h2 class="display-5 fw-bold">Welcome <?php echo $loggedInUsername; ?>!</h2>
                            <p class="fs-4">To get started, search for the exam using its ID (Chemistry's Exam ID is <b>4</b>) or choose from the list of exams available to you.</p>
                            <p>Don't forget to answer the <a href="https://docs.google.com/forms/d/e/1FAIpQLSfyKNM_ilcYKR4ZvEJx3_lyZuQ8tgA7xU5WrjXTkL4VlpME4A/viewform" target="_blank">Usability Survey after taking an exam.</a></p>
                            <p>Please access this site using Google Chrome on your computer.</p>
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
                                        <button type='submit' class='btn btn-primary' name='answerExam' ";
                                        if ($examResult != null && $examResult->isFinished()) {
                                            echo "disabled";
                                        }
                                        echo">Answer Exam</button>
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
                        <div class="list-group">
                        <?php 
                            $publicExamination = new Exam($con);
                            $publicExams = $publicExamination->retrievePublicExams();
                            foreach ($publicExams as $publicExam) {
                                $publicExamToCheck = new Exam($con);
                                $questionsToCheck = $publicExamToCheck->getQuestions($publicExam['examID']);
                                if ($questionsToCheck != null && !empty($questionsToCheck)) {
                                    $publicExamResult = new ExamResult($con);
                                    $publicExamResult->retrieveExamResult($loggedInUserID, $publicExam['examID']);
                                    if ($publicExamResult == null || !$publicExamResult->isFinished()) {
                                        echo "<a href='answer-exam.php?examIDtoTake=";
                                        echo $publicExam['examID'];
                                        echo "' class='list-group-item list-group-item-action list-group-item-primary'>";
                                        echo $publicExam['examName'];
                                        echo "</a>";
                                    }
                                }
                            }
                        ?>
                        </div>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="p-3 bg-light min-height-430 rounded">
                        <h3>Past exams you answered</h3>
                        <div class="list-group">
                        <?php
                            $pastExamResult = new ExamResult($con);
                            $pastExamResults = $pastExamResult->retrievePastExamResults($loggedInUserID);
                            foreach ($pastExamResults as $pastResult) {
                                if ($pastResult['isFinished']) {
                                    echo "<a href='show-answers.php?examID=";
                                    echo $pastResult['examID'];
                                    echo "' class='list-group-item list-group-item-action list-group-item-secondary'>";
                                    $pastExam = new Exam($con);
                                    $pastExam->retrieveExam($pastResult['examID']);
                                    echo $pastExam->getExamName();
                                    echo "</a>";
                                }
                            }
                        ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </main>
    <?php include("includes/footer.php") ?>
</body>

</html>