<?php
    /*导出人事处表格
     * 按年导出
     */

    include dirname(__FILE__) . './../config.php';
    include dirname(__FILE__) . './../functions.php';
    set_time_limit(0);

    # 判断是否登录
    if(!isset($_SESSION['username'])) {
        header("location: ./error.php?txt="."请登录后再操作.");
        exit();
    }

    # 检验权限
    if(!$_SESSION['export_renshichu']) {
        header("location: ./error.php?txt="."您没有导出人事处工作量表的权限.");
        exit();
    }

    if(!isset($_POST['year'])) {
        header("location: ./error.php?txt="."请输入需要导出的年份.");
        exit();
    }

    $year = $_POST['year'];
    //echo $year;
    //exit();
    # 数据准备
    $sql = "SELECT DISTINCT `teacher_id` FROM `teachers`;";
    $teachers_result = mysql_query($sql);
    if(!$teachers_result) {
        die("SQL ERROR : ". mysql_error());
    }
    $cf = array();
    $cfzc = array();
    while($cf_row = mysql_fetch_assoc($teachers_result)) {
        $sql1 = "SELECT * FROM `teachers` WHERE `teacher_id`='".$cf_row['teacher_id']."' AND `xueqi`='".$year."1';";
        $sql2 = "SELECT * FROM `teachers` WHERE `teacher_id`='".$cf_row['teacher_id']."' AND `xueqi`='".$year."2';";
        $result_1 = mysql_query($sql1);
        $result_2 = mysql_query($sql2);
        if(!$result_2 || !$result_1) {
            die('SQL ERROR zhic: '. mysql_error());
        }
        $zc1 = mysql_fetch_assoc($result_1);
        $zc2 = mysql_fetch_assoc($result_2);
        if($zc2['zhicheng'] == $zc1['zhicheng']) {
            $cf[$cf_row['teacher_id']] = 0;
            $cfzc[$cf_row['teacher_id']] = $zc1['zhicheng'];
        }else{
            $cf[$cf_row['teacher_id']] = 1; //职称不同
        }
    }

    if($_POST['xueyuan']!=null){
        $sql = "SELECT DISTINCT `teacher_id` FROM `teachers`WHERE `xueyuan`='".$_POST['xueyuan']."';";
    }else{
        $sql = "SELECT DISTINCT `teacher_id` FROM `teachers`;";
    }

    $teachers_result = mysql_query($sql);
    if(!$teachers_result) {
        die("SQL ERROR : ". mysql_error());
    }

    # Excel 的预处理

    // Excel开始
    // 准备EXCEL的包括文件
    // Error reporting
    //error_reporting(0);
    // PHPExcel
    require_once dirname(__FILE__) . './../include/Classes/PHPExcel.php';
    // 生成新的excel对象
    $objPHPExcel = new PHPExcel();
