<?php
include 'dbconfig.php';
$rlecourse=$_POST['rlecourse'];
$rlecoursearr=explode('/-/', $rlecourse);
$rlecourselen=count($rlecoursearr);
$rledept=$_POST['rledept'];
$rledeptarr=explode('/-/', $rledept);

$rleamount=$_POST['rleamount'];
$rleamountarr=explode('/-/', $rleamount);


$rlecat=$_POST['rlecat'];
$rlecatarr=explode('/-/', $rlecat);

$sy=$_POST['sy'];
$sem=$_POST['sem'];
$year=$_POST['year'];

$startrle=1;
while ($startrle<$rlecourselen) {
	$ccheck=mysql_query("select course_id from course where description='$rlecoursearr[$startrle]' order by course_id desc");
	$cresult=mysql_num_rows($ccheck);
	$getcid=mysql_fetch_array($ccheck);
	if ($cresult>0) {
		mysql_query("insert into schedule_of_fees values ('','RLE','$rleamountarr[$startrle]','$rlecatarr[$startrle]','$getcid[course_id]','$year','$sy','$sem')") or die(mysql_error());
	}else{
		mysql_query("insert into course values ('','$rlecoursearr[$startrle]','$rledeptarr[$startrle]')") or die(mysql_error());
		$newcid=mysql_query("select course_id from course where description='$rlecoursearr[$startrle]' order by course_id desc");
		$newcid2=mysql_fetch_array($newcid);
		mysql_query("insert into schedule_of_fees values ('','RLE','$rleamountarr[$startrle]','$rlecatarr[$startrle]','$newcid2[course_id]','$year','$sy','$sem')") or die(mysql_error());
	}	

$startrle++;
}
?>