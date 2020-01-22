<?php
session_start();
include 'numbertoword.php';
?>
<meta charset="utf-8">
<style type="text/css">
@media print { 
*{font-size:10px;}
}
*{margin:0;padding:0;font-family:tahoma,verdana,arial;}
#listcon {height:140px; display:block;width:inherit;position:relative;top:30px;}
#numbertoword{width:280px;min-height:50px;padding:0 10px 0 10px;position:relative;top:5px;text-indent:100px}
</style>
<?php
$sy=$_SESSION['sy'];
$semester=$_SESSION['semester'];
$stud_name=$_REQUEST['name'];
$date=$_REQUEST['date'];
$cash=$_REQUEST['cash'];

 $or=$_REQUEST['or'];
 $stud_id=$_REQUEST['stud_id'];
?>
<title>Certification</title>
<div style="margin-left:20px;position:relative;width:3.9in;padding:0.7in 0 0 0">
	<p  style="padding-left:0.3in"><?=$date;?><br><?php echo date('h:i a') ;?></p> <br>
	<div style="width:2.5in;padding-left:0.3in;text-transform:capitalize"><?=$stud_name;?> <?=$sy." ".$semester;?></div>
	
	 <?php
	 include 'dbconfig.php';
	 $getcert=mysql_query("select * from schedule_of_fees,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and payment_desc='Certification' and sy='$_SESSION[sy]' and semester='$_SESSION[semester]'");
	 $certrow=mysql_fetch_array($getcert);
	 $change=$cash-$certrow['amount'];
	 ?>
	<div id="listcon">
	 
			<div class="list" style="width:250px; padding:0 10px 0 5px">
				<div style="display:inline-block;  width:1.8in; height:15px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis">Certification</div>
				<div style="height:15px;text-align:right; width:70px;float:right"><?=number_format($certrow['amount'],2);?></div>
				<div style="clear:both"></div>
			</div>
 
		

	</div>
	<div id="total" style="position:relative;left:230px"><?=number_format($certrow['amount'],2);?></div>
 
	<div id="numbertoword"> <?=receivenumber($certrow['amount']);?> only</div>
	<div style="padding-left:5px">
		Amount received: <?=number_format($cash,2);?><br>
		Change: <?=number_format($cash-$certrow['amount'],2);?><br>
	</div>
</div>

 <?php
 $date=date('m/d/Y');
 $check=mysql_query("select * from collection,schedule_of_fees,paymentlist where collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.payment_id=paymentlist.payment_id and payment_desc='Certification' and collection.sy='$sy' and collection.semester='$semester' and stud_id='$stud_id' and date='$date' and receipt_num='$or'");
 if(mysql_num_rows($check)>0){

 }else{
mysql_query("insert into collection values('','$date','$or','$stud_id','$certrow[sched_id]','$certrow[amount]','$sy','$semester','$_SESSION[user_id]','0')") or die(mysql_error());
}
 ?>
 
<script type="text/javascript">
	window.print();	 
	setTimeout(function(){

	window.close();
	},1000);
</script>
 