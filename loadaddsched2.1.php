<?php
include 'dbconfig.php';
 

?>
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
	<?php
	 
			$date=date('Y')-10;
			$loop=1;
 			$dateend2=$date+1;
 			$date2=$date."-".$dateend2;
	?>
School Year: <select name="<?=$date2;?>">
		<?php
			while ($loop<20) {
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
		<td rowspan="2">School Fee <button title="Add School Fee Description" onclick="addschoolfee('schoolfeerow')" style="padding:2px 5px 2px 5px;" class="addschoolfee schs	oolfee">+</button></td><td rowspan="2">Payment<br>Category</td><td id="courses" colspan="<?=$count;?>">All Courses <button onclick="addcourse('all')" title="Add New Course" style="padding:2px 5px 2px 5px" id="addcoursse">+</button></td>
	</tr>
	<tr id="listcourses">
	
		<?php
			$course=getcourse();
			while ($row=mysql_fetch_array($course)) {
				?>
					<td class="regcourselist colid<?=$row['course_id'];?>" style="text-align:center"><div style="position:relative"><?=$row['acronym'];?><button class="removecourse" onclick="removecourse(<?=$row['course_id'];?>,'remove')"></button></div></td>
				<?php
			}
		?>
 	</tr>
 	<?php

		$schoolfee=mysql_query("select * from  paymentlist where payment_group='sched'") or die(mysql_error());
		$countschoolfee=mysql_num_rows($schoolfee);
		while ($schoolfeerow=mysql_fetch_array($schoolfee)) {
			$readonly="";
			$hide="";
			if($schoolfeerow['payment_desc']=="Laboratory Fee"){
				$readonly="readonly='readonly'";
				$hide="display:none";
			}
			?>
			<tr class="row schoolfeerow" id="row<?=$schoolfeerow['payment_id'];?>" name="<?=$schoolfeerow['payment_id'];?>">
			
			<td ><div style="position:relative">
				<input type="text" <?=$readonly;?> class="schoolfeedesc" onkeyup="checkdescvalue(this)" name="<?=$schoolfeerow['payment_id'];?>" group="sched" value="<?=$schoolfeerow['payment_desc'];?>" refreshval="<?=$schoolfeerow['payment_desc'];?>">
				<div class="dupentry2"><div></div>Duplicate entry</div>

				<span><?=$schoolfeerow['payment_desc'];?></span>
				<button class="removeadd" title="Remove" onclick="removeadd(<?=$schoolfeerow['payment_id'];?>)" style="display:inline-block;<?=$hide;?>"></button>
			</div>
			</td>
			<td>
				<?php
				$getschedcategory=mysql_query("select category from schedule_of_fees where payment_id='$schoolfeerow[payment_id]' order by sched_id desc");
				$schedcategory=mysql_fetch_array($getschedcategory);
				?>
				<select>
					<?php
					if($schoolfeerow['payment_desc']=="Laboratory Fee"){?>
					<option value="tui">Tuition</option>

					<?php	}else{?>	
					<option <?php if($schedcategory['category']=="tui"){ echo "selected='selected'";}  ?> value="tui">Tuition</option>
					<option <?php if($schedcategory['category']=="tf"){ echo "selected='selected'";}  ?> value="tf">Trust Fund</option>
					<option <?php if($schedcategory['category']=="misc"){ echo "selected='selected'";}  ?> value="misc">Miscellaneous</option>
					<?php } ?>
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
			?>
	<tr>
		<td colspan="10000" style="text-align:left">MISCELLANEOUS <button onclick="addschoolfee('row')" title="Add New Miscellaneous Description"  style="padding:2px 5px 2px 5px" class="schoolfee miscsfee">+</button></td>
	
	</tr>
	
	 	<?php

		$schoolfee=mysql_query("select * from  paymentlist where payment_group='misc'") or die(mysql_error());
		$countschoolfee=mysql_num_rows($schoolfee);
		while ($schoolfeerow=mysql_fetch_array($schoolfee)) {
			?>
			<tr class="row" id="row<?=$schoolfeerow['payment_id'];?>"  name="<?=$schoolfeerow['payment_id'];?>">
			
			<td class="miscdesccon"><div style="position:relative">
				<input type="text" class="miscdesc" onkeyup="checkdescvalue(this)" name="<?=$schoolfeerow['payment_id'];?>" group="misc" value="<?=$schoolfeerow['payment_desc'];?>" refreshval="<?=$schoolfeerow['payment_desc'];?>">
				<div class="dupentry2"><div></div>Duplicate entry</div>
				<span><?=$schoolfeerow['payment_desc'];?></span>
				<button class="removeadd" title="Remove" onclick="removeadd(<?=$schoolfeerow['payment_id'];?>)" style="display:inline-block"></button>
				</div>
			</td>
			<td>
				<?php
				$getschedcategory=mysql_query("select category from schedule_of_fees where payment_id='$schoolfeerow[payment_id]' order by sched_id desc");
				$schedcategory=mysql_fetch_array($getschedcategory);
				?>
				<select>
					<option <?php if($schedcategory['category']=="tui"){ echo "selected='selected'";}  ?> value="tui">Tuition</option>
					<option <?php if($schedcategory['category']=="tf"){ echo "selected='selected'";}  ?> value="tf">Trust Fund</option>
					<option <?php if($schedcategory['category']=="misc"){ echo "selected='selected'";}  ?> value="misc">Miscellaneous</option>
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
						<input type="text" class="miscdesc" onkeyup="checkdescvalue(this)"  name="a2" group="misc" value="<?=$schoolfeerow['payment_desc'];?>" refreshval="<?=$schoolfeerow['payment_desc'];?>">
						<div class="dupentry2"><div></div>Duplicate entry</div>
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
</div>

<!-- other fees --> 
<div id="otherfeemaincon">
<div id="separator">OTHER SCHOOL FEES</div>
<style type="text/css">
.dupentry{right:-105px;top:-1px}
</style>
<table id="rletable" border>
	<tr>
		<td colspan="3" style="border-top:1px solid transparent;font-weight:bold;color:#515151">Related Learning Experience (RLE)</td>
	</tr>
	<tr>
		<td>Courses</td><td>Year Level</td><td>Amount</td>
	</tr>

	<?php

	include 'dbconfig.php';
	$getcourse=mysql_query("select * from course where dept_id in (select dept_id from department order by dept_id)") or die(mysql_error());
	while ($course=mysql_fetch_array($getcourse)) {
	?>
	<tr class="rlerow rlerow<?=$course['course_id'];?>" id="rlerows<?=$course['course_id'];?>" name="s<?=$course['course_id'];?>" rlecourse="<?=$course['course_id'];?>">
		<td><div style="position:relative"><input type="hidden" class="rlecourse" value="<?=$course['course_id'];?>">
		<span class="rlecourse"><?=$course['acronym'];?></span>
				<button onclick="rlecourseadd(<?=$course['course_id'];?>)"  class="rlecourseadd" title="Add <?=$course['acronym'];?>"></button>
				<button onclick="removerlecourse('s<?=$course['course_id'];?>')"  class="removerlecourse"></button>

		</div>
		</td>
		<td style="text-align:center">
			<select  id="rleyear"  name="<?=$course['course_id'];?>" onchange="checkrlecourse(this,<?=$course['course_id'];?>)">
				<option>I</option>
				<option>II</option>
				<option>III</option>
				<option>IV</option>		
						
			</select>
			<div class="dupentry"><div></div>Duplicate entry</div>
		</td>
		
		
		<td><input type="text" class="rleamount" name="s<?=$course['course_id'];?>"></td>
		
	</tr>
	<?php
		}
	?>

</table>



<table id="othertable" border>
	<tr>
		<td colspan="3" style="border-top:1px solid transparent;font-weight:bold;color:#515151">Other Fees</td>
	</tr>
	<tr>
		<td>Description<button onclick="addother()" style="padding:2px 5px 2px">+</button></td><td>Amount</td><td>Payment Category</td>
	</tr>
	<?php
		$getother=mysql_query("select * from paymentlist where payment_group='other'") or die(mysql_error());
		$countother=mysql_num_rows($getother);
		while ($other=mysql_fetch_array($getother)) {

	?>
	<tr class="otherrow" id="otherrow<?=$other['payment_id'];?>" name="<?=$other['payment_id'];?>">
		<td><div style="position:relative">
		<?php
		$readonly="";
		$hide="";
		if($other['payment_desc']=="Overload/Additional Subject" || $other['payment_desc']=="Completion Fee" || $other['payment_desc']=="Adding/Dropping/Changing"){
			$readonly="readonly='readonly'";
			$hide="display:none";
		}
		?>
		<input type="text" class="otherdesc" <?=$readonly;?> value="<?=$other['payment_desc'];?>" style="padding-right:20px;width:114px" name="<?=$other['payment_id'];?>">
			<button onclick="removeotherdesc(<?=$other['payment_id'];?>)" style='<?=$hide;?>' class="removeotherdesc"></button>
		</div>
		</td>
		<td><input type="text" class="otheramount" name="<?=$other['payment_id'];?>"></td>
		<td>
		<?php
				$getschedcategory=mysql_query("select category from schedule_of_fees where payment_id='$other[payment_id]' order by sched_id desc");
				$schedcategory=mysql_fetch_array($getschedcategory);
				?>
				<select class="cat">
					<option <?php if($schedcategory['category']=="tui"){ echo "selected='selected'";}  ?> value="tui">Tuition</option>
					<option <?php if($schedcategory['category']=="tf"){ echo "selected='selected'";}  ?> value="tf">Trust Fund</option>
					<option <?php if($schedcategory['category']=="misc"){ echo "selected='selected'";}  ?> value="misc">Miscellaneous</option>
				</select>
		
		</td>
	</tr>
	<?php
		} 
	?>
</table>
 
<div style="display:inline;float:right">
<table id="gradtable" border>
	<tr>
		<td colspan="3" style="border-top:1px solid transparent;font-weight:bold;color:#515151">Graduation Fees</td>
	</tr>
	<tr>
		<td>Description<button onclick="addgrad()">+</button></td><td>Amount</td><td>Payment Category</td>
	</tr>
	<?php
			$getgrad=mysql_query("select * from paymentlist where payment_group='grad'") or die(mysql_error());
			$countgrad=mysql_num_rows($getgrad);
			while ($grad=mysql_fetch_array($getgrad)){

		?>
	<tr class="gradrow" id="gradrow<?=$grad['payment_id'];?>" name="<?=$grad['payment_id'];?>">
		<td>
		<div style="position:relative">
		<input type="text" class="graddesc" value="<?=$grad['payment_desc'];?>" style="padding-right:20px;width:114px" name="<?=$grad['payment_id'];?>">
			<button onclick="removegraddesc(<?=$grad['payment_id'];?>)"  class="removerlecourse gradbut"></button>
		</div>
		</td>
		<td><input type="text" class="gradamount" name="<?=$grad['payment_id'];?>"></td>
		<td>
			<?php
				$getschedcategory=mysql_query("select category from schedule_of_fees where payment_id='$grad[payment_id]' order by sched_id desc");
				$schedcategory=mysql_fetch_array($getschedcategory);
				?>
				<select class="cat">
					<option <?php if($schedcategory['category']=="tui"){ echo "selected='selected'";}  ?> value="tui">Tuition</option>
					<option <?php if($schedcategory['category']=="tf"){ echo "selected='selected'";}  ?> value="tf">Trust Fund</option>
					<option <?php if($schedcategory['category']=="misc"){ echo "selected='selected'";}  ?> value="misc">Miscellaneous</option>
				</select>

		</td>
	</tr>
	<?php
	}

	if($countgrad==0){
		?>
			<tr class="gradrow" id="gradrowg1" name="g1">
		<td><div style="position:relative"><input type="text" class="graddesc" name="g1">
			<button onclick="removegraddesc(1)"  class="removerlecourse gradbut"></button>
			</div>
		</td>
		<td><input type="text" class="gradamount" name="g1"></td>
		<td><select class="cat">
			<option value="tui">Tuition</option>
			<option value="tf">Trust Fund</option>
			<option value="misc">Miscellaneous</option>
		</select>
		</td>
	</tr>	

		<?php
	}
	?>	
</table>


<table id="transtable" border>
	<tr>
		<td colspan="3" style="font-weight:bold;font-size:14px;color:#515151">Additional Fees for New Students/Transferees</td>
	</tr>
	<tr>
		<td>Description<button onclick="addtrans()" style="padding:2px 5px 2px 5px">+</button></td><td>Amount</td><td>Payment Category</td>
	</tr>
	<?php
			$getgrad=mysql_query("select * from paymentlist where payment_group='new'") or die(mysql_error());
			$countgrad=mysql_num_rows($getgrad);
			while ($grad=mysql_fetch_array($getgrad)){

		?>
	<tr class="transrow" id="transrow<?=$grad['payment_id'];?>" name="<?=$grad['payment_id'];?>">
		<td>
		<div style="position:relative">
		<input type="text" class="transdesc"  value="<?=$grad['payment_desc'];?>" name="<?=$grad['payment_id'];?>" style="padding-right:20px;width:114px">
			<button onclick="removetransdesc(<?=$grad['payment_id'];?>)"  class="removerlecourse transbut"></button>
			</div>
		</td>
		<td><input type="text" class="transamount" name="<?=$grad['payment_id'];?>"></td>
		<td>
		<?php
				$getschedcategory=mysql_query("select category from schedule_of_fees where payment_id='$grad[payment_id]' order by sched_id desc");
				$schedcategory=mysql_fetch_array($getschedcategory);
				?>
				<select class="cat">
					<option <?php if($schedcategory['category']=="tui"){ echo "selected='selected'";}  ?> value="tui">Tuition</option>
					<option <?php if($schedcategory['category']=="tf"){ echo "selected='selected'";}  ?> value="tf">Trust Fund</option>
					<option <?php if($schedcategory['category']=="misc"){ echo "selected='selected'";}  ?> value="misc">Miscellaneous</option>
				</select>
		</td>
	</tr>
	<?php
	}

	if($countgrad==0){
		?>
			<tr class="transrow" id="transrowt1" name="t1">
		<td><div style="position:relative"><input type="text" class="transdesc" name="t1">
			<button onclick="removetransdesc(1)"  class="removerlecourse transbut"></button>
			</div>
		</td>
		<td><input type="text" class="transamount" name="t1"></td>
		<td><select class="cat">
			<option value="tui">Tuition</option>
			<option value="tf">Trust Fund</option>
			<option value="misc">Miscellaneous</option>
		</select>
		</td>
	</tr>	

		<?php
	}
	?>	
</table>

<table id="othermisctable" border >
	<tr>
		<td colspan="3" style="font-weight:bold;font-size:14px;color:#515151">Other Miscellaneous Fees</td>
	</tr>
	<tr>
		<td>Description<button onclick="addothermisc()" style="padding:2px 5px 2px 5px">+</button></td><td>Amount</td><td>Payment Category</td>
	</tr>
	<?php
			$getgrad=mysql_query("select * from paymentlist where payment_group='othermisc'") or die(mysql_error());
			$countgrad=mysql_num_rows($getgrad);
			while ($grad=mysql_fetch_array($getgrad)){

		?>
	<tr class="othermiscrow" id="othermiscrow<?=$grad['payment_id'];?>" name="<?=$grad['payment_id'];?>">
		<td>
		<div style="position:relative">
		<input type="text" class="othermiscdesc" value="<?=$grad['payment_desc'];?>" name="<?=$grad['payment_id'];?>" style="padding-right:20px;width:114px">
			<button onclick="removeothermiscdesc(<?=$grad['payment_id'];?>)"  class="removerlecourse transbut"></button>
			</div>
		</td>
		<td><input type="text" class="miscotheramount"  name="<?=$grad['payment_id'];?>"></td>
		<td>
		<?php
				$getschedcategory=mysql_query("select category from schedule_of_fees where payment_id='$grad[payment_id]' order by sched_id desc");
				$schedcategory=mysql_fetch_array($getschedcategory);
				?>
				<select class="cat">
					<option <?php if($schedcategory['category']=="tui"){ echo "selected='selected'";}  ?> value="tui">Tuition</option>
					<option <?php if($schedcategory['category']=="tf"){ echo "selected='selected'";}  ?> value="tf">Trust Fund</option>
					<option <?php if($schedcategory['category']=="misc"){ echo "selected='selected'";}  ?> value="misc">Miscellaneous</option>
				</select>
		</td>
	</tr>
	<?php
	} 

	if($countgrad==0){
		?>
		<tr class="othermiscrow" id="othermiscrow131" name="131">
		<td>
		<div style="position:relative">
		<input type="text" class="othermiscdesc" value="<?=$grad['payment_desc'];?>" name="<?=$grad['payment_id'];?>">
			<button onclick="removeothermiscdesc(131)"  class="removerlecourse transbut"></button>
			</div>
		</td>
		<td><input type="text" class="miscotheramount"  name="<?=$grad['payment_id'];?>"></td>
		<td>
		<?php
				$getschedcategory=mysql_query("select category from schedule_of_fees where payment_id='$grad[payment_id]' order by sched_id desc");
				$schedcategory=mysql_fetch_array($getschedcategory);
				?>
				<select class="cat">
					<option <?php if($schedcategory['category']=="tui"){ echo "selected='selected'";}  ?> value="tui">Tuition</option>
					<option <?php if($schedcategory['category']=="tf"){ echo "selected='selected'";}  ?> value="tf">Trust Fund</option>
					<option <?php if($schedcategory['category']=="misc"){ echo "selected='selected'";}  ?> value="misc">Miscellaneous</option>
				</select>
		</td>
	</tr>

		<?php
	}


	?>
</table>


</div>

<div style="clear:both"></div>
</div>
<button  onclick="refresh(this)" class="schedbut">Refresh</button>
<button  onclick="savemisc(this)" class="schedbut" id="savemisc">Save</button>
<img src="img/loading.gif" id="savemiscloader">
<?php
$checkotherfees=mysql_query("select * from schedule_of_fees where payment_id in (select payment_id from paymentlist where payment_group='other' or payment_group='rle' or payment_group='new')");
while ($row=mysql_fetch_array($checkotherfees)) {
	?>
		<span style="display:none" class="existsched" sy="<?=$row['sy'];?>" semester="<?=$row['semester'];?>"></span>
	<?php
}
?>
<script type="text/javascript">
$(function() {
		$('.feeamount,.rleamount,.otheramount,.transamount,.miscotheramount').numeric();
		var b=$('#otherfeemaincon');
		var c=$('#schedheader select:eq(0)').val();
		var d=$('#schedheader select:eq(1)').val();
		if($("span[sy="+d+"]").length>0){
			$('#gradtable').hide();
		}
		if($("span[sy="+d+"][semester="+c+"]").length>0){
			b.hide();
		}else{
			b.show();
		}
});
	$('#schedheader select:lt(2)').change(function(event) {
		var b=$('#otherfeemaincon');
		var c=$('#schedheader select:eq(0)').val();
		var d=$('#schedheader select:eq(1)').val();
		if($("span[sy="+d+"]").length>0){
			 
			$('#gradtable').hide();
		}else{
			$('#gradtable').show();
		}

		if($("span[sy="+d+"][semester="+c+"]").length>0){
			b.hide();
 			$('#gradtable').show();
		}else{
 			b.show();
 		}

	});
	inheritvalue();
	highlightrow();
</script>
 <div id="secretjake"></div>