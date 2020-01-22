<?php
session_start();
?>
<style type="text/css">
	#addscholartable td{padding:5px;}
	#addscholartable #savescholar{height:30px;width:120px;margin:0 auto;display:block;}
</style>

<?php
include '../dbconfig.php';
$receipt_num=$_POST['receipt'];
$selecteddate=$_POST['selecteddate'];


?>
<div id="addcourseheader">Reprint Receipt &nbsp;<img id="addcourseload" src="img/loading.gif" style="display:none;position:relative;top:3px"><button class="closemodal" onclick="closemodal()"></button></div>
<div style="padding:10px">
<form onsubmit="return reprintreceipt2()">
<table id="addscholartable" style="width:280px;margin:0 auto">
	
	 
	<tr>
		<td style="white-space:nowrap">Receipt Number:</td><td><input type="text" required="required" id="askor" name="<?=$receipt_num;?>" value="<?=$receipt_num;?>"></td>
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
<div id="dummycon" style="display:none"></div>
<script>

function reprintreceipt2 () {
	 var or=$('#askor').val();
	var cur=$('#askor').attr('name');

 		$.ajax({
			type:'post',
			url:'inventory/attemptreprint.php',
			data:{'current_or':cur,'newor':or},
			success:function(data){
				if(data=="existed"){
					alert("ERROR: Receipt number is already existed");
				}else{					
					$('#dummycon').html(data);
					$('#overlay, #modal').hide();
					dailyreport(or,'<?=$selecteddate;?>');
				}
 			},
			error:function(){
				connection();
				$('#overlay, #modal').hide();
			}
		});
	return false;
}
</script>