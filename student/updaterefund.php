<?php
session_start();
include '../dbconfig.php';
$stud_id=$_POST['stud_id'];
$amount=$_POST['amount'];
$sy=$_POST['sy'];
$semester=$_POST['semester'];
$action=$_POST['action'];
if($semester!=$_SESSION['semester'] || $sy!=$_SESSION['sy']){
	if($action=="Advance Payment"){
		mysql_query("update exceeded_money set action='$action',to_sy='$_SESSION[sy]',to_semester='$_SESSION[semester]' where stud_id='$stud_id' and from_semester='$semester' and from_sy='$sy'");
	}else{
		mysql_query("update exceeded_money set action='$action' where stud_id='$stud_id' and from_semester='$semester' and from_sy='$sy'");

	}
}else{
	mysql_query("update exceeded_money set action='$action' where stud_id='$stud_id' and from_semester='$semester' and from_sy='$sy'");

}
echo "Money exceeded $amount  $action";
?>