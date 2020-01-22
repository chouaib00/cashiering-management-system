<style type="text/css">
.feeamount {width:50px;}
.miscdesc {margin-left:15px;width:120px}
.schoolfeedesc {width:135px}
.adddeptrow {display: none}
.miscdesccon {text-align: right;}
.row select {width:60px;}
.removeadd {opacity:0.8;border:none;background-color:transparent;background-image:url('img/removeadd.png');background-repeat:no-repeat;display:none;height:15px;width:15px;position: absolute;right:4px;top:4px;}
.removeadd:hover {opacity:1;cursor:pointer;}
table {border-collapse: collapse;}
#schoolfeetable td{text-align:center;position: relative;}
#schedheader select {border:1px solid transparent}
.regcourselist {padding:4px 0 4px 0;}
#courses {padding:7px;}
</style> 

<div id="schedheader" style="background:url('img/paymentheader.png');padding:10px;text-align:center;color:white">SCHEDULE OF FEES Semester: <select id="schedsem"><option value="I">First</option><option value="II">Second</option></select> School Year: <select id="schedsy">
	<?php
	$date=date('Y')-1;
	$a=1;
	while ($a<=3) {
		$date2=$date+1;
		echo "<option>$date-$date2</option>";
		$date=$date2;
		$a++;
	}
	?>
</select> Year Levels: <select id="schedyear"><option>I&II</option><option>II&IV</option></select></div>


<div style="width:100%;overflow:auto">
<table border id="schoolfeetable" style="width:100%">
	<?php
	include 'dbconfig.php';
	function getcourse(){
	$course=mysql_query("select * from course where dept_id in (select dept_id from course group by dept_id order by dept_id asc)");
	return $course;
	}

	$count=mysql_num_rows(getcourse());
	?>
	<tr id="listcourses1">
		<td rowspan="2">School Fee <button onclick="addschoolfee('schoolfeerow')" style="border:none;height:22px;width:22px;background-position:-1px -1px;background-image:url('img/add.png');background-repeat:no-repeat"></button></td><td rowspan="2">Payment<br>Category</td><td id="courses" colspan="<?=$count;?>">All Courses <button onclick="addcourse()">+</button></td><td colspan="2">Graduate Programs</td>
	</tr>
	<tr id="listcourses">
	
		<?php
			$course=getcourse();
			while ($row=mysql_fetch_array($course)) {
				?>
					<td class="regcourselist"><?=$row['description'];?></td>
				<?php
			}
		?>
		<td>Masteral</td>
		<td>Doctoral</td>
 	</tr>
	<tr id="row1" class="row schoolfeerow" name="1">

		<td><input type="text" class="schoolfeedesc" name="1" group="sched|>>">
		<button class="removeadd" title="Remove"></button>
		</td>
		<td><select>
		<option value="tui">Tuition</option>
		<option value="tf">Trust Fund</option>
		<option value="misc">Miscellaneous</option>
		</select>
		</td>
		<?php
		$checkloop=1;
			$course=getcourse();
			while ($row=mysql_fetch_array($course)) {
					if($checkloop==1){
						$keyup="onkeyup='inheritvalue(this.value,1)'";
					}else{
						$keyup="";
					}
					$checkloop++;
				?>

				<td class="regcourse"><input type="text" class="feeamount" <?=$keyup;?> coursegroup="<?=$row['course_id'];?>" name="1" description="reg"></td>
				<?php
			}
		?>	
			<td><input type="text" class="feeamount" coursegroup="master" description=""></td>
			<td><input type="text" class="feeamount" coursegroup="doctoral" description=""></td>

	</tr>

	<tr>
		<td colspan="10000" style="text-align:left">MISCELLANEOUS <button onclick="addschoolfee('row')">+</button></td>
	</tr>
	
	<tr id="row2" class="row saf" name="2">
		

		<td class="miscdesccon"><input type="text" class="miscdesc" group="misc|>>">
			<button class="removeadd" title="Remove"></button>
		</td>
		<td><select>
			<option value="tui">Tuition</option>
			<option value="tf">Trust Fund</option>
			<option value="misc">Miscellaneous</option>
			</select>
		</td>

		<?php
		$checkloop=1;
			$course=getcourse();
			while ($row=mysql_fetch_array($course)) {
				if($checkloop==1){
						$keyup="onkeyup='inheritvalue(this.value,2)'";
					}else{
						$keyup="";
					}
					$checkloop++;
				?>				
				<td class="regcourse"><input type="text"  <?=$keyup;?> class="feeamount" coursegroup="<?=$row['course_id'];?>" name="2" description="reg"></td>
				<?php
			}
		?>	
			<td><input type="text" class="feeamount" coursegroup="master" description="gradprograms"></td>
			<td><input type="text" class="feeamount" coursegroup="doctoral" description="gradprograms"></td>

	</tr>
</table>

