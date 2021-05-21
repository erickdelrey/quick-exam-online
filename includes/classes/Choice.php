<?php
class Choice
{
    private $description;
    private $examID;
    private $questionID;
    private $isAnswer;

    private $con;
    private $errorArray;

    public function __construct($con)
    {
        $this->con = $con;
        $this->errorArray = array();
    }

    public function saveChoice($choice)
    {

        $examID = $choice['examID'];
        $questionID = $choice['questionID'];
        $isAnswer = $choice['isAnswer'];
        $description = $choice['description'];;
        $stmt = mysqli_stmt_init($this->con);
        $sql = "INSERT INTO choices VALUES (?, ?, ?, ?, ?)";

        if (!mysqli_stmt_prepare($stmt, $sql)){
            echo "SQL statement failed";
        } else {
            mysqli_stmt_bind_param($stmt, "siiis", $default, $examID, $questionID, $isAnswer, $description);
            mysqli_stmt_execute($stmt);
        }    
    }

    public function getChoice($choiceID, $examID, $questionID)
    {
        $query = mysqli_query($this->con, "SELECT * FROM choices WHERE choiceID='$choiceID' AND examID='$examID' AND questionID='$questionID'");
        $choice = mysqli_fetch_array($query);
        if ($choice != null) {
            $this->description = $choice['description'];
            $this->examID = $choice['examID'];
            $this->isAnswer = $choice['isAnswer'];
            $this->questionID = $choice['questionID'];
        }
    }

    public function getDescription()
    {
        return $this->description;
    }

    public function getExamID()
    {
        return $this->examID;
    }

    public function getQuestionID()
    {
        return $this->questionID;
    }

    public function isAnswer()
    {
        return $this->isAnswer;
    }
}
