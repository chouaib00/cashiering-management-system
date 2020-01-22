<!DOCTYPE html>
<html lang="en-US">
<title>
Home
</title>
 <head>
<meta charset="utf-8"/>
<link href="css/style.css" type="text/css" rel="stylesheet"></link>
<script src="js/jquery-1.9.1.js"></script>
<script>
	$(document).ready(function() {
		
	});
	
	/*  OTHER COURSE*/
	
	function stucourse(){
		var c = $('#stucourse').val();
 		if(c=='Other'){
 			$('#stucourse2con').show();
 		$('#stucourse').hide();
		}
 		
	}
	
	function allcourse(){
		$('#stucourse2con').hide();
 		$('#stucourse').show();
	}
	
	</script>
</head>
<body>
	<!--main div-->
	<div id="body">
	
		<!--header-->
		<div id="header">
			<img src="img/header.jpg" height="120px" width="1000px">
		</div>		
		<!--end header-->
		
		<!--menu con-->
		<div id="menucon">
 				<span id="user">User Accounts</span>
 				<span id="user">My Account</span>
				<span id="student">Student</span>
				<span id="history">History</span>
				<span id="inventory">Inventory</span>
				<span id="payment">Payments</span>
				<span id="sittings">Sittings</span>
				<span id="logout">Logout</span>
				
				<div style="border:1px solid #cbcbcb;border-radius:3px;position:relative;float:right;background:white">
					 
					
					<!--
					<div style="border:1px solid #cbcbcb;width:220px;height:29px;">
						<div style="background:url(img/search.png);position:relative;margin:0 0 -1px;top:-1px;left:-2px;width:55px;height:31px;float:left">
						 
						</div>
						<input type="text" style="position:relative;top:4px;width:155px;border:none">
					</div>
					-->
				</div>
			
		</div>
		<!--end menu con-->
		
		<!--content-->
		<div id="content">
			
			<!--left-->
			<div id="leftcon">
			lasdfsfs<br>
			lasdfsfs<br>
			lasdfsfs<br>
			lasdfsfs<br>
			lasdfsfs<br>
			lasdfsfs<br>
			lasdfsfs<br>
			</div>
			<!--end left-->
			
			<!--right-->
			<div id="rightcon">
				<div id="payment" style="margin:0 0 100px;border:1px solid #d7d7d7;border-radius:5px;">
					<div id="paymentheader" style="height:40px;background:url(img/paymentheader.png);color:gray;font-weight:bold">
						
						<div id="paymentoptioin" style="padding:0 0 0 7px;width:450px;float:left;line-height:40px;">Payment Section
							<span id="payhistory">History</span>
							<span id="payhistory">Pay</span>
						</div>
						
						<div style="position:relative;top:6px;right:6px;float:right;width:220px;padding:2px;border:1px solid #c9c6c6;border-radius:4px">
							<input type="text" placeholder="Search by ID/Lastname" style="width:190px;border:none;color:#7f7a7a;" >
							 <img  src="img/lens.png" style="float:right">
						</div>
						
					
					</div>
					<br>
					<table id="studescription">
						<tr>
							<td>Name: </td><td><input type="text" id="stuname"></td>
							<td>ID: </td><td><input type="text"id="stuid"></td>
						</tr>
						<tr>
							<td>Course: </td>
							<td id="coursecon">
								<div id="stucourse2con">
									<input type="text" placeholder="New Course" id="stucourse2">
									<div onclick="allcourse()" title="Cancel" id="allcourse"></div>
								</div>
								
								<select id="stucourse" onchange="stucourse()">	
									<option>Batcherlor of Science in Information Technology</option>
									<option>Batcherlor of Science in Computer Science</option>
									<option>Associate in Computer Science</option>
									<option>Bachelor of Secondary Education</option>
									<option>Bachelor of Elementary Education</option>
									<option>Criminal Justice Education</option>
									<option>Other</option>
								</select>
							
							</td>
  							<td>Year: </td>
							<td>
								<select name="stuyear">
									<option>I</option>
									<option>II</option>
									<option>III</option>
									<option>IV</option>
								</select>
							</td>
						</tr>
					</table>
					
					<br>
					<hr>
					<br>
					<table id="studpayment" border style="position:relative;margin:0px auto">
						<tr>
							<td>Tuition: </td><td><input type="stuname"></td>
						</tr>
						
						<tr>
							<td>Micellaneous: </td><td><input type="stuid"></td>
						</tr>
						
						<tr>
							<td>Scholarship: </td><td><input type="stuid"></td>
						</tr>
						 
					</table>
					<button style="font-weight:bold;border-radius:5px;border:1px solid blue;background:url(img/button_bg.gif);padding:5px;color:white;">SAVE PAYMENT</button>
					<br> 
					<br> 
 				</div>
 			</div>
			<!--end right-->
			<div style="clear:both"></div>
 		</div>
		<!--content-->
		
	</div>
	<!--end main div-->
</body>
</html>