<?php
define('YQURI',get_template_directory_uri());
add_theme_support( 'post-thumbnails' );

//无限下拉滚动
add_action('wp_head','infinite_scroll');
function infinite_scroll() {
    if(is_single() && get_query_var('page')>0) {
        $script = YQURI . '/js/infinitescroll_ch.js';        
    }else {
         $script = YQURI . '/js/infinitescroll_dir.js';
    }
    echo '<script type="text/javascript" src="' . $script . '?yqxs=1.0"></script>';
//echo '<script type="text/javascript" src="http://www.infinite-scroll.com/wp-content/plugins/infinite-scroll/jquery.infinitescroll.js"></script>';
        

}

//高亮当前分类
add_action('wp_head','high_light_term');

function high_light_term() {
    if(is_category()){
        $category_name = get_query_var('category_name');
        $cat_obj = get_category_by_slug($category_name); 
        if(!is_object($cat_obj)) return;
        echo "<style>a.yqxs_cat_{$cat_obj->term_id}";
        echo '{border: 1px solid;color: #FF0000;padding: 0.2em;background-color:#fe0}</style>';
    }elseif(is_tag()) {
        $slug = get_query_var('tag');        
        $tag_obj =get_term_by( 'slug', $slug,'post_tag');
         if(!is_object($tag_obj)) return;
        echo "<style>a.yqxs_tag_{$tag_obj->term_id}";
        echo '{border: 1px solid;color: #FF0000;padding: 0.2em;background-color:#fe0}</style>';
    }elseif(is_author()) {
        echo "<style>a.yqxs_author";
        echo '{border: 1px solid;color: #FF0000;padding: 0.2em;background-color:#fe0}</style>';
    }
}
//高亮当前tag页


//改写原来的get_the_category_list，用于高亮当前分类
function yqxs_get_the_category_list( $current_cat_ID, $separator = '', $parents='', $post_id = false ) {
	global $wp_rewrite;
	$categories = get_the_category( $post_id );
	if ( !is_object_in_taxonomy( get_post_type( $post_id ), 'category' ) )
		return apply_filters( 'the_category', '', $separator, $parents );

	if ( empty( $categories ) )
		return apply_filters( 'the_category', __( 'Uncategorized' ), $separator, $parents );

	$rel = ( is_object( $wp_rewrite ) && $wp_rewrite->using_permalinks() ) ? 'rel="category tag"' : 'rel="category"';
       

	$thelist = '';
	if ( '' == $separator ) {
		$thelist .= '<ul class="post-categories">';
		foreach ( $categories as $category ) {
                    $current_class = ($current_cat_ID == $category->term_id) ? ' class="current_cat"' : '';
                    $rel .=$current_class;
			$thelist .= "\n\t<li>";
			switch ( strtolower( $parents ) ) {
				case 'multiple':
					if ( $category->parent )
						$thelist .= get_category_parents( $category->parent, true, $separator );
					$thelist .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" ' . $rel . '>' . $category->name.'</a></li>';
					break;
				case 'single':
					$thelist .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" ' . $rel . '>';
					if ( $category->parent )
						$thelist .= get_category_parents( $category->parent, false, $separator );
					$thelist .= $category->name.'</a></li>';
					break;
				case '':
				default:
					$thelist .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" ' . $rel . '>' . $category->name.'</a></li>';
			}
		}
		$thelist .= '</ul>';
	} else {
		$i = 0;
        
		foreach ( $categories as $category ) {
                    
        
                    //$current_class = ($current_cat_ID ==$category->term_id) ? ' class="current_cat"' : '';
                    /*
                    var_dump($category);
                    var_dump($current_cat_ID);
                    var_dump($current_class);
                    */
                    $rel .=$current_class;        
			if ( 0 < $i )
				$thelist .= $separator;
			switch ( strtolower( $parents ) ) {
				case 'multiple':
					if ( $category->parent )
						$thelist .= get_category_parents( $category->parent, true, $separator );
					$thelist .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" ' . $rel ." class='yqxs_cat_{$category->term_id}'". '>' . $category->name.'</a>';
					break;
				case 'single':
					$thelist .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" ' . $rel ." class='yqxs_cat_{$category->term_id}'". '>';
					if ( $category->parent )
						$thelist .= get_category_parents( $category->parent, false, $separator );
					$thelist .= "$category->name</a>";
					break;
				case '':
				default:
					$thelist .= '<a href="' . get_category_link( $category->term_id ) . '" title="' . esc_attr( sprintf( __( "View all posts in %s" ), $category->name ) ) . '" ' . $rel ." class='yqxs_cat_{$category->term_id}'". '>' . $category->name.'</a>';
			}
			++$i;
		}
	}
	//return apply_filters( 'the_category', $thelist, $separator, $parents );
    return $thelist;
}



//设置图片懒加载
add_filter('wp_get_attachment_image_attributes','set_for_laze_load',10,2);
function set_for_laze_load ($attr, $attachment) {
    //Ajax加载时不使用懒加载
     if(isset($_SERVER['HTTP_X_REQUESTED_WITH'])) 
        return $attr;
        
    if(isset($attr['src']) && defined('LAZY_LOAD')) {
        $attr ['data-original'] = $attr['src'];
        $attr['src'] = YQURI .'/images/grey.gif';
     }
    return $attr;
}

function the_chapter_title($post_id='',$chapter_order='') {

    if(empty($post_id)) {
        global $post;
        $post_id = $post->ID;
    }
    if(empty($chapter_order)) {
        $chapter_order = get_query_var('page');
        if($chapter_order<1) return ;
    }        
    static $store;
    if(empty ($store)) {        
        $store = array();
    } 

    if(!isset($store[$post_id][$chapter_order])) {
        $store[$post_id][$chapter_order] = get_chapter_title($post_id,$chapter_order);
    }   else {
        //echo '不用查库';
        
    }
    
    echo $store[$post_id][$chapter_order];


}
function get_chapter_title($post_id,$chapter_order) {
    //	chapter_title
    global $wpdb;        
    $title = $wpdb->get_var(
        $wpdb->prepare(
        "SELECT chapter_title FROM $wpdb->chapters WHERE post_id=%d AND chapter_order=%d",
        $post_id,
        $chapter_order
        )
    );

    return $title;


    }


