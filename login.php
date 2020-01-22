<?php
session_start();
include('dbconfig.php');
$username=$_POST['username'];
$pass=md5($_POST['password']);

$user = mysql_query("select * from user where username='$username' and password='$pass'");
$count=mysql_num_rows($user);
$row=mysql_fetch_array($user);

if ($count==1){
	if($row['status']==0){
			header("location:index.php?msg=Account is deactivated.");
	}else{
		$_SESSION['user_id']=$row['user_id'];
			$_SESSION['type']=$row['type'];
			$_SESSION['date']=date('m/d/Y');
		// if($row['username']=='jakecorn'){
		// 	$_SESSION['type']='admin';
		// }
		

		//get sy and semester
		$getsy=mysql_query("select * from user where type='admin' order by user_id asc limit 1");
		$sy=mysql_fetch_array($getsy);
		$_SESSION['sy']=$sy['sy'];
		$_SESSION['semester']=$sy['semester'];
		$_SESSION['name']=$row['name'];
	 	header("location:home.php");
 	}
}else{
header("location:index.php?msg=Username or password is incorrect.");
}
?>