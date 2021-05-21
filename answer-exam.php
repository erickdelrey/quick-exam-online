<?php
include("includes/config.php");
include("includes/classes/Exam.php");
include("includes/classes/Question.php");
include("includes/classes/Choice.php");
include("includes/classes/ExamResult.php");
$examIDtoTake = $_GET["examIDtoTake"];
$message = "";
$exam = null;
$questions = null;
$choices = null;
$examResult = null;
if (!empty($examIDtoTake)) {
    $exam = new Exam($con);
    $exam->retrieveExam($examIDtoTake);

    if ($exam->getExamID() == null) {
        $message = "No Exam retrieved";
    } else {
        $questions = $exam->getQuestions();
    }
} else {
    $message = "No Exam ID";
}

if (isset($_POST['submitAnswer'])) {
    $userID = $_SESSION['userID'];
    $examID = $_POST['examID'];
    $answers = $_POST['answers'];
    $correctAnswerCount = 0;
    $correctAnswers = '';
    $wrongAnswerCount = 0;
    $wrongAnswers = '';
    foreach ($answers as $key => $value) {
        $choice = new Choice($con);
        $choice->getChoice($value, $examID, $key);
        if ($choice->isAnswer()) {
            $correctAnswerCount++;
            $correctAnswers .= " $key";
        } else {
            $wrongAnswerCount++;
            $wrongAnswers .= " $key,$value";
        }
    }
    $percentage = ($correctAnswerCount / ($correctAnswerCount + $wrongAnswerCount)) * 100;
    $examResult = new ExamResult($con);
    echo $examResult->saveExamResult($userID, $examID, $correctAnswerCount, $correctAnswers, $wrongAnswerCount, $wrongAnswers, $percentage);
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Answer Exam | Quick Exam Online</title>
    <link rel="icon" href="assets/images/logo-icon.png" sizes="32x32" type="image/png">
    <link rel="stylesheet" type="text/css" href="assets/css/answer-exam.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/common.css" />
    <?php include("includes/roboto.php") ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="assets/js/answer-exam.js"></script>
    <?php include("includes/bootstrap.php") ?>
</head>

<body>
    <?php include("includes/header.php") ?>
    <script>
        $(document).ready(function() {
            $('#reminderModal').modal('show')
        });
    </script>
    <main>
        <!-- Modal -->
        <div id="reminderModal" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        ...
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary">Save changes</button>
                    </div>
                </div>
            </div>
        </div>
        <?php
        if ($examResult != null && $examResult->getExamResultID() != null) {
            echo "examID: " . $examResult->getExamID() . "<br/>";
            echo "correctAnswerCount: " . $examResult->getCorrectAnswersCount() . "<br/>";
            echo "correctAnswers: " . $examResult->getCorrectAnswers() . "<br/>";
            echo "wrongAnswerCount: " . $examResult->getWrongAnswersCount() . "<br/>";
            echo "wrongAnswers: " . $examResult->getWrongAnswers() . "<br/>";
            echo "percentage: " . $examResult->getPercentage() . "<br/>";
        } else {
            if ($message != "") {
                echo $message;
            } else {
                echo "
            <div class='container' id='answerExamBody'>
            <div class='row'>
            <form method='POST' action=''>
                <div class='row'>
                <h1>" . $exam->getExamName() . "</h1>
                <input type='hidden' value='" . $exam->getExamID() . "' name='examID' />
                </div>
                <div class='row'>
                <div id='carouselExampleDark' class='carousel slide' data-interval='false' data-bs-ride='carousel'>
                    <div class='carousel-inner'>";
                $counter = 0;
                foreach ($questions as $question) {
                    $questionClass = new Question($con);
                    $questionClass->getQuestion($question['questionID']);
                    $choices = $questionClass->getChoices();
                    echo "<div class='carousel-item'>";
                    echo "<div class='carousel-caption'>
                                    <p class='questionDescription'>" . $question['description'] . "</p>
                                    <div>";
                    foreach ($choices as $choice) {
                        echo "<p class='choices'><input type='radio' class='form-check-input' name='answers[" . $question['questionID'] . "]' id='choice-" . $choice['choiceID'] . "' value='" . $choice['choiceID'] . "'
                    onClick='updateQuestionButtonStatus(" . $counter . ")'/>
                                                    <label for='choice-" . $choice['choiceID'] . "'>" . $choice['description'] . "</label></p>";
                    }
                    echo "</div>";
                    echo "</div>";
                    echo "</div>";
                    $counter++;
                }
                echo "</div>";
                echo "
                    <button class='carousel-dark carousel-control-prev' type='button' data-bs-target='#carouselExampleDark' data-bs-slide='prev'>
                        <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                        <span class='visually-hidden'>Previous</span>
                    </button>
                    <button class='carousel-dark carousel-control-next' type='button' data-bs-target='#carouselExampleDark' data-bs-slide='next'>
                        <span class='carousel-control-next-icon' aria-hidden='true'></span>
                        <span class='visually-hidden'>Next</span>
                    </button>
                </div>
                </div>";

                echo "<div class='row'>
            <div class='col'>";
                for ($x = 0; $x < $counter; $x++) {
                    echo "<button type='button' class='btn btn-secondary questionLink' id='questionLink-" . $x . "' data-bs-target='#carouselExampleDark' data-bs-slide-to='" . $x . "'>" . ($x + 1) . "</button>";
                }
                echo "</div>
                <div class='col'>
                <button type='submit' class='btn btn-primary' name='submitAnswer'>SUBMIT ANSWER</button>
                </div>
            </form>
            </div>
            </div>";
            }
        }
        ?>
    </main>
    <?php include("includes/footer.php") ?>
</body>

</html>