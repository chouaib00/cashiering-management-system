<?php
session_start();
include '../dbconfig.php';
$sy=$_POST['sy'];
$user_id=$_SESSION['user_id'];
$semester=$_POST['semester'];
$stud_id=$_POST['stud_id'];
$newstud_number=$_POST['stud_number'];
$fname=$_POST['fname'];
$lname=$_POST['lname'];
$scholar_id=$_POST['scholar_id'];
$course_id=$_POST['course_id'];
$year_level=$_POST['year_level'];
$status=$_POST['status'];
$date=date('m/d/Y');
$time=date('h:i a');
 $getinfo=mysql_query("select fname,lname,student_status.year_level,stud_number,student_status.course_id,course.acronym,student_status.status from student,student_status,course where student.stud_id=student_status.stud_id and course.course_id=student_status.course_id  and student.stud_id='$stud_id' and sy='$sy' and semester='$semester' order by student_status.stat_id desc ") or die(mysql_error());
$info=mysql_fetch_array($getinfo);
$lastfname=$info['fname'];
$lastlname=$info['lname'];
$lastcourse=$info['course_id'];
$lastscholar_id=$info['scholar_id'];
$laststatus=$info['status'];
$lastyearlevel=$info['year_level'];
$stud_number=$info['stud_number'];
$acronym=$info['acronym'];


$commadetector="";
$action="";
$changes=0;
if($lastfname!=$fname || $lastlname!=$lname){
	$action.="Updated $lastfname $lastlname\'s name to $fname $lname";
	$commadetector="write";
 	$changes=1;
}

if($newstud_number!=$stud_number){
	$changes=1;
	if($commadetector=="write"){
		$action.=", student number from $stud_number to $newstud_number";
	}else{
		$action.="Updated $lastfname $lastlname\'s student number from $stud_number to $newstud_number";
	}
}
$getcourse=mysql_query("select * from course where course_id='$course_id'");
$courserow=mysql_fetch_array($getcourse);

if($lastcourse!=$course_id){
	$changes=1;
 	$comma="";
	$studname="";
	if($commadetector=="write"){
		$comma=",";
	}else{
		$studname="Updated $fname $lname\'s course from ";
	}

	$newyear="";
	if($lastyearlevel!=$year_level){
		$newyear="-$year_level";
	}else{
		$newyear=$info['year_level'];
	}

	

	//get the new course
	
	$action.="$comma$studname,$acronym-$info[year_level] to $courserow[acronym] $newyear";
	$commadetector="write";
}else{
	if($lastyearlevel!=$year_level){
		$changes=1;
		if($commadetector=="write"){
 		$action.=", changed year level $comma$info[acronym]-$info[year_level] to $comma$info[acronym]-$newyear";

		}else{
 			$action.="Changed $fname $lname\'s year level from $info[acronym]-$info[year_level] to $info[acronym]-$year_level";
		}
		$commadetector="write";
	}

}


	//get scholar ship
	
	$getscholar=mysql_query("select * from scholarship,student_status where student_status.scholar_id=scholarship.scholar_id and student_status.stud_id='$stud_id'");
	$scholarrow=mysql_fetch_array($getscholar);
	$getnewscholar=mysql_query("select * from scholarship where scholar_id='$scholar_id'");
	$scholarnewrow=mysql_fetch_array($getnewscholar);

	//detecting new scholarship
	if($scholar_id!=$scholarrow['scholar_id'] && mysql_num_rows($getscholar)!=0 && $scholar_id!=0){
 		$changes=1;		
		if($commadetector=="write"){
			$action.=", changed scholarship from $scholarrow[description] $scholarrow[amount] to $scholarnewrow[description] $scholarnewrow[amount]";
		}else{
			$action.="Changed $fname $lname\'s scholarship from $scholarrow[description] $scholarrow[amount] to $scholarnewrow[description] $scholarnewrow[amount]";
		}
		 
	}elseif(mysql_num_rows($getscholar)!=0 && $scholar_id==0){
 		$changes=1;	
		if($commadetector=="write"){
			$action.=", removed scholarship $scholarrow[description] $scholarrow[amount]";
		}else{
			$action.="Removed $fname $lname\'s scholarship $scholarrow[description] $scholarrow[amount]";
		}
		 
	}elseif(mysql_num_rows($getscholar)==0 && $scholar_id!=0){
 		$changes=1;	
		if($commadetector=="write"){
			$action.=", added scholarship $scholarnewrow[description] $scholarnewrow[amount]";
		}else{
			$action.="Added $fname $lname\'s scholarship $scholarnewrow[description] $scholarnewrow[amount]";
		}
	}		

$stat=$status;
if($status=='grad'){
$stat="graduating";
}elseif ($status=="trans"){
$stat="graduating";
}
if($status!=$laststatus){
	$changes=1;	
	if($commadetector=="write"){
			$action.=", and status from  $laststatus to $stat.";
		}else{
		$action.="Changed $fname $lname\'s status from  $laststatus to $stat.";
	}
}
mysql_query("update student set fname='$fname',lname='$lname',stud_number='$newstud_number' where stud_id='$stud_id'") or die(mysql_error());
$check=mysql_query("select * from student_status where stud_id='$stud_id' and sy='$sy' and semester='$semester'");
if(mysql_num_rows($check)>0){
echo "string";
 mysql_query("update student_status set scholar_id='$scholar_id',course_id='$course_id',year_level='$year_level',status='$status',status='$status' where sy='$sy' and semester='$semester' and stud_id='$stud_id'") or die(mysql_error());
	 if($changes==1){
  		mysql_query("insert into user_log values ('','$date','$time','$action','$user_id')");
	}
}else{
 	mysql_query("insert into student_status values ('','$stud_id','0','$course_id','II','$sy','$year_level','$status')");
}
?>
