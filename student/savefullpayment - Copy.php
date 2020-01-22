<?php
session_start();
include '../dbconfig.php';
$user_id=$_SESSION['user_id'];
$sy=$_POST['sy'];
$semester=$_POST['semester'];
$date=date('m/d/Y');
$stud_id=$_POST['stud_id'];
$receipt=$_POST['receipt'];
$money=$_POST['topay'];
 $change=$_POST['cash'];
$sendmoney=$money;
$paymentdata=$_POST['paymentdata'];
$scholar_id=$_POST['scholar_id'];
$paymentdataarr=explode("[endline]", $paymentdata);
$paymentdatalen=count($paymentdataarr);
$start=1;
$paymentarray="pp";

//check  receiptnhumber
$checkreceipt=mysql_query("select receipt_num from collection where receipt_num='$receipt' group by receipt_num limit 1");


if(mysql_num_rows($checkreceipt)==0){
if($money==""){
	header("location:index.php");
}
//scholar
if($scholar_id>0){
mysql_query("update student_status set scholar_id='$scholar_id' WHERE stud_id='$stud_id' and sy='$sy' and semester='$semester' ") or die(mysql_error());
}



$getstatus=mysql_query("select * from student_status,student,course where course.course_id=student_status.course_id and student_status.stud_id=student.stud_id and  student_status.stud_id='$stud_id' and sy='$sy' and semester='$semester' order by stat_id desc") or die(mysql_error());
$statusrow=mysql_fetch_array($getstatus);

$GLOBALS['course_id'] = $statusrow['course_id'];
$GLOBALS['year_level'] = $statusrow['year_level'];
$GLOBALS['status'] = $statusrow['status'];
include 'detectfullpayment.php';
$paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
$explodearray=explode("//", $paymentexplode);
$overallpaid=$explodearray[1];
$overallbalance=$explodearray[0];

echo "$overallpaid --$overallbalance";
function checkoverallmiscpaid(){

	$stud_id=$_POST['stud_id'];
	$sy=$_POST['sy'];
	$semester=$_POST['semester'];
	$total=0;
	$misc=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='misc' and course_id='$GLOBALS[course_id]' and ( year_level like '$GLOBALS[year_level]&%' or year_level like '%&$GLOBALS[year_level]') and sy='$sy' and semester='$semester' ") or die(mysql_error());
	while ($miscrow=mysql_fetch_array($misc)) {
		 $bal=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and  schedule_of_fees.payment_id='$miscrow[payment_id]' and stud_id='$stud_id' and remark='0' and  schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester'");
		$balrow=mysql_fetch_array($bal);
			$total=$total+$balrow['amount'];
		
	 }
	return $total;
}

function overallmisctotal($year_level,$course_id){
	$stud_id=$_POST['stud_id'];
	$sy=$_POST['sy'];
	$semester=$_POST['semester'];	
 	$misc=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='misc' and course_id='$course_id' and ( year_level like '$year_level&%' or year_level like '%&$year_level') and sy='$sy' and semester='$semester' ") or die(mysql_error());
	$total=0;
	while ($row=mysql_fetch_array($misc)) {
		$total=$total+$row['amount'];
	}
	return $total;
} 

$overallmisctotal=overallmisctotal($statusrow['year_level'],$statusrow['course_id']);
 while ($start<$paymentdatalen) {
		
	$paymentdata2=$paymentdataarr[$start];
	$paymentdata2arr=explode("<->", $paymentdata2);
	$payment_id=$paymentdata2arr[0];
	$amount=$paymentdata2arr[1];

	if($payment_id=="misc"){
			$totalmiscpaid=0;
			if($overallmisctotal>checkoverallmiscpaid()){
					//save payment to not fully paid misc
					$misc2=mysql_query("select schedule_of_fees.sched_id,schedule_of_fees.amount,collection.amount,schedule_of_fees.payment_id   from schedule_of_fees,paymentlist,collection  where schedule_of_fees.sched_id=collection.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='misc' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' group by schedule_of_fees.sched_id");
					
					while ($miscrow2=mysql_fetch_array($misc2)){
						if($money>0 && $overallmisctotal>checkoverallmiscpaid()){								
							$misctotalpartial=0;
			 				$misc3=mysql_query("select collection.amount,schedule_of_fees.sched_id from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and  schedule_of_fees.payment_id='$miscrow2[payment_id]' and stud_id='$statusrow[stud_id]' and remark='0' and  schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester'") or die(mysql_error());
							while ($miscrow3=mysql_fetch_array($misc3)) {
								$misctotalpartial=$misctotalpartial+$miscrow3['amount'];
							}
							 if($misctotalpartial<$miscrow2[1]){
									$remainingamount1=0;
				 					$amount2=0;
									$moneytopay=$miscrow2[1]-$misctotalpartial;
				 					if($moneytopay<$money){
				 						$money=$money-$moneytopay;
				 					}else{
				 						$moneytopay=$money;
				 						$money=0;
				 					}
				 					$paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
									$explodearray=explode("//", $paymentexplode);
									$overallpaid=$explodearray[1];
									$overallbalance=$explodearray[0];
									if($overallpaid<($overallbalance+$moneytopay)){
										$moneytopay=$overallbalance-$overallpaid;
										if($moneytopay>$miscrow2['amount']){
											$moneytopay=$miscrow2['amount'];
										}
									}
				 					$totalmiscpaid=$totalmiscpaid+$moneytopay;
				 					if($moneytopay>0){
								 	mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$miscrow2[0]','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
								 }
								 }
						}
					}
					

				//save the other remaining money to MISCELLANEOUS that is not yet paid
				if($amount>0){
							$getnotpaidmisc=mysql_query("select schedule_of_fees.amount,schedule_of_fees.sched_id from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id not in (select payment_id from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and stud_id='$statusrow[stud_id]' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester')  and schedule_of_fees.sched_id not in (select schedule_of_fees.sched_id from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and  schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and course_id='$statusrow[course_id]' and ( year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]')) and  schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='misc' and course_id='$statusrow[course_id]' and ( year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]') and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' group by schedule_of_fees.sched_id") or die(mysql_error());
							while($notpaid=mysql_fetch_array($getnotpaidmisc)){
							 	if($money>0 && $overallmisctotal>checkoverallmiscpaid()){	
							 		$moneytopay=$notpaid['amount'];					 	
							 		if($moneytopay<$money){
							 			$money=$money-$moneytopay;
							 		}else{
							 			$moneytopay=$money;
							 			$money=0;
							 		}
							 		$paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
									$explodearray=explode("//", $paymentexplode);
									$overallpaid=$explodearray[1];
									$overallbalance=$explodearray[0];
									if($overallpaid<($overallbalance+$moneytopay)){
										$moneytopay=$overallbalance-$overallpaid;
										if($moneytopay>$notpaid['amount']){
											$moneytopay=$notpaid['amount'];
										}
									}
							 		$totalmiscpaid=$totalmiscpaid+$moneytopay;
							 		if($moneytopay>0){
									mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$notpaid[sched_id]','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
									}
							 	}
							}
				}

				//save the other remaining money to MISCELLANEOUS that is not yet paid:posibilty-if shifted o other course and the remaining misc in that list at the last part is not enough so it will go black to the beginning
				if($amount>0){
						$getnotpaidmisc=mysql_query("select schedule_of_fees.amount,schedule_of_fees.sched_id from schedule_of_fees,paymentlist  where schedule_of_fees.sched_id not in (select schedule_of_fees.sched_id from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and  schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and course_id='$statusrow[course_id]' and ( year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]')) and  schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='misc' and course_id='$statusrow[course_id]' and ( year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]') and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' group by schedule_of_fees.sched_id") or die(mysql_error());
						 while($notpaid=mysql_fetch_array($getnotpaidmisc)){
						 	if($money>0 && $overallmisctotal>checkoverallmiscpaid()){	
						 		$moneytopay=$notpaid['amount'];					 	
						 		if($moneytopay<$money){
						 			$money=$money-$moneytopay;
						 		}else{
						 			$moneytopay=$money;
						 			$money=0;
						 		}
						 		$paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
									$explodearray=explode("//", $paymentexplode);
									$overallpaid=$explodearray[1];
									$overallbalance=$explodearray[0];
									if($overallpaid<($overallbalance+$moneytopay)){
										$moneytopay=$overallbalance-$overallpaid;
										if($moneytopay>$notpaid['amount']){
											$moneytopay=$notpaid['amount'];
										}
									}
						 		$totalmiscpaid=$totalmiscpaid+$moneytopay;
						 		if($moneytopay>0){
								mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$notpaid[sched_id]','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
							}
						 	}
					}
				}
			}
			if($totalmiscpaid>0){
		$paymentarray="$paymentarray<endline>Miscellaneous<->$totalmiscpaid";
	}
	}else{////
		//save the other payment if not misc
		if($money>0){
			if($money<$amount){
				$amount=$money;
				$money=0;
			}else{
			$money=$money-$amount;
			}
			$moneytopay=$amount;


		//get the normal amount

		$getnorm=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and  sched_id='$payment_id'");
		$normalamount=mysql_fetch_array($getnorm);
		$paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
				$explodearray=explode("//", $paymentexplode);
				$overallpaid=$explodearray[1];
				$overallbalance=$explodearray[0];
				if($normalamount['payment_group']=="other" || $normalamount['payment_group']=="othermisc"){
				}else{
					// if($overallpaid<($overallbalance+$moneytopay)){
					// 	$moneytopay=$overallbalance-$overallpaid;
					// 	if($moneytopay>$normalamount['amount']){
					// 		$moneytopay=$normalamount['amount'];
					// 	}
					// }
				}

				if($moneytopay>0 || ($normalamount['payment_group']=="other" || $normalamount['payment_group']=="othermisc")){
			 mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$payment_id','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());

	 			//get description to sent to receipt
	 			$desc=mysql_query("select payment_desc from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and sched_id='$payment_id'");
	 			$descrow=mysql_fetch_array($desc);

	 			$paymentarray="$paymentarray<endline>$descrow[payment_desc]<->$moneytopay";
 			}

		}	
	}
	$start++;
}

