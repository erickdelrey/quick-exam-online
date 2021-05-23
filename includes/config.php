<?php
ob_start();
if(!isset($_SESSION)) 
{ 
    session_start();
} 
$timezone = date_default_timezone_set("Asia/Manila");
$con = mysqli_connect("127.0.0.1", "root", "P@sswordmoodledude123", "quick_exam_online");
if (mysqli_connect_errno()) {
    echo "Failed to connect: " . mysqli_connect_errno();
}