<!DOCTYPE html>
<?php
/*
*    查询修改某教师的工作量明细
* GET : $techer_id;
*         $xueqi；
*/
include "./../config.php";
include "./../functions.php";

    # 判断是否登录
    if(!isset($_SESSION['username'])) {
        header("location: ./error.php?txt="."请登录后再操作.");
        exit();
    }

    # 检验权限
    if(!$_SESSION['modify_data']) {
        header("location: ./error.php?txt="."您没有修改工作量的权限.");
        exit();
    }

if(isset($_GET['teacher_id'])) {
    $teacher_id = $_GET['teacher_id'];
    $sql = "select * from `teachers` where `teacher_id` =\"".$teacher_id."\";";
    $result = mysql_query($sql);
    if(!$result) {
        die("mysql select error:".mysql_error());
    }
    $teacher_rows = mysql_fetch_assoc($result);
    if(empty($teacher_rows)) {
        header("location: ./error.php?txt="."没有查询到教师号为 ".$teacher_id." 的老师.");
        exit();
    }
    //理论授课
    $result_ll = mysql_query("select * from `lilun` where `teacher_id` = \"". $teacher_id ."\" and `xueqi` like '".$_GET['xueqi']."' ORDER BY `course_name` DESC, `num_of_p` DESC, `course_index` DESC, `xishu_cfk` DESC;");
    if(!$result_ll) {
        die("mysql select error:".mysql_error());
    }
    $list_ll  = mysql_num_rows($result_ll);

    #计算理论原始和折合
    $llyuanshi = 0;
    $sql = "SELECT SUM(xueshi) FROM `lilun` WHERE `teacher_id`= \"". $teacher_id ."\" and `xueqi` LIKE '".$_GET['xueqi']."';";
    $result_sumlilun = mysql_query($sql);
    if(!$result_sumlilun) {
        die("sql error ： ". mysql_error());
    }
    $llyuanshi = mysql_fetch_row($result_sumlilun);
    $llzhehe = 0;
    $sql = "SELECT SUM(jiaofen) FROM `lilun` WHERE `teacher_id`= \"". $teacher_id ."\" and `xueqi` LIKE '".$_GET['xueqi']."';";
    $result_sumlilunzhehe = mysql_query($sql);
    if(!$result_sumlilunzhehe) {
        die("sql error ： ". mysql_error());
    }
    $llzhehe = mysql_fetch_row($result_sumlilunzhehe);

    //$row = mysql_fetch_array($result);
    #实践
    $result_sj = mysql_query("select * from `shijian` where `teacher_id` = \"". $teacher_id ."\"  and `xueqi` like '".$_GET['xueqi']."';");
    if(!$result_sj) {
        die("mysql select error:".mysql_error());
    }
    $list_sj  = mysql_num_rows($result_sj);

    #计算实践原始和折合
    $sjyuanshi = 0;
    $sql = "SELECT SUM(zhoushu) FROM `shijian` WHERE `teacher_id`= \"". $teacher_id ."\" and `xueqi` LIKE '".$_GET['xueqi']."';";
    $result_sumshijian = mysql_query($sql);
    if(!$result_sumshijian) {
        die("sql error ： ". mysql_error());
    }
    $sjyuanshi = mysql_fetch_row($result_sumshijian);
    $sjzhehe = 0;
    $sql = "SELECT SUM(jiaofen) FROM `shijian` WHERE `teacher_id`= \"". $teacher_id ."\" and `xueqi` LIKE '".$_GET['xueqi']."';";
    $result_sumshijianzhehe = mysql_query($sql);
    if(!$result_sumshijianzhehe) {
        die("sql error ： ". mysql_error());
    }
    $sjzhehe = mysql_fetch_row($result_sumshijianzhehe);
    // print_r($llyuanshi);
    // print_r($llzhehe);
    // print_r($sjyuanshi);
    // print_r($sjzhehe);
    //exit();

    #实验
    $result_shiyan = mysql_query("select SUM(yuanshi),SUM(zhehe),SUM(jintie) from `shiyan` where `teacher_id` = \"". $teacher_id ."\"  and `xueqi` like '".$_GET['xueqi']."';");
    if(!$result_shiyan) {
        die("mysql select error:".mysql_error());
    }
    $result_shiyan = mysql_fetch_assoc($result_shiyan);

    #竞赛
    $result_jingsai = mysql_query("select SUM(jiaofen) from `jingsai` where `teacher_id` = \"". $teacher_id ."\"  and `xueqi` like '".$_GET['xueqi']."';");
    if(!$result_jingsai) {
        die("mysql select error:".mysql_error());
    }
    $result_jingsai = mysql_fetch_assoc($result_jingsai);

    #教务津贴
    $result_jiaowu = mysql_query("select SUM(jiaofen) from `jiaowu` where `teacher_id` = \"". $teacher_id ."\"  and `xueqi` like '".$_GET['xueqi']."';");
    if(!$result_jiaowu) {
        die("mysql select error:".mysql_error());
    }
    $result_jiaowu = mysql_fetch_assoc($result_jiaowu);

    #其他
    # SUM
    $result_qita = mysql_query("select SUM(jiaofen) from `qita` where `teacher_id` = \"". $teacher_id ."\"  and `xueqi` like '".$_GET['xueqi']."';");
    if(!$result_qita) {
        die("mysql select error:".mysql_error());
    }
    $result_qita = mysql_fetch_assoc($result_qita);
    # rows
    $result_qita_row = mysql_query("select * from `qita` where `teacher_id` = \"". $teacher_id ."\"  and `xueqi` like '".$_GET['xueqi']."';");
    if(!$result_qita_row) {
        die("mysql select error:".mysql_error());
    }

    #成人
    $result_chengren = mysql_query("select SUM(yuanshi),SUM(zhehe),SUM(shijianzhehe) from `chengren` where `teacher_id` = \"". $teacher_id ."\"  and `xueqi` like '".$_GET['xueqi']."';");
    if(!$result_chengren) {
        die("mysql select error:".mysql_error());
    }
    $result_chengren = mysql_fetch_assoc($result_chengren);

    #研究生
    $result_yjs = mysql_query("select SUM(yuanshi),SUM(zhehe),SUM(zhidao),SUM(mubiao),SUM(zdjs) from `yanjiusheng` where `teacher_id` = \"". $teacher_id ."\"  and `xueqi` like '".$_GET['xueqi']."';");
    if(!$result_yjs) {
        die("mysql select error:".mysql_error());
    }
    $result_yjs = mysql_fetch_assoc($result_yjs);

    # 欠考次数
    # SUM
    $result_qiankao = mysql_query("select SUM(jiaofen) from `qiankao` where `teacher_id` = \"". $teacher_id ."\"  and `xueqi` like '".$_GET['xueqi']."';");
    if(!$result_qiankao) {
        die("mysql select error:".mysql_error());
    }
    $result_qiankao = mysql_fetch_assoc($result_qiankao);

    # 汇总
    $sum_all = 0;
    $sum_all = $llzhehe[0] + $sjzhehe[0];
    $sum_all += $result_yjs['SUM(zhehe)'] + $result_yjs['SUM(mubiao)'] + $result_yjs['SUM(zhidao)'] + $result_yjs['SUM(zdjs)'];
    $sum_all += $result_chengren['SUM(zhehe)'] + $result_chengren['SUM(shijianzhehe)'];
    $sum_all += $result_shiyan['SUM(zhehe)'] + $result_shiyan['SUM(jintie)'];
    $sum_all += $result_jingsai['SUM(jiaofen)'];
    $sum_all += $result_jiaowu['SUM(jiaofen)'];
    $sum_all += $result_qita['SUM(jiaofen)'];
}

