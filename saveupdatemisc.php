<?php
include 'dbconfig.php';
$data=$_POST['data'];

$sy=$_POST['sy'];
$sem=$_POST['sem'];
$year=$_POST['year'];
$oldsy=$_POST['oldsy'];
$oldsem=$_POST['oldsem'];
$oldyear=$_POST['oldyear'];
$allarr=explode("[endline>]", $data);
$datalen=count($allarr);
 $start=1;
$changecheck="nochnage";
 if($oldsy!=$sy || $oldyear!=$year || $oldsem!=$sem){
$changecheck="changed";
 }
 
$newyear="";
 
$exyear=explode("&",$year);
$yearlen=count($exyear)-1;
if(count($exyear)==1){
   if($year=="I"){
     $year="$year&";
  }else{
    $year="&$year";
  }
}
while (0<=$yearlen) {
  if($newyear==""){
    $newyear.=" year_level like '%&$exyear[$yearlen]&%' or year_level like '$exyear[$yearlen]&%' or year_level like '%&$exyear[$yearlen]'";
  }else{
        $newyear.=" or year_level like '%&$exyear[$yearlen]&%' or year_level like '$exyear[$yearlen]&%' or year_level like '%&$exyear[$yearlen]'";

      }
  $yearlen--;
}


$validatechange="valid";
if($changecheck=="changed"){											                   
 	$checkifexisted=mysql_query("select * from schedule_of_fees,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and payment_group='misc' and sy='$sy' and semester='$sem' and ($newyear) and year_level!='$oldyear'");
	$count=mysql_num_rows($checkifexisted);
	if($count==0){
	$validatechange="valid";
	}else{
		$validatechange="invalid" ;
	}
}else{
	$validatechange="valid";
}

