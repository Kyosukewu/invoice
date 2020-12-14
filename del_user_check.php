<?php
$user_id = $_GET['id'];
$sql1 = "delete from `invoice_login` where `id`='$user_id'";
$sql2 = "delete from `invoice_member` where `login_id`='$user_id'";

//判斷是否確定刪除
if (isset($_GET['ask'])) {
    switch ($_GET['ask']) {
        case 'true':
            $pdo->exec($sql1);
            $pdo->exec($sql2);
            header("location:logindex.php?in=management&inside=m_user");
        case 'false':
            header("location:logindex.php?in=management&inside=m_user");
    }
}
?>