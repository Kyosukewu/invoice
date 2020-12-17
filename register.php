<?php
include_once("base.php")
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-CuOF+2SnTUfTwSZjCXf01h7uYhfOBuxIhGKPbfEJ3+FqH/s6cIFN9bGr1HmAg4fQ" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0-alpha3/dist/js/bootstrap.bundle.min.js" integrity="sha384-popRpmFF9JQgExhfw5tZT4I9/CI5e2QcuUZPOVXb1m7qUmeR2b50u+YFEYe1wgzy" crossorigin="anonymous"></script>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Noto+Sans+TC&display=swap" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a1381bb91e.js" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="css/style.css">
    <title>統一發票紀錄及對獎系統</title>
</head>

<body>
    <div class="bg">
        <img class="bg1" src="https://i.postimg.cc/WbXLXfFs/bg1.png" alt="">
        <img class="bg2" src="https://i.postimg.cc/9MNNw1DB/bg3.png" alt="">
        <img class="bg3" src="https://i.postimg.cc/hvkypjbz/bg4.png" alt="">
        <img class="bg4" src="https://i.postimg.cc/x8JpvHHV/bg5.png" alt="">
    </div>
    <div class="container">
        <div class="bookindex">
            <?php
            include_once "base.php";
            if (isset($_SESSION['login'])) {
                $sql_user = "select `member`.`role`,`login`.`acc` from invoice_member,invoice_login where `member`.`login_id`=`login`.`id` &&  acc='{$_SESSION['login']}'";
                $user = $pdo->query($sql_user)->fetch(PDO::FETCH_ASSOC);
            }
            ?>
            <div class="login text-center">
                <form action="check.php" class="" method="post">
                    <div class="text-light mb-2 mb-md-3">帳號：<input type="text" name="acc" class="inp"></div>
                    <div class="text-light">密碼：<input type="password" name="pw" class="inp"></div>
                    <small class="text-center text-danger"><?php if (isset($_GET['meg'])) {
                                                                echo $_GET['meg'];
                                                            } ?></small>
                    <div class="fog my-md-2">
                        <a href="forget_pw.php"><small>忘記密碼?</small></a>
                        <a href="register.php" class="ml-2"><small>註冊新帳號</small></a>
                    </div>
                    <p class="text-center mt-1"><input class="btn btn-outline-light" type="submit" value="登入"><a href="visitor.php" class="btn btn-outline-light ml-3">訪客模式</a></p>
                </form>
            </div>
        </div>
    </div>

    <div class="overreg">
        <div class="title bg-dark">
            <p class="text-white">註冊帳號</p>
            <a href="index.php"><i class="fas fa-times"></i></a>
        </div>
        <div class="edit2 text-center">
            <form action="add_user.php" method="post">
                <div class="mainedit mb-2">
                    <div class="w-100">
                        <ul class="list-group">
                            <li class="list-group-item">帳號:<input type="text" name='acc' required></li>
                            <li class="list-group-item">密碼:<input type="password" name='pw' required></li>
                            <li class="list-group-item">生日:<input type="date" name='birthday' required></li>
                            <li class="list-group-item">地址:<input type="text" name='addr' required></li>
                            <li class="list-group-item">Email:<input type="email" name='email' required></li>
                            <li class="list-group-item">學歷:
                                <select name="education" id="">
                                    <option value="國中">國中</option>
                                    <option value="高中">高中</option>
                                    <option value="大學/專科">大學/專科</option>
                                    <option value="碩士">碩士</option>
                                    <option value="博士">博士</option>
                                </select>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="text-center">
                    <input type="submit" value="確認新增" class="btn btn-sm btn-outline-primary my-3">
                    <input type="reset" value="重置" class="btn btn-sm btn-outline-warning my-3">
                    <div>
                    </div>
                </div>
        </div>
    </div>
</body>

</html>
<?php $_SESSION['err'] = []; ?>