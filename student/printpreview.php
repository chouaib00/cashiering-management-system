<?php
session_start();
include '../dbconfig.php';

// $stud_id=$_POST['stud_id'];
$sy=$_REQUEST['sy'];
$semester=$_REQUEST['semester'];
$stud_id=$_REQUEST['stud_id'];
$student=mysql_query("select * from student,course,student_status where course.course_id=student_status.course_id and  student.stud_id=student_status.stud_id and student.stud_id='$stud_id' and student_status.sy='$sy' and semester='$semester' order by stat_id desc") or die(mysql_error());
$studentrow=mysql_fetch_array($student);
?>
<title>&nbsp; &nbsp;</title>
<meta charset="utf-8">
<style type="text/css">
	#statementcon {font-family:tahoma;font-size:15px;position: relative;width:8in;margin:0 auto;margin-top:0;margin-bottom:1in;}
	table {font-size:15px;}
	#ssy,#ssemester,#searchstate {padding:1px;}
@media print { 
*{font-size:13px;line-height:14px}
}
</style>

<div id="statementcon">

<div id="sheader" style="text-align:center;position:relative">
<img src="../img/norsulogo.png" height="60px" style="position:absolute;top:-9px;left:160px">
	Republic of the Philippines<br>
	NEGROS ORIENTAL STATE UNIVERSITY<br>
	Bayawan-Sta.Catalina Campus<br>
	Bawayan City.
</div>
<?php
	$displaysemester="";
	if($semester=="I"){
		$displaysemester="1st";
	}else{
		$displaysemester="2nd";
	}