?>

<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="./../favicon.ico">

    <title>HRBUST</title>

    <!-- Bootstrap core CSS -->
    <link href="./../assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">

    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="./../assets/js/ie-emulation-modes-warning.js"></script>

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->



  </head>

  <body>
<?php
/*
 * 当无数据时，工作量修改页面将不会显示 添加 按钮
 *
 * houbaron
 * 20180509 20:47
 */
    $houbaron_teacher_info = 'SELECT `xi`, `teacher_name`, `zhicheng` FROM `teachers` WHERE `xueqi`=\'' . $_GET['xueqi'] . '\' and `teacher_id`=\'' . $_GET['teacher_id'] . '\';';
    $houbaron_teacher_info = mysql_query($houbaron_teacher_info);
    $houbaron_teacher_info = mysql_fetch_assoc($houbaron_teacher_info);
    if(!$houbaron_teacher_info) {
        die("mysql select error:" . mysql_error());
        exit(0);
    }
?>
<!-- 理论教学工作量明细 add start -->
<div class="modal fade" id="lilun_add_Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
      <div class="modal-content">
          <form action="./modify_lilun_per_handle.php" method="post">

              <div class="modal-header">
                  <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                  <h4 class="modal-title" id="myModalLabel">添加信息</h4>
              </div>
              <div class="modal-body">
                  <table>
                      <thead>
                      <tr>
                          <th>学期</th><td><input name="xueqi" type="text" class="form-control" value="<?php echo $_GET['xueqi']; ?>"></td>
                          <th>人数系数</th><td><input name="xishu_people" type="text" class="form-control" value=""></td>
                      </tr>
                      <tr>
                          <th>教师号</th>
                          <td><input name="teacher_id" type="text" class="form-control" value="<?php echo $_GET['teacher_id']; ?>"></td>
                          <th>重复课系数</th><td><input name="xishu_cfk" type="text" class="form-control" value=""></td>
                      </tr>
                      <tr>
                          <th>姓名</th><td><input name="teacher_name" type="text" class="form-control" value="<?php echo $houbaron_teacher_info['teacher_name'] ?>"></td>
                          <th>专业课系数</th><td><input name="xishu_zyk" type="text" class="form-control" value=""></td>
                      </tr>
                      <tr>
                          <th>职称</th><td><input name="teacher_zc" type="text" class="form-control" value="<?php echo $houbaron_teacher_info['zhicheng']; ?>"></td>
                          <th>三表系数</th><td><input name="xishu_sb" type="text" class="form-control" value=""></td>
                      </tr>
                      <tr>
                          <th>课程号</th><td><input name="course_id" type="text" class="form-control" value=""></td>
                          <th>质量系数</th><td><input name="xishu_zl" type="text" class="form-control" value=""></td>
                      </tr>
                      <tr>
                          <th>课程名</th><td><input name="course_name" type="text" class="form-control" value=""></td>
                          <th>难度系数</th><td><input name="xishu_nd" type="text" class="form-control" value=""></td>
                      </tr>
                      <tr>
                          <th>合班</th><td><input name="heban" type="text" class="form-control" value=""></td>
                          <th>职称系数</th><td><input name="xishu_zc" type="text" class="form-control" value=""></td>

                      </tr>
                      <tr>
                          <th>学时</th><td><input name="xueshi" type="text" class="form-control" value=""></td>
                          <th>计算过程</th><td><input name="guocheng" type="text" class="form-control" value=""></td>
                      </tr>
                      <tr>
                          <th>人数</th><td><input name="num_of_p" type="text" class="form-control" value=""></td>
                          <th>教分</th><td><input name="jiaofen" type="text" class="form-control" value=""></td>
                      </tr>

                      </thead>
                  </table>
                  <br />
                  <br />
                  <div>提示：请确认你的输入正确性</div>
              </div>
              <input type="hidden" name="op" value="lilun">
              <input type="hidden" name="course_index" value="">
              <input type="hidden" name="teacher_yuanxi" value="<?php echo $houbaron_teacher_info['xi']; ?>">
              <input type="hidden" name="add" value="1">
              <input type="hidden" name="year" value="<?php echo $_GET['xueqi']; ?>">

              <div class="modal-footer">
                  <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                  <button type="submit" class="btn btn-primary">添加</button>
              </div>
          </form>
      </div>
  </div>
