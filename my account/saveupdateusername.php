<?php
session_start();
include '../dbconfig.php';
$username=$_POST['username'];
$password=md5($_POST['password']);
$name=$_POST['name'];
 $check=mysql_query("select * from user where password='$password' and user_id='$_SESSION[user_id]'");
if(mysql_num_rows($check)==0){
	echo "error";
}else{
	mysql_query("update user set name='$name',username='$username' where user_id='$_SESSION[user_id]'");
}
?>