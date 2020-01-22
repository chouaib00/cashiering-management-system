<?php
include 'dbconfig.php';
$data=$_POST['data'];

$sy=$_POST['sy'];
$semester=$_POST['sem'];
$year=$_POST['year'];

$allarr=explode("[endline>]", $data);
$datalen=count($allarr);
$start=1;
while ($start<$datalen){
	$data2=$allarr[$start];
	$data2arr=explode("[&&]", $data2);
	$data2len=count($data2arr);
	$start2=2;

	$schoolfee=$data2arr[1];
	$schoolfeearr=explode("<->", $schoolfee);
	$desc=$schoolfeearr[1];
	$descgroup=$schoolfeearr[0];
	$desc_cat=$schoolfeearr[2];
	$checkdesc=mysql_query("select * from paymentlist where payment_desc='$desc' order by payment_id desc") or die (mysql_error());
		
	$checkdesc_count=mysql_num_rows($checkdesc);
	$desc_id="";
	if($checkdesc_count>0){
		$getdesc_id=mysql_fetch_array($checkdesc);
		$desc_id=$getdesc_id['payment_id'];
	}else{
		$insert_desc=mysql_query("insert into paymentlist values ('','$desc','$descgroup')") or die(mysql_error());
		$get_desc=mysql_query("select * from paymentlist where payment_desc='$desc' order by payment_id desc") or die (mysql_error());
		$getdesc_id=mysql_fetch_array($get_desc);
		$desc_id=$getdesc_id['payment_id'];
	}
		

		
		while ($start2<$data2len) {
			$amount=explode("<->",$data2arr[$start2]);
			$course=$amount[1];
			$desc_amount=$amount[2];
			mysql_query("insert into schedule_of_fees values ('','$desc_id','$desc_amount','$desc_cat','$course','$year','$sy','$semester')") or die(mysql_error());
   			$start2++;
		}
 	$start++;
}

//other fees/////////////////////////////////////////

$rledata=$_POST['rledata'];
$sem=$semester;
$rlearr=explode('[endline]', $rledata);
$rlearrlen=count($rlearr);
$startrle=1;
//for the rledata
while ($startrle<$rlearrlen) {
	$rledata2=$rlearr[$startrle];
	$rledata2arr=explode("<->", $rledata2);
	$rlecourse=$rledata2arr[0];
	$rledamount=$rledata2arr[1];   
	$rleyear=$rledata2arr[2];   
	$rlevar="Related Learning Experience (RLE)";
	$saverle=mysql_query("select * from paymentlist where payment_desc='$rlevar' and payment_group='rle'");
	$checkrle=mysql_num_rows($saverle);
	if($checkrle==0){
		mysql_query("insert into paymentlist values ('','$rlevar','rle')");
		}
		$getrleid=mysql_query("select payment_id from paymentlist where payment_desc='$rlevar' and payment_group='rle'");
		$rleid=mysql_fetch_array($getrleid);
		mysql_query("insert into schedule_of_fees values ('','$rleid[payment_id]','$rledamount','misc','$rlecourse','$rleyear&','$sy','$sem')") or die(mysql_error());

$startrle++;
}


//for the other fee
$otherdata=$_POST['otherdata'];
$otherdataarr=explode("[endline]", $otherdata);
$otherdataarrlen=count($otherdataarr);
$startother=1;
while ($startother<$otherdataarrlen) {
	$otherdata2=$otherdataarr[$startother];
	$otherdata2arr=explode("<->", $otherdata2);
	$otherdesc=$otherdata2arr[0];	
	$otheramount=$otherdata2arr[1];	
	$othercat=$otherdata2arr[2];	

	$checkdesc=mysql_query("select * from paymentlist where payment_desc='$otherdesc' and payment_group='other'");
	$countoher=mysql_num_rows($checkdesc);
	if($countoher==0){
		mysql_query("insert into paymentlist values ('','$otherdesc','other')");
	}
	$getotherid=mysql_query("select payment_id from paymentlist where payment_desc='$otherdesc' and payment_group='other'");
	$otherid=mysql_fetch_array($getotherid);
	mysql_query("insert into schedule_of_fees values ('','$otherid[payment_id]','$otheramount','$othercat','','','$sy','$sem')") or die(mysql_error());


	$startother++;
}

