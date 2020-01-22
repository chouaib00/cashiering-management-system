<style type="text/css">
	#addscholartable td{padding:5px;}
	#addscholartable #savescholar{height:30px;width:120px;margin:0 auto;display:block;}
</style>
<div id="addcourseheader">Add Scholarship &nbsp;<img id="addcourseload" src="img/loading.gif" style="display:none;position:relative;top:3px"><button class="closemodal" onclick="closemodal()"></button></div>
<div style="padding:10px">
<form onsubmit="return savescholars()">
<table id="addscholartable" style="width:280px;margin:0 auto">
	
	<tr>
		<td>Name:</td><td><input type="text" required="required" id="scholarname"></td>
	</tr>
	<tr>
		<td>Amount:</td><td><input type="number" required="required" id="scholaramount"></td>
	</tr>
	<tr>
		<td></td>
		<td>
			<button id="savescholar">SAVE</button>
		</td>
	</tr>
	
</table>
</form>
</div>

<script>
function savescholars() {
	var name = $('#scholarname').val();
	var amount = $('#scholaramount').val();
	var loader=$('#addcourseload');
	loader.show();
	$.ajax({
		type:'post',
		url:'scholarship/savescholar.php',
		data:{'name':name,'amount':amount},
		success:function(data){
			loader.hide();
			$('#overlay,#modal').fadeOut();
			$('#success div').html("Successfully added.").show();
			setTimeout(function(){$('#success div').fadeOut()},5000);
		},
		error:function(){
			loader.hide();
			connection();
		}
	});
	return false;
};
</script>