</div>
<!-- 理论教学工作量明细 add end -->

<!-- 实践教学工作量明细 add start -->
<div class="modal fade" id="shijian_adds_Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="./modify_lilun_per_handle.php" method="post">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">添加</h4>
                </div>
                <div class="modal-body">
                    <table>
                        <thead>
                        <tr>
                            <th>学期</th><td><input name="xueqi" type="text" class="form-control" value="<?php echo $_GET['xueqi'];?>"></td>
                        </tr>
                        <tr>
                            <th>教师号</th><td><input name="teacher_id" type="text" class="form-control" value="<?php echo $_GET['teacher_id'];?>"></td>
                        </tr>
                        <tr>
                            <th>姓名</th><td><input name="teacher_name" type="text" class="form-control" value="<?php echo $houbaron_teacher_info['teacher_name'] ?>"></td>
                        </tr>
                        <tr>
                            <th>职称</th><td><input name="teacher_name" type="text" class="form-control" value="<?php echo $houbaron_teacher_info['teacher_zc'] ?>"></td>
                        </tr>
                        <tr>
                            <th>实践名称</th><td><input name="shijian_name" type="text" class="form-control" value="" ></td>
                        </tr>
                        <tr>
                            <th>课程号</th><td><input name="course_id" type="text" class="form-control" value="" ></td>
                        </tr>
                        <tr>
                            <th>实践类型</th><td><input name="shijian_type" type="text" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <th>周数</th><td><input name="zhoushu" type="text" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <th>人数</th><td><input name="num_of_p" type="text" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <th>班级</th><td><input name="banji" type="text" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <th>地点</th><td><input name="didian" type="text" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <th>计算过程</th><td><input name="guocheng" type="text" class="form-control" value=""></td>
                        </tr>
                        <tr>
                            <th>教分</th><td><input name="jiaofen" type="text" class="form-control" value=""></td>
                        </tr>
                        </thead>
                    </table>
                    <br />
                </div>
                <input type="hidden" name="op" value="shijian">
                <input type="hidden" name="addshijian" value="1">

                <input type="hidden" name="id" value="">
                <input type="hidden" name="year" value="<?php echo $_GET['xueqi'];?>">
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">添加</button>
                </div>
            </form>
        </div>
    </div>
</div>
<!-- 实践教学工作量明细 add end -->


  <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a class="navbar-brand" href="#">工作量计算系统</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right">
            <li><a href="./../index.php">首页</a></li>
            <?php
              if(isset($_SESSION['rank'])) {
            ?>
            <li><a href="./../loginout.php">退出</a></li>
            <?php
              }else{
            ?>
            <li><a href="./../signin.php">登录</a></li>
            <?php
              }
            ?>
          </ul>
<!--           <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search...">
          </form> -->
        </div>
      </div>
    </nav>