//get the overall money paid
$getmoneypaid=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees,paymentlist where schedule_of_fees.sched_id=collection.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group!='other' and remark='0'  and paymentlist.payment_group!='othermisc' and stud_id='$stud_id' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester'") or die(mysql_error());
$moneypaid=mysql_fetch_array($getmoneypaid);


 
////////////////////////////////////////////////////////////////////////////////////////////////////////
// save the remaining balance to miscellaneous
$paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
$explodearray=explode("//", $paymentexplode);
$overallpaid=$explodearray[1];
$overallbalance=$explodearray[0];
  if($money>0 && $overallpaid<$overallbalance){
		//check if the paid misc is greater than the misc to paid in the current status:possiblities-if student shifted to other course which has greater amount of misc than before the student paid
		if($overallmisctotal>checkoverallmiscpaid()){
			
				$totalmiscpaid=0;

					//save payment to not fully paid misc
					$misc2=mysql_query("select schedule_of_fees.sched_id,schedule_of_fees.amount,collection.amount,schedule_of_fees.payment_id   from schedule_of_fees,paymentlist,collection  where schedule_of_fees.sched_id=collection.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='misc' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and remark='0' group by schedule_of_fees.sched_id");
					
					while ($miscrow2=mysql_fetch_array($misc2)){
						if($money>0 && $overallmisctotal>checkoverallmiscpaid() && $overallpaid<$overallbalance){								
							$misctotalpartial=0;
			 				$misc3=mysql_query("select collection.amount,schedule_of_fees.sched_id from collection,schedule_of_fees where  remark='0' and  collection.sched_id=schedule_of_fees.sched_id and  schedule_of_fees.payment_id='$miscrow2[payment_id]' and stud_id='$statusrow[stud_id]' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester'") or die(mysql_error());
							while ($miscrow3=mysql_fetch_array($misc3)) {
								$misctotalpartial=$misctotalpartial+$miscrow3['amount'];
 							}
 							 if($misctotalpartial<$miscrow2[1] && $money>0){
									$remainingamount1=0;
				 					$amount2=0;
									$moneytopay=$miscrow2[1]-$misctotalpartial;
									$bal=$miscrow2[1]-$misctotalpartial;
				 					if($moneytopay<$money){
				 						
				 					}else{
				 						$moneytopay=$money;
 				 					}

				 					$paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
									$explodearray=explode("//", $paymentexplode);
									$overallpaid=$explodearray[1];
									$overallbalance=$explodearray[0];
									if($overallpaid<($overallbalance+$moneytopay)){
										$moneytopay=$overallbalance-$overallpaid;
										if($moneytopay>$bal){
											$moneytopay=$bal;
										}
									}
									if($overallmisctotal>checkoverallmiscpaid()){
										if($moneytopay>0){
											$money=$money-$moneytopay;
									 	mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$miscrow2[0]','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
										 }
									}
									$totalmiscpaid=$totalmiscpaid+$moneytopay;
								 }
						}
					}
			
					$paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
					$explodearray=explode("//", $paymentexplode);
					$overallpaid=$explodearray[1];
					$overallbalance=$explodearray[0];


				//save the other remaining money to MISCELLANEOUS that is not yet paid
				if($money>0 && $overallpaid<$overallbalance){
							$getnotpaidmisc=mysql_query("select schedule_of_fees.amount,schedule_of_fees.sched_id from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id not in (select payment_id from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and stud_id='$statusrow[stud_id]' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester')  and schedule_of_fees.sched_id not in (select schedule_of_fees.sched_id from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and  schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and course_id='$statusrow[course_id]' and ( year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]')) and  schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='misc' and course_id='$statusrow[course_id]' and ( year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]') and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' group by schedule_of_fees.sched_id") or die(mysql_error());
							while($notpaid=mysql_fetch_array($getnotpaidmisc)){
							 	if($money>0 && $overallmisctotal>checkoverallmiscpaid() && $overallpaid<$overallbalance){	
							 		$moneytopay=$notpaid['amount'];					 	
							 		if($moneytopay<$money){
 							 		}else{
							 			$moneytopay=$money;
 							 		}

							 		$paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
									$explodearray=explode("//", $paymentexplode);
									$overallpaid=$explodearray[1];
									$overallbalance=$explodearray[0];
									if($overallpaid<($overallbalance+$moneytopay)){
										$moneytopay=$overallbalance-$overallpaid;
										if($moneytopay>$notpaid['amount']){
											$moneytopay=$notpaid['amount'];
										}
										if($money<$moneytopay){
											$moneytopay=$money;
										}
									}

									 
 								 		$totalmiscpaid=$totalmiscpaid+$moneytopay;
								 		if($moneytopay>0){
										mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$notpaid[sched_id]','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
								 			$money=$money-$moneytopay;
									 	}
							 	}
							}
				}

				$paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
				$explodearray=explode("//", $paymentexplode);
				$overallpaid=$explodearray[1];
				$overallbalance=$explodearray[0];
				//save the other remaining money to MISCELLANEOUS that is not yet paid:posibilty-if shifted o other course and the remaining misc in that list at the last part is not enough so it will go black to the beginning
				if($money>0 && $overallpaid<$overallbalance){
						$getnotpaidmisc=mysql_query("select schedule_of_fees.amount,schedule_of_fees.sched_id from schedule_of_fees,paymentlist  where schedule_of_fees.sched_id not in (select schedule_of_fees.sched_id from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and  remark='0' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and course_id='$statusrow[course_id]' and ( year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]')) and  schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='misc' and course_id='$statusrow[course_id]' and ( year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]') and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' group by schedule_of_fees.sched_id") or die(mysql_error());
						 while($notpaid=mysql_fetch_array($getnotpaidmisc)){
						 	if($money>0 && $overallmisctotal>checkoverallmiscpaid()  && $overallpaid<$overallbalance){	
						 		$moneytopay=$notpaid['amount'];					 	
						 		if($moneytopay<$money){
 						 		}else{
						 			$moneytopay=$money;
 						 		}
						 			$paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
									$explodearray=explode("//", $paymentexplode);
									$overallpaid=$explodearray[1];
									$overallbalance=$explodearray[0];
									if($overallpaid<($overallbalance+$moneytopay)){
										$moneytopay=$overallbalance-$overallpaid;
										if($moneytopay>$notpaid['amount']){
											$moneytopay=$notpaid['amount'];
										}
									}
						 	$totalmiscpaid=$totalmiscpaid+$moneytopay;
						 	if($moneytopay>0){
						 		$money=$money-$moneytopay;
								mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$notpaid[sched_id]','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
							}
						 	}
					}
				}
				if($totalmiscpaid>0){
				$paymentarray="$paymentarray<endline>Miscellaneous<->$totalmiscpaid";
			}
			}//////
 			
			
 }
   //pay the tution after the miscellaneous
 $paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
