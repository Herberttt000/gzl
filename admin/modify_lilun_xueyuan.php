<?php
include "./../config.php";
include "./../functions.php";

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
	$sql = "SELECT DISTINCT `xueyuan` FROM `teachers`";
	$result_xy = mysql_query($sql);
	if(!$result_xy){
		die("SQL ERRROR : ".mysql_error());
	}
	//$result_xy = mysql_fetch_row($result);
	//print_r($result_xy);
	$sql = "SELECT DISTINCT `xi` FROM `teachers`";
	$result_xi = mysql_query($sql);
	if(!$result_xi){
		die("SQL ERRROR : ".mysql_error());
	}
	//$result_xi = mysql_fetch_row($result);
	//print_r($result_xi);


?>
<?php
include "./header.php";
?>
<script>

function showHint()
{



	var xy = document.getElementById("xy").value;
	var xi = document.getElementById("xi").value;
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
  			document.getElementById("txtHint").innerHTML=xmlhttp.responseText;
  		}
  	}
  	xmlhttp.open("POST","./get_teachers.php?t="+Math.random(),true);
  	xmlhttp.setRequestHeader("Content-type","application/x-www-form-urlencoded");
	xmlhttp.send("xy="+xy+"&xi="+xi);
}
</script>
<div class="span9">
	<div class="row">
		
	</div>
	<div class="row">
		<h3 class="page-header">查询学院教师</h3>
		<!-- Select Basic -->
		
		<div class="controls">
			<label class="control-label">学院：</label>
			<select class="input-xlarge" id="xy">
				<?php
					while($xy_row = mysql_fetch_row($result_xy)){
						echo "<option>".$xy_row[0]."</option>";
					}
				?>
			</select>
	
			<label class="control-label">系所：</label>
		
			<select class="input-xlarge" id="xi">
				<?php
					while($xi_row = mysql_fetch_row($result_xi)){
						echo "<option>".$xi_row[0]."</option>";
					}
				?>
			</select>
		</div>
		<button type="submit" class="btn btn-danger" onclick="showHint()">find</button>

	</div>
	<div id="txtHint">

	</div>
</div>

<?php
include "./footer.php";
?>
	</body>
</html>