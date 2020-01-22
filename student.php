<?php
session_start();
?>
<script>
 
 
 /*  OTHER COURSE*/
	
	function stucourse(){
		var c = $('#stucourse').val();
 		if(c=='Other'){
		alert();
 			$('#stucourse2con').show();
 		$('#stucourse').hide();
		}
 		
	}
	
	function scholar(a){
		if(a=="Other"){
			$('.amount').numeric();
			$('#otherschcon,#otherschcon2,#scholar2,.scholar').toggle();
			$('#newscholar2,#newamount2').val("");
			$('.scholar option:first,#scholar2 option:first').attr("selected",true);
			
		}
	}
	
	
	function allcourse(){
		$('#stucourse2con').hide();
 		$('#stucourse').show();
	}
	
	//show add student table
	
	//showing the add user form
	function showaddstudent(){
		$('#paymentcon').hide();
 		if( $('#studescription:visible').length==1 ){
		$('#studescription').fadeOut();
		}else{
		$('#studescription input').val("");
		$('#studescription').fadeIn();
		}
	}
	
	//add student
		//add new user
	function addstudent(){
	var addlod=$('#addstudentloader');	
	var studnumber=$('#studnumber').val();
	var fname=$('#fname').val();
	var lname=$('#lname').val();
	var year=$('#year').val();
	var status=$('#status').val();
	var course=$('#stucourse').val();
 	var addbut = $('#addstudentbut');	
	
 		addlod.show();
		addbut.attr('disabled',true);
		$.ajax({
			type: 'POST',
			url: 'addstudent.php',
			data: {'fname':fname,'lname':lname,'course':course,'year':year,'status':status,'studnumber':studnumber},
			success: function(data){
				if(data=="existed"){
					alert("ERROR: Student number is already existed");
				}else{
						selectsearchstud(data);		
				}
			addlod.hide();	
 		
			addbut.removeAttr('disabled');
			},
			error:function(){
			addbut.removeAttr('disabled');
			}
		});
 	return false;
		
	}
	
	//random id
	function randomid(){
		var randid = "";
		var possible = "123456789";
		for( var i=0; i < 10; i++ )
		{
		randid += possible.charAt(Math.floor(Math.random() * possible.length));
		}
		return randid;
	}
	
	
	//add description
	
	
	function description(a,b){
   		$('[name="amount'+a+'"]').attr('id',b)
 	}
	//add other payment description
	
	
	
	
	

	

	
	//paing
	
	
	function pay(id){
	var l = $('#pay'+id);
	$('#studescription').hide();
	l.show();
	$('#paybut'+id).attr("disabled",true).css("opacity",0.5);
		$.ajax({
			url: 'pay.php',
			type: 'post',
			data: {'stud_id':id},
			success: function (data){
				$('#paymentcon').html(data).show();
				$('#paybut'+id).attr("disabled",false).css("opacity",1);
				l.hide();
  			},
			error: function (){
			alert("error");
			l.hide();
			}
			});
	}
	
	function paymenthist(id){
	var l = $('#payhist'+id);
	l.show();
	$('#paymenthistbut'+id).attr("disabled",true).css("opacity",0.5);
		$.ajax({
			url: 'paymenthist.php',
			type: 'post',
			data: {'stud_id':id},
			success: function (data){
				$('#paymentcon').html(data).show();
				$('#paymenthistbut'+id).attr("disabled",false).css("opacity",1);
				l.hide();
 			},
			error: function (){
			alert("error");
			}
			});
	}
	
	
	//show the hidden semester
	
	function showsem(id){
		$('.paymenthistcon').hide();
		$('#paymenthistcon'+id).slideDown();
	}

	//student.php
	var studxhr;
	
	function searchstud(a){
		var b=$('#usersuggest');
		b.html("<tr><td>Loading...</td><td></td></tr>").show();
		var aborted = 0;
		if(a!=""){
			
		if((studxhr) && (studxhr.readyState != 4)){
					//studxhr.abort();
					aborted=1;
				}
				
				studxhr=$.ajax({
				type: 'POST',
				url: 'searchstud.php',
				data: {'name':a},
				success: function(data){
				b.html(data).show();
					
				},
				error:function(){
				
					b.html("<tr><td>Connection failed!"+aborted+"</td><td></td></tr>").show();
				
				}
				});
			}else{
				b.hide();
			}
	}

	function selectsearchstud(a,sem,sy){
		$('#searchstud').val("");
 		$.ajax({
			type:'post',
			url:'student/selectsearchstud.php',
			data:{'stud_id':a,'semester':sem,'sy':sy},
			success:function(data){
				$('#dynamiccontent').html(data);
				$('#studescription,#usersuggest').hide();
			},
			error:function(){

			}
		});
	}
	function viewstudent(){
  		$.ajax({
			type:'post',
			url:'student/viewstudent.php',			
		 
			success:function(data){
				$('#dynamiccontent').html(data);
				$('#studescription,#usersuggest').hide();
 			},
			error:function(){
				connection();
			}
		});
	}
	</script>
</script>

	<div id="contentheader">
 						
		<div id="headeroption">
			<span id="addstudent" onclick="menu('student')">Add Student</span>
			<?php if($_SESSION['type']=='admin') {?> <span id="addstudent" onclick="viewstudent('student')">View Students</span><? } ?>
 		</div>
						
		<div id="searchusercon">
			<input type="text" id="searchstud" onkeyup="searchstud(this.value)" placeholder="Last Name/Student Number" >
				<img  src="img/lens.png" id="lens">							
				<table id="usersuggest"></table>							
		</div>
						
					
	</div> 
	<?php
		include('dbconfig.php');
		$scholar=mysql_query("select * from scholarship order by description asc");
	?>
	<div id="dynamiccontent"></div>
	<div id="studescription" style="display:block">
	<form onsubmit="return addstudent()">
	<table>	
		<tr>
			<td style="white-space:nowrap">Student Number:</td><td><input id="studnumber" type="text" ></td><td style="white-space:nowrap">First Name:</td>
			<td><input type="text" id="fname" required="required"></td>
		</tr>
		
		<tr><td style="white-space:nowrap">Last name:</td><td><input type="text" id="lname" required="required"></td>

			<td>Course:</td>
			<td>
				<div id="stucourse2con">
					<input type="text" placeholder="New Course" id="stucourse2" placeholder="stucourse">
					<div onclick="allcourse()" title="Cancel" id="allcourse"></div>
				</div>
				<select id="stucourse"  onchange="stucourse()">	
						<?php
						$c=mysql_query("select * from course order by description asc");
						while($row=mysql_fetch_array($c)){
						?>
							<option value="<?=$row['course_id'];?>"><?=$row['description'];?></option>
						<? } ?>
				</select>
			</td>
			
		</tr>
		<tr>
		<td>Year:</td><td>
				<select id="year">
					<option>I</option>
					<option>II</option>
					<option>III</option>
					<option>IV</option>
				</select>
			</td>
			<td>Status:</td><td>
				<select id="status">
					<option value="new">New</option>					
					<option value="ongoing">Ongoing</option>
					<option value="grad">Graduating</option>
					<option value="ongoing">Shiftee</option>
					<option value="trans">Trasferee</option>
				</select>
			</td><td></td>
			
		</tr>
		<tr><td></td><td></td><td>
			<td>
			<button id="addstudentbut"></button>
			<img src="img/loading.gif" id="addstudentloader">
			</td>
		</td>
		</tr>
	</table>
	</form>
	</div>
	<div id="paymentcon" style="display:none;position:relative;background:# ;width:100%;margin-bottom:20px;">
	</div>
					
					
				 