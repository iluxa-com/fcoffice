<?php if (have_posts()) : while (have_posts()) : the_post(); ?>
<div id="main">
	<div class="left" id='chapter_left'>
		<div class="index_hot" id='chapter_hot'>
			 <h1 id='chapter_head_bar'><b>Announce</b> <a href='/'>首页</a>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;<a href='<?php echo announce_list_link();?>'>本站公告</a>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;<a href="<?php the_permalink()?>" title="<?php the_title()?>"><?php the_title()?></a> 
                </h1>

      <div class="listBox" id='chapter_list_box'>
      
          
         
      <h1 id="yqxs_title_chapter" style="margin-top:20px;">
      
      <a href="<?php the_permalink()?>" title="<?php the_title()?>">
      <?php the_title()?></a>
      
      </h1>
      
     <ul class='list_info_1' style="text-align:center">
     <!--
     <li class='terms' style="margin:0 10px 0 0">作 者：<a target="_blank" title="点击查看<?php the_author()?>作品集" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author()?></a></li>
        
        <li class='terms'>发布日期：<?php the_time('Y-m-d')?></li>
        -->
        <li class='terms'>阅读次数：        
        <?php if(function_exists('the_read_time')) the_read_time();?>
         </li>        
    </ul> 
    
      <h2 class="yqxs_ch_title"><?php the_title();?></h2>
<div id="yqxs_ch_content" style="margin:auto;width:90%;">
<?php the_content();?>
<div style="text-align:right;"><?php the_author()?></div>
<div style="text-align:right"><?php the_time('Y-m-d')?></div>
</div>
<div class="yqxs_dir_nav1"><a href="http://story99.sinaapp.com/doumanwang-zhuyinghui.html"> 【目录页】 </a><a id="yqxs_next" href="http://story99.sinaapp.com/doumanwang-zhuyinghui-2.html">【下一章】</a></div>
                  
        
        <div class="blank_4px"></div>
       
		</div></div>
         
		<div class="line_l_bon"id="chapter_line_bon">
        </div>
	</div>

    
    	<div class="clear"></div>




</div>
<?php endwhile;?>
<?php endif?>

<?php get_footer(); ?>
