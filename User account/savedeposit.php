<?php
include '../dbconfig.php';
include '../rand.php';
$date=$_POST['date'];
$user_id=$_POST['user_id'];
$amount=$_POST['amount'];
 mysql_query("insert into user_deposit values ('','$date','$amount','$user_id')") or die(mysql_error());
 ?>