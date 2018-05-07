<?php
	#教务津贴的删除

	#导入配置
	include dirname(__FILE__) . './../config.php';
	include dirname(__FILE__) . './../functions.php';

    # 判断是否登录
    if(!isset($_SESSION['username'])) {
        header("location: ./error.php?txt="."请登录后再操作.");
        exit();
    }

    # 检验权限
    if(!$_SESSION['import_chengren']) {
        header("location: ./error.php?txt="."您没有导入成人信息的权限.");
        exit();
    }
	if(empty($_POST)) {
		header("location: ./error.php?txt=请选择正确的学期");
		exit();
	}
	$sql = "DELETE FROM `chengren` WHERE `xueqi`=".$_POST['xueqi'].";";
	$result = mysql_query($sql);
	if(!$result){
		die("SQL ERROR : " . mysql_error());
	}else{
		header("location: ./error.php?txt="."删除成功.");
        exit();
	}

?>