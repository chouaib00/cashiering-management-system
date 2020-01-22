<?php
include 'dbconfig.php';
$courses=$_POST['courses'];
$arrcourses=explode("/-/", $courses);
$courseslen=count($arrcourses);

$tuition=$_POST['tuition'];
$arrtuition=explode("/-/", $tuition);
$tuitionlen=count($arrtuition);

$dept=$_POST['dept'];
$arrdept=explode("/-/", $dept);
$deptlen=count($arrdept);

$reg=$_POST['reg'];
$arrreg=explode("/-/", $reg);
$reglen=count($arrreg);

$lab=$_POST['lab'];
$arrlab=explode("/-/", $lab);
$lablen=count($arrlab);
$sy=$_POST['sy'];
$sem=$_POST['sem'];
$year=$_POST['year'];
$coursestart=1;
//check course if already exist
while ( $coursestart<$courseslen) {
	$ccheck=mysql_query("select course_id from course where description='$arrcourses[$coursestart]'") or die(mysql_error());
	$cresult=mysql_num_rows($ccheck);
	if($cresult==1){
		$getcourseid=mysql_fetch_array($ccheck);
		$cid=$getcourseid['course_id'];

		$arrtuition2=explode("-]",$arrtuition[$coursestart]);
		$arrreg2=explode("-]",$arrreg[$coursestart]);
		$arrlab2=explode("-]",$arrlab[$coursestart]);
		//0 description
		//1 course
		//2 amount
		mysql_query("insert into schedule_of_fees values ('','$arrtuition2[0]','$arrtuition2[2]','tui','$cid','$year','$sy','$sem')") or die(mysql_error());
		mysql_query("insert into schedule_of_fees values ('','$arrreg2[0]','$arrreg2[2]','tui','$cid','$year','$sy','$sem')") or die(mysql_error());
		mysql_query("insert into schedule_of_fees values ('','$arrlab2[0]','$arrlab2[2]','tui','$cid','$year','$sy','$sem')") or die(mysql_error());
	}else{
		mysql_query("insert into course values ('','$arrcourses[$coursestart]','$arrdept[$coursestart]')");
		$getcourse=mysql_query("select course_id from course where description='$arrcourses[$coursestart]' order by course_id desc limit 1");
		$newcourseid=mysql_fetch_array($getcourse);
		$arrtuition2=explode("-]",$arrtuition[$coursestart]);
		$arrreg2=explode("-]",$arrreg[$coursestart]);
		$arrlab2=explode("-]",$arrlab[$coursestart]);
		//0 description
		//1 course
		//2 amount
		mysql_query("insert into schedule_of_fees values ('','$arrtuition2[0]','$arrtuition2[2]','tui','$newcourseid[course_id]','$year','$sy','$sem')") or die(mysql_error());
		mysql_query("insert into schedule_of_fees values ('','$arrreg2[0]','$arrreg2[2]','tui','$newcourseid[course_id]','$year','$sy','$sem')") or die(mysql_error());
		mysql_query("insert into schedule_of_fees values ('','$arrlab2[0]','$arrlab2[2]','tui','$newcourseid[course_id]','$year','$sy','$sem')") or die(mysql_error());

	}
echo "the loop";
	$coursestart++;
}
?>