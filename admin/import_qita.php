<?php
	# 导入其他明细
	# 2014.10.14

	include './../config.php';
	include './../functions.php';

    # 判断是否登录
    if(!isset($_SESSION['username'])) {
        header("location: ./error.php?txt="."请登录后再操作.");
        exit();
    }

    # 检验权限
    if(!$_SESSION['import_qita']) {
        header("location: ./error.php?txt="."您没有导入其他信息的权限.");
        exit();
    }

	# 列出可删除的学期
	$sql = "SELECT DISTINCT `xueqi` FROM `qita`;";
	$result_xueqi = mysql_query($sql);
	if(!$result_xueqi) {
		die("sql error : ".mysql_error());
	}

	
?>
<?php
	include './header.php';
?>


<h1 class="page-header">导入其他数据</h1>
		<div class="alert alert-info alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<strong>必读提示!</strong>如果您再上传成功以后发现数据有误，需要修改时。您应该先<strong>删除该学期</strong>的数据，再重新导入<strong>正确的并包含该学期所有数据的</strong>Excel。 
		</div>
	<article class="col-md-12 maincontent">

		<form action="import_qita_handle.php" method="post" enctype="multipart/form-data">
			<label for="file">选择文件:<a href="./../files/其他.xls">请下载模板填写</a></label>
			<input type="file" name="file" id="file" /> 
			<p class="help-block">请确定你的文件是xls格式.</p>
			<button type="submit" class="btn btn-danger">导入</button>
		</form>
		<form class="form-horizontal" action="./import_qita_delete_handle.php" method="post">
						<fieldset>
							<!-- Select Basic -->
							<h3>选择要删除的学期</h3>

							<select name="xueqi" class="input-xlarge">
								<?php
								while($xueqi_row = mysql_fetch_assoc($result_xueqi)) {
									echo "<option>".$xueqi_row['xueqi']."</option>";
								}
								?>
							</select>
							<br />
							<br />
							<blockquote>
								<p>警告：请务必确认你选择了正确的学期.</p>
							</blockquote>
							<br />
							<button type="submit" class="btn btn-warning">删除</button>
						</fieldset>
						

					</form>
	</article>



<?php include './footer.php'; ?>

</body>
</html>