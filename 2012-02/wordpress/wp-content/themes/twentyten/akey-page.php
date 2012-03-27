<?php
/**
 * Template Name:author_stars_key
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
define('LAZY_LOAD', TRUE); //使用图片懒加载
//var_dump(get_query_var('akey'),get_query_var('page'));
$akey = strtoupper(get_query_var('akey'));
if (!empty($akey)) {
    $all_users = yqxs_get_users_by_char($akey, 100);
    $count = count($all_users);
}
?>

<div id="main">
    <div class="left">
        <div class="index_hot">
            <h1><b>Char</b> <a href='/'>首页</a>&nbsp;&nbsp;&raquo;&nbsp;&nbsp;
<?php if (!empty($akey)): ?>
                    姓名拼音以 <?php echo $akey ?> 开头的作家
                    (<?php echo $count; ?>位)
                <?php else: ?>
                    作家姓名拼音索引
<?php endif ?>

                </h1>

                <div class="listBox">

                        <div class="tb">
<?php if ($count > 0): ?>
                                <ul class="info_list">

                    <?php $rest_width = (5 - $count % 4 ) * 155; ?>
<?php foreach ($all_users as $key => $user) : ?>
<?php if ($count % 4 === 0 OR $key + 1 != $count): ?>
                                        <li style="width:140px">
                        <?php else: ?>
                                        <li style="width:<?php echo $rest_width ?>px;">
<?php endif ?>
                            <?php echo ' <a class="author_link" href="' . get_author_posts_url($user->ID) . '">' . $user->display_name . '</a></li>'; ?>
<?php endforeach ?>

                                </ul>
                            <?php else: ?>
                                    <?php if (!empty($akey)):?>
                                    没有找到以姓名拼音以 【<?php echo $akey; ?>】开头的作家。
                                    <?php else:?>
                                    请点击以下字母开始查找
                                    <?php endif;?>
<?php endif ?>

                                            <div class="clear"></div>
                                        </div>
                                        <div class="tb" style="clear:both;margin-top:20px">
                                            <ul class="info_list">
<?php foreach (range('A', 'Z') AS $char) : ?>
                                                    <li style="height: 25px;width:48px;background:none"><a class="pkey_btn" href="<?php echo akey_link($char); ?>"><?php echo $char ?></a></li>
<?php endforeach; ?>
                                                </ul>
                                                <div class="clear"></div>
                                            </div>





                                            <div class="blank_4px"></div>

                                        </div></div>

                                    <div class="line_l_bon"></div>
                                </div>
                                <div class="right">
                                    <div class="index_rank">
                                        <h1><b>Time</b> 最新小说</h1>
                                        <ul>

<?php echo yqxs_get_recent_posts2(50); ?>

                                    </ul>
                                </div>

                                <div class="line_r_bon"></div>
                            </div>
                            <div class="clear"></div>






                        </div>

<?php get_footer(); ?>