$explodearray=explode("//", $paymentexplode);
$overallpaid=$explodearray[1];
$overallbalance=$explodearray[0];
 if($money>0  && $overallpaid<$overallbalance){
 	//get first the tuition,lab,reg
 	$sched=mysql_query("select * from schedule_of_fees,paymentlist,course,student_status where course.course_id=student_status.course_id and student_status.stud_id='$statusrow[stud_id]' and schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='sched' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and ( schedule_of_fees.year_level like '$statusrow[year_level]&%' or  schedule_of_fees.year_level like '%&$statusrow[year_level]') and schedule_of_fees.course_id='$statusrow[course_id]'") or die(mysql_error());
	
 	while ($schedrow=mysql_fetch_array($sched)) {

 		//check if how much money paid in collection
 		$tuitionpaid=0;
		$uitpaidquery=mysql_query("select collection.amount from collection,schedule_of_fees,paymentlist where collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and schedule_of_fees.sy='$sy' and schedule_of_fees.payment_id='$schedrow[payment_id]' and  schedule_of_fees.semester='$semester' and paymentlist.payment_group='sched' and collection.stud_id='$stud_id' and  remark='0'") or die(mysql_error());
		while ($miscrow=mysql_fetch_array($uitpaidquery)) {	 	
			$tuitionpaid=$tuitionpaid+$miscrow['amount'];
		}

			if($tuitionpaid<$schedrow['amount'] && $money>0 && $overallpaid<$overallbalance){
			 		if($money>0){			 			
				 	 		if($schedrow['amount']>$tuitionpaid){
					 		
						 		$moneytopay=$schedrow['amount']-$tuitionpaid;
						 		$bal3=$schedrow['amount']-$tuitionpaid;
						 				// 3000=4000-1000;
						 		if($money>$moneytopay){
 						 		}else{
						 			$moneytopay=$money;
 						 		}

						 			$paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
									$explodearray=explode("//", $paymentexplode);
									$overallpaid=$explodearray[1];
									$overallbalance=$explodearray[0];
									if($overallpaid<($overallbalance+$moneytopay)){
										$moneytopay=$overallbalance-$overallpaid;
										if($moneytopay>$bal3){
											$moneytopay=$bal3;
										}
										if($money<$moneytopay){
											$moneytopay=$money;
										}
									}

									if($moneytopay>0){
										$money=$money-$moneytopay;
								mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$schedrow[sched_id]','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
 								$paymentarray="$paymentarray<endline>$schedrow[payment_desc]<->$moneytopay";
 							}
							}
					}
			}
 	}
 }

  //pay the rle after the miscellaneous
 $paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
