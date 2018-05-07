<?php
///////////////////////////////////////////////////////////////////////////////////////////
// ADD BY HouBaron 20161108 START
////////////////////////////////////////////////////////////////////////////////////////////
#计算理论课工作量

#导入配置
include dirname(__FILE__) . './../config.php';
include dirname(__FILE__) . './../functions.php';

# 判断是否登录
if(!isset($_SESSION['username'])) {
    header("location: ./error.php?txt="."请登录后再操作.");
    exit();
}

# 检验权限
if(!$_SESSION['modify_data']) {
    header("location: ./error.php?txt="."您没有导入专业平台课的权限.");
    exit();
}


# 检验权限
if(!$_SESSION['import_lilun']) {
    header("location: ./error.php?txt="."您没有导入理论课表的权限.");
    exit();
}

if(!isset($_FILES["file"]["name"])) {
    header("location: ./error.php?txt="."你没有上传文件.");
    exit();
}
if (($_FILES["file"]["type"] == "application/vnd.ms-excel")
    && ($_FILES["file"]["size"] < 20000000)) {
    if ($_FILES["file"]["error"] > 0) {
        echo "Error: " . $_FILES["file"]["error"] . "<br />";
    } else {
        echo "Upload: " . $_FILES["file"]["name"] . "<br />";
        echo "Type: " . $_FILES["file"]["type"] . "<br />";
        echo "Size: " . ($_FILES["file"]["size"] / 1024) . " Kb<br />";
        echo "Stored in: " . $_FILES["file"]["tmp_name"];

    }
} else {
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

//没有查询构造器，痛苦
for ($currentRow = 2; $currentRow <= $allRow; ++$currentRow) {
    $course_id   = (String)$currentSheet->getCell('A'.$currentRow)->getValue();
    $course_name = (String)$currentSheet->getCell('B'.$currentRow)->getValue();
    $flag        = (int)   $currentSheet->getCell('C'.$currentRow)->getValue();
    $sql = "INSERT INTO `teshukecheng`(`course_id`, `course_name`, `flag`) VALUES ('$course_id', '$course_name', '$flag');";

    $result = mysql_query($sql);
    if(!$result) {
        die("insert error:" . mysql_error());
    }
}

header("location: ./error.php?txt="."导入成功！");
exit();

///////////////////////////////////////////////////////////////////////////////////////////
// ADD BY HouBaron 20161108 END
////////////////////////////////////////////////////////////////////////////////////////////
?>
