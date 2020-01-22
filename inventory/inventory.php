<style type="text/css">
	#inventorybut {position:relative;margin:0 0 -15px -6px;top:-5px;border:none;height:20px;background-repeat:no-repeat;padding:16px;background-position:5px 6px;border-radius:10px;width:20px;background-color:white;background-image:url(img/lens.png)}
</style>

<div id="contentheader">
 						
		<div id="headeroption">
			<span class="dailyreport" onclick="dailyreport('dummy')">Daily Report</span>
			<span class="dailydeposit"  onclick="dailydeposit('dummy')">Daily Deposit</span>
			<span class="monthlyreport" onclick="monthlyreport('monthlyreport')">Monthly Report</span>
			<span class="monthlyreport"  onclick="monthlyreport('monthlyreport2')">Monthly Report 2</span>
 		</div>
						
		<div style="float:right;margin:9px 8px 0 0;color:white">
			Search by: <span id="searchoptioncon" style="display:none"><input type="radio" name="searchoption" id="byday" onclick="searchoption('day')"><label for="byday">Day </label><input id="bymonth"  checked="checked" onclick="searchoption('month')" type="radio"  name="searchoption"><label for="bymonth">Month</label></span> <input type="month" id="date" value="<?=date('Y-m-d');?>">
			<button  id="inventorybut" onclick="searchinventory()"></button>
									
		</div>
						
					
	</div> 


<div id="inventorysearchresult">

</div>
<?php
include '../dbconfig.php';
$getlastmonth=mysql_query("select * from collection order by col_id desc");
$lastmonth=mysql_fetch_array($getlastmonth);
$lastday=$lastmonth;
$explodemonth=explode('/', $lastmonth['date']);
$lastmonth=$explodemonth[0]."/".$explodemonth[2];

?>
<script type="text/javascript">
function loader (){
	var a="<center style='color:gray'><br><br>Generating Report<br>Please wait.<br><img src='img/loading2.gif' height='30px'></center>";
	return a;
}
 function transferpayment(a,b){
  		$('#overlay, #modal').show();
 		$.ajax({
			type:'post',
			url:'inventory/askttransfer.php',
			data:{'receipt':a,'selecteddate':'<?=$date;?>','stud_id':b},
			success:function(data){
				$('#addcoursecon').html(data);

 			},
			error:function(){
				connection();
				$('#overlay, #modal').hide();
			}
		});

 } 

