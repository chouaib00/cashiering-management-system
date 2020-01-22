<?php
session_start();
include '../dbconfig.php';
$user_id=$_SESSION['user_id'];
$name=$_POST['name'];
$uname=$_POST['uname'];
$curpass=md5($_POST['curpass']);
$newpass=md5($_POST['newpass']);
$checkpassword=mysql_query("select * from user where user_id='$user_id' and password='$curpass'");
$count=mysql_num_rows($checkpassword);
$last=mysql_fetch_array($checkpassword);
echo "$count";
$date=date('m/d/y h:i a');
if($count>0){
	$comma="";
	$action="";
	$check=0;
	if($name!=$last['name']){
		$check=1;
		$action.="$last[name] changed his name to $name";
		$comma="comma";
	}
	if($uname!=$last['username']){
		$check=1;
		if($comma=="comma"){
			$action.=", changed his username from $last[username] to $uname";
		}else{
			$action.="$last[name] changed his username from $last[username] to $uname";
		}
		

	}

	if($newpass!=$last['password']){
		$check=1;
		if($comma=="comma"){
			$action.=" and  changed his password.";
		}else{
			$action.="$last[name] changed his password.";
		}
		

	}

	if($check==1){
		mysql_query("insert into user_log values ('','$date','$action','$user_id')");
	}	

	mysql_query("update user set name='$name',password='$newpass',username='$uname' where user_id='$user_id'") or die(mysql_error());
}
?>