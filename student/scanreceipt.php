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
function random(){
		$possiblevalue="1,2,3,4,5,6,7,8,9";
		$explode=explode(",", $possiblevalue);
		$rand="";
		for ($i=0; $i < 10; $i++) { 
			$rand.=$explode[rand(0,8)];
		}
		return $rand;
	}
?>

<style type="text/css">
#scancon {position:relative;margin-top:10px}
#ssy,#searchstate {padding:1px;}
.scantable {margin-bottom:5px;}
.scantable tr {cursor:pointer;}
.scantable tr:hover {background:#e7fdff;}
#searchscan select {padding:3px;}
#searchscan button {padding:5px;}
</style>

<div id="scancon" >
	<div id="searchscan" style=" top:5px;position:relative;height:30px;text-align:center">
		<?php
		$getsy=mysql_query("select * from student_status where stud_id='$stud_id' group by sy order by sy");

		?>
		Select: SY

		<select id="ssy">
			<?php
				while ($row=mysql_fetch_array($getsy)) {
					?>
						<option <?php if($sy==$row['sy']){echo "selected='selected'";};?>><?=$row['sy'];?></option>
					<?php
				}
			?>
			
		</select>
		<button id="searchstate" onclick="scanreceipt(<?=$stud_id;?>)">Search</button>
	</div> <!-- end of search scan of account -->
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

			<!-- get the list of payment -->
			<?php
			$paymentfsem=mysql_query("select date,receipt_num, SUM(collection.amount) as amount,payment_desc from collection,schedule_of_fees,paymentlist,student where student.stud_id=collection.stud_id  and remark!='Cancelled' and student.stud_id='$stud_id' and  collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and schedule_of_fees.sy='$sy' and paymentlist.payment_group!='misc' and schedule_of_fees.semester='I' group by paymentlist.payment_id,receipt_num order by receipt_num") or die(mysql_error());
				while ($paymentfsemrow=mysql_fetch_array($paymentfsem)) {
					$rand=rand();
						echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
								echo "<td><input type='checkbox' value='I<->$paymentfsemrow[date]<->$paymentfsemrow[receipt_num]<->$paymentfsemrow[amount]<->$paymentfsemrow[payment_desc]'></td>";
								?>
							<td><?=$paymentfsemrow['date'];?></td>
							<td><?=$paymentfsemrow['receipt_num'];?></td>
							<td><?=$paymentfsemrow['amount'];?></td>
							<td><?=$paymentfsemrow['payment_desc'];?></td>
						</tr>
					<?php
						

				}

				//graduation feees
				$paymentfsem=mysql_query("select date,receipt_num,collection.amount,payment_desc from collection,schedule_of_fees,paymentlist,student where student.stud_id=collection.stud_id and student.stud_id='$stud_id' and  collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and remark='0' and schedule_of_fees.sy='$sy' and paymentlist.payment_group='grad' and collection.semester='I' order by receipt_num") or die(mysql_error());
				while ($paymentfsemrow=mysql_fetch_array($paymentfsem)) {
					$paidmoneypersemester=$paidmoneypersemester+$paymentfsemrow['amount'];
					$rand=rand();
						echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
								echo "<td><input type='checkbox' value='I<->$paymentfsemrow[date]<->$paymentfsemrow[receipt_num]<->$paymentfsemrow[amount]<->$paymentfsemrow[payment_desc]'></td>";
								?>
							<td><?=$paymentfsemrow['date'];?></td>
							<td><?=$paymentfsemrow['receipt_num'];?></td>
							<td><?=$paymentfsemrow['amount'];?></td>
							<td><?=$paymentfsemrow['payment_desc'];?></td>
						</tr>
					<?php

				}

				//trans/new feees
				$paymentfsem=mysql_query("select date,receipt_num,collection.amount,payment_desc from collection,schedule_of_fees,paymentlist,student where student.stud_id=collection.stud_id and student.stud_id='$stud_id' and  collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and remark='0' and schedule_of_fees.sy='$sy' and paymentlist.payment_group='new' and collection.semester='I' order by receipt_num") or die(mysql_error());
				while ($paymentfsemrow=mysql_fetch_array($paymentfsem)) {
					$paidmoneypersemester=$paidmoneypersemester+$paymentfsemrow['amount'];
					$rand=rand();
						echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
								echo "<td><input type='checkbox' value='I<->$paymentfsemrow[date]<->$paymentfsemrow[receipt_num]<->$paymentfsemrow[amount]<->$paymentfsemrow[payment_desc]'></td>";
								?>
							<td><?=$paymentfsemrow['date'];?></td>
							<td><?=$paymentfsemrow['receipt_num'];?></td>
							<td><?=$paymentfsemrow['amount'];?></td>
							<td><?=$paymentfsemrow['payment_desc'];?></td>
						</tr>
					<?php

				}

				$paymentmisc=mysql_query("select date,receipt_num,collection.amount,payment_desc from collection,schedule_of_fees,paymentlist,student where    student.stud_id=collection.stud_id and student.stud_id='$stud_id' and  collection.sched_id=schedule_of_fees.sched_id and remark='0' and schedule_of_fees.payment_id=paymentlist.payment_id and schedule_of_fees.sy='$sy' and paymentlist.payment_group='misc' and schedule_of_fees.semester='I' group by receipt_num") or die(mysql_error());
				while ($paymentmiscrow=mysql_fetch_array($paymentmisc)) {
						$sameor=mysql_query("select collection.amount from collection,schedule_of_fees,paymentlist where stud_id='$stud_id' and collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and  remark='0' and  paymentlist.payment_group='misc' and receipt_num='$paymentmiscrow[receipt_num]' and collection.col_id!='$paymentmiscrow[col_id]' ") or die(mysql_error());
						$totalmisc=0;
						while ($row=mysql_fetch_array($sameor)) {
							$totalmisc=$totalmisc+$row['amount'];
							$paidmoneypersemester=$paidmoneypersemester+$row['amount'];
						}
						if($totalmisc>0){
						$rand=rand();
							echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
									echo "<td><input type='checkbox' value='I<->$paymentmiscrow[date]<->$paymentmiscrow[receipt_num]<->$totalmisc<->Miscellaneous'></td>";
									?>
								<td><?=$paymentmiscrow['date'];?></td>
								<td><?=$paymentmiscrow['receipt_num'];?></td>
								<td><?=$totalmisc;?></td>
								<td>Miscellaneous</td>
							</tr>
					<?php
					}
				}	 
							//get advance payment

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

			<!-- get the list of payment -->
			<?php
			$paymentfsem=mysql_query("select date,receipt_num, SUM(collection.amount) as amount,payment_desc from collection,schedule_of_fees,paymentlist,student where student.stud_id=collection.stud_id  and remark!='Cancelled' and student.stud_id='$stud_id' and  collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and schedule_of_fees.sy='$sy' and paymentlist.payment_group!='misc' and schedule_of_fees.semester='II' group by paymentlist.payment_id,receipt_num order by receipt_num") or die(mysql_error());
				while ($paymentfsemrow=mysql_fetch_array($paymentfsem)) {
					$rand=rand();
						echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
								echo "<td><input type='checkbox' value='II<->$paymentfsemrow[date]<->$paymentfsemrow[receipt_num]<->$paymentfsemrow[amount]<->$paymentfsemrow[payment_desc]'></td>";
								?>
							<td><?=$paymentfsemrow['date'];?></td>
							<td><?=$paymentfsemrow['receipt_num'];?></td>
							<td><?=$paymentfsemrow['amount'];?></td>
							<td><?=$paymentfsemrow['payment_desc'];?></td>
						</tr>
					<?php
						

				}

				//graduation feees
				$paymentfsem=mysql_query("select date,receipt_num,collection.amount,payment_desc from collection,schedule_of_fees,paymentlist,student where student.stud_id=collection.stud_id and student.stud_id='$stud_id' and  collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and remark='0' and schedule_of_fees.sy='$sy' and paymentlist.payment_group='grad' and collection.semester='II' order by receipt_num") or die(mysql_error());
				while ($paymentfsemrow=mysql_fetch_array($paymentfsem)) {
					$paidmoneypersemester=$paidmoneypersemester+$paymentfsemrow['amount'];
					$rand=rand();
						echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
								echo "<td><input type='checkbox' value='II<->$paymentfsemrow[date]<->$paymentfsemrow[receipt_num]<->$paymentfsemrow[amount]<->$paymentfsemrow[payment_desc]'></td>";
								?>
							<td><?=$paymentfsemrow['date'];?></td>
							<td><?=$paymentfsemrow['receipt_num'];?></td>
							<td><?=$paymentfsemrow['amount'];?></td>
							<td><?=$paymentfsemrow['payment_desc'];?></td>
						</tr>
					<?php

				}

				//trans/new feees
				$paymentfsem=mysql_query("select date,receipt_num,collection.amount,payment_desc from collection,schedule_of_fees,paymentlist,student where student.stud_id=collection.stud_id and student.stud_id='$stud_id' and  collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and remark='0' and schedule_of_fees.sy='$sy' and paymentlist.payment_group='new' and collection.semester='II' order by receipt_num") or die(mysql_error());
				while ($paymentfsemrow=mysql_fetch_array($paymentfsem)) {
					$paidmoneypersemester=$paidmoneypersemester+$paymentfsemrow['amount'];
					$rand=rand();
						echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
								echo "<td><input type='checkbox' value='II<->$paymentfsemrow[date]<->$paymentfsemrow[receipt_num]<->$paymentfsemrow[amount]<->$paymentfsemrow[payment_desc]'></td>";
								?>
							<td><?=$paymentfsemrow['date'];?></td>
							<td><?=$paymentfsemrow['receipt_num'];?></td>
							<td><?=$paymentfsemrow['amount'];?></td>
							<td><?=$paymentfsemrow['payment_desc'];?></td>
						</tr>
					<?php

				}

				$paymentmisc=mysql_query("select date,receipt_num,collection.amount,payment_desc from collection,schedule_of_fees,paymentlist,student where    student.stud_id=collection.stud_id and student.stud_id='$stud_id' and  collection.sched_id=schedule_of_fees.sched_id and remark='0' and schedule_of_fees.payment_id=paymentlist.payment_id and schedule_of_fees.sy='$sy' and paymentlist.payment_group='misc' and schedule_of_fees.semester='II' group by receipt_num") or die(mysql_error());
				while ($paymentmiscrow=mysql_fetch_array($paymentmisc)) {
						$sameor=mysql_query("select collection.amount from collection,schedule_of_fees,paymentlist where stud_id='$stud_id' and collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and  remark='0' and  paymentlist.payment_group='misc' and receipt_num='$paymentmiscrow[receipt_num]' and collection.col_id!='$paymentmiscrow[col_id]' ") or die(mysql_error());
						$totalmisc=0;
						while ($row=mysql_fetch_array($sameor)) {
							$totalmisc=$totalmisc+$row['amount'];
							$paidmoneypersemester=$paidmoneypersemester+$row['amount'];
						}
						if($totalmisc>0){
						$rand=rand();
							echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
									echo "<td><input type='checkbox' value='II<->$paymentmiscrow[date]<->$paymentmiscrow[receipt_num]<->$totalmisc<->Miscellaneous'></td>";
									?>
								<td><?=$paymentmiscrow['date'];?></td>
								<td><?=$paymentmiscrow['receipt_num'];?></td>
								<td><?=$totalmisc;?></td>
								<td>Miscellaneous</td>
							</tr>
					<?php
					}
				}	 
							//get advance payment

 						?>
				
	</table>
			<div style="clear:both"></div>
	 <button onclick="checkall()" style="padding:5px">Check All</button>
			<a hresf="studenft/printpreviewscanreceipt.php" id="printlin" onclick="askprintcert('<?=$sy;?>')"><button class="print" style="position:relative;top:4px;right:10px;float:right;"></button></a>
</div> <!--  end of scancon-->

<script>
function checkall () {
	$('.scantable input[type=checkbox]').click();
}
function askprintcert(a){
	var cover=$('#overlay, #modal');
	var amountdata="";
	$(".scantable input[checked='checked']").each(function() {
		amountdata+="[endline]"+$(this).val();
  	});
	cover.show();
	$.ajax({
		type:'post',
		url:'student/askreceiptforscanning.php',
		data:{'stud_id':<?=$stud_id;?>,'data':amountdata},
 		success:function(data){
		 $('#addcoursecon').html(data);	
  		},
		error:function(){
			connection();
			cover.hide();
		}

	});
}



function printcert(sy){	
	var amountdata="";
	$("input[checked='checked']").each(function() {
		amountdata+="[endline]"+$(this).val();
	});
	// $('#printlink').attr("href","student/printpreviewscanreceipt.php?data="+amountdata+"&stud_id=<?=$stud_id;?>&sy=<?=$sy;?>&semester=<?=$semester;?>");
	$.ajax({
		type:'post',
		url:'student/printscanreceipt.php',
		data:{'stud_id':<?=$stud_id;?>,'data':amountdata,'sy':sy },
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