
$(function() {
$('#remainingbal span').html($('#baltopay').html());
if($('#remainingbal span').html()==0){
	$('#remainingbal span').html("P 0.00")
	$('#remainingbal').css({'background':'#e5ffe2','border':'1px solid #4dcd3c'});
}

$('#cash').numeric();
$('#paytable input:gt(0)').keyup(function(event) {

	var amount=parseInt($('#cash').val());
	var total=0;
	$('input[checked=checked]').each(function(){
		total+=parseInt($(this).attr('name'));
	});

	if($('input[checked=checked]').length>0){
		$('#change').val(amount-total);
	}
});
});




function savefullpayment(){

var paymentdata;
	$("input[checked='checked'] ").each(function(){
	var amount=$(this).val();
	paymentdata+="[endline]"+amount;
});

alert(paymentdata);
var receipt=$('#receipt').val();
var stud_id=$('#sfname').attr("name");
var scholar_id=$('#scholar_id').val();
	$.ajax({
	type:'post',
	url:'student/savefullpayment.php',
	data:{'scholar_id':scholar_id,'receipt':receipt,'stud_id':stud_id,'paymentdata':paymentdata},
	success:function(data){
		alert("success"+data);
	},error:function(){
		alert("Conneciton error,please try again.");
	}
	});
}

function checkall(){
var total=0;
$("#fullpaymenttable .paymentrow[name]").each(function(){
	var a=$(this).attr("schedid")
	var val=$('#payment'+a+" input").val();
	$('#payment'+a+" td:first").html("<input type='checkbox' checked='checked' value='"+val+"'>");
	total=total+parseInt($(this).attr("name"));
	})
$('#total').val(total);
}
function uncheckall(){
$('input[type=checkbox]').removeAttr("checked");
$('#total').val(0);
}
function checkpayment(a,amount,misc){
		alert()
var b= $('#payment'+a+" [type=checkbox]");
var c= $('#payment'+a+" [type=checkbox]").attr("checked");
var name= $('#payment'+a+" [type=checkbox]").attr("name");
	var total=$('#total');
	var cash=$('#cash');
var ctotal=parseInt(total.val());

	if(c=="checked"){
	b.attr("checked",false);
	total.val(ctotal-amount);
	$('#change').val(parseInt(cash.val())-parseInt(total.val()));
}else{
	total.val(ctotal+amount);
	$('#change').val(parseInt(cash.val())-parseInt(total.val()));
	$("#payment"+a+" td:first").html("<input type='checkbox' checked='checked' value='"+a+"<->"+amount+"' name='"+name+"'>");
}

if(misc=='misc'){
	// $('#misc').removeAttr('name').val("");
	$('.submisc span').html("<input type='checkbox' checked='checked'>");
}else{
	}
}
function updatestudentrecord(a){
	$('.studoption').hide();
	$('.savestudentstatus,.studoptioncancel').show();
 	$('#studinfotable input:lt(2),#studinfotable select:eq(0)').show();
 	$('#studinfotable span:lt(3)').hide();
 }
 function updatestudentstatus(){
 	$('#studinfotable input:gt(1),#studinfotable select:gt(0),.studoptioncancel,.savestudentstatus').show();
 	$('#studinfotable span:gt(2),.studoptionrecord,.studoptionstatus').hide();
 }

 function savestudentstatus(){
 	var course_id=$('#course_id').val();
 	var year_level=$('#year_level').val();
 	var status=$('#sstatus').val();
 	if($('#sfname:visible').length==1){
 		
 	}else{
 		$.ajax({
 			type:'post',
 			url:'student/savestudentstatus.php',
 			data:{'stud_id':<?=$studrow['stud_id'];?>,'course_id':course_id,'year_level':year_level,'status':status},
 			success:function(data){
 				alert(data);
 			},
 			error:function(){
 				alert("error");
 			}
 		});
 	}
 	}