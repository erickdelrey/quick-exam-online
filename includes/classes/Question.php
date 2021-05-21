<?php
class Question
{
    private $con;
    private $errorArray;

    private $description;
    private $examID;
    private $orderNumber;
    private $questionID;

    public function __construct($con)
    {
        $this->con = $con;
        $this->errorArray = array();
    }

    public function createQuestion($description, $examID, $orderNumber)
    {
        $this->description = $description;
        $this->examID = $examID;
        $this->orderNumber = $orderNumber;

        $this->con->query("INSERT INTO questions VALUES 
        (DEFAULT, '$examID', '$description', '$orderNumber')");

        $questionID = $this->con->insert_id;
        return $questionID;

    }

    public function getQuestion($id)
    {
        $query = mysqli_query($this->con, "SELECT * FROM questions WHERE questionID='$id'");
        $question = mysqli_fetch_array($query);
        if ($question != null) {
            $this->description = $question['description'];
            $this->examID = $question['examID'];
            $this->orderNumber = $question['orderNumber'];
            $this->questionID = $question['questionID'];
        }
    }

    public function getChoices() {
        $results = mysqli_query($this->con, "SELECT * FROM choices WHERE examID=$this->examID AND questionID=$this->questionID");
        $result_set = array();

        while ($row = mysqli_fetch_assoc($results)) {
            $result_set[] = $row;
        }

        return $result_set;
    }
}
