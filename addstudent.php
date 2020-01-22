<?php
session_start();
include('dbconfig.php');
$sy=$_SESSION['sy'];
$user_id=$_SESSION['user_id'];
$studnumber=$_POST['studnumber'];
$semester=$_SESSION['semester'];
$fname=$_POST['fname'];
$lname=$_POST['lname'];
$year=$_POST['year'];
$course=$_POST['course'];
$status=$_POST['status'];
$date=date('m/d/y h:i a');
$time=date('h:i a');
$checkpoint=0;
if($studnumber==""){
}else{
	$checknumber=mysql_query("select * from student where stud_number='$studnumber'");
	if(mysql_num_rows($checknumber)==0){

	}else{
		$checkpoint=1;
	}
}
if($checkpoint==0){
mysql_query("insert into student values ('','$studnumber','$fname','$lname')");
$getstudent=mysql_query("select * from student where fname='$fname' and lname='$lname' order by stud_id desc limit 1") or die(mysql_error());
$studrow=mysql_fetch_array($getstudent);
mysql_query("insert into student_status values('','$studrow[stud_id]','0','','$course','$semester','$sy','$year','$status')") or die(mysql_error());
mysql_query("insert into user_log values ('','$date','$time','Added <a>$lname\, $fname</a>','$user_id')") or die(mysql_error());
echo $studrow['stud_id'];
}else{
	echo "existed";
}
?>