<?php
include_once("base.php");
$get_new = $pdo->query("select * from `award_numbers` order by year desc,period desc limit 1")->fetch();
$nyear = $get_new['year'];
$nperiod = $get_new['period'];
$year =  !empty($_GET['y']) ? $_GET['y'] : $nyear;
$period = !empty($_GET['p']) ? $_GET['p'] : $nperiod;

$user=$pdo->query("select * from `invoice_login` where acc='{$_SESSION['login']}'")->fetch();
$user_id=$user['id'];

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

?>
<div class="row h-100">
    <div class="path">
        <div class="pagination pagination-sm justify-content-center align-items-end mt-lg-2">
            <li class="page-item">
                <a class="page-link" href="">
                    <span aria-hidden="true" class="text-dark fas fas fa-angle-left"></span>
                </a>
            </li>
            <li class="page-item">
                <form class="d-flex" action="logindex.php" method="get">
                    <input type="hidden" name="do" value="api/check_awards">
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
                <a class="page-link" href="">
                    <span aria-hidden="true" class="text-dark fas fa-angle-right"></span>
                </a>
            </li>
        </div>
    </div>
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
        <a href="">
            <button class="btn btn-outline-success">對獎</button></a>
    </div>
</div>
<!-- 以上假資料區 -->
<?php
$period_str = [
    1 => '1,2月',
    2 => '3,4月',
    3 => '5,6月',
    4 => '7,8月',
    5 => '9,10月',
    6 => '11,12月'
];
?>
<div class="overlay">
    <div class="title bg-success">
        <p class="text-white"><?= $year . "年，" . $period_str[$_GET['p']] . "發票對獎" ?></p>
        <a href="?do=award_numbers&y=<?= $year; ?>&p=<?= $period; ?>"><i class="fas fa-times"></i></a>
    </div>
    <div class="edit text-center h-100">
        <div class="mainedit">
        </div>
        <div class="h-100 overflow-auto">開獎結果：
            <?php
            //撈出該期發票
            if($_SESSION['login'] == "admin"){
                $sql = "select * from `invoice_invoices` where left(date,4)='{$_GET['y']}' && period='{$_GET['p']}' Order by date";
            }else{
                $sql = "select * from `invoice_invoices` where user_id='$user_id' && left(date,4)='{$_GET['y']}' && period='{$_GET['p']}' Order by date";
            }
            $invoices = $pdo->query($sql)->fetchALL(PDO::FETCH_ASSOC);
            
            //撈出該期獎號
            $sql = "select * from `invoice_award_numbers` where year='{$_GET['y']}' && period='{$_GET['p']}'Order by type";
            $award_numbers = $pdo->query($sql)->fetchALL(PDO::FETCH_ASSOC);
            //PDO::FETCH_ASSOC 以欄位的方式取得資料
            //PDO::FETCH_NUM 取索引

            //開始對獎
            $all_res = -1;
            $count_res = 0;
            $count_award = 0;
            $aw = "";
            $abonus = [
                "特別獎" => 10000000,
                "特獎" => 2000000,
                "頭獎" => 200000,
                "貳獎" => 40000,
                "參獎" => 10000,
                "肆獎" => 4000,
                "伍獎" => 1000,
                "陸獎" => 200,
            ];
            foreach ($invoices as $inv) {
                $number = $inv['number'];
                $date = $inv['date'];
                foreach ($award_numbers as $award) {
                    switch ($award['type']) {
                        case 1: //特別獎
                            if ($award['number'] == $number) {
                                echo "<br>發票號碼：" . $number . "<br>";
                                echo "<br>中了特別獎<br>";
                                $all_res = 1;
                                $count_res = $count_res + 1;
                                $count_award = $count_award + 10000000;
                                $aw = "特別獎";
                                $bonus = $abonus[$aw];
                                $reinv=$pdo->query("select * from invoice_invoices where id='{$inv['id']}'")->fetch(PDO::FETCH_ASSOC);
                                array_push($reinv,$aw,$bonus);
                                $reinvs[]=$reinv;
                                // print_r($reinvs);
                            }
                            break;
                        case 2: //特獎          
                            if ($award['number'] == $number) {
                                echo "<br>發票號碼：" . $number . "<br>";
                                echo "中了特獎<br>";
                                $all_res = 1;
                                $count_res = $count_res + 1;
                                $count_award = $count_award + 2000000;
                                $aw = "特獎";
                                $bonus = $abonus[$aw];
                                $reinv=$pdo->query("select * from invoice_invoices where id='{$inv['id']}'")->fetch(PDO::FETCH_ASSOC);
                                array_push($reinv,$aw,$bonus);
                                $reinvs[]=$reinv;
                                // print_r($reinvs);
                            }
                            break;
                        case 3: //頭獎
                            $res = -1;
                            for ($i = 5; $i >= 0; $i--) {
                                $target = mb_substr($award['number'], $i, (8 - $i), 'utf8');
                                $mynumber = mb_substr($number, $i, (8 - $i), 'utf8');
                                if ($target == $mynumber) {
                                    $res = $i;
                                } else {
                                    break;
                                    //continue
                                }
                            }
                            //判斷最後中的獎項
                            if ($res != -1) {
                                echo "<br>發票號碼：" . $number . "<br>";
                                echo "中了{$awardStr[$res]}獎<br>";
                                $all_res = 1;
                                $aw = "{$awardStr[$res]}獎";
                                $bonus = $abonus[$aw];
                                $reinv=$pdo->query("select * from invoice_invoices where id='{$inv['id']}'")->fetch(PDO::FETCH_ASSOC);
                                array_push($reinv,$aw,$bonus);
                                $reinvs[]=$reinv;
                                // print_r($reinvs);
                                $count_res = $count_res + 1;
                                
                                switch ($awardStr[$res]) {
                                    case "頭":
                                        $count_award = $count_award + 200000;
                                        break;
                                    case "貳":
                                        $count_award = $count_award + 40000;
                                        break;
                                    case "參":
                                        $count_award = $count_award + 10000;
                                        break;
                                    case "肆":
                                        $count_award = $count_award + 4000;
                                        break;
                                    case "伍":
                                        $count_award = $count_award + 1000;
                                        break;
                                    case "陸":
                                        $count_award = $count_award + 200;
                                        break;
                                }
                            }
                            break;
                        case 4: //增開六獎
                            if ($award['number'] == mb_substr($number, 5, 3, 'utf8')) {
                                echo "<br>發票號碼：" . $number . "<br>";
                                $all_res = 1;
                                $count_res = $count_res + 1;
                                $count_award = $count_award + 200;
                                $aw = "陸獎";
                                $bonus = $abonus[$aw];
                                $reinv=$pdo->query("select * from invoice_invoices where id='{$inv['id']}'")->fetch(PDO::FETCH_ASSOC);
                                array_push($reinv,$aw,$bonus);
                                $reinvs[]=$reinv;
                                // print_r($reinvs);
                                echo "中了增開六獎<br>";
                            }
                            break;
                    }
                }
            }
            if ($all_res == -1) {
                echo "<br>可惜不是你,再接再厲吧<br>";
            }
            echo "<br>總計" . count($invoices) . "張發票<br>";
            echo "中獎<span class='text-danger'>" . $count_res . "</span>張發票，共計獎金<span class='text-danger'>" . $count_award . "</span>元";
            
            if($all_res >= 0){
            foreach($reinvs as $reinv){
                $check=$pdo->query("select * from `invoice_reward_record` where inid='{$reinv['id']}'")->fetch();
                if(empty($check)){
                    $sql="insert into `invoice_reward_record` (`inid`,`user_id`,`code`,`number`,`period`,`payment`,`date`,`reward`,`bonus`) values ('{$reinv['id']}','{$reinv['user_id']}','{$reinv['code']}','{$reinv['number']}','{$reinv['period']}','{$reinv['payment']}','{$reinv['date']}','{$reinv['0']}','{$reinv['1']}')";
                    $pdo->exec($sql);
                }
            }
        }
            ?>
        </div>
        <div class="text-center mt-2">
            <a href="?do=award_numbers&y=<?= $year; ?>&p=<?= $period; ?>">
                <button class="btn btn-success">確認</button>
            </a>
            <div>
            </div>
        </div>