<?php
session_start();
include '../dbconfig.php';
include 'detectfullpayment.php';
$sy=$_POST['sy'];
$semester=$_POST['semester'];
$from_stud_id=$_POST['from_stud_id'];
$to_stud_id=$_POST['to_stud_id'];
$receipt_num=$_POST['receipt_num'];
$totalexceeded=0;
 if($semester!=""){

//get from
$from=mysql_query("select * from student,student_status where student.stud_id=student_status.stud_id and sy='$sy' and semester='$semester'  and student.stud_id='$from_stud_id'") or die(mysql_error());
$fromstud=mysql_fetch_array($from);

$to=mysql_query("select * from student where stud_id='$to_stud_id'");
$tostud=mysql_fetch_array($to);

//get the receipt of the exceeded money

$receipt=mysql_query("select * from exceeded_money where stud_id='$from_stud_id' and from_sy='$sy' and from_semester='$semester' and receipt_num='$receipt_num'");	
$receiptrow=mysql_fetch_array($receipt);
$exceeded_money=$receiptrow['amount'];
$date="";
// echo "$exceeded_money $receiptrow[receipt_num]";
//get what has been paid

$check=0;
$getpaidpayment=mysql_query("select schedule_of_fees.payment_id,col_id,date,user_id,collection.sched_id, schedule_of_fees.amount as totaltobepaid,collection.amount as totalpaid from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and stud_id='$from_stud_id' and receipt_num='$receiptrow[receipt_num]' group by schedule_of_fees.payment_id");
$date=123;				

while ($paidrow=mysql_fetch_array($getpaidpayment)) {
	
	//GET TOTAL AMOUNT OF EVERY PAYMENT
	$totalpaidpersched=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and stud_id='$from_stud_id' and schedule_of_fees.payment_id='$paidrow[payment_id]'");
	$totalpaidrow=mysql_fetch_array($totalpaidpersched);

	//GET TOTAL AMOUNT from the other receipt
	$otherpaid=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester' and stud_id='$from_stud_id' and schedule_of_fees.payment_id='$paidrow[payment_id]' and receipt_num!='$receipt_num'");
	$otherpaidrow=mysql_fetch_array($otherpaid);
	if(mysql_num_rows($otherpaid)==0){
		$otherpaidrow['amount']=0;
	}
	//get the normal payment
	$normalpayment=mysql_query("select * from schedule_of_fees where sy='$sy' and semester='$semester' and course_id='$fromstud[course_id]' and payment_id='$paidrow[payment_id]'") or die(mysql_error());
	$normalpaid=mysql_fetch_array($normalpayment);

	if($totalpaidrow['amount']>$normalpaid['amount']){

		$exceeded=$totalpaidrow['amount']-$paidrow['totaltobepaid'];		
		$balance=$normalpaid['amount']-$otherpaidrow['amount'];
		$totalexceeded+=$balance;	
		mysql_query("delete from collection where stud_id='$from_stud_id' and receipt_num='$receipt_num' and col_id='$paidrow[col_id]'") or die(mysql_error());
		mysql_query("insert into collection values ('','$paidrow[date]','$receipt_num','$from_stud_id','$paidrow[sched_id]','$balance','$sy','$semester','$paidrow[user_id]','0')") or die(mysql_error());

		$check=1;
	}


}
echo "total paid exceeed $totalexceeded";
$money=$exceeded_money;
 // include "savetransferexceedmoneytootherstudent.php";
 
}else{
	header("location:index.php");
}

?>saf