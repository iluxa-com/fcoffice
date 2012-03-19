<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * E.g., it puts together the home page when no home.php file exists.
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
<?php //var_dump($_COOKIE['read_list']);?>
<?php //echo '当前文章分页'.get_query_var('did');?>
<div id="main">
	<div class="left">
		<div class="index_hot">
			<h1><b>HOT</b> <a href='/'>首页</a>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;<a href='/list/7.html' >连载动漫</a></h1>

      <div class="listBox">

        <ul>
        <li class='novel_news_style_form'>
            <div class="novel_news_style_img">
                <a target="_blank" href="http://www.laoshu.org/novel/11964/">
                <img src="http://www.yqxscaiji.tk/wp-content/uploads/covers/1286337640-120x160.jpg">
                </a>
            </div>
            <div class="novel_news_style_text">
                        <h3><a class='title_link' target="_blank" href="http://www.laoshu.org/novel/11964/">人间丈夫</a></h3>
                         <ul class='list_info_1'><li class='terms'>作 者：<a target="_blank" title="点击查看采威作品集" href="http://www.laoshu.org/author/%B2%C9%CD%FE">陈中国</a></li>
                        <li class='terms'>分类:<a href=#>别后重逢</a>,<a href=#>再续前缘</a></li>                        <li class='terms'>发布日期:<a href=#>2012-01-02</a></li>
                        </ul>
                        
                        <p>   <?php
                        $str = '她不知道这男人怎么还有脸敢出现在自己面前，
就算他功成名就，在商场上被誉为举世无双的伟大企业家，
也不能抹煞他是个负心汉的事实，她怎会给他好脸色看！
可他不惧她的冷脸，每天风雨无阻的送便当来给她吃，
还买东西给她买上了瘾，小至手表大至房子无一不送，
甚至装可怜的说自己离开这里太久，人生地不熟的，
想博取同情拐她陪他去买生活用品……
真亏他说得出口，当初他被有钱老爸接回美国后，
就像人间蒸发一样消失了，她再也联络不上他，
而她却因为他一句“不要嫁别人”的要求，
就傻傻的一等十四年，等到再也不抱希望、心也死了，
他却又突然出现，像牛皮糖一样甩也甩不掉，
可他不知道的是，现在她生命中已有了更重要的男人，
想和她在一起，得先过“他”那一关……';
    echo mb_substr($str,0,200).'......';
?>
                        <a class='read_icon' target="_blank" href="http://www.laoshu.org/read/11964_1661257/">立即阅读</a></p>
                        <ul class='list_info'>
                        <li class='terms'>出版社:<a href=#>花园文化</a></li>
                        <li class='terms'>出版日期:<a href=#>2010-10-01</a></li>
                        <li class='terms'>阅读指数:<a href=#>3</a></li>
                         <li class='terms'>下载次数:500</li>
                        </ul>
                    </div>
                    
        </li>
        <li class='novel_news_style_form'>
            <div class="novel_news_style_img">
                <a target="_blank" href="http://www.laoshu.org/novel/11964/">
                <img src="http://www.yqxscaiji.tk/wp-content/uploads/covers/1286337640-120x160.jpg">
                </a>
            </div>
            <div class="novel_news_style_text">
                        <h3><a class='title_link' target="_blank" href="http://www.laoshu.org/novel/11964/">人间丈夫</a></h3>
                         <ul class='list_info_1'><li class='terms'>作 者：<a target="_blank" title="点击查看采威作品集" href="http://www.laoshu.org/author/%B2%C9%CD%FE">陈中国</a></li>
                        <li class='terms'>分类:<a href=#>别后重逢</a>,<a href=#>再续前缘</a></li>                        <li class='terms'>发布日期:<a href=#>2012-01-02</a></li>
                        </ul>
                        
                        <p>   <?php
                        $str = '她不知道这男人怎么还有脸敢出现在自己面前，
就算他功成名就，在商场上被誉为举世无双的伟大企业家，
也不能抹煞他是个负心汉的事实，她怎会给他好脸色看！
可他不惧她的冷脸，每天风雨无阻的送便当来给她吃，
还买东西给她买上了瘾，小至手表大至房子无一不送，
甚至装可怜的说自己离开这里太久，人生地不熟的，
想博取同情拐她陪他去买生活用品……
真亏他说得出口，当初他被有钱老爸接回美国后，
就像人间蒸发一样消失了，她再也联络不上他，
而她却因为他一句“不要嫁别人”的要求，
就傻傻的一等十四年，等到再也不抱希望、心也死了，
他却又突然出现，像牛皮糖一样甩也甩不掉，
可他不知道的是，现在她生命中已有了更重要的男人，
想和她在一起，得先过“他”那一关……';
    echo mb_substr($str,0,200).'......';
