<?php
include 'dbconfig.php';
$cname=$_POST['cname'];
$cacronym=$_POST['cacronym'];
$dacronym=$_POST['dacronym'];
$dname=$_POST['dname'];
 if(strlen($dacronym)==0){
 	 	mysql_query("insert into course values ('','$cname','$cacronym','$dname')");
 }else{
 	mysql_query("insert into department values ('','$dname','$dacronym')") or die(mysql_error());
 	$getid=mysql_query("select * from department where description='$dname' and acronym='$dacronym' order by dept_id desc");
 	$newid=mysql_fetch_array($getid);
 	mysql_query("insert into course values ('','$cname','$cacronym','$newid[dept_id]')");
 }
 $courseid=mysql_query("select course_id from course where description='$cname' and acronym='$cacronym' order by course_id desc");
 $row=mysql_fetch_array($courseid);
 echo $row['course_id'];
?>