<?php
	
	include dirname(__FILE__) . './../config.php';
	include dirname(__FILE__) . './../functions.php';

	# 判断是否登录
	if(!isset($_SESSION['rank'])) {
		header("location: ./error.php?txt="."请登录后再操作.");
		exit();
	}

	if(!$_SESSION['modify_users']) {
		header("location: ./error.php?txt="."您没有修改用户的权限.");
		exit();
	}

	$sql = "SELECT * FROM `users`;";
	$result = mysql_query($sql);
	if(!$result) {
		die("SQL ERROR : ".mysql_error());
	}
	$num_of_users = mysql_num_rows($result);

	function get_action($username){
		echo "<td>";
		echo "<a href=\"./admin_users_modify.php?username=".$username."&action=modify\">";
		echo "<button type=\"button\" class=\"btn btn-success btn-xs\">修改</button></a>";
		// echo "<a href=\"./admin_users_modify.php?username=".$username."&action=delete\">";
		// echo "<button type=\"button\" class=\"btn btn-danger btn-xs\">删除</button></a></td>";
		echo "<!-- Single button -->
<div class=\"btn-group\">
  <button type=\"button\" class=\"btn btn-danger btn-xs dropdown-toggle\" data-toggle=\"dropdown\">
    删除<span class=\"caret\"></span>
  </button>
  <ul class=\"dropdown-menu\" role=\"menu\">
    <li><a href=\"./admin_users_modify.php?username=".$username."&action=delete\">确认删除</a></li>
  </ul>
</div>";
	}
?>
<?php
	include './header.php'
?>
<span class="pull-right">
	<a href="./admin_users_add.php"><button type="button" class="btn btn-success">添加用户</button></a>
	
</span>
<h1 class="page-header">账号管理<span class="badge">用户数量:<?php echo $num_of_users; ?></span></h1>

<!-- container -->
<div class="span9">
	<table class="table table-condensed">
		<tr><th>用户名</th><th>姓名</th><th>操作</th>
		</tr>
<?php
		while($row = mysql_fetch_assoc($result)) {
			echo "<tr>";
			echo "<td>".$row['username']."</td>";
			echo "<td>".$row['name']."</td>";
			echo "<td>".get_action($row['username'])."</td>";
			echo "</tr>";
		}
?>
	</table>

</div>

<?php include('./footer.php'); ?>
  </body>
</html>
