<?php

	/*
	 * 学院导出理论课程表
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
	if(!isset($_GET['xueqi'])) {
		header("location: ./error.php?txt="."请输入需要导出的年份.");
		exit();
	}
	
	$get_xueqi   = mysql_real_escape_string($_GET['xueqi']);
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

	//echo $get_xueqi;
	$sql = "select `teacher_id`from `teachers` where ".$find_xueyuan.";";
	$result_teacher_list = mysql_query($sql);
	if(!$result_teacher_list) {
		die("SQL ERROR : ".mysql_error().$sql);
	}
	$t_sql = "";
	$i = 0;
	while ($row = mysql_fetch_assoc($result_teacher_list)) {
		if ($i == 0) {
			$t_sql .= "`teacher_id` = '".$row['teacher_id']."'";
		}
		$t_sql .= " or `teacher_id` = '".$row['teacher_id']."'";
		$i++;
	}

	$sql = "SELECT * FROM `lilun` WHERE (".$find_method.") and (".$t_sql.") order by `xueqi` asc, `teacher_id` asc;";
	//echo $sql;
	$result_ll = mysql_query($sql);
	if(!$result_ll) {
		die("SQL ERROR : " . mysql_error().$sql);
	}
	$num_ll = mysql_num_rows($result_ll);
	if($num_ll==0) {
		header("location: ./error.php?txt="."没有“".$get_xueqi."”年的记录.");
		exit();	
	}

include "header.php";
?>
<script>
function export_lilun(){
	var xueqi = document.getElementById("id_years").value;
	window.open("./admin_search_teacher_works_output_lilun.php?xueqi="+xueqi);
}
</script>
<div class="container-fluid">
	<div class="row">
				<h2 class="sub-header"><?php echo $user_xueyuan; ?> 理论工作量明细汇总<span class="label label-info"><?php if (strlen($_GET['xueqi']) > 5) echo @get_xq($begin_xueqi)." - ".@get_xq($end_xueqi); else echo @get_xq($_GET['xueqi']); ?></span><span class="pull-right"><button id="lilun_Button" type="button" class="btn btn-success" onclick="export_lilun()">下载</button></span><input type="hidden" name="userId" id="id_years" class="form-control" value="<?php echo $get_xueqi;?>"></h2>
	</div>
	<div class="row">
		<table class="table table-hover table-striped table-condensed"><thead><tr>


<?php
function table_h($str) {
	echo "<th>".$str."</th>";
}

table_h('流水号');
table_h('学期');
table_h('课程号');
table_h('课程名');
table_h('序号');
table_h('教师学院');
table_h('教师系');
table_h('教师号');
table_h('姓名');
table_h('职称');
table_h('合班');
table_h('学生专业');
table_h('是否3表');
table_h('学时');
table_h('人数');
table_h('人数系数');
table_h('重复课系数');
table_h('专业课系数');
table_h('三表系数');
table_h('质量系数');
table_h('难度系数');
table_h('职称系数');
table_h('计算过程');
table_h('教分');
echo "</tr></thead><tbody>";


function table_b($val) {
	echo "<td>".$val."</td>";
}



while ( $row = mysql_fetch_assoc($result_ll)) {
		echo "<tr>";
		table_b((int)$row['id']);
		table_b((int)$row['xueqi']);
		table_b((string)$row['course_id']);
		table_b((string)$row['course_name']);
		table_b((int)$row['course_index']);
		table_b((string)$row['teacher_xueyuan']);
		table_b((string)$row['teacher_yuanxi']);
		table_b((string)$row['teacher_id']);
		table_b((string)$row['teacher_name']);
		table_b((string)$row['teacher_zc']);
		table_b((string)$row['heban']);
		table_b((string)$row['zhuanye']);
		table_b((int)$row['issb']);
		table_b((int)$row['xueshi']);
		table_b((int)$row['num_of_p']);
		table_b((float)$row['xishu_people']);
		table_b((float)$row['xishu_cfk']);
		table_b((float)$row['xishu_zyk']);
		table_b((float)$row['xishu_sb']);
		table_b((float)$row['xishu_zl']);
		table_b((float)$row['xishu_nd']);
		table_b((float)$row['xishu_zc']);
		table_b((string)$row['guocheng']);
		table_b((float)$row['jiaofen']);

		echo "</tr>";
}

?>			
</tbody>
		</table>
</div>
</div>
