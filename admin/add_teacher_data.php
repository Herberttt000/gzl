<?php
	###
	#	导入教师信息
	###

	# 导入配置
	include dirname(__FILE__) . './../config.php';
	include dirname(__FILE__) . './../functions.php';

	# 判断是否登录
	if(!isset($_SESSION['username'])) {
		header("location: ./error.php?txt="."请登录后再操作.");
		exit();
	}

	# 检验权限
	if(!$_SESSION['import_teachers']) {
		header("location: ./error.php?txt="."您没有导入教师信息的权限.");
		exit();
	}
?>

<?php include './header.php';?>



<h1 class="page-header">导入教师数据</h1>

	<article class="col-md-12 maincontent">

		<form action="add_teacher_data_handle.php" method="post" enctype="multipart/form-data">
			<label for="file">选择文件:</label>
			<input type="file" name="file" id="file" /> 
			<p class="help-block">请确定你的文件是xls格式.</p>
			<button type="submit" class="btn btn-danger">导入</button>
		</form>
		<div calss="row">
		<hr />
		excel格式如下：
		</div>
		<div class="row">
		<img src="./../files/img/teacher_excel.png">
		</div>
	</article>

<?php include('./footer.php'); ?>
  </body>
</html>