?>
                        <a class='read_icon' target="_blank" href="http://www.laoshu.org/read/11964_1661257/">立即阅读</a></p>
                        <ul class='list_info'>
                        <li class='terms'>出版社:<a href=#>花园文化</a></li>
                        <li class='terms'>出版日期:<a href=#>2010-10-01</a></li>
                        <li class='terms'>阅读指数:<a href=#>3</a></li>
                         <li class='terms'>下载次数:500</li>
                        </ul>
                    </div>
                    
        </li>
        <li class='novel_news_style_form'>
            <div class="novel_news_style_img">
                <a target="_blank" href="http://www.laoshu.org/novel/11964/">
                <img src="http://www.yqxscaiji.tk/wp-content/uploads/covers/1286337640-120x160.jpg">
                </a>
            </div>
            <div class="novel_news_style_text">
                        <h3><a class='title_link' target="_blank" href="http://www.laoshu.org/novel/11964/">人间丈夫</a></h3>
                         <ul class='list_info_1'><li class='terms'>作 者：<a target="_blank" title="点击查看采威作品集" href="http://www.laoshu.org/author/%B2%C9%CD%FE">陈中国</a></li>
                        <li class='terms'>分类:<a href=#>别后重逢</a>,<a href=#>再续前缘</a></li>                        <li class='terms'>发布日期:<a href=#>2012-01-02</a></li>
                        </ul>
                        
                        <p>   <?php
                        $str = '她不知道这男人怎么还有脸敢出现在自己面前，
就算他功成名就，在商场上被誉为举世无双的伟大企业家，
也不能抹煞他是个负心汉的事实，她怎会给他好脸色看！
可他不惧她的冷脸，每天风雨无阻的送便当来给她吃，
还买东西给她买上了瘾，小至手表大至房子无一不送，
甚至装可怜的说自己离开这里太久，人生地不熟的，
想博取同情拐她陪他去买生活用品……
真亏他说得出口，当初他被有钱老爸接回美国后，
就像人间蒸发一样消失了，她再也联络不上他，
而她却因为他一句“不要嫁别人”的要求，
就傻傻的一等十四年，等到再也不抱希望、心也死了，
他却又突然出现，像牛皮糖一样甩也甩不掉，
可他不知道的是，现在她生命中已有了更重要的男人，
想和她在一起，得先过“他”那一关……';
    echo mb_substr($str,0,200).'......';
?>
                        <a class='read_icon' target="_blank" href="http://www.laoshu.org/read/11964_1661257/">立即阅读</a></p>
                        <ul class='list_info'>
                        <li class='terms'>出版社:<a href=#>花园文化</a></li>
                        <li class='terms'>出版日期:<a href=#>2010-10-01</a></li>
                        <li class='terms'>阅读指数:<a href=#>3</a></li>
                         <li class='terms'>下载次数:500</li>
                        </ul>
                    </div>
                    
        </li>
        <li class='novel_news_style_form'>
            <div class="novel_news_style_img">
                <a target="_blank" href="http://www.laoshu.org/novel/11964/">
                <img src="http://www.yqxscaiji.tk/wp-content/uploads/covers/1286337640-120x160.jpg">
                </a>
            </div>
            <div class="novel_news_style_text">
                        <h3><a class='title_link' target="_blank" href="http://www.laoshu.org/novel/11964/">人间丈夫</a></h3>
                         <ul class='list_info_1'><li class='terms'>作 者：<a target="_blank" title="点击查看采威作品集" href="http://www.laoshu.org/author/%B2%C9%CD%FE">陈中国</a></li>
                        <li class='terms'>分类:<a href=#>别后重逢</a>,<a href=#>再续前缘</a></li>                        <li class='terms'>发布日期:<a href=#>2012-01-02</a></li>
                        </ul>
                        
                        <p>   <?php
                        $str = '她不知道这男人怎么还有脸敢出现在自己面前，
就算他功成名就，在商场上被誉为举世无双的伟大企业家，
也不能抹煞他是个负心汉的事实，她怎会给他好脸色看！
可他不惧她的冷脸，每天风雨无阻的送便当来给她吃，
还买东西给她买上了瘾，小至手表大至房子无一不送，
甚至装可怜的说自己离开这里太久，人生地不熟的，
想博取同情拐她陪他去买生活用品……
真亏他说得出口，当初他被有钱老爸接回美国后，
就像人间蒸发一样消失了，她再也联络不上他，
而她却因为他一句“不要嫁别人”的要求，
就傻傻的一等十四年，等到再也不抱希望、心也死了，
他却又突然出现，像牛皮糖一样甩也甩不掉，
可他不知道的是，现在她生命中已有了更重要的男人，
想和她在一起，得先过“他”那一关……';
    echo mb_substr($str,0,200).'......';
