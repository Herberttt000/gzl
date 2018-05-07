<?php

	/*
	 * 学院导出汇总表
	 */

	include dirname(__FILE__) . './../config.php';
	include dirname(__FILE__) . './../functions.php';
	set_time_limit(0);

	# 判断是否登录
	if(!isset($_SESSION['username'])) {
		header("location: ./error.php?txt="."请登录后再操作.");
		exit();
	}

	# 检验权限
	if(!$_SESSION['find_gzl']) {
		header("location: ./error.php?txt="."您没有导出的权限.");
		exit();
	}
	# 处理学院
	$user_xueyuan = $_SESSION['xueyuan'];
	if($user_xueyuan == "无") {
		header("location: ./error.php?txt="."错误的操作,你的学院为'无'.");
		exit();
	} else if ($user_xueyuan == "全校") {
		$find_xueyuan = "1";
	} else {
		$find_xueyuan = "`xueyuan` = '".$user_xueyuan."'";
	}



	# 处理学期
	if(!isset($_POST['years'])) {
		header("location: ./error.php?txt="."请输入需要导出的年份.");
		exit();
	}

	$get_xueqi   = mysql_real_escape_string($_POST['years']);
	if (strlen($get_xueqi) == 10 || strlen($get_xueqi) == 5) {
		}else{echo("请检查你的输入格式是否正确.");
		exit();
	}
	$begin_xueqi = "";
	$end_xueqi   = $find_method = "";

	if(strlen($get_xueqi) > 5) { #区间查询
		$begin_xueqi = substr($get_xueqi, 0, 5);
		$end_xueqi   = substr($get_xueqi, 5, 5);
		$find_method = "`xueqi`
						BETWEEN ".$begin_xueqi."
						AND ".$end_xueqi." ";

	}else{ #单学期查询
		$find_method = "`xueqi`
						BETWEEN ".$get_xueqi."
						AND ".$get_xueqi." ";
	}


	# 数据准备


	#获取教师列表
	$sql = "SELECT * FROM `teachers` where ".$find_xueyuan.";";
	$teachers_result = mysql_query($sql);
	if(!$teachers_result) {
		die("SQL ERROR : ". mysql_error());
	}

?>
<table><thead><tr>

<?php
function table_h($val){
	echo "<th>".$val."</th>";
}
function table_b($val){
	echo "<td>".$val."</td>";
}

	table_h('学期');
	table_h('教师号');
	table_h('姓名');
	table_h('职称');
	table_h('是否硕导');
	table_h('学院');
	table_h('系');
	table_h('研究生原始');
	table_h('研究生折合');
	table_h('研究生指导');
	table_h('研究生目标');
	table_h('研究生指导竞赛');
	table_h('成人原始');
	table_h('成人折合');
	table_h('成人实践折合');
	table_h('本科理论原始');
	table_h('本科理论折合');
	table_h('本科实践原始');
	table_h('本科实践折合');
	table_h('竞赛');
	table_h('教务津贴');
	table_h('其他');
	table_h('实验原始');
	table_h('实验折合');
	table_h('实验津贴');
	table_h('欠监考次数');
	table_h( '总计');

	echo "</tr></thead><tbody>";

	# 遍历教师列表 写入Excel
	while($teacher_row = mysql_fetch_assoc($teachers_result)) {
		$sql = "select
				sum(yjs_yuanshi),
				sum(yjs_zhehe),
				sum(yjs_zhidao),
				sum(yjs_mubiao),
				sum(yjs_zhidaojingsai),
				sum(cr_yuanshi),
				sum(cr_zhehe),
				sum(cr_shijianzhehe),
				sum(bk_lilunyuanshi),
				sum(bk_lilunzhehe),
				sum(bk_shijianyuanshi),
				sum(bk_shijianzhehe),
				sum(jingsai),
				sum(jiaowu),
				sum(qita),
				sum(sy_yuanshi),
				sum(sy_zhehe),
				sum(sy_jintie),
				sum(qiankao),
				sum(zhongji)
				from `huizong` where `teacher_id`='".$teacher_row['teacher_id']."' and ".$find_method.";";
		$result_sum = mysql_query($sql);
		if(!$result_sum) {
			die("SQL ERROR : ".mysql_error());
		}
		$sum_list = mysql_fetch_assoc($result_sum);
		if($sum_list['sum(zhongji)'] == 0) continue;
		//var_dump($sum_list);
		# 写入Excel类
		echo "<tr>";
		//table_b((int)$get_xueqi);
		echo '<td>';
		if (strlen($get_xueqi) > 5) {
			echo @get_xq($begin_xueqi)." - ".@get_xq($end_xueqi);
		}
		else {
			echo @get_xq($_GET['xueqi']);
		}
		echo '</td>';
		table_b((string)$teacher_row['teacher_id']);
		table_b((string)$teacher_row['teacher_name']);
		table_b((string)$teacher_row['zhicheng']);
		table_b((string)$teacher_row['issd']);
		table_b((string)$teacher_row['xueyuan']);
		table_b((string)$teacher_row['xi']);
		table_b(round((float)$sum_list['sum(yjs_yuanshi)'],4));
		table_b(round((float)$sum_list['sum(yjs_zhehe)'],4));
		table_b(round((float)$sum_list['sum(yjs_zhidao)'],4));
		table_b(round((float)$sum_list['sum(yjs_mubiao)'],4));
		table_b(round((float)$sum_list['sum(yjs_zhidaojingsai)'],4));
		table_b(round((float)$sum_list['sum(cr_yuanshi)'],4));
		table_b(round((float)$sum_list['sum(cr_zhehe)'],4));
		table_b(round((float)$sum_list['sum(cr_shijianzhehe)'],4));
		table_b(round((float)$sum_list['sum(bk_lilunyuanshi)'],4));
		table_b(round((float)$sum_list['sum(bk_lilunzhehe)'],4));
		table_b(round((float)$sum_list['sum(bk_shijianyuanshi)'],4));
		table_b(round((float)$sum_list['sum(bk_shijianzhehe)'],4));
		table_b(round((float)$sum_list['sum(jingsai)'],4));
		table_b(round((float)$sum_list['sum(jiaowu)'],4));
		table_b(round((float)$sum_list['sum(qita)'],4));
		table_b(round((float)$sum_list['sum(sy_yuanshi)'],4));
		table_b(round((float)$sum_list['sum(sy_zhehe)'],4));
		table_b(round((float)$sum_list['sum(sy_jintie)'],4));
		table_b(round((float)$sum_list['sum(qiankao)'],4));
		table_b(round((float)$sum_list['sum(zhongji)'],4));
		echo "</td>";
}


?>
</tbody>
</table>
