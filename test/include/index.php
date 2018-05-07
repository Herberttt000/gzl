<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>用户登录</title>
    <link href="http://202.118.201.228/academic/client/default/info.css" rel="stylesheet" type="text/css">
    <script type="http://202.118.201.228/text/javascript" src="http://202.118.201.228/academic/styles/js/md5.js"></script>
    <script type="text/javascript">
        function check() {
            if (document.form1['j_username'].value == "")
            {
                document.form1['j_username'].focus();
                alert("用户名不能为空！");
                return false;

            } else if (document.form1['j_password'].value == "")
            {
                document.form1['j_password'].focus();
                alert("密码不能为空！");
                return false;
            }
            else {
                trans();
                return true;
            }
        }

        function trans() {
            var passvalue = document.form1.j_password.value;
            document.form1.j_password.value = hex_md5(passvalue);
        }

    </script>
</head>
<body class="login">
<div class="login">
    <div class="top">&nbsp;</div>
    <div class="middle">
        <div class="error">
            
            <div id="error">错误提示：
            </div>
            
        </div>

        <div id="login">
            <form name="form1" method="post" action="./log_to_jiaowu.php"
                   onsubmit="trans()">
                

                <input type="hidden" name="groupId" value=""/>
                <table cellspacing="0" cellpadding="0" class="login">
                    <tr>
                        <th class="title" colspan="2"><span>用户登录</span></th>
                    </tr>
                    <tr>
                        <th class="uname">用户名</th>
                        <td>&nbsp;<input name="j_username"
                                         type="text" class="input"></td>
                    </tr>
                    <tr>
                        <th class="password">密码</th>
                        <td>&nbsp;<input name="j_password"
                                         type="password" class="input">
                        </td>
                    </tr>
                    <tr>
                        <th class="uname">
                            验证码
                        </th>
                        <td>&nbsp;<input type="text" name="j_captcha" class="input">
                        </td>
                    </tr>
                    <tr>
                        <th class="uname">
                            &nbsp;
                        </th>
                        <td>
                            <img name="jcaptcha" id="jcaptcha" onclick="refresh_jcaptcha(this)"
                                 src="./get.php"
                                 alt="点击刷新验证码"
                                 title="点击刷新验证码"
                                 style="cursor:pointer;"/>
                            <script language="Javascript">
                                function refresh_jcaptcha(obj) {
                                    obj.src = "./get.php?" + Math.random();
                                }
                            </script>
                        </td>
                    </tr>
                    


                    <tr>
                        <th class="button" colspan="2" align="center">
                            
                            
                            
                            <input name="button1" type="submit"
                                   class="button"
                                   value="登录">
                            

                        </th>
                    </tr>
                </table>
            </form>
        </div>
        <div style="clear:both"></div>
    </div>

    <div class="bottom">
        版权所有&nbsp;&nbsp;2014&nbsp;&nbsp;<a href="http://www.theti.org/" target="_blank" >清华大学教育技术研究所</a>&nbsp;&nbsp;（请使用1024x768分辨率，IE6.0或更高版本浏览器访问本系统）</div>
</div>
</body>
</html>
