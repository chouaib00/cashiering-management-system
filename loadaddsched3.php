<?php
include 'dbconfig.php';
$semester=$_POST['sem'];
$sy=$_POST['sy'];
$year=$_POST['year'];

?>
<link href="css/style.css" type="text/css" rel="stylesheet"></link>
<link href="css/scheduleoffees.css" type="text/css" rel="stylesheet"></link>
<script type="text/javascript" src="js/jquery-1.9.1.js"></script>
<script type="text/javascript" src="js/scheduleoffees.js"></script>

<style type="text/css">
	#schedheader select,#schoolfeetable input,#schoolfeetable select,#schoolfeetable button{display:inline-block;}
	#schoolfeetable span,#schedheader span {display:none}
</style>
<div id="schedheader">SCHEDULE OF FEES EFFECTIVE 
Semester: <select>
		
	<option value="I">First</option>
	<option value="II">Second</option>
	</select>
	<span></span>
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
<span></span>
Year Levels: 
<select id="schedyear"><option>I&II</option><option>III&IV</option></select>
<span></span>
</div>


<div  style="width:100%;overflow:auto">
<table border id="schoolfeetable" style="width:100%">
	<?php
	function getcourse(){

	$course=mysql_query("select * from course where dept_id in (select dept_id from department) order by dept_id") or die(mysql_error());
	 
	return $course;
	}

	$count=mysql_num_rows(getcourse());
	?>
	<tr id="listcourses1">
		<td rowspan="2">School Fee<button onclick="addschoolfee('schoolfeerow')" class="addschoolfee schoolfee"></button></td><td rowspan="2">Payment<br>Category</td><td id="courses" colspan="<?=$count;?>">All Courses <button onclick="addcourse('all')" id="addcourse"></button></td>
	</tr>
	<tr id="listcourses">
	
		<?php
			$course=getcourse();
			while ($row=mysql_fetch_array($course)) {
				?>
					<td class="regcourselist colid<?=$row['course_id'];?>" style="text-align:center"><div style="position:relative"><?=$row['description'];?><button class="removecourse" onclick="removecourse(<?=$row['course_id'];?>,'remove')"></button></div></td>
				<?php
			}
		?>
 	</tr>
 	<?php

		$schoolfee=mysql_query("select * from  paymentlist where payment_group='sched'") or die(mysql_error());
		$countschoolfee=mysql_num_rows($schoolfee);
		while ($schoolfeerow=mysql_fetch_array($schoolfee)) {
			?>
			<tr class="row schoolfeerow" id="row<?=$schoolfeerow['payment_id'];?>" name="<?=$schoolfeerow['payment_id'];?>">
			
			<td ><div style="position:relative">
				<input type="text"  class="schoolfeedesc" onkeyup="checkdescvalue(this)" name="<?=$schoolfeerow['payment_id'];?>" group="sched" value="<?=$schoolfeerow['payment_desc'];?>">
				<div class="dupentry"><div></div>Duplicate entry</div>

				<span><?=$schoolfeerow['payment_desc'];?></span>
				<button class="removeadd" title="Remove" onclick="removeadd(<?=$schoolfeerow['payment_id'];?>)" style="display:inline-block"></button>
			</div>
			</td>
			<td>
				<select>
					<option value="tui">Tuition</option>
					<option value="tf">Trust Fund</option>
					<option value="misc">Miscellaneous</option>
				</select>
				<span></span>
				
			</td>
			<?php
				$getcourse=getcourse();
				while($course=mysql_fetch_array($getcourse)){							
						
				?>
						<td  class="regcourse colid<?=$course['course_id'];?>">
							<input type="text" class="feeamount"  coursegroup="<?=$course['course_id'];?>" name="<?=$schoolfeerow['payment_id'];?>">
							<span></span>
						</td>

				
				<?php
						
					}
				?>
				
		
		</tr>
		<?php
			}

			if($countschoolfee==0){
				?>
				<tr class="row schoolfeerow" id="rowa1" name="a1">
			
					<td><div style="position:relative">
						<input type="text"  class="schoolfeedesc" onkeyup="checkdescvalue(this)" name="<?=$schoolfeerow['payment_id'];?>" group="sched" value="<?=$schoolfeerow['payment_desc'];?>">
						<div class="dupentry"><div></div>Duplicate entry</div>

						<span></span>
						<button class="removeadd" title="Remove" onclick="removeadd('a1')" style="display:inline-block"></button>
						</div>
					</td>
					<td>
						<select>
							<option value="tui">Tuition</option>
							<option value="tf">Trust Fund</option>
							<option value="misc">Miscellaneous</option>
						</select>
						<span></span>
						
					</td>
					<?php
						$getcourse=getcourse();
						while($course=mysql_fetch_array($getcourse)){							
								
						?>
								<td class="regcourse colid<?=$course['course_id'];?>">
									<input type="text" class="feeamount"  coursegroup="<?=$course['course_id'];?>" name="a1">
									<span></span>
								</td>

						
						<?php
								
							}
						?>
						
				
		</tr>
				<?php
			}
		?>
	<tr>
		<td colspan="10000" style="text-align:left">MISCELLANEOUS <button onclick="addschoolfee('row')" class="schoolfee miscfee"></button></td>
	
	</tr>
	
	 	<?php

		$schoolfee=mysql_query("select * from  paymentlist where payment_group='misc'") or die(mysql_error());
		$countschoolfee=mysql_num_rows($schoolfee);
		while ($schoolfeerow=mysql_fetch_array($schoolfee)) {
			?>
			<tr class="row" id="row<?=$schoolfeerow['payment_id'];?>" name="<?=$schoolfeerow['payment_id'];?>">
			
			<td class="miscdesccon"><div style="position:relative">
				<input type="text" class="miscdesc" onkeyup="checkdescvalue(this)"  group="misc" value="<?=$schoolfeerow['payment_desc'];?>">
				<div class="dupentry"><div></div>Duplicate entry</div>
				<span><?=$schoolfeerow['payment_desc'];?></span>
				<button class="removeadd" title="Remove" onclick="removeadd(<?=$schoolfeerow['payment_id'];?>)" style="display:inline-block"></button>
				</div>
			</td>
			<td>
				<select>
					<option value="tui">Tuition</option>
					<option value="tf">Trust Fund</option>
					<option value="misc">Miscellaneous</option>
				</select>
				<span></span>
			
			</td>
			<?php
				$getcourse=getcourse();
				while($course=mysql_fetch_array($getcourse)){
						
				?>
						<td class="regcourse colid<?=$course['course_id'];?>">
							<input type="text" class="feeamount"  coursegroup="<?=$course['course_id'];?>" name="<?=$schoolfeerow['payment_id'];?>">
							<span></span>
						</td>

				
				<?php
					}
				?>
				
		
		</tr>
		<?php
			}
			if($countschoolfee==0){
				?>	
					<tr class="row" id="rowa2" name="a2">
					
					<td class="miscdesccon"><div style="position:relative">
						<input type="text" class="miscdesc" onkeyup="checkdescvalue(this)"  group="misc" value="<?=$schoolfeerow['payment_desc'];?>">
						<div class="dupentry"><div></div>Duplicate entry</div>
						<span><?=$schoolfeerow['payment_desc'];?></span>
						<button class="removeadd" title="Remove" onclick="removeadd('a2')" style="display:inline-block"></button>
						</div>
					</td>
					<td>
						<select>
							<option value="tui">Tuition</option>
							<option value="tf">Trust Fund</option>
							<option value="misc">Miscellaneous</option>
						</select>
						<span></span>
					
					</td>
					<?php
						$getcourse=getcourse();
						while($course=mysql_fetch_array($getcourse)){
								
						?>
								<td class="regcourse colid<?=$course['course_id'];?>">
									<input type="text" class="feeamount"  coursegroup="<?=$course['course_id'];?>" name="a2">
									<span></span>
								</td>

						
						<?php
							}
						?>
						
				
				</tr>
				<?php
			}
		?>
	<tr>
</table>

<button  onclick="savemisc(this)" class="schedbut">save</button>
<button  onclick="refresh(this)" class="schedbut">Refresh</button>
<img src="img/loading.gif" id="savemiscloader">
</div>

<script type="text/javascript">
	inheritvalue();
	highlightrow();
</script>
 