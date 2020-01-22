<?php
session_start();
include '../dbconfig.php';
$stud_id=$_POST['stud_id'];
 $sy=$_POST['sy'];
$semester=$_POST['semester'];
$action=$_POST['action'];
$amount=$_POST['amount'];
echo "$stud_id $semester $sy";
$date=date('m/d/Y');
if($stud_id!=""){
if($action=="Refund"){
	mysql_query("update collection set remark='Refunded' where stud_id='$stud_id' and semester='$semester' and sy='$sy'");
	mysql_query("insert into note values ('','$stud_id','$sy','$semester','Payments in this semester have been refunded','$date','$_SESSION[user_id]')") or die(mysql_error());
 	mysql_query("insert into exceeded_money values ('','$stud_id','','','','$sy','$semester','','','$action')");
}else{
  	mysql_query("insert into exceeded_money values ('','$stud_id','','','$amount','$sy','$semester','','','Advance Payment')");

}
}
?>