<!-- container -->
<div class="container">

    <?php
    if(isset($_GET['teacher_id'])) {
        ?>
        <div class="row">


            <header class="page-header">
                <h1 class="page-title"><?php echo $teacher_rows['teacher_name']; ?> 的工作量
                            <form class="navbar-form navbar-right" method="get">
            <input type="text" name="teacher_id" class="form-control" value="<?php echo $_GET['teacher_id']; ?>">
            <input type="text" name="xueqi" class="form-control" value="<?php echo $_GET['xueqi']; ?>">
            <span class="text-right"><button type="submit" class="btn btn-success">查询</button></span>
        </form></h1>
            </header>
            <div class="container-fluid">

                <h2 class="sub-header">个人工作量明细汇总<span class="label label-info"><?php echo get_xq($_GET['xueqi']); ?></span></h2>
                <div class="span12">
                    <table class="table table-condensed">
                        <thead>
                            <tr>
                                <th>教师号</th>
                                <th>姓名</th>
                                <th>职称</th>
                                <th>学院</th>
                                <th>系</th>
                                <th>是否硕导</th>
                                <th>本科理论原始</th>
                                <th>本科理论折合</th>
                                <th>本科实践原始</th>
                                <th>本科实践折合</th>
                                <th>研究生原始</th>
                                <th>研究生折合</th>
                                <th>研究生指导</th>
                                <th>研究生目标</th>
                                <th>研究生指导竞赛</th>
                                <th>成人原始</th>
                                <th>成人折合</th>
                                <th>成人实践折合</th>
                                <th>实验原始</th>
                                <th>实验折合</th>
                                <th>实验津贴</th>
                                <th>竞赛</th>
                                <th>教务津贴</th>
                                <th>其他</th>
                                <th>欠考次数</th>
                                <th>总计</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                            <?php

                            echo "<td>".$teacher_rows['teacher_id']."</td>";
                            echo "<td>".$teacher_rows['teacher_name']."</td>";
                            echo "<td>".$teacher_rows['zhicheng']."</td>";
                            echo "<td>".$teacher_rows['xueyuan']."</td>";
                            echo "<td>".$teacher_rows['xi']."</td>";
                            echo "<td>".$teacher_rows['issd']."</td>";
                            printf("<td>%.4f</td>", $llyuanshi[0]);
                            printf("<td>%.4f</td>", $llzhehe[0]);

                            //echo "<td>".$llzhehe[0]."</td>";
                            printf("<td>%.4f</td>", $sjyuanshi[0]);
                            //echo "<td>".$sjzhehe[0]."</td>";
                            printf("<td>%.4f</td>", $sjzhehe[0]);
                            echo "<td>".round($result_yjs['SUM(yuanshi)'],4)."</td>";
                            echo "<td>".round($result_yjs['SUM(zhehe)'],4)."</td>";
                            echo "<td>".round($result_yjs['SUM(zhidao)'],4)."</td>";
                            echo "<td>".round($result_yjs['SUM(mubiao)'],4)."</td>";
                            echo "<td>".round($result_yjs['SUM(zdjs)'],4)."</td>";
                            echo "<td>".round($result_chengren['SUM(yuanshi)'],4)."</td>";
                            echo "<td>".round($result_chengren['SUM(zhehe)'],4)."</td>";
                            echo "<td>".round($result_chengren['SUM(shijianzhehe)'],4)."</td>";
                            echo "<td>".round($result_shiyan['SUM(yuanshi)'],4)."</td>";
                            echo "<td>".round($result_shiyan['SUM(zhehe)'],4)."</td>";
                            echo "<td>".round($result_shiyan['SUM(jintie)'],4)."</td>";
                            echo "<td>".round($result_jingsai['SUM(jiaofen)'],4)."</td>";
                            echo "<td>".round($result_jiaowu['SUM(jiaofen)'],4)."</td>";
                            echo "<td>".round($result_qita['SUM(jiaofen)'],4)."</td>";
                            echo "<td>".round($result_qiankao['SUM(jiaofen)'],4)."</td>";
                            printf("<td>%.4f</td>", $sum_all);

                            ?>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <h2 class="sub-header">
                    理论教学工作量明细
                    <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#lilun_add_Modal">添加</button>
                </h2>
                <div class="span12">
                    <table class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>学期</th>
                                <th>教师号</th>
                                <th>姓名</th>
                                <th>职称</th>
                                <th>课程号</th>
                                <th>课程名</th>
                                <th>课序号</th>
                                <th>合班</th>
                                <th>学时</th>
                                <th>人数</th>
                                <th>人数系数</th>
                                <th>重复课系数</th>
                                <th>专业课系数</th>
                                <th>三表系数</th>
                                <th>质量系数</th>
                                <th>难度系数</th>
                                <th>职称系数</th>
                                <th>计算过程</th>
                                <th>教分</th>
                                <th>操作</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for($i = 0; $i < $list_ll; $i++) {
                                $row = mysql_fetch_assoc($result_ll);
                                        //print_r($row);
                                // if($i%2==0) echo "<tr>";
                                // else echo "<tr class=\"success\">";
                                echo "<tr>";
                                echo "<td>".get_xq($row['xueqi'])."</td>";
                                echo "<td>".$row['teacher_id']."</td>";
                                echo "<td>".$row['teacher_name']."</td>";
                                echo "<td>".$row['teacher_zc']."</td>";
                                echo "<td>".$row['course_id']."</td>";
                                echo "<td>".$row['course_name']."</td>";
                                echo "<td>".$row['course_index']."</td>";
                                echo "<td>".$row['heban']."</td>";
                                echo "<td>".$row['xueshi']."</td>";
                                echo "<td>".$row['num_of_p']."</td>";
                                echo "<td>".$row['xishu_people']."</td>";
                                echo "<td>".$row['xishu_cfk']."</td>";
                                echo "<td>".$row['xishu_zyk']."</td>";
                                echo "<td>".$row['xishu_sb']."</td>";
                                echo "<td>".$row['xishu_zl']."</td>";
                                echo "<td>".$row['xishu_nd']."</td>";
                                echo "<td>".$row['xishu_zc']."</td>";
                                echo "<td>".$row['guocheng']."</td>";
                                echo "<td>".sprintf("%.4f",$row['jiaofen'])."</td>";





                                        //echo $teacher_rows['teacher_XY'];
                                        //echo $teacher_rows['teacher_X'];
                                ?>
                                <td>
                                <!-- Button trigger modal -->
<button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#<?php echo $row['id'];?>_Modal">
    修改
</button>
<button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#<?php echo $row['id'];?>_add_Modal">
    添加
</button>
<button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#<?php echo $row['id'];?>_sm_Modal">
    删除
</button>


<!-- Modal -->
<div class="modal fade" id="<?php echo $row['id'];?>_Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="./modify_lilun_per_handle.php" method="post">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">修改信息</h4>
                </div>
                <div class="modal-body">
                    <table>
                        <thead>
                            <tr>
                                <th>学期</th><td><input name="xueqi" type="text" class="form-control" value="<?php echo $row['xueqi'];?>" disabled></td>
                                <th>人数系数</th><td><input name="xishu_people" type="text" class="form-control" value="<?php echo $row['xishu_people'];?>" disabled></td>
                            </tr>
                            <tr>
                                <th>教师号</th>
                                <td><input name="t_id" type="text" class="form-control" value="<?php echo $row['teacher_id'];?>" disabled></td>
                                <th>重复课系数</th><td><input name="xishu_cfk" type="text" class="form-control" value="<?php echo $row['xishu_cfk'];?>" disabled></td>
                            </tr>
                            <tr>
                                <th>姓名</th><td><input name="teacher_name" type="text" class="form-control" value="<?php echo $row['teacher_name'];?>" disabled></td>
                                <th>专业课系数</th><td><input name="xishu_zyk" type="text" class="form-control" value="<?php echo $row['xishu_zyk'];?>" disabled></td>
                            </tr>
                            <tr>
                                <th>职称</th><td><input name="teacher_zc" type="text" class="form-control" value="<?php echo $row['teacher_zc'];?>" disabled></td>
                                <th>三表系数</th><td><input name="xishu_sb" type="text" class="form-control" value="<?php echo $row['xishu_sb'];?>" disabled></td>
                            </tr>
                            <tr>
                                <th>课程号</th><td><input name="course_id" type="text" class="form-control" value="<?php echo $row['course_id'];?>" disabled></td>
                                <th>质量系数</th><td><input name="xishu_zl" type="text" class="form-control" value="<?php echo $row['xishu_zl'];?>" disabled></td>
                            </tr>
                            <tr>
                                <th>课程名</th><td><input name="course_name" type="text" class="form-control" value="<?php echo $row['course_name'];?>" disabled></td>
                                <th>难度系数</th><td><input name="xishu_nd" type="text" class="form-control" value="<?php echo $row['xishu_nd'];?>" disabled></td>
                            </tr>
                            <tr>
                                <th>合班</th><td><input name="heban" type="text" class="form-control" value="<?php echo $row['heban'];?>" disabled></td>
                                <th>职称系数</th><td><input name="xishu_zc" type="text" class="form-control" value="<?php echo $row['xishu_zc'];?>" disabled></td>

                            </tr>
                            <tr>
                                <th>学时</th><td><input name="xueshi" type="text" class="form-control" value="<?php echo $row['xueshi'];?>" ></td>
                                <th>计算过程</th><td><input name="guocheng" type="text" class="form-control" value="<?php echo $row['guocheng'];?>" disabled></td>
                            </tr>
                            <tr>
                                <th>人数</th><td><input name="num_of_p" type="text" class="form-control" value="<?php echo $row['num_of_p'];?>"></td>
                                <th>教分</th><td><input name="jiaofen" type="text" class="form-control" value="<?php echo $row['jiaofen'];?>" disabled></td>
                            </tr>

                        </thead>
                    </table>
                    <br />
                    <br />
                    <div>提示：请确认你的输入正确性</div>
                </div>
                <input type="hidden" name="op" value="lilun">
                <input type="hidden" name="id" value="<?php echo $row['id'];?>">
                <input type="hidden" name="year" value="<?php echo $_GET['xueqi'];?>">
                <input type="hidden" name="teacher_id" value="<?php echo $_GET['teacher_id'];?>">


                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">保存修改</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal -->
<div class="modal fade" id="<?php echo $row['id'];?>_add_Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="./modify_lilun_per_handle.php" method="post">

                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">添加信息</h4>
                </div>
                <div class="modal-body">
                    <table>
                        <thead>
                            <tr>
                                <th>学期</th><td><input name="xueqi" type="text" class="form-control" value="<?php echo $row['xueqi'];?>" ></td>
                                <th>人数系数</th><td><input name="xishu_people" type="text" class="form-control" value="<?php echo $row['xishu_people'];?>" ></td>
                            </tr>
                            <tr>
                                <th>教师号</th>
                                <td><input name="teacher_id" type="text" class="form-control" value="" ></td>
                                <th>重复课系数</th><td><input name="xishu_cfk" type="text" class="form-control" value="<?php echo $row['xishu_cfk'];?>" ></td>
                            </tr>
                            <tr>
                                <th>姓名</th><td><input name="teacher_name" type="text" class="form-control" value="" ></td>
                                <th>专业课系数</th><td><input name="xishu_zyk" type="text" class="form-control" value="<?php echo $row['xishu_zyk'];?>" ></td>
                            </tr>
                            <tr>
                                <th>职称</th><td><input name="teacher_zc" type="text" class="form-control" value="" ></td>
                                <th>三表系数</th><td><input name="xishu_sb" type="text" class="form-control" value="<?php echo $row['xishu_sb'];?>" ></td>
                            </tr>
                            <tr>
                                <th>课程号</th><td><input name="course_id" type="text" class="form-control" value="<?php echo $row['course_id'];?>" ></td>
                                <th>质量系数</th><td><input name="xishu_zl" type="text" class="form-control" value="<?php echo $row['xishu_zl'];?>" ></td>
                            </tr>
                            <tr>
                                <th>课程名</th><td><input name="course_name" type="text" class="form-control" value="<?php echo $row['course_name'];?>" ></td>
                                <th>难度系数</th><td><input name="xishu_nd" type="text" class="form-control" value="<?php echo $row['xishu_nd'];?>" ></td>
                            </tr>
                            <tr>
                                <th>合班</th><td><input name="heban" type="text" class="form-control" value="<?php echo $row['heban'];?>" ></td>
                                <th>职称系数</th><td><input name="xishu_zc" type="text" class="form-control" value="<?php echo $row['xishu_zc'];?>" ></td>

                            </tr>
                            <tr>
                                <th>学时</th><td><input name="xueshi" type="text" class="form-control" value="<?php echo $row['xueshi'];?>" ></td>
                                <th>计算过程</th><td><input name="guocheng" type="text" class="form-control" value="<?php echo $row['guocheng'];?>" ></td>
                            </tr>
                            <tr>
                                <th>人数</th><td><input name="num_of_p" type="text" class="form-control" value="<?php echo $row['num_of_p'];?>"></td>
                                <th>教分</th><td><input name="jiaofen" type="text" class="form-control" value="<?php echo $row['jiaofen'];?>" ></td>
                            </tr>

                        </thead>
                    </table>
                    <br />
                    <br />
                    <div>提示：请确认你的输入正确性</div>
                </div>
                <input type="hidden" name="op" value="lilun">
                <input type="hidden" name="course_index" value="<?php echo $row['course_index']; ?>">
                <input type="hidden" name="teacher_yuanxi" value="<?php echo $row['teacher_yuanxi']; ?>">
                <input type="hidden" name="add" value="1">
                <input type="hidden" name="year" value="<?php echo $_GET['xueqi'];?>">

                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">添加</button>
                </div>
            </form>
        </div>
    </div>
</div>



<!-- Small modal -->

<div class="modal fade bs-example-modal-sm" id="<?php echo $row['id'];?>_sm_Modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">修改信息</h4>
                </div>
                <div class="modal-body">
      确认删除该条记录？
      <form action="./modify_lilun_per_delete.php" method="post">
                  <input type="hidden" name="id" value="<?php echo $row['id'];?>">
                  <input type="hidden" name="delete_type" value="lilun">

                  <input type="hidden" name="year" value="<?php echo $_GET['xueqi'];?>">
                <input type="hidden" name="teacher_id" value="<?php echo $_GET['teacher_id'];?>">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">删除</button>
                </div>
      </form>
    </div>
  </div>
</div>
</td>
                                <?php
                                echo "</tr>";
                            }
                            ?>

                        </tbody>
                    </table>
                </div>

                <h2 class="sub-header">
                    实践教学工作量明细
                    <button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#shijian_adds_Modal">添加</button>
                </h2>
                <div class="span12">
                    <table class="table table-condensed table-striped">
                        <thead>
                            <tr>
                                <th>学期</th>
                                <th>教师号</th>
                                <th>姓名</th>
                                <th>职称</th>
                                <th>实践名称</th>
                                <th>课程号</th>
                                <th>实践类型</th>
                                <th>职称系数</th>
                                <th>学时(周)</th>
                                <th>人数</th>
                                <th>班级</th>
                                <th>地点</th>
                                <th>计算过程</th>
                                <th>教分</th>
                                <th>操作</th>

                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            for($i = 0; $i < $list_sj; $i++) {
                                $row = mysql_fetch_assoc($result_sj);
                                        //print_r($row);
                                // if($i%2==0) echo "<tr>";
                                // else echo "<tr class=\"success\">";
                                echo "<tr>";
                                echo "<td>".get_xq($row['xueqi'])."</td>";
                                echo "<td>".$row['teacher_id']."</td>";
                                echo "<td>".$row['teacher_name']."</td>";
                                echo "<td>".$row['teacher_zc']."</td>";
                                echo "<td>".$row['shijian_name']."</td>";
                                echo "<td>".$row['course_id']."</td>";
                                echo "<td>".$row['shijian_type']."</td>";
                                echo "<td>".$row['zhichengxishu']."</td>";
                                echo "<td>".$row['zhoushu']."</td>";
                                echo "<td>".$row['num_of_p']."</td>";
                                echo "<td>".$row['banji']."</td>";
                                echo "<td>".$row['didian']."</td>";
                                echo "<td>".$row['guocheng']."</td>";
                                echo "<td>".$row['jiaofen']."</td>";
                                ?>
<td>
    <!-- Button trigger modal -->
<button class="btn btn-warning btn-xs" data-toggle="modal" data-target="#<?php echo $row['id']; ?>_Modal">
  修改
</button>
<button class="btn btn-primary btn-xs" data-toggle="modal" data-target="#<?php echo $row['id']; ?>_adds_Modal">
  添加
</button>
<button class="btn btn-danger btn-xs" data-toggle="modal" data-target="#<?php echo $row['id']; ?>_sm_ds_Modal">
  删除
</button>
<!-- delete modal -->

<div class="modal fade bs-example-modal-sm" id="<?php echo $row['id'];?>_sm_ds_Modal" tabindex="-1" role="dialog" aria-labelledby="mySmallModalLabel" aria-hidden="true">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                    <h4 class="modal-title" id="myModalLabel">修改信息</h4>
                </div>
                <div class="modal-body">
      确认删除该条记录？
      <form action="./modify_lilun_per_delete.php" method="post">
                  <input type="hidden" name="id" value="<?php echo $row['id'];?>">
                  <input type="hidden" name="delete_type" value="shijian">
                  <input type="hidden" name="year" value="<?php echo $_GET['xueqi'];?>">
                <input type="hidden" name="teacher_id" value="<?php echo $_GET['teacher_id'];?>">
                  </div>
                  <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
                    <button type="submit" class="btn btn-primary">删除</button>
                </div>
      </form>
    </div>
  </div>
</div>
<!-- modify_Modal -->
<div class="modal fade" id="<?php echo $row['id']; ?>_Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="./modify_lilun_per_handle.php" method="post">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">修改</h4>
      </div>
      <div class="modal-body">
        <table>
            <thead>
                <tr>
                    <th>学期</th><td><input name="xueqi" type="text" class="form-control" value="<?php echo $row['xueqi'];?>" disabled></td>
                </tr>
                <tr>
                    <th>教师号</th><td><input name="teacher_id" type="text" class="form-control" value="<?php echo $row['teacher_id'];?>" disabled></td>
                </tr>
                <tr>
                    <th>姓名</th><td><input name="teacher_name" type="text" class="form-control" value="<?php echo $row['teacher_name'];?>" disabled></td>
                </tr>
                <tr>
                    <th>实践名称</th><td><input name="shijian_name" type="text" class="form-control" value="<?php echo $row['shijian_name'];?>" disabled></td>
                </tr>
                <tr>
                    <th>课程号</th><td><input name="course_id" type="text" class="form-control" value="<?php echo $row['course_id'];?>" disabled></td>
                </tr>
                <tr>
                    <th>实践类型</th><td><input name="shijian_type" type="text" class="form-control" value="<?php echo $row['shijian_type'];?>"></td>
                </tr>
                <tr>
                    <th>周数</th><td><input name="zhoushu" type="text" class="form-control" value="<?php echo $row['zhoushu'];?>"></td>
                </tr>
                <tr>
                    <th>人数</th><td><input name="num_of_p" type="text" class="form-control" value="<?php echo $row['num_of_p'];?>"></td>
                </tr>
                <tr>
                    <th>班级</th><td><input name="banji" type="text" class="form-control" value="<?php echo $row['banji'];?>"></td>
                </tr>
                <tr>
                    <th>地点</th><td><input name="didian" type="text" class="form-control" value="<?php echo $row['didian'];?>"></td>
                </tr>
                <tr>
                    <th>计算过程</th><td><input name="guocheng" type="text" class="form-control" value="<?php echo $row['guocheng'];?>"></td>
                </tr>
                <tr>
                    <th>教分</th><td><input name="jiaofen" type="text" class="form-control" value="<?php echo $row['jiaofen'];?>"></td>
                </tr>
            </thead>
        </table>
        <br />
        <div>提示: 此过程将不会重新计算，所以请将<b>教分</b>和<b>计算过程</b>一并更改.
        </div>
      </div>
                      <input type="hidden" name="op" value="shijian">
                <input type="hidden" name="id" value="<?php echo $row['id'];?>">
                <input type="hidden" name="year" value="<?php echo $_GET['xueqi'];?>">
                <input type="hidden" name="teacher_id" value="<?php echo $_GET['teacher_id'];?>">
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="submit" class="btn btn-primary">保存修改</button>
      </div>
  </form>
    </div>
  </div>
</div>
<!-- add_Modal -->
<div class="modal fade" id="<?php echo $row['id']; ?>_adds_Modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
        <form action="./modify_lilun_per_handle.php" method="post">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
        <h4 class="modal-title" id="myModalLabel">添加</h4>
      </div>
      <div class="modal-body">
        <table>
            <thead>
                <tr>
                    <th>学期</th><td><input name="xueqi" type="text" class="form-control" value="<?php echo $row['xueqi'];?>"></td>
                </tr>
                <tr>
                    <th>教师号</th><td><input name="teacher_id" type="text" class="form-control" value="<?php echo $row['teacher_id'];?>" ></td>
                </tr>
                <tr>
                    <th>姓名</th><td><input name="teacher_name" type="text" class="form-control" value="<?php echo $row['teacher_name'];?>" ></td>
                </tr>
                <tr>
                    <th>实践名称</th><td><input name="shijian_name" type="text" class="form-control" value="<?php echo $row['shijian_name'];?>" ></td>
                </tr>
                <tr>
                    <th>课程号</th><td><input name="course_id" type="text" class="form-control" value="<?php echo $row['course_id'];?>" ></td>
                </tr>
                <tr>
                    <th>实践类型</th><td><input name="shijian_type" type="text" class="form-control" value="<?php echo $row['shijian_type'];?>"></td>
                </tr>
                <tr>
                    <th>周数</th><td><input name="zhoushu" type="text" class="form-control" value="<?php echo $row['zhoushu'];?>"></td>
                </tr>
                <tr>
                    <th>人数</th><td><input name="num_of_p" type="text" class="form-control" value="<?php echo $row['num_of_p'];?>"></td>
                </tr>
                <tr>
                    <th>班级</th><td><input name="banji" type="text" class="form-control" value="<?php echo $row['banji'];?>"></td>
                </tr>
                <tr>
                    <th>地点</th><td><input name="didian" type="text" class="form-control" value="<?php echo $row['didian'];?>"></td>
                </tr>
                <tr>
                    <th>计算过程</th><td><input name="guocheng" type="text" class="form-control" value="<?php echo $row['guocheng'];?>"></td>
                </tr>
                <tr>
                    <th>教分</th><td><input name="jiaofen" type="text" class="form-control" value="<?php echo $row['jiaofen'];?>"></td>
                </tr>
            </thead>
        </table>
        <br />
      </div>
                  <input type="hidden" name="op" value="shijian">
                  <input type="hidden" name="addshijian" value="1">

                <input type="hidden" name="id" value="<?php echo $row['id'];?>">
                <input type="hidden" name="year" value="<?php echo $_GET['xueqi'];?>">
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal">取消</button>
        <button type="submit" class="btn btn-primary">添加</button>
      </div>
  </form>
    </div>
  </div>
</div>

</td>
                                <?php
                                echo "</tr>";
                            }
                            ?>
                        </tbody>
                    </table>
                </div>

            </div>
        </div>

        <?php
    }else{
        ?><h3>输入要修改的教师号与时间</h3>
        <!-- <div class="alert alert-danger" role="alert">由于计算方式发生变动， 请暂时不要修改理论课工作量！</div> -->
        <form class="navbar-form navbar-left" method="get">
            <input type="text" name="teacher_id" class="form-control" placeholder="请输入教师号">
            <input type="text" name="xueqi" class="form-control" placeholder="2014春请输入“20141”">
            <span class="text-right"><button type="submit" class="btn btn-success">查询</button></span>
            <a href="./modify_lilun_xueyuan.php"><button type="button" class="btn btn-info">按学院查询</button></a>
        </form>



        <?php
    }
    ?>
</div>    <!-- /container -->
<?php
include './footer.php'

?>
