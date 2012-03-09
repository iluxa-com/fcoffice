<?php
//加载插件目录下的js
function yqxs_loadJs($js) {
    $jq_src = YQXS_URL .'/js/'.$js.'?yqxs=1.0';
    echo '<script type="text/javascript" src="'.  $jq_src   .'"></script>';
}

//切割系列的字符
function split_words($str) {
    if(preg_match('#(.+?)(\w+)$#',$str,$matches) ) {
        array_shift($matches);
        return array_map('trim',$matches);
    }else {
        return array($str,);
    }
 }   