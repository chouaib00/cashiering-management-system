<?php
session_start();
include '../dbconfig.php';
$stud_id=$_POST['stud_id'];
$sy=$_SESSION['sy'];
$semester=$_SESSION['semester'];

////check all semester if not fully paid

	//get all status of the the student
	$getstatus=mysql_query("select * from student,student_status where student.stud_id=student_status.stud_id and student.stud_id='$stud_id'");
	while ($statusrow=mysql_fetch_array($getstatus)) {
		$balancepersemester=0;
		$moneytobepaidpersemester=0;
		$moneypaidpersemester=0;
		
		//check the tuition
		$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='sched' and course_id='$statusrow[course_id]' and (year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]') and sy='$statusrow[sy]' and semester='$statusrow[semester]' ") or die(mysql_error());
		while ($schedrow=mysql_fetch_array($sched)) {
			$moneytobepaidpersemester=$moneytobepaidpersemester+$schedrow['amount'];
		$bal=mysql_query("select * from collection where   stud_id='$stud_id' and sched_id in (select distinct sched_id from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_id='$schedrow[payment_id]' and sy='$statusrow[sy]' and semester='$statusrow[semester]')") or die(mysql_error());


		while ($balrow=mysql_fetch_array($bal)) {
				$moneypaidpersemester=$moneypaidpersemester+$balrow['amount'];
			}
		}
		//check the miscellaneous
		$misc=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='misc' and course_id='$statusrow[course_id]' and ( year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]') and sy='$statusrow[sy]' and semester='$statusrow[semester]' ") or die(mysql_error());
		while ($miscrow=mysql_fetch_array($misc)) {
			$moneytobepaidpersemester=$moneytobepaidpersemester+$miscrow['amount'];
		 	$bal=mysql_query("select * from collection where sched_id='$miscrow[sched_id]'  and stud_id='$stud_id'");
			while ($balrow=mysql_fetch_array($bal)){
				// echo "$balrow[sched_id] jake $balrow[amount]";
				$moneypaidpersemester=$moneypaidpersemester+$balrow['amount'];
			}
		 }

		 //check the rle
		$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='rle' and course_id='$statusrow[course_id]' and (year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]') and sy='$statusrow[sy]' and semester='$statusrow[semester]' ") or die(mysql_error());
		while ($schedrow=mysql_fetch_array($sched)) {
			$moneytobepaidpersemester=$moneytobepaidpersemester+$schedrow['amount'];
			 $bal=mysql_query("select * from collection where sched_id='$schedrow[sched_id]'  and stud_id='$stud_id'");
			while ($balrow=mysql_fetch_array($bal)) {
					$moneypaidpersemester=$moneypaidpersemester+$balrow['amount'];
				}
		}

		//check the graduation fees
		if($statusrow['status']=="grad"){		 
			$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='grad' and (year_level like 'IV&%' or year_level like '%&IV') and sy='$statusrow[sy]'") or die(mysql_error());
			while ($schedrow=mysql_fetch_array($sched)) {
				$moneytobepaidpersemester=$moneytobepaidpersemester+$schedrow['amount'];
				$bal=mysql_query("select * from collection where sched_id='$schedrow[sched_id]'  and stud_id='$stud_id'");
	 			while ($balrow=mysql_fetch_array($bal)) {
					$moneypaidpersemester=$moneypaidpersemester+$balrow['amount'];
				}
			}
		}

		//check the new/trans student fees//////////////////////
		if($statusrow['status']=="trans" || $statusrow['status']=="new"){		
			$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='new' and (year_level like 'I&%' or year_level like '%&I') and sy='$statusrow[sy]' and semester='$statusrow[semester]'") or die(mysql_error());
			$status=$studrow['status'];
			if($status=="new" or $status=="trans"){
				$year="I";
			}else{
				$year=$studrow['year_level'];
			}
			while ($schedrow=mysql_fetch_array($sched)) {
				$moneytobepaidpersemester=$moneytobepaidpersemester+$schedrow['amount'];
				$bal=mysql_query("select * from collection where sched_id='$schedrow[sched_id]' and stud_id='$stud_id'");
				while ($balrow=mysql_fetch_array($bal)) {
					$moneypaidpersemester=$moneypaidpersemester+$balrow['amount'];
				}
			}
		}

		if($moneypaidpersemester<$moneytobepaidpersemester){
			$sy=$statusrow['sy'];
			$semester=$statusrow['semester'];

		}
		
	} //end of statusrow

//////


$stud=mysql_query("select * from student,student_status,course where student.stud_id='$stud_id' and student_status.course_id=course.course_id and  student.stud_id=student_status.stud_id and sy='$sy' and semester='$semester' order by stat_id desc") or die(mysql_error()) ;
$studrow=mysql_fetch_array($stud);

