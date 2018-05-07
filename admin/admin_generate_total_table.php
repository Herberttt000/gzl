<?php
      # 生成个人工作量汇总模块

      # 导入配置
include dirname(__FILE__) . './../config.php';
include dirname(__FILE__) . './../functions.php';

    # 判断是否登录
if(!isset($_SESSION['username'])) {
    header("location: ./error.php?txt="."请登录后再操作.");
    exit();
}

    # 检验权限
if(!$_SESSION['calc_lilun']) {
    header("location: ./error.php?txt="."您没有修改用户的权限.");
    exit();
}

# 筛选学期  
$sql = "SELECT DISTINCT `xueqi` FROM `lilun`";
$result_xq = mysql_query($sql);
if(!$result_xq){
    die("SQL ERRROR : ".mysql_error());
}

# 得到统计数据，可视化
$visual_xq = $result_xq;
$visual_data = array();
while ($i = mysql_fetch_assoc($visual_xq)) {
    $sql = "SELECT COUNT(xueqi) AS NumberOfXueqi FROM `huizong` WHERE `xueqi` = " .$i['xueqi']. ";";
    $result = mysql_query($sql);
    if (!$result) {
        die("SQL ERROR: ". mysql_error());
    }

    $num = mysql_fetch_assoc($result);
    $visual_data[$i['xueqi']] = $num['NumberOfXueqi'];
}

$sql = "SELECT DISTINCT `xueqi` FROM `lilun`";
$result_xq = mysql_query($sql);
if(!$result_xq){
    die("SQL ERRROR : ".mysql_error());
}

include './header.php';
?>
<script>

function showHint()
{

	var myButton = document.getElementById("myButton");
	myButton.innerHTML="请稍后...";

	var years = document.getElementById("select_xueqi").value;

	alert(years);
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
  			document.getElementById("hits").innerHTML=xmlhttp.responseText;
  			//alert(xmlhttp.responseText);
  			myButton.innerHTML="查询";
  		}

  	}
  	xmlhttp.open("POST", "./admin_generate_total_table_handle.php?t="+Math.random(), true);
  	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.send("xueqi="+years);
}
</script>
<div class="container-fluid">
    <ol class="breadcrumb">
      <li><a href="./index.php">首页</a></li>
      <li class="active">生成个人工作量汇总表</li>
    </ol>
    <div class="row">
    	<div id="hits" class="alert alert-warning alert-dismissible" role="alert">
        请生成记录。
</div>
    </div>
    <div class="row">

		<form class="form-inline">
		       	<select id="select_xueqi" class="form-control">
<?php

	while ($list_xueqi = mysql_fetch_assoc($result_xq)) {
		echo "<option value=\"".$list_xueqi['xueqi']."\">".get_xq((string)$list_xueqi['xueqi'])."</option>";
	}

?>
		    	</select>
<button id="myButton" type="button" class="btn btn-success" onclick="showHint()">生成</button>
		</form>
    </div>

    <div class="row"> 
        <h3>已存在记录:</h3>
    </div>
    <div class="row"> 
        <table class="table"> 
            <thead>
                <tr>
                    <td>#</td>
                    <td>学期</td>
                    <td>记录数</td>
                </tr>
            </thead>
            <tbody>
            <?php
                $i = 1;
                foreach ($visual_data as $key => $value) {
                    echo "<tr>";
                    echo "<td>".$i."</td>";
                    echo "<td>".get_xq((string)$key)."</td>";
                    echo "<td>".$value."</td>";
                    echo "</tr>";
                    $i++;
                }
            ?>
            </tbody>
        </table>
    </div>
<div><!-- end container-fluid -->

<?php
include './footer.php';
?>


</body>
</html>