<?php

include dirname(__FILE__) . './../config.php';
include dirname(__FILE__) . './../functions.php';

	# 判断是否登录
	if(!isset($_SESSION['username'])) {
		header("location: ./error.php?txt="."请登录后再操作.");
		exit();
	}

	# 检验权限
	if(!$_SESSION['calc_tiyu']) {
		header("location: ./error.php?txt="."您没有计算体育课工作量的权限.");
		exit();
	}
?>
<?php
include './header.php';

?>
<p id="p1">Hello World!</p>
<?php
/*
* S1 =  H(计划学时) * 0.65 * C(重复课系数) * Z(职称系数)
*/

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

//获取班级是否是3表(体育课不考虑)
// $sql = "SELECT * FROM `class`;";
// $result = mysql_query($sql);
// if(!$result) {
// 	die("sql select error class: " . mysql_error());
// }
// $issb = array();
// while ($row = mysql_fetch_assoc($result)) {
// 	if(substr($row['zhuanye'], -1) == "."
// 		|| substr($row['zhuanye'], -1) == "L" ) {
// 		$issb[$row['name']] = 1;
// 	}else{
// 		$issb[$row['name']] = 0;
// 	}
// }

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
$sql = "SELECT * FROM `teachers` WHERE `xueqi`=\"".$_GET['xueqi']."\";";
$result_t = mysql_query($sql);
if(!$result_t) {
	die("SQL ERROR : " . mysql_error());
}
$id2zc = array();
while ($row_t = mysql_fetch_assoc($result_t)) {
	$id2zc[$row_t['teacher_id']] = $row_t['zhicheng'];
}
//var_dump($id2zc);
# 体育课重复课细节解决:（体育课按照学期计算重复课）
# 建立一个数组记录老师某门课的授课次数;
# 由于我们查询数据的时候按照人数倒序排序了 result
# 所以得到的结果是人数多的优先 ORDER BY `num_of_p` DESC



## let begin !
## 开始计算

# 体育的学期区分
$tiyu_xueqi = array(
	1 => "体育（一）",
	2 => "体育（二）",
	3 => "体育（三）",
	4 => "体育（四）",
	5 => '体育-Ⅰ',
    6 => "体育-Ⅱ",
    7 => "体育-Ⅲ",
    8 => "体育-Ⅳ",
);

#循环计算每个学期的体育
for($tiyu_xueqi_i = 1; $tiyu_xueqi_i <= count($tiyu_xueqi)+1; ++ $tiyu_xueqi_i) {
	$cfk = array();
	#sql 查询
	$sql = "SELECT * FROM `lilun_temp` WHERE `course_name`=\"".$tiyu_xueqi[$tiyu_xueqi_i]."\"  AND `xueqi`=\"".$_GET['xueqi']."\" ORDER BY `num_of_p` DESC;";
	$ll_result = mysql_query($sql);
	if(!$ll_result) {
		die("sql select error : " . mysql_error());
	}
	$list = mysql_num_rows($ll_result);

	set_time_limit(0);

	$width = 800; //显示的进度条长度，单位 px
	$total = 500; //总共需要操作的记录数
	$pix = $width / $list; //每条记录的操作所占的进度条单位长度
	$progress = 0; //当前进度条长度
	$cnt = 0;

	?>





	<?php
	while($row = mysql_fetch_assoc($ll_result)) {
		$cnt++;
		?>


		<?php
	//echo "<p>正在计算第".$tiyu_xueqi_i."学期的体育</p>";
	echo "<script>
document.getElementById(\"p1\").innerHTML=\"正在计算第".$tiyu_xueqi_i."学期的体育\";
</script>";
	flush(); //将输出发送给客户端浏览器，使其可以立即执行服务器端输出的 JavaScript 程序。
	$progress += $pix;


	# 重复课哈希
	$cfk_string = $row['teacher_id']."_".$row['course_name'];
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



	# 职称系数
	$Z = $zc_rows[$id2zc[$row['teacher_id']]];//从教师库获取的职称信息

	# 计划学时
	$H = $row['xueshi'];


	#体育教分公式
	if($row['num_of_p']==0)
		$S = 0;
	else
		$S = $H * $xishu_rows['TYK'] * $C * $Z;
	//$S = $H * $xishu_rows['TYK'] * $C * $Z;

	# 完事，写到正式表里面去
	$sql = "INSERT INTO `lilun` (
		xueqi,
		course_id,
		course_name,
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
		\"".$row['teacher_yuanxi']."\",
		\"".$row['teacher_id']."\",
		\"".$row['teacher_name']."\",
		\"".$id2zc[$row['teacher_id']]."\",
		\"".$row['heban']."\",
		".$row['xueshi'].",
		".$row['num_of_p'].",
		0,
		".$C.",
		0,
		0,
		0,
		1,
		".$Z.",
		\"".$H."*". $xishu_rows['TYK']."*".$C."*".$Z."\",
		".$S."
		)";
$result = mysql_query($sql);
if(!$result) {
	die("sql error : ".mysql_error()."<br />".$sql);
}
}


// header("location: ./error.php?txt="."计算完成.");
// exit();


unset($arr);
}
?>


<?php
echo "<p>操作完成</p>";
echo "<a class=\"btn btn-primary\" href=\"./index.php\" role=\"button\">返回首页</a>";
flush();
?>


<?php include('./footer.php'); ?>
</body>
</html>
