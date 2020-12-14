<?php
include_once("base.php");
//期別查詢
$user = $pdo->query("select * from `invoice_login` where acc='{$_SESSION['login']}'")->fetch();
$user_id = $user['id'];

$get_new = $pdo->query("select * from `invoice_reward_record` order by date desc,period desc limit 1")->fetch(PDO::FETCH_ASSOC);

$nyear = mb_substr($get_new['date'], 0, 4);
$nperiod = $get_new['period'];
$year =  !empty($_GET['y']) ?  $_GET['y'] : $nyear;
$period = !empty($_GET['p']) ?  $_GET['p'] : $nperiod;


//資料分頁
$pageSize = 19; //每頁幾條紀錄

if ($_SESSION['login'] == "admin") {
    $rowCount = $pdo->query("select COUNT(period) from `invoice_reward_record` where date LIKE'$year%' && period='$period'")->fetch(); //共幾條紀錄
} else {
    $rowCount = $pdo->query("select COUNT(period) from `invoice_reward_record` where user_id='$user_id' &&  date LIKE'$year%' && period='$period'")->fetch();
}
$pn = 1; //顯示第幾頁
$pageCount = ceil($rowCount[0] / $pageSize); //共多少頁
if (!empty($_GET['pn'])) {
    $pn = $_GET['pn'];
}
$pageStart = ($pn - 1) * $pageSize; //目前頁面


//取得當期資料
if ($_SESSION['login'] == "admin") {
    $sql = "select * from `invoice_reward_record` where date LIKE'$year%' && period='$period' Order by date desc limit $pageStart,$pageSize";
} else {
    $sql = "select * from `invoice_reward_record` where user_id='$user_id' &&  date LIKE'$year%' && period='$period' Order by date desc limit $pageStart,$pageSize";
}


$rows = $pdo->query($sql)->fetchall(PDO::FETCH_ASSOC);

if ($_SESSION['login'] == "admin") {
    //取得單期中獎發票總數
    $rea = $pdo->query("select COUNT(id) from `invoice_reward_record` where date LIKE'$year%' && period='$period'")->fetch(PDO::FETCH_ASSOC);
    //取得單期中獎總獎金
    $boa = $pdo->query("select SUM(bonus) from `invoice_reward_record` where date LIKE'$year%' && period='$period'")->fetch(PDO::FETCH_ASSOC);
    //取得總計獎金
    $bonusCountAll = $pdo->query("select SUM(bonus) as bonusAll from `invoice_reward_record` where date LIKE'$year%'")->fetch(PDO::FETCH_ASSOC);
    //取得總計發票數量
    $invoiceAll = $pdo->query("SELECT COUNT(id) FROM `invoice_invoices` WHERE date LIKE'$year%'")->fetch(PDO::FETCH_ASSOC);
    //取得總消費金額
    $pay = $pdo->query("select SUM(payment) as paymentAll from `invoice_reward_record` where date LIKE'$year%'")->fetchColumn();
    $invoices = $pdo->query("select * from `invoice_invoices` where left(date,4)='{$year}' && period='{$period}' Order by date")->fetchALL(PDO::FETCH_ASSOC);
} else {
    //取得單期中獎發票總數
    $rea = $pdo->query("select COUNT(id) from `invoice_reward_record` where user_id='$user_id' &&  date LIKE'$year%' && period='$period'")->fetch(PDO::FETCH_ASSOC);
    //取得單期中獎總獎金
    $boa = $pdo->query("select SUM(bonus) from `invoice_reward_record` where user_id='$user_id' &&  date LIKE'$year%' && period='$period'")->fetch(PDO::FETCH_ASSOC);
    //取得總計獎金
    $bonusCountAll = $pdo->query("select SUM(bonus) as bonusAll from `invoice_reward_record` where user_id='$user_id' &&  date LIKE'$year%'")->fetch(PDO::FETCH_ASSOC);
    //取得總計發票數量
    $invoiceAll = $pdo->query("SELECT COUNT(id) FROM `invoice_invoices` WHERE user_id='$user_id' &&  date LIKE'$year%'")->fetch(PDO::FETCH_ASSOC);
    //取得總消費金額
    $pay = $pdo->query("select SUM(payment) as paymentAll from `invoice_reward_record` where user_id='$user_id' &&  date LIKE'$year%'")->fetchColumn();
    $invoices = $pdo->query("select * from `invoice_invoices` where user_id='$user_id' &&  left(date,4)='{$year}' && period='{$period}' Order by date")->fetchALL(PDO::FETCH_ASSOC);
}



