<?php
session_start();
include 'dbconfig.php';
$data=$_POST['data'];
$sy=$_POST['sy'];
$semester=$_POST['sem'];
$year=$_POST['year'];
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
if($sy==""){
  header("location:index.php");
}else
$checkifexisted=mysql_query("select * from schedule_of_fees,paymentlist where paymentlist.payment_id=schedule_of_fees.payment_id and payment_group='misc' and sy='$sy' and semester='$semester' and ($newyear) ");
if(mysql_num_rows($checkifexisted)==0){
$allarr=explode("[endline>]", $data);
$datalen=count($allarr);
$start=1;
while ($start<$datalen){
  $data2=$allarr[$start];
  $data2arr=explode("[&&]", $data2);
  $data2len=count($data2arr);
  $start2=2;

  $schoolfee=$data2arr[1];
  $schoolfeearr=explode("<->", $schoolfee);
  $desc=$schoolfeearr[1];
  $descgroup=$schoolfeearr[0];
  $desc_cat=$schoolfeearr[2];
  $checkdesc=mysql_query("select * from paymentlist where payment_desc='$desc' order by payment_id desc") or die (mysql_error());
    
  $checkdesc_count=mysql_num_rows($checkdesc);
  $desc_id="";
  if($checkdesc_count>0){
    $getdesc_id=mysql_fetch_array($checkdesc);
    $desc_id=$getdesc_id['payment_id'];
  }else{
    $insert_desc=mysql_query("insert into paymentlist values ('','$desc','$descgroup')") or die(mysql_error());
    $get_desc=mysql_query("select * from paymentlist where payment_desc='$desc' order by payment_id desc") or die (mysql_error());
    $getdesc_id=mysql_fetch_array($get_desc);
    $desc_id=$getdesc_id['payment_id'];
  }
    

    
    while ($start2<$data2len) {
      $amount=explode("<->",$data2arr[$start2]);
      $course=$amount[1];
      $desc_amount=$amount[2];
      mysql_query("insert into schedule_of_fees values ('','$desc_id','$desc_amount','$desc_cat','$course','$year','$sy','$semester')") or die(mysql_error());
        $start2++;
    }
  $start++;
}

//other fees/////////////////////////////////////////

$rledata=$_POST['rledata'];
$sem=$semester;
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
  $rlevar="Related Learning Experience (RLE)";
  $saverle=mysql_query("select * from paymentlist where payment_desc='$rlevar' and payment_group='rle'");
  $checkrle=mysql_num_rows($saverle);
  if($checkrle==0){
    mysql_query("insert into paymentlist values ('','$rlevar','rle')");
    }
    $getrleid=mysql_query("select payment_id from paymentlist where payment_desc='$rlevar' and payment_group='rle'");
    $rleid=mysql_fetch_array($getrleid);
    if($rleyear=="I"){
     $rleyear="$rleyear&";
  }else{
    $rleyear="&$rleyear";
  } 
    mysql_query("insert into schedule_of_fees values ('','$rleid[payment_id]','$rledamount','misc','$rlecourse','$rleyear','$sy','$sem')") or die(mysql_error());

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

  $checkdesc=mysql_query("select * from paymentlist where payment_desc='$otherdesc' and payment_group='other'");
  $countoher=mysql_num_rows($checkdesc);
  if($countoher==0){
    mysql_query("insert into paymentlist values ('','$otherdesc','other')");
  }
  $getotherid=mysql_query("select payment_id from paymentlist where payment_desc='$otherdesc' and payment_group='other'");
  $otherid=mysql_fetch_array($getotherid);
  mysql_query("insert into schedule_of_fees values ('','$otherid[payment_id]','$otheramount','$othercat','','','$sy','$sem')") or die(mysql_error());


  $startother++;
}

//for the graduation fees
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

  $checkdesc=mysql_query("select * from paymentlist where payment_desc='$graddesc' and payment_group='grad'");
  $countgrad=mysql_num_rows($checkdesc);
  if($countgrad==0){
    mysql_query("insert into paymentlist values ('','$graddesc','grad')");
  }
  $getgradid=mysql_query("select payment_id from paymentlist where payment_desc='$graddesc' and payment_group='grad'");
  $gradid=mysql_fetch_array($getgradid);
  mysql_query("delete from schedule_of_fees where payment_id='$gradid[payment_id]' and sy='$sy' ");
  mysql_query("insert into schedule_of_fees values ('','$gradid[payment_id]','$gradamount','$gradcat','','&IV','$sy','0')") or die(mysql_error());


  $startgrad++;
}


//for the trans and new studens
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

  $checkdesc=mysql_query("select * from paymentlist where payment_desc='$transdesc' and payment_group='new'");
  $counttrans=mysql_num_rows($checkdesc);
  if($counttrans==0){
    mysql_query("insert into paymentlist values ('','$transdesc','new')");
  }
  $gettransid=mysql_query("select payment_id from paymentlist where payment_desc='$transdesc' and payment_group='new'");
  $transid=mysql_fetch_array($gettransid);
  mysql_query("insert into schedule_of_fees values ('','$transid[payment_id]','$transamount','$transcat','','I&','$sy','$sem')") or die(mysql_error());


  $starttrans++;
}

//for the other misc 
$othermiscdata=$_POST['othermiscdata'];
$othermiscarr=explode("[endline]", $othermiscdata);
$transdataarrlen=count($othermiscarr);
$startothermisc=1;
while ($startothermisc<$transdataarrlen){
  $othermiscdata2=$othermiscarr[$startothermisc];
  $othermiscdata2arr=explode("<->", $othermiscdata2);
  $othermiscdesc=$othermiscdata2arr[0]; 
  $othermiscamount=$othermiscdata2arr[1]; 
  $othermisccat=$othermiscdata2arr[2];  

  $checkdesc=mysql_query("select * from paymentlist where payment_desc='$othermiscdesc' and payment_group='othermisc'");
  $counttrans=mysql_num_rows($checkdesc);
  if($counttrans==0){
    mysql_query("insert into paymentlist values ('','$othermiscdesc','othermisc')");
  }
  $getothermiscid=mysql_query("select payment_id from paymentlist where payment_desc='$othermiscdesc' and payment_group='othermisc'");
  $othermiscid=mysql_fetch_array($getothermiscid);
  mysql_query("insert into schedule_of_fees values ('','$othermiscid[payment_id]','$othermiscamount','$othermisccat','','','$sy','$sem')") or die(mysql_error());


  $startothermisc++;
}
?>
<script type="text/javascript">
      $('#schedsearch select:eq(1)').prepend("<option selected='selected'><?=$sy;?></option>");
searchsched("search");
</script>
<?php
}else{
?>
<script type="text/javascript">
  
  alert("ERROR: Schedule of Fees SY <?=$sy." Semester "." $semester"." Year Level $year ";?> is already existed.");
</script>
<?php
}
?> 
 