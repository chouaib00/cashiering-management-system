<?php
include 'dbconfig.php';

$rledata=$_POST['rledata'];
$sem='II';
$sy='2012';
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
	mysql_query("delete from schedule_of_fees where payment_id='$gradid[payment_id]' and sy='$sy' and semester='$sem'");
	mysql_query("insert into schedule_of_fees values ('','$gradid[payment_id]','$gradamount','$gradcat','','4','$sy','$sem')") or die(mysql_error());


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
	mysql_query("insert into schedule_of_fees values ('','$transid[payment_id]','$transamount','$transcat','','1','$sy','$sem')") or die(mysql_error());


	$starttrans++;
}

?>