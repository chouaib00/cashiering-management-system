<?php
session_start();
include '../dbconfig.php';
$cash=$_POST['cash'];
$stud_id=$_POST['stud_id'];
$data=$_POST['data'];
 ?>

<script>
window.open("student/printpreviewscanreceipt.php?data=<?=$data;?>&stud_id=<?=$stud_id;?>","jenelyn");
window.open("asdfsdf.com","asdf")
</script>