$explodearray=explode("//", $paymentexplode);
$overallpaid=$explodearray[1];
$overallbalance=$explodearray[0];
 if($money>0  && $overallpaid<$overallbalance){
 	//get first the tuition,lab,reg
 	$sched=mysql_query("select * from schedule_of_fees,paymentlist,course,student_status where course.course_id=student_status.course_id and student_status.stud_id='$statusrow[stud_id]' and schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='rle' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and ( schedule_of_fees.year_level like '$statusrow[year_level]&%' or  schedule_of_fees.year_level like '%&$statusrow[year_level]') and schedule_of_fees.course_id='$statusrow[course_id]'") or die(mysql_error());
	
 	while ($schedrow=mysql_fetch_array($sched)) {

 		//check if how much money paid in collection
 		$tuitionpaid=0;
		$uitpaidquery=mysql_query("select collection.amount from collection,schedule_of_fees,paymentlist where collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and schedule_of_fees.sy='$sy' and schedule_of_fees.payment_id='$schedrow[payment_id]' and  schedule_of_fees.semester='$semester' and paymentlist.payment_group='rle'  and remark='0' and collection.stud_id='$stud_id'") or die(mysql_error());
		while ($miscrow=mysql_fetch_array($uitpaidquery)) {	 	
			$tuitionpaid=$tuitionpaid+$miscrow['amount'];
		}

			if($tuitionpaid<$schedrow['amount']  && $overallpaid<$overallbalance){
			 		if($money>0){			 			
				 	 		if($schedrow['amount']>$tuitionpaid){
					 		
						 		$moneytopay=$schedrow['amount']-$tuitionpaid;
						 				// 3000=4000-1000;
						 		if($money>$moneytopay){
 						 		}else{
						 			$moneytopay=$money;
 						 		}
						 		$paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
									$explodearray=explode("//", $paymentexplode);
									$overallpaid=$explodearray[1];
									$overallbalance=$explodearray[0];
									if($overallpaid<($overallbalance+$moneytopay)){
										$moneytopay=$overallbalance-$overallpaid;
										if($moneytopay>$schedrow['amount']){
											$moneytopay=$schedrow['amount'];
										}
										if($money<$moneytopay){
											$moneytopay=$money;
										}
									}
					
									if($moneytopay>0){
										$money=$money-$moneytopay;
									mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$schedrow[sched_id]','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
									$paymentarray="$paymentarray<endline>Related Learning Experience (RLE)<->$moneytopay";
							}
							}	
					}
			}
 	}
 }

 $paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
