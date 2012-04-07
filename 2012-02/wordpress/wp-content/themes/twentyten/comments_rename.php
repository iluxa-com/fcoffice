<?php
/**
 * @author:falcon_chen@qq.com
 * @date: 2012-3-31
 * @description:评论页
 */
?>
<div style="margin-top:20px;border: 1px solid #CCCCCC">
<?php if ( post_password_required() ) : ?>
				<p class="nopassword"><?php _e( 'This post is password protected. Enter the password to view any comments.', 'twentyten' ); ?></p>
			</div><!-- #comments -->
<?php
		/* Stop the rest of comments.php from being processed,
		 * but don't kill the script entirely -- we still have
		 * to fully load the template.
		 */
		return;
	endif;
?>

<?php
	// You can start editing here -- including this comment!
/*
 *       <?php if($post->post_content =='[cai-ji-ok]'):?>
        <div style="margin-top:20px;border: 1px solid #CCCCCC">
            <h2 class="sbar" style="margin:0;padding:0">

                <strong style="width:100%">发表评论 </strong>
            </h2>
            <ul style="margin-left:20px;height:210px;" class="chrComicList">
              <?php the_content()?>
            </ul>
        </div>
        <?php endif;?>
 */
?>

<?php if ( have_comments() ) : ?>
            
                <h2 class="comment-sbar">
                <strong>
            <?php
                    printf( _n( '《%2$s》 收到一条评论 ', '《%2$s》共有 %1$s 条评论', get_comments_number()),
                    number_format_i18n( get_comments_number() ), '<em>' . get_the_title() . '</em>' );
                ?>
                </strong>
            </h2>





<?php if ( get_comment_pages_count() > 1 && get_option( 'page_comments' ) ) : // Are there comments to navigate through? ?>
			<div class="navigation">
				<div class="nav-previous"><?php previous_comments_link( __( '<span class="meta-nav">&larr;</span> Older Comments', 'twentyten' ) ); ?></div>
				<div class="nav-next"><?php next_comments_link( __( 'Newer Comments <span class="meta-nav">&rarr;</span>', 'twentyten' ) ); ?></div>
			</div> <!-- .navigation -->


<?php endif; // check for comment navigation ?>
  <ol style="margin-left:20px;height:210px;" class="chrComicList">
        <?php
            /* Loop through and list the comments. Tell wp_list_comments()
             * to use twentyten_comment() to format the comments.
             * If you want to overload this in a child theme then you can
             * define twentyten_comment() and that will be used instead.
             * See twentyten_comment() in twentyten/functions.php for more.
             */
            wp_list_comments( array( 'callback' => 'yqxs_comment' ) );
        ?>
    </ol>




<?php else:?>
   <h2 class=" comment-sbar">
                <strong>
            <?php
                    printf('《%s》 暂无评论，要不你先来?', '<em>' . get_the_title() . '</em>' );
                ?>
                </strong>
 </h2>



<?php endif?>
</div>