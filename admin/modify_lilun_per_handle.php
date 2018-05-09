<?php

ini_set('display_errors',1);            //错误信息
ini_set('display_startup_errors',1);    //php启动错误信息
error_reporting(-1);                    //打印出所有的 错误信息
include dirname(__FILE__) . './../config.php';
include dirname(__FILE__) . './../functions.php';

    # 判断是否登录
if(!isset($_SESSION['username'])) {
    header("location: ./error.php?txt="."请登录后再操作.");
    exit();
}

    # 检验权限
if(!$_SESSION['modify_data']) {
    header("location: ./error.php?txt="."您没有修改工作量的权限.");
    exit();
}
?>
<?php

?>
<?php




if($_POST['op']=="lilun") {

    # 更新改动的条目\
    if(!isset($_POST['add'])) {
        $sql = "UPDATE  `lilun` SET
        `xueshi`=\"".$_POST['xueshi']."\",
        `num_of_p`=\"".$_POST['num_of_p']."\"
        WHERE `id`=\"".$_POST['id']."\";";
    }else{
        $sql = "INSERT INTO `lilun`
        (
            xueqi,
            course_id,
            course_name,
            course_index,
            teacher_yuanxi,
            teacher_id,
            teacher_name,
            teacher_zc,
            heban,
            xueshi,
            num_of_p
            ) VALUES (
            \"".$_POST['xueqi']."\",
            \"".$_POST['course_id']."\",
            \"".$_POST['course_name']."\",
            \"".$_POST['course_index']."\",
            \"".$_POST['teacher_yuanxi']."\",
            \"".$_POST['teacher_id']."\",
            \"".$_POST['teacher_name']."\",
            \"".$_POST['teacher_zc']."\",
            \"".$_POST['heban']."\",
            \"".$_POST['xueshi']."\",
            \"".$_POST['num_of_p']."\"
            );";
    }
    $result = mysql_query($sql);
    if(!$result) {
        die("sql error : ".mysql_error());
    }

    # 刷新该教师的记录
    $sql = "SELECT * FROM `lilun`
    WHERE `teacher_id`='".$_POST['teacher_id']."'
    AND `xueqi`='".$_POST['year']."'
    ORDER BY `num_of_p` DESC;";
    $ll_result = mysql_query($sql);
    if(!$ll_result) {
        die("SQL ERROR : ".mysql_error());
    }



    # 获取就算人数系数需要的系数 K的递增值等信息
    $sql = "SELECT * FROM `jisuanxishu`;";
    $result = mysql_query($sql);
    if(!$result) {
        die("sql select error jisuanxishu" . mysql_error());
    }
    $xishu_rows = array();
    while($row = mysql_fetch_assoc($result)) {
        $xishu_rows[$row['name']] = $row['value'];
    }

    # 获取班级是否是3表
    $sql = "SELECT * FROM `class`;";
    $result = mysql_query($sql);
    if(!$result) {
        die("sql select error class: " . mysql_error());
    }
    $issb = array();
    while ($row = mysql_fetch_assoc($result)) {
        if(substr($row['zhuanye'], -1) == "."
            || substr($row['zhuanye'], -1) == "L" ) {
            $issb[$row['name']] = 1;
        }else{
            $issb[$row['name']] = 0;
        }
    }

    # 职称系数
    $sql = "SELECT * FROM `zcxishu`;";
    $result = mysql_query($sql);
    if(!$result) {
        die("sql select error :  ". mysql_error());
    }
    $zc_rows = array();
    while ($row = mysql_fetch_assoc($result)) {
        $zc_rows[$row['name']] = $row['xishu'];
    }


    # 教师职称提取
    $sql = "SELECT * FROM `teachers` WHERE `xueqi`=\"".$_POST['year']."\";";
    $result_t = mysql_query($sql);
    if(!$result_t) {
        die("SQL ERROR : " . mysql_error());
    }
    $id2zc = array();
    while ($row_t = mysql_fetch_assoc($result_t)) {
        $id2zc[$row_t['teacher_id']] = $row_t['zhicheng'];
    }
    # 重复课细节解决:
    # 建立一个数组记录老师某门课的授课次数;
    # 由于我们查询数据的时候按照人数倒序排序了 result
    # 所以得到的结果是人数多的优先 ORDER BY `num_of_p` DESC
    $cfk = array();

    # 这里再过滤一下课序号相同的课程
    # 用哈希记录一下
    $iscf = array();

    # 循环计算该教师的工作量

    while($row = mysql_fetch_assoc($ll_result)) {
        #重复课哈希
        if(substr($row['course_name'],0,6)=="体育"){
            # 体育课哈希方式
            $cfk_string = $row['teacher_id']."_".substr($row['course_id'], 0, 7)."_".$row['course_name'];
            if(!isset($cfk[$cfk_string])) {
                $cfk[$cfk_string] = 1;
            } else {
                $cfk[$cfk_string] += 1;
            }
        }else{
            # 理论课哈希方式:
            #                1) 英语课
            #                2) 普通理论课
            if (preg_match("/([^0-9]){1,8}\d\d-([a-zA-Z])\d班/", $row['course_alias'],$matchs) == 1) {
                # 针对英语课进行特殊哈希：加入班级等级因素
                if (strlen($row['course_id']) == 9) {
                    $cfk_string = $row['teacher_id']."_".substr($row['course_id'], 0, 7)."_".$matchs[2];
                }else if(strlen($row['course_id']) == 12){
                    $cfk_string = $row['teacher_id']."_".substr($row['course_id'], 0, 10)."_".$matchs[2];
                }
            } else {
                if (strlen($row['course_id']) == 9) {
                    $cfk_string = $row['teacher_id']."_".substr($row['course_id'], 0, 7)."_";
                }else if (strlen($row['course_id']) == 12){
                    $cfk_string = $row['teacher_id']."_".substr($row['course_id'], 0, 10)."_";
                }
            }
            // 2016-01-02:
            //            课程号改变，重复课哈希改变！
            // $cfk_string = $row['teacher_id']."_".substr($row['course_id'], 0, 7)."_";
            // if(!isset($cfk[$cfk_string])) {
            //     $cfk[$cfk_string] = 1;
            // } else {
            //     $cfk[$cfk_string] += 1;
            // }
        }

            # 重复课系数
        $C = 0;
        if($cfk[$cfk_string]==1){
            $C = $xishu_rows['CFK1'];
        } elseif($cfk[$cfk_string]==2){
            $C = $xishu_rows['CFK2'];
        } elseif ($cfk[$cfk_string]==3){
            $C = $xishu_rows['CFK3'];
        } else {
            $C = $xishu_rows['CFK4'];
        }

            // # 记录课序号哈希
            // if(!isset($iscf[$row['course_index']."_".substr($row['course_id'], 0, 7)."_".$row['teacher_id']])) {
            //     $iscf[$row['course_index']."_".substr($row['course_id'], 0, 7)."_".$row['teacher_id']] = 1;
            // } else {
            //     //continue;
            // }


        #计算K值:这里比较复杂了，一个一个来吧....
            #首先看人数对k的影响
        $K = 0;
        $num_of_people = $row['num_of_p'];
        if($num_of_people < $xishu_rows['M0']) {
            $K = $xishu_rows['M0K'];
        }
        //else if($num_of_people >= $xishu_rows['M0K'] && $num_of_people <= $xishu_rows['NOP1']){
        else if($num_of_people >= $xishu_rows['M0'] && $num_of_people <= $xishu_rows['NOP1']) {//update by houbaron 20161108
            $K = $xishu_rows['M1K'];
        }
        else if($num_of_people > $xishu_rows['NOP1'] && $num_of_people <= $xishu_rows['L1']){
            $duo = floor(($num_of_people - $xishu_rows['NOP1'])/5);
            $K = $xishu_rows['M1K'] + $duo * $xishu_rows['L1K'];
        }
        else if($num_of_people > $xishu_rows['L1'] && $num_of_people <= $xishu_rows['L2']){
            $K = $xishu_rows['M1K'] + floor(($xishu_rows['L1'] - $xishu_rows['NOP1'])/5)*$xishu_rows['L1K'];
            $duo = floor(($num_of_people - $xishu_rows['L1'])/5);
            $K += $duo * $xishu_rows['L2K'];
        }
        else if($num_of_people > $xishu_rows['L2']){

            $K = $xishu_rows['M1K'] + floor(($xishu_rows['L1'] - $xishu_rows['NOP1'])/5)*$xishu_rows['L1K'];
            $K += floor(($xishu_rows['L2'] - $xishu_rows['L1'])/5) * $xishu_rows['L2K'];
                //$K = $xishu_rows['M1K'] + $duo * $xishu_rows['L2K'];

        }
        //$renshuxishu = sprintf("%.3f", $K);
        //$K = $renshuxishu;
        $renshuxishu = $K;

        # 难度系数 本科 都为 1
        $D = 1;
        # 专业课对D的影响
        //$zhuanyeke = $row['course_id'][4];
        //if($zhuanyeke == 'D' || $zhuanyeke == 'E' || $zhuanyeke == 'F') {
        //    $D += $xishu_rows['ZYK'];
        //    $zhuanyekexishu = $xishu_rows['ZYK'];
        //}else{
        //    $zhuanyekexishu = 0;
        //}

        //////////////////////////////////////////////////////////////////
        //                  2 0 1 5 新 版 教 学 大 纲                     //
        //                        Hou Baron                             //
        //                       2016-11-06                             //
        //////////////////////////////////////////////////////////////////
        //别忘了改 calc_lilun_handle.php
        $zhuanyekexishu = 0;
        if (strlen($row['course_id']) == 9) {

            $zhuanyeke = $row['course_id'][4];
            if($zhuanyeke == 'D' || $zhuanyeke == 'E' || $zhuanyeke == 'F') {
                //$D += $xishu_rows['ZYK'];20161202
                $zhuanyekexishu = $xishu_rows['ZYK'];
            } else {
                $zhuanyekexishu = 0;
            }

        } else if (strlen($row['course_id']) == 12) { //少用else 分支。多具体指明条件！
            /*
            $zhuanyeke = $row['course_id'][6];
            if ($zhuanyeke == 'H') { //专业核心课（专业平台课或者专业基础课） 或者 历史课
                $sql = "SELECT `flag` FROM `teshukecheng` WHERE `course_id`='".substr($row['course_id'], 0, 10)."';";
                $zypt_result = mysql_query($sql);
                if(!$zypt_result) {
                    die("SQL ERROR : ".mysql_error());
                }
                $zypt_row = mysql_fetch_row($zypt_result);
                if ($zypt_row && $zypt_row[0]['flag'] == 1) {
                    $zhuanyekexishu = 0.2;
                }
            } else {
                $zhuanyekexishu = 0;
            }
            */
            /////////////////////////////////////////////////
            // 2017-05-16 不再考虑专业核心课，只有专业选修课为专业课
            // houbaron
            //别忘了改 calc_lilun_handle.php
            if ($row['course_id'][6] == 'X') {
                $zhuanyekexishu = $xishu_rows['ZYK'];
            }
        }
        $D += $zhuanyekexishu;
        //////////////////////////////////////////////////////////////////////
        // 新版 end
        //////////////////////////////////////////////////////////////////////

        # 二表B，三表学生授课对K的影响
        $arr = explode(" ",$row['heban']);
        $intNumber = count($arr);
        $boolHunban = false;
        $sb = 0; $yb = 0;
        # 三表一表混班处理
        //echo "<br />";
        //echo "==".$row['heban']."#".$intNumberBanji."# ";
        //print_r($arr);
        //echo $intNumberBanji;
        for ($heban_row=0 ; $heban_row < $intNumberBanji; $heban_row++) {
            if(empty($arr[$heban_row])) continue;
            //echo $arr[$heban_row];
            //echo $row['heban'];
            if($issb[$arr[$heban_row]]) {
                $sb++;
            }else{
                $yb++;
            }
            if($sb&&$yb){
                $boolHunban = true;
                break;
            }
        }


    #我也不知道为什么这样是对的。。。
    if (preg_match("/\d\d-([a-zA-Z])\d班/", $row['course_alias'],$matchs) == 1) {
        $shanbiaoxishu = 0;
        //echo $row['course_alias'].$row['teacher_name']."</br>";
    } else {
        $shanbiaoxishu = 0;
        if($boolHunban) {
            //echo $row['heban'];
            $D += $xishu_rows['HHB'];
            $shanbiaoxishu += $xishu_rows['HHB'];
        }else if($sb > 0) {
            $D += $xishu_rows['SB'];
            $shanbiaoxishu += $xishu_rows['SB'];
        }
    }

        # K终于结束了
        # 授课质量为 0 只能手动修改了
        $K += 0;

        # 职称系数

        $Z = $zc_rows[$id2zc[$row['teacher_id']]];//从教师库获取的职称信息

        # 计划学时
        $H = $row['xueshi'];


        #教分公式

        $tiyu_name = array("体育（一）", "体育（二）","体育（三）","体育（四）", '体育-Ⅰ', '体育-Ⅱ', '体育-Ⅲ', '体育-Ⅳ');

        #如果是体育课
        //echo substr($row['course_name'],0,6);
        //exit();
        if(substr($row['course_name'],0,6)=="体育"){
            $renshuxishu = 0;
            $zhuanyekexishu = 0;
            $shanbiaoxishu = 0;
            if($row['num_of_p']==0) $S = 0;
            else $S = $H * $xishu_rows['TYK'] * $C * $Z;
        }else{
            $S = $H * $K * $C * $Z * $D;
        }

        # 完事，写到正式表里面去
        $sql = "UPDATE `lilun` SET
        `xueqi`=\"".$row['xueqi']."\",
        `course_id`=\"".$row['course_id']."\",
        `course_name`=\"".$row['course_name']."\",
        `course_index`=\"".$row['course_index']."\",
        `teacher_yuanxi`=\"".$row['teacher_yuanxi']."\",
        `teacher_id`=\"".$row['teacher_id']."\",
        `teacher_name`=\"".$row['teacher_name']."\",
        `teacher_zc`=\"".$id2zc[$row['teacher_id']]."\",
        `heban`=\"".$row['heban']."\",
        `xueshi`=\"".$row['xueshi']."\",
        `num_of_p`=\"".$row['num_of_p']."\",
        `xishu_people`=\"".$renshuxishu."\",
        `xishu_cfk`=\"".$C."\",
        `xishu_zyk`=\"".$zhuanyekexishu."\",
        `xishu_sb`=\"".$shanbiaoxishu."\",
        `xishu_zl`=\"0\",
        `xishu_nd`=\"".$D."\",
        `xishu_zc`=\"".$Z."\",
        `guocheng`=\"".$H."*". $K."*".$C."*".$Z."*".$D."\",
        `jiaofen`=\"".$S."\"
        WHERE `id`=\"".$row['id']."\"
        ;";

            //echo $sql."<br>";
        $result = mysql_query($sql);
        if(!$result) {
            die("sql error : ".mysql_error());
        }
    }
}else if($_POST['op']=="shijian"){

    if(!isset($_POST['addshijian'])) { //修改实践工作量
        $sql = "UPDATE `shijian` SET
        `shijian_type`=\"".$_POST['shijian_type']."\",
        `zhoushu`=\"".$_POST['zhoushu']."\",
        `num_of_p`=\"".$_POST['num_of_p']."\",
        `banji`=\"".$_POST['banji']."\",
        `didian`=\"".$_POST['didian']."\",
        `guocheng`=\"".$_POST['guocheng']."\",
        `jiaofen`=\"".$_POST['jiaofen']."\"
        WHERE `id`=\"".$_POST['id']."\";";
    } else { //添加新的实践工作量
        $sql = "INSERT INTO `shijian` (
            xueqi,
            teacher_id,
            teacher_name,
            teacher_zc,
            shijian_name,
            course_id,
            shijian_type,
            zhoushu,
            num_of_p,
            banji,
            didian,
            guocheng,
            jiaofen
            ) VALUES (
            ".$_POST['xueqi'].",
            \"".$_POST['teacher_id']."\",
            \"".$_POST['teacher_name']."\",
            \"".$_POST['teacher_zc']."\",
            \"".$_POST['shijian_name']."\",
            \"".$_POST['course_id']."\",
            \"".$_POST['shijian_type']."\",
            \"".$_POST['zhoushu']."\",
            \"".$_POST['num_of_p']."\",
            \"".$_POST['banji']."\",
            \"".$_POST['didian']."\",
            \"".$_POST['guocheng']."\",
            \"".$_POST['jiaofen']."\"
            )";
    }
    //echo $sql;

    $result = mysql_query($sql);
    if(!$result) {
        die("sql error : ".mysql_error());
    }
    //////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
    // 2017-05-16 start
    // 在 http://222.27.192.51/admin/modify_lilun_per.php?teacher_id=0302231&xueqi=20162 点击修改后，显示「提示: 此过程将不会重新计算，所以请将教分和计算过程一并更改.」
    // 但是下面又重算了，而且算的不对
    // 所以屏蔽以下算法
    /*
    # 获取就算人数系数需要的系数 K的递增值等信息
    $sql = "SELECT * FROM `jisuanxishu`;";
    $result = mysql_query($sql);
    if(!$result) {
        die("sql select error jisuanxishu" . mysql_error());
    }
    $xishu_rows = array();
    while($row = mysql_fetch_assoc($result)) {
        $xishu_rows[$row['name']] = $row['value'];
    }
    # 职称系数
    $sql = "SELECT * FROM `zcxishu`;";
    $result = mysql_query($sql);
    if(!$result) {
        die("sql select error :  ". mysql_error());
    }
    $zc_rows = array();
    while ($row = mysql_fetch_assoc($result)) {
        $zc_rows[$row['name']] = $row['xishu'];
    }

    $sql = "SELECT * FROM `teachers` WHERE `xueqi` = \"".$_POST['year']."\";";
    $result = mysql_query($sql);
    if(!$result) {
        die("sql error: ".mysql_error());
    }
    $teacher2xishu = array();
    while ($row = mysql_fetch_assoc($result)) {
        $teacher2xishu[$row['teacher_id']] = $row['zhicheng'];
    }


    # 重新计算该教师记录
    $sql = "SELECT * FROM `shijian` WHERE `teacher_id`=\"".$_POST['teacher_id']."\"
    AND `xueqi`=\"".$_POST['year']."\" ORDER BY `num_of_p` DESC;";
    $sj_result = mysql_query($sql);
    if(!$sj_result) {
        die("sql select error : " . mysql_error());
    }
    while ($row = mysql_fetch_assoc($sj_result)) {

        #该教师职称系数
        $zhichengxishu = $zc_rows[$teacher2xishu[$row['teacher_id']]];

        #课程设计
        if($row['shijian_type']=="课程设计") {
            if($row['num_of_p'] <= $xishu_rows['NOP1']) {
                $jiaofen = $xishu_rows['KCSJ'] * $row['zhoushu'] * $row['num_of_p'] * $zhichengxishu;
                $guocheng = $xishu_rows['KCSJ'] ."*". $row['zhoushu'] ."*". $row['num_of_p'] ."*". $zhichengxishu;
            }
            else if($row['num_of_p'] <= 2*$xishu_rows['NOP1']) {
                $jiaofen1 = ($xishu_rows['KCSJ']-0.1) * $row['zhoushu'] * ($row['num_of_p']-$xishu_rows['NOP1']) * $zhichengxishu;
                $jiaofen2 = $xishu_rows['KCSJ'] * $row['zhoushu'] * $xishu_rows['NOP1'] * $zhichengxishu;
                $guocheng = $xishu_rows['KCSJ'] ."*". $row['zhoushu'] ."*". $xishu_rows['NOP1'] ."*". $zhichengxishu;
                $guocheng .= "+(".$xishu_rows['KCSJ'] ."-0.1)*". $row['zhoushu'] ."*(". ($row['num_of_p']-$xishu_rows['NOP1']).")*". $zhichengxishu;
                $jiaofen = $jiaofen1 + $jiaofen2;
            }
            else{
                $jiaofen1 = $xishu_rows['KCSJ'] * $row['zhoushu'] * $xishu_rows['NOP1'] * $zhichengxishu;
                $jiaofen2 = ($xishu_rows['KCSJ']-0.1) * $row['zhoushu'] * $xishu_rows['NOP1'] * $zhichengxishu;
                $jiaofen3 = ($xishu_rows['KCSJ']-0.2) * $row['zhoushu'] * ($row['num_of_p']-2*$xishu_rows['NOP1']) * $zhichengxishu;
                $guocheng = $xishu_rows['KCSJ'] ."*". $row['zhoushu'] ."*". $xishu_rows['NOP1'] ."*". $zhichengxishu;
                $guocheng .= "+(".$xishu_rows['KCSJ'] ."-0.1)*". $row['zhoushu'] ."*". ($xishu_rows['NOP1']) ."*". $zhichengxishu;
                $guocheng .= "+(".$xishu_rows['KCSJ'] ."-0.2)*". $row['zhoushu'] ."*(". $row['num_of_p'] ."-".(2*$xishu_rows['NOP1']).")*". $zhichengxishu;
                $jiaofen = $jiaofen1 + $jiaofen2 + $jiaofen3;
            }
        }


        #生产实习
        if($row['shijian_type']=="生产实习") {
            if($row['didian']=="市外") {
                $jiaofen = $xishu_rows['SCSX'] * $row['zhoushu'] * $row['num_of_p'] * $zhichengxishu * $xishu_rows['SCSX2'];
                $guocheng = $xishu_rows['SCSX'] ."*". $row['zhoushu'] ."*". $row['num_of_p'] ."*". $zhichengxishu."*".$xishu_rows['SCSX2'];
            } else {
                $jiaofen = $xishu_rows['SCSX'] * $row['zhoushu'] * $row['num_of_p'] * $zhichengxishu ;
                $guocheng = $xishu_rows['SCSX'] ."*". $row['zhoushu'] ."*". $row['num_of_p'] ."*". $zhichengxishu;
            }
        }

        #毕业设计
        if($row['shijian_type']=="毕业设计") {
            if( $row['num_of_p'] <= 8 && $row['num_of_p'] >= 0) {
                $jiaofen = $xishu_rows['BYSJ'] * $row['zhoushu'] * $row['num_of_p'] * $zhichengxishu;
                $guocheng = $xishu_rows['BYSJ'] ."*". $row['zhoushu'] ."*". $row['num_of_p'] ."*". $zhichengxishu;
            }else{
                //超过8人
                $jiaofen = $xishu_rows['BYSJ'] * $row['zhoushu'] * 8 * $zhichengxishu;
                $jiaofen += ($xishu_rows['BYSJ'] - 0.15) * $row['zhoushu'] * ($row['num_of_p'] - 8) * $zhichengxishu;
                $guocheng = $xishu_rows['BYSJ'] ."*". $row['zhoushu'] ."*8*". $zhichengxishu;
                $guocheng .= "+(".$xishu_rows['BYSJ']."-0.15)*".$row['zhoushu']."*(".$row['num_of_p']."-8)*".$zhichengxishu;
                //echo $guocheng;
            }
        }

        #金工实习
        if($row['shijian_type']=="金工实习") {
            $jiaofen = $xishu_rows['JGSX'] * $row['banjishu'] * $zhichengxishu;
            $guocheng = $xishu_rows['JGSX'] ."*". $row['banjishu'] ."*". $zhichengxishu;
        }

        #综合性设计与训练
        if($row['shijian_type']=="综合性设计与训练") {
            $jiaofen = $xishu_rows['ZHXSJYXL'] * $row['zhoushu'] * $row['num_of_p'] * $zhichengxishu;
            $guocheng = $xishu_rows['ZHXSJYXL'] ."*". $row['zhoushu'] ."*". $row['num_of_p'] ."*". $zhichengxishu;
        }

        #分散性实习与实践
        if($row['shijian_type']=="分散性实习与实践") {
            $jiaofen = $xishu_rows['FSXSXYSJ'] * $row['zhoushu'] * $row['num_of_p'] * $zhichengxishu;
            $guocheng = $xishu_rows['FSXSXYSJ'] ."*". $row['zhoushu'] ."*". $row['num_of_p'] ."*". $zhichengxishu;
        }

        $sql = "UPDATE `shijian` SET
        `zhichengxishu`=\"".$zhichengxishu."\",
        `guocheng`=\"".$guocheng."\",
        `teacher_zc`=\"".$teacher2xishu[$row['teacher_id']]."\",
        `jiaofen`=\"".$jiaofen."\"
        WHERE `id`=\"".$row['id']."\";";
        $result = mysql_query($sql);
        if(!$result) {
            die("sql error : ".mysql_error());
        }
    }//end while

*/
} else {

}

?>
<?php
header("location: ./modify_lilun_per.php?teacher_id=".$_POST['teacher_id']."&xueqi=".$_POST['year']);
exit();
?>
