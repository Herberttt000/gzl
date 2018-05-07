<?php
	#计算理论课工作量

	#导入配置
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
?>
<?php
include './header.php';

?>

<?php

/*
* 人数系数更新
*/
//获取就算人数系数需要的系数 K的递增值等信息
$sql = "SELECT * FROM `jisuanxishu`;";
$result = mysql_query($sql);
if(!$result) {
	die("sql select error jisuanxishu" . mysql_error());
}
$xishu_rows = array();
while($row = mysql_fetch_assoc($result)) {
	$xishu_rows[$row['name']] = $row['value'];
}

//获取班级是否是3表
$sql = "SELECT * FROM `class`;";
$result = mysql_query($sql);
if(!$result) {
	die("sql select error class: " . mysql_error());
}
$issb = array();
while ($row = mysql_fetch_assoc($result)) {
	if(substr($row['zhuanye'], -1) == "."
		|| substr($row['zhuanye'], -1) == "L" ) {
		$issb[$row['name']] = 1;
	}else{
		$issb[$row['name']] = 0;
	}
}

# 职称系数
$sql = "SELECT * FROM `zcxishu`;";
$result = mysql_query($sql);
if(!$result) {
	die("sql select error :  ". mysql_error());
}
$zc_rows = array();
while ($row = mysql_fetch_assoc($result)) {
	$zc_rows[$row['name']] = $row['xishu'];
}


# 教师职称提取
$sql = "SELECT * FROM `teachers`;";
$result_t = mysql_query($sql);
if(!$result_t) {
	die("SQL ERROR : " . mysql_error());
}
$id2zc = array();
while ($row_t = mysql_fetch_assoc($result_t)) {
	$id2zc[$row_t['teacher_id']] = $row_t['zhicheng'];
}

# 重复课细节解决:`
# 建立一个数组记录老师某门课的授课次数;
# 由于我们查询数据的时候按照人数倒序排序了 result
# 所以得到的结果是人数多的优先 ORDER BY `num_of_p` DESC
$cfk = array();


# 这里再过滤一下课序号相同的课程
# 用哈希记录一下
$iscf = array();

# 如果数据库里面已经存在了这条数据
# 学期相同，课程号相同，教师号相同 课程序号相同
# 则进行 update
# 这是为了避免重复计算的bug
$isupdate = array();

$sql = "SELECT * FROM `lilun` WHERE `xueqi`=\"".$_GET['xueqi']."\";";
$result = mysql_query($sql);
if(!$result) {
	die("SQL ERROR : ".mysql_error());
}
while( $row = mysql_fetch_assoc($result)) {
	$isupdate[$row['xueqi']."_".substr($row['course_id'],0,7)."_".$row['teacher_id']."_".$row['course_index']] = 1;
}


