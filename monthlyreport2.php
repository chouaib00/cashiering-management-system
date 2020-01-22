<?php
session_start();
$sy='2014-2015';
$semester='I';
$month='01';
?>
<style type="text/css">
	#monthlytable td {padding:1px;}
	#monthlytable .amount,.totalbottom {text-align:right;}
	.totalbottom {font-weight:bold;}
	#monthlytasble {
		-webkit-transform: rotate(10deg);
    	}
</style>
<table border id="monthlytable" style="border-collapse:collapse">
	<?php
		include 'dbconfig.php';
		//get paymentst in tuition category
		$tuith=mysql_query("select * from schedule_of_fees,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and  sched_id in (select sched_id from schedule_of_fees where sched_id in (select sched_id from collection where sy='$sy' and semester='$semester') or sched_id in (select sched_id from schedule_of_fees where sy='$sy' and semester='$semester')) and category='tui' group by schedule_of_fees.payment_id order by sched_id") or die(mysql_error());
		$miscth=mysql_query("select * from schedule_of_fees,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and  sched_id in (select sched_id from schedule_of_fees where sched_id in (select sched_id from collection where sy='$sy' and semester='$semester') or sched_id in (select sched_id from schedule_of_fees where sy='$sy' and semester='$semester')) and category='misc' group by schedule_of_fees.payment_id order by sched_id") or die(mysql_error());
		$tfth=mysql_query("select * from schedule_of_fees,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and  sched_id in (select sched_id from schedule_of_fees where sched_id in (select sched_id from collection where sy='$sy' and semester='$semester') or sched_id in (select sched_id from schedule_of_fees where sy='$sy' and semester='$semester')) and category='tf' group by schedule_of_fees.payment_id order by sched_id") or die(mysql_error());
		$misccolspan=mysql_num_rows($miscth);
		$tuicolspan=mysql_num_rows($tuith);
		$tfcolspan=mysql_num_rows($tfth);
 	?>
	<tr>
		<td></td>
		<td></td>
		<td></td>
		<td colspan="<?=$tuicolspan+3;?>" style="background:#cdffe5;text-align:center">Tuition</td>
		<td colspan="<?=$misccolspan+1;?>" style="background:#ffedcd;text-align:center">Miscellaneous</td>
		<td colspan="<?=$tfcolspan+2;?>" style="background:gray;text-align:center">Trust Fund</td>
	</tr>
	<tr>		
		<td rowspan="2">Date</td>
		<td rowspan="2">OR NO.</td>
		<td rowspan="2">Name</td>
		<?php
		//retrieve tui payment description
			while ($tuithrow=mysql_fetch_array($tuith)) {
				
				if($tuithrow['payment_desc']=="Laboratory Fee"){
					//get the count of the department
					$dept=mysql_query("select * from schedule_of_fees,course,paymentlist,department,collection where   schedule_of_fees.course_id=course.course_id and schedule_of_fees.payment_id=paymentlist.payment_id and course.dept_id=department.dept_id and paymentlist.payment_desc='Laboratory Fee'and collection.sy='$sy' and collection.semester='$semester' group by department.acronym order by department.dept_id")or die(mysql_error());
					$countdept=mysql_num_rows($dept);
					echo "<td colspan='$countdept' >Laboratory Fees</td>";
			
				}else{
					echo "<td rowspan='2'>$tuithrow[payment_desc]</td>";
				}
			}

		?>
			<td rowspan="2">Tuition Total</td>
		<?php

		///retreive  miscellaneous payment descrition
		while ($miscthrow=mysql_fetch_array($miscth)) {
			echo "<td rowspan='2'>$miscthrow[payment_desc]</td>";
		}
		?>
  		<td rowspan="2">Total Misc.</td>

  		<?php
  		//retrieve tf payment descritpion
		while ($tfthrow=mysql_fetch_array($tfth)) {
			echo "<td rowspan='2'>$tfthrow[payment_desc]</td>";
		}
		?>
  		<td rowspan="2">Total Tf.</td>
  		<td rowspan="2">TOTAL</td>
  	</tr>

	<!-- show department under lab fee during the school year  and semester in the collectin and -->
	<?php	
	$dept=mysql_query("select * from schedule_of_fees,course,paymentlist,department,collection where   schedule_of_fees.course_id=course.course_id and schedule_of_fees.payment_id=paymentlist.payment_id and course.dept_id=department.dept_id and paymentlist.payment_desc='Laboratory Fee'and collection.sy='$sy' and collection.semester='$semester' group by department.acronym order by department.dept_id")or die(mysql_error());
	echo "<tr>";
	while ($deptrow=mysql_fetch_array($dept)) {
		echo  "<td>$deptrow[acronym]</td>";
	}
	?>
	</tr>

	<?php
	//get student who paid
	 $studpay=mysql_query("select collection.col_id,student.stud_id,collection.date,collection.receipt_num,student.lname,student.fname from student,collection,schedule_of_fees where   collection.sched_id=schedule_of_fees.sched_id and student.stud_id=collection.stud_id and collection.sy='$sy' and  collection.semester='$semester' and date like '$month/%' group by collection.receipt_num order by collection.receipt_num") or die(mysql_error());
		echo mysql_num_rows($studpay);
	while($studpayrow=mysql_fetch_array($studpay)){
		// $studentstatus=mysql_query("select * from student_status,department,course where student_status.course_id=course.course_id and course.dept_id=department.dept_id and student_status.stud_id='$studpayrow[stud_id] and student_status.sy='$sy' and student_status.semester='$semester'") or die(mysql_error());
		// $studentstatusrow=mysql_fetch_array($studentstatus);
		?>
		<tr class="paymentrow" id="paymentrow<?=$studpayrow['col_id'];?>" name="<?=$studpayrow['col_id'];?>">
			<td><?=$studpayrow['date'];?></td>
			<td><?=$studpayrow['receipt_num'];?></td>
			<td><?=$studpayrow['lname'];?>, <?=$studpayrow['fname'];?></td>
			<?php
			//get payment in tuition category
			$tuith=mysql_query("select * from schedule_of_fees,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and  sched_id in (select sched_id from schedule_of_fees where sched_id in (select sched_id from collection where sy='$sy' and semester='$semester') or sched_id in (select sched_id from schedule_of_fees where sy='$sy' and semester='$semester')) and category='tui'  group by schedule_of_fees.payment_id order by sched_id") or die(mysql_error());
			while ($tuithrow=mysql_fetch_array($tuith)) {
				
				//get amount of every payment
				if($tuithrow['payment_desc']=="Laboratory Fee"){
					$studlabamount=mysql_query("select collection.amount from collection,paymentlist,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_desc='Laboratory Fee' and collection.sy='$sy' and collection.semester='$semester' and collection.receipt_num='$studpayrow[receipt_num]'") or die(mysql_error());
					$studlabamountrow=mysql_fetch_array($studlabamount);
					
					//get the student department
					$studdept=mysql_query("select department.dept_id from schedule_of_fees,course,paymentlist,department where schedule_of_fees.course_id=course.course_id and schedule_of_fees.payment_id=paymentlist.payment_id and course.dept_id=department.dept_id and paymentlist.payment_desc='Laboratory Fee'and sy='$sy' and semester='$semester' group by department.acronym order by department.dept_id");
				 
					while ($studdeptrow=mysql_fetch_array($studdept)) {
						if($studdeptrow['dept_id']==$studpayrow['dept_id']){
							if($studlabamountrow['amount']==""){
								$studlabamountrow['amount']='0.00';
							}
							echo "<td class='tuicat amount coliddept$studdeptrow[dept_id]' >$studlabamountrow[amount]</td>";
						}else{
							echo "<td class='tuicat amount coliddept$studdeptrow[dept_id]'>0.00</td>";
						}
					}
					
				}else{
					$tuiamount=mysql_query("select collection.amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and  schedule_of_fees.payment_id='$tuithrow[payment_id]' and stud_id='$studpayrow[stud_id]' and receipt_num='$studpayrow[receipt_num]'") or die(mysql_error());
					$counttuiamount=mysql_num_rows($tuiamount);
					
					while ($tuiamountrow=mysql_fetch_array($tuiamount)) {
						echo "<td class='tuicat amount colid$tuithrow[payment_id]'>$tuiamountrow[amount]</td>";
					}

					if($counttuiamount==0){
						echo "<td class='tuicat amount colid$tuithrow[payment_id]'>0.00</td>";
					}
				}
			}
			?>
				<td class="tuicat" style="font-weight:bold">0</td>
			<?php
			//get payment in miscellaneous category
			$miscth=mysql_query("select * from schedule_of_fees,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and  sched_id in (select sched_id from schedule_of_fees where sched_id in (select sched_id from collection where sy='$sy' and semester='$semester') or sched_id in (select sched_id from schedule_of_fees where sy='$sy' and semester='$semester')) and category='misc'  group by schedule_of_fees.payment_id order by sched_id") or die(mysql_error());
			while ($miscthrow=mysql_fetch_array($miscth)) {				
				//get amount of every payment
					$miscamount=mysql_query("select collection.amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and  schedule_of_fees.payment_id='$miscthrow[payment_id]' and stud_id='$studpayrow[stud_id]' and receipt_num='$studpayrow[receipt_num]'") or die(mysql_error());
					$countmiscamount=mysql_num_rows($miscamount);
					
					while ($miscamountrow=mysql_fetch_array($miscamount)) {
						echo "<td class='misccat amount colid$miscthrow[payment_id]'>$miscamountrow[amount]</td>";
					}

					if($countmiscamount==0){
						echo "<td class='misccat amount colid$miscthrow[payment_id]'>0.00</td>";
					}
			}
			?>
			<td class="misccat" style="font-weight:bold;text-align:right">0</td>

			<?php
			//get payment in tf category
			$tfth=mysql_query("select * from schedule_of_fees,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and  sched_id in (select sched_id from schedule_of_fees where sched_id in (select sched_id from collection where sy='$sy' and semester='$semester') or sched_id in (select sched_id from schedule_of_fees where sy='$sy' and semester='$semester')) and category='tf' group by schedule_of_fees.payment_id order by sched_id") or die(mysql_error());
			while ($tfthrow=mysql_fetch_array($tfth)) {				
				//get amount of every payment
					$tfamount=mysql_query("select collection.amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and  schedule_of_fees.payment_id='$tfthrow[payment_id]' and stud_id='$studpayrow[stud_id]' and receipt_num='$studpayrow[receipt_num]'") or die(mysql_error());
					$counttfamount=mysql_num_rows($tfamount);
					
					while ($tfamountrow=mysql_fetch_array($tfamount)) {
						echo "<td class='tfcat amount colid$tfthrow[payment_id]'>$tfamountrow[amount]</td>";
					}

					if($counttfamount==0){
						echo "<td class='tfcat amount colid$tfthrow[payment_id]'>0.00</td>";
					}
			}
			?>
			<td class="tfcat" style="font-weight:bold;text-align:right">0</td>
  		</tr>
		<?php
	}
	?>

	<!-- bottom of the tuition category -->
	<tr>
		<td></td>
		<td></td>
		<td>Total</td>
		<?php
		//get payment in tuition category
		//total bottom at tuition category
			$tuith=mysql_query("select * from schedule_of_fees,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and  sched_id in (select sched_id from schedule_of_fees where sched_id in (select sched_id from collection where sy='$sy' and semester='$semester') or sched_id in (select sched_id from schedule_of_fees where sy='$sy' and semester='$semester')) and category='tui'  group by schedule_of_fees.payment_id order by sched_id") or die(mysql_error());
			while ($tuithrow=mysql_fetch_array($tuith)) {
				
				//get amount of every payment
				if($tuithrow['payment_desc']=="Laboratory Fee"){
					$studlabamount=mysql_query("select collection.amount from collection,paymentlist,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_desc='Laboratory Fee' and collection.sy='$sy' and collection.semester='$semester' and collection.receipt_num='$studpayrow[receipt_num]'") or die(mysql_error());
					$studlabamountrow=mysql_fetch_array($studlabamount);
					
					//get the student department
					$studdept=mysql_query("select department.dept_id from schedule_of_fees,course,paymentlist,department where schedule_of_fees.course_id=course.course_id and schedule_of_fees.payment_id=paymentlist.payment_id and course.dept_id=department.dept_id and paymentlist.payment_desc='Laboratory Fee'and sy='$sy' and semester='$semester' group by department.acronym order by department.dept_id");
				 
					while ($studdeptrow=mysql_fetch_array($studdept)) {
						echo "<td class='totalbottom tuitotalbotom coliddept$studdeptrow[dept_id]' name='dept$studdeptrow[dept_id]'>0.00</td>";
					}
					
				}else{
						echo "<td class='totalbottom tuitotalbotom colid$tuithrow[payment_id]' name='$tuithrow[payment_id]'>0.00</td>";
				}
			}

			echo "<td class='totalbottom tuitotalbotom'>0</td>";
			//total bottom for the miscellaneous category
			$miscth=mysql_query("select * from schedule_of_fees,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and  sched_id in (select sched_id from schedule_of_fees where sched_id in (select sched_id from collection where sy='$sy' and semester='$semester') or sched_id in (select sched_id from schedule_of_fees where sy='$sy' and semester='$semester')) and category='misc'  group by schedule_of_fees.payment_id order by sched_id") or die(mysql_error());
			while ($miscthrow=mysql_fetch_array($miscth)) {	
 				echo "<td class='totalbottom misctotalbottom  colid$miscthrow[payment_id]' name='$miscthrow[payment_id]'>0.00</td>";
 			}
 			echo "<td class='totalbottom misctotalbottom'>0</td>";

 			//total bottom for the trust category
			$tfth=mysql_query("select * from schedule_of_fees,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and  sched_id in (select sched_id from schedule_of_fees where sched_id in (select sched_id from collection where sy='$sy' and semester='$semester') or sched_id in (select sched_id from schedule_of_fees where sy='$sy' and semester='$semester')) and category='tf' group by schedule_of_fees.payment_id order by sched_id") or die(mysql_error());
			while ($tfthrow=mysql_fetch_array($tfth)) {	
 				echo "<td class='totalbottom tftotalbottom  colid$tfthrow[payment_id]' name='$tfthrow[payment_id]'>0.00</td>";
 			}
 			echo "<td class='totalbottom tftotalbottom'>0</td>";
