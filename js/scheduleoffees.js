function addcourse (a) {
	var e=$('#addcoursecon');
	var d=$('#overlay,#modal');	
	var sem=$('#schedheader select:eq(0)').next('span').attr('name');
	var sy=$('#schedheader select:eq(1)').next('span').text();
	var year=$('#schedheader input:eq(0)').val();
		 
	d.show();
	e.html("<img src='img/loading2.gif' style='margin:15px 120px 15px 120px'>")
	$.ajax({
		type: 'post',
		url:'loadaddcourse.php',
		data: {'check':a,'sem':sem,'sy':sy,'year':year},
		success:function(data){
			e.html(data);
		},
		error:function(){
		loaderror();	
		}
	});
	
}

function refresh(){
	$('#schoolfeetable td,.row').show();
	$('.addedcourse,[addedrow=yes]').remove();
	$('.rlerow,.transrow,.gradrow,.otherrow').show();
	$('[addedrow=yes]').remove();
	$("#rletable input,.otheramount,.gradamount,.transamount").val("");
	$('.rleamount,.gradamount,.otheramount,.transamount').css('border','1px solid #a3a3a3');

	$('select option:selected').removeAttr('selected');
	$('.row input:gt(0)').each(function(){
		var a=$(this).attr('name');
		$('#row'+a+" input:gt(0)").val("").css('border','1px solid #a3a3a3');
	});

	$('.schoolfeedesc,.miscdesc').each(function(){
		var a=$(this).attr('name');
		var ref=$(this).attr('refreshval');
		$(this).val(ref);
	})
}

function gettotal(){
 	if($('#savemisc:visible').length==0){
	$('.feeamount').keyup(function(event){

		$('.miscrow:visible:first input:gt(0)').each(function(){
 			var col=$(this).attr("coursegroup");
			var total=0;
			var schedtotal=0;
			var misctotal=0;

			$('.colid'+col+":visible input[group='misc']").each(function(){
				
				if($(this).val()==""){
					misctotal+=0;
				}else{
					misctotal+=parseInt($(this).val());
				}
			});

			$('.colid'+col+"[name='subtotalmisc']").html(misctotal);

			$('.colid'+col+":visible input[group='sched']").each(function(){
 				if($(this).val()==""){
					schedtotal+=0;
				}else{
					schedtotal+=parseInt($(this).val());
				}
			});

			$('.colid'+col+"[name='subtotalsched']").html(schedtotal);

			$('.colid'+col+":visible input").each(function(){
				
				if($(this).val()==""){
					total+=0;
				}else{
					total+=parseInt($(this).val());
				}
			});

			$('.colid'+col+":last").html(total);
		})
	});

}
}

function gettotal2(){
 	if($('#savemisc:visible').length==0){
 	$('.schoolfeerow:visible input:gt(0)').each(function(){
 			var col=$(this).attr("coursegroup");
			var total=0;
			var schedtotal=0;
			var misctotal=0;

			$('.colid'+col+":visible input[group='misc']").each(function(){
				misctotal+=parseInt($(this).val());
			});

			$('.colid'+col+"[name='subtotalmisc']").html(misctotal+"000");

			$('.colid'+col+":visible input[group='sched']").each(function(){
				schedtotal+=parseInt($(this).val());
			});

			$('.colid'+col+"[name='subtotalsched']").html(schedtotal);

			$('.colid'+col+":visible input").each(function(){
				total+=parseInt($(this).val());
			});

			$('.colid'+col+":last").html(total);
		})
 }
}

