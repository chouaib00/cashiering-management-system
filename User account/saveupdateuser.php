<?php
include('../dbconfig.php');
$id=$_POST['id'];
$uname=$_POST['uname'];
$username=$_POST['username'];
$status=$_POST['status'];
$designation=$_POST['designation'];
 mysql_query("update user set name='$uname',username='$username',status='$status',type='$designation' where user_id='$id'") or die(mysql_error());
$getuser=mysql_query("select * from user where user_id='$id'");
$row=mysql_fetch_array($getuser);
?>
 							<td ><?=$row['name'];?></td>
							<td><?=$row['username'];?></td>
							<td style="text-align:center"><span>******</span><input style="display:none" type="password" id="resetpassword"> <img onclick="reset(<?=$row['user_id'];?>)" style="cursor:pointer" src="img/reset.png" title="Reset Password">
							<input type="submit" value="Reset" onclick="savereset(<?=$row['user_id'];?>)" style="display:none;cursor:pointer;background:#44bcd2;color:white">
							</td>
							
							<td>
								<?php
								if($row['status']==1){
								echo "Activated";
								}else{
								echo "Deactivated";
								}
								?>
							</td>
							<td style="text-align:center">
								<?=$row['type'];?> 

							</td>
							<td class="action">
								<div class="actionwrap">
									<img src="img/loading.gif" class="uploading" id="uploading<?=$row['user_id'];?>">
									<?php if($row['type']!="admin"){?>
									<button style="float:left;" class="updateuserbut" id="updatebut<?=$row['user_id'];?>" onclick="updateuser(<?=$row['user_id'];?>)"></button>
									<button onclick="log(<?=$row['user_id'];?>)" id="log" ></button>
									
									<?php }else{
										?>
									<button onclick="log(<?=$row['user_id'];?>)" id="log" style="float:right;right:5px;position:relative"></button>

										<?php
										} ?>	
								</div>
							</td>
 						 
