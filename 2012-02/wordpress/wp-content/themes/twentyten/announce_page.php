<?php
/**
 * Template Name: announce, no sidebar
 *
 * A custom page template for announces display without sidebar.
 *
 *
 * @package YQXS
 * @subpackage YQXS
 * @since yqxs 1.0
 */
 get_header(); ?>
 <?php query_posts("paged=$paged&posts_per_page=10&&orderby=modified&order=DESC&post_type=announce"); ?>
 
 
 

<div id="main">
	<div class="left" id='chapter_left' style="margin:auto;width:980px;">
		<div class="index_hot" id='chapter_hot'>
			 <h1 id='chapter_head_bar'><b>Announce</b> <a href='/'>首页</a>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;<a href="<?php echo announce_list_link();?>">本站公告</a>
                </h1>
                <div class="tb" style="border:none;margin-bottom:0">
                
                <ul class="yqxs_ch_list" style="display: block;float: none;">
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
      

      <li class="announce_li" style="float:none;width:100%;margin:0 0px;">
      
      <a href="<?php the_permalink()?>" title="<?php the_title()?>">
      <?php the_title()?>
      
      ( 日期:<?php the_time('Y-m-d H:i')?> 
      阅读数:<?php if(function_exists('the_read_time')) the_read_time();?>
      )
      </a>
      </li>
      
        
                  
      
<?php endwhile;?>
<?php endif?>    

</ul>
<div class="clear"></div>
</div>    
<div class="clear"></div>         
		</div>
        
        </div>
         
		<div class="line_l_bon"id="chapter_line_bon">
        </div>
	

    
    	<div class="clear"></div>




</div>


<?php get_footer(); ?>
