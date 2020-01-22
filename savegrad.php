<?php
include 'dbconfig.php';
$graddesc=$_POST['graddesc'];
$graddescarr=explode("/-/", $graddesc);
$graddesclen=count($graddescarr);

$gradamount=$_POST['gradamount'];
$gradamountarr=explode("/-/", $gradamount);

$gradcategory=$_POST['gradcategory'];
$gradcategoryarr=explode("/-/", $gradcategory);

$sy=$_POST['sy'];
$sem=$_POST['sem'];
$year=$_POST['year'];
$startcount=1;
while($startcount<$graddesclen){
	mysql_query("insert into schedule_of_fees values ('','grad>>$graddescarr[$startcount]','$gradamountarr[$startcount]','$gradcategoryarr[$startcount]','0','IV','$sy','$sem') ") or die(mysql_error()); 
	$startcount++;
}

?>