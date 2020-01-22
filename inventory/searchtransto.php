<?php
session_start();
include '../dbconfig.php';
$name=$_POST['name'];
$stud_id=$_POST['fromname'];
$getname=mysql_query("select * from student,student_status where student_status.stud_id=student.stud_id and sy='$_SESSION[sy]' and semester='$_SESSION[semester]' and  (lname like '$name%' or stud_number like '$name%') and student.stud_id!='$stud_id' ") or die(mysql_error());
?>
<style type="text/css">
	#search2 {line-height:12px;font-size:14px;}
	#search2 td {padding:3px;}
	#search2 tr:hover {background:#e3e3e3;cursor:pointer;}
</style>
<table id="search2" style="width:100%;">
<?php
while ($row=mysql_fetch_array($getname)) {
	echo "<tr id='rowid$row[stud_id]' onclick='selecttransferto($row[stud_id])'><td>$row[stud_number]</td><td style='text-transform:capitalize'>$row[lname], $row[fname]</td></tr>";
}
if(mysql_num_rows($getname)==0){
	echo "<tr><td style='text-align:center'>No results.</td></tr>";
}
?>
</table>