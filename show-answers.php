<?php
include("includes/config.php");
include("includes/classes/Exam.php");
include("includes/classes/Question.php");
include("includes/classes/ExamResult.php");

$examID = $_GET["examID"];
$examName = null;
if (!isset($_SESSION['userLoggedIn'])) {
    header("Location:index.php");
} else if (!empty($examID)) {
    $exam = new Exam($con);
    $userID = $_SESSION['userLoggedIn'];
    $exam->retrieveExam($examID);
    $questions = $exam->getQuestions($examID);
    $examResult = new ExamResult($con);
    $examResult->retrieveExamResult($userID, $examID);
    if ($examResult->getExamID() == null || empty($examResult->getExamID()) || !$examResult->isFinished()) {
        header("Location:dashboard.php");
    }
    $correctAnswers = $examResult->getCorrectAnswers();
    $correctAnswers = explode(" ", $correctAnswers);

    $wrongAnswers = $examResult->getWrongAnswers();
    $wrongAnswers = explode(" ", $wrongAnswers);
    $wrongAnswersMap = array();
    foreach ($wrongAnswers as $wrongAnswer) {
        $wrongAnswerArray = explode(",", $wrongAnswer);
        if (count($wrongAnswerArray ) == 2) {
            $question = $wrongAnswerArray[0];
            $answer = $wrongAnswerArray[1];
            $wrongAnswersMap[$question] = $answer;
        }
    }
} else {
    header("Location:dashboard.php");
}
?>



<!DOCTYPE html>
<html>
<head>
    <title>Show Answers | Quick Exam Online</title>
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
                        <div class="col-sm-6">
                            <h1>Exam Result</h1>
                            <dl class="row">
                            <?php
                                echo "<dt class='col-sm-3'>";
                                echo "Exam Name:";
                                echo "</dt>";
                                echo "<dd class='col-sm-9'>";
                                echo $exam->getExamName();
                                echo "</dd>";

                                echo "<dt class='col-sm-3'>";
                                echo "Result:";
                                echo "</dt>";
                                echo "<dd class='col-sm-9'>";
                                echo $examResult->getCorrectAnswersCount() . "/" . $exam->getExamQuestionsCount();
                                echo "</dd>";

                                echo "<dt class='col-sm-3'>";
                                echo "Percentage:";
                                echo "</dt>";
                                echo "<dd class='col-sm-9'>";
                                echo round($examResult->getPercentage(), 1) . "%";
                                echo "</dd>";

                                echo "<dt class='col-sm-3'>";
                                echo "Taken on:";
                                echo "</dt>";
                                echo "<dd class='col-sm-9'>";
                                echo $examResult->getDateOfExamSubmission();
                                echo "</dd>";
                            ?>
                            </dl>
                        </div>
                        <div class="col-sm-6">
                            <?php 
                            $counter = 0;
                            foreach ($questions as $question) {
                                $questionClass = new Question($con);
                                $questionID = $question['questionID'];
                                $questionClass->getQuestion($questionID);
                                $choices = $questionClass->getChoices();
                                echo "<div class='padding-top-30'>";
                                    echo "<h5>";
                                    echo "Question ";
                                    echo $counter + 1;
                                    echo ": " . $question['description'];
                                        if (in_array($questionID, $correctAnswers)) {
                                            echo "<img src='assets/images/icons/correct.png' alt='correct'/>";
                                        } else if ($wrongAnswersMap[$questionID]) {
                                            echo "<img src='assets/images/icons/wrong.png' alt='wrong'/>";
                                        }
                                    echo "</h5>";
                                echo "</div>";
                                echo "<ul class='list-group'>";
                                foreach ($choices as $choice) {
                                    echo "<li class='list-group-item ";
                                    if ($choice['isAnswer']) {
                                        echo "list-group-item-success";
                                    } else if ($wrongAnswersMap[$questionID] != null && $wrongAnswersMap[$questionID] == $choice['choiceID']) {
                                        echo "list-group-item-danger";
                                    }
                                    echo "'>" . $choice['description'] . "</li>";
                                }
                                echo "</ul>";
                                $counter++;
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