if($validatechange=="valid"){
 
while ($start<$datalen){
	$data2=$allarr[$start];
	$data2arr=explode("[&&]", $data2);
	$data2len=count($data2arr);
	$start2=2;

	$schoolfee=$data2arr[1];
	$schoolfeearr=explode("<->", $schoolfee);
	$desc=$schoolfeearr[1];
	$desc_cat=$schoolfeearr[2];
	$group=$schoolfeearr[0];
	$removeornot=$schoolfeearr[3];
	echo $removeornot;

	$checkdesc=mysql_query("select * from paymentlist where payment_desc='$desc' order by payment_id desc") or die (mysql_error());
		
	$checkdesc_count=mysql_num_rows($checkdesc);
	$desc_id="";
	if($checkdesc_count>0){
		$getdesc_id=mysql_fetch_array($checkdesc);
		$desc_id=$getdesc_id['payment_id'];
	}else{
		$insert_desc=mysql_query("insert into paymentlist values ('','$desc','$group')") or die(mysql_error());
		$get_desc=mysql_query("select * from paymentlist where payment_desc='$desc' order by payment_id desc") or die (mysql_error());
		$getdesc_id=mysql_fetch_array($get_desc);
		$desc_id=$getdesc_id['payment_id'];
	}
		

		
		while ($start2<$data2len) {
			$amount=explode("<->",$data2arr[$start2]);
			$sched_id=$amount[0];
			$course=$amount[1];
			$desc_amount=$amount[2];
			$removeornot2=$amount[3];
				//check if fee is deleted
				//if yes then delete 
				if($removeornot=="yes"){
							mysql_query("update   schedule_of_fees set amount='0' where sched_id='$sched_id' and sy='$sy' and semester='$sem' and year_level='$year'") or die(mysql_error());
				}else{
						if($removeornot2=="yes"){
							mysql_query("update   schedule_of_fees set amount='0' where sched_id='$sched_id' and sy='$sy' and semester='$sem' and year_level='$year'") or die(mysql_error());
						}else{
								$check_sched=mysql_query("select sched_id from schedule_of_fees where sched_id='$sched_id'") or die(mysql_error());
								$countcheck=mysql_num_rows($check_sched);
								if($countcheck>0){
									mysql_query("replace into schedule_of_fees values ('$sched_id','$desc_id','$desc_amount','$desc_cat','$course','$year','$sy','$sem')") or die(mysql_error());
								}else{
									mysql_query("insert into schedule_of_fees values ('','$desc_id','$desc_amount','$desc_cat','$course','$year','$sy','$sem')") or die(mysql_error());

								}
						}
					
				}
			
   			$start2++;
		}
		
	$start++;
}

//other fees
$rledata=$_POST['rledata'];

$rlearr=explode('[endline]', $rledata);
$rlearrlen=count($rlearr);
$startrle=1;
//for the rledata
while ($startrle<$rlearrlen) {
	$rledata2=$rlearr[$startrle];
	$rledata2arr=explode("<->", $rledata2);
	$rlecourse=$rledata2arr[0];
	$rledamount=$rledata2arr[1];   
	$rleyear=$rledata2arr[2];   
	$rleschedid=$rledata2arr[3];   
	$removerle=$rledata2arr[4]; 
	$addedrow=$rledata2arr[5]; 
	$rlevar="Related Learning Experience (RLE)";  
	if($removerle=="yes"){
		mysql_query("update  schedule_of_fees set amount='0' where sched_id='$rleschedid'");
	}else{	
			$getrleid=mysql_query("select payment_id from paymentlist where payment_desc='$rlevar' and payment_group='rle'");
			$rleid=mysql_fetch_array($getrleid);
			if($addedrow=="yes"){
 					mysql_query("insert into schedule_of_fees values ('','$rleid[payment_id]','$rledamount','misc','$rlecourse','$rleyear&','$sy','$sem')") or die(mysql_error());
			}else{
 				mysql_query("replace into schedule_of_fees values ('$rleschedid','$rleid[payment_id]','$rledamount','misc','$rlecourse','$rleyear&','$sy','$sem')") or die(mysql_error());
			}
	}
$startrle++;
}


//for the other fee
$otherdata=$_POST['otherdata'];
$otherdataarr=explode("[endline]", $otherdata);
$otherdataarrlen=count($otherdataarr);
$startother=1;
while ($startother<$otherdataarrlen) {
	$otherdata2=$otherdataarr[$startother];
	$otherdata2arr=explode("<->", $otherdata2);
	$otherdesc=$otherdata2arr[0];	
	$otheramount=$otherdata2arr[1];	
	$othercat=$otherdata2arr[2];	
	$otherschedid=$otherdata2arr[3];	
	$removeother=$otherdata2arr[4];	

	//check first if the payment is deleted
	if($removeother=="yes"){

		mysql_query("update  schedule_of_fees set amount='0'   where sched_id='$otherschedid'") or die(mysql_error());
	}else{
		$checkdesc=mysql_query("select payment_id from paymentlist where payment_desc='$otherdesc' and payment_group='other'") or die(mysql_error());
		$countother=mysql_num_rows($checkdesc);
		$otherid="";
		if($countother>0){
			$otherrow=mysql_fetch_array($checkdesc);
			$otherid=$otherrow['payment_id'];
		}else{
		mysql_query("insert into paymentlist values ('','$otherdesc','other')");
		$getotherid=mysql_query("select payment_id from paymentlist where payment_desc='$otherdesc' and payment_group='other' order by payment_id desc")  or die(mysql_error());
		$otherrow=mysql_fetch_array($getotherid);
		$otherid=$otherrow['payment_id'];
		}
		$check2=mysql_query("select sched_id from schedule_of_fees where sched_id='$otherschedid'") or die(mysql_error());
		$countcheck2=mysql_num_fields($check2);
			if($countcheck2==0){
			mysql_query("insert into schedule_of_fees values ('','$otherid','$otheramount','$othercat','','','$sy','$sem')") or die(mysql_error());
			}else{
			mysql_query("replace into schedule_of_fees values ('$otherschedid','$otherid','$otheramount','$othercat','','','$sy','$sem')") or die(mysql_error());
			}
	}
	$startother++;
}

// //for the graduation fees
$graddata=$_POST['graddata'];

$graddataarr=explode("[endline]", $graddata);
$graddataarrlen=count($graddataarr);
$startgrad=1;
while ($startgrad<$graddataarrlen){
	$graddata2=$graddataarr[$startgrad];
	$graddata2arr=explode("<->", $graddata2);
	$graddesc=$graddata2arr[0];	
	$gradamount=$graddata2arr[1];	
	$gradcat=$graddata2arr[2];	
	$gradschedid=$graddata2arr[3];	
	$removegrad=$graddata2arr[4];	
	if($removegrad=="yes"){
		mysql_query("update  schedule_of_fees set amount='0' where sched_id='$gradschedid'") or die(mysql_error());

	}else{
		$checkdesc=mysql_query("select * from paymentlist where payment_desc='$graddesc' and payment_group='grad'");
		$countgrad=mysql_num_rows($checkdesc);
		$gradid="";
		
		if($countgrad>0){
			$gradrow=mysql_fetch_array($checkdesc);
			$gradid=$gradrow['payment_id'];
		}else{
			mysql_query("insert into paymentlist values ('','$graddesc','grad')");
			$getgradid=mysql_query("select payment_id from paymentlist where payment_desc='$graddesc' and payment_group='grad'");
			$gradrow=mysql_fetch_array($getgradid);
			$gradid=$gradrow['payment_id'];
		}
			$check2=mysql_query("select sched_id from schedule_of_fees where sched_id='$gradschedid'") or die(mysql_error());
			$countcheck2=mysql_num_fields($check2);
			if($countcheck2==0){
				mysql_query("insert into schedule_of_fees values ('','$gradid','$gradamount','$gradcat','','&IV','$sy','0')") or die(mysql_error());
			}else{
				mysql_query("replace into schedule_of_fees values ('$gradschedid','$gradid','$gradamount','$gradcat','','&IV','$sy','0')") or die(mysql_error());
			}	

	}


	$startgrad++;
}


// //for the trans and new studens
$transdata=$_POST['transdata'];
$transdataarr=explode("[endline]", $transdata);
$transdataarrlen=count($transdataarr);
$starttrans=1;
while ($starttrans<$transdataarrlen){
	$transdata2=$transdataarr[$starttrans];
	$transdata2arr=explode("<->", $transdata2);
	$transdesc=$transdata2arr[0];	
	$transamount=$transdata2arr[1];	
	$transcat=$transdata2arr[2];	
	$transschedid=$transdata2arr[3];	
	$removetrans=$transdata2arr[4];	
	if($removetrans=="yes"){
		mysql_query("update  schedule_of_fees set amount='0' where sched_id='$transschedid'") or die(mysql_error());

	}else{
		$checktransdesc=mysql_query("select * from paymentlist where payment_desc='$transdesc' and payment_group='new'");
		$counttrans=mysql_num_rows($checktransdesc);
		$transid="";
		if($counttrans>0){
			$transrow=mysql_fetch_array($checktransdesc);
			$transid=$transrow['payment_id'];
		}else{
			mysql_query("insert into paymentlist values ('','$transdesc','new')");
			$gettransid=mysql_query("select payment_id from paymentlist where payment_desc='$transdesc' and payment_group='new' order by payment_id desc");
			$transrow=mysql_fetch_array($gettransid);
			$transid=$transrow['payment_id'];
		}

			$checktranssched=mysql_query("select sched_id from schedule_of_fees where sched_id='$transschedid'") or die(mysql_error());
			$checktransschedcount=mysql_num_fields($checktranssched);
			if($checktransschedcount==0){
				mysql_query("insert into schedule_of_fees values ('','$transid','$transamount','$transcat','','I&','$sy','$sem')") or die(mysql_error());
			}else{
				mysql_query("replace into schedule_of_fees values ('$transschedid','$transid','$transamount','$transcat','','I&','$sy','$sem')") or die(mysql_error());
			}


	}

	$starttrans++;
}

////////////////for the othermisc table

//for the other fee
$othermiscdata=$_POST['othermiscdata'];
$otherdataarr=explode("[endline]", $othermiscdata);
$otherdataarrlen=count($otherdataarr);
$startother=1;
while ($startother<$otherdataarrlen) {
	$otherdata2=$otherdataarr[$startother];
	$otherdata2arr=explode("<->", $otherdata2);
	$otherdesc=$otherdata2arr[0];	
	$otheramount=$otherdata2arr[1];	
	$othercat=$otherdata2arr[2];	
	$otherschedid=$otherdata2arr[3];	
	$removeother=$otherdata2arr[4];	

	//check first if the payment is deleted
	if($removeother=="yes"){
		mysql_query("update  schedule_of_fees set amount='0' where sched_id='$otherschedid'") or die(mysql_error());
	}else{
		$checkdesc=mysql_query("select payment_id from paymentlist where payment_desc='$otherdesc' and payment_group='othermisc'") or die(mysql_error());
		$countother=mysql_num_rows($checkdesc);
		$otherid="";
		if($countother>0){
			$otherrow=mysql_fetch_array($checkdesc);
			$otherid=$otherrow['payment_id'];
		}else{
		mysql_query("insert into paymentlist values ('','$otherdesc','othermisc')");
		$getotherid=mysql_query("select payment_id from paymentlist where payment_desc='$otherdesc' and payment_group='othermisc' order by payment_id desc")  or die(mysql_error());
		$otherrow=mysql_fetch_array($getotherid);
		$otherid=$otherrow['payment_id'];
		}
		$check2=mysql_query("select sched_id from schedule_of_fees where sched_id='$otherschedid'") or die(mysql_error());
		$countcheck2=mysql_num_fields($check2);
			if($countcheck2==0){
			mysql_query("insert into schedule_of_fees values ('','$otherid','$otheramount','$othercat','','','$sy','$sem')") or die(mysql_error());
			}else{
			mysql_query("replace into schedule_of_fees values ('$otherschedid','$otherid','$otheramount','$othercat','','','$sy','$sem')") or die(mysql_error());
			}
	}
	$startother++;
}
?>
<script type="text/javascript"> 	
			 searchsched("search"); 
   </script>
<?php
}else{
	?>
<script type="text/javascript">
 	
	alert("ERROR: Schedule of Fees SY <?=$sy." Semester"."$sem"." Year Level $year ";?> is already existed.");
  </script>

	<?php
}
?>asdf