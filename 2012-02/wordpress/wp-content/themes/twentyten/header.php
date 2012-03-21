<?php
/**
 * The Header for our theme.
 *
 * Displays all of the <head> section and everything up till <div id="main">
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

?>
<?php
/* $data = yqxs_get_all_users(64);
var_dump($data);
die(); */

?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="keywords" content="关键字" />
<meta name="description" content="<?php bloginfo( 'description' ); ?>" />
<meta name="robots" content="index,follow">
<meta name="googlebot" content="index,follow">
<meta http-equiv="x-ua-compatible" content="ie=7" />

<title><?php
	/*
	 * Print the <title> tag based on what is being viewed.
	 */
	global $page, $paged;

	wp_title( '|', true, 'right' );

	// Add the blog name.
	bloginfo( 'name' );

	// Add the blog description for the home/front page.
	$site_description = get_bloginfo( 'description', 'display' );
	if ( $site_description && ( is_home() || is_front_page() ) )
		echo " | $site_description";

	// Add a page number if necessary:
	if ( $paged >= 2 || $page >= 2 )
		echo ' | ' . sprintf( __( 'Page %s', 'twentyten' ), max( $paged, $page ) );

	?></title>
    
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="stylesheet" type="text/css" media="all" href="<?php echo YQURI;?>/images/all.css" />
<link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo( 'stylesheet_url' ); ?>" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<script type="text/javascript" src="/wp-content/plugins/yqxs/js/jquery-1.6.2.min.js?yqxs=1.0"></script>
<script type="text/javascript" src="<?php echo YQURI;?>/js/jquery.lazyload.js?yqxs=1.0"></script>
<script type="text/javascript" src="<?php echo YQURI;?>/js/common.js?yqxs=1.0"></script>
<script type="text/javascript" src="<?php echo YQURI;?>/js/Ajax_Search.js?yqxs=1.0"></script>





<?php
	/* We add some JavaScript to pages with the comment form
	 * to support sites with threaded comments (when in use).
	 */
	if ( is_singular() && get_option( 'thread_comments' ) )
		wp_enqueue_script( 'comment-reply' );

	/* Always have wp_head() just before the closing </head>
	 * tag of your theme, or you will break many plugins, which
	 * generally use this hook to add elements to <head> such
	 * as styles, scripts, and meta tags.
	 */
	wp_head();
?>
</head>

<body <?php //body_class(); ?>>


<center><div class="logo">
<h1 id="blog_name"><?php bloginfo( 'name' );?></h1>
<h2 id="blog_url"><?php bloginfo( 'url' ); ?></h2>
<span style="float:right">
</span>
<span id="myads" style="float:right; margin-right:0px;"></span>
</div></center>
<div class="channel-nav">
	<ul>
		<li class="ye"><a href="<?php echo home_url('/')?>">首页</a></li>
    <li><a href="/recent">最近更新</a></li>    
    <li><a href="/list/18.html" >作家列表</a></li>
    <li><a href="/list/18.html" >情节分类</a></li>
    <li><a href="http://bbs.hltm.cc/forum-55-1.html" target='_blank' >推荐作品</a></li>
    <li><a href="http://bbs.hltm.cc/forum-55-1.html" target='_blank' >热门浏览</a></li>
    <li><a href="http://bbs.hltm.cc/forum-55-1.html" target='_blank' >下载排行</a></li>
     <li><a href="http://www.hltm.cc/html/article/index535.html">我的微博</a></li>

     <li><a href="http://bbs.hltm.cc/" target='_blank'>关于本站</a></li>
	</ul>

<form action="<?php bloginfo('home'); ?>" method="get" class="search"  name="formsearch" id="formsearch">
    <input type="text" size="25" autocomplete="off" id="searchword" name="s" value="<?php the_search_query(); ?>" onkeyup="searchSuggest();" >
    <button id="searchbar" type="submit" name="submit">搜索</button>
