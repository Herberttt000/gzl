<?php
///////////////////////////////////////////////////////////////////////////////////////////
// ADD BY HouBaron 20161108 START
////////////////////////////////////////////////////////////////////////////////////////////
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
	if(!$_SESSION['modify_data']) {
		header("location: ./error.php?txt="."您没有标记专业平台工作量的权限.");
		exit();
	}
?>
<?php
include './header.php';
?>



<h1 class="page-header">导入专业平台课</h1>

    <article class="col-md-12 maincontent">

        <form action="modify_zzptk_handle.php" method="post" enctype="multipart/form-data">
            <label for="file">选择文件:</label>
            <input type="file" name="file" id="file" />
            <p class="help-block">请确定你的文件是xls格式.导入会追加到末尾。</p>
            <button type="submit" class="btn btn-danger">导入</button>
        </form>
        <div class="row">
            <hr />
            请下载示例文件，把待上传的文件整理成对应格式，并通过excel的筛选功能，删除没有必要的行（例如重修，实践课，毕业论文）。
        </div>
        <div class="row">
            <a href="./../files/专业平台课.xls">示例文件下载</a>
        </div>

    </article>
<?php include('./footer.php'); ?>
  </body>
</html>

</html>
<?php
///////////////////////////////////////////////////////////////////////////////////////////
// ADD BY HouBaron 20161108 END
////////////////////////////////////////////////////////////////////////////////////////////
?>
