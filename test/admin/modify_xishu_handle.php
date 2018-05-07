<?php
include './../config.php';
	# 判断是否登录
	if(!isset($_SESSION['username'])) {
		header("location: ./error.php?txt="."请登录后再操作.");
		exit();
	}

	# 检验权限
	if(!$_SESSION['modify_jisuanxishu']) {
		header("location: ./error.php?txt="."您没有修改计算系数的权限.");
		exit();
	}

$sql = "update `jisuanxishu` set `value` = '".$_POST['value']."' where `name` = '".$_POST['name']."';";
		//echo $sql;
$result = mysql_query($sql);
if (!$result)
{
	die('update error : ' . mysql_error());
}


header("location: ./modify_xishu.php");
//print_r($_POST);
?>