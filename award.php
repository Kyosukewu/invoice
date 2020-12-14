<?php
include_once("base.php");
//期別查詢
$get_new = $pdo->query("select * from `invoice_award_numbers` order by year desc,period desc limit 1")->fetch();
$nyear = $get_new['year'];
$nperiod = $get_new['period'];
$year =  !empty($_GET['y']) ? $_GET['y'] : $nyear;
$period = !empty($_GET['p']) ? $_GET['p'] : $nperiod;
$user=$pdo->query("select * from `invoice_login` where acc='{$_SESSION['login']}'")->fetch();
$user_id=$user['id'];
//資料分頁
$pageSize = 19; //每頁幾條紀錄
if($_SESSION['login'] == "admin"){
    $rowCount = $pdo->query("select COUNT(period) from `invoice_invoices` where date LIKE'$year%' && period='$period'")->fetch(); //共幾條紀錄
}else{
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
                <form class="d-flex" action="loglogindex.php" method="get">
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
                <span aria-hidden=" true" class="text-dark fas fas fa-angle-left"></span>
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
    <div class="title bg-success">
        <p class="text-white">發票對獎</p>
        <a href="?do=invoice_list&y=<?= $year; ?>&p=<?= $period; ?>"><i class="fas fa-times"></i></a>
    </div>
    <div class="edit text-center">
        <div class="mainedit mb-2">
            <div class="w-100">
                <ul class="list-group">
                    <li class="list-group-item">發票號碼：<?= $inv['code'] . $inv['number']; ?></li>
                    <!-- <li class="list-group-item"><?= $inv['date']; ?></li>
                    <li class="list-group-item"><?= $inv['payment']; ?></li> -->
                </ul>
            </div>
        </div>
        <div>開獎結果：
            <?php
            // include_once("base.php");
            $inv_id = $_GET['id'];
            $invoice = $pdo->query("select * from invoices where id='$inv_id'")->fetch();
            // echo "<pre>";
            // print_r($invoice);
            // echo "</pre>";
            $number = $invoice['number'];
            //找出獎號
            //**
            //確認期數->依照目前的發票日期做分析
            //得到期數資料後->撈出該期的開獎獎號
            $date = $invoice['date'];
            //explode('-',$date)取日期資料的陣列,陣列的第二個元素即月
            //月份期可推算期數，有期數及年份即可找到開獎號碼
            $year = explode('-', $date)[0];
            $period = ceil(explode('-', $date)[1] / 2);
            $awards = $pdo->query("select * from award_numbers where year='$year' && period='$period'")->fetchALL();

            $all_res = -1;
            $aw="";
            $abonus=[
                "特別獎"=>10000000,
                "特獎"=>2000000,
                "頭獎"=>200000,
                "貳獎"=>40000,
                "參獎"=>10000,
                "肆獎"=>4000,
                "伍獎"=>1000,
                "陸獎"=>200,
            ];
            foreach ($awards as $award) {
                switch ($award['type']) {
                    case 1: //特別號
                        if ($award['number'] == $number) {
                            echo "<div class='h3 text-danger'>中大獎啦！<br>特別獎-獎金壹千萬！！</div>";
                            $all_res = 1;
                            $aw="特別獎";
                            $bonus=$abonus[$aw];
                        }
                        break;
                    case 2: //特獎
                        if ($award['number'] == $number) {
                            echo "<div class='h3 text-danger'>中大獎啦！<br>特獎-獎金貳佰萬！！</div>";
                            $all_res = 1;
                            $aw="特獎";
                            $bonus=$abonus[$aw];
                        }
                        break;
                    case 3: //頭獎
                        $res = -1;
                        for ($i = 5; $i >= 0; $i--) { //從尾數開始對
                            $target = mb_substr($award['number'], $i, (8 - $i), 'utf8');
                            $mynumber = mb_substr($number, $i, (8 - $i), 'utf8');

                            if ($target == $mynumber) {
                                //已中獎等待獎項最終判定
                                $res = $i;
                            } else {
                                break; //continue;
                            }
                        }
                        if ($res != -1) { //判斷最終的獎項
                            echo "<div class='h3 text-danger'>中獎啦！<br>中了{$awardStr[$res]}獎啦~";
                            $all_res = 1;
                            $aw="{$awardStr[$res]}獎";
                            $bonus=$abonus[$aw];
                        }
                        break;
                    case 4: //增開六獎
                        if ($award['number'] == mb_substr($number, 5, 3, 'utf8')) {
                            echo "<div class='h3 text-danger'>中獎啦！<br>增開陸獎-獎金貳佰元</div>";
                            $all_res = 1;
                            $aw="陸獎";
                            $bonus=$abonus[$aw];
                        }
                        break;
                }
            }
            if ($all_res == -1) {
                echo "<div class='h5 text-dark'>可惜不是你~ 再接再厲吧</div>";
            }

            // insert into invoices (`date`,`period`,`code`,`number`,`payment`) values('2020-12-02','1','SG','0267817448','40')

            $check=$pdo->query("select * from `reward_record` where id='$inv_id'")->fetch();
            //資料寫入
            if($all_res>=0 && empty($check)){
                    $sql="insert into `reward_record` (`inid`,`user_id`,`code`,`number`,`period`,`payment`,`date`,`reward`,`bonus`) values ('{$invoice['id']}','{$invoice['user_id']}','{$invoice['code']}','{$invoice['number']}','{$invoice['period']}','{$invoice['payment']}','{$invoice['date']}','$aw','$bonus')";
                    $pdo->exec($sql);
                }

            ?>
        </div>
        <div class="text-center mt-2">
            <a href="?do=invoice_list&y=<?= $year; ?>&p=<?= $period; ?>">
                <button class="btn-success">確認</button>
            </a>
            <div>
            </div>
        </div>