?>
                        <a class='read_icon' target="_blank" href="http://www.laoshu.org/read/11964_1661257/">立即阅读</a></p>
                        <ul class='list_info'>
                        <li class='terms'>出版社:<a href=#>花园文化</a></li>
                        <li class='terms'>出版日期:<a href=#>2010-10-01</a></li>
                        <li class='terms'>阅读指数:<a href=#>3</a></li>
                         <li class='terms'>下载次数:500</li>
                        </ul>
                    </div>
                    
        </li>
        <li class='novel_news_style_form'>
            <div class="novel_news_style_img">
                <a target="_blank" href="http://www.laoshu.org/novel/11964/">
                <img src="http://www.yqxscaiji.tk/wp-content/uploads/covers/1286337640-120x160.jpg">
                </a>
            </div>
            <div class="novel_news_style_text">
                        <h3><a class='title_link' target="_blank" href="http://www.laoshu.org/novel/11964/">人间丈夫</a></h3>
                         <ul class='list_info_1'><li class='terms'>作 者：<a target="_blank" title="点击查看采威作品集" href="http://www.laoshu.org/author/%B2%C9%CD%FE">陈中国</a></li>
                        <li class='terms'>分类:<a href=#>别后重逢</a>,<a href=#>再续前缘</a></li>                        <li class='terms'>发布日期:<a href=#>2012-01-02</a></li>
                        </ul>
                        
                        <p>   <?php
                        $str = '她不知道这男人怎么还有脸敢出现在自己面前，
就算他功成名就，在商场上被誉为举世无双的伟大企业家，
也不能抹煞他是个负心汉的事实，她怎会给他好脸色看！
可他不惧她的冷脸，每天风雨无阻的送便当来给她吃，
还买东西给她买上了瘾，小至手表大至房子无一不送，
甚至装可怜的说自己离开这里太久，人生地不熟的，
想博取同情拐她陪他去买生活用品……
真亏他说得出口，当初他被有钱老爸接回美国后，
就像人间蒸发一样消失了，她再也联络不上他，
而她却因为他一句“不要嫁别人”的要求，
就傻傻的一等十四年，等到再也不抱希望、心也死了，
他却又突然出现，像牛皮糖一样甩也甩不掉，
可他不知道的是，现在她生命中已有了更重要的男人，
想和她在一起，得先过“他”那一关……';
    echo mb_substr($str,0,200).'......';
?>
                        <a class='read_icon' target="_blank" href="http://www.laoshu.org/read/11964_1661257/">立即阅读</a></p>
                        <ul class='list_info'>
                        <li class='terms'>出版社:<a href=#>花园文化</a></li>
                        <li class='terms'>出版日期:<a href=#>2010-10-01</a></li>
                        <li class='terms'>阅读指数:<a href=#>3</a></li>
                         <li class='terms'>下载次数:500</li>
                        </ul>
                    </div>
                    
        </li>
        <li class='novel_news_style_form'>
            <div class="novel_news_style_img">
                <a target="_blank" href="http://www.laoshu.org/novel/11964/">
                <img src="http://www.yqxscaiji.tk/wp-content/uploads/covers/1286337640-120x160.jpg">
                </a>
            </div>
            <div class="novel_news_style_text">
                        <h3><a class='title_link' target="_blank" href="http://www.laoshu.org/novel/11964/">人间丈夫</a></h3>
                         <ul class='list_info_1'><li class='terms'>作 者：<a target="_blank" title="点击查看采威作品集" href="http://www.laoshu.org/author/%B2%C9%CD%FE">陈中国</a></li>
                        <li class='terms'>分类:<a href=#>别后重逢</a>,<a href=#>再续前缘</a></li>                        <li class='terms'>发布日期:<a href=#>2012-01-02</a></li>
                        </ul>
                        
                        <p>   <?php
                        $str = '她不知道这男人怎么还有脸敢出现在自己面前，
