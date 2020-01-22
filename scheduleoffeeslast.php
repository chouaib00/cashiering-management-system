<style>
.list:nth-child(even){background:#e5e6e6;}
.list:nth-child(odd){background:white;}
.submisc {padding:0 0 0 15px}
#fees input {margin:2px}
.feeamount {text-align:center}
.feeamount input {width:40px}
.miscdesc  {width:100px}
.miscamount {width:40px}
.coursedes {text-align:center}
.coursedes input{width:100px}
#addcourse {float:right}

 </style>
<script src="js/jquery-1.9.1.js"></script>
<script>	
	function randomid(){
		var randid = "";
		var possible = "123456789";
		for( var i=0; i < 10; i++ )
		{
		randid += possible.charAt(Math.floor(Math.random() * possible.length));
		}
		return randid;
	}
	
	function addmisc(a){
		var jake = $('#fees tr:last').clone();
		$('#fees tr:last').after(jake);
		$('#fees tr:last input').val("");
		$('#fees tr:last td:first').html("<input type='text' class='miscdesc'>");
		
	}

	function newcourse(a){
		var courseindex =$(a).index('tr td');
alert(courseindex);
	}
	
	function addcourse(){
		$('#courses').append("<td class='coursedes'><input type='text' onkeyup='newcourse(this)'></td>");
		//count courses
		var c = $('#courses td').length;
		$('#tuiamount').append("<td class='feeamount'><input type='text' name='1-"+c+1+"'></td>");
		
		$('.misc').each(function(){
		var id = $(this).attr("id");
			$(this).append("<td class='feeamount'><input type='text' id='amount"+"'></td>");
		});
	}
	
	//get the value
	
	function feeamount(a){
			var val=$(a).val();
			var name=$(a).attr("name");
			$(a).attr("name",name+val);
			alert($(a).attr("name"));
			
		}
</script>
<table id="fees" border style="width:700px;border-collapse:collapse">
	<tr>
		<td rowspan="2">School Fee</td>
		<td colspan="100" style="text-align:center">Courses<button onclick="addcourse()" id="addcourse">Add course</button></td>
	</tr>
	
	<tr id="courses">
		<td>BSINT</td>
		<td>COMSCI</td>
		<td>ASCOMSCI</td>
		<td>MIDWIERY</td>
		
	</tr>
	
	<tr id="tuiamount" class="fees">
		<td style="font-weight:bold">Tuition</td>
		<td  class="feeamount"><input type="text" name="1-1-" onkeyup="feeamount(this)"></td>
		<td  class="feeamount"><input type="text" name="1-2-" onkeyup="feeamount(this)"></td>
		<td  class="feeamount"><input type="text" name="1-3-" onkeyup="feeamount(this)"></td>
		<td  class="feeamount"><input type="text" name="1-4-" onkeyup="feeamount(this)"></td>
	</tr>

	
	<tr>
		<td style="font-weight:bold">Miscellaneous</td>
		<td colspan="100"></td>
	</tr>
	
	<tr class="misc" id="2" name="Athletic" class="fees">
		<td class="submisc">Athletic</td>
		<td  class="feeamount"><input type="text" name="2-1-" onkeyup="feeamount(this)"></td>
		<td  class="feeamount"><input type="text" name="2-2-" onkeyup="feeamount(this)"></td>
		<td  class="feeamount"><input type="text" name="2-3-" onkeyup="feeamount(this)"></td>
		<td  class="feeamount"><input type="text" name="2-4-" onkeyup="feeamount(this)"></td>
	</tr>
</table>
<button onclick="addmisc(this)">Other Miscellaneous</button>
