<?php
session_start();
?>
<style type="text/css">
	#addscholartable td{padding:5px;}
	#addscholartable #savescholar{height:30px;width:120px;margin:0 auto;display:block;}
</style>

<?php
include '../dbconfig.php';
 
$getlastor=mysql_query("select * from collection  where user_id='$_SESSION[user_id]'order by col_id desc");
$lastorrow=mysql_fetch_array($getlastor);
$suggestor="";
if(mysql_num_rows($getlastor)){
	$suggestor=$lastorrow['receipt_num']+1;
}
 
$getcert=mysql_query("select * from schedule_of_fees,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and payment_desc='Certification' and sy='$_SESSION[sy]' and semester='$_SESSION[semester]'");
$certrow=mysql_fetch_array($getcert);

?>
<div id="addcourseheader">Issue Receipt &nbsp;<img id="addcourseload" src="img/loading.gif" style="display:none;position:relative;top:3px"><button class="closemodal" onclick="closemodal()"></button></div>
<div style="padding:10px">
<form onsubmit="return printstatement()">
<table id="addscholartable" style="width:280px;margin:0 auto">
	
	<tr>
		<td></td><td>Certification <b><?=number_format($certrow['amount'],2);?> only</b></td>
	</tr>

	<tr>
		<td style="white-space:nowrap">Receipt Number:</td><td><input type="text" required="required" id="askor" value="<?php printf("%07d", $suggestor);?>"></td>
	</tr>
	
	<tr>
		<td>Cash:</td><td><input type="number" required="required" onkeyup="cash2(this.value)" id="cash" class="askcash" required="required"></td>
	</tr>
	<tr>
		<td>Change:</td><td><input type="text" required="required" readonly="readonly" id="change2" ></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<button id="savescholar">OK</button>
		</td>
	</tr>
	
</table>
</form>
</div>
<div id="dummnyamount" style="display:none"><?=$certrow['amount'];?></div>
<script>
//  

function cash2(a){
	var amount=parseInt($('#dummnyamount').html());
	$('#change2').val(a-amount);
}

</script>