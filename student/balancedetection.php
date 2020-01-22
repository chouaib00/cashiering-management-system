 <?php
 function moneytobepaidpersemester(){
		global $course_id;
		global $year_level;
		global $sy;
		global $semester;
		global $stud_id;
		global $status;
 		$moneytobepaidpersemester=0;
		
		//check the tuition
		$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and (payment_group='sched' or payment_group='rle' or payment_group='misc') and course_id='$course_id' and (year_level like '%&$year_level&%' or year_level like '$year_level&%' or year_level like '%&$year_level') and sy='$sy' and semester='$semester' ") or die(mysql_error());
		while ($schedrow=mysql_fetch_array($sched)){
			$moneytobepaidpersemester=$moneytobepaidpersemester+$schedrow['amount'];

		}
	 

		// //check the graduation fees
		if($status=="grad"){
 			$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='grad' and (year_level like '%&IV&%' or year_level like 'IV&%' or year_level like '%&IV') and sy='$sy'") or die(mysql_error());
			while ($schedrow=mysql_fetch_array($sched)){
				$moneytobepaidpersemester=$moneytobepaidpersemester+$schedrow['amount'];
				
			}
		}

		// //check the new/trans student fees//////////////////////
			if($status=="trans" || $status=="new"){		
				$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='new' and (year_level like '%&I&%' or year_level like 'I&%' or year_level like '%&I') and sy='$sy' and semester='$semester'") or die(mysql_error());
			 
				while ($schedrow=mysql_fetch_array($sched)) {
					$moneytobepaidpersemester=$moneytobepaidpersemester+$schedrow['amount'];
					
				}
			} 
		 
		return $moneytobepaidpersemester;
	}

	 function moneypaidpersemester(){
		global $sy;
		global $semester;
		global $stud_id;
		$jake=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees,paymentlist where  schedule_of_fees.sched_id=collection.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and (payment_group='misc' or payment_group='sched' or payment_group='misc' or payment_group='rle'  or payment_group='grad' or payment_group='new') and stud_id='$stud_id' and  schedule_of_fees.sy='$sy' and (schedule_of_fees.semester='$semester' or schedule_of_fees.semester='0')") or die(mysql_error());
 		$countme=mysql_fetch_array($jake);
		 
		$getadvancepayment=mysql_query("select SUM(amount) as amount from exceeded_money where stud_id='$stud_id' and to_semester='$semester' and to_sy='$sy' and action='Advance Payment'");
 		$amountadvancepayment=mysql_fetch_array($getadvancepayment);
		return $countme['amount']+$amountadvancepayment['amount'];
	} 
?>