<?php
session_start();
include('dbconfig.php');
$id=$_POST['stud_id'];
$student=mysql_query("select * from course,student,student_status where  student_status.course_id=course.course_id and student.stud_id='$id'");
$stud=mysql_fetch_array($student);
?>
<script>
$(function(){
$('.amount,#cash,#newamount2').numeric();
});

//removing the added description
	function closedes(id){
		$('#paydes'+id).remove();
	}
	//
function otherpayment(){
		var id = randomid();
		
		$('#otherpayment').before("<tr id='paydes"+id+"' style='position:relative'><td><input type='text' onkeyup='description("+id+",this.value)' name='"+id+"' class='ndes' style='width:100px' placeholder='Descritption'></td><td class='otheramountcon' style='position:relative'><input type='text' placeholder='Amount' name='amount"+id+"' class='amount' onkeyup='total()'><div onclick='closedes("+id+")' class='closedes' title='Remove'></div></td></tr>");
		$('[type=number]').numeric();
		$('.amount').numeric();
	}
	
		function total(){	
		var el = $('#cash');
		if(el.val()!=""){
			var total = 0;
			$('.amount:visible').each(function(){
				total=total+parseInt($(this).val());
			});
			
			var cash = el.val();
			var change = $('#change');
			change.val(cash-total);
			var c = change.val();
			if(c<0){
				change.addClass("errorf");
			}else{
				change.removeClass("errorf");
			}
		}
	}
	
		//savepayment
	
	function savepayment(){
		var values="";
		$('.amount:visible').each(function(){
			var name = $(this).attr("id");
			var amount = $(this).val();
			values+=name+","+amount+",";
		});
		var scholar;
		var a=0;
			if($('#scholar2:visible').length==1){
				var scholar =$('#scholar2').val();
			}else{
				scholar=$('#newscholar2').val()+"/////"+$('#newamount2').val();
				a=1;
			}
		var payor = $('#payor').attr("name");
		var or = $('#or').val();
		var check;
		$('#paymenttable .amount,#paymenttable .ndes').each(function(){
			if($(this).val()==""){
			check = 1;
			}
		});
				
		if(check==1){
			$('#paymenttable .amount,#paymenttable .ndes').each(function(){
				if($(this).val()==""){
					$(this).emptyField();
				}
			});
		}else{
			$.ajax({
				type: 'POST',
				url: 'savepayment.php',
				data: {'or':or,'payor':payor,'values':values,'scholar':scholar},
				success: function(data){
					$('#or').val(parseInt(or)+1);
					if(a==1){
						function loadsc(){
							$.ajax({
							url: 'newscholar.php',
							type: 'get',
							success: function (data){
								$('#scholar2').html(data);
							},error: function (){
							loadsc();
							}
							});
						}
						loadsc();
						
					}
					$('#otherschcon:visible').hide();
					$('#scholar2').show();

				},
				error:function(){
					alert("error");
				}
			});
		}	
	}
	
</script>
	<table id="payorname" style="width:inherit">
			<tr>
				<td id="payor" name="<?=$stud['stud_id'];?>"><?=$stud['fname']." ".$stud['lname'];?>
				</td>
				<td style="text-align:right"><?=$stud['description'];?>
				</td>
			</tr>
		</table>
	<div id="paymentwrap">
		<div style="border:1px solid #d5d5d5;border-radius:5px;padding:8px 30px 8px 30px;position:absolute;float:left;background:white;top:-20px;left:20px">PAYMENT AREA</div>
		<table id="paymenttable">
		<?php
		$or=mysql_query("select receipt from payment where user_id='$_SESSION[userid]' order by receipt desc limit 1");
		$newor=mysql_fetch_array($or);
		$checkscholar=mysql_query("select * from scholar where stud_id='$id' and sem='II'");
		$cScholar=mysql_fetch_array($checkscholar);
		?>
			<tr>	
				<td>OR:</td><td><input value="<?=$newor['receipt']+1;?>" type="text" id="or"></td>
			</tr>
			
			<tr>	
				<td>Tuition:</td><td><input type="text" id="tuition" class="amount" placeholder="Amount" onkeyup="total()"></td>
			</tr>
			<tr>	
				<td>Miscellaneous:</td><td><input type="text" id="misc" class="amount" onkeyup="total()" placeholder="Amount"></td>
			</tr>
			<?php
				if($cScholar==0){
				?>
			<tr id="scholarcon">	
				<td>Scholarship:</td>
				<td>
				<div id="otherschcon2">
					<input type="text" id="newscholar2"  placeholder="Other Scholarship"><br>
					<input type="text" id="newamount2" placeholder="Amount">
					<div id="closescholar" style="display:block" title="Cancel" onclick="scholar('Other')"></div>
				</div>
								
				<select id="scholar2" onchange="scholar(this.value)">
					<option value="0">--none--</option>
					<?php
					$scholar=mysql_query("select * from scholarship order by description asc");

					while($row=mysql_fetch_array($scholar)){
					?>
						<option value="<?=$row['scholar_id'];?>"><?=$row['description'];?></option>
					<? } ?>
					<option value="Other">Add new scholarship</option>
				</select>
				</td>
			</tr>
			<?php
			}
			?>
			
			<tr id="otherpayment">	
				<td  onclick="otherpayment()" style="cursor:pointer;color:blue;text-decoration:underline">Add Description</td> <td><button id="savepayment" onclick="savepayment()"></button></td>
			</tr>
			
			<tr>

				<td>Cash:</td> <td><input type="text" id="cash" onkeyup="total()"></td>
			</tr>
			
			<tr>	
				<td>Change</td> <td><input type="text" id="change" readonly="readonly"></td>
			</tr>
		
			
			
		</table>
	</div>
		
