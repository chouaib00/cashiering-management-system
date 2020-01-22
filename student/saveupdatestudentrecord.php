<?php
session_start();
include '../dbconfig.php';
$sy=$_SESSION['sy'];
$semester=$_SESSION['semester'];
$stud_id=$_POST['stud_id'];
$fname=$_POST['fname'];
$lname=$_POST['lname'];
$scholar=$_POST['scholar'];
mysql_query("update student set fname='$fname',lname='$lname' where stud_id='$stud_id'");
mysql_query("update student_status set scholar_id='$scholar' where sy='$sy' and semester='$semester' and stud_id='$stud_id'");
?>
