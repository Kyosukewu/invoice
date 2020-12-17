<?php
include_once("base.php");
$year=$_GET['y'];
$period = $_GET['p'];
$awards = $pdo->query("select * from invoice_award_numbers where year='$year' && period='$period'")->fetchALL();
if(!empty($_SESSION['login'])){
if(empty($awards)){
header("location:logindex.php?do=award_numbers&y=$year&p=$period&aemp=1");
}else{
header("location:logindex.php?do=award_numbers&y=$year&p=$period");
}
}else{
    if(empty($awards)){
        header("location:visitor.php?do=award_numbers_v&y=$year&p=$period&aemp=1");
        }else{
        header("location:visitor.php?do=award_numbers_v&y=$year&p=$period");
        }
}
?>