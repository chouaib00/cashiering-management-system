<?php
session_start();
$password=md5($_POST['password']);
$stud_id=$_POST['stud_id'];
$sy=$_POST['sy'];
$semester=$_POST['semester'];
if($password==""){
	header("location:index.php");
}else
include '../dbconfig.php';
$checkpass=mysql_query("select * from user where user_id='$_SESSION[user_id]' and password='$password' and type='admin'");
 $count=mysql_num_rows($checkpass);
if($count==1){
		// $getallpayment=mysql_query("select * from schedule_of_fees,collection where collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and collection.stud_id='$stud_id'");
		// while ($row=mysql_fetch_array($getallpayment)) {
		// 	mysql_query("update collection set remark='Refunded' where stud_id='$stud_id' and sched_id='$row[sched_id]' and remark!='Canceled'");
		// }
	$getname=mysql_query("select * from student where stud_id='$stud_id'");
	$name=mysql_fetch_array($getname);
	$date=date('m/d/Y');
	$time=date('h:i a');
	mysql_query("insert into user_log values ('','$date','$time','Cancel $name[fname] $name[lname]\'s status SY $sy $semester','$_SESSION[user_id]')");
	mysql_query("update student_status set status='Cancelled',scholar_id='0' where stud_id='$stud_id' and semester='$semester' and sy='$sy'");
}else{
	echo "1";
}

?>