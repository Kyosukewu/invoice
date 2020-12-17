<?php
include_once("../base.php");

// $sql="update 
// invoices 
// set 
// `code`='{$_POST['code']}',
// `number`='{$_POST['number']}',
// `date`='{$_POST['date']}',
// `payment`='{$_POST['payment']}' 
// where 
// `id`='{$_POST['id']}'";

$row=find('invoice_invoices',$_POST['id']);
$row['code']=$_POST['code'];
$row['number']=$_POST['number'];
$row['date']=$_POST['date'];
$row['payment']=$_POST['payment'];

save('invoice_invoices',$row);

$pdo->exec($sql);

// header("location:../logindex.php?do=invoice_list");
to("../logindex.php?do=invoice_list&y={$_POST['y']}&p={$_POST['p']}");
?>