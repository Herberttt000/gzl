<?php
	/*
	file:   admin_search_teacher_works_handle.php
	auther: moonsn
	date:   2015-05-29
	drsc: 	对查询进行过滤处理，不同权限的用户查询范围不同
	*/

	include "./../config.php";
	include "./../functions.php";

	# 判断是否登录
	if(!isset($_SESSION['username'])) {
		header("location: ./error.php?txt="."请登录后再操作.");
		exit();
	}

	# 检验权限
	if(!$_SESSION['find_gzl']) {
		header("location: ./error.php?txt="."您没有查询工作量的权限.");
		exit();
	}


	# 过滤查询
	$user_xueyuan = $_SESSION['xueyuan'];
	$target_teacher = mysql_real_escape_string($_GET['userId']);

	if($user_xueyuan == "全校") {
		header("location: ./../get_access.php?userId=".$target_teacher);
		exit();
	}

	$sql = "select xueyuan from `teachers` where teacher_id = ". $target_teacher;
	$result = mysql_query($sql);
	if(!$result) {
		die("SQL ERROR : ".mysql_error());
	}

	$rs= mysql_fetch_array($result);

	if($rs['xueyuan'] == $user_xueyuan) {
		header("location: ./../get_access.php?userId=".$target_teacher."&xueqi=".$_GET['xueqi']);
		exit();
	}else{
		header("location: ./error.php?txt="."该教师不在你的学院！只能查询自己学院的教师工作量。");
		exit();
	}
?>