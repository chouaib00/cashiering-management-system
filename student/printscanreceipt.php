<?php
session_start();
include '../dbconfig.php';

$stud_id=$_POST['stud_id'];
$sy=$_POST['sy'];
$student=mysql_query("select * from student where stud_id='$stud_id'") or die(mysql_error());
 $studentrow=mysql_fetch_array($student);
?>

<style type="text/css">
	#scancon {position: relative; padding-top:0.8in;padding-bottom:0.8in;border:1px solid gray;width:8in;margin:0 auto;margin-top:0.7in;margin-bottom:1in;}
	#ssy,#ssemester,#searchstate {padding:1px;}
	#tablecert td,#tablecert th {padding:5px;border:1px solid #bdbdbd;}
</style>

<div id="scancon">
	<div style="top:-45px;position:absolute;height:30px">
		<?php
		$getsy=mysql_query("select * from student_status where stud_id='$stud_id' group by sy order by sy");

		?>
		Select:
		SY

		<select id="ssy">
			<?php
				while ($row=mysql_fetch_array($getsy)) {
					?>
						<option><?=$row['sy'];?></option>
					<?php
				}
			?>
			
		</select>
		<button id="searchstate" onclick="scanreceipt()">Search</button>
</div> <!-- end of search statement of account -->
<div id="sheader" style="text-align:center;position:relative">
<img src="img/norsulogo.png" style="position:absolute;top:0px;left:160px">
	Republic of the Philippines<br>
	NEGROS ORIENTAL STATE UNIVERSITY<br>
	Bayawan-Sta.Catalina Campus<br>
	Bawayan City
</div>
<h1 style="text-align:center;margin-top:15px">CERTIFICATION</h1>
<?php
	$displaysemester="";
	if($semester=="I"){
		$displaysemester="1st";
	}else{
		$displaysemester="2nd";
	}
?>
<p style="margin-top:0.5in;margin-bottom:0.2in;text-indent:25px;padding:0 1in 0 1in;">
This is to certify that <span style="font-weight:bold;text-transform:capitalize"><?=$studentrow['fname']." ".$studentrow['lname'];?></span>, 
has paid <span id="displaytotal"></span> Pesos during the school year <?=$sy;?> as stated below.
</p>
	<table id="tablecert" style="width:6.5in;margin:0 auto">
	<tr>
		<th>Semester</th><th>OR Number</th><th>Date Paid</th><th>Particulars</th><th>Amount Paid</th>
		</tr>
		<?php
			$data=$_POST['data'];
			$dataarr=explode("[endline]", $data);
			$datalen=count($dataarr);			
			$start=1;
			$total=0;
			while ($start<$datalen) {
				$data2=explode("<->", $dataarr[$start]);
				$sem=$data2[0];
				$date=$data2[1];
				$or=$data2[2];
				$amount=$data2[3];
				$description=$data2[4];
				$total=$total+$amount;
				?>
					<tr>
						<td style="text-align:center"><?=$sem;?></td>
						<td style="text-align:center"><?=$or;?></td>
						<td style="text-align:center"><?=$date;?></td>
						<td style="padding-left:12px"><?=$description;?></td>
						<td style="text-align:right"><?=number_format($amount,2);?></td>
					</tr>
				<?php
				$start++;
			}

		?>
		<tr>
			<td colspan="4" style="text-align:right;font-weight:bold">Total</td>
			<td id="total" style="text-align:right;font-weight:bold">
				<?=number_format($total,2);?>
			</td>
		</tr>
	</table>
	<?php
		$day=date('dS');
		$month=date('M');
		$y=date('Y');
	?>
	<p style="padding:0 0.7in 0 0.8in;text-indent:25px;margin-top:20px">
	This certification is issued upon request of Ms./Mr. <span style="text-transform:capitalize"><?=$studentrow['lname'];?></span> for whatever purpose it may
		serve her/his best on this <?=$day;?> of <?=$month;?> <?=$y;?> at Negros Oriental State University, Bayawan-
		Sta. Catalina Campus, Bayawan City.
	</p>
	<p style="text-align:center;padding-top:0.6in">
		<u><b>MYRNA M. TONGZON</b></u><br>
		Cashier
	</p>

	<button class="print" style="position:absolute;bottom:-40px;right:10px "></button>
</div> <!--  -->

<script>

$('#displaytotal').html($('#total').html());
window.open("student/printpreviewscanreceipt.php?data=<?=$data;?>&stud_id=<?=$stud_id;?>&sy=<?=$sy;?>&semester=<?=$semester;?>","<?=date('hisa');?>");
</script>