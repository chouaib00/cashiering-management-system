<?php
include('dbconfig.php');
$name=$_POST['name'];
$department=$_POST['department'];
$username=$_POST['username'];
$password=$_POST['password'];
mysql_query("insert into user values('','$name','$department','$username','$password',0)");
$userid=mysql_query("select * from user where username='$username' and password='$password'");
$row=mysql_fetch_array($userid);
?>
<tr class="ulist" id="ulist<?=$row['id'];?>">
							<td><?=$row['name'];?></td>
							<td><?=$row['department'];?></td>
							<td><?=$row['username'];?></td>
							<td><?=$row['password'];?></td>
							<td>
								<?php
								if($row['status']==1){
								echo "Activated";
								}else{
								echo "Deactivated";
								}
								?>
							</td>
							<td class="action">
								<div class="actionwrap">
									<img src="img/loading.gif" class="uploading" id="uploading<?=$row['id'];?>">
									<button style="float:left" class="updateuserbut" id="updatebut<?=$row['id'];?>" onclick="updateuser(<?=$row['id'];?>)"></button>
									<button onclick="delete(<?=$row['id'];?>)" id="log" ></button>
								</div>
							</td>
 						</tr>
