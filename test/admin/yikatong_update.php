<?php
	# 导入研究生明细
	# 2014.10.14

	include './../config.php';
	include './../functions.php';





	
?>
<?php
	include './header.php';
?>


<h1 class="page-header">一卡通数据</h1>
		<div class="alert alert-info alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<strong>必读提示!</strong>如果您再上传成功以后发现数据有误，需要修改时。您应该先<strong>删除该学期</strong>的数据，再重新导入<strong>正确的并包含该学期所有数据的</strong>Excel。 
		</div>
	<article class="col-md-12 maincontent">

		<form action="yikatong_update_handle.php" method="post" enctype="multipart/form-data">
			<label for="file">选择文件:<a href="./../files/研究生.xls">请下载模板填写</a></label>
			<input type="file" name="file" id="file" /> 
			<p class="help-block">请确定你的文件是xls格式.</p>
			<button type="submit" class="btn btn-danger">导入</button>
		</form>
	</article>



<?php include './footer.php'; ?>

</body>
</html>