$count=mysql_num_rows($stud);
if($count==0){
$stud=mysql_query("select * from student,student_status,course where student.stud_id='$stud_id' and student_status.course_id=course.course_id and  student.stud_id=student_status.stud_id order by stat_id desc") or die(mysql_error());
$studrow=mysql_fetch_array($stud);
$studrow['status']="ongoing";

}
?>
<link href="css/style.css" type="text/css" rel="stylesheet"></link>
<link href="css/student.css" type="text/css" rel="stylesheet"></link>
<script type="text/javascript" src="js/print.js"></script>
<div id="pheader">
<span onclick="selectsearchstud(<?=$studrow['stud_id']?>)">Student Info</span> | 
<span onclick="paymenthistory(<?=$studrow['stud_id']?>)">Payment History</span> | 
<span onclick="statementofaccount(<?=$studrow['stud_id']?>)">Statement of Account</span> | 
<span onclick="scanreceipt(<?=$studrow['stud_id']?>,'null')">Scan Receipt</span>
</div>
<script type="text/javascript">
	
	function printstatement(){
	$('#statementcon').print();
}
</script>
<div id="paymenthistcon">
</div>
<div id="studinfotablecon">
<div class="paymentheader">Student Info <img src="img/loading.gif" id="studloader" style="display:none;position:relative;top:3px"></div>
<table border id="studinfotable">
<tr>
	<?php
		$checkyear=0;
		$lastyear=explode("-", $studrow['sy']);
		$thisyear=explode("-", $sy);
		$checkyear=$thisyear[1]-$lastyear[1];
		$display="";
		$display2="";
		$checksy=mysql_query("select * from student_status where sy='$sy' and stud_id='$stud_id' and semester='$semester' and year_level='$studrow[year_level]'");
		$checksycount=mysql_num_rows($checksy);
		if($checkyear>=1 || $checksycount==0){
				$display="style='display:inline-block'";
				$display2="style='display:none'";
		}
		?>
	<td>First Name:</td>
	<td><span <?=$display2;?>> <?=$studrow['fname'];?></span><input type="text" <?=$display;?> id="sfname" value="<?=$studrow['fname'];?>" name="<?=$studrow['stud_id'];?>"></td>
</tr>

<tr>
	<td>Last Name:</td><td><span <?=$display2;?>><?=$studrow['lname'];?></span>
		<input  <?=$display;?> type="text" id="slname" value="<?=$studrow['lname'];?>">
	</td>
</tr>
<?php
		$checkscholar=mysql_query("select * from student_status,scholarship where scholarship.scholar_id=student_status.scholar_id and stud_id='$studrow[stud_id]' and course_id='$studrow[course_id]' and year_level='$studrow[year_level]' and sy='$sy' and semester='$semester'") or die(mysql_error());
			$checkscholarrow=mysql_fetch_array($checkscholar);
			if(mysql_num_rows($checkscholar)>0){
?>
<tr  id="scholarcon">
	<td>Scholarship:</td><td><span><?=$checkscholarrow['description'];?> - <?=$checkscholarrow['amount'];?></span>
	<select id="scholar">

		<option value="<?=$checkscholarrow['scholar_id'];?>"><?=$checkscholarrow['description'];?></option>		
		<?php
			$sch=mysql_query("select *  from scholarship where scholar_id!='$studrow[scholar_id]' order by description asc");
			while($row=mysql_fetch_array($sch)){
				?>
		<option value="<?=$row['scholar_id'];?>"><?=$row['description'];?> - <?=$row['amount'];?></option>		

				<?php
			}
		?>
		<option value="0">None</option>
	</select>
	</td>
</tr>

<?php
	}
?>

<tr>
	<td>Course:</td><td>

		<span <?=$display2;?> ><?=$studrow['acronym'];?></span>
		<select id="course_id" <?=$display;?> >
		<option value="<?=$studrow['course_id'];?>"><?=$studrow['acronym'];?></option>
		<?php
			$course=mysql_query("select * from course where course_id!='$studrow[course_id]'") or die(mysql_error());
			while ($courserow=mysql_fetch_array($course)) {
			
		?>
			<option value="<?=$courserow['course_id'];?>"><?=$courserow['acronym'];?></option>
		<?php
			}
		?>
	</select>

	</td>
</tr>

<tr>
	<td>Year Level:</td><td><span <?=$display2;?>> <?=$studrow['year_level'];?> </span>
		<select id="year_level" <?=$display;?> >
			<?php
				if($checkyear>=1){
					$newyearlevel='';
					if($studrow['year_level']=="I"){
						$newyearlevel="II";
					}else if($studrow['year_level']=="I"){
						$newyearlevel="III";
					}else if($studrow['year_level']=="I"){
						$newyearlevel="III";
					}else{
						$newyearlevel="IV";					}
					
					?>
					<option><?=$newyearlevel;?></option>
					<option <?php if($studrow['year_level']=="sI") echo "selected='selected'"; ?> >I</option>
					<option <?php if($studrow['year_level']=="IsI") echo "selected='selected'"; ?> >II</option>
					<option <?php if($studrow['year_level']=="IsII") echo "selected='selected'"; ?> >III</option>
					<option <?php if($studrow['year_level']=="IsV") echo "selected='selected'"; ?> >IV</option>

					<?php
				}else{?>
					<option <?php if($studrow['year_level']=="I") echo "selected='selected'"; ?> >I</option>
					<option <?php if($studrow['year_level']=="II") echo "selected='selected'"; ?> >II</option>
					<option <?php if($studrow['year_level']=="III") echo "selected='selected'"; ?> >III</option>
					<option <?php if($studrow['year_level']=="IV") echo "selected='selected'"; ?> >IV</option>
					<?php

				}
			?>
			
		</select>
	</td>
</tr>

