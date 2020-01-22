<?php
include 'dbconfig.php';
$name=$_POST['name'];
mysql_query("update signatory set status='Deactivated'");
mysql_query("insert into signatory values ('','$name','Activated')");

?>