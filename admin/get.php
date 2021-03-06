<?php
//header("Content-type: text/html; charset=utf-8");
    // ini_set('display_errors',1);            //错误信息 
    // ini_set('display_startup_errors',1);    //php启动错误信息 
    // error_reporting(-1);                    //打印出所有的 错误信息 
define ( 'IS_PROXY', FALSE ); //是否启用代理
/* cookie文件 */
$cookie_file = dirname ( __FILE__ ) . "/cookie.txt"; // 设置Cookie文件保存路径及文件名
/*模拟浏览器*/
$user_agent = "Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.2; .NET CLR 1.1.4322)";

function vlogin($url, $data) { // 模拟登录获取Cookie函数
    $curl = curl_init (); // 启动一个CURL会话
    if (IS_PROXY) {
        //以下代码设置代理服务器
        //代理服务器地址
        curl_setopt ( $curl, CURLOPT_PROXY, $GLOBALS ['proxy'] );
    }
    curl_setopt ( $curl, CURLOPT_URL, $url ); // 要访问的地址
    curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
    //curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 2 ); // 从证书中检查SSL加密算法是否存在
    curl_setopt ( $curl, CURLOPT_USERAGENT, $GLOBALS ['user_agent'] ); // 模拟用户使用的浏览器
    @curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转
    curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
    curl_setopt ( $curl, CURLOPT_POST, 1 ); // 发送一个常规的Post请求
    curl_setopt ( $curl, CURLOPT_POSTFIELDS, $data ); // Post提交的数据包
    curl_setopt ( $curl, CURLOPT_COOKIEJAR, $GLOBALS ['cookie_file'] ); // 存放Cookie信息的文件名称
    curl_setopt ( $curl, CURLOPT_COOKIEFILE, $GLOBALS ['cookie_file'] ); // 读取上面所储存的Cookie信息
    curl_setopt ( $curl, CURLOPT_TIMEOUT, 30 ); // 设置超时限制防止死循环
    curl_setopt ( $curl, CURLOPT_HEADER, 0 ); // 显示返回的Header区域内容
    curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec ( $curl ); // 执行操作
    if (curl_errno ( $curl )) {
        echo 'Errno' . curl_error ( $curl );
    }
    curl_close ( $curl ); // 关闭CURL会话
    return $tmpInfo; // 返回数据
}

function vget($url) { // 模拟获取内容函数
    $curl = curl_init (); // 启动一个CURL会话
    if (IS_PROXY) {
        //以下代码设置代理服务器
        //代理服务器地址
        curl_setopt ( $curl, CURLOPT_PROXY, $GLOBALS ['proxy'] );
    }
    curl_setopt ( $curl, CURLOPT_URL, $url ); // 要访问的地址
    curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
    //curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 2 ); // 从证书中检查SSL加密算法是否存在
    curl_setopt ( $curl, CURLOPT_USERAGENT, $GLOBALS ['user_agent'] ); // 模拟用户使用的浏览器
    @curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转
    curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
    curl_setopt ( $curl, CURLOPT_HTTPGET, 1 ); // 发送一个常规的Post请求
    curl_setopt ( $curl, CURLOPT_COOKIEFILE, $GLOBALS ['cookie_file'] ); // 读取上面所储存的Cookie信息
    curl_setopt ( $curl, CURLOPT_TIMEOUT, 120 ); // 设置超时限制防止死循环
    curl_setopt ( $curl, CURLOPT_HEADER, 0 ); // 显示返回的Header区域内容
    curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec ( $curl ); // 执行操作
    if (curl_errno ( $curl )) {
        echo 'Errno' . curl_error ( $curl );
    }
    curl_close ( $curl ); // 关闭CURL会话
    return $tmpInfo; // 返回数据
}

function vpost($url, $data) { // 模拟提交数据函数
    $curl = curl_init (); // 启动一个CURL会话
    if (IS_PROXY) {
        //以下代码设置代理服务器
        //代理服务器地址
        curl_setopt ( $curl, CURLOPT_PROXY, $GLOBALS ['proxy'] );
    }
    curl_setopt ( $curl, CURLOPT_URL, $url ); // 要访问的地址
    curl_setopt ( $curl, CURLOPT_SSL_VERIFYPEER, 0 ); // 对认证证书来源的检查
    curl_setopt ( $curl, CURLOPT_SSL_VERIFYHOST, 1 ); // 从证书中检查SSL加密算法是否存在
    curl_setopt ( $curl, CURLOPT_USERAGENT, $GLOBALS ['user_agent'] ); // 模拟用户使用的浏览器
    @curl_setopt ( $curl, CURLOPT_FOLLOWLOCATION, 1 ); // 使用自动跳转
    curl_setopt ( $curl, CURLOPT_AUTOREFERER, 1 ); // 自动设置Referer
    curl_setopt ( $curl, CURLOPT_POST, 1 ); // 发送一个常规的Post请求
    curl_setopt ( $curl, CURLOPT_POSTFIELDS, $data ); // Post提交的数据包
    curl_setopt ( $curl, CURLOPT_COOKIEFILE, $GLOBALS ['cookie_file'] ); // 读取上面所储存的Cookie信息
    curl_setopt ( $curl, CURLOPT_TIMEOUT, 120 ); // 设置超时限制防止死循环
    curl_setopt ( $curl, CURLOPT_HEADER, 0 ); // 显示返回的Header区域内容
    curl_setopt ( $curl, CURLOPT_RETURNTRANSFER, 1 ); // 获取的信息以文件流的形式返回
    $tmpInfo = curl_exec ( $curl ); // 执行操作
    if (curl_errno ( $curl )) {
        echo 'Errno' . curl_error ( $curl );
    }
    curl_close ( $curl ); // 关键CURL会话
    return $tmpInfo; // 返回数据
}

function delcookie($cookie_file) { // 删除Cookie函数
    unlink ( $cookie_file ); // 执行删除
}


$xxx = vlogin("http://202.118.201.228/academic/common/security/login.jsp","");
$x = vlogin("http://202.118.201.228/academic/getCaptcha.do","");
header('Content-type: image/jpg');
echo $x;
//print_r(vget("http://202.118.201.228/academic/calendarinfo/viewCalendarInfo.do"));
?>