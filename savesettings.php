<?php
session_start();
include 'dbconfig.php';
$sy=$_POST['sy'];
$semester=$_POST['semester'];
$_SESSION['sy']=$sy;
$_SESSION['semester']=$semester;
  mysql_query("update user set sy='$sy',semester='$semester' where user_id='$_SESSION[user_id]' and type='admin'") or die(mysql_error());
?>