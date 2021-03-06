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
	if(!$_SESSION['calc_tiyu']) {
		header("location: ./error.php?txt="."您没有计算体育课工作量的权限.");
		exit();
	}

	$sql = "SELECT DISTINCT `xueqi` FROM `lilun_temp`
			WHERE `course_name`=\"体育（一）\"
			OR `course_name`=\"体育（二）\"
			OR `course_name`=\"体育（三）\"
			OR `course_name`=\"体育（四）\"
            OR `course_name`=\"体育（四）\"
            OR `course_name`=\"体育-Ⅰ\"
            OR `course_name`=\"体育-Ⅱ\"
            OR `course_name`=\"体育-Ⅲ\"
            OR `course_name`=\"体育-Ⅳ\"
            ;";
	$result_xueqi_temp = mysql_query($sql);
	if(!$result_xueqi_temp) {
		die("sql error : ".mysql_error());
	}

	// # 列出可删除的学期
	// $sql = "SELECT DISTINCT `xueqi` FROM `lilun_temp`
	// 		WHERE `course_name`=\"体育（一）\"
	// 		OR `course_name`=\"体育（二）\"
	// 		OR `course_name`=\"体育（三）\"
	// 		OR `course_name`=\"体育（四）\";";
	// $result_xueqi = mysql_query($sql);
	// if(!$result_xueqi) {
	// 	die("sql error : ".mysql_error());
	// }

?>
<?php
include './header.php';
?>

<div class="span9">
	<div class="row">
		<h2>计算体育工作量</h2>
	</div>
	<div class="row">
		<h3 class="page-header">删除理论课计算结果</h3>
		<div class="btn-group">
			<button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
				请删除该学期的理论课即可
			</button>
		</div>

	</div>

	<div class="row">
		<h3 class="page-header">计算体育工作量</h3>
		<div class="btn-group">
			<button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
				点此选择要计算的学期
				<span class="caret"></span>
			</button>
			<ul class="dropdown-menu" role="menu">
				<?php
				while($xueqi_row = mysql_fetch_assoc($result_xueqi_temp)) {
					echo "<li><a href=\"./calc_tiyu_handle.php?xueqi=".$xueqi_row['xueqi']."\">".$xueqi_row['xueqi']."(".get_xq($xueqi_row['xueqi']).")</a></li>";
				}
				?>

			</ul>
		</div>
	</div>
</div>

<?php include('./footer.php'); ?>
</body>
</html>
