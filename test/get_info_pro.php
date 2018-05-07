<?php
/*
*	查询某教师的工作量明细
*/

include "./config.php";
include "./functions.php";
header("Content-type: text/html; charset=utf-8");

# 验证用户来源开关
$host_need = 1;

if($host_need) {
	if(!isset($_SESSION['referer'])) {
		echo "<script>alert('验证失败(No referer page!)');</script>";
		exit();
	} else {
		$url_host = "202.118.201.228"; //parse_url($_SESSION['referer']);
		if($url_host != "202.118.201.228") {
			echo "<script>alert('只接受来自202.118.201.228的跳转(referer is wrong!)');</script>";
			exit();	
		}
	}
}


if(isset($_SESSION['teacher_id']) && isset($_GET['xueqi']) && isset($_SESSION['token']) ){
	if(!isset($_SESSION['token'])){
		echo "<script>alert('验证失败(token is wrong!)');</script>";
		exit();
	}
}else{
	echo "<script>alert('你的请求数据不全(need some param!)');</script>.";
	exit();
}

	$sql = "SELECT `value` FROM `xitong` WHERE name='chaxun';";
	$result = mysql_query($sql);
	if(!$result){
		die("SQL ERROR : ".mysql_error());
	}

$open = mysql_fetch_assoc($result);
$open = $open['value'];

if($open==0){
	echo "<script>alert('管理员关闭了查询功能(this function has being colsed, please query after! )');</script>";
	exit();
}

# 解析学期
$get_xueqi = "20151";

