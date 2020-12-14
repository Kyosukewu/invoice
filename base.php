<?php

$dsn = "mysql:host=localhost;dbname=s1090421;charset=utf8";
$pdo = new PDO($dsn, 's1090421', 's1090421');

date_default_timezone_set("Asia/Taipei");
session_start();

$awardStr = ['頭', '貳', '參', '肆', '伍', '陸',];
$month = [
    1 => "1-2月",
    2 => "3-4月",
    3 => "5-6月",
    4 => "7-8月",
    5 => "9-10月",
    6 => "11-12月",
];
//欄位檢查
function accept($field, $meg = '此欄位不得為空')
{
    if (empty($_POST[$field])) {
        $_SESSION['err'][$field]['empty'] = $meg;
    }
}

function length($field, $min, $max, $meg = "長度不足")
{
    if (strlen($_POST[$field]) > $max || strlen($_POST[$field]) < $min) {
        $_SESSION['err'][$field]['len'] = $meg;
    }
}

function email($field, $meg = 'email格式錯誤')
{
    $email = $_POST[$field];
    echo mb_strpos($email, '@');
    if (mb_strpos($email, '@') == false) {
        $_SESSION['err'][$field]['email'] = $meg;
    }
}
//錯誤回報
function errFeedBack($field)
{
    if (!empty($_SESSION['err'][$field])) {

        foreach ($_SESSION['err'][$field] as $err) {
            echo "<div style='font-size:12px;color:red'>";
            echo $err;
            echo "</div>";
        }
    }
}

function all($table,...$arg){
    global $pdo;
    $sql="select * from $table ";

    if(isset($arg[0])){
    
        if(is_array($arg[0])){
            //製作會在 where 後面的句子字串(陣列格式)
                foreach($arg[0] as $key => $value){
                    $tmp[]=sprintf("`%s`='%s'",$key,$value);
                }

                $sql=$sql." where ".implode(' && ',$tmp);
            
        }else{
            //製作非陣列的語句接在$sql後面
                $sql=$sql.$arg[0];       
        }
    }
    if(isset($arg[1])){
        $sql=$sql.$arg[1];
    }
    // echo $sql."<br>";
    return $pdo->query($sql)->fetchAll(); 
}

function find($table,$id){
    global $pdo;
    $sql="select * from $table where ";
    if(is_array($id)){
        foreach($id as $key => $value){
            $tmp[]=sprintf("`%s`='%s'",$key,$value);
            //$tmp[]="`".$key."`='".$value."'";
        }
        $sql=$sql.implode(' && ',$tmp);
    }else{
        $sql=$sql . " id='$id' ";
    }
    $row=$pdo->query($sql)->fetch(PDO::FETCH_ASSOC);
    //mysqli_fetch_assoc()
    return $row;
}


function del($table, $id)
{
    global $pdo;
    $sql = "delete from $table where ";
    if (is_array($id)) {
        foreach ($id as $key => $value) {
            $tmp[] = sprintf("`%s`='%s'", $key, $value);
        }
        $sql = $sql . implode(' && ', $tmp);
    } else {
        $sql = $sql . " id='$id' ";
    }

    $row=$pdo->exec($sql);
    return $row;
}

function update($table,$array){
    global $pdo;
    $sql="update $table set ";
    foreach($array as $key => $value){
        if($key!='id'){

            $tmp[]=sprintf("`%s`='%s'",$key,$value);
        }
        //$tmp[]="`".$key."`='".$value."'";
    }
    $sql=$sql.implode(",",$tmp) . " where `id`='{$array['id']}'";
    echo $sql;
    $pdo->exec($sql);
}

function insert($table,$array){
    global $pdo;
    $sql="insert into $table(`" . implode("`,`",array_keys($array)) . "`) values('".implode("','",$array)."')";


    $pdo->exec($sql);
}

function save($table,$array){
        if(isset($array['id'])){
            //update
            update($table,$array);
        }else{
            //insert
            insert($table,$array);
        }
}


function to($url){
    header("location:".$url);
}

function q($sql){
    global $pdo;
    return $pdo->query($sql)->fetchALL();
}
?>