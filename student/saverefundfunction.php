<?php
function saverefundfunction($stud_id,$amount,$sy,$semester,$check){
 	$amount=str_replace("-", "",$amount);
	if($check=="delete"){
		//check if there's already action taken
		$checkref=mysql_query("select * from exceeded_money where from_sy='$sy' and from_semester='$semester' and stud_id='$stud_id' and to_stud_id='0' and action=''") or die(mysql_error());
		if(mysql_num_rows($checkref)>0){
			mysql_query("delete from exceeded_money where stud_id='$stud_id' and from_sy='$sy' and from_semester='$semester' and to_stud_id='0' and action=''");
		}
	}else{
		$aa=mysql_query("select * from exceeded_money where stud_id='$stud_id' and from_semester='$semester' and from_sy='$sy'")or die(mysql_error());
		if(mysql_num_rows($aa)==0){

			//get last or to detect that this is the exceeded or
			$getor=mysql_query("select * from collection where stud_id='$stud_id' and sy='$sy' and semester='$semester' order by col_id desc");
			$orrow=mysql_fetch_array($getor);
	 		if(mysql_num_rows($getor)==0){
					$getor=mysql_query("select * from collection where stud_id='$stud_id' order by col_id desc");
					$orrow=mysql_fetch_array($getor);
	 		}
			//check if it belongs from the other or other misc group

			$checkcat=mysql_query("select * from schedule_of_fees,paymentlist where schedule_of_fees.payment_id=paymentlist.payment_id and schedule_of_fees.sched_id='$orrow[sched_id]'") or die(mysql_error());
			$catrow=mysql_fetch_array($checkcat);
			if($catrow['payment_group']!='other' || $catrow['payment_group']!='othemisc'){
		 		mysql_query("insert into exceeded_money values('','$stud_id','','$orrow[receipt_num]','$amount','$sy','$semester','','','')"); 
			}else{
				//this is not required coz you cant no longer to misc or tuition if already exceeded the you pay


			}
		}else{
			mysql_query("update exceeded_money set amount='$amount', where stud_id='$stud_id' and from_semester='$semester' and from_sy='$sy' and action='' ");
		}
	}
}
?> 