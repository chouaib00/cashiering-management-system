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

					function random(){
						$possiblevalue="1,2,3,4,5,6,7,8,9";
						$explode=explode(",", $possiblevalue);
						$rand="";
						for ($i=0; $i < 10; $i++) { 
							$rand.=$explode[rand(0,8)];
						}
						return $rand;
					}

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
									array_push($orexeededarray,$paidrow['receipt_num'],$paidrow['date'],$total);
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

					// echo "over all payment in the sem:$moneytobepaidpersemester<br>";
					// echo "over all payment paid:$paidmoneypersemester<br><br>";
					// echo "normal misc to pay:$moneytobepaidpersemesterinmisc<br>";
					// echo "Money Paid in misc:$moneypaidpersemesterinmisc<br>";
					// echo "money exceeded in misc:$moneyexceededpersemesterinmisc<br><br>";
					// echo "normal tui to pay:$moneytobepaidpersemesterintuition<br>";
					// echo "money exceeded in tuition:$moneyexceedeedpersemesterintuition<br>";
					// echo "over all exceeded money:$overallexceededmoney s<br>";

					
					//end calculating the paymentst

					// echo "jkae".count($orexeededarray)."$orexeededarray[2] cortn<br>";
					//get all the payments in sched and rle
					$ortodisplay="";
					$datetodisplay="";
					$overallmoneydisplayed=0;
					$paymentfsem=mysql_query("select * from schedule_of_fees,paymentlist where  schedule_of_fees.payment_id=paymentlist.payment_id and schedule_of_fees.sy='$sy' and course_id='$studstatusrow[course_id]' and  (paymentlist.payment_group='sched' or paymentlist.payment_group='rle') and (year_level like '$studstatusrow[year_level]&%' or year_level like '%&$studstatusrow[year_level]') and schedule_of_fees.semester='I'") or die(mysql_error());
					while ($paymentfsemrow=mysql_fetch_array($paymentfsem)){

						// check if how much is paid
						$totalpaid=0;
						$totalpaid2=0;
						$displayamount=0;			
						$moneypaid=mysql_query("select collection.amount,collection.receipt_num,date from collection, schedule_of_fees,paymentlist where collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_id='$paymentfsemrow[payment_id]' and collection.stud_id='$stud_id' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='I'") or die(mysql_error());
						while ($paidrow=mysql_fetch_array($moneypaid)){
								$totalpaidpersched=0;
								if($totalpaid<$paymentfsemrow['amount']){
								
										if($paidrow['amount']>$paymentfsemrow['amount']){
											$displayamount=$paymentfsemrow['amount'];	
										}else{
											$displayamount=$paidrow['amount'];
										}

										$totalpaid2=$totalpaid;
										$totalpaid=$totalpaid+$paidrow['amount'];

										if($totalpaid>$paymentfsemrow['amount']){
											
											$displayamount=$paymentfsemrow['amount']-$totalpaid2;
										}
										$totalpaidpersched=$totalpaidpersched+$displayamount;
										$rand=random();
												
												
										echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
										echo "<td><input type='checkbox' value='I<->--<->--<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
										echo "<td>$paidrow[date] $orexeededarray[2]  here".count($orexeededarray)."</td>";
										echo "<td>$paidrow[receipt_num]</td>";
										echo "<td>".number_format($displayamount,2)."</td>";
										echo "<td>$paymentfsemrow[payment_desc]</td>";
										echo "<tr>";
								}////////

								if($totalpaidpersched<$paymentfsemrow['amount'] & $overallexceededmoney>0){
									$displayamount=0;
									$displayamount=$paymentfsemrow['amount']-$totalpaidpersched;
									if($displayamount<$overallexceededmoney){
										$overallexceededmoney=$overallexceededmoney-$displayamount;
									}else{
										$displayamount=$overallexceededmoney;
										$overallexceededmoney=0;
									}
									$rand=random();
										$start=0;
										$or="";
										$date="";
										$len=count($orexeededarray)/3;
										while ($len>=$start) {
											
											if($orexeededarray[2]>0){
													$or=$orexeededarray[0];
													$date=$orexeededarray[1];
													$amount=$orexeededarray[2]-$displayamount;
													
													if($amount>0){
														$orexeededarray[2]=$orexeededarray[2]-$displayamount;
													}else{
														unset($orexeededarray[0]);
														unset($orexeededarray[1]);
														unset($orexeededarray[2]);
														
													}

													$start=13123;
											}else{
												$start=0;
												unset($orexeededarray[0]);
												unset($orexeededarray[1]);
												unset($orexeededarray[2]);
											}
										}
										echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
										echo "<td><input type='checkbox' value='I<->--<->--<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
										echo "<td>$date</td>";
										echo "<td>$or</td>";
										echo "<td>".number_format($displayamount,2)."</td>";
										echo "<td>$paymentfsemrow[payment_desc]</td>";
									echo "<tr>";
								}

						}////

						if(mysql_num_rows($moneypaid)==0){
							if($overallexceededmoney>0){
								if($overallexceededmoney>$paymentfsemrow['amount']){
									$displayamount=$paymentfsemrow['amount'];
									$overallexceededmoney=$overallexceededmoney-$displayamount;
								}else{	
									$displayamount=$overallexceededmoney;
									$overallexceededmoney=0;
								}
								$rand=random();
								$start=0;
								$or="";
								$date="";
								$len=count($orexeededarray)/3;
											$amount=$orexeededarray[2]-$paymentfsemrow['amount'];
								echo "$amount -----";
								// while ($len>=$start) {									
								// 	if($orexeededarray[2]>0){
								// 			$or=$orexeededarray[0];
								// 			$date=$orexeededarray[1];
								// 			$amount=$orexeededarray[2]-$paymentfsemrow['amount'];
											
								// 			if($amount>0){
								// 				$displayamount=$paymentfsemrow['amount'];
								// 				$orexeededarray[2]=$orexeededarray[2]-$displayamount;
								// 				if(count($orexeededarray)>0){
								// 					$len=2;
								// 					$start=1;
								// 				}else{
								// 					$len=1;
								// 					$start=2;
								// 				}
								// 			}else{
								// 				$displayamount=$orexeededarray[2];
								// 				array_splice($orexeededarray, 0,3);
								// 				$len=1;
								// 				$start=2;
												
								// 			}

											
								// 	}
								// 	$start=123;
								// 	$len=1;
								// 	echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
								// 	echo "<td><input type='checkbox' value='I<->--<->--<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
								// 	echo "<td>$date ber</td>";
								// 	echo "<td>$or</td>";
								// 	echo "<td>$displayamount af</td>";
								// 	echo "<td>$paymentfsemrow[payment_desc]</td>";
								// 	echo "<tr>";
								// }
								
							}
						}

						
					}
					echo "over all: $overallexceededmoney, left $orexeededarray[2] count".count($orexeededarray);
					

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
							echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
								echo "<td><input type='checkbox' value='I<->--<->--<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
								echo "<td>$paymentfsemrow[date]</td>";
								echo "<td>$paymentfsemrow[receipt_num]</td>";
								echo "<td>".number_format($displayamount,2)."</td>";
								echo "<td>Miscellaneous</td>";
							echo "</tr>";
							
					
					}
					if($overallexceededmoney>0 && $overallmiscdisplayed2<$moneytobepaidpersemesterinmisc){
						$displayamount=0;
						$miscleft=$moneytobepaidpersemesterinmisc-$overallmiscdisplayed;
						if($miscleft<$overallexceededmoney){
							$displayamount=$miscleft;
							$overallexceededmoney=$overallexceededmoney-$displayamount;
						}else{
							$displayamount=$overallexceededmoney;
							$overallexceededmoney=0;
						}
							$rand=random();
							$or="";
							$date="";
							$start=1;
							$len=count($orexeededarray)/3;
							while ($len>=$start){								
								$displayamount=0;
								echo "<br>count".count($orexeededarray)." $orexeededarray[0] $orexeededarray[1] $orexeededarray[2]";
										$or=$orexeededarray[0];
										$date=$orexeededarray[1];
										$remaining=$moneytobepaidpersemesterinmisc-$overallmiscdisplayed2;
										$amount=$orexeededarray[2]-$remaining;
										echo "-$orexeededarray[2]-";
										if($amount>0){											
											$displayamount=$remaining;											
											$orexeededarray[2]=$orexeededarray[2]-$displayamount;
											$start=132;
											echo "last";
										}else{
											echo "<br>before unset ".count($orexeededarray);
											$displayamount=$orexeededarray[2];
											array_splice($orexeededarray,0,3);
											echo "<br>after unset ".count($orexeededarray);
											echo "$orexeededarray[4]";
											// if(count($orexeededarray)>0){
											// 	echo "string";
											// 	$len=1;
											// 	$start=1;
											// }else{
											// 	echo "string2";
											// 	$len=11;
											// 	$start=22;
											// }
											
										}
										 

										
									$len--;
								echo "<br>newcount ".count($orexeededarray).$orexeededarray[5];
								echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
								echo "<td><input type='checkbox' value='I<->--<->--<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
								echo "<td>$date</td>";
								echo "<td>$or</td>";
								echo "<td>".number_format($displayamount,2)."</td>";
								echo "<td>Miscellaneous</td>";
								echo "</tr>";
							}
							
					}
					
					//get the paid amount in new.trans fees
					
					$overallnewfeesdisplayed=0;
					$newfeesrequire="no";
					if($studstatusrow['status']=="trans" || $studstatusrow['status']=="new"){
							
							$paymentfsem=mysql_query("select * from schedule_of_fees,paymentlist where  paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='new' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='I' group by schedule_of_fees.payment_id");
							 while ($paymentfsemrow=mysql_fetch_array($paymentfsem)){
									$displayamount=0;
									$totalpaid="-";
									$getthesamemiscor=mysql_query("select date,payment_desc,collection.amount,schedule_of_fees.payment_id,receipt_num from collection,schedule_of_fees,paymentlist where collection.stud_id='$stud_id' and collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id='$paymentfsemrow[payment_id]' and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='new' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='I' group by receipt_num order by receipt_num") or die(mysql_error());
									while ($thesameor=mysql_fetch_array($getthesamemiscor)){
											$thesamenewor=0;
											$totalpaid=0;
											$getthesamemiscor1=mysql_query("select collection.amount from collection,schedule_of_fees,paymentlist where  stud_id='$stud_id' and schedule_of_fees.payment_id='$paymentfsemrow[payment_id]' and  collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='new' and receipt_num='$thesameor[receipt_num]'") or die(mysql_error());
											while ($thesameor1=mysql_fetch_array($getthesamemiscor1)){
												$thesamenewor=$thesamenewor+$thesameor1['amount'];
											}
											$totalpaid=$totalpaid+$thesamenewor;
											$rand=random();
											echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
											echo "<td><input type='checkbox' value='I<->--<->--<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
											echo "<td>$thesameor[date]</td>";
											echo "<td>$thesameor[receipt_num]</td>";
											echo "<td>".number_format($totalpaid,2)."</td>";
											echo "<td>$thesameor[payment_desc]</td>";
											echo "</tr>";

												
									}

									if($totalpaid<$paymentfsemrow['amount'] && $totalpaid!='-'){
										$displayamount2=0;
										$displayamount2=$paymentfsemrow['amount']-$totalpaid;
										if($displayamount2<$overallexceededmoney){
											$overallexceededmoney=$overallexceededmoney-$displayamount2;
										}else{
											$displayamount2=$overallexceededmoney;
											$overallexceededmoney=0;
										}
											$rand=random();
											$start=0;
								
										echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
										echo "<td><input type='checkbox' value='I<->--<->--<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
											echo "<td>--</td>";
											echo "<td>--</td>";
											echo "<td>".number_format($displayamount2)."</td>";
											echo "<td>$paymentfsemrow[payment_desc]</td>";
											echo "</tr>";
									}
									
									if(mysql_num_rows($getthesamemiscor)==0 && $overallexceededmoney>0){
										$displayamount=$paymentfsemrow['amount'];										
										if($displayamount<$overallexceededmoney){
											$displayamount=$paymentfsemrow['amount'];
											$overallexceededmoney=$overallexceededmoney-$displayamount;
										}else{
											$displayamount=$overallexceededmoney;
											$overallexceededmoney=0;
										}

										$rand=random();
										$or="";
										$date="";
										$start=0;
										$len=count($orexeededarray)/3;
										while ($len>=$start) {
											
											if($orexeededarray[2]>0){
													$or=$orexeededarray[0];
													$date=$orexeededarray[1];
													$amount=$orexeededarray[2]-$displayamount;
													
													if($amount>0){
														$orexeededarray[2]=$orexeededarray[2]-$displayamount;
													}else{
														unset($orexeededarray[0]);
														unset($orexeededarray[1]);
														unset($orexeededarray[2]);
														
													}

													$start=13123;
											}else{
												$start++;
											}
										}
										echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
										echo "<td><input type='checkbox' value='I<->--<->--<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
										echo "<td>$date</td>";
										echo "<td>$or</td>";
										echo "<td>".number_format($displayamount,2)."</td>";
										echo "<td>$paymentfsemrow[payment_desc] as</td>";
										echo "</tr>";

									}
									
									
							
							}
					}

					/////graduation fees
					$overallnewfeesdisplayed=0;
					$newfeesrequire="no";
					if($studstatusrow['status']=="grad" || $studstatusrow['grad']=="new"){
							
							$paymentfsem=mysql_query("select * from schedule_of_fees,paymentlist where  paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='grad' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='0' group by schedule_of_fees.payment_id");
							 while ($paymentfsemrow=mysql_fetch_array($paymentfsem)){
									$displayamount=0;
									$totalpaid="-";
									$getthesamemiscor=mysql_query("select date,payment_desc,collection.amount,schedule_of_fees.payment_id,receipt_num from collection,schedule_of_fees,paymentlist where collection.stud_id='$stud_id' and collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id='$paymentfsemrow[payment_id]' and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='grad' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='0' group by receipt_num order by receipt_num") or die(mysql_error());
									while ($thesameor=mysql_fetch_array($getthesamemiscor)){
											$thesamenewor=0;
											$totalpaid=0;
											$getthesamemiscor1=mysql_query("select collection.amount from collection,schedule_of_fees,paymentlist where  stud_id='$stud_id' and schedule_of_fees.payment_id='$paymentfsemrow[payment_id]' and  collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_group='grad' and receipt_num='$thesameor[receipt_num]'") or die(mysql_error());
											while ($thesameor1=mysql_fetch_array($getthesamemiscor1)){
												$thesamenewor=$thesamenewor+$thesameor1['amount'];
											}
											$totalpaid=$totalpaid+$thesamenewor;
											$rand=random();
											echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
											echo "<td><input type='checkbox' value='I<->--<->--<->$totalpaid<->$paymentfsemrow[payment_desc]'></td>";
											echo "<td>$thesameor[date]</td>";
											echo "<td>$thesameor[receipt_num]</td>";
											echo "<td>".number_format($totalpaid,2)."</td>";
											echo "<td>$thesameor[payment_desc]</td>";
											echo "</tr>";

												
									}

									if($totalpaid<$paymentfsemrow['amount'] && $totalpaid!='-' && $overallexceededmoney>0 ){
										$displayamount2=0;
										$displayamount2=$paymentfsemrow['amount']-$totalpaid;
										if($displayamount2<$overallexceededmoney){
											$overallexceededmoney=$overallexceededmoney-$displayamount2;
										}else{
											$displayamount2=$overallexceededmoney;
											$overallexceededmoney=0;
										}
											$rand=random();
								
											echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
											echo "<td><input type='checkbox' value='I<->--<->--<->$displayamount2<->$paymentfsemrow[payment_desc]'></td>";
											echo "<td>$date</td>";
											echo "<td>$or</td>";
											echo "<td>".number_format($displayamount2)."</td>";
											echo "<td>$paymentfsemrow[payment_desc]</td>";
											echo "</tr>";
									}
									
									if(mysql_num_rows($getthesamemiscor)==0 && $overallexceededmoney>0){
										$displayamount=$paymentfsemrow['amount'];										
										if($displayamount<$overallexceededmoney){
											$displayamount=$paymentfsemrow['amount'];
											$overallexceededmoney=$overallexceededmoney-$displayamount;
										}else{
											$displayamount=$overallexceededmoney;
											$overallexceededmoney=0;
										}

										$rand=random();
									
										echo "<tr id='scanrow$rand' onclick='selectamount($rand)'>";
										echo "<td><input type='checkbox' value='I<->--<->--<->$displayamount<->$paymentfsemrow[payment_desc]'></td>";
										echo "<td>--</td>";
										echo "<td>--</td>";
										echo "<td>".number_format($displayamount,2)."</td>";
										echo "<td>$paymentfsemrow[payment_desc]</td>";
										echo "</tr>";

									}
									
									
							
							}
					}
					

					?>
			</table>
			<div style="clear:both"></div>
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