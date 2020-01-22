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
<div id="addcourseheader">Transfer Payment &nbsp;<img id="addcourseload" src="img/loading.gif" style="display:none;position:relative;top:3px"><button class="closemodal" onclick="closemodal()"></button></div>
<div style="padding:10px">
<form onsubmit="return savetransfer()">
<table id="addscholartable" style="width:280px;margin:0 auto">
	
	 
	<tr>
		<td style="white-space:nowrap">Receipt Number:</td><td style="position:relative">
			 <b><?=number_format($receipt_num);?></b>  
		</td>
	</tr>
	<tr>
		<td style="white-space:nowrap">Transfer to:</td><td style="position:relative"><input type="text" required="required" id="transto" onkeyup="searchtransto(this.value)" placeholder='Search Last Name/Student number'>
			<div style="display:none;position:absolute;border:1px solid #b4b4b4;box-shadow:2px 2px 5px #a3a3a3;min-width:200px;background:white;margin-top:2px" id="transtoresult">as fsdf</div>
		</td>
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

function selecttransferto(a){
	var name2=$('#rowid'+a+" td:first").html();
	var name=$('#rowid'+a+" td:last").html();
	$('#transto').val(name2+" "+name);
	$('#transto').attr('name',a);
	 $('#transtoresult').hide();
}
function savetransfer() {
	var id = $('#transto').attr('name');
 	var jake=confirm("Are you sure you want to transfer this payment ?");
	if(jake==true){
		$.ajax({
			type:'post',
			url:'inventory/savetransfer.php',
			data:{'id':id,'or':'<?=$receipt_num;?>'},
			success:function(data){
 				if(data=="not found"){
					alert("ERROR: Student not found.");
				}else{
					dailyreport();
					$('#overlay, #modal').fadeOut();
				}
 			},
			error:function(){
				connection();
 			}
		});
	}
		return false;
 
}

function searchtransto (a) {
	$('#transto').removeAttr('name');
	var img="<center><img src='img/loading2.gif' height='15px' style='margin:4px'></center>";
	$('#transtoresult').html(img);

	if(a.length>0){
		$.ajax({
			type:'post',
			url:'inventory/searchtransto.php',
			data:{'name':a},
			success:function(data){
				 $('#transtoresult').html(data).show();
 			},
			error:function(){
				connection();
 			}
		});
	}else{
		$('#transtoresult').hide();
	}
 
}
function reprintreceipt2 () {
	 var or=$('#askor').val();
	var cur=$('#askor').attr('name');

 		$.ajax({
			type:'post',
			url:'inventory/attemptreprint.php',
			data:{'current_or':cur,'newor':or},
			success:function(data){
				$('#dummycon').html(data);
				$('#overlay, #modal').hide();
				dailyreport(or,'<?=$selecteddate;?>');
 			},
			error:function(){
				connection();
				$('#overlay, #modal').hide();
			}
		});
	return false;
}
</script>