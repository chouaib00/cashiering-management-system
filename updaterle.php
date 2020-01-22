 
<tr>
			<td colspan="3" style="border-top:1px solid transparent;font-weight:bold;color:#515151">Related Learning Experience (RLE)</td>
		</tr>
		<tr>
			<td>Coursesssssss</td><td>Year Level</td><td>Amount</td>
		</tr>

		<?php
		$sem=$_POST['sem'];
		$sy=$_POST['sy'];
		include 'dbconfig.php';
		$getcourse=mysql_query("select * from course,schedule_of_fees,paymentlist where course.course_id=schedule_of_fees.course_id and paymentlist.payment_id=schedule_of_fees.payment_id   and course.dept_id in (select dept_id from department order by dept_id) and paymentlist.payment_group='rle' and sy='$sy' and semester='$sem' and paymentlist.payment_desc='Related Learning Experience (RLE)' group by acronym order by sched_id") or die(mysql_error());
		while ($course=mysql_fetch_array($getcourse)) {
		?>
		<tr class="rlerow rlerow<?=$course['course_id'];?> rlegroup<?=$course['sched_id'];?>" id="rlerow<?=$course['sched_id'];?>" name="<?=$course['sched_id'];?>" rlecourse="<?=$course['course_id'];?>">
			<td><div style="position:relative"><input type="hidden" class="rlecourse" value="<?=$course['course_id'];?>">
			<span><?=$course['acronym'];?></span>
					<button onclick="rlecourseadd2(<?=$course['course_id'];?>,<?=$course['sched_id'];?>)"  class="rlecourseadd" title="Add <?=$course['acronym'];?>"></button>
					<button onclick="removerlecourse(<?=$course['sched_id'];?>)"  class="removerlecourse"></button>

			</div>
			</td>
			<td style="text-align:center">
				<select schedid="<?=$course['sched_id'];?>"  refreshval="<?=str_replace("&", "",$course['year_level']);?>" name="<?=$course['course_id'];?>" class="rleyear" onchange="checkrlecourse(this,<?=$course['course_id'];?>)">
					
					<option <?php if($course['year_level']=='I&'){ echo "selected='selected'";}?> name="I">I</option>
					<option <?php if($course['year_level']=='II&'){ echo "selected='selected'";}?> name="II">II</option>
					<option <?php if($course['year_level']=='III&'){ echo "selected='selected'";}?> name="III">III</option>
					<option <?php if($course['year_level']=='IV&'){ echo "selected='selected'";}?> name="IV">IV</option>
							
				</select>
				<div class="dupentry"><div></div>Duplicate entry</div>
						<span class='hidval'><?=str_replace("&", "",$course['year_level']);?></span>

									

			</td>
			
			
			<td><input type="text" value="<?=$course['amount'];?>" name="<?=$course['sched_id'];?>" class="rleamount">
			<span class='hidval'><?=$course['amount'];?></span>
			</td>
			
		</tr>
		<?php
			$getothercourse2=mysql_query("select * from course,schedule_of_fees,paymentlist where course.course_id=schedule_of_fees.course_id and paymentlist.payment_id=schedule_of_fees.payment_id   and course.dept_id in (select dept_id from department order by dept_id) and paymentlist.payment_group='rle' and sy='$sy' and semester='$sem' and paymentlist.payment_desc='Related Learning Experience (RLE)' and course.course_id='$course[course_id]' and schedule_of_fees.sched_id!='$course[sched_id]' order by sched_id");
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
					
					<option <?php if($course2['year_level']=='I&'){ echo "selected='selected'";}?> >I</option>
					<option <?php if($course2['year_level']=='II&'){ echo "selected='selected'";}?> >II</option>
					<option <?php if($course2['year_level']=='III&'){ echo "selected='selected'";}?> >III</option>
					<option <?php if($course2['year_level']=='IV&'){ echo "selected='selected'";}?> >IV</option>
							
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