//    var_dump($objPHPExcel);
    // 设置excel文档的属性
    $objPHPExcel->getProperties()->setCreator("Sam.c")->setLastModifiedBy("Sam.c Test")->setTitle("Microsoft Office Excel Document")->setSubject("Test")->setDescription("Test")->setKeywords("Test")->setCategory("Test result file");
    // 开始操作excel表
    // 操作第一个工作表
    $objPHPExcel->setActiveSheetIndex(0);
    // 设置工作薄名称
    $objPHPExcel->getActiveSheet()->setTitle(iconv('gbk', 'utf-8', 'daocuo'));
    // 设置默认字体和大小
    $objPHPExcel->getDefaultStyle()->getFont()->setName(iconv('gbk', 'utf-8', '宋体'));
    $objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
    $m_exportType = "excel";
    $objPHPExcel->getActiveSheet()->setCellValue('A1', '学期');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', '教师号');
    $objPHPExcel->getActiveSheet()->setCellValue('C1', '姓名');
    $objPHPExcel->getActiveSheet()->setCellValue('D1', '职称');
    $objPHPExcel->getActiveSheet()->setCellValue('E1', '教师分类');
    $objPHPExcel->getActiveSheet()->setCellValue('F1', '是否硕导');
    $objPHPExcel->getActiveSheet()->setCellValue('G1', '是否双肩挑');
    $objPHPExcel->getActiveSheet()->setCellValue('H1', '学院');
    $objPHPExcel->getActiveSheet()->setCellValue('I1', '系');
    $objPHPExcel->getActiveSheet()->setCellValue('J1', '身份证号');
    $objPHPExcel->getActiveSheet()->setCellValue('K1', '一卡通号');
    $objPHPExcel->getActiveSheet()->setCellValue('L1', '工资账号');
    $objPHPExcel->getActiveSheet()->setCellValue('M1', '研究生原始');
    $objPHPExcel->getActiveSheet()->setCellValue('N1', '研究生折合');
    $objPHPExcel->getActiveSheet()->setCellValue('O1', '研究生指导');
    $objPHPExcel->getActiveSheet()->setCellValue('P1', '研究生目标');
    $objPHPExcel->getActiveSheet()->setCellValue('Q1', '研究生指导竞赛');
    $objPHPExcel->getActiveSheet()->setCellValue('R1', '成人原始');
    $objPHPExcel->getActiveSheet()->setCellValue('S1', '成人折合');
    $objPHPExcel->getActiveSheet()->setCellValue('T1', '成人实践折合');
    $objPHPExcel->getActiveSheet()->setCellValue('U1', '本科理论原始');
    $objPHPExcel->getActiveSheet()->setCellValue('V1', '本科理论折合');
    $objPHPExcel->getActiveSheet()->setCellValue('W1', '本科实践原始');
    $objPHPExcel->getActiveSheet()->setCellValue('X1', '本科实践折合');
    $objPHPExcel->getActiveSheet()->setCellValue('Y1', '竞赛');
    $objPHPExcel->getActiveSheet()->setCellValue('Z1', '教务津贴');
    $objPHPExcel->getActiveSheet()->setCellValue('AA1', '其他');
    $objPHPExcel->getActiveSheet()->setCellValue('AB1', '实验原始');
    $objPHPExcel->getActiveSheet()->setCellValue('AC1', '实验折合');
    $objPHPExcel->getActiveSheet()->setCellValue('AD1', '实验津贴');
    $objPHPExcel->getActiveSheet()->setCellValue('AE1', '欠监考次数');
    $objPHPExcel->getActiveSheet()->setCellValue('AF1', '总计');


    $num = 2;
    # 遍历结果集 写入Excel
    while($teacher_row = mysql_fetch_assoc($teachers_result)) {
        //echo "++";
        # 简化变量名
        $cx_sql = "SELECT * FROM `teachers` WHERE `teacher_id` = '".$teacher_row['teacher_id']."'";
        $cx_result = mysql_query($cx_sql);
        if(!$cx_result) {
            die("Sql ERROR : ". mysql_error());
        }
        $cx_result = mysql_fetch_assoc($cx_result);

        $t_id = $cx_result['teacher_id'];
        $t_name = $cx_result['teacher_name'];
        $t_xueyuan = $cx_result['xueyuan'];
        $t_xi = $cx_result['xi'];
        $t_idcard = $cx_result['idcard'];
        $t_ykt = $cx_result['yikatong'];
        $t_zc = $cx_result['zhicheng'];
        //$t_cat = isset($cx_result['teacher_cat']) ? $cx_result['teacher_cat'] : -1;
        $t_cat = $cx_result['teacher_cat'];
        $t_sd = $cx_result['issd'];
        $t_sjt = $cx_result['issjt'];
        $t_gzid = $cx_result['gz_id'];

        if($cf[$t_id]) { //职称不同不合并

        # 提取理论表里的工作量
        $sql = "SELECT SUM(jiaofen),SUM(xueshi) FROM `lilun` WHERE `teacher_id`='".$t_id."' AND `xueqi`='".$year."1';";
        //echo $sql;
        $result = mysql_query($sql);
        if(!$result) {
            die("SQR ERROR : ". mysql_error());
        }
        $lilun_jiaofen = mysql_fetch_assoc($result);

        $lilun_zhehe = round($lilun_jiaofen['SUM(jiaofen)'],4);
        $lilun_yuanshi = round($lilun_jiaofen['SUM(xueshi)'],4);

        # 实践课的工作量
        $sql = "SELECT SUM(jiaofen),SUM(zhoushu) FROM `shijian` WHERE `teacher_id`='".$t_id."' AND `xueqi`='".$year."1';";
        //echo $sql;
        $result = mysql_query($sql);
        if(!$result) {
            die("SQL ERROR : ". mysql_error());
        }
        $shijian_jiaofen = mysql_fetch_assoc($result);

        $shijian_yuanshi = round($shijian_jiaofen['SUM(zhoushu)'],4);
        $shijian_zhehe = round($shijian_jiaofen['SUM(jiaofen)'],4);

        #实验
        $result_shiyan = mysql_query("select * from `shiyan` where `teacher_id` = \"". $t_id ."\"  and `xueqi` like '".$year."1';");
        if(!$result_shiyan) {
            die("mysql select error:".mysql_error());
        }
        $result_shiyan = mysql_fetch_assoc($result_shiyan);
        $shiyan_yuanshi = round($result_shiyan['yuanshi'],4);
        $shiyan_zhehe = round($result_shiyan['zhehe'],4);
        $shiyan_jintie = round($result_shiyan['jintie'],4);


        #竞赛
        $result_jingsai = mysql_query("select SUM(jiaofen) from `jingsai` where `teacher_id` = \"". $t_id ."\"  and `xueqi` like '".$year."1';");
        if(!$result_jingsai) {
            die("mysql select error:".mysql_error());
        }
        $result_jingsai = mysql_fetch_assoc($result_jingsai);
        $jingsai_jiaofen = $result_jingsai['SUM(jiaofen)'];

        #教务津贴
        $result_jiaowu = mysql_query("select SUM(jiaofen) from `jiaowu` where `teacher_id` = \"". $t_id ."\"  and `xueqi`='".$year."1';");
        if(!$result_jiaowu) {
            die("mysql select error:".mysql_error());
        }
        $result_jiaowu = mysql_fetch_assoc($result_jiaowu);
        $jiaowu_jiaofen = $result_jiaowu['SUM(jiaofen)'];

        #其他
        $result_qita = mysql_query("select SUM(jiaofen) from `qita` where `teacher_id` = \"". $t_id ."\"  and `xueqi` like '".$year."1';");
        if(!$result_qita) {
            die("mysql select error:".mysql_error());
        }
        $result_qita = mysql_fetch_assoc($result_qita);
        $qita_jiaofen = $result_qita['SUM(jiaofen)'];

        #欠考
        $result_qiankao = mysql_query("select SUM(jiaofen) from `qiankao` where `teacher_id` = \"". $t_id ."\"  and `xueqi` like '".$year."1';");
        if(!$result_qiankao) {
            die("mysql select error:".mysql_error());
        }
        $result_qiankao = mysql_fetch_assoc($result_qiankao);
        $qiankao = $result_qiankao['SUM(jiaofen)'];


        #成人
        $result_chengren = mysql_query("select * from `chengren` where `teacher_id` = \"". $t_id ."\"  and `xueqi` like '".$year."1';");
        if(!$result_chengren) {
            die("mysql select error:".mysql_error());
        }
        $result_chengren = mysql_fetch_assoc($result_chengren);
        $chengren_yuanshi = $result_chengren['yuanshi'];
        $chengren_zhehe = $result_chengren['zhehe'];
        $chengren_shijianzhehe = $result_chengren['shijianzhehe'];

        #研究生
        $result_yjs = mysql_query("select * from `yanjiusheng` where `teacher_id` = \"". $t_id ."\"  and `xueqi` like '".$year."1';");
        if(!$result_yjs) {
            die("mysql select error:".mysql_error());
        }
        $result_yjs = mysql_fetch_assoc($result_yjs);
        $yjs_yuanshi = $result_yjs['yuanshi'];
        $yjs_zhehe = round($result_yjs['zhehe'],4);
        $yjs_zhidao = $result_yjs['zhidao'];
        $yjs_mubiao = round($result_yjs['mubiao'],4);
        $yjs_zdjs = round($result_yjs['zdjs'],4);
        $t_sd = $result_yjs['isshuodao']=="是"? "是" : "否";


        # 汇总
        $sum_all = 0;
        $sum_all = $lilun_zhehe + $shijian_zhehe;
        $sum_all += $yjs_zhehe + $yjs_zhidao + $yjs_mubiao + $yjs_zdjs;
        $sum_all += $chengren_zhehe + $chengren_shijianzhehe;
        $sum_all += $shiyan_zhehe + $shiyan_jintie;
        $sum_all += $jingsai_jiaofen;
        $sum_all += $jiaowu_jiaofen;
        $sum_all += $qita_jiaofen;
        $sum_all += $qiankao;
        if($sum_all==0) goto c2;

        $xxx = $year."1";
        $sql = "select zhicheng,teacher_cat from `teachers` where `teacher_id` = '" . $t_id . "' and `xueqi` = ".$xxx;
        $result = mysql_query($sql);
        if (!$result) {
            die("sql errer:".mysql_error());
        }

        $t_info = mysql_fetch_assoc($result);
        # 写入Excel类
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$num, (int)$xxx);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$num, (string)$t_id,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$num, (string)$t_name,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$num, (string)$t_info['zhicheng'],PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$num, (int)$t_info['teacher_cat']);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$num, (string)$t_sd,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$num, (string)$t_sjt,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('H'.$num, (string)$t_xueyuan,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('I'.$num, (string)$t_xi,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('J'.$num, (string)$t_idcard,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('K'.$num, (string)$t_ykt,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('L'.$num, (string)$t_gzid,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('M'.$num, (float)$yjs_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('N'.$num, (float)$yjs_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('O'.$num, (float)$yjs_zhidao,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('P'.$num, (float)$yjs_mubiao,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('Q'.$num, (float)$yjs_zdjs,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('R'.$num, (float)$chengren_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('S'.$num, (float)$chengren_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('T'.$num, (float)$chengren_shijianzhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('U'.$num, (float)$lilun_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('V'.$num, (float)$lilun_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('W'.$num, (float)$shijian_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('X'.$num, (float)$shijian_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('Y'.$num, (float)$jingsai_jiaofen,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('Z'.$num, (float)$jiaowu_jiaofen,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('AA'.$num, (float)$qita_jiaofen,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('AB'.$num, (float)$shiyan_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('AC'.$num, (float)$shiyan_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('AD'.$num, (float)$shiyan_jintie,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('AE'.$num, (float)$qiankao,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('AF'.$num, (float)$sum_all,PHPExcel_Cell_DataType::TYPE_NUMERIC);

        # 累加行数
        $num++;
c2:

        # 提取理论表里的工作量
        $sql = "SELECT SUM(jiaofen),SUM(xueshi) FROM `lilun` WHERE `teacher_id`='".$t_id."' AND `xueqi`='".$year."2';";
        //echo $sql;
        $result = mysql_query($sql);
        if(!$result) {
            die("SQR ERROR : ". mysql_error());
        }
        $lilun_jiaofen = mysql_fetch_assoc($result);

        $lilun_zhehe = round($lilun_jiaofen['SUM(jiaofen)'],4);
        $lilun_yuanshi = round($lilun_jiaofen['SUM(xueshi)'],4);

        # 实践课的工作量
        $sql = "SELECT SUM(jiaofen),SUM(zhoushu) FROM `shijian` WHERE `teacher_id`='".$t_id."' AND `xueqi`='".$year."2';";
        //echo $sql;
        $result = mysql_query($sql);
        if(!$result) {
            die("SQL ERROR : ". mysql_error());
        }
        $shijian_jiaofen = mysql_fetch_assoc($result);

        $shijian_yuanshi = round($shijian_jiaofen['SUM(zhoushu)'],4);
        $shijian_zhehe = round($shijian_jiaofen['SUM(jiaofen)'],4);

        #实验
        $result_shiyan = mysql_query("select * from `shiyan` where `teacher_id` = \"". $t_id ."\"  and `xueqi`='".$year."2';");
        if(!$result_shiyan) {
            die("mysql select error:".mysql_error());
        }
        $result_shiyan = mysql_fetch_assoc($result_shiyan);
        $shiyan_yuanshi = round($result_shiyan['yuanshi'],4);
        $shiyan_zhehe = round($result_shiyan['zhehe'],4);
        $shiyan_jintie = round($result_shiyan['jintie'],4);


        #竞赛
        $result_jingsai = mysql_query("select SUM(jiaofen) from `jingsai` where `teacher_id` = \"". $t_id ."\"  and `xueqi`='".$year."2';");
        if(!$result_jingsai) {
            die("mysql select error:".mysql_error());
        }
        $result_jingsai = mysql_fetch_assoc($result_jingsai);
        $jingsai_jiaofen = $result_jingsai['SUM(jiaofen)'];

        #教务津贴
        //$sql = "select SUM(jiaofen) from `jiaowu` where `teacher_id` = \"". $t_id ."\"  and `xueqi`='".$year."2';";
        $result_jiaowu = mysql_query("select SUM(jiaofen) from `jiaowu` where `teacher_id` = \"". $t_id ."\"  and `xueqi`='".$year."2';");
        if(!$result_jiaowu) {
            die("mysql select error:".mysql_error());
        }
        $result_jiaowu = mysql_fetch_assoc($result_jiaowu);
        $jiaowu_jiaofen = $result_jiaowu['SUM(jiaofen)'];


        #其他
        $result_qita = mysql_query("select SUM(jiaofen) from `qita` where `teacher_id` = \"". $t_id ."\"  and `xueqi`='".$year."2';");
        if(!$result_qita) {
            die("mysql select error:".mysql_error());
        }
        $result_qita = mysql_fetch_assoc($result_qita);
        $qita_jiaofen = $result_qita['SUM(jiaofen)'];

        #欠考
        $result_qiankao = mysql_query("select SUM(jiaofen) from `qiankao` where `teacher_id` = \"". $t_id ."\"  and `xueqi`='".$year."2';");
        if(!$result_qiankao) {
            die("mysql select error:".mysql_error());
        }
        $result_qiankao = mysql_fetch_assoc($result_qiankao);
        $qiankao = $result_qiankao['SUM(jiaofen)'];


        #成人
        $result_chengren = mysql_query("select * from `chengren` where `teacher_id` = \"". $t_id ."\"  and `xueqi`='".$year."2';");
        if(!$result_chengren) {
            die("mysql select error:".mysql_error());
        }
        $result_chengren = mysql_fetch_assoc($result_chengren);
        $chengren_yuanshi = $result_chengren['yuanshi'];
        $chengren_zhehe = $result_chengren['zhehe'];
        $chengren_shijianzhehe = $result_chengren['shijianzhehe'];

        #研究生
        $result_yjs = mysql_query("select * from `yanjiusheng` where `teacher_id` = \"". $t_id ."\"  and `xueqi`='".$year."2';");
        if(!$result_yjs) {
            die("mysql select error:".mysql_error());
        }
        $result_yjs = mysql_fetch_assoc($result_yjs);
        $yjs_yuanshi = $result_yjs['yuanshi'];
        $yjs_zhehe = round($result_yjs['zhehe'],4);
        $yjs_zhidao = $result_yjs['zhidao'];
        $yjs_mubiao = round($result_yjs['mubiao'],4);
        $yjs_zdjs = round($result_yjs['zdjs'],4);
        $t_sd = $result_yjs['isshuodao']=="是"? "是" : "否";

        # 汇总
        $sum_all = 0;
        $sum_all = $lilun_zhehe + $shijian_zhehe;
        $sum_all += $yjs_zhehe + $yjs_zhidao + $yjs_mubiao + $yjs_zdjs;
        $sum_all += $chengren_zhehe + $chengren_shijianzhehe;
        $sum_all += $shiyan_zhehe + $shiyan_jintie;
        $sum_all += $jingsai_jiaofen;
        $sum_all += $jiaowu_jiaofen;
        $sum_all += $qita_jiaofen;
        $sum_all += $qiankao;

        if($sum_all==0) continue;

        $xxx = $year."2";
        $sql = "select zhicheng,teacher_cat from `teachers` where `teacher_id` = '" . $t_id . "' and `xueqi` = ".$xxx;
        $result = mysql_query($sql);
        if (!$result) {
            die("sql errer:".mysql_error());
        }

        $t_info = mysql_fetch_assoc($result);
        # 写入Excel类
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$num, (int)$xxx);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$num, (string)$t_id,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$num, (string)$t_name,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$num, (string)$t_info['zhicheng'],PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$num, (int)$t_info['teacher_cat']);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$num, (string)$t_sd,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$num, (string)$t_sjt,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('H'.$num, (string)$t_xueyuan,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('I'.$num, (string)$t_xi,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('J'.$num, (string)$t_idcard,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('K'.$num, (string)$t_ykt,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('L'.$num, (string)$t_gzid,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('M'.$num, (float)$yjs_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('N'.$num, (float)$yjs_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('O'.$num, (float)$yjs_zhidao,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('P'.$num, (float)$yjs_mubiao,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('Q'.$num, (float)$yjs_zdjs,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('R'.$num, (float)$chengren_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('S'.$num, (float)$chengren_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('T'.$num, (float)$chengren_shijianzhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('U'.$num, (float)$lilun_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('U'.$num, (float)$lilun_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('W'.$num, (float)$shijian_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('X'.$num, (float)$shijian_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('Y'.$num, (float)$jingsai_jiaofen,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('Z'.$num, (float)$jiaowu_jiaofen,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('AA'.$num, (float)$qita_jiaofen,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('AB'.$num, (float)$shiyan_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('AC'.$num, (float)$shiyan_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('AD'.$num, (float)$shiyan_jintie,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('AE'.$num, (float)$qiankao,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('AF'.$num, (float)$sum_all,PHPExcel_Cell_DataType::TYPE_NUMERIC);

        # 累加行数
        $num++;
        }else{

        # 提取理论表里的工作量
        $sql = "SELECT SUM(jiaofen),SUM(xueshi) FROM `lilun` WHERE `teacher_id`='".$t_id."' AND `xueqi` LIKE '".$year."_';";
        //echo $sql;
        $result = mysql_query($sql);
        if(!$result) {
            die("SQR ERROR : ". mysql_error());
        }
        $lilun_jiaofen = mysql_fetch_assoc($result);

        $lilun_zhehe = round($lilun_jiaofen['SUM(jiaofen)'],4);
        $lilun_yuanshi = round($lilun_jiaofen['SUM(xueshi)'],4);

        # 实践课的工作量
        $sql = "SELECT SUM(jiaofen),SUM(zhoushu) FROM `shijian` WHERE `teacher_id`='".$t_id."' AND `xueqi` LIKE '".$year."_';";
        //echo $sql;
        $result = mysql_query($sql);
        if(!$result) {
            die("SQL ERROR : ". mysql_error());
        }
        $shijian_jiaofen = mysql_fetch_assoc($result);

        $shijian_yuanshi = round($shijian_jiaofen['SUM(zhoushu)'],4);
        $shijian_zhehe = round($shijian_jiaofen['SUM(jiaofen)'],4);

        #实验
        $result_shiyan = mysql_query("select * from `shiyan` where `teacher_id` = \"". $t_id ."\"  and `xueqi` like '".$year."_';");
        if(!$result_shiyan) {
            die("mysql select error:".mysql_error());
        }
        $shiyan_yuanshi = 0;
        $shiyan_zhehe = 0;
        $shiyan_jintie = 0;

        while ( $result_shiyan_row = mysql_fetch_assoc($result_shiyan)) {
            $shiyan_yuanshi += round($result_shiyan_row['yuanshi'],4);
            $shiyan_zhehe += round($result_shiyan_row['zhehe'],4);
            $shiyan_jintie += round($result_shiyan_row['jintie'],4);
        }


        #竞赛

        $result_jingsai = mysql_query("select SUM(jiaofen) from `jingsai` where `teacher_id` = \"". $t_id ."\"  and `xueqi` like '".$year."_';");
        if(!$result_jingsai) {
            die("mysql select error:".mysql_error());
        }
        $result_jingsai = mysql_fetch_assoc($result_jingsai);
        $jingsai_jiaofen = $result_jingsai['SUM(jiaofen)'];

        #教务津贴
        $result_jiaowu = mysql_query("select SUM(jiaofen) from `jiaowu` where `teacher_id` = \"". $t_id ."\"  and `xueqi` like '".$year."_';");
        if(!$result_jiaowu) {
            die("mysql select error:".mysql_error());
        }
        $result_jiaowu = mysql_fetch_assoc($result_jiaowu);
        $jiaowu_jiaofen = $result_jiaowu['SUM(jiaofen)'];

        #其他
        $result_qita = mysql_query("select SUM(jiaofen) from `qita` where `teacher_id` = \"". $t_id ."\"  and `xueqi` like '".$year."_';");
        if(!$result_qita) {
            die("mysql select error:".mysql_error());
        }
        $result_qita = mysql_fetch_assoc($result_qita);
        $qita_jiaofen = $result_qita['SUM(jiaofen)'];

        #欠考
        $result_qiankao = mysql_query("select SUM(jiaofen) from `qiankao` where `teacher_id` = \"". $t_id ."\"  and `xueqi` like '".$year."_';");
        if(!$result_qiankao) {
            die("mysql select error:".mysql_error());
        }
        $result_qiankao = mysql_fetch_assoc($result_qiankao);
        $qiankao = $result_qiankao['SUM(jiaofen)'];


        #成人
        $result_chengren = mysql_query("select * from `chengren` where `teacher_id` = \"". $t_id ."\"  and `xueqi` like '".$year."_';");
        if(!$result_chengren) {
            die("mysql select error:".mysql_error());
        }
        $chengren_yuanshi = 0;
        $chengren_zhehe = 0;
        $chengren_shijianzhehe = 0;
        while ( $result_chengren_row = mysql_fetch_assoc($result_chengren)) {
            $chengren_yuanshi += $result_chengren_row['yuanshi'];
            $chengren_zhehe += $result_chengren_row['zhehe'];
            $chengren_shijianzhehe += $result_chengren_row['shijianzhehe'];
        }

        #研究生
        $result_yjs = mysql_query("select * from `yanjiusheng` where `teacher_id` = \"". $t_id ."\"  and `xueqi` like '".$year."_';");
        if(!$result_yjs) {
            die("mysql select error:".mysql_error());
        }
        $yjs_yuanshi = 0;
        $yjs_zhehe = 0;
        $yjs_zhidao = 0;
        $yjs_mubiao = 0;
        $yjs_zdjs = 0;
        $t_sd = "否";
        while ( $result_yjs_row = mysql_fetch_assoc($result_yjs) ) {
            $yjs_yuanshi += $result_yjs_row['yuanshi'];
            $yjs_zhehe += round($result_yjs_row['zhehe'],4);
            $yjs_zhidao += $result_yjs_row['zhidao'];
            $yjs_mubiao += round($result_yjs_row['mubiao'],4);
            $yjs_zdjs += round($result_yjs_row['zdjs'],4);
            $t_sd = $result_yjs_row['isshuodao']=="是"? "是" : "否";

        }

        # 汇总
        $sum_all = 0;
        $sum_all = $lilun_zhehe + $shijian_zhehe;
        $sum_all += $yjs_zhehe + $yjs_zhidao + $yjs_mubiao + $yjs_zdjs;
        $sum_all += $chengren_zhehe + $chengren_shijianzhehe;
        $sum_all += $shiyan_zhehe + $shiyan_jintie;
        $sum_all += $jingsai_jiaofen;
        $sum_all += $jiaowu_jiaofen;
        $sum_all += $qita_jiaofen;
        $sum_all += $qiankao;

        if($sum_all==0) continue;

        # 写入Excel类
        $objPHPExcel->getActiveSheet()->setCellValue('A'.$num, (int)$year);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$num, (string)$t_id,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$num, (string)$t_name,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$num, (string)$t_zc,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValue('E'.$num, (int)$t_cat);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$num, (string)$t_sd,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('G'.$num, (string)$t_sjt,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('H'.$num, (string)$t_xueyuan,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('I'.$num, (string)$t_xi,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('J'.$num, (string)$t_idcard,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('K'.$num, (string)$t_ykt,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('L'.$num, (string)$t_gzid,PHPExcel_Cell_DataType::TYPE_STRING);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('M'.$num, (float)$yjs_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('N'.$num, (float)$yjs_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('O'.$num, (float)$yjs_zhidao,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('P'.$num, (float)$yjs_mubiao,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('Q'.$num, (float)$yjs_zdjs,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('R'.$num, (float)$chengren_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('S'.$num, (float)$chengren_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('T'.$num, (float)$chengren_shijianzhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('U'.$num, (float)$lilun_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('V'.$num, (float)$lilun_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('W'.$num, (float)$shijian_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('X'.$num, (float)$shijian_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('Y'.$num, (float)$jingsai_jiaofen,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('Z'.$num, (float)$jiaowu_jiaofen,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('AA'.$num, (float)$qita_jiaofen,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('AB'.$num, (float)$shiyan_yuanshi,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('AC'.$num, (float)$shiyan_zhehe,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('AD'.$num, (float)$shiyan_jintie,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('AE'.$num, (float)$qiankao,PHPExcel_Cell_DataType::TYPE_NUMERIC);
        $objPHPExcel->getActiveSheet()->setCellValueExplicit('AF'.$num, (float)$sum_all,PHPExcel_Cell_DataType::TYPE_NUMERIC);

        # 累加行数
        $num++;
        }

    }

    # 输出excel
    if (isset($_POST['summary']) && $_POST['summary'] === 'HouBaron20170517') {
        $currentSheet = $objPHPExcel->getSheet(0);
        $allRow       = $currentSheet->getHighestRow();
        //$array        = [];
        $hashTable    = [
            '职称' => [],
            '学院' => [],
            '系'  => [],
        ];
        $head2pos = [
            '职称' => 3,
            '学院' => 7,
            '系'  => 8,
        ];
        $need2sum = [
            '本科理论原始' => 20,
            '本科理论折合' => 21,
            '本科实践折合' => 23,
            '实践理论之和' => 32,
        ];
        for($currentRow = 2; $currentRow <= $allRow; ++$currentRow) {
            //echo "正在处理第$currentRow 行，请稍后。<br>";
            //flush();
            $row = [];
            //$row[] = (int)   $currentSheet->getCell('A' . $currentRow)->getValue();
            //$row[] = (string)$currentSheet->getCell('B' . $currentRow)->getValue();
            //$row[] = (string)$currentSheet->getCell('C' . $currentRow)->getValue();
            $row[3] = (string)$currentSheet->getCell('D' . $currentRow)->getValue();
            //$row[] = (int)   $currentSheet->getCell('E' . $currentRow)->getValue();
            //$row[] = (string)$currentSheet->getCell('F' . $currentRow)->getValue();
            //$row[] = (string)$currentSheet->getCell('G' . $currentRow)->getValue();
            $row[7] = (string)$currentSheet->getCell('H' . $currentRow)->getValue();
            $row[8] = (string)$currentSheet->getCell('I' . $currentRow)->getValue();
            //$row[] = (string)$currentSheet->getCell('J' . $currentRow)->getValue();
            //$row[] = (string)$currentSheet->getCell('K' . $currentRow)->getValue();
            //$row[] = (string)$currentSheet->getCell('L' . $currentRow)->getValue();
            //$row[] = (float) $currentSheet->getCell('M' . $currentRow)->getValue();
            //$row[] = (float) $currentSheet->getCell('N' . $currentRow)->getValue();
            //$row[] = (float) $currentSheet->getCell('O' . $currentRow)->getValue();
            //$row[] = (float) $currentSheet->getCell('P' . $currentRow)->getValue();
            //$row[] = (float) $currentSheet->getCell('Q' . $currentRow)->getValue();
            //$row[] = (float) $currentSheet->getCell('R' . $currentRow)->getValue();
            //$row[] = (float) $currentSheet->getCell('S' . $currentRow)->getValue();
            //$row[] = (float) $currentSheet->getCell('T' . $currentRow)->getValue();
            $row[20] = (float) $currentSheet->getCell('U' . $currentRow)->getValue();
            $row[21] = (float) $currentSheet->getCell('V' . $currentRow)->getValue();
            //$row[] = (float) $currentSheet->getCell('W' . $currentRow)->getValue();
            $row[23] = (float) $currentSheet->getCell('X' . $currentRow)->getValue();
            //$row[] = (float) $currentSheet->getCell('Y' . $currentRow)->getValue();
            //$row[] = (float) $currentSheet->getCell('Z' . $currentRow)->getValue();
            //$row[] = (float) $currentSheet->getCell('AA'. $currentRow)->getValue();
            //$row[] = (float) $currentSheet->getCell('AB'. $currentRow)->getValue();
            //$row[] = (float) $currentSheet->getCell('AC'. $currentRow)->getValue();
            //$row[] = (float) $currentSheet->getCell('AD'. $currentRow)->getValue();
            //$row[] = (float) $currentSheet->getCell('AE'. $currentRow)->getValue();
            //$row[] = (float) $currentSheet->getCell('AF'. $currentRow)->getValue();
            $row[32] = $row[21] + $row[23];

            foreach ($head2pos as $head => $pos) {
                // 准备统计数组
                if (!isset( $hashTable[$head][ $row[$pos] ] )) {
                    $hashTable[$head][ $row[$pos] ] = [];
                    foreach ($need2sum as $sumHead => $sumPos) {
                        $hashTable[$head][ $row[$pos] ][$sumHead]       = 0.0;
                        $hashTable[$head][ $row[$pos] ][$sumHead . 'c'] = 0;
                    }
                }

                // 开始统计
                foreach ($need2sum as $sumHead => $sumPos) {
                    if ($row[$sumPos] != 0) {
                        $hashTable[$head][ $row[$pos] ][$sumHead] += $row[$sumPos];
                        ++$hashTable[$head][ $row[$pos] ][$sumHead . 'c'];
                    }
                }
            }

        }

        // 导出
        $sheetIndex = 0;
        // 生成新的excel对象
        unset($objPHPExcel);
        $objPHPExcel = new PHPExcel();
        // 设置excel文档的属性
        $objPHPExcel->getProperties()->setCreator("Sam.c")->setLastModifiedBy("Sam.c Test")
            ->setTitle("Microsoft Office Excel Document")->setSubject("Test")
            ->setDescription("Test")->setKeywords("Test")->setCategory("Test result file");

        // 求平均数
        foreach ($head2pos as $head => $pos) {
            // 操作第一个工作表
            if ($sheetIndex != 0) {
                $objPHPExcel->createSheet();
            }
            $objPHPExcel->setActiveSheetIndex($sheetIndex++);
            // 设置工作薄名称
            $objPHPExcel->getActiveSheet()->setTitle($head);
            // 设置默认字体和大小
            //$objPHPExcel->getDefaultStyle()->getFont()->setName(iconv('gbk', 'utf-8', '宋体'));
            //$objPHPExcel->getDefaultStyle()->getFont()->setSize(10);
            // 表头
            $lineNum = 1;
            $cellNum = 66;
            foreach ($need2sum as $sumHead => $sumPos) {
                $objPHPExcel->getActiveSheet()->setCellValueExplicit(chr($cellNum++) . $lineNum, $sumHead, PHPExcel_Cell_DataType::TYPE_STRING);
            }
            $lineNum = 2;
            foreach ($hashTable[$head] as $hashTag => &$row) {
                $cellNum = 65;
                $objPHPExcel->getActiveSheet()->setCellValueExplicit(chr($cellNum++) . $lineNum, $hashTag, PHPExcel_Cell_DataType::TYPE_STRING);
                foreach ($need2sum as $sumHead => $sumPos) {
                    //$row[$sumHead . 't'] =  $hashTable[$head][ $row[$pos] ][$sumHead];//use to temp test
                    $row[$sumHead] /= $row[$sumHead . 'c'];
                    // 写入！
                    $objPHPExcel->getActiveSheet()->setCellValueExplicit(chr($cellNum++) . $lineNum, $row[$sumHead], PHPExcel_Cell_DataType::TYPE_NUMERIC);
                }
                ++$lineNum;

            }

        }

        //var_dump($hashTable);

    }

    $filename = "excel".$year.".xls";
    // 如果需要输出EXCEL格式
    if($m_exportType=="excel"){
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        // 从浏览器直接输出$filename
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        // header("Content-Type:application/force-download");
        header("Content-Type: application/vnd.ms-excel;");
        // header("Content-Type:application/octet-stream");
        // header("Content-Type:application/download");
        header("Content-Disposition:attachment;filename=".$filename);
        header("Content-Transfer-Encoding:binary");
        $objWriter->save("php://output");
    }
    // 如果需要输出PDF格式
    if($m_exportType=="pdf"){
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'PDF');
        $objWriter->setSheetIndex(0);
        header("Pragma: public");
        header("Expires: 0");
        header("Cache-Control:must-revalidate, post-check=0, pre-check=0");
        header("Content-Type:application/force-download");
        header("Content-Type: application/pdf");
        header("Content-Type:application/octet-stream");
        header("Content-Type:application/download");
        header("Content-Disposition:attachment;filename=".$m_strOutputPdfFileName);
        header("Content-Transfer-Encoding:binary");
        $objWriter->save("php://output");
    }


?>
