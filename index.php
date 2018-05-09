<?php
include 'config.php';
if(isset($_SESSION['rank'])) {
    $rank  = $_SESSION['rank'];
}else{
    $rank = 0;
}
?>
<?php
    include './header.php';
?>

    <!-- Header -->
    <header id="head">
        <div class="container">
            <div class="row">
                <h1 class="lead">理论教学与实践教学工作量查询系统</h1>
                <p class="tagline">哈尔滨理工大学</p>
            </div>
        </div>
    </header>
    <!-- /Header -->

<?php include './footer.php'; ?>
<script>
alert("最新公告: 请勿使用IE10以下浏览器访问本站。如果您使用的是360浏览器，或搜狗浏览器，请打开极速模式。推荐使用chrome浏览器.");
</script>
</body>
</html>
