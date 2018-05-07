<?php
      # 用户管理模块 

      # 导入配置
include dirname(__FILE__) . './../config.php';
include dirname(__FILE__) . './../functions.php';

    # 判断是否登录
if(!isset($_SESSION['username'])) {
    header("location: ./error.php?txt="."请登录后再操作.");
    exit();
}

    # 检验权限
if(!$_SESSION['modify_users']) {
    header("location: ./error.php?txt="."您没有修改用户的权限.");
    exit();
}

    $sql = "SELECT DISTINCT `xueyuan` FROM `teachers`";
    $result_xy = mysql_query($sql);
    if(!$result_xy){
        die("SQL ERRROR : ".mysql_error());
    }

function print_radio($label, $name){
        echo "<div class=\"form-group\">
                        <label class=\"col-sm-2 control-label\" for=\"input01\">" . $label . "</label>
        <div class=\"radio col-sm-10\">
            <label>
                <input type=\"radio\" name=\"" . $name . "\" value=\"1\" autocomplete=\"off\">&nbsp;是
            </label>
             &nbsp;&nbsp;&nbsp;&nbsp;
            <label>
                <input type=\"radio\" name=\"" . $name . "\" value=\"0\" autocomplete=\"off\" checked>&nbsp;否
            </label>
        </div>
        </div>";
}


include './header.php';
?>

<div class="container-fluid">
    <div calss="row">
        <h1 class="page-header">添加用户</h1>
    </div>
    <ol class="breadcrumb">
      <li><a href="./admin_users.php">用户管理</a></li>
      <li class="active">添加用户</li>
    </ol>
    <div calss="row">
    <form action="./admin_users_add_handle.php" method="post" class="form-horizontal">
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input01">登录名称</label>
            <div class="col-sm-10">
                <input type="text" name="username" placeholder="请输入登录名称" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input01">中文名称</label>
            <div class="col-sm-10">
                <input type="text" name="name" placeholder="请输入中文名称" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input01">账号所属学院</label>
            <div class="col-sm-10">
                <select class="form-control" name="xueyuan">
                    <option value="无">无</option>
                    <option value="全校">全校</option>
<?php
    while($rs_xy = mysql_fetch_assoc($result_xy)) {
        echo "<option value=\"".$rs_xy['xueyuan']."\">".$rs_xy['xueyuan']."</option>";
    }
?>
              </select>                
              <p class="text-muted">查询和导入权限需要这个信息</p>
          </div>

        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label" for="input01">用户密码</label>
            <div class="col-sm-10">
                <input type="text" name="password" placeholder="请输入该用户的初始密码" class="form-control">
            </div>
        </div>

<?php
    print_radio('用户管理权限', 'modify_users');
    print_radio('导入教师信息', 'import_teachers');
    print_radio('导入班级信息', 'import_banji');
    print_radio('导入理论课信息', 'import_lilun');
    print_radio('导入竞赛工作量', 'import_jingsai');
    print_radio('导入教务津贴', 'import_jiaowu');
    print_radio('导入其他工作量', 'import_qita');
    print_radio('导入欠考信息', 'import_qiankao');
    print_radio('导入研究生工作量', 'import_yanjiusheng');
    print_radio('导入实验工作量', 'import_shiyan');
    print_radio('导入成人教育工作量', 'import_chengren');
    print_radio('导入实践课信息', 'import_shijian');
    print_radio('修改计算系数', 'modify_jisuanxishu');
    print_radio('修改职称系数', 'modify_zcxishu');
    print_radio('修改工作量数据', 'modify_data');
    print_radio('计算理论课工作量', 'calc_lilun');
    print_radio('计算实践课工作量', 'calc_shijian');
    print_radio('计算体育课工作量', 'calc_tiyu');
    print_radio('导出人事处表格', 'export_renshichu');
    print_radio('导出个人工作量表格', 'export_geren');
    print_radio('查询工作量权限', 'find_gzl');
    print_radio('网站系统功能管理', 'modify_xitong');
?>


        <div class="form-group">
            <div class="col-sm-offset-2 col-sm-10">
                <button type="submit" class="btn btn-success">添加</button>
            </div>
        </div>
    </form>
    </div>
<div><!-- end container-fluid -->
    <?php
    include './footer.php';
    ?>


</body>
</html>