function refresh(){
		$.ajax({
		type:'post',
		url:'loadaddsched.php',
		success:function(data){
			$('#searchschedresult').html(data);
		}
	});
}
 function checkdescvalue(a){
 	var b=$(a).val()
 	var c=$('input').index(a);
 	var rid=randomid();
 	var e=$(a).attr('checkvalue');
 	 	$(a).css('border','1px solid #a3a3a3');
 	 	var g;
 	 	var h;
 	 	var i=$('.row:visible [checkvalue="'+e+'"]').length;
 	 	$('.row [checkvalue="'+e+'"]').each(function(){
 	 		if($(this).val()!=b){
 	 			if(i==2){
 	 				$('.row [checkvalue="'+e+'"]').css('border','1px solid #a3a3a3').removeAttr('duplicate');
 	 			}
 	 		}
  	 	})
 	$('.schoolfeedesc:visible,.miscdesc:visible').each(function(){
 		if(b==$(this).val() && c!=$('input').index($(this))){
 			$(a).css('border','1px solid red').attr('checkvalue',"check"+rid).attr('duplicate','1');
 			$(this).css('border','1px solid red').attr('checkvalue',"check"+rid).attr('duplicate','1');
 			g=1;
 		} 			
 		
 	});
 	
 	if(g!=1){
 		$(a).removeAttr('checkvalue').removeAttr('duplicate');
 	}
 }

function loadaddsched () {
	var a=$('#searchschedresult');
	var  b=$('#addschedloader');
	b.show();
	$.ajax({
		type:'post',
		url:'loadaddsched.php',
		success:function(data){
			a.html(data);
			b.hide();
		},
		error:function(){
			b.hide();
			loaderror();
		}
	});

}

function selectaddcourse(){
	var a=$('#choosecourse').val();
	
	if(a=="new"){
		$('#addcourseform').toggle();
		$('#addcoursetable').toggle();
	}else{

	}
}

function searchsched(search){
	if(search=="search"){
		var sem=$('#schedheader select:eq(0)').val();
		var sy=$('#schedheader select:eq(1)').val();
		var year=$('#schedheader input:eq(0)').val();
		if(year.split("&").length==1){
		}else{
			year=year.split("&")[0];
		}
		 
	}else{
		var sem=$('#schedopt select:eq(0)').val();
		var sy=$('#schedopt select:eq(1)').val();
		var year=$('#schedopt select:eq(2)').val();
	}
		
	var b=$('#schedopt img');
	b.show();
	$.ajax({
		type:'post',
		url:'searchsched.php',
		data: {'sem':sem,'sy':sy,'year':year},
		success: function(data){
			b.hide();
			$('#searchschedresult').html(data);
			highlightrow();
		},
		error: function(){
			b.hide();
			loaderror();
		}
	})				
}
function removecourse(a,b){
	var c = confirm("Are you sure you want to remove this course?");
	if(c){
		if(b=='removedummy'){
			$('.colid'+a).remove();
		
		}else{
			$('.colid'+a).hide();
			$('.colid'+a+ " input").attr("remove","yes");
			var sem=$('#schedheader span:eq(0)').text();
			var sy=$('#schedheader span:eq(1)').text();
			var year=$('#schedheader span:eq(2)').text();
			if(b!="removedummy"){
	 			$.ajax({
					type:'post',
					url:'deleteaddedcourse.php',
					data:{'course_id':a,'sy':sy,'semester':sem,'year':year},
					success:function(data){
						searchsched("search");
					},
					error:function(){
						loaderror();
					}
				});
 			}
		}
	}else{

	}
	
	inheritvalue();
}
function removeadd (a) {
	$('#row'+a).hide();
	$('#row'+a+ " input:first").attr("remove","yes");
	gettotal2();
	gettotal();
}

function updatesched(a) {
	$(a).text('Save Update').attr('onclick','saveschedupdate(this)');
 	$('#cancelschedupdate,#schedheader select,#schoolfeetable input,#schoolfeetable select,.removeadd,.removecourse,.schoolfee,#addcourse').show();
	$('.removeadd').show();
	$('.row span,#schedheader span,#schedtotalbottom,#misctotalbottom,#totaltuiandmisc').hide();
	//other fees	
	$('table button').show();
	$('.hidval').hide();
	$('.rleyear,.rleamount,#othertable input,.cat,#gradtable input,#transtable input,#othermisctable input').show();


}

