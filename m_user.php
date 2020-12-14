<?php

include_once "base.php";
$userData = "select `login`.`id`,`acc`,`name`,`role`,`birthday`,`email`,`addr`,`create_time` from `invoice_login`,`invoice_member` where `login`.`id`=`member`.`login_id` ";
$users = $pdo->query($userData)->fetchALL();
?>

<table class="ttable table table-sm table-striped table-hover text-center">
    <tr class="bg-secondary text-light">
        <td class="u1">ID</td>
        <td class="u2">帳號</td>
        <td class="u3">姓名</td>
        <td class="u4">生日</td>
        <td class="u5">Email</td>
        <td class="u6">地址</td>
        <td class="u7">加入時間</td>
        <td class="u8">權限</td>
        <td class="u8">操作</td>
    </tr>
    <?php
    foreach ($users as $user) {
    ?>
        <tr>
            <td class="u1"><?= $user['id']; ?></td>
            <td class="u2"><?= $user['acc']; ?></td>
            <td class="u3"><?= $user['name']; ?></td>
            <td class="u4"><?= $user['birthday']; ?></td>
            <td class="u5"><?= $user['email']; ?></td>
            <td class="u6"><?= $user['addr']; ?></td>
            <td class="u7"><?= $user['create_time']; ?></td>
            <td class="u8"><?= $user['role']; ?></td>
            <td class="u8 d-flex">
                <a href='?in=management&inside=edit_user&id=<?= $user['id']; ?>'><button class='btn btn-sm btn-outline-primary'>編輯</button></a>
                <a class="ml-1" href='?in=management&inside=del_user&id=<?= $user['id']; ?>'><button class='btn btn-sm btn-outline-danger'>刪除</button></a>
            </td>
    </tr>
    <?php
    }
    ?>
</table>
