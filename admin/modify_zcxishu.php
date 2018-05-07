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


	$sql = "select * from `zcxishu`;";
	$result = mysql_query($sql);
	if(!$result) {
		die("mysql error: ". mysql_error());
	}
	$num = mysql_num_rows($result);
?>
<?php
	include './header.php'
?>

<h1 class="page-header">现有职称系数(<?php echo $num; ?> 条)</h1>
	<div class="span9">
		
			<table class="table">
				<thead>
					<tr>
						<th>
							职称
						</th>
						<th>
							系数
						</th>
						<th>
							操作
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
					while($row = mysql_fetch_assoc($result)) {
						echo "<tr>";
						echo "<form action=\"modify_zcxishu_handle.php\" method=\"post\">";
						//else echo "<tr class=\"success\">";
						echo "<td>";
						echo $row['name'];
						echo "</td>";
						echo "<td>";
						echo "<input name=\"xishu\" style=\"width: auto;\" type=\"text\" class=\"form-control\" value=\"".$row['xishu']."\">";
						echo "<input type=\"hidden\" name=\"id\" value=\"".$row['id']."\">";
						echo "<input type=\"hidden\" name=\"handle\" value=\"1\">";
						echo "</td>";
						echo "<td>";
						echo "<button type=\"submit\" class=\"btn btn-success\">修改</button>";
						echo "</td>";
						echo "</form>";
						echo "</tr>";
					}
					?>
				</tbody>
			</table>

	</div>
<h1 class="page-header">添加职称系数</h1>
	<div class="span9">
		<table class="table">
				<thead>
					<tr>
						<th>
							职称
						</th>
						<th>
							系数
						</th>
						<th>
							操作
						</th>
					</tr>
				</thead>
				<tbody>
					<tr>
					<form action="modify_zcxishu_handle.php" method="post">
						<td>
							<input name="name" style="width: auto;" type="text" class="form-control" value="">
						</td>
						<td>
							<input name="xishu" style="width: auto;" type="text" class="form-control" value="">
						</td>
						<td>
							<input type="hidden" name="handle" value="2">
							<button type="submit" class="btn btn-success">添加</button>
						</td>
					</form>
					</tr>
				</tbody>
			</table>
	</div>

<?php include('./footer.php'); ?>
  </body>
</html>
