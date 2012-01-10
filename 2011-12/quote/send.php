<?php
    require_once('config.php');
    require_once('PHPFetion.php');
    $file = file_get_contents('data.xml');
    $id_file = 'id.txt';
    if(preg_match_all('#<item>.*?<description>(.*?)</description></item>#',$file,$matches)){
        $id_data = file_get_contents($id_file);
        $id_arr = explode(',',$id_data);
        $count = range(0,count($matches[1])-1);
        foreach($count as $k=>$v) {
            if(in_array($v,$id_arr)) unset($count[$k]);
        }
        if (count($count)<1) {
            $failed = true;
            $id = -1;
         }   
        else {
            $id = array_rand($count);
        }
        
        //发送内容
        $msg = !isset($failed) ? $matches[1][$id] : '数据出错';
        $fetion = new PHPFetion($tel,$fetion_psw);
        $send_res = $fetion->toMyself($msg);
        //var_dump($send_res);
        //echo $msg;
        //写入id
        file_put_contents($id_file,$id.',',FILE_APPEND);
    }