<?php
session_start();
include 'dbconfig.php';
$note=$_POST['note'];
$sy=$_POST['sy'];
$stud_id=$_POST['stud_id'];
$semester=$_POST['semester'];
$date=date('m/d/Y');
mysql_query("insert into note values ('','$stud_id','$sy','$semester','$note','$date','$_SESSION[user_id]')");

?>