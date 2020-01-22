<?php
include '../dbconfig.php';
 $user_id=$_POST['user_id'];
 $datepost2=$_POST['date'];
$datearray=explode("-", $datepost2);
$date=$datearray[1]."/".$datearray[2]."/".$datearray[0];
$getlog=mysql_query("select * from user_log where date='$date'  and user_id='$user_id'");
  	while ($logrow=mysql_fetch_array($getlog)) {
  		echo "<tr class='loglist'>";
  			echo "<td style='white-space:nowrap;vertical-align:top'>$logrow[date] $logrow[time]</td>";
  			echo "<td>$logrow[action]</td>";
  		echo "</tr>";
  	}
?>