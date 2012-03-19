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
         
          <li onmouseover="this.className='over'" onmouseout="this.className=''">
          <div class="yqxs_excerpt">
          她不知道这男人怎么还有脸敢出现在自己面前，<br />
就算他功成名就，在商场上被誉为举世无双的伟大企业家，<br />
也不能抹煞他是个负心汉的事实，她怎会给他好脸色看！<br />
可他不惧她的冷脸，每天风雨无阻的送便当来给她吃，<br />
还买东西给她买上了瘾，小至手表大至房子无一不送，<br />
甚至装可怜的说自己离开这里太久，人生地不熟的，<br />
想博取同情拐她陪他去买生活用品……<br />
真亏他说得出口，当初他被有钱老爸接回美国后，<br />
就像人间蒸发一样消失了，她再也联络不上他，<br />
而她却因为他一句“不要嫁别人”的要求，<br />
就傻傻的一等十四年，等到再也不抱希望、心也死了，<br />
他却又突然出现，像牛皮糖一样甩也甩不掉，<br />
可他不知道的是，现在她生命中已有了更重要的男人，<br />
想和她在一起，得先过“他”那一关……
          </div>
          
            <div class="listimg"> <a href="/view/2012/3_18/3292.html" target="_blank"><img src="http://www.yqxscaiji.tk/wp-content/uploads/covers/1254635549-120x160.jpg" onerror="src='/template/default2.6/images/nopic.gif'" 

alt="人间丈夫" /><span>作者：陈东方</span></a>
            <div class="listInfo">
              <h3><a href="/view/2012/3_18/3292.html" title="暴力宇宙海賊" target="_blank">暴力宇宙海賊</a></h3>
              <P>下载次数：121575</p>

              <P>动漫类型：连载动漫</p>
              <P>动漫地区：日本</p>
              <P>动漫时间：2012</p>
            </div>

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
