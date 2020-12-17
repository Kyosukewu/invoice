<?php
include_once "base.php";

?>

<div class="fake">

</div>

<?php
$user_id = $_GET['id'];
$user_sql = "select * from `invoice_login`,`invoice_member` where `invoice_login`.`id` = `invoice_member`.`login_id` AND `invoice_login`.`id`='$user_id'";
$userdata = $pdo->query($user_sql)->fetch();
?>
<div class="overlayl">
    <div class="title bg-danger">
        <p class="text-white">刪除使用者</p>
        <a href="?in=management&inside=m_user"><i class="fas fa-times"></i></a>
    </div>
    <div class="edit text-center">
        <p>確定要刪除此使用者？</p>
        <div class="mainedit mb-2 w-100">
            <div class="w-100">
            <ul class="list-group">
                <li class="list-group-item">ID： <?= $userdata['id']; ?></li>
                <li class="list-group-item">帳號： <?= $userdata['acc']; ?></li>
                <li class="list-group-item">名字： <?= $userdata['name']; ?></li>
            </ul>
            </div>
        </div>
        <div class="text-center">
            <a href="?in=management&inside=del_user_check&id=<?= $user_id; ?>&ask=true">
                <button class="btn-danger">確認</button>
            </a>
            <a href="?in=management&inside=m_user">
                <button class="btn-warning">取消</button>
            </a>
            <div>
            </div>
        </div>
    </div>
</div>