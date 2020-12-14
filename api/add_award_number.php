<?php

//撰寫建立各期中獎號碼的程式
//將表單傳送過來的中獎號碼寫入資料庫
include_once("../base.php");// once 只要一份
$_SESSION['err']=[];
// foreach($_POST as $key =>$value){
//     $tmp[]=$key;
// }
// foreach($_POST as $key =>$value){
//     $tmp2[]=$value;
// }
accept('special_prize','此欄位必填');
length('special_prize',8,8,'開獎號碼為8位數字');
accept('grand_prize','此欄位必填');
length('grand_prize',8,8,'開獎號碼為8位數字');
// accept('first_prize[]','此欄位必填');
// length('first_prize[]',8,8,'開獎號碼為8位數字');

// echo "<pre>";
// print_r(array_keys($_POST));
// echo "</pre>";
if(empty($_SESSION['err'])){

$year=$_POST['year'];
$period=$_POST['period'];
//特別獎新增 type=1

$sql="insert into 
invoice_award_numbers 
(`year`,`period`,`number`,`type`) 
values
('$year','$period','{$_POST['special_prize']}','1')";
$pdo->exec($sql);

//特獎新增 type=2
$sql="insert into 
invoice_award_numbers 
(`year`,`period`,`number`,`type`) 
values
('$year','$period','{$_POST['grand_prize']}','2')";//陣列一定要{}  非陣列都可
$pdo->exec($sql);

//頭獎新增 type=3
foreach($_POST['first_prize'] as $first){
    if(!empty($first)){
    $sql="insert into 
    invoice_award_numbers 
    (`year`,`period`,`number`,`type`) 
    values
    ('$year','$period','$first','3')";//陣列一定要{}  非陣列都可
    $pdo->exec($sql);
}
}

//增開六獎新增 type=4
foreach($_POST['add_prize'] as $six){
    if(!empty($six)){
    $sql="insert into 
    invoice_award_numbers 
    (`year`,`period`,`number`,`type`) 
    values
    ('$year','$period','$six','4')";//陣列一定要{}  非陣列都可
    $pdo->exec($sql);
}
}


// echo "新增完成";
// header("location:../logindex.php?do=award_numbers&pd=".$year."-".$period);
// to("../logindex.php?do=award_numbers&y=$year&p=$period");

header("location:../logindex.php?do=award_numbers&y=$year&p=$period");

}else{
    header("location:../logindex.php?in=add_awards");
}
