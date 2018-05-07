<?php
    include dirname(__FILE__) . './../config.php';
    include dirname(__FILE__) . './../functions.php';


    # 判断是否登录
    if(!isset($_SESSION['username'])) {
        header("location: ./error.php?txt="."请登录后再操作.");
        exit();
    }

    # 检验权限
    if(!$_SESSION['modify_zcxishu']) {
        header("location: ./error.php?txt="."您没有修改职称系数的权限.");
        exit();
    }
    $handle = 0;
    if(isset($_POST['handle'])) {
        $handle = $_POST['handle'];
    } else {
        header("location: ./error.php?txt="."错误的操作.");
        exit();
    }
    if($handle == 1) {
        $sql = "UPDATE `zcxishu` SET `xishu`=\"". $_POST['xishu'] ."\" WHERE `id`= \"".$_POST['id']."\"";
        $result = mysql_query($sql);
        if(!$result) {
            die("mysql error:" . mysql_error());
        } else {
            header("location: ./modify_zcxishu.php");
            exit();
        }
    }

    if($handle == 2) {
        $sql = "INSERT INTO `zcxishu` (
            name,
            xishu
            ) VALUES (
            \"".$_POST['name']."\",
            \"".$_POST['xishu']."\"
            )";
        $result = mysql_query($sql);
        if(!$result) {
            die("sql error : " . mysql_error());
        } else {
            header("location: ./modify_zcxishu.php");
            exit();
        }
    }

?>