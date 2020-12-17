<?php


include_once("base.php");
if(isset($_GET['del'])){
del('invoice_invoices',$_GET['id']);
header("location:logindex.php?do=invoice_list&y={$_GET['y']}&p={$_GET['p']}");
}
?>
