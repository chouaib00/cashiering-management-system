<?php
session_start();

$date=$_POST['date'];
$date2=$_POST['date2'];
$date1array=explode("-", $date);
$month=$date1array[1]."/";
$year="/".$date1array[0];
if($date2!=""){
	$explode2=explode("/", $date2);
	$month=$explode2[0]."/";
	$year="/".$explode2[1];
}
?>
<style type="text/css">
	#monthlytable td {padding:2px;border:1px solid #989999;}
	#monthlytable .amount,.totalbottom {text-align:right;}
	.totalbottom {font-weight:bold;}
	#monthlytasble {
		-webkit-transform: rotate(10deg);
    	}
  
    .paymentrow:hover {background:#cee2e6}
</style>
<div style="width:100%;overflow:auto;margin-bottom:60px">
<table id="monthlytable" border="1px solid red" style="border-collapse:collapse;position:relative ">
	<?php
		include '../dbconfig.php';

		//get the semester//
		$getsemester=mysql_query("select sy,semester  from collection where date like '$month%' and date like '%$year' group by sy");
		$countsy=mysql_fetch_array($getsemester);
		$sy=$countsy['sy'];
		$semester=$countsy['semester'];
		$tuinotin="select schedule_of_fees.payment_id from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  ((schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester') or  (date like '$month%' and date like '%$year'))  and category='tui' group by schedule_of_fees.payment_id";
		$miscnotin="select schedule_of_fees.payment_id from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  ((schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester') or  (date like '$month%' and date like '%$year'))  and category='misc'  group by schedule_of_fees.payment_id";
		$tfnotin="select schedule_of_fees.payment_id from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  ((schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester') or  (date like '$month%' and date like '%$year'))  and category='tf'  group by schedule_of_fees.payment_id";
		
 		//get paymentst in tuition category
		$GLOBALS['tuinotin'] = $tuinotin;
		$GLOBALS['miscnotin'] = $miscnotin;
		$GLOBALS['tfnotin'] = $tfnotin;
		$GLOBALS['semester'] = $semester;
		$GLOBALS['sy'] = $sy;
		$GLOBALS['month'] = $month;
		$GLOBALS['year'] = $year;


		
	 
		function tuith(){
		$tuith=mysql_query("select * from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  ((schedule_of_fees.sy='$GLOBALS[sy]' and schedule_of_fees.semester='$GLOBALS[semester]') or  (date like '$GLOBALS[month]%' and date like '%$GLOBALS[year]'))  and category='tui' group by schedule_of_fees.payment_id order by schedule_of_fees.sched_id asc") or die(mysql_error());	  	return $tuith;
	  	}

	  	function miscth(){
	  	$miscth=mysql_query("select * from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  ((schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester') or  (date like '$month%' and date like '%$year'))  and  schedule_of_fees.payment_id not in ($GLOBALS[tfnotin])  and  schedule_of_fees.payment_id not in ($GLOBALS[tuinotin]) and category='misc'  group by schedule_of_fees.payment_id")  or die(mysql_error());;
	  	return $miscth;
	  	}

	  	function tfth(){
		$tfth=mysql_query("select * from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  ((schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester') or  (date like '$month%' and date like '%$year'))   and schedule_of_fees.payment_id not in ($GLOBALS[tuinotin]) and schedule_of_fees.payment_id not in ($GLOBALS[miscnotin]) and category='tf'  group by schedule_of_fees.payment_id")  or die(mysql_error());;
 		return $tfth;
 		}
 		$misccolspan=mysql_num_rows(miscth());
		$tuicolspan=mysql_num_rows(tuith());
 		$tfcolspan=mysql_num_rows(tfth());
		
		$studpay=mysql_query("select collection.col_id,student.stud_id,collection.date,collection.receipt_num,student.lname,student.fname,remark, SUM(collection.amount) as refund from student,collection,schedule_of_fees,course,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_desc='Laboratory Fee' and collection.sched_id=schedule_of_fees.sched_id and student.stud_id=collection.stud_id and date like '$month%' and date like '%$year' and  category='tui'   group by collection.receipt_num order by collection.date limit 1") or die(mysql_error());
 		$countdeptspan=1;
 		if(mysql_num_rows($studpay)){
 			$dept=mysql_query("select * from schedule_of_fees,course,paymentlist,department,collection where   schedule_of_fees.course_id=course.course_id and schedule_of_fees.payment_id=paymentlist.payment_id and course.dept_id=department.dept_id and paymentlist.payment_desc='Laboratory Fee'and collection.sy='$sy' and collection.semester='$semester' group by department.acronym order by department.dept_id")or die(mysql_error());
 	 		$countdeptspan=mysql_num_rows($dept);
 		}
 		
 		 
 	?>
	<tr>
		<td colspan="3"></td>
		
		<td class="tuicatheader" colspan="<?=$tuicolspan+$countdeptspan;?>" style="text-align:center;background:#c3e6b6">Tuition</td>
		<td  class="misccatheader" colspan="<?=$misccolspan+1;?>" style="background:#acc9f9; text-align:center">Miscellaneous</td>
		<td class="tfcatheader" colspan="<?=$tfcolspan+3;?>" style=" text-align:center;background:#f9beac">Trust Fund</td>
	</tr>
	<tr>		
		<td rowspan="2">Date<br>mm/dd/yy</td>
		<td rowspan="2">OR NO.</td>
		<td rowspan="2">Name</td>
		<?php
		//retrieve tui payment description
		$tuith=tuith();
			while ($tuithrow=mysql_fetch_array($tuith)) {	
			// echo "$tuithrow[payment_desc] <br>";			
				if($tuithrow['payment_desc']=="Laboratory Fee"){

					//get the count of the department
					$dept=mysql_query("select * from schedule_of_fees,course,paymentlist,department,collection where   schedule_of_fees.course_id=course.course_id and schedule_of_fees.payment_id=paymentlist.payment_id and course.dept_id=department.dept_id and paymentlist.payment_desc='Laboratory Fee'and collection.sy='$sy' and collection.semester='$semester' group by department.acronym order by department.dept_id")or die(mysql_error());
					$countdept=mysql_num_rows($dept);
					echo "<td colspan='$countdept' style='text-align:center'>Laboratory Fees</td>";
			
					}else{
						echo "<td rowspan='2'>$tuithrow[payment_desc]</td>";
					}
			}

		?>
			<td rowspan="2">Tuition Total</td>
		<?php

		///retreive  miscellaneous payment descrition
		$miscth=miscth();
		while ($miscthrow=mysql_fetch_array($miscth)) {
			echo "<td rowspan='2'>$miscthrow[payment_desc]</td>";
		}
		?>
  		<td rowspan="2">Total Misc.</td>

  		<?php
  		//retrieve tf payment descritpion
  		$tfth=tfth();
		while ($tfthrow=mysql_fetch_array($tfth)) {
			echo "<td rowspan='2'>$tfthrow[payment_desc]</td>";
		}
		?>
  		<td rowspan="2">Total Tf.</td>
  		<td rowspan="2">Refunded</td>
  		<td rowspan="2">TOTAL</td>
  	</tr>

	<!-- show department under lab fee during the school year  and semester in the collectin and -->
	<?php

	 $studpay=mysql_query("select collection.col_id,student.stud_id,collection.date,collection.receipt_num,student.lname,student.fname,remark, SUM(collection.amount) as refund from student,collection,schedule_of_fees,course,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_desc='Laboratory Fee' and collection.sched_id=schedule_of_fees.sched_id and student.stud_id=collection.stud_id and date like '$month%' and date like '%$year' and  category='tui'   group by collection.receipt_num order by collection.date limit 1") or die(mysql_error());
 	if(mysql_num_rows($studpay)>0){
 	$dept=mysql_query("select * from schedule_of_fees,course,paymentlist,department,collection where   schedule_of_fees.course_id=course.course_id and schedule_of_fees.payment_id=paymentlist.payment_id and course.dept_id=department.dept_id and paymentlist.payment_desc='Laboratory Fee'and collection.sy='$sy' and collection.semester='$semester' group by department.acronym order by department.dept_id")or die(mysql_error());
	echo "<tr>";
	while ($deptrow=mysql_fetch_array($dept)){
		echo  "<td>$deptrow[acronym]</td>";
	}
	$GLOBALS['checklab'] = "style='display:none'";
	?>
	</tr>
	
	<?php
	}else{
		echo "<tr></tr>";
	}
	?>

	 
	<?php
	//get student who paid
	$totalarray[0]=0;
	$overallrefund=0;
 	 $studpay=mysql_query("select collection.col_id,student.stud_id,collection.date,collection.receipt_num,student.lname,student.fname,remark, SUM(collection.amount) as refund from student,collection,schedule_of_fees,course where   collection.sched_id=schedule_of_fees.sched_id and student.stud_id=collection.stud_id and date like '$month%' and date like '%$year'    group by collection.date order by date asc") or die(mysql_error());
	// echo ;
	while($studpayrow=mysql_fetch_array($studpay)){
		//check if canceled
			$startarray=0;
			$horizontallyamounttotal=0;
			$tuitionhorizontaltotal=0;
		$strike="font-weight:;";
		$cancel=0;
		if($studpayrow['remark']=='Canceled'){
			$strike="text-decoration:line-through";
			$cancel="cancel='cancel'";
 		}

		?>
		<tr  style="<?=$strike;?>" class="paymentrow" id="paymentrow<?=$studpayrow['col_id'];?>" name="<?=$studpayrow['col_id'];?>">
			<td style="<?=$strike;?>" ><?=$studpayrow['date'];?></td>
			<td  style="<?=$strike;?>" ><?=$studpayrow['receipt_num'];?></td>
			<td style="<?=$strike;?>" ><div style="white-space:nowrap;height:20px;text-transform:capitalize"><?=$studpayrow['lname'];?>, <?=$studpayrow['fname'];?></div></td>
			<?php
			//get payment in tuition category
			$tuith=tuith();
			while ($tuithrow=mysql_fetch_array($tuith)){ 				
				//get amount of every payment
				//get the student department				 
				if($tuithrow['payment_desc']=="Laboratory Fee"){

  					
 					//get studednt department

 					$studdept=mysql_query("select dept_id from student_status,course where course.course_id=student_status.course_id and student");

					//get all department
					$alldept=mysql_query("select * from schedule_of_fees,course,paymentlist,department,collection where   schedule_of_fees.course_id=course.course_id and schedule_of_fees.payment_id=paymentlist.payment_id and course.dept_id=department.dept_id and paymentlist.payment_desc='Laboratory Fee'and collection.sy='$sy' and collection.semester='$semester' group by department.acronym order by department.dept_id")or die(mysql_error());
				 	while ($alldeptrow=mysql_fetch_array($alldept)){
						  $studlabamount=mysql_query("select SUM(collection.amount) as amount,course.dept_id from collection,schedule_of_fees,department,course where collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.course_id=course.course_id and course.dept_id='$alldeptrow[dept_id]' and course.dept_id=department.dept_id and schedule_of_fees.payment_id='$tuithrow[payment_id]' and date='$studpayrow[date]'") or die(mysql_error());
							$studlabamountrow=mysql_fetch_array($studlabamount);
							if($studlabamountrow['amount']==0){
								$studlabamountrow['amount']=0.00;
							}
				 		 $startarray++;
						//get the student department during he paid the lab fee,if he shifted to another course,still the recent course will followed in putting the lab amount in the department category
								$totalarray[$startarray]=$totalarray[$startarray]+$studlabamountrow['amount'];
							 
							$horizontallyamounttotal+=$studlabamountrow['amount'];												
							 
							echo "<td $ancel   style='$strike;text-align:right'  class='tuicat tuitd amount coliddept$alldeptrow[dept_id]'><a style='color:white'>`</a>".number_format($studlabamountrow['amount'],2)."</td>";
						 
						
					}
					$tuitionhorizontaltotal+=$studlabamountrow['amount'];
					
				}else{
					$tuiamount=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and schedule_of_fees.payment_id='$tuithrow[payment_id]'  and date='$studpayrow[date]'") or die(mysql_error());
					
					$tuiamountrow=mysql_fetch_array($tuiamount);

					
					

					if($tuiamountrow['amount']==""){
						echo "<td $cancel style='$strike;text-align:right'  class='tuicat tuitd amount colid$tuithrow[payment_id]'><a style='color:white'>`</a>0.00</td>";
					}else{
						 ?><td  style='<?=$strike;?>;text-align:right'  class='tuicat tuitd amount colid<?=$tuithrow['payment_id'];?>'><a style='color:white'>`</a><?=number_format($tuiamountrow['amount'],2);?></td>
						 <?php
					}
					$startarray++;
					if($studpayrow['remark']=='Canceled'){
						$totalarray[$startarray]=$totalarray[$startarray]+0;
					}else{
						$totalarray[$startarray]=$totalarray[$startarray]+$tuiamountrow['amount'];
					}
 
 					$tuitionhorizontaltotal+=$tuiamountrow['amount'];

					 $horizontallyamounttotal+=$tuiamountrow['amount'];												

				}
			}
			?>
				<td class="tuicat" <?=$cancel;?> style="<?=$strike;?>;font-weight:bold;text-align:right"><a style='color:white'>`</a><?=number_format($tuitionhorizontaltotal,2);?></td>
			<?php
			//get payment in miscellaneous category
			$mischorizontaltotal=0;
	  		$miscth=miscth();
			while ($miscthrow=mysql_fetch_array($miscth)){				
				//get amount of every payment
					$miscamount=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and  schedule_of_fees.payment_id='$miscthrow[payment_id]'  and date='$studpayrow[date]' 	and category='misc'") or die(mysql_error());
					$countmiscamount=mysql_num_rows($miscamount);
					
					$miscamountrow=mysql_fetch_array($miscamount);
					
					 $horizontallyamounttotal+=$miscamountrow['amount'];												
					 $mischorizontaltotal+=$miscamountrow['amount'];
						$startarray++;
					

					if($miscamountrow['amount']==""){
						echo "<td  style='$strike' class='misccat  amount colid$miscthrow[payment_id]' style='text-align:right'><a style='color:white'>`</a>0.00</td>";
						$totalarray[$startarray]=$totalarray[$startarray]+0;
					}else{
						$totalarray[$startarray]=$totalarray[$startarray]+$miscamountrow['amount'];
						echo "<td   class='misccat amount colid$miscthrow[payment_id]' style='$strike;text-align:right'><a style='color:white'>`</a>".number_format($miscamountrow['amount'],2)."</td>";
					}
					if($studpayrow['remark']=='Canceled'){
						$totalarray[$startarray]=$totalarray[$startarray]-$miscamountrow['amount'];	
					}
			}
			?>
			<td class="misccat" <?=$cancel;?> style="<?=$strike;?>;font-weight:bold;text-align:right;"><a style='color:white'>`</a><?=number_format($mischorizontaltotal,2);?></td>

			<?php
			//get payment in tf category
			$tfhorizontaltotal=0;
			$tfth=tfth();
 			while ($tfthrow=mysql_fetch_array($tfth)){				
				//get amount of every payment
					$tfamount=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and  schedule_of_fees.payment_id='$tfthrow[payment_id]' and  date='$studpayrow[date]' ") or die(mysql_error());
					$counttfamount=mysql_num_rows($tfamount);
					$tfamountrow=mysql_fetch_array($tfamount);
					$jake+=$tfamountrow['amount'];
					
					 $horizontallyamounttotal+=$tfamountrow['amount'];												
					 $tfhorizontaltotal+=$tfamountrow['amount'];
					 $startarray++;
					if($tfamountrow['amount']==""){
						$totalarray[$startarray]=$totalarray[$startarray]+0;
						echo "<td $cancel style='$strike' class='tfcat amount colid$tfthrow[payment_id]' style='text-align:right'><a style='color:white'>`</a>0.00</td>";
					}else{
						$totalarray[$startarray]=$totalarray[$startarray]+$tfamountrow['amount'];
						echo "<td $cancel style='$strike' class='tfcat amount colid$tfthrow[payment_id]' style='text-align:right'><a style='color:white'>`</a>".number_format($tfamountrow['amount'],2)."</td>";
					}
 
					if($studpayrow['remark']=='Canceled'){
						$totalarray[$startarray]=$totalarray[$startarray]-$tfamountrow['amount'];	
					}
			}

			//check if refunded
			if($studpayrow['remark']=="Refunded"){
				$refunded=mysql_query("select SUM(amount) as amount from collection where receipt_num='$studpayrow[receipt_num]' and remark='Refunded'") or die(mysql_error());
				$refundedrow=mysql_fetch_array($refunded);
				$refunded=$refundedrow['amount'];

			}else{
				$refunded=mysql_query("select SUM(amount) as amount from exceeded_money where receipt_num='$studpayrow[receipt_num]' and action='Refunded'");
				$refundedrow=mysql_fetch_array($refunded);
				$refunded=0;
				if($refundedrow['amount']==""){
					$refunded+=0.00;
 				}else{
					$refunded=$refundedrow['amount'];
 				}
			}
			$overallrefund=$overallrefund+$refunded;
			?>
			<td class="tfcat" style="<?=$strike;?>;font-weight:bold;text-align:right"><a style='color:white'>`</a><?=number_format($tfhorizontaltotal,2);?>0</td>
			<td class="refunded" style="<?=$strike;?>;font-weight:bold;text-align:right" name="<?=$refunded;?>"><a style='color:white'>`</a><?=number_format($refunded,2);?></td>
			<td class="overalltotalhorizontal" style="<?=$strike;?>;text-align:right;font-weight:bold"><a style='color:white'>`</a><?=number_format($horizontallyamounttotal,2);?></td>
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
		$tuith=tuith();
		$startarray=0;
		$totalarray[0]=0;
		$alltuitotal=0;
		$colspan=8;
			while ($tuithrow=mysql_fetch_array($tuith)) {

				
				//get amount of every payment
				if($tuithrow['payment_desc']=="Laboratory Fee"){
					$studlabamount=mysql_query("select collection.amount from collection,paymentlist,schedule_of_fees where collection.sched_id=schedule_of_fees.sched_id and paymentlist.payment_id=schedule_of_fees.payment_id and paymentlist.payment_desc='Laboratory Fee' and collection.sy='$sy' and collection.semester='$semester' and collection.receipt_num='$studpayrow[receipt_num]'") or die(mysql_error());
					$studlabamountrow=mysql_fetch_array($studlabamount);
					
					//get the student department
					$studdept=mysql_query("select department.dept_id from schedule_of_fees,course,paymentlist,department where schedule_of_fees.course_id=course.course_id and schedule_of_fees.payment_id=paymentlist.payment_id and course.dept_id=department.dept_id and paymentlist.payment_desc='Laboratory Fee'and sy='$sy' and semester='$semester' group by department.acronym order by department.dept_id");
				 
					while ($studdeptrow=mysql_fetch_array($studdept)) {
						$startarray++;
						if($totalarray[$startarray]==0){
							$totalarray[$startarray]=0.00;
						}

						$alltuitotal=$alltuitotal+$totalarray[$startarray];
						echo "<td class='totalbottom tuitotalbotom coliddept$studdeptrow[dept_id]' name='dept$studdeptrow[dept_id]'>".number_format($totalarray[$startarray],2)."</td>";
						$colspan++;
 					}
					
				}else{
						$startarray++;
						if($totalarray[$startarray]==0){
							$totalarray[$startarray]=0.00;
						}
						$alltuitotal=$alltuitotal+$totalarray[$startarray];
						echo "<td class='totalbottom tuitotalbotom colid$tuithrow[payment_id]' name='$tuithrow[payment_id]'>".number_format($totalarray[$startarray],2)."</td>";
				$colspan++;
 				}
			}

			echo "<td class='totalbottom tuitotalbotom'>".number_format($alltuitotal,2)."</td>";
			//total bottom for the miscellaneous category
	  	$miscth=miscth();
	  		$allmisctotal=0;
			while ($miscthrow=mysql_fetch_array($miscth)) {	
				$colspan++;
					$startarray++;
						if($totalarray[$startarray]==0){
							$totalarray[$startarray]=0.00;
						}
						$allmisctotal+=$totalarray[$startarray];
 				echo "<td class='totalbottom misctotalbottom  colid$miscthrow[payment_id]' name='$miscthrow[payment_id]'>".number_format($totalarray[$startarray],2)."</td>";
  			}
 			echo "<td class='totalbottom misctotalbottom'>".number_format($allmisctotal,2)."</td>";

 			//total bottom for the trust category
		$tfth=tfth();
		$alltftotal=0;
			while ($tfthrow=mysql_fetch_array($tfth)) {	
				$colspan++;
				$startarray++;
				if($totalarray[$startarray]==0){
					$totalarray[$startarray]=0.00;
				}
				$alltftotal=$alltftotal+$totalarray[$startarray];
 				echo "<td class='totalbottom tftotalbottom  colid$tfthrow[payment_id]' name='$tfthrow[payment_id]'>".number_format($totalarray[$startarray],2)."</td>";
  			}
 			echo "<td class='totalbottom tftotalbottom'>".number_format($alltftotal,2)."</td>";
 			echo "<td class='refunded'   style='text-align:right;font-weight:bold' name='0'>".number_format($overallrefund,2)."</td>";
 			echo "<td id='finaltotal' colspan=2 style='text-align:right;font-weight:bold'>".number_format($alltftotal+$allmisctotal+$alltuitotal,2)."</td>";
?>
	</tr>
	<tr>
		<td id="overalltotal" colspan="<?=$colspan;?>" style="text-align:right;background:#e1ffd7">
		 Overall Total: <?php echo  number_format($alltftotal+$allmisctotal+$alltuitotal,2)." - ".number_format($overallrefund,2)." = <b>".number_format($alltftotal+$allmisctotal+$alltuitotal-$overallrefund,2);?></b>
		</td>
 		 
	</tr>

</table></div>
<a href="inventory/printmonthlyreport.php?month=<?=$month;?>&year=<?=$year;?>" target='jakecorn'><button  class="print" style="float:right;position:absolute;bottom:-40px;right:10px"></button></a>
<script src="js/jquery.number.min.js"></script>
<script>
	 function roundNumber(number, decimals) {
	    var newnumber = new Number(number+'').toFixed(parseInt(decimals));
	    return parseFloat(newnumber); 
	}
	$(function() {

		//total refunded
		// $refundedrow=0;
		// $('.refunded').each(function(){
		// 	$refunded = (+$(this).attr('name'))+(+$refunded);
 	// 	});
		// $('.refunded:last').html(roundNumber($refunded, 12));
		//to get the overall total of every line ||  horizontally
		

	


		//total the refunded
	// 	var totalrefunded=0;
	// 	$('.refunded').each(function(){
	// 		totalrefunded+=parseInt($(this).attr('name'));
	// 	});
	// 	$('.refunded:last').html(totalrefunded);

	// 	$('.paymentrow').each(function(){
	// 		var totaltui=0;
	// 		var rid=$(this).attr('name');

	// 		//get total tuition horizontally
	// 		$('#paymentrow'+rid+" .tuicat").each(function(){
	// 				totaltui=totaltui+parseInt($(this).html());
	// 		});	
	// 		$('#paymentrow'+rid+" .tuicat:last").html(totaltui);

	// 		var totalmisc=0;
	// 		//get total tuition horizontally
	// 		$('#paymentrow'+rid+" .misccat").each(function(){
	// 				totalmisc=totalmisc+parseInt($(this).html());
	// 		});	
	// 		$('#paymentrow'+rid+" .misccat:last").html(totalmisc);

	// 		var totaltf=0;
	// 		//get total tuition horizontally
	// 		$('#paymentrow'+rid+" .tfcat").each(function(){
	// 				totaltf=totaltf+parseInt($(this).html());
	// 		});	
	// 		$('#paymentrow'+rid+" .tfcat:last").html(totaltf);

	// 	});

	// 	$('.totalbottom').each(function() {
 // 			var colid=$(this).attr('name');
	// 		var total=0;
	// 		$('.colid'+colid+"[cancel!='cancel']").each(function(){
	// 			total=total+parseInt($(this).html());
	// 		});
	// 		$('.colid'+colid+":last").html(total);
	// 	});

	// 	//get the total tuition total horizontally
	// 	var tuitotal=0;		
	// 	$('.tuitotalbotom').each(function() {
 // 			tuitotal+=parseInt($(this).html());

	// 	});
	// 	$('.tuitotalbotom:last').html(tuitotal);

	// 	//get the total miscellaenous total horizontally
	// 	var misctotal=0;		
	// 	$('.misctotalbottom').each(function() {
 // 			misctotal+=parseInt($(this).html());

	// 	});
	// 	$('.misctotalbottom:last').html(misctotal);

	// 	//get the total trust fund total horizontally
	// 	var totaltf=0;		
	// 	$('.tftotalbottom').each(function() {
 // 			totaltf+=parseInt($(this).html());

	// 	});
	// 	$('.tftotalbottom:last').html(totaltf);

	// 	$('.paymentrow').each(function(){
	// 		var overalltotalhorizontal=0;
	// 		var name=$(this).attr('name');
	// 		var totaltui=parseInt($('#paymentrow'+name+" .tuicat:last").html());
	// 		var totalmisc=parseInt($('#paymentrow'+name+" .misccat:last").html());
	// 		var totaltf=parseInt($('#paymentrow'+name+" .tfcat:last").html());
	// 		overalltotalhorizontal=totaltui+totalmisc+totaltf;
	// 		$('#paymentrow'+name+" .overalltotalhorizontal").html(overalltotalhorizontal);
			
	// 	});

	// 	//get the overall total 

	// 	var finaltotal=0;
	// 	var tuitotalbotom=parseInt($('.tuitotalbotom:last').html());
	// 	var misctotalbottom=parseInt($('.misctotalbottom:last').html());
	// 	var tftotalbottom=parseInt($('.tftotalbottom:last').html());
		
	// 	finaltotal=tuitotalbotom+misctotalbottom+tftotalbottom;
	// 	$('#finaltotal').html(finaltotal);

	// });

	// 	//subtracting the refunded to the overall total
	// 	var refunded=parseInt($('.refunded:last').html());
	// 	var finaltotal=parseInt($('#finaltotal').html());
 // 		$('#overalltotal a').html(finaltotal-refunded)
</script>