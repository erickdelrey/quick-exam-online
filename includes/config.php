<?php
ob_start();
if(!isset($_SESSION)) 
{ 
    session_start();
    $_SESSION['userID'] = 1;
} 
$timezone = date_default_timezone_set("Asia/Manila");
$con = mysqli_connect("127.0.0.1", "root", "", "quick_exam_online");
if (mysqli_connect_errno()) {
    echo "Failed to connect: " . mysqli_connect_errno();
}
