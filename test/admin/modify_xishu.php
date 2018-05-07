<?php
	
	include dirname(__FILE__) . './../config.php';
	include dirname(__FILE__) . './../functions.php';

	# 判断是否登录
	if(!isset($_SESSION['username'])) {
		header("location: ./error.php?txt="."请登录后再操作.");
		exit();
	}

	# 检验权限
	if(!$_SESSION['modify_jisuanxishu']) {
		header("location: ./error.php?txt="."您没有修改计算系数的权限.");
		exit();
	}

$sql = "select * from `jisuanxishu`;";
$result = mysql_query($sql);
$list  = mysql_num_rows($result);
?>

<?php

	include './header.php'
?>

        
    <h1 class="page-header">修改系数</h1>

	<!-- container -->
	<div class="span9">
		
			<table class="table">
				<thead>
					<tr>
						<th>
							编号
						</th>
						<th>
							系数
						</th>
						<th>
							系数说明
						</th>
						<th>
							状态
						</th>
						<th>
							操作
						</th>
					</tr>
				</thead>
				<tbody>
					<?php
					for($i = 0; $i < $list; $i++) {
						$row = mysql_fetch_assoc($result);
										//print_r($row);
						if($i%2==0) echo "<tr>";
						else echo "<tr class=\"success\">";
						echo "<form action=\"modify_xishu_handle.php\" method=\"post\">";
						echo "<td>";
						echo "#".($i+1);
						echo "</td>";
						echo "<td>";
						echo $row['name'];
						echo "</td>";
						echo "<td>";
						echo $row['disc'];
						echo "</td>";
						echo "<td>";
						echo "<input name=\"value\" style=\"width: auto;\" type=\"text\" class=\"form-control\" value=\"".$row['value']."\">";
						echo "</td>";
						echo "<td>";
						echo "<input type=\"hidden\" name=\"name\" value=\"".$row['name']."\">";
						echo "<button type=\"submit\" class=\"btn btn-success\">提交修改</button>";
						echo "</td></form>";	
						echo "</tr>";
					}
					?>
				</tbody>
			</table>

	
	</div>
	<!-- /container -->


<?php include('./footer.php'); ?>
  </body>
</html>
