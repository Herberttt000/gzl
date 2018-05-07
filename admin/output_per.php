<?php
	
	include dirname(__FILE__) . './../config.php';
	include dirname(__FILE__) . './../functions.php';

	# 判断是否登录
	if(!isset($_SESSION['username'])) {
		header("location: ./error.php?txt="."请登录后再操作.");
		exit();
	}

	# 检验权限
	if(!$_SESSION['export_geren']) {
		header("location: ./error.php?txt="."您没有导出个人工作量表的权限.");
		exit();
	}



?>
<?php
	include './header.php'
?>

 	<div class="container">       

 		<div class="row">
		<h3 class="page-header">导出个人理论表格(按课程导出)</h3>
		<form action="./output_per_lilun_handle.php" class="navbar-form navbar-left" method="post">
			<input type="text" name="year" class="form-control" placeholder="输入学期，如“20141”">
		
			<span class="text-right"><button type="submit" class="btn btn-success">导出</button></span>
		</form>
	</div>
	<div class="row">
    	<h3 class="page-header">导出个人实践表格(按课程导出)</h3>

		<form action="./output_per_shijian_handle.php" class="navbar-form navbar-left" method="post">
			<input type="text" name="year" class="form-control" placeholder="输入学期，如“20141”">
		
			<span class="text-right"><button type="submit" class="btn btn-success">导出</button></span>
		</form>
	</div>
</div>
	<!-- /container -->

<?php include('./footer.php'); ?>
  </body>
</html>