就算他功成名就，在商场上被誉为举世无双的伟大企业家，
也不能抹煞他是个负心汉的事实，她怎会给他好脸色看！
可他不惧她的冷脸，每天风雨无阻的送便当来给她吃，
还买东西给她买上了瘾，小至手表大至房子无一不送，
甚至装可怜的说自己离开这里太久，人生地不熟的，
想博取同情拐她陪他去买生活用品……
真亏他说得出口，当初他被有钱老爸接回美国后，
就像人间蒸发一样消失了，她再也联络不上他，
而她却因为他一句“不要嫁别人”的要求，
就傻傻的一等十四年，等到再也不抱希望、心也死了，
他却又突然出现，像牛皮糖一样甩也甩不掉，
可他不知道的是，现在她生命中已有了更重要的男人，
想和她在一起，得先过“他”那一关……';
    echo mb_substr($str,0,200).'......';
?>
                        <a class='read_icon' target="_blank" href="http://www.laoshu.org/read/11964_1661257/">立即阅读</a></p>
                        <ul class='list_info'>
                        <li class='terms'>出版社:<a href=#>花园文化</a></li>
                        <li class='terms'>出版日期:<a href=#>2010-10-01</a></li>
                        <li class='terms'>阅读指数:<a href=#>3</a></li>
                         <li class='terms'>下载次数:500</li>
                        </ul>
                    </div>
                    
        </li>
        <li class='novel_news_style_form'>
            <div class="novel_news_style_img">
                <a target="_blank" href="http://www.laoshu.org/novel/11964/">
                <img src="http://www.yqxscaiji.tk/wp-content/uploads/covers/1286337640-120x160.jpg">
                </a>
            </div>
            <div class="novel_news_style_text">
                        <h3><a class='title_link' target="_blank" href="http://www.laoshu.org/novel/11964/">人间丈夫</a></h3>
                         <ul class='list_info_1'><li class='terms'>作 者：<a target="_blank" title="点击查看采威作品集" href="http://www.laoshu.org/author/%B2%C9%CD%FE">陈中国</a></li>
                        <li class='terms'>分类:<a href=#>别后重逢</a>,<a href=#>再续前缘</a></li>                        <li class='terms'>发布日期:<a href=#>2012-01-02</a></li>
                        </ul>
                        
                        <p>   <?php
                        $str = '她不知道这男人怎么还有脸敢出现在自己面前，
就算他功成名就，在商场上被誉为举世无双的伟大企业家，
也不能抹煞他是个负心汉的事实，她怎会给他好脸色看！
可他不惧她的冷脸，每天风雨无阻的送便当来给她吃，
还买东西给她买上了瘾，小至手表大至房子无一不送，
甚至装可怜的说自己离开这里太久，人生地不熟的，
想博取同情拐她陪他去买生活用品……
真亏他说得出口，当初他被有钱老爸接回美国后，
就像人间蒸发一样消失了，她再也联络不上他，
而她却因为他一句“不要嫁别人”的要求，
就傻傻的一等十四年，等到再也不抱希望、心也死了，
他却又突然出现，像牛皮糖一样甩也甩不掉，
可他不知道的是，现在她生命中已有了更重要的男人，
想和她在一起，得先过“他”那一关……';
    echo mb_substr($str,0,200).'......';
?>
                        <a class='read_icon' target="_blank" href="http://www.laoshu.org/read/11964_1661257/">立即阅读</a></p>
                        <ul class='list_info'>
                        <li class='terms'>出版社:<a href=#>花园文化</a></li>
                        <li class='terms'>出版日期:<a href=#>2010-10-01</a></li>
                        <li class='terms'>阅读指数:<a href=#>3</a></li>
                         <li class='terms'>下载次数:500</li>
                        </ul>
                    </div>
                    
        </li>
          
        
          
        </ul>
        <div class="blank_4px"></div>
        <div class="pagebox"><span>共102条数据 页次:1/9页</span><em class='nolink'>首页</em><em class='nolink'>上一页</em><em>1</em><a href='/list/7_2.html'>2</a><a href='/list/7_3.html'>3</a><a href='/list/7_4.html'>4</a><a href='/list/7_5.html'>5</a><a href='/list/7_6.html'>6</a><a href='/list/7_7.html'>7</a><a href='/list/7_8.html'>8</a><a href='/list/7_2.html'>下一页</a><a href='/list/7_9.html'>尾页</a><span><input type='input' name='page' size='4'/><input type='button' value='跳转' onclick="getPageGoUrl(9,'page','/list/7_<page>.html')" class='btn' /></span></div>		
		</div></div>

		<div class="line_l_bon"></div>
	</div>
	<div class="right">
	<div class="index_rank">
	<h1><b>Time</b> 最新小说</h1>
	<ul>
	
    <?php echo yqxs_get_recent_posts2(50);?>
    
		</ul>
	</div>
	
	<div class="line_r_bon"></div>
	</div>
	<div class="clear"></div>



 


</div>
<?php get_footer(); ?>
