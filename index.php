<?php
session_start();
if(isset($_SESSION['type'])){
	header("location:home.php");
}else
?>
<!DOCTYPE html>
<html>
<head>
	<title>Computerized Cashiering System</title>
	 <meta http-equiv="X-UA-Compatible" content="IE=edge">
</head>
<script type="text/javascript" src="js/jquery.min.js"></script>
<body bgproperties='fixed' background="img/bodybg2.png" style="background-size:100% 100%">
<link href="css/normalize.css" type="text/css" rel="stylesheet"></link>
<link href="css/style.css" type="text/css" rel="stylesheet"></link>
<link href="css/start.css" type="text/css" rel="stylesheet"></link>
<div style="position:absolute;min-height:100%;width:100%;padding:0;margin:0">
<?php

include "startheader.php";
?>

<table style="z-index:3;position:fixed;height:100%;width:99%;left:0;right:0;bottom:0;top:0;">

<tr>
	<td>
	<div style="position:relative;margin:0 auto;width:400px">
	<?php
	if(isset($_REQUEST['msg'])){?>
		<div id="successreg" style="font-size:14px;padding:5px;margin-bottom:10px;text-align:center;background:#fddfdf"><?=$_REQUEST['msg'];?></div>
		<script type="text/javascript">
		setTimeout(function(){
			$('#successreg').fadeOut();
		},5000);
		</script>
		<?php
			}
		?>

  		<div id="headedr" style="text-align:center">&nbsp;&nbsp;LOGIN</div>
		<div style="padding:10px;z-index:1;backgsround:#f2f2f2;border-radius:0 0 4px 4px">
			<form action="login.php" method="post">
 				<table id="logintable" style="border-collapse:collapse">
					<tr>
						<td>Username:</td><td><input type="text" autocomplete='off' name="username" id="username" autofocus style="padding:7px 60px 7px 35px"></td>
					</tr>
					
					<t>
						<td>Password:</td><td style="position:absolute"><input type="password" name="password" id="password" style="position:relative;	padding:7px 60px 7px 35px"> <div style="border:5px solid #edefed;border-radius:40px;position:absolute;top:-25px;right:11px;cursor:pointer"><button style="height:42px;border:5px solid white;background-color:#2384ac;background-image:url(img/arrow.png);box-shadow:inset 4px 6px 10px #5fc5da;background-repeat:no-repeat;background-position:4px 7px;border-radius:25px;width:42px;cursor:pointer;"></button></div></td>
					</tr>

					<tr style="">
						<td></td><td style="text-align:left;margin-top:15px;"><br><a href="register.php" style="text-decoration:none;color:#0078ff;line-height:22px;font-size:13px;">Not yet Registered?</a> 	</form></td>
					</tr>

				</table>
			</div>
		
		</div>

	</div>
	</td>
</tr>
</table>
<div class="footer2">A Thesis Presented to the<br>
Faculty of CSIT Department of College of Arts and Sciences<br>
 
By: Jake D. Cornelia, Christine C. Lajot and Jenelyn C. Dela Torre</div>
</div>
</body>
</html>