$bc = $boa['SUM(bonus)']; //單期中獎獎金
$rca = $rea['COUNT(id)']; //單期中獎發票總數
$bca = $bonusCountAll['bonusAll']; //年度總計獎金
$inva = $invoiceAll['COUNT(id)']; //年度總計發票數;
$periodCount = count($invoices); //單期發發票總數


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
    <div class="path">
        <div class="pagination pagination-sm justify-content-center align-items-end mt-lg-2">
            <li class="page-item">
                <a class="page-link" href="?do=check_reward&y=<?= $preYear ?>&p=<?= $prePeriod; ?>">
                    <span aria-hidden="true" class="text-dark fas fas fa-angle-left"></span>
                </a>
            </li>
            <li class="page-item">
                <form class="d-flex" action="check_reward.php" method="get">
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
                <a class="page-link" href="?do=check_reward&y=<?= $nextYear ?>&p=<?= $nextPeriod; ?>">
                    <span aria-hidden="true" class="text-dark fas fa-angle-right"></span>
                </a>
            </li>
        </div>
    </div>
    <?php
    if ($rca > 0) {
        $win = floor((($rca / $periodCount) * 1000)) / 1000;
    } else {
        $win = 0;
    }
    if ($rca > 0) {
        $win2 = floor((($bca / $pay) * 1000)) / 1000;
    } else {
        $win2 = 0;
    }
    ?>
    <div class="inv">
        <div class="total col-12 col-md-11 mx-auto pb-2">
            <div class="col-12 d-flex justify-content-between"><span>本期發票總數：</span><span><?= $periodCount ?></span>張</div>
            <div class="col-12 d-flex justify-content-between"><span>本期中獎發票：</span><span><?= $rca; ?></span>張</div>
            <div class="col-12 d-flex justify-content-between"><span>本期中獎獎金：</span><span class="text-danger"><?= $bc; ?></span>元</div>
            <div class="float-right"><small class="text-danger">(中獎率：<?= $win; ?>%)</small></div>
        </div>
        <?php
        if (!empty($_GET['tip'])) {
        ?>
            <div class="alert alert-warning alert-dismissible fade show text-center" role="alert">發票已過兌獎期限囉~
                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
        }
        if (!empty($_GET['empty'])) {
        ?>
            <div class="alert alert-danger alert-dismissible fade show text-center" role="alert">殘念，本期發票未有中獎發票
                <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
            </div>
        <?php
        }
        ?>
        <div class="tit d-flex w-100 py-2 my-3 border-top">
            <div class="col-5 col-md-3 text-center">發票號碼</div>
            <div class="col-4 col-md-3 text-center">消費日期</div>
            <div class="col-3 col-md-2 text-center">金額</div>
            <div class="col-md-2 text-center d-none d-md-block">獎項</div>
            <div class="col-md-2 text-center d-none d-md-block">獎金</div>
        </div>
        <div class="rewards">
            <?php
            foreach ($rows as $row) {
            ?>
                <div class="reward d-flex flex-wrap">
                    <div class="col-5 col-md-3 text-center"><?= $row['code'] . "-" . $row['number'] ?></div>
                    <div class="col-4 col-md-3 text-center  text-secondary"><?= $row['date'] ?></div>
                    <div class="col-3 col-md-2 text-center"><?= $row['payment'] ?>元</div>
                    <div class="text-center col-4 col-md-2">-<?= $row['reward'] ?>-</div>
                    <div class="text-right col-4 d-block d-md-none mb-4">獎金：</div>
                    <div class="text-center col-4 col-md-2"><?= $row['bonus'] ?>元</div>
                </div>
            <?php
            }
            ?>
        </div>
        <div class="total col-12 col-md-11 mx-auto pt-2">
            <div class="col-12  d-flex justify-content-between border-top pt-2"><span>年度累計發票：</span><span><?= $inva; ?></span>張</div>
            <div class="col-12  d-flex justify-content-between"><span>年度累計獎金：</span><span class="text-danger"><?= $bca ?></span>元</div>
            <div class="float-right"><small class="text-danger">(報酬率：<?= $win2; ?>%)</small></div>
        </div>
    </div>
    <?php
    $prePage = $pn - 1;

    if ($pn < $pageCount) {
        $nextPage = $pn + 1;
    } else {
        $nextPage = $pageCount;
    }
    ?>
    <div class="page pagination pagination-sm justify-content-center align-items-end mb-0 mb-md-2 mt-lg-2">
        <li class="page-item">
            <a class="page-link" href="?do=reward_record&pn=<?= $pn = 1; ?>&p=<?= $period; ?>" aria-label="Previous">
                <span aria-hidden="true" class="text-dark fas fa-angle-double-left"></span>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link" href="?do=reward_record&pn=<?= $prePage; ?>&p=<?= $period; ?>" aria-label="Previous">
                <span aria-hidden="true" class="text-dark fas fas fa-angle-left"></span>
            </a>
        </li>
        <li class="page-item">
            <form action="logindex.php" method="get">
                <input type="hidden" name="y" value="<?= $year ?>">
                <input type="hidden" name="p" value="<?= $period ?>">
                <select name="pn" onchange="submit();" class="form-select form-select-sm text-dark">
                    <?php
                    for ($i = 1; $i <= $pageCount; $i++) {
                        if ($_GET['pn'] == $i) {
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
            <a class="page-link" href="?do=reward_record&pn=<?= $nextPage; ?>&p=<?= $period; ?>" aria-label="Next">
                <span aria-hidden="true" class="text-dark fas fa-angle-right"></span>
            </a>
        </li>
        <li class="page-item">
            <a class="page-link" href="?do=reward_record&pn=<?= $pn = $pageCount; ?>&p=<?= $period; ?>" aria-label="Next">
                <span aria-hidden="true" class="text-dark fas fa-angle-double-right"></span>
            </a>
        </li>
    </div>
</div>