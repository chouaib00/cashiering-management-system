<?php
session_start();
include '../dbconfig.php';
$user_id=$_SESSION['user_id'];
$name=$_POST['name'];
$amount=$_POST['amount'];
$date=date('m/d/y');
$time=date('h:i a');
mysql_query("insert into scholarship values ('','$name','$amount')");
mysql_query("insert into user_log values ('','$date','$time','Added $name scholarship.','$user_id')");
?>