//for the graduation fees
$graddata=$_POST['graddata'];

$graddataarr=explode("[endline]", $graddata);
$graddataarrlen=count($graddataarr);
$startgrad=1;
while ($startgrad<$graddataarrlen){
	$graddata2=$graddataarr[$startgrad];
	$graddata2arr=explode("<->", $graddata2);
	$graddesc=$graddata2arr[0];	
	$gradamount=$graddata2arr[1];	
	$gradcat=$graddata2arr[2];	

	$checkdesc=mysql_query("select * from paymentlist where payment_desc='$graddesc' and payment_group='grad'");
	$countgrad=mysql_num_rows($checkdesc);
	if($countgrad==0){
		mysql_query("insert into paymentlist values ('','$graddesc','grad')");
	}
	$getgradid=mysql_query("select payment_id from paymentlist where payment_desc='$graddesc' and payment_group='grad'");
	$gradid=mysql_fetch_array($getgradid);
	mysql_query("delete from schedule_of_fees where payment_id='$gradid[payment_id]' and sy='$sy' ");
	mysql_query("insert into schedule_of_fees values ('','$gradid[payment_id]','$gradamount','$gradcat','','&IV','$sy','0')") or die(mysql_error());


	$startgrad++;
}


//for the trans and new studens
$transdata=$_POST['transdata'];
$transdataarr=explode("[endline]", $transdata);
$transdataarrlen=count($transdataarr);
$starttrans=1;
while ($starttrans<$transdataarrlen){
	$transdata2=$transdataarr[$starttrans];
	$transdata2arr=explode("<->", $transdata2);
	$transdesc=$transdata2arr[0];	
	$transamount=$transdata2arr[1];	
	$transcat=$transdata2arr[2];	

	$checkdesc=mysql_query("select * from paymentlist where payment_desc='$transdesc' and payment_group='new'");
	$counttrans=mysql_num_rows($checkdesc);
	if($counttrans==0){
		mysql_query("insert into paymentlist values ('','$transdesc','new')");
	}
	$gettransid=mysql_query("select payment_id from paymentlist where payment_desc='$transdesc' and payment_group='new'");
	$transid=mysql_fetch_array($gettransid);
	mysql_query("insert into schedule_of_fees values ('','$transid[payment_id]','$transamount','$transcat','','I&','$sy','$sem')") or die(mysql_error());


	$starttrans++;
}

//for the other misc 
$othermiscdata=$_POST['othermiscdata'];
$othermiscarr=explode("[endline]", $othermiscdata);
$transdataarrlen=count($othermiscarr);
$startothermisc=1;
while ($startothermisc<$transdataarrlen){
	$othermiscdata2=$othermiscarr[$startothermisc];
	$othermiscdata2arr=explode("<->", $othermiscdata2);
	$othermiscdesc=$othermiscdata2arr[0];	
	$othermiscamount=$othermiscdata2arr[1];	
	$othermisccat=$othermiscdata2arr[2];	

	$checkdesc=mysql_query("select * from paymentlist where payment_desc='$othermiscdesc' and payment_group='othermisc'");
	$counttrans=mysql_num_rows($checkdesc);
	if($counttrans==0){
		mysql_query("insert into paymentlist values ('','$othermiscdesc','othermisc')");
	}
	$getothermiscid=mysql_query("select payment_id from paymentlist where payment_desc='$othermiscdesc' and payment_group='othermisc'");
	$othermiscid=mysql_fetch_array($getothermiscid);
	mysql_query("insert into schedule_of_fees values ('','$othermiscid[payment_id]','$othermiscamount','$othermisccat','','','$sy','$sem')") or die(mysql_error());


	$startothermisc++;
}

//end of other fees 

