<?php
session_start();
ini_set('max_execution_time', 0);
?>
<html lang="en">
<head>
<meta charset="utf-8">
	
</head>
<link rel="stylesheet" type="text/css" href="css/dailyreport.css">
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
<style type="text/css">
	.receiptdel{
		background:#ff6600;
		color:white;
		font-size:11px;
		border-radius:4px;
		border:1px solid white;
		padding:2px;
		box-shadow:1px 1px 5px gray;
	}
	.receiptdel:focus{
		box-shadow:none;
	}
	.receiptdel:hover{
		background:#ffa061;
	}

		.editdate{
		border:1px solid white;
		background:#0ea0bf;
		font-size: 11px;
		padding:2px;
		border:2px;
		color:white;
		border-radius:2px
	}
	.editdate:hover{
		background: #67cee4
	}
	.changedate{
		width:130px;
		display: none
	}
</style>
<script type="text/javascript">
	 function deletereceipt(a,b){
 	var c=confirm("Are you sure to delete this receipt?");
 	if(c==true){
		$.ajax({
			type:'post',
			url:'user account/deletereceipt.php',
			data:{'receipt_num':a,'stud_id':b},
			success:function(data){
				dailyreport()
			},
			error:function(){
				alert("Unable to connect to server. Please try again!")
			}
		})
 	}
 }

  function editdate(a){
   	var re=$(a).attr("receiptnum");
 	var st=$(a).attr("studid");
 	var c=$(a).val();
 	var con=confirm("Are you sure to update the date?");
 	if(con==true){
  		$.ajax({
 			type:'post',
 			data:{'receipt_num':re,'stud_id':st,'date':c},
 			url:'user account/editdate.php',
 			success:function(data){
  				$('.inventory').click();
 			},
 			error:function(){
 				alert("Unable to connect to server. Please try again!");
 			}
 		})
  	}
 	 
 }

