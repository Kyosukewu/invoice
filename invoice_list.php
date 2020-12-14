<?php
include_once("base.php");
//期別查詢
$get_new = $pdo->query("select * from `invoices` order by date desc,period desc limit 1")->fetch(PDO::FETCH_ASSOC);

$nyear = mb_substr($get_new['date'], 0, 4);
$nperiod = $get_new['period'];
$year =  !empty($_GET['y']) ?  $_GET['y'] : $nyear;
$period = !empty($_GET['p']) ?  $_GET['p'] : $nperiod;

$user=$pdo->query("select * from `invoice_login` where acc='{$_SESSION['login']}'")->fetch();
$user_id=$user['id'];

//資料分頁
$pageSize = 19; //每頁幾條紀錄
if($_SESSION['login'] == "admin"){
    $rowCount = $pdo->query("select COUNT(period) from `invoice_invoices` where date LIKE'$year%' && period='$period'")->fetch(); //共幾條紀錄
}else{
    $rowCount = $pdo->query("select COUNT(period) from `invoice_invoices` where user_id='$user_id' && date LIKE'$year%' && period='$period'")->fetch(); //共幾條紀錄
}
// print_r($rowCount);
$pageNow = 1; //顯示第幾頁
$pageCount = ceil($rowCount[0] / $pageSize); //共多少頁
if (!empty($_GET['pageNow'])) {
    $pageNow = $_GET['pageNow'];
}
$pageStart = ($pageNow - 1) * $pageSize; //目前頁面
if($_SESSION['login'] == "admin"){
    $sql = "select * from `invoice_invoices` where date LIKE'$year%' && period='$period' Order by date desc limit $pageStart,$pageSize";
}else{
    $sql = "select * from `invoice_invoices` where user_id='$user_id' && date LIKE'$year%' && period='$period' Order by date desc limit $pageStart,$pageSize";
}
// $sql="select * from `invoices` order by date desc";

$rows = $pdo->query($sql)->fetchall(PDO::FETCH_ASSOC);

// foreach($rows as $row){
//     echo $row['code']."-".$row['number']."<br>";
// }
$preYear = $year;
$prePeriod = $period - 1;
$nextYear = $year;
$nextPeriod = $period + 1;


if ($prePeriod < 1) {
    $preYear = $year - 1;
    $prePeriod = 6;
}