function searchoption(a){
	var b=$('#date');
	if(a=="day"){
		b.attr('type','date');
	}else{
		b.attr('type','month');

	}
}
function monthlyreport(a){
	$('#searchoptioncon').hide();
	$('#date').attr('type','month');
	$('#inventorybut').attr('onclick',"searchinventory(123,'"+a+"')");
	searchinventory('dummy',a);
}
	var xhr;
	function searchinventory(a,b){
		if(xhr && xhr.readystate != 4){
            xhr.abort();
        }
		var date=$('#date').val();
		var date2="";
		if(a=="dummy"){
			date2='<?=$lastmonth;?>';
		}
		$('#inventorysearchresult').html(loader());
		xhr=$.ajax({
			type:'post',
			url:"inventory/"+b+".php",
			data: {'date':date,'date2':date2},
			success:function(data){
				$('#inventorysearchresult').html(data);
			},	
			error:function(){
				connection();
			}
		})
	}

	function dailyreport(a,b){
 	$('#searchoptioncon').hide();
 		$('#date').attr('type','date');  		
 		$('#inventorybut').attr('onclick','dailyreport()');  		
 		$('#inventorysearchresult').html(loader());
 		var date=$('#date').val();
   		$.ajax({
			type:'post',
		 	ifModified:true,
		 	cache:true,
			url:'inventory/dailyreport.php',
			data: {'date':date},
			success:function(data){
				$('#inventorysearchresult').html(data);
			},	
			error:function(){
				connection();
			}
		});
   		$('#date').val('<?=date('Y-m-d');?>');
	}

 function searchdailyreport(a,b){
	 	var date=$('#date').val();

	 	if(a=="dummy"){
	 		date=b;
	 	}

	 	$('#inventorysearchresult').html(loader());
   		$.ajax({
			type:'post',
			url:'inventory/searchdailyreport.php',
			data: {'date':date,'checkdummy':a},
			success:function(data){
				$('#inventorysearchresult').html(data);
			},	
			error:function(){
				connection();
			}
		});
	}



	function dailydeposit(a,b){
		$('#inventorysearchresult').html(loader());
	$('#searchoptioncon').show();		
		$('#inventorybut').attr('onclick','searchdailydeposit()');
  		 $('#date').attr("type","month");
  		 $('#date').val("<?php echo date("Y-m");?>");
 		 
		var	date2="<?=$lastmonth;?>";
		 
 		 
  		$.ajax({
			type:'post',
			url:'inventory/dailydeposit.php',
			data: {'date2':date2},
			success:function(data){
				$('#inventorysearchresult').html(data);
			},	
			error:function(){
				connection();
			}
		});
		 
	}

	function searchdailydeposit(a){
 		$('#inventorybut').attr('onclick','searchdailydeposit()');
		var date=$('#date').val();
		var date2="";
		 
		$.ajax({
			type:'post',
			url:'inventory/searchdailydeposit.php',
			data: {'date':date,'date2':date2},
			success:function(data){
				$('#inventorysearchresult').html(data);
			},	
			error:function(){
				connection();
			}
		});
	}

 
	dailyreport('dummy');


 function reprintreceipt(a){
 		$('#overlay, #modal').show();
 		$.ajax({
			type:'post',
			url:'inventory/asktoreprintreceipt.php',
			data:{'receipt':a,'selecteddate':'<?=$date;?>'},
			success:function(data){
				$('#addcoursecon').html(data);

 			},
			error:function(){
				connection();
				$('#overlay, #modal').hide();
			}
		});

 } 

 function cancelreceipt(a,t){
 	var date=$(t).attr('name');

	var b=confirm("Are you sure you want to cancel this receipt");
	if(b==true){
		$.ajax({
			type:'post',
			url:'inventory/cancelreceipt.php',
			data:{'receipt':a},
			success:function(data){
				dailyreport('dummy');
			},
			error:function(){
				connection();
			}
		});
	}
}


function editdaily(id){
	$("#dailydeposittable #col_id"+id+" span");
	$("#dailydeposittable #col_id"+id+" input").toggle();
	$("#dailydeposittable #col_id"+id+" a").toggle();
	$("#dailydeposittable #col_id"+id+" td:eq(1)").css("text-align","center");
	$("#dailydeposittable #col_id"+id+" td:eq(3)").css("text-align","center");
	$("#dailydeposittable #col_id"+id+" td:eq(2)").css("text-align","center");
}

function saveupdatedailydeposit(id,a,amount){
	var date=$(a).attr('date');
	var tui=parseInt($("#dailydeposittable #col_id"+id+" input:eq(0)").val());
	var misc=parseInt($("#dailydeposittable #col_id"+id+" input:eq(1)").val());
	var tf=parseInt($("#dailydeposittable #col_id"+id+" input:eq(2)").val());
	 var check=0;
 var total=tf+misc+tui;

 if(isNaN(tui)==true){
	check=1;
	$("#dailydeposittable #col_id"+id+" input:eq(0)").val("").css("border","1px solid red");
}

if(isNaN(misc)==true){
	check=1;
	$("#dailydeposittable #col_id"+id+" input:eq(1)").val("").css("border","1px solid red");
 }

if(isNaN(tf)==true){
	check=1;
	$("#dailydeposittable #col_id"+id+" input:eq(2)").val("").css("border","1px solid red");
 }

 if(check==0){
 	if(total<=0){
 		alert("Please enter an amount");
 	}else{
 			if(amount<total){
 				alert("Money has Exceeded. Please check your input.")
 			}else{
			 	$.ajax({
					type:'post',
					url:'inventory/saveupdatedailydeposit.php',
					data:{'id':id,'tui':tui,'misc':misc,'tf':tf,'date':date},
					success:function(data){
						$('#col_id'+id).after(data).hide();
						searchdailydeposit(1123);
						
					},
					error:function(){
						connection();
					}
				});
	 	}
 	}
}

}

 
</script>
