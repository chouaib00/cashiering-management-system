<?php
session_start();
include '../dbconfig.php';
$scholarshipname=$_POST['scholarshipname'];
$sy=$_POST['sy'];
$semester=$_POST['semester'];
$_SESSION['semester2']=$_POST['semester'];
$_SESSION['sy2']=$_POST['sy'];
$semdisplay="";
if($semester=="I"){
	$semdisplay="1st";
}else{
	$semdisplay="2nd";
}

?>
<style type="text/css">
#inventorybut {position:relative;margin:0 0 -15px -6px;top:-5px;border:none;height:20px;background-repeat:no-repeat;padding:16px;background-position:5px 6px;border-radius:10px;width:20px;background-color:white;background-image:url(img/lens.png)}
#scholartable td{padding:2px;}
.scholaraction  {display:none}
</style>
 
 	<div style="text-align:center">
		Republic of the Philippines<br>
		NEGROS ORIENTAL STATE UNIVERSITY<br>
		Bayawan - Sta. Catalina Campus<br>
		Bayawan City<br>
	</div>
	<p style="text-align:center">STATEMENT OF ACCOUNT<br>
	<span style="text-transform:uppercaseth"><?=$scholarshipname;?></span>, <?=$semdisplay;?>  Semester, A.Y. <?=$sy;?>
	</p>


	<table border id="scholartable" style="border-collapse:collapse;width:100%">
	<tr>
		<th>NO.</th>
		<th colspan="2">NAME</th>
		<th>PROGRAM </th>
		<th>AMOUNT</th>
		<th class="scholaraction">ACTION</th>
	</tr>
	<?php
	$getlastor=mysql_query("select receipt_num from collection where user_id='$_SESSION[user_id]' order by col_id desc");
	$lastor=mysql_fetch_array($getlastor);	
	$lastor=$lastor['receipt_num']+1;

	$getstudent=mysql_query("select * from student,student_status,scholarship,course where student_status.course_id=course.course_id and  student.stud_id=student_status.stud_id and student_status.scholar_id=scholarship.scholar_id and student_status.sy='$sy' and student_status.semester='$semester' and scholarship.description='$scholarshipname' order by student.lname asc");
	$number=1;
	$totalamount=0;
	while ($studentrow=mysql_fetch_array($getstudent)){
		$totalamount=$totalamount+$studentrow['amount'];
		?>
			<tr>
				<td style="text-align:center"><?=$number;?></td>
				<td><?=$studentrow['lname'];?>,</td>
				<td><?=$studentrow['fname'];?></td>
				<td><?=$studentrow['acronym'];?> </td>
				<td style="text-align:right"><?=number_format($studentrow['amount'],2);?> </td>
				<td class="scholaraction" id="scholaraction<?=$studentrow['stud_id'];?>" style="text-align:center;color:blue">
				<?php
				if($studentrow['scholar_printed']==0){?>
				<input type="number"  class="scholaror" onkeyup="checkor(this)" value="<?php printf("%07d", $lastor);?>" id="receipt_num<?=$studentrow['stud_id'];?>" placeholder="Receipt #"  style="width:80px;padding:2px">
				<button style="padding:2px" onclick="printscholarreceipt(<?=$studentrow['stud_id'];?>,<?=$studentrow['amount'];?>,this)" title="Issue Receipt">Print Receipt</button>
				<?php
				 $lastor++;
				}else{
					echo "Receipt Printed";
				}?>
				</td>
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
<div style="float:right;margin:30px -10px 20px 0">
<button class="scholarbut" onclick="issuereceipt()" style="padding:5px;">ISSUE RECEIPT</button>
<a class="scholarbut" href="scholarship/printscholarship.php?sy=<?=$sy;?>&semester=<?=$semester;?>&scholar_id=<?=$scholarshipname;?>" target="jakecorn"><button style="padding:5px">PRINT</button></a>
<button class="scholarbut cancelissuereceipt" onclick="cancelissuereceipt()" style="display:none;padding:5px">CANCEL</button>
 </div>
 <div id="secretcon" style="display:none"></div>
  