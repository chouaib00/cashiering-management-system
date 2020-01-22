<?php
session_start();
include '../dbconfig.php';
$user_id=$_SESSION['user_id'];
$getinfo=mysql_query("select * from user where user_id='$user_id'");
$row=mysql_fetch_array($getinfo);
?>
<style type="text/css">
	#myacounttable * {margin:0;padding:0;}
	#myacounttable td { vertical-align:top; padding:4px;border:1px solid #dbdbdb;}
	#myacounttable input {display:none;border:1px solid #a6a5a5;padding:3px;width:170px;}
 /*	#myacounttable span {display:none;}
*/	#myacounttable #usersavebut {height:30px;width:170px;}
	.editpass {display:none;}
	.usernameimg {display:none;position:absolute;height:15px;left:190px;top:11px}
	.edituseraccount {float:right;color:blue;font-size:12px}
	.edituseraccount:hover {text-decoration:underline;cursor:pointer;}
</style>
<script type="text/javascript" src="js/myaccount.js"></script>

<div style="width:400px;margin:0 auto;border:1px solid gray">
	<div  id="success" style="display:none;background:#d8ffda;color:green;padding:4px;text-align:center;border:1px solid #51c957;margin-bottom:5px">Successfully update.</div>
	<form  onsubmit="return saveupdateusername()">
	
	<div style="padding:8px;background:url(img/userlistheader.png);color:white"><b>Account Info</b> <div style="clear:both;"></div></div>		
		<div style="padding:10px">
		<table id="myacounttable"   style=" margin:0 auto;width:100%">
			
			
			<tr>
				<td>Name:</td><td style="width:240px"><span><?=$row['name'];?></span>
				<input type="text" id="name" value="<?=$row['name'];?>">
				</td>	
			</tr>
			<tr>
				<td>Designation:</td><td style="width:240px"><span><?=$row['type'];?></span>
 				</td>	
			</tr>

			<tr>
				<td>Username:</td><td style="position:relative"><span id="unamespan" ><?=$row['username'];?></span><a class="edituseraccount" onclick="editusername(this)">Edit</a> 
						
			</tr>

			<tr id="currentpass">
				<td>Password:</td><td><span>******** </span><a class="edituseraccount" onclick="editpassword(this)">Edit</a></td>	
			</tr>

			<tr class="editpass current">
				<td style="white-space:nowrap" >Current Password:</td><td><a class="editpassword" onclick="editpassword(this)">Cancel	</a>
 				</td>	
			</tr>

			<tr class="editpass">
				<td style="white-space:nowrap">New Password:</td><td></td>	
			</tr>

			<tr class="editpass">
				<td style="white-space:nowrap" >Confirm Password:</td><td><input type="password" id="confirmpassword"    required="required">
				<span class="msg matching" style="color:red;font-size:13px;"><br>Password did not match</span>
				</td>	
			</tr>

			<tr id="userbutcon" style="display:none">
				<td></td><td><button id="usersavebut">Save Update</button></td>	
			</tr>

		</table>
	</div>
	</form>
</div>
<script>

function editpassword(a){
 $('#overlay,#modal').show();
 $.ajax({
	type:'post',
	url:'my account/editpassword.php',	 
	success:function(data){
			 $('#addcoursecon').html(data);
	 },
	error:function(){
		connection();
	}
})
} 

function editusername(a){
 $('#overlay,#modal').show();
 $.ajax({
	type:'post',
	url:'my account/editusername.php',	 
	success:function(data){
			 $('#addcoursecon').html(data);
	 },
	error:function(){
		connection();
	}
})
} 

function saveupdateusername(){
	var username=$('#username').val();
	var check=$('#username').attr('name');
	var password=$('#password').val();
	return false
}
</script>