<?php
include '../dbconfig.php';
$tui=$_POST['tui'];
$misc=$_POST['misc'];
$tf=$_POST['tf'];
$date=$_POST['date'];
 mysql_query("insert into daily_deposit values('$date','$tui','$misc','$tf')") or die(mysql_error());

include '../rand.php';
$rand=rand();
$getdate=mysql_query("select * from collection where date='$date'");
$daterow=mysql_fetch_array($getdate);
echo "<tr id='col_id$rand'>";
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
				 $tuitointotal+=$tuiamount;
				 $misctotal+=$miscamount;
				 $tftotal+=$tfamount;
				 $horizontaltotal+=$tuiamount+$miscamount+$tfamount;
				 echo "<td style='text-align:right'><input type='number' class='tui dailyinput' value='$tuiamount'><span>".number_format($tuiamount,2)."</span></td>";
				 echo "<td style='text-align:right'><input type='number' class='tui dailyinput' value='$miscamount'><span>".number_format($miscamount,2)."</span></td>";
				 echo "<td style='text-align:right'><input type='number' class='tui dailyinput' value='$tfamount'><span>".number_format($tfamount,2)."</span></td>";
				
				$overalltotal+=$horizontaltotal;
				echo "<td style='text-align:right'>".number_format($horizontaltotal,2)."</td>";
				echo "<td style='text-align:center'><a style='color:blue;' onclick='editdaily($rand)'>Update</a>&nbsp;<a style='color:blue;display:none' onclick='saveupdatedailydeposit($rand,this)' date='$daterow[date]'>Save</a>&nbsp;<a style='color:blue;display:none' onclick='editdaily($rand)'>Cancel</a></td>";
			echo "</tr>";
			?>
	<tr>