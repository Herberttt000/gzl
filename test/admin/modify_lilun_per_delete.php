<?php

include dirname(__FILE__) . './../config.php';
include dirname(__FILE__) . './../functions.php';

	# 判断是否登录
	if(!isset($_SESSION['username'])) {
		header("location: ./error.php?txt="."请登录后再操作.");
		exit();
	}

	# 检验权限
	if(!$_SESSION['modify_data']) {
		header("location: ./error.php?txt="."您没有修改工作量的权限.");
		exit();
	}
?>
<?php

if(!isset($_POST['id'])) {
	header("location: ./error.php?txt="."错误的操作.");
	exit();
}

if($_POST['delete_type']=="lilun"){
	$sql = "DELETE FROM `lilun` WHERE `id` = '".$_POST['id']."';";
}else{
	$sql = "DELETE FROM `shijian` WHERE `id` = '".$_POST['id']."';";
}


$result = mysql_query($sql);
if(!$result) {
	die("SQL ERROR : ".mysql_error());
}


?>
<?php

	header("location: ./modify_lilun_per.php?teacher_id=".$_POST['teacher_id']."&xueqi=".$_POST['year']."");
	exit();
?>


