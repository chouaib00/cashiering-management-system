<?php
include('dbconfig.php');
$name=$_POST['name'];
$user=mysql_query("select * from student where lname like '$name%' or  stud_number like '$name%'  order by lname limit 15") or die(mysql_error());
if($c=mysql_num_rows($user)==0){
?>
<tr>
<td style="text-align:center">No result found.</td>
</tr>
<?php
}
while($row=mysql_fetch_array($user)){
	$course=mysql_query("select * from course,student_status where course.course_id=student_status.course_id and student_status.stud_id='$row[stud_id]' order by stat_id desc limit 1");
	$courserow=mysql_fetch_array($course);
?>
<tr onclick="selectsearchstud(<?=$row['stud_id'];?>)"><td><?=$row['stud_number'];?></td>
<td style="text-transform:capitalize;white-space:nowrap"><?=$row['lname'].", ".$row['fname'];?></td><td><?=$courserow['acronym'];?></td>
</tr>
<?php

}