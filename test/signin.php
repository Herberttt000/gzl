<?php
include 'config.php';
if(isset($_SESSION['rank'])) {
	$rank  = $_SESSION['rank'];
}else{
	$rank = 0;
}


?>
<?php include 'header.php'; ?>
	<!-- container -->
	<div class="container">

		<ol class="breadcrumb">
			<li><a href="index.html">首页</a></li>
			<li class="active">用户验证</li>
		</ol>

		<div class="row">
			
			<!-- Article main content -->
			<article class="col-xs-12 maincontent">
				<header class="page-header">
					<h1 class="page-title">登录</h1>
				</header>
				
				<div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
					<div class="panel panel-default">
						<div class="panel-body">
							<h3 class="thin text-center">验证你的账号</h3>
							<hr>
							
							<form action="./login_handle.php" method="post">
								<div class="top-margin">
									<label>用户名 <span class="text-danger">*</span></label>
									<input type="text" name="username" class="form-control">
								</div>
								<div class="top-margin">
									<label>密码 <span class="text-danger">*</span></label>
									<input type="password" name="password" class="form-control">
								</div>

								<hr>

								<div class="row">
									<div class="col-lg-8">
										<b><a href="">Forgot password?</a></b>
									</div>
									<div class="col-lg-4 text-right">
										<button class="btn btn btn-success" type="submit">Sign in</button>
									</div>
								</div>
							</form>
						</div>
					</div>

				</div>
				
			</article>
			<!-- /Article -->

		</div>
	</div>	<!-- /container -->
	

	<?php include './footer.php'; ?>

</body>
</html>