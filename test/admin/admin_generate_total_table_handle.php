<?php
      # 生成个人工作量汇总模块

      # 导入配置
include dirname(__FILE__) . './../config.php';
include dirname(__FILE__) . './../functions.php';
	set_time_limit(0);

    # 判断是否登录
if(!isset($_SESSION['username'])) {
    header("location: ./error.php?txt="."请登录后再操作.");
    exit();
}

    # 检验权限
if(!$_SESSION['calc_lilun']) {
    header("location: ./error.php?txt="."您没有修改用户的权限.");
    exit();
}

?>
<?php



$sql = "select DISTINCT `teacher_id`, `teacher_name` from `teachers`;";
$result_teacher_list = mysql_query($sql);
if (!$result_teacher_list) {
	die("SQL ERROR : ".mysql_error());
}

$num_of_teacher = 0;


# 先删除相应学期
$sql = "DELETE FROM `huizong` WHERE `xueqi` = ".$_POST['xueqi'].";";
$result = mysql_query($sql);
if(!$result) {
	die("mysql error:" .mysql_error());
}

# 定义汇总学期
$xueqi = $_POST['xueqi'];
$find_method = "`xueqi` = '".$xueqi."'";

#遍历教师名单,计算工作量汇总
while ($teacher_list = mysql_fetch_assoc($result_teacher_list)) {
	$teacher_id = mysql_real_escape_string($teacher_list['teacher_id']);
	$sql = "select * from `teachers` where `teacher_id` =\"".$teacher_id."\";";
	$result = mysql_query($sql);
	if(!$result) {
		die("mysql select error:".mysql_error());
	}
	$teacher_rows = mysql_fetch_assoc($result);
	if(empty($teacher_rows)) {
		echo "<script>alert('没有查询到教师号为 ".$teacher_id." 的老师.')</script>)";
		exit();
	}
	//理论授课
	$result_ll = mysql_query("select * from `lilun` where `teacher_id` = \"". $teacher_id ."\" and ".$find_method.";");
	if(!$result_ll) {
		die("mysql select error:".mysql_error());
	}
	$list_ll  = mysql_num_rows($result_ll);

	#计算理论原始和折合
	$llyuanshi = 0;
	$sql = "SELECT SUM(xueshi) FROM `lilun` WHERE `teacher_id`= \"". $teacher_id ."\" and ".$find_method." ORDER BY `xueqi` ASC, `course_name` DESC, `num_of_p` DESC, `course_index` DESC, `xishu_cfk` DESC;;";
	$result_sumlilun = mysql_query($sql);
	if(!$result_sumlilun) {
		die("sql error ： ". mysql_error());
	}
	$llyuanshi = mysql_fetch_row($result_sumlilun);
	$llzhehe = 0;
	$sql = "SELECT SUM(jiaofen) FROM `lilun` WHERE `teacher_id`= \"". $teacher_id ."\" and ".$find_method.";";
	$result_sumlilunzhehe = mysql_query($sql);
	if(!$result_sumlilunzhehe) {
		die("sql error ： ". mysql_error());
	}
	$llzhehe = mysql_fetch_row($result_sumlilunzhehe);

	//$row = mysql_fetch_array($result);
	#实践
	$result_sj = mysql_query("select * from `shijian` where `teacher_id` = \"". $teacher_id ."\"  and ".$find_method.";");
	if(!$result_sj) {
		die("mysql select error:".mysql_error());
	}
	$list_sj  = mysql_num_rows($result_sj);
	
	#计算实践原始和折合
	$sjyuanshi = 0;
	$sql = "SELECT SUM(zhoushu) FROM `shijian` WHERE `teacher_id`= \"". $teacher_id ."\" and ".$find_method.";";
	$result_sumshijian = mysql_query($sql);
	if(!$result_sumshijian) {
		die("sql error ： ". mysql_error());
	}
	$sjyuanshi = mysql_fetch_row($result_sumshijian);
	$sjzhehe = 0;
	$sql = "SELECT SUM(jiaofen) FROM `shijian` WHERE `teacher_id`= \"". $teacher_id ."\" and ".$find_method.";";
	$result_sumshijianzhehe = mysql_query($sql);
	if(!$result_sumshijianzhehe) {
		die("sql error ： ". mysql_error());
	}
	$sjzhehe = mysql_fetch_row($result_sumshijianzhehe);
	// print_r($llyuanshi);
	// print_r($llzhehe);
	// print_r($sjyuanshi);
	// print_r($sjzhehe);
	//exit();

	#实验
	$result_shiyan = mysql_query("select * from `shiyan` where `teacher_id` = \"". $teacher_id ."\"  and ".$find_method.";");
	if(!$result_shiyan) {
		die("mysql select error:".mysql_error());
	}
	$result_shiyan = mysql_fetch_assoc($result_shiyan);
	
	#竞赛
	$result_jingsai = mysql_query("select SUM(jiaofen) from `jingsai` where `teacher_id` = \"". $teacher_id ."\"  and ".$find_method.";");
	if(!$result_jingsai) {
		die("mysql select error:".mysql_error());
	}
	$result_jingsai = mysql_fetch_row($result_jingsai);

	#教务津贴
	$result_jiaowu = mysql_query("select SUM(jiaofen) from `jiaowu` where `teacher_id` = \"". $teacher_id ."\"  and ".$find_method.";");
	if(!$result_jiaowu) {
		die("mysql select error:".mysql_error());
	}
	$result_jiaowu = mysql_fetch_row($result_jiaowu);

	#其他
	$result_qita = mysql_query("select SUM(jiaofen) from `qita` where `teacher_id` = \"". $teacher_id ."\"  and ".$find_method.";");
	if(!$result_qita) {
		die("mysql select error:".mysql_error());
	}
	$result_qita = mysql_fetch_row($result_qita);
    
    #欠考
	$result_qiankao = mysql_query("select SUM(jiaofen) from `qiankao` where `teacher_id` = \"". $teacher_id ."\"  and ".$find_method.";");
	if(!$result_qiankao) {
		die("mysql select error:".mysql_error());
	}
	$result_qiankao = mysql_fetch_row($result_qiankao);


	#成人
	$result_chengren = mysql_query("select * from `chengren` where `teacher_id` = \"". $teacher_id ."\"  and ".$find_method.";");
	if(!$result_chengren) {
		die("mysql select error:".mysql_error());
	}
	$result_chengren = mysql_fetch_assoc($result_chengren);

	#研究生
	$result_yjs = mysql_query("select * from `yanjiusheng` where `teacher_id` = \"". $teacher_id ."\"  and ".$find_method.";");
	if(!$result_yjs) {
		die("mysql select error:".mysql_error());
	}
	$result_yjs = mysql_fetch_assoc($result_yjs);

	# 汇总
	$sum_all = 0;
	$sum_all = $llzhehe[0] + $sjzhehe[0];
	$sum_all += $result_yjs['zhehe'] + $result_yjs['zhidao'] + $result_yjs['mubiao'] + $result_yjs['zdjs'];
	$sum_all += $result_chengren['zhehe'] + $result_chengren['shijianzhehe'];
	$sum_all += $result_shiyan['zhehe'] + $result_shiyan['jintie'];
	$sum_all += $result_jingsai[0];
	$sum_all += $result_jiaowu[0];
	$sum_all += round($result_qita[0], 4);

$sql = "INSERT INTO `huizong` (
			xueqi,
			teacher_id,
			teacher_name,
			yjs_yuanshi,
			yjs_zhehe,
			yjs_zhidao,
			yjs_mubiao,
			yjs_zhidaojingsai,
			cr_yuanshi,
			cr_zhehe,
			cr_shijianzhehe,
			bk_lilunyuanshi,
			bk_lilunzhehe,
			bk_shijianyuanshi,
			bk_shijianzhehe,
			jingsai,
			jiaowu,
			qita,
			sy_yuanshi,
			sy_zhehe,
			sy_jintie,
			qiankao,
			zhongji
			) VALUES (
			".$xueqi.",
			\"".$teacher_list['teacher_id']."\",
			\"".$teacher_list['teacher_name']."\",
			".round($result_yjs['yuanshi'], 4).",
			".round($result_yjs['zhehe'], 4).",
			".round($result_yjs['zhidao'], 4).",
			".round($result_yjs['mubiao'], 4).",
			".round($result_yjs['zdjs'], 4).",
			".round($result_chengren['yuanshi'], 4).",
			".round($result_chengren['zhehe'], 4).",
			".round($result_chengren['shijianzhehe'], 4).",
			".round($llyuanshi[0], 4).",
			".round($llzhehe[0], 4).",
			".round($sjyuanshi[0], 4).",
			".round($sjzhehe[0],4).",
			".round($result_jingsai[0], 4).",
			".round($result_jiaowu[0], 4).",
			".round($result_qita[0], 4).",
			".round($result_shiyan['yuanshi'], 4).",
			".round($result_shiyan['zhehe'], 4).",
			".round($result_shiyan['jintie'], 4).",
			\"".$result_qiankao[0]."\",
			".round($sum_all)."
			);";

	if ($sum_all == 0) {
		continue;
	}
	$insert_total_result = mysql_query($sql);
	if (!$insert_total_result) {
		die("SQL ERROR : ".mysql_error()."\n".$sql.var_dump($_POST));
	}
	$num_of_teacher ++;
}

echo "成功生成".$xueqi."汇总表。非零记录条数：".$num_of_teacher;

?>