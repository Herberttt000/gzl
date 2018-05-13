<?php

include "config.php";
//$sql = "select * from `User` where `name` = ". $_POST['username'] . ";";
$_POST['username'] = mysql_real_escape_string($_POST['username']);
$result = mysql_query("select * from `users` where `username` = \"".$_POST['username']."\";");
if (!$result){
      die('select error: ' . mysql_error());
}
$rs= mysql_fetch_array($result);

if($rs['password']==md5($_POST['password'])) {
    $_SESSION['rank'] = $rs['rank'];
    $_SESSION['username'] = $_POST['username'];
    $_SESSION['name'] = $rs['name'];
    $_SESSION['xueyuan'] = $rs['xueyuan'];
    $_SESSION['import_jiaowu'] = $rs['import_jiaowu'];
    $_SESSION['import_qita'] = $rs['import_qita'];
    $_SESSION['import_jingsai'] = $rs['import_jingsai'];
    $_SESSION['import_chengren'] = $rs['import_chengren'];
    $_SESSION['import_yanjiusheng'] = $rs['import_yanjiusheng'];
    $_SESSION['import_shiyan'] = $rs['import_shiyan'];
    $_SESSION['import_qiankao'] = $rs['import_qiankao'];
    $_SESSION['import_teachers'] = $rs['import_teachers'];
    $_SESSION['import_banji'] = $rs['import_banji'];
    $_SESSION['import_lilun'] = $rs['import_lilun'];
    $_SESSION['import_shijian'] = $rs['import_shijian'];
    $_SESSION['import_something'] = $rs['import_something'];
    $_SESSION['export_renshichu'] = $rs['export_renshichu'];
    $_SESSION['export_geren'] = $rs['export_geren'];
    $_SESSION['modify_jisuanxishu'] = $rs['modify_jisuanxishu'];
    $_SESSION['modify_zcxishu'] = $rs['modify_zcxishu'];
    $_SESSION['modify_data'] = $rs['modify_data'];
    $_SESSION['calc_lilun'] = $rs['calc_lilun'];
    $_SESSION['calc_shijian'] = $rs['calc_shijian'];
    $_SESSION['calc_tiyu'] = $rs['calc_tiyu'];
    $_SESSION['find_gzl'] = $rs['find_gzl'];
    $_SESSION['modify_users'] = $rs['modify_users'];
    $_SESSION['modify_xitong'] = $rs['modify_xitong'];


    header("location: ./index.php");
} else {
        header("location: ./error.php?txt="."用户名或密码错误！");
        exit();
}


?>