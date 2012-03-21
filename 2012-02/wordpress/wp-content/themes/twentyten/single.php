<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
<?php $current_page = get_query_var('page'); ?>
<div id="main">
	<div class="left" <?php if($current_page>0): ?>id='chapter_left'<?php endif?>>
		<div class="index_hot" <?php if($current_page>0): ?>id='chapter_hot'<?php endif?>>
			<h1 <?php if($current_page>0): ?>id='chapter_head_bar'<?php endif?>><b>Read</b> <a href='/'>首页</a>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;<?php the_category(' | '); ?>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;<a href="<?php the_permalink()?>" title="<?php the_title()?>"><?php the_title()?></a> <?php if($current_page>0): ?>&nbsp;&nbsp;&raquo;&nbsp;&nbsp; <?php the_chapter_title()?><?php endif?></h1>

      <div class="listBox" <?php if($current_page>0): ?>id='chapter_list_box'<?php endif?>>
      
     <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
     <?php if($current_page<1): ?>
     <?php if ( has_post_thumbnail() ):?>
     <?php 
        echo get_the_post_thumbnail($post->ID, array(150,240), array('class' => 'yqxs_thumb_single')); ?>
     <?php endif?> 
    <div class="yqxs_excerpt_single">
      <h2 id="single_title">《<?php the_title(); ?>》</h2>
         <ul class='list_info_1'><li class='terms' style="margin:0 10px 0 0">作 者：<a target="_blank" title="点击查看<?php the_author()?>作品集" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author()?></a></li>
        

        <li class='terms'>发布日期：<?php the_time('Y-m-d')?></li>
        <li class='terms'>下载/阅读次数：
        <?php if(function_exists('the_down_time')) the_down_time();?>/
        <?php if(function_exists('the_read_time')) the_read_time();?>
         </li>
        </ul>      
      <div id="single_excerpt">
      <?php 
                    if ( !empty( $post->post_excerpt ) ){
                        $excerpt = $post->post_excerpt;
                    }elseif(!empty($post->content)) {
                        $excerpt = $post->content;
                    }else{
                        $excerpt = '暂无描述';
                    }
                    $excerpt = str_replace(array('　　',"&nbsp;"),'',$excerpt);
                    $excerpt = preg_replace("#<br />\s+?<br />#",'<br />',$excerpt);
                    echo   mb_substr(trim($excerpt),0,500);

                    ?>
      </div>
    </div>  
  
      <div class="clear"></div>
      <div class = "yqxs_info">
         <h2 class="info_header">相关信息 </h2>
      <div class="tb">
                    <ul class="info_list">
                    <li>出版社：
                            <?php $meta_link = yqxs_get_meta_links($post->ID,'publisher');?>
                            <?php if ($meta_link !==NULL):?>                            
                            <?php echo $meta_link;?>
                            <?php endif?>                    
                    </li>
                    <li>出版日期：
                            <?php $meta_link = yqxs_get_meta_links($post->ID,'pub_date');?>
                            <?php if ($meta_link !==NULL):?>                            
                            <?php echo $meta_link;?>
                            <?php endif?>                    
                    </li>
                    <li>小说系列：        
                            <?php $meta_link = yqxs_get_meta_links($post->ID,'nov_series');?>
                            <?php if ($meta_link !==NULL):?>                            
                            <?php echo $meta_link;?>
                            <?php endif?> 
                    </li>
                    <li>系 列：
                            <?php $meta_link = yqxs_get_meta_links($post->ID,'series');?>
                            <?php if ($meta_link !==NULL):?>                            
                            <?php echo $meta_link;?>
                            <?php endif?> 
                    </li>
                    <li>时代背景：
                            <?php $meta_link = yqxs_get_meta_links($post->ID,'background');?>
                            <?php if ($meta_link !==NULL):?>                            
                            <?php echo $meta_link;?>
                            <?php endif?> 
                    </li> 
                    <li>故事地点：
                            <?php $meta_link = yqxs_get_meta_links($post->ID,'place');?>
                            <?php if(is_array($meta_link)):?>
                            <?php foreach($meta_link as $link):?>
                               <?php echo $link?>&nbsp;&nbsp; 
                            <?php endforeach?>   
                            <?php elseif ($meta_link !==NULL):?>                            
                            <?php echo $meta_link;?>
                            <?php endif?> 
                    <li>男主角：
                            <?php $meta_link = yqxs_get_meta_links($post->ID,'male');?>
                            <?php if(is_array($meta_link)):?>
                            <?php foreach($meta_link as $link):?>
                               <?php echo $link?>&nbsp;&nbsp; 
                            <?php endforeach?>   
                            <?php elseif ($meta_link !==NULL):?>                            
                            <?php echo $meta_link;?>
                            <?php endif?> 
                    
                    </li>
                    <li>女主角：
                            <?php $meta_link = yqxs_get_meta_links($post->ID,'female');?>
                            <?php if(is_array($meta_link)):?>
                            <?php foreach($meta_link as $link):?>
                               <?php echo $link?>&nbsp;&nbsp; 
                            <?php endforeach?>   
                            <?php elseif ($meta_link !==NULL):?>                            
                            <?php echo $meta_link;?>
                            <?php endif?> 
                    </li>
                    <li>其他人物：
                            <?php $meta_link = yqxs_get_meta_links($post->ID,'others');?>
                            <?php if(is_array($meta_link)):?>
                            <?php foreach($meta_link as $link):?>
                               <?php echo $link?>&nbsp;&nbsp; 
                            <?php endforeach?>   
                            <?php elseif ($meta_link !==NULL):?>                            
                            <?php echo $meta_link;?>
                            <?php endif?>                     
                    </li>
                    
                    
                  
                    <li>阅读指数：
                            <?php $meta_link = yqxs_get_meta_links($post->ID,'stars');?>
                            <?php if ($meta_link !==NULL):?>                            
                            <?php echo $meta_link;?>
                            <?php endif?> 
                    </li>
                     <li style="width:420px">情节分类：
                    <?php the_category(' , '); ?>
                    </li>
                    
                    </ul>
            <div class="clear"></div>                        
      </div>
       <h2 class="info_header">章节列表 </h2>
      <div class="tb">
                    <ul class="info_list" id="chapter_list">
                    <?php the_content()?>
                    </ul>
            <div class="clear"></div>                        
      </div>
      <?php if($post->post_content =='[cai-ji-ok]'):?>
        <div style="margin-top:20px;border: 1px solid #CCCCCC">
            <h2 class="sbar" style="margin:0;padding:0">
                
                <strong style="width:100%">发表评论 </strong>
            </h2>
            <ul style="margin-left:20px;height:210px;" class="chrComicList"> 
              <?php the_content()?>
            </ul>
        </div>   
        <?php endif;?>
                    
      </div>
      <?php else :?>
    
      <h1 id="yqxs_title_chapter">
      
      <a href="<?php the_permalink()?>" title="<?php the_title()?>">
      <?php the_title(); ?></a> - <?php the_chapter_title()?>
      
      </h1>
     <ul class='list_info_1' style="text-align:center"><li class='terms' style="margin:0 10px 0 0">作 者：<a target="_blank" title="点击查看<?php the_author()?>作品集" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author()?></a></li>
    

    <li class='terms'>发布日期：<?php the_time('Y-m-d')?></li>
    <li class='terms'>下载/阅读次数：
        <?php if(function_exists('the_down_time')) the_down_time();?>/
        <?php if(function_exists('the_read_time')) the_read_time();?></li>
    </ul> 
      <?php the_content()?>
      <?php endif?>
      <?php endwhile;?>
      <?php endif;?>

        
        <div class="blank_4px"></div>
       
		</div></div>
         
		<div class="line_l_bon"<?php if($current_page>0) :?>id="chapter_line_bon"<?php endif;?>>
        </div>
	</div>
    
    <?php if($current_page<1) :?>
	<div class="right">
	<div class="index_rank">
	<h1><b>Time</b> 最新小说</h1>
	<ul>
	
    <?php echo yqxs_get_recent_posts2(50);?>
    
		</ul>
	</div>
	
	<div class="line_r_bon"></div>
	</div>
    <?php endif;?>
	<div class="clear"></div>



 


</div>

<?php get_footer(); ?>