<?php
    include dirname(__FILE__) . './../config.php';
    include dirname(__FILE__) . './../functions.php';

    # 判断是否登录
    if(!isset($_SESSION['username'])) {
        header("location: ./error.php?txt="."请登录后再操作.");
        exit();
    }

    # 检验权限
    if(!$_SESSION['export_renshichu']) {
        header("location: ./error.php?txt="."您没有导出人事处表的权限.");
        exit();
    }



?>
<?php
    include './header.php';
?>
   <h1 class="page-header">导出汇总（测试！！）</h1>
   <div class="row">
   <form action="./output_all_handle_by_nian.php" class="navbar-form navbar-left" method="post">
        <input type="text" name="year" class="form-control" placeholder="输入年份，如“2014”">
        <span class="text-right"><button type="submit" class="btn btn-success">导出</button></span>
        <input type="hidden" name="summary" value="HouBaron20170517">
    </form>
</div>
<?php include('./footer.php'); ?>
  </body>
</html>