//meta_key
  
function yqxs_get_meta_links($post_id,$meta_key,$class='yqxs_tag_link') {
    $meta_values = get_post_meta($post_id, $meta_key, true);

   if($meta_values == '') {
    return ;
   }elseif(is_array($meta_values)){    
           $links = array();
        foreach($meta_values as $v) {
            $name = trim($v,',');
            $tag_id = get_term_id_by_name($name);
            if($tag_id !=NULL)
                $links[] = '<a class="'. $class .' yqxs_tag_'.$tag_id .' yqxs_' . $meta_key.'" href="'.get_tag_link($tag_id) .'" title="' . $name.'">' .$name .'</a>';
        }
        
        return (count($links)>0)  ? $links : NULL;
   } else {
        $links =NULL;
        $name = trim($meta_values,',');
        $tag_id = get_term_id_by_name($name);
        if($tag_id !=NULL)
            $links =  '<a class="'. $class .' yqxs_tag_'.$tag_id .' yqxs_' . $meta_key.'" href="'.get_tag_link($tag_id) .'" title="' . $name.'">' .$name .'</a>';
         return $links;
   }
   
 }
 function get_term_id_by_name($name) {
        global $wpdb;
        $term_id = $wpdb->get_var(
            $wpdb->prepare("SELECT term_id FROM $wpdb->terms WHERE name=%s",$name)
        );
        return $term_id;
    }
 
function yqxs_get_all_users($num=100) {
    global $wpdb;
     
    $sql =$wpdb->prepare(
"SELECT $wpdb->users.ID, $wpdb->users.display_name, count( $wpdb->posts.ID ) 
 AS total
FROM $wpdb->posts
INNER JOIN $wpdb->users ON $wpdb->posts.post_author = $wpdb->users.ID
WHERE `post_status` = 'publish' AND post_type='post' 
GROUP BY $wpdb->posts.post_author
ORDER BY total DESC
LIMIT 0 , %d", $num
    );
    $results = $wpdb->get_results(
        $sql
    );
    return $results;
}

function yqxs_get_users_by_char($char,$num=1000) {
    global $wpdb;
    $sql =$wpdb->prepare(
"SELECT ID,display_name,meta_value FROM $wpdb->users
INNER JOIN $wpdb->usermeta ON $wpdb->users.ID = $wpdb->usermeta.user_id
WHERE meta_key='asw' AND meta_value=%s LIMIT 0, %d",$char,$num
    );

    $results = $wpdb->get_results(
        $sql
    );
    return $results;
}



function yqxs_get_all_posts($num=100) {
    global $wpdb;
    
}
//<li><em><font size=2  color="red"   ><span>03-16</span></font></em><a href="/view/2012/3_16/3283.html" target="_blank" title="爱杀宝贝第11集下载">爱杀宝贝第11集更新</a>
function yqxs_get_recent_posts($no_posts = 100, $show_pass_post = false, $skip_posts = 0) {
    global $wpdb, $tableposts;
    $request = "SELECT ID, post_title, post_date, post_content FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='post' ";
        if(!$show_pass_post) { $request .= "AND post_password ='' "; }
    $request .= "ORDER BY post_date DESC LIMIT $skip_posts, $no_posts";
    $posts = $wpdb->get_results($request);
    $output = '';
    $today =date('m-d');
    foreach ($posts as $post) {
        $post_title = stripslashes($post->post_title);
        //$post_date = mysql2date('j.m.Y', $post->post_date);
        $post_date = date('m-d',strtotime($post->post_date));
        $permalink = get_permalink($post->ID);
        $color =($post_date===$today) ?'red':'grey';
        $output .= '<li><em><font size=2  color="'.$color.'"   ><span>'.$post_date.'</span></font></em>' . '<a href="' . $permalink . '" rel="bookmark" title="Permanent Link: ' . $post_title . '">' . $post_title . "</a></li>\n";
    }
    return $output;
} 

function yqxs_get_recent_posts2($no_posts = 100, $show_pass_post = false, $skip_posts = 0) {
    global $wpdb, $tableposts;
    $request = "SELECT ID, post_title, post_date, post_content FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='post' ";
        if(!$show_pass_post) { $request .= "AND post_password ='' "; }
    $request .= "ORDER BY `post_modified` DESC LIMIT $skip_posts, $no_posts";
    $posts = $wpdb->get_results($request);
    $output = '';
    $today =date('m-d');
    foreach ($posts as $post) {
        $post_title = stripslashes($post->post_title);
        //$post_date = mysql2date('j.m.Y', $post->post_date);
        //<li class="line"><a href="/view/2012/3_15/3189.html" title="花牌情缘">　花牌情缘</a><span>03-15</span></li>
        $post_date = date('m-d',strtotime($post->post_date));
        $permalink = get_permalink($post->ID);
        $color =($post_date===$today) ?'red':'grey';
        $output .= '<li class="line"><a href="' . $permalink . '" rel="bookmark" title="Permanent Link: ' . $post_title . '">' . $post_title . "</a><span>$post_date</span></li>\n";
    }
    return $output;
} 

//摘自网上
function get_recent_posts($no_posts = 100, $before = '<li>', $after = '</li>', $show_pass_post = false, $skip_posts = 0) {
    global $wpdb, $tableposts;
    $request = "SELECT ID, post_title, post_date, post_content FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='post'";
        if(!$show_pass_post) { $request .= "AND post_password ='' "; }
    $request .= "ORDER BY post_date DESC LIMIT $skip_posts, $no_posts";
    $posts = $wpdb->get_results($request);
    $output = '';
    foreach ($posts as $post) {
        $post_title = stripslashes($post->post_title);
//         $post_date = mysql2date('j.m.Y', $post->post_date);
        $permalink = get_permalink($post->ID);
        $output .= $before . '<a href="' . $permalink . '" rel="bookmark" title="Permanent Link: ' . $post_title . '">' . $post_title . '</a>'. $after;
    }
    return $output;
} 

