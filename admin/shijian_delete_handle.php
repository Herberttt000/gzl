<?php
	/* 
	*	删除实践数据 
	*/
    include './../config.php';
    include './../functions.php';

    # 判断是否登录
    if(!isset($_SESSION['username'])) {
        header("location: ./error.php?txt="."请登录后再操作.");
        exit();
    }

    # 检验权限
    if(!$_SESSION['import_shijian']) {
        header("location: ./error.php?txt="."您没有导入实践课信息的权限.");
        exit();
    }

    if(!isset($_POST['xueqi'])) {
        header("location: ./error.php?txt="."您没有选择需要删除的学期！");
        exit();
    }

    # sql 删除某学期的实践数据
    $sql = "DELETE FROM `shijian_temp` WHERE `xueqi` = \"".(int)$_POST['xueqi']."\" 
    AND `username`=\"".$_SESSION['username']."\";";
    $result = mysql_query($sql);
    if(!$result) {
       die("insert error:" . mysql_error());
    }

header("location: ./error.php?txt="."您删除了“".$_SESSION['xueyuan']."”的“".$_POST['xueqi']."”学期的数据.");
exit();
?>