$explodearray=explode("//", $paymentexplode);
$overallpaid=$explodearray[1];
$overallbalance=$explodearray[0];

 // //pay the trans/new student fees after schedule of fee  
 if($money>0 && ($statusrow['status']=="trans" || $statusrow['status']=="new")  && $overallpaid<$overallbalance){
 	//check if what are the trans/new student fees during this sy and  semester
 	$newpayment=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and sy='$sy' and semester='$semester' and payment_group='new' group by schedule_of_fees.payment_id");
 	while ($newpaymentrow=mysql_fetch_array($newpayment)){
 		
 		if($money>0  && $overallpaid<$overallbalance){
		 		//get the amount of every payment 
		 		$amountpaid=0;
		 		$getamount=mysql_query("select collection.amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and  stud_id='$stud_id' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and  remark='0' and schedule_of_fees.payment_id='$newpaymentrow[payment_id]'") or die(mysql_error());
		 		while ($amountrow=mysql_fetch_array($getamount)){
		 			$amountpaid=$amountpaid+$amountrow['amount'];
		 		}

		 		if($newpaymentrow['amount']>$amountpaid){
		 			$moneytopay=0;
		 			$moneytopay=$newpaymentrow['amount']-$amountpaid;
		 			if($money>$moneytopay){
 		 			}else{
		 				$moneytopay=$money;
 		 			}
		 			$paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
					$explodearray=explode("//", $paymentexplode);
					$overallpaid=$explodearray[1];
					$overallbalance=$explodearray[0];
					if($overallpaid<($overallbalance+$moneytopay)){
						$moneytopay=$overallbalance-$overallpaid;
						if($moneytopay>$newpaymentrow['amount']){
							$moneytopay=$newpaymentrow['amount'];
						}
						if($money<$moneytopay){
							$moneytopay=$money;
						}
					}
					
					if($moneytopay>0){
						$money=$money-$moneytopay;
					mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$newpaymentrow[sched_id]','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
					$paymentarray="$paymentarray<endline>$newpaymentrow[payment_desc]<->$moneytopay";
					}
		 		}

	 	}

 	}

 }
 $paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
