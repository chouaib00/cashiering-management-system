<?php
session_start();
include "../dbconfig.php";

?>
<style type="text/css">
#studlist td,#studlist th{padding:2px;border:1px solid gray;}
 #numberlist a{padding:0 4px 0 4px;text-decoration:underline;color:blue;cursor:pointer;}
#active {background: gray}
</style>
<div style="text-align:center;margin-top:20px">
	Search Semester: <select id="searchsem">
		<option>I</option>
		<option>II</option>
	</select>
	SY: 
	<select id="searchsy">
		<?php
			$getsy=mysql_query("select sy from student_status group by sy order by sy asc");
			while ($row=mysql_fetch_array($getsy)) {
				echo "<option>$row[sy]</option>";
			}
		?>
	</select>
	<button onclick="searchsysemester()">Search</button>
</div>
<table id="studlist" style="width:90%;margin:0 auto;margin-top:10px;margin-bottom:20px">
<tr>
	<th>Name</th>
	<th>Course</th>
	<th>Year</th>
	<th>Payment History</th>
</tr>
 

<?php
$sy=$_SESSION['sy'];
$semester=$_SESSION['semester'];
$lname='a';
$getstu=mysql_query("select * from student,student_status,course where course.course_id=student_status.course_id and  student_status.stud_id=student.stud_id and sy='$sy' and semester='$semester' and lname like '$lname%'group by student.stud_id order by fname") or die(mysql_error());
while ($row=mysql_fetch_array($getstu)) {
	?>
	<tr>
		<td style="text-transform:capitalize"><b><?=$row['lname']."</b>, ".$row['fname'];?></td>
		<td><?=$row['acronym'];?></td>
		<td align='center'><?=$row['year_level'];?></td>
		<td align="center"><a href="student/downloadhist.php?stud_id=<?="$row[stud_id]&semester=$semester&sy=$sy";?>" target="jenelyn"><?=$sy." ".$semester;?></a></td>
	</tr>
	<?php
}
?>

</table>
<div id="numberlist" style="width:90%;margin:0 auto">
	Last Name: 
	<a  onclick="searchlastname('a',this)" id="active">A</a>
	<a  onclick="searchlastname('B',this)">B</a>
	<a  onclick="searchlastname('C',this)">C</a>
	<a  onclick="searchlastname('D',this)">D</a>
	<a  onclick="searchlastname('E',this)">E</a>
	<a  onclick="searchlastname('F',this)">F</a>
	<a  onclick="searchlastname('G',this)">G</a>
	<a  onclick="searchlastname('H',this)">H</a>
	<a  onclick="searchlastname('I',this)">I</a>
	<a  onclick="searchlastname('J',this)">J</a>
	<a  onclick="searchlastname('K',this)">K</a>
	<a  onclick="searchlastname('L',this)">L</a>
	<a  onclick="searchlastname('M',this)">M</a>
	<a  onclick="searchlastname('N',this)">N</a>
	<a  onclick="searchlastname('O',this)">O</a>
	<a  onclick="searchlastname('P',this)">P</a>
	<a  onclick="searchlastname('Q',this)">Q</a>
	<a  onclick="searchlastname('R',this)">R</a>
	<a  onclick="searchlastname('S',this)">S</a>
	<a  onclick="searchlastname('T',this)">T</a>
	<a  onclick="searchlastname('U',this)">U</a>
	<a  onclick="searchlastname('V',this)">V</a>
	<a  onclick="searchlastname('W',this)">W</a>
	<a  onclick="searchlastname('X',this)">X</a>
	<a  onclick="searchlastname('Y',this)">Y</a>
	<a  onclick="searchlastname('Z',this)">Z</a>
	
</div>

<script>

function searchsysemester(){
	var sy=$('#searchsy').val();
	var semester=$('#searchsem').val();
 	$.ajax({
		type:'post',
		url:'student/searchlastname.php',
		data: {'lname':'a','sy':sy,'semester':semester},
		success:function (data) {
			$('#studlist tr:gt(0)').remove();
			$('#studlist tr:first').after(data);
		},
		error:function(){
			connection();
		}
	});
}
function searchlastname (a,b) {
	$('#numberlist a').removeAttr('id').css("color","blue");
	$(b).attr("id","active").css({"color":"white","text-decoration":"none"});
	var sy=$('#searchsy').val();
	var semester=$('#searchsem').val();
 	$.ajax({
		type:'post',
		url:'student/searchlastname.php',
		data: {'lname':a,'sy':sy,'semester':semester},
		success:function (data) {
			$('#studlist tr:gt(0)').remove();
			$('#studlist tr:first').after(data);
		},
		error:function(){
			connection();
		}
	})
}
</script>