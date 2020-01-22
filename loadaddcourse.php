<div id="addcourseheader">ADD COURSE &nbsp;<img id="addcourseload" src="img/loading.gif" style="display:none;position:relative;top:3px"><button class="closemodal" onclick="closemodal()"></button></div>
						<div style="padding:5px">
							<table id="addcoursetable">
								<tr>
									<td>Course Name:</td>
									<td style="width:225px">
									<select id="choosecourse" onchange="selectaddcourse(this)">
										<?php
										$semester=$_POST['sem'];
										$sy=$_POST['sy'];
										$year=$_POST['year'];
										$check=$_POST['check'];
										////


									$newyear="";
									 
									$exyear=explode("&",$year);
									$yearlen=count($exyear)-1;
									 
									while (0<=$yearlen) {
									  if($newyear==""){
									    $newyear.=" year_level like '%&$exyear[$yearlen]&%' or year_level like '$exyear[$yearlen]&%' or year_level like '%&$exyear[$yearlen]'";
									  }else{
									        $newyear.=" or year_level like '%&$exyear[$yearlen]&%' or year_level like '$exyear[$yearlen]&%' or year_level like '%&$exyear[$yearlen]'";

									      }
									  $yearlen--;
									}
 										//
										include 'dbconfig.php';
											if($check=="all"){
												$getcourse="";
											}else{
												$getcourse=mysql_query("select * from course where course_id not in (select course_id from schedule_of_fees,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and payment_group='misc' and semester='$semester' and sy='$sy' and (year_level like '%&II&%' or year_level like 'II&%' or year_level like '%&II' or year_level like '%&I&%' or year_level like 'I&%' or year_level like '%&I' )  group by course_id) order by description asc") or die(mysql_error());
											}
											$countcourse=mysql_num_rows($getcourse);
											while ($row=mysql_fetch_array($getcourse)) {
												echo "<option value='$row[course_id]' name='$row[acronym]'>$row[description]</option>";
											}
											echo "<option value='new'>Add New Course</option>";
										if($countcourse==0){
											?>
												<script>
												$(function() {

													$('#addcoursetable,.cancelcourse').hide();
													$('#addcourseform').show();
												});
												</script>
											<?php
										}
										?>
										
									</select>
									</td>
								</tr>
								<tr>
									<td></td><td ><button id="saveaddcoursebut" onclick="savecourse()">ADD COURSE</button></td>
								</tr>
							</table>

							<table id="addcourseform" style="display:none">
								<tr><td>Course Name:</td><td style="position:relative"><input type="text" id="newcourse"><button class="cancelcourse" onclick="cancelcourse()"></button></td></tr>
								<tr><td> Course Acronym:</td><td><input type="text" id="newacronym"></td></tr>
								
								<tr id="listdeptrow"><td style="width:132px">Department:</td>
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
									<td >Department Name:</td><td style="position:relative"><input type="text" class="listdept">
										<button onclick="checkdepartment('add')" class="canceldepartment" title="Cancel add new department"></button>
									</td>
								</tr>

								<tr class="adddeptrow">
									<td>Dept. Acronym:</td><td><input type="text" id="newdacronym"></td>
								</tr>
								<tr>
									<td></td><td><button onclick="savecourse()" id="savecourse">ADD COURSE</button></td>
								</tr>
							</table>
						</div>