<button  onclick="savemisc()">Sa MISCELLANEOUS</button>
</div>
<table border id="addcourseform" style="display:none">
	<tr><td>Add New Course</td></tr>
	<tr><td>Course Name:</td><td><input type="text" id="newcourse"></td></tr>
	<tr><td>Course Acronym:</td><td><input type="text" id="newacronym"></td></tr>
	
	<tr id="listdeptrow"><td>Department:</td>
		<td>
			<select onchange="checkdepartment(this.value)" class="listdept">
					<?php
						$dept=mysql_query("select  * from department order by acronym");
						while ($row=mysql_fetch_array($dept)) {
							?>
							<option value="<?=$row['dept_id'];?>"><?=$row['description'];?></option>
							<?php
						}

					?>

				<option value="add">Add New Department</option>
			</select>
		</td>
	</tr>
	<tr class="adddeptrow">
		<td>Department Name:</td><td><input type="text" class="listdept">
			<button onclick="checkdepartment('add')">X</button>
		</td>
	</tr>

	<tr class="adddeptrow">
		<td>Dept. Acronym:</td><td><input type="text" id="newdacronym"></td>
	</tr>
	<tr>
		<td></td><td><button onclick="savecourse()">ADD COURSE</button></td>
	</tr>
</table>
<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
<script type="text/javascript">

function inheritvalue (a,b) {
	$('#row'+b+" input:gt(1)").val(a);
}

function removeadd (a) {
	$('#row'+a).remove();
}
function savecourse (){
	var cname=$('#newcourse:visible').val();
	var cacronym=$('#newacronym').val();
	var dacronym=$('#newdacronym:visible').val();
	var dname=$('.listdept:visible').val();
	var course=$('#courses')
	var colspan=course.attr('colspan');
	
	$.ajax({
		type:'post',
		url:'savenewcourse.php',
		data: {'cname':cname,'cacronym':cacronym,'dacronym':dacronym,'dname':dname},
		success: function (a){
			course.attr('colspan',parseInt(colspan)+1);
				$('#listcourses .regcourselist:last').after("<td>"+cname+"</td>");
				$('#schoolfeetable .row').each(function() {
					var rid=$(this).attr('name');		
				$('#row'+rid+ " .regcourse:last").after("<td class='regcourse'><input type='text' class='feeamount' coursegroup='"+a+"' description='reg'></td>");
				});

		},
		error: function(){
			alert("error in conneciton to somewhere");
		}
	})
}
function checkdepartment(a) {
	var b=$('#listdeptrow');
	var c=$('.adddeptrow');
	if(a=="add"){
		b.toggle();
		c.toggle();
	} 
}
function savemisc() {
	var amount="";
	var sy=$('#schedsy').val();
	var sem=$('#schedsem').val();
	var year=$('#schedyear').val();
	
	$('#schoolfeetable .row ').each(function() {
		var rid=$(this).attr('name');
		var a=$('#row'+rid+ " input:first");
		var cat=$('#row'+rid+ " select").val();
		amount+="[endline>]";
		amount+="[&&]"+a.attr('group')+a.val()+"<->"+cat;
		$('#row'+rid+ " input:gt(0)").each(function() {
			amount+="[&&]"+$(this).attr('description')+"<->"+$(this).attr('coursegroup')+"<->"+$(this).val();
		
		});
			
	});
	alert(amount);
	$.ajax({
		type:'post',
		url:'savemisc.php',
		data: {'data':amount,'sy':sy,'sem':sem,'year':year},
		success:function(data){
		},
		error:function(){
			alert("error");
		}
	})	

}
// function getdescription(a){
// 			var rid=$(a).attr('name');
// 			$('#row'+rid+" td:gt(0) input").attr('description',$(a).val());
// 			console.log("1111");
// 		}
	
function addschoolfee(a) {
	
	var rid=randomid();
	
		var row=$('#schoolfeetable .'+a+':last');
		var rowc=row.clone();
		row.after(rowc);
		$('#schoolfeetable .'+a+':last').attr({'id':'row'+rid,'name':rid});
		$('#schoolfeetable .'+a+':last button').attr('onclick','removeadd("'+rid+'")').show();;
		$('#schoolfeetable .'+a+':last input:first').attr('name',rid);
		$('#schoolfeetable .'+a+':last input:eq(1)').attr('onkeyup','inheritvalue(this.value,"'+rid+'")');
		$('#schoolfeetable .'+a+':last input').each(function() {
			$(this).val("");
		}); 	
	
}
 	$(document).ready(function() {
		// $('table').click(function(event) {
		// 	var a= $('#courses').attr("colspan");
		// 	$('#courses').attr("colspan",parseInt(a)+1);
		// 	var a= $('#courses').attr("colspan");
		// 	$('.regcourse:last').after('<td>newww</td>');

		// });	
	

		// $('input').click(function(event) {
		// 	alert($(this).attr('description')+"-"+$(this).attr('course'));
		// });
	});

	function randomid(){
		var randid = "";
		var possible = "123456789";
		for( var i=0; i < 10; i++ )
		{
		randid += possible.charAt(Math.floor(Math.random() * possible.length));
		}
		return randid;
	}
</script>