$explodearray=explode("//", $paymentexplode);
$overallpaid=$explodearray[1];
$overallbalance=$explodearray[0];

  // //pay the graduation fees after new fees
 if($money>0 && $statusrow['status']=="grad"  && $overallpaid<$overallbalance){
 	//check if what are the trans/new student fees during this sy and  semester
 	$gradpayment=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and sy='$sy' and semester='0' and payment_group='grad' group by schedule_of_fees.payment_id");
 	while ($gradpaymentrow=mysql_fetch_array($gradpayment)){
 		if($money>0){
		 		//get the amount of every payment 
		 		$amountpaid=0;
		 		$getamount=mysql_query("select collection.amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and  stud_id='$stud_id' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='0' and  remark='0' and schedule_of_fees.payment_id='$gradpaymentrow[payment_id]'") or die(mysql_error());
		 		while ($amountrow=mysql_fetch_array($getamount)){
		 			$amountpaid=$amountpaid+$amountrow['amount'];
		 		}

		 		if($gradpaymentrow['amount']>$amountpaid  && $overallpaid<$overallbalance){
		 			$moneytopay=0;
		 			$moneytopay=$gradpaymentrow['amount']-$amountpaid;
		 			if($money>$moneytopay){
 		 			}else{
		 				$moneytopay=$money;
 		 			}
		 			$paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
					$explodearray=explode("//", $paymentexplode);
					$overallpaid=$explodearray[1];
					$overallbalance=$explodearray[0];
					if($overallpaid<($overallbalance+$moneytopay)){
						$moneytopay=$overallbalance-$overallpaid;
						if($moneytopay>$gradpaymentrow['amount']){
							$moneytopay=$gradpaymentrow['amount'];
						}
						if($money<$moneytopay){
							$moneytopay=$money;
						}
					}
					if($moneytopay>0){
						$money=$money-$moneytopay;
					mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$gradpaymentrow[sched_id]','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
					$paymentarray="$paymentarray<endline>$gradpaymentrow[payment_desc]<->$moneytopay";
					}
		 		}

	 	}

 	}

 }
 $paymentexplode=detectfullpayment($GLOBALS['course_id'],$GLOBALS['year_level'],$sy,$semester,$stud_id);
				$explodearray=explode("//", $paymentexplode);
				$overallpaid=$explodearray[1];
				$overallbalance=$explodearray[0];
 
?>
	<script type="text/javascript">
	window.open('printreceipt3.php?cash=<?=$change;?>&data=<?=$paymentarray;?>&name=<?=$statusrow[lname];?>, <?=$statusrow[fname];?> <?=$statusrow[acronym];?> <?=$statusrow[year_level];?>&date=<?=$date;?>&receipt_num=<?=$receipt;?>&overallpaid=<?=$overallpaid."--".$overallbalance;?>',"somewhere").focus();
	</script>
<?php
}else{
	echo "existed";
}
?>