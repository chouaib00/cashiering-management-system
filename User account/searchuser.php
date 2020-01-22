<?php
include('../dbconfig.php');
$name=$_POST['name'];
$user=mysql_query("select * from user where name like '%$name%'   order by name limit 20 ");
if($c=mysql_num_rows($user)==0){
?>
<tr>
<td style="text-align:center">No result found.</td>
</tr>
<?php
}
while($row=mysql_fetch_array($user)){
?>
<tr onclick="selectsearch(<?=$row['user_id'];?>)">
<td style="text-transform:capitalize"><?=$row['name'];?></td><td><?=$row['department'];?></td>
</tr>
<?php

}