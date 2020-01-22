<?php
session_start();
include '../dbconfig.php';
$id=$_POST['id'];
$or=$_POST['or'];
$getfrom=mysql_query("select SUM(amount) as amount,student.stud_id,stud_number,fname,lname from collection,student where collection.stud_id=student.stud_id and receipt_num='$or'");
$from=mysql_fetch_array($getfrom);

//get the sy of transfered 
$getthesy=mysql_query("select schedule_of_fees.sy,collection.semester  from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and receipt_num='$or' and stud_id='$from[stud_id]'");
$getsyrow=mysql_fetch_array($getthesy);

 $stud=mysql_query("select * from student where stud_id='$id'");
if(mysql_num_rows($stud)==0){
echo "not found";
}else{
	

	$getto=mysql_query("select * from student where student.stud_id='$id'");
	$to=mysql_fetch_array($getto);
	$date=date('m/d/Y');
	$time=date('h:i a');
	
	mysql_query("insert into user_log values ('','$date','$time','Transfered payment $or  from $from[stud_number]  $from[fname]  $from[lname] to $to[stud_number] $to[fname]  $to[lname]','$_SESSION[user_id]')");
	$jake=mysql_query("update collection set stud_id='$id' where receipt_num='$or'") or die(mysql_error());
 	mysql_query("insert into note values('','$from[stud_id]','$getsyrow[sy]','$getsyrow[semester]','Transfered payment O.R number $or &#8369 $from[amount] pesos to $to[stud_number] $to[fname] $to[lname]','$date','$_SESSION[user_id]')") or die(mysql_error());
 }
?>