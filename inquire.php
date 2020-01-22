<!DOCTYPE html>
<html>
<head>
	<title></title>
	<link rel="stylesheet" type="text/css" href="css/normalize.css">
	<link rel="stylesheet" type="text/css" href="css/inquire.css">
</head>
<body>
<?php
$stud_id=1;
$sy=2015;
include 'dbconfig.php';
include "rand.php";
?>
<div id="mainbody">
	<h3 id="mainheader">Computerized Cashiering System</h3>
	<div id="content">
		<div id="con-header">
			<span>Payments</span>
			<span>Payment History</span>
		</div>
		
		<div id="container">
			<ul id="sylist">
				<?php				
					$sy1=mysql_query("select * from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and remark!='Cancelled'  and stud_id='$stud_id' group by schedule_of_fees.sy asc") or die(mysql_error());
					$sy2=mysql_query("select * from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and remark!='Cancelled'  and stud_id='$stud_id' group by schedule_of_fees.sy asc") or die(mysql_error());
					while($syrow=mysql_fetch_array($sy2)){
						?>
						<li  onclick="showsem(<?=$syrow['col_id'];?>)">SY <?=$syrow['sy'];?></li>
						<?php
					}

					$syrow=mysql_fetch_array($sy1);

				?>
				 
			</ul>

			<div id="wrapper">

				<table>
						<tr>	
							<th colspan="4">First Semester</th>							
						</tr>
						
						<tr class="tablehead">	
							<th>DATE</th>
							<th>OR</th>
							<th>AMOUNT</td>
							<th>DESCRIPTION</th>
						</tr>
						<?php
							$paidmoneypersemester=0;
							$moneytobepaidpersemester=0;

							//get the student status during this school year and this semester;
							$studstatus=mysql_query("select * from student_status where stud_id='$stud_id' and sy='$syrow[sy]' and semester='I' and status!='Cancelled'");
							$studstatusrow=mysql_fetch_array($studstatus);

							

							//get the tuition and misc
							$tuimisc=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and  course_id='$studstatusrow[course_id]' and (payment_group='misc' or payment_group='sched') and sy='$studstatusrow[sy]' and semester='$studstatusrow[semester]' and (year_level like '$studstatusrow[year_level]&%' or year_level like '%&$studstatusrow[year_level]')");
							while ($tuimiscrow=mysql_fetch_array($tuimisc)) {
 								$moneytobepaidpersemester=$moneytobepaidpersemester+$tuimiscrow['amount'];
							}

							//get the rle if there's any
							$rle=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and  course_id='$studstatusrow[course_id]' and paymentlist.payment_group='rle' and sy='$studstatusrow[sy]' and semester='$studstatusrow[semester]' and (year_level like '$studstatusrow[year_level]&%' or year_level like '%&$studstatusrow[year_level]')");
							while ($rlerow=mysql_fetch_array($rle)) {
								$moneytobepaidpersemester=$moneytobepaidpersemester+$rlerow['amount'];
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
							///////////////////////////////////////////////////////
							$paymentfsem=mysql_query("select date,receipt_num, SUM(collection.amount) as amount,payment_desc from collection,schedule_of_fees,paymentlist,student where student.stud_id=collection.stud_id  and remark!='Cancelled' and student.stud_id='$stud_id' and  collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and schedule_of_fees.sy='$syrow[sy]' and paymentlist.payment_group!='misc' and schedule_of_fees.semester='I' group by paymentlist.payment_id,receipt_num order by receipt_num") or die(mysql_error());
							while ($paymentfsemrow=mysql_fetch_array($paymentfsem)) {
								$paidmoneypersemester=$paidmoneypersemester+$paymentfsemrow['amount'];
								?>
									<tr>
										<td><?=$paymentfsemrow['date'];?></td>
										<td><?=$paymentfsemrow['receipt_num'];?></td>
										<td><?=$paymentfsemrow['amount'];?></td>
										<td><?=$paymentfsemrow['payment_desc'];?></td>
									</tr>
								<?php
									

							}

							$paymentfsem=mysql_query("select date,receipt_num,collection.amount,payment_desc from collection,schedule_of_fees,paymentlist,student where student.stud_id=collection.stud_id and student.stud_id='$stud_id' and  collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and remark='0' and schedule_of_fees.sy='$syrow[sy]' and paymentlist.payment_group='grad' and collection.semester='I' order by receipt_num") or die(mysql_error());
							while ($paymentfsemrow=mysql_fetch_array($paymentfsem)) {
								$paidmoneypersemester=$paidmoneypersemester+$paymentfsemrow['amount'];

								?>
									<tr>
										<td><?=$paymentfsemrow['date'];?></td>
										<td><?=$paymentfsemrow['receipt_num'];?></td>
										<td><?=$paymentfsemrow['amount'];?></td>
										<td><?=$paymentfsemrow['payment_desc'];?></td>
									</tr>
								<?php

							}

							$paymentmisc=mysql_query("select date,receipt_num,collection.amount,payment_desc from collection,schedule_of_fees,paymentlist,student where    student.stud_id=collection.stud_id and student.stud_id='$stud_id' and  collection.sched_id=schedule_of_fees.sched_id and remark='0' and schedule_of_fees.payment_id=paymentlist.payment_id and schedule_of_fees.sy='$syrow[sy]' and paymentlist.payment_group='misc' and schedule_of_fees.semester='I' group by receipt_num") or die(mysql_error());
							while ($paymentmiscrow=mysql_fetch_array($paymentmisc)) {
									$sameor=mysql_query("select collection.amount from collection,schedule_of_fees,paymentlist where stud_id='$stud_id' and collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and  remark='0' and  paymentlist.payment_group='misc' and receipt_num='$paymentmiscrow[receipt_num]' and collection.col_id!='$paymentmiscrow[col_id]' ") or die(mysql_error());
									$totalmisc=0;
									while ($row=mysql_fetch_array($sameor)) {
										$totalmisc=$totalmisc+$row['amount'];
										$paidmoneypersemester=$paidmoneypersemester+$row['amount'];
									}
									if($totalmisc>0){
								?>
									<tr>
										<td><?=$paymentmiscrow['date'];?></td>
										<td><?=$paymentmiscrow['receipt_num'];?></td>
										<td><?=$totalmisc;?></td>
										<td>Miscellaneous</td>
									</tr>
								<?php
								}
							}	

							$getstatus=mysql_query("select * from student_status where stud_id='$stud_id' and sy='$syrow[sy]' and semester='I'");
							$statuscourse=mysql_fetch_array($getstatus);
							$scholar=mysql_query("select * from student_status,scholarship where student_status.scholar_id=scholarship.scholar_id  and course_id='$stud[course_id]' and sy='$syrow[sy]' and semester='I' and stud_id='$stud_id' and course_id='$statuscourse[course_id]'") or die(mysql_error());
							$scholarrow=mysql_fetch_array($scholar);
							if(mysql_num_rows($scholar)==1){
						?>
						<tr>
							<td colspan="2">Scholarship</td>
						
							<td><?=number_format($scholarrow['amount'],2);?></td>
							<td><?=$scholarrow['description'];?></td>
						</tr>
						<?php
						}
						$getrefundmount=mysql_query("select * from exceeded_money where stud_id='$stud_id' and to_sy='$syrow[sy]' and to_semester='I' and action='Advance Payment'");
						while($refudnamount=mysql_fetch_array($getrefundmount)){
							?>
							<tr>
								<td>- -</td>
								<td><?=$refudnamount['receipt_num'];?></td>
								<td><?=$refudnamount['amount'];?></td>
								<td>Advance Payment</td>
							</tr>
						<?php
							}

							//get advance payment

 						?>
						 
						 
						<tr>
							<td colspan="2" style="text-align:right">Amount Paid </td>
							<td colspan="2"><?=number_format($paidmoneypersemester,2);?> 
							<?php
							//check if refunded
							$checkrefund=mysql_query("select collection.remark from schedule_of_fees,collection where collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.sy='$syrow[sy]' and schedule_of_fees.semester='I' and collection.stud_id='$stud_id'");
							$checkrefundrow=mysql_fetch_array($checkrefund);
 							if($checkrefundrow['remark']=="Refunded"){
									$refunddisplay="Refunded";
									$removeline="text-decoration:none;cursor:auto";

							}else{
								$refunddisplay="Refund";
							}
							 if($studstatusrow['status']==""){
							 	if($_SESSION['type']=="admin") {

							 		//check if the money paid in cancelled status has been trans fered or etc.
							 		$checkcanceled=mysql_query("select * from exceeded_money where stud_id='$stud_id' and receipt_num='' and from_sy='$syrow[sy]' and from_semester='I' ") or die(mysql_error());
							 		$checkrowcancelled=mysql_fetch_array($checkcanceled);
 							 		if($checkrowcancelled['action']=="" && mysql_num_rows($checkcanceled)>0){
 
							 			$rand=rand();
									?>
									<select id="updatecancelstatus" onchange="cancelaction(this.value,'<?=$syrow['sy'];?>','I')">
										<option>Refund</option>
										<option>Advance Payment</option>
										<option value="trans">Transfer</option>
									</select>
									<button  onclick="updatecancelstatus(<?=$stud_id;?>,'<?=$syrow['sy'];?>','I','<?=$paidmoneypersemester;?>')">Save</button>

									<!-- <a style="color:blue;<?=$removeline;?>" id="refundbut" <?php if($checkrefundrow['remark']!="Refunded"){?> onclick="refundrereipt('<?=$syrow['sy'];?>','<?=$syrow['semester'];?>','<?=$stud_id;?>',this)" <?php }?>  title="Refund all the payments in this semester"><?=$refunddisplay;?></a> -->
									<?php
									
									}
								}elseif($checkrefundrow['remark']=="Refunded"){?>
									<a style="color:blue;<?=$removeline;?>" id="refundbut" title="Refund all the payments in this semester">Refunded</a>
									<?php
								}

								}
  							?>
							</td>
						</tr>

						<tr>
							<td colspan="2" style="text-align:right">Payment Amount</td>
							<td colspan="2"><?=number_format($moneytobepaidpersemester,2);?></td>
						</tr>
					</table>
					
					<?php
						
						//get the exceded money
						$getaction=mysql_query("select * from exceeded_money where stud_id='$stud_id' and from_sy='$syrow[sy]' and from_semester='I' and amount>0");
						while ($refundrow=mysql_fetch_array($getaction)) {
							$rand=rand();
							
								?>
									<div id="refundcontent<?=$rand;?>" style="background:#e7f2f7;border:1px solid gray;padding:5px;margin:5px 0 0 0 ">Money Exceeded: &#8369 <?=number_format(str_replace("-", "",$refundrow['amount']),2);?> 
											
									</div>
					
								<?php
						
						}
					?>
					<div id="refundcontent<?=$rand;?>" style="background:red;border:1px solid gray;padding:5px;margin:5px 0 0 0 ">Balance: &#8369 12323.00;
											
					</div>
					
			</div>	

		</div>

	</div>
</div>
</body>
</html>