function cancelschedupdate2() {
	var sem=$('#schedheader select:eq(0)').next('span').attr('name');
	var sy=$('#schedheader select:eq(1)').next('span').text();	
	var year=$('#schedheader select:eq(2)').next('span').text();
 	$.ajax({
			type: 'post',
			url: "searchsched.php",
			data: {'sem':sem,'sy':sy,'year':year},
			success: function(data){
				$('#searchschedresult').html(data);
			},
			error:function(){
			cancelschedupdate();
			}
		});
}
function cancelschedupdate() {
	$.ajax({
			type: 'get',
			url: "scheduleoffees.php",
			success: function(data){
				$('#loadcontent').html(data);
			},
			error:function(){
			cancelschedupdate();
			}
		});
	 
}
function savecourse (){
	var rand=randomid();
	if($('#choosecourse:visible').length==1){

			var course_id=$('#choosecourse').val();
			var newcourse=$('#choosecourse option:selected').attr('name');
			$('#listcourses .regcourselist:last').after("<td class='addedcourse addedcourse regcourselist colid"+rand+"'><div style='position:relative'>"+newcourse+" <button class='removecourse' onclick=removecourse("+rand+",'remove')></button></div></td>");
				$('#schoolfeetable .schoolfeerow').each(function() {
					var rid=$(this).attr('name');		
				$('#row'+rid+ " .regcourse:last").after("<td class='regcourse addedcourse colid"+rand+"'><input type='text' class='feeamount' coursegroup='"+course_id+"'  group='sched' description='reg'><span></span></td>");
				});

				$('#schoolfeetable .miscrow').each(function() {
					var rid=$(this).attr('name');		
				$('#row'+rid+ " .regcourse:last").after("<td class='regcourse addedcourse colid"+rand+"'><input type='text' class='feeamount' coursegroup='"+course_id+"'  group='misc' description='reg'><span></span></td>");
				});

				$('#cancelschedupdate,#schedheader select,#schoolfeetable input,#schoolfeetable select,#schoolfeetable button').show();
				$('.row span,#schedheader span').hide();
				// gettotal();
				// gettotal2();
				var rle=$('.rlerow:last').clone();	
				var a=course_id;
				$('.rlerow:last').after(rle);
				$('.rlerow:last').attr({'class':"rlerow rlerow"+a,'id':'rlerows'+a,'name':"s"+a,'rlecourse':a});
				$('.rlerow:last input:eq(0)').val(a);
				$('.rlerow:last input:eq(1)').attr('name',"s"+a).val("");
				$('.rlerow:last select').attr({'name':a,'onchange':"checkrlecourse(this,"+a+")"});
				$('.rlerow:last .rlecourse').html(newcourse);
				$('.rlerow:last .rlecourseadd').attr("onclick","rlecourseadd("+a+")");
				$('.rlerow:last .removerlecourse').attr("onclick","removerlecourse('s"+a+"')");
				highlightrow();

		$('#overlay,#modal').hide();
	}else{
 		var l=$('#addcourseload');
		l.show();
	var cname=$('#newcourse:visible').val();
	var cacronym=$('#newacronym').val();
	var dacronym=$('#newdacronym:visible').val();
	var dname=$('.listdept:visible').val();
	var course=$('#courses');
	var colspan=course.attr('colspan');
	
	$.ajax({
		type:'post',
		url:'savenewcourse.php',
		data: {'cname':cname,'cacronym':cacronym,'dacronym':dacronym,'dname':dname},
		success: function (a){
			loadaddsched();
				$('#overlay,#modal').hide(); 

		},
		error: function(){
			
			loaderror();
		}
	}).alaways(function(){
		l.hide();
	})

	
	


}

}

