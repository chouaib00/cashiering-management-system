<?php
session_start();
ini_set('max_execution_time', 0);
$month=$_POST['month'];
$year=$_POST['year'];
$limitstart=$_POST['limitstart'];
if($limitstart==""){
	$limitstart=111111111111111;
} 
?>
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
 		$countdeptspan=0;
 		$deptaarray=array();

  		$alldept=mysql_query("select course.dept_id from schedule_of_fees,course,paymentlist,department,collection where   schedule_of_fees.course_id=course.course_id and schedule_of_fees.payment_id=paymentlist.payment_id and course.dept_id=department.dept_id and paymentlist.payment_desc='Laboratory Fee'and collection.sy='$sy' and collection.semester='$semester' group by department.dept_id order by department.dept_id asc");
	 	while ($alldeptrow=mysql_fetch_array($alldept)){
	 		$countdeptspan++;
	 		array_push($deptaarray, $alldeptrow['dept_id']);
 	 	}
  		$deptaarraycount=count($deptaarray);
 ?>
	<!-- show department under lab fee during the school year  and semester in the collectin and -->
 <?php
  
	//get student who paid
	$totalarray[0]=0;
	$overallrefund=0;
 	
 	$studpay=mysql_query("select collection.col_id,student.stud_id,collection.date,collection.receipt_num,student.lname,student.fname,remark, SUM(collection.amount) as refund from student,collection,schedule_of_fees,course,dummydata where dummydata.col_id=collection.col_id and    collection.sched_id=schedule_of_fees.sched_id and student.stud_id=collection.stud_id and date like '$month%' and date like '%$year'    group by collection.receipt_num order by dummydata.receipt_num,collection.receipt_num asc limit $limitstart,5 ") or die(mysql_error());

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
			$miscth=mysql_query("select payment_id,payment_desc from paymentlist where payment_id in ($miscarray) order by payment_id asc");
	  		 
			while ($miscthrow=mysql_fetch_array($miscth)){	
				//get the student department				 
				if($miscthrow['payment_desc']=="Laboratory Fee"){

					$studlabamount=mysql_query("select SUM(collection.amount) as amount,course.dept_id from collection,schedule_of_fees,department,course where collection.sched_id=schedule_of_fees.sched_id and schedule_of_fees.course_id=course.course_id and course.dept_id=department.dept_id and schedule_of_fees.payment_id='$miscthrow[payment_id]' and stud_id='$studpayrow[stud_id]' and  receipt_num='$studpayrow[receipt_num]'") or die(mysql_error());
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
							$mischorizontaltotal+=$studlabamountrow['amount'];
							$horizontallyamounttotal+=$studlabamountrow['amount'];												
							echo "<td $cancel  style='$strike;text-align:right'   class='tuicat tuitd amount colid$deptaarray[$deptstart]dept' value='$value' name='$deptaarray[$deptstart]dept'>".number_format($studlabamountrow['amount'],2)."</td>";
							
							
						}else{
 							echo "<td $cancel   style='$strike;text-align:right'  class='tuicat tuitd amount colid$deptaarray[$deptstart]dept;' value='0' name='$deptaarray[$deptstart]dept'>0.00</td>";
						}
						$deptstart++;
					}
				}else{	
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
						echo "<td  style='$strike' class='misccat  amount colid$miscthrow[payment_id]' name='$miscthrow[payment_id]' style='text-align:right;background:orange' value='0'>0.00</td>";
						$totalarray[$startarray]=$totalarray[$startarray]+0;
					}else{
						$totalarray[$startarray]=$totalarray[$startarray]+$miscamountrow['amount'];
						echo "<td   class='misccat amount colid$miscthrow[payment_id]' name='$miscthrow[payment_id]' style='$strike;text-align:right' value='$value'>".number_format($miscamountrow['amount'],2)."</td>";
					}
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
			<td class="tfcat tfcattotal" style="<?=$strike;?>;font-weight:bold;text-align:right" value="<?php echo "$value2"; ?>"><?=number_format($tfhorizontaltotal,2);?>	</td>
			<td class="refunded" style="<?=$strike;?>;font-weight:bold;text-align:right" name="<?=$refunded;?>"><?=number_format($refunded,2);?></td>
			<td class="overalltotalhorizontal" name="<?php echo "$horizontallyamounttotal2";?>" style="<?=$strike;?>;text-align:right;font-weight:bold"><?=number_format($horizontallyamounttotal,2);?></td>
  		</tr>
		<?php
	}
  	 $studpay=mysql_query("select collection.col_id,student.stud_id,collection.date,collection.receipt_num,student.lname,student.fname,remark, SUM(collection.amount) as refund from student,collection,schedule_of_fees,course,dummydata where dummydata.col_id=collection.col_id and    collection.sched_id=schedule_of_fees.sched_id and student.stud_id=collection.stud_id and date like '$month%' and date like '%$year'    group by collection.receipt_num order by dummydata.receipt_num,collection.receipt_num asc limit $limitstart,1") ;
 	 if(mysql_num_rows($studpay)>0){
 	 	?>

 	 	<script type="text/javascript">
	loader(<?php echo "$limitstart";?>);
	</script>

 	 	<?php
 	 }else{
 	 	?>
<script type="text/javascript">
	alltotal();
	</script>

 	 	<?php
 	 }
	?>
	
 