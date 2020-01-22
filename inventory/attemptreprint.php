<?php
include '../dbconfig.php';
$currentor=$_POST['current_or'];
$newor=$_POST['newor'];
$countor=0;
if($currentor!=$newor){
$checkor=mysql_query("select receipt_num from collection where receipt_num='$newor' group by receipt_num limit 1");
	if(mysql_num_rows($checkor)>0){
	$countor=1;
	}
}
if($countor==0){

$getordatail=mysql_query("select date,fname,lname,collection.stud_id,schedule_of_fees.sy,schedule_of_fees.semester from collection,student,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and student.stud_id=collection.stud_id and receipt_num='$currentor'") or die(mysql_error());
$statusrow=mysql_fetch_array($getordatail);
$getcourse=mysql_query("select * from student_status,course where course.course_id=student_status.course_id and stud_id='$statusrow[stud_id]' and sy='$statusrow[sy]' and semester='$statusrow[semester]' ") or die(mysql_error());
$courserow=mysql_fetch_array($getcourse);
$date=date('m/d/Y');
 
//get payments
$paymentarray="";
 $getpayment=mysql_query("select payment_desc,collection.amount from collection,schedule_of_fees,paymentlist where collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and payment_group!='misc' and receipt_num='$currentor'");
while ($row=mysql_fetch_array($getpayment)){
	$paymentarray="$paymentarray<endline>".$row['payment_desc']."<->".$row['amount'];
 }

$getpayment=mysql_query("select payment_desc,SUM(collection.amount) as amount from collection,schedule_of_fees,paymentlist where collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and payment_group='misc' and receipt_num='$currentor' limit 1");
$row=mysql_fetch_array($getpayment);

if($row['amount']!=""){
	 
		$paymentarray=$paymentarray."<endline>Miscellaneous"."<->".$row['amount'];
	 
}
 

mysql_query("update collection set receipt_num='$newor' where receipt_num='$currentor'");
?>
<script>
	window.open('inventory/repreprintreceipt.php?data=<?=$paymentarray;?>&name=<?=$statusrow[lname];?>, <?=$statusrow[fname];?> <?=$courserow[acronym];?> <?=$courserow[year_level];?>&date=<?=$statusrow[date] ;?>',"<?php echo date('his');?>").focus();
	
</script>
<?php
}else{
	echo "existed";
}
?>