## let begin !
## 开始计算
$sql = "SELECT * FROM `lilun_temp`
WHERE `course_name`!=\"体育（一）\"
AND `course_name`!=\"体育（二）\"
AND `course_name`!=\"体育（三）\"
AND `course_name`!=\"体育（四）\"
AND `course_name`!=\"体育-Ⅰ\"
AND `xueqi` = \"".$_GET['xueqi']."\"
ORDER BY `num_of_p` DESC ;";
$ll_result = mysql_query($sql);
if(!$ll_result) {
	die("sql select error : " . mysql_error());
}
$list = mysql_num_rows($ll_result);
// echo $list;
// exit();
set_time_limit(0);
	$width = 800; //显示的进度条长度，单位 px
	$total = 500; //总共需要操作的记录数
	$pix = $width / $list; //每条记录的操作所占的进度条单位长度
	$progress = 0; //当前进度条长度
	$cnt = 0;
	?>



	<script language="JavaScript">
	function updateProgress(sMsg, iWidth)
	{
		document.getElementById("status").innerHTML = sMsg;
		document.getElementById("progress").style.width = iWidth + "px";
		document.getElementById("percent").innerHTML = parseInt(iWidth / <?php echo $width; ?> * 100) + "%";
	}
	</script>
	<div style="margin: 4px; padding: 8px; border: 1px solid gray; background: #EAEAEA; width: <?php echo $width+8; ?>px">
		<div><font color="gray">进度</font></div>
		<div style="padding: 0; background-color: white; border: 1px solid navy; width: <?php echo $width; ?>px">
			<div id="progress" style="padding: 0; background-color: #FFCC66; border: 0; width: 0px; text-align: center; height: 16px"></div>
		</div>
		<div id="status">&nbsp;</div>
		<div id="percent" style="position: relative; top: -30px; text-align: center; font-weight: bold; font-size: 8pt">0%</div>
	</div>



	<?php
	while($row = mysql_fetch_assoc($ll_result)) {
		if(!isset($id2zc[$row['teacher_id']])) {
			continue;
		}
		$cnt++;
		?>

		<script language="JavaScript">
		<?php
		echo "updateProgress(\"正在计算第“".$cnt."”条记录....\", ".min($width, intval($progress)).");";
		?>

		</script>

		<?php
	flush(); //将输出发送给客户端浏览器，使其可以立即执行服务器端输出的 JavaScript 程序。
	$progress += $pix;

	# 重复课哈希
	# 用数组 cfk_string 标记
	# 如果相同教师在本学期上了同一门课（经过前面过滤，课序号已经唯一了），则重复课+1
	# 即 教师号_课程号 相同判断为重复课
	# 2015-06-01 :
	# 		英语分级教学算不同课程（虽然教师号相同）。
	#		修改哈希算法加上等级
	var_dump($row['course_alias']);
	if (preg_match("/([^0-9]){1,8}\d\d-([a-zA-Z])\d班/", $row['course_alias'],$matchs) == 1) {
		if (strlen($row['course_id']) == 9) {
			$cfk_string = $row['teacher_id']."_".substr($row['course_id'], 0, 7)."_".$matchs[2];
		}else if(strlen($row['course_id']) == 12){
			$cfk_string = $row['teacher_id']."_".substr($row['course_id'], 0, 10)."_".$matchs[2];
		}
		var_dump($matchs);
		var_dump($cfk_string);
		//die("asdfa");
	} else {
		if (strlen($row['course_id']) == 9) {
			$cfk_string = $row['teacher_id']."_".substr($row['course_id'], 0, 7)."_";
		}else if (strlen($row['course_id']) == 12){
			$cfk_string = $row['teacher_id']."_".substr($row['course_id'], 0, 10)."_";
		}
		var_dump($cfk_string);
	}
	if(!isset($cfk[$cfk_string])) {
		$cfk[$cfk_string] = 1;
	} else {
		$cfk[$cfk_string] += 1;
	}
	# 重复课系数
	$C = 0;
	if($cfk[$cfk_string]==1){
		$C = $xishu_rows['CFK1'];
	} elseif($cfk[$cfk_string]==2){
		$C = $xishu_rows['CFK2'];
	} elseif ($cfk[$cfk_string]==3){
		$C = $xishu_rows['CFK3'];
	} else {
		$C = $xishu_rows['CFK4'];
	}

	// # 重复课哈希，不需要课程号
	// if(!isset($iscf[$row['course_index']."_".substr($row['course_id'], 0, 7)."_".$row['teacher_id']])) {
	// 	$iscf[$row['course_index']."_".substr($row['course_id'], 0, 7)."_".$row['teacher_id']] = 1;
	// } else {
	// 	continue;
	// }


	#计算K值:这里比较复杂了，一个一个来吧....
	#首先看人数对k的影响
	$K = 0;
	$num_of_people = $row['num_of_p'];
	if($num_of_people < $xishu_rows['M0']) {
		$K = $xishu_rows['M0K'];
	}
	else if($num_of_people >= $xishu_rows['M0K']
		&& $num_of_people <= $xishu_rows['NOP1']){
		$K = $xishu_rows['M1K'];
}
else if($num_of_people > $xishu_rows['NOP1']
	&& $num_of_people <= $xishu_rows['L1']){
	$duo = floor(($num_of_people - $xishu_rows['NOP1'])/5);
$K = $xishu_rows['M1K'] + $duo * $xishu_rows['L1K'];
}
else if($num_of_people > $xishu_rows['L1']
	&& $num_of_people <= $xishu_rows['L2']){
	$K = $xishu_rows['M1K'] + floor(($xishu_rows['L1'] - $xishu_rows['NOP1'])/5)*$xishu_rows['L1K'];
$duo = floor(($num_of_people - $xishu_rows['L1'])/5);
$K += $duo * $xishu_rows['L2K'];
}
else if($num_of_people > $xishu_rows['L2']){

	$K = $xishu_rows['M1K'] + floor(($xishu_rows['L1'] - $xishu_rows['NOP1'])/5)*$xishu_rows['L1K'];
	$K += floor(($xishu_rows['L2'] - $xishu_rows['L1'])/5) * $xishu_rows['L2K'];
		//$K = $xishu_rows['M1K'] + $duo * $xishu_rows['L2K'];

}

//$renshuxishu = sprintf("%.3f", $K);
//$K = $renshuxishu;
$renshuxishu = $K;

	# 难度系数 本科 都为 1
	$D = 1;
	# 专业课对D的影响
$zhuanyeke = $row['course_id'][4];
if($zhuanyeke == 'D' || $zhuanyeke == 'E' || $zhuanyeke == 'F') {
	$D += $xishu_rows['ZYK'];
	$zhuanyekexishu = $xishu_rows['ZYK'];
}else{
	$zhuanyekexishu = 0;
}

	# 二表B，三表学生授课对K的影响
	$arr = explode(" ",$row['heban']);
	$intNumberBanji = count($arr);
	$boolHunban = false;
	$sb = 0; $yb = 0;
	# 三表一表混班处理
	//echo "<br />";
	//echo "==".$row['heban']."#".$intNumberBanji."# ";
	//print_r($arr);
	//echo $intNumberBanji;
	for ($heban_row=0 ; $heban_row < $intNumberBanji; $heban_row++) {
		if(empty($arr[$heban_row])) continue;
		//echo $arr[$heban_row];
		//echo $row['heban'];
		if($issb[$arr[$heban_row]]) {
			$sb++;
		}else{
			$yb++;
		}
		if($sb&&$yb){
			$boolHunban = true;
			break;
		}
	}


	#我也不知道为什么这样是对的。。。
	if (preg_match("/\d\d-([a-zA-Z])\d班/", $row['course_alias'],$matchs) == 1) {
		$shanbiaoxishu = 0;
		//echo $row['course_alias'].$row['teacher_name']."</br>";
	} else {
		$shanbiaoxishu = 0;
		if($boolHunban) {
			//echo $row['heban'];
			$D += $xishu_rows['HHB'];
			$shanbiaoxishu += $xishu_rows['HHB'];
		}else if($sb > 0) {
			$D += $xishu_rows['SB'];
			$shanbiaoxishu += $xishu_rows['SB'];
		}
	}



	# K终于结束了
	# 授课质量为 0 只能手动修改了
	$K += 0;

	# 职称系数
	if(!isset($zc_rows[$id2zc[$row['teacher_id']]])) {
		echo "不存在“".$id2zc[$row['teacher_id']]."”职称，请请在系统中添加该职称信息.</br>";
		continue;
	}
	$Z = $zc_rows[$id2zc[$row['teacher_id']]];//从教师库获取的职称信息

	# 计划学时
	$H = $row['xueshi'];


	#教分公式
	if($row['num_of_p']==0)
		$S = 0;
	else
		//$S = sprintf("%.3f", $H * $K * $C * $Z * $D);
		$S = $H * $K * $C * $Z * $D;

	# 完事，写到正式表里面去
	if(!isset($isupdate[$row['xueqi']."_".$row['course_id']."_".$row['teacher_id']."_".$row['course_index']])) {
		$sql = "INSERT INTO `lilun` (
			xueqi,
			course_id,
			course_name,
			course_index,
			course_alias,
			teacher_yuanxi,
			teacher_id,
			teacher_name,
			teacher_zc,
			heban,
			xueshi,
			num_of_p,
			xishu_people,
			xishu_cfk,
			xishu_zyk,
			xishu_sb,
			xishu_zl,
			xishu_nd,
			xishu_zc,
			guocheng,
			jiaofen
			) VALUES (
			".$row['xueqi'].",
			\"".$row['course_id']."\",
			\"".$row['course_name']."\",
			\"".$row['course_index']."\",
			\"".$row['course_alias']."\",
			\"".$row['teacher_yuanxi']."\",
			\"".$row['teacher_id']."\",
			\"".$row['teacher_name']."\",
			\"".$id2zc[$row['teacher_id']]."\",
			\"".$row['heban']."[".$row['course_alias']."]\",
			".$row['xueshi'].",
			".$row['num_of_p'].",
			".$renshuxishu.",
			".$C.",
			".$zhuanyekexishu.",
			".$shanbiaoxishu.",
			0,
			".$D.",
			\"".$Z."\",
			\"".$H."*". $K."*".$C."*".$Z."*".$D."\",
			".$S."
			)";
		} else {
			$sql = "UPDATE `lilun` SET
			`xueqi`=\"".$row['xueqi']."\",
			`course_id`=\"".$row['course_id']."\",
			`course_name`=\"".$row['course_name']."\",
			`course_index`=\"".$row['course_index']."\",
			`course_alias`=\"".$row['course_alias']."\",
			`teacher_yuanxi`=\"".$row['teacher_yuanxi']."\",
			`teacher_id`=\"".$row['teacher_id']."\",
			`teacher_name`=\"".$row['teacher_name']."\",
			`teacher_zc`=\"".$id2zc[$row['teacher_id']]."\",
			`heban`=\"".$row['heban']."\",
			`xueshi`=\"".$row['xueshi']."\",
			`num_of_p`=\"".$row['num_of_p']."\",
			`xishu_people`=\"".$renshuxishu."\",
			`xishu_cfk`=\"".$C."\",
			`xishu_zyk`=\"".$zhuanyekexishu."\",
			`xishu_sb`=\"".$shanbiaoxishu."\",
			`xishu_zl`=\"0\",
			`xishu_nd`=\"".$D."\",
			`xishu_zc`=\"".$Z."\",
			`guocheng`=\"".$H."*". $K."*".$C."*".$Z."*".$D."\",
			`jiaofen`=\"".$S."\"
			WHERE `teacher_id`=\"".$row['teacher_id']."\"
			AND `xueqi`=\"".$row['xueqi']."\"
			AND `course_id`=\"".$row['course_id']."\"
			AND `course_index`=\"".$row['course_index']."\"
			;";
		}
		$result = mysql_query($sql);

		if(!$result) {
			echo ("sql error : ".mysql_error());
			echo "<br />".$sql;
			exit();
		}
}


// header("location: ./error.php?txt="."计算完成.");
// exit();



?>
<script language="JavaScript">
updateProgress("操作完成！", <?php echo $width; ?>);
</script>
<?php
echo "<a class=\"btn btn-primary\" href=\"./index.php\" role=\"button\">返回首页</a>";

flush();
?>


<?php include('./footer.php'); ?>
</body>
</html>
