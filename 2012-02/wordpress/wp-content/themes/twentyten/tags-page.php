<?php
/**
 * Template Name: tags, no sidebar
 *
 * A custom page template without sidebar.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>

	<div class="main">
		<div class="blank2 index_bg1">
		</div>
		<div class="index_bg2">

			<div class="indexLeft">


<div class="right" style="width:745px;">

						<h2 class="sbar">
							<span><a href='#' onclick=getzi()></a></span>
							<strong>人气作家列表 </strong>
						</h2>



								<ul class="chrComicList" style="margin-left:20px;height:210px;">
<!--    
									<iframe id="frame_content" width="720px" src="http://www.hltm.cc/shixianindex.asp" scrolling="no" frameborder="0"
onload="this.height=frame_content.document.body.scrollHeight+5"></iframe>
-->

                                
                                <?php
                                                            $all_users = yqxs_get_all_users(48);
                                                             foreach($all_users as $user)  {
                                                                echo '<li> <a href="'. get_author_posts_url($user->ID) .'">'.$user->display_name .'</li>';
                                                             }
                                                            ?>
                                
                                                            
								</ul>

							</div>







<div style="margin-top:-12px"></div>
<div class="blank2 index_bg1">
</div>

				<div class="lr1px">
					<div class="tabList" id="tabList">

						<ul>
							<li class="on" onmouseover="changeTab(4,0)"><strong>新增小说</strong></li>
							<li onmouseover="changeTab(4,1)"><strong>青梅竹马</strong></li>
							<li onmouseover="changeTab(4,2)"><strong>穿越时空</strong></li>
							<li onmouseover="changeTab(4,3)"><strong>办公室恋情</strong></li>
												</ul>
					</div>

					<div class="blank8">
					</div>
					<div class="comicList" id="comicList">

						<ul style="display:block;">
                        <?php echo yqxs_get_reacent_posts_and_thumbnails(10);?>

                                                </ul>
						<ul>
                        <?php echo yqxs_get_cat_posts_and_thumbnails(10,'青梅竹马');?>

						</ul>
						<ul>
                        <?php echo yqxs_get_cat_posts_and_thumbnails(10,'穿越时空');?>
                       </ul>
						<ul>
                        <?php echo yqxs_get_cat_posts_and_thumbnails(10,'办公室恋情');?>

                                               </ul>
					</div>
				</div>
<div id="2" class="lineGray1" style="text-align:center; padding:6px 0 2px;">
<span id="myads1" style="float:right; margin-right:0px;"></span>
				</div>



				<div class="blank4 index_bg4"></div>

				<div class="index_bg5">
					<div class="left" style="width:146px;">
						<div class="lr1px">
							<h2 class="bar"><strong>热门分类排行</strong></h2>
							<ul class="topHits">
                            <?php echo yqxs_get_categories_list();?>
                            </ul>
						</div>
						<div class="blank8 lineGray"></div>


                                                <div class="lr1px">
							<h2 class="bar"><strong>动漫下载排行</strong></h2>
							<ul class="topHits">

<li class="line"><a href="/view/2012/3_4/972.html" title='海贼王' target='_blank' ><span class="t6"><em>1.</em></span>海贼王</a></li>

<li class="line"><a href="/view/2012/3_15/1018.html" title='火影忍者' target='_blank' ><span class="t6"><em>2.</em></span>火影忍者</a></li>


<li class="line"><a href="/view/2012/3_10/994.html" title='妖精的尾巴' target='_blank' ><span class="t6"><em>3.</em></span>妖精的尾巴</a></li>

<li class="line"><a href="/view/2012/3_13/999.html" title='死神' target='_blank' ><span class="t6"><em>4.</em></span>死神</a></li>

<li class="line"><a href="/view/2012/3_10/974.html" title='名侦探柯南' target='_blank' ><span class="t6"><em>5.</em></span>名侦探柯南</a></li>

<li class="line"><a href="/view/2012/3_11/3004.html" title='魔王的父亲/魔王奶爸/恶魔奶爸' target='_blank' ><span class="t6"><em>6.</em></span>魔王的父亲/魔王奶爸/恶魔奶爸</a></li>


<li class="line"><a href="/view/2011/8_24/975.html" title='龙珠改' target='_blank' ><span class="t6"><em>7.</em></span>龙珠改</a></li>

<li class="line"><a href="/view/2012/3_12/952.html" title='银魂' target='_blank' ><span class="t6"><em>8.</em></span>银魂</a></li>

<li class="line"><a href="/view/2011/2_21/1359.html" title='亲吻姐姐' target='_blank' ><span class="t6"><em>9.</em></span>亲吻姐姐</a></li>

<li class="line"><a href="/view/2011/2_20/1029.html" title='钢之炼金术师第二季' target='_blank' ><span class="t6"><em>10.</em></span>钢之炼金术师第二季</a></li>

<li class="line"><a href="/view/2011/4_2/2948.html" title='魔法禁书目录II' target='_blank' ><span class="t6"><em>11.</em></span>魔法禁书目录II</a></li>


<li class="line"><a href="/view/2011/4_27/2764.html" title='学园默示录+OVA' target='_blank' ><span class="t6"><em>12.</em></span>学园默示录+OVA</a></li>

<li class="line"><a href="/view/2011/10_2/3098.html" title='青之驱魔师/蓝色驱魔师' target='_blank' ><span class="t6"><em>13.</em></span>青之驱魔师/蓝色驱魔师</a></li>

<li class="line"><a href="/view/2012/3_16/3199.html" title='罪恶王冠/原罪之冠' target='_blank' ><span class="t6"><em>14.</em></span>罪恶王冠/原罪之冠</a></li>

<li class="line"><a href="/view/2011/12_26/3116.html" title='滑头鬼之孙 千年魔京' target='_blank' ><span class="t6"><em>15.</em></span>滑头鬼之孙 千年魔京</a></li>


<li class="line"><a href="/view/2011/2_21/1551.html" title='学生会长是女仆' target='_blank' ><span class="t6"><em>16.</em></span>学生会长是女仆</a></li>
	</ul>
						</div>
						<div class="blank8 lineGray"></div>


						<div class="lr1px">
							<h2 class="bar"><strong>动漫推荐排行</strong></h2>
							<ul class="topHits">

<li class="line"><a href="/view/2012/3_4/972.html" title='海贼王' target='_blank' ><span class="t6"><em>1.</em></span>海贼王</a></li>

<li class="line"><a href="/view/2012/3_15/1018.html" title='火影忍者' target='_blank' ><span class="t6"><em>2.</em></span>火影忍者</a></li>

<li class="line"><a href="/view/2012/3_10/994.html" title='妖精的尾巴' target='_blank' ><span class="t6"><em>3.</em></span>妖精的尾巴</a></li>

<li class="line"><a href="/view/2012/3_13/999.html" title='死神' target='_blank' ><span class="t6"><em>4.</em></span>死神</a></li>


<li class="line"><a href="/view/2011/2_21/1359.html" title='亲吻姐姐' target='_blank' ><span class="t6"><em>5.</em></span>亲吻姐姐</a></li>

<li class="line"><a href="/view/2012/3_10/974.html" title='名侦探柯南' target='_blank' ><span class="t6"><em>6.</em></span>名侦探柯南</a></li>

<li class="line"><a href="/view/2011/2_21/966.html" title='家庭教师' target='_blank' ><span class="t6"><em>7.</em></span>家庭教师</a></li>

<li class="line"><a href="/view/2011/2_21/1551.html" title='学生会长是女仆' target='_blank' ><span class="t6"><em>8.</em></span>学生会长是女仆</a></li>

<li class="line"><a href="/view/2011/2_20/1554.html" title='Angel_Beats!' target='_blank' ><span class="t6"><em>9.</em></span>Angel_Beats!</a></li>


<li class="line"><a href="/view/2011/2_21/971.html" title='犬夜叉完结篇' target='_blank' ><span class="t6"><em>10.</em></span>犬夜叉完结篇</a></li>

<li class="line"><a href="/view/2011/2_20/1029.html" title='钢之炼金术师第二季' target='_blank' ><span class="t6"><em>11.</em></span>钢之炼金术师第二季</a></li>

<li class="line"><a href="/view/2011/2_20/1076.html" title='管家后宫学园' target='_blank' ><span class="t6"><em>12.</em></span>管家后宫学园</a></li>

<li class="line"><a href="/view/2011/4_27/2764.html" title='学园默示录+OVA' target='_blank' ><span class="t6"><em>13.</em></span>学园默示录+OVA</a></li>


<li class="line"><a href="/view/2011/2_22/986.html" title='天降之物' target='_blank' ><span class="t6"><em>14.</em></span>天降之物</a></li>

<li class="line"><a href="/view/2011/6_12/2935.html" title='缘之空' target='_blank' ><span class="t6"><em>15.</em></span>缘之空</a></li>

<li class="line"><a href="/view/2012/3_12/952.html" title='银魂' target='_blank' ><span class="t6"><em>16.</em></span>银魂</a></li>

</ul>
						</div>
					</div>

					<div class="right" style="width:591px;">

						<h2 class="sbar">
							<span></span>
							<strong>字母 A - D</strong>
						</h2>
						<ul class="chrComicList">

                        <?php echo yqxs_get_posts_by_chars(40,array('A','B','C','D'));?>

						</ul>
						<span class="blank8">
						</span>
						<div class="lineGray1">
						</div>
						<h2 class="sbar">
							<span></span>
							<strong>字母 E - H</strong>

						</h2>
						<ul class="chrComicList">
                        <?php echo yqxs_get_posts_by_chars(40,array('E','F','G','H'));?>

                                                </ul>
						<div class="blank8">
						</div>
						<div class="lineGray1">
						</div>
						<h2 class="sbar">
							<span></span>

							<strong>字母 I - L</strong>
						</h2>
						<ul class="chrComicList">
                            <?php echo yqxs_get_posts_by_chars(40,array('I','J','K','L'));?>

                        </ul>
						<div class="blank8">
						</div>
						<div class="lineGray1">

						</div>
						<h2 class="sbar">
							<span></span>
							<strong>字母 M - P</strong>
						</h2>
						<ul class="chrComicList">
                        <?php echo yqxs_get_posts_by_chars(40,array('M','N','O','P'));?>
						</ul>

						<div class="blank8">
						</div>
						<div class="lineGray1">
						</div>
						<h2 class="sbar">
							<span></span>
							<strong>字母 Q - V</strong>
						</h2>

						<ul class="chrComicList">
                        <?php echo yqxs_get_posts_by_chars(40,array('Q','R','S','T'));?>

						</ul>
						<div class="blank8">
						</div>
						<div class="lineGray1">
						</div>
						<h2 class="sbar">

							<span></span>
							<strong>字母 W - Z</strong>
						</h2>
						<ul class="chrComicList">
                         <?php echo yqxs_get_posts_by_chars(45,array('U','V','W','X','Y','Z'));?>

						</ul>
						<div class="blank8">
						</div>
					</div>

					<div class="blank2">
					</div>
				</div>
			</div>
			<div class="indexRight">
				<div class="indexRightIn">
				<div class="lr1px">




						<h2 class="bar">
							<span>

							</span>
							<strong>
								小说更新列表							</strong>
						</h2>
						<ul class="newUpdate">
                        
                        <?php echo yqxs_get_recent_posts(74);?>
						</li>



                        </ul>



						<div class="blank2">
						</div>
					</div>
				</div>

			</div>
			<div class="blank4 index_bg3">
			</div>


		</div>
	</div>
    
<div class="main border1">
		<h2 class="bar">

			<span>
				合作联系:hltmnet#qq.com
			</span>
			<strong>
				友情链接
			</strong>
		</h2>
		<div class="blank4">
		</div>
		<ul class="friendLink">

<li><a href="http://www.hao123.com/" target='_blank'>好123</a></li>
<li><a href="http://www.2345.com/" target='_blank'>2345网址导航</a></li>
<li><a href="http://hao.360.cn/" target='_blank'>360网址导航</a></li>
<li><a href="http://123.sogou.com/" target='_blank'>搜狗网址导航</a></li>
<li><a href="http://hao.qq.com/" target='_blank'>QQ网址导航</a></li>
<li><a href="http://www.13393.com/" target='_blank'>傲游网址导航</a></li>
<li><a href="http://www.1616.net/" target='_blank'>1616网址导航</a></li>
<li><a href="http://www.46.com/" target='_blank'>46网站导航</a></li>
    <li><a href="http://bbs.wydm.net/" target='_blank'>散漫舍</a></li>

    <li><a href="http://www.narutom.com/" target='_blank'>火影忍者</a></li>
    <li><a href="http://dm.56.com/" target='_blank'>56动漫频道</a></li>
    <li><a href="http://naruto4u.com/" target='_blank'>火影忍者</a></li>
    <li><a href="http://op.52pk.com/" target='_blank'>海贼王</a></li>
    <li><a href="http://www.psp99.com/" target='_blank'>PSP</a></li>
    <li><a href="http://www.dm5x.com/" target='_blank'>日本动画片</a></li>

    <li><a href="http://www.naruto.hk/" target='_blank'>疾风传中文网</a></li>
    <li><a href="http://www.comic.gov.cn/" target='_blank'>中国动漫网</a></li>
    <li><a href="http://www.ruanhu.com/" target='_blank'>软狐动漫</a></li>
    <li><a href="http://www.pspmi.com/" target='_blank'>动漫迷</a></li>
    <li><a href="http://game.ycwb.com/" target='_blank'>金羊游戏</a></li>
    <li><a href="http://www.173kt.com/" target='_blank'>在线动画片</a></li>

    <li><a href="http://www.guan5.com/" target='_blank'>网页游戏</a></li>
    <li><a href="http://www.xingrao.com/" target='_blank'>星绕网</a></li>
    <li><a href="http://www.pcomic.com.cn/" target='_blank'>死神漫画</a></li>
    <li><a href="http://www.jumpcn.com.cn/" target='_blank'>海贼王漫画</a></li>
    <li><a href="http://www.5pk.com/" target='_blank'>5PK网游</a></li>
    <li><a href="http://www.xmanwang.com/" target='_blank'>兴漫网</a></li>
    <li><a href="http://www.animenewtype.com/forum.php" target='_blank'>动心论坛</a></li>
    <li><a href="http://www.qiuhu.com/" target='_blank'>秋虎漫画网</a></li>

    <li><a href="http://www.gxdmw.com/" target='_blank'>高校动漫网</a></li>
    <li><a href="http://bt.ktxp.com/" target='_blank'>极影动漫BT</a></li>
    <li><a href="http://www.kkkmh.com/" target='_blank'>火影忍着漫画</a></li>
    <li><a href="http://www.dj527.com/" target='_blank'>DJ</a></li>
    <li><a href="http://www.bengou.com/" target='_blank'>笨狗漫画</a></li>
    <li><a href="http://www.yuyudy.com/" target='_blank'>鱼鱼电影</a></li>

    <li><a href="http://www.78dm.net/" target='_blank'>78动漫</a></li>
    <li><a href="http://www.3737.cc/" target='_blank'>小游戏</a></li>
    <li><a href="http://www.sc0817.com/" target='_blank'>果城网景</a></li>
    <li><a href="http://www.muu.com.cn/" target='_blank'>漫悠悠</a></li>
    <li><a href="http://v.766.com/" target='_blank'>766视频</a></li>
    <li><a href="http://www.5icbs.com/" target='_blank'>蓝巨星官方</a></li>

    <li><a href="http://www.iqiyi.com/dongman/" target='_blank'>奇艺动漫</a></li>
    <li><a href="http://www.dodo8.com/" target='_blank'>火影忍者中文网</a></li>
    <li><a href="http://www.9lala.com/" target='_blank'>九啦啦漫画</a></li>
    <li><a href="http://www.ggyy8.cc/" target='_blank'>ggyy8漫画</a></li>
    <li><a href="http://www.52tian.net/" target='_blank'>天上人间动漫网</a></li>
    <li><a href="http://www.tuku.cc/" target='_blank'>CC漫画</a></li>

		</ul>
		<div class="blank4">
		</div>
	</div>    


<?php get_footer(); ?>
