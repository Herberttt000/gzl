<?php
	# 
	# 2014.10.14

	include './../config.php';
	include './../functions.php';

	#身份验证
	if(!isset($_SESSION['rank'])) {
		header("location: ./error.php?txt="."请登录后再操作.");
		exit();
	}else if(!is_admin($_SESSION['rank'])) {
		header("location: ./error.php?txt="."你的权限不够.");
		exit();
	}else{
		$rank = 1;
	}

	
?>
<?php
	include './header.php';
?>
<h1 class="page-header">导入数据</h1>
	<article class="col-md-12 maincontent">
						<ul>
							<li><a href="./import_jingsai.php">竞赛数据导入</a></li>
							<li><a href="./import_jiaowu.php">教务津贴数据导入</a></li>
							<li><a href="./import_qita.php">其他数据导入</a></li>
							<li><a href="./import_yjs.php">研究生数据导入</a></li>
							<li><a href="./import_chengren.php">成人数据导入</a></li>
							<li><a href="./import_shiyan.php">实验数据导入</a></li>
							<li><a href="./import_qiankao.php">欠考数据导入</a></li>
						</ul>
	</article>

<?php include './footer.php'; ?>

</body>
</html>