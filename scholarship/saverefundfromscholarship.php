<?php
include '../dbconfig.php';
$stud_id=$_POST['stud_id'];
$amount=$_POST['amount'];
$sy=$_POST['sy'];
$semester=$_POST['semester'];
$receipt_num=$_POST['receipt_num'];
echo "$stud_id $amount $sy $semester";
 mysql_query("insert into exceeded_money values('','$stud_id','$receipt_num','$amount','$sy','$semester','','','')"); 
 
?> 