<tr>
	<td>Status:</td><td><span <?=$display2;?>>
		<?php
			if($studrow['status']=="grad"){
				echo "Graduating";
			}else if($studrow['status']=="trans"){
					echo "Transferee";
			}else{
				echo $studrow['status'];
			}
		?>

	</span>
	<select id="sstatus" <?=$display;?> >
		<option <?php if($studrow['status']=="ongoing") echo "selected='selescted'"; ?> value="ongoing">Ongoing</option>
		<option <?php if($studrow['status']=="new") echo "selected='selescted'"; ?> value="new">New</option>
		<option <?php if($studrow['status']=="grad") echo "selected='selescted'"; ?> value="grad">Graduating</option>
		<option <?php if($studrow['status']=="trans") echo "selected='selescted'"; ?> value="trans">Transferee</option>
		<option <?php if($studrow['status']=="shiftee") echo "selected='selescted'"; ?> value="shifttee">Shiftee</option>
	</select>
	</td>
</tr>
<tr>
	<td colspan="2">
	<?php
	if($checkyear==0 && $checksycount>0){
	?>

		 	<button onclick="updatestudentstatus(this)" class="studoption studoptionstatus" style="display:inline-block">Update</button>

			<button onclick="savestudentstatus(this)" class="studoption savestudentstatus">Save Update</button>
			<button onclick="studoptioncancel(this)" class="studoption studoptioncancel">Cancel</button>
			<?php
	}else{ ?>
		<button onclick="savenewstatus()" id="savenewstatus">Save New Status</button>
		<script>
		$(function(){
			$('input[type=checkbox]').attr("disabled",true);
		});
		</script>	
	<?php
	}
	?>
	</td>
</tr>
</table>
</div>
<div id="paycon">
<div class="paymentheader">Payment Area

</div>
<table border="" id="paytable">
<form id="paymentform" onsubmit="return savefullpayment()">
<tr>
	<td>Receipt Number:</td><td><input type="text" id="receipt" required="required"></td>
</tr>

<tr>
	<td>Cash:</td><td><input type="number" id="cash" required="required"></td>
</tr>

<?php
		$checkscholar=mysql_query("select * from student_status,scholarship where scholarship.scholar_id=student_status.scholar_id and stud_id='$studrow[stud_id]' and course_id='$studrow[course_id]' and year_level='$studrow[year_level]' and sy='$sy' and semester='$semester'") or die(mysql_error());
			$checkscholarrow=mysql_fetch_array($checkscholar);
			if(mysql_num_rows($checkscholar)==0){
?>

<tr>
	<td>Scholarship:</td><td>
		<select id="scholar_id">
		<option value="0">None</option>
			<?php
				$getscholar=mysql_query("select * from Scholarship order by description asc") or die(mysql_error());
				while ($scholarrow=mysql_fetch_array($getscholar)) {
					?>
					<option value="<?=$scholarrow['scholar_id']?>"><?=$scholarrow['description'] ." - ". $scholarrow['amount'];?></option>

					<?php
				}
			?>
			
		</select>
	</td>
</tr>
<?php
	}
?>
<!-- <tr>
	<td>Tuition:</td><td><input type="number" >  </td>
</tr>

<tr>
	<td>Miscellaneous:</td><td><input type="number" >  </td>
</tr> -->
<tr>
	<td>Change:</td><td><input type="text" id="change" readonly="readonly" value="0"></td>
</tr>
 
 <tr>
	<td>Total:</td><td><input type="text" readonly="readonly" id="total" value="0"></td>
</tr>

</table>
<div style="border:1px solid #b2b2b2;border-right:none;border-left:none;background:#e3e3e3;padding:4px;text-align:center">
<?php
if($semester=="I"){
	echo "First";
}else{
	echo "Second";
}
?>
 Semester School Year <?=$sy;?></div>
 <?php
