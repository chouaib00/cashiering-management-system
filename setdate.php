<?php
session_start();
$date=$_POST['date'];
$explode=explode('-', $date);
if($_SESSION['user_id']!=""){
	$_SESSION['date']=$explode[1]."/".$explode[2]."/".$explode[0];
}

?>