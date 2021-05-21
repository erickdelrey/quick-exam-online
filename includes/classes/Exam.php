<?php

class Exam
{
    private $con;
    private $errorArray;

    private $examID;
    private $examName;
    private $examVisibility;
    private $hasDuration;
    private $examDuration;
    private $hasExpiration;
    private $expirationDateTime;
    private $showAnswersAfterExam;
    private $examQuestionsCount;
    private $examType;
    private $choicesCount;
    private $creatorID;

    public function __construct($con)
    {
        $this->con = $con;
        $this->errorArray = array();
    }

    public function retrieveExam($id)
    {
        $query = mysqli_query($this->con, "SELECT * FROM exams WHERE examID='$id'");
        $exam = mysqli_fetch_array($query);
        if ($exam != null) {
            $this->examID = $id;
            $this->examName = $exam['examName'];
            $this->examVisibility = $exam['examVisibility'];
            $this->hasDuration = $exam['hasDuration'];
            $this->examDuration = $exam['examDuration'];
            $this->hasExpiration = $exam['hasExpiration'];
            $this->expirationDateTime = $exam['expirationDateTime'];
            $this->showAnswersAfterExam = $exam['showAnswersAfterExam'];
            $this->examQuestionsCount = $exam['examQuestionsCount'];
            $this->examType = $exam['examType'];
            $this->choicesCount = $exam['choicesCount'];
            $this->creatorID = $exam['userID'];
        }
    }

    public function createExam(
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
        $creatorID
    ) {
        $this->examName = $examName;
        $this->examVisibility = $examVisibility;
        $this->hasDuration = $hasDuration;
        $this->examDuration = $examDuration;
        $this->hasExpiration = $hasExpiration;
        $this->expirationDateTime = $expirationDateTime;
        $this->showAnswersAfterExam = $showAnswersAfterExam;
        $this->examQuestionsCount = $examQuestionsCount;
        $this->examType = $examType;
        $this->choicesCount = $choicesCount;
        $this->creatorID = $creatorID;

        $this->con->query("INSERT INTO exams VALUES (DEFAULT, '$examName', '$examVisibility', 
        '$hasDuration', $examDuration, '$hasExpiration', '$expirationDateTime', '$showAnswersAfterExam', 
        $examQuestionsCount, '$examType', $choicesCount, $creatorID)");

        $examID = $this->con->insert_id;
        $this->examID = $examID;
    }

    public function getQuestions()
    {

        $results = mysqli_query($this->con, "SELECT * FROM questions WHERE examID=$this->examID ORDER BY orderNumber ASC");
        $result_set = array();

        while ($row = mysqli_fetch_assoc($results)) {
            $result_set[] = $row;
        }

        return $result_set;
    }

    public function getExamID() {
        return $this->examID;
    }

    public function getExamName() {
        return $this->examName;
    }

    public function getExamType() {
        return $this->examType;
    }

    public function getExamQuestionsCount() {
        return $this->examQuestionsCount;
    }

    public function getChoicesCount() {
        return $this->choicesCount;
    }

    public function getCreator() {
        $result = mysqli_query($this->con, "SELECT username FROM users WHERE id=$this->creatorID");
        $creator = mysqli_fetch_array($result);
        return $creator["username"];
    }
}
