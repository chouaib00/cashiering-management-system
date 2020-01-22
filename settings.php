<?php
session_start();
include 'dbconfig.php';
$getyear=mysql_query("select * from schedule_of_fees group by sy");
?>
<style type="text/css">
	#settings select{padding:5px;}
	#settings button{padding:7px;}
	#signatory th,#signatory td{padding:4px;}
	#signatory {padding:4px;width:400px;margin:0 auto;margin-top:10px;}
	#signatory th,#signatory td{border:1px solid #636363;}
	.action a {color:blue}
	.action   {text-align: center;}
	.action a:hover {text-decoration: underline;cursor:pointer;}

</style>
<div id="settings" style="padding:5px;text-align:center">
<?php if($_SESSION['type']=='admin'){?>
Semester:
<select id="setsemester">
	<option>I</option>
	<option>II</option>
</select>

 Set School-year
<select id="setsy">

  <?php
  if(mysql_num_rows($getyear)==0){
  		$date=date('Y')-10;
			$loop=1;
 			$dateend2=$date+1;
 			$date2=$date."-".$dateend2;
	?>
 		<?php
			while ($loop<20) {
				$dateend=$date+1;
				echo "<option>$date-$dateend</option>";
					$date=$dateend;
				$loop++;
			}
  }
while ($row=mysql_fetch_array($getyear)) {
	?>
	<option><?=$row['sy'];?></option>
	<?php
}
?>

</select>


<button onclick="setsy()">Save Settings</button>
<table id="signatory" >
	<tr><td colspan="3" style="padding:5px;background:#686868;color:white">
		Signatory <button style="padding:3px;line-height:8px;" onclick="addsignatory();">+</button>
	</td>
	<tr>
		<th>Name</th>
		<th>Status</th>
		<th>Action</th>
	</tr>
	</tr>
	<?php
	$sign=mysql_query('select * from Signatory');
	while ($row=mysql_fetch_array($sign)) {
		?>

		<tr>
			<td style="text-transform:capitalize;text-align:left"><?=$row['name'];?></td>
			<td style="text-align:left"><?=$row['status'];?></td>
			<td class="action" style="text-align:center"><a onclick="choosesign(<?=$row['sig_id'];?>,'activate')">Activate</a></td>
 		</tr>
		<?php
	}
	?>
</table>

<?php }else {?>
	<style type="text/css">
	 .fordate{
	 	padding:3px;
	 }
</style>
	<?php
		$date=$_SESSION['date'];
		$explode=explode('/', $date);
	?>	
	Set System Date: <input type="date" value="<?=$explode[2]."-".$explode[0]."-".$explode[1];?>" id="systemdate" class="fordate"><button class="fordate" onclick="setdate()">Set Date</button>

<?php } ?>
</div>


<script>
function setdate(){
	var date=$('#systemdate').val();
	$.ajax({
		type:'post',
		url:'setdate.php',
		data:{'date':date},
		success:function(){
			window.location="";
 		},
		error:function(){
			alert("Unable to connect to server. Please try again!")
		}
	})
}
function addsignatory( ){
	var cover=$('#overlay,#modal');
	cover.show();
	$.ajax({
		type:'post',
		url:'addsignatory.php',
 		success:function(data){
 			$('#addcoursecon').html(data).show();
  
 		},
		error:function(){
			connection();
 		}

	});
}
 
function choosesign(a,b){
	$.ajax({
		type:'post',
		url:'signatory.php',
		data: {'signatory':a,'action':b},
		success:function(data){
			menu('settings');
 		},
		error:function(){
			connection();
		}

	});
}
function setsy(){
	var sy=$('#setsy').val();
	var semester=$('#setsemester').val();
	var con=confirm("Are you sure you want to set the system to "+semester+" semester and school-year "+sy+"?");
	if(con==true){
	$.ajax({
		type:'post',
		url:'savesettings.php',
		data: {'sy':sy,'semester':semester},

		success:function(data){
			alert("System was successfully set to "+semester+" semester and school-year "+sy);
			$('#csemester').html(semester);
			$('#csy').html(sy);
		},
		error:function(){
			alert('error')
		}

	});
}
}
</script>