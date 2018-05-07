<?php
	
	include dirname(__FILE__) . './../config.php';
	include dirname(__FILE__) . './../functions.php';
	
	# 判断是否登录
	if(!isset($_SESSION['username'])) {
		header("location: ./error.php?txt="."请登录后再操作.");
		exit();
	}

	# 检验权限
	if(!$_SESSION['calc_lilun']) {
		header("location: ./error.php?txt="."您没有计算理论课工作量的权限.");
		exit();
	}

	if($_GET['xueqi']==""){
		header("location: ./error.php?txt="."请选择需要删除的学期.");
		exit();
	}

	# sql
	$sql = "DELETE FROM `lilun` WHERE `xueqi` = \"".$_GET['xueqi']."\";";
	$result_delete = mysql_query($sql);
	if(!$result_delete) {
		die("sql error : ".mysql_error());
	}

	header("location: ./error.php?txt="."您成功删除了“".get_xq($_GET['xueqi'])."”的理论课计算结果.");
	exit();


?>
