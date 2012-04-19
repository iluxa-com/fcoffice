<?php
/*
Plugin Name: Blog Shout
Plugin URI: http://www.pkphp.com/blog-shout/
Description: This plugin create shout box to easily edit.
Author: askie
Version: 0.2
Author URI: http://www.pkphp.com
*/
//基本设置
function bs_generalsetting()
{
	if ($_POST['flag']=="general") 
     {
		foreach ($_POST as $key=>$value) 
		{
			if ($key=="bs_templet") 
			{
				$ckeys=array("[title]","[body]","[date]");
				foreach ($ckeys as $tag) 
				{
					if (strstr($value,$tag)==false) 
					{
						echo '<div class="updated"><p>'.__("[title],[body],[date] must be added in templet.","BlogShout").'</p></div>';
						$failure=true;
						break 2;
					}
				}
			}
			if (strstr($key,"bs_")==$key) 
			{
				update_option($key, stripslashes($value));
			}
		}
		if ($failure<>true) 
		{
			echo '<div class="updated"><p>General setting saved!</p></div>';
		}
     }
?>	
<div class="wrap">
<table width="100%" border="0" cellpadding="3">
<tr>
<td valign="top">
		<form name="updateoption" method="post">
		<input type="hidden" name="flag" value="general">		
		<table class="form-table">	
		   <tr>
           		<th nowrap><? _e("Output:","BlogShout");?></th>
           		<td>
           		<? bs_script(); ?>
           		<div>
           		<?
					$style=stripslashes(get_option("bs_style"));
					$templet=stripslashes(get_option("bs_templet"));
					$ckeys=array("[title]","[body]","[date]");
					$cvalue=array(get_option("bs_widget_title"),get_option("bs_widget_text"),get_option("bs_widget_mdate"));
					$templet=str_replace($ckeys,$cvalue,$templet);
					echo $style;
					echo $templet;
				?>
				</div>
           		</td>
			</tr>
			<tr>
           		<th nowrap><? _e("Output templet:","BlogShout");?></th>
           		<td>
           		<?
					$templet=stripslashes(get_option("bs_templet"));
				?>
           		<textarea name="bs_templet" style="width:400px;height:300px;"><?=$templet?></textarea><br>
				[title],[body],[date] must be added in.
           		</td>
			</tr>
			<tr>
           		<th nowrap><? _e("CSS style:","BlogShout");?></th>
           		<td>
           		<?
					$style=stripslashes(get_option("bs_style"));
				?>
           		<textarea name="bs_style" style="width:400px;height:300px;"><?=$style?></textarea>
           		</td>
			</tr>		
		</table>
	<p><div class="submit"><input type="submit" name="update_rp" value="<?php _e('Save!', 'update_rp') ?>"  style="font-weight:bold;" /></div></p>
	</form> 		
</td>
<td valign="top" width="250">

</td>
</tr>
</table>	
</div>
<?php 
}
//输出内容
function bs_out()
{
	$title = get_option('bs_widget_title');
	$text = get_option('bs_widget_text');
	$templet=stripslashes(get_option("bs_templet"));
	$ckeys=array("[title]","[body]","[date]");
	$cvalue=array(get_option("bs_widget_title"),get_option("bs_widget_text"),get_option("bs_widget_mdate"));
	$templet=str_replace($ckeys,$cvalue,$templet);
	return $templet;				
}
//widget
function widget_sidebar_blogshout() 
{
	function widget_blogshout($args) 
	{
	    extract($args);
		echo $before_widget;
		echo bs_out();
		echo $after_widget;
	}
	register_sidebar_widget('BlogShout', 'widget_blogshout');
	
	//widget 选项
	function widget_blogshout_options() 
	{
		//如果提交更新
		if ( $_POST["bs_submit"] ) 
		{ 	
			foreach ($_POST as $key=>$var) 
			{
				if (strstr($key,"bs_widget_")==$key) 
				{
					update_option($key,$var);
				}
			}
		}
		$title = attribute_escape(get_option('bs_widget_title'));
		$text = attribute_escape(get_option('bs_widget_text'));
		if ( empty($title) ) $title = 'Attention:';
	?>
		<p><label>
		<?php _e('Title:'); ?> <input class="widefat" id="bs_widget_title" name="bs_widget_title" type="text" value="<?php echo $title; ?>" />
		</label></p>
		<p><? _e("Text:","BlogShout")?><br>
		<textarea class="widefat" name="bs_widget_text"><?=$text?></textarea>
		</p>
		<input type="hidden" id="bs_submit" name="bs_submit" value="1" />
	<?php
	}
	register_widget_control('BlogShout', 'widget_blogshout_options', 200, 90);
	register_sidebar_widget('BlogShout', 'widget_blogshout');
}
add_action('plugins_loaded', 'widget_sidebar_blogshout');
function bs_init()
{
	if ($_GET["bscmd"]=="savetitle") 
	{
		$title=stripslashes($_POST["update_value"]);
		update_option("bs_widget_title",$title);
		echo $title;
		exit();
	}
	if ($_GET["bscmd"]=="savetext") 
	{
		$text=stripslashes($_POST["update_value"]);
		update_option("bs_widget_text",$text);		
		echo $text;
		exit();
	}
	$ab_config=array(
		"bs_widget_mdate"	=>date("Y-m-d"),
		"bs_style"			=>'<style type="text/css">

.chamfer {background: transparent; width:98%; margin:0 auto;}
.chamfer h1, .chamfer p {margin:0 10px;}
.chamfer h1 {font-size:1em; color:#000; letter-spacing:1px;}
.chamfer p {padding-bottom:0.5em;font-size:1em; color:#000; letter-spacing:1px;}

.chamfer .top, .chamfer .bottom {display:block; background:transparent; font-size:1px;}
.chamfer .b1, .chamfer .b2, .chamfer .b3, .chamfer .b4, .chamfer .b5 {display:block; overflow:hidden; height:1px; background:#eca; border-left:1px solid #000; border-right:1px solid #000;}
.chamfer .b1 {margin:0 5px; background:#000;}
.chamfer .b2 {margin:0 4px;}
.chamfer .b3 {margin:0 3px;}
.chamfer .b4 {margin:0 2px;}
.chamfer .b5 {margin:0 1px;}

.chamfer .boxcontent {display:block; background:#eca; border-left:1px solid #000; border-right:1px solid #000;}

</style>',
		"bs_templet"		=>'<div class="chamfer">
<b class="top"><b class="b1"></b><b class="b2"></b><b class="b3"></b><b class="b4"></b><b class="b5"></b></b>
<div class="boxcontent">
<h1  id="shouttitle">[title]</h1>
<p id="shouttext">[body]</p>
<p align="right">[date]</p>
</div>
<b class="bottom"><b class="b5"></b><b class="b4"></b><b class="b3"></b><b class="b2"></b><b class="b1"></b></b>
</div>',
	);
	foreach ($ab_config as $key=>$var) 
	{
		if (get_option($key)=="") 
		{
			update_option($key,$var);
		}
	}
}
add_action('init', 'bs_init');
function bs_head()
{
	//输出css
	echo stripslashes(get_option("bs_style"));
		
	$current_user = wp_get_current_user();
	if (!in_array("administrator",$current_user->roles)) 
	{
		return ;
	}
	if (is_feed()) 
	{
		return;
	}
	bs_script();
}
function bs_script()
{
	?>
<script type="text/javascript" src="<?=get_option("siteurl")?>/wp-content/plugins/blog-shout/jquery.js"></script>
<script type='text/javascript' src='<?=get_option("siteurl")?>/wp-content/plugins/blog-shout/jquery.inplace.js'></script>
<script type="text/javascript">
            $(document).ready(function(){
                $("#shouttitle").editInPlace({
                    url: "<?=get_option("siteurl")?>/index.php?bscmd=savetitle",
                    params: "ajax=yes"
                });

                $("#shouttext").editInPlace({
                    url: "<?=get_option("siteurl")?>/index.php?bscmd=savetext",
                    params: "ajax=yes",
                    bg_over: "#cff",
                    field_type: "textarea",
                    textarea_rows: "15",
                    textarea_cols: "25",
                    saving_image: "<?=get_option("siteurl")?>/wp-content/plugins/blog-shout/ajax-loader.gif"
                });
            });
        </script>
	<?
}
add_filter('wp_head', 'bs_head');
//加入菜单
add_action('admin_menu', 'bs_admin_menu');
function bs_admin_menu() 
{
	if (function_exists('add_options_page')) 
	{ 
		add_options_page('BlogShout', 'BlogShout', 8, basename(__FILE__), 'bs_generalsetting');
	}
}
?>