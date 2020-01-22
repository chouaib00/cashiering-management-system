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
<form onsubmit="return saveupdateusername()">
<table id="addscholartable" style="width:280px;margin:0 auto">
	
	<tr>
		<td></td><td> </td>
	</tr>

	<tr>
		<td style="white-space:nowrap">Name </td><td><input type="text" required="required" id="finalname" value="<?=$user['name'];?>" ></td>
	</tr>
	
	<tr>
		<td>Username</td><td style="position:relative"><input type="text" required="required" onkeyup="checkusername(this)"  required="required" id="username"   value="<?=$user['username'];?>">
		<img class="usernameimg usernameloader"  src="img/loading2.gif">
				<img class="usernameimg usernameerror"  src="img/error.png"  title="Username is already in use">
				<img class="usernameimg usernamecheck"  src="img/check.png" title="Username is good">
				<div  id="takenmsg" style="display:none;box-shadow:2px 1px 5px #a6a6a6;position:absolute;padding:7px;border-radius:4px;color:white;white-space:nowrap;background:gray;left:189px;top:1px">
				<div style="height:0;width:0;position:absolute;left:-14px;top:9px;border:7px solid transparent;border-right-color:gray;"></div>
				Username is already taken</div>
				</td>
		</td>
	</tr>
	<tr>
		<td>Current Password:</td><td><input type="password" required="required"   id="oldpassword">
			<span id="wrongpass" style="display:none;color:red;font-size:13px">Incorrect password</span>
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
	$('#wrongpass').hide();
})
function saveupdateusername(){
	var username=$('#username').val();
	var check=$('#username').attr('name');
	var password=$('#oldpassword').val();
	var name=$('#finalname').val();
 	$.ajax({
		type:'post',
		url:'my account/saveupdateusername.php',
		data:{'username':username,'password':password,'name':name},
		success:function(data){
			console.log(data);
			if(data=="error"){
				$('#wrongpass').show();
			}else{
				menu("my account/myaccount");
 				$('#overlay,#modal').hide();
			}

		},
		error:function(){
			connection();
		}
	})
	return false
}
</script>