<?php
    # 处理 导入的教师表
    ini_set('upload_max_filesize', 8388608);
    include dirname(__FILE__) . './../config.php';
    include dirname(__FILE__) . './../functions.php';
	set_time_limit(0);
    # 判断是否登录
    if(!isset($_SESSION['username'])) {
        header("location: ./error.php?txt="."请登录后再操作.");
        exit();
    }

    # 检验权限
    if(!$_SESSION['import_teachers']) {
        header("location: ./error.php?txt="."您没有导入教师信息的权限.");
        exit();
    }


    if(!isset($_FILES["file"]["name"])) {
        header("location: ./error.php?txt="."你没有上传文件.");
        exit();
    }
    //print_r($_FILES);
    if (($_FILES["file"]["type"] == "application/vnd.ms-excel")
        && ($_FILES["file"]["size"] < 20000000))
    {
        if ($_FILES["file"]["error"] > 0)
        {
            echo "Error: " . $_FILES["file"]["error"] . "<br />";
        }
        else
        {
            echo "Upload: " . $_FILES["file"]["name"] . "<br />";
            echo "Type: " . $_FILES["file"]["type"] . "<br />";
            echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
            echo "Stored in: " . $_FILES["file"]["tmp_name"];
        }
    }
    else
    {
        echo "Invalid file".$_FILES["file"]["error"];
        //exit();
    }
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
    //按照文件格式从第2行开始循环读取数据
    for($currentRow = 2;$currentRow<=$allRow;$currentRow++){

        $output[$currentRow]['teacher_id'] = (string)$currentSheet->getCell('A'.$currentRow)->getValue();
        $output[$currentRow]['teacher_name'] = (String)$currentSheet->getCell('B'.$currentRow)->getValue();
        $output[$currentRow]['id_card'] = (String)$currentSheet->getCell('C'.$currentRow)->getValue();
        $output[$currentRow]['xueyuan'] = (String)$currentSheet->getCell('D'.$currentRow)->getValue();
        $output[$currentRow]['teacher_X'] = (String)$currentSheet->getCell('E'.$currentRow)->getValue();
        $output[$currentRow]['teacher_zc'] = (String)$currentSheet->getCell('F'.$currentRow)->getValue();
        $output[$currentRow]['gz_id'] = (String)$currentSheet->getCell('G'.$currentRow)->getValue();
        $output[$currentRow]['yikatong'] = (String)$currentSheet->getCell('H'.$currentRow)->getValue();
        $issd = (String)$currentSheet->getCell('I'.$currentRow)->getValue();
        switch ($issd) {
            case '硕导':
                break;
            case '博导':
                break;
            default:
                $issd = "未知";
                break;
        }
        $output[$currentRow]['issd'] = $issd;
        $iswork = (String)$currentSheet->getCell('J'.$currentRow)->getValue();
        if($iswork == "正常") $iswork = 1;
        else $iswork = 0;
        $output[$currentRow]['iswork'] = $iswork;
        $output[$currentRow]['xueqi'] = (int)$currentSheet->getCell('K'.$currentRow)->getValue();
    }

    // for($currentRow = 1; $currentRow <= $allRow; $currentRow ++) {
    //     echo "<table>";
    //     echo "<tr>";

    //     echo "<td>"; echo $output[$currentRow]['teacher_id']; echo "</td>";
    //     echo "<td>"; echo $output[$currentRow]['teacher_name']; echo "</td>";
    //     echo "<td>"; echo $output[$currentRow]['id_card']; echo "</td>";
    //     echo "<td>"; echo $output[$currentRow]['teacher_X']; echo "</td>";
    //     echo "<td>"; echo $output[$currentRow]['iswork']; echo "</td>";
    //     echo "</tr>";
    //     echo "</table>";
    // }
    //


    //得到学期
    $xueqi = $output[2]['xueqi'];

    /*
    * 清空教师数据表
    */
    $sql = "DELETE FROM teachers
    		WHERE xueqi=$xueqi";

    $result = mysql_query($sql);
    if(!$result) {
        die("sql error: ".mysql_error());
        exit();
    }

    for($row = 2; $row <= $allRow; $row++) {
        $sql = "INSERT INTO `teachers`(
            teacher_id,
            teacher_name,
            idcard,
            xueyuan,
            xi,
            zhicheng,
            gz_id,
            yikatong,
            issd,
            iswork,
            xueqi) VALUES (\"".
            $output[$row]['teacher_id']."\",\"".
            $output[$row]['teacher_name']."\",\"".
            $output[$row]['id_card']."\",\"".
            $output[$row]['xueyuan']."\",\"".
            $output[$row]['teacher_X']."\",\"".
            $output[$row]['teacher_zc']."\",\"".
            $output[$row]['gz_id']."\",\"".
            $output[$row]['yikatong']."\",\"".
            $output[$row]['issd']."\",\"".
            $output[$row]['iswork']."\",\"".
            $output[$row]['xueqi']."\");";
        $result = mysql_query($sql);
        if(!$result) {
            echo "insert error:" . mysql_error();
            echo "<br />";
            flush();
        }
    }
    include "./update_teacher_table.php";
    header("location: ./error.php?txt="."导入成功,导入".$xueqi."教师数据.");
    exit();


?>
