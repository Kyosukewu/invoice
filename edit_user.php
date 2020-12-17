<?php
include_once "base.php";
//編輯會員資料
$user_id=$_GET['id']; //login 資料表的id .member 資料表login_id
$user_sql="select * from `invoice_login`,`invoice_member` where `invoice_login`.`id` = `invoice_member`.`login_id` AND `invoice_login`.`id`='$user_id'";
$user=$pdo->query($user_sql)->fetch();
?>
<div class="d-flex justify-content-center">
<form action="save_user.php" method="post" class="col-6">
    <div class="list-group">
        <input type="hidden" name="id" value="<?=$user['id'];?>">
        <li class="list-group-item">帳號：<input type="text" name='acc' value="<?=$user['acc'];?>"></li>
        <li class="list-group-item">密碼：<input type="password" name='pw'  value="<?=$user['pw'];?>"></li>
        <li class="list-group-item">姓名：<input type="text" name='name' value="<?=$user['name'];?>"></li>
        <li class="list-group-item">生日：<input type="date" name='birthday' value="<?=$user['birthday'];?>"></li>
        <li class="list-group-item">地址：<input type="text" name='addr' value="<?=$user['addr'];?>"></li>
        <li class="list-group-item">email：<input type="text" name='email' value="<?=$user['email'];?>"></li>
        <li class="list-group-item">學歷：
            <select name="education" id="">
                <option value="國中" <?=($user['education']=='國中')?"selected":""?>>國中</option>
                <option value="高中" <?=($user['education']=='高中')?"selected":""?>>高中</option>
                <option value="大學/專科" <?=($user['education']=='大學/專科')?"selected":""?>>大學/專科</option>
                <option value="碩士" <?=($user['education']=='碩士')?"selected":""?>>碩士</option>
                <option value="博士" <?=($user['education']=='博士')?"selected":""?>>博士</option>
            </select>
        </li>
        <li class="list-group-item">角色：
        <select name="role" id="">
                <option value="會員" <?=($user['role']=='會員')?"selected":""?>>會員</option>
                <option value="VIP" <?=($user['role']=='VIP')?"selected":""?>>VIP</option>
                <option value="管理員" <?=($user['role']=='管理員')?"selected":""?>>管理員</option>
            </select>
        </li>
    </div>
    <div class="text-center">
    <input type="submit" value="確認更新" class="btn btn-sm btn-outline-primary my-">
    <input type="reset" value="重置" class="btn btn-sm btn-outline-warning my-1">
    </div>
</form>
</div>
