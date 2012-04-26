<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>多选下拉列表 - 点击弹出</title>
<style>

.muti_selector_float_div {
    border-collapse: collapse;
    font-size: 0.8em;
    width: 150px;
    background-color:#EEEEEE;
    z-index: 999999;
    position: absolute;
    border: 1px solid #DDDDDD;
    margin-top:2px;
    display:none;

    border-bottom-right-radius: 4px;
    border-bottom-left-radius: 4px;
}

.muti_selector_controller {
    background: url("images/ui-bg_gloss-wave_35_f6a828_500x100.png") repeat-x scroll 50% 50% #F6A828;
    border: 1px solid #E78F08;
    color: #FFFFFF;
    font-weight: bold;
    border-top-left-radius: 4px;
    border-top-right-radius: 4px;
    border-bottom-right-radius: 4px;
    border-bottom-left-radius: 4px;
    margin:2px;
}
.controller_items {
 cursor: pointer; 
}
.controller_items_ok {
 margin-left: 5px;
}
.controller_items_cancel {
 float: right;
 margin-right: 5px;
}

</style>
<script type="text/javascript" src="jquery-1.6.2.min.js"></script>
</head>
<body>
<?php 
    require('helper2.php');
    $config = array( 
        'label'=>'请选择人员:', //显示名称文本框前的文字
        'hidden'=>'members', //隐藏的字段名和id，实际提交用
        'display'=>'mem_display', //显示名称的文本框id
        'data' => array(
            array('id'=>170,'name'=>'陈小明'),
            array('id'=>110,'name'=>'测试用1'),
            array('id'=>120,'name'=>'测试用2'),
            array('id'=>190,'name'=>'测试用3'),
            array('id'=>191,'name'=>'测试用4'),
            array('id'=>192,'name'=>'测试用5'),
            array('id'=>193,'name'=>'测试用6'),
            array('id'=>194,'name'=>'测试用7'),
        ),//绑定数据
    );
   $config2 = array( 
    'label'=>'第二个选择:', //显示名称文本框前的文字
    'hidden'=>'members2', //隐藏的字段名和id，实际提交用
    'display'=>'mem_display2', //显示名称的文本框id
    'data' => array(
        array('id'=>2130,'name'=>'测试2用1'),
        array('id'=>2140,'name'=>'测试2用2'),
        array('id'=>2150,'name'=>'测试2用3'),
        array('id'=>2161,'name'=>'测试2用4'),
        array('id'=>2122,'name'=>'测试2用5'),
        array('id'=>2193,'name'=>'测试2用6'),
        array('id'=>2134,'name'=>'测试2用7'),
    ),//绑定数据
    );
    $config3 = array( 
    'label'=>'第三个选择:', //显示名称文本框前的文字
    'hidden'=>'members3', //隐藏的字段名和id，实际提交用
    'display'=>'mem_display3', //显示名称的文本框id
    'data' => array(
        array('id'=>2130,'name'=>'测试3用1'),
        array('id'=>2140,'name'=>'测试3用2'),
        array('id'=>2150,'name'=>'测试3用3'),
        array('id'=>2161,'name'=>'测试3用5'),
        array('id'=>2193,'name'=>'测试3用6'),
        array('id'=>2134,'name'=>'测试3用7'),
        ),//绑定数据
    
    ); 
?>        
<?php echo muti_selector($config);?>
<?php echo muti_selector($config2);?>
<?php echo muti_selector($config3);?>
</body>
</html>