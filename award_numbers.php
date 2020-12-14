<?php
include_once("base.php");
$get_new = $pdo->query("select * from `invoice_award_numbers` order by year desc,period desc limit 1")->fetch();
$nyear = $get_new['year'];
$nperiod = $get_new['period'];
$year =  !empty($_GET['y']) ? $_GET['y'] : $nyear;
$period = !empty($_GET['p']) ? $_GET['p'] : $nperiod;
$awards = $pdo->query("select * from invoice_award_numbers where year='$year' && period='$period'")->fetchALL();
$special = "";
$grand = "";
$first = [];
$six = [];
foreach ($awards as $aw) {
    switch ($aw['type']) {
        case 1:
            $special = $aw['number'];
            break;
        case 2:
            $grand = $aw['number'];
            break;
        case 3:
            $first[] = $aw['number'];
            break;
        case 4:
            $six[] = $aw['number'];
            break;
    }
}
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
<div class="row h-100">
    <div class="path">
        <div class="pagination pagination-sm justify-content-center align-items-end mt-lg-2">
            <li class="page-item">
                <a class="page-link" href="?do=api/check_awards&y=<?=$preYear?>&p=<?= $prePeriod; ?>">
                    <span aria-hidden="true" class="text-dark fas fas fa-angle-left"></span>
                </a>
            </li>
            <li class="page-item">
                <form class="d-flex" action="logindex.php" method="get">
                    <input type="hidden" name="do" value="api/check_awards">
                    <select name="y" onchange="submit();" class="form-select form-select-sm text-dark">
                        <?php
                        for($y=$nyear-1;$y<$nyear+2;$y++){
                            if ($y == $year) {
                                echo "<option value='$y' selected>";
                            } else {
                                echo "<option value='$y'>";
                            }
                            echo  $y. "</option>";
                        }
                        ?>
                    </select>
                    <select name="p" onchange="submit();" class="form-select form-select-sm text-dark">
                        <?php
                        for ($i = 1; $i < 7; $i++) {
                            if($i==$period){
                                echo "<option value='$i' selected>";
                            }else{
                                echo "<option value='$i'>";
                            }
                            echo "$month[$i]" . "</option>";
                        }
                        ?>
                    </select>
                </form>
            </li>
            <li class="page-item">
                <a class="page-link" href="?do=api/check_awards&y=<?=$nextYear?>&p=<?= $nextPeriod; ?>">
                    <span aria-hidden="true" class="text-dark fas fa-angle-right"></span>
                </a>
            </li>
        </div>
    </div>
    <?php
    if (!empty($_GET['aemp'])) {
    ?>
        <div class="alert alert-danger alert-dismissible fade show text-center position-absolute top-50" role="alert">本期發票尚未開獎，敬請期待！
            <button type="button" class="btn-close" data-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php
    }
    ?>
    <div class="table-container">
        <table class="table table-sm col-12">
            <tbody>
                <tr>
                    <td class="md text-center col-3" rowspan="2">特別獎</td>
                    <td class="pl-md-3">
                        <p class="d-block d-lg-none mb-0">特別獎</p>
                        <p>同期統一發票收執聯<span class="text-danger">8位數號碼</span>與特別獎號碼相同者獎金1,000萬元</p>
                        <p class="d-block d-lg-none mb-0"><?= $special; ?></p>
                    </td>
                </tr>
                <tr>
                    <td class="md pl-md-3">
                        <?= $special; ?>
                    </td>
                </tr>
                <tr>
                    <td class="md text-center col-3" rowspan="2">特獎</td>
                    <td class="pl-md-3">
                        <p class="d-block d-lg-none mb-0">特獎</p>
                        <p>同期統一發票收執聯<span class="text-danger">8位數號碼</span>與特獎號碼相同者獎金200萬元</p>
                        <p class="d-block d-lg-none mb-0"><?= $grand; ?></p>
                    </td>
                </tr>
                <tr>
                    <td class="md pl-md-3">
                        <?= $grand; ?>
                    </td>
                </tr>
                <tr>
                    <td class="md  text-center col-3" rowspan="2">頭獎</td>
                    <td class="pl-md-3">
                        <p class="d-block d-lg-none mb-0">頭獎</p>
                        <p>同期統一發票收執聯<span class="text-danger">8位數號碼</span>與頭獎號碼相同者獎金20萬元</p>
                        <p class="d-block d-lg-none mb-0">
                            <?php
                            foreach ($first as $f) {
                                echo $f . "<br>";
                            }
                            ?>
                        </p>
                    </td>
                </tr>
                <tr>
                    <td class="md pl-md-3 mb-0">
                        <?php
                        foreach ($first as $f) {
                            echo $f . "<br>";
                        }
                        ?>
                    </td>
                </tr>
                <tr>
                    <td class="md text-center col-3">二獎</td>
                    <td class="pl-md-3">
                        <p class="d-block d-lg-none mb-0">二獎</p>
                        <p>同期統一發票收執聯末7 位數號碼與頭獎中獎號碼<span class="text-danger">末7位</span>相同者各得獎金4萬元</p>
                    </td>
                </tr>
                <tr>
                    <td class="md  text-center col-3">三獎</td>
                    <td class="pl-md-3">
                        <p class="d-block d-lg-none mb-0">三獎</p>
                        <p>同期統一發票收執聯末6 位數號碼與頭獎中獎號碼<span class="text-danger">末6位</span>相同者各得獎金1萬元</p>
                    </td>
                </tr>
                <tr>
                    <td class="md text-center col-3">四獎</td>
                    <td class="pl-md-3">
                        <p class="d-block d-lg-none mb-0">四獎</p>
                        <p>同期統一發票收執聯末5 位數號碼與頭獎中獎號碼<span class="text-danger">末5位</span>相同者各得獎金4千元</p>
                    </td>
                </tr>
                <tr>
                    <td class="md text-center col-3">五獎</td>
                    <td class="pl-md-3">
                        <p class="d-block d-lg-none mb-0">五獎</p>
                        <p>同期統一發票收執聯末4 位數號碼與頭獎中獎號碼<span class="text-danger">末4位</span>相同者各得獎金1千元</p>
                    </td>
                </tr>
                <tr>
                    <td class="md text-center col-3">六獎</td>
                    <td class="pl-md-3">
                        <p class="d-block d-lg-none mb-0">六獎</p>
                        <p>同期統一發票收執聯末3 位數號碼與頭獎中獎號碼<span class="text-danger">末3位</span>相同者各得獎金2百元</p>
                    </td>
                </tr>
                <tr>
                    <td class="md text-center col-3">增開六獎</td>
                    <td class="pl-md-3">
                        <p class="d-block d-lg-none mb-0">增開六獎</p>
                        <p>
                            <?php
                            foreach ($six as $s) {
                                echo $s . "&nbsp&nbsp&nbsp";
                            }
                            ?>
                        </p>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
    <div class="aw text-center">
        <a href="?do=all_awards&y=<?= $year ?>&p=<?= $period ?>">
            <button class="btn btn-outline-success">對獎</button></a>
    </div>
</div>