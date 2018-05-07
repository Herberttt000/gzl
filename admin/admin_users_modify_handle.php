<?php

	include dirname(__FILE__) . './../config.php';
	include dirname(__FILE__) . './../functions.php';

	# 判断是否登录
	if(!isset($_SESSION['rank'])) {
		header("location: ./error.php?txt="."请登录后再操作.");
		exit();
	}

	if(!$_SESSION['modify_users']) {
		header("location: ./error.php?txt="."您没有修改用户的权限.");
		exit();
	}
	if($_POST['password']=="") {
		$sql = "SELECT password FROM `users` WHERE `username`='".$_POST['username']."';";
		$result = mysql_query($sql);
		if(!$result) {
			die("SQL ERROR : ".mysql_error());
		}
		$result = mysql_fetch_assoc($result);
		$pwd = $result['password'];
	}else{
		$pwd = md5($_POST['password']);
	}

	$sql = "UPDATE `users` SET 
		name = '".$_POST['name']."',
		xueyuan = '".$_POST['xueyuan']."',
		password = '".$pwd."',
		import_teachers = '".$_POST['import_teachers']."',
		import_banji = '".$_POST['import_banji']."',
		import_jingsai = '".$_POST['import_jingsai']."',
		import_jiaowu = '".$_POST['import_jiaowu']."',
		import_qita = '".$_POST['import_qita']."',
		import_qiankao = '".$_POST['import_qiankao']."',
		import_yanjiusheng = '".$_POST['import_yanjiusheng']."',
		import_shiyan = '".$_POST['import_shiyan']."',
		import_chengren = '".$_POST['import_chengren']."',
		import_shijian = '".$_POST['import_shijian']."',
		import_lilun = '".$_POST['import_lilun']."',
		modify_jisuanxishu = '".$_POST['modify_jisuanxishu']."',
		modify_zcxishu = '".$_POST['modify_zcxishu']."',
		modify_data = '".$_POST['modify_data']."',
		calc_lilun = '".$_POST['calc_lilun']."',
		calc_shijian = '".$_POST['calc_shijian']."',
		calc_tiyu = '".$_POST['calc_tiyu']."',
		export_renshichu = '".$_POST['export_renshichu']."',
		export_geren = '".$_POST['export_geren']."',
		find_gzl = '".$_POST['find_gzl']."',
		modify_users = '".$_POST['modify_users']."',
		modify_xitong = '".$_POST['modify_xitong']."'
		WHERE `username` = '".$_POST['username']."';";
	$result = mysql_query($sql);
	if(!$result) {
		die("SQL ERROR : ".mysql_error());
	}

	header("location: ./error.php?txt="."修改成功.");
	exit();

?>
