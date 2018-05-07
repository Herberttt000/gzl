<?php
	
	include dirname(__FILE__) . './../config.php';
	include dirname(__FILE__) . './../functions.php';

	# 判断是否登录
	if(!isset($_SESSION['rank'])) {
		header("location: ./error.php?txt="."请登录后再操作.");
		exit();
	}

	if(!$_SESSION['modify_users']) {
		header("location: ./error.php?txt="."您没有修改用户的权限.");
		exit();
	}

	if($_GET['action']=="delete"){
		$sql = "DELETE FROM `users` WHERE `username` = \"".$_GET['username']."\";";
		$result = mysql_query($sql);
		if(!$result) {
			dir("SQL ERROR : ".mysql_error());
		}
		header("location: ./error.php?txt="."您已成功删除用户名为“".$_GET['username']."”的用户.");
		exit();
	}
	if($_GET['action']=="modify") {
		include './header.php';

		# 查询该用户的信息
		$sql = "SELECT * FROM `users` WHERE `username`=\"".$_GET['username']."\";";
		$result = mysql_query($sql);
		if(!$result) {
			die("SQL ERROR : " . mysql_error());
		}

		$info_row = mysql_fetch_assoc($result);
		//print_r($info_row);
		// exit();

    $sql = "SELECT DISTINCT `xueyuan` FROM `teachers`";
    $result_xy = mysql_query($sql);
    if(!$result_xy){
        die("SQL ERRROR : ".mysql_error());
    }


function print_radio($label, $name, $value){
		$yes = "";
		$no  = "";

		if($value == 1) {
			$yes = "checked";
		}else{
			$no  = "checked";
		}
        echo "<div class=\"form-group\">
                        <label class=\"col-sm-2 control-label\" for=\"input01\">" . $label . "</label>
        <div class=\"radio col-sm-10\">
            <label>
                <input type=\"radio\" name=\"" . $name . "\" value=\"1\" autocomplete=\"off\" ". $yes .">&nbsp;是
            </label>
             &nbsp;&nbsp;&nbsp;&nbsp;
            <label>
                <input type=\"radio\" name=\"" . $name . "\" value=\"0\" autocomplete=\"off\" ". $no .">&nbsp;否
            </label>
        </div>
        </div>";
}
?>

<div class="container-fluid">
    <div calss="row">
        <h1 class="page-header">修改用户信息</h1>
    </div>
    <ol class="breadcrumb">
      <li><a href="./admin_users.php">用户管理</a></li>
      <li class="active">修改用户信息</li>
    </ol>
</div><!-- end container-fluid -->


  <form action="./admin_users_modify_handle.php" method="post" class="form-horizontal">
  	        <div class="form-group">
            <label class="col-sm-2 control-label" for="input01">登录名称</label>
            <div class="col-sm-10">
                <input type="text" name="username1" placeholder="请输入登录名称" value="<?php echo $_GET['username'];?>" class="form-control" disabled>
                <input type="hidden" name="username" value="<?php echo $_GET['username'];?>" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input01">中文名称</label>
            <div class="col-sm-10">
                <input type="text" name="name" placeholder="请输入中文名称" value="<?php echo $info_row['name'];?>" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input01">账号所属学院</label>

            <div class="col-sm-10">
                <select class="form-control" name="xueyuan">

<?php
	if($info_row['xueyuan'] == "无") {
		echo "<option value=\"无\" selected>无</option>";
	}else{
		echo "<option value=\"无\">无</option>";
	}
	if($info_row['xueyuan'] == "全校") {
		echo "<option value=\"全校\" selected>全校</option>";
	}else{
		echo "<option value=\"全校\">全校</option>";
	}
    while($rs_xy = mysql_fetch_assoc($result_xy)) {
    	if($rs_xy['xueyuan'] == $info_row['xueyuan']) {
        	echo "<option value=\"".$rs_xy['xueyuan']."\" selected>".$rs_xy['xueyuan']."</option>";
    	}else{
        	echo "<option value=\"".$rs_xy['xueyuan']."\">".$rs_xy['xueyuan']."</option>";
    	}
    }
?>
              </select>                
              <p class="text-muted">查询和导入权限需要这个信息</p>
          </div>
      </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input01">用户密码</label>
            <div class="col-sm-10">
                <input type="password" name="password" placeholder="如不修改，请留空" class="form-control">
            </div>
        </div>



<table>
<?php
	print_radio('导入教师信息', 'import_teachers', $info_row['import_teachers']);
	print_radio('导入班级信息', 'import_banji', $info_row['import_banji']);
	print_radio('导入理论课信息', 'import_lilun', $info_row['import_lilun']);
	print_radio('导入竞赛信息', 'import_jingsai', $info_row['import_jingsai']);
	print_radio('导入教务津贴', 'import_jiaowu', $info_row['import_jiaowu']);
	print_radio('导入其他信息', 'import_qita', $info_row['import_qita']);
	print_radio('导入欠考信息', 'import_qiankao', $info_row['import_qiankao']);
	print_radio('导入研究生信息', 'import_yanjiusheng', $info_row['import_yanjiusheng']);
	print_radio('导入实验信息', 'import_shiyan', $info_row['import_shiyan']);
	print_radio('导入成人教育信息', 'import_chengren', $info_row['import_chengren']);
	print_radio('导入实践课信息', 'import_shijian', $info_row['import_shijian']);
	print_radio('修改计算系数', 'modify_jisuanxishu', $info_row['modify_jisuanxishu']);
	print_radio('修改职称系数', 'modify_zcxishu', $info_row['modify_zcxishu']);
	print_radio('修改数据', 'modify_data', $info_row['modify_data']);
	print_radio('计算理论课', 'calc_lilun', $info_row['calc_lilun']);
	print_radio('计算实践课', 'calc_shijian', $info_row['calc_shijian']);
	print_radio('计算体育课', 'calc_tiyu', $info_row['calc_tiyu']);
	print_radio('导出人事处表', 'export_renshichu', $info_row['export_renshichu']);
	print_radio('导出个人表', 'export_geren', $info_row['export_geren']);
	print_radio('查询工作量', 'find_gzl', $info_row['find_gzl']);
	print_radio('管理网站用户', 'modify_users', $info_row['modify_users']);
	print_radio('网站系统管理', 'modify_xitong', $info_row['modify_xitong']);
?>
        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-success">修改</button>
            </div>
        </div>
</form>
</div>

<?php
		include('./footer.php');
		echo "</body></html>";
	}
?>




	

	




