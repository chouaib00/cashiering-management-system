<?php
session_start();
ini_set('max_execution_time', 0);
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
    .paymentrow:hover {background:#cee2e6}
</style>
<div style="width:100%;overflow:auto;margin-bottom:60px">
<table id="monthlytable" border="1px solid red" style="min-width:100%;border-collapse:collapse;position:relative ">
	<?php
		include '../dbconfig.php';

		//get the semester//
		$getsemester=mysql_query("select sy,semester  from collection where date like '$month%' and date like '%$year' group by sy");
		$countsy=mysql_fetch_array($getsemester);
		$sy=$countsy['sy'];
		$semester=$countsy['semester'];
		
 		$tuith=mysql_query("select 	* from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  (date like '$month%' and date like '%$year')  and category='tui' group by schedule_of_fees.payment_id");
		$miscth=mysql_query("select 	* from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  (date like '$month%' and date like '%$year')  and category='misc'  group by schedule_of_fees.payment_id");
		$tfth=mysql_query("select 	* from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  (date like '$month%' and date like '%$year')  and category='tf'  group by schedule_of_fees.payment_id");
		

		//sub query
		$tuiarraysub="0";
		$tuicheck=0;
		while ($tuithrow=mysql_fetch_array($tuith)){
			if($tuicheck==0){
				$tuiarraysub="";
				$tuiarraysub.=$tuithrow['payment_id'];
			}else{
				$tuiarraysub.=",".$tuithrow['payment_id'];

			}
			$tuicheck=1;
		}

		$miscarraysub="0";
		$misccheck=0;
		while ($miscthrow=mysql_fetch_array($miscth)) {
 			if($misccheck==0){
				$miscarraysub="";
				$miscarraysub.=$miscthrow['payment_id'];
			}else{
				$miscarraysub.=",".$miscthrow['payment_id'];

			}
			$misccheck=1;
		}
 		$tfarraysub="0";
		$tfcheck=0;
		while ($tfrow=mysql_fetch_array($tfth)) {
  			if($tfcheck==0){
				$tfarraysub="";
				$tfarraysub.=$tfrow['payment_id'];
			}else{
				$tfarraysub.=",".$tfrow['payment_id'];

			}
			$tfcheck=1;
		}


 		//get paymentst in tuition category
		$GLOBALS['tuinotin'] = $tuiarraysub;
		$GLOBALS['miscnotin'] = $miscarraysub;
		$GLOBALS['tfnotin'] = $tfarraysub;
		$GLOBALS['semester'] = $semester;
		$GLOBALS['sy'] = $sy;
		$GLOBALS['month'] = $month;
		$GLOBALS['year'] = $year;


		//GET THE OUTPUTTED VALUE
	 	$tuicolspan=0;
 		$misccolspan=0;
 		$tfcolspan=0;
		

		$tuiarray="0";
		$tuicheck=0;
		$tuith=tuith();
		while ($tuithrow=mysql_fetch_array($tuith)) {
 			$tuicolspan++;
			if($tuicheck==0){
				$tuiarray="";
				$tuiarray.=$tuithrow['payment_id'];
			}else{
				$tuiarray.=",".$tuithrow['payment_id'];

			}
			$tuicheck=1;
		}

  		$miscarray="0";
		$misccheck=0;
		$miscth=miscth();
		while ($miscthrow=mysql_fetch_array($miscth)) {
			$misccolspan++;
			if($misccheck==0){
				$miscarray="";
				$miscarray.=$miscthrow['payment_id'];
			}else{
				$miscarray.=",".$miscthrow['payment_id'];

			}
			$misccheck=1;
		}

		function tuith(){
			$tuith=mysql_query("select * from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  (date like '$GLOBALS[month]%' and date like '%$GLOBALS[year]')  and category='tui' group by schedule_of_fees.payment_id order by schedule_of_fees.sched_id asc") or die(mysql_error());
			return $tuith;
	  	}

	  	function miscth(){
	  		$miscth=mysql_query("select * from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  (date like '$GLOBALS[month]%' and date like '%$GLOBALS[year]')  and  schedule_of_fees.payment_id not in ($GLOBALS[tfnotin])  and  schedule_of_fees.payment_id not in ($GLOBALS[tuinotin]) and category='misc'  group by schedule_of_fees.payment_id")  or die(mysql_error());;
	  		return $miscth;
	  	}

	  	function tfth(){
	  		global $miscarray;
	  		global $tuiarray;
	  		
			$tfth=mysql_query("select * from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  (date like '$GLOBALS[month]%' and date like '%$GLOBALS[year]')    and  schedule_of_fees.payment_id not in ($tuiarray,$miscarray)  and category='tf'  group by schedule_of_fees.payment_id")  or die(mysql_error());;
 			return $tfth;
 		}

		$tuicolspan=1;
 		$misccolspan=0;
 		$tfcolspan=0;
		

		$tuiarray="0";
		$tuicheck=0;
		$tuith=tuith();
		while ($tuithrow=mysql_fetch_array($tuith)) {
 			$tuicolspan++;
			if($tuicheck==0){
				$tuiarray="";
				$tuiarray.=$tuithrow['payment_id'];
			}else{
				$tuiarray.=",".$tuithrow['payment_id'];

			}
			$tuicheck=1;
		}

  		$miscarray="0";
		$misccheck=0;
		$miscth=miscth();
		while ($miscthrow=mysql_fetch_array($miscth)) {
			$misccolspan++;
			if($misccheck==0){
				$miscarray="";
				$miscarray.=$miscthrow['payment_id'];
			}else{
				$miscarray.=",".$miscthrow['payment_id'];

			}
			$misccheck=1;
		}

		$tfarray="0";
		$tfcheck=0;
		$tfth=tfth();
		while ($tfrow=mysql_fetch_array($tfth)) {
			$tfcolspan++;
			if($tfcheck==0){
				$tfarray="";
				$tfarray.=$tfrow['payment_id'];
			}else{
				$tfarray.=",".$tfrow['payment_id'];

			}
			$tfcheck=1;
		}

 		$countdeptspan=-1;
 		$deptaarray=array();
  		$alldept=mysql_query("select course.dept_id from schedule_of_fees,course,paymentlist,department,collection where   schedule_of_fees.course_id=course.course_id and schedule_of_fees.payment_id=paymentlist.payment_id and course.dept_id=department.dept_id and paymentlist.payment_desc='Laboratory Fee'and collection.sy='$sy' and collection.semester='$semester' group by department.dept_id order by department.dept_id asc")or die(mysql_error());
	 	while ($alldeptrow=mysql_fetch_array($alldept)){
	 		$countdeptspan++;
	 		array_push($deptaarray, $alldeptrow['dept_id']);
 	 	}
  		$deptaarraycount=count($deptaarray);
   		//check if laboratory
  		$studpay=mysql_query("select * from collection,schedule_of_fees,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and payment_desc='Laboratory Fee' and collection.sched_id=schedule_of_fees.sched_id and date like '$month%' and date like '%$year'    group by collection.receipt_num limit 1") or die(mysql_error());
  		if(mysql_num_rows($studpay)>0){
  			$tuicolspan=$tuicolspan+$countdeptspan;
  		}
  	?>
	<tr>
		<td colspan="3"></td>
		
		<td class="tuicatheader" colspan="<?=$tuicolspan;?>" style="text-align:center;background:#c3e6b6">Tuition</td>
		<td  class="misccatheader" colspan="<?=$misccolspan+1;?>" style="background:#acc9f9; text-align:center">Miscellaneous</td>
		<td class="tfcatheader" colspan="<?=$tfcolspan+3;?>" style=" text-align:center;background:#f9beac">Trust Fund</td>
	</tr>
	<tr>		
		<td rowspan="2">Date<br>mm/dd/yy</td>
		<td rowspan="2">OR NO.</td>
		<td rowspan="2">Name</td>
		<?php
		//retrieve tui payment description
		$tuith=mysql_query("select * from paymentlist where payment_id in ($tuiarray) order by payment_id asc");
			while ($tuithrow=mysql_fetch_array($tuith)) {
 				if($tuithrow['payment_desc']=="Laboratory Fee"){

					//get the count of the department
					$dept=mysql_query("select * from schedule_of_fees,course,paymentlist,department,collection where   schedule_of_fees.course_id=course.course_id and schedule_of_fees.payment_id=paymentlist.payment_id and course.dept_id=department.dept_id and paymentlist.payment_desc='Laboratory Fee'and collection.sy='$sy' and collection.semester='$semester' group by department.acronym order by department.dept_id")or die(mysql_error());
					$countdept=mysql_num_rows($dept);
					echo "<td colspan='$countdept' style='text-align:center'>wwLaboratory Fees</td>";
			
					}else{
						echo "<td rowspan='2'>$tuithrow[payment_desc]</td>";
					}
			}

		?>
			<td rowspan="2">Tuition Total</td>
		<?php

		///retreive  miscellaneous payment descrition
		$miscth=mysql_query("select payment_desc from paymentlist where payment_id in ($miscarray) order by payment_id asc");
		while ($miscthrow=mysql_fetch_array($miscth)){
			if($miscthrow['payment_desc']=="Laboratory Fee"){

				//get the count of the department
				$dept=mysql_query("select * from schedule_of_fees,course,paymentlist,department,collection where   schedule_of_fees.course_id=course.course_id and schedule_of_fees.payment_id=paymentlist.payment_id and course.dept_id=department.dept_id and paymentlist.payment_desc='Laboratory Fee'and collection.sy='$sy' and collection.semester='$semester' group by department.acronym order by department.dept_id")or die(mysql_error());
				$countdept=mysql_num_rows($dept);
				echo "<td colspan='$countdept' style='text-align:center'>Laboratory Fees</td>";
		
				}else{

					echo "<td rowspan='2'>$miscthrow[payment_desc]</td>";
				}
		}
		?>
  		<td rowspan="2">Total Misc.</td>

  		<?php
  		//retrieve tf payment descritpion
  		 $tfth=mysql_query("select payment_desc from paymentlist where payment_id in ($tfarray) order by payment_id asc");

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
  
	 $studpay=mysql_query("select collection.col_id,student.stud_id,collection.date,collection.receipt_num,student.lname,student.fname,remark, SUM(collection.amount) as refund from student,collection,schedule_of_fees,course,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_desc='Laboratory Fee' and collection.sched_id=schedule_of_fees.sched_id and student.stud_id=collection.stud_id and date like '$month%' and date like '%$year' and  category='tui'   group by collection.receipt_num order by collection.date") or die(mysql_error());
 	
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
	//insert dummy data
	mysql_query("truncate dummydata");
	 $studpay=mysql_query("select collection.col_id,receipt_num from student,collection,schedule_of_fees,course where   collection.sched_id=schedule_of_fees.sched_id and student.stud_id=collection.stud_id and date like '$month%' and date like '%$year'    group by collection.receipt_num") or die(mysql_error());
	 while ($row=mysql_fetch_array($studpay)) {
	 		mysql_query("insert into dummydata values ('','$row[col_id]','$row[receipt_num]')");
	 }

 	 
 	//get student who paid
	$totalarray[0]=0;
	$overallrefund=0;
	$limitstart=0;
 	
 	$studpay=mysql_query("select collection.col_id,student.stud_id,collection.date,collection.receipt_num,student.lname,student.fname,remark, SUM(collection.amount) as refund from student,collection,schedule_of_fees,course,dummydata where dummydata.col_id=collection.col_id and    collection.sched_id=schedule_of_fees.sched_id and student.stud_id=collection.stud_id and date like '$month%' and date like '%$year'    group by collection.receipt_num order by dummydata.receipt_num,collection.receipt_num asc limit $limitstart,20 ") or die(mysql_error());

	while($studpayrow=mysql_fetch_array($studpay)){
		$limitstart++;
		//check if cancelled
		$startarray=0;
		 $horizontallyamounttotal=0;
		$tuitionhorizontaltotal=0;
		$strike="font-weight:;";
		$cancel=0;
 		if($studpayrow['remark']=='Cancelled'){
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
			$tuith=mysql_query("select payment_id,payment_desc from paymentlist where payment_id in ($tuiarray) order by payment_id asc");
			while ($tuithrow=mysql_fetch_array($tuith)){ 				
				//get amount of every payment
				//get the student department				 
				if($tuithrow['payment_desc']=="Laboratory Fee"){
					
					$studlabamount=mysql_query("select SUM(collection.amount) as amount,course.dept_id from collection,schedule_of_fees,department,course where collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.course_id=course.course_id and course.dept_id=department.dept_id and schedule_of_fees.payment_id='$tuithrow[payment_id]' and stud_id='$studpayrow[stud_id]' and  receipt_num='$studpayrow[receipt_num]'") or die(mysql_error());
					$studlabamountrow=mysql_fetch_array($studlabamount);			 

 					$deptstart=0;
					//get all department
				 	while ($deptstart<$deptaarraycount){
				 		 $startarray++;
  						
   						if($deptaarray[$deptstart]==$studlabamountrow['dept_id']){
 							if($studlabamountrow['amount']==""){
								$studlabamountrow['amount']='0.00';
								$totalarray[$startarray]=$totalarray[$startarray]+0;
							}else{
								if($studpayrow['remark']=='Cancelled'){
								}else{
									$totalarray[$startarray]=$totalarray[$startarray]+$studlabamountrow['amount'];
								}

							}

							$value=$studlabamountrow['amount'];
							$tuitionhorizontaltotal+=$studlabamountrow['amount'];
							$value2=$tuitionhorizontaltotal;
							if($studpayrow['remark']=='Cancelled'){
								$value=0;
								$value2=0;
							}

							$horizontallyamounttotal+=$studlabamountrow['amount'];												
							echo "<td $cancel  style='$strike;text-align:right'   class='tuicat tuitd amount colid$deptaarray[$deptstart]dept' value='$value' name='$deptaarray[$deptstart]dept'>".number_format($studlabamountrow['amount'],2)."</td>";
							
							
						}else{
							echo "<td $ancel   style='$strike;text-align:right'  class='tuicat tuitd amount colid$deptaarray[$deptstart]dept' value='0' name='$deptaarray[$deptstart]dept'>0.00</td>";
						}
						$deptstart++;
					}
					
				}else{
					$tuiamount=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and  schedule_of_fees.payment_id='$tuithrow[payment_id]' and stud_id='$studpayrow[stud_id]' and receipt_num='$studpayrow[receipt_num]' ") or die(mysql_error());
					
					$tuiamountrow=mysql_fetch_array($tuiamount);
					$tuitionhorizontaltotal+=$tuiamountrow['amount'];
					$value2=$tuitionhorizontaltotal;
					$value=$tuiamountrow['amount'];
					if($studpayrow['remark']=='Cancelled'){
						$value=0;
						$value2=0;
						$totalarray[$startarray]=$totalarray[$startarray]+0;
					}else{
						$totalarray[$startarray]=$totalarray[$startarray]+$tuiamountrow['amount'];
					}
						


					if($tuiamountrow['amount']==""){
						echo "<td $cancel style='$strike;text-align:right'  class='tuicat tuitd amount colid$tuithrow[payment_id]' value='0' name='$tuithrow[payment_id]'>0.00</td>";
					}else{
						 ?><td  style='<?=$strike;?>;text-align:right'  class='tuicat tuitd amount colid<?=$tuithrow['payment_id'];?>' value='<?=$value;?>' name='<?=$tuithrow['payment_id'];?>'><?=number_format($tuiamountrow['amount'],2);?></td>
						 <?php
					}
					$startarray++;
					 
 					

					 $horizontallyamounttotal+=$tuiamountrow['amount'];												

				}
			}
			?>
				<td class="tuicat tuicattotal" <?=$cancel;?> style="<?=$strike;?>;font-weight:bold;text-align:right" value='<?php echo "$value2"; ?>'><?=number_format($tuitionhorizontaltotal,2);?></td>
			<?php
			//get payment in miscellaneous category
			$mischorizontaltotal=0;
			$miscth=mysql_query("select payment_id from paymentlist where payment_id in ($miscarray) order by payment_id asc");
	  		 
			while ($miscthrow=mysql_fetch_array($miscth)){				
				//get amount of every payment
					$miscamount=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and  schedule_of_fees.payment_id='$miscthrow[payment_id]' and stud_id='$studpayrow[stud_id]' and receipt_num='$studpayrow[receipt_num]' 	and category='misc'") or die(mysql_error());
					$countmiscamount=mysql_num_rows($miscamount);
					
					$miscamountrow=mysql_fetch_array($miscamount);
					
					$horizontallyamounttotal+=$miscamountrow['amount'];												
					$startarray++;
					$mischorizontaltotal+=$miscamountrow['amount'];
					$value=$miscamountrow['amount'];
					$value2=$mischorizontaltotal;
					if($studpayrow['remark']=='Cancelled'){
						$value2=0;
						$value=0;
						$totalarray[$startarray]=$totalarray[$startarray]-$miscamountrow['amount'];	
					}

					if($miscamountrow['amount']==""){
						echo "<td  style='$strike' class='misccat  amount colid$miscthrow[payment_id]' name='$miscthrow[payment_id]' style='text-align:right' value='0'>0.00</td>";
						$totalarray[$startarray]=$totalarray[$startarray]+0;
					}else{
						$totalarray[$startarray]=$totalarray[$startarray]+$miscamountrow['amount'];
						echo "<td   class='misccat amount colid$miscthrow[payment_id]' name='$miscthrow[payment_id]' style='$strike;text-align:right' value='$value'>".number_format($miscamountrow['amount'],2)."</td>";
					}
					
			}
			?>
			<td class="misccat misccattotal" <?=$cancel;?> style="<?=$strike;?>;font-weight:bold;text-align:right;" value="<?php echo "$value2"; ?>"><?=number_format($mischorizontaltotal,2);?></td>

			<?php
			//get payment in tf category
			$tfhorizontaltotal=0;
			$tfth=mysql_query("select payment_id,payment_desc from paymentlist where payment_id in ($tfarray) order by payment_id asc");
			 
 			while ($tfthrow=mysql_fetch_array($tfth)){				
				//get amount of every payment
					$tfamount=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees where schedule_of_fees.sched_id=collection.sched_id and  schedule_of_fees.payment_id='$tfthrow[payment_id]' and stud_id='$studpayrow[stud_id]' and receipt_num='$studpayrow[receipt_num]' ") or die(mysql_error());
					$counttfamount=mysql_num_rows($tfamount);
					$tfamountrow=mysql_fetch_array($tfamount);
					$jake+=$tfamountrow['amount'];
					
					$value=$tfamountrow['amount'];	
					$tfhorizontaltotal+=$tfamountrow['amount'];	
					$value2=$tfhorizontaltotal;
					
					 $horizontallyamounttotal+=$tfamountrow['amount'];												
					 $startarray++;

					 if($studpayrow['remark']=='Cancelled'){
					 	$value2=0;
					 	$value=0;
						$totalarray[$startarray]=$totalarray[$startarray]-$tfamountrow['amount'];	
					} 


					if($tfamountrow['amount']==""){
						$totalarray[$startarray]=$totalarray[$startarray]+0;
						echo "<td $cancel style='$strike' class='tfcat amount colid$tfthrow[payment_id]' name='$tfthrow[payment_id]' value='0' style='text-align:right'>0.00</td>";
					}else{
						$totalarray[$startarray]=$totalarray[$startarray]+$tfamountrow['amount'];
						echo "<td $cancel style='$strike' class='tfcat amount colid$tfthrow[payment_id]' name='$tfthrow[payment_id]'  value='$value' style='text-align:right'>".number_format($tfamountrow['amount'],2)."</td>";
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
			$horizontallyamounttotal2=$horizontallyamounttotal;

			if($studpayrow['remark']=='Cancelled'){
				$horizontallyamounttotal2=0;
	 		}
			?>
			<td class="tfcat tfcattotal" style="<?=$strike;?>;font-weight:bold;text-align:right" value="<?php echo "$value2"; ?>"><?=number_format($tfhorizontaltotal,2);?>0</td>
			<td class="refunded" style="<?=$strike;?>;font-weight:bold;text-align:right" name="<?=$refunded;?>"><?=number_format($refunded,2);?></td>
			<td class="overalltotalhorizontal" name="<?php echo "$horizontallyamounttotal2";?>" style="<?=$strike;?>;text-align:right;font-weight:bold"><?=number_format($horizontallyamounttotal,2);?></td>
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
		$tuith=mysql_query("select payment_id,payment_desc from paymentlist where payment_id in ($tuiarray) order by payment_id asc");
		$startarray=0;
		$totalarray[0]=0;
		$alltuitotal=0;
		$colspan=8;
			while ($tuithrow=mysql_fetch_array($tuith)){
				//get amount of every payment
				if($tuithrow['payment_desc']=="Laboratory Fee"){
									
					//get the student department
 				 	$deptstart=0;
					while ($deptstart<$deptaarraycount){
						
						$startarray++;
						if($totalarray[$startarray]==0){
							$totalarray[$startarray]=0.00;
						}

						$alltuitotal=$alltuitotal+$totalarray[$startarray];
						echo "<td class='totalbottom tuitotalbotom colid$deptaarray[$deptstart]dept' value='0' name='$deptaarray[$deptstart]dept'>".number_format($totalarray[$startarray],2)."</td>";
						$colspan++;
						$deptstart++;
 					}
					
				}else{
						$startarray++;
						if($totalarray[$startarray]==0){
							$totalarray[$startarray]=0.00;
						}
						$alltuitotal=$alltuitotal+$totalarray[$startarray];
						echo "<td class='totalbottom tuitotalbotom colid$tuithrow[payment_id]' value='0' name='$tuithrow[payment_id]'>".number_format($totalarray[$startarray],2)."</td>";
				$colspan++;
 				}
			}

			echo "<td class='totalbottom tuitotalbotom tuicattotal' value='0'></td>";
			//total bottom for the miscellaneous category
			$miscth=mysql_query("select payment_id from paymentlist where payment_id in ($miscarray) order by payment_id asc");
	  	 
	  		$allmisctotal=0;
			while ($miscthrow=mysql_fetch_array($miscth)){	
				$colspan++;
					$startarray++;
						if($totalarray[$startarray]==0){
							$totalarray[$startarray]=0.00;
						}
						$allmisctotal+=$totalarray[$startarray];
 				echo "<td class='totalbottom misctotalbottom  colid$miscthrow[payment_id]' value='0' name='$miscthrow[payment_id]'>".number_format($totalarray[$startarray],2)."</td>";
  			}
 			echo "<td class='totalbottom misctotalbottom misccattotal' value='0'>".number_format($allmisctotal,2)."</td>";

 			//total bottom for the trust category
			$tfth=mysql_query("select payment_id from paymentlist where payment_id in ($tfarray) order by payment_id asc");
		 
		$alltftotal=0;
			while ($tfthrow=mysql_fetch_array($tfth)) {	
				$colspan++;
				$startarray++;
				if($totalarray[$startarray]==0){
					$totalarray[$startarray]=0.00;
				}
				$alltftotal=$alltftotal+$totalarray[$startarray];
				///lassssssssssssssssssssssssssssssssssssssssssssssssssssst
 				echo "<td class='totalbottom tftotalbottom  colid$tfthrow[payment_id]' value='0' name='$tfthrow[payment_id]'>".number_format($totalarray[$startarray],2)."</td>";
  			}
 			echo "<td class='totalbottom tftotalbottom tfcattotal' value='0'>".number_format($alltftotal,2)."</td>";
 			echo "<td class='refunded'   style='text-align:right;font-weight:bold' name='0'>".number_format($overallrefund,2)."</td>";
 			echo "<td id='finaltotal' colspan=2 style='text-align:right;font-weight:bold'>ddddd</td>";
?>
	</tr>
	<tr>
		<td id="overalltotal" colspan="<?=$colspan;?>" style="text-align:right;background:#e1ffd7">
		 Overall Total: <span id="majortotal"></span>-<span id="majorrefundtotal"></span>=<span id="majormajortotal" style="font-weight:bold"></span>
		</td>
 		 
	</tr>

</table>
 </div>
<div  id="generateloader" style="position:absolute;color:gray;bottom:-58px;left:500px;text-align:center">Generating Report.<br>Please wait<br><img src="img/loading2.gif" height="20px"></div>
<script type="text/javascript">
function alltotal(){
  	//get total tuicat
	var tuicattotal=0;
	$('.tuicattotal').each(function(){
		tuicattotal=tuicattotal+parseInt($(this).attr('value'));
	});
	$('.tuicattotal:last').html(tuicattotal).number(true,2);

	//get total misccat
	var misccattotal=0;
	$('.misccattotal').each(function(){

		misccattotal=misccattotal+parseInt($(this).attr('value'));
	});
	$('.misccattotal:last').html(misccattotal).number(true,2);

	//get total misccat
	var tfcattotal=0;
	$('.tfcattotal').each(function(){
		tfcattotal=tfcattotal+parseInt($(this).attr('value'));
	});
	$('.tfcattotal:last').html(tfcattotal).number(true,2);
 
$('.paymentrow:first .amount').each(function(){
 	var id=$(this).attr('name');

 	var total=0;
	$('.colid'+id).each(function(){	
 		total+=parseInt($(this).attr('value'));
	});
	$('.colid'+id+":last").html(total).number(true,2);
});

//get the overall total

 var overalltotal=0;
$('.overalltotalhorizontal').each(function(){
	overalltotal=overalltotal+parseInt($(this).attr('name'));
 });

$('#finaltotal').html(overalltotal).number(true,2).attr("name",overalltotal);

//get all refunded
var refunded=0;
$('.refunded').each(function(){
	refunded=refunded+parseInt($(this).attr('name'));
});
$('.refunded:last').html(refunded).number(true,2);

var majortotal=overalltotal;
var majormajortotal=majortotal-refunded;

$('#majortotal').html(majortotal).number(true,2);
$('#majorrefundtotal').html(refunded).number(true,2);
$('#majormajortotal').html(majormajortotal).number(true,2);
}
  	loader(<?php echo "$limitstart"; ?>);
	function loader(limit){
		$('#generateloader').show();
		$.ajax({
			type:'post',
			url:'inventory/loadmonthlyreport.php',
			data:{'month':'<?=$month;?>','year':'<?=$year;?>','limitstart':limit},
			success:function(data){
				$('.paymentrow:last').after(data);
				$('#generateloader').hide();
				alltotal()	
			},error:function(){
				loader(limit);
			}	
		});
	}
 </script>
<a href="inventory/printmonthlyreport.php?month=<?=$month;?>&year=<?=$year;?>" target='jakecorn'><button  class="download" style="float:right;position:absolute;bottom:-40px;right:10px"></button></a>
   