if($sy!=""){
?>
	<div id="searchschedresult">
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


			<option value="I"><?=$semval;?></option>
			<option value="I">First</option>
			<option value="II">Second</option>
			</select>

		<span name='<?=$semester;?>'><?=$semester;?></span>
		<script>
			$('#schedheader [value=<?=$semester;?>]:last').remove();
		</script>
		School Year: <select id="schedsy">
			<option><?=$sy;?></option>
			<?php
			$syarray=explode("-", $sy);

			$date=$syarray[0]-5;
			$a=1;
			while ($a<=10) {
				$date2=$date+1;
				echo "<option value='$date-$date2'>$date-$date2</option>";
				$date=$date2;
				$a++;
			}
			?>

		</select>
		<span class="b"><?=$sy;?></span>
		<script>
			$("option[value='<?=$sy;?>']:last").remove();
		</script>
		Year Levels: 
		<select id="schedyear">
			<option value="<?=$year;?>"><?=$year;?></option>
			<option value="I&II">I&II</option>
			<option value="III&IV">III&IV</option>
		</select>
		<span class="b"><?=$year;?></span>
		<script>
			$("option[value='<?=$year;?>']:last").remove();
		</script>
		</div>



	<div  id="schedcon">
		<table border id="schoolfeetable" style="width:100%">
			<?php
			function getcourse(){
 				$semester=$_POST['sem'];
				$sy=$_POST['sy'];
				$year=$_POST['year'];
					$course=mysql_query("select * from course where dept_id in (select dept_id from department) and course_id in (select course_id from schedule_of_fees where sy='$sy' and year_level='$year' and semester='$semester') order by dept_id") or die(mysql_error());
					 
					return $course; 
				}

			$count=mysql_num_rows(getcourse());
			?>
			<tr id="listcourses1">
				<td rowspan="2">School Fee<button onclick="addschoolfee('schoolfeerow')" class="addschoolfee schoolfee"></button></td><td rowspan="2">Payment<br>Category</td><td id="courses" colspan="<?=$count;?>">All Courses <button onclick="addcourse('not')"  id="addcourse"></button></td>
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
		 		
				$schoolfee=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and sy in ('$sy') and paymentlist.payment_group='sched' and semester='$semester' and year_level='$year' group by payment_desc order by paymentlist.payment_id asc") or die(mysql_error());
				$countschoolfee=mysql_num_rows($schoolfee);
				while ($schoolfeerow=mysql_fetch_array($schoolfee)) {
					?>
					<tr class="row schoolfeerow" id="row<?=$schoolfeerow['payment_id'];?>" name="<?=$schoolfeerow['payment_id'];?>">
					
					<td><div style="position:relative">
						<input type="text" class="schoolfeedesc"  onkeyup="checkdescvalue(this)" name="<?=$schoolfeerow['payment_id'];?>" group="sched" value="<?=$schoolfeerow['payment_desc'];?>">
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
							<option value="tf">Trust Fund</option>
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
								$amount=mysql_query("select * from schedule_of_fees where course_id='$course[course_id]' and payment_id=$schoolfeerow[payment_id] and sy='$sy' and semester='$semester' and year_level='$year'") or die(mysql_error());
								$countamount=mysql_num_rows($amount);
								while ($amountrow=mysql_fetch_array($amount)) {
								
								
						?>
								<td class="regcourse colid<?=$course['course_id'];?>">
									<input type="text" class="feeamount" value="<?=$amountrow['amount'];?>" coursegroup="<?=$course['course_id'];?>" name="<?=$schoolfeerow['payment_id'];?>" description="<?=$amountrow['sched_id'];?>">
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
			<tr>
				<td colspan="10000" style="text-align:left">MISCELLANEOUS <button onclick="addschoolfee('row')" class="schoolfee miscfee"></button></td>
			
			</tr>
			
			 	<?php

				$schoolfee=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and sy in ('$sy') and paymentlist.payment_group='misc' and semester='$semester' and year_level='$year' group by payment_desc order by paymentlist.payment_id asc") or die(mysql_error());
				$countschoolfee=mysql_num_rows($schoolfee);
				while ($schoolfeerow=mysql_fetch_array($schoolfee)) {
					?>
					<tr class="row" id="row<?=$schoolfeerow['payment_id'];?>" name="<?=$schoolfeerow['payment_id'];?>">
					
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
							<option value="tf">Trust Fund</option>
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
								$amount=mysql_query("select * from schedule_of_fees where course_id='$course[course_id]' and payment_id=$schoolfeerow[payment_id] and sy='$sy' and semester='$semester' and year_level='$year'") or die(mysql_error());
								$countamount=mysql_num_rows($amount);
								while ($amountrow=mysql_fetch_array($amount)) {
								
								
						?>
								<td class="regcourse colid<?=$course['course_id'];?>">
									<input type="text" class="feeamount" value="<?=$amountrow['amount'];?>" coursegroup="<?=$course['course_id'];?>" name="<?=$schoolfeerow['payment_id'];?>" description="<?=$amountrow['sched_id'];?>">
									<span><?=$amountrow['amount'];?></span>
								</td>

						
						<?php					
							}
						}
						?>
						
				
				</tr>
				<?php
					}
				?>

			<tr>
		</table>

	</div> <!-- end of schedcon  -->

	<div id="separator">OTHER SCHOOL FEES</div>

	<!-- other fees -->
	<style type="text/css">
	.dupentry{right:-114px;top:-3px;}
	#rletable .hidval,.hidval{display:inline;font-size:14px}
	.adddesc,.removerlecourse,.rlecourseadd,.removeotherdesc,.removerlecourse,.rleamount,.rleyear,.cat,.otherdesc,.otheramount,.graddesc,.gradamount,.transdesc,.transamount {display:none}
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
		include 'dbconfig.php';
		$getcourse=mysql_query("select * from course,schedule_of_fees,paymentlist where course.course_id=schedule_of_fees.course_id and paymentlist.payment_id=schedule_of_fees.payment_id   and course.dept_id in (select dept_id from department order by dept_id) and paymentlist.payment_group='rle' and sy='$sy' and semester='$sem' and paymentlist.payment_desc='Related Learning Experience (RLE)' group by acronym order by sched_id") or die(mysql_error());
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

	</table>



	<table id="othertable" border>
		<tr>
			<td colspan="3" style="border-top:1px solid transparent;font-weight:bold;color:#515151">Other Fees</td>
		</tr>
		<tr>
			<td>Description<button onclick="addother()" class="adddesc">+</button></td><td class="th">Amount</td><td  class="th">Payment Category</td>
		</tr>
		<?php
			$getother=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='other' and sy='$sy' and semester='$sem' order by schedule_of_fees.sched_id asc") or die(mysql_error());
			$countother=mysql_num_rows($getother);
			while ($other=mysql_fetch_array($getother)) {

		?>
		<tr class="otherrow" id="otherrow<?=$other['payment_id'];?>" name="<?=$other['payment_id'];?>">
			<td><div style="position:relative">
			<input type="text" name="<?=$other['payment_id'];?>" schedid="<?=$other['sched_id'];?>" class="otherdesc" value="<?=$other['payment_desc'];?>">
			<span class="hidval"><?=$other['payment_desc'];?></span>
			<button onclick="removeotherdesc(<?=$other['payment_id'];?>)"  class="removeotherdesc"></button>
			</div>
			</td>
			<td  style="text-align:center"><input type="text" name="<?=$other['payment_id'];?>" class="otheramount" value="<?=$other['amount'];?>">
			<span class="hidval"><?=$other['amount'];?></span>
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
			<td>Description<button onclick="addgrad()" class="adddesc">+</button></td><td>Amount</td><td>Payment Category</td>
		</tr>
		<?php
				$getgrad=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='grad' and sy='$sy' and semester='$sem' order by schedule_of_fees.payment_id asc") or die(mysql_error());
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
				<span class="hidval"><?=$grad['amount'];?></span>
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
			<td>Description<button onclick="addtrans()" class="adddesc">+</button></td><td class="th">Amount</td><td class="th">Payment Category</td>
		</tr>
		<?php
				$getgrad=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='new' and sy='$sy' and semester='$sem'") or die(mysql_error());
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
				<span class="hidval"><?=$grad['amount'];?></span>
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
		?>	
	</table>


	<button  class="schedbut" onclick="updatesched(this)" id="updatesched">Edit</button>
	<button class="schedbut" onclick="cancelschedupdate(this)" id="cancelschedupdate">Cancel</button>
	 
	</div>
	</div> 
	<!-- end other fees -->
	</div>
<?php
}else{
	header('location:index.php');
}

?>