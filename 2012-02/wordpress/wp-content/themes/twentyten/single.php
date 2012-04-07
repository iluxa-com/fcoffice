<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */
reading_cookie(10);

get_header();


?>

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
      <h2 id="single_title"><?php the_title(); ?></h2>
         <ul class='list_info_1'><li class='terms' style="margin:0 10px 0 0">作 者：<a target="_blank" title="点击查看<?php the_author()?>作品集" href="<?php echo get_author_posts_url( get_the_author_meta( 'ID' ) ); ?>"><?php the_author()?></a></li>
        

        <li class='terms'>发布日期：<?php the_time('Y-m-d')?></li>
        <li class='terms'>下载/阅读次数：
        <?php if(function_exists('the_down_time')) the_down_time();?>/
        <?php if(function_exists('the_read_time')) the_read_time();?>
         </li>
         <li class='terms'><?php the_down_link();?></li>
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
                    echo   $excerpt = mb_substr(trim($excerpt),0,500);

                    ?>
                    
      </div>
  
    </div>  
       
      <div class="clear"></div>
           <!--share begin-->
<div style='margin-left:200px'>
      <div style="padding:10px 5px; width:420px; _height:1%;" class="tb">
                    <!--<div class="friendtitle">分享是一种美德</div>-->

<script type="text/javascript">
var txt = $('#single_excerpt').text().substr(0,100);
function postToQQWb(){ 
    /*
    //var  txt ="<?php echo htmlspecialchars(trim(strip_tags($excerpt)));?>";
    //var txt = $('#single_excerpt').text();
    */
    //var txt = '什么情况啊我擦'
    var _site = '<?php bloginfo('url')?>'; 
    var txt = $('#single_excerpt').text().substr(0,100);
    var _t = encodeURI(document.title+location.href)  ;
    
    var _url = encodeURI(document.location); 
    var _appkey = encodeURI("f70be5f8d64a40e691d77b10e7c1eacd"); 
    var _pic = encodeURI($('.yqxs_thumb_single').attr('src')); 
    
    var _u = 'http://v.t.qq.com/share/share.php?title='+_t+'&amp;url='+_url+'&amp;appkey='+_appkey+'&amp;site='+_site+'&amp;pic='+_pic; 
    window.open( _u,'转播到腾讯微博', 'width=700, height=680, top=0, left=0, toolbar=no, menubar=no, scrollbars=no, location=yes, resizable=no, status=no' ); 
     
} 
</script> 

                    <div class="friendmain" style="width:auto;">
                        <div class="friendtitle" style="float:left">分享是一种美德</div>
                        <a title="分享到新浪微博" href="javascript:(function(){window.open('http://v.t.sina.com.cn/share/share.php?title='+encodeURIComponent('好书分享:'+txt+document.title)+'&amp;url='+encodeURIComponent(location.href)+'&amp;source='+encodeURIComponent('<?php bloginfo('name')?>')+'&amp;sourceUrl='+encodeURIComponent('<?php bloginfo('url')?>')+'','_blank','width=450,height=400');})()" rel="nofollow"><div style="background:url(/wp-content/themes/yq2012/images/weibo.gif) no-repeat 0px 0px;" class="weibo"></div></a>
                        <a title="分享到QQ空间" href="javascript:(function(){var intro='&lt;P&gt;<?php echo strip_tags($excerpt);?>';for(var i=0;i&lt;10;i++){intro=intro.replace('&lt;P&gt;','').replace('&lt;/P&gt;','').replace('&lt;p&gt;','').replace('&lt;/p&gt;','')} window.open('http://sns.qzone.qq.com/cgi-bin/qzshare/cgi_qzshare_onekey?url='+ encodeURIComponent(location.href)+ '&amp;site='+ encodeURIComponent('<?php bloginfo('name')?>')+ '&amp;pics='+ encodeURIComponent($('.yqxs_thumb_single').attr('src'))+ '&amp;summary='+ encodeURIComponent(intro.substring(0, 100) + '...')+ '&amp;title='+encodeURIComponent(document.title),'_blank');})()" rel="nofollow"><div style="background:url(/wp-content/themes/yq2012/images/weibo.gif) no-repeat 0px -64px;" class="weibo"></div></a>
                        <a title="分享到腾迅微博" onclick="postToQQWb();" href="javascript:void(0)" rel="nofollow"><div style="background:url(/wp-content/themes/yq2012/images/weibo.gif) no-repeat 0px -32px;" class="weibo"></div></a>
                        <a title="分享到豆瓣" href="javascript:void(function(){var%20d=document,e=encodeURIComponent,s1=window.getSelection,s2=d.getSelection,s3=d.selection,s=s1?s1():s2?s2():s3?s3.createRange().text:'',r='http://www.douban.com/recommend/?url='+e(d.location.href)+'&amp;title='+e(d.title)+'&amp;sel='+e(s)+'&amp;v=1',x=function(){if(!window.open(r,'douban','toolbar=0,resizable=1,scrollbars=yes,status=1,width=450,height=330'))location.href=r+'&amp;r=1'};if(/Firefox/.test(navigator.userAgent)){setTimeout(x,0)}else{x()}})()" rel="nofollow"><div style="background:url(/wp-content/themes/yq2012/images/weibo.gif) no-repeat 0px -96px;" class="weibo"></div></a>
                        <a title="分享到搜狐微博" href="javascript:void((function(s,d,e,r,l,p,t,z,c){var f='http://t.sohu.com/third/post.jsp?',u=z||d.location,p=['&amp;url=',e(u),'&amp;title=',e(t||d.title),'&amp;content=',c||'gb2312','&amp;pic=',e(p||'')].join('');function%20a(){if(!window.open([f,p].join(''),'mb',['toolbar=0,status=0,resizable=1,width=660,height=470,left=',(s.width-660)/2,',top=',(s.height-470)/2].join('')))u.href=[f,p].join('');};if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else%20a();})(screen,document,encodeURIComponent,'','','','','','utf-8'));" rel="nofollow"><div style="background:url(/wp-content/themes/yq2012/images/weibo.gif) no-repeat 0px -128px;" class="weibo"></div></a>
                        <a title="分享到网易微博" href="javascript:(function(){window.open('http://t.163.com/article/user/checkLogin.do?link=http://news.163.com/&amp;source='+encodeURIComponent('<?php bloginfo('name')?>')+ '&amp;info='+encodeURIComponent(document.title)+' '+encodeURIComponent(location.href),'_blank','width=510,height=300');})()" rel="nofollow"><div style="background:url(/wp-content/themes/yq2012/images/weibo.gif) no-repeat 0px -160px;" class="weibo"></div></a>
                        <a title="分享到开心网" href="javascript:d=document;t=d.selection?(d.selection.type!='None'?d.selection.createRange().text:''):(d.getSelection?d.getSelection():'');void(kaixin=window.open('http://www.kaixin001.com/~repaste/repaste.php?&amp;rurl='+escape(d.location.href)+'&amp;rtitle='+escape(d.title)+'&amp;rcontent='+escape(d.title),'kaixin'));kaixin.focus();" rel="nofollow"><div style="background:url(/wp-content/themes/yq2012/images/weibo.gif) no-repeat 0px -192px;" class="weibo"></div></a>
                        <a title="分享到人人网" href="javascript:void((function(s,d,e){if(/renren\.com/.test(d.location))return;var%20f='http://share.renren.com/share/buttonshare.do?link=',u=d.location,l=d.title,p=[e(u),'&amp;title=',e(l)].join('');function%20a(){if(!window.open([f,p].join(''),'xnshare',['toolbar=0,status=0,resizable=1,width=626,height=436,left=',(s.width-626)/2,',top=',(s.height-436)/2].join('')))u.href=[f,p].join('');};if(/Firefox/.test(navigator.userAgent))setTimeout(a,0);else%20a();})(screen,document,encodeURIComponent));" rel="nofollow"><div style="background:url(/wp-content/themes/yq2012/images/weibo.gif) no-repeat 0px -224px;" class="weibo"></div></a>
                    </div>
                    <div class="clear"></div>
                </div>
      </div>
<!--share end-->   
      <div class = "yqxs_info">
         <h2 class="info_header">作品信息 </h2>
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
                    
                    <?php the_content()?>
                    
            <div class="clear"></div>                        
      </div>
<!--
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
      -->
      <?php comments_template(); ?>
      </div>
      <?php else :?>
    
      <h1 id="yqxs_title_chapter">
      
      <a href="<?php echo esc_attr($_SERVER['REQUEST_URI']);?>" title="<?php the_title()?>">
      <?php the_title(); ?> - <?php the_chapter_title()?></a>
      
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