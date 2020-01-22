<style>
.list:nth-child(even){background:#e5e6e6;}
.list:nth-child(odd){background:white;}
#dailreporttable td,#dailreporttable th{padding:2px;border:1px solid gray;}
.tuicolumn,.misccolumn,.tfcolumn,.collectioncolumn {text-align: right}
<?php
include 'dbconfig.php';
$sy='2014-2015';
$semester='I';
$date='01/01/2015';
?>
 </style>
<table id="dailreporttable" style="border-collapse:collapse">
	<tr>
		<td colspan="4" style="text-align:center">
			<p style="margin:0 0 5px 0"><u>MYRNA M. TONGZON</u><br>
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
		Undeposited<br>Collection
		</th>
	</tr>
	
	<tr>
		<th>
		Tuition (-75)
		</th>
		
		<td>
		
		</td>
		
		<th>
		Misc (-63)
		</th>
		
		<td>
		
		</td>
		
		<th>
		Trust Fund
		</th>
		<td>
		
		</td>
		
		<th>
		Amount
		</th>
		 
	</tr>
	<?php
	$daily=mysql_query("select * from student,collection where student.stud_id=collection.stud_id and semester='$semester' and sy='$sy' and date='$date' group by receipt_num order by date");
	while ($dailyrow=mysql_fetch_array($daily)) {
	echo "<tr>";
	echo "<td>$dailyrow[date]</td>";
	echo "<td>$dailyrow[receipt_num]</td>";
	echo "<td style='text-transform:capitalize;height:20px;white-space:nowrap'>$dailyrow[lname], $dailyrow[fname]</td>";

	//get the amount of the payment
		//get the tuiton category
		$tuitotal=0;
		$horizontaltotal=0;
		$getamount=mysql_query("select collection.amount from collection,schedule_of_fees where  collection.sched_id=schedule_of_fees.sched_id and  stud_id='$dailyrow[stud_id]' and collection.sy='$sy' and collection.semester='$semester' and receipt_num='$dailyrow[receipt_num]' and category='tui'");
		while ($amountrow=mysql_fetch_array($getamount)) {
			$tuitotal=$tuitotal+$amountrow['amount'];
		}
		$horizontaltotal=$horizontaltotal+$tuitotal;
		echo "<td>Tuition</td>";
		echo "<td class='dailyamount tuicolumn' name='$tuitotal'>".number_format($tuitotal,2)."</td>";


		//get the miscellaneous category
		$misctotal=0;
		$getamount=mysql_query("select collection.amount from collection,schedule_of_fees where  collection.sched_id=schedule_of_fees.sched_id and  stud_id='$dailyrow[stud_id]' and collection.sy='$sy' and collection.semester='$semester' and receipt_num='$dailyrow[receipt_num]' and category='misc'");
		while ($amountrow=mysql_fetch_array($getamount)) {
			$misctotal=$misctotal+$amountrow['amount'];
		}
		$horizontaltotal=$horizontaltotal+$misctotal;
		echo "<td>Miscellaneous</td>";
		echo "<td class='dailyamount misccolumn' name='$misctotal'>".number_format($misctotal,2)."</td>";

		//get the trust fund category
		$tftotal=0;
		$getamount=mysql_query("select collection.amount from collection,schedule_of_fees where  collection.sched_id=schedule_of_fees.sched_id and  stud_id='$dailyrow[stud_id]' and collection.sy='$sy' and collection.semester='$semester' and receipt_num='$dailyrow[receipt_num]' and category='tf'");
		while ($amountrow=mysql_fetch_array($getamount)) {
			$tftotal=$tftotal+$amountrow['amount'];
		}
		$horizontaltotal=$horizontaltotal+$tftotal;
		echo "<td>Trust Fund</td>";
		echo "<td class='tfcolumn' name='$tftotal'>".number_format($tftotal,2)."</td>";
		echo "<td class='dailyamount collectioncolumn' name='$horizontaltotal'>".number_format($horizontaltotal,2)."</td>";
		echo "<td class='dailyamount'>".number_format($horizontaltotal,2)."</td>";
	echo "</tr>";	
	}

	echo "<tr>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td></td>";
		echo "<td class='tuicolumn' name='0'>0</td>";
		echo "<td></td>";
		echo "<td class='misccolumn'  name='0'>0</td>";
		echo "<td></td>";
		echo "<td class='tfcolumn' name='0'>0</td>";
		echo "<td class='collectioncolumn' name='0'>0</td>";
		echo "<td>123</td>";
	echo "</tr>";
	echo "<tr>";
		echo "<td colspan='11' style='text-align:center'>CERTIFICATION</td>";
	echo "</tr>";
		echo "<td colspan='11'>";
		?>
			<div style="margin:0 auto;text-indent:25px;margin-top:10px;width:800px">I hereby certify that the foregoing is a correct and complete record of all all collections and deposits had by me in my capacity as CASHIER of NORSU, Bayawan-Sta.Catalina Campus, Bayawan City during the period as indicated in the corresponding columns.<br>
 				<br>
 				<br>
 				<div style='float:right;text-indent:0;padding:0;display:inline;width:200px;text-align:center;'>
 				<u>MYRNA M. TONGZON</u><br>
 				Name and Signature
 				</div>

 			</div>
		<?php
		echo "</td>";
	echo "</tr>";

	?>
	
</table>
<script type="text/javascript" src='js/jquery.min.js'></script>
<script>
	$(function() {
		
		//total tuition
		var tuicolumn=0;
		$('.tuicolumn').each(function(){
			tuicolumn=tuicolumn+parseInt($(this).attr('name'));
		});
		$('.tuicolumn:last').html(tuicolumn);

		//total miscellaneous
		var misccolumn=0;
		$('.misccolumn').each(function(){
			misccolumn=misccolumn+parseInt($(this).attr('name'));
		});
		$('.misccolumn:last').html(misccolumn);

		//total trust fund
		var tfcolumn=0;
		$('.tfcolumn').each(function(){
			tfcolumn=tfcolumn+parseInt($(this).attr('name'));
		});
		$('.tfcolumn:last').html(tfcolumn);

		//total collection column
		var collectioncolumn=0;
		$('.collectioncolumn').each(function(){
			collectioncolumn=collectioncolumn+parseInt($(this).attr('name'));
		});
		$('.collectioncolumn:last').html(collectioncolumn);

	});
</script>
