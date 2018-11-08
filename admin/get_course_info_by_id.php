<?php

include "./../config.php";
include "./../functions.php";

# 判断是否登录
if(!isset($_SESSION['username'])) {
    header("location: ./error.php?txt="."请登录后再操作.");
    exit();
}

# 检验权限
if(!$_SESSION['add_gzl']) {
    header("location: ./error.php?txt="."您没有添加工作量的权限.");
    exit();
}

if(isset($_POST['course_id'])){
    $sql="SELECT DISTINCT shijian_name,shijian_type FROM shijian_temp WHERE course_id='".$_POST['course_id']."'";

    $result = mysql_query($sql);
    if(!$result) {
        die("SQL ERROR : ".mysql_error());
    }

    $row=mysql_fetch_assoc($result);

    $arr=array('shijian_name'=>$row['shijian_name'],'shijian_type'=>$row['shijian_type']);
    echo json_encode($arr);
}

?>