function highlightrow(){
$('input').focus(function(){
	var a=$(this).attr('name');
$('.row').css('background','white');
$('#row'+a).css('background','#cce9f1');
//rletable
$('.rlerow').css('background','white');
$("#rlerow"+a).css('background','#cce9f1');

//others fees
$('.otherrow').css('background','white');
$("#otherrow"+a).css('background','#cce9f1');

//gradution fees
$('.gradrow').css('background','white');
$("#gradrow"+a).css('background','#cce9f1');

//transferee fees
$('.transrow').css('background','white');
$("#transrow"+a).css('background','#cce9f1');

});

$('.miscdesc,.schoolfeedesc').click(function(){
	var a=$(this).attr('name');
$('#row'+a+" .dupentry2").fadeOut();
 });

}

function savemisc(a) {
	var amount="";
	var sem=$('#schedheader select:eq(0)').val();
	var sy=$('#schedheader select:eq(1)').val();
	var year=$('#schedyearcon input:eq(0)').val();
	var check=0;
	var b=$(a);
	$('.row input:visible').each(function(){
		if($(this).val()==""){
			$(this).css("border","1px solid red").focus();
			check=1;
		}else{
			
			if($(this).attr('duplicate')!=1){
				$(this).css("border","1px solid #a3a3a3");
			}
		}
	})
	var d=$('[duplicate=1]').length;
	if(d>0){
		$('.dupentry2').hide();
		$('[duplicate=1]').focus();
		$('[duplicate=1]:first').next('.dupentry2').show();
		check=1;
		alert("ERROR:You have duplicate entry.");

	}

	///other fees
	var rledata;
	$('.rlerow:visible').each(function() {
		var rid=$(this).attr('name');
		var rlecourse=$('#rlerow'+rid+" input:eq(0)").val();
		var rleamount=$('#rlerow'+rid+" input:eq(1)").val();
		var rleyear=$('#rlerow'+rid+" select").val();
		rledata+="[endline]"+rlecourse+"<->"+rleamount+"<->"+rleyear;
	});

	var otherdata;
	$('.otherrow:visible').each(function() {
		var a=$(this).attr('name');
		var otherdesc=$('#otherrow'+a+" input:eq(0)").val();
		var otheramount=$('#otherrow'+a+" input:eq(1)").val();
		var othercat=$('#otherrow'+a+" select").val();
		otherdata+="[endline]"+otherdesc+"<->"+otheramount+"<->"+othercat;
		
	});

var graddata;
	$('.gradrow:visible').each(function(){
		var a=$(this).attr('name');
		var graddesc=$('#gradrow'+a+" input:eq(0)").val();
		var gradamount=$('#gradrow'+a+" input:eq(1)").val();
		var gradcat=$('#gradrow'+a+" select").val();
			graddata+="[endline]"+graddesc+"<->"+gradamount+"<->"+gradcat;
	})
	var transdata;
	$('.transrow:visible').each(function() {
		var a=$(this).attr('name');
		var transdesc=$('#transrow'+a+" input:eq(0)").val();
		var transamount=$('#transrow'+a+" input:eq(1)").val();
		var transcat=$('#transrow'+a+" select").val();
		transdata+="[endline]"+transdesc+"<->"+transamount+"<->"+transcat;
		
	});

	var othermiscdata;
	$('.othermiscrow:visible').each(function() {
		var a=$(this).attr('name');
		var othermisc=$('#othermiscrow'+a+" input:eq(0)").val();
		var othermiscamount=$('#othermiscrow'+a+" input:eq(1)").val();
		var othermisccat=$('#othermiscrow'+a+" select").val();
		othermiscdata+="[endline]"+othermisc+"<->"+othermiscamount+"<->"+othermisccat;
		
	});

	var checkempty=0;
	$('.rleamount:visible,.gradamount:visible,.graddesc:visible,.transdesc:visible,.otherdesc:visible,.otheramount:visible,.transamount:visible,.miscotheramount:visible').each(function(){
		if($(this).val()==""){
			$(this).css("border","1px solid red");
			check=1;
			checkempty=1;
			
		}else{
			$(this).css("border","1px solid #a3a3a3");
		}
	});

	if(checkempty==1){
		alert("ERROR: Empty field");
	}

		var checklen=$('select[checkduplicate=yes]:visible').length;
	if(checklen>0){
		check=1;
		$('.dupentry').fadeOut();
		$('select[checkduplicate]').css('border','1px solid red');
		$('select[checkduplicate=yes]:visible').each(function(){
			var rid=$(this).attr('name');
			$('.rlerow'+rid+ ":visible select[checkduplicate=yes]:first").next('.dupentry').show().focus();
		});
			alert("ERROR: You have duplicate entry 2");
	}



	//end other fees


	if(check==0){
			if(year==""){
				alert("Please select year level.");
			}else{
				var concon=confirm("Save Schedule of Fees SEM: "+sem+" SY: "+sy+" Y-LEVEL: "+year+" ?");
				
				if(concon==true){


			$('#schoolfeetable .row:visible').each(function() {
				var rid=$(this).attr('name');
				var a=$('#row'+rid+ " input:first");
				var cat=$('#row'+rid+ " select").val();
				amount+="[endline>]";
				amount+="[&&]"+a.attr('group')+"<->"+a.val()+"<->"+cat;
				$('#row'+rid+ " input:gt(0):visible").each(function() {
					amount+="[&&]"+$(this).attr('description')+"<->"+$(this).attr('coursegroup')+"<->"+$(this).val();
				
				});
					
			});
			var e=$('#savemiscloader');
			e.show();
			b.attr('disabled',true);
			var c=$('#savemiscloader');
			c.show();
			$.ajax({
				type:'post',
				url:'savemisc.php',
				data: {'data':amount,'sy':sy,'sem':sem,'year':year,'rledata':rledata,'otherdata':otherdata,'graddata':graddata,'transdata':transdata,'othermiscdata':othermiscdata},
				success:function(data){
 					$('#secretjake').html(data);

					b.attr('disabled',false);
		 		},
				error:function(){
					b.attr('disabled',false);
					alert("error");
					e.hide();
					c.hide();
				}
			})
			}	
		}
	}
}

