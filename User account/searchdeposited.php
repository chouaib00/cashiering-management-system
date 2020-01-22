<?php
include '../dbconfig.php';
 $datepost2=$_POST['date'];
 $check=$_POST['check'];
 if($check==1){
	$date=$datepost2;
 }else{
	$datearray=explode("-", $datepost2);
	$date=$datearray[1]."/".$datearray[2]."/".$datearray[0];
}
echo "<span id='getdate'>$date</span>";
$user_id=$_POST['user_id'];
?>
 <table border class="logtable" id="paymentlog" style="float:right">
	<tr>
		<td colspan="11" id="paymentheader">Payment Logs
		<div>
			<input type="date" id="depositeddate">
			<button onclick="searchdeposited()">Search</button>
		</div>
		</td>
	</tr>

	<tr>
		<th>Date</th><th >O.R</th><th>Name</th><th>Amount</th>		
	</tr>
	<tr id="searchresultdeposited">
		
	</tr>
	<?php

	//get last date
	$getlastdate=mysql_query("select * from collection where user_id='$user_id' order by col_id desc limit 1");
	$daterow=mysql_fetch_array($getlastdate);
	//insert dummy data
	mysql_query("truncate dummydata");
	 $studpay=mysql_query("select * from collection where user_id='$user_id'  and date='$date' group by receipt_num order by collection.receipt_num asc");
	 while ($row=mysql_fetch_array($studpay)) {
	 		mysql_query("insert into dummydata values ('','$row[col_id]','$row[receipt_num]')");
	 }

		  
	 	$total=0;
 		$getdate=mysql_query("select *,collection.receipt_num from collection,dummydata where dummydata.col_id=collection.col_id and  user_id='$user_id'  and date='$date' group by collection.receipt_num order by dummydata.receipt_num,collection.receipt_num asc") or die(mysql_error());
		while ($getdaterow=mysql_fetch_array($getdate)) {
			//get amount collected of every data
			$amount=mysql_query("select SUM(amount) as amount,col_id,remark from collection where user_id='$user_id'   and receipt_num='$getdaterow[receipt_num]'");
			$amountrow=mysql_fetch_array($amount);
			$strike="";
			
			$getname=mysql_query("select * from student where stud_id='$getdaterow[stud_id]'");
			$namerow=mysql_fetch_array($getname);
				$date=$getdaterow['date'];
			$strike="";
			if($getdaterow['remark']=="Cancelled"){
				$strike="text-decoration:line-through";
			}
			
			if($amountrow['remark']=='0'){
				$total=$total+$amountrow['amount'];
 			}else{
 				if($amountrow['remark']=='0'){ 					
 				$strike="text-decoration:line-through";
 				}
 			}
			?>

			<tr class="loglist" id="loglist<?=$getdaterow['col_id'];?>" style="<?=$strike;?>">
				<td style="width:100px"><input type="checkbox" value="<?=$getdaterow['receipt_num'];?>" id="label<?=$getdaterow['receipt_num'];?>"> <label for="label<?=$getdaterow['receipt_num'];?>"><?=$getdaterow['date'];?></label></td>
				<td><?=$getdaterow['receipt_num'];?></td>				
				<td style="text-transform:capitalize"><div style="white-space:nowrap;text-overflow:ellipsis;width:200px;overflow:hidden">as dfsdfsfadfds<?=$namerow['lname'].", ".$namerow['fname'];?></div></td>				
 				<td style="text-align:right;width:150px">
				<?php
						echo number_format($amountrow['amount'],2);
						
					?>
				</td>	
				 	
			</tr>
			
			<?php
		}
	?>
	<tr>
		<td colspan="3" style="text-align:right;">
		<a  style="color:blue;text-decoration:underline;cursor:pointer" onclick="checkall()"><button class="logbuts">Check All</button></a>
		<a id="printcollection"  href="" target="_blank" title="Print Report for this user."><button class="logbuts">Print Collection</button></a>&nbsp;&nbsp;&nbsp;<b>Total: </b></td>
		<td style="text-align:right"><b><?php echo number_format($total,2);?></b></td>
	</tr>

	 
</table>
<script type="text/javascript">
	 function checkall(){
 	$('input:checkbox').click();
  }
   $('#printcollection').click(function(){
 	var receipt=""
 	$('input:checkbox:checked').each(function(){
 		if(receipt==""){
 			receipt=$(this).val();
 		}else{
 			receipt+=","+$(this).val();

 		}
 	});
 	 $(this).attr("href","user account/printindividual.php?receipt="+receipt+"&date=<?=$date;?>");

 		 
 })
</script>
<table border class="logtable" id="paymentlog" style="float:right">
	<tr>
		<th>Amount Collected</th>
		<th>Deposited Amount</th>
		<th>Undeposited Amount</th>
		<th>Action</th>
	</tr>
	<tr>
		<td><?php echo number_format($total,2);?></td>
		<?php
		$getdeposit=mysql_query("select sum(deposited_amount) as amount from user_deposit where date='$date' and user_id='$user_id'");
		$deposited=mysql_fetch_array($getdeposit);
		$undeposited=$total-$deposited['amount'];
		
		?>
		<td style="text-align:center"><?php echo number_format($deposited['amount'],2);?> </td>
		<td style="text-align:center"><?php echo number_format($undeposited,2);?></td>
		<td style="text-align:center;width:120px">
		<?php 
		if($undeposited>0){

		?>
		<input type="number" id="depositamount" style="width:50px;padding:2px"><button onclick="deposit(<?=$undeposited;?>)">Deposit</button>
		<?php 
			
		}else{
			 echo "Done";
		}
		?>
		</td>

	</tr>
</table>