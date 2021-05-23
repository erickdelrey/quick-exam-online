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
    private $examStartDate;
    private $isStarted;
    private $isFinished;

    public function __construct($con)
    {
        $this->con = $con;
        $this->errorArray = array();
    }

    public function startExam($userID, $examID) {
        $findSql = "SELECT isStarted FROM exam_results WHERE userID=$userID AND examID=$examID";
        $isStarted = $this->con->query($findSql);
        if (mysqli_num_rows($isStarted) == 0) {
            $examStartDate = date("Y-m-d h:i:sa");
            $sql = "INSERT INTO exam_results (userID, examID, examStartDate, isStarted) VALUES ($userID, $examID, '$examStartDate', 1)";
            $this->con->query($sql);
        } else {
            $examStartDate = date("Y-m-d h:i:sa");
            $sql = "UPDATE exam_results SET examStartDate='$examStartDate', isStarted=1 WHERE isStarted=0 AND userID=$userID AND examID=$examID";
            $this->con->query($sql);
        }
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

        $this->con->query("UPDATE exam_results SET dateOfExamSubmission='$this->dateOfExamSubmission', 
        correctAnswersCount='$correctAnswersCount', correctAnswers='$correctAnswers', wrongAnswersCount='$wrongAnswersCount', 
        wrongAnswers='$wrongAnswers', percentage='$percentage', isFinished=1
        WHERE userID=$userID AND examID=$examID");

        echo "UPDATE exam_results SET dateOfExamSubmission='$this->dateOfExamSubmission', 
        correctAnswersCount='$correctAnswersCount', correctAnswers='$correctAnswers', wrongAnswersCount='$wrongAnswersCount', 
        wrongAnswers='$wrongAnswers', percentage='$percentage', isFinished=1
        WHERE userID=$userID AND examID=$examID";
    }

    public function retrieveExamResult($userID, $examID) {
        $query = mysqli_query($this->con, "SELECT * FROM exam_results WHERE userID=$userID AND examID=$examID");
        $examResult = mysqli_fetch_array($query);
        if ($examResult != null) {
            $this->examResultID = $examResult['examResultID'];
            $this->userID = $examResult['userID'];
            $this->examID = $examResult['examID'];
            $this->dateOfExamSubmission = $examResult['dateOfExamSubmission'];
            $this->correctAnswersCount = $examResult['correctAnswersCount'];
            $this->correctAnswers = $examResult['correctAnswers'];
            $this->wrongAnswersCount = $examResult['wrongAnswersCount'];
            $this->wrongAnswers = $examResult['wrongAnswers'];
            $this->percentage = $examResult['percentage'];
            $this->examStartDate = $examResult['examStartDate'];
            $this->isStarted = $examResult['isStarted'];
            $this->isFinished = $examResult['isFinished'];
        }
    }

    public function retrievePastExamResults($userID) {
        $results = mysqli_query($this->con, "SELECT * FROM exam_results WHERE userID='$userID'");
        $result_set = array();
        if ($results) {
            while ($row = mysqli_fetch_assoc($results)) {
                $result_set[] = $row;
            }
        }

        return $result_set;
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

    public function isStarted() {
        return $this->isStarted;
    }

    public function isFinished() {
        return $this->isFinished;
    }
}
