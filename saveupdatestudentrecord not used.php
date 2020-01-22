<?php
session_start();
include '../dbconfig.php';
$sy=$_SESSION['sy'];
$semester=$_SESSION['semester'];

$stud_id=$_POST['stud_id'];
$fname=$_POST['fname'];
$lname=$_POST['lname'];
$scholar=$_POST['scholar'];

echo $stud_id."aa";
mysql_query("update student_status set fname='$fname',lname='$lname',scholar_id='$scholar' where sy='$sy' and semester='$semester' and stud_id='$stud_id'");
?>
