<?php
session_start();
include '../dbconfig.php';
$newpassword=md5($_POST['newpassword']);
$password=md5($_POST['password']);
   $check=mysql_query("select * from user where password='$password' and user_id='$_SESSION[user_id]'");
if(mysql_num_rows($check)==0){
	echo "error";
}else{
	mysql_query("update user set password='$newpassword' where user_id='$_SESSION[user_id]'");
}
?>