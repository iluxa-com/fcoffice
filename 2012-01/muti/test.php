<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>多选下拉列表 - 点击弹出</title>
<style>

#muti_selector_float_div {
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

#muti_selector_controller {
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
.controller_items#ok {
 margin-left: 5px;
}
.controller_items#cancel {
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
?>        
<?php echo muti_selector($config);?>
</body>
</html>