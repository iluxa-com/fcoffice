<?php
/**
 * Template Name: post_stars_key
 *
 * A custom page use to display the recently posts 
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */


get_header(); 
define('LAZY_LOAD',TRUE); //使用图片懒加载
$pkey = strtoupper(get_query_var('pkey'));

?>
<?php //文章列表
     $paged = get_query_var('paged');
    $page =get_query_var('page') ;
    $paged = ($paged >= $page) ? $paged :$page;
    $paged = ($paged<1) ? 1 :$paged;
    query_posts("meta_key=psw&meta_value=$pkey&paged=$paged&&orderby=modified&order=DESC");


?>
<div id="main">
	<div class="left">
		<div class="index_hot">
			<h1><b>Key</b> <a href='/'>首页</a>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;
            <?php if(!empty($pkey)):?>按字母
            <?php echo $pkey?>开头的最新小说
            (<?php echo $wp_query->found_posts;?>篇)
            <?php else:?>
            按作品开头字母检索
            <?php endif?>
            
            </h1>

      <div class="listBox">
        <div class="tb" style="clear:both;margin-top:20px">
                    <ul class="info_list">
                    <?php foreach (range('A','Z') AS $char) :?>
                    <li style="height: 25px;width:48px;background:none"><a class="pkey_btn" href="<?php echo pkey_link($char);?>"><?php echo $char?></a></li>
                    <?php endforeach;?>
                    </ul>
            <div class="clear"></div>
         </div>
        <ul>

         <?php if (have_posts() && $pkey!='' ) : ?>

            <?php while (have_posts()) : the_post(); ?>
            <li class='novel_news_style_form'>
            <div class="novel_news_style_img">
                <a target="_blank" href="<?php the_permalink() ?>">
                    <?php if ( has_post_thumbnail() ):?>
                    <?php 
                                    //<img src="img/grey.gif" data-original="img/bmw_m1_hood.jpg" width="765" height="574" alt="BMW M1 Hood">
                                    
                                        echo get_the_post_thumbnail($post->ID, array(120,160), array('class' => 'yqxs_tab')); 
                                     ?>
                    <?php endif?>                                                     
                </a>
            </div>
            <div class="novel_news_style_text">
                        <h3><a class='title_link' target="_blank" href="<?php the_permalink()?>"><?php the_title()?></a></h3>
                         <ul class='list_info_1'><li class='terms'>作 者：<a target="_blank" title="点击查看<?php the_author()?>作品集" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author()?></a></li>
                        <li class='terms'>分类:<?php echo get_the_category_list(', ');?></li>

                        <li class='terms'>发布日期:<?php the_time('Y-m-d')?></li>
                        </ul>
                        
                        <p>   
                        <?php 
                                            if ( !empty( $post->post_excerpt ) ){
                                                $excerpt = $post->post_excerpt;
                                            }elseif(!empty($post->content)) {
                                                $excerpt = $post->content;
                                            }else{
                                                $excerpt = '暂无描述';
                                            }
                                            $excerpt = str_replace(array('　　',"&nbsp;"),'',$excerpt);
                                            echo str_repeat("&nbsp",8) . mb_substr(strip_tags(trim($excerpt)),0,200).'......';
                        
                                            ?>
                        
                          
                        
                        
                        <?php
                        //$str =
    //echo mb_substr($str,0,200).'......';
?>
                        <a class='read_icon' target="_blank" href="<?php the_permalink()?>">立即阅读</a></p>
                        <ul class='list_info'>
                        <li class='terms'>背景:
                            <?php $meta_link = yqxs_get_meta_links($post->ID,'background');?>
                            <?php if ($meta_link !==NULL):?>                            
                            <?php echo $meta_link;?>
                            <?php endif?>
                       </li>                        
                        <li class='terms'>出版社:
                            <?php $meta_link = yqxs_get_meta_links($post->ID,'publisher');?>
                            <?php if ($meta_link !==NULL):?>                            
                            <?php echo $meta_link;?>
                            <?php endif?>
                       </li>
                        <li class='terms'>出版日期:
                            <?php $meta_link = yqxs_get_meta_links($post->ID,'pub_date');?>
                            <?php if ($meta_link !==NULL):?>                            
                            <?php echo $meta_link;?>
                            <?php endif?>
                        </li>
                        <li class='terms'>阅读指数:                            
                            <?php $meta_link = yqxs_get_meta_links($post->ID,'stars');?>
                            <?php if ($meta_link !==NULL):?>                            
                            <?php echo $meta_link;?>
                            <?php endif?></li>
                         <li class='terms'>下载次数:500</li>
                        </ul>
                    </div>
                    
        </li>
            
            <?php endwhile ?>
            
        
        
        
        
          
        </ul>
        <?php yqxs_page_numbers();?>
        <?php else:?>
            
        <?php endif ?>

        <div class="blank_4px"></div>
       
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
