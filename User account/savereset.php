<?php
include '../dbconfig.php';
$user_id=$_POST['user_id'];
$password=md5($_POST['password']);

mysql_query("update user set password='$password' where user_id='$user_id'");

?>