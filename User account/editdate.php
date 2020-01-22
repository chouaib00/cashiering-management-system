<?php 
include '../dbconfig.php';
$receipt_num=$_POST['receipt_num'];
$stud_id=$_POST['stud_id'];
$date=explode("-",$_POST['date']);
$date=$date[1]."/".$date[2]."/".$date[0];
 if($receipt_num!=""){
mysql_query("update collection set date='$date' where receipt_num='$receipt_num' and stud_id='$stud_id'");
}
 ?>