?>
<p style="margin-top:0.5in;margin-bottom:0.2in;text-indent:25px;padding:0 1in 0 1in;">This is to certify that <span style="font-weight:bold;text-transform:capitalize"><?=$studentrow['fname']." ".$studentrow['lname'];?></span>, a <?=$studentrow['description'];?> has the following
fees for <?=$displaysemester;?> semester of A.Y. <?=$sy;?>.
</p>
	<?php
		$getpayment=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and schedule_of_fees.course_id='$studentrow[course_id]' and (year_level like '%&$studentrow[year_level]&%' or year_level like '$studentrow[year_level]&%' or year_level like '%&$studentrow[year_level]') and paymentlist.payment_group='sched' and amount!='0' and semester='$semester' and sy='$sy' order by sched_id asc") or die(mysql_error());
	?>
	<table style="border-collapse:collapse;margin:0 auto">
		<?php
		$overalltotal=0;
		$schedtotal=0;
		while ($paymentrow=mysql_fetch_array($getpayment)) {
			$overalltotal=$overalltotal+$paymentrow['amount'];
			$schedtotal=$schedtotal+$paymentrow['amount'];
			?>
				<tr>
					<td><?=$paymentrow['payment_desc'];?></td>
					<td style="text-align:right"><?=number_format($paymentrow['amount'],2);?></td>
				</tr>
			<?php
		}
		?>
		<tr>
			<td style="text-align:right;font-style:italic"><b>Sub-total&nbsp;&nbsp;&nbsp;&nbsp;</b></td><td style="text-decoration:overline;text-align:right"><?=number_format($schedtotal,2);?></td>
		</tr>
		<tr>
			<td>Miscellaneous Fees</td>
		</tr>
		<?php
		$getmisc=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and schedule_of_fees.course_id='$studentrow[course_id]' and (year_level like '%&$studentrow[year_level]&%' or year_level like '$studentrow[year_level]&%' or year_level like '%&$studentrow[year_level]') and amount!='0' and paymentlist.payment_group='misc' and semester='$semester' and sy='$sy' order by sched_id asc") or die(mysql_error());
		$misctotal=0;
		while ($miscrow=mysql_fetch_array($getmisc)) {
			$overalltotal=$overalltotal+$miscrow['amount'];
			$misctotal=$misctotal+$miscrow['amount'];
			?>
				<tr>
					<td style="padding-left:15px"><?=$miscrow['payment_desc'];?></td>
					<td style="text-align:right"><?=number_format($miscrow['amount'],2);?></td>
				</tr>
			<?php
		}
		?>
		<tr>
			<td style="text-align:right;font-style:italic"><b>Sub-total&nbsp;&nbsp;&nbsp;&nbsp;</b></td><td style="text-decoration:overline;text-align:right"><?=number_format($misctotal,2);?></td>
		</tr>

		<!-- rle-->
		<?php
		$getpayment=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and (year_level like '%&$studentrow[year_level]&%' or year_level like '$studentrow[year_level]&%' or year_level like '%&$studentrow[year_level]') and paymentlist.payment_group='rle' and amount!='0' and sy='$sy' and semester='$semester' and course_id='$studentrow[course_id]'") or die(mysql_error());
		while ($paymentrow=mysql_fetch_array($getpayment)) {
			$overalltotal=$overalltotal+$paymentrow['amount'];
			?>
				<tr>
					<td><?=$paymentrow['payment_desc'];?>
					</td>
					<td style="text-align:right"><?=number_format($paymentrow['amount'],2);?></td>
				</tr>
			<?php
		}
		?>

		<!-- for the gradution fee -->
		<?php
		$getpayment=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and (year_level like 'IV&%' or year_level like '%&IV') and paymentlist.payment_group='grad' and paymentlist.payment_group='$studentrow[status]' and sy='$sy' and amount!='0' and semester='0' order by sched_id asc") or die(mysql_error());
		if(mysql_num_rows($getpayment)>0){?>

		<tr>
			<td>Graduation Fees</td>
		</tr>
		<?php
		$gradtotal=0;
		while ($paymentrow=mysql_fetch_array($getpayment)) {
			$overalltotal=$overalltotal+$paymentrow['amount'];
			$gradtotal=$gradtotal+$paymentrow['amount'];
			?>
				<tr>
					<td style="padding-left:15px"><?=$paymentrow['payment_desc'];?></td>
					<td style="text-align:right"><?=number_format($paymentrow['amount'],2);?></td>
				</tr>
			<?php
		}
		?>
		<tr>
			<td style="text-align:right;font-style:italic"><b>Sub-total&nbsp;&nbsp;&nbsp;&nbsp;</b></td><td style="text-decoration:overline;text-align:right"><?=number_format($gradtotal,2);?></td>
		</tr>
		<?php
			}
		?>
		<!-- transferee or new student-->
		<?php
		$getpayment=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id  and paymentlist.payment_group='$studentrow[status]' and paymentlist.payment_group='new' and sy='$sy' and amount!='0' and semester='$semester' order by sched_id asc") or die(mysql_error());
		if(mysql_num_rows($getpayment)>0){?>
		<tr>
			<td>Additional Fees for New Students/Transferees</td>
		</tr>
		<?php
		$transtotal=0;
		if($studentrow['status']=="trans"){
			$studentrow['status']="new";
		}

		while ($paymentrow=mysql_fetch_array($getpayment)) {
			$overalltotal=$overalltotal+$paymentrow['amount'];
			$transtotal=$transtotal+$paymentrow['amount'];
			?>
				<tr>
					<td style="padding-left:15px"><?=$paymentrow['payment_desc'];?></td>
					<td style="text-align:right"><?=number_format($paymentrow['amount'],2);?></td>
				</tr>
			<?php
		}
		?>
		<tr>
			<td style="text-align:right;font-style:italic"><b>Sub-total&nbsp;&nbsp;&nbsp;&nbsp;</b></td><td style="text-decoration:overline;text-align:right"><?=number_format($transtotal,2);?></td>
		</tr>
		<?php
		}
		?>

		<tr>
			<td>
				<b><br>TOTAL...</b>
			</td>
			<td style="text-align:right;text-decoration:underline">
				<b><?=number_format($overalltotal,2);?></b>
			</td>
		</tr>
	</table>
	<?php
		$day=date('jS');
		$month=date('M');
		$y=date('Y');
	?>
	<p style="margin-top:25px;padding:0 1in 0 1in;text-indent:25px">
		This certification is issued upon request of Ms./Mr. <span style="text-transform:capitalize"><?=$studentrow['lname'];?></span> for whatever purpose it may
		serve her/his best on this <?=$day;?> of <?=$month;?> <?=$y;?> at Negros Oriental State University, Bayawan-
		Sta. Catalina Campus, Bayawan City.
	</p>
	<p style="text-align:center;padding-top:0.5in;text-transform:uppercase">
	<?php
	$jake=mysql_query("select * from signatory where status='Activated'");
	$name=mysql_fetch_array($jake);
	?>
		<u><b><?=$name['name'];?></b></u><br>
		Cashier
	</p>
</div> <!-- end of statementcon  -->
 <?php
$date=$_SESSION['date'];
 $or=$_REQUEST['or'];
 $cash=$_REQUEST['cash'];
 $change=$_REQUEST['change'];
 ?>
<script>
   
 
  		window.print();
  		setTimeout(function(){
  		  window.open('../printreceipt2.php?name=<?=$studentrow[lname];?>, <?=$studentrow[fname];?> <?=$studentrow[acronym];?> <?=$studentrow[year_level];?>&date=<?=$date;?>&or=<?=$or;?>&cash=<?=$cash;?>&change=<?=$change;?>&stud_id=<?=$stud_id;?>',"<?php echo date('his');?>");
  		},0);
  		
  		setTimeout(function(){
  				window.close();
  		},100);
  		


  </script>