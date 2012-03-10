<?php
    date_default_timezone_set('Asia/shanghai');
    $arr = array(
        '1' =>'one',
        '2' =>'two',
        '3'=>'three',
        '4'=>'four',

    );
    header('Content-type: text/json');
    header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");
    if(isset($arr[$_GET['id']])) {
        $data = array('id'=>$_GET['id'],'value'=>$arr[$_GET['id']],'state'=>'ok');
    }else {
        $data = array('id'=>$_GET['id'],'value'=>'not Set','state'=>'fail');
    }
    $data['time'] = date('H:i:s');
    sleep(3);
    echo json_encode($data);