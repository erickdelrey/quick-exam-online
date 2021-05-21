<?php
include("includes/config.php");
include("includes/classes/Exam.php");
$examID = $_SESSION['createExamID'];
$exam = new Exam($con);
$exam->retrieveExam($examID);
include("includes/handlers/create-questions-handler.php")
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create Questions | Quick Exam Online</title>
    <link rel="stylesheet" type="text/css" href="assets/css/create-questions.css" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
    <script type="text/javascript" src="assets/js/create-questions.js"></script>
    <?php include("includes/bootstrap.php") ?>
</head>

<body>
    <div class="container">
        <div class="row">
            <div class="row">
                <h1>Exam name: <?php echo $exam->getExamName() ?></h1>
            </div>
            <div id="questionContainer" class="row">
                <form method="POST" action="create-questions.php">
                    <input type="hidden" name="questionsExamID" value=<?php echo $exam->getExamID() ?> />
                    <div id='carouselExampleDark' class='carousel slide' data-interval='false' data-bs-ride='carousel'>
                        <div class='carousel-inner'>
                            <?php
                            $examQuestionsCount = $exam->getExamQuestionsCount();
                            $choicesCount = $exam->getChoicesCount();
                            for ($x = 0; $x < $examQuestionsCount; $x++) {
                                echo "<div class='carousel-item'>";
                                echo "<div class='carousel-caption'>";
                                echo "<div class='input-group'><span class='input-group-text'>Question " . ($x + 1) . ": </span>" .
                                    "<textarea class='form-control' id='questionNumber" . $x . "' name='questions[" . $x . "][description]'></textarea></div>";
                                for ($y = 0; $y < $choicesCount; $y++) {
                                    echo "<div class='input-group'><div class='input-group-text'><input class='form-check-input mt-0' type='radio' name='questions[" . $x . "][answer]' value='" . $y . "'/></div>
                            <input type='text' class='form-control' name='questions[" . $x . "][choices" . $x . "][$y]' id=choiceNumber_" . $x . "_" . $y . "></div>";
                                }
                                echo "</fieldset>";
                                echo "</div>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <button class='carousel-dark carousel-control-prev' type='button' data-bs-target='#carouselExampleDark' data-bs-slide='prev'>
                            <span class='carousel-control-prev-icon' aria-hidden='true'></span>
                            <span class='visually-hidden'>Previous</span>
                        </button>
                        <button class='carousel-dark carousel-control-next' type='button' data-bs-target='#carouselExampleDark' data-bs-slide='next'>
                            <span class='carousel-control-next-icon' aria-hidden='true'></span>
                            <span class='visually-hidden'>Next</span>
                        </button>
                    </div>
                    <button type="submit" class='btn btn-primary' name="createQuestions">Create Questions</button>
                </form>
            </div>
        </div>
    </div>
</body>

</html>