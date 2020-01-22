<?php
include 'dbconfig.php';
$id=$_POST['signatory'];
$action=$_POST['action'];
if($action=="activate"){
mysql_query("update signatory set status='Deactivated'");
mysql_query("update signatory set status='Activated' where sig_id='$id'");
echo "string";
}else{
	mysql_query("update signatory set status='Deactivated' where sig_id='$id'");
	echo "string11";
}

?>