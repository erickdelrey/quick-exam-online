<?php include("includes/classes/Question.php");
include("includes/classes/Choice.php");
include("includes/config.php");
include("includes/util.php");

if (isset($_POST['createQuestions'])) {
    $counter = 0;
    $actualCounter = 0;
    $questionsExamID = $_POST['questionsExamID'];
    $questionsExamID = sanitizeInputText($questionsExamID);
    $question = new Question($con);
    foreach ($_POST['questions'] as $value) {
        $description = $value['description'];
        if ($description == null || empty($description)) {
            $actualCounter++;
            continue;
        }
        $description = sanitizeInputText($description);
        $questionID = $question->createQuestion($description, $questionsExamID, $counter);
        $choicesCounter = 0;
        $answer = $value['answer'];
        foreach ($value['choices' . $actualCounter] as $choice) {
            if ($choice == null || empty($choice)) {
                $choicesCounter++;
                continue;
            }
            $choice = sanitizeInputText($choice);
            $isAnswer = $answer == $choicesCounter ? 1 : 0;

            $choiceMap = array();
            $choiceMap['examID'] = $questionsExamID;
            $choiceMap['questionID'] = $questionID;
            $choiceMap['isAnswer'] = $isAnswer;
            $choiceMap['description'] = $choice;
            
            $choice = new Choice($con);
            $choice->saveChoice($choiceMap);

            $choicesCounter++;
        }
        $counter++;
        $actualCounter++;
    }
    //unset($_SESSION['createExamID']);
    header("Location: dashboard.php");
}
