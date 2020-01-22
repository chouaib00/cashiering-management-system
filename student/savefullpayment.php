<?php
session_start();
//course is not set in every misc payment to change
include '../dbconfig.php';
$user_id=$_SESSION['user_id'];
$sy=$_POST['sy'];
$semester=$_POST['semester'];
$date=$_SESSION['date'];
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
	if($stud_id==""){
		header("location:index.php");
	}
	//scholar
	if($scholar_id>0){
	mysql_query("update student_status set scholar_id='$scholar_id' WHERE stud_id='$stud_id' and sy='$sy' and semester='$semester' ") or die(mysql_error());
	}


$getstatus=mysql_query("select * from student_status,student,course where course.course_id=student_status.course_id and student_status.stud_id=student.stud_id and  student_status.stud_id='$stud_id' and sy='$sy' and semester='$semester' order by stat_id desc") or die(mysql_error());
$statusrow=mysql_fetch_array($getstatus);
$status=$statusrow['status'];
$year_level=$statusrow['year_level'];
$course_id=$statusrow['course_id'];

$GLOBALS['course_id'] = $statusrow['course_id'];
$GLOBALS['year_level'] = $statusrow['year_level'];
$GLOBALS['status'] = $statusrow['status'];
include 'balancedetection.php';


 while ($start<$paymentdatalen) {
		
	$paymentdata2=$paymentdataarr[$start];
	$paymentdata2arr=explode("<->", $paymentdata2);
	$payment_id=$paymentdata2arr[0];
	$amount=$paymentdata2arr[1];

	if($payment_id=="misc"){
			$totalmiscpaid=0;
			if(moneytobepaidpersemester()>moneypaidpersemester()){
					//save payment to not fully paid misc
					$misc2=mysql_query("select schedule_of_fees.sched_id,schedule_of_fees.amount,collection.amount,schedule_of_fees.payment_id   from schedule_of_fees,paymentlist,collection  where schedule_of_fees.sched_id=collection.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='misc' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and course_id='$statusrow[course_id]' and ( year_level like '%&$statusrow[year_level]&%' or year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]') group by schedule_of_fees.payment_id");
					
					while ($miscrow2=mysql_fetch_array($misc2)){
						if($money>0 && moneytobepaidpersemester()>moneypaidpersemester()){								
							$misctotalpartial=0;
			 				$misc3=mysql_query("select collection.amount,schedule_of_fees.sched_id from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and  schedule_of_fees.sched_id='$miscrow2[sched_id]' and stud_id='$statusrow[stud_id]' and remark='0' and  schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and course_id='$statusrow[course_id]' and ( year_level like '%&$statusrow[year_level]&%' or year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]')") or die(mysql_error());
							while ($miscrow3=mysql_fetch_array($misc3)) {
								$misctotalpartial=$misctotalpartial+$miscrow3['amount'];
							}
							 if($misctotalpartial<$miscrow2[1]){
									$remainingamount1=0;
				 					$amount2=0;
									$moneytopay=$miscrow2[1]-$misctotalpartial;
				 					if($moneytopay<$money){
				 					}else{
				 						$moneytopay=$money;
				 					}
				 					
									if(moneytobepaidpersemester()<(moneypaidpersemester()+$moneytopay)){
										$moneytopay=moneytobepaidpersemester()-moneypaidpersemester();
										if($moneytopay>$miscrow2['amount']){
											$moneytopay=$miscrow2['amount'];
										}
									}
				 					if($moneytopay>0){
				 					$totalmiscpaid=$totalmiscpaid+$moneytopay;
				 					$money=$money-$moneytopay;
								 	mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$miscrow2[0]','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
								 }
							}
						}
					}
					

				//save the other remaining money to MISCELLANEOUS that is not yet paid
				if($amount>0 && moneytobepaidpersemester()>moneypaidpersemester()){
							$getnotpaidmisc=mysql_query("select schedule_of_fees.amount,schedule_of_fees.sched_id from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id not in (select payment_id from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and stud_id='$statusrow[stud_id]' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester')  and schedule_of_fees.sched_id not in (select schedule_of_fees.sched_id from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and  schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and course_id='$statusrow[course_id]' and ( year_level like '%&$statusrow[year_level]&%' or year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]')) and  schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='misc' and course_id='$statusrow[course_id]' and ( year_level like '%&$statusrow[year_level]&%' or year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]') and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' group by schedule_of_fees.sched_id") or die(mysql_error());
							while($notpaid=mysql_fetch_array($getnotpaidmisc)){
							 	if($money>0 && moneytobepaidpersemester()>moneypaidpersemester()){	
							 		$moneytopay=$notpaid['amount'];					 	
							 		if($moneytopay<$money){
							 		}else{
							 			$moneytopay=$money;
							 		}
							 		
									if(moneytobepaidpersemester()<(moneypaidpersemester()+$moneytopay)){
										$moneytopay=moneytobepaidpersemester()-moneypaidpersemester();
										if($moneytopay>$notpaid['amount']){
											$moneytopay=$notpaid['amount'];
										}
									}
							 		if($moneytopay>0){
							 			$totalmiscpaid=$totalmiscpaid+$moneytopay;
							 			$money=$money-$moneytopay;
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
			}
			$moneytopay=$amount;


				//get the normal amount

				$getnorm=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and  sched_id='$payment_id'");
				$normalamount=mysql_fetch_array($getnorm);

				if($normalamount['payment_group']=="other" || $normalamount['payment_group']=="othermisc"){
				
					mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$payment_id','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
					$money=$money-$moneytopay;
		 			 

		 			$paymentarray="$paymentarray<endline>$normalamount[payment_desc]<->$moneytopay";
				}else{


					if(moneytobepaidpersemester()<(moneypaidpersemester()+$moneytopay)){
						$moneytopay=moneytobepaidpersemester()-moneypaidpersemester();
						if($moneytopay>$normalamount['amount']){
							$moneytopay=$normalamount['amount'];
						}
					}

					if($moneytopay>0){			
						mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$payment_id','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
						$money=$money-$moneytopay;
 

			 			$paymentarray="$paymentarray<endline>$normalamount[payment_desc]<->$moneytopay";
		 			}
				}

			 	
 			

		}	
	}
	$start++;
}

 


 
////////////////////////////////////////////////////////////////////////////////////////////////////////
// save the remaining balance to miscellaneous

  if($money>0 && moneytobepaidpersemester()>moneypaidpersemester()){
  		$totalmiscpaid=0;
			if(moneytobepaidpersemester()>moneypaidpersemester()){
					//save payment to not fully paid misc
					$misc2=mysql_query("select schedule_of_fees.sched_id,schedule_of_fees.amount,collection.amount,schedule_of_fees.payment_id   from schedule_of_fees,paymentlist,collection  where schedule_of_fees.sched_id=collection.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='misc' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and course_id='$statusrow[course_id]' and ( year_level like '%&$statusrow[year_level]&%' or year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]') group by schedule_of_fees.sched_id");
					
					while ($miscrow2=mysql_fetch_array($misc2)){
						if($money>0 && moneytobepaidpersemester()>moneypaidpersemester()){								
							$misctotalpartial=0;
			 				$misc3=mysql_query("select collection.amount,schedule_of_fees.sched_id from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and  schedule_of_fees.sched_id='$miscrow2[sched_id]' and stud_id='$statusrow[stud_id]' and remark='0' and  schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester'") or die(mysql_error());
							while ($miscrow3=mysql_fetch_array($misc3)) {
								$misctotalpartial=$misctotalpartial+$miscrow3['amount'];
							}
							 if($misctotalpartial<$miscrow2[1]){
									$remainingamount1=0;
				 					$amount2=0;
									$moneytopay=$miscrow2[1]-$misctotalpartial;
				 					if($moneytopay<$money){
				 					}else{
				 						$moneytopay=$money;
				 					}
				 					
									if(moneytobepaidpersemester()<(moneypaidpersemester()+$moneytopay)){
										$moneytopay=moneytobepaidpersemester()-moneypaidpersemester();
										if($moneytopay>$miscrow2[1]){
											$moneytopay=$miscrow2[1];
										}
									}
				 					if($moneytopay>0){
				 					$totalmiscpaid=$totalmiscpaid+$moneytopay;
				 					$money=$money-$moneytopay;
								 	mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$miscrow2[0]','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
								 }
							}
						}
					}
					

				//save the other remaining money to MISCELLANEOUS that is not yet paid

				if($money>0 && moneytobepaidpersemester()>moneypaidpersemester()){
							$getnotpaidmisc=mysql_query("select schedule_of_fees.amount,schedule_of_fees.sched_id from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id not in (select payment_id from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and stud_id='$statusrow[stud_id]' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester')  and schedule_of_fees.sched_id not in (select schedule_of_fees.sched_id from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and  schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and course_id='$statusrow[course_id]' and ( year_level like '%&$statusrow[year_level]&%' or year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]')) and  schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='misc' and course_id='$statusrow[course_id]' and ( year_level like '%&$statusrow[year_level]&%' or year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]') and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' group by schedule_of_fees.sched_id") or die(mysql_error());
							while($notpaid=mysql_fetch_array($getnotpaidmisc)){
							 	if($money>0 && moneytobepaidpersemester()>moneypaidpersemester()){	
							 		$moneytopay=$notpaid['amount'];					 	
							 		if($moneytopay<$money){
							 		}else{
							 			$moneytopay=$money;
							 		}
							 		
									if(moneytobepaidpersemester()<(moneypaidpersemester()+$moneytopay)){
										$moneytopay=moneytobepaidpersemester()-moneypaidpersemester();
										if($moneytopay>$notpaid['amount']){
											$moneytopay=$notpaid['amount'];
										}
									}
							 		if($moneytopay>0){
							 			$totalmiscpaid=$totalmiscpaid+$moneytopay;
							 			$money=$money-$moneytopay;
										mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$notpaid[sched_id]','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
									}
							 	}
							}
				}

				 
			}
			if($totalmiscpaid>0){
				$paymentarray="$paymentarray<endline>Miscellaneous<->$totalmiscpaid";
			}	
 }
   

//save the remaianing money to sched

if($money>0 && moneytobepaidpersemester()>moneypaidpersemester()){
	
	//check the tuition
	$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and (payment_group='sched' or payment_group='rle') and course_id='$course_id' and ( year_level like '%&$year_level&%' or year_level like '$year_level&%' or year_level like '%&$year_level') and sy='$sy' and semester='$semester' order by sched_id ") or die(mysql_error());
	while ($schedrow=mysql_fetch_array($sched)){
		$moneytopay=0;
		if($money>0 && moneytobepaidpersemester()>moneypaidpersemester()){
			//get the amount paid in every sched
			$getamount=mysql_query("select SUM(amount) as amount from collection where stud_id='$stud_id' and sched_id='$schedrow[sched_id]'");
			$paidamount=mysql_fetch_array($getamount);

			if($schedrow['amount']>$paidamount['amount']){
				$moneytopay=$schedrow['amount']-$paidamount['amount'];
				
				if($moneytopay>$money){
					$moneytopay=$money;
				}
				if(moneytobepaidpersemester()<(moneypaidpersemester()+$moneytopay)){
					$moneytopay=moneytobepaidpersemester()-moneypaidpersemester();
					if($moneytopay>$schedrow['amount']){
						$moneytopay=$schedrow['amount'];
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

 // //pay the trans/new student fees after schedule of fee  
 if($money>0 && ($statusrow['status']=="trans" || $statusrow['status']=="new")  && moneytobepaidpersemester()>moneypaidpersemester()){
  	//check if what are the trans/new student fees during this sy and  semester
 	$newpayment=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and sy='$sy' and semester='$semester' and payment_group='new' group by schedule_of_fees.payment_id");
 	while ($newpaymentrow=mysql_fetch_array($newpayment)){
 		
 		if($money>0 && moneytobepaidpersemester()>moneypaidpersemester()){
		 		//get the amount of every payment 
		 		$amountpaid=0;
		 		$getamount=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and  stud_id='$stud_id' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and  remark='0' and schedule_of_fees.sched_id='$newpaymentrow[sched_id]'") or die(mysql_error());
		 		$amountrow=mysql_fetch_array($getamount);
		 		
		 		$amountpaid=$amountrow['amount'];		 		

		 		if($newpaymentrow['amount']>$amountpaid){
		 			$moneytopay=0;
		 			$moneytopay=$newpaymentrow['amount']-$amountpaid;
		 			if($money<$moneytopay){
		 				$moneytopay=$money;
 		 			}

		 			if(moneytobepaidpersemester()<(moneypaidpersemester()+$moneytopay)){
						$moneytopay=moneytobepaidpersemester()-moneypaidpersemester();
						if($moneytopay>$newpaymentrow['amount']){
							$moneytopay=$newpaymentrow['amount'];
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



 // //graduation fees  
 if($money>0 && $statusrow['status']=="grad"  && moneytobepaidpersemester()>moneypaidpersemester()){
 	//check if what are the trans/new student fees during this sy and  semester
 	$gradpayment=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and sy='$sy' and semester='0' and payment_group='grad' group by schedule_of_fees.payment_id");
 	while ($newpaymentrow=mysql_fetch_array($gradpayment)){
 		
 		if($money>0 && moneytobepaidpersemester()>moneypaidpersemester()){
		 		//get the amount of every payment 
		 		$amountpaid=0;
		 		$getamount=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and  stud_id='$stud_id' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='0' and  remark='0' and schedule_of_fees.sched_id='$newpaymentrow[sched_id]'") or die(mysql_error());
		 		$amountrow=mysql_fetch_array($getamount);
		 		
		 		$amountpaid=$amountrow['amount'];		 		

		 		if($newpaymentrow['amount']>$amountpaid){
		 			// echo "ASF $newpaymentrow[payment_desc] $amountpaid gh";
		 			$moneytopay=0;
		 			$moneytopay=$newpaymentrow['amount']-$amountpaid;
		 			if($money<$moneytopay){
		 				$moneytopay=$money;
 		 			}

		 			if(moneytobepaidpersemester()<(moneypaidpersemester()+$moneytopay)){
						$moneytopay=moneytobepaidpersemester()-moneypaidpersemester();
						if($moneytopay>$newpaymentrow['amount']){
							$moneytopay=$newpaymentrow['amount'];
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


 
?>
	<script type="text/javascript">
	window.open('printreceipt3.php?cash=<?=$change;?>&data=<?=$paymentarray;?>&name=<?=$statusrow[lname];?>, <?=$statusrow[fname];?> <?=$statusrow[acronym];?> <?=$statusrow[year_level];?>&date=<?=$date;?>&receipt_num=<?=$receipt;?>',"somewhere").focus();
	</script>
<?php
}else{
	echo "existed";
}
?>