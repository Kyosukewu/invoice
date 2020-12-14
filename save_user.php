<?php
//更新使用者資料

include_once "base.php";
$id=$_POST['id'];
$acc=$_POST['acc'];
$pw=$_POST['pw'];
$name=$_POST['name'];
$birthday=$_POST['birthday'];
$addr=$_POST['addr'];
$email=$_POST['email'];
$education=$_POST['education'];
$role=$_POST['role'];

// $login=$pdo->query("select * from `invoice_login` where `id`='$id'")->fetch();

$update_login_sql="update `login` set `acc`='$acc',`pw`='$pw',`email`='$email' where `id`='$id'";
$pdo->exec($update_login_sql);

$update_member_sql="update `member` set `name`='$name',`birthday`='$birthday',`role`='$role',`education`='$education',`addr`='$addr' where `login_id`='$id'";
$pdo->exec($update_member_sql);


header('location:logindex.php?in=management&inside=m_user');
?>