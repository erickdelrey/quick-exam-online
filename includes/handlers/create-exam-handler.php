<?php

if (isset($_POST['createExam'])) {
    $examName = sanitizeInputText($_POST['examName']);
    $examVisibility = sanitizeInputText($_POST['examVisibility']);
    $hasDuration = sanitizeInputText($_POST['hasDuration']);
    $examDuration = sanitizeInputText($_POST['examDuration']);
    $hasExpiration = sanitizeInputText($_POST['hasExpiration']);
    $expirationDateTime = sanitizeInputText($_POST['expirationDateTime']);
    $showAnswersAfterExam = sanitizeInputText($_POST['showAnswersAfterExam']);
    $examQuestionsCount = sanitizeInputText($_POST['examQuestionsCount']);
    $examType = sanitizeInputText($_POST['examType']);
    $choicesCount = sanitizeInputText($_POST['choicesCount']);

    $userID = $_SESSION['userID'];

    $exam->createExam(
        $examName,
        $examVisibility,
        $hasDuration,
        $examDuration,
        $hasExpiration,
        $expirationDateTime,
        $showAnswersAfterExam,
        $examQuestionsCount,
        $examType,
        $choicesCount,
        $userID
    );

    $_SESSION['createExamID'] = $exam->getExamID();
    header("Location: create-questions.php");
}
