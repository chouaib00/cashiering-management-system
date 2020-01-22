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
				<?php
					$paidmoneypersemester=0;
					$moneytobepaidpersemester=0;

					

					//get the student status during this school year and this semester;
					$studstatus=mysql_query("select * from student_status where stud_id='$stud_id' and sy='$sy' and semester='I'");
					$studstatusrow=mysql_fetch_array($studstatus);

					

					//get the tuition to add to the over all payment per semester
					$overallexceededmoney=0;
					$moneyexceededpersemesterintuition=0;
					$moneytobepaidpersemesterintuition=0;
					$orexeededarray=array();
					$tui=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and  course_id='$studstatusrow[course_id]' and payment_group='sched' and sy='$studstatusrow[sy]' and semester='$studstatusrow[semester]' and (year_level like '$studstatusrow[year_level]&%' or year_level like '%&$studstatusrow[year_level]')");
					while ($tuirow=mysql_fetch_array($tui)){
							$totaltui=0;
							$moneytobepaidpersemester=$moneytobepaidpersemester+$tuirow['amount'];
							$moneytobepaidpersemesterintuition=$moneytobepaidpersemesterintuition+$tuirow['amount'];
							$paid=mysql_query("select SUM(collection.amount) as amount,receipt_num,date from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and stud_id='$stud_id' and schedule_of_fees.payment_id='$tuirow[payment_id]' and schedule_of_fees.sy='$studstatusrow[sy]' and schedule_of_fees.semester='I'") or die(mysql_error());
							
							$paidrow=mysql_fetch_array($paid);
								$paidmoneypersemester=$paidmoneypersemester+$paidrow['amount'];	
								$totaltui=$totaltui+$paidrow['amount'];											
 							
							$moneyexceedeedpersemesterintuition=0;
 							if($tuirow['amount']<$totaltui && $paidrow['amount']!=""){
 								
								$overallexceededmoney=$overallexceededmoney+$totaltui-$tuirow['amount'];

								$moneyexceedeedpersemesterintuition=$moneyexceedeedpersemesterintuition+($totaltui-$tuirow['amount']);					
								array_push($orexeededarray,$paidrow['receipt_num'],$paidrow['date'],$moneyexceedeedpersemesterintuition);
							
							}	

 							

					}

					

					//get the misc to add to the overall payment per semester
					$moneytobepaidpersemesterinmisc=0;
					$moneypaidpersemesterinmisc=0;
					$moneyexceededpersemesterinmisc=0;
					
					$misc=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and  course_id='$studstatusrow[course_id]' and payment_group='misc' and sy='$studstatusrow[sy]' and semester='$studstatusrow[semester]' and (year_level like '$studstatusrow[year_level]&%' or year_level like '%&$studstatusrow[year_level]')");
					while ($miscrow=mysql_fetch_array($misc)){
							$moneytobepaidpersemesterinmisc=$moneytobepaidpersemesterinmisc+$miscrow['amount'];
							$moneytobepaidpersemester=$moneytobepaidpersemester+$miscrow['amount'];
							$paid=mysql_query("select SUM(collection.amount) as amount,date,receipt_num from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and stud_id='$stud_id' and schedule_of_fees.payment_id='$miscrow[payment_id]' and schedule_of_fees.sy='$studstatusrow[sy]' and schedule_of_fees.semester='I'") or die(mysql_error());
							$paidrow=mysql_fetch_array($paid);
							if($paidrow['amount']!=""){	
								$paidmoneypersemester=$paidmoneypersemester+$paidrow['amount'];
								$moneypaidpersemesterinmisc=$moneypaidpersemesterinmisc+$paidrow['amount'];
								
 							}
							

					}
					if($moneytobepaidpersemesterinmisc<$moneypaidpersemesterinmisc){
						$overallexceededmoney=$overallexceededmoney+($moneypaidpersemesterinmisc-$moneytobepaidpersemesterinmisc);
						array_push($orexeededarray,$paidrow['receipt_num'],$paidrow['date'],$moneypaidpersemesterinmisc-$moneytobepaidpersemesterinmisc);

					}

					

					//get the rle if there's any to add to the overall payment per semester
	  				  $rle=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and  course_id='$studstatusrow[course_id]' and paymentlist.payment_group='rle' and sy='$studstatusrow[sy]' and semester='$studstatusrow[semester]' and (year_level like '$studstatusrow[year_level]&%' or year_level like '%&$studstatusrow[year_level]')");
						if(mysql_num_rows($rle)==0){
						
							//if rle is not required but already paid,get the amount paid to added to overall exceeded money
							$sched=mysql_query("select SUM(collection.amount) as amount,receipt_num,date from collection,paymentlist,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='rle' and collection.stud_id='$stud_id' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester'") or die(mysql_error());
							$schedrow=mysql_fetch_array($sched);

							if($schedrow['amount']!=""){					
								$paidmoneypersemester=$paidmoneypersemester+$schedrow['amount'];
								$overallexceededmoney=$overallexceededmoney+$schedrow['amount'];
								array_push($orexeededarray,$schedrow['receipt_num'],$schedrow['date'],$schedrow['amount']);
							}

						}

						

						$total=0;
						while ($rlerow=mysql_fetch_array($rle)){
							$moneytobepaidpersemester=$moneytobepaidpersemester+$rlerow['amount'];
							//get the amount if how much paid in the rle
							
							$paid=mysql_query("select SUM(collection.amount) as amount,date,receipt_num from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and stud_id='$stud_id' and schedule_of_fees.payment_id='$rlerow[payment_id]' and schedule_of_fees.sy='$studstatusrow[sy]' and schedule_of_fees.semester='I' order by col_id desc") or die(mysql_error());
								$paidrow=mysql_fetch_array($paid);
									$paidmoneypersemester=$paidmoneypersemester+$paidrow['amount'];
									$total=$total+$paidrow['amount'];
								

								if($total>$rlerow['amount']){
									array_push($orexeededarray,$paidrow['receipt_num'],$paidrow['date'],$total-$rlerow['amount']);
									$overallexceededmoney=$overallexceededmoney+($total-$rlerow['amount']);
								}
						}

						

					///get the trans/new student fees
					$newfeesrequire="";
					if($studstatusrow['status']=="trans" || $studstatusrow['status']=="new"){
						$newfees=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and paymentlist.payment_group='new' and sy='$studstatusrow[sy]' and semester='$studstatusrow[semester]'");
						$newfeesrequire="yes";
					}else{
						$newfees=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and paymentlist.payment_group='new' and sy='$studstatusrow[sy]' and semester='$studstatusrow[semester]'");
						$newfeesrequire="no";
					}
						while ($newfeesrow=mysql_fetch_array($newfees)) {
								$paid=mysql_query("select * from collection where stud_id='$stud_id'  and sched_id='$newfeesrow[sched_id]' and sy='$sy' and semester='I'");
								while ($paidrow=mysql_fetch_array($paid)) {
									
									$paidmoneypersemester=$paidmoneypersemester+$paidrow['amount'];
								}

								if($newfeesrequire=="yes"){
									$moneytobepaidpersemester=$moneytobepaidpersemester+$newfeesrow['amount'];
								}
						}
					
					
					///get the gradution fees fees
					$gradfeesrequire="";
					if($studstatusrow['status']=="grad"){
						$grad=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and paymentlist.payment_group='grad' and sy='$studstatusrow[sy]' and semester='0'");
						$gradfeesrequire="yes";
					}else{
						$grad=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and paymentlist.payment_group='grad' and sy='$studstatusrow[sy]' and semester='0'");
						$gradfeesrequire="no";
					}	
 						while ($gradrow=mysql_fetch_array($grad)) {
							$paid=mysql_query("select * from collection where stud_id='$stud_id'  and sched_id='$gradrow[sched_id]' and sy='$sy' and semester='I'");
							while ($paidrow=mysql_fetch_array($paid)) {						
								
								$paidmoneypersemester=$paidmoneypersemester+$paidrow['amount'];
							}
							if($gradfeesrequire=="yes"){
								$moneytobepaidpersemester=$moneytobepaidpersemester+$gradrow['amount'];
							}
						}

					$moneyexceededpersemesterinmisc=$moneypaidpersemesterinmisc-$moneytobepaidpersemesterinmisc;
					if($moneyexceededpersemesterinmisc<=0){
						$moneyexceededpersemesterinmisc=0;
					} 
					$ortodisplay="";
					$datetodisplay="";
					$overallmoneydisplayed=0;
					$paymentfsem=mysql_query("select * from schedule_of_fees,paymentlist where  schedule_of_fees.payment_id=paymentlist.payment_id and schedule_of_fees.sy='$sy' and course_id='$studstatusrow[course_id]' and  (paymentlist.payment_group='sched' or paymentlist.payment_group='rle') and (year_level like '$studstatusrow[year_level]&%' or year_level like '%&$studstatusrow[year_level]') and schedule_of_fees.semester='I'") or die(mysql_error());
					while ($paymentfsemrow=mysql_fetch_array($paymentfsem)){
							$totalpaidpersched=0;	
						// check if how much is paid
 						$totalpaid=0;
						$totalpaid2=0;
						$displayamount=0;			
						$moneypaid=mysql_query("select SUM(collection.amount) as amount ,collection.receipt_num,date from collection, schedule_of_fees,paymentlist where collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_id='$paymentfsemrow[payment_id]' and collection.stud_id='$stud_id' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='I' group by receipt_num") or die(mysql_error());
						while ($paidrow=mysql_fetch_array($moneypaid)){						
								
										if($paidrow['amount']>$paymentfsemrow['amount']){
											$displayamount=$paymentfsemrow['amount'];	
										}else{
											$displayamount=$paidrow['amount'];
										}
										$totalpaid=$totalpaid+$displayamount;
										$totalpaidpersched=$totalpaidpersched+$displayamount;
										$rand=random();
												
												
										echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
										echo "<td><input type='checkbox' value='I<->$paidrow[date]<->$paidrow[receipt_num]<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
										echo "<td>$paidrow[date]</td>";
										echo "<td>$paidrow[receipt_num]</td>";
										echo "<td>".number_format($displayamount,2)."</td>";
										echo "<td>$paymentfsemrow[payment_desc]</td>";
										echo "<tr>";
								



						}////

						//add money to sched that is not fully paid
								$rand=random();
								$start=0;
								$or="";
								$date="";
								$len=count($orexeededarray)/3;
									$displayamount=0;									
									
									$start=0;
									$or="";
									$date="";
									$len=count($orexeededarray)/3;

									while ($len>$start) {
										if($totalpaidpersched<$paymentfsemrow['amount']){

												$remaining=$paymentfsemrow['amount']-$totalpaidpersched;
 												$or=$orexeededarray[0];
												$date=$orexeededarray[1];
												$amount=$orexeededarray[2]-$remaining;
												
												if($amount>0){
													$displayamount=$remaining;													
													$orexeededarray[2]=$orexeededarray[2]-$displayamount;
													$len=2;
													$start=1;
  												}else{
 													$displayamount=$orexeededarray[2];
													array_splice($orexeededarray, 0,3);
													if(count($orexeededarray)>0){
														$len=2;
													$start=1;
													}else{
														$len=1;
														$start=2;
													}
													
												}
												$rand=random();
												$totalpaidpersched=$totalpaidpersched+$displayamount;
												echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
												echo "<td><input type='checkbox' value='I<->$date<->$or<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
												echo "<td>$date</td>";
												echo "<td>$or</td>";
												echo "<td>".number_format($displayamount,2)."</td>";
												echo "<td>$paymentfsemrow[payment_desc]</td>";
												echo "<tr>";											
										}else{
											$len=1;
											$start=2;
										}
										
									}

						if(mysql_num_rows($moneypaid)==0){				
							
								$rand=random();
								$start=0;
								$or="";
								$date="";
								$len=count($orexeededarray)/3;
									$displayamount=0;									
									$rand=random();
									$start=0;
									$or="";
									$date="";
									$len=count($orexeededarray)/3;
									while ($len>$start) {
										
										if($totalpaidpersched<$paymentfsemrow['amount']){

												$remaining=$paymentfsemrow['amount']-$totalpaidpersched;
												$or=$orexeededarray[0];
												$date=$orexeededarray[1];
												$amount=$orexeededarray[2]-$remaining;
												
												if($amount>0){
													$displayamount=$remaining;													
													$orexeededarray[2]=$orexeededarray[2]-$displayamount;
													$len=2;
													$start=1;
												}else{
													$displayamount=$orexeededarray[2];
													array_splice($orexeededarray, 0,3);
													$len=1;
													$start=2;
													
												}
												$totalpaidpersched=$totalpaidpersched+$displayamount;
												echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
												echo "<td><input type='checkbox' value='I<->$date<->$or<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
												echo "<td>$date</td>";
												echo "<td>$or</td>";
												echo "<td>".number_format($displayamount,2)."</td>";
												echo "<td>$paymentfsemrow[payment_desc]</td>";
												echo "<tr>";											
										}else{
											$len=1;
											$start=2;
										}
										
									}
									
							
								
							
						}

						
					}
 					



					//get the paid amount in misc

					$displayamount=0;
					$overallmiscdisplayed=0;
					$overallmiscdisplayed2=0;
					$paymentfsem=mysql_query("select date,payment_desc,collection.amount,receipt_num from schedule_of_fees,paymentlist,collection where stud_id='$stud_id'  and collection.sched_id=schedule_of_fees.sched_id and  schedule_of_fees.payment_id=paymentlist.payment_id and schedule_of_fees.sy='$sy' and  paymentlist.payment_group='misc' and schedule_of_fees.semester='I' group by receipt_num order by receipt_num asc") or die(mysql_error());
					while ($paymentfsemrow=mysql_fetch_array($paymentfsem)){
							$thesamemiscoramount=0;

							$getthesamemiscor=mysql_query("select collection.amount from collection,schedule_of_fees,paymentlist where  stud_id='$stud_id' and  collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='misc' and receipt_num='$paymentfsemrow[receipt_num]'") or die(mysql_error());
							while ($thesamemiscorrow=mysql_fetch_array($getthesamemiscor)) {
								$thesamemiscoramount=$thesamemiscoramount+$thesamemiscorrow['amount'];
							}

							$overallmiscdisplayed=$overallmiscdisplayed+$thesamemiscoramount;
							if($overallmiscdisplayed<$moneytobepaidpersemesterinmisc){
								$displayamount=$thesamemiscoramount;
							}else{
								$displayamount=$thesamemiscoramount-($overallmiscdisplayed-$moneytobepaidpersemesterinmisc);
							}
							$$overallmiscdisplayed=$$overallmiscdisplayed+$displayamount;
							$overallmiscdisplayed2=$displayamount;
							$rand=random();
							if($displayamount>0){
							echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
								echo "<td><input type='checkbox' value='I<->$paymentfsemrow[date]<->$paymentfsemrow[receipt_num]<->$displayamount<->Miscellaneous'></td>";
								echo "<td>$paymentfsemrow[date]</td>";
								echo "<td>$paymentfsemrow[receipt_num]</td>";
								echo "<td>".number_format($displayamount,2)."</td>";
								echo "<td>Miscellaneous</td>";
							echo "</tr>";
							}
							
					
					}//

					if($moneytobepaidpersemesterinmisc>$overallmiscdisplayed2){
						$start=0;
						$or="";
						$date="";
						$len=count($orexeededarray)/3;
 						while ($len>$start) {
							
							if($overallmiscdisplayed2<$moneytobepaidpersemesterinmisc){
									$remaining=$moneytobepaidpersemesterinmisc-$overallmiscdisplayed2;
									$or=$orexeededarray[0];
									$date=$orexeededarray[1];
									$amount=$orexeededarray[2]-$remaining;
									
									if($amount>0){

										$displayamount=$remaining;
										$orexeededarray[2]=$orexeededarray[2]-$displayamount;
										$len=2;
										$start=1;
									}else{

										$displayamount=$orexeededarray[2];
										array_splice($orexeededarray, 0,3);
										if(count($orexeededarray)>0){
											$len=2;
											$start=1;
										}else{
											$len=1;
											$start=2;
										}
										
										
									}
									$overallmiscdisplayed2=$overallmiscdisplayed2+$displayamount;
									$rand=random();
									echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
									echo "<td><input type='checkbox' value='I<->$date<->$or<->$displayamount<->Miscellaneous'></td>";
									echo "<td>$date</td>";
									echo "<td>$or</td>";
									echo "<td>".number_format($displayamount,2)."</td>";
									echo "<td>Miscellaneous</td>";
									echo "<tr>";											
							}else{
								$len=1;
								$start=2;
							}
							
						}
					}


					//get the paid amount in new.trans fees
					
					$overallnewfeesdisplayed=0;
					$newfeesrequire="no";
					if($studstatusrow['status']=="trans" || $studstatusrow['status']=="new"){
							
							$paymentfsem=mysql_query("select * from schedule_of_fees,paymentlist where  paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='new' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='I' group by schedule_of_fees.payment_id");
							 while ($paymentfsemrow=mysql_fetch_array($paymentfsem)){
									$displayamount=0;
									$totalpaid=0;
									$getthesamemiscor=mysql_query("select date,payment_desc,collection.amount,schedule_of_fees.payment_id,receipt_num from collection,schedule_of_fees,paymentlist where collection.stud_id='$stud_id' and collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id='$paymentfsemrow[payment_id]' and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='new' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='I' group by receipt_num order by receipt_num") or die(mysql_error());
									while ($thesameor=mysql_fetch_array($getthesamemiscor)){
											$displayamount=0;
											$thesamenewor=0;											
											$getthesamemiscor1=mysql_query("select collection.amount from collection,schedule_of_fees,paymentlist where  stud_id='$stud_id' and schedule_of_fees.payment_id='$paymentfsemrow[payment_id]' and  collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='new' and receipt_num='$thesameor[receipt_num]'") or die(mysql_error());
											while ($thesameor1=mysql_fetch_array($getthesamemiscor1)){
												$thesamenewor=$thesamenewor+$thesameor1['amount'];
											}
											$displayamount=$thesamenewor;
											$totalpaid=$totalpaid+$thesamenewor;
											$rand=random();
											echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
											echo "<td><input type='checkbox' value='I<->$thesameor[date]<->$thesameor[receipt_num]<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
											echo "<td>$thesameor[date]</td>";
											echo "<td>$thesameor[receipt_num]</td>";
											echo "<td>".number_format($displayamount,2)."</td>";
											echo "<td>$thesameor[payment_desc]</td>";
											echo "</tr>";

												
									}
									//heewr
									if($paymentfsemrow['amount']>$totalpaid){

										$start=0;
										$or="";
										$date="";
										$len=count($orexeededarray)/3;
										while ($len>$start) {
											
											if($totalpaid<$paymentfsemrow['amount']){
													$remaining=$paymentfsemrow['amount']-$totalpaid;
													$or=$orexeededarray[0];
													$date=$orexeededarray[1];
													$amount=$orexeededarray[2]-$remaining;
													
													if($amount>0){

														$displayamount=$remaining;
														$orexeededarray[2]=$orexeededarray[2]-$displayamount;
														$len=2;
														$start=1;
													}else{

														$displayamount=$orexeededarray[2];
														array_splice($orexeededarray, 0,3);
														if(count($orexeededarray)>0){
															$len=2;
															$start=1;
														}else{
															$len=1;
															$start=2;
														}
														
														
													}
													$rand=rand2();
													$totalpaid=$totalpaid+$displayamount;
													echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
													echo "<td><input type='checkbox' value='I<->$date<->$or<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
													echo "<td>$date</td>";
													echo "<td>$or</td>";
													echo "<td>".number_format($displayamount,2)."</td>";
													echo "<td>$paymentfsemrow[payment_desc]</td>";
													echo "<tr>";											
											}else{
												$len=1;
												$start=2;
											}
											
										}
									}
									
									
							
							}//
					}

					/////graduation fees
					$overallnewfeesdisplayed=0;
					$newfeesrequire="no";
					if($studstatusrow['status']=="grad"){
							
							$paymentfsem=mysql_query("select * from schedule_of_fees,paymentlist where  paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='grad' and schedule_of_fees.sy='$sy'  group by schedule_of_fees.payment_id");
							 while ($paymentfsemrow=mysql_fetch_array($paymentfsem)){
									
									$displayamount=0;
									$totalpaid=0;
									$getthesamemiscor=mysql_query("select date,payment_desc,collection.amount,schedule_of_fees.payment_id,receipt_num from collection,schedule_of_fees,paymentlist where collection.stud_id='$stud_id' and collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id='$paymentfsemrow[payment_id]' and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='grad' and schedule_of_fees.sy='$sy' group by receipt_num order by receipt_num") or die(mysql_error());
									while ($thesameor=mysql_fetch_array($getthesamemiscor)){
										if($totalpaid<$paymentfsemrow['amount']){
											$displayamount=0;
											$thesamenewor=0;											
											$getthesamemiscor1=mysql_query("select collection.amount from collection,schedule_of_fees,paymentlist where  stud_id='$stud_id' and schedule_of_fees.payment_id='$paymentfsemrow[payment_id]' and  collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='grad' and receipt_num='$thesameor[receipt_num]'") or die(mysql_error());
											while ($thesameor1=mysql_fetch_array($getthesamemiscor1)){
												$thesamenewor=$thesamenewor+$thesameor1['amount'];
											}
											$displayamount=$thesamenewor;
											$totalpaid=$totalpaid+$thesamenewor;
											$rand=random();
											echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
											echo "<td><input type='checkbox' value='I<->$thesameor[date]<->$thesameor[receipt_num]<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
											echo "<td>$thesameor[date]</td>";
											echo "<td>$thesameor[receipt_num]</td>";
											echo "<td>".number_format($displayamount,2)."g</td>";
											echo "<td>$thesameor[payment_desc]</td>";
											echo "</tr>";
										}
												
									}
									//heewr
									if($paymentfsemrow['amount']>$totalpaid){

										$start=0;
										$or="";
										$date="";
										$len=count($orexeededarray)/3;
										while ($len>$start) {
											
											if($totalpaid<$paymentfsemrow['amount']){
													$remaining=$paymentfsemrow['amount']-$totalpaid;
													$or=$orexeededarray[0];
													$date=$orexeededarray[1];
													$amount=$orexeededarray[2]-$remaining;
													
													if($amount>0){

														$displayamount=$remaining;
														$orexeededarray[2]=$orexeededarray[2]-$displayamount;
														$len=2;
														$start=1;
													}else{

														$displayamount=$orexeededarray[2];
														array_splice($orexeededarray, 0,3);
														if(count($orexeededarray)>0){
															$len=2;
															$start=1;
														}else{
															$len=1;
															$start=2;
														}
														
														
													}
													$rand=random();
													$totalpaid=$totalpaid+$displayamount;
													echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
													echo "<td><input type='checkbox' value='I<->$date<->$or<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
													echo "<td>$date</td>";
													echo "<td>$or</td>";
													echo "<td>".number_format($displayamount,2)."</td>";
													echo "<td>$paymentfsemrow[payment_desc]</td>";
													echo "<tr>";											
											}else{
												$len=1;
												$start=2;
											}
											
										}
									}
									
									
							
							}//
					}

					//get not required payment


					$getpayment=mysql_query("select collection.amount,payment_desc,date,receipt_num from collection,schedule_of_fees,paymentlist where collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and (payment_group='other' or payment_group='othermisc') and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='I'") or die(mysql_error()) ;
					while ($unrequired=mysql_fetch_array($getpayment)) {
						$rand=random();
						 
					echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
					echo "<td><input type='checkbox' value='I<->$date<->$unrequired[receipt_num]<->$unrequired[amount]<->$unrequired[payment_desc]'></td>";
					echo "<td>$unrequired[date]</td>";
					echo "<td>$unrequired[receipt_num]</td>";
					echo "<td>".number_format($unrequired['amount'],2)."</td>";
					echo "<td>$unrequired[payment_desc]</td>";
					echo "<tr>";
					}

					?>
			</table>


