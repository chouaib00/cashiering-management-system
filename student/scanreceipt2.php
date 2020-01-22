<?php
session_start();
include '../dbconfig.php';

$stud_id=$_POST['stud_id'];
$sy2=$_POST['sy'];
$semester=$_SESSION['semester'];
if($sy2=="null"){
	$sy=$_SESSION['sy'];	
}else{
	$sy=$_POST['sy'];
 }
$student=mysql_query("select * from student,course,student_status where course.course_id=student_status.course_id and  student.stud_id=student_status.stud_id and student.stud_id='$stud_id' and student_status.sy='$sy' and semester='$semester' order by stat_id desc") or die(mysql_error());
$studentrow=mysql_fetch_array($student);
?>

<style type="text/css">
#scancon {position:relative;margin-top:50px}
#ssy,#searchstate {padding:1px;}
.scantable {margin-bottom:5px;}
.scantable tr {cursor:pointer;}
.scantable tr:hover {background:#e7fdff;}
</style>

<div id="scancon">
	<div style="top:-35px;position:absolute;height:30px">
		<?php
		$getsy=mysql_query("select * from student_status where stud_id='$stud_id' group by sy order by sy");

		?>
		Select: SY

		<select id="ssy">
			<?php
				while ($row=mysql_fetch_array($getsy)) {
					?>
						<option><?=$row['sy'];?></option>
					<?php
				}
			?>
			
		</select>
		<button id="searchstate" onclick="scanreceipt(<?=$stud_id;?>)">Search</button>
	</div> <!-- end of search scan of account -->
	<div>Select payments what to scan.</div>
	<div style="margin-top:10px;text-align:center;background:#c6c6c6;padding:5px;">SY <?=$sy;?></div>
	<table style="float:left" class="scantable">
				<tr>	
					<th colspan="5">First Semester</th>
					
				</tr>
				
				<tr class="tablehead">
					<td></td>	
					<td>DATE</td>
					<td>OR</td>
					<td>AMOUNT</td>
					<td>DESCRIPTION</td>
				</tr>
				<?php
					$paidmoneypersemester=0;
					$moneytobepaidpersemester=0;

					//get the student status during this school year and this semester;
					$studstatus=mysql_query("select * from student_status where stud_id='$stud_id' and sy='$sy' and semester='I'");
					$studstatusrow=mysql_fetch_array($studstatus);

					

					//get the tuition and misc
					$tuimisc=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and  course_id='$studstatusrow[course_id]' and (payment_group='misc' or payment_group='sched') and sy='$studstatusrow[sy]' and semester='$studstatusrow[semester]' and (year_level like '$studstatusrow[year_level]&%' or year_level like '%&$studstatusrow[year_level]')");
					while ($tuimiscrow=mysql_fetch_array($tuimisc)) {
							$moneytobepaidpersemester=$moneytobepaidpersemester+$tuimiscrow['amount'];
							$paid=mysql_query("select collection.amount from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and stud_id='$stud_id' and schedule_of_fees.payment_id='$tuimiscrow[payment_id]' and schedule_of_fees.sy='$studstatusrow[sy]' and schedule_of_fees.semester='I'") or die(mysql_error());
							while ($paidrow=mysql_fetch_array($paid)) {
								echo "$tuimiscrow[payment_desc] $paidrow[amount] <br>";
								$paidmoneypersemester=$paidmoneypersemester+$paidrow['amount'];
							}
							

					}
					//last action to get the amount in collection table in tuimisc category:succees
					echo "$paidmoneypersemester total";

					//get the rle if there's any
					$rle=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and  course_id='$studstatusrow[course_id]' and paymentlist.payment_group='rle' and sy='$studstatusrow[sy]' and semester='$studstatusrow[semester]' and (year_level like '$studstatusrow[year_level]&%' or year_level like '%&$studstatusrow[year_level]')");
					while ($rlerow=mysql_fetch_array($rle)) {
						$moneytobepaidpersemester=$moneytobepaidpersemester+$rlerow['amount'];
						// $paid=mysql_query("select collection.amount from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and stud_id='$stud_id' and schedule_of_fees.payment_id='$rlerow[payment_id]' and schedule_of_fees.sy='$studstatusrow[sy]' and schedule_of_fees.semester='I'") or die(mysql_error());
						// 	while ($paidrow=mysql_fetch_array($paid)) {
						// 		echo "$rlerow[payment_desc] $paidrow[amount] <br>";
						// 		$paidmoneypersemester=$paidmoneypersemester+$paidrow['amount'];
						// 	}
					}

					///get the trans/new student fees
					if($studstatusrow['status']=="trans" || $studstatusrow['status']=="new"){
						$newfees=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and paymentlist.payment_group='new' and sy='$studstatusrow[sy]' and semester='$studstatusrow[semester]'");
						while ($newfeesrow=mysql_fetch_array($newfees)) {
							$moneytobepaidpersemester=$moneytobepaidpersemester+$newfeesrow['amount'];
						}
					}

					///get the trans/new student fees
					if($studstatusrow['status']=="grad"){

						$grad=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and paymentlist.payment_group='grad' and sy='$studstatusrow[sy]' and semester='0'");
						while ($gradrow=mysql_fetch_array($grad)) {
							$moneytobepaidpersemester=$moneytobepaidpersemester+$gradrow['amount'];
						}
					}

					//end calculating the paymentst
					$paymentfsem=mysql_query("select collection.amount,schedule_of_fees.amount,collection.semester,date,receipt_num,payment_desc,schedule_of_fees.sched_id from collection,schedule_of_fees,paymentlist,student where student.stud_id=collection.stud_id and student.stud_id='$stud_id' and  collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and schedule_of_fees.sy='$sy' and paymentlist.payment_group!='misc' and schedule_of_fees.semester='I'") or die(mysql_error());
					while ($paymentfsemrow=mysql_fetch_array($paymentfsem)) {
						echo ":$paymentfsemrow[1]:";
							$paidmoneypersemester=$paidmoneypersemester-$paymentfsemrow[0];
						?>
							<tr id="scanrow<?=$paymentfsemrow['sched_id'];?>" onclick="selectamount(<?=$paymentfsemrow['sched_id'];?>)">
								<td>
								<input type="checkbox" value="<?=$paymentfsemrow['semester'];?><-><?=$paymentfsemrow['date'];?><-><?=$paymentfsemrow['receipt_num'];?><-><?=$paymentfsemrow[0];?><-><?=$paymentfsemrow['payment_desc'];?>">
								</td>
								<td><?=$paymentfsemrow['date'];?></td>
								<td><?=$paymentfsemrow['receipt_num'];?></td>
								<td><?=number_format($paymentfsemrow[0],2);?></td>
								<td><?=$paymentfsemrow['payment_desc'];?></td>
							</tr>
						<?php

					}
					echo "$paidmoneypersemester asdf";
					$paymentmisc=mysql_query("select col_id,collection.semester,date,receipt_num,collection.amount,payment_desc from collection,schedule_of_fees,paymentlist,student where student.stud_id=collection.stud_id and student.stud_id='$stud_id' and  collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and collection.sy='$sy' and paymentlist.payment_group='misc' and collection.semester='I' group by receipt_num") or die(mysql_error());
					while ($paymentmiscrow=mysql_fetch_array($paymentmisc)) {
							$sameor=mysql_query("select collection.amount from collection,schedule_of_fees,paymentlist where collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='misc' and receipt_num='$paymentmiscrow[receipt_num]' and collection.col_id!='$paymentmiscrow[col_id]' ") or die(mysql_error());
							$totalmisc=0;
							while ($row=mysql_fetch_array($sameor)) {
								$totalmisc=$totalmisc+$row['amount'];
							}
						?>
							<tr id="scanrow<?=$paymentmiscrow['col_id'];?>" onclick="selectamount(<?=$paymentmiscrow['col_id'];?>)">
								<td>
								<input type="checkbox" value="<?=$paymentmiscrow['semester'];?><-><?=$paymentmiscrow['date'];?><-><?=$paymentmiscrow['receipt_num'];?><-><?=$totalmisc;?><->Miscellaneous">
								</td>
								<td><?=$paymentmiscrow['date'];?></td>
								<td><?=$paymentmiscrow['receipt_num'];?></td>
								<td><?=number_format($totalmisc,2);?></td>
								<td>Miscellaneous</td>
							</tr>
						<?php

					}
					
					?>
			</table>

			<!-- second semester -->
				<table style="float:right" class="scantable">
				<tr>	
					<th colspan="5">Second Semester</th>
					
				</tr>
				
				<tr class="tablehead">
					<td></td>	
					<td>DATE</td>
					<td>OR</td>
					<td>AMOUNT</td>
					<td>DESCRIPTION</td>
				</tr>
				<?php
					$paymentfsem=mysql_query("select collection.semester,date,receipt_num,collection.amount,payment_desc,schedule_of_fees.sched_id from collection,schedule_of_fees,paymentlist,student where student.stud_id=collection.stud_id and student.stud_id='$stud_id' and  collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and collection.sy='$sy' and paymentlist.payment_group!='misc' and collection.semester='II'") or die(mysql_error());
					while ($paymentfsemrow=mysql_fetch_array($paymentfsem)) {
						?>
							<tr id="scanrow<?=$paymentfsemrow['sched_id'];?>" onclick="selectamount(<?=$paymentfsemrow['sched_id'];?>)">
								<td>
								<input type="checkbox" value="<?=$paymentfsemrow['semester'];?><-><?=$paymentfsemrow['date'];?><-><?=$paymentfsemrow['receipt_num'];?><-><?=$paymentfsemrow['amount'];?><-><?=$paymentfsemrow['payment_desc'];?>">
								</td>
								<td><?=$paymentfsemrow['date'];?></td>
								<td><?=$paymentfsemrow['receipt_num'];?></td>
								<td><?=number_format($paymentfsemrow['amount'],2);?></td>
								<td><?=$paymentfsemrow['payment_desc'];?></td>
							</tr>
						<?php

					}

					$paymentmisc=mysql_query("select col_id,collection.semester,date,receipt_num,collection.amount,payment_desc from collection,schedule_of_fees,paymentlist,student where student.stud_id=collection.stud_id and student.stud_id='$stud_id' and  collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and collection.sy='$sy' and paymentlist.payment_group='misc' and collection.semester='II' group by receipt_num") or die(mysql_error());
					while ($paymentmiscrow=mysql_fetch_array($paymentmisc)) {
							$sameor=mysql_query("select collection.amount from collection,schedule_of_fees,paymentlist where collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='misc' and receipt_num='$paymentmiscrow[receipt_num]' and collection.col_id!='$paymentmiscrow[col_id]' ") or die(mysql_error());
							$totalmisc=0;
							while ($row=mysql_fetch_array($sameor)) {
								$totalmisc=$totalmisc+$row['amount'];
							}
						?>
							<tr id="scanrow<?=$paymentmiscrow['col_id'];?>" onclick="selectamount(<?=$paymentmiscrow['col_id'];?>)">
								<td>
								<input type="checkbox" value="<?=$paymentmiscrow['semester'];?><-><?=$paymentmiscrow['date'];?><-><?=$paymentmiscrow['receipt_num'];?><-><?=$totalmisc;?><->Miscellaneous">
								</td>
								<td><?=$paymentmiscrow['date'];?></td>
								<td><?=$paymentmiscrow['receipt_num'];?></td>
								<td><?=number_format($totalmisc,2);?></td>
								<td>Miscellaneous</td>
							</tr>
						<?php

					}
					
					?>
			</table>
			<div style="clear:both"></div>
			<a id="jake" href="googlecom" target="asf">asfsf</a>
			<a href="student/printpreviewscanreceipt.php" id="printlink" target="jakecorn"  onclick="printcert('<?=$sy;?>')"><button>Print Certification</button></a>
</div> <!--  end of scancon-->

<script>
function printcert(sy){
	
	var amountdata="";
	$("input[checked='checked']").each(function() {
		amountdata+="[endline]"+$(this).val();
	});
	$('#printlink').attr("href","student/printpreviewscanreceipt.php?data="+amountdata+"&stud_id=<?=$stud_id;?>&sy=<?=$sy;?>&semester=<?=$semester;?>");
	$.ajax({
		type:'post',
		url:'student/printscanreceipt.php',
		data:{'stud_id':<?=$stud_id;?>,'data':amountdata,'sy':sy},
		success:function(data){
			
			$('#paymenthistcon').html(data).show();			
			$('#jake').click();
		},
		error:function(){
			connection();
		}

	})
}
function selectamount(a){
	var b=$('#scanrow'+a+" input[type='checkbox']");
	var val=$('#scanrow'+a+" input[type='checkbox']").val();
	var c=b.attr("checked");
 	if(c=="checked"){
		b.removeAttr('checked');
		
	}else{
		$('#scanrow'+a+" td:first").html("<input type='checkbox' value='"+val+"' checked='checked'>");
	}

}
</script>