<?php
include('../dbconfig.php');
$id=$_POST['id'];
echo $id."asddf";
$user=mysql_query("select * from user where user_id='$id'");
$row=mysql_fetch_array($user);
 	?>						
		<td><?=$row['name'];?></td>
		<td><?=$row['username'];?></td>
		<td>*****</td>
		<td>
		<?php
		if($row['status']==1){
			echo "Activated";
		}else{
			echo "Deactivated";
		}
		?>
		</td>
		<td><?=$row['type'];?>
			<select style="display:none">
								<option>Collection</option>
								<option>Collection & Inventory</option>
							</select>
		</td>
		<td class="action">
			<div class="actionwrap">
				<img src="img/loading.gif" class="uploading" id="uploading<?=$row['user_id'];?>">
				<button style="float:left" class="updateuserbut" id="updatebut<?=$row['user_id'];?>" onclick="updateuser(<?=$row['user_id'];?>)"></button>
				<button onclick="log(<?=$row['user_id'];?>)" id="log" ></button>
			</div>
		</td>

					