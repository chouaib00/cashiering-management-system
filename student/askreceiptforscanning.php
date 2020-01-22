<?php
session_start();
?>
<style type="text/css">
	#addscholartable td{padding:5px;}
	#addscholartable #savescholar{height:30px;width:120px;margin:0 auto;display:block;}
</style>

<?php
include '../dbconfig.php';
$stud_id=$_POST['stud_id'];
$data=$_POST['data'];
$getlastor=mysql_query("select * from collection order by col_id desc");
$or=mysql_fetch_array($getlastor);

$getcert=mysql_query("select * from schedule_of_fees,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and payment_desc='Certification' and sy='$_SESSION[sy]' and semester='$_SESSION[semester]'");
$certrow=mysql_fetch_array($getcert);

?>
<div id="addcourseheader">Issue Receipt &nbsp;<img id="addcourseload" src="img/loading.gif" style="display:none;position:relative;top:3px"><button class="closemodal" onclick="closemodal()"></button></div>
<div style="padding:10px">
<form onsubmit="return printscanreceipt()">
<table id="addscholartable" style="width:280px;margin:0 auto">
	
	<tr>
		<td></td><td>Certification <b><?=number_format($certrow['amount'],2);?> only</b></td>
	</tr>

	<tr>
		<td style="white-space:nowrap">Receipt Number: </td><td><input type="text" required="required" id="askor" value="<?php printf("%07d", $or['receipt_num']+1);?>"></td>
	</tr>
	
	<tr>
		<td>Cash:</td><td><input type="number" required="required" onkeyup="cash2(this.value)" id="cash" class="askcash" required="required"></td>
	</tr>
	<tr>
		<td>Change:</td><td><input type="text"   id="change2" readonly="readonly"></td>
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
<div id="dummnyamount2" style="display:none"></div>
<script>

function printscanreceipt(){
	var cash=$('.askcash').val();
	var or=$('#askor').val();
 	var change=parseInt($('#change2').val());

		if(change>=0){
			$.ajax({
				type:'post',
				url:'checkor.php',
				data:{'receipt_num':or},
				success:function(data){
						if(data=="existed"){
							alert("O.R. number already existed.")
						}else{
								window.open("student/printpreviewscanreceipt.php?data=<?=$data;?>&stud_id=<?=$stud_id;?>&sy=<?=$sy;?>&semester=<?=$semester;?>&or="+or+"&cash="+cash+"&change="+change,"<?=date('hisa');?>").focus();
								$('#overlay,#modal').hide();
						}
				},error:function(){
					printscanreceipt();
				}
			})

		}else{
				$('.askcash').css("border","1px solid red");
				alert("Insufficient money");
		}
	return false
}

function cash2(a){
	var amount=parseInt($('#dummnyamount').html());
	$('#change2').val(a-amount);
}

</script>