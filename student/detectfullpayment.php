 <?php
 function detectfullpayment($course_id,$year_level,$sy,$semester,$stud_id){
		$balancepersemester=0;
		$moneytobepaidpersemester=0;
		$moneypaidpersemester=0;
		
		//check the tuition
		$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='sched' and course_id='$course_id' and (year_level like '$year_level&%' or year_level like '%&$year_level') and sy='$sy' and semester='$semester' ") or die(mysql_error());
		while ($schedrow=mysql_fetch_array($sched)){

			$moneytobepaidpersemester=$moneytobepaidpersemester+$schedrow['amount'];
			$bal=mysql_query("select collection.amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and  schedule_of_fees.payment_id='$schedrow[payment_id]' and stud_id='$stud_id' and remark='0' and   schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester'") or die(mysql_error());
			
			while ($balrow=mysql_fetch_array($bal)){
					$moneypaidpersemester=$moneypaidpersemester+$balrow['amount'];
				}
		}
		//check the miscellaneous
		$misc=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='misc' and course_id='$course_id' and ( year_level like '$year_level&%' or year_level like '%&$year_level') and sy='$sy' and semester='$semester' ") or die(mysql_error());
		while ($miscrow=mysql_fetch_array($misc)) {
			$moneytobepaidpersemester=$moneytobepaidpersemester+$miscrow['amount'];
			 $bal=mysql_query("select collection.amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and  schedule_of_fees.payment_id='$miscrow[payment_id]' and stud_id='$stud_id' and remark='0' and  schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester'") or die(mysql_error());
			while ($balrow=mysql_fetch_array($bal)){
				$moneypaidpersemester=$moneypaidpersemester+$balrow['amount'];
			}
		 }

		 //check the rle
		$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='rle' and course_id='$course_id' and (year_level like '$year_level&%' or year_level like '%&$year_level') and sy='$sy' and semester='$semester' ") or die(mysql_error());
		while ($schedrow=mysql_fetch_array($sched)) {
			$moneytobepaidpersemester=$moneytobepaidpersemester+$schedrow['amount'];
			 $bal=mysql_query("select collection.amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and  schedule_of_fees.payment_id='$schedrow[payment_id]' and stud_id='$stud_id' and  remark='0' and  schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester'") or die(mysql_error());
			while ($balrow=mysql_fetch_array($bal)) {
					$moneypaidpersemester=$moneypaidpersemester+$balrow['amount'];
				}
		}

		// //check the graduation fees
		if($GLOBALS['status']=="grad"){
 			$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='grad' and (year_level like 'IV&%' or year_level like '%&IV') and sy='$sy'") or die(mysql_error());
			while ($schedrow=mysql_fetch_array($sched)) {
				$moneytobepaidpersemester=$moneytobepaidpersemester+$schedrow['amount'];
				$bal=mysql_query("select * from collection where sched_id='$schedrow[sched_id]'  and remark='0' and stud_id='$stud_id'") or die(mysql_error());
	 			while ($balrow=mysql_fetch_array($bal)) {
					$moneypaidpersemester=$moneypaidpersemester+$balrow['amount'];
				}
			}
		}

		// //check the new/trans student fees//////////////////////
			if($GLOBALS['status']=="trans" || $GLOBALS['status']=="new"){		
				$sched=mysql_query("select * from schedule_of_fees,paymentlist  where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='new' and (year_level like 'I&%' or year_level like '%&I') and sy='$sy' and semester='$semester'") or die(mysql_error());
				$status=$GLOBALS['status'];
				if($status=="new" or $status=="trans"){
					$year="I";
				}else{
					$year=$studrow['year_level'];
				}
				while ($schedrow=mysql_fetch_array($sched)) {
					$moneytobepaidpersemester=$moneytobepaidpersemester+$schedrow['amount'];
					$bal=mysql_query("select * from collection where sched_id='$schedrow[sched_id]' and remark='0' and stud_id='$stud_id'") or die(mysql_error());
					while ($balrow=mysql_fetch_array($bal)) {
						$moneypaidpersemester=$moneypaidpersemester+$balrow['amount'];
					}
				}
			} 
		 
		return $moneytobepaidpersemester."//".$moneypaidpersemester;
	} 
	?>