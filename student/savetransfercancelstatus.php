<?php
session_start();
include '../dbconfig.php';
$sy=$_POST['sy'];
$semester=$_POST['semester'];
$from_stud_id=$_POST['from_stud_id'];
$to_stud_id=$_POST['to_stud_id'];
if($semester!=""){

//get from
$from=mysql_query("select * from student where stud_id='$from_stud_id'");
$fromstud=mysql_fetch_array($from);

$to=mysql_query("select * from student where stud_id='$to_stud_id'");
$tostud=mysql_fetch_array($to);

//get all payment

$getpayment=mysql_query("select col_id,collection.amount from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and ((schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester') or  (schedule_of_fees.sy='$sy' and schedule_of_fees.semester='0'))  and stud_id='$from_stud_id'") or die(mysql_error());
$totalamount=0;
while ($payment=mysql_fetch_array($getpayment)) {
 $totalamount+=$payment['amount'];
 mysql_query("update collection set stud_id='$to_stud_id' where col_id='$payment[col_id]' and stud_id='$from_stud_id'");

}

$checkstud=mysql_query("select * from student,student_status where student_status.stud_id=student.stud_id and sy='$_SESSION[sy]' and semester='$_SESSION[semester]' and  (lname like '$name%' or stud_number like '$name%') and student.stud_id='$to_stud_id' and status!='Canceled' ") or die(mysql_error());
if(mysql_num_rows($checkstud)>0){

mysql_query("insert into exceeded_money values ('','$from_stud_id','','','','$sy','$semester','','','Transfered')");

$date=date('m/d/Y');
$time=date('h:i');

$action="Transfered &#8369 ".number_format($totalamount,2)." of $fromstud[stud_number] $fromstud[fname] $fromstud[lname] from $sy $semester to  $tostud[stud_number] $tostud[fname] $tostud[lname]";
mysql_query("insert into user_log values ('','$date','$time','$action','$_SESSION[user_id]')")or die(mysql_error());
mysql_query("insert into note values ('','$from_stud_id','$sy','$semester','Amount of &#8369 ".number_format($totalamount,2)." has been transfered  to  $tostud[stud_number] $tostud[fname] $tostud[lname]','$date','$_SESSION[user_id]')");
}else{
echo "not found";
}
}else{
	header("location:index.php");
}

?>