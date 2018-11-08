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

if(isset($_POST['teacher_id'])){
    $sql="SELECT DISTINCT teacher_name,xueyuan,xi FROM teachers WHERE teacher_id='".$_POST['teacher_id']."'";
    $result = mysql_query($sql);
    if(!$result) {
        die("SQL ERROR : ".mysql_error());
    }

    $row=mysql_fetch_assoc($result);

    $arr=array('teacher_name'=>$row['teacher_name'],'teacher_xueyuan'=>$row['xueyuan'],'teacher_yuanxi'=>$row['xi']);
    echo json_encode($arr);
}

?>