<?php
include 'dbconfig.php';
$course_id=$_POST['course_id'];
$sy=$_POST['sy'];
$semester=$_POST['semester'];
$year=$_POST['year'];
echo "$course_id $sy $semester $year";
mysql_query("delete from schedule_of_fees where sy='$sy' and semester='$semester' and year_level='$year' and course_id='$course_id'") or die(mysql_error());
?>