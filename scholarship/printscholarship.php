<?php
include '../dbconfig.php';
session_start();
$scholar_id=$_REQUEST['scholar_id'];
$sy=$_REQUEST['sy'];
$semester=$_REQUEST['semester'];

$semdisplay="";
if($semester=="I"){
	$semdisplay="1st";
}else{
	$semdisplay="2nd";
}

 
?>
<meta charset="utf-8">
<style type="text/css">
@media print{
	table,div {font-family:tahoma,verdana,arial;font-size:13px;}
	#scholartable td{padding:2px;}
}
</style>
 

<div style="width:8in;margin:0 auto">
	<div style="text-align:center">
		Republic of the Philippines<br>
		NEGROS ORIENTAL STATE UNIVERSITY<br>
		Bayawan - Sta. Catalina Campus<br>
		Bayawan City<br>
	</div>
	<p style="text-align:center">STATEMENT OF ACCOUNT<br>
	<span style="text-transform:uppercaseth"><?=$scholar_id;?></span>, <?=$semdisplay;?>  Semester, A.Y. <?=$sy;?>
	</p>


	<table border id="scholartable" style="border-collapse:collapse;width:100%">
	<tr>
		<th>NO.</th>
		<th colspan="2">NAME</th>
		<th>PROGRAM </th>
		<th>AMOUNT</th>
	</tr>
	<?php
	$getstudent=mysql_query("select * from student,student_status,scholarship,course where student_status.course_id=course.course_id and  student.stud_id=student_status.stud_id and student_status.scholar_id=scholarship.scholar_id and student_status.sy='$sy' and student_status.semester='$semester' and scholarship.description='$scholar_id' order by student.lname asc");
	$number=1;
	$totalamount=0;
	while ($studentrow=mysql_fetch_array($getstudent)) {
		$totalamount=$totalamount+$studentrow['amount'];
		?>
			<tr>
				<td style="text-align:center"><?=$number;?></td>
				<td><?=$studentrow['lname'];?>,</td>
				<td><?=$studentrow['fname'];?></td>
				<td><?=$studentrow['acronym'];?> </td>
				<td style="text-align:right"><?=number_format($studentrow['amount'],2);?> </td>
			</tr>
		<?php
	$number++;
	}
	?>
	<tr>
		<td colspan="4" style="text-align:right">Total</td>
	
		<td style="text-align:right"><?=number_format($totalamount,2);?></td>
	</tr>
</table>

</div>
<?php
$date=date('m/d/Y');
$time=date('h:i a');
mysql_query("insert into user_log values ('','$date','$time','Printed the $scholar_id scholarship.','$_SESSION[user_id]')");
?>
<script>
window.print();
setTimeout(function(){

	window.close();
	},500);
</script>