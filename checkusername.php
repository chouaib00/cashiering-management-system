<?php
include('dbconfig.php');
$username=$_POST['username'];
$user=mysql_query("select username from user where username='$username'");
$count=mysql_num_rows($user);
if($count==1){
echo 1;
}else{
echo 0;
}
?>