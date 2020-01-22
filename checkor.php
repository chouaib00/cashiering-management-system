<?php
include "dbconfig.php";
$receipt_num=$_POST['receipt_num'];
$or=mysql_query("select date from collection where receipt_num='$receipt_num' group by receipt_num limit 1") or die(mysql_error());
if(mysql_num_rows($or)==1){
echo "existed";
}else{
echo "ok";
}

?>