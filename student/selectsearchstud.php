<?php
session_start();
include '../dbconfig.php';
include '../rand.php';
$stud_id=$_POST['stud_id'];
$sy=$_SESSION['sy'];
$semester=$_SESSION['semester'];
if($_POST['sy']!=""){
	$sy=$_POST['sy'];
	$semester=$_POST['semester'];
 }
  

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
<style type="text/css">
	.studoption { padding:6px;float:right;}
 </style>
 <script>
 $(document).ready(function() {
 		$('#pheader span').click(function(){
 			$('#pheader span').css({"box-shadow":"none","border-radius":"4px","border":"1px solid transparent"});
 			$(this).css({"box-shadow":"inset 0 0 3px #e7e7e7","border-radius":"4px","border":"1px solid #e7e7e7"});
  		});
 });
 </script>
<div id="pheader">
<span onclick="selectsearchstud(<?=$studrow['stud_id']?>)" style="box-shadow:inset 0 0 3px #e7e7e7;border-radius:4px;border:1px solid #e7e7e7;">Student Info</span> | 
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
<table border id="studinfotable" style="text-transform:capitalize">
<tr>
	<?php
		$checkyear=0;
		$lastyear=explode("-", $studrow['sy']);
		$thisyear=explode("-", $sy);
		$checkyear=$thisyear[1]-$lastyear[1];
		$display="";
		$display2="";
		$checkstat=mysql_query("select * from student_status where stud_id='$stud_id' and semester='$semester' and sy='$sy'");
		$countstat=mysql_num_rows($checkstat);
		if($countstat==0){
				$display="style='display:inline-block'";
				$display2="style='display:none'";
		}
		?>
	<td>Student Number</td>
	<td><span  > <?=$studrow['stud_number'];?></span>
	<input type="text"   id="stud_number" value="<?=$studrow['stud_number'];?>" name="<?=$studrow['stud_number'];?>"></td>
</tr>

<tr>
	<td>Last Name</td><td><span ><?=$studrow['fname'];?></span>
		<input    type="text" id="sfname" value="<?=$studrow['fname'];?>" name="<?=$studrow['stud_id'];?>">
	</td>
</tr>
<tr>
	<td>Last Name</td><td><span ><?=$studrow['lname'];?></span>
		<input   type="text" id="slname" value="<?=$studrow['lname'];?>">
	</td>
</tr>
<?php
		$checkscholar=mysql_query("select * from student_status,scholarship where scholarship.scholar_id=student_status.scholar_id and stud_id='$studrow[stud_id]' and course_id='$studrow[course_id]' and year_level='$studrow[year_level]' and sy='$sy' and semester='$semester'") or die(mysql_error());
			$checkscholarrow=mysql_fetch_array($checkscholar);
 
?>
<tr  id="scholarcon">
	<td>Scholarship</td><td><span>
		<?php
		if(mysql_num_rows($checkscholar)>0){
		echo $checkscholarrow['description']."-".$checkscholarrow['amount'];
		}else{
			echo "None";
		}
		?>
	</span>
	<select id="scholar">
		<?php
		if(mysql_num_rows($checkscholar)>0){
			?>
			<option value="<?=$checkscholarrow['scholar_id'];?>"><?=$checkscholarrow['description'];?></option>		
			<?php
		}
		?>
		<?php
			$sch=mysql_query("select *  from scholarship where scholar_id!='$studrow[scholar_id]' order by description asc");
			while($row=mysql_fetch_array($sch)){
				?>
		<option value="<?=$row['scholar_id'];?>"><?=$row['description'];?> - <?=$row['amount'];?></option>		

				<?php
			}
		?>
		<option value="0" <?php if(mysql_num_rows($checkscholar)==0){echo "selected='selected'";};?>>None</option>
	</select>
	</td>
</tr>
 

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
		<option <?php if($studrow['status']=="ongoing") echo "selected='selected'"; ?> value="ongoing">Ongoing</option>
		<option <?php if($studrow['status']=="new") echo "selected='selected'"; ?> value="new">New</option>
		<option <?php if($studrow['status']=="grad") echo "selected='selected'"; ?> value="grad">Graduating</option>
		<option <?php if($studrow['status']=="trans") echo "selected='selected'"; ?> value="trans">Transferee</option>
		<option <?php if($studrow['status']=="shiftee") echo "selected='selected'"; ?> value="shiftee">Shiftee</option>
  	</select>
	</td>
