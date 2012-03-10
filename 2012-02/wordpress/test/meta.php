<?php
    $data =file_get_contents('meta.txt');
    echo "<pre>";
    $arr = unserialize($data);
    var_dump($arr);
    