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
//echo$_POST['xueqi'];
if(isset($_POST['json'])){
//    echo $_POST['json'];

    $obj=json_decode($_POST['json']);
    foreach($obj as $i){
        $xueqi=$i->学期;
        $teacher_id=$i->教师id;
        $teacher_name=$i->教师名;
        $shijian_name=$i->实践名;
        $course_id=$i->课程号;
        $shijian_type=$i->实践类型;
        $zhoushu=$i->周数;
        $num_of_p=$i->人数;
        $banji=$i->班级;
        $banjishu=$i->班级数;
        $didian=$i->地点;
        $teacher_xueyuan=$i->学院;
        $teacher_xi=$i->系;

        $sql = "INSERT INTO `shijian_temp`
        (
            xueqi,
            teacher_id,
            teacher_name,
            shijian_name,
            course_id,
            course_index,
            shijian_type,
            zhoushu,
            num_of_p,
            banji,
            banjishu,
            didian,
            teacher_xueyuan,
            teacher_xi,
            username
            ) VALUES (
            \"".$xueqi."\",
            \"".$teacher_id."\",
            \"".$teacher_name."\",
            \"".$shijian_name."\",
            \"".$course_id."\",
            NULL,
            \"".$shijian_type."\",
            \"".$zhoushu."\",
            \"".$num_of_p."\",
            \"".$banji."\",
            \"".$banjishu."\",
            \"".$didian."\",
            \"".$teacher_xueyuan."\",
            \"".$teacher_xi."\",
            \"".$_SESSION['username']."\"
            );";

//echo $sql;
        $result = mysql_query($sql);

        if(!$result) {
            die("SQL ERROR : ".mysql_error());
        }
    }
}



?>