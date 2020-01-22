<?php
session_start();
?>
<style type="text/css">
	#addscholartable td{padding:5px;}
	#addscholartable #savescholar{height:30px;width:120px;margin:0 auto;display:block;}
</style>

<?php
include 'dbconfig.php';
$getlastor=mysql_query("select * from collection order by col_id desc");
$or=mysql_fetch_array($getlastor);

$getcert=mysql_query("select * from schedule_of_fees,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and payment_desc='Certification' and sy='$_SESSION[sy]' and semester='$_SESSION[semester]'");
$certrow=mysql_fetch_array($getcert);

?>
<div id="addcourseheader">Add Signatory &nbsp;<img id="addcourseload" src="img/loading.gif" style="display:none;position:relative;top:3px"><button class="closemodal" onclick="closemodal()"></button></div>
<div style="padding:10px">
<form onsubmit="return savesignatory()">
<table id="addscholartable" style="width:280px;margin:0 auto">
	
	 

	<tr>
		<td style="white-space:nowrap">Name: </td><td><input type="text" required="required" id="signame"></td>
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
function savesignatory () {
	var name=$('#signame').val();
	$.ajax({
		type:'post',
		url:'saveaddsignatory.php',
		data:{'name':name},
 		success:function(data){
 			menu("settings");
 			$('#overlay,#modal').hide();
  
 		},
		error:function(){
			connection();
 		}

	});
	return false
}
</script>