if($checkyear>=1){
	?>
	<div id="remainingbal">Balance: <span>0.00</span></div>
	<?php
}else{
 ?>

<div id="remainingbal">Balance: <span></span></div>
<?php
}
?>
<div id="fulltable"> 
<table border id="fullpaymenttable">
<tr>
<td class="payheader"></td><td class="payheader">Payment Description</td><td class="payheader">Amount</td><td class="payheader">Balance</td>
</tr>
<?php
	//for the schedule of fees
	$overalltotal=0;
	$balance=0;
	$paidsched=0;
	$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='sched' and course_id='$studrow[course_id]' and (year_level like '$studrow[year_level]&%' or year_level like '%&$studrow[year_level]') and sy='$sy' and semester='$semester' order by paymentlist.payment_id") or die(mysql_error());
	while ($schedrow=mysql_fetch_array($sched)) {
		$bal=mysql_query("select * from collection where stud_id='$stud_id' and sched_id in (select distinct sched_id from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_id='$schedrow[payment_id]' and sy='$sy' and semester='$semester' )") or die(mysql_error());
								$countbal=mysql_num_rows($bal);
								while ($balrow=mysql_fetch_array($bal)){
									$balance=$balance+$balrow['amount'];
									$paidsched=$paidsched+$balrow['amount'];
								}
								$overalltotal=$overalltotal+$schedrow['amount'];
								

									?>

									<tr id="payment<?=$schedrow['sched_id'];?>" class="paymentrow" schedid="<?=$schedrow['sched_id'];?>"
										 <?php if($countbal==0){?>
										 onclick="checkpayment(<?=$schedrow['sched_id'];?>,<?=$schedrow['amount'];?>)" 
										 name="<?=$schedrow['amount'];?>"
										 <?php
										}else{
											if($paidsched<$schedrow['amount']){
												?>
												onclick="checkpayment(<?=$schedrow['sched_id'];?>,<?=$schedrow['amount']-$paidsched;?>)" 
										 		name="<?=$schedrow['amount']-$paidsched;?>"
										 		<?php
 											}
										}
										 ?>
										 >

										<td class="radiocon" style="width:10px;">
										 <?php
										 if($countbal==0){
										 ?>
										<input type="checkbox" class="notother" value="<?=$schedrow['sched_id'];?><-><?=$schedrow['amount'];?>" name="<?=$schedrow['amount'];?>">
									 	<?php
											 }else{
											 	 if($paidsched<$schedrow['amount']){?>
	 	 											<input type="checkbox" class="notother" value="<?=$schedrow['sched_id'];?><-><?=$schedrow['amount']-$paidsched;?>" name="<?=$schedrow['amount']-$paidsched;?>">
	 	 										<?php
											 	 }
											 }
											 ?>
										</td><td><?=$schedrow['payment_desc'];?></td><td><?=$schedrow['amount'];?></td>
										<td>
										<?php
										if($countbal==0){
											echo $schedrow['amount'];
										}else{
											if($paidsched!=$schedrow['amount']){
												echo number_format($schedrow['amount']-$paidsched,2)."";
											}else{
												echo "0.00";
											}
											
										}	
										?>
										</td>
									</tr>

									<?php
								

								$paidsched=0;
	}//end of
	 

	//for the miscellaneous
	$misc=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='misc' and course_id='$studrow[course_id]' and ( year_level like '$studrow[year_level]&%' or year_level like '%&$studrow[year_level]') and sy='$sy' and semester='$semester' ") or die(mysql_error());
	$misctotal=0;
	$miscpaid=0;
	$countmisc=mysql_num_rows($misc);
	while ($miscrow=mysql_fetch_array($misc)) {
		$bal=mysql_query("select * from collection where   stud_id='$stud_id' and sched_id in (select distinct sched_id from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_id='$miscrow[payment_id]' and  sy='$sy' and semester='$semester')") or die(mysql_error());
	 	$countbal=mysql_num_rows($bal);
		while ($balrow=mysql_fetch_array($bal)) {
			$miscpaid=$miscpaid+$balrow['amount'];
			$balance=$balance+$balrow['amount'];
		}

		$misctotal=$misctotal+$miscrow['amount'];

			 
	}
	$overalltotal=$overalltotal+$misctotal;
	if($miscpaid>=$misctotal){
		?>
		<tr id="paymentmisc" style="display:nones" class="paymentrow" schedid="misc">
			<td class="radiocon">
			</td><td>Miscllaneous</td><td><?=number_format($misctotal,2);?></td>
			<td><?=number_format($misctotal-$miscpaid,2);?></td>
		</tr>
	<?php
	}else{ ?>
		<tr id="paymentmisc" style="display:nones" class="paymentrow" schedid="misc"   name="<?=$misctotal-$miscpaid;?>">
			<td class="radiocon" onclick="checkpayment('misc',<?=$misctotal-$miscpaid;?>,'misc')">
			<input type="checkbox" class="notother" value="misc<-><?=$misctotal-$miscpaid;?>" name="<?=$misctotal-$miscpaid;?>"></td>
			<td onclick="checkpayment('misc',<?=$misctotal-$miscpaid;?>,'misc')">Miscllaneous</td><td><?=number_format($misctotal,2);?></td>
			<td><?=number_format($misctotal-$miscpaid,2);?></td>
		</tr>
		<?php
	}
	?>
		
	<?php



	if($checkyear>=1){
					$newyearlevel='';
					if($studrow['year_level']=="I"){
						$newyearlevel="II";
					}else if($studrow['year_level']=="I"){
						$newyearlevel="III";
					}else if($studrow['year_level']=="I"){
						$newyearlevel="III";
					}
					$studrow['year_level']=$newyearlevel;
		}
		 //for the rle
		$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='rle' and course_id='$studrow[course_id]' and (year_level like '$studrow[year_level]&%' or year_level like '%&$studrow[year_level]') and sy='$sy' and semester='$semester' ") or die(mysql_error());
		
		if(mysql_num_rows($sched)==0){
			//this will check if rle is paid and in the current course rle is not needed he is shifter from amdna/midwidfery to another course which doesnt require rle
			$sched=mysql_query("select schedule_of_fees.sched_id,schedule_of_fees.amount,paymentlist.payment_desc,schedule_of_fees.payment_id from collection,paymentlist,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='rle' and collection.stud_id='$stud_id' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' limit 1") or die(mysql_error());
			$rlerquire="no";
		}else{
			$rlerquire="yes";
		}

		while ($schedrow=mysql_fetch_array($sched)) {
			if($rlerquire=="yes"){
				$overalltotal=$overalltotal+$schedrow['amount'];
			}
			$rlebalance=0;
			 $bal=mysql_query("select collection.amount from collection,schedule_of_fees where  schedule_of_fees.sched_id=collection.sched_id and  schedule_of_fees.payment_id='$schedrow[payment_id]' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester'  and stud_id='$stud_id'");
			 $countbal=mysql_num_rows($bal);
				while ($balrow=mysql_fetch_array($bal)) {
					$rlebalance=$rlebalance+$balrow['amount']; 										
				}
				
				$balance=$balance+$rlebalance;
			if($countbal==0){
					if($rlerquire=="yes"){ ?>
						<tr id="payment<?=$schedrow['sched_id'];?>" schedid="<?=$schedrow['sched_id'];?>" class="paymentrow" onclick="checkpayment(<?=$schedrow['sched_id'];?>,<?=$schedrow['amount'];?>)" name="<?=$schedrow['amount'];?>">
							<td class="radiocon" style="vertical-align:top"><input type="checkbox" class="notother" value="<?=$schedrow['sched_id'];?><-><?=$schedrow['amount'];?>" name="<?=$schedrow['amount'];?>"></td><td><?=$schedrow['payment_desc'];?></td><td><?=$schedrow['amount'];?></td>
							<td><?=number_format($schedrow['amount'],2);?> a</td>
						</tr>
					<?php
					}else{?>
						<tr id="payment<?=$schedrow['sched_id'];?>" schedid="<?=$schedrow['sched_id'];?>" class="paymentrow">
							<td class="radiocon" style="vertical-align:top"></td><td><?=$schedrow['payment_desc'];?></td><td>--</td>
							<td>-<?=number_format($rlebalance,2);?> b</td>
						</tr>
					<?php
					}
 			}else{
				if($schedrow['amount']>=$rlebalance){			

						if($rlerquire=="yes"){ ?>
								<tr id="payment<?=$schedrow['sched_id'];?>" schedid="<?=$schedrow['sched_id'];?>" class="paymentrow" <?php if($schedrow['amount']>$rlebalance){?> onclick="checkpayment(<?=$schedrow['sched_id'];?>,<?=$schedrow['amount']-$rlebalance;?>)" name="<?=$schedrow['amount']-$rlebalance;}?>">
									<td class="radiocon" style="vertical-align:top">
									<?php
									if($schedrow['amount']>$rlebalance){?>
										<input type="checkbox" class="notother" value="<?=$schedrow['sched_id'];?><-><?=$schedrow['amount']-$rlebalance;?>" name="<?=$schedrow['amount']-$rlebalance;?>">
									<?php } ?>
									</td>
									<td><?=$schedrow['payment_desc'];?></td><td><?=$schedrow['amount'];?></td>
									<td><?=number_format($schedrow['amount']-$rlebalance,2);?> cc</td>
								</tr>
								<?php
						}else{ ?>
							<tr id="payment<?=$schedrow['sched_id'];?>" schedid="<?=$schedrow['sched_id'];?>" class="paymentrow">
								<td class="radiocon" style="vertical-align:top"></td><td><?=$schedrow['payment_desc'];?></td><td style="text-align:center">--</td>
								<td>-<?=number_format($rlebalance,2);?></td>
							</tr>
						<?php
						}

				}
			}
		}
	
	//for the Graduation fees
		if($studrow['status']=="grad"){
			$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='grad' and (year_level like 'IV&%' or year_level like '%&IV') and sy='$sy'") or die(mysql_error());
			if(mysql_num_rows($sched)!=0){
				 ?>

				 <tr>
					<td colspan="2">Graduation Fees</td><td></td>
				</tr>
				<?php
				
				 
				while ($schedrow=mysql_fetch_array($sched)) {
					 	$bal=mysql_query("select * from collection where sched_id='$schedrow[sched_id]'  and stud_id='$stud_id'");
					 	$countbal=mysql_num_rows($bal);
						while ($balrow=mysql_fetch_array($bal)) {
							$balance=$balance+$balrow['amount'];
						}

					$overalltotal=$overalltotal+$schedrow['amount'];
					if($countbal==0) { ?>
					
						<tr id="payment<?=$schedrow['sched_id'];?>" schedid="<?=$schedrow['sched_id'];?>" class="paymentrow" onclick="checkpayment(<?=$schedrow['sched_id'];?>,<?=$schedrow['amount'];?>)" name="<?=$schedrow['amount'];?>">
							<td class="radiocon"><input type="checkbox" class="notother" value="<?=$schedrow['sched_id'];?><-><?=$schedrow['amount'];?>" name="<?=$schedrow['amount'];?>"></td><td><?=$schedrow['payment_desc'];?></td><td><?=$schedrow['amount'];?></td>
							<td><?=$schedrow['amount'];?></td>
						</tr>
					<?php
					}
					else{ ?>
						<tr id="payment<?=$schedrow['sched_id'];?>" schedid="<?=$schedrow['sched_id'];?>" class="paymentrow" >
							<td class="radiocon"></td><td><?=$schedrow['payment_desc'];?></td><td><?=$schedrow['amount'];?></td>
							<td>0.00</td>
						</tr>
					<?php
					}	
				}
			}
	}

	//for the additional fees

 	$firststatrow['sy']=$sy;
 	$firststatrow['semester']=$semester;
	if($studrow['status']=="trans" || $studrow['status']=="new"){

	$status1="";
		$status1="new";
	}

		$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='$status1' and (year_level like 'I&%' or year_level like '%&I') and sy='$firststatrow[sy]' and semester='$firststatrow[semester]'") or die(mysql_error());
		if(mysql_num_rows($sched)>0){
					$sched1=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='$status1' and (year_level like 'I&%' or year_level like '%&I') and sy='$firststatrow[sy]' and semester='$firststatrow[semester]'") or die(mysql_error());
					//check all new/trans student fee if paid
					$moneytobefortransfees=0;
					$moneypaidfortransfees=0;
					while($schedrow=mysql_fetch_array($sched1)) {

						$moneytobefortransfees=$moneytobefortransfees+$schedrow['amount'];
						$bal=mysql_query("select * from collection where sched_id='$schedrow[sched_id]' and stud_id='$stud_id'");
						while($balrow=mysql_fetch_array($bal)) {
							$moneypaidfortransfees=$moneypaidfortransfees+$balrow['amount'];
						}
					}
			 		if(mysql_num_rows($sched)!=0 && $moneypaidfortransfees<$moneytobefortransfees || ($firststatrow['sy']==$sy && $firststatrow['semester']==$semester)){
						?>
						<tr>
							<td colspan="5">Additional Fees for New Students/Transferees</td>
						</tr>
						<?php
						
						$status=$studrow['status'];
						if($status=="new" or $status=="trans"){
							$year="I";
						}else{
							$year=$studrow['year_level'];
						}
						while ($schedrow=mysql_fetch_array($sched)){
							 $bal=mysql_query("select * from collection where sched_id='$schedrow[sched_id]' and stud_id='$stud_id'");
							 $countbal=mysql_num_rows($bal);
							 $balancetopaybysched=0;
								while ($balrow=mysql_fetch_array($bal)) {
									$balance=$balance+$balrow['amount'];
									$balancetopaybysched=$balancetopaybysched+$balrow['amount'];
								}

						$overalltotal=$overalltotal+$schedrow['amount'];
						?>
							<tr id="payment<?=$schedrow['sched_id'];?>" schedid="<?=$schedrow['sched_id'];?>" class="paymentrow" 
								<?php if($schedrow['amount']>$balancetopaybysched){?> 
								onclick="checkpayment(<?=$schedrow['sched_id'];?>,<?=$schedrow['amount']-$balancetopaybysched;?>)"
								 name="<?=$schedrow['amount']-$balancetopaybysched;?>"
								 <?php
								 	} 
								 ?>

								 >
								
								<td class="radiocon">
								<?php
								if($countbal==0){?>
								<input type="checkbox" class="notother" value="<?=$schedrow['sched_id'];?><-><?=$schedrow['amount'];?>" name="<?=$schedrow['amount'];?>">
								<?php
								}else{

									if($schedrow['amount']>$balancetopaybysched){
										?>
										<input type="checkbox" class="notother" value="<?=$schedrow['sched_id'];?><-><?=$schedrow['amount']-$balancetopaybysched;?>" name="<?=$schedrow['amount']-$balancetopaybysched;?>">
										<?php
									}

								}
								?>
								</td>
								<td><?=$schedrow['payment_desc'];?></td><td><?=$schedrow['amount'];?></td>
								<td> 
								<?php
								if($countbal==0){
									echo number_format($schedrow['amount'],2);
			 					}else{
									
									if($schedrow['amount']>$balancetopaybysched){
										echo number_format($schedrow['amount']-$balancetopaybysched,2);
									}else{
										echo "0.00";
									}					
			 					}
								?>
								</td>
							</tr>
						<?php
					}
				}
	}
	$advancepayment=mysql_query("select * from refund where stud_id='$stud_id' and to_sy='$sy' and to_semester='$semester' ");
	while($advanceamount=mysql_fetch_array($advancepayment)){
		$balance=$balance+$advanceamount['amount'];?>
			<tr class="paymentrow">
				<td></td>
				<td>Advance Payment</td>
				<td></td>
				<td>-<?=$advanceamount['amount'];?></td>
			</tr>
		<?php

	}
	
	?>
<tr>
	<td  colspan="2" style="text-align:right;font-weight:bold">Total</td><td><?php echo number_format($overalltotal,2)?></td>
	<td id="baltopay" name="<?=$overalltotal-$balance;?>"><?=number_format($overalltotal-$balance,2);?></td>
	
</tr>
</table> 
<div  style="clear:both"></div>
<span class="checkall" onclick="checkall()">Check All</span> / 
<span  class="checkall"  onclick="uncheckall()">Uncheck All</span>
<button style="padding:5px;">Save Payment</button>

</div>

<div id="othertablecon">
<table border id="otherpaytable">
<?php
//for the other fees
	$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='other' and year_level='' and sy='$sy' and semester='$semester'") or die(mysql_error());
			?>
		<tr>
			<td class="payheader"></td><td class="payheader">Other fees</td><td class="payheader">Amount</td>
		</tr>
		<?php
		
		$status=$studrow['status'];
		if($status=="new"){
			$year="I";
		}else{
			$year=$studrow['year_level'];
		}
		while ($schedrow=mysql_fetch_array($sched)) {
				?>
				<tr id="payment<?=$schedrow['sched_id'];?>" schedid="<?=$schedrow['sched_id'];?>" class="paymentrow" name="<?=$schedrow['amount'];?>">
					<td onclick="checkpayment(<?=$schedrow['sched_id'];?>,<?=$schedrow['amount'];?>,'other')">
					<input type="checkbox" class="other" refresh="<?=$schedrow['amount'];?>" value="<?=$schedrow['sched_id'];?><-><?=$schedrow['amount'];?>" name="<?=$schedrow['amount'];?>">

					</td><td onclick="checkpayment(<?=$schedrow['sched_id'];?>,<?=$schedrow['amount'];?>,'other')"><?=$schedrow['payment_desc'];?>
					
					</td><td  style="position:relative">
					<?php 
						if($schedrow['payment_desc']=="Completion Fee"){?>
					<input type="number" value="1"  required="required" onchange="completionfee(<?=$schedrow['sched_id'];?>,'<?=$schedrow['amount'];?>',this)" onkeyup="completionfee(<?=$schedrow['sched_id'];?>,'<?=$schedrow['amount'];?>',this)" placeholder="Units" class="completionfee" style="display:none;top:-1px;padding:2px;position:absolute;left:-60px;width:50px">
						<?php
						}
						?>
					<?=$schedrow['amount'];?></td>
				</tr>
			<?php
		}
	  		?>
		 </form>
</table>

</div>
</div>
<div style="clear:both"></div>
<script>

// $('#cash').keyup(function(event) {
// 	var a=$('#change');
// 	var total=0;
// 	var b=parseInt($('#baltopay').attr('name'));
// 	if($(this).val()<b){
// 		a.val(0)
// 	}else{
// 		a.val($(this).val()-b);
// 	}
// });




function completionfee(a,b,c){
	var unit=$(c).val();
	var total=unit*parseInt(b);
	$('#payment'+a+" input[type='checkbox']").val(a+"<->"+total).attr('name',total);
	var cash=$('#cash').val();
	var change=$('#change');
	var total2=$('#total');
	
	
 	var jake=0;
 	$(".paymentrow[id!=payment"+a+"] input[type='checkbox'][checked='checked']").each(function(){
 		jake=jake+parseInt($(this).attr('name'));
 	});
	total2.val(jake+total);
	change.val(parseInt($('#cash').val())-parseInt($('#total').val()));

 	 	
}
function paymenthistory(a){
	var b=$('#paymenthistcon');
	var c=$('#studinfotablecon,#paycon');
	$.ajax({
		type:'post',
		url:'student/paymenthist.php',
		data:{'stud_id':a},
		success:function(data){
				b.html(data).show();
				c.hide();

		},
		error:function(){
			connection();
		}
	});
}

function scanreceipt(a,ab){
	var b=$('#paymenthistcon');
	var c=$('#studinfotablecon,#paycon');
	var sy="";
	if(ab=="null"){
		sy="null";
	}else{
		sy=$('#ssy').val();
	}
	$.ajax({
		type:'post',
		url:'student/scanreceipt.php',
		data:{'stud_id':a,'sy':sy},
		success:function(data){
				b.html(data).show();
				c.hide();

		},
		error:function(){
			connection();
		}
	});
}

function statementofaccount(a){
	var b=$('#paymenthistcon');
	var c=$('#studinfotablecon,#paycon');
	$.ajax({
		type:'post',
		url:'student/statementofaccount.php',
		data:{'stud_id':a},
		success:function(data){
				b.html(data).show();
				c.hide();

		},
		error:function(){
			connection();
		}
	});
}

function saverefund(a){
	$.ajax({
		type:'post',
		url:'student/saverefund.php',
		data:{'stud_id':'<?=$stud_id;?>','amount':'<?=$overalltotal-$balance;?>','sy':'<?=$sy;?>','semester':'<?=$semester;?>','check':a},
		success:function(da){
 		},
		error:function(){
			saverefund();
		}
	});
}
	
$(function() {
if($('#savenewstatus:visible').length==1){
		$('#remainingbal span').html("0.00");
}else{
	$('#remainingbal span').html($('#baltopay').html());
}
 if($('#remainingbal span').html()=="0.00"){
	saverefund("delete");
	$('#remainingbal span').html("0.00");
	$('#remainingbal').css({'background':'#e3ffdb','border':'1px solid #4dcd3c'});

}else if(parseInt($('#baltopay').attr('name'))<0){
	saverefund("no");
  	$('#remainingbal').css({'background':'#c8e1fa','border':'1px solid blue'});
 }
 else if(parseInt($('#baltopay').attr('name'))>0){
		saverefund("delete");
 	$('#remainingbal').css({'background':'#fedcd5','border':'1px solid #fb836b'});
 }

$('#paytable input:gt(0)').keyup(function(event) {

	var amount=parseInt($('#cash').val());
	var total=0;
	$('input[checked=checked]').each(function(){
		total+=parseInt($(this).attr('name'));
	});

	if($('input[checked=checked]').length>0){
		$('#change').val(amount-total);
	}
});
});

function savenewstatus(){
	var stud_id=$('#sfname').attr("name");
	var course_id=$('#course_id').val();
	var year_level=$('#year_level').val();
	var sstatus=$('#sstatus').val();
	var load=$('#studloader');
	load.show();
	$.ajax({
		type:'post',
		url:'student/savenewstatus.php',
		data: {'stud_id':stud_id,'course_id':course_id,'year_level':year_level,'status':sstatus},
		success:function(data){
			selectsearchstud(stud_id);
		},
		error:function(){
			load.hide();
			alert("Connection error,please try again.");
		}
	})
}

function savefullpayment(){

		var paymentdata;		
		$("input[checked='checked'].other").each(function(){
			var amount=$(this).val();
			paymentdata+="[endline]"+amount;
		});		
		var receipt=$('#receipt').val();
		var stud_id=$('#sfname').attr("name");
		var scholar_id=$('#scholar_id').val();
		var cash=$('#cash').val();
		
		$("input[checked='checked'].notother").each(function(){
			var amount=$(this).val();
			paymentdata+="[endline]"+amount;
		});
		
 			$.ajax({
			type:'post',
			url:'student/savefullpayment.php',
			data:{'cash':cash,'scholar_id':scholar_id,'receipt':receipt,'stud_id':stud_id,'paymentdata':paymentdata,'sy':'<?=$sy;?>','semester':'<?=$semester;?>'},
			success:function(data){
				alert(data)
				selectsearchstud($('#sfname').attr("name"));
			},error:function(){
				connection();
			}
			});
			return false;	
}

function checkall(){
var total=0;
	if($('#savenewstatus:visible').length!=1){
		$("#fullpaymenttable .paymentrow[name]").each(function(){
			var a=$(this).attr("schedid")
			var val=$('#payment'+a+" input").val();
			$('#payment'+a+" td:first").html("<input type='checkbox' checked='checked' value='"+val+"'>");
			total=total+parseInt($(this).attr("name"));
			})
		$('#total').val(total);
	}else{
		alert("Save first the new status.")
	}
}
function uncheckall(){
$('input[type=checkbox]').removeAttr("checked");
$('#total').val(0);

}
function checkpayment(a,amount,misc){
var b= $('#payment'+a+" [type=checkbox]");
var c= $('#payment'+a+" [type=checkbox]").attr("checked");
var name= $('#payment'+a+" [type=checkbox]").attr("name");
var refresh=b.attr('refresh');
 	var total=$('#total');
	var cash=$('#cash');
 	if($('#payment'+a+" input").attr("disabled")!="disabled"){

			if(c=="checked"){
			b.attr("checked",false);
			var origamount=b.attr('name');
			b.val(a+"<->"+origamount);			
 				b.attr("name",refresh);
 				
 								if(misc=="other"){
									var total2=0;
									$(".paymentrow input[type='checkbox'][checked='checked'][class='other']").each(function(){
										total2=total2+parseInt($(this).attr('name'));
									});
									$('#change').val(cash.val()-(parseInt($('#baltopay').attr('name'))+parseInt(total2)));
									if($('#change').val()<0){
										$('#change').val(0);
									}
								}

				}else{
					if(misc=="other"){
						$("#payment"+a+" td:first").html("<input type='checkbox' checked='checked' value='"+a+"<->"+amount+"' name='"+name+"' refresh='"+refresh+"' class='other'>");
					}else{
						$("#payment"+a+" td:first").html("<input type='checkbox' class='notother' checked='checked' value='"+a+"<->"+amount+"' name='"+name+"' refresh='"+refresh+"'>");
					}
							if(cash.val()>parseInt($('#baltopay').attr('name'))){
								if(misc=="other"){
									var total2=0;
									$(".paymentrow input[type='checkbox'][checked='checked'][class='other']").each(function(){
										total2=total2+parseInt($(this).attr('name'));
									});
									$('#change').val(cash.val()-(parseInt($('#baltopay').attr('name'))+parseInt(total2)));
									if($('#change').val()<0){
										console.log("message");
											$('#change').val(0);
									}
								}

							}
					}

				if(misc=='misc'){
					// $('#misc').removeAttr('name').val("");
					$('.submisc span').html("<input type='checkbox' checked='checked'>");
				}
		}else{
			alert("Save First the new status.");
		}
		var jake=0;
		$(".paymentrow input[type='checkbox'][checked=checked]").each(function(){
			jake=jake+parseInt($(this).attr('name'));
		});
		total.val(jake);
		
		$("#payment"+a+" td:eq(2) input").toggle().val(1);
}
function updatestudentrecord(a){
	$('.studoption').hide();
	$('.savestudentstatus,.studoptioncancel').show();
	$('#scholar').show();
	$('#scholarcon span').hide();	
 	$('#studinfotable input:lt(2)').show();
 	$('#studinfotable span:lt(2)').hide();
 }
 function updatestudentstatus(){
 	$('#studinfotable input,#studinfotable select,.studoptioncancel,.savestudentstatus').show();
 	$('#studinfotable span,.studoptionrecord,.studoptionstatus').hide();
 }

 function savestudentstatus(){
 	var course_id=$('#course_id').val();
 	var year_level=$('#year_level').val();
 	var status=$('#sstatus').val();
 	var fname=$('#sfname').val();
 	var lname=$('#slname').val();
 	var stud_id=$('#sfname').attr("name");
 	var scholar=$('#scholar:visible').val();
 	var loader=$('#studloader');
   	loader.show();
 
 		$.ajax({
 			type:'post',
 			url:'student/savestudentstatus.php',
 			data:{'semester':'<?=$semester;?>','sy':'<?=$sy;?>','fname':fname,'lname':lname,'scholar':scholar,'stud_id':stud_id,'course_id':course_id,'year_level':year_level,'status':status},
 			success:function(data){
 				alert(data);
 			selectsearchstud(stud_id);
 			},
 			error:function(){
 				loader.hide();
 				connection();
 			}
 		});
 	 	}
 </script>
