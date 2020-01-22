<?php
session_start();
?>
<style type="text/css">
	#addscholartable td{padding:5px;}
	#addscholartable #savescholar{height:30px;width:120px;margin:0 auto;display:block;}
</style>

<?php
include '../dbconfig.php';
$semester=$_POST['semester'];
$sy=$_POST['sy'];
$stud_id=$_POST['stud_id'];

$getamount=mysql_query("select SUM(amount) as amount from collection where receipt_num='$receipt_num' and receipt_num='$receipt_num'");
$amount=mysql_fetch_array($getamount);
?>
<div id="addcourseheader">Transfer Payment&nbsp;<img id="addcourseload" src="img/loading.gif" style="display:none;position:relative;top:3px"><button class="closemodal" onclick="closemodal()"></button></div>
<div style="padding:10px">
<form onsubmit="return savetransfercancelstatus()">
<table id="addscholartable" style="width:280px;margin:0 auto">
	 	<tr>
	 		<td colspan="2">This can be transfered only to officially enrolled student in <?=$sy." ".$semester;?>.</td>
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
function savetransfercancelstatus(){
	var id = $('#transto').attr('name');
 	var jake=confirm("Are you sure you want to transfer this payment ?");
	if(jake==true){
		$.ajax({
			type:'post',
			url:'student/savetransfercancelstatus.php',
			data:{'from_stud_id':'<?=$stud_id;?>','to_stud_id':id,'semester':'<?=$semester;?>','sy':'<?=$sy;?>'},
			success:function(data){
				
   				if(data=="not found"){
					alert("ERROR: Student not found.");
				}else{
					$('#overlay, #modal').fadeOut();
					paymenthistory('<?=$stud_id;?>');
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
			data:{'name':a,'fromname':'<?=$stud_id;?>'},
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
 
</script>