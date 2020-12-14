<?php
//撰寫新增消費發票的程式碼
//將發票的號碼及相關資訊寫入資料庫

include_once("../base.php");// once 只要一份
$_SESSION['err']=[];
// foreach($_POST as $key =>$value){
//     $tmp[]=$key;
// }
// foreach($_POST as $key =>$value){
//     $tmp2[]=$value;
// }
// echo "<pre>";
// print_r(array_keys($_POST));
// echo "</pre>";
// echo "<pre>";
// print_r(array_keys($_POST));
// echo "</pre>";
accept('code','發票號碼的欄位必填');
length('code',2,2,'發票號碼前兩位須為2碼英文');
accept('number','發票號碼的欄位必填');
// length('number',8,8,'發票號碼須為8碼數字');
accept('payment','發票金額的欄位必填');



// $sql="insert into invoices (`".implode("`,`",array_keys($_POST))."`) values('".implode("','",$_POST)."')";



// echo "新增完成";

if(empty($_SESSION['err'])){
    save('invoice_invoices',$_POST);
    // $pdo->exec($sql);
    header("location:../logindex.php?do=invoice_list");
}else{
    header("location:../logindex.php");
}

?>