if (isset($_GET['xueqi'])) {
	$_GET['xueqi'] = mysql_real_escape_string($_GET['xueqi']);
	$get_xueqi   = $_GET['xueqi'];
}else{
	$get_xueqi = "20151";
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

if(isset($_SESSION['teacher_id'])) {
	$teacher_id = mysql_real_escape_string($_SESSION['teacher_id']);
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
	$sum_all += $result_qita[0];
}

?>
<?php
header_remove();
include './header_c.php';
?>

<!-- container -->
<div class="container-fluid">

	<?php
	if(isset($_SESSION['teacher_id'])){
		?>
		<div class="row">
			
			
			<header class="page-header">
				<h1 class="page-title"><?php echo $teacher_rows['teacher_name']; ?> 的工作量
							<form class="navbar-form navbar-right" method="get">
			<input type="text" name="xueqi" class="form-control" placeholder="2014春请输入“20141”">
			
			<span class="text-right"><button type="submit" class="btn btn-success">查询</button></span>
		</form></h1>
			</header>
		</div>
		<div class="row">
			<div class="alert alert-success" role="alert">查询小技巧: <br><ul><li><strong>20141</strong> 查询 2014春 的工作量</li><li><strong>20142</strong> 查询 2014秋 的工作量</li><li><strong>2013120142</strong> 查询 2013春 到 2014秋 的工作量</li></ul></div>
		</div>
		<div class="row">
			<div class="container-fluid">
				
				<h2 class="sub-header">个人工作量明细汇总<span class="label label-info"><?php if (strlen($_GET['xueqi']) > 5) echo @get_xq($begin_xueqi)." - ".@get_xq($end_xueqi); else echo @get_xq($_GET['xueqi']); ?></span></h2>
				<div class="span12">
					<table class="table table-condensed">
						<thead>
							<tr>
								<th>教师号</th>
								<th>姓名</th>
								<th>职称</th>
								<th>学院</th>
								<th>系</th>
								<th>是否硕导</th>
								<th>本科理论原始</th>
								<th>本科理论折合</th>
								<th>本科实践原始</th>
								<th>本科实践折合</th>
								<th>研究生原始</th>
								<th>研究生折合</th>
								<th>研究生指导</th>
								<th>研究生目标</th>
								<th>研究生指导竞赛</th>
								<th>成人原始</th>
								<th>成人折合</th>
								<th>成人实践折合</th>
								<th>实验原始</th>
								<th>实验折合</th>
								<th>实验津贴</th>
								<th>竞赛</th>
								<th>教务津贴</th>
								<th>其他</th>
								<th>欠考次数</th>
								<th>总计</th>
							</tr>
						</thead>
						<tbody>
							<tr>
							<?php

							echo "<td>".$teacher_rows['teacher_id']."</td>";
							echo "<td>".$teacher_rows['teacher_name']."</td>";
							echo "<td>".$teacher_rows['zhicheng']."</td>";
							echo "<td>".$teacher_rows['xueyuan']."</td>";
							echo "<td>".$teacher_rows['xi']."</td>";
							echo "<td>".$teacher_rows['issd']."</td>";
							printf("<td>%.4f</td>", round($llyuanshi[0], 4));
							printf("<td>%.4f</td>", round($llzhehe[0], 4));

							//echo "<td>".$llzhehe[0]."</td>";
							printf("<td>%.4f</td>", round($sjyuanshi[0], 4));
							//echo "<td>".$sjzhehe[0]."</td>";
							printf("<td>%.4f</td>", round($sjzhehe[0],4));
							echo "<td>".round($result_yjs['yuanshi'], 4)."</td>";
							echo "<td>".round($result_yjs['zhehe'], 4)."</td>";
							echo "<td>".round($result_yjs['zhidao'], 4)."</td>";
							echo "<td>".round($result_yjs['mubiao'], 4)."</td>";
							echo "<td>".round($result_yjs['zdjs'], 4)."</td>";
							echo "<td>".round($result_chengren['yuanshi'], 4)."</td>";
							echo "<td>".round($result_chengren['zhehe'], 4)."</td>";
							echo "<td>".round($result_chengren['shijianzhehe'], 4)."</td>";
							echo "<td>".round($result_shiyan['yuanshi'], 4)."</td>";
							echo "<td>".round($result_shiyan['zhehe'], 4)."</td>";
							echo "<td>".round($result_shiyan['jintie'], 4)."</td>";
							echo "<td>".round($result_jingsai[0], 4)."</td>";
							echo "<td>".round($result_jiaowu[0], 4)."</td>";
							echo "<td>".round($result_qita[0], 4)."</td>";
							echo "<td>".round($result_qiankao[0], 4)."</td>";
							printf("<td>%.4f</td>", $sum_all);

							?>
							</tr>
						</tbody>
					</table>
				</div>

				<h2 class="sub-header">理论教学工作量明细</h2>
				<div class="span12">
					<table class="table table-hover table-condensed table-striped">
						<thead>
							<tr>
								<th>学期</th>
								<th>教师号</th>
								<th>姓名</th>
								<th>职称</th>
								<th>课程号</th>
								<th>课程名</th>
								<th>课序号</th>
								<th>合班</th>
								<th>学时</th>
								<th>人数</th>
								<th>人数系数</th>
								<th>重复课系数</th>
								<th>专业课系数</th>
								<th>三表系数</th>
								<th>质量系数</th>
								<th>难度系数</th>
								<th>职称系数</th>
								<th>计算过程</th>
								<th>教分</th>
								


							</tr>
						</thead>
						<tbody>
							<?php
							for($i = 0; $i < $list_ll; $i++) {
								$row = mysql_fetch_assoc($result_ll);
										//print_r($row);
								// if($i%2==0) echo "<tr>";
								// else echo "<tr class=\"success\">";
								echo "<tr>";
								echo "<td>".get_xq($row['xueqi'])."</td>";
								echo "<td>".$row['teacher_id']."</td>";
								echo "<td>".$row['teacher_name']."</td>";
								echo "<td>".$row['teacher_zc']."</td>";
								echo "<td>".$row['course_id']."</td>";
								echo "<td>".$row['course_name']."</td>";
								echo "<td>".$row['course_index']."</td>";
								echo "<td>".$row['heban']."</td>";
								echo "<td>".$row['xueshi']."</td>";
								echo "<td>".$row['num_of_p']."</td>";
								echo "<td>".$row['xishu_people']."</td>";
								echo "<td>".$row['xishu_cfk']."</td>";
								echo "<td>".$row['xishu_zyk']."</td>";
								echo "<td>".$row['xishu_sb']."</td>";
								echo "<td>".$row['xishu_zl']."</td>";
								echo "<td>".$row['xishu_nd']."</td>";
								echo "<td>".$row['xishu_zc']."</td>";
								echo "<td>".$row['guocheng']."</td>";
								echo "<td>".$row['jiaofen']."</td>";





										//echo $teacher_rows['teacher_XY'];
										//echo $teacher_rows['teacher_X'];
								echo "</tr>";
							}
							?>
						</tbody>
					</table>
				</div>

				<h2 class="sub-header">实践教学工作量明细</h2>
				<div class="span12">
					<table class="table table-hover table-condensed table-striped">
						<thead>
							<tr>
								<th>学期</th>
								<th>教师号</th>
								<th>姓名</th>
								<th>职称</th>
								<th>实践名称</th>
								<th>课程号</th>
								<th>实践类型</th>
								<th>职称系数</th>
								<th>学时(周)</th>
								<th>人数</th>
								<th>班级</th>
								<th>地点</th>
								<th>计算过程</th>
								<th>教分</th>

							</tr>
						</thead>
						<tbody>
							<?php

							for($i = 0; $i < $list_sj; $i++) {
								$row = mysql_fetch_assoc($result_sj);
										//print_r($row);
								// if($i%2==0) echo "<tr>";
								// else echo "<tr class=\"success\">";
								echo "<tr>";
								echo "<td>".get_xq($row['xueqi'])."</td>";
								echo "<td>".$row['teacher_id']."</td>";
								echo "<td>".$row['teacher_name']."</td>";
								echo "<td>".$row['teacher_zc']."</td>";
								echo "<td>".$row['shijian_name']."</td>";
								echo "<td>".$row['course_id']."</td>";
								echo "<td>".$row['shijian_type']."</td>";
								echo "<td>".$row['zhichengxishu']."</td>";
								echo "<td>".$row['zhoushu']."</td>";
								echo "<td>".$row['num_of_p']."</td>";
								echo "<td>".$row['banji']."</td>";
								echo "<td>".$row['didian']."</td>";
								echo "<td>".$row['guocheng']."</td>";
								echo "<td>".$row['jiaofen']."</td>";
								echo "</tr>";
							}
							?>
						</tbody>
					</table>
				</div> 
				<h2 class="sub-header">竞赛明细</h2>
				<div class="span12">
					<table class="table table-hover table-condensed table-striped">
						<thead>
							<tr>
								<th>学期</th>
								<th>教师号</th>
								<th>姓名</th>
								<th>原因</th>
								<th>教分</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = "SELECT * FROM `jingsai` 
								WHERE `teacher_id`=\"".$_SESSION['teacher_id']."\" 
								AND `xueqi` = \"".$_GET['xueqi']."\"";
							$result_jingsai = mysql_query($sql);
							if(!$result_jingsai) {
								die("SQL ERROR : ".mysql_error());
							}
							while($row = mysql_fetch_assoc($result_jingsai)) {
								// $row = mysql_fetch_assoc($result_sj);
										//print_r($row);
								// if($i%2==0) echo "<tr>";
								// else echo "<tr class=\"success\">";
								echo "<tr>";
								echo "<td>".get_xq($row['xueqi'])."</td>";
								echo "<td>".$row['teacher_id']."</td>";
								echo "<td>".$row['teacher_name']."</td>";
								echo "<td>".$row['yuanyin']."</td>";
								echo "<td>".$row['jiaofen']."</td>";
								echo "</tr>";
							}
							?>
						</tbody>
					</table>
				</div>
				<h2 class="sub-header">教务津贴明细</h2>
				<div class="span12">
					<table class="table table-hover table-condensed table-striped">
						<thead>
							<tr>
								<th>学期</th>
								<th>教师号</th>
								<th>姓名</th>
								<th>原因</th>
								<th>教分</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = "SELECT * FROM `jiaowu` 
								WHERE `teacher_id`=\"".$_SESSION['teacher_id']."\" 
								AND `xueqi` = \"".$_GET['xueqi']."\"";
							$result_jiaowu_1 = mysql_query($sql);
							if(!$result_jiaowu_1) {
								die("SQL ERROR : ".mysql_error());
							}
							while($row = mysql_fetch_assoc($result_jiaowu_1)) {
								// $row = mysql_fetch_assoc($result_sj);
										//print_r($row);
								// if($i%2==0) echo "<tr>";
								// else echo "<tr class=\"success\">";
								echo "<tr>";
								echo "<td>".get_xq($row['xueqi'])."</td>";
								echo "<td>".$row['teacher_id']."</td>";
								echo "<td>".$row['teacher_name']."</td>";
								echo "<td>".$row['yuanyin']."</td>";
								echo "<td>".$row['jiaofen']."</td>";
								echo "</tr>";
							}
							?>
						</tbody>
					</table>
				</div>
				<h2 class="sub-header">其他明细</h2>
				<div class="span12">
					<table class="table table-hover table-condensed table-striped">
						<thead>
							<tr>
								<th>学期</th>
								<th>教师号</th>
								<th>姓名</th>
								<th>原因</th>
								<th>教分</th>
							</tr>
						</thead>
						<tbody>
							<?php
							$sql = "SELECT * FROM `qita` 
								WHERE `teacher_id`=\"".$_SESSION['teacher_id']."\" 
								AND `xueqi` = \"".$_GET['xueqi']."\"";
							$result_qita1 = mysql_query($sql);
							if(!$result_qita1) {
								die("SQL ERROR : ".mysql_error());
							}
							while($row = mysql_fetch_assoc($result_qita1)) {
								// $row = mysql_fetch_assoc($result_sj);
										//print_r($row);
								// if($i%2==0) echo "<tr>";
								// else echo "<tr class=\"success\">";
								echo "<tr>";
								echo "<td>".get_xq($row['xueqi'])."</td>";
								echo "<td>".$row['teacher_id']."</td>";
								echo "<td>".$row['teacher_name']."</td>";
								echo "<td>".$row['yuanyin']."</td>";
								echo "<td>".$row['jiaofen']."</td>";
								echo "</tr>";
							}
							?>
						</tbody>
					</table>
				</div>

			</div>
		</div>


		<?php
	}else{
		?>
		<form class="navbar-form navbar-left" method="get">
			<input type="text" name="xueqi" class="form-control" placeholder="2014春请输入“20141”">
			<span class="text-right"><button type="submit" class="btn btn-success">查询</button></span>
		</form>
		<?php
	}
	?>
</div>	<!-- /container -->
<br />
<br />
<br />
<br />

</body>
</html>