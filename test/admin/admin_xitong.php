<?php
	
	include dirname(__FILE__) . './../config.php';
	include dirname(__FILE__) . './../functions.php';

	# 判断是否登录
	if(!isset($_SESSION['rank'])) {
		header("location: ./error.php?txt="."请登录后再操作.");
		exit();
	}
	# 检验权限
	if(!$_SESSION['modify_xitong']) {
		header("location: ./error.php?txt="."您没有修改系统属性的权限.");
		exit();
	}


?>
<?php
	include './header.php'
?>
<?php
	$chaxun;
	if(isset($_POST['p'])){
		if(isset($_POST['chaxun'])) {
			$chaxun = 1;
		}else{
			$chaxun = 0;
		}

		$sql = "UPDATE `xitong` SET value = '".$chaxun."' WHERE name = 'chaxun';";
		$result = mysql_query($sql);
		if(!$result){
			die("SQL ERROR : ". mysql_error());
		}

		#显示修改成功
		?>
		<div id="show" class="alert alert-success alert-dismissible" role="alert">
			<button type="button" class="close" data-dismiss="alert"><span aria-hidden="true">&times;</span><span class="sr-only">Close</span></button>
			<strong>修改成功!</strong>
		</div>
		<?php
	}
	#查询当前状态
	$sql = "SELECT * FROM `xitong`";
	$result = mysql_query($sql);
	if(!$result){
		die("SQL ERROR : " . mysql_error());
	}
	$status = array();
	while($row = mysql_fetch_assoc($result)){
		$status[$row['name']] = $row['value'];
	}
	//print_r($status);
?>

<h2>系统设置</h2>
<br />
<form method="post">
查询开关:
<?php 
if($status['chaxun'])
	echo '<input type="checkbox" name="chaxun" id="moonsn" checked>';
else
	echo '<input type="checkbox" name="chaxun" id="moonsn">';
?>
<input type=hidden name="p" value="1">
<button type="submit" class="btn btn-success">更新状态</button>
</form>

<?php include('./footer.php'); ?>
<script type="text/javascript">
	//$("[name='chaxun']").bootstrapSwitch();
</script>
  </body>
</html>
