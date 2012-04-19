<?php
//http://codex.wordpress.org/Function_Reference/register_post_type
add_action('init', 'yqxs_announce_init');
function yqxs_announce_init() 
{
  $labels = array(
    'name' => '公告管理',
    'singular_name' =>'所有公告',
    'add_new' =>'发布新公告',
    'add_new_item' => __('发布公告'),
    'edit_item' => __('编辑公告'),
    'new_item' => __('新公告'),
    'view_item' => __('查看公告'),
    'search_items' => __('公告搜索'),
    'not_found' =>  __('没有找到公告'),
    'not_found_in_trash' => __('回收站找不到公告'), 
    'parent_item_colon' => '',
    'menu_name' => '公告'

  );
  $args = array(
    'labels' => $labels,
    'public' => true,
    'publicly_queryable' => true,
    'show_ui' => true, 
    'show_in_menu' => true, 
    'query_var' => true,
    'rewrite' => true,
    'capability_type' => 'post',
    'has_archive' => true, 
    'hierarchical' => false,
    'menu_position' => null,
    'supports' => array('title','editor','comments','revisions')
  ); 
  register_post_type('announce',$args);
}

//添加过滤器，以确保课本用户更新时会显示。
 
add_filter('post_updated_messages', 'announce_updated_messages');
function announce_updated_messages( $messages ) {
  global $post, $post_ID;

  $messages['announce'] = array(
    0 => '', // 未使用。信息开始在索引1。
    1 => sprintf( __('公告更新. <a href="%s">查看公告</a>'), esc_url( get_permalink($post_ID) ) ),
    2 => __('公告更新.'),
    3 => __('公告删除.'),
    4 => __('公告已更新.'),
    /* translators: %s: date and time of the revision */
    5 => isset($_GET['revision']) ? sprintf( __('将公告重置到版本 %s'), wp_post_revision_title( (int) $_GET['revision'], false ) ) : false,
    6 => sprintf( __('公告已发布. <a href="%s">查看公告</a>'), esc_url( get_permalink($post_ID) ) ),
    7 => __('公告已保存.'),
    8 => sprintf( __('公告已提交. <a target="_blank" href="%s">预览</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
    9 => sprintf( __('公告安排完成: <strong>%1$s</strong>. <a target="_blank" href="%2$s">预览</a>'),
      // translators: Publish box date format, see http://php.net/date
      date_i18n( __( 'M j, Y @ G:i' ), strtotime( $post->post_date ) ), esc_url( get_permalink($post_ID) ) ),
    10 => sprintf( __('公告已更新. <a target="_blank" href="%s">预览</a>'), esc_url( add_query_arg( 'preview', 'true', get_permalink($post_ID) ) ) ),
  );

  return $messages;
}

//
//add_action( 'contextual_help', 'add_help_text', 10, 3 );

function add_help_text($contextual_help, $screen_id, $screen) { 
  //$contextual_help .= var_dump($screen); // use this to help determine $screen->id
  if ('book' == $screen->id ) {
    $contextual_help =
      '<p>' . __('Things to remember when adding or editing a book:') . '</p>' .
      '<ul>' .
      '<li>' . __('Specify the correct genre such as Mystery, or Historic.') . '</li>' .
      '<li>' . __('Specify the correct writer of the book.  Remember that the Author module refers to you, the author of this book review.') . '</li>' .
      '</ul>' .
      '<p>' . __('If you want to schedule the book review to be published in the future:') . '</p>' .
      '<ul>' .
      '<li>' . __('Under the Publish module, click on the Edit link next to Publish.') . '</li>' .
      '<li>' . __('Change the date to the date to actual publish this article, then click on Ok.') . '</li>' .
      '</ul>' .
      '<p><strong>' . __('For more information:') . '</strong></p>' .
      '<p>' . __('<a href="http://codex.wordpress.org/Posts_Edit_SubPanel" target="_blank">Edit Posts Documentation</a>') . '</p>' .
      '<p>' . __('<a href="http://wordpress.org/support/" target="_blank">Support Forums</a>') . '</p>' ;
  } elseif ( 'edit-book' == $screen->id ) {
    $contextual_help = 
      '<p>' . __('This is the help screen displaying the table of books blah blah blah.') . '</p>' ;
  }
  return $contextual_help;
}
?>