<style>
.list:nth-child(even){background:#e5e6e6;}
.list:nth-child(odd){background:white;}
#dailydeposittable td,#dailydeposittable th{padding:2px;border:1px solid gray;}
.tuicolumn,.misccolumn,.tfcolumn,.collectioncolumn {text-align: right}
#dailydeposittable input{display:none;width:70px;padding:2px;}
.dailyaction a {cursor: pointer}
.dailyaction a:hover {text-decoration:underline;}
 </style>

<?php
include '../dbconfig.php';

$datepost=$_POST['date2'];
 $explode=explode("/", $datepost);
 $month="$explode[0]/";
 $year="/$explode[1]";
   		$getdate=mysql_query("select * from collection where date like '$month%' and date like '%$year' and remark!='Canceled' group by date asc");

?>


<table id="dailydeposittable" style="width:96%;margin:0 auto;margin-top:10px;border-collapse:collapse">
	<tr>
		<th>Date </th>
		<th>Tuition</th>
		<th>Miscellaneous</th>
		<th>Trust Fund</th>
		<th>Deposited Amount</th>
		<th>UnDeposited Amount</th>
		<th>Action</th>
	</tr>
	<?php
 		$tuitointotal=0;
		$misctotal=0;
		$tftotal=0;
		$overalltotal=0;
		$totalundeposited=0;
		$totaldeposited=0;
		while ($daterow=mysql_fetch_array($getdate)){
			echo "<tr id='col_id$daterow[col_id]'>";
				echo "<td>$daterow[date]</td>";
				$horizontaltotal=0;
				$gettuition=mysql_query("select * from daily_deposit where date='$daterow[date]'");
				$tuiamount=0;
				$miscamount=0;
				$tfamount=0;
				while ($amountrow=mysql_fetch_array($gettuition)) {
				 	$tuiamount+=$amountrow['tui_amount'];
				 	$miscamount+=$amountrow['misc_amount'];
				 	$tfamount+=$amountrow['tf_amount'];
						
				 }

				 //get the amount to be deposit

				 $amounttodeposit=mysql_query("select SUM(amount) as todeposit from collection where date='$daterow[date]' and remark='0'");
				 $todepositrow=mysql_fetch_array($amounttodeposit);
				
				 $tuitointotal+=$tuiamount;
				 $misctotal+=$miscamount;
				 $tftotal+=$tfamount;
				 $horizontaltotal+=$tuiamount+$miscamount+$tfamount;
				 echo "<td style='text-align:right'><span>".number_format($tuiamount,2)."</span><br><input type='number' value='0' class='tui dailyinput'></td>";
				 echo "<td style='text-align:right'><span>".number_format($miscamount,2)."</span><br><input type='number' value='0' class='tui dailyinput'></td>";
				 echo "<td style='text-align:right'><span>".number_format($tfamount,2)."</span><br><input type='number' value='0' class='tui dailyinput'></td>";
				
				$overalltotal+=$horizontaltotal;
				$totaldeposited+=$horizontaltotal;
				$totalundeposited+=$todepositrow['todeposit']-$horizontaltotal;
				echo "<td style='text-align:right'>".number_format($horizontaltotal,2)."</td>";
				echo "<td style='text-align:right'>".number_format($todepositrow['todeposit']-$horizontaltotal,2)."</td>";
				$totalhere=$todepositrow['todeposit']-$horizontaltotal;
				echo "<td style='text-align:center' class='dailyaction'><a style='color:blue;' onclick='editdaily($daterow[col_id])'>Update</a>&nbsp;<a style='color:blue;display:none' onclick='saveupdatedailydeposit($daterow[col_id],this,$totalhere)' date='$daterow[date]'>Save</a>&nbsp;<a style='color:blue;display:none' onclick='editdaily($daterow[col_id])'>Cancel</a></td>";
			echo "</tr>";
		}
	?>
	<tr>
		<th>Total</th>
		<th style='text-align:right' style='text-align:right'><?=number_format($tuitointotal,2);?></th>
		<th style='text-align:right'><?=number_format($misctotal,2);?></th>
		<th style='text-align:right'><?=number_format($tftotal,2);?></th>
		<th style='text-align:right'><?=number_format($totaldeposited,2);?></th>
		<th style='text-align:right'><?=number_format($totalundeposited,2);?></th>
		<th style='text-align:right'></th>
		
	</tr>
</table>
 