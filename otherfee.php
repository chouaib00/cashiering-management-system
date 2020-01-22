
<link rel="stylesheet" type="text/css" href="css/style.css">
<link rel="stylesheet" type="text/css" href="css/scheduleoffees.css">
<style type="text/css">
.dupentry{right:-114px;top:-3px;}
#rletable .hidval,.hidval{display:inline;font-size:14px}
.adddesc,.removerlecourse,.rlecourseadd,.removeotherdesc,.removerlecourse,.rleamount,.rleyear,.cat,.otherdesc,.otheramount,.graddesc,.gradamount,.transdesc,.transamount {display:none}
.rlerow:hover,.otherrow:hover,.gradrow:hover,.transrow:hover {background:#cce9f1;}
</style>
<div id="otherfeemaincon">
<table id="rletable" border>
	<tr>
		<td colspan="3">Related Learning Experience (RLE)</td>
	</tr>
	<tr>
		<td>Courses</td><td>Year Level</td><td>Amount</td>
	</tr>

	<?php
	$sem="I";
	$sy="2010-2011";
	include 'dbconfig.php';
	$getcourse=mysql_query("select * from course,schedule_of_fees,paymentlist where course.course_id=schedule_of_fees.course_id and paymentlist.payment_id=schedule_of_fees.payment_id   and course.dept_id in (select dept_id from department order by dept_id) and paymentlist.payment_group='rle' and sy='$sy' and semester='$sem' and paymentlist.payment_desc='Related Learning Experience (RLE)' group by course.acronym order by sched_id") or die(mysql_error());
	while ($course=mysql_fetch_array($getcourse)) {
	?>
	<tr class="rlerow rlerow<?=$course['course_id'];?>" id="rlerowa<?=$course['sched_id'];?>" name="<?=$course['sched_id'];?>" rlecourse="<?=$course['course_id'];?>">
		<td><div style="position:relative"><input type="hidden" class="rlecourse" value="<?=$course['course_id'];?>">
		<span><?=$course['acronym'];?></span>
				<button onclick="rlecourseadd(<?=$course['course_id'];?>)"  class="rlecourseadd" title="Add <?=$course['acronym'];?>"></button>
				<button onclick="removerlecourse(<?=$course['sched_id'];?>)"  class="removerlecourse"></button>

		</div>
		</td>
		<td style="text-align:center">
			<select schedid="<?=$course['sched_id'];?>" name="<?=$course['course_id'];?>" class="rleyear" onchange="checkrlecourse(this,<?=$course['course_id'];?>)">
				
				<option <?php if($course['year_level']=='I'){ echo "selected='selected'";}?> >I</option>
				<option <?php if($course['year_level']=='II'){ echo "selected='selected'";}?> >II</option>
				<option <?php if($course['year_level']=='III'){ echo "selected='selected'";}?> >III</option>
				<option <?php if($course['year_level']=='IV'){ echo "selected='selected'";}?> >IV</option>
						
			</select>
					<span class='hidval'><?=$course['year_level'];?></span>

					<div class="dupentry"><div></div>Duplicate entry</div>			

		</td>
		
		
		<td><input type="text" value="<?=$course['amount'];?>" name="a<?=$course['sched_id'];?>" class="rleamount">
		<span class='hidval'><?=$course['amount'];?></span>
		</td>
		
	</tr>
	<?php
		}
	?>

</table>



<table id="othertable" border>
	<tr>
		<td colspan="3">Other Fees</td>
	</tr>
	<tr>
		<td>Description<button onclick="addother()" class="adddesc">+</button></td><td>Amount</td><td>Payment Category</td>
	</tr>
	<?php
		$getother=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='other' and sy='$sy' and semester='$sem' order by schedule_of_fees.sched_id asc") or die(mysql_error());
		$countother=mysql_num_rows($getother);
		while ($other=mysql_fetch_array($getother)) {

	?>
	<tr class="otherrow" id="otherrow<?=$other['payment_id'];?>" name="<?=$other['payment_id'];?>">
		<td><div style="position:relative">
		<input type="text" name="<?=$other['payment_id'];?>" schedid="<?=$other['sched_id'];?>" class="otherdesc" value="<?=$other['payment_desc'];?>">
		<span class="hidval"><?=$other['payment_desc'];?></span>
		<button onclick="removeotherdesc(<?=$other['payment_id'];?>)"  class="removeotherdesc"></button>
		</div>
		</td>
		<td><input type="text" name="<?=$other['payment_id'];?>" class="otheramount" value="<?=$other['amount'];?>">
		<span class="hidval"><?=$other['amount'];?></span>
		</td>
		<td><select class="cat">
				<option <?php if($other['category']=='tui'){ echo "selected='selected'";}?> value="tui">Tuition</option>
				<option <?php if($other['category']=='tf'){ echo "selected='selected'";}?> value="tf">Trust Fund</option>
				<option <?php if($other['category']=='misc'){ echo "selected='selected'";}?> value="misc">Miscellaneous</option>
		</select>
		<?php
			$cat="";
			if($other['category']=="tui"){
				$cat="Tuition";
			}else if($other['category']=="tf"){
				$cat="Trust Fund";
			}else{
				$cat="Miscellaneous";
			}

		?>
		<span class="hidval"><?=$cat;?></span>
		</td>
	</tr>
	<?php
		}
	?>
</table>
 
<div style="display:inline;float:right">
<table id="gradtable" border>
	<tr>
		<td colspan="3">Graduation Fees</td>
	</tr>
	<tr>
		<td>Description<button onclick="addgrad()" class="adddesc">+</button></td><td>Amount</td><td>Payment Category</td>
	</tr>
	<?php
			$getgrad=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='grad' and sy='$sy' and semester='$sem' order by schedule_of_fees.payment_id asc") or die(mysql_error());
			$countgrad=mysql_num_rows($getgrad);
			while ($grad=mysql_fetch_array($getgrad)){

		?>
	<tr class="gradrow" id="gradrow<?=$grad['payment_id'];?>" name="<?=$grad['payment_id'];?>">
		<td>
		<div style="position:relative">
		<input type="text" name="<?=$grad['payment_id'];?>" schedid="<?=$grad['sched_id'];?>"  class="graddesc" value="<?=$grad['payment_desc'];?>">
		<span class="hidval"><?=$grad['payment_desc'];?></span>
		<button onclick="removegraddesc(<?=$grad['payment_id'];?>)"  class="removerlecourse gradbut"></button>
		</div>
		</td>
		<td><input type="text" name="<?=$grad['payment_id'];?>" class="gradamount" value="<?=$grad['amount'];?>">
			<span class="hidval"><?=$grad['amount'];?></span>
		</td>
		<td><select class="cat">
			<option <?php if($grad['category']=='tui'){ echo "selected='selected'";}?> value="tui">Tuition</option>
				<option <?php if($grad['category']=='tf'){ echo "selected='selected'";}?> value="tf">Trust Fund</option>
				<option <?php if($grad['category']=='misc'){ echo "selected='selected'";}?> value="misc">Miscellaneous</option>
		</select>
		<?php
			$cat="";
			if($grad['category']=="tui"){
				$cat="Tuition";
			}else if($grad['category']=="tf"){
				$cat="Trust Fund";
			}else{
				$cat="Miscellaneous";
			}

		?>
		<span class="hidval"><?=$cat;?></span></td>
	</tr>
	<?php
	}
	?>	
</table>


<table id="transtable" border>
	<tr>
		<td colspan="3">Additional Fees for New Students/Transferees</td>
	</tr>
	<tr>
		<td>Description<button onclick="addtrans()" class="adddesc">+</button></td><td>Amount</td><td>Payment Category</td>
	</tr>
	<?php
			$getgrad=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and paymentlist.payment_group='new' and sy='$sy' and semester='$sem'") or die(mysql_error());
			$countgrad=mysql_num_rows($getgrad);
			while ($grad=mysql_fetch_array($getgrad)){

		?>
	<tr class="transrow" id="transrow<?=$grad['payment_id'];?>" name="<?=$grad['payment_id'];?>">
		<td>
		<div style="position:relative">
		<input type="text" name="<?=$grad['payment_id'];?>" schedid="<?=$grad['sched_id'];?>" class="transdesc"  value="<?=$grad['payment_desc'];?>">
			<span class="hidval"><?=$grad['payment_desc'];?></span>
			<button onclick="removetransdesc(<?=$grad['payment_id'];?>)"  class="removerlecourse transbut"></button>
			</div>
		</td>
		<td><input type="text" name="<?=$grad['payment_id'];?>" class="transamount" value="<?=$grad['amount'];?>">
			<span class="hidval"><?=$grad['amount'];?></span>
		</td>
		<td><select class="cat">
			<option <?php if($grad['category']=='tui'){ echo "selected='selected'";}?> value="tui">Tuition</option>
				<option <?php if($grad['category']=='tf'){ echo "selected='selected'";}?> value="tf">Trust Fund</option>
				<option <?php if($grad['category']=='misc'){ echo "selected='selected'";}?> value="misc">Miscellaneous</option>
		</select>
		<?php
			$cat="";
			if($grad['category']=="tui"){
				$cat="Tuition";
			}else if($grad['category']=="tf"){
				$cat="Trust Fund";
			}else{
				$cat="Miscellaneous";
			}

		?>
		<span class="hidval"><?=$cat;?></span></td>
	</tr>
	<?php
	}
	?>	
</table>
	<button onclick="saveupdateotherfee()">save update</button>
	<button onclick="editotherfee()">edit</button>
</div>
</div>
<script type="text/javascript" src="js/jquery.min.js"></script>
<script type="text/javascript" src="js/scheduleoffees.js"></script>
<script type="text/javascript">
highlightrow()
function editotherfee(){
	$('table button').show();
	$('.hidval').hide();
	$('.rleyear,.rleamount,#othertable input,.cat,#gradtable input,#transtable input').show();

}
function checkrlecourse(a,b){
	var c=$(a).val();
	var e=$("select").index(a);
	var group=$(a).attr('duplicate');
	var name=$(a).attr('name');
	var check;
	var rand=randomid();
	var len=$('[duplicate="'+group+'"]').length;
		$('[rlecourse="'+b+'"] select').each(function(){
			if(len==2){
				$('[duplicate="'+group+'"]').removeAttr('duplicate').removeAttr('checkduplicate').css('border','1px solid #a3a3a3');
				$(".rlerow"+name+" .dupentry").hide();
			}
			if($(this).val()==c && e!=$("select").index($(this))){
				$(this).css("border","1px solid red").attr({'duplicate':rand,'checkduplicate':'yes'});
				$(a).css("border","1px solid red").attr({'duplicate':rand,'checkduplicate':'yes'});
				check=1;
			}
		})
	
		if(!check){
			$(a).css("border","1px solid #a3a3a3").removeAttr('duplicate').removeAttr('checkduplicate');
		}
		
}

function rlecourseadd(a){
	var b=$('.rlerow'+a+":last").clone();
	var rand=randomid();
	$(".rlerow"+a+":last").after(b);
	$(".rlerow"+a +":last button:eq(1)").attr('onclick','removerlecourse("'+rand+'")');
	
	$(".rlerow"+a +":last").css("background",'#ebebeb').attr({'id':'rlerow'+rand,'addedrow':'yes','name':rand}).removeAttr('remove');
	$(".rlerow"+a +" select").attr('duplicate',rand);
	$(".rlerow"+a +" select").removeAttr('schedid').attr('checkduplicate','yes');
	$(".rlerow"+a +":last input").attr('name',rand).val("");
	highlightrow();

}

/////////////

function saveupdateotherfee(){
	var sy='2012';
	var sem='II';
	var year=$('#schedyear').val();

		var rledata;
	$('.rlerow').each(function() {
		var rid=$(this).attr('name');
		var rlecourse=$('#rlerowa'+rid+" input:eq(0)").val();
		var rleamount=$('#rlerowa'+rid+" input:eq(1)").val();
		var rleyear=$('#rlerowa'+rid+" select").val();
		var rleschedid=$('#rlerowa'+rid+" select").attr('schedid');
		var remove=$('#rlerowa'+rid).attr('remove');
		rledata+="[endline]"+rlecourse+"<->"+rleamount+"<->"+rleyear+"<->"+rleschedid+"<->"+remove;
	});
	var otherdata;
	$('.otherrow').each(function(){
		var a=$(this).attr('name');
		var otherdesc=$('#otherrow'+a+" input:eq(0)").val();
		var otherschedid=$('#otherrow'+a+" input:eq(0)").attr('schedid');
		var otheramount=$('#otherrow'+a+" input:eq(1)").val();
		var othercat=$('#otherrow'+a+" select").val();
		var remove=$('#otherrow'+a).attr('remove');
		otherdata+="[endline]"+otherdesc+"<->"+otheramount+"<->"+othercat+"<->"+otherschedid+"<->"+remove;
		
	});
var graddata;
	$('.gradrow').each(function(){
		var a=$(this).attr('name');
		var graddesc=$('#gradrow'+a+" input:eq(0)").val();
		var gradschedid=$('#gradrow'+a+" input:eq(0)").attr('schedid');
		var gradamount=$('#gradrow'+a+" input:eq(1)").val();
		var gradcat=$('#gradrow'+a+" select").val();
		var remove=$('#gradrow'+a).attr('remove');
			graddata+="[endline]"+graddesc+"<->"+gradamount+"<->"+gradcat+"<->"+gradschedid+"<->"+remove;
	})

	var transdata;
	$('.transrow').each(function() {
		var a=$(this).attr('name');
		var transdesc=$('#transrow'+a+" input:eq(0)").val();
		var transschedid=$('#transrow'+a+" input:eq(0)").attr('schedid');
		var transamount=$('#transrow'+a+" input:eq(1)").val();
		var transcat=$('#transrow'+a+" select").val();
		var remove=$('#transrow'+a).attr('remove');
		transdata+="[endline]"+transdesc+"<->"+transamount+"<->"+transcat+"<->"+transschedid+"<->"+remove;
		
	});
	var check=0;
	$('.rleamount:visible,.gradamount:visible,.graddesc:visible,.transdesc:visible,.otherdesc:visible,.otheramount:visible,.transamount:visible').each(function(){
		if($(this).val()==""){
			$(this).css("border","1px solid red");
			check=1;
		}else{
			$(this).css("border","1px solid #a3a3a3");
		}
	});

		var checklen=$('select[checkduplicate=yes]').length;
	if(checklen>0){
		check=1;
		$('.dupentry').hide();
		$('select[checkduplicate]').css('border','1px solid red');
		$('select[checkduplicate=yes]').each(function(){
			var rid=$(this).attr('name');
			$('.rlerow'+rid+ " select[checkduplicate=yes]:first").next('.dupentry').show()
		
		});
	}
	 if(check==0){
	 	
		$.ajax({
			type:'post',
			url:'saveupdateotherfee.php',
			data: {'rledata':rledata,'otherdata':otherdata,'graddata':graddata,'transdata':transdata},
			success:function(data){
				$('tr input').each(function(){
					var vals=$(this).val();
					$(this).next('.hidval').html(vals).show();
				});
				$('tr select').each(function(){
					var vals=$(this).val();
					if(vals=="tui"){
						vals="Tuition";
					}else if(vals=="tf"){
						vals="Trust Fund";
					}else if(vals=="misc"){
						vals="Miscellaneous";
					}
					$(this).next('span').html(vals).show();
				});
				$('.removeotherdesc,.adddesc,.removerlecourse,.rlecourseadd,.rleyear,.rleamount,#othertable input,.cat,#gradtable input,#transtable input').hide();
			},
			error:function(){
				alert("asdf");
				loaderror();
			}
		});
 }

}

function removerlecourse(a){
	$('#rlerowa'+a).hide().attr('remove','yes');
}

function removeotherdesc(a){
	$('#otherrow'+a).hide().attr('remove','yes');
}
function refreshrle(){
	$('.rlerow,.transrow,.gradrow,.otherrow').show();
	$('[addedrow=yes]').remove();
	$('#rletable input,#othertable input,#gradtable input,#transtable input').val("");
	$('.rleamount,.gradamount,.otheramount,.transamount').css('border','1px solid #a3a3a3')
	$('[remove]').removeAttr('remove');
}

function removegraddesc(a){
	$('#gradrow'+a).hide().attr('remove','yes');
}
function removetransdesc(a){
	$('#transrow'+a).hide().attr('remove','yes');
}
function addmisc () {
	var a = $('.misccategory').html();
	var rid=randomid();
	$('#misctable tr:last').before("<tr id='row"+rid+"' class='rows'><td><input type='text' class='miscdesc'><button class='removeadd'  onclick='removeadd("+rid+")'></td><td><input type='text' class='miscamount'></td><td><select class='misccategory'>"+a+"</select></td></tr>");
}
 

function addgrad() {
	var a = $('#gradtable .gradrow:last').clone();
	var rand=randomid();
	$('#gradtable .gradrow:last').after(a);
	$('#gradtable .gradrow:last').attr({'id':'gradrow'+rand,'name':rand,'addedrow':'yes'}).removeAttr('remove').show();
	$('#gradtable .gradrow:last button').attr('onclick','removegraddesc("++")');
	$('#gradtable .gradrow:last input').val("").removeAttr('schedid');
}

function addtrans() {
	var a = $('#transtable .transrow:last').clone();
	var rand=randomid();
	$('#transtable .transrow:last').after(a);
	$('#transtable .transrow:last').attr({'id':'transrow'+rand,'name':rand,'addedrow':'yes'}).removeAttr('remove').show();
	$('#transtable .transrow:last button').attr('onclick','removetransdesc("'+rand+'")');
	$('#transtable .transrow:last input').val("").removeAttr('schedid');
}

function addother() {
	var a=$('#othertable .otherrow:last').clone();
	var rand=randomid();
	$('#othertable .otherrow:last').after(a);
	$('#othertable .otherrow:last').attr({'id':'otherrow'+rand,'name':rand,'addedrow':'yes'}).removeAttr('remove').show();
	$('#othertable .otherrow:last button').attr('onclick','removeotherdesc("'+rand+'")');
	$('#othertable .otherrow:last input').val("").removeAttr('schedid');
}
</script>