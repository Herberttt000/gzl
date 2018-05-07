<?php
	
	include dirname(__FILE__) . './../config.php';
	include dirname(__FILE__) . './../functions.php';
	
	# 判断是否登录
	if(!isset($_SESSION['username'])) {
		header("location: ./error.php?txt="."请登录后再操作.");
		exit();
	}

	# 检验权限
	if(!$_SESSION['calc_shijian']) {
		header("location: ./error.php?txt="."您没有计算实践课工作量的权限.");
		exit();
	}
?>
<?php
	include './header.php';

?>

<?php

/*
* 系数更新
*/
$sql = "SELECT * FROM `jisuanxishu`;";
$result = mysql_query($sql);
if(!$result) {
	die("sql select error jisuanxishu" . mysql_error());
}
$xishu_rows = array();
while($row = mysql_fetch_assoc($result)) {
	$xishu_rows[$row['name']] = $row['value'];
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

$sql = "SELECT * FROM `teachers` WHERE `xueqi`=\"".$_GET['xueqi']."\";";
$result = mysql_query($sql);
if(!$result) {
	die("sql error: ".mysql_error());
}
$teacher2xishu = array();
while ($row = mysql_fetch_assoc($result)) {
	$teacher2xishu[$row['teacher_id']] = $row['zhicheng'];
}


## let begin !
## 开始计算
$sql = "SELECT * FROM `shijian_temp` WHERE `xueqi`=\"".$_GET['xueqi']."\" ORDER BY `num_of_p` DESC;";
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

	#该教师职称系数
	$zhichengxishu = $zc_rows[$teacher2xishu[$row['teacher_id']]];
	
	#课程设计
	if($row['shijian_type']=="课程设计") {
		if($row['num_of_p'] <= $xishu_rows['NOP1']) {
			$jiaofen = $xishu_rows['KCSJ'] * $row['zhoushu'] * $row['num_of_p'] * $zhichengxishu;
			$guocheng = $xishu_rows['KCSJ'] ."*". $row['zhoushu'] ."*". $row['num_of_p'] ."*". $zhichengxishu;
		}
		else if($row['num_of_p'] <= 2*$xishu_rows['NOP1']) {
			$jiaofen1 = ($xishu_rows['KCSJ']-0.1) * $row['zhoushu'] * ($row['num_of_p']-$xishu_rows['NOP1']) * $zhichengxishu;
			$jiaofen2 = $xishu_rows['KCSJ'] * $row['zhoushu'] * $xishu_rows['NOP1'] * $zhichengxishu;
			$guocheng = $xishu_rows['KCSJ'] ."*". $row['zhoushu'] ."*". $xishu_rows['NOP1'] ."*". $zhichengxishu;
			$guocheng .= "+(".$xishu_rows['KCSJ'] ."-0.1)*". $row['zhoushu'] ."*(". ($row['num_of_p']-$xishu_rows['NOP1']).")*". $zhichengxishu;
			$jiaofen = $jiaofen1 + $jiaofen2;
		}
		else{
			$jiaofen1 = $xishu_rows['KCSJ'] * $row['zhoushu'] * $xishu_rows['NOP1'] * $zhichengxishu;
			$jiaofen2 = ($xishu_rows['KCSJ']-0.1) * $row['zhoushu'] * $xishu_rows['NOP1'] * $zhichengxishu;
			$jiaofen3 = ($xishu_rows['KCSJ']-0.2) * $row['zhoushu'] * ($row['num_of_p']-2*$xishu_rows['NOP1']) * $zhichengxishu;
			$guocheng = $xishu_rows['KCSJ'] ."*". $row['zhoushu'] ."*". $xishu_rows['NOP1'] ."*". $zhichengxishu;
			$guocheng .= "+(".$xishu_rows['KCSJ'] ."-0.1)*". $row['zhoushu'] ."*". ($xishu_rows['NOP1']) ."*". $zhichengxishu;
			$guocheng .= "+(".$xishu_rows['KCSJ'] ."-0.2)*". $row['zhoushu'] ."*(". $row['num_of_p'] ."-".(2*$xishu_rows['NOP1']).")*". $zhichengxishu;
			$jiaofen = $jiaofen1 + $jiaofen2 + $jiaofen3;
		}	
	}
	#生产实习
	if($row['shijian_type']=="生产实习") {
		if($row['didian']=="市外") {
			$jiaofen = $xishu_rows['SCSX'] * $row['zhoushu'] * $row['num_of_p'] * $zhichengxishu * $xishu_rows['SCSX2'];
			$guocheng = $xishu_rows['SCSX'] ."*". $row['zhoushu'] ."*". $row['num_of_p'] ."*". $zhichengxishu."*".$xishu_rows['SCSX2'];
		} else {
			$jiaofen = $xishu_rows['SCSX'] * $row['zhoushu'] * $row['num_of_p'] * $zhichengxishu ;
			$guocheng = $xishu_rows['SCSX'] ."*". $row['zhoushu'] ."*". $row['num_of_p'] ."*". $zhichengxishu;
		}
	}
	
	#毕业设计
	if($row['shijian_type']=="毕业设计") {
		if( $row['num_of_p'] <= 8 && $row['num_of_p'] >= 0) {
			$jiaofen = $xishu_rows['BYSJ'] * $row['zhoushu'] * $row['num_of_p'] * $zhichengxishu;
			$guocheng = $xishu_rows['BYSJ'] ."*". $row['zhoushu'] ."*". $row['num_of_p'] ."*". $zhichengxishu;
		}else{
			//超过8人
			$jiaofen = $xishu_rows['BYSJ'] * $row['zhoushu'] * 8 * $zhichengxishu;
			$jiaofen += ($xishu_rows['BYSJ'] - 0.15) * $row['zhoushu'] * ($row['num_of_p'] - 8) * $zhichengxishu;
			$guocheng = $xishu_rows['BYSJ'] ."*". $row['zhoushu'] ."*8*". $zhichengxishu;
			$guocheng .= "+(".$xishu_rows['BYSJ']."-0.15)*".$row['zhoushu']."*(".$row['num_of_p']."-8)*".$zhichengxishu;
			//echo $guocheng;
		}
	}

	#金工实习
	if($row['shijian_type']=="金工实习") {
		$jiaofen = $xishu_rows['JGSX'] * $row['banjishu'] * $zhichengxishu;
		$guocheng = $xishu_rows['JGSX'] ."*". $row['banjishu'] ."*". $zhichengxishu;
	}

	#综合性设计与训练
	if($row['shijian_type']=="综合性设计与训练") {
		$jiaofen = $xishu_rows['ZHXSJYXL'] * $row['zhoushu'] * $row['num_of_p'] * $zhichengxishu;
		$guocheng = $xishu_rows['ZHXSJYXL'] ."*". $row['zhoushu'] ."*". $row['num_of_p'] ."*". $zhichengxishu;
	}

	#分散性实习与实践
	if($row['shijian_type']=="分散性实习与实践") {
		$jiaofen = $xishu_rows['FSXSXYSJ'] * $row['zhoushu'] * $row['num_of_p'] * $zhichengxishu;
		$guocheng = $xishu_rows['FSXSXYSJ'] ."*". $row['zhoushu'] ."*". $row['num_of_p'] ."*". $zhichengxishu;
	}	

	//$jiaofen = sprintf("3f", $jiaofen);

	# 完事，写到正式表里面去
	# 把操作人员的信息写到正式表里面 方便按学院删除
	$sql = "INSERT INTO `shijian` (
		xueqi,
		teacher_id,
		teacher_name,
		teacher_zc,
		shijian_name,
		course_id,
		shijian_type,
		zhichengxishu,
		zhoushu,
		num_of_p,
		banji,
		didian,
		guocheng,
		jiaofen
		) VALUES (
		".$row['xueqi'].",
		\"".$row['teacher_id']."\",
		\"".$row['teacher_name']."\",
		\"".$teacher2xishu[$row['teacher_id']]."\",
		\"".$row['shijian_name']."\",
		\"".$row['course_id']."\",
		\"".$row['shijian_type']."\",
		\"".$zhichengxishu."\",
		\"".$row['zhoushu']."\",
		\"".$row['num_of_p']."\",
		\"".$row['banji']."\",
		\"".$row['didian']."\",
		\"".$guocheng."\",
		\"".$jiaofen."\"
		)";
	$result = mysql_query($sql);
	if(!$result) {
		die("sql error : ".mysql_error());
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