function yqxs_get_reacent_posts_and_thumbnails($no_posts = 100, $show_pass_post = false, $skip_posts = 0){
global $wpdb, $tableposts;
    $request = "SELECT ID, post_title, post_date, post_content FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='post'";
        if(!$show_pass_post) { $request .= "AND post_password ='' "; }
    $request .= "ORDER BY post_date DESC LIMIT $skip_posts, $no_posts";
    $posts = $wpdb->get_results($request);
    $output = '';
    $today =date('m-d');
    foreach ($posts as $post) {
        $post_title = stripslashes($post->post_title);
        //$post_date = mysql2date('j.m.Y', $post->post_date);
        $post_date = date('m-d',strtotime($post->post_date));
        $permalink = get_permalink($post->ID);
        $img = get_the_post_thumbnail($post->ID, array(120,160), array('class' => 'yqxs_tab'));
        $output .="<li><a href='{$permalink}' target='_blank' title='{$post_title}'>{$img}</a><a href='{$permalink}' target='_blank' title='{$post_title}'>{$post_title}</a></li>\n";
       
    }
    return $output;

}

//取特定分类名的文章和缩略图
function yqxs_get_cat_posts_and_thumbnails($no_posts = 100,$term_name ,$show_pass_post = false, $skip_posts = 0){
global $wpdb;
    

    $request = "SELECT * FROM $wpdb->posts WHERE ID IN(
SELECT object_id AS ID
FROM $wpdb->term_relationships
WHERE term_taxonomy_id = (
SELECT term_taxonomy_id
FROM $wpdb->terms
INNER JOIN $wpdb->term_taxonomy ON $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id
WHERE $wpdb->terms.name = '$term_name' LIMIT 0,1)
ORDER BY ID DESC
) AND post_status = 'publish' AND post_type='post' ";

        if(!$show_pass_post) { $request .= "AND post_password ='' "; }
    $request .= "ORDER BY post_date DESC LIMIT $skip_posts, $no_posts";
    $posts = $wpdb->get_results($request);

    $output = '';


    foreach ($posts as $post) {
        $post_title = stripslashes($post->post_title);
        //$post_date = mysql2date('j.m.Y', $post->post_date);
        
        $permalink = get_permalink($post->ID);
        $img = get_the_post_thumbnail($post->ID, array(120,160), array('class' => 'yqxs_tab'));
        $output .="<li><a href='{$permalink}' target='_blank' title='{$post_title}'>{$img}</a><a href='{$permalink}' target='_blank' title='{$post_title}'>{$post_title}</a></li>\n";
       
    }
    //var_dump($output);
    return $output;

}

//按字母
function yqxs_get_posts_by_chars($no_posts=100,$chars=array(),$show_pass_post = false, $skip_posts = 0) {
    if(!is_array($chars) OR count($chars)<1) return FALSE;
    $char_str ='';
    foreach($chars as $k=>$char) {
        $char =strtoupper($char);
        $char ="'$char',";
        $char_str .=$char;
    }
    $char_str = rtrim($char_str,',');
    global $wpdb;
    $request = "SELECT *
FROM yqxs_posts
INNER JOIN yqxs_postmeta ON yqxs_posts.ID = yqxs_postmeta.post_id
WHERE `meta_key` = 'psw'
AND `meta_value`
IN (
$char_str
)
 AND post_status='publish' AND post_type='post' ";

     if(!$show_pass_post) { $request .= "AND post_password ='' "; }
    $request .= "ORDER BY meta_value ASC , ID DESC  LIMIT $skip_posts, $no_posts";
    
 
    
    $posts = $wpdb->get_results($request);
    //<li><a href="/view/2011/2_19/1214.html" target="_blank" title="暗夜魔法使">暗夜魔法使</a></li>
    
    $output = '';


    foreach ($posts as $post) {
        $post_title = stripslashes($post->post_title);
        //$post_date = mysql2date('j.m.Y', $post->post_date);
        $permalink = get_permalink($post->ID);
        
        $output .="<li><a href='{$permalink}' target='_blank' title='{$post_title}'>{$post_title}</a></li>\n";
       
    }
    //var_dump($output);
    return $output;    

}



function yqxs_get_categories_list($number=18) {

    $args = array(
	'type'                     => 'post',
	'child_of'                 => 0,
	'parent'                   => '',
	'orderby'                  => 'count',
	'order'                    => 'DESC',
	'hide_empty'               => 1,
	'hierarchical'             => 1,
	'exclude'                  => '1',
	'include'                  => '',
	'number'                   => $number,
	'taxonomy'                 => 'category',
	'pad_counts'               => false );
     $categories = get_categories( $args ); 
     $output ='';
     foreach ($categories as $k=>$cat) {
        $id = $k+1;
        $link= get_category_link($cat->cat_ID);        
        $output .="<li class='line'><a href='{$link}' title='{$cat->name}' target='_blank' ><span class='t6'><em>{$id}.</em></span>{$cat->name}</a></li>\n";

      }
     return $output;
    
    

}

//define('YQURI',get_template_directory_uri());

/*
if ( function_exists( 'add_theme_support' ) ) {
	add_theme_support( 'post-thumbnails' );
        set_post_thumbnail_size( 50, 50 );
}

 */
