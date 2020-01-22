<?php
session_start();
?>
<style>

.list:nth-child(even){background:#e5e6e6;}
.list:nth-child(odd){background:white;}
#dailreporttable td,#dailreporttable th{padding:2px;border:1px solid gray;}
.tuicolumn,.misccolumn,.tfcolumn,.collectioncolumn {text-align: right}
#orcon img{display:inline;cursor:pointer;opacity:1}
 tr td:hover> img{display:inline-block;opacity:0.7 }.
 .transcon {height: 15px}
 <?php
include '../dbconfig.php';

$datepost=$_POST['date'];
  $datearray=explode("-", $datepost);
 
$date=$datearray[1]."/".$datearray[2]."/".$datearray[0];
 
?>
 </style>
<?php
	$checkCancelled=mysql_query("select * from collection where date='$date' and Remark='1'");
	$countCancelled=mysql_num_rows($checkCancelled);
 
	$jake=mysql_query("select * from signatory where status='Activated'");
	$signatory=mysql_fetch_array($jake);
 
	 
?>
<table id="dailreporttable" style="width:96%;margin:0 auto;margin-bottom:60px;margin-top:10px;border-collapse:collapse">
	<tr>
	<td colspan="12" style="text-align:center;padding:5px;">NEGROS ORIENTAL STATE UNIVERSITY, BAYAWAN-STA. CATALINA CAMPUS<br> Agency</td>
	</tr>
	<tr>
		<td colspan="4" style="text-align:center">
			<p style="margin:0 0 5px 0;text-transform:uppercase"><u><?=$signatory['name'];?></u><br>
			Accountable Officer</p>
			 
		</td>

		<td colspan="4" style="text-align:center">
			<p style="margin:0 0 5px 0"><u>Cashier</u><br>
			Official Designation</p>
			 
		</td>

		<td colspan="4" style="text-align:center">
			<p style="margin:0 0 5px 0"><u>NORSU-BSC</u><br>
			Station</p>
			 
		</td>
	</tr>
	<tr>
		<th rowspan=2>
			Date
		</th>
		<th rowspan=2>
		OR No.
		</th>
		<th rowspan=2>
		Name of Payor
		</th>
		<th colspan="6">
		Nature of Collection
		</th>

		<th>
		Collection
		</th>
		<th rowspan="2">
		Remarks
		</th>
		<th rowspan="2">
		Undeposited<br>Collection
		</th>
	</tr>
	
	<tr>
		<th>
		Tuition (-75)
		</th>
		
		<td>
		Amount
		</td>
		
		<th>
		Misc (-63)
		</th>
		
		<td>
		Amount
		</td>
		
		<th>
		Trust Fund
		</th>
		<td>
		Amount
		</td>
		
		<th>
		Amount
		</th>
		 
	</tr>
	<?php

	//get the semester//
 	 

	 	$getsemester=mysql_query("select sy,semester  from collection where date='$date' group by sy");
		$countsy=mysql_fetch_array($getsemester);
		$sy=$countsy['sy'];
		$semester=$countsy['semester'];
 		//get paymentst in tuition category
	 
		$tuinotin="select schedule_of_fees.payment_id from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  ((schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester') or  (date='$date'))  and category='tui' group by schedule_of_fees.payment_id";
		$miscnotin="select schedule_of_fees.payment_id from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  ((schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester') or  (date='$date'))  and category='misc'  group by schedule_of_fees.payment_id";
		$tfnotin="select schedule_of_fees.payment_id from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  ((schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester') or  (date='$date'))  and category='tf'  group by schedule_of_fees.payment_id";
	
		$tuith=mysql_query("select * from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  ((schedule_of_fees.sy='$GLOBALS[sy]' and schedule_of_fees.semester='$semester') or  (date='$date'))  and category='tui' group by schedule_of_fees.payment_id order by schedule_of_fees.sched_id asc") or die(mysql_error());	 
	  	$miscth=mysql_query("select * from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  ((schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester') or  (date='$date'))  and  schedule_of_fees.payment_id not in ($tfnotin)  and  schedule_of_fees.payment_id not in ($tuinotin) and category='misc'  group by schedule_of_fees.payment_id")  or die(mysql_error());;
		$tfth=mysql_query("select * from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  ((schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester') or  (date='$date'))   and schedule_of_fees.payment_id not in ($tuinotin) and schedule_of_fees.payment_id not in ($GLOBALS[miscnotin]) and category='tf'  group by schedule_of_fees.payment_id")  or die(mysql_error());;


	$tuitiontotal2=0;
	$misctotal2=0;
	$tftotal2=0;
	$collectionamount=0;

	//check that the money collected in this has been depsited]$display2=""
	

	$checkdeposit=mysql_query("select * from daily_deposit where date='$date'");
	if(mysql_num_rows($checkdeposit)>0){
		$display2="style='display:none'";
	}

	$daily=mysql_query("select *,collection.receipt_num as fororeder from student,collection where student.stud_id=collection.stud_id and date='$date' group by collection.stud_id,receipt_num order by date,fororeder ASC");
	while ($dailyrow=mysql_fetch_array($daily)) {
		$cancel="";
		$display3="";
		$display2="";
		if($dailyrow['remark']=="Cancelled" || $dailyrow['remark']=="Refunded"){
			$cancel="style='text-decoration:line-through;white-space:nowrap'";
			$display2="style='display:none'";
			$display3="style='display:none'";
		} else{
			$cancel="style=' white-space:nowrap'";
		}
		//check if this receipt has been Cancelled
		$Cancelled=mysql_query("select remark from collection where date='$date' and receipt_num='$dailyrow[receipt_num]'");
		$Cancelledrow=mysql_fetch_array($Cancelled);
	echo "<tr >";
	echo "<td>$dailyrow[date]</td>";
	echo "<td $cancel  style=' width:300px' id='orcon'>$dailyrow[receipt_num]";
	if($_SESSION['type']=='admin'){
		echo "<img src='img/transfer.png' class='transcon' $display3  onclick='transferpayment($dailyrow[receipt_num],$dailyrow[stud_id])' title='Transfer this payment to another student'  > <img src='img/removeadd.png' $display2 name='$dailyrow[date]' title='Cancel receipt' onclick='cancelreceipt($dailyrow[receipt_num],this)'>&nbsp;<a $display2 target='xtine'  onclick='reprintreceipt($dailyrow[receipt_num])' hsref='inventory/repreprintreceipt.php?receipt_num=$dailyrow[receipt_num]&stud_id=$dailyrow[stud_id]'><img src='img/printagain.png'  title='Reprint this receipt'>";
	}
	echo "</td>";
	echo "<td style='text-transform:capitalize;height:20px;white-space:nowrap'>$dailyrow[lname], $dailyrow[fname]</td>";

	//get the amount of the payment
		//get the tuiton category
		$tuitotal=0;
		$horizontaltotal=0;
			$tuith=mysql_query("select * from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  ((schedule_of_fees.sy='$GLOBALS[sy]' and schedule_of_fees.semester='$semester') or  (date='$date'))  and category='tui' group by schedule_of_fees.payment_id order by schedule_of_fees.sched_id asc") or die(mysql_error());	   		while ($tuithrow=mysql_fetch_array($tuith)){
 			$getamount=mysql_query("select SUM(collection.amount) as amount from  collection,schedule_of_fees where  schedule_of_fees.sched_id=Collection.sched_id and schedule_of_fees.payment_id='$tuithrow[payment_id]' and receipt_num='$dailyrow[receipt_num]' and stud_id='$dailyrow[stud_id]' ");
			$amountrow=mysql_fetch_array($getamount);
			 // echo "- $amountrow[amount]- dec".$tuithrow['payment_desc']."==<br>";
			$tuitotal=$tuitotal+$amountrow['amount'];

			
		}
		
		$horizontaltotal=$horizontaltotal+$tuitotal;
		if($dailyrow['remark']=='0'){
			$tuitiontotal2+=$tuitotal;
			$collectionamount+=$tuitotal;
		}
		echo "<td>Tuition </td>";
		echo "<td $cancel class='dailyamount tuicolumn' name='$tuitotal'>".number_format($tuitotal,2)."</td>";


		//get the miscellaneous category
		$misctotal=0;
	  	$miscth=mysql_query("select * from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  ((schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester') or  (date='$date'))  and  schedule_of_fees.payment_id not in ($tfnotin)  and  schedule_of_fees.payment_id not in ($tuinotin) and category='misc'  group by schedule_of_fees.payment_id")  or die(mysql_error());;
	  	while ($jake=mysql_fetch_array($miscth)) {
	  		$getamount=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees where  collection.sched_id=schedule_of_fees.sched_id and  stud_id='$dailyrow[stud_id]' and receipt_num='$dailyrow[receipt_num]' and schedule_of_fees.payment_id='$jake[payment_id]'");
			$amountrow=mysql_fetch_array($getamount);
				$misctotal=$misctotal+$amountrow['amount'];
			
	  	}
		
		$horizontaltotal=$horizontaltotal+$misctotal;
		if($dailyrow['remark']=='0'){
		
		$collectionamount+=$misctotal;
		$misctotal2+=$misctotal;
		}
		echo "<td>Miscellaneous</td>";
		echo "<td $cancel class='dailyamount misccolumn' name='$misctotal'>".number_format($misctotal,2)."</td>";

		//get the trust fund category
		$tftotal=0;
		$tfth=mysql_query("select * from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  ((schedule_of_fees.sy='$sy' and schedule_of_fees.semester='$semester') or  (date='$date'))   and schedule_of_fees.payment_id not in ($tuinotin) and schedule_of_fees.payment_id not in ($GLOBALS[miscnotin]) and category='tf'  group by schedule_of_fees.payment_id")  or die(mysql_error());;
		while ($jake=mysql_fetch_array($tfth)) {
			$getamount=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees where  collection.sched_id=schedule_of_fees.sched_id and  stud_id='$dailyrow[stud_id]' and receipt_num='$dailyrow[receipt_num]' and schedule_of_fees.payment_id='$jake[payment_id]'");
			$amountrow=mysql_fetch_array($getamount);
				$tftotal=$tftotal+$amountrow['amount'];
			
		}
		
		$horizontaltotal=$horizontaltotal+$tftotal;
		if($dailyrow['remark']=='0'){
		
		$tftotal2+=$tftotal;
		$collectionamount+=$tftotal;
		}
		
		//get the undeposited amount
		
		echo "<td>Trust&nbsp;Fund</td>";
		echo "<td $cancel class='tfcolumn' name='$tftotal'>".number_format($tftotal,2)."</td>";
		echo "<td $cancel class='dailyamount collectioncolumn' name='$horizontaltotal'>".number_format($horizontaltotal,2)."</td>";
		echo "<td style=text-align:center;text-decoration:none>";

		
		if($Cancelledrow['remark']!="0"){
			echo $Cancelledrow['remark'];
		}else{
			echo "-";
		
		}
 		echo "</td>";
		echo "<td class='dailyamount' style='text-align:right;border-bottom:1px solid transparent;border-top:1px solid transparent'></td>";
	echo "</tr>";	
	}

	echo "<tr>";
 		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td class='tuicolumn' name='0' style='font-weight:bold'>".number_format($tuitiontotal2,2)."</td>";
		echo "<td></td>";
		echo "<td class='misccolumn'  name='0' style='font-weight:bold'>".number_format($misctotal2,2)."</td>";
		echo "<td></td>";
		echo "<td class='tfcolumn' name='0' style='font-weight:bold'>".number_format($tftotal2,2)."</td>";
		echo "<td class='collectioncolumn' name='0' style='font-weight:bold'>".number_format($collectionamount,2)."</td>";
		echo "<td>";

		echo "</td>";
		
		
		$totaldeposited=0;
		$updeposit=mysql_query("select  * from daily_deposit where date='$date'");
		while($updepositrow=mysql_fetch_array($updeposit)){
			$totaldeposited+=$updepositrow['tui_amount']+$updepositrow['misc_amount']+$updepositrow['tf_amount'];

		}
		$totaldeposited=$collectionamount-$totaldeposited;
		 

		echo "<td style='text-align:center;font-weight:bold'	>".number_format($totaldeposited,2)."</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td colspan='111' style='text-align:center'>CERTIFICATION</td>";
	echo "</tr>";
		echo "<td colspan='111'>";
		?>
			<div style="margin:0 auto;text-indent:25px;margin-top:10px;width:90%">I hereby certify that the foregoing is a correct and complete record of all all collections and deposits had by me in my capacity as CASHIER of NORSU, Bayawan-Sta.Catalina Campus, Bayawan City during the period as indicated in the corresponding columns.<br>
 				<br>
 				<br>
 				<div style='text-transform:uppercase;float:right;text-indent:0;padding:0;display:inline;width:200px;text-align:center;'>
 				
		<u><b><?=$signatory['name'];?></b></u><br>
		Cashier
  				</div>

 			</div>
		<?php
		echo "</td>";
	echo "</tr>";

	?>
	<tr>
		<td style="border:none" colspan="11">
			<a href="inventory/printdailyreport.php?date=<?=$date;?>" target="jakecorn"><button  class="print" style="right:22px"></button></a>
		</td>
	</tr>
</table>
<span id="secret"></span>
