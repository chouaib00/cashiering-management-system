<?php
include('dbconfig.php');
$scholar=mysql_query("select * from scholarship order by description asc");
?>
<option value="0">--None--</option>
<?php

while($row=mysql_fetch_array($scholar)){
?>
	<option  value="<?=$row['scholarship_id'];?>"><?=$row['description']." - ".number_format($row['amount'],2);?></option>
<? } ?>
<option value="Other">Add new scholarship</option>
