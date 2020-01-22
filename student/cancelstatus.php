<?php
session_start();
include '../dbconfig.php';
$stud_id=$_POST['stud_id'];
$semester=$_POST['semester'];
$sy=$_POST['sy'];
if($stud_id==""){
	header("location:../index.php");
}else
?>
<style type="text/css">
	#addscholartable td{padding:5px;}
	#addscholartable #savescholar{height:30px;width:120px;margin:0 auto;display:block;}
	.yesno {height:30px;width:100px;
 
	}
	 
</style>

<?php
include '../dbconfig.php';
$getlastor=mysql_query("select * from collection order by col_id desc");
$or=mysql_fetch_array($getlastor);

$getcert=mysql_query("select * from schedule_of_fees,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and payment_desc='Certification' and sy='$_SESSION[sy]' and semester='$_SESSION[semester]'");
$certrow=mysql_fetch_array($getcert);

?>
<div name="jake" id="addcourseheader">Cancel Status &nbsp;<img id="addcourseload" src="img/loading.gif" style="display:none;position:relative;top:3px"><button class="closemodal" onclick="closemodal()"></button></div>
<div style="padding:10px;text-align:center" id="donemsg">
<form onsubmit="return savecancelstatus()">
<table id="addscholartable" style="width:280px;margin:0 auto">
	
	<tr>


		<td colspan="4" style="text-align:center">Are you sure you want to cancel this status?</td>
	</tr>

	<tr>
		<td style="white-space:nowrap">Admin Password:</td><td><input type="password" onclick="hideerror()" required="required" id="adminpassword"></td>
	</tr> 
	<tr id="errorpasscon" style="display:none">
		<td></td><td style="color:red">Password was incorrect.</td>
	</tr>
	<tr>
		<td></td>
		<td>
			<button class="yesno">Yes</button>
			</form>
			<span  onclick="canceldeletestatus()"><button class="yesno">No</button></span>
		</td>
	</tr>
	
</table>

</div>
<script>
function savecancelstatus(){
	var pass=$('#adminpassword').val();
	var loader=$('#addcourseload');
	loader.show();
 	$.ajax({
		type:'post',
		url:'student/savecancelstatus.php',
		data:{'password':pass,'stud_id':'<?=$stud_id;?>','sy':'<?=$sy;?>','semester':'<?=$semester;?>'},
		success:function(data){
 			if(data=="1"){
				$('#errorpasscon').show();
				$('#adminpassword').val("");
			}else{
				$('#donemsg').html("Status has been successfully cancelled.<br><br><button class='yesno' onclick='closemodal(<?=$stud_id;?>,11)'>Done</button>");
				$('.closemodal').hide();
			}
			loader.hide();
		},
		error:function(){
			loader.hide();
			connection();
		}
	})
	return false;
}

function hideerror(){
	$('#errorpasscon').hide();
}
function canceldeletestatus(){
 	$('#overlay,#modal').fadeOut(200);
}
</script>