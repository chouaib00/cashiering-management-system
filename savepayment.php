<?php
session_start();
include('dbconfig.php');
$values=$_POST['values'];
$sc=$_POST['scholar'];
$payor=$_POST['payor'];
$or=$_POST['or'];
$scexp=explode("/////",$sc);
echo count($scexp);
if(count($scexp)==2){
	mysql_query("insert into scholarship values ('','$scexp[0]','$scexp[1]')");
	$s=mysql_query("select * from scholarship where description='$scexp[0]' and amount='$scexp[1]' order by scholarship_id desc limit 1");
	$sc=mysql_fetch_array($s);
	mysql_query("insert into scholar values('','$payor','$sc[scholarship_id]','II','2017-2018')");
	}else{
mysql_query("insert into scholar values('','$payor','$sc','II','2017-2018')");

}


$var = explode(",",$values);
$count = count($var)-1;
$date = date('m/d/y');
$ind = 0;

while($count>$ind){
$des = $var[$ind];
$amount = $var[$ind+1];
mysql_query("insert into payment values ('','$date','2017-2018','II','$or','$payor','$des','$amount','$_SESSION[userid]')");
$ind=$ind+2;
}
?>