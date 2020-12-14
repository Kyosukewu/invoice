<?php
include_once("base.php");
//期別查詢

$year = $_GET['y'];
$period = $_GET['p'];
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
                <form class="d-flex" action="logindex.php" method="get">
                    <select name="y" class="form-select form-select-sm text-dark">
                        <option value="<?= $year-1 ?>"><?= $year-1 ?></option>
                        <option value="<?= $year ?>" selected><?= $year ?></option>
                        <option value="<?= $year+1 ?>"><?= $year+1 ?></option>
                    </select>
                    <select name="p" class="form-select form-select-sm text-dark">
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
            <a class="page-link" href="#"">
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
// include_once("base.php");
if($_SESSION['login'] == "admin"){
    $sql = "select * from invoice_invoices where id='{$_GET['id']}'";
}else{
    $sql = "select * from invoice_invoices where user_id='$user_id' && id='{$_GET['id']}'";
}
$inv = $pdo->query($sql)->fetch();
?>
<div class="overlay">
    <div class="title bg-warning">
        <p class="text-white">編輯發票</p>
        <a href="?do=invoice_list&y=<?=$year;?>&p=<?=$period;?>"><i class="fas fa-times"></i></a>
    </div>
    <form class="edit" action="api/update_invoice.php" method="post">
        <div class="mainedit mb-2">
            <input type="hidden" name="id" value="<?= $inv['id']; ?>">
            <input type="hidden" name="y" value="<?= $year; ?>">
            <input type="hidden" name="p" value="<?= $period; ?>">
            <div class="text col-12">
                <p class="pb-3" >發票號碼：</p>
                <p class="pb-3" >消費日期：</p>
                <p class="pb-3" >消費金額：</p>
            </div>
            <div class="work">
                <input class="i1 mb-2" type="text" name="code" value="<?= $inv['code']; ?>">-<input class="i2" type="number" name="number" value="<?= $inv['number']; ?>">
                <input class="i3 mb-2" type="date" name="date" value="<?= $inv['date']; ?>">
                <input class="i3 mb-2" type="number" name="payment" value="<?= $inv['payment']; ?>">
            </div>
        </div>
        <div class="text-center">
            <input class="btn btn-outline-warning" type="submit" value="修改">
            <input class="btn btn-outline-dark" type="reset" value="重填">
        <div>
    </form>
</div>