</tr>
<?php
if($studrow['status']!="Cancelled"){?>
<tr>
	<td colspan="2">
	<?php
	//check if the student status is in the  current sy and semester;
	
	if($countstat>0){

	?>

		 	<button   onclick="updatestudentstatus(this)" class="studoption studoptionstatus" style="display:inline-block">Update</button>

			<button onclick="selectsearchstud(<?=$stud_id;?>)" class="studoption studoptioncancel">Cancel</button>
			<button onclick="savestudentstatus(this)" class="studoption savestudentstatus">Save Update</button>
			<?php
	}elseif($studrow['status']!="Cancelled"){ ?>
 		
  		<button onclick="savenewstatus()" class="studoption savenewstatus" id="savenewstatus" style="display:inline-block;float:right">Save New Status</button>
  		<div style="clear:both"></div>
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
<?php
}
?>
</table>

<?php
include 'saverefundfunction.php';
 
?>
<div id="unpaidsy" style="margin-left:0px">
	<div class="paymentheader">Balances<img src="img/loading.gif" id="studloader" style="display:none;position:relative;top:3px"></div>
	<table border style="width:100%">
		 
		<tr>
			<th>SY</th>
			<th>Sem</th>
			<th>Balance</th>
			<!-- <th>Paid</th> -->
 			<th>Action</th>
		</tr>
		
 		<?php
	// get all status of the the student
 		$checkcount=0;
	$getstatus=mysql_query("select * from student,student_status where student.stud_id=student_status.stud_id and student.stud_id='$stud_id'");
	while ($statusrow=mysql_fetch_array($getstatus)) {
			$course_id=$statusrow['course_id'];
			$year_level=$statusrow['year_level'];
			$course_id=$statusrow['course_id'];
			$status=$statusrow['status'];
			$sy2=$statusrow['sy'];
			$semester2=$statusrow['semester'];
 			//check the advace payment for this semester


 
 		$moneytobepaidpersemester=0;
		
		//check the tuition
		$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and (payment_group='sched' or payment_group='rle' or payment_group='misc') and course_id='$course_id' and (year_level like '%&$year_level&%' or year_level like '$year_level&%' or year_level like '%&$year_level') and sy='$sy2' and semester='$semester2' ") or die(mysql_error());
		while ($schedrow=mysql_fetch_array($sched)){
			$moneytobepaidpersemester=$moneytobepaidpersemester+$schedrow['amount'];

		}
	 

		// //check the graduation fees
		if($status=="grad"){
 			$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='grad' and (year_level like '%&IV&%' or year_level like 'IV&%' or year_level like '%&IV') and sy='$sy2'") or die(mysql_error());
			while ($schedrow=mysql_fetch_array($sched)){
				$moneytobepaidpersemester=$moneytobepaidpersemester+$schedrow['amount'];
				
			}
		}


		// //check the new/trans student fees//////////////////////
			if($status=="trans" || $status=="new"){		
				$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='new' and (year_level like '%&I&%' or year_level like 'I&%' or year_level like '%&I') and sy='$sy2' and semester='$semester2'") or die(mysql_error());
			 
				while ($schedrow=mysql_fetch_array($sched)) {
					$moneytobepaidpersemester=$moneytobepaidpersemester+$schedrow['amount'];
					
				}
			} 
	 
 		$moneypaidpersemester=0;
		$jake=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees,paymentlist where  schedule_of_fees.sched_id=collection.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and (payment_group='misc' or payment_group='sched' or payment_group='misc' or payment_group='rle'  or payment_group='grad' or payment_group='new') and stud_id='$stud_id' and  schedule_of_fees.sy='$sy2' and (schedule_of_fees.semester='$semester2' or schedule_of_fees.semester='0')") or die(mysql_error());
 		$countme=mysql_fetch_array($jake);
		 
		$getadvancepayment=mysql_query("select SUM(amount) as amount from exceeded_money where stud_id='$stud_id' and to_semester='$semester2' and to_sy='$sy2' and action='Advance Payment'");
 		$amountadvancepayment=mysql_fetch_array($getadvancepayment);
		$moneypaidpersemester=$countme['amount']+$amountadvancepayment['amount'];
	 

		$checkrefunded=mysql_query("select *,SUM(amount) as amount from exceeded_money where stud_id='$stud_id' and to_sy='$statusrow[sy]' and to_semester='$statusrow[semester]' and action=''");
		$advanceamount=mysql_fetch_array($checkrefunded);
		if(mysql_num_rows($checkrefunded)>0){
				$moneypaidpersemester+=$advanceamount['amount'];
		}

		 	$rand=rand();
			$jake=$moneytobepaidpersemester-$moneypaidpersemester;
			$jake2=$moneytobepaidpersemester-$moneypaidpersemester;
			$remark="";
			$checkrefunded=mysql_query("select * from exceeded_money where stud_id='$stud_id' and from_sy='$statusrow[sy]' and from_semester='$statusrow[semester]'");
			$checkrow=mysql_fetch_array($checkrefunded);
			if($checkrow['action']=="Refunded"  || $checkrow['action']=="Advance Payment"|| $statusrow['status']=="Cancelled"){
				$jake='0.00';
			} 
			?>
			<tr id="balancelist<?=$rand;?>">
				<td style="text-align:center;white-space:nowrap"><?=$statusrow['sy'];?></td>
				<td style="text-align:center" id="balancedisplay"><?=$statusrow['semester'];?></td>
				<td style="text-align:right"><?=number_format($jake,2);?></td>
				<!-- <td style="text-align:right"><?=number_format($moneypaidpersemester,2);?></td> -->
				<td>
 				<?php
				
				if($_SESSION['type']=="admin" && $statusrow['status']!="Cancelled" && $statusrow['sy']==$_SESSION['sy'] && $statusrow['semester']==$_SESSION['semester']){?>
				
				
				<button onclick="cancelstatus(<?=$stud_id;?>,'<?=$statusrow['semester'];?>','<?=$statusrow['sy'];?>')" style="float:right">Cancel</button>
					 
					<?php
					}
				 

				if($jake>0 && $statusrow['status']!='Cancelled' and ($semester!="$statusrow[semester]" && $sy=="$statusrow[sy]" || $sy!="$statusrow[sy]")){ ?>	
				<button onclick="selectsearchstud(<?=$stud_id;?>,'<?=$statusrow['semester'];?>','<?=$statusrow['sy'];?>',this)" style="float:right">Pay</button>
				
				<?php
				}
				if($statusrow['status']=="Cancelled"){
					echo "<center>Cancelled</center>";
				}
				?>
  					
				</td>
			</tr>
			<?php
			

			if($jake<0){

				saverefundfunction($stud_id,$jake,$sy2,$semester2,'no');
				?>
				<script>
 				$('#balancelist<?=$rand;?> td').css("background",'#c8e1fa').attr("title","To be refund");
 				$('#balancelist<?=$rand;?> #balancedisplay').html();
 				</script>
				<?php
			}

			if($jake==0){
				saverefundfunction($stud_id,$jake,$sy2,$semester2,'delete');
			}

			if($jake>0){
				saverefundfunction($stud_id,$jake,$sy2,$semester2,'delete');
				?>
				<script>
 				$('#balancelist<?=$rand;?> td').css("background",'#ffd6d2');
				</script>
				<?php
			} 

			if($checkrow['action']=='Refunded'){
				?>
				<script>
  				$('#balancelist<?=$rand;?> td').css("background",'#e0fdc9');
				</script>
				<?php
			}
	 		
	 		if($jake==0){
				?>
				<script>
  				$('#balancelist<?=$rand;?> td').css("background",'#e0fdc9').attr("title","Fully paid");
				</script>
				<?php
			}


$checkifrefunded=mysql_query("select * from exceeded_money where from_sy='$statusrow[sy]' and  stud_id='$stud_id' and from_semester='$statusrow[semester]'") or die(mysql_error());
 $checkifrefundedrow=mysql_fetch_array($checkifrefunded);
if($checkifrefundedrow['action']=="Refunded"  || $checkifrefundedrow['action']=="Advance Payment"){
?>
<script>
 $('#balancelist<?=$rand;?> td').css("background",'#e0fdc9');
$('#balancelist<?=$rand;?> button').remove();
</script>
 
  <?php
}
 

		
	} 

?>


	</table>
</div>


</div>

<?php
$getlastor=mysql_query("select * from collection  where user_id='$_SESSION[user_id]'order by col_id desc");
$lastorrow=mysql_fetch_array($getlastor);
$suggestor="";
if(mysql_num_rows($getlastor)){
	$suggestor=$lastorrow['receipt_num']+1;
}
?>


<div id="paycon">
<div class="paymentheader">Payment Area

</div>
<form id="paymentform" onsubmit="return savefullpayment()">
<table border="" id="paytable">

<tr>
	<td>Receipt Number:</td><td><input type="text" id="receipt" required="required" value="<?php printf("%07d", $suggestor);?>"></td>
</tr>

<tr>
	<td>Cash:</td><td><input type="number" id="cash" onkeyup="checkchange('a')" required="required" autofocus="autofocus"></td>
</tr>

<tr>
	<td>Amount to Pay:</td><td><input type="number" id="amounttopay" onkeyup="checkchange('b')" required="required"></td>
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
	<div id="remainingbal"><a>Balance</a>: <span>0.00</span></div>
	<?php
}else{
 ?>

<div id="remainingbal"><a>Balance</a>: <span></span></div>
<?php
}

