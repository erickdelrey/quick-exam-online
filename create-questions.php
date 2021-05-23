<?php
include("includes/config.php");
include("includes/classes/Exam.php");
$examID = $_SESSION['createExamID'];
if (!$examID) {
    header("Location: index.php");
}
$exam = new Exam($con);
$exam->retrieveExam($examID);
include("includes/handlers/create-questions-handler.php")
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create Questions | Quick Exam Online</title>
    <link rel="icon" href="assets/images/logo-icon.png" sizes="32x32" type="image/png">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <?php include("includes/bootstrap.php") ?>
    <?php include("includes/roboto.php") ?>
    <link rel="stylesheet" type="text/css" href="assets/css/common.css" />
    <script type="text/javascript" src="assets/js/create-questions.js"></script>
</head>

<body>
    <?php include("includes/header.php") ?>
    <div class="container background-color-white rounded padding-80 margin-top-80">
        <div class="row">
            <h1>Exam name: <?php echo $exam->getExamName() ?></h1>
        </div>
        <div id="questionContainer" class="row">
            <form method="POST" action="create-questions.php" >
                <input type="hidden" name="questionsExamID" value="<?php echo $examID ?>" />
                <?php
                $examQuestionsCount = $exam->getExamQuestionsCount();
                $choicesCount = $exam->getChoicesCount();
                for ($x = 0; $x < $examQuestionsCount; $x++) {
                    echo "<fieldset class='padding-bottom-20'><div class='input-group'><span class='input-group-text'>Question " . ($x + 1) . ": </span>" .
                        "<textarea class='form-control' id='questionNumber" . $x . "' name='questions[" . $x . "][description]'></textarea></div>";
                    for ($y = 0; $y < $choicesCount; $y++) {
                        echo "<div class='input-group'><div class='input-group-prepend'><div class='input-group-text'><input type='radio' name='questions[" . $x . "][answer]' value='" . $y . "'/></div>
                            <input type='text' class='form-control' name='questions[" . $x . "][choices" . $x . "][$y]' id=choiceNumber_" . $x . "_" . $y . "></div></div>";
                    }
                    echo "</fieldset>";
                }
                ?>
                <button type="submit" class='btn btn-primary' name="createQuestions">Create Questions</button>
            </form>
        </div>
    </div>
    <?php include("includes/footer.php") ?>
</body>

</html>