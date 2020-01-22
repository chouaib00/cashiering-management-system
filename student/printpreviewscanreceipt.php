<?php
session_start();
include '../dbconfig.php';
include '../numbertoword.php';
$stud_id=$_REQUEST['stud_id'];
$cash=$_REQUEST['cash'];
$stud_id=$_REQUEST['stud_id'];

$sy=$_SESSION['sy'];
$semester=$_SESSION['semester'];
$student=mysql_query("select * from student where student.stud_id='$stud_id'") or die(mysql_error());
$studentrow=mysql_fetch_array($student);
?>
<title>&nbsp;</title>
<meta charset="utf-8">
<style type="text/css">
	#scancon {font-family:tahoma;font-size:14px;position: relative;width:8in;margin:0 auto;}
	#ssy,#ssemester,#searchstate {padding:1px;}
	#tablecert td,#tablecert th {padding:5px;border:1px solid #bdbdbd;}
	@media print { 
*{font-size:14px;}
}
</style>
<script type="text/javascript" src="../js/jquery.min.js"></script>

<div id="scancon">

<div id="sheader" style="text-align:center;position:relative">
<img src="../img/norsulogo.png" height="60px" style="position:absolute;top:-9px;left:160px">
	Republic of the Philippines <br>
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
has paid <span id="displaytotal"></span> during the school year <?=$sy;?> as stated below.
</p>
	<table id="tablecert" style="border-collapse:collapse;width:6.5in;margin:0 auto">
	<tr>
		<th>Semester</th><th>OR Number </th><th>Date Paid</th><th>Particulars</th><th>Amount Paid</th>
		</tr>
		<?php
			$data=$_REQUEST['data'];
			$dataarr=explode("[endline]", $data);
			$datalen=count($dataarr);			
			$start=1;
			$total=0;
			while ($start<$datalen) {
				$data2=explode("<->", $dataarr[$start]);
				$sem=$data2[0];
				$date=$_SESSION['date'];
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
			<td id="total" name="<?=receivenumber($total);?>" style="text-align:right;font-weight:bold">
				<?=number_format($total,2);?>
			</td>
		</tr>
	</table>
	<?php
		$day=date('dS');
		$month=date('M');
		$y=date('Y');
		$or2=$_REQUEST['or'];
	?>
	<p style="padding:0 0.7in 0 0.8in;text-indent:25px;margin-top:20px">
	This certification is issued upon request of Ms./Mr. <span style="text-transform:capitalize"><?=$studentrow['lname'];?></span> for whatever purpose it may
		serve her/his best on this <?=$day;?> of <?=$month;?> <?=$y;?> at Negros Oriental State University, Bayawan-
		Sta. Catalina Campus, Bayawan City.
	</p>
	<p style="text-align:center;padding-top:0.5in;text-transform:uppercase">
	<?php
	$jake=mysql_query("select * from signatory where status='Activated'");
	$name=mysql_fetch_array($jake);
	?>
		<u><b><?=$name['name'];?></b></u><br>
		Cashier
	</p>
</div> <!--  -->

<script>
$('#displaytotal').html($('#total').attr('name')+" ("+$('#total').html()+")");
window.print();
   				
  		 
setTimeout(function(){
	window.open('../printreceipt2.php?name=<?=$studentrow[lname];?>, <?=$studentrow[fname];?> <?=$studentrow[acronym];?> <?=$studentrow[year_level];?>&date=<?=$date;?>&or=<?=$or2;?>&cash=<?=$cash;?>&change=<?=$change;?>&stud_id=<?=$stud_id;?>',"<?=date('hisa');?>");
},0);
setTimeout(function(){
	window.close();
},123);
  </script>