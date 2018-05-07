<?php

    include dirname(__FILE__) . './../config.php';
    include dirname(__FILE__) . './../functions.php';

    # 判断是否登录
    if(!isset($_SESSION['username'])) {
        header("location: ./error.php?txt="."请登录后再操作.");
        exit();
    }

    # 检验权限
    if(!$_SESSION['calc_shijian']) {
        header("location: ./error.php?txt="."您没有计算实践课工作量的权限.");
        exit();
    }


    # 列出可删除的学期
    $sql = "SELECT DISTINCT `xueqi` FROM `shijian`;";
    $result_xueqi = mysql_query($sql);
    if(!$result_xueqi) {
        die("sql error : ".mysql_error());
    }

    #数据库没有计算的数据
    $sql = "SELECT DISTINCT `xueqi` FROM `shijian_temp`;";
    $result_xueqi_temp = mysql_query($sql);
    if(!$result_xueqi_temp) {
        die("sql error : ".mysql_error());
    }


?>
<?php
    include './header.php';
?>

<div class="span9">
    <div class="row">
        <h2 class="page-header">实践工作量计算</h2>
        <div class="alert alert-info alert-dismissible" role="alert">
            <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
            <strong>提示!</strong> 如果你发现计算结果有误，那么请删除该学期的计算结果，并要求错误学院导入者纠正并重新上传后，你再重新计算。
        </div>
    </div>
    <div class="row">
        <h3 class="page-header">删除实践课程计算结果</h3>
        <div class="btn-group">
            <button type="button" class="btn btn-danger dropdown-toggle" data-toggle="dropdown">
                点此选择要删除的学期
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
            <?php
                while($xueqi_row = mysql_fetch_assoc($result_xueqi)) {
                    echo "<li><a href=\"./calc_shijian_delete.php?xueqi=".$xueqi_row['xueqi']."\">".$xueqi_row['xueqi']."(".get_xq($xueqi_row['xueqi']).")</a></li>";
                }
            ?>

            </ul>
        </div>

    </div>

    <div class="row">
        <h3 class="page-header">计算实践教学工作量</h3>
        <div class="btn-group">
            <button type="button" class="btn btn-success dropdown-toggle" data-toggle="dropdown">
                点此选择要计算的学期
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <?php
                while($xueqi_row = mysql_fetch_assoc($result_xueqi_temp)) {
                    echo "<li><a href=\"./calc_shijian_handle.php?xueqi=".$xueqi_row['xueqi']."\">".$xueqi_row['xueqi']."(".get_xq($xueqi_row['xueqi']).")</a></li>";
                }
                ?>

            </ul>
        </div>
    </div>
</div>

<?php include('./footer.php'); ?>
  </body>
</html>
