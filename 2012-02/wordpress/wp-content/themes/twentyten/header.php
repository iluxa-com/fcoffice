<?php
global $yqxs_token;
if (!session_id())
    session_start();
if(is_single()) {
     require_once(get_template_directory() ."/yxm/YinXiangMaLib.php");
    $yqxs_token = YinXiangMa_tokenRequest();
}

if(is_single()) {
        
        $chapter_order = get_query_var('page');
        //var_dump($post->ID,$chapter_order);
        if($chapter_order>0) {
            $result_arr = $wpdb->get_results(
            $wpdb->prepare(
                "SELECT * FROM $wpdb->chapters WHERE `post_id` =%d  AND `chapter_order`=%d ORDER BY `chapter_order` ASC",
                $post->ID,
                $chapter_order,
                ARRAY_A 
            )
        );
       
        if(count($result_arr)==0) {
            wp_die('章节不存在','章节不存在',array( 'response' => 404,'back_link'=>true ));
            exit();
        }
    }
    
}


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
date_default_timezone_set('Asia/shanghai');
?>

<!DOCTYPE html>
<html <?php language_attributes(); ?>>
    <head>
        <meta charset="<?php bloginfo('charset'); ?>" />
        <meta name="keywords" content="关键字" />
        <meta name="description" content="<?php bloginfo('description'); ?>" />
        <meta name="robots" content="index,follow">
        <meta name="googlebot" content="index,follow">
        <meta http-equiv="x-ua-compatible" content="ie=7" />
        
        <title><?php
/*
 * Print the <title> tag based on what is being viewed.
 */
global $page, $paged;

wp_title('|', true, 'right');

// Add the blog name.
bloginfo('name');

// Add the blog description for the home/front page.
$site_description = get_bloginfo('description', 'display');
if ($site_description && ( is_home() || is_front_page() ))
    echo " | $site_description";

// Add a page number if necessary:
if ($paged >= 2 || $page >= 2)
    echo ' | ' . sprintf(__('Page %s', 'twentyten'), max($paged, $page));
?></title>

        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php echo YQURI; ?>/images/all.css" />
        <link rel="stylesheet" type="text/css" media="all" href="<?php bloginfo('stylesheet_url'); ?>" />
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>" />
        <script type="text/javascript" src="/wp-content/plugins/yqxs/js/jquery-1.6.2.min.js?yqxs=1.0"></script>
        <script type="text/javascript" src="<?php echo YQURI; ?>/js/jquery.lazyload.js?yqxs=1.0"></script>
        <script type="text/javascript" src="<?php echo YQURI; ?>/js/jquery.scroll.js?yqxs=1.0"></script>
        <script type="text/javascript" src="<?php echo YQURI; ?>/js/common.js?yqxs=1.0"></script>
        <script type="text/javascript" src="<?php echo YQURI; ?>/js/Ajax_Search.js?yqxs=1.0"></script>
        <script type="text/javascript" src="<?php echo YQURI; ?>/js/history.js?yqxs=1.0"></script>
        



<?php
            /* We add some JavaScript to pages with the comment form
             * to support sites with threaded comments (when in use).
             */
            if (is_singular() && get_option('thread_comments'))
                wp_enqueue_script('comment-reply');

            /* Always have wp_head() just before the closing </head>
             * tag of your theme, or you will break many plugins, which
             * generally use this hook to add elements to <head> such
             * as styles, scripts, and meta tags.
             */
            wp_head();
            $records = parse_history();
?>
        </head>

        <body>
        
            
            <!--历史记录-->
            <div id='yqxs_filter'></div>
            <ol id="yqxs_history" style="display:none;position:absolute">
            <?php if(is_array($records) && count($records)>0) :?>
                <?php foreach($records as $book=>$chapter):?>
                <li>
                   <?php if (is_object($book_obj=get_post_by_slug($book))): ?>
                    <a href="<?php echo $chapter['url'];?>" target="_blank">《<?php echo $book_obj->post_title;?>》<?php echo get_chapter_title($book_obj->ID,$chapter['order'])?></a> 
                    <?php echo date('H:i n/d',$chapter['time'])?>
                    <?php endif?>
                </li>
                <?php endforeach?>
            <?php else:?>
                <li>暂无历史阅读记录</li>
            <?php endif?>          
            </ol>
            <!--历史记录结束-->


            <center><div class="logo">
                    <h1 id="blog_name"><?php bloginfo('name'); ?></h1>
                    <h2 id="blog_url"><?php bloginfo('url'); ?></h2>
                    <span style="float:right">
                    </span>
                    <span id="myads" style="float:right; margin-right:0px;"></span>
                </div></center>
            <div class="channel-nav">
                <ul>
                    <li class="ye"><a href="<?php echo home_url('/') ?>">首页</a></li>
                    <li><a href="<?php echo home_url('/recent') ?>">最近更新</a></li>
                    <li><a href="<?php echo home_url('/pkey') ?>" >作品索引</a></li>
                    <li><a href="<?php echo home_url('/akey') ?>" >作家索引</a></li>
                    <li><a href="/list/18.html" >情节分类</a></li>
                    
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
                        <strong><img src="<?php echo YQURI; ?>/images/btn-chrlist.gif"></strong>
                          <?php foreach (range('A','Z') AS $char) :?>
                          <a href="<?php echo akey_link($char);?>"  target="_blank"><?php echo $char?></a>
                           <?php endforeach?>

                    </div>
                    <div class="otherNav">

                        <span>
                            <a id="read_history" href="#">我的阅读记录</a>
                    
                            <a href="#" onClick="this.style.behavior='url(#default#homepage)';this.setHomePage('http://www.hltm.cc/');" onMouseOut="window.status='红旅动漫';return true;" onMouseOver="window.status='将本站设为首页';return true;" style="behavior: url(#default#homepage);" target="_self">设红旅为首页</a>
                            <a href="javascript:void(0);" onClick="window.external.AddFavorite('http://www.hltm.cc/','红旅动漫');"class="addFav">收藏红旅动漫</a>
                        </span>
                        <em id="anc_ico"><a href="<?php echo announce_list_link();?>">本站公告：</a></em>
                        <div id="scroll_div">
                        
                                <ul>
                                <?php echo get_recent_announces()?>
           
                    </ul>
                        </div>
                       

                        
                        

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
<?php if (!is_page() AND !is_single() AND !is_404()) : ?>
            <div id="1" class="ad960">
<?php
        global $wpdb;
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
                        );
                foreach ($post_arr as $post_id) {
                    $size = array(120, 160);
                    $thumb = get_the_post_thumbnail($post_id, $size);
                    if ($thumb == null)
                        $thumb = "<img src='/wp-content/uploads/covers/notimg-120x160.gif' />";
                    echo '<a href="' . get_permalink($post_id) . '">' . $thumb . '</a>';
                }
?>

            </div>
                <?php endif ?>
        </center>

