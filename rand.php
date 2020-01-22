<?php
function rand2(){
$possiblevalue="1,2,3,4,5,6,7,8,9";
$explode=explode(",", $possiblevalue);
$start=0;
$rand="";
while ($start<10) {
    $rand.=$explode[rand(0,8)];
    $start++;
}
return $rand;
}
?>