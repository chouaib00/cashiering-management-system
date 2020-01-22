<?php
include 'dbconfig.php';
$miscdesc=$_POST['miscdesc'];
$miscarr=explode("/-/", $miscdesc);
$miscdesclen=count($miscarr);

$miscamount=$_POST['miscamount'];
$miscamountarr=explode("/-/", $miscamount);

$misccategory=$_POST['misccategory'];
$misccategoryarr=explode("/-/", $misccategory);


$sy=$_POST['sy'];
$sem=$_POST['sem'];
$year=$_POST['year'];
$startcount=1;
while($startcount<$miscdesclen){
	mysql_query("insert into schedule_of_fees values ('','misc>>$miscarr[$startcount]','$miscamountarr[$startcount]','$misccategoryarr[$startcount]','0','0','$sy','$sem') ") or die(mysql_error()); 
	$startcount++;
}

?>