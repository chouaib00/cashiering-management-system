<?php
session_start();
include('../dbconfig.php');
$id =$_POST['id'];
$user=mysql_query("select * from user where user_id='$id'");
$row=mysql_fetch_array($user);
?>
	<td><input type="text" id="uname<?=$row['user_id'];?>" value="<?=$row['name'];?>" style="width:115px;"></td>

<td><input type="text" style="width:90px;" id="username<?=$row['user_id'];?>" value="<?=$row['username'];?>"></td>
<td>
	
</td>
<td>
		<select id="status<?=$row['user_id'];?>"  >
			<?php
			if($row['status']==1){
				echo "<option value='1'>Activate</option>";
				echo "<option value='0'>Deactivate</option>";
			}else{
				echo "<option value='0'>Deactivate</option>";
				echo "<option value='1'>Activate</option>";
			}
		?>
	</select>
	</td>
<td>
<select id="designation<?=$row['user_id'];?>">
	<option>Collection</option>
	<option>Collection & Inventory</option>
</select>
</td>
<td class="action">
	<div class="actionwrap">
		<button class="saveupdate" onclick="saveupdateuser(<?=$row['user_id'];?>)"></button>
		<button onclick="cancelupdate(<?=$row['user_id'];?>)" id="cancelupdate" ></button>
	</div>
</td>
