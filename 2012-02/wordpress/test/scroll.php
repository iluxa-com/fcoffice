<html>
<head>
<title>测试 滚动</title>
<style type="text/css">
body{ color:#333; font-size:13px;}
h3,ul,li{margin:0;padding:0; list-style:none;}
.scrollbox{width:400px; padding:10px;border:#ccc 1px solid;}
#scrollDiv{width:400px;height:180px; overflow:hidden;}/*这里的高度和超出隐藏是必须的*/
#scrollDiv li{height:25px;line-height:25px; vertical-align:bottom; zoom:1; border-bottom:#CCC dashed 1px;}
#scrollDiv li a{ color:#333; text-decoration:none;}
#scrollDiv li a:hover{ color:#FF0000; text-decoration:underline;}

.scroltit{ height:26px; line-height:26px; border-bottom:#CCC dashed 1px; padding-bottom:4px; margin-bottom:4px;}
.scroltit h3{ width:100px; float:left;}
.scroltit small{float:right; font-size:13px;}
</style>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.4.2/jquery.min.js" type="text/javascript"></script>
<script src="jq_scroll.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function(){
        $("#scrollDiv").Scroll({line:1,speed:500,timer:3000,up:"but_up",down:"but_down"});
});
</script>
</head>

<body>
<h1><a href="http://www.51xuediannao.com/js/jquery/jquery_scroll_sx.html">基于jquery文本向上滚动代码带上下翻转按钮的jQuery插件</a></h1>
<div class="scrollbox">
	<div class="scroltit"><h3><a href="#">Jquery 特效</a></h3><small id="but_up">↑向上</small><small id="but_down">↓向下</small></div>
    <div id="scrollDiv">
        <ul>
            <li>[<a href='http://www.51xuediannao.com/js/texiao/'>网页特效</a>] <a href="http://www.51xuediannao.com/js/texiao/642.html" title="为网站增加圣诞节祝福动画百度的圣诞老人动画" class="linktit">为网站增加圣诞节祝福动画百度的圣诞老人动画</a></li>

            <li>[<a href='http://www.51xuediannao.com/yumenba/'>郁闷吧</a>] <a href="http://www.51xuediannao.com/yumenba/606.html" title="2011最简单最给力的兼职网赚项目" class="linktit">2011最简单最给力的兼职网赚项目</a></li>
            <li>[<a href='http://www.51xuediannao.com/yumenba/'>郁闷吧</a>] <a href="http://www.51xuediannao.com/yumenba/yuwangmen_heti.html" title="90后富家二小姐渔网妹欲造合体门(多图)" class="linktit">90后富家二小姐渔网妹欲造合体门(多图)</a></li>
            <li>[<a href='http://www.51xuediannao.com/yumenba/'>郁闷吧</a>] <a href="http://www.51xuediannao.com/yumenba/google.html" title="关于谷歌中国的最新声明(来自谷歌)" class="linktit">关于谷歌中国的最新声明(来自谷歌)</a></li>

            <li>[<a href='http://www.51xuediannao.com/jiqiao/'>建站技巧</a>] <a href="http://www.51xuediannao.com/jiqiao/chuangyi.html" title="设计创意究竟是怎么练出来的？" class="linktit">设计创意究竟是怎么练出来的？</a></li>
            <li>[<a href='http://www.51xuediannao.com/weike/'>威客网排行榜</a>] <a href="http://www.51xuediannao.com/weike/weike_daxuesheng.html" title="大三学生做威客一边学习一边赚生活费" class="linktit">大三学生做威客一边学习一边赚生活费</a></li>
            <li>[<a href='http://www.51xuediannao.com/js/texiao/'>网页特效</a>] <a href="http://www.51xuediannao.com/js/texiao/js-riqi.html" title="日期时间带星期农历js代码特效" class="linktit">日期时间带星期农历js代码特效</a></li>

            <li>[<a href='http://www.51xuediannao.com/html+css/htmlcssjq/'>html+css技巧</a>] <a href="http://www.51xuediannao.com/html+css/htmlcssjq/IE6 min.html" title="IE6最小高度最小宽度最大高度最大宽度css写法" class="linktit">IE6最小高度最小宽度最大高度最大宽度css写法</a></li>
        </ul>
    </div>
</div>
</body>
</html>