<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="icon" href="./../favicon.ico">

    <title>HRBUST</title>
    <!-- Bootstrap core CSS -->
    <link href="./../assets/css/bootstrap.min.css" rel="stylesheet">

    <!-- Custom styles for this template -->
    <link href="dashboard.css" rel="stylesheet">
<link href="./../assets/skins/square/green.css" rel="stylesheet">

    <link href="./../assets/css/bootstrap-switch.css" rel="stylesheet">
    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
    <script src="./../assets/js/ie-emulation-modes-warning.js"></script>
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
      <![endif]-->



  </head>

  <body>

    <nav class="navbar navbar-inverse navbar-fixed-top" role="navigation">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">工作量计算系统</a>
            </div>
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav navbar-left">
                    <li><a href="#" onclick="switchmenu()">菜单</a></li>
                </ul>
                <ul class="nav navbar-nav navbar-right">
                    <li><a href="./index.php">首页</a></li>
                    <?php
                    if(isset($_SESSION['rank'])) {
                    ?>
                    <li><a href="./../loginout.php">退出</a></li>
                    <?php
                    }else{
                        ?>
                        <li><a href="./../signin.php">登录</a></li>
                        <?php
                    }
                    ?>
                </ul>
            </div>
        </div>
    </nav>

<div class="container-fluid">
    <div class="row">
    <div id="left_menu" class="col-sm-3 col-md-2 sidebar">
      <?php show_left_nav(); ?>
    </div>
    <div id="right_menu" class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
        <script type="text/javascript">
    var status = 1;

    function switchmenu() {
        if (status == 1) {
            status = 0;
            var left_menu = document.getElementById('left_menu');
            var right_menu = document.getElementById('right_menu');
            left_menu.setAttribute("class", "col-sm-0 col-md-0 sidebar hidden");
            right_menu.setAttribute("class", "col-sm-12 col-md-12 main")
        }else {
            status = 1;
            var left_menu = document.getElementById('left_menu');
            var right_menu = document.getElementById('right_menu');
            left_menu.setAttribute("class", "col-sm-3 col-md-2 sidebar show");
            right_menu.setAttribute("class", "col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main")
        }
    }
</script>