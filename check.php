<?php
/******登入檢查******/

include_once "base.php";

$acc=$_POST['acc'];
$pw=$_POST['pw'];
$_SESSION['login']=$acc;

$sql="select * from `invoice_login` where `acc`='$acc' && `pw`='$pw'";

$check=$pdo->query($sql)->fetch();

if(!empty($check)){
    header('location:logindex.php');
}else{
    header("location:index.php?meg=帳號或密碼不正確，請重新輸入或註冊新帳號");
}

?>