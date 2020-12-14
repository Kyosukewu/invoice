<?php
//處理新增使用者的功能
include_once "base.php";

$acc=$_POST['acc'];
$pw=$_POST['pw'];
$name=$_POST['name'];
$birthday=$_POST['birthday'];
$addr=$_POST['addr'];
$email=$_POST['email'];
$education=$_POST['education'];

//寫入登入資料表
$insert_to_login="insert into `invoice_login`(`acc`,`pw`,`email`) values('$acc','$pw','$email')";

$pdo->exec($insert_to_login); //回傳成功/失敗/影響筆數
$select_login_user="select * from `invoice_login` where `acc`='$acc' && `pw`='$pw'";
$login_user=$pdo->query($select_login_user)->fetch();
$login_id=$login_user['id'];

//寫入使用者資料表

$insert_to_member="insert into `invoice_member`(`name`,`birthday`,`role`,`addr`,`education`,`login_id`) values('$name','$birthday','會員','$addr','$education','$login_id')";

$result=$pdo->exec($insert_to_member);

if($result){
    header("location:index.php?meg=帳號新增成功");
}else{
    header("location:index.php?meg=帳號新增失敗");
}

?>