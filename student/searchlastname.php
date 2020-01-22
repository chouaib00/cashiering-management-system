<?php
include "../dbconfig.php";

$sy=$_POST['sy'];
$semester=$_POST['semester'];
$lname=$_POST['lname'];

 $getstu=mysql_query("select * from student,student_status,course where course.course_id=student_status.course_id and  student_status.stud_id=student.stud_id and sy='$sy' and semester='$semester' and lname like '$lname%'group by student.stud_id order by fname") or die(mysql_error());
while ($row=mysql_fetch_array($getstu)) {
	?>
	<tr>
		<td style="text-transform:capitalize"><b><?=$row['lname']."</b>, ".$row['fname'];?></td>
		<td><?=$row['acronym'];?></td>
		<td align='center'><?=$row['year_level'];?></td>
		<td align="center"><a href="student/downloadhist.php?stud_id=<?="$row[stud_id]&semester=$semester&sy=$sy";?>" target="jenelyn"><?=$sy." ".$semester;?></a></td>
	</tr>
	<?php
}
if(mysql_num_rows($getstu)==0){
	echo "<tr><td colspan='4' align='center'>No results found.</td></tr>";
}
 ?>