</form>
</div>
<div style="margin-top:-10px"></div>
<div class="subNav">
	<div class="subNavList">

		<div class="charNav">
			<strong><img src="<?php echo YQURI;?>/images/btn-chrlist.gif"></strong><a href="/search.asp?searchtype=4&searchword=A">A</a><a href="/search.asp?searchtype=4&searchword=B">B</a><a href="/search.asp?searchtype=4&searchword=C">C</a><a href="/search.asp?searchtype=4&searchword=D">D</a><a href="/search.asp?searchtype=4&searchword=E">E</a><a href="/search.asp?searchtype=4&searchword=F">F</a><a href="/search.asp?searchtype=4&searchword=G">G</a><a href="/search.asp?searchtype=4&searchword=H">H</a><a href="/search.asp?searchtype=4&searchword=I">I</a><a href="/search.asp?searchtype=4&searchword=J">J</a><a href="/search.asp?searchtype=4&searchword=K">K</a><a href="/search.asp?searchtype=4&searchword=L">L</a><a href="/search.asp?searchtype=4&searchword=M">M</a><a href="/search.asp?searchtype=4&searchword=N">N</a><a href="/search.asp?searchtype=4&searchword=O">O</a><a href="/search.asp?searchtype=4&searchword=P">P</a><a href="/search.asp?searchtype=4&searchword=Q">Q</a><a href="/search.asp?searchtype=4&searchword=R">R</a><a href="/search.asp?searchtype=4&searchword=S">S</a><a href="/search.asp?searchtype=4&searchword=T">T</a><a href="/search.asp?searchtype=4&searchword=U">U</a><a href="/search.asp?searchtype=4&searchword=V">V</a><a href="/search.asp?searchtype=4&searchword=W">W</a><a href="/search.asp?searchtype=4&searchword=X">X</a><a href="/search.asp?searchtype=4&searchword=Y">Y</a><a href="/search.asp?searchtype=4&searchword=Z">Z</a>


		</div>
		<div class="otherNav">
			<span>
<a href="javascript:hidead()">屏蔽站内广告</a>
                                <a href="#" onClick="this.style.behavior='url(#default#homepage)';this.setHomePage('http://www.hltm.cc/');" onMouseOut="window.status='红旅动漫';return true;" onMouseOver="window.status='将本站设为首页';return true;" style="behavior: url(#default#homepage);" target="_self">设红旅为首页</a>
				<a href="javascript:void(0);" onClick="window.external.AddFavorite('http://www.hltm.cc/','红旅动漫');"class="addFav">收藏红旅动漫</a>
			</span>
<a href="http://www.hltm.cc/topiclist/20121yuexinfan.html">2012年一月新番主题</a>&nbsp;
                                                <a href="http://www.hltm.cc/topiclist/201110yuexinfan.html">2011年十月新番主题</a>&nbsp;			                                                <a href="http://www.hltm.cc/zt/index.html">往期全部新番主题</a>&nbsp;

                                               			本站共有<font color=red>1776</font>部动漫，今日更新<font color=red>7</font>部动漫  		</div>
	</div>
</div>
<span style="margin-right:250px;float:right"><div id="search_suggest" style="display:none"></div></span>


<script language="javascript">
/*
function getzi()
{
window.frames["frame_content"].getWeek();

 }
*/
 </script>
<center>
<?php if(!is_page() AND !is_single() AND !is_404())  :?>
<div id="1" class="ad960">
<?php
    
        global $wpdb;
        
        //$post_arr = $wpdb->get_col("SELECT post_parent FROM $wpdb->posts WHERE post_parent>0 ORDER BY RAND() LIMIT 0,8");
        //var_dump($attachment);
        /*
            $default_attr = array(
                'src'	=> '-',
                'class'	=> "attachment-",
                'alt'	=> trim(strip_tags( $attachment->post_excerpt )),
                'title'	=> trim(strip_tags( $attachment->post_title )),
            );
           */
         $post_arr = $wpdb->get_col(
                 "SELECT post_id
            FROM $wpdb->postmeta
            WHERE `meta_key` = '_thumbnail_id'
            AND meta_value NOT
            IN (
            SELECT post_id
            FROM $wpdb->postmeta
            WHERE meta_value = 'covers/notimg.gif'
            ) ORDER BY RAND() LIMIT 0,8"
         )  ;
        foreach($post_arr as $post_id) {
                $size = array(120,160);
                $thumb = get_the_post_thumbnail( $post_id, $size);
                if($thumb==null) $thumb ="<img src='/wp-content/uploads/covers/notimg-120x160.gif' />";
                echo '<a href="'.get_permalink($post_id). '">'. $thumb .'</a>';
         }


  
 ?>

</div>
<?php endif?>
</center>

