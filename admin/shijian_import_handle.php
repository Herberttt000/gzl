<?php
    /*
    # 实践课的导入处理
    */
    //ini_set('display_errors',1);            //错误信息
    //ini_set('display_startup_errors',1);    //php启动错误信息
    //error_reporting(-1);                    //打印出所有的 错误信息

    include './../config.php';
    include './../functions.php';
    include './header.php';

    # 判断是否登录
    if (!isset($_SESSION['username'])) {
        header("location: ./error.php?txt="."请登录后再操作.");
        exit();
    }

    # 检验权限
    if (!$_SESSION['import_shijian']) {
        header("location: ./error.php?txt="."您没有导入实践课信息的权限.");
        exit();
    }



    if (!isset($_FILES["file"]["name"])) {
        header("location: ./error.php?txt="."你没有上传文件.");
        exit();
    }
    if (($_FILES["file"]["type"] == "application/vnd.ms-excel") && ($_FILES["file"]["size"] < 2000000)) {
        if ($_FILES["file"]["error"] > 0) {
            echo "Error: " . $_FILES["file"]["error"] . "<br />";
            exit();
        } else {
            echo "Upload: " . $_FILES["file"]["name"] . "<br />";
            echo "Type: " . $_FILES["file"]["type"] . "<br />";
            echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
            echo "Stored in: " . $_FILES["file"]["tmp_name"];

        }
    } else {
        echo "Invalid file";
        //exit();
    }

    echo "文件上传成功!<br/>";

    echo "读取数据...<br/>";
    /*
    * 读取Excel
    */
    require_once './../include/Classes/PHPExcel/IOFactory.php';

    $filePath = $_FILES["file"]["tmp_name"];

    $fileType = PHPExcel_IOFactory::identify($filePath); //文件名自动判断文件类型
    $objReader = PHPExcel_IOFactory::createReader($fileType);
    $objPHPExcel = $objReader->load($filePath);


    $currentSheet = $objPHPExcel->getSheet(0); //第一个工作簿
    $allRow = $currentSheet->getHighestRow(); //行数
    $output = array();
    $preType = '';

    //$qh = $currentSheet->getCell('A4')->getValue();
    //按照文件格式从第7行开始循环读取数据
    for ($currentRow = 2; $currentRow <= $allRow; ++$currentRow) {
        $output[$currentRow]['xueqi']           = (int)$currentSheet->getCell('A'.$currentRow)->getValue();
        $output[$currentRow]['teacher_id']      = str_replace(' ','',(String)$currentSheet->getCell('B'.$currentRow)->getValue());
        $output[$currentRow]['teacher_name']    = str_replace(" ", "", (String)$currentSheet->getCell('C'.$currentRow)->getValue());
        $output[$currentRow]['teacher_xueyuan'] = (String)$currentSheet->getCell('D'.$currentRow)->getValue();
        $output[$currentRow]['teacher_xi']      = (String)$currentSheet->getCell('E'.$currentRow)->getValue();

        $output[$currentRow]['shijian_name']    = (String)$currentSheet->getCell('F'.$currentRow)->getValue();
        $output[$currentRow]['course_index']    = (int)   $currentSheet->getCell('G'.$currentRow)->getValue();
        $output[$currentRow]['course_id']       = (String)$currentSheet->getCell('H'.$currentRow)->getValue();
        $output[$currentRow]['shijian_type']    = (String)$currentSheet->getCell('I'.$currentRow)->getValue();
        $output[$currentRow]['zhoushu']         = (float) $currentSheet->getCell('J'.$currentRow)->getValue();
        $output[$currentRow]['num_of_p']        = (String)$currentSheet->getCell('K'.$currentRow)->getValue();
        $output[$currentRow]['banji']           = (String)$currentSheet->getCell('L'.$currentRow)->getValue();
        $output[$currentRow]['banjishu']        = (int)   $currentSheet->getCell('M'.$currentRow)->getValue();
        $output[$currentRow]['didian']          = (String)$currentSheet->getCell('N'.$currentRow)->getValue();
    }
    //echo $allRow;
    //exit();

    # 从表格的第二行获取上传的学期
    $upload_xueqi = $output[2]['xueqi'];
    # 创建标准的教师哈希表检查错误信息并返回。
    //$sql = "SELECT * FROM `teachers` WHERE `xueqi`=\"".$upload_xueqi."\";";
    $sql = "SELECT * FROM `teachers` WHERE `xueqi`=\"".$upload_xueqi."\";";
    $result = mysql_query($sql);
    if (!$result) {
        die("sql error: ".mysql_error());
    }
    $teacher_id_rows = array();
    $teacher_name_rows = array();
    while ($row = mysql_fetch_assoc($result)) {
        if ($row['iswork']) {
            $teacher_id_rows[$row['teacher_id']] = $row['teacher_name'];
            $teacher_name_rows[$row['teacher_name']] = $row['teacher_id'];
        }
    }

    # 准备Excel
    // Excel开始
    // 准备EXCEL的包括文件
    // Error reporting
    error_reporting(0);
    // PHPExcel
    require_once dirname(__FILE__) . './../include/Classes/PHPExcel.php';
    // 生成新的excel对象
    $objPHPExcel = new PHPExcel();
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
    # 定义数据类型
    $m_exportType = "excel";
    $objPHPExcel->getActiveSheet()->setCellValue('A1', '学期');
    $objPHPExcel->getActiveSheet()->setCellValue('B1', '教师号');
    $objPHPExcel->getActiveSheet()->setCellValue('C1', '姓名');
    $objPHPExcel->getActiveSheet()->setCellValue('D1', '学院');
    $objPHPExcel->getActiveSheet()->setCellValue('E1', '系');
    $objPHPExcel->getActiveSheet()->setCellValue('F1', '实践名称');
    $objPHPExcel->getActiveSheet()->setCellValue('G1', '序号');
    $objPHPExcel->getActiveSheet()->setCellValue('H1', '课程号');
    $objPHPExcel->getActiveSheet()->setCellValue('I1', '实践类型');
    $objPHPExcel->getActiveSheet()->setCellValue('J1', '周数');
    $objPHPExcel->getActiveSheet()->setCellValue('K1', '人数');
    $objPHPExcel->getActiveSheet()->setCellValue('L1', '班级');
    $objPHPExcel->getActiveSheet()->setCellValue('M1', '班级数');
    $objPHPExcel->getActiveSheet()->setCellValue('N1', '地点');
    $objPHPExcel->getActiveSheet()->setCellValue('O1', '错误信息');

    $num = 2;   //起始行
    function insert2Excel($error_row, $errorinfo, $hang) {
            Global $objPHPExcel;
            $objPHPExcel->getActiveSheet()->setCellValue('A'.$hang, (int)$error_row['xueqi']);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('B'.$hang, (string)$error_row['teacher_id'],PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('C'.$hang, (string)$error_row['teacher_name'],PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('D'.$hang, (string)$error_row['teacher_xueyuan'],PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('E'.$hang, (string)$error_row['teacher_xi'],PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('F'.$hang, (string)$error_row['shijian_name'],PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValue('G'.$hang, (int)$error_row['course_index']);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('H'.$hang, (string)$error_row['course_id'],PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('I'.$hang, (string)$error_row['shijian_type'],PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValue('J'.$hang, (float)$error_row['zhoushu']);
            $objPHPExcel->getActiveSheet()->setCellValue('K'.$hang, (int)$error_row['num_of_p']);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('L'.$hang, (string)$error_row['banji'],PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValue('M'.$hang, (int)$error_row['banjishu']);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('N'.$hang, (string)$error_row['didian'],PHPExcel_Cell_DataType::TYPE_STRING);
            $objPHPExcel->getActiveSheet()->setCellValueExplicit('O'.$hang, (string)$errorinfo,PHPExcel_Cell_DataType::TYPE_STRING);

    }

    echo '检查数据有效性<br/>';
    #首先检查一遍教师的正确性
    $error = "";
    $iserror = 0;
    $un = 0;
    //echo "<br /> allrows:".$allRow."<br />";
    for($row = 2; $row <= $allRow; $row++) {
        if($output[$row]['xueqi']%10 > 2 || $output[$row]['xueqi'] < 10000) {
            $error .= "请检查你的“学期”填写是否正确.";
            $iserror = 1;
        }
        if(!isset($teacher_id_rows[$output[$row]['teacher_id']])) {
            $error .= "不存在教师号为“".$output[$row]['teacher_id']."”的教师.";
            $iserror = 1;
        }

        if(!isset($teacher_name_rows[$output[$row]['teacher_name']])) {
            $error .= "不存在姓名为“".$output[$row]['teacher_name']."”的教师.";
            $iserror = 1;
        }

        if(isset($teacher_name_rows[$output[$row]['teacher_name']])&&$teacher_name_rows[$output[$row]['teacher_name']]!=$output[$row]['teacher_id']) {
            $error .= "姓名为“".$output[$row]['teacher_name']."”的老师教师号应该为“".$teacher_name_rows[$output[$row]['teacher_name']]."”.";
            $iserror = 1;
        }

        if(isset($teacher_id_rows[$output[$row]['teacher_id']])&&$teacher_id_rows[$output[$row]['teacher_id']]!=$output[$row]['teacher_name']) {
            $error .= "教师号为“".$output[$row]['teacher_id']."”的教师，姓名应该为“".$teacher_id_rows[$output[$row]['teacher_id']]."”";
            $iserror = 1;
        }

        if($iserror) {
            $un = 1;
            //echo "<br />".$error."<br />";
            //flush();

            insert2Excel($output[$row], $error, $num++);

            $error = "";
            $iserror = 0;
            $output[$row]['error'] = 1;

        } else {

            $output[$row]['error'] = 0;
        }

    }
    #发现错误的信息停止导入
    // if($un!=0) {

    // }

    // #判断实践临时里是否已经有该教师信息
    // $isset_in_temp = array();
    // for($row = 2; $row <= $allRow; $row++) {
    //     $sql = "select count(*) from `shijian_temp` where `teacher_id`=\"".$output[$row]['teacher_id']."\"
    //     AND `username`=\"".$_SESSION['username']."\";";
    //     $result = mysql_query($sql);
    //     if(!$result) {
    //         die("sql error: " . mysql_error());
    //     }
    //     $cunzai = mysql_fetch_row($result);
    //     if($cunzai[0]==0) {
    //         $isset_in_temp[$output[$row]['teacher_id']] = 0;
    //     } else {
    //         $isset_in_temp[$output[$row]['teacher_id']] = 1;
    //     }
    // }


    for($row = 2; $row <= $allRow; $row++) {

        # 该条信息有错误就不插入
        if($output[$row]['error']) continue;


        // if($isset_in_temp[$output[$row]['teacher_id']]) {
        //     $sql = "UPDATE `shijian_temp` SET
        //     `xueqi`=\"".$output[$row]['xueqi']."\",
        //     `teacher_id`=\"".$output[$row]['teacher_id']."\",
        //     `teacher_name`=\"".$output[$row]['teacher_name']."\",
        //     `teacher_xueyuan`=\"".$output[$row]['teacher_xueyuan']."\",
        //     `teacher_xi`=\"".$output[$row]['teacher_xi']."\",
        //     `shijian_name`=\"".$output[$row]['shijian_name']."\",
        //     `course_id`=\"".$output[$row]['course_id']."\",
        //     `shijian_type`=\"".$output[$row]['shijian_type']."\",
        //     `zhoushu`=\"".$output[$row]['zhoushu']."\",
        //     `num_of_p`=\"".$output[$row]['num_of_p']."\",
        //     `banji`=\"".$output[$row]['banji']."\",
        //     `banjishu`=\"".$output[$row]['banjishu']."\",
        //     `didian`=\"".$output[$row]['didian']."\"
        //     WHERE `teacher_id`=".$output[$row]['teacher_id']."
        //     AND `username`=\"".$_SESSION['username']."\";";
        // } else {
            $sql = "INSERT INTO `shijian_temp` (xueqi, teacher_id,
                teacher_name, shijian_name, course_id,
                shijian_type, zhoushu, num_of_p, banji,
                banjishu,
                didian,teacher_xueyuan,teacher_xi,username
                ) VALUES (\"".
                $output[$row]['xueqi']."\",\"".
                $output[$row]['teacher_id']."\",\"".
                $output[$row]['teacher_name']."\",\"".
                $output[$row]['shijian_name']."\",\"".
                $output[$row]['course_id']."\",\"".
                $output[$row]['shijian_type']."\",\"".
                $output[$row]['zhoushu']."\",\"".
                $output[$row]['num_of_p']."\",\"".
                $output[$row]['banji']."\",\"".
                $output[$row]['banjishu']."\",\"".
                $output[$row]['didian']."\",\"".
                $output[$row]['teacher_xueyuan']."\",\"".
                $output[$row]['teacher_xi']."\",\"".
                $_SESSION['username']."\"
                );";
// }
        $result = mysql_query($sql);
        if(!$result) {
            die("insert error:" . mysql_error());
        }
        // }else{
        //     echo "<div class=\"alert alert-success alert-dismissible\" role=\"alert\">导入完成！</div>";
        //     flush();
        //     exit();
        // }#end if
}#end while


if ($un != 0) {
    $year = time();
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
        //$objWriter->save("php://output");
        echo "生成错误文件..<br/>";
        var_dump($objWriter->save($filename));
        //header("location: ./excel".$year.".xls");

        echo "<br /> 该表 <span class=\"label label-danger\">".($num-2)."</span> 条错误信息，请务必下载错误表，修正后上传！(正确的条目已经写入数据库，请勿重复上传)";
        $url = "./excel".$year.".xls";
        echo "<br /><br /><a href=\"".$url."\"> 下载错误信息表 </a>(已含有错误信息)";


    }
}



?>
