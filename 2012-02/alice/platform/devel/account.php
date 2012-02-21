<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <title>童话迷城(Alice) - 测试帐号列表</title>
        <link type="text/css" rel="stylesheet" href="../../admin/css/global.css" />
        <script type="text/javascript" src="../../admin/js/jquery-1.5.min.js"></script>
        <script type="text/javascript" src="../../admin/js/global.js"></script>
        <script type="text/javascript">
            $(function(){
                $('td').each(function(){
                    $(this).mouseover(function(){
                        $(this).css({"background-color":"#f50","color":"#fff","cursor":"pointer"});
                    });
                    $(this).mouseout(function(){
                        $(this).css({"background-color":"#fff","color":"#000"});
                    });
                    $(this).click(function(){
                        $(this).css({"background-color":"#fff","color":"#000"});
                        var email = $(this).text();
                        var password = '123456';
                        location.href='login.php?do=login&email=' + encodeURIComponent(email) + '&password=' + encodeURIComponent(password);
                    });
                });
            });
        </script>
    </head>
    <body>
        <table class="gTable" width="100%" border="0" cellpadding="0" cellspacing="1" style="padding:3px;">
            <tr><th colspan="6" style="height:40px;line-height:40px;">测试帐号列表（<font color="red">请使用各自的员工号对应的帐号登录，以免帐号冲突！点击帐号即可自动登录游戏，或<a href="login.php">手动登录</a>。</font>）</th></tr>
            <?php
            $col = 6; // 每行个数
            $html = '';
            for ($i = 0; $i < 25; ++$i) {
                $html .= '<tr>';
                for ($j = 1; $j <= $col; ++$j) {
                    $html .= '<td>' . sprintf('test%03d@fanhougame.com', $i * $col + $j) . '</td>';
                }
                $html .= '</tr>';
            }
            echo $html;
            ?>
        </table>
        <p align="center">Copyright &copy; 2011 FANHOUGAME.COM All Rights Reserved</p>
    </body>
</html>