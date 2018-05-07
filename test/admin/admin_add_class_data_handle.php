<?php
    # 导入班级信息处理

    include dirname(__FILE__) . './../config.php';
    include dirname(__FILE__) . './../functions.php';
    set_time_limit(0);


    # 判断是否登录
    if(!isset($_SESSION['username'])) {
        header("location: ./error.php?txt="."请登录后再操作.");
        exit();
    }

    # 检验权限
    if(!$_SESSION['import_banji']) {
        header("location: ./error.php?txt="."您没有导入班级信息的权限.");
        exit();
    }


    if(!isset($_FILES["file"]["name"])) {
            header("location: ./error.php?txt="."你没有上传文件.");
            exit();
    }
    if (($_FILES["file"]["type"] == "application/vnd.ms-excel")
        && ($_FILES["file"]["size"] < 2000000))
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
        echo "Invalid file";
        exit();
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

        $output[$currentRow]['name'] = (string)$currentSheet->getCell('A'.$currentRow)->getValue();
        $output[$currentRow]['zhuanye'] = (String)$currentSheet->getCell('B'.$currentRow)->getValue();

    }

    /*
    for($currentRow = 1; $currentRow <= $allRow; $currentRow ++) {
        echo "<table>";
        echo "<tr>";
        
        echo "<td>"; echo $output[$currentRow]['teacher_id']; echo "</td>";
        echo "<td>"; echo $output[$currentRow]['teacher_name']; echo "</td>";
        echo "<td>"; echo $output[$currentRow]['teacher_ifsd']; echo "</td>";
        echo "<td>"; echo $output[$currentRow]['teacher_ZC']; echo "</td>";
        echo "<td>"; echo $output[$currentRow]['teacher_YX']; echo "</td>";
        echo "<td>"; echo $output[$currentRow]['teacher_X']; echo "</td>";

        echo "</tr>";
        echo "</table>";
    }
    */
    set_time_limit(0);
    /*
    * 清空数据表
    */
    $sql = "TRUNCATE TABLE `class`";
    $result = mysql_query($sql);
    if(!$result) {
        die("sql error: ".mysql_error());
        exit();
    }

	$countofok = 0;
    for($row = 2; $row <= $allRow; $row++) {
        $sql = "INSERT INTO `class`(name, zhuanye) value(\"".
            $output[$row]['name']."\",\"".
            $output[$row]['zhuanye']."\");";
        $result = mysql_query($sql);
        if(!$result) {
            die("insert error:" . mysql_error());
        }
		$countofok ++;
    }

    header("location: ./error.php?txt="."导入成功.(".$countofok."条)");
    exit();

?>
