<?php
session_start();
include '../numbertoword.php';
?>
<meta charset="utf-8">
<style type="text/css">
@media print { 
*{font-size:10px;}
.list {width:260px;line-height:10px;margin:0;}
.list div{background:red;margin:0 }
 
}
*{margin:0;padding:0;font-family:tahoma,verdana,arial;}
#listcon {height:140px; display:block;width:inherit;position:relative;top:30px;}
#numbertoword{width:280px;min-height:50px;padding:0 10px 0 10px;position:relative;top:5px;text-indent:100px}
 
</style>
<?php
$sy=$_SESSION['sy'];
$semester=$_SESSION['semester'];
$stud_name=$_REQUEST['name'];
$date=$_REQUEST['date'];
$cash=$_REQUEST['cash'];
$receip_num=$_REQUEST['receip_num'];
?>
<title>&nbsp;</title>
<div style="margin-left:20px;position:relative;width:3.9in;padding:0.7in 0 0 0">
	<p  style="padding-left:0.3in"><?=$date;?><br><?php echo date('h:i a') ;?></p> <br>
	<div style="width:2.5in;padding-left:0.3in;text-transform:capitalize"><?=$stud_name;?> <?=$sy." ".$semester;?></div>
	
	 
	<div id="listcon">
		<?php
		$date=$_REQUEST['date'];

		$data=$_REQUEST['data'];
		// echo "$data";
		$explode=explode("<endline>", $data);
 		$start=1;
 		$total=0;

 		//check if already saved
 		while ($start<count($explode)) {
			$data2=explode("<->", $explode[$start]);
			$name=$data2[0];
			$amount=$data2[1];
			$total+=$amount;
			$start++;
			?>
			<div class="list" style=" ;padding:0 10px 0 5px">
				<div style="display:inline-block; width:2in; white-space:nowrap;overflow:hidden;text-overflow:ellipsis"><?=$name;?></div>
				<div style=" text-align:right; width:50px;float:right"><?=number_format($amount,2);?></div>
				<div style="clear:both"></div>
			</div>

			<?php
		}
		?>
		

	</div>
	<div id="total" style="position:relative;left:229px"><?=number_format($total,2);?></div>
 
	<div id="numbertoword"> <?=receivenumber($total);?> only</div>
 	<div style="padding-left:5px">
		Amount received: <?=number_format($total,2);?><br>
		Change: 0.00
	</div>
</div>

 
 
<script type="text/javascript">
	window.print();
	setTimeout(function(){

	window.close();
	},500)
</script>
 