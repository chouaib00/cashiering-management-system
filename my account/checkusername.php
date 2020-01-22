<?php
session_start();
include '../dbconfig.php';
$username=$_POST['username'];
 $check=mysql_query("select * from user where username='$username' and user_id!='$_SESSION[user_id]'");
$count=mysql_num_rows($check);
echo "$count";
?>