function inheritvalue () {

	
	$('.row').each(function() {
		var a=$(this).attr('name');
		$('#row'+a+" input:eq(1)").keyup(function(event) {
			$('#row'+a+' input:gt(1)').val($(this).val());
		});
	});
}

function cancelcourse(){
	$('#addcourseform').hide();
	$('#addcoursetable').show();
}
function checkdepartment(a) {
	var b=$('#listdeptrow');
	var c=$('.adddeptrow');
	if(a=="add"){
		b.toggle();
		c.toggle();
		$('.listdept option:first:select');
	} 
}

function addschoolfee(a) {
	
	var rid=randomid();
	
		var row=$('#schoolfeetable .'+a+':last');
		var rowc=row.clone();
		row.after(rowc);
		$('#schoolfeetable .'+a+':last').attr({'id':'row'+rid,'name':rid,'addedrow':'yes'}).css('background','').show();
		$('#schoolfeetable .'+a+':last button').attr('onclick','removeadd("'+rid+'")');
		$('#schoolfeetable .'+a+':last input:first').attr('name',rid).removeAttr('remove');
		$('#schoolfeetable .'+a+':last input:eq(1)').attr('onkeyup','inheritvalue(this.value,"'+rid+'")');
		$('#schoolfeetable .'+a+':last input:gt(0)').numeric();
		$('#schoolfeetable .'+a+':last button').css("visibility","").show();
 		$('#schoolfeetable .'+a+':last input').each(function() {
			$(this).val("").attr({'description':'a','name':rid}).removeAttr('readonly');
		});
		highlightrow();
	
}
function saveschedupdate() {
	var amount="";
	var sy=$('#schedheader select:eq(1)').val();
	var sem=$('#schedheader select:eq(0)').val();
 	var year=$('#schedheader #schedyearcon input:eq(0)').val();
 	var oldsem=$('#schedheader span:eq(0)').attr("name");
	var oldsy=$('#schedheader span:eq(1)').text();
 	var oldyear=$('#schedheader span:eq(2)').text();
 		
  	
 	$('#schoolfeetable .row ').each(function() {
		var rid=$(this).attr('name');
		var a=$('#row'+rid+ " input:first");
		var cat=$('#row'+rid+ " select").val();
		amount+="[endline>]";
		amount+="[&&]"+a.attr('group')+"<->"+a.val()+"<->"+cat+"<->"+a.attr("remove");
		$('#row'+rid+ " input:gt(0)").each(function() {
			amount+="[&&]"+$(this).attr('description')+"<->"+$(this).attr('coursegroup')+"<->"+$(this).val()+"<->"+$(this).attr("remove");
		});
	});
	var check=0;
	$('.row input:visible').each(function(){
		if($(this).val()==""){
			$(this).css("border","1px solid red").focus();
			check=1;
		}else{
			
			if($(this).attr('s')!=1){
				$(this).css("border","1px solid #a3a3a3");
			}
		}
	})
	var checklen=$('select[checkduplicate=yes]').length;
	if(checklen>0){
		check=1;
		$('select[checkduplicate]').css('border','1px solid red');
		$('select[checkduplicate=yes]').each(function(){
			var rid=$(this).attr('name');
			$('.rlerow'+rid+ " select[checkduplicate=yes]:first").next('.dupentry').show()
		
		});
	}

	///other fees

	var rledata;
	$('.rlerow').each(function() {
		var rid=$(this).attr('name');
		var rlecourse=$('#rlerow'+rid+" input:eq(0)").val();
		var rleamount=$('#rlerow'+rid+" input:eq(1)").val();
		var rleyear=$('#rlerow'+rid+" select").val();
		var rleschedid=$('#rlerow'+rid+" select").attr('schedid');
		var remove=$('#rlerow'+rid).attr('remove');
		var addedrow=$('#rlerow'+rid).attr('addedrow');
		rledata+="[endline]"+rlecourse+"<->"+rleamount+"<->"+rleyear+"<->"+rleschedid+"<->"+remove+"<->"+addedrow;
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

	var othermiscdata;
	$('.othermiscrow').each(function() {
		var a=$(this).attr('name');
		var transdesc=$('#othermiscrow'+a+" input:eq(0)").val();
		var transschedid=$('#othermiscrow'+a+" input:eq(0)").attr('schedid');
		var transamount=$('#othermiscrow'+a+" input:eq(1)").val();
		var transcat=$('#othermiscrow'+a+" select").val();
		var remove=$('#othermiscrow'+a).attr('remove');
		othermiscdata+="[endline]"+transdesc+"<->"+transamount+"<->"+transcat+"<->"+transschedid+"<->"+remove;
		
	});

	$('.rleamount:visible,.gradamount:visible,.graddesc:visible,.transdesc:visible,.otherdesc:visible,.otheramount:visible,.transamount:visible,.othermiscamount:visible').each(function(){
		if($(this).val()==""){
			$(this).css("border","1px solid red");
			check=1;
		}else{
			$(this).css("border","1px solid #a3a3a3");
		}
	});

	var checklen=$('select[checkduplicate=yes]:visible').length;
	if(checklen>0){
		check=1;
		$('.dupentry').hide();
		$('select[checkduplicate]').css('border','1px solid red');
		$('select[checkduplicate=yes]').each(function(){
			var rid=$(this).attr('name');
			$('.rlerow'+rid+":visible select[checkduplicate=yes]:first").next('.dupentry').show().attr("jakecornelia","jakecornela");
		
		});
	}
	//end other fees
	if(check==0){
			if(year==""){
				alert("Please select year level.");
			}else{
					var concheck=false;
					if(oldsy!=sy|| oldsem!=sem || oldyear!=year){
						var con=confirm("Update Schedule of Fees SEM: "+oldsem+" SY: "+oldsy+" Y-LEVEL: "+oldyear+" to SEM: "+sem+" SY: "+sy+" Y-LEVEL: "+year+" ?");
						if(con==true){
							concheck=true;
 						}
					}else{
							concheck=true;
					}
					if(concheck==true){
 					
			 	$.ajax({
					type:'post',
					url:'saveupdatemisc.php',
					data: {'data':amount,'oldsy':oldsy,'oldsem':oldsem,'oldyear':oldyear,'sy':sy,'sem':sem,'year':year,'rledata':rledata,'otherdata':otherdata,'graddata':graddata,'transdata':transdata,'othermiscdata':othermiscdata},
					success:function(data){
 						$('#secretschedcon').html(data);
					},
					error:function(){
						connection();
					}
				})	;
				}
			}
}

}
	function randomid(){
		var randid = "";
		var possible = "123456789";
		for( var i=0; i < 10; i++ )
		{
		randid += possible.charAt(Math.floor(Math.random() * possible.length));
		}
		return randid;
	}

function loaderror(){
	return alert("Unable to connect to server. Please try again.")
}

$(document).ready(function() {
	highlightrow();
	inheritvalue();

});


//other fees saving and update

function checkrlecourse(a,b){
	var c=$(a).val();
	var e=$("select").index(a);
	var group=$(a).attr('duplicate');
	var name=$(a).attr('name');
	var check;
	var rand=randomid();
	var len=$('[duplicate="'+group+'"]:visible').length;
		$('[rlecourse="'+b+'"]:visible select').each(function(){
			if(len==2){
				$('[duplicate="'+group+'"]').removeAttr('duplicate').removeAttr('checkduplicate').css('border','1px solid #a3a3a3');
				$(".rlerow"+name+" .dupentry").fadeOut();		
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
		$(".dupentry").fadeOut();
}



function removerlecourse(a){
	var b=$('#rlerow'+a);
	var c=b.attr("addedrow");
		var d= $('#rlerow'+a+" select").attr('duplicate');
		var len=$("select[duplicate="+d+"]:visible").length;
		if(len==2){
			$("select[duplicate="+d+"]").css("border","1px solid #a3a3a3").removeAttr('duplicate').removeAttr('checkduplicate');
			$('.dupentry').fadeOut();
		}
	
	if(c=='yes'){
		b.remove();
	}else{
		b.hide().attr('remove','yes');
	}



}


function removeotherdesc(a){
	$('#otherrow'+a).hide().attr('remove','yes');
}

function removegraddesc(a){
	$('#gradrow'+a).hide().attr('remove','yes');
}
function removetransdesc(a){
	$('#transrow'+a).hide().attr('remove','yes');
}

function removeothermiscdesc(a){
	$('#othermiscrow'+a).hide().attr('remove','yes');
}
function addmisc () {
	var a = $('.misccategory').html();
	var rid=randomid();
	$('#misctable tr:last').before("<tr id='row"+rid+"' class='rows'><td><input type='text' class='miscdesc'><button class='removeadd'  onclick='removeadd("+rid+")'></td><td><input type='text' class='miscamount'></td><td><select class='misccategory'>"+a+"</select></td></tr>");
}
 
///////////////////////////////////add fields

function rlecourseadd(a){
	var b=$('.rlerow'+a+":last").clone();
	var rand=randomid();
 	$('.rlerow'+a+":last").after(b).show();
	$(".rlerow"+a +":last button:eq(0)").attr('onclick',"rlecourseadd("+a+")");
	$(".rlerow"+a +":last button:eq(1)").attr('onclick','removerlecourse("s'+rand+'")');
	$(".rlerow"+a +":last").css("background",'#fcf4df').attr({'id':'rlerows'+rand,'addedrow':'yes','name':"s"+rand}).removeAttr('remove');
	$(".rlerow"+a +" select").attr('duplicate',rand);
	$(".rlerow"+a +" select").attr('checkduplicate','yes');
	$(".rlerow"+a +":last input").attr('name',"s"+rand).numeric();
	$(".rlerow"+a +":last input:last").val("");
	highlightrow();
}


function rlecourseadd2(a,b){
	var c=$('.rlegroup'+b+":last").clone();
	var rand=randomid();
	$('.rlegroup'+b+":last").after(c).show();
	$(".rlegroup"+b +":last button:eq(1)").attr('onclick','removerlecourse("'+rand+'")');
	$(".rlegroup"+b +":last").css("background",'#fcf4df').attr({'id':'rlerow'+rand,'addedrow':'yes','name':rand}).removeAttr('remove');
	$(".rlegroup"+b +" select").attr('duplicate',rand);
	$(".rlegroup"+b +" select").attr('checkduplicate','yes');
	$(".rlegroup"+b +":last input").attr('name',rand);
	$(".rlegroup"+b +":last input:last").val("");
	highlightrow();
}

//add gradution fees
function addgrad() {
	var a = $('#gradtable .gradrow:last').clone();
	var rand=randomid();
	$('#gradtable .gradrow:last').after(a);
	$('#gradtable .gradrow:last').attr({'id':'gradrow'+rand,'name':rand,'addedrow':'yes'}).removeAttr('remove').css("background",'white').show();
	$('#gradtable .gradrow:last button').attr('onclick',"removegraddesc("+rand+")");
	$('#gradtable .gradrow:last input').val("").removeAttr('schedid').attr('name',rand);
	$('#gradtable .gradrow:last input:last').numeric();
	highlightrow();
}

//adding trasferees payment description
function addtrans() {
	var a = $('#transtable .transrow:last').clone();
	var rand=randomid();
	$('#transtable .transrow:last').after(a);
	$('#transtable .transrow:last').attr({'id':'transrow'+rand,'name':rand,'addedrow':'yes'}).removeAttr('remove').css("background","white").show();
	$('#transtable .transrow:last button').attr('onclick','removetransdesc("'+rand+'")');
	$('#transtable .transrow:last input').val("").removeAttr('schedid').attr('name',rand);
	$('#transtable .transrow:last input:last').numeric();
	highlightrow();
}

function addothermisc() {
	var a = $('#othermisctable .othermiscrow:last').clone();
	var rand=randomid();
	$('#othermisctable .othermiscrow:last').after(a);
	$('#othermisctable .othermiscrow:last').attr({'id':'othermiscrow'+rand,'name':rand,'addedrow':'yes'}).removeAttr('remove').css("background","white").show();
	$('#othermisctable .othermiscrow:last button').attr('onclick','removeothermiscdesc("'+rand+'")');
	$('#othermisctable .othermiscrow:last input').val("").removeAttr('schedid').attr('name',rand);
	$('#othermisctable .othermiscrow:last input:last').numeric();
	highlightrow();
}


function addother() {
	var a=$('#othertable .otherrow:last').clone();
	var rand=randomid();
	$('#othertable .otherrow:last').after(a);
	$('#othertable .otherrow:last').attr({'id':'otherrow'+rand,'name':rand,'addedrow':'yes'}).removeAttr('remove').show().css("background",'white');
	$('#othertable .otherrow:last button').attr('onclick','removeotherdesc("'+rand+'")');
	$('#othertable .otherrow:last input').val("").removeAttr('schedid').attr('name',rand).removeAttr('readonly')
	$('#othertable .otherrow:last input:last').numeric();
	highlightrow();
}

//end of other fees saving and updtaae