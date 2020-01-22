<?php
session_start();
include '../dbconfig.php';
$stud_id=$_POST['stud_id'];
$course_id=$_POST['course_id'];
$year_level=$_POST['year_level'];
$status=$_POST['status'];
$scholar_id=$_POST['scholar_id'];
if($stud_id!=""){
	$date=date("m/d/Y");
	$time=date('h:i a');
	$getdetail=mysql_query("select * from student where stud_id='$stud_id'") or die(mysql_error());
	$detail=mysql_fetch_array($getdetail);
	$action="Saved $detail[fname] $detail[lname]\'s status in $_SESSION[semester] $_SESSION[sy] ";
	mysql_query("insert into student_status values ('','$stud_id','$scholar_id','','$course_id','$_SESSION[semester]','$_SESSION[sy]','$year_level','$status')") or die(mysql_error());
	mysql_query("update exceeded_money set to_sy='$_SESSION[sy]',to_semester='$_SESSION[semester]'  where stud_id='$stud_id' and to_sy='' and to_semester='' and action='Advance Payment'");
	mysql_query("insert into user_log values('','$date','$time','$action','$_SESSION[user_id]')");
}
?>