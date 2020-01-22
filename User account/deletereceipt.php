<?php
include '../dbconfig.php';
$receipt_num=$_POST['receipt_num'];
$stud_id=$_POST['stud_id'];
if($stud_id!=""){
	mysql_query("delete from collection where receipt_num='$receipt_num' and stud_id='$stud_id'");
}
?>