?>
	</tr>

</table>

<script src="js/jquery.min.js"></script>
<script>
	$(function() {
		$('.paymentrow').each(function(){
			var totaltui=0;
			var rid=$(this).attr('name');

			//get total tuition horizontally
			$('#paymentrow'+rid+" .tuicat").each(function(){
					totaltui=totaltui+parseInt($(this).html());
			});	
			$('#paymentrow'+rid+" .tuicat:last").html(totaltui);

			var totalmisc=0;
			//get total tuition horizontally
			$('#paymentrow'+rid+" .misccat").each(function(){
					totalmisc=totalmisc+parseInt($(this).html());
			});	
			$('#paymentrow'+rid+" .misccat:last").html(totalmisc);

			var totaltf=0;
			//get total tuition horizontally
			$('#paymentrow'+rid+" .tfcat").each(function(){
					totaltf=totaltf+parseInt($(this).html());
			});	
			$('#paymentrow'+rid+" .tfcat:last").html(totaltf);

		});

		$('.totalbottom').each(function() {
 			var colid=$(this).attr('name');
			var total=0;
			$('.colid'+colid).each(function(){
				total=total+parseInt($(this).html());
			});
			$('.colid'+colid+":last").html(total);
		});

		//get the total tuition total horizontally
		var tuitotal=0;		
		$('.tuitotalbotom').each(function() {
 			tuitotal+=parseInt($(this).html());

		});
		$('.tuitotalbotom:last').html(tuitotal);

		//get the total miscellaenous total horizontally
		var misctotal=0;		
		$('.misctotalbottom').each(function() {
 			misctotal+=parseInt($(this).html());

		});
		$('.misctotalbottom:last').html(misctotal);

		//get the total trust fund total horizontally
		var totaltf=0;		
		$('.tftotalbottom').each(function() {
 			totaltf+=parseInt($(this).html());

		});
		$('.tftotalbottom:last').html(totaltf);

	});
</script>