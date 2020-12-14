<?php
include_once("base.php");

$year=$_GET['y'];
$period=$_GET['p'];

$nowYear=date('Y');
$nowPeriod=ceil(date("m") / 2);
$nowMonth=date('m');
$today=date('d');

$inv=$pdo->query("select * from `invoice_invoices` where date LIKE'$year%' && period='$period'")->fetchALL();

if(empty($inv)){
    if($year<$nowYear){
        header("location:logindex.php?do=invoice_list&y=$year&p=$period&ex=1");
    }
    if($year>$nowYear || ($year==$nowYear && $period>$nowperiod)){
        header("location:logindex.php?in=main&do=invoice_list&y=$year&p=$period&emp=1");
    }
}elseif(($year<$nowYear && ($period<4 || ($period==4 && ($nowPeriod>1 || ($nowMonth==1 && $today>5) || $nowMonth==2)) || ($period==5 && ($nowPeriod>2 || ($nowMonth==3 && $today>5) || $nowMonth==4)) || ($period==6 && ($nowPeriod>3 || ($nowMonth==5 && $today>5) || $nowMonth==6)))) || 
($year==$nowYear && ($period<4 || ($period==1 && ($nowPeriod>4 || ($nowMonth==7 && $today>5) || $nowMonth==8)) || ($period==2 && ($nowPeriod>5 || ($nowMonth==9 && $today>5) || $nowMonth==10)) || ($period==3 && (($nowMonth==11 && $today>5) || $nowMonth==12))))){
    header("location:logindex.php?do=invoice_list&y=$year&p=$period&ex=1");
}else{
    header("location:logindex.php?do=invoice_list&y=$year&p=$period");
}
?>