<?php

    # 用户管理模块 

    # 导入配置
include dirname(__FILE__) . './../config.php';
include dirname(__FILE__) . './../functions.php';
error_reporting(E_ALL);
    # 判断是否登录
if(!isset($_SESSION['username'])) {
    header("location: ./error.php?txt="."请登录后再操作.");
    exit();
}

    # 检验权限
if(!$_SESSION['modify_users']) {
    header("location: ./error.php?txt="."您没有修改用户的权限.");
    exit();
}


# 错误信息
$error;
if(!isset($_POST['import_teachers'])) {
    $error .= "<br>你没有选择是否给予“导入教师信息”的权限。";
}
if(!isset($_POST['import_banji'])) {
    $error .= "<br>你没有选择是否给予“导入班级信息”的权限。";
}
if(!isset($_POST['import_lilun'])) {
    $error .= "<br>你没有选择是否给予“导入理论课信息”的权限。";
}
if(!isset($_POST['import_jingsai'])) {
    $error .= "<br>你没有选择是否给予“导入竞赛信息”的权限。";
}
if(!isset($_POST['import_jiaowu'])) {
    $error .= "<br>你没有选择是否给予“导入教务信息”的权限。";
}
if(!isset($_POST['import_qita'])) {
    $error .= "<br>你没有选择是否给予“导入其他信息”的权限。";
}
if(!isset($_POST['import_qiankao'])) {
    $error .= "<br>你没有选择是否给予“导入欠考信息”的权限。";
}
if(!isset($_POST['import_yanjiusheng'])) {
    $error .= "<br>你没有选择是否给予“导入研究生信息”的权限。";
}
if(!isset($_POST['import_shiyan'])) {
    $error .= "<br>你没有选择是否给予“导入实验信息”的权限。";
}
if(!isset($_POST['import_chengren'])) {
    $error .= "<br>你没有选择是否给予“导入成人信息”的权限。";
}
if(!isset($_POST['import_shijian'])) {
    $error .= "<br>你没有选择是否给予“导入实践信息”的权限。";
}
if(!isset($_POST['modify_jisuanxishu'])) {
    $error .= "<br>你没有选择是否给予“修改计算系数”的权限。";
}
if(!isset($_POST['modify_zcxishu'])) {
    $error .= "<br>你没有选择是否给予“修改职称系数”的权限。";
}
if(!isset($_POST['modify_data'])) {
    $error .= "<br>你没有选择是否给予“修改数据”的权限。";
}
if(!isset($_POST['calc_lilun'])) {
    $error .= "<br>你没有选择是否给予“计算理论课工作量”的权限。";
}
if(!isset($_POST['calc_shijian'])) {
    $error .= "<br>你没有选择是否给予“计算实践课工作量”的权限。";
}
if(!isset($_POST['calc_tiyu'])) {
    $error .= "<br>你没有选择是否给予“计算体育课工作量”的权限。";
}
if(!isset($_POST['export_renshichu'])) {
    $error .= "<br>你没有选择是否给予“导出人事处表”的权限。";
}
if(!isset($_POST['export_geren'])) {
    $error .= "<br>你没有选择是否给予“导出个人工作量表”的权限。";
}
if(!isset($_POST['find_gzl'])) {
    $error .= "<br>你没有选择是否给予“查询工作量”的权限。";
}
if(!isset($_POST['modify_users'])) {
    $error .= "<br>你没有选择是否给予“管理用户”的权限。";
}
if(!isset($_POST['modify_xitong'])) {
    $error .= "<br>你没有选择是否给予“网站系统管理”的权限。";
}
if(count($_POST)!=26) {
    header("location: ./error.php?txt=".$error);
    exit();
}

$sql = "INSERT INTO `users` 
        (
            username,
            password,
            name,
            rank,
            xueyuan,
            import_jiaowu,
            import_qita,
            import_jingsai,
            import_chengren,
            import_yanjiusheng,
            import_shiyan,
            import_qiankao,
            import_teachers,
            import_banji,
            import_lilun,
            import_shijian,
            import_something,
            export_renshichu,
            export_geren,
            modify_jisuanxishu,
            modify_zcxishu,
            modify_data,
            calc_lilun,
            calc_tiyu,
            calc_shijian,
            find_gzl,
            modify_users,
            modify_xitong
            ) VALUES (
            \"".$_POST['username']."\",
            \"".md5($_POST['password'])."\",
            \"".$_POST['name']."\",
            \"2\",
            \"".$_POST['xueyuan']."\",
            \"".$_POST['import_jiaowu']."\",
            \"".$_POST['import_qita']."\",
            \"".$_POST['import_jingsai']."\",
            \"".$_POST['import_chengren']."\",
            \"".$_POST['import_yanjiusheng']."\",
            \"".$_POST['import_shiyan']."\",
            \"".$_POST['import_qiankao']."\",
            \"".$_POST['import_teachers']."\",
            \"".$_POST['import_banji']."\",
            \"".$_POST['import_lilun']."\",
            \"".$_POST['import_shijian']."\",
            \"1\",
            \"".$_POST['export_renshichu']."\",
            \"".$_POST['export_geren']."\",
            \"".$_POST['modify_jisuanxishu']."\",
            \"".$_POST['modify_zcxishu']."\",
            \"".$_POST['modify_data']."\",
            \"".$_POST['calc_lilun']."\",
            \"".$_POST['calc_shijian']."\",
            \"".$_POST['calc_tiyu']."\",
            \"".$_POST['find_gzl']."\",
            \"".$_POST['modify_users']."\",
            \"".$_POST['modify_xitong']."\"
            );";
$result = mysql_query($sql);
if(!$result) {
    die("SQL ERROR : ".mysql_error());
}

header("location: ./error.php?txt="."添加成功！");
exit();

?>

