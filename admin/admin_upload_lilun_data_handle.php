<?php
    # 导入理论课表

    # 导入配置
    include dirname(__FILE__) . './../config.php';
    include dirname(__FILE__) . './../functions.php';
    set_time_limit(0);

    # 判断是否登录
    if(!isset($_SESSION['username'])) {
        header("location: ./error.php?txt="."请登录后再操作.");
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
    }

    /*
    * 读取Excel
    */
    include 'lilun_excel_handle.php';

    /*
    * 清空理论临时数据表
    */
    $sql = "TRUNCATE TABLE `lilun_temp`";
    $result = mysql_query($sql);
    if(!$result) {
        die("sql error: ".mysql_error());
        exit();
    }

    # 教师号相同，课程号相同，序号相同不导入
    $iscf =array();

    $youxiao  = 0;
    for($row = 2; $row <= $allRow; $row++) {
        if ($output[$row]['kcxz'] != "课程") continue;
        if(isset($iscf[$output[$row]['teacher_id']."_".$output[$row]['course_id']."_".$output[$row]['course_index']]))
            continue;
        else
            $iscf[$output[$row]['teacher_id']."_".$output[$row]['course_id']."_".$output[$row]['course_index']] = 1;
        $youxiao++;

        $sql = "INSERT INTO `lilun_temp` (xueqi, course_id, course_name, course_index, course_alias, num_of_p,
            xueshi, xuankeshuxing, heban, kcxz, teacher_id, teacher_name,
            teacher_yuanxi, teacher_zc) VALUES (\"".
            $output[$row]['xueqi']."\",\"".
            $output[$row]['course_id']."\",\"".
            $output[$row]['course_name']."\",\"".
            $output[$row]['course_index']."\",\"".
            $output[$row]['course_alias']."\",\"".
            $output[$row]['num_of_p']."\",\"".
            $output[$row]['xueshi']."\",\"".
            $output[$row]['xuankeshuxing']."\",\"".
            $output[$row]['heban']."\",\"".
            $output[$row]['kcxz']."\",\"".
            $output[$row]['teacher_id']."\",\"".
            $output[$row]['teacher_name']."\",\"".
            $output[$row]['teacher_yuanxi']."\",\"".
            $output[$row]['teacher_zc']."\"
            );";

        $result = mysql_query($sql);
        
        if(!$result) {
            die("insert error:" . mysql_error());
        }

    }


            header("location: ./error.php?txt="."您的文档中有“".($allRow-1)."”条数据，去重后有“".$youxiao."”条数据.");
            exit();



?>
