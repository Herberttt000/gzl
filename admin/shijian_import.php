<?php
	# 系主任导入实践课程明细
	# 2014.10.14

	include './../config.php';
	include './../functions.php';

    # 判断是否登录
    if(!isset($_SESSION['username'])) {
        header("location: ./error.php?txt="."请登录后再操作.");
        exit();
    }

    # 检验权限
    if(!$_SESSION['import_shijian']) {
        header("location: ./error.php?txt="."您没有导入实践课信息的权限.");
        exit();
    }

	# 列出可删除的学期
$sql = "SELECT DISTINCT `xueqi` FROM `shijian_temp` WHERE `username`=\"".$_SESSION['username']."\";";
$result_xueqi = mysql_query($sql);
if(!$result_xueqi) {
	die("sql error : ".mysql_error());
}

?>
<?php
include './header.php';
?>

<!-- container -->
<div class="container">


	<div class="row">

		<header class="page-header">
			<h1 class="page-title">删除实践明细(<?php echo $_SESSION['xueyuan']; ?>)</h1>
		</header>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span12">

					<form class="form-horizontal" action="./shijian_delete_handle.php" method="post">
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
							<blockquote>
								<p>警告：请务必确认你选择了正确的学期.</p>
							</blockquote>
							<br />
							<button type="submit" class="btn btn-warning">删除</button>
						</fieldset>
						

					</form>
				</div>
			</div>
		</div>


	</div>

	<div class="row">

		<header class="page-header">
			<h1 class="page-title">导入实践明细数据(<?php echo $_SESSION['xueyuan']; ?>)</h1>
		</header>
		<div class="container-fluid">
			<div class="row-fluid">
				<div class="span12">
					<blockquote>
						<p>警告：在你重新上传某个学期的课表时请删除该学期原有数据.</p>
					</blockquote>
					<form action="shijian_import_handle.php" method="post" enctype="multipart/form-data">
						<label for="file">选择文件:<a href="./../files/实践导入(模板).xls">请下载模板填写</a></label>
						<input type="file" name="file" id="file" /> 
						<p class="help-block">请确定你的文件是xls格式.</p>
						<button type="submit" class="btn btn-danger">导入</button>
					</form><a href="./shijian_import_byhand.php"><button class="btn btn-success">手动添加</button></a>
				</div>
			</div>
		</div>


	</div>
</div>	<!-- /container -->





<?php include './footer.php'; ?>

</body>
</html>