/**
 * TwentyTen functions and definitions
 *
 * Sets up the theme and provides some helper functions. Some helper functions
 * are used in the theme as custom template tags. Others are attached to action and
 * filter hooks in WordPress to change core functionality.
 *
 * The first function, twentyten_setup(), sets up the theme by registering support
 * for various features in WordPress, such as post thumbnails, navigation menus, and the like.
 *
 * When using a child theme (see http://codex.wordpress.org/Theme_Development and
 * http://codex.wordpress.org/Child_Themes), you can override certain functions
 * (those wrapped in a function_exists() call) by defining them first in your child theme's
 * functions.php file. The child theme's functions.php file is included before the parent
 * theme's file, so the child theme functions would be used.
 *
 * Functions that are not pluggable (not wrapped in function_exists()) are instead attached
 * to a filter or action hook. The hook can be removed by using remove_action() or
 * remove_filter() and you can attach your own function to the hook.
 *
 * We can remove the parent theme's hook only after it is attached, which means we need to
 * wait until setting up the child theme:
 *
 * <code>
 * add_action( 'after_setup_theme', 'my_child_theme_setup' );
 * function my_child_theme_setup() {
 *     // We are providing our own filter for excerpt_length (or using the unfiltered value)
 *     remove_filter( 'excerpt_length', 'twentyten_excerpt_length' );
 *     ...
 * }
 * </code>
 *
 * For more information on hooks, actions, and filters, see http://codex.wordpress.org/Plugin_API.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

/**
 * Set the content width based on the theme's design and stylesheet.
 *
 * Used to set the width of images and content. Should be equal to the width the theme
 * is designed for, generally via the style.css stylesheet.
 */
if ( ! isset( $content_width ) )
	$content_width = 640;

/** Tell WordPress to run twentyten_setup() when the 'after_setup_theme' hook is run. */
add_action( 'after_setup_theme', 'twentyten_setup' );

if ( ! function_exists( 'twentyten_setup' ) ):
/**
 * Sets up theme defaults and registers support for various WordPress features.
 *
 * Note that this function is hooked into the after_setup_theme hook, which runs
 * before the init hook. The init hook is too late for some features, such as indicating
 * support post thumbnails.
 *
 * To override twentyten_setup() in a child theme, add your own twentyten_setup to your child theme's
 * functions.php file.
 *
 * @uses add_theme_support() To add support for post thumbnails and automatic feed links.
 * @uses register_nav_menus() To add support for navigation menus.
 * @uses add_custom_background() To add support for a custom background.
 * @uses add_editor_style() To style the visual editor.
 * @uses load_theme_textdomain() For translation/localization support.
 * @uses add_custom_image_header() To add support for a custom header.
 * @uses register_default_headers() To register the default custom header images provided with the theme.
 * @uses set_post_thumbnail_size() To set a custom post thumbnail size.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_setup() {

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Post Format support. You can also use the legacy "gallery" or "asides" (note the plural) categories.
	add_theme_support( 'post-formats', array( 'aside', 'gallery' ) );

	// This theme uses post thumbnails
	add_theme_support( 'post-thumbnails' );

	// Add default posts and comments RSS feed links to head
	add_theme_support( 'automatic-feed-links' );

	// Make theme available for translation
	// Translations can be filed in the /languages/ directory
	load_theme_textdomain( 'twentyten', get_template_directory() . '/languages' );

	$locale = get_locale();
	$locale_file = get_template_directory() . "/languages/$locale.php";
	if ( is_readable( $locale_file ) )
		require_once( $locale_file );

	// This theme uses wp_nav_menu() in one location.
	register_nav_menus( array(
		'primary' => __( 'Primary Navigation', 'twentyten' ),
	) );

	// This theme allows users to set a custom background
	add_custom_background();

	// Your changeable header business starts here
	if ( ! defined( 'HEADER_TEXTCOLOR' ) )
		define( 'HEADER_TEXTCOLOR', '' );

	// No CSS, just IMG call. The %s is a placeholder for the theme template directory URI.
	if ( ! defined( 'HEADER_IMAGE' ) )
		define( 'HEADER_IMAGE', '%s/images/headers/path.jpg' );

	// The height and width of your custom header. You can hook into the theme's own filters to change these values.
	// Add a filter to twentyten_header_image_width and twentyten_header_image_height to change these values.
	define( 'HEADER_IMAGE_WIDTH', apply_filters( 'twentyten_header_image_width', 940 ) );
	define( 'HEADER_IMAGE_HEIGHT', apply_filters( 'twentyten_header_image_height', 198 ) );

	// We'll be using post thumbnails for custom header images on posts and pages.
	// We want them to be 940 pixels wide by 198 pixels tall.
	// Larger images will be auto-cropped to fit, smaller ones will be ignored. See header.php.
	set_post_thumbnail_size( HEADER_IMAGE_WIDTH, HEADER_IMAGE_HEIGHT, true );

	// Don't support text inside the header image.
	if ( ! defined( 'NO_HEADER_TEXT' ) )
		define( 'NO_HEADER_TEXT', true );

	// Add a way for the custom header to be styled in the admin panel that controls
	// custom headers. See twentyten_admin_header_style(), below.
	add_custom_image_header( '', 'twentyten_admin_header_style' );

	// ... and thus ends the changeable header business.

	// Default custom headers packaged with the theme. %s is a placeholder for the theme template directory URI.
	register_default_headers( array(
		'berries' => array(
			'url' => '%s/images/headers/berries.jpg',
			'thumbnail_url' => '%s/images/headers/berries-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Berries', 'twentyten' )
		),
		'cherryblossom' => array(
			'url' => '%s/images/headers/cherryblossoms.jpg',
			'thumbnail_url' => '%s/images/headers/cherryblossoms-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Cherry Blossoms', 'twentyten' )
		),
		'concave' => array(
			'url' => '%s/images/headers/concave.jpg',
			'thumbnail_url' => '%s/images/headers/concave-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Concave', 'twentyten' )
		),
		'fern' => array(
			'url' => '%s/images/headers/fern.jpg',
			'thumbnail_url' => '%s/images/headers/fern-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Fern', 'twentyten' )
		),
		'forestfloor' => array(
			'url' => '%s/images/headers/forestfloor.jpg',
			'thumbnail_url' => '%s/images/headers/forestfloor-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Forest Floor', 'twentyten' )
		),
		'inkwell' => array(
			'url' => '%s/images/headers/inkwell.jpg',
			'thumbnail_url' => '%s/images/headers/inkwell-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Inkwell', 'twentyten' )
		),
		'path' => array(
			'url' => '%s/images/headers/path.jpg',
			'thumbnail_url' => '%s/images/headers/path-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Path', 'twentyten' )
		),
		'sunset' => array(
			'url' => '%s/images/headers/sunset.jpg',
			'thumbnail_url' => '%s/images/headers/sunset-thumbnail.jpg',
			/* translators: header image description */
			'description' => __( 'Sunset', 'twentyten' )
		)
	) );
}
endif;

