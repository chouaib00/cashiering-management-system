<?php
session_start();
?>
<style type="text/css">
	#addscholartable td{padding:5px;}
	#addscholartable #savescholar{height:30px;width:120px;margin:0 auto;display:block;}
</style>

<?php
include '../dbconfig.php';
 $getuser=mysql_query("select * from user where user_id='$_SESSION[user_id]'");
 $user=mysql_fetch_array($getuser);
?>
<div id="addcourseheader"> Update Account &nbsp;<img id="addcourseload" src="img/loading.gif" style="display:none;position:relative;top:3px"><button class="closemodal" onclick="closemodal()"></button></div>
<div style="padding:10px">
<form onsubmit="return saveupdatepassword()">
<table id="addscholartable" style="width:280px;margin:0 auto">
	
	<tr>
		<td></td><td> </td>
	</tr>

	<tr>
		<td style="white-space:nowrap">Current Password </td><td><input type="password" required="required" id="curpassword" >
		<span id="wrongpass" style="color:red;font-size:13px;display:none">Incorrect Password</span>
			</td>
	</tr>
	
	<tr>
		<td>New Password</td><td style="position:relative"><input type="password" required="required"   required="required" id="newpassword" >
  
		</td>
	</tr>
	<tr>
		<td>Confirm Password:</td><td><input type="password" required="required"   id="confirm">
			<span id="didnotmatch" style="display:none;color:red;font-size:13px">Password did not match</span>
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
<div id="dummnyamount" style="display:none"><?=$certrow['amount'];?></div>
<script>
$('#addscholartable input').focus(function(){
	$('#addscholartable span').hide();
})
function saveupdatepassword(){
	var password=$('#curpassword').val();
 	var newpassword=$('#newpassword').val();
 	var newpassword=$('#newpassword').val();
 	var confirm=$('#confirm').val();

 	if(confirm==newpassword){
	  	$.ajax({
			type:'post',
			url:'my account/saveupdatepassword.php',
			data:{'newpassword':newpassword,'password':password},
			success:function(data){
				console.log(data);
				if(data=="error"){
 					$('#wrongpass').show();
 				}else{
					$('#success div').show();

  					$('#overlay,#modal').hide();
				}

			},
			error:function(){
				connection();
			}
		})
	  }else{
	  	$('#didnotmatch').show();
 	  }
	return false
}
</script>