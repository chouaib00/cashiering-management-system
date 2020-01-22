<?php
session_start();
include '../dbconfig.php';
$cash=$_POST['cash'];
$or=$_POST['or'];
$stud_id=$_POST['stud_id'];
echo "$cash- $stud_id- $or sdf";

 ?>

<script>
 // window.open("student/printpreview.php?stud_id=<?=$stud_id;?>&sy=<?=$sy;?>&semester=<?=$semester;?>&or="+or+"&cash="+cash+"&change="+change,"somewhere").focus();

</script>