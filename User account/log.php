<?php
session_start();
include '../dbconfig.php';

$user_id=$_POST['user_id'];
 $getuser=mysql_query("select * from user where user_id='$user_id'");
 $getuser2=mysql_query("select * from user where user_id='$user_id'");
$userrow=mysql_fetch_array($getuser);
$status="";
if($userrow['status']==1){
$status="Activated";
}else{
$status="Deactivated";
}
?>
 
<style type="text/css">
	#paymentlog td{padding:2px;}
	#paymentlog #paymentheader{padding:4px;}
	#paymentlog #paymentheader div{display: inline-block;float:right;}
	#paymentlog {width:500px;}
	#getdate {display:none;}
.actionlog {width:470px;}
.actionlog td {padding:3px;}
.loglist a{text-transform:capitalize;}
.logtable {margin:6px;}
 .logtable td, .logtable th {border:1px solid #827e7e;}
.actionlog tr:first-child th {background:#525252;padding:6px;color:white;}
.logbuts{
		padding:6px;
		color:white;
		border-radius:4px;
		border:1px solid white;
		box-shadow:3px 3px 3px gray;
		margin:5px 0 5px 0;
 		background-image:linear-gradient(#2495dd,#0a598c);
	}

	.logbuts:focus{
			box-shadow:none;
	}
	button{

	}
</style>

<table id="userlist">
						<tr>
							<th>Name</th><th>Username</th><th>Status</th><th>Designation</th> 
 						</tr>
 						<tr class="searchcon">						
 						</tr>
 						<tr class="ulist">
						</tr>
						<?php
						while($row=mysql_fetch_array($getuser2)){
						?>						
						<tr class="ulist" id="ulist<?=$row['user_id'];?>">
							<td style="text-transform:capitalize"><?=$row['name'];?></td>
							<td><?=$row['username'];?></td>
							<td>
								<?php
								if($row['status']==1){
								echo "Activated";
								}else{
								echo "Deactivated";
								}
								?>
							</td>
							<td style="text-align:center">
								<?=$row['type'];?>
							</td>
							 
 						</tr>
						<?php
						}
						?>
						
					 
 					</table>


<table class="logtable actionlog" style="float:left">
	<tr>
		<th style="width:130px">Date & Time</th>
		<th style="text-align:left">Actions
		<div style="display:inline;float:right">
			<input type="date" id="searchlog" style="width:130px">
			<button onclick="actionlog()">Search</button>
		</div>
		</th>
  	</tr>
  	<tr id="searchlogresult">
  		
  	</tr>
  	<?php
  	$getlog=mysql_query("select * from user_log where user_id='$user_id'");
   	while ($logrow=mysql_fetch_array($getlog)) {
  		echo "<tr class='loglist'>";
  			echo "<td style='white-space:nowrap;vertical-align:top'>$logrow[date] $logrow[time]</td>";
  			echo "<td>$logrow[action]</td>";
  		echo "</tr>";
  	}
  	?>
	 
</table>
<div id="savedeposit">

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
	$date="";
	$getlastdate=mysql_query("select * from collection where user_id='$user_id' order by col_id desc limit 1");
	$daterow=mysql_fetch_array($getlastdate);
	$date=$daterow['date'];
	echo "<span id='getdate'>$date</span>";
	//insert dummy data
	mysql_query("truncate dummydata");
	$date="";
	 $studpay=mysql_query("select * from collection where user_id='$user_id'   and date='$daterow[date]' group by receipt_num order by collection.receipt_num asc");
	 while ($row=mysql_fetch_array($studpay)) {
	 		mysql_query("insert into dummydata values ('','$row[col_id]','$row[receipt_num]')");
	 }

		  
	 	$total=0;
 		$getdate=mysql_query("select *,collection.receipt_num from collection,dummydata where dummydata.col_id=collection.col_id and  user_id='$user_id'   and date='$daterow[date]' group by collection.receipt_num order by dummydata.receipt_num,collection.receipt_num asc") or die(mysql_error());
		while ($getdaterow=mysql_fetch_array($getdate)) {
			//get amount collected of every data
			$amount=mysql_query("select SUM(amount) as amount,col_id from collection where user_id='$user_id' and remark='0' and receipt_num='$getdaterow[receipt_num]'");
			$amountrow=mysql_fetch_array($amount);
			
			$getname=mysql_query("select * from student where stud_id='$getdaterow[stud_id]'");
			$namerow=mysql_fetch_array($getname);
			$total=$total+$amountrow['amount'];
			$date=$getdaterow['date'];
			$strike="";
			if($getdaterow['remark']=="Cancelled"){
				$strike="text-decoration:line-through";
			}
			?>

			<tr class="loglist" id="loglist<?=$getdaterow['col_id'];?>" style="<?=$strike;?>">
				<td style="width:100px"><input type="checkbox" value="<?=$getdaterow['receipt_num'];?>" id="label<?=$getdaterow['receipt_num'];?>"> <label for="label<?=$getdaterow['receipt_num'];?>"><?=$getdaterow['date'];?></label></td>
				<td><?=$getdaterow['receipt_num'];?></td>				
				<td style="text-transform:capitalize"><div style="white-space:nowrap;text-overflow:ellipsis;width:200px;overflow:hidden"><?=$namerow['lname'].", ".$namerow['fname'];?></div></td>				
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
	<tr id="savedepositcon">
		<td><?php echo number_format($total,2);?></td>
		<?php
		$getdeposit=mysql_query("select sum(deposited_amount) as amount from user_deposit where date='$daterow[date]' and user_id='$user_id'");
		$deposited=mysql_fetch_array($getdeposit);
		$undeposited=$total-$deposited['amount'];
		if($undeposited<0){
			$undeposited=0;
		}
		
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
</div>
 <script>

function actionlog(){
		var date=$('#searchlog').val();
			$.ajax({
				type:'post',
				url:'user account/searchactionlog.php',
				data:{'date':date,'user_id':<?=$user_id;?>},
				success:function(data){
					$('.actionlog .loglist').hide();
					$('#searchlogresult').after(data);
				},
				error:function(){
					connection();
				}
			});
			}

function searchdeposited(){
		var date=$('#depositeddate').val();
		var check=0;
		if(date==""){
			date='<?php echo $date;?>';
			check=1;
		}
  		$.ajax({
			type:'post',
			url:'user account/searchdeposited.php',
			data:{'date':date,'user_id':<?=$user_id;?>,'check':check},
			success:function(data){
				$('#savedeposit').html(data);
			 
			},
			error:function(){
				connection();
			}
		});
}

	function deposit(a){
		var amount=$('#depositamount').val();
		var date=$('#getdate').html();
  		if(amount>0){
  			if(amount>a){
  				alert("Money has Exceeded. Please check your input.")
  			}else{
				$.ajax({
					type:'post',
					url:'user account/savedeposit.php',
					data:{'date':date,'user_id':'<?php echo "$user_id";?>','amount':amount},
					success:function(data){
	 					searchdeposited();
					},
					error:function(){
						connection();
					}
				});
			}
		}else{
			alert("Invalid amount.")
		}
	}
</script>