<?php
include 'dbconfig.php';
$name=$_POST['name'];
$uname=$_POST['username'];
$pword= md5($_POST['password']) ; 
 mysql_query("insert into user values ('','$name','$uname','$pword','Collection','','','Deactivated')") or die(mysql_error());

?>