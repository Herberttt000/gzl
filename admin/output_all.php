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

    $sql="SELECT DISTINCT xueyuan FROM teachers";
    $result=mysql_query($sql);
    $result1=mysql_query($sql);

?>
<?php
    include './header.php'
?>


    <h1 class="page-header">导出人事部表格(按人导出)</h1>

    <!-- container -->
<div class="row">
        <form action="./output_all_handle.php" class="navbar-form navbar-left" method="post">
            <input type="text" name="year" class="form-control" placeholder="输入学期，如“20141”">
            <select name="xueyuan" class="selectpicker form-control">
                <?php
                            while($xueqi_row = mysql_fetch_assoc($result)) {
                                echo "<option value='".$xueqi_row['xueyuan']."' >".$xueqi_row['xueyuan']."</option>";
                            }
                ?>
            </select>

            <span class="text-right"><button type="submit" class="btn btn-success">导出</button></span>
        </form>
        <br />
    </div>
       <h1 class="page-header">导出人事部表格(按人导出)</h1>

       <div class="row">

       <form action="./output_all_handle_by_nian.php" class="navbar-form navbar-left" method="post">
            <input type="text" name="year" class="form-control" placeholder="输入年份，如“2014”">
           <select name="xueyuan" class="selectpicker form-control">
               <?php
               while($xueqi_row1 = mysql_fetch_assoc($result1)) {
                   echo "<option value='".$xueqi_row1['xueyuan']."' >".$xueqi_row1['xueyuan']."</option>";
               }
               ?>
           </select>

            <span class="text-right"><button type="submit" class="btn btn-success">导出</button></span>
        </form>
</div>
    <!-- /container -->

<?php include('./footer.php'); ?>
  </body>
</html>
