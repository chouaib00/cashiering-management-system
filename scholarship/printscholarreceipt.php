<?php
session_start();
include '../dbconfig.php';
if($_SESSION['user_id']!="" && $_POST['stud_id']!=""){
$receipt_num=$_POST['receipt_num'];
$receipt=$_POST['receipt_num'];
$stud_id=$_POST['stud_id'];
$money=$_POST['amount'];
$money2=$_POST['amount'];
$cash=$money;
$sy=$_SESSION['sy2'];
$user_id=$_SESSION['user_id'];
$date=$_SESSION['date'];
$semester=$_SESSION['semester2'];
$paymentarray="pp";
$totalmiscpaid=0;

$or=mysql_query("select date from collection where receipt_num='$receipt_num' group by receipt_num limit 1") or die(mysql_error());
if(mysql_num_rows($or)==1){
echo "existed";
}else{
//get student status
$getstatus=mysql_query("select * from student_status,student,course where student.stud_id=student_status.stud_id and student_status.course_id=course.course_id and  student_status.stud_id='$stud_id' and semester='$_SESSION[semester2]' and sy='$_SESSION[sy2]'");
$statusrow=mysql_fetch_array($getstatus);
$year_level=$statusrow['year_level'];
 ///////////////////

////////////////////////////////////////////////////////////////////////////////////////////////////////
// save the remaining balance to miscellaneous

  if($money>0){
		$totalmiscpaid=0;
					//save payment to not fully paid misc

					$misc2=mysql_query("select schedule_of_fees.sched_id,schedule_of_fees.amount,collection.amount,schedule_of_fees.payment_id   from schedule_of_fees,paymentlist,collection  where schedule_of_fees.sched_id=collection.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='misc' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and course_id='$statusrow[course_id]' and ( year_level like '%&$statusrow[year_level]&%' or  year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]') group by schedule_of_fees.sched_id");
					
					while ($miscrow2=mysql_fetch_array($misc2)){
						if($money>0){								
							$misctotalpartial=0;
			 				$misc3=mysql_query("select collection.amount,schedule_of_fees.sched_id from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and  schedule_of_fees.payment_id='$miscrow2[payment_id]' and stud_id='$statusrow[stud_id]' and remark='0' and  schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and course_id='$statusrow[course_id]' and ( year_level like '%&$statusrow[year_level]&%' or  year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]')") or die(mysql_error());
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

				 					if($moneytopay>0){
				 					$totalmiscpaid=$totalmiscpaid+$moneytopay;
				 					$money=$money-$moneytopay;
								 	mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$miscrow2[0]','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
								 }
							}
						}
					}
					

				//save the other remaining money to MISCELLANEOUS that is not yet paid
				if($money>0){
 							$getnotpaidmisc=mysql_query("select schedule_of_fees.amount,schedule_of_fees.sched_id from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id not in (select payment_id from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and stud_id='$statusrow[stud_id]' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester')  and schedule_of_fees.sched_id not in (select schedule_of_fees.sched_id from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and  schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and course_id='$statusrow[course_id]' and ( year_level like '%&$statusrow[year_level]&%' or  year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]')) and  schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='misc' and course_id='$statusrow[course_id]' and ( year_level like '%&$statusrow[year_level]&%' or  year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]') and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' group by schedule_of_fees.sched_id") or die(mysql_error());
							while($notpaid=mysql_fetch_array($getnotpaidmisc)){
							 	if($money>0){	
							 		$moneytopay=$notpaid['amount'];					 	
							 		
									if($moneytopay<$money){
							 		}else{
							 			$moneytopay=$money;
							 		}

							 		if($moneytopay>0){
							 			$totalmiscpaid=$totalmiscpaid+$moneytopay;
							 			$money=$money-$moneytopay;
										mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$notpaid[sched_id]','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
									}
							 	}
							}
				}

				//save the other remaining money to MISCELLANEOUS that is not yet paid:posibilty-if shifted o other course and the remaining misc in that list at the last part is not enough so it will go black to the beginning
				// if($money>0){
				// 		$getnotpaidmisc=mysql_query("select schedule_of_fees.amount,schedule_of_fees.sched_id from schedule_of_fees,paymentlist  where schedule_of_fees.sched_id not in (select schedule_of_fees.sched_id from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and  schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and course_id='$statusrow[course_id]' and ( year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]')) and  schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='misc' and course_id='$statusrow[course_id]' and ( year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]') and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' group by schedule_of_fees.sched_id") or die(mysql_error());
				// 		 while($notpaid=mysql_fetch_array($getnotpaidmisc)){
				// 		 	if($money>0){	
				// 		 		$moneytopay=$notpaid['amount'];					 	
				// 		 		if($moneytopay<$money){
				// 		 		}else{
				// 		 			$moneytopay=$money;
				// 		 		}
						 		
				// 		 		if($moneytopay>0){
				// 		 			$totalmiscpaid=$totalmiscpaid+$moneytopay;
				// 		 			$money=$money-$moneytopay;
				// 				mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$notpaid[sched_id]','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
				// 			}
				// 		 	}
				// 	}
				// }
			
 }
   

//save the remaianing money to sched

if($money>0){
	//check the tuition
	$sched=mysql_query("select amount,sched_id,paymentlist.payment_id from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and (payment_group='sched' or payment_group='rle' or payment_group='misc') and course_id='$statusrow[course_id]' and (year_level like '%&$statusrow[year_level]&%' or year_level like '$statusrow[year_level]&%' or year_level like '%&$statusrow[year_level]') and sy='$sy' and semester='$semester' order by sched_id ") or die(mysql_error());
	while ($schedrow=mysql_fetch_array($sched)){
		$moneytopay=0;
		if($money>0){
			//get the amount paid in every sched
			$getamount=mysql_query("select SUM(amount) as amount from collection where stud_id='$stud_id' and sched_id='$schedrow[sched_id]'");
			$paidamount=mysql_fetch_array($getamount);

			if($schedrow['amount']>$paidamount['amount']){
				$moneytopay=$schedrow['amount']-$paidamount['amount'];
					
				if($moneytopay>$money){
					$moneytopay=$money;
				}
				

				if($moneytopay>0){
					$money=$money-$moneytopay;
					mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$schedrow[sched_id]','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
				}
			}
		}
	}

}

 // //pay the trans/new student fees after schedule of fee  
 if($money>0 && ($statusrow['status']=="trans" || $statusrow['status']=="new")){
 	//check if what are the trans/new student fees during this sy and  semester
 	$newpayment=mysql_query("select amount,sched_id,paymentlist.payment_id from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and sy='$sy' and semester='$semester' and payment_group='new' group by schedule_of_fees.payment_id");
 	while ($newpaymentrow=mysql_fetch_array($newpayment)){
 		
 		if($money>0){
		 		//get the amount of every payment 
		 		$amountpaid=0;
		 		$getamount=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and  stud_id='$stud_id' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and  remark='0' and schedule_of_fees.payment_id='$newpaymentrow[payment_id]'") or die(mysql_error());
		 		$amountrow=mysql_fetch_array($getamount);
		 		
		 		$amountpaid=$amountrow['amount'];		 		

		 		if($newpaymentrow['amount']>$amountpaid){
		 			$moneytopay=0;
		 			$moneytopay=$newpaymentrow['amount']-$amountpaid;
		 			if($money<$moneytopay){
		 				$moneytopay=$money;
 		 			}

					if($moneytopay>0){
						$money=$money-$moneytopay;
						mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$newpaymentrow[sched_id]','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
					}
		 		}

	 	}

 	}

 }



 // //graduation fees  
 if($money>0 && $statusrow['status']=="grad"){
 	//check if what are the trans/new student fees during this sy and  semester
 	$gradpayment=mysql_query("select  amount,sched_id,paymentlist.payment_id from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and sy='$sy' and semester='0' and payment_group='grad' group by schedule_of_fees.payment_id");
 	while ($newpaymentrow=mysql_fetch_array($gradpayment)){
 		
 		if($money>0){
		 		//get the amount of every payment 
		 		$amountpaid=0;
		 		$getamount=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and  stud_id='$stud_id' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='0' and  remark='0' and schedule_of_fees.payment_id='$newpaymentrow[payment_id]'") or die(mysql_error());
		 		$amountrow=mysql_fetch_array($getamount);
		 		
		 		$amountpaid=$amountrow['amount'];		 		

		 		if($newpaymentrow['amount']>$amountpaid){
		 			$moneytopay=0;
		 			$moneytopay=$newpaymentrow['amount']-$amountpaid;
		 			if($money<$moneytopay){
		 				$moneytopay=$money;
 		 			}
					if($moneytopay>0){
						$money=$money-$moneytopay;
						mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$newpaymentrow[sched_id]','$moneytopay','$_SESSION[sy]','$_SESSION[semester]','$user_id','0')") or die(mysql_error());
					}
		 		}

	 	}
	 }
}

$status=$statusrow['status'];
$year_level=$statusrow['year_level'];
$course_id=$statusrow['course_id'];
$semester2=$_SESSION['semester'];
$sy2=$_SESSION['sy2'];
$user_id=$_SESSION['user_id'];

function jakecornelia(){
	echo "string";
 	global $status;
	global $year_level;
	global $course_id;
 	global $semester;
	global $sy;
	global $user_id;
	global $sy2;
	global $semester2;
	global $sy;
	global $date;
	global $receipt;
	global $money;
	global $stud_id;
	  if($money>0){
		$totalmiscpaid=0; 

				//save the other remaining money to MISCELLANEOUS that is not yet paid

				if($money>0){

							$getnotpaidmisc=mysql_query("select sched_id,amount from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and payment_group='misc' and course_id='$course_id' and ( year_level like '%&$year_level&%' or  year_level like '$year_level&%' or year_level like '%&$year_level') and semester='$semester' and sy='$sy'") or die(mysql_error());
							while($notpaid=mysql_fetch_array($getnotpaidmisc)){
							 	if($money>0){	
							 		$moneytopay=$notpaid['amount'];					 	
							 		
									if($moneytopay<$money){
							 		}else{
							 			$moneytopay=$money;
							 		}

							 		if($moneytopay>0){
							 			$totalmiscpaid=$totalmiscpaid+$moneytopay;
							 			$money=$money-$moneytopay;
										mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$notpaid[sched_id]','$moneytopay','$sy2]','$semester2','$user_id','0')") or die(mysql_error());
									}
							 	}
							}
				}
 }
  

//save the remaianing money to sched

if($money>0){
	//check the tuition
	$sched=mysql_query("select  amount,sched_id from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and (payment_group='sched' or payment_group='rle' or payment_group='misc') and course_id='$course_id' and (year_level like '%&$year_level&%' or year_level like '$year_level&%' or year_level like '%&$year_level') and sy='$sy' and semester='$semester' order by sched_id ") or die(mysql_error());
	while ($schedrow=mysql_fetch_array($sched)){
		$moneytopay=0;
		if($money>0){	
				
				$moneytopay=$schedrow['amount'];
				if($moneytopay>$money){
					$moneytopay=$money;
				}
				

				if($moneytopay>0){
					$money=$money-$moneytopay;
					mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$schedrow[sched_id]','$moneytopay','$sy2]','$semester2','$user_id','0')") or die(mysql_error());
				}
			
		}
	}

}

 // //pay the trans/new student fees after schedule of fee  
 if($money>0 && ($status=="trans" || $status=="new")){
 	//check if what are the trans/new student fees during this sy and  semester
 	$newpayment=mysql_query("select amount,sched_id from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and sy='$sy' and semester='$semester' and payment_group='new' group by schedule_of_fees.payment_id");
 	while ($newpaymentrow=mysql_fetch_array($newpayment)){
 		
 		if($money>0){		

	 			$moneytopay=0;
	 			$moneytopay=$newpaymentrow['amount'];
	 			if($money<$moneytopay){
	 				$moneytopay=$money;
		 			}

				if($moneytopay>0){
					$money=$money-$moneytopay;
					mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$newpaymentrow[sched_id]','$moneytopay','$sy2]','$semester2','$user_id','0')") or die(mysql_error());
				}	 	

	 	}

 	}

 }



 // //graduation fees  
 if($money>0 && $status=="grad"){
 	//check if what are the trans/new student fees during this sy and  semester
 	$gradpayment=mysql_query("select  amount,sched_id from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and sy='$sy' and semester='0' and payment_group='grad' group by schedule_of_fees.payment_id");
 	while ($newpaymentrow=mysql_fetch_array($gradpayment)){
 		
 		if($money>0){
		 		//get the amount of every payment 
		 		$getamount=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and  stud_id='$stud_id' and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='0' and  remark='0' and schedule_of_fees.payment_id='$newpaymentrow[payment_id]'") or die(mysql_error());
		 		$amountrow=mysql_fetch_array($getamount);		 		
		 			$moneytopay=0;
		 			$moneytopay=$newpaymentrow['amount'];
		 			if($money<$moneytopay){
		 				$moneytopay=$money;
 		 			}
					if($moneytopay>0){
						$money=$money-$moneytopay;
						mysql_query("insert into collection values ('','$date','$receipt','$stud_id','$newpaymentrow[sched_id]','$moneytopay','$sy2]','$semester2','$user_id','0')") or die(mysql_error());
					}
	 	}
	 }
}
	if($money>0){
		jakecornelia();
	}
} //end of the funciton 
if($money>0){
	jakecornelia();
}
//////////////////////
// include '../student/balancedetection.php';
// if(moneytobepaidpersemester()<moneypaidpersemester()){
// 	$money_exceeded=moneypaidpersemester()-moneytobepaidpersemester();
// 	mysql_query("insert into exceeded_money values ('','$stud_id','','$receipt','$money_exceeded','$sy','$semester','','','')") or die(mysql_error());

