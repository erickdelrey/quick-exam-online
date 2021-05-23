<?php
include("includes/config.php");
include("includes/classes/Exam.php");
include("includes/classes/Question.php");
include("includes/classes/Choice.php");
include("includes/classes/ExamResult.php");
$examIDtoTake = $_GET["examIDtoTake"];
$userID = $_SESSION['userLoggedIn'];
$message = "";
$exam = null;
$questions = null;
$choices = null;

if (isset($_SESSION['userLoggedIn'])) {
    if (!$_SESSION['userRoleLoggedIn'] == 'TEST_CREATOR') {
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
}

if (!empty($examIDtoTake)) {
    $exam = new Exam($con);
    $exam->retrieveExam($examIDtoTake);

    if ($exam->getExamID() == null) {
        $message = "No Exam retrieved";
    } else {
        $questions = $exam->getQuestions($exam->getExamID());
    }
} else {
    header("Location: dashboard.php");
}

$examResult = new ExamResult($con);
$examResult->retrieveExamResult($userID, $examIDtoTake);
if ($examResult->isFinished()) {
    header("Location: dashboard.php");
}

if (isset($_POST['submitAnswer'])) {
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
    $examResult->saveExamResult($userID, $examID, $correctAnswerCount, $correctAnswers, $wrongAnswerCount, $wrongAnswers, $percentage);
    header("Location: show-answers.php?examID=$examID");
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
        <script>
            $(document).ready(function(){
                $("#startExamModalButton").click(function(e){
                    e.preventDefault();
                    var examID = $("#modalExamID").val();
                    var userID = $("#userLoggedIn").val();
                
                    $.ajax({
                        url:'includes/handlers/create-exam-result-handler.php',
                        type:'post',
                        data:{examID:examID, userID:userID},
                        success:function(response){
                             $('#answerExamBody').show();
                             //$('#reminderModal').modal('hide');
                            var elem = document.getElementById('answerExamBody');
                            if (elem.requestFullscreen) {
                                elem.requestFullscreen();
                            } else if (elem.webkitRequestFullscreen) {
                                elem.webkitRequestFullscreen();
                            } else if (elem.msRequestFullscreen) {
                                elem.msRequestFullscreen();
                            }
                        },
                        failure:function(response){
                            console.log(response);
                        }
                    });
                });
            });
        </script>

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
                        <form id="startExamForm" method="POST">
                            <input type="hidden" value="<?php echo $userID; ?>" name='userLoggedIn' id="userLoggedIn" />
                            <input type="hidden" value="<?php echo $exam->getExamID(); ?>" name='modalExamID'id="modalExamID" />
                            <a href="/quick-exam-online/dashboard.php" class="btn btn-secondary" role="button">Go back to Home</a>
                            <input type="submit" class="btn btn-primary" name="startExamModalButton" id="startExamModalButton" value="Start Exam" />
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <?php
            if ($message != "") {
                echo $message;
            } else {
                echo "
                <div id='answerExamBody'>
                    <form method='POST' action=''>
                        <div class='container py-4'>
                            <div class='row'>
                                <div class='col-md-12 py-4 padding-top-50 padding-bottom-50 '>
                                    <div class='row padding-bottom-50'>
                                        <h2 id='examName'>" . $exam->getExamName() . "</h2>
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
                                                    echo "<div class='padding-30'><p class='questionDescription'> Question " . $counter +1 . ": " . $question['description'] . "</p>";
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
                            echo "<div class='row'>";
                                echo "<div class='col-md-6 answerContainer padding-right-30'>";
                                    echo "<div class='padding-30 bg-light min-height-100 rounded'>";
                                        for ($x = 0; $x < $counter; $x++) {
                                            echo "<button type='button' id='questionLink-" . $x . "' onClick='slideToQuestion(" . $x . ")' class='questionLink btn btn-secondary'>" . ($x + 1) . "</button>";
                                        }
                                    echo "</div>";
                                echo "</div>";
                                echo "<div class='col-md-6 answerContainer'>";
                                    echo "<div class='padding-30 bg-light min-height-100 rounded'>";
                                        echo "<button type='submit' class='btn btn-primary' name='submitAnswer'>SUBMIT ANSWER</button>";
                                    echo "</div>";
                                echo "</div>";
                            echo "</div>";
                        echo "</div>";
                    echo "</form>";
                include("includes/footer.php");
                echo "</div>";
            }
        ?>
    </main>
</body>
</html>