if ( ! function_exists( 'twentyten_admin_header_style' ) ) :
/**
 * Styles the header image displayed on the Appearance > Header admin panel.
 *
 * Referenced via add_custom_image_header() in twentyten_setup().
 *
 * @since Twenty Ten 1.0
 */
function twentyten_admin_header_style() {
?>
<style type="text/css">
/* Shows the same border as on front end */
#headimg {
	border-bottom: 1px solid #000;
	border-top: 4px solid #000;
}
/* If NO_HEADER_TEXT is false, you would style the text with these selectors:
	#headimg #name { }
	#headimg #desc { }
*/
</style>
<?php
}
endif;

/**
 * Get our wp_nav_menu() fallback, wp_page_menu(), to show a home link.
 *
 * To override this in a child theme, remove the filter and optionally add
 * your own function tied to the wp_page_menu_args filter hook.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_page_menu_args( $args ) {
	$args['show_home'] = true;
	return $args;
}
add_filter( 'wp_page_menu_args', 'twentyten_page_menu_args' );

/**
 * Sets the post excerpt length to 40 characters.
 *
 * To override this length in a child theme, remove the filter and add your own
 * function tied to the excerpt_length filter hook.
 *
 * @since Twenty Ten 1.0
 * @return int
 */
function twentyten_excerpt_length( $length ) {
	return 40;
}
add_filter( 'excerpt_length', 'twentyten_excerpt_length' );

/**
 * Returns a "Continue Reading" link for excerpts
 *
 * @since Twenty Ten 1.0
 * @return string "Continue Reading" link
 */
function twentyten_continue_reading_link() {
	return ' <a href="'. get_permalink() . '">' . __( 'Continue reading <span class="meta-nav">&rarr;</span>', 'twentyten' ) . '</a>';
}

/**
 * Replaces "[...]" (appended to automatically generated excerpts) with an ellipsis and twentyten_continue_reading_link().
 *
 * To override this in a child theme, remove the filter and add your own
 * function tied to the excerpt_more filter hook.
 *
 * @since Twenty Ten 1.0
 * @return string An ellipsis
 */
function twentyten_auto_excerpt_more( $more ) {
	return ' &hellip;' . twentyten_continue_reading_link();
}
add_filter( 'excerpt_more', 'twentyten_auto_excerpt_more' );

/**
 * Adds a pretty "Continue Reading" link to custom post excerpts.
 *
 * To override this link in a child theme, remove the filter and add your own
 * function tied to the get_the_excerpt filter hook.
 *
 * @since Twenty Ten 1.0
 * @return string Excerpt with a pretty "Continue Reading" link
 */
function twentyten_custom_excerpt_more( $output ) {
	if ( has_excerpt() && ! is_attachment() ) {
		$output .= twentyten_continue_reading_link();
	}
	return $output;
}
add_filter( 'get_the_excerpt', 'twentyten_custom_excerpt_more' );

/**
 * Remove inline styles printed when the gallery shortcode is used.
 *
 * Galleries are styled by the theme in Twenty Ten's style.css. This is just
 * a simple filter call that tells WordPress to not use the default styles.
 *
 * @since Twenty Ten 1.2
 */
add_filter( 'use_default_gallery_style', '__return_false' );

/**
 * Deprecated way to remove inline styles printed when the gallery shortcode is used.
 *
 * This function is no longer needed or used. Use the use_default_gallery_style
 * filter instead, as seen above.
 *
 * @since Twenty Ten 1.0
 * @deprecated Deprecated in Twenty Ten 1.2 for WordPress 3.1
 *
 * @return string The gallery style filter, with the styles themselves removed.
 */
function twentyten_remove_gallery_css( $css ) {
	return preg_replace( "#<style type='text/css'>(.*?)</style>#s", '', $css );
}
// Backwards compatibility with WordPress 3.0.
if ( version_compare( $GLOBALS['wp_version'], '3.1', '<' ) )
	add_filter( 'gallery_style', 'twentyten_remove_gallery_css' );

