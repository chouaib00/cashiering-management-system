	<link href="css/scheduleoffees.css" type="text/css" rel="stylesheet"></link>
	<?php
	include 'dbconfig.php';
	$getlastsched=mysql_query("select sched_id,sy,semester,year_level from schedule_of_fees where year_level='I&II' or year_level='III&IV' and year_level!='' order by sy desc limit 1");
	$lastsched=mysql_fetch_array($getlastsched);
	$semester=$lastsched['semester'];
	$sy=$lastsched['sy'];
	$year=$lastsched['year_level'];
	?>
	<div id="schedsearch">

		<div id="headeroption">
			<span id="addsched" onclick="loadaddsched()">Add School Fees</span>
	 	<img src="img/loading.gif" id="addschedloader">
 
	 	</div>

		<div id="schedopt">
			<img src="img/loading.gif" style="position:absolute;right:460px">
	 		Semester: <select><option value="I">First</option><option value="II">Second</option></select>
	 		School Year: <select>
				<?php
					$getschoolyear=mysql_query("select sy from schedule_of_fees group by sy order by sy desc");
						while($row=mysql_fetch_array($getschoolyear)) {
							echo "<option>$row[sy]</option>";

						}
						if(mysql_num_rows($getschoolyear)==0){
							$date=date('Y');
							$date2=date('Y')+1;
						?>

							<option><?=$date."-".$date2;?></option>
						<?php
						}

				?>
			</select>
				Year Levels: <select id="searchforlevel">
				<!-- get year Levels -->
				<?php $getlevels=mysql_query("select * from schedule_of_fees,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and payment_group='sched' and year_level!='' group by year_level");
					// while ($row=mysql_fetch_array($getlevels)) {
					// 	echo "<option value='$row[year_level]'>$row[year_level]</option>";
					// }
				?>
				<option>I</option>
				<option>II</option>
				<option>III</option>
				<option>IV</option>
			
			
			</select>
			
			<script>
				$('#searchforlevel option').each(function(){
					var a =$(this).text();
					if(a.split("&").length==2){
						if(a.split("&")[1]=="" || a.split("&")[0]==""){
							$(this).text(a.replace('&', ""))
						}
					}
				});
			</script>
			<button onclick="searchsched()" id="searchschedbut" title="Search Schedule Of Fees"></button>
	 	</div>
	</div>
	<div id="searchschedresult">
	</div>
	<script type="text/javascript" src="js/scheduleoffees.js"></script>
	<script>
		gettotal();
		loadaddsched();
	</script>