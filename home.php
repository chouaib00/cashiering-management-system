<?php
session_start();
ini_set('max_execution_time', 0);
if(!isset($_SESSION['user_id'])){
header("location:index.php");
}else
include 'dbconfig.php';
?>
<!DOCTYPE html>
<html lang="en-US">
<title>
Computerized Cashiering System
</title>

 <meta http-equiv="X-UA-Compatible" content="IE=edge">
 <head>
<meta charset="utf-8"/>
<link href="css/normalize.css" type="text/css" rel="stylesheet"></link>
<link href="css/style.css" type="text/css" rel="stylesheet"></link>
<script src="js/jquery.min.js"></script>
<script src="js/jquery.number.min.js"></script>
<script src="js/mylib.js"></script>
<script>
	 
	function menu(page){
		var a=$('#mloader img');
		$('#success div').hide();
		$('#subcon').hide();
		a.css("opacity",1);
		$.ajax({
			type: 'get',
			url: page+".php",
			success: function(data){
				$('#loadcontent').html(data);
			a.css("opacity",0);
			},
			error:function(){
				a.css("opacity",0)
			}
		});
	}
	
	function closemodal(a,b){
		$('#overlay,#modal').fadeOut();
		if(b==11){
			selectsearchstud(a);
		}
	}

	$(document).ready(function(){
		$('#loadcontent').load('student.php');
	});

	function connection(){
		alert("Connection error,please try again.");
	}

	$(function() {
 		$('#sub').mouseover(function(){
			$('#subcon').show();
		});
		// $('#menucon span').mouseover(function(){
		// 	$('#menucon div').hide();
		// 	var id=$(this).attr("class");
		// 	$("."+id+" div").show();
 	// 	});

 		$('#menucon span').click(function(){
			$('#menucon div').hide();
			var id=$(this).attr("class");
			$("."+id+" div").show();
 		})
	});
	function keepalive(){
		$.ajax({
			type:'post',
			url:'alive.php',
			success:function(){
				setTimeout(keepalive, 20000);
			},
			error:function(){
				setTimeout(keepalive, 20000);
			}
		});
	}
	keepalive()
	</script>
</head>

<body> 
<style type="text/css">
	#dateinfo span {
		color: #42ff00;
	}
