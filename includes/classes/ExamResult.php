<?php
class ExamResult
{
    private $con;
    private $errorArray;

    private $examResultID;
    private $userID;
    private $examID;
    private $dateOfExamSubmission;
    private $correctAnswersCount;
    private $correctAnswers;
    private $wrongAnswersCount;
    private $wrongAnswers;
    private $percentage;

    public function __construct($con)
    {
        $this->con = $con;
        $this->errorArray = array();
    }

    public function saveExamResult(
        $userID,
        $examID,
        $correctAnswersCount,
        $correctAnswers,
        $wrongAnswersCount,
        $wrongAnswers,
        $percentage
    ) {

        $this->userID = $userID;
        $this->examID = $examID;
        $this->dateOfExamSubmission = date("Y-m-d h:i:sa");
        $this->correctAnswersCount = $correctAnswersCount;
        $this->correctAnswers = $correctAnswers;
        $this->wrongAnswersCount = $wrongAnswersCount;
        $this->wrongAnswers = $wrongAnswers;
        $this->percentage = $percentage;

        $this->con->query("INSERT INTO exam_results VALUES (DEFAULT, '$userID', '$examID', '$this->dateOfExamSubmission', 
        '$correctAnswersCount', '$correctAnswers', '$wrongAnswersCount', '$wrongAnswers', '$percentage')");

        $examResultID = $this->con->insert_id;
        $this->examResultID = $examResultID;
        
    }

    public function getExamResultID() {
        return $this->examResultID;
    }

    public function getExamID() {
        return $this->examID;
    }

    public function getDateOfExamSubmission() {
        return $this->dateOfExamSubmission;
    }

    public function getCorrectAnswersCount() {
        return $this->correctAnswersCount;
    }

    public function getCorrectAnswers() {
        return $this->correctAnswers;
    }

    public function getWrongAnswersCount() {
        return $this->wrongAnswersCount;
    }

    public function getWrongAnswers() {
        return $this->wrongAnswers;
    }

    public function getPercentage() {
        return $this->percentage;
    }
}
