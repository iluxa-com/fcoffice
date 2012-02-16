<?php
    /**
        *  把中国天气网数据可使用jsonp页面
        * 请求如http://localhost/wt_jsonp.php?id=101280601&callback=jsonp1232617941775(回调函数如果使用jquery可自动生成)
        * 参考http://www.ibm.com/developerworks/cn/web/wa-aj-jsonp1/
        */
    if(!isset($_REQUEST['callback']) OR empty($_REQUEST['callback'])) {
        header("HTTP/1.0 500 Internal Server Error");
        $ret_arr = array(
            'msg'=>"Must specify a callback for JavaScript responses.",
            'code'=>-1,
            'http_status'=>400,
        );
        echo json_encode($ret_arr);
        die();
    }
    if(!isset($_REQUEST['id']) OR !is_numeric($_REQUEST['id'])){
        $_REQUEST['id'] = 101010100; //默认为北京
    }
    $id = (int)$_REQUEST['id'];
    $dataUrl = 'http://m.weather.com.cn/data/' . $id . '.html?';
    $data = file_get_contents($dataUrl);
    if(strlen($data) == 0) {
        header("HTTP/1.0 500 Internal Server Error");
        $ret_arr = array(
            'msg'=>"get data error",
            'code'=>-2,
            'http_status'=>500,
        );
    }elseif(strpos($data,'302 Found')) {
        header("HTTP/1.0 500 Internal Server Error");
        $ret_arr = array(
            'msg'=>"city id may not exist",
            'code'=>-3,
            'http_status'=>400,
        );
        
    }else {
        //2℃~-7℃ 把温度从低到高排列,非必需
        $data = preg_replace('#(\-?[\d.]+)(℃|℉)~(\-?[\d.]+)#','\\3'.'\\2'.'~'.'\\1',$data);
        echo $_REQUEST['callback'] . '(' . $data . ');';
        die();
    }
    echo json_encode($ret_arr);
    
    