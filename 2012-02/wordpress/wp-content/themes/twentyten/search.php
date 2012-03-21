<?php
/**
 * Template Name: index00
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
//$tag_name = get_query_var('tag');

/*
$cat_obj = get_category_by_slug($tag_name); 
if(!is_object($cat_obj)) $cat_obj =new stdClass;
*/
?>

<div id="main">
	<div class="left">
		<div class="index_hot">
			<h1><b>Search</b> <a href=""<?php echo home_url('/')?>">首页</a>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;<span style="color:#B90103;">搜索到与"<?php the_search_query(); ?>"相关的作品（<?php echo $wp_query->found_posts?>篇）</span></h1>

      <div class="listBox">

        <ul>
        <?php //文章列表
                    //var_dump(get_query_var('page'));
                    //var_dump(get_query_var('paged'));
                    /*
             $paged = get_query_var('paged');
            $page =get_query_var('page') ;
            $paged = ($paged >= $page) ? $paged :$page;
            $paged = ($paged<1) ? 1 :$paged;
            
            query_posts("paged=$paged&posts_per_page=20&&tag=$tag&orderby=modified&order=DESC"); 

        */
                ?>
         <?php if (have_posts()) : ?>

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
                        <li class='terms'>分类:<?php echo yqxs_get_the_category_list($cat_obj->term_id,', ');?></li>

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
                                            $excerpt = str_repeat("&nbsp",8) . mb_substr(strip_tags(trim($excerpt)),0,200).'......';
                                            echo $excerpt = strip_tags(high_search_excerpt($excerpt),'<b>');
                        
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
            
        
        <?php endif ?>
        
        
          
        </ul>
        <?php yqxs_page_numbers();?>
        
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
