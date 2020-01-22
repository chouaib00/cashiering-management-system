<?php
session_start();
if(isset($_SESSION['type'])){
	header("location:home.php");
}else
?>
<html<!DOCTYPE html>
<html>
<head>
	<title></title>
	<script type="text/javascript" src='js/jquery.min.js'></script>
</head>
<body bgproperties='fixed' background="img/bodybg2.png" style="background-size:100% 100%">
<link href="css/style.css" type="text/css" rel="stylesheet"></link>
<link href="css/start.css" type="text/css" rel="stylesheet"></link>
<div style="position:absolute;min-height:100%;width:100%;padding:0;margin:0">

<?php

include "startheader.php";
?>
<style type="text/css">
	.errorimg {display: none;position:absolute;height:15px;top:14px;right:11;}
</style>
<div style="position:relative;margin:0 auto;top:40px; width:400px;background:#f2df2f2">
  		<div id="successreg" style="display:none;padding:5px;text-align:center;background:#e0fddf">Successfully Registered</div>
  		<div id="header" style=" z-index:1;text-align:center; height:40px; background:url(img/button2_bg.gif);background-size:100% 190%;position:relative;top:0px;line-height:40px;color:white;font-weight:bold">&nbsp;&nbsp;REGISTER</div>
		<div style="padding:20px;z-index:1;box-shadow:inset 0 0 35px white">

			<form onsubmit="return saveregister()">
 				<table   id="logintable" style="padding-top:20px;border-collapse:collapse">
 				 
					<tr>
						<td>Name</td><td><input type="text" autocomplete='off' name="username" id="regname" required="required"  ></td>
					</tr>
					<tr>
						<td>Username</td><td style="position:relative"><input type="text" autocomplete='off' name="username" id="regusername" onkeyup="checkusername(this)"  required="required"  >
							<img class="errorimg error " src="img/error.png">
							<img class="errorimg check" src="img/check.png">
							<img class="errorimg loader" src="img/loading2.gif">
						</td>
					</tr>
					
					<tr>
						<td>Password:</td><td><input type="password" autocomplete='off' name="password" id="regpassword"  required="required"></td>
					</tr>

					<tr>
						<td>Confirm Password:</td><td><input type="password" autocomplete='off' name="password" id="regcpassword" required="required"></td>
					</tr>


					<tr>
						<td></td><td style="text-align:left;"><a href="index.php" style="text-decoration:none;color:#0078ff;line-height:22px;font-size:13px;line-height:30px;">Login here.</a> 	<button style="background:url('img/button2_bg.gif');opacity:0.8;float:right;cursor:pointer">Register</button></form></td>
					</tr>

				</table>
			</div>
		</form>
		</div>

	</div>

<div class="footer2">A Thesis Study Presented to the<br>
Faculty of CSIT Department of College of Arts and Sciences<br>
 
By: Jake D. Cornelia, Christine C. Lajot and Jenelyn C. Dela Torre</div>
</div>
</body>
</html>


<script>

function checkusername(a){
	var val=$(a).val();
	var img=$('.errorimg');
	var loader=$('.loader');
	var check=$('.check');
	var error=$('.error');
	if(val!=""){
	loader.show();
	$.ajax({
 		type:'post',
 		url:'my account/checkusername.php',
 		data:{'username':val},
 		success:function(data){
 			img.hide();
 			if(data>0){
 				error.show();
 				$(a).attr('name',1);


 			}else{
 				img.hide();
 				check.show();
 				$(a).attr('name',0);
 			}
 		},
 		error:function(){
 			alert("Unable to connect.")
 		}
 	});
}
}
function saveregister(){
	var name=$('#regname').val();
	var regusername=$('#regusername').val();
	var check=$('#regusername').attr('name');
	var regpassword=$('#regpassword').val();
	var regcpassword=$('#regcpassword').val();
	if(check==0){
		if(regcpassword==regpassword){
		 	$.ajax({
		 		type:'post',
		 		url:'saveregister.php',
		 		data:{'name':name,'username':regusername,'password':regpassword },
		 		success:function(data){
		 			$('#successreg').show();
		 			$('#logintable input').val("");
		 			$('#logintable img').hide();
		 		},
		 		error:function(){
		 			alert("Unable to connect.")
		 		}
		 	});
 	}else{
 		alert("ERROR: Password did not match.");
 	}
 	}else{
 		alert("ERROR: Username has already existed. Try another one.");
 	}
 	return false;
 }
</script>