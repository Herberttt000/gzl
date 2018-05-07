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



?>
<?php
include './header.php';
?>

<?php

	if(!empty($_POST)){
		//print_r($_POST);
		//exit();
		$sql = "INSERT INTO `shijian_temp` (xueqi, teacher_id,
                teacher_name, shijian_name, course_id,
                shijian_type, zhoushu, num_of_p, banji,
                banjishu,
                didian,teacher_xueyuan,teacher_xi,username
                ) VALUES (\"".
                $_POST['xueqi']."\",\"".
                $_POST['teacher_id']."\",\"".
                $_POST['teacher_name']."\",\"".
                $_POST['shijian_name']."\",\"".
                $_POST['course_id']."\",\"".
                $_POST['shijian_type']."\",\"".
                $_POST['zhoushu']."\",\"".
                $_POST['num_of_p']."\",\"".
                $_POST['banji']."\",\"".
                $_POST['banjishu']."\",\"".
                $_POST['didian']."\",\"".
                $_POST['teacher_xueyuan']."\",\"".
                $_POST['teacher_xi']."\",\"".
                $_SESSION['username']."\"
                );";

        $result = mysql_query($sql);
        if(!$result) {
            die("insert error:" . mysql_error());
        }else{
        	?>
        <div class="alert alert-info alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<strong>提交成功!</strong> 
		</div>
		<?php
        }
	}

?>

<!-- container -->
<div class="container">
	<h1> 手动导入实践教学记录 </h1>
	<hr>
	<blockquote>
		<p>警告：提交前请认真核对.</p>
	</blockquote>
	<form class="form-horizontal" method="post">
		<fieldset>
			<div class="control-group">

				<!-- Text input-->
				<label class="control-label" for="input01">学期</label>
				<div class="controls">
					<input type="text" placeholder="" class="input-xlarge" name="xueqi" >
					<p class="help-block">2014年春请填写“20141”，秋填“20142”</p>
				</div>
			</div>

			<div class="control-group">

				<!-- Text input-->
				<label class="control-label" for="input01">教师号</label>
				<div class="controls">
					<input type="text" placeholder="请输入教师号" class="input-xlarge" name="teacher_id" >
					<p class="help-block">教师号一般都为7位数字组成</p>
				</div>
			</div>

			<div class="control-group">

				<!-- Text input-->
				<label class="control-label" for="input01">姓名</label>
				<div class="controls">
					<input type="text" placeholder="" class="input-xlarge" name="teacher_name" >
					<p class="help-block"></p>
				</div>
			</div>

			<div class="control-group">

				<!-- Text input-->
				<label class="control-label" for="input01">学院</label>
				<div class="controls">
					<input type="text" placeholder="" class="input-xlarge" name="teacher_xueyuan" >
					<p class="help-block"></p>
				</div>
			</div>

			<div class="control-group">

				<!-- Text input-->
				<label class="control-label" for="input01">系</label>
				<div class="controls">
					<input type="text" placeholder="" class="input-xlarge" name="teacher_xi" >
					<p class="help-block">没有系所请填学院</p>
				</div>
			</div>

			<div class="control-group">

				<!-- Text input-->
				<label class="control-label" for="input01">实践名称</label>
				<div class="controls">
					<input type="text" placeholder="" class="input-xlarge" name="shijian_name" >
					<p class="help-block"></p>
				</div>
			</div>



			<div class="control-group">

				<!-- Text input-->
				<label class="control-label" for="input01">课程号</label>
				<div class="controls">
					<input type="text" placeholder="" class="input-xlarge" name="course_id" >
					<p class="help-block"></p>
				</div>
			</div>

			<div class="control-group">

				<!-- Text input-->
				<label class="control-label" for="input01">实践类型</label>
				<div class="controls">
					<input type="text" placeholder="" class="input-xlarge" name="shijian_type" >
					<p class="help-block">这项只有六类</p>
				</div>
			</div>

			<div class="control-group">

				<!-- Text input-->
				<label class="control-label" for="input01">周数</label>
				<div class="controls">
					<input type="text" placeholder="" class="input-xlarge" name="zhoushu" >
					<p class="help-block"></p>
				</div>
			</div>

			<div class="control-group">

				<!-- Text input-->
				<label class="control-label" for="input01">人数</label>
				<div class="controls">
					<input type="text" placeholder="" class="input-xlarge" name="num_of_p" >
					<p class="help-block"></p>
				</div>
			</div>

			<div class="control-group">

				<!-- Text input-->
				<label class="control-label" for="input01">班级</label>
				<div class="controls">
					<input type="text" placeholder="" class="input-xlarge" name="banji" >
					<p class="help-block"></p>
				</div>
			</div>



			<div class="control-group">

				<!-- Text input-->
				<label class="control-label" for="input01">班级数</label>
				<div class="controls">
					<input type="text" placeholder="" class="input-xlarge" name="banjishu" >
					<p class="help-block">金工实习必须填写！</p>
				</div>
			</div>

			<div class="control-group">

				<!-- Text input-->
				<label class="control-label" for="input01">地点</label>
				<div class="controls">
					<input type="text" placeholder="" class="input-xlarge" name="didian" >
					<p class="help-block"></p>
				</div>
			</div>

			<div class="control-group">

				<!-- Button -->
				<div class="controls">
					<button type="submit" class="btn btn-success">提交</button>
				</div>
			</div>

		</fieldset>
	</form>

</div>	<!-- /container -->





<?php include './footer.php'; ?>

</body>
</html>