<?php
        require('../wp-load.php');
        /*
        $page = 'http://www.yqxs.com/data/writer/writer223.html';//单作者
        $page = 'http://www.yqxs.com/data/book2/Gdjqq35006/'; //单页
        $page = 'http://www.yqxs.com/data/top/new.html'; //最新列表
        $page = 'http://www.yqxs.com/data/top/top.html' ;//总排行 
        $page = 'http://www.yqxs.com/data/xz/list1_2.html' ;//列表次页
        
        var_dump($m);
        */
        $res = yqxs_down_image('http://www.yqxs.com/data/images/notimg.gif');
        var_dump($res);