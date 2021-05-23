<?php include("../config.php");
include("../classes/ExamResult.php");

$userID = $_POST['userID'];
$examID = $_POST['examID'];
$examResult = new ExamResult($con);
return $examResult->startExam($userID, $examID);