function showeditdate(a){
	$('#row'+a+" div").hide();
	$('#row'+a+" input").show();
}
</script>
<div style="width:1000px;overflow:auto;margin-bottom:70px">
 <table id="dailreporttable">
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
Amount		</th>
		 
	</tr>
	 
	<?php

	//get the semester//
 	 

	 	$getsemester=mysql_query("select sy,semester  from collection where date='$date' group by sy");
		$countsy=mysql_fetch_array($getsemester);
		$sy=$countsy['sy'];
		$semester=$countsy['semester'];
 		//get paymentst in tuition category
	 
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

	$tuitiontotal2=0;
	$misctotal2=0;
	$tftotal2=0;
	$collectionamount=0;

	//check that the money collected in this has been depsited]$display2=""
	

	$checkdeposit=mysql_query("select * from daily_deposit where date='$date'");
	if(mysql_num_rows($checkdeposit)>0){
		$display2="style='display:none'";
	}

	mysql_query("truncate dummydata");
	 $studpay=mysql_query("select * from collection where date='$date' group by receipt_num") or die(mysql_error());
	 while ($row=mysql_fetch_array($studpay)){
	 		mysql_query("insert into dummydata values ('','$row[col_id]','$row[receipt_num]')");
	 }

	$daily=mysql_query("select *,collection.receipt_num from student,collection,dummydata where dummydata.col_id=collection.col_id and student.stud_id=collection.stud_id and date='$date' group by collection.stud_id,collection.receipt_num order by dummydata.receipt_num,collection.receipt_num asc");
	while ($dailyrow=mysql_fetch_array($daily)){
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
	echo "<tr >"; ?>
	<td id="row<?=$dailyrow['receipt_num'];?>"><div style='width:110px'><?=$dailyrow['date'];?> 
	<button class='editdate' title="Edit date" onclick="showeditdate('<?=$dailyrow['receipt_num'];?>')">Edit</button></div>
		<input type="date" class="changedate" receiptnum='<?=$dailyrow['receipt_num'];?>' studid='<?=$dailyrow['stud_id'];?>' onchange="editdate(this)">
	</td>
	<?php
	echo "<td $cancel  style=' width:300px' id='orcon'>$dailyrow[receipt_num]";
 
		?>
 		<button class='receiptdel' onclick="deletereceipt('<?=$dailyrow['receipt_num'];?>','<?=$dailyrow['stud_id'];?>')" title="Delete Receipt">Del</button>

		 <img src='img/transfer.png' class='transcon' <?=$display3;?>  onclick="transferpayment('<?=$dailyrow['receipt_num'];?>',<?=$dailyrow['stud_id'];?>)" title='Transfer this payment to another student'  > <img src='img/removeadd.png' <?=$display2;?> name='<?=$dailyrow['date'];?>' title='Cancel receipt' onclick="cancelreceipt('<?=$dailyrow['receipt_num'];?>',this)">&nbsp;<a <?=$display2;?> target='xtine'  onclick="reprintreceipt('<?=$dailyrow['receipt_num'];?>')" hsref="inventory/repreprintreceipt.php?receipt_num=<?=$dailyrow['receipt_num'];?>&stud_id=<?=$dailyrow['stud_id'];?>"><img src='img/printagain.png'  title='Reprint this receipt'>
	 <?php
	echo "</td>";
	echo "<td class='pname'>$dailyrow[lname], $dailyrow[fname]</td>";

	//get the amount of the payment
		//get the tuiton category
		$tuitotal=0;
		$horizontaltotal=0;
 			$getamount=mysql_query("select SUM(collection.amount) as amount from  collection,schedule_of_fees where  schedule_of_fees.sched_id=Collection.sched_id and schedule_of_fees.payment_id in ($tuiarray) and receipt_num='$dailyrow[receipt_num]' and stud_id='$dailyrow[stud_id]'  limit 1 ");
			$amountrow=mysql_fetch_array($getamount);
			 // echo "- $amountrow[amount]- dec".$tuithrow['payment_desc']."==<br>";
			$tuitotal=$tuitotal+$amountrow['amount'];

			
				
		$horizontaltotal=$horizontaltotal+$tuitotal;
		if($dailyrow['remark']=='0'){
			$tuitiontotal2+=$tuitotal;
			$collectionamount+=$tuitotal;
		}
		echo "<td>Tuition </td>";
		echo "<td $cancel class='dailyamount tuicolumn' name='$tuitotal'>".number_format($tuitotal,2)."</td>";


		//get the miscellaneous category
		$misctotal=0;
  	  		$getamount=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees where  collection.sched_id=schedule_of_fees.sched_id and  stud_id='$dailyrow[stud_id]' and receipt_num='$dailyrow[receipt_num]' and schedule_of_fees.payment_id in  ($miscarray)  limit 1");
			$amountrow=mysql_fetch_array($getamount);
				$misctotal=$misctotal+$amountrow['amount'];
			
	  	 		
		$horizontaltotal=$horizontaltotal+$misctotal;
		if($dailyrow['remark']=='0'){
		
		$collectionamount+=$misctotal;
		$misctotal2+=$misctotal;
		}
		echo "<td>Miscellaneous</td>";
		echo "<td $cancel class='dailyamount misccolumn' name='$misctotal'>".number_format($misctotal,2)."</td>";

		//get the trust fund category
		$tftotal=0;
			$getamount=mysql_query("select SUM(collection.amount) as amount from collection,schedule_of_fees where  collection.sched_id=schedule_of_fees.sched_id and  stud_id='$dailyrow[stud_id]' and receipt_num='$dailyrow[receipt_num]' and schedule_of_fees.payment_id in ($tfarray) limit 1");
			$amountrow=mysql_fetch_array($getamount);
				$tftotal=$tftotal+$amountrow['amount'];
			
		
		$horizontaltotal=$horizontaltotal+$tftotal;
		if($dailyrow['remark']=='0'){
		
		$tftotal2+=$tftotal;
		$collectionamount+=$tftotal;
		}
		
		//get the undeposited amount
		
		echo "<td>Trust&nbsp;Fund</td>";
		echo "<td $cancel class='tfcolumn' name='$tftotal'>".number_format($tftotal,2)."</td>";
		echo "<td $cancel class='dailyamount collectioncolumn' name='$horizontaltotal'>".number_format($horizontaltotal,2)."</td>";
		echo "<td class='remark'>";

		
		if($Cancelledrow['remark']!="0"){
			echo $Cancelledrow['remark'];
		}else{
			echo "-";
		
		}
 		echo "</td>";
		echo "<td class='dailyamount'></td>";
	echo "</tr>";	
	}

	echo "<tr>";
 		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td class='tuicolumn' name='0'>".number_format($tuitiontotal2,2)."</td>";
		echo "<td></td>";
		echo "<td class='misccolumn'  name='0'>".number_format($misctotal2,2)."</td>";
		echo "<td></td>";
		echo "<td class='tfcolumn' name='0''>".number_format($tftotal2,2)."</td>";
		echo "<td class='collectioncolumn' name='0'>".number_format($collectionamount,2)."</td>";
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
</div>
 </html>
<span id="secret"></span>