if ($nextPeriod > 6) {
    $nextYear = $year + 1;
    $nextPeriod = 1;
}
?>
<div class="rightPage h-100 d-flex flex-column justify-content-between">
    <div class="path0">
        <div class="pagination pagination-sm justify-content-center align-items-end mt-lg-2">
            <li class="page-item">
                <a class="page-link" href="?do=check_invoices&y=<?= $preYear ?>&p=<?= $prePeriod; ?>">
                    <span aria-hidden="true" class="text-dark fas fas fa-angle-left"></span>
                </a>
            </li>
            <li class="page-item">
                <form class="d-flex" action="check_invoices.php" method="get">
                    <select name="y" onchange="submit();" class="form-select form-select-sm text-dark">
                        <?php
                        for ($y = $nyear - 1; $y < $nyear + 2; $y++) {
                            if ($y == $year) {
                                echo "<option value='$y' selected>";
                            } else {
                                echo "<option value='$y'>";
                            }
                            echo  $y . "</option>";
                        }
                        ?>
                    </select>
                    <select name="p" onchange="submit();" class="form-select form-select-sm text-dark">
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
                <a class="page-link" href="?do=check_invoices&y=<?= $nextYear ?>&p=<?= $nextPeriod; ?>">
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
            if (!empty($_GET['ex'])) {
            ?>
                <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">本期發票已過兌獎期限！
                    <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php
            }
            if (!empty($_GET['emp'])) {
            ?>
                <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">本期尚未有發票登錄，請由左側表單輸入
                    <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php
            }
            foreach ($rows as $row) {
            ?>
                <div class="invoice d-flex flex-wrap">
                    <div class="t1 text-center"><?= $row['code'] . "-" . $row['number'] ?></div>
                    <div class="t2 text-center  text-secondary"><?= $row['date'] ?></div>
                    <div class="t3 text-center"><?= $row['payment'] ?></div>
                    <div class="t4 text-center d-none d-md-block">
                        <a href="?do=edit_invoice&id=<?= $row['id']; ?>&y=<?= $year; ?>&p=<?= $period; ?>">
                            <button type="button" data-toggle="tooltip" data-placement="top" title="編輯" class="btn btn-sm btn-outline-warning">
                                <p class="far fa-edit"></p>
                            </button>
                        </a>
                        <a href="?do=del_invioce&id=<?= $row['id']; ?>&y=<?= $year; ?>&p=<?= $period; ?>"><button type="button" data-toggle="tooltip" data-placement="top" title="刪除" class="btn btn-sm btn-outline-danger">
                                <p class="fas fa-trash-alt"></p>
                            </button>
                        </a>
                        <a href="?do=award&id=<?= $row['id']; ?>&y=<?= $year; ?>&p=<?= $period; ?>"><button type="button" data-toggle="tooltip" data-placement="top" title="對獎" class="btn btn-sm btn-outline-success">
                                <p class="fas fa-medal"></p>
                            </button>
                        </a>
                    </div>
                    <div class="t5 text-center d-flex align-items-center d-md-none w-100 mb-3">
                        <div class="contral w-100 text-right mt-1 mr-4">
                            <a href="?do=edit_invoice&id=<?= $row['id']; ?>&y=<?= $year; ?>&p=<?= $period; ?>">
                                <button type="button" data-toggle="tooltip" data-placement="top" title="編輯" class="btn btn-sm btn-outline-warning">
                                    <span class="far fa-edit"></span><span> 編輯</span>
                                </button>
                            </a>
                            <a href="?do=del_invioce&id=<?= $row['id']; ?>&y=<?= $year; ?>&p=<?= $period; ?>"><button type="button" data-toggle="tooltip" data-placement="top" title="刪除" class="btn btn-sm btn-outline-danger">
                                    <span class="fas fa-trash-alt"></span><span> 刪除
                                    </span>
                                </button>
                            </a>
                            <a href="?do=award&id=<?= $row['id']; ?>&y=<?= $year; ?>&p=<?= $period; ?>"><button type="button" data-toggle="tooltip" data-placement="top" title="對獎" class="btn btn-sm btn-outline-success">
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
    <?php
    $prePage = $pageNow - 1;

    if ($pageNow < $pageCount) {
        $nextPage = $pageNow + 1;
    } else {
        $nextPage = $pageCount;
    }
    ?>
    <div class="page pagination pagination-sm justify-content-center align-items-end mb-0 mb-md-2 mt-lg-2">
        <li class="page-item">
            <a class="page-link" href="?pageNow=<?= $pageNow = 1; ?>&p=<?= $period; ?>" aria-label="Previous">
                <span aria-hidden="true" class="text-dark fas fa-angle-double-left"></span>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link" href="?pageNow=<?= $prePage; ?>&p=<?= $period; ?>" aria-label="Previous">
                <span aria-hidden="true" class="text-dark fas fas fa-angle-left"></span>
            </a>
        </li>
        <li class="page-item">
            <form action="logindex.php" method="get">
                <input type="hidden" name="y" value="<?= $year ?>">
                <input type="hidden" name="p" value="<?= $period ?>">
                <select name="pageNow" onchange="submit();" class="form-select form-select-sm text-dark">
                    <?php
                    for ($i = 1; $i <= $pageCount; $i++) {
                        if ($_GET['pageNow'] == $i) {
                            echo "<option value='$i' selected>Page-" . $i . "</option>";
                        } else {
                            echo "<option value='$i'>Page-" . $i . "</option>";
                        }
                    }
                    ?>
                </select>
            </form>
        </li>
        <li class="page-item">
            <a class="page-link" href="?pageNow=<?= $nextPage; ?>&p=<?= $period; ?>" aria-label="Next">
                <span aria-hidden="true" class="text-dark fas fa-angle-right"></span>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link" href="?pageNow=<?= $pageNow = $pageCount; ?>&p=<?= $period; ?>" aria-label="Next">
                <span aria-hidden="true" class="text-dark fas fa-angle-double-right"></span>
            </a>
        </li>
    </div>
</div>