</style>

	<!--main div-->
	<div style="position:absolute;min-height:100%;width:100%;">
	<div id="body" style="	min-height:100%;margin-bottom:100px;">

		<!--header-->
		<div id="header" style="background:url(img/headerbg.png);position:relative">
		 <img src="img/textheader.png" style="position:absolute;left:300px;top:10px">
			<img src="img/header.jpg" height="120px">
			<img src="img/rightheader.png" style="position:absolute;top:0PX;right:0PX">
			<?php
	echo "<a id='dateinfo' style='color:white;font-weight:bold;z-index:123;text-shadow:0 0 3px black; position:absolute;left:5px;bottom:5px;'>Semester: <span id='csemester'>$_SESSION[semester]</span> SY: <span id='csy'>$_SESSION[sy]</span> DATE: <span id='csy'>$_SESSION[date]</span> </a>";
	?>	
		<div style="text-align:right;  width:190px;text-transform:capitalize;position:absolute;right:5px;z-index:1;bottom:0px;font-size:12px;;color:white;">Welcome 
		 <?php
		 echo "$_SESSION[name] !";
		 ?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
		</div>
		</div>		
		<!--end header-->
		
		<!--menu con-->
	<!--	<div style="z-index:0;border:1px solid s;top:15px;left:-2px;background:url(img/title.png);position:absolute;height:165px;width:300px">
			</div>-->
		<?php
		$display="";
		if($_SESSION['type']!='admin'){
			$display="style=display:none;";
		}
		?>
		<style type="text/css">
			
			.cert #sub{position:absolute;width:112px;top:8px;left:-2px;height:30px;}
			.cert #subcon{display:none;bottom:-35px;left:-4px; position:absolute;white-space:nowrap;box-shadow:1px 2px 5px gray;border-radius:3px;padding:4px 0 4px 0; background:#595959}
			.cert #subcon a {font-size:11px;padding:2px 6px 2px 6px;display:block;color:white}
			.cert #subcon a:hover {background:#787777;}
			#sub:hover > #subcon {display:block}
			.cert  #pointer {position:absolute;left:55px;top:-20px;height:0;width:0;border:10px solid transparent;border-bottom-color:#595959;}
		</style>
		<div id="menucon">
 				<span <?=$display;?> id="user account/useraccount" class='usermanagement' onclick="menu(this.id)">User Account
 				<div style="position:absolute;display:none;width:100%;bottom:-11px;right:0">
					<div style="height:0;width:0;border:10px solid transparent;border-bottom-color:white;margin:0 auto;"></div>
				</div>
				</span>
   				
 				<span id="my account/myaccount" class='myaccount' onclick="menu(this.id)">My Account
 				<div style="position:absolute;display:none;width:100%;bottom:-11px;right:0">
					<div style="height:0;width:0;border:10px solid transparent;border-bottom-color:white;margin:0 auto;"></div>
				</div>
 				</span>
 				 

				<span id="student" onclick="menu(this.id)" class="studdent" >Student
				<div style="position:absolute;width:100%;bottom:-11px;right:0">
					<div style="height:0;width:0;border:10px solid transparent;border-bottom-color:white;margin:0 auto;"></div>
				</div>
				</span>
			 
				<?php if($_SESSION['type']!="Collection"){  ?>
				<span id="inventory/inventory" class='inventory' onclick="menu(this.id)">Inventory

				<div style="position:absolute;display:none;width:100%;bottom:-11px;right:0">
					<div style="height:0;width:0;border:10px solid transparent;border-bottom-color:white;margin:0 auto;"></div>
				</div>
				</span>
				<?php } if($_SESSION['type']!="Collection"){?>
				<span id="scholarship/scholarship" class="scholar" onclick="menu(this.id)">Scholarship
					<div style="position:absolute;display:none;width:100%;bottom:-11px;right:0">
					<div style="height:0;width:0;border:10px solid transparent;border-bottom-color:white;margin:0 auto;"></div>
				</div>
				</span>
				<?php } ?>
				<?php if($_SESSION['type']!="Collection"){?>
				<span   id="scheduleoffees" onclick="menu(this.id)" class="scheduleoffees">Schedule of Fees
					<div style="position:absolute;display:none;width:100%;bottom:-11px;right:0">
					<div style="height:0;width:0;border:10px solid transparent;border-bottom-color:white;margin:0 auto;"></div>
				</div>
				</span>
				<?php }  ?>
				
				<?php   
				if($_SESSION['type']!="admin"){
					?>
					<span   id="user account/moneylog" onclick="menu(this.id)" class="inventory" >Logs
						<div style="position:absolute;display:none;width:100%;bottom:-11px;right:0">
						<div style="height:0;width:0;border:10px solid transparent;border-bottom-color:white;margin:0 auto;"></div>
					</div>
					</span>
					<?php
				}
				?>
				<span   id="settings" onclick="menu(this.id)" class="settings">Settings
					<div style="position:absolute;display:none;width:100%;bottom:-11px;right:0">
					<div style="height:0;width:0;border:10px solid transparent;border-bottom-color:white;margin:0 auto;"></div>
				</div>
				</span>
				<span id="logout" ><a href="logout.php" style="color:white;text-decoration:none">Logout</a>
		<div style="position:absolute;display:none;width:100%;bottom:-11px;right:0">
					<div style="height:0;width:0;border:10px solid transparent;border-bottom-color:white;margin:0 auto;"></div>
				</div>
				</span> 
			
		</div>
		<!--end menu con-->
		<div id="mloader" style="margin-bottom:-50px;height:40px"><img src="img/loading2.gif"></div>
		<!--content-->
		<div id="content">
			
			
			<div class="loadcontent" id="useraccount2">
 			</div>
			<div  id="success" style="position:absolute;top:-28px;left:300px;width:400px;z-index:1;"><div style="position:relative;display:none;background:#d8ffda;color:green;padding:4px;text-align:center;border:1px solid #51c957;">Successfully updated.</div></div>
			<div class="loadcontent" id="loadcontent">
 			</div>
 		</div>
		<!--content-->
		<div style="clear:both"></div>

	
	</div>
	<!--end main div-->
	<div class="footer">
<br>A Thesis presented to the<br>
Faculty of CSIT Department of College of Arts and Sciences<br>
by: Jake D. Cornelia, Christine C. Lajot & Jenelyn C. Dela Torre
</div>

</div>
	<div id="overlay">
	</div>
	
	<table id="modal">
		<tr>
			<td style="text-align:center">
				<div id="halign">
					<div id="addcoursecon">
						
					</div>
					
				</div>
			</td>
		</tr>
	</table>
 

</body>

</html>