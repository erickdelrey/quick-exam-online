<?php
include("includes/config.php");
include("includes/util.php");
include("includes/classes/Exam.php");
$exam = new Exam($con);
include("includes/handlers/create-exam-handler.php");

if (isset($_SESSION['userLoggedIn'])) {
    if (!$_SESSION['userRoleLoggedIn'] == 'TEST_CREATOR') {
        header("Location: index.php");
    }
} else {
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html>

<head>
    <title>Create Exam | Quick Exam Online</title>
    <link rel="icon" href="assets/images/logo-icon.png" sizes="32x32" type="image/png">
    <link rel="stylesheet" type="text/css" href="assets/css/common.css" />
    <?php include("includes/roboto.php") ?>
    <?php include("includes/bootstrap.php") ?>
</head>

<body>
    <?php include("includes/header.php") ?>
    <div id="createExamContainer" class="container background-color-white rounded padding-80 margin-top-20">
        <div class="row">
            <h1>Create Exam</h1>
        </div>
        <div id="createExamBtnContainer" class="row">
            <div class="col">
                <form id="createExamForm" method="POST" action="create-exam.php">
                    <fieldset>
                        <label for="examName">Name of Exam: </label>
                        <input type="text" name="examName" id="examName" />
                    </fieldset>

                    <fieldset>
                        <label>Exam Visibility: </label>
                        <p>
                            <input type="radio" name="examVisibility" value="Public" id="publicExamVisibility" checked="checked" />
                            <label for="publicExamVisibility">Public</label>

                            <input type="radio" name="examVisibility" value="Private" id="privateExamVisibility" />
                            <label for="privateExamVisibility">Private</label>
                        </p>
                    </fieldset>

                    <fieldset>
                        <label>Has Duration: </label>
                        <p>
                            <input type="radio" name="hasDuration" value="True" id="hasExamDurationTrue" checked="checked" />
                            <label for="hasExamDurationTrue">True</label>

                            <input type="radio" name="hasDuration" value="False" id="hasExamDurationFalse" />
                            <label for="hasExamDurationFalse">False</label>
                        </p>
                    </fieldset>

                    <fieldset>
                        <label for="examDuration">Exam Duration:</label>
                        <input type="number" name="examDuration" value="10" /> minutes
                    </fieldset>

                    <fieldset>
                        <label>Has Expiration:</label>
                        <p>
                            <input type="radio" name="hasExpiration" value="Yes" id="hasExamExpirationYes" checked="checked" />
                            <label for="hasExamExpirationYes">Yes</label>

                            <input type="radio" name="hasExpiration" value="No" id="hasExamExpirationNo" />
                            <label for="hasExamExpirationNo">No</label>
                        </p>
                    </fieldset>

                    <fieldset>
                        <label id="expirationDateTime">Expiration:</label>
                        <input type="datetime-local" name="expirationDateTime" id="expirationDateTime" />
                    </fieldset>

                    <fieldset>
                        <label>Show Answers After Exam:</label>
                        <p>
                            <input type="radio" name="showAnswersAfterExam" value="Yes" id="showAnswersAfterExamYes" checked="checked" />
                            <label for="showAnswersAfterExamYes">Yes</label>

                            <input type="radio" name="showAnswersAfterExam" value="No" id="showAnswersAfterExamNo" />
                            <label for="showAnswersAfterExamNo">No</label>
                        </p>
                    </fieldset>

                    <fieldset>
                        <label id="examQuestionsCount">Number of Questions:</label>
                        <input type="number" name="examQuestionsCount" id="examQuestionsCount" value="10" />
                    </fieldset>

                    <fieldset>
                        <label>Exam Type:</label>
                        <p>
                            <input type="radio" name="examType" value="Multiple-choice" id="multipleChoice" checked="checked" />
                            <label for="multipleChoice">Multiple-choice</label>

                            <input type="radio" name="examType" value="True or False" id="trueOrFalse" />
                            <label for="trueOrFalse">True or False</label>
                        </p>
                    </fieldset>

                    <fieldset>
                        <label id="choicesCount">Number of Choices:</label>
                        <input type="number" name="choicesCount" id="choicesCount" value="4" />
                    </fieldset>

                    <button type="submit" class="btn btn-primary" name="createExam">Create Exam</button>
                </form>
            </div>
        </div>
    </div>
    <?php include("includes/footer.php") ?>
</body>

</html>