<style>

.list:nth-child(even){background:#e5e6e6;}
.list:nth-child(odd){background:white;}
#dailreporttable td,#dailreporttable th{padding:2px;border:1px solid gray;}
.tuicolumn,.misccolumn,.tfcolumn,.collectioncolumn {text-align: right}
#orcon img{display:inline;cursor:pointer;opacity:1}
 tr td:hover> img{display:inline-block;opacity:0.7 }
 <?php
include '../dbconfig.php';

$datepost=$_POST['date'];
$datepost2=$_POST['date2'];
$datepost3=$_POST['date3'];
$datearray=explode("-", $datepost);
if($datepost){
$date=$datearray[1]."/".$datearray[2]."/".$datearray[0];
}elseif($datepost2){
	$date=$_POST['date2'];
 
}elseif($datepost3){
	$date=$_POST['date3'];
 
}
?>
 </style>
<?php
	$checkcanceled=mysql_query("select * from collection where date='$date' and Remark='1'");
	$countcanceled=mysql_num_rows($checkcanceled);
	 
?>
<table id="dailreporttable" style="width:96%;margin:0 auto;margin-top:10px;border-collapse:collapse">
	<tr>
		<td colspan="4" style="text-align:center">
			<p style="margin:0 0 5px 0"><u><?=$date;?>MYRNA M. TONGZON</u><br>
			Accountable Officer</p>
			
			<p style="margin:0"><u>MYRNA M. TONGZON</u><br>
			Accountable Officer</p>
		</td>

		<td colspan="4" style="text-align:center">
			<p style="margin:0 0 5px 0"><u>Cashier</u><br>
			Official Designation</p>
			
			<p style="margin:0"><u>Cashier</u><br>
			Official Designation</p>
		</td>

		<td colspan="4" style="text-align:center">
			<p style="margin:0 0 5px 0"><u>NORSU-BSC</u><br>
			Station</p>
			
			<p style="margin:0"><u>NORSU-BSC</u><br>
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
	$daily=mysql_query("select * from student,collection where student.stud_id=collection.stud_id and date='$date' group by receipt_num order by date");
	while ($dailyrow=mysql_fetch_array($daily)) {
		$cancel="";
		$display2="";
		if($dailyrow['remark']=="Canceled" || $dailyrow['remark']=="Refunded"){
			$cancel="style='text-decoration:line-through;white-space:nowrap'";
			$display2="style='display:none'";
		} else{
			$cancel="style=' white-space:nowrap'";
		}
		//check if this receipt has been canceled
		$canceled=mysql_query("select remark from collection where date='$date' and receipt_num='$dailyrow[receipt_num]'");
		$canceledrow=mysql_fetch_array($canceled);
	echo "<tr >";
	echo "<td>$dailyrow[date]</td>";
	echo "<td $cancel  style=' width:300px' id='orcon'>$dailyrow[receipt_num]&nbsp;<img src='img/removeadd.png' $display2 title='Cancel receipt.' onclick='cancelreceipt($dailyrow[receipt_num])'>&nbsp;<a $display2 target='xtine'  onclick='reprintreceipt($dailyrow[receipt_num])' hsref='inventory/repreprintreceipt.php?receipt_num=$dailyrow[receipt_num]&stud_id=$dailyrow[stud_id]'><img src='img/printagain.png'  title='Reprint this receipt.'> </td>";
	echo "<td style='text-transform:capitalize;height:20px;white-space:nowrap'>$dailyrow[lname], $dailyrow[fname]</td>";

	//get the amount of the payment
		//get the tuiton category
		$tuitotal=0;
		$horizontaltotal=0;
$tuith=mysql_query("select * from schedule_of_fees,paymentlist,collection where collection.sched_id=schedule_of_fees.sched_id and  paymentlist.payment_id=schedule_of_fees.payment_id and  ((schedule_of_fees.sy='$GLOBALS[sy]' and schedule_of_fees.semester='$semester') or  (date='$date'))  and category='tui' group by schedule_of_fees.payment_id order by schedule_of_fees.sched_id asc") or die(mysql_error());	   		while ($tuithrow=mysql_fetch_array($tuith)){
 			$getamount=mysql_query("select collection.amount from  collection,schedule_of_fees where  schedule_of_fees.sched_id=Collection.sched_id and schedule_of_fees.payment_id='$tuithrow[payment_id]' and receipt_num='$dailyrow[receipt_num]' and stud_id='$dailyrow[stud_id]' ");
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
	  		$getamount=mysql_query("select collection.amount from collection,schedule_of_fees where  collection.sched_id=schedule_of_fees.sched_id and  stud_id='$dailyrow[stud_id]' and receipt_num='$dailyrow[receipt_num]' and schedule_of_fees.payment_id='$jake[payment_id]'");
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
			$getamount=mysql_query("select collection.amount from collection,schedule_of_fees where  collection.sched_id=schedule_of_fees.sched_id and  stud_id='$dailyrow[stud_id]' and receipt_num='$dailyrow[receipt_num]' and schedule_of_fees.payment_id='$jake[payment_id]'");
			$amountrow=mysql_fetch_array($getamount);
				$tftotal=$tftotal+$amountrow['amount'];
			
		}
		
		$horizontaltotal=$horizontaltotal+$tftotal;
		if($dailyrow['remark']=='0'){
		
		$tftotal2+=$tftotal;
		$collectionamount+=$tftotal;
		}
		
		//get the undeposited amount
		
		echo "<td>Trust Fund</td>";
		echo "<td $cancel class='tfcolumn' name='$tftotal'>".number_format($tftotal,2)."</td>";
		echo "<td $cancel class='dailyamount collectioncolumn' name='$horizontaltotal'>".number_format($horizontaltotal,2)."</td>";
		echo "<td style=text-align:center;text-decoration:none>";

		
		if($canceledrow['remark']!="0"){
			echo $canceledrow['remark'];
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
		$updeposit=mysql_query("select * from daily_deposit where date='$date'");
		$updepositrow=mysql_fetch_array($updeposit);
		$totaldeposited=$updepositrow['tui_amount']+$updepositrow['misc_amount']+$updepositrow['tf_amount'];
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
 				<div style='float:right;text-indent:0;padding:0;display:inline;width:200px;text-align:center;'>
 				<u>MYRNA M. TONGZON</u><br>
 				Name and Signature<br><br><br>
 				</div>

 			</div>
		<?php
		echo "</td>";
	echo "</tr>";

	?>
	<tr>
		<td style="border:none" colspan="11">
			<a href="inventory/printdailyreport.php?date=<?=$date;?>" target="jakecorn"><button style="float:right;padding:10px;margin:0">Print</button></a>
		</td>
	</tr>
</table>
<span id="secret"></span>
<script>
 function reprintreceipt(a){
 		$('#overlay, #modal').show();
 		$.ajax({
			type:'post',
			url:'inventory/asktoreprintreceipt.php',
			data:{'receipt':a,'selecteddate':'<?=$date;?>'},
			success:function(data){
				$('#addcoursecon').html(data);

 			},
			error:function(){
				connection();
				$('#overlay, #modal').hide();
			}
		});

 }
function cancelreceipt(a){
	var b=confirm("Are you sure you want to cancel this receipt");
	if(b==true){
		$.ajax({
			type:'post',
			url:'inventory/cancelreceipt.php',
			data:{'receipt':a},
			success:function(data){
				dailyreport(a,'<?=$date;?>');
			},
			error:function(){
				connection();
			}
		});
	}
}
	
	$(function() {
		
		//total tuition
		// var tuicolumn=0;
		// $('.tuicolumn').each(function(){
		// 	tuicolumn=tuicolumn+parseInt($(this).attr('name'));
		// });
		// $('.tuicolumn:last').html(tuicolumn).number(true,2);

		// //total miscellaneous
		// var misccolumn=0;
		// $('.misccolumn').each(function(){
		// 	misccolumn=misccolumn+parseInt($(this).attr('name'));
		// });
		// $('.misccolumn:last').html(misccolumn).number(true,2);

		// //total trust fund
		// var tfcolumn=0;
		// $('.tfcolumn').each(function(){
		// 	tfcolumn=tfcolumn+parseInt($(this).attr('name'));
		// });
		// $('.tfcolumn:last').html(tfcolumn).number(true,2);

		// //total collection column
		// var collectioncolumn=0;
		// $('.collectioncolumn').each(function(){
		// 	collectioncolumn=collectioncolumn+parseInt($(this).attr('name'));
		// });
		// $('.collectioncolumn:last').html(collectioncolumn).number(true,2);

	});

	$('[type=date]').val("1/2/3");
</script>
