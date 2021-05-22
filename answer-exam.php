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
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick-theme.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/answer-exam.css" />
    <link rel="stylesheet" type="text/css" href="assets/css/common.css" />
    <?php include("includes/roboto.php") ?>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script src="assets/js/answer-exam.js"></script>
    <?php include("includes/bootstrap.php") ?>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/slick-carousel/1.9.0/slick.js"></script>
</head>

<body>
    <?php include("includes/header.php") ?>
    <main>
        <!-- Modal -->
        <div id="reminderModal" class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="exampleModalLabel">Exam Reminder</h5>
                    </div>
                    <div class="modal-body">
                        Starting the exam will take your screen into full-screen mode.
                    </div>
                    <div class="modal-footer">
                        <a href="/quick-exam-online/dashboard.php" class="btn btn-secondary" role="button">Go back to Home</a>
                        <button type="button" class="btn btn-primary" id="startExamModalButton">Start Exam</button>
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
                <form method='POST' action='' id='answerExamBody'>
                <div class='container py-4'>
                    <div class='row'>
                        <div class='col-md-12 py-4'>
                            <div class='row'>
                                <h2>" . $exam->getExamName() . "</h2>
                                <input type='hidden' value='" . $exam->getExamID() . "' name='examID' />
                            </div>
                            <div class='row bg-light rounded text-center'>
                                <div class='col'>
                                    <div class='slider'>";
                                        $counter = 0;
                                        foreach ($questions as $question) {
                                            $questionClass = new Question($con);
                                            $questionClass->getQuestion($question['questionID']);
                                            $choices = $questionClass->getChoices();
                                            echo "<div class='padding-30'><p class='questionDescription'>" . $question['description'] . "</p>";
                                            foreach ($choices as $choice) {
                                                echo "<p class='choices'><input type='radio' class='form-check-input' name='answers[" . $question['questionID'] . "]' id='choice-" . $choice['choiceID'] . "' value='" . $choice['choiceID'] . "'
                                                                            onClick='updateQuestionButtonStatus(" . $counter . ")'/><label for='choice-" . $choice['choiceID'] . "'>" . $choice['description'] . "</label></p>";
                                            }
                                            $counter++;
                                            echo "</div>";
                                        }
                                    echo "</div>";
                                echo "</div>";
                            echo "</div>";
                            echo "</div>";
                        echo "</div>";
                    echo "<div class='row align-items-md-stretch'>";
                        echo "<div class='col-md-6'>";
                            echo "<div class='p-3 bg-light min-height-430 rounded'>";
                                for ($x = 0; $x < $counter; $x++) {
                                    echo "<button type='button' id='questionLink-" . $x . "' onClick='slideToQuestion(" . $x . ")' class='btn btn-secondary'>" . ($x + 1) . "</button>";
                                }
                                echo "</div>";
                            echo "</div>";
                        echo "<div class='col-md-6'>";
                            echo "<div class='p-3 bg-light min-height-430 rounded'>";
                                echo "<div id='counter'></div>";
                                echo "<div class='row'><button type='submit' class='btn btn-primary' name='submitAnswer'>SUBMIT ANSWER</button></div>";
                            echo "</div>";
                        echo "</div>";
                    echo "</div>";
                echo "</div>";
                include("includes/footer.php");
                echo "</form>";
            }
        }
        ?>
    </main>
    <script>
        // set the date we're counting down to
        var target_date = new Date(today.getFullYear(), today.getMonth(), today.getDate(), 0, 0, 0).getTime();

        // variables for time units
        var minutes, seconds;

        // get tag element
        var countdown = document.getElementById('countdown');

        // update the tag with id "countdown" every 1 second
        setInterval(function() {

            // find the amount of "seconds" between now and target
            var current_date = new Date().getTime();
            var seconds_left = (target_date - current_date) / 1000;

            // do some time calculations
            minutes = parseInt(seconds_left / 60);
            seconds = parseInt(seconds_left % 60);

            // format countdown string + set tag value
            countdown.innerHTML = '<span class="days">' + days + ' <label>Days</label></span> <span class="hours">' + hours + ' <label>Hours</label></span> <span class="minutes">' +
                minutes + ' <label>Minutes</label></span> <span class="seconds">' + seconds + ' <label>Seconds</label></span>';

        }, 1000);
    </script>
</body>
</html>