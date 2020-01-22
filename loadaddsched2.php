<link href="css/style.css" type="text/css" rel="stylesheet"></link>
<link href="css/scheduleoffees.css" type="text/css" rel="stylesheet"></link>

<style type="text/css">
/*to make fiedlds visible*/
#schoolfeetable input,#schoolfeetable button,#schoolfeetable select,#schoolfeetable .removeadd{display:inline-block}
#schedheader select {display: inline-block}
#schoolfeetable {margin:0px;;}
#schoolfeetable td{border:1px solid gray;}
</style> 
<?php
include 'dbconfig.php';
?>

<div id="schedheader">SCHEDULE OF FEES EFFECTIVE 
Semester: <select>
		
	<option value="I">First</option>
	<option value="II">Second</option>
	</select>
School Year: <select>
		<?php
			$date=date('Y')-4;
			$loop=1;
			while ($loop<8) {
				$dateend=$date+1;
				echo "<option>$date-$dateend</option>";
					$date=$dateend;
				$loop++;
			}

		?>
</select>
Year Levels: 
<select id="schedyear"><option>I&II</option><option>III&IV</option></select>
</div>

<div style="width:100%;overflow:auto">
<table id="schoolfeetable" style="width:100%">
	<?php
	
	function getcourse(){
	$course=mysql_query("select * from course where dept_id in (select dept_id from course group by dept_id order by dept_id asc)");
	return $course;
	}

	$count=mysql_num_rows(getcourse());
	?>
	<tr id="listcourses1">
		<td rowspan="2">School Fee <button onclick="addschoolfee('schoolfeerow')" style="border:none;height:22px;width:22px;background-position:-1px -1px;background-image:url('img/add.png');background-repeat:no-repeat"></button></td><td rowspan="2">Payment<br>Category</td><td id="courses" colspan="<?=$count;?>">All Courses <button onclick="addcourse('all')">+</button></td>
	</tr>
	<tr id="listcourses">
	
		<?php
			$course=getcourse();
			while ($row=mysql_fetch_array($course)) {
				?>
					<td class="regcourselist colid<?=$row['course_id'];?>"><?=$row['description'];?><button class="removecourse" onclick="removecourse(<?=$row['course_id'];?>,'no')"></button></td>
				<?php
			}
		?>
 	</tr>
<?php
	$getschoolfee=mysql_query("select * from paymentlist where payment_group='sched'") or die(mysql_error());
	$count=mysql_num_rows($getschoolfee);
		while ($schoolfeerow=mysql_fetch_array($getschoolfee)){
		?>

			<tr id="row<?=$schoolfeerow['payment_id'];?>" name="<?=$schoolfeerow['payment_id'];?>"  class="row schoolfeerow">

		<td><input type="text" class="schoolfeedesc" onkeyup="checkdescvalue(this)" value="<?=$schoolfeerow['payment_desc'];?>" name="<?=$schoolfeerow['payment_id'];?>" group="sched">
		<button class="removeadd" title="Remove" onclick="removeadd(<?=$schoolfeerow['payment_id'];?>)" style="display:block"></button>
		</td>
		<td><select>
		<option value="tui">Tuition</option>
		<option value="tf">Trust Fund</option>
		<option value="misc">Miscellaneous</option>
		</select>
		</td>
		<?php
			$course=getcourse();
			while ($row=mysql_fetch_array($course)) {
					
				?>

				<td class="regcourse colid<?=$row['course_id'];?>"><input type="text" class="feeamount" name="<?=$schoolfeerow['payment_id'];?>" coursegroup="<?=$row['course_id'];?>" description="reg"></td>
				<?php
			}
		?>	

	</tr>


		<?php
	}
	if($count==0){
	?>

	<tr id="row1" class="row schoolfeerow" name="1">

		<td><input type="text" class="schoolfeedesc" onkeyup="checkdescvalue(this)" name="1" group="sched">
		<button class="removeadd" title="Remove" onclick="removeadd(1)"></button>
		</td>
		<td><select>
		<option value="tui">Tuition</option>
		<option value="tf">Trust Fund</option>
		<option value="misc">Miscellaneous</option>
		</select>
		</td>
		<?php
			$course=getcourse();
			while ($row=mysql_fetch_array($course)) {
					
				?>

				<td class="regcourse colid<?=$row['course_id'];?>"><input type="text" class="feeamount"  name="1" coursegroup="<?=$row['course_id'];?>" description="reg"></td>
				<?php
			}
		?>	

	</tr>
	<?php
}
	?>
	<tr>
		<td colspan="10000" style="text-align:left">MISCELLANEOUS <button onclick="addschoolfee('row')">+</button></td>
	</tr>
	
	<?php
	$getmisc=mysql_query("select * from paymentlist where payment_group='misc'");
	$count2=mysql_num_rows($getmisc);
	while ($schoolfeerow=mysql_fetch_array($getmisc)) {
	
	?>
	<tr id="row<?=$schoolfeerow['payment_id'];?>" class="row" name="<?=$schoolfeerow['payment_id'];?>">
		

		<td class="miscdesccon"><input type="text" name="<?=$schoolfeerow['payment_id'];?>" value="<?=$schoolfeerow['payment_desc'];?>" onkeyup="checkdescvalue(this)" class="miscdesc" group="misc">
		<button class="removeadd" title="Remove" onclick="removeadd(<?=$schoolfeerow['payment_id'];?>)" style="display:block"></button>
		</td>
		<td><select>
			<option value="tui">Tuition</option>
			<option value="tf">Trust Fund</option>
			<option value="misc">Miscellaneous</option>
			</select>
		</td>

		<?php
			$course=getcourse();
			while ($row=mysql_fetch_array($course)) {
				
				?>				
				<td class="regcourse colid<?=$row['course_id'];?>"><input type="text" class="feeamount" coursegroup="<?=$row['course_id'];?>" name="<?=$schoolfeerow['payment_id'];?>" description="reg"></td>
				<?php
			}
		?>	

	</tr>

	<?php
		}
		if($count2==0){
	?>

		<tr id="rowa2" class="row sadf" name="a2">
		
		<td class="miscdesccon"><input type="text" name="a2" onkeyup="checkdescvalue(this)" class="miscdesc" group="misc">
		<button class="removeadd" title="Remove" onclick="removeadd('a2')" style="display:block"></button>
		</td>
		<td><select>
			<option value="tui">Tuition</option>
			<option value="tf">Trust Fund</option>
			<option value="misc">Miscellaneous</option>
			</select>
		</td>

		<?php
			$course=getcourse();
			while ($row=mysql_fetch_array($course)) {			
				?>				
				<td class="regcourse colid<?=$row['course_id'];?>"><input type="text"  name="a2" class="feeamount" coursegroup="<?=$row['course_id'];?>"  description="reg"></td>
				<?php
			}
		?>	

	</tr>
	<?php
		}
	?>
</table>

<button class="schedbut" onclick="savemisc(this)">SAVE</button><img src="img/loading.gif" id="savemiscloader">
</div>
<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="js/scheduleoffees.js"></script>