<!-- /////////////  start of second semester   ///////////////////////////////////////////////////////////////////// -->
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
					$paidmoneypersemester=0;
					$moneytobepaidpersemester=0;
 

					//get the student status during this school year and this semester;
					$studstatus=mysql_query("select * from student_status where stud_id='$stud_id' and sy='$sy' and semester='II'");
					$studstatusrow=mysql_fetch_array($studstatus);

					

					//get the tuition to add to the over all payment per semester
					$overallexceededmoney=0;
					$moneyexceededpersemesterintuition=0;
					$moneytobepaidpersemesterintuition=0;
					$orexeededarray=array();
					$tui=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and  course_id='$studstatusrow[course_id]' and payment_group='sched' and sy='$studstatusrow[sy]' and semester='$studstatusrow[semester]' and (year_level like '$studstatusrow[year_level]&%' or year_level like '%&$studstatusrow[year_level]')");
					while ($tuirow=mysql_fetch_array($tui)){
							$totaltui=0;
							$moneytobepaidpersemester=$moneytobepaidpersemester+$tuirow['amount'];
							$moneytobepaidpersemesterintuition=$moneytobepaidpersemesterintuition+$tuirow['amount'];
							$paid=mysql_query("select SUM(collection.amount) as amount,receipt_num,date from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and stud_id='$stud_id' and schedule_of_fees.payment_id='$tuirow[payment_id]' and schedule_of_fees.sy='$studstatusrow[sy]' and schedule_of_fees.semester='II'") or die(mysql_error());
							
							$paidrow=mysql_fetch_array($paid);
								$paidmoneypersemester=$paidmoneypersemester+$paidrow['amount'];	
								$totaltui=$totaltui+$paidrow['amount'];											
 							
							$moneyexceedeedpersemesterintuition=0;
 							if($tuirow['amount']<$totaltui && $paidrow['amount']!=""){
 								
								$overallexceededmoney=$overallexceededmoney+$totaltui-$tuirow['amount'];

								$moneyexceedeedpersemesterintuition=$moneyexceedeedpersemesterintuition+($totaltui-$tuirow['amount']);					
								array_push($orexeededarray,$paidrow['receipt_num'],$paidrow['date'],$moneyexceedeedpersemesterintuition);
							
							}	

 							

					}

					

					//get the misc to add to the overall payment per semester
					$moneytobepaidpersemesterinmisc=0;
					$moneypaidpersemesterinmisc=0;
					$moneyexceededpersemesterinmisc=0;
					
					$misc=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and  course_id='$studstatusrow[course_id]' and payment_group='misc' and sy='$studstatusrow[sy]' and semester='$studstatusrow[semester]' and (year_level like '$studstatusrow[year_level]&%' or year_level like '%&$studstatusrow[year_level]')");
					while ($miscrow=mysql_fetch_array($misc)){
							$moneytobepaidpersemesterinmisc=$moneytobepaidpersemesterinmisc+$miscrow['amount'];
							$moneytobepaidpersemester=$moneytobepaidpersemester+$miscrow['amount'];
							$paid=mysql_query("select SUM(collection.amount) as amount,date,receipt_num from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and stud_id='$stud_id' and schedule_of_fees.payment_id='$miscrow[payment_id]' and schedule_of_fees.sy='$studstatusrow[sy]' and schedule_of_fees.semester='II'") or die(mysql_error());
							$paidrow=mysql_fetch_array($paid);
							if($paidrow['amount']!=""){	
								$paidmoneypersemester=$paidmoneypersemester+$paidrow['amount'];
								$moneypaidpersemesterinmisc=$moneypaidpersemesterinmisc+$paidrow['amount'];
								
 							}
							

					}
					if($moneytobepaidpersemesterinmisc<$moneypaidpersemesterinmisc){
						$overallexceededmoney=$overallexceededmoney+($moneypaidpersemesterinmisc-$moneytobepaidpersemesterinmisc);
						array_push($orexeededarray,$paidrow['receipt_num'],$paidrow['date'],$moneypaidpersemesterinmisc-$moneytobepaidpersemesterinmisc);

					}

					

					//get the rle if there's any to add to the overall payment per semester
	  				  $rle=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and  course_id='$studstatusrow[course_id]' and paymentlist.payment_group='rle' and sy='$studstatusrow[sy]' and semester='$studstatusrow[semester]' and (year_level like '$studstatusrow[year_level]&%' or year_level like '%&$studstatusrow[year_level]')");
						if(mysql_num_rows($rle)==0){
						
							//if rle is not required but already paid,get the amount paid to added to overall exceeded money
							$sched=mysql_query("select SUM(collection.amount) as amount,receipt_num,date from collection,paymentlist,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='rle' and collection.stud_id='$stud_id' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester'") or die(mysql_error());
							$schedrow=mysql_fetch_array($sched);

							if($schedrow['amount']!=""){					
								$paidmoneypersemester=$paidmoneypersemester+$schedrow['amount'];
								$overallexceededmoney=$overallexceededmoney+$schedrow['amount'];
								array_push($orexeededarray,$schedrow['receipt_num'],$schedrow['date'],$schedrow['amount']);
							}

						}

						

						$total=0;
						while ($rlerow=mysql_fetch_array($rle)){
							$moneytobepaidpersemester=$moneytobepaidpersemester+$rlerow['amount'];
							//get the amount if how much paid in the rle
							
							$paid=mysql_query("select SUM(collection.amount) as amount,date,receipt_num from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and stud_id='$stud_id' and schedule_of_fees.payment_id='$rlerow[payment_id]' and schedule_of_fees.sy='$studstatusrow[sy]' and schedule_of_fees.semester='II' order by col_id desc") or die(mysql_error());
								$paidrow=mysql_fetch_array($paid);
									$paidmoneypersemester=$paidmoneypersemester+$paidrow['amount'];
									$total=$total+$paidrow['amount'];
								

								if($total>$rlerow['amount']){
									array_push($orexeededarray,$paidrow['receipt_num'],$paidrow['date'],$total-$rlerow['amount']);
									$overallexceededmoney=$overallexceededmoney+($total-$rlerow['amount']);
								}
						}

						

					///get the trans/new student fees
					$newfeesrequire="";
					if($studstatusrow['status']=="trans" || $studstatusrow['status']=="new"){
						$newfees=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and paymentlist.payment_group='new' and sy='$studstatusrow[sy]' and semester='$studstatusrow[semester]'");
						$newfeesrequire="yes";
					}else{
						$newfees=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and paymentlist.payment_group='new' and sy='$studstatusrow[sy]' and semester='$studstatusrow[semester]'");
						$newfeesrequire="no";
					}
						while ($newfeesrow=mysql_fetch_array($newfees)) {
								$paid=mysql_query("select * from collection where stud_id='$stud_id'  and sched_id='$newfeesrow[sched_id]' and sy='$sy' and semester='II'");
								while ($paidrow=mysql_fetch_array($paid)) {
									
									$paidmoneypersemester=$paidmoneypersemester+$paidrow['amount'];
								}

								if($newfeesrequire=="yes"){
									$moneytobepaidpersemester=$moneytobepaidpersemester+$newfeesrow['amount'];
								}
						}
					
					
					///get the gradution fees fees
					$gradfeesrequire="";
					if($studstatusrow['status']=="grad"){
						$grad=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and paymentlist.payment_group='grad' and sy='$studstatusrow[sy]' and semester='0'");
						$gradfeesrequire="yes";
					}else{
						$grad=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and paymentlist.payment_group='grad' and sy='$studstatusrow[sy]' and semester='0'");
						$gradfeesrequire="no";
					}	
 						while ($gradrow=mysql_fetch_array($grad)) {
							$paid=mysql_query("select * from collection where stud_id='$stud_id'  and sched_id='$gradrow[sched_id]' and sy='$sy' and semester='II'");
							while ($paidrow=mysql_fetch_array($paid)) {						
								
								$paidmoneypersemester=$paidmoneypersemester+$paidrow['amount'];
							}
							if($gradfeesrequire=="yes"){
								$moneytobepaidpersemester=$moneytobepaidpersemester+$gradrow['amount'];
							}
						}

					$moneyexceededpersemesterinmisc=$moneypaidpersemesterinmisc-$moneytobepaidpersemesterinmisc;
					if($moneyexceededpersemesterinmisc<=0){
						$moneyexceededpersemesterinmisc=0;
					} 
					$ortodisplay="";
					$datetodisplay="";
					$overallmoneydisplayed=0;
					$paymentfsem=mysql_query("select * from schedule_of_fees,paymentlist where  schedule_of_fees.payment_id=paymentlist.payment_id and schedule_of_fees.sy='$sy' and course_id='$studstatusrow[course_id]' and  (paymentlist.payment_group='sched' or paymentlist.payment_group='rle') and (year_level like '$studstatusrow[year_level]&%' or year_level like '%&$studstatusrow[year_level]') and schedule_of_fees.semester='II'") or die(mysql_error());
					while ($paymentfsemrow=mysql_fetch_array($paymentfsem)){
							$totalpaidpersched=0;	
						// check if how much is paid
 						$totalpaid=0;
						$totalpaid2=0;
						$displayamount=0;			
						$moneypaid=mysql_query("select SUM(collection.amount) as amount ,collection.receipt_num,date from collection, schedule_of_fees,paymentlist where collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_id='$paymentfsemrow[payment_id]' and collection.stud_id='$stud_id' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='II' group by receipt_num") or die(mysql_error());
						while ($paidrow=mysql_fetch_array($moneypaid)){						
								
										if($paidrow['amount']>$paymentfsemrow['amount']){
											$displayamount=$paymentfsemrow['amount'];	
										}else{
											$displayamount=$paidrow['amount'];
										}
										$totalpaid=$totalpaid+$displayamount;
										$totalpaidpersched=$totalpaidpersched+$displayamount;
										$rand=random();
												
												
										echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
										echo "<td><input type='checkbox' value='II<->$paidrow[date]<->$paidrow[receipt_num]<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
										echo "<td>$paidrow[date] $orexeededarray[2]</td>";
										echo "<td>$paidrow[receipt_num]</td>";
										echo "<td>".number_format($displayamount,2)."</td>";
										echo "<td>$paymentfsemrow[payment_desc]</td>";
										echo "<tr>";
								



						}////

						//add money to sched that is not fully paid
								$rand=random();
								$start=0;
								$or="";
								$date="";
								$len=count($orexeededarray)/3;
									$displayamount=0;									
									
									$start=0;
									$or="";
									$date="";
									$len=count($orexeededarray)/3;

									while ($len>$start) {
										if($totalpaidpersched<$paymentfsemrow['amount']){

												$remaining=$paymentfsemrow['amount']-$totalpaidpersched;
												$or=$orexeededarray[0];
												$date=$orexeededarray[1];
												$amount=$orexeededarray[2]-$remaining;
												
												if($amount>0){
													$displayamount=$remaining;													
													$orexeededarray[2]=$orexeededarray[2]-$displayamount;
													$len=2;
													$start=1;
  												}else{
 													$displayamount=$orexeededarray[2];
													array_splice($orexeededarray, 0,3);
													if(count($orexeededarray)>0){
														$len=2;
													$start=1;
													}else{
														$len=1;
														$start=2;
													}
													
												}
												$rand=random();
												$totalpaidpersched=$totalpaidpersched+$displayamount;
												echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
												echo "<td><input type='checkbox' value='II<->$date<->$or<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
												echo "<td>$date</td>";
												echo "<td>$or</td>";
												echo "<td>".number_format($displayamount,2)."</td>";
												echo "<td>$paymentfsemrow[payment_desc]</td>";
												echo "<tr>";											
										}else{
											$len=1;
											$start=2;
										}
										
									}

						if(mysql_num_rows($moneypaid)==0){				
							
								$rand=random();
								$start=0;
								$or="";
								$date="";
								$len=count($orexeededarray)/3;
									$displayamount=0;									
									$rand=random();
									$start=0;
									$or="";
									$date="";
									$len=count($orexeededarray)/3;
									while ($len>$start) {
										
										if($totalpaidpersched<$paymentfsemrow['amount']){

												$remaining=$paymentfsemrow['amount']-$totalpaidpersched;
												$or=$orexeededarray[0];
												$date=$orexeededarray[1];
												$amount=$orexeededarray[2]-$remaining;
												
												if($amount>0){
													$displayamount=$remaining;													
													$orexeededarray[2]=$orexeededarray[2]-$displayamount;
													$len=2;
													$start=1;
												}else{
													$displayamount=$orexeededarray[2];
													array_splice($orexeededarray, 0,3);
													$len=1;
													$start=2;
													
												}
												$totalpaidpersched=$totalpaidpersched+$displayamount;
												echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
												echo "<td><input type='checkbox' value='II<->$date<->$or<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
												echo "<td>$date</td>";
												echo "<td>$or</td>";
												echo "<td>".number_format($displayamount,2)."</td>";
												echo "<td>$paymentfsemrow[payment_desc]</td>";
												echo "<tr>";											
										}else{
											$len=1;
											$start=2;
										}
										
									}
									
							
								
							
						}

						
					}
 					



					//get the paid amount in misc

					$displayamount=0;
					$overallmiscdisplayed=0;
					$overallmiscdisplayed2=0;
					$paymentfsem=mysql_query("select date,payment_desc,collection.amount,receipt_num from schedule_of_fees,paymentlist,collection where stud_id='$stud_id'  and collection.sched_id=schedule_of_fees.sched_id and  schedule_of_fees.payment_id=paymentlist.payment_id and schedule_of_fees.sy='$sy' and  paymentlist.payment_group='misc' and schedule_of_fees.semester='II' group by receipt_num order by receipt_num asc") or die(mysql_error());
					while ($paymentfsemrow=mysql_fetch_array($paymentfsem)){
							$thesamemiscoramount=0;

							$getthesamemiscor=mysql_query("select collection.amount from collection,schedule_of_fees,paymentlist where  stud_id='$stud_id' and  collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='misc' and receipt_num='$paymentfsemrow[receipt_num]'") or die(mysql_error());
							while ($thesamemiscorrow=mysql_fetch_array($getthesamemiscor)) {
								$thesamemiscoramount=$thesamemiscoramount+$thesamemiscorrow['amount'];
							}

							$overallmiscdisplayed=$overallmiscdisplayed+$thesamemiscoramount;
							if($overallmiscdisplayed<$moneytobepaidpersemesterinmisc){
								$displayamount=$thesamemiscoramount;
							}else{
								$displayamount=$thesamemiscoramount-($overallmiscdisplayed-$moneytobepaidpersemesterinmisc);
							}
							$$overallmiscdisplayed=$$overallmiscdisplayed+$displayamount;
							$overallmiscdisplayed2=$displayamount;
							$rand=random();
							if($displayamount>0){
							echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
								echo "<td><input type='checkbox' value='II<->$paymentfsemrow[date]<->$paymentfsemrow[receipt_num]<->$displayamount<->Miscellaneous'></td>";
								echo "<td>$paymentfsemrow[date]</td>";
								echo "<td>$paymentfsemrow[receipt_num]</td>";
								echo "<td>".number_format($displayamount,2)."</td>";
								echo "<td>Miscellaneous</td>";
							echo "</tr>";
							}
							
					
					}//

					if($moneytobepaidpersemesterinmisc>$overallmiscdisplayed2){
						$start=0;
						$or="";
						$date="";
						$len=count($orexeededarray)/3;
 						while ($len>$start) {
							
							if($overallmiscdisplayed2<$moneytobepaidpersemesterinmisc){
									$remaining=$moneytobepaidpersemesterinmisc-$overallmiscdisplayed2;
									$or=$orexeededarray[0];
									$date=$orexeededarray[1];
									$amount=$orexeededarray[2]-$remaining;
									
									if($amount>0){

										$displayamount=$remaining;
										$orexeededarray[2]=$orexeededarray[2]-$displayamount;
										$len=2;
										$start=1;
									}else{

										$displayamount=$orexeededarray[2];
										array_splice($orexeededarray, 0,3);
										if(count($orexeededarray)>0){
											$len=2;
											$start=1;
										}else{
											$len=1;
											$start=2;
										}
										
										
									}
									$rand=random();
									$overallmiscdisplayed2=$overallmiscdisplayed2+$displayamount;
									echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
									echo "<td><input type='checkbox' value='II<->$date<->$or<->$displayamount<->Miscellaneous'></td>";
									echo "<td>$date</td>";
									echo "<td>$or</td>";
									echo "<td>".number_format($displayamount,2)."</td>";
									echo "<td>Miscellaneous</td>";
									echo "<tr>";											
							}else{
								$len=1;
								$start=2;
							}
							
						}
					}


					//get the paid amount in new.trans fees
					
					$overallnewfeesdisplayed=0;
					$newfeesrequire="no";
					if($studstatusrow['status']=="trans" || $studstatusrow['status']=="new"){
							
							$paymentfsem=mysql_query("select * from schedule_of_fees,paymentlist where  paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='new' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='II' group by schedule_of_fees.payment_id");
							 while ($paymentfsemrow=mysql_fetch_array($paymentfsem)){
									$displayamount=0;
									$totalpaid=0;
									$getthesamemiscor=mysql_query("select date,payment_desc,collection.amount,schedule_of_fees.payment_id,receipt_num from collection,schedule_of_fees,paymentlist where collection.stud_id='$stud_id' and collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id='$paymentfsemrow[payment_id]' and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='new' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='II' group by receipt_num order by receipt_num") or die(mysql_error());
									while ($thesameor=mysql_fetch_array($getthesamemiscor)){
											$displayamount=0;
											$thesamenewor=0;											
											$getthesamemiscor1=mysql_query("select collection.amount from collection,schedule_of_fees,paymentlist where  stud_id='$stud_id' and schedule_of_fees.payment_id='$paymentfsemrow[payment_id]' and  collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='new' and receipt_num='$thesameor[receipt_num]'") or die(mysql_error());
											while ($thesameor1=mysql_fetch_array($getthesamemiscor1)){
												$thesamenewor=$thesamenewor+$thesameor1['amount'];
											}
											$displayamount=$thesamenewor;
											$totalpaid=$totalpaid+$thesamenewor;
											$rand=random();
											echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
											echo "<td><input type='checkbox' value='II<->$thesameor[date]<->$thesameor[receipt_num]<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
											echo "<td>$thesameor[date]</td>";
											echo "<td>$thesameor[receipt_num]</td>";
											echo "<td>".number_format($displayamount,2)."</td>";
											echo "<td>$thesameor[payment_desc]</td>";
											echo "</tr>";

												
									}
									//heewr
									if($paymentfsemrow['amount']>$totalpaid){

										$start=0;
										$or="";
										$date="";
										$len=count($orexeededarray)/3;
										while ($len>$start) {
											
											if($totalpaid<$paymentfsemrow['amount']){
													$remaining=$paymentfsemrow['amount']-$totalpaid;
													$or=$orexeededarray[0];
													$date=$orexeededarray[1];
													$amount=$orexeededarray[2]-$remaining;
													
													if($amount>0){

														$displayamount=$remaining;
														$orexeededarray[2]=$orexeededarray[2]-$displayamount;
														$len=2;
														$start=1;
													}else{

														$displayamount=$orexeededarray[2];
														array_splice($orexeededarray, 0,3);
														if(count($orexeededarray)>0){
															$len=2;
															$start=1;
														}else{
															$len=1;
															$start=2;
														}
														
														
													}
													$rand=random();
													$totalpaid=$totalpaid+$displayamount;
													echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
													echo "<td><input type='checkbox' value='II<->$date<->$or<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
													echo "<td>$date</td>";
													echo "<td>$or</td>";
													echo "<td>".number_format($displayamount,2)."</td>";
													echo "<td>$paymentfsemrow[payment_desc]</td>";
													echo "<tr>";											
											}else{
												$len=1;
												$start=2;
											}
											
										}
									}
									
									
							
							}//
					}

					/////graduation fees
					$overallnewfeesdisplayed=0;
					$newfeesrequire="no";
					if($studstatusrow['status']=="grad"){
							
							$paymentfsem=mysql_query("select * from schedule_of_fees,paymentlist where  paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='grad' and schedule_of_fees.sy='$sy'  group by schedule_of_fees.payment_id");
							 while ($paymentfsemrow=mysql_fetch_array($paymentfsem)){
									
									$displayamount=0;
									$totalpaid=0;
									$getthesamemiscor=mysql_query("select date,payment_desc,collection.amount,schedule_of_fees.payment_id,receipt_num from collection,schedule_of_fees,paymentlist where collection.stud_id='$stud_id' and collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id='$paymentfsemrow[payment_id]' and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='grad' and schedule_of_fees.sy='$sy' group by receipt_num order by receipt_num") or die(mysql_error());
									while ($thesameor=mysql_fetch_array($getthesamemiscor)){
										if($totalpaid<$paymentfsemrow['amount']){
											$displayamount=0;
											$thesamenewor=0;											
											$getthesamemiscor1=mysql_query("select collection.amount from collection,schedule_of_fees,paymentlist where  stud_id='$stud_id' and schedule_of_fees.payment_id='$paymentfsemrow[payment_id]' and  collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='grad' and receipt_num='$thesameor[receipt_num]'") or die(mysql_error());
											while ($thesameor1=mysql_fetch_array($getthesamemiscor1)){
												$thesamenewor=$thesamenewor+$thesameor1['amount'];
											}
											$displayamount=$thesamenewor;
											$totalpaid=$totalpaid+$thesamenewor;
											$rand=random();
											echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
											echo "<td><input type='checkbox' value='II<->$thesameor[date]<->$thesameor[receipt_num]<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
											echo "<td>$thesameor[date]</td>";
											echo "<td>$thesameor[receipt_num]</td>";
											echo "<td>".number_format($displayamount,2)."g</td>";
											echo "<td>$thesameor[payment_desc]</td>";
											echo "</tr>";
										}
												
									}
									//heewr
									if($paymentfsemrow['amount']>$totalpaid){

										$start=0;
										$or="";
										$date="";
										$len=count($orexeededarray)/3;
										while ($len>$start) {
											
											if($totalpaid<$paymentfsemrow['amount']){
													$remaining=$paymentfsemrow['amount']-$totalpaid;
													$or=$orexeededarray[0];
													$date=$orexeededarray[1];
													$amount=$orexeededarray[2]-$remaining;
													
													if($amount>0){

														$displayamount=$remaining;
														$orexeededarray[2]=$orexeededarray[2]-$displayamount;
														$len=2;
														$start=1;
													}else{

														$displayamount=$orexeededarray[2];
														array_splice($orexeededarray, 0,3);
														if(count($orexeededarray)>0){
															$len=2;
															$start=1;
														}else{
															$len=1;
															$start=2;
														}
														
														
													}
													$rand=random();
													$totalpaid=$totalpaid+$displayamount;
													echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
													echo "<td><input type='checkbox' value='II<->$date<->$or<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
													echo "<td>$date</td>";
													echo "<td>$or</td>";
													echo "<td>".number_format($displayamount,2)."</td>";
													echo "<td>$paymentfsemrow[payment_desc]</td>";
													echo "<tr>";											
											}else{
												$len=1;
												$start=2;
											}
											
										}
									}
									
									
							
							}//
					}
					$getpayment=mysql_query("select collection.amount,payment_desc,date,receipt_num from collection,schedule_of_fees,paymentlist where collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and (payment_group='other' or payment_group='othermisc') and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='II'") or die(mysql_error()) ;
					while ($unrequired=mysql_fetch_array($getpayment)) {
						$rand=random();
						 
					echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
					echo "<td><input type='checkbox' value='II<->$date<->$unrequired[receipt_num]<->$unrequired[amount]<->$unrequired[payment_desc]'></td>";
					echo "<td>$unrequired[date]</td>";
					echo "<td>$unrequired[receipt_num]</td>";
					echo "<td>".number_format($unrequired['amount'],2)."</td>";
					echo "<td>$unrequired[payment_desc]</td>";
					echo "<tr>";
					}

					?>
			</table>


			<div style="clear:both"></div>
			<a hresf="studenft/printpreviewscanreceipt.php" id="printlin" onclick="askprintcert('<?=$sy;?>')"><button class="print" style="position:relative;top:4px;right:10px;float:right;"></button></a>
</div> <!--  end of scancon-->

<script>

function askprintcert(a){
	var cover=$('#overlay, #modal');
	var amountdata="";
	$("input[checked='checked']").each(function() {
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