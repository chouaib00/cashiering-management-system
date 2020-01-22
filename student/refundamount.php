<?php
session_start();
include '../dbconfig.php';
$sy=$_POST['sy'];
$semester=$_POST['sem'];
$stud_id=$_POST['id'];
$getallpayment=mysql_query("select * from schedule_of_fees,collection where collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and collection.stud_id='$stud_id'") or die(mysql_error());

 while ($row=mysql_fetch_array($getallpayment)) {
	mysql_query("update collection set remark='Refunded' where stud_id='$stud_id' and sched_id='$row[sched_id]' and remark!='Canceled'");
}

 ?>