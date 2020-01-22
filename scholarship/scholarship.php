<?php
include '../dbconfig.php';
$scholar_id="Lgu Baywan";
$sy='2014-2015';
$semester='I';
$semdisplay="";
if($semester=="I"){
	$semdisplay="1st";
}else{
	$semdisplay="2nd";
}

  
?>
<style type="text/css">
#inventorybut {position:relative;margin:0 0 -15px -6px;top:-5px;border:none;height:20px;background-repeat:no-repeat;padding:16px;background-position:5px 6px;border-radius:10px;width:20px;background-color:white;background-image:url(img/lens.png)}
#scholartable td{padding:2px;}
.scholaraction  {display:none}
</style>
<div id="contentheader">
 						
		<div id="headeroption">
			<span id="addscholar" onclick="addscholarship('dummy')">Add Scholarship</span>
 		</div>
						
		<div style="float:right;margin:9px 8px 0 0;color:white">
			Select Semester: <select id="semester">
								<option>I</option>
								<option>II</option>
						</select>
						<?php
							$getsy=mysql_query("select sy from schedule_of_fees group by sy order by sy desc");
						?>
						SY: 
						<select id="sy">
								<?php
									while ($syrow=mysql_fetch_array($getsy)) {
										echo "<option>$syrow[sy]</option>";
									}
								?>
						</select>

						<?php
							$getscholarship=mysql_query("select * from scholarship group by description order by description asc");
						?>
						Description: 
						<select id="scholar_id">
								<?php
									while ($scholarshiprow=mysql_fetch_array($getscholarship)) {
										echo "<option>$scholarshiprow[description]</option>";
									}
								?>
						</select>

			<button  id="inventorybut" title="Search" onclick="scholarsearch()"></button>
									
		</div>
						
					
	</div> 

<div id="scholarsearchresult" style="padding:20px 10px 20px 10px;border:1px solid gray;width:8in;margin:0 auto;margin-top:10px">


</div>
 <script>
function addscholarship(){
	$('#overlay,#modal').show();
	var con=$('#addcoursecon');
	con.html("<img src='img/loading2.gif' style='margin:6px 35px 6px 35px'>");
	$.ajax({
		type:'get',
		url:'scholarship/addscholarship.php',
		success:function(data){
			con.html(data);
		},
		error:function(){
			connection();
		}
	});
}

function cancelissuereceipt(){
	$('.scholaraction').toggle();
	$('.scholarbut').toggle();
 }

function issuereceipt(){
	$('.scholaraction').show();
	$('.scholarbut').toggle();
}

 function checkor(a){
 	var or=parseInt($(a).val());
 	$('.scholaror').each(function(){

 		if($(a).index('input')!=$(this).index('input')){
 		or++;
 		$(this).val(or)

 		}
 		
 	});
 }
function printscholarreceipt(a,amount,c){
	var receipt_num=$('#receipt_num'+a).val();
	$(c).attr('disabled',true);
	$.ajax({
		type:'post',
		url:'scholarship/printscholarreceipt.php',
		data:{'receipt_num':receipt_num,'stud_id':a,'amount':amount},
		success:function(data){
 			if(data=="existed"){
 				$(c).removeAttr('disabled');
				alert("ERROR: Receipt number is already existed.");
			}else{
				$('#scholaraction'+a).html("Printed");
 				$('#secretcon').html(data);
			}
 			
		},
		error:function(){
			$(c).removeAttr('disabled');
			connection();
		}
	})
}


 function scholarsearch(){
	var semester=$('#semester').val();
	var sy=$('#sy').val();
	var scholar_id=$('#scholar_id').val();
	
	$.ajax({
		type:'post',
		url:'scholarship/searchscholarship.php',
		data: {'semester':semester,'sy':sy,'scholarshipname':scholar_id},
		success:function(data){
			$('#scholarsearchresult').html(data);
		},
		error:function(){
			connection();
		}
	});
}
scholarsearch();
</script>