<?php get_header(); ?>

<div id="main">
	<div class="left">
		<div class="index_hot" style="  background: none repeat scroll 0 0 #F6F6F6;">
			<h1><span style=" float: left;display:block-inline;color#B90103;font-size:14px"><b>404</b><b><a href=<?php echo home_url('/');?>>首页</a></b>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;<b style="font-size:14px;">出错了哦，亲，再找找其他小说?</b></span>
            <span style=" float: left;display:block-inline;margin-left:50px;"><?php get_search_form(); ?></span>
            </h1>

      <div class="listBox" style="text-align: center;">
     

      
      <img src="<?php echo YQURI;?>/images/404.gif" style="margin-bottom:15px"/>
      <p></p>
           
        <div class="blank_4px"></div>
       
		</div>
        </div>
         
		<div class="line_l_bon">
        
        </div>
        <div>这里放其他列表(待决)</div>
	</div>
    <div class="right">
	<div class="index_rank">
	<h1><b>Time</b> 最新小说</h1>
	<ul>
	
    <?php echo yqxs_get_recent_posts2(18);?>
    
		</ul>
	</div>
	
	<div class="line_r_bon"></div>
	</div>




 


</div>

	<script type="text/javascript">
		// focus on search field after it has loaded
		document.getElementById('s') && document.getElementById('s').focus();
	</script>
<?php get_footer(); ?>