if ( ! function_exists( 'twentyten_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyten_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;
	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
		<div id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment, 40 ); ?>
			<?php printf( __( '%s <span class="says">says:</span>', 'twentyten' ), sprintf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ); ?>
		</div><!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em class="comment-awaiting-moderation"><?php _e( 'Your comment is awaiting moderation.', 'twentyten' ); ?></em>
			<br />
		<?php endif; ?>

		<div class="comment-meta commentmetadata"><a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
			<?php
				/* translators: 1: date, 2: time */
				printf( __( '%1$s at %2$s', 'twentyten' ), get_comment_date(),  get_comment_time() ); ?></a><?php edit_comment_link( __( '(Edit)', 'twentyten' ), ' ' );
			?>
		</div><!-- .comment-meta .commentmetadata -->

		<div class="comment-body"><?php comment_text(); ?></div>

		<div class="reply">
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
		</div><!-- .reply -->
	</div><!-- #comment-##  -->

	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyten' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'twentyten' ), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
endif;

/**
 * Register widgetized areas, including two sidebars and four widget-ready columns in the footer.
 *
 * To override twentyten_widgets_init() in a child theme, remove the action hook and add your own
 * function tied to the init hook.
 *
 * @since Twenty Ten 1.0
 * @uses register_sidebar
 */
function twentyten_widgets_init() {
	// Area 1, located at the top of the sidebar.
	register_sidebar( array(
		'name' => __( 'Primary Widget Area', 'twentyten' ),
		'id' => 'primary-widget-area',
		'description' => __( 'The primary widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 2, located below the Primary Widget Area in the sidebar. Empty by default.
	register_sidebar( array(
		'name' => __( 'Secondary Widget Area', 'twentyten' ),
		'id' => 'secondary-widget-area',
		'description' => __( 'The secondary widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 3, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'First Footer Widget Area', 'twentyten' ),
		'id' => 'first-footer-widget-area',
		'description' => __( 'The first footer widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 4, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Second Footer Widget Area', 'twentyten' ),
		'id' => 'second-footer-widget-area',
		'description' => __( 'The second footer widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 5, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Third Footer Widget Area', 'twentyten' ),
		'id' => 'third-footer-widget-area',
		'description' => __( 'The third footer widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );

	// Area 6, located in the footer. Empty by default.
	register_sidebar( array(
		'name' => __( 'Fourth Footer Widget Area', 'twentyten' ),
		'id' => 'fourth-footer-widget-area',
		'description' => __( 'The fourth footer widget area', 'twentyten' ),
		'before_widget' => '<li id="%1$s" class="widget-container %2$s">',
		'after_widget' => '</li>',
		'before_title' => '<h3 class="widget-title">',
		'after_title' => '</h3>',
	) );
}
/** Register sidebars by running twentyten_widgets_init() on the widgets_init hook. */
add_action( 'widgets_init', 'twentyten_widgets_init' );

/**
 * Removes the default styles that are packaged with the Recent Comments widget.
 *
 * To override this in a child theme, remove the filter and optionally add your own
 * function tied to the widgets_init action hook.
 *
 * This function uses a filter (show_recent_comments_widget_style) new in WordPress 3.1
 * to remove the default style. Using Twenty Ten 1.2 in WordPress 3.0 will show the styles,
 * but they won't have any effect on the widget in default Twenty Ten styling.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_remove_recent_comments_style() {
	add_filter( 'show_recent_comments_widget_style', '__return_false' );
}
add_action( 'widgets_init', 'twentyten_remove_recent_comments_style' );

if ( ! function_exists( 'twentyten_posted_on' ) ) :
/**
 * Prints HTML with meta information for the current post-date/time and author.
 *
 * @since Twenty Ten 1.0
 */
function twentyten_posted_on() {
	printf( __( '<span class="%1$s">Posted on</span> %2$s <span class="meta-sep">by</span> %3$s', 'twentyten' ),
		'meta-prep meta-prep-author',
		sprintf( '<a href="%1$s" title="%2$s" rel="bookmark"><span class="entry-date">%3$s</span></a>',
			get_permalink(),
			esc_attr( get_the_time() ),
			get_the_date()
		),
		sprintf( '<span class="author vcard"><a class="url fn n" href="%1$s" title="%2$s">%3$s</a></span>',
			get_author_posts_url( get_the_author_meta( 'ID' ) ),
			esc_attr( sprintf( __( 'View all posts by %s', 'twentyten' ), get_the_author() ) ),
			get_the_author()
		)
	);
}
endif;

if ( ! function_exists( 'twentyten_posted_in' ) ) :
/**
 * Prints HTML with meta information for the current post (category, tags and permalink).
 *
 * @since Twenty Ten 1.0
 */
function twentyten_posted_in() {
	// Retrieves tag list of current post, separated by commas.
	$tag_list = get_the_tag_list( '', ', ' );
	if ( $tag_list ) {
		$posted_in = __( 'This entry was posted in %1$s and tagged %2$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	} elseif ( is_object_in_taxonomy( get_post_type(), 'category' ) ) {
		$posted_in = __( 'This entry was posted in %1$s. Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	} else {
		$posted_in = __( 'Bookmark the <a href="%3$s" title="Permalink to %4$s" rel="bookmark">permalink</a>.', 'twentyten' );
	}
	// Prints the string, replacing the placeholders.
	printf(
		$posted_in,
		get_the_category_list( ', ' ),
		$tag_list,
		get_permalink(),
		the_title_attribute( 'echo=0' )
	);
}
endif;




//内容控制
add_filter('the_content','yqxs_the_content',1,1);
function yqxs_the_content($content) {
    if(strpos($content,'[cai-ji-ok]') !==FALSE)  {
        
        global $post;
        global $wpdb;
        $content ='';
        
        if(is_single()) {
            $page = get_query_var('page');
            $permalink = get_permalink($post->ID);
            if($page==0) {
                //显示内容简介
              // $content .= "<div id='yqxs_excerpt' >" .$post->post_excerpt ."<hr/></div>";
                
                $result_arr = $wpdb->get_results(
                    $wpdb->prepare(
                        "SELECT chapter_order,chapter_title FROM $wpdb->chapters WHERE `post_id` =%d ORDER BY `chapter_order` ASC",
                        $post->ID,
                        ARRAY_A 
                    )
                );
                
                $chapter_arr =array();
                foreach ($result_arr as $result) {
                    
                     $chapter_url = str_replace(
                         $post->post_name,
                         $post->post_name.'-'.$result->chapter_order,
                         $permalink
                     );
                     $chapter_title = $result->chapter_title;
                     $chapter = '<li class="yqxs_ch_item"><a href=' . $chapter_url . '>';
                     $chapter .= $chapter_title . '</a></li>';
                     $chapter_arr[] = $chapter;
                }
                //章节链接
                $chapter_str = '<ul class="yqxs_ch_list">' .implode("\n",$chapter_arr) .'</ul>';
                $content .= $chapter_str;
                
                
            }else {
                //显示指定章节数的内容
                    
                 $result_arr = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT * FROM $wpdb->chapters WHERE `post_id` = %d AND `chapter_order`=%d",
                        $post->ID,
                        $page
                    ), ARRAY_A
                 );
                   
                 //取下页链接，注意要检查是否存在，即是否超过最后章节
                 $next_page = $page+1;
                 $next_res = $wpdb->get_var(
                    $wpdb->prepare(
                        "SELECT id FROM $wpdb->chapters WHERE `post_id`=%d AND `chapter_order` = %d",
                        $post->ID,
                        $next_page
                    )
                 );
                 $next_link = '';
                 if($next_res !==NULL) {
                    //$next_page = () ? $next_page : '';
                    $next_link = str_replace($post->post_name,$post->post_name .'-'.$next_page, $permalink);
                 }
                 
                 //取上页链接，当为0时要处理为小说首页
                 $prev_link = '';
                 $prev_page = $page -1;
                 if($prev_page>0) {
                    $prev_link = str_replace($post->post_name,$post->post_name .'-'.$prev_page, $permalink);
                 }
                    
                 
                 $content .='<h2 class="yqxs_ch_title">'.$result_arr['chapter_title'].'</h2>';
                 $content .='<div id="yqxs_ch_content">'.$result_arr['content'] .'</div>';
                 $content .='<div class="yqxs_dir_nav">';
                 $prev_link && $content .='<a id="yqxs_prev" href="'.$prev_link.'">【上一章】</a>' ;
                 $content .= '<a href="'.$permalink.'"> 【目录页】 </a>' ;
                 $next_link && $content .='<a id="yqxs_next" href="'.$next_link.'">【下一章】</a>' ;
                 $content .='</div>';
                 
              
                
              
                
            }
            
        }
        
         return $content;
    }elseif(strpos($content,'[cai-ji-ready]') !==FALSE) {
        $content = "章节正在整理";
    }elseif(strpos($content,'[no-content]') !==FALSE) {
         $content = "本书暂无全文,";
    }
    
    return $content;
    
   
}

//搜索结果高亮关键字
add_filter('the_title','high_search_title',999);
function high_search_title($title){
    if(!is_search()) return $title;
    global $s;
    $keys = explode(" ",$s);
    $title = preg_replace('/('.implode('|', $keys) .')/iu','<h2 class="search_high">\0</h2>',$title);
    return $title;
}

//不使用钩子，直接在搜索页调用
function high_search_excerpt($excerpt) {
    if(!is_search()) return $excerpt;
    global $s;
    $keys = explode(" ",$s);
    $excerpt = preg_replace('/('.implode('|', $keys) .')/iu','<b class="search_me">\0</b>',$excerpt);
    return $excerpt;
}

//生成作品字母索引链接
function pkey_link($char) {
    $char = strtoupper($char{0});
    return home_url('/pkey/'.$char);
}
//生成作家字母索引链接
function akey_link($char) {
    $char = strtoupper($char{0});
    return home_url('/akey/'.$char);
}

function the_down_link($post_title='【下载地址】',$post_name='') {
    global $post;
    if(empty($post_name) && isset($post) && is_object($post)) {
        $post_name =$post->post_name;         
    }
    if(empty($post_name)) return ;
    
    echo '<a href="' .esc_attr(download_link($post_name)) .'" class="down_link">'.$post_title.'</a>';
    
}
function download_link($post_name) {
    //return home_url('?down='.$post_name);
    return home_url('/down/'.$post_name);
}
function share_link($post_id) {
    return home_url('/share/'.$post_id);
}

function yqxs_get_thumblink($sid=NULL,$size='thumbnail') {
    $attachment_id = get_post_thumbnail_id($sid);
    $image_attributes = wp_get_attachment_image_src( $attachment_id ,$size);
    return $image_attributes[0];
}

function yqxs_redirect($page_title,$info,$page_url,$time=5) {
    $template = dirname(__FILE__).'/jump.html';
    if(!file_exists($template)) return FALSE;
    
    $content = str_replace(
        array('[page_title]','[info]','[page_url]','[time]',),
        array($page_title,$info,$page_url,$time),
        file_get_contents($template)
   );
   echo $content;
}



if ( ! function_exists( 'yqxs_comment' ) ) :
/**
 * Template for comments and pingbacks.
 *
 * To override this walker in a child theme without modifying the comments template
 * simply create your own twentyten_comment(), and that function will be used instead.
 *
 * Used as a callback by wp_list_comments() for displaying the comments.
 *
 * @since Twenty Ten 1.0
 */
function yqxs_comment( $comment, $args, $depth ) {
	$GLOBALS['comment'] = $comment;


	switch ( $comment->comment_type ) :
		case '' :
	?>
	<li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
    
		<div class="comment-item" id="comment-<?php comment_ID(); ?>">
		<div class="comment-author vcard">
			<?php echo get_avatar( $comment ); ?>
			<?php  printf( '<cite class="fn">%s</cite>', get_comment_author_link() ) ; ?>
            
           <!--         
           
           -->     
		</div>
        <!-- .comment-author .vcard -->
		<?php if ( $comment->comment_approved == '0' ) : ?>
			<em class="comment-awaiting-moderation">
                <?php _e( 'Your comment is awaiting moderation.', 'twentyten' ); ?>
            </em>
            <div class="comment-meta commentmetadata">
                <a href="<?php echo esc_url( get_comment_link( $comment->comment_ID ) ); ?>">
                
            </div><!-- .comment-meta .commentmetadata -->            
		<?php endif; ?>

		<div class="comment-body"><?php comment_text(); ?></div>
        
        
        <div class='clear'></div>
		<div class="reply">
            <div class='comment_time'>
             <?php printf( '发表于： %s %s', get_comment_date(),  get_comment_time() ); ?>
            <?php edit_comment_link( __( '(Edit)', 'twentyten' ), ' ' );?>
            </div>
			<?php comment_reply_link( array_merge( $args, array( 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
            
		</div><!-- .reply -->
        
	</div><!-- #comment-##  -->
    <div class='clear'></div>
	<?php
			break;
		case 'pingback'  :
		case 'trackback' :
	?>
	<li class="post pingback">
		<p><?php _e( 'Pingback:', 'twentyten' ); ?> <?php comment_author_link(); ?><?php edit_comment_link( __( '(Edit)', 'twentyten' ), ' ' ); ?></p>
	<?php
			break;
	endswitch;
}
    
endif;

//$yqxs_comment_notes_after='';

add_filter('comment_form_default_fields',yqxs_comment_fields,10,1);

function yqxs_comment_fields($fields) {
    /*
    unset($fields['url']);
    global $yqxs_comment_notes_after;
    $yqxs_comment_notes_after = $fields;
    return ;
     *
     */
     unset($fields['url']);
    // unset($fields['email']);
    $fields['yxm'] =  yqxs_GetYinXiangMaWidget();
    return $fields;
}
add_action('comment_form_before_fields',fields_wrap_start);
function fields_wrap_start() {
    echo '<div id="fields_wrap" style="width:310px;float: left; margin-top: 15px;">';
}
add_action('comment_form_after_fields',fields_wrap_end);
function fields_wrap_end() {
    echo '</div>';
}

add_filter('comment_form_defaults',yqxs_comment_form);
function yqxs_comment_form($defaults) {
    unset($defaults['comment_notes_before']);    
    $defaults['comment_notes_after'] ='';
    /*
    global $yqxs_comment_notes_after;
    
    foreach ( (array) $yqxs_comment_notes_after as $name => $field ) {
            $defaults['comment_notes_after'] .= $field ."\n";
    }
     *
     */
   $oldcomment = '';
    if(isset($_SESSION['old_comment']) && !empty($_SESSION['old_comment']) ) {
        $oldcomment =$_SESSION['old_comment'];
        unset($_SESSION['old_comment']);
    }    
    $defaults['comment_field'] ='<p class="comment-form-comment"><textarea id="comment" name="comment" aria-required="true">'.$oldcomment.'</textarea></p>';
   if(is_user_logged_in())
       //   height=258px width= 602px

       $defaults['comment_field'] ='<p class="comment-form-comment"><textarea id="comment" name="comment" style="height:258px; width: 602px" aria-required="true">'.$oldcomment.'</textarea></p>';

    return $defaults;
}


function yqxs_GetYinXiangMaWidget()
{
    global $yqxs_token;

	$YinXiangMaDataString = $yqxs_token;
	$YinXiangMaWidgetHtml="\n<script type='text/javascript'>\nvar YinXiangMaDataString ='".$YinXiangMaDataString."';\n</script>\n";

	$YinXiangMaWidgetHtml.="<script type='text/javascript' charset='gbk' src='".YinXiangMa_API_SERVER."/widget/"."YinXiangMa.php'></script>\n";
	

	$YinXiangMaWidgetHtml.=
	"<noscript>
		您的浏览器不支持或者禁用了Javascript，验证码将不能正常显示。<br/>
		点击这里，教您如何调整您的浏览器设置。<br/>
	</noscript>";

	return $YinXiangMaWidgetHtml;
}


//提交评论
add_action('pre_comment_on_post',yqxs_validate_yxm);
function yqxs_validate_yxm() {
    if(is_user_logged_in()) return TRUE;
    session_start();    
    require_once(get_template_directory() ."/yxm/YinXiangMaLib.php");
    if(!isset($_POST['YinXiangMa_response']) OR empty($_POST['YinXiangMa_response'])) {
        $comment_content = ( isset($_POST['comment']) ) ? trim($_POST['comment']) : null;
        $_SESSION['old_comment'] = $comment_content;
        $error = '验证码不能为空!';

        wp_die($error,$error,array( 'response' => 403,'back_link'=>true ));
    }
    //验证
    $response = YinXiangMa_validRequest($_POST['YinXiangMa_response'],@$_POST['YinXiangMa_challenge']);
    if(TRUE === $response->is_valid) {
        return TRUE;
    }else {
       $comment_content = ( isset($_POST['comment']) ) ? trim($_POST['comment']) : null;
       $_SESSION['old_comment'] = $comment_content;
       wp_die('验证码错误。',$response->error,array( 'response' => 403,'back_link'=>true ));
    }
}


//子评论
add_filter('comment_text',child_comment,99,2);
function child_comment ($comment_text,$comment) {
    if($comment->comment_parent>0) {
         $parent = get_comment($comment->comment_parent);
        // $content = substr(strip_tags(trim($parent->comment_content)),0,50).'...';
         $content = mb_substr(get_comment_excerpt($comment->comment_parent),0,20,'utf-8').'...';
       
         $link = get_comment_link($parent);
         $comment_text ='<blockquote class="quote_comment">@' . "<a href=\"{$link}\">" . $parent->comment_author .'</a>:'.$content.'</blockquote>'.$comment_text;
        
    }
     return $comment_text;
}

add_action('wp_loaded','chapter_valid');
function chapter_valid() {
     /*
                 $result_arr = $wpdb->get_row(
                    $wpdb->prepare(
                        "SELECT * FROM $wpdb->chapters WHERE `post_id` = %d AND `chapter_order`=%d",
                        $post->ID,
                        $page
                    ), ARRAY_A
                 );
                                 //不存在该章节
                 if(NULL ===$result_arr) {
                    wp_die( '章节不存在', '章节不存在', array( 'response' => 404,'back_link'=>true ) );
                    exit();
                 }
        */
            //wp_die( '章节不存在', '章节不存在', array( 'response' => 404,'back_link'=>true ) );
                   //var_dump(get_query_var('page'),get_query_var('name'));
            

}

//取所有公告

function get_recent_announces($no_posts = 10, $before = '<li>', $after = '</li>', $show_pass_post = false, $skip_posts = 0) {
    global $wpdb, $tableposts;
    $request = "SELECT ID, post_title, post_date, post_content FROM $wpdb->posts WHERE post_status = 'publish' AND post_type='announce'";
        if(!$show_pass_post) { $request .= "AND post_password ='' "; }
    $request .= "ORDER BY post_date DESC LIMIT $skip_posts, $no_posts";
    $posts = $wpdb->get_results($request);
    $output = '';
    foreach ($posts as $post) {
        $post_title = stripslashes($post->post_title);
//         $post_date = mysql2date('j.m.Y', $post->post_date);
        $permalink = get_permalink($post->ID);
        $time =  date('m-d',strtotime($post->post_date)) ;
        $id = ($time === date('m-d'))? 'anc_today' : 'anc_pass';
        
        $output .= $before . '<a id="'. $id .'" class="linktit" href="' . $permalink . '" rel="bookmark" title="链接到: ' . $post_title . '">' . esc_html("[$time]&nbsp;&nbsp;").$post_title . '</a>'. $after;
    }
    return $output;
} 

//全部公告链接
function announce_list_link() {
    return home_url('/announces');
}

