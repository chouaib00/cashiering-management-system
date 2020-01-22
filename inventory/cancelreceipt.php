<?php
include '../dbconfig.php';
$receipt=$_POST['receipt'];
  mysql_query("update collection set remark='Cancelled',amount=0 where receipt_num='$receipt' ")or die(mysql_error());

?>