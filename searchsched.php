<?php
session_start();
include 'dbconfig.php';
$semester=$_POST['sem'];
$sy=$_POST['sy'];
$year=$_POST['year'];
if($semester!=""){
	function getcourse(){
			$semester=$_POST['sem'];
				$sy=$_POST['sy'];
				$year=$_POST['year'];
			$course=mysql_query("select * from course where dept_id in (select dept_id from department) and course_id in (select course_id from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and  payment_group='misc' and sy='$sy'  and semester='$semester' and (year_level like '$year&%' or year_level like '%&$year&%' or year_level like '%&$year')  and amount>0) order by dept_id") or die(mysql_error());
			 
			return $course;
			}

			$count=mysql_num_rows(getcourse());
			if($count>0){
?>
		<div id="schedheader">SCHEDULE OF FEES EFFECTIVE
		Semester: <select>
				<?php
					$semval="";
					if($semester=="I"){
						$semval="First";
					}else{
						$semval="Second";
					}
				?>


			<option value="<?=$semester;?>"><?=$semval;?></option>
			<option value="I">First</option>
			<option value="II">Second</option>
			</select>

		<span name='<?=$semester;?>'><?=$semester;?></span>
		<script>
			$('#schedheader option[value=<?=$semester;?>]:last').remove();
		</script>
		School Year: <select id="schedsy">
			<option><?=$sy;?></option>
			<?php
			$syarray=explode("-", $sy);

			$date=$syarray[0]-10;
			$a=1;
			while ($a<=20){
				$date2=$date+1;
				echo "<option value='$date-$date2'>$date-$date2</option>";
				$date=$date2;
				$a++;
			}
			?>

		</select>
		<span class="b"><?=$sy;?></span>
		<?php
			$getyearlevel=mysql_query("select year_level from schedule_of_fees where sy='$sy' and semester='$semester' and (year_level like '%&$year&%'  or  year_level like '%&$year'  or  year_level like '$year&%')");
			$newyear=mysql_fetch_array($getyearlevel);
		?>
		<script>
			$("option[value='<?=$sy;?>']:last").remove();
			var year="<?=$newyear[year_level];?>";
			var yearb=year.split("&");
			var len=yearb.length-1;
			while(0<=len){
				$("input:checkbox[name=year_levels][value="+yearb[len]+"]").attr("checked","checked");
				len--;
			}
			$(function() {
				$('#schedyearcon input').click(function(){
					var val="";
						$('#schedyearcon input:checkbox:checked').each(function(){
			 					if(val==""){
			 					val=$(this).val();

			 					}else{
			 					val+="&"+$(this).val();

			 					}
							  
						});
						$('#schedyearcon input:eq(0)').val(val);
				});	
			});
		</script>
		Year Levels: 
		<div style="display:inline" id="schedyearcon">
<input type="hidden"  id="schedyear" value="<?=$newyear['year_level'];?>">
<input type="checkbox" name="year_levels" id="year1" value="I"><label for="year1"> I &nbsp;</label>
<input type="checkbox" name="year_levels" id="year2" value="II"><label for="year2"> II&nbsp; </label>
<input type="checkbox" name="year_levels" id="year3" value="III"><label for="year3"> III&nbsp; </label>
<input type="checkbox" name="year_levels" id="year4" value="IV"><label for="year4"> IV </label>
</div>
		 <span class="b" style="display:none"><?=$newyear['year_level'];?></span> 
		<script>
			$("#schedsy option[value='<?=$year;?>']:last").remove();
		</script>
		</div>



	<div  id="schedcon">
		<table border id="schoolfeetable" style="width:100%">
		 
			<tr id="listcourses1">
				<td rowspan="2">School Fee <button onclick="addschoolfee('schoolfeerow')" style="padding:2px 5px 2px 5px;display:none" class="addschoolfee schoolfee">+</button></td><td rowspan="2">Payment<br>Category</td><td id="courses" colspan="<?=$count;?>">All Courses <button onclick="addcourse('not')" style="padding:2px 5px 2px 5px;display:none"  id="addcourse">+</button></td>
			</tr>
			<tr id="listcourses">
			
				<?php
					$course=getcourse();
					while ($row=mysql_fetch_array($course)) {

						//check if some has enrolled in this course
						$checkcourse=mysql_query("select * from student_status where course_id=$row[course_id] and sy='$sy' and semester='$semester'") or die(mysql_error());
						
						?>
							<td class="regcourselist colid<?=$row['course_id'];?>" style="text-align:center"><div style="position:relative"><?=$row['acronym'];?>
									<?php
					if(mysql_num_rows($checkcourse)==0){
							?>

							<button class="removecourse" onclick="removecourse(<?=$row['course_id'];?>,'remove')"></button></div></td>

							<?php
						}
								 
					}
				?>
		 	</tr>
		 	<?php
		 	 
				  if($year=="I"){
				     $year="year_level like '%&$year&%'  or  year_level like '%&$year'  or  year_level like '$year&%'";
				  }else{
				    $year="year_level like '%&$year&%'  or  year_level like '%&$year'  or  year_level like '$year&%'";
				  } 
					 
				 
			 		
				$schoolfee=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and sy in ('$sy') and paymentlist.payment_group='sched' and semester='$semester' and ($year) and amount!='0' group by payment_desc order by paymentlist.payment_id asc") or die(mysql_error());
				$countschoolfee=mysql_num_rows($schoolfee);
				while ($schoolfeerow=mysql_fetch_array($schoolfee)) {
					$readonly="";
					if($schoolfeerow['payment_desc']=="Laboratory Fee"){
						$readonly="readonly='readonly'";
					}
					?>
					<tr class="row schoolfeerow" id="row<?=$schoolfeerow['payment_id'];?>" name="<?=$schoolfeerow['payment_id'];?>">
					
					<td><div style="position:relative">
						<input type="text" class="schoolfeedesc" <?=$readonly;?>  onkeyup="checkdescvalue(this)" name="<?=$schoolfeerow['payment_id'];?>" group="sched" value="<?=$schoolfeerow['payment_desc'];?>">
						<span><?=$schoolfeerow['payment_desc'];?></span>
						 
						
						<button <?php if($schoolfeerow['payment_desc']=="Laboratory Fee"){ echo "style='visibility:hidden'";}?> class="removeadd" title="Remove" onclick="removeadd(<?=$schoolfeerow['payment_id'];?>)"></button>
						 
					</div>
					</td>
					<td>
					<?php
						$option="";
							if($schoolfeerow['category']=="tui"){
								$option="Tuition";
							}else if($schoolfeerow['category']=="tf"){
								$option="Trust Fund";
							}else{
								$option="Miscellaneous";
							}	
						?>
						<select name="<?=$schoolfeerow['payment_id'];?>" refreshval="<?=$schoolfeerow['category'];?>">
							

							<?php
							if($schoolfeerow['payment_desc']=="Laboratory Fee"){?>
							<option value="tui">Tuition</option>
							<option value="tf">Trust Fund</option>
							<option value="misc">Miscellaneous</option>

							<?php	}else{?>	
							<option value="<?=$schoolfeerow['category'];?>"><?=$option;?></option>
							<option value="tui">Tuition</option>
							<option value="tf">Trust Fund</option>
							<option value="misc">Miscellaneous</option>
										<?php } ?>


						</select>
						<span><?=$schoolfeerow['category'];?></span>
						<script>
							$('#row<?=$schoolfeerow['payment_id'];?> select [value=<?=$schoolfeerow['category'];?>]:gt(0)').remove();
						</script>
					</td>
					<?php
						$getcourse=getcourse();
						while($course=mysql_fetch_array($getcourse)){
								//get amount on every course
								$amount=mysql_query("select * from schedule_of_fees where course_id='$course[course_id]' and payment_id=$schoolfeerow[payment_id] and sy='$sy' and semester='$semester' and ($year)") or die(mysql_error());
								$countamount=mysql_num_rows($amount);
								while ($amountrow=mysql_fetch_array($amount)) {
								
								
						?>
								<td class="regcourse colid<?=$course['course_id'];?>">
									<input type="text" class="feeamount" value="<?=$amountrow['amount'];?>" group="sched" coursegroup="<?=$course['course_id'];?>" name="<?=$schoolfeerow['payment_id'];?>" description="<?=$amountrow['sched_id'];?>">
									<span><?=number_format($amountrow['amount'],2);?></span>
								</td>

						
						<?php
								}
							}
						?>
						
				
				</tr>
				<?php
					}
				?>
				<tr id="schedtotalbottom">
					<td  colspan="2" style="text-align:right;font-weight:bold">Sub-total</td>
					<?php
					$course=getcourse();
					while($courserow=mysql_fetch_array($course)){
						$amount=mysql_query("select * from schedule_of_fees,course,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and course.course_id=schedule_of_fees.course_id and sy='$sy' and semester='$semester' and ($year) and schedule_of_fees.course_id='$courserow[course_id]' and paymentlist.payment_group='sched'");
						$total=0;
						while ($amountrow=mysql_fetch_array($amount)) {
							$total+=$amountrow['amount'];
						}
						?>
						<td class="colid<?=$courserow['course_id']?>" name="subtotalsched"><?=number_format($total,2);?></td>

					<?php
					}

					?>

				</tr>
			<tr>
				<td colspan="10000" style="text-align:left">MISCELLANEOUS <button onclick="addschoolfee('row')" style="padding:2px 5px 2px 5px;display:none" class="schoolfee miscfee">+</button></td>
			
			</tr>
			
			 	<?php

				$schoolfee=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and sy in ('$sy') and paymentlist.payment_group='misc' and semester='$semester' and ($year)  and amount!='0' group by payment_desc order by paymentlist.payment_id asc") or die(mysql_error());
				$countschoolfee=mysql_num_rows($schoolfee);
				while ($schoolfeerow=mysql_fetch_array($schoolfee)) {
					?>
					<tr class="row miscrow" id="row<?=$schoolfeerow['payment_id'];?>" name="<?=$schoolfeerow['payment_id'];?>">
					
					<td class="miscdesccon"><div style="position:relative">
						<input type="text" class="miscdesc" onkeyup="checkdescvalue(this)"  group="misc" value="<?=$schoolfeerow['payment_desc'];?>">
						<span><?=$schoolfeerow['payment_desc'];?></span>
						<button class="removeadd" title="Remove" onclick="removeadd(<?=$schoolfeerow['payment_id'];?>)"></button>
						</div>
					</td>
					<td>
						<?php
						$option="";
							if($schoolfeerow['category']=="tui"){
								$option="Tuition";
							}else if($schoolfeerow['category']=="tf"){
								$option="Trust Fund";
							}else{
								$option="Miscellaneous";
							}	
						?>
						<select name="<?=$schoolfeerow['payment_id'];?>" refreshval="<?=$schoolfeerow['category'];?>">
							<option value="<?=$schoolfeerow['category'];?>"><?=$option;?></option>
							<option value="tui">Tuition</option>
							<?php if($schoolfeerow['payment_desc']!="Laboratory Fee"){?>
								<option value="tf">Trust Fund</option>
							<?php } ?>
 							<option value="misc">Miscellaneous</option>
						</select>
						<span><?=$schoolfeerow['category'];?></span>
						<script>
							$('#row<?=$schoolfeerow['payment_id'];?> select [value=<?=$schoolfeerow['category'];?>]:gt(0)').remove();
						</script>
					</td>
					<?php
						$getcourse=getcourse();
						while($course=mysql_fetch_array($getcourse)){
								//get amount on every course
								$amount=mysql_query("select * from schedule_of_fees where course_id='$course[course_id]' and payment_id=$schoolfeerow[payment_id] and sy='$sy' and semester='$semester' and amount!='0.00' and ($year)") or die(mysql_error());
								$countamount=mysql_num_rows($amount);
								while ($amountrow=mysql_fetch_array($amount)) {
								
								
						?>
								<td class="regcourse colid<?=$course['course_id'];?>">
									<input type="text" class="feeamount" group="misc" value="<?=$amountrow['amount'];?>" coursegroup="<?=$course['course_id'];?>" name="<?=$schoolfeerow['payment_id'];?>" description="<?=$amountrow['sched_id'];?>">
									<span><?=number_format($amountrow['amount'],2);?></span>
								</td>

						
						<?php					
							}
						}
						?>
						
				
				</tr>
				<?php
					}
				?>
				<tr id="misctotalbottom">
					<td  colspan="2" style="text-align:right;font-weight:bold">Sub-total</td>
					<?php
					$course=getcourse();
					while($courserow=mysql_fetch_array($course)){
						$amount=mysql_query("select * from schedule_of_fees,course,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and course.course_id=schedule_of_fees.course_id and sy='$sy' and semester='$semester' and ($year) and schedule_of_fees.course_id='$courserow[course_id]' and paymentlist.payment_group='misc'");
						$total=0;
						while ($amountrow=mysql_fetch_array($amount)) {
							$total+=$amountrow['amount'];
						}
						?>
						<td class="colid<?=$courserow['course_id']?>" name="subtotalmisc"><?=number_format($total,2);?></td>

					<?php
					}

					?>

				</tr>

				<tr id="totaltuiandmisc">
					<td  colspan="2" style="text-align:right">Total Tution and Miscellaneous Fees</td>
					<?php
					$course=getcourse();
					while($courserow=mysql_fetch_array($course)){
						$tutionamount=mysql_query("select * from schedule_of_fees,course,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and course.course_id=schedule_of_fees.course_id and sy='$sy' and semester='$semester' and ($year) and schedule_of_fees.course_id='$courserow[course_id]' and paymentlist.payment_group='sched'");
						$miscamount=mysql_query("select * from schedule_of_fees,course,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and course.course_id=schedule_of_fees.course_id and sy='$sy' and semester='$semester' and ($year) and schedule_of_fees.course_id='$courserow[course_id]' and paymentlist.payment_group='misc'");
						$total=0;
						while ($amountrow=mysql_fetch_array($tutionamount)) {
							$total+=$amountrow['amount'];
						}
						while ($amountrow=mysql_fetch_array($miscamount)) {
							$total+=$amountrow['amount'];
						}
						?>

						<td class="colid<?=$courserow['course_id']?>"><?=number_format($total,2);?></td>

					<?php
					}

					?>

				</tr>
		</table>

	</div> <!-- end of schedcon  -->

	<div id="separator">OTHER SCHOOL FEES</div>

	<!-- other fees -->
	<style type="text/css">
	.dupentry{right:-114px;top:-3px;}
	#rletable .hidval,.hidval{display:inline;font-size:14px}
	.adddesc,.removerlecourse,.rlecourseadd,.removeotherdesc,.removerlecourse,.rleamount,.rleyear,.cat,.otherdesc,.otheramount,.graddesc,.gradamount,.transdesc,.transamount ,.othermiscdesc,.transamount{display:none}
	.rlerow:hover,.otherrow:hover,.gradrow:hover,.transrow:hover {background:#cce9f1;}
	</style>
	<div id="otherfeemaincon">
	<table id="rletable" border>
		<tr>
			<td colspan="3" style="border-top:1px solid transparent;font-weight:bold;color:#515151">Related Learning Experience (RLE)</td>
		</tr>
		<tr>
			<td>Courses</td><td class="th">Year Level</td><td>Amount</td>
		</tr>

		<?php
		$sem=$semester;

  $getothercourse2=mysql_query("select * from course,schedule_of_fees,paymentlist where course.course_id=schedule_of_fees.course_id and paymentlist.payment_id=schedule_of_fees.payment_id   and course.dept_id in (select dept_id from department order by dept_id) and paymentlist.payment_group='rle' and sy='$sy' and semester='$sem' and paymentlist.payment_desc='Related Learning Experience (RLE)' and amount!='0' and course.course_id='$course[course_id]' and schedule_of_fees.sched_id!='$course[sched_id]' order by sched_id");

 		$getcourse=mysql_query("select * from course,schedule_of_fees,paymentlist where course.course_id=schedule_of_fees.course_id and paymentlist.payment_id=schedule_of_fees.payment_id   and course.dept_id in (select dept_id from department order by dept_id) and paymentlist.payment_group='rle' and sy='$sy' and semester='$sem' and paymentlist.payment_desc='Related Learning Experience (RLE)' and amount!='0' group by acronym order by sched_id") or die(mysql_error());
		while ($course=mysql_fetch_array($getcourse)) {
		?>
		<tr class="rlerow rlerow<?=$course['course_id'];?> rlegroup<?=$course['sched_id'];?>" id="rlerow<?=$course['sched_id'];?>" name="<?=$course['sched_id'];?>" rlecourse="<?=$course['course_id'];?>">
			<td><div style="position:relative"><input type="hidden" class="rlecourse" value="<?=$course['course_id'];?>">
			<span class="rlecourse"><?=$course['acronym'];?></span>
					<button onclick="rlecourseadd2(<?=$course['course_id'];?>,<?=$course['sched_id'];?>)"  class="rlecourseadd" title="Add <?=$course['acronym'];?>"></button>
					<button onclick="removerlecourse(<?=$course['sched_id'];?>)"  class="removerlecourse"></button>

			</div>
			</td>
			<td style="text-align:center">
				<select schedid="<?=$course['sched_id'];?>"  refreshval="<?=str_replace("&", "",$course['year_level']);?>" name="<?=$course['course_id'];?>" class="rleyear" onchange="checkrlecourse(this,<?=$course['course_id'];?>)">
				 
					<option <?php if("I"==str_replace("&", "",$course['year_level'])){ echo "selected='selected'";}?> name="I">I</option>
					<option <?php if("II"==str_replace("&", "",$course['year_level'])){ echo "selected='selected'";}?> name="II">II</option>
					<option <?php if("III"==str_replace("&", "",$course['year_level'])){ echo "selected='selected'";}?> name="III">III</option>
					<option <?php if("IV"==str_replace("&", "",$course['year_level'])){ echo "selected='selected'";}?> name="IV">IV</option>
							
				</select>
				<div class="dupentry"><div></div>Duplicate entry</div>
				<span class='hidval'><?=str_replace("&", "",$course['year_level']);?></span>

									

			</td>
			
			
			<td><input type="text" value="<?=$course['amount'];?>" name="<?=$course['sched_id'];?>" class="rleamount">
			<span class='hidval'><?=number_format($course['amount'],2);?></span>
			</td>
			
		</tr>
		<?php
 			$getothercourse2=mysql_query("select * from course,schedule_of_fees,paymentlist where course.course_id=schedule_of_fees.course_id and paymentlist.payment_id=schedule_of_fees.payment_id   and course.dept_id in (select dept_id from department order by dept_id) and paymentlist.payment_group='rle' and sy='$sy' and semester='$sem' and paymentlist.payment_desc='Related Learning Experience (RLE)' and amount!='0' and course.course_id='$course[course_id]' and schedule_of_fees.sched_id!='$course[sched_id]' order by sched_id");
			while ($course2=mysql_fetch_array($getothercourse2)) {
						?>
						<tr class="rlerow rlerow<?=$course2['course_id'];?> rlegroup<?=$course2['sched_id'];?>" id="rlerow<?=$course2['sched_id'];?>" name="<?=$course2['sched_id'];?>" rlecourse="<?=$course2['course_id'];?>">
					<td><div style="position:relative"><input type="hidden" class="rlecourse" value="<?=$course2['course_id'];?>">
					<span><?=$course['acronym'];?></span>
							<button onclick="rlecourseadd2(<?=$course2['course_id'];?>,<?=$course2['sched_id'];?>)"  class="rlecourseadd" title="Add <?=$course2['acronym'];?>"></button>
							<button onclick="removerlecourse(<?=$course2['sched_id'];?>)"  class="removerlecourse"></button>

					</div>
					</td>
					<td style="text-align:center">
						<select schedid="<?=$course2['sched_id'];?>" name="<?=$course2['course_id'];?>" class="rleyear" onchange="checkrlecourse(this,<?=$course2['course_id'];?>)">
							
						<option <?php if("I"==str_replace("&", "",$course2['year_level'])){ echo "selected='selected'";}?> name="I">I</option>
					<option <?php if("II"==str_replace("&", "",$course2['year_level'])){ echo "selected='selected'";}?> name="II">II</option>
					<option <?php if("III"==str_replace("&", "",$course2['year_level'])){ echo "selected='selected'";}?> name="III">III</option>
					<option <?php if("IV"==str_replace("&", "",$course2['year_level'])){ echo "selected='selected'";}?> name="IV">IV</option>
			
						</select>
						<div class="dupentry"><div></div>Duplicate entry</div>
								<span class='hidval'><?=str_replace("&", "",$course2['year_level']);?></span>

											

					</td>
					
					
					<td><input type="text" value="<?=$course2['amount'];?>" name="<?=$course2['sched_id'];?>" class="rleamount">
					<span class='hidval'><?=$course2['amount'];?></span>
					</td>
					
				</tr>
						<?php
				}
			}
		?>

	</table>



	<table id="othertable" border  style="margin-bottom:10px;">
		<tr>
			<td colspan="3" style="border-top:1px solid transparent;font-weight:bold;color:#515151">Other Fees</td>
		</tr>
		<tr>
			<td>Description<button onclick="addother()" class="adddesc" style="padding:2px 5px 2px 5px">+</button></td><td class="th">Amount</td><td  class="th">Payment Category</td>
		</tr>
		<?php
			$getother=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='other' and  amount!='0' and sy='$sy' and semester='$sem' order by schedule_of_fees.sched_id asc") or die(mysql_error());
			$countother=mysql_num_rows($getother);
			while ($other=mysql_fetch_array($getother)) {

				$readonly="";
				$hide="";
				if($other['payment_desc']=="Overload/Additional Subject" || $other['payment_desc']=="Completion Fee" || $other['payment_desc']=="Adding/Dropping/Changing"){
					$readonly="readonly='readonly'";
					$hide="display:none";
 				}
				 		?>
		<tr class="otherrow" id="otherrow<?=$other['payment_id'];?>" name="<?=$other['payment_id'];?>">
			<td><div style="position:relative">
			<input type="text" name="<?=$other['payment_id'];?>" schedid="<?=$other['sched_id'];?>" class="otherdesc" <?=$readonly;?>  value="<?=$other['payment_desc'];?>">
			<span class="hidval"><?=$other['payment_desc'];?></span>
			<?php
			if($other['payment_desc']!="Overload/Additional Subject" && $other['payment_desc']!="Completion Fee" && $other['payment_desc']!="Adding/Dropping/Changing"){?>

			<button onclick="removeotherdesc(<?=$other['payment_id'];?>)"  class="removeotherdesc"></button>
			<?php
				}
			?>
			</div>
			</td>
			<td  style="text-align:center"><input type="text" name="<?=$other['payment_id'];?>" class="otheramount" value="<?=$other['amount'];?>">
			<span class="hidval"><?=number_format($other['amount'],3);?></span>
			</td>
			<td  style="text-align:center"><select class="cat">
					<option <?php if($other['category']=='tui'){ echo "selected='selected'";}?> value="tui">Tuition</option>
					<option <?php if($other['category']=='tf'){ echo "selected='selected'";}?> value="tf">Trust Fund</option>
					<option <?php if($other['category']=='misc'){ echo "selected='selected'";}?> value="misc">Miscellaneous</option>
			</select>
			<?php
				$cat="";
				if($other['category']=="tui"){
					$cat="Tuition";
				}else if($other['category']=="tf"){
					$cat="Trust Fund";
				}else{
					$cat="Miscellaneous";
				}

			?>
			<span class="hidval"><?=$cat;?></span>
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
			<td>Description<button onclick="addgrad()" class="adddesc" style="  padding: 2px 5px 2px 5px;">+</button></td><td>Amount</td><td>Payment Category</td>
		</tr>
		<?php
				$getgrad=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='grad' and amount!='0' and sy='$sy' order by schedule_of_fees.payment_id asc") or die(mysql_error());
				$countgrad=mysql_num_rows($getgrad);
 				while ($grad=mysql_fetch_array($getgrad)){

			?>
		<tr class="gradrow" id="gradrow<?=$grad['payment_id'];?>" name="<?=$grad['payment_id'];?>">
			<td>
			<div style="position:relative">
			<input type="text" name="<?=$grad['payment_id'];?>" schedid="<?=$grad['sched_id'];?>"  class="graddesc" value="<?=$grad['payment_desc'];?>">
			<span class="hidval"><?=$grad['payment_desc'];?></span>
			<button onclick="removegraddesc(<?=$grad['payment_id'];?>)"  class="removerlecourse gradbut"></button>
			</div>
			</td>
			<td  style="text-align:center"><input type="text" name="<?=$grad['payment_id'];?>" class="gradamount" value="<?=$grad['amount'];?>">
				<span class="hidval"><?=number_format($grad['amount'],2);?></span>
			</td>
			<td  style="text-align:center"><select class="cat">
				<option <?php if($grad['category']=='tui'){ echo "selected='selected'";}?> value="tui">Tuition</option>
					<option <?php if($grad['category']=='tf'){ echo "selected='selected'";}?> value="tf">Trust Fund</option>
					<option <?php if($grad['category']=='misc'){ echo "selected='selected'";}?> value="misc">Miscellaneous</option>
			</select>
			<?php
				$cat="";
				if($grad['category']=="tui"){
					$cat="Tuition";
				}else if($grad['category']=="tf"){
					$cat="Trust Fund";
				}else{
					$cat="Miscellaneous";
				}

			?>
			<span class="hidval"><?=$cat;?></span></td>
		</tr>
		<?php
		}
		?>	
	</table>


	<table id="transtable" border>
		<tr>

			<td colspan="3" style="font-weight:bold;font-size:14px;color:#515151"> Additional Fees for New Students/Transferees</td>
		</tr>
		<tr>
			<td>Description<button onclick="addtrans()" class="adddesc" style="  padding: 2px 5px 2px 5px;">+</button></td><td class="th">Amount</td><td class="th">Payment Category</td>
		</tr>
		<?php
				$getgrad=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='new' and amount!='0' and sy='$sy' and semester='$sem'") or die(mysql_error());
				$countgrad=mysql_num_rows($getgrad);
				while ($grad=mysql_fetch_array($getgrad)){

			?>
		<tr class="transrow" id="transrow<?=$grad['payment_id'];?>" name="<?=$grad['payment_id'];?>">
			<td>
			<div style="position:relative">
			<input type="text" name="<?=$grad['payment_id'];?>" schedid="<?=$grad['sched_id'];?>" class="transdesc"  value="<?=$grad['payment_desc'];?>">
				<span class="hidval"><?=$grad['payment_desc'];?></span>
				<button onclick="removetransdesc(<?=$grad['payment_id'];?>)"  class="removerlecourse transbut"></button>
				</div>
			</td>
			<td style="text-align:center"><input type="text" name="<?=$grad['payment_id'];?>" class="transamount" value="<?=$grad['amount'];?>">
				<span class="hidval"><?=number_format($grad['amount'],2);?></span>
			</td>
			<td style="text-align:center"><select class="cat">
				<option <?php if($grad['category']=='tui'){ echo "selected='selected'";}?> value="tui">Tuition</option>
					<option <?php if($grad['category']=='tf'){ echo "selected='selected'";}?> value="tf">Trust Fund</option>
					<option <?php if($grad['category']=='misc'){ echo "selected='selected'";}?> value="misc">Miscellaneous</option>
			</select>
			<?php
				$cat="";
				if($grad['category']=="tui"){
					$cat="Tuition";
				}else if($grad['category']=="tf"){
					$cat="Trust Fund";
				}else{
					$cat="Miscellaneous";
				}

			?>
			<span class="hidval"><?=$cat;?></span></td>
		</tr>
		<?php
		}

		if(mysql_num_rows($getgrad)==0){
		?>
			<tr class="transrow" id="transrowt1" name="t1" style="display:none">
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

	<table id="othermisctable" border>
		<tr>

			<td colspan="3" style="font-weight:bold;font-size:14px;color:#515151">Other Miscellaneous Fees</td>
		</tr>
		<tr>
			<td>Description<button onclick="addothermisc()" class="adddesc" style="  padding: 2px 5px 2px 5px;">+</button></td><td class="th">Amount</td><td class="th">Payment Category</td>
		</tr>
		<?php

				$getgrad=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='othermisc' and amount!='0' and sy='$sy' and semester='$sem'") or die(mysql_error());
				$countgrad=mysql_num_rows($getgrad);
				while ($grad=mysql_fetch_array($getgrad)){

			?>
		<tr class="othermiscrow" id="othermiscrow<?=$grad['payment_id'];?>" name="<?=$grad['payment_id'];?>">
			<td>
			<div style="position:relative">
			<input type="text" name="<?=$grad['payment_id'];?>" schedid="<?=$grad['sched_id'];?>" class="othermiscdesc"  value="<?=$grad['payment_desc'];?>">
				<span class="hidval"><?=$grad['payment_desc'];?></span>
				<button onclick="removeothermiscdesc(<?=$grad['payment_id'];?>)"  class="removerlecourse transbut"></button>
				</div>
			</td>
			<td style="text-align:center"><input type="text" name="<?=$grad['payment_id'];?>" class="transamount" value="<?=$grad['amount'];?>">
				<span class="hidval"><?=number_format($grad['amount'],2);?></span>
			</td>
			<td style="text-align:center"><select class="cat">
				<option <?php if($grad['category']=='tui'){ echo "selected='selected'";}?> value="tui">Tuition</option>
					<option <?php if($grad['category']=='tf'){ echo "selected='selected'";}?> value="tf">Trust Fund</option>
					<option <?php if($grad['category']=='misc'){ echo "selected='selected'";}?> value="misc">Miscellaneous</option>
			</select>
			<?php
				$cat="";
				if($grad['category']=="tui"){
					$cat="Tuition";
				}else if($grad['category']=="tf"){
					$cat="Trust Fund";
				}else{
					$cat="Miscellaneous";
				}

			?>
			<span class="hidval"><?=$cat;?></span></td>
		</tr>
		<?php
		}

		if($countgrad==0){
		?>
		<tr class="othermiscrow" id="othermiscrow131" name="131" style="display:none">
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
<div id="secretschedcon" style="display:none"></div>
	<?php
	if($_SESSION['type']=="admin"){?>
	<button class="schedbut" onclick="cancelschedupdate2(this)" id="cancelschedupdate">Cancel</button>
	<button  class="schedbut" onclick="updatesched(this)" id="updatesched">Edit</button>
	<?php
	}?>
	 
	</div>
	</div> 
	<!-- end other fees -->
	<script>
		gettotal();
	</script>
<?php
	}else{
		?>
			<div style="line-height:10;text-align:center">No result found.</div>
		<?php
	}
}else{
	header('location:index.php');
}

?>

<script>
		$('.feeamount,.rleamount,.otheramount,.transamount,.gradamount,.miscotheramount').numeric();
</script>