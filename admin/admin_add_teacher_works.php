<!DOCTYPE html>
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

	if(!empty($_POST)){
		//print_r($_POST);
		//exit();
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
            \"".$_POST['xueqi']."\",
            \"".$_POST['teacher_id']."\",
            \"".$_POST['teacher_name']."\",
            \"".$_POST['shijian_name']."\",
            \"".$_POST['course_id']."\",
            NULL,
            \"".$_POST['shijian_type']."\",
            \"".$_POST['zhoushu']."\",
            \"".$_POST['num_of_p']."\",
            \"".$_POST['banji']."\",
            \"".$_POST['banjishu']."\",
            \"".$_POST['didian']."\",
            \"".$_POST['teacher_xueyuan']."\",
            \"".$_POST['teacher_xi']."\",
            \"".$_SESSION['username']."\"
            );";

        $result = mysql_query($sql);
        if(!$result) {
            die("insert error:" . mysql_error());
        }else{
        	?>
        <div class="alert alert-info alert-dismissible" role="alert">
    <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
    <strong>提交成功!</strong>
</div>
<?php
}
}

include "./header.php";
?>

<script>
    function from_teacher_id(){
        var teacher_id=document.getElementById("teacher_id").value
        // alert(teacher_id)
        var xmlhttp;
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var info=eval('('+this.responseText+')');

                document.getElementById("teacher_name").value=info.teacher_name;
                document.getElementById("teacher_xueyuan").value=info.teacher_xueyuan;
                document.getElementById("teacher_xi").value=info.teacher_yuanxi;
            }

        }
        xmlhttp.open("POST", "./get_teacher_info_by_id.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("teacher_id="+teacher_id);

    }

    function from_course_id(){
        var course_id=document.getElementById("course_id").value
        var xmlhttp;
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                var info=eval('('+this.responseText+')');

                document.getElementById("shijian_name").value=info.shijian_name;
                document.getElementById("shijian_type").value=info.shijian_type;
            }

        }
        xmlhttp.open("POST", "./get_course_info_by_id.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("course_id="+course_id);

    }

    function append_data(){
        var temp_xueqi=document.getElementById("xueqi").value
        var temp_teacher_id=document.getElementById("teacher_id").value
        var temp_teacher_name=document.getElementById("teacher_name").value
        var temp_shijian_name=document.getElementById("shijian_name").value
        var temp_course_id=document.getElementById("course_id").value
        var temp_shijian_type=document.getElementById("shijian_type").value
        var temp_zhoushu=document.getElementById("zhoushu").value
        var temp_num_of_p=document.getElementById("num_of_p").value
        var temp_banji=document.getElementById("banji").value
        var temp_banjishu=document.getElementById("banjishu").value
        var temp_didian=document.getElementById("didian").value
        var temp_teacher_xueyuan=document.getElementById("teacher_xueyuan").value
        var temp_teacher_xi=document.getElementById("teacher_xi").value

        var str=""
        str+="<tr>"
        str+="<td>"+temp_xueqi+"</td>"
        str+="<td>"+temp_teacher_id+"</td>"
        str+="<td>"+temp_teacher_name+"</td>"
        str+="<td>"+temp_shijian_name+"</td>"
        str+="<td>"+temp_course_id+"</td>"
        str+="<td>"+temp_shijian_type+"</td>"
        str+="<td>"+temp_zhoushu+"</td>"
        str+="<td>"+temp_num_of_p+"</td>"
        str+="<td>"+temp_banji+"</td>"
        str+="<td>"+temp_banjishu+"</td>"
        str+="<td>"+temp_didian+"</td>"
        str+="<td>"+temp_teacher_xueyuan+"</td>"
        str+="<td>"+temp_teacher_xi+"</td>"
        str+="<td><button onclick='delete_row(this)'>删除</button></td>"
        str+="</tr>"

        $("#show tbody").append(str)
    }

    function delete_row(id){
        // alert("123")
        $(id).parent().parent().remove()
    }

    function upload_post(json){
        var xmlhttp;
        if (window.XMLHttpRequest)
        {// code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp=new XMLHttpRequest();
        }
        else
        {// code for IE6, IE5
            xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange=function()
        {
            if (xmlhttp.readyState==4 && xmlhttp.status==200)
            {
                // var info=eval('('+this.responseText+')');
                //
                // document.getElementById("teacher_name").value=info.teacher_name;
                // document.getElementById("teacher_xueyuan").value=info.teacher_xueyuan;
                // document.getElementById("teacher_xi").value=info.teacher_yuanxi;
                alert("添加成功")
                $("#show tbody").html("")
            }

        }
        xmlhttp.open("POST", "./admin_add_teacher_works_handle.php", true);
        xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xmlhttp.send("json="+json);
    }

    function upload(){
        var table=document.getElementById("show")
        var json=""
        for(var i=1;i<table.rows.length;i++){
            json+="{"
            for(var j=0;j<table.rows[i].cells.length-1;j++){
                json+="\""+table.rows[0].cells[j].innerHTML+"\":\""+table.rows[i].cells[j].innerHTML+"\","
            }
            json=json.substring(0,json.length-1,)+"},"
        }
        json="["+json.substring(0,json.length-1)+"]"

        // alert("111")
        upload_post(json)
        // alert("222")
    }
</script>

<div class="container-fluid">
    <div class="row">
        <h3>添加工作量</h3>
        <form action="admin_add_teacher_works.php" class="form-horizontal" method="post">
            <div class="alert alert-info alert-dismissible" role="alert">
                <button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
                <strong>必读提示!</strong>请确认信息无误后添加！
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label" for="input01">学期：</label>
                <div class="col-sm-2">
                    <input type="text" id="xueqi" name="xueqi" placeholder="请输入学期" class="form-control" value="<?php if(!empty($_POST)){echo $_POST['xueqi'];} ?>">
                </div>

                <label class="col-sm-1 control-label" for="input01">教师id：</label>
                <div class="col-sm-2">
                        <input type="text" id="teacher_id" name="teacher_id" placeholder="请输入教师id" class="form-control" value="<?php if(!empty($_POST)){echo $_POST['teacher_id'];} ?>" onchange="from_teacher_id()">
                </div>

                <label class="col-sm-1 control-label" for="input01">教师名：</label>
                <div class="col-sm-2">
                        <input type="text" id="teacher_name" name="teacher_name" placeholder="请输入教师名" class="form-control" value="<?php if(!empty($_POST)){echo $_POST['teacher_name'];} ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label" for="input01">实践名：</label>
                <div class="col-sm-2">
                        <input type="text" id="shijian_name" name="shijian_name" placeholder="请输入实践名" class="form-control" value="<?php if(!empty($_POST)){echo $_POST['shijian_name'];} ?>">
                </div>

                <label class="col-sm-1 control-label" for="input01">课程号：</label>
                <div class="col-sm-2">
                        <input type="text" id="course_id" name="course_id" placeholder="请输入课程号" class="form-control" value="<?php if(!empty($_POST)){echo $_POST['course_id'];} ?>" onchange="from_course_id()">
                </div>

                <label class="col-sm-1 control-label" for="input01">实践类型：</label>
                <div class="col-sm-2">
                        <input type="text" id="shijian_type" name="shijian_type" placeholder="请输入实践类型" class="form-control" value="<?php if(!empty($_POST)){echo $_POST['shijian_type'];} ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label" for="input01">周数：</label>
                <div class="col-sm-2">
                    <input type="text" id="zhoushu" name="zhoushu" placeholder="请输入周数" class="form-control" value="<?php if(!empty($_POST)){echo $_POST['zhoushu'];} ?>">
                </div>

                <label class="col-sm-1 control-label" for="input01">人数：</label>
                <div class="col-sm-2">
                    <input type="text" id="num_of_p" name="num_of_p" placeholder="请输入人数" class="form-control" value="<?php if(!empty($_POST)){echo $_POST['num_of_p'];} ?>">
                </div>

                <label class="col-sm-1 control-label" for="input01">班级：</label>
                <div class="col-sm-2">
                    <input type="text" id="banji" name="banji" placeholder="请输入班级" class="form-control" value="<?php if(!empty($_POST)){echo $_POST['banji'];} ?>">
                </div>

                <label class="col-sm-1 control-label" for="input01">班级数：</label>
                <div class="col-sm-2">
                    <input type="text" id="banjishu" name="banjishu" placeholder="请输入班级数" class="form-control" value="<?php if(!empty($_POST)){echo $_POST['banjishu'];} ?>">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-1 control-label" for="input01">地点：</label>
                <div class="col-sm-2">
                    <input type="text" id="didian" name="didian" placeholder="请输入地点" class="form-control" value="<?php if(!empty($_POST)){echo $_POST['didian'];} ?>">
                </div>

                <label class="col-sm-1 control-label" for="input01">学院：</label>
                <div class="col-sm-2">
                        <input type="text" id="teacher_xueyuan" name="teacher_xueyuan" placeholder="请输入学院" class="form-control" value="<?php if(!empty($_POST)){echo $_POST['teacher_xueyuan'];} ?>">
                </div>

                <label class="col-sm-1 control-label" for="input01">系：</label>
                <div class="col-sm-2">
                        <input type="text" id="teacher_xi" name="teacher_xi" placeholder="请输入系" class="form-control" value="<?php if(!empty($_POST)){echo $_POST['teacher_xi'];} ?>">
                </div>
            </div>
            <div class="form-group">
                <div class="col-sm-offset-0 col-sm-10">
                    <button type="button" id="show_btn" class="btn btn-success" onclick="append_data()">添加</button>
<!---->
<!--                    <button type="submit" class="btn btn-success">提交</button>-->
                </div>
            </div>
        </form>
    </div>
</div>

<hr style=" height:1px;border:none;border-top:1px dotted #185598;" />
<hr style=" height:1px;border:none;border-top:1px dotted #185598;" />
<div class="form-group">
    <div class="col-sm-offset-0 col-sm-10">
        <button type="button" id="submit_btn" class="btn btn-success" onclick="upload()">添加</button>
    </div>
</div>

<div class="form-group">
    <table class="table table-hover" id="show">
        <thead>
            <tr>
                <th>学期</th>
                <th>教师id</th>
                <th>教师名</th>
                <th>实践名</th>
                <th>课程号</th>
                <th>实践类型</th>
                <th>周数</th>
                <th>人数</th>
                <th>班级</th>
                <th>班级数</th>
                <th>地点</th>
                <th>学院</th>
                <th>系</th>
                <th>操作</th>
            </tr>
        </thead>
        <tbody>

        </tbody>
    </table>
</div>


<?php
include './footer.php'

?>