$hidetable=10;
if($studrow['status']=="Cancelled"){
	$hidetable="style='display:none'";
}
if($studrow['status']=="Cancelled"){
?>
<div style="background:#edfee4;font-size:15px;padding:10px;text-align:center">Cancelled</div>
<?php
}
?>
<div id="fulltable"  <?=$hidetable;?>  > 
<table border id="fullpaymenttable">
<tr>
<td class="payheader"></td><td class="payheader">Payment Description </td><td class="payheader">Amount</td><td class="payheader">Balance</td>
</tr>
<?php
	//for the schedule of fees
	$overalltotal=0;
	$balance=0;
	$paidsched=0;
	$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='sched' and course_id='$studrow[course_id]' and (year_level like '%&$studrow[year_level]&%' or year_level like '$studrow[year_level]&%' or year_level like '%&$studrow[year_level]') and sy='$sy' and semester='$semester' order by paymentlist.payment_id") or die(mysql_error());
 	while ($schedrow=mysql_fetch_array($sched)) {
		$paidsched=0; 
		$bal=mysql_query("select * from collection where stud_id='$stud_id' and remark='0'  and sched_id in (select distinct sched_id from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_id='$schedrow[payment_id]' and sy='$sy' and semester='$semester' )") or die(mysql_error());
								$countbal=mysql_num_rows($bal);
								while ($balrow=mysql_fetch_array($bal)){
									$balance=$balance+$balrow['amount'];
									$paidsched=$paidsched+$balrow['amount'];
								}
								$overalltotal=$overalltotal+$schedrow['amount'];
								
								$hide2="";
								if($schedrow['amount']==0 && $paidsched==0){
										$hide2="style='display:none'";
 								}

									?>

									<tr  id="payment<?=$schedrow['sched_id'];?>"  <?=$hide2;?>  class="paymentrow" schedid="<?=$schedrow['sched_id'];?>"
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
								

								
	}//end of
	 

	//for the miscellaneous
	$misc=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='misc' and course_id='$studrow[course_id]' and ( year_level like '%&$studrow[year_level]&%' or year_level like '$studrow[year_level]&%' or year_level like '%&$studrow[year_level]') and sy='$sy' and semester='$semester' ") or die(mysql_error());
	$misctotal=0;
	$miscpaid=0;
	$countmisc=mysql_num_rows($misc);
	while ($miscrow=mysql_fetch_array($misc)) {
		$bal=mysql_query("select * from collection where   stud_id='$stud_id' and remark='0' and sched_id in (select distinct sched_id from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_id='$miscrow[payment_id]' and  sy='$sy' and semester='$semester')") or die(mysql_error());
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
		<tr id="paymentmisc"  onclick="checkpayment('misc',<?=$misctotal-$miscpaid;?>,'misc')" style="display:nones" class="paymentrow" schedid="misc"   name="<?=$misctotal-$miscpaid;?>">
			<td class="radiocon">
			<input type="checkbox" class="notother" value="misc<-><?=$misctotal-$miscpaid;?>" name="<?=$misctotal-$miscpaid;?>"></td>
			<td>Miscllaneous</td><td><?=number_format($misctotal,2);?></td>
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
		$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='rle' and course_id='$studrow[course_id]' and (year_level like '%&$studrow[year_level]&%' or year_level like '$studrow[year_level]&%' or year_level like '%&$studrow[year_level]') and sy='$sy' and semester='$semester' ") or die(mysql_error());
		
		if(mysql_num_rows($sched)==0){
			//this will check if rle is paid and in the current course rle is not needed he is shifter from amdna/midwidfery to another course which doesnt require rle
			$sched=mysql_query("select schedule_of_fees.sched_id,schedule_of_fees.amount,paymentlist.payment_desc,schedule_of_fees.payment_id from collection,paymentlist,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and remark='0' and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='rle' and collection.stud_id='$stud_id' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' limit 1") or die(mysql_error());
			$rlerquire="no";
		}else{
			$rlerquire="yes";
		}

		while ($schedrow=mysql_fetch_array($sched)) {
			if($rlerquire=="yes"){
				$overalltotal=$overalltotal+$schedrow['amount'];
			}
			$rlebalance=0;
			 $bal=mysql_query("select collection.amount from collection,schedule_of_fees where  schedule_of_fees.sched_id=collection.sched_id and remark='0' and  schedule_of_fees.payment_id='$schedrow[payment_id]' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester'  and stud_id='$stud_id'");
			 $countbal=mysql_num_rows($bal);
				while ($balrow=mysql_fetch_array($bal)) {
					$rlebalance=$rlebalance+$balrow['amount']; 										
				}
				$rlehide="";
				if($schedrow['amount']==0 && $rlebalance==0){
					$rlehide="style='display:none'";
				}
				$balance=$balance+$rlebalance;
			if($countbal==0){
					if($rlerquire=="yes"){ ?>
						<tr id="payment<?=$schedrow['sched_id'];?>" <?=$rlehide;?> schedid="<?=$schedrow['sched_id'];?>" class="paymentrow" onclick="checkpayment(<?=$schedrow['sched_id'];?>,<?=$schedrow['amount'];?>)" name="<?=$schedrow['amount'];?>">
							<td class="radiocon" style="vertical-align:top"><input type="checkbox" class="notother" value="<?=$schedrow['sched_id'];?><-><?=$schedrow['amount'];?>" name="<?=$schedrow['amount'];?>"></td><td><?=$schedrow['payment_desc'];?></td><td><?=$schedrow['amount'];?></td>
							<td><?=number_format($schedrow['amount'],2);?></td>
						</tr>
					<?php
					}else{?>
						<tr id="payment<?=$schedrow['sched_id'];?>" schedid="<?=$schedrow['sched_id'];?>" class="paymentrow">
							<td class="radiocon" style="vertical-align:top"></td><td><?=$schedrow['payment_desc'];?></td><td>--</td>
							<td>-<?=number_format($rlebalance,2);?></td>
						</tr>
					<?php
					}
 			}else{
						if($rlerquire=="yes"){ ?>
								<tr id="payment<?=$schedrow['sched_id'];?>" <?=$rlehide;?> schedid="<?=$schedrow['sched_id'];?>" class="paymentrow" <?php if($schedrow['amount']>$rlebalance){?> onclick="checkpayment(<?=$schedrow['sched_id'];?>,<?=$schedrow['amount']-$rlebalance;?>)" name="<?=$schedrow['amount']-$rlebalance;}?>">
									<td class="radiocon" style="vertical-align:top">
									<?php
									if($schedrow['amount']>$rlebalance){?>
										<input type="checkbox" class="notother" value="<?=$schedrow['sched_id'];?><-><?=$schedrow['amount']-$rlebalance;?>" name="<?=$schedrow['amount']-$rlebalance;?>">
									<?php } ?>
									</td>
									<td><?=$schedrow['payment_desc'];?></td><td><?=$schedrow['amount'];?></td>
									<td><?=number_format($schedrow['amount']-$rlebalance,2);?></td>
								</tr>
								<?php
						}else{ ?>
							<tr id="payment<?=$schedrow['sched_id'];?>" <?=$rlehide;?> schedid="<?=$schedrow['sched_id'];?>" class="paymentrow">
								<td class="radiocon" style="vertical-align:top"></td><td><?=$schedrow['payment_desc'];?></td><td style="text-align:center">--</td>
								<td>-<?=number_format($rlebalance,2);?></td>
							</tr>
						<?php
						}

				}
		}
	
	//for the Graduation fees
		if($studrow['status']=="grad"){
			$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='grad' and (year_level like '%&IV&%' or year_level like 'IV&%' or year_level like '%&IV') and sy='$sy'") or die(mysql_error());
			if(mysql_num_rows($sched)!=0){
				 ?>

				 <tr>
					<td colspan="2">Graduation Fees</td><td></td>
				</tr>
				<?php
				
				 
				while ($schedrow=mysql_fetch_array($sched)) {
						$paidamountingradeverypayment=0;
					 	$bal=mysql_query("select * from collection where sched_id='$schedrow[sched_id]'  and stud_id='$stud_id' and remark='0'");
					 	$countbal=mysql_num_rows($bal);
						while ($balrow=mysql_fetch_array($bal)) {
							$balance=$balance+$balrow['amount'];
							$paidamountingradeverypayment=$paidamountingradeverypayment+$balrow['amount'];
						}
							$hidegrad="";
						if($schedrow['amount']==0 && $paidamountingradeverypayment==0 ){
							$hidegrad="style='display:none'";
						}
					$overalltotal=$overalltotal+$schedrow['amount'];
					if($countbal==0) { ?>
					
						<tr id="payment<?=$schedrow['sched_id'];?>" <?=$hidegrad;?> schedid="<?=$schedrow['sched_id'];?>" <?=$hidegrad;?> class="paymentrow" onclick="checkpayment(<?=$schedrow['sched_id'];?>,<?=$schedrow['amount'];?>)" name="<?=$schedrow['amount'];?>">
							<td class="radiocon"><input type="checkbox" class="notother" value="<?=$schedrow['sched_id'];?><-><?=$schedrow['amount'];?>" name="<?=$schedrow['amount'];?>"></td><td><?=$schedrow['payment_desc'];?></td><td><?=$schedrow['amount'];?></td>
							<td><?=number_format($schedrow['amount'],2);?></td>
						</tr>
					<?php
					}
					else{ ?>
						<tr id="payment<?=$schedrow['sched_id'];?>"  <?=$hidegrad;?> schedid="<?=$schedrow['sched_id'];?>" class="paymentrow" <?php if($schedrow['amount']>$paidamountingradeverypayment){?> onclick="checkpayment(<?=$schedrow['sched_id'];?>,<?=$schedrow['amount']-$paidamountingradeverypayment;?>)" name="<?=$schedrow['amount'];}?>">
							<td class="radiocon">
								<?php
								if($schedrow['amount']>$paidamountingradeverypayment){?>
								<input type="checkbox" class="notother" value="<?=$schedrow['sched_id'];?><-><?=$schedrow['amount']-$paidamountingradeverypayment;?>" name="<?=$schedrow['amount']-$paidamountingradeverypayment;?>">
								<?php
								}
								?>
							</td>
							<td><?=$schedrow['payment_desc'];?></td><td><?=$schedrow['amount'];?></td>
							<td><?=number_format($schedrow['amount']-$paidamountingradeverypayment,2);?></td>
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

		$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='$status1' and (year_level like '%&I&%' or year_level like 'I&%' or year_level like '%&I') and sy='$firststatrow[sy]' and semester='$firststatrow[semester]'") or die(mysql_error());
		if(mysql_num_rows($sched)>0){
					$sched1=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='$status1' and (year_level like '%&I&%' or year_level like 'I&%' or year_level like '%&I') and sy='$firststatrow[sy]' and semester='$firststatrow[semester]'") or die(mysql_error());
					//check all new/trans student fee if paid
					$moneytobefortransfees=0;
					$moneypaidfortransfees=0;
					while($schedrow=mysql_fetch_array($sched1)) {

						$moneytobefortransfees=$moneytobefortransfees+$schedrow['amount'];
						$bal=mysql_query("select * from collection where sched_id='$schedrow[sched_id]' and stud_id='$stud_id' and remark='0'");
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
							 $bal=mysql_query("select * from collection where sched_id='$schedrow[sched_id]' and stud_id='$stud_id' and remark='0'");
							 $countbal=mysql_num_rows($bal);
							 $balancetopaybysched=0;
								while ($balrow=mysql_fetch_array($bal)) {
									$balance=$balance+$balrow['amount'];
									$balancetopaybysched=$balancetopaybysched+$balrow['amount'];
								}
								$hidenewfees="";
								if($schedrow['amount']==0 && $balancetopaybysched==0){
									$hidenewfees="style='display:none'";
								}
						$overalltotal=$overalltotal+$schedrow['amount'];
						?>
							<tr id="payment<?=$schedrow['sched_id'];?>" <?=$hidenewfees;?> schedid="<?=$schedrow['sched_id'];?>" class="paymentrow" 
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
										echo number_format($schedrow['amount']-$balancetopaybysched,2);
									}
								}
								?>
								</td>
							</tr>
						<?php
					}
			}
	}



	$advancepayment=mysql_query("select * from exceeded_money where stud_id='$stud_id' and to_sy='$sy' and to_semester='$semester' and action='Advance Payment' ");
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
		$displaybalance=0;
 	if($studrow['status']=="Cancelled"){
 		$displaybalance=0;
 	}else{
 		$displaybalance=$overalltotal-$balance;
 	}	
 	?>
<tr>
	<td  colspan="2" style="text-align:right;font-weight:bold">Total</td><td><?php echo number_format($overalltotal,2)?></td>
	<td id="baltopay" name="<?=$overalltotal-$balance;?>"><?=number_format($displaybalance,2);?></td>
	
</tr>
</table> 
<div  style="clear:both"></div>
<span class="checkall" onclick="checkall()">Check All</span> / 
<span  class="checkall"  onclick="uncheckall()">Uncheck All</span>
<?php

if($hidetable==10){?>
<button id="savepayment"></button>
<?php
}
?>

</div>

<div id="othertablecon" <?=$hidetable;?>>
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
						if($schedrow['payment_desc']=="Overload/Additional Subject"){?>
					<input type="number" value="1"  required="required" onchange="completionfee(<?=$schedrow['sched_id'];?>,'<?=$schedrow['amount'];?>',this)" onkeyup="completionfee(<?=$schedrow['sched_id'];?>,'<?=$schedrow['amount'];?>',this)" placeholder="Units" class="adding" style="display:none;top:-1px;padding:2px;position:absolute;left:-60px;width:50px">
						<?php
						}
						if($schedrow['payment_desc']=="Adding/Dropping/Changing"){?>
					<input type="number" value="1"  required="required" onchange="completionfee(<?=$schedrow['sched_id'];?>,'<?=$schedrow['amount'];?>',this)" onkeyup="completionfee(<?=$schedrow['sched_id'];?>,'<?=$schedrow['amount'];?>',this)" placeholder="Units" class="adding" style="display:none;top:-1px;padding:2px;position:absolute;left:-60px;width:50px">
						<?php
						}
						?>
					<?=$schedrow['amount'];?></td>
				</tr>
			<?php
		}
	  		?>
		
</table>


<!-- other misc table -->
<table border id="otherpaytable">
<?php
//for the other fees
	$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='othermisc' and year_level='' and sy='$sy' and semester='$semester'") or die(mysql_error());
			?>
		<tr>
			<td class="payheader"></td><td class="payheader">Other Miscellaneous fees</td><td class="payheader">Amount</td>
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

					</td><td onclick="checkpayment(<?=$schedrow['sched_id'];?>,<?=$schedrow['amount'];?>,'lksdaf')"><?=$schedrow['payment_desc'];?>
					
					</td><td  style="position:relative">
					
					<input type="number" value="<?=$schedrow['amount'];?>"  required="required" onchange="completionfee(<?=$schedrow['sched_id'];?>,'<?=$schedrow['amount'];?>',this,'jake')" onkeyup="completionfee(<?=$schedrow['sched_id'];?>,'<?=$schedrow['amount'];?>',this,'jake')" placeholder="Units" class="completionfee" style="display:none;top:-1px;padding:2px;position:absolute;left:6px;width:60px">										
						
					<span><?=$schedrow['amount'];?><span></td>
				</tr>
			<?php
		}
	  		?>
		
</table>


 </form>
</div>
</div>
<div id="receiptdummy" style="display:none"></div>
<div style="clear:both"></div>
<script>
 
 function checkchange(a){
	var cash=$('#cash').val();	
	var amounttopay=parseInt($('#amounttopay').val());
	if(cash<amounttopay){
		$('#amounttopay').val(cash)
	}
	var overallbalance=parseInt($('#baltopay').attr('name'));
	var otherfees=0
	$('input[type=checkbox][checked=checked][class=other]').each(function(){
		otherfees+=parseInt($(this).attr('name'));
 	});

	var totaltobepaid=overallbalance+otherfees;
 	var change=$('#change');
		if(a!='null'){
			if(a!='b'){
				$('#amounttopay').val(cash);
			}else{

				if(cash<amounttopay){
					$('#amounttopay').val(cash);			
		 		}	
			}
 		}
	var amounttopay=parseInt($('#amounttopay').val());
	if(overallbalance>0){
		if(totaltobepaid>amounttopay){
			change.val(cash-amounttopay);
			console.log("1");
		}else{
			console.log("2");
			change.val(cash-totaltobepaid)
		}
		
	}else{
		console.log("3");
		if(totaltobepaid>amounttopay){
			change.val(cash-amounttopay);
		}else{
			console.log("4");
			change.val(cash-totaltobepaid)
		}
	}

	// if(a=='null' && amounttopay!=""){
	// 	if(otherfees<amounttopay){
	// 		console.log("5");
	// 		// change.val(cash-amounttopay);
	// 	}


	// }
	if(amounttopay<1 || isNaN(amounttopay)){
		change.val(cash)
		console.log("6");
	}
}
function cancelstatus(id,sem,sy){
	$('#overlay, #modal').show();
	var con=$('#addcoursecon');
	con.html("<img src='img/loading2.gif' style='margin:6px 35px 6px 35px'>");

	$.ajax({
		type:'post',
		url:'student/cancelstatus.php',
		data:{'stud_id':id,'semester':sem,'sy':sy},
		success:function(data){
			con.html(data);

		},
		error:function(){
			connection();
			$('#overlay, #modal').hide();
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
		var subtractchange=$('#amounttopay').val();
		var change=$('#change').val();
		
		$("input[checked='checked'].notother").each(function(){
			var amount=$(this).val();
			paymentdata+="[endline]"+amount;
		});

		var whattopay=0;
		$('input[type=checkbox]:checked').each(function(){
			whattopay=1;
		});
		var bal=parseInt($('#baltopay').attr('name'));
		if(bal>0){
			whattopay=1;
		}
		if(whattopay!=0){

			 	if(parseInt($('#amounttopay').val())>0){
		  			$.ajax({
					type:'post',
					url:'student/savefullpayment.php',
					data:{'cash':cash,'topay':subtractchange,'scholar_id':scholar_id,'receipt':receipt,'stud_id':stud_id,'paymentdata':paymentdata,'sy':'<?=$sy;?>','semester':'<?=$semester;?>','change':change},
					success:function(data){
 						if(data=="existed"){
							alert("ERROR: Receipt number already existed");
						}else{
 		 				$('#receiptdummy').html(data);
		 				selectsearchstud($('#sfname').attr("name"));
						}
					},error:function(){
						connection();
					}
					});
		  		}else{
		  			$('#amounttopay').css("border","1px solid red").attr('placeholder','Invalid amount');
		  		}
		 }else{
		 	alert("Please select payment.");
		 }

			return false;	
}

function completionfee(a,b,c,d){
	var unit=$(c).val();
 	if(d=='jake'){
		var total=unit;
	}else{
		var total=unit*parseInt(b);
 	}
	
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

// function saverefund(a){
//  	$.ajax({
// 		type:'post',
// 		url:'student/saverefund.php',
// 		data:{'stud_id':'<?=$stud_id;?>','amount':'<?=$overalltotal-$balance;?>','sy':'<?=$sy;?>','semester':'<?=$semester;?>','check':a},
// 		success:function(da){
//   		},
// 		error:function(){
// 			saverefund();
// 		}
// 	});
// }
	
$(function() {
	$('#cash,#amounttopay').numeric();
if($('#savenewstatus:visible').length==1){
		$('#remainingbal span').html("0.00");
}else{
	$('#remainingbal span').html($('#baltopay').html());
}
 if($('#remainingbal span').html()=="0.00"){
	// saverefund("delete");
 	$('#remainingbal span').html("0.00");
	$('#remainingbal').css({'background':'#e3ffdb','border':'1px solid #4dcd3c'});
	$('#fullpaymenttable input').remove();
   	$('#fullpaymenttable tr').removeAttr('onclick').removeAttr('name');

}else if(parseInt($('#baltopay').attr('name'))<0){
   	$('#remainingbal').css({'background':'#c8e1fa','border':'1px solid blue'});
   	$('#remainingbal a').html("Refund");
   	// saverefund("no");

   	$('#fullpaymenttable input').remove();
   	$('#fullpaymenttable tr').removeAttr('onclick').removeAttr('name');

 
 } else if(parseInt($('#baltopay').attr('name'))>0){
		
		// saverefund("delete");
 
 	$('#remainingbal').css({'background':'#fedcd5','border':'1px solid #fb836b'});
 }
 
//show scholar option if savenew status botton is visible

if($('#savenewstatus:visible').length==1){
	$('#scholarcon span').hide();
	$('#scholarcon select').show();
}


});

function savenewstatus(){
	var stud_id=$('#sfname').attr("name");
	var course_id=$('#course_id').val();
	var year_level=$('#year_level').val();
	var sstatus=$('#sstatus').val();
	var scholar_id=$('#scholarcon select').val();
	var load=$('#studloader');
	load.show();
	$.ajax({
		type:'post',
		url:'student/savenewstatus.php',
		data: {'stud_id':stud_id,'course_id':course_id,'year_level':year_level,'status':sstatus,'scholar_id':scholar_id},
		success:function(data){
 			selectsearchstud(stud_id);
		},
		error:function(){
			load.hide();
			alert("Connection error,please try again.");
		}
	})
}



function checkall(){
var total=0;
	if($('#savenewstatus:visible').length!=1){
		$("#fullpaymenttable .paymentrow[name]").each(function(){
			var a=$(this).attr("schedid")
			var val=$('#payment'+a+" input").val();
			var clas=$('#payment'+a+" input").attr('class');
			var name=$('#payment'+a+" input").attr('name');
			$('#payment'+a+" td:first").html("<input type='checkbox' class='"+clas+"' checked='checked' value='"+val+"' name='"+name+"'>");
			total=total+parseInt($(this).attr("name"));
			})
		$('#total').val(total);
		checkchange('null');



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
	var cash=$('#amounttopay');
 	if($('#payment'+a+" input").attr("disabled")!="disabled"){

			if(c=="checked"){
			b.attr("checked",false);
			var origamount=b.attr('name');
			b.val(a+"<->"+origamount);	

			//this is the original, i forgot to is refresh for,b.attr("name",refresh);		
 				
 								if(misc=="other"){
 									b.attr("name",refresh);
 									$("#payment"+a+" span").show();
 									$("#payment"+a+" td:last input").val(refresh);
									var total2=0;
									$(".paymentrow input[type='checkbox'][checked='checked'][class='other']").each(function(){
										// total2=total2+parseInt($(this).attr('name'));
									});
									// $('#change').val(cash.val()-(parseInt($('#baltopay').attr('name'))+parseInt(total2)));
									if($('#change').val()<0){
										$('#change').val(0);
									}
								}

				}else{
					if(misc=="other"){
						$("#payment"+a+" span").hide();
						$("#payment"+a+" td:first").html("<input type='checkbox' checked='checked' value='"+a+"<->"+amount+"' name='"+name+"' refresh='"+refresh+"' class='other'>");
					}else{

						$("#payment"+a+" td:first").html("<input type='checkbox' class='notother' checked='checked' value='"+a+"<->"+amount+"' name='"+name+"' refresh='"+refresh+"'>");
					}
							if(cash.val()>parseInt($('#baltopay').attr('name'))){
								if(misc=="other"){
									var total2=0;
									$(".paymentrow input[type='checkbox'][checked='checked'][class='other']").each(function(){
										// total2=total2+parseInt($(this).attr('name'));
									});
									// $('#change').val(cash.val()-(parseInt($('#baltopay').attr('name'))+parseInt(total2)));
									if($('#change').val()<0){
										console.log("message");
											$('#change').val(0);
									}
								}

							}
					}

		//get overall check total
		var checkedvalue=0;
		$('input[type=checkbox][checked=checked]').each(function(){
			checkedvalue+=parseInt($(this).attr('name'));
		});
		total.val(checkedvalue);

		}else{
			alert("Save First the new status.");
		}
		checkchange("null");
		if(misc=="other"){
  			$("#payment"+a+" td:eq(2) input").toggle().val(1);
		}else{
			var restore=$("#payment"+a+" td:eq(0) input").attr("name");
 			$("#payment"+a+" td:eq(2) input").toggle().val(restore);
		}
		
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
 	var stud_number=$('#stud_number').val();
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
 			data:{'stud_number':stud_number,'semester':'<?=$semester;?>','sy':'<?=$sy;?>','fname':fname,'lname':lname,'scholar_id':scholar,'stud_id':stud_id,'course_id':course_id,'year_level':year_level,'status':status},
 			success:function(data){
   			selectsearchstud(stud_id);
 			},
 			error:function(){
 				loader.hide();
 				connection();
 			}
 		});
 	 	}
 	 	var ag=$('#remainingbal span').html();
 	 $('#remainingbal span').html(ag.split("-").join(""));
 </script>

 <?php
$checkifrefunded=mysql_query("select * from exceeded_money where from_sy='$studrow[sy]' and stud_id='$stud_id' and from_semester='$studrow[semester]'") or die(mysql_error());
$checkifrefundedrow=mysql_fetch_array($checkifrefunded);
if($checkifrefundedrow['action']=="Refunded"){
	?>
	<script>
 	$('#remainingbal a').html("Refunded");
 	
	$('#remainingbal').css({'background':'#e3ffdb','border':'1px solid #4dcd3c','font-size':'30px'});
	</script>
	<?php
}elseif($checkifrefundedrow['action']=="Advance Payment"){
?>
	<script type="text/javascript">
	$('#remainingbal a').html("Balance");
	$('#remainingbal span').html("0.00");
 	$('#remainingbal').css({'background':'#e3ffdb','border':'1px solid #4dcd3c','font-size':'30px'});
	</script>
<?php
}
 

?>
