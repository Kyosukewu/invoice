<?php
include_once("base.php");
//期別查詢

$year =  $_GET['y'];
$period = $_GET['p'];
$user = $pdo->query("select * from `invoice_login` where acc='{$_SESSION['login']}'")->fetch();
$user_id = $user['id'];
//資料分頁
$pageSize = 19; //每頁幾條紀錄
if ($_SESSION['login'] == "admin") {
    $rowCount = $pdo->query("select COUNT(period) from `invoice_invoices` where date LIKE'$year%' && period='$period'")->fetch(); //共幾條紀錄
} else {
    $rowCount = $pdo->query("select COUNT(period) from `invoice_invoices` where user_id='$user_id' && date LIKE'$year%' && period='$period'")->fetch(); //共幾條紀錄
}
$pageNow = 1; //顯示第幾頁
$pageCount = ceil($rowCount[0] / $pageSize); //共多少頁
if (!empty($_GET['pageNow'])) {
    $pageNow = $_GET['pageNow'];
}
$pageStart = ($pageNow - 1) * $pageSize; //目前頁面
$sql = "select * from `invoice_invoices` where period='$period' Order by date desc limit $pageStart,$pageSize";
$rows = $pdo->query($sql)->fetchall();

?>
<div class="rightPage h-100 d-flex flex-column justify-content-between">
    <div class="path">
        <div class="pagination pagination-sm justify-content-center align-items-end mt-lg-2">
            <li class="page-item">
                <a class="page-link" href="#">
                    <span aria-hidden="true" class="text-dark fas fas fa-angle-left"></span>
                </a>
            </li>
            <li class="page-item">
                <form class="d-flex" action="logindex.php" method="get">
                    <select name="y" class="form-select form-select-sm text-dark">
                        <option value="<?= $year - 1 ?>"><?= $year - 1 ?></option>
                        <option value="<?= $year ?>" selected><?= $year ?></option>
                        <option value="<?= $year + 1 ?>"><?= $year + 1 ?></option>
                    </select>
                    <select name="p" class="form-select form-select-sm text-dark">
                        <?php
                        for ($i = 1; $i < 7; $i++) {
                            if ($i == $period) {
                                echo "<option value='$i' selected>";
                            } else {
                                echo "<option value='$i'>";
                            }
                            echo "$month[$i]" . "</option>";
                        }
                        ?>
                    </select>
                </form>
            </li>
            <li class="page-item">
                <a class="page-link" href="#">
                    <span aria-hidden="true" class="text-dark fas fa-angle-right"></span>
                </a>
            </li>
        </div>
    </div>
    <div class="inv">
        <div class="tit d-flex w-100 py-2 mb-3 border-top">
            <div class="t1 text-center">發票號碼</div>
            <div class="t2 text-center">消費日期</div>
            <div class="t3 text-center">金額</div>
            <div class="t4 text-center d-none d-md-block">操作</div>
        </div>
        <div class="invoices w-100">
            <?php
            foreach ($rows as $row) {
            ?>
                <div class="invoice d-flex flex-wrap">
                    <div class="t1 text-center"><?= $row['code'] . "-" . $row['number'] ?></div>
                    <div class="t2 text-center  text-secondary"><?= $row['date'] ?></div>
                    <div class="t3 text-center"><?= $row['payment'] ?></div>
                    <div class="t4 text-center d-none d-md-block">
                        <a href="#">
                            <button type="button" data-toggle="tooltip" data-placement="top" title="編輯" class="btn btn-sm btn-outline-warning">
                                <p class="far fa-edit"></p>
                            </button>
                        </a>
                        <a href="#"><button type="button" data-toggle="tooltip" data-placement="top" title="刪除" class="btn btn-sm btn-outline-danger">
                                <p class="fas fa-trash-alt"></p>
                            </button>
                        </a>
                        <a href="#"><button type="button" data-toggle="tooltip" data-placement="top" title="對獎" class="btn btn-sm btn-outline-success">
                                <p class="fas fa-medal"></p>
                            </button>
                        </a>
                    </div>
                    <div class="t5 text-center d-flex align-items-center d-md-none w-100 mb-3">
                        <div class="contral w-100 text-right mt-1 mr-4">
                            <a href="#">
                                <button type="button" data-toggle="tooltip" data-placement="top" title="編輯" class="btn btn-sm btn-outline-warning">
                                    <span class="far fa-edit"></span><span> 編輯</span>
                                </button>
                            </a>
                            <a href="#"><button type="button" data-toggle="tooltip" data-placement="top" title="刪除" class="btn btn-sm btn-outline-danger">
                                    <span class="fas fa-trash-alt"></span><span> 刪除
                                    </span>
                                </button>
                            </a>
                            <a href="#"><button type="button" data-toggle="tooltip" data-placement="top" title="對獎" class="btn btn-sm btn-outline-success">
                                    <span class="fas fa-medal"></span><span> 對獎
                                    </span>
                                </button>
                            </a>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>
        </div>
    </div>
    <div class="page pagination pagination-sm justify-content-center align-items-end mb-0 mb-md-2 mt-lg-2">
        <li class="page-item">
            <a class="page-link" href="#">
                <span aria-hidden="true" class="text-dark fas fa-angle-double-left"></span>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link" href="#">
                <span aria-hidden="true" class="text-dark fas fas fa-angle-left"></span>
            </a>
        </li>
        <li class="page-item">
            <form action="logindex.php" method="get">
                <input type="hidden" name="p" value="<?= $period ?>">
                <select name="pageNow" class="form-select form-select-sm text-dark">
                    <?php
                    for ($i = 1; $i <= $pageCount; $i++) {
                        echo "<option value='$i'>Page-" . $i . "</option>";
                    }
                    ?>
                </select>
            </form>
        </li>
        <li class="page-item">
            <a class="page-link" href="#">
                <span aria-hidden="true" class="text-dark fas fa-angle-right"></span>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link" href="#">
                <span aria-hidden="true" class="text-dark fas fa-angle-double-right"></span>
            </a>
        </li>
    </div>
</div>

<?php
$inv = $pdo->query("select * from invoice_invoices where id='{$_GET['id']}'")->fetch();
?>
<div class="overlay">
    <div class="title bg-danger">
        <p class="text-white">刪除發票</p>
        <a href="?do=invoice_list&y=<?= $year; ?>&p=<?= $period; ?>"><i class="fas fa-times"></i></a>
    </div>
    <div class="edit text-center">
        <p>確定要刪除以下發票？</p>
        <div class="mainedit mb-2">
            <div class="w-100">
                <ul class="list-group">
                    <li class="list-group-item"><?= $inv['code'] . $inv['number']; ?></li>
                    <li class="list-group-item"><?= $inv['date']; ?></li>
                    <li class="list-group-item"><?= $inv['payment']; ?></li>
                </ul>
            </div>
        </div>
        <div class="text-center">
            <a href="?do=del_invoice_check&del=1&id=<?= $_GET['id']; ?>&y=<?= $year; ?>&p=<?= $period; ?>">
                <!-- do=del用來區分是要帶值來刪除頁or刪除資料 -->
                <button class="btn-danger">確認</button>
            </a>
            <a href="?do=invoice_list&y=<?= $year; ?>&p=<?= $period; ?>">
                <button class="btn-warning">取消</button>
            </a>
            <div>
            </div>
        </div>
    </div>
</div>