// }

//get all payment in not a misc
$paymentarray="";
 $getpayment=mysql_query("select SUM(collection.amount) as amount,payment_desc from collection,schedule_of_fees,paymentlist where collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and payment_group!='misc' and receipt_num='$receipt' and stud_id='$stud_id' group by schedule_of_fees.payment_id") or die(mysql_error());
 while($row=mysql_fetch_array($getpayment)){
 	$paymentarray="$paymentarray<endline>$row[payment_desc]<->$row[amount]";
 }
 
$totalmisc=0;
  $getpayment=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees,paymentlist where collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and payment_group='misc' and receipt_num='$receipt' and stud_id='$stud_id' group by schedule_of_fees.payment_id") or die(mysql_error());
 while($row=mysql_fetch_array($getpayment)){
 	$totalmisc=$totalmisc+$row['amount'];
 }
 $paymentarray="$paymentarray<endline>Miscellaneous<->$totalmisc";


	//update the scholar_printed to printed
	mysql_query("update student_status set scholar_printed='1' where stud_id='$stud_id' and sy='$sy' and semester='$semester'");
	?>
	<script type="text/javascript">
	window.open('printreceipt.php?data=<?=$paymentarray;?>&name=<?=$statusrow[lname];?>, <?=$statusrow[fname];?> <?=$statusrow[acronym];?> <?=$statusrow[year_level];?>&date=<?=$date;?>&cash=<?=$cash;?>',"<?php echo date('hisa') ?>").focus().height(400);
	</script>
	<?php
	}
  }else{
	header("location:../index.php");
}
?>