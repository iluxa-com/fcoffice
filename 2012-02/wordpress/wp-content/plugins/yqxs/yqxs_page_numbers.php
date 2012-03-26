<?php
function yqxs_page_numbers() {
    
    $settings = array(
        'page_of_page' =>'yes',
        'page_of_page_text' =>'页次',
        'page_of_of' =>' / ',
        'next_prev_text'=>'yes',
        'show_start_end_numbers'=>'yes',
        'show_page_numbers'=>'yes',
        'limit_pages'=>6,
        'nextpage'=>'下一页',
        'prevpage'=>'上一页',
        'startspace'=>'',
        'endspace'=>'',
    );
    wp_page_numbers($settings);

}

function yqxs_get_pagenum_link($num) {
    /*
    $num =(int)$num; 
    if(get_query_var('pagename') == 'recent'){
        $link = home_url( '/'.$num);
    }else {
        $link = get_pagenum_link($num);
    }
    return $link;
    */
    return get_pagenum_link($num);
}


// function wp_page_numbers_stylesheet()
// {
	// $settings = get_option('wp_page_numbers_array');
	// $head_stylesheet = $settings["head_stylesheetsheet"];
	// $head_stylesheet_folder_name = $settings["head_stylesheetsheet_folder_name"];
	// $style_theme = $settings["style_theme"];
	
	// if($head_stylesheet == "on" || $head_stylesheet == "" && (is_archive() || is_search() || is_home() ||is_page()))
	// {
		// echo '<link rel="stylesheet" href="'. get_bloginfo('wpurl') . '/wp-content/plugins/wp-page-numbers/';
		// if($head_stylesheet_folder_name == "")
		// {
			// if($style_theme == "default")
				// echo 'default';
			// elseif($style_theme == "classic")
				// echo 'classic';
			// elseif($style_theme == "tiny")
				// echo 'tiny';
			// elseif($style_theme == "panther")
				// echo 'panther';
			// elseif($style_theme == "stylish")
				// echo 'stylish';
			// else
				// echo 'default';
		// }
		// else
			// echo $head_stylesheet_folder_name;
		// echo '/wp-page-numbers.css" type="text/css" media="screen" />';
	// }
// }
// add_action('wp_head', 'wp_page_numbers_stylesheet');

function wp_page_numbers_check_num($num)
{
  return ($num%2) ? true : false;
}

function wp_page_numbers_page_of_page($max_page, $paged, $page_of_page_text, $page_of_of)
{
      global $wp_query;
      $total = $wp_query->found_posts;
    
	$pagingString = "";
        

	if ( $max_page > 1)
	{
		$pagingString .= '<span style="display:inline-block;margin-left: 140px;">共 '.$total .' 条数据 ';
		if($page_of_page_text == "")
			$pagingString .= 'Page ';
		else
			$pagingString .= $page_of_page_text . ' ';
		
		if ( $paged != "" )
			$pagingString .= $paged;
		else
			$pagingString .= 1;
		
		if($page_of_of == "")
			$pagingString .= ' of ';
		else
			$pagingString .= ' ' . $page_of_of . ' ';
		$pagingString .= floor($max_page).' 页</span>';
	}
	return $pagingString;
}

function wp_page_numbers_prevpage($paged, $max_page, $prevpage)
{
	if( $max_page > 1 && $paged > 1 )
		$pagingString = '<a href="'.yqxs_get_pagenum_link($paged-1). '">'.$prevpage.'</a>';
	return $pagingString;
}

function wp_page_numbers_left_side($max_page, $limit_pages, $paged, $pagingString)
{
	$pagingString = "";
	$page_check_max = false;
	$page_check_min = false;
	if($max_page > 1)
	{
		for($i=1; $i<($max_page+1); $i++)
		{
			if( $i <= $limit_pages )
			{
				if ($paged == $i || ($paged == "" && $i == 1))
					$pagingString .= '<em>'.$i.'</em>'."\n";
				else
					$pagingString .= '<a href="'.yqxs_get_pagenum_link($i). '">'.$i.'</a>'."\n";
				if ($i == 1)
					$page_check_min = true;
				if ($max_page == $i)
					$page_check_max = true;
			}
		}
		return array($pagingString, $page_check_max, $page_check_min);
	}
}

function wp_page_numbers_middle_side($max_page, $paged, $limit_pages_left, $limit_pages_right)
{
	$pagingString = "";
	$page_check_max = false;
	$page_check_min = false;
	for($i=1; $i<($max_page+1); $i++)
	{
		if($paged-$i <= $limit_pages_left && $paged+$limit_pages_right >= $i)
		{
			if ($paged == $i)
				$pagingString .= '<em>'.$i.'</em>'."\n";
			else
				$pagingString .= '<a href="'.yqxs_get_pagenum_link($i). '">'.$i.'</a>'."\n";
				
			if ($i == 1)
				$page_check_min = true;
			if ($max_page == $i)
				$page_check_max = true;
		}
	}
	return array($pagingString, $page_check_max, $page_check_min);
}

function wp_page_numbers_right_side($max_page, $limit_pages, $paged, $pagingString)
{
	$pagingString = "";
	$page_check_max = false;
	$page_check_min = false;
	for($i=1; $i<($max_page+1); $i++)
	{
		if( ($max_page + 1 - $i) <= $limit_pages )
		{
			if ($paged == $i)
				$pagingString .= '<em>'.$i.'</em>'."\n";
			else
				$pagingString .= '<a href="'.yqxs_get_pagenum_link($i). '">'.$i.'</a>'."\n";
				
			if ($i == 1)
			$page_check_min = true;
		}
		if ($max_page == $i)
			$page_check_max = true;
		
	}
	return array($pagingString, $page_check_max, $page_check_min);
}

function wp_page_numbers_nextpage($paged, $max_page, $nextpage)
{
	if( $paged != "" && $paged < $max_page)
		$pagingString = '<a href="'.yqxs_get_pagenum_link($paged+1). '">'.$nextpage.'</a>'."\n";
	return $pagingString;
}

function wp_page_numbers($settings=array(),$start = "", $end = "")
{
	global $wp_query;
	global $max_page;
	global $paged;
	if ( !$max_page ) { $max_page = $wp_query->max_num_pages; }
	if ( !$paged ) { $paged = 1; }
	
	//$settings = get_option('wp_page_numbers_array');
	$page_of_page = $settings["page_of_page"];
	$page_of_page_text = $settings["page_of_page_text"];
	$page_of_of = $settings["page_of_of"];
	
	$next_prev_text = $settings["next_prev_text"];
	$show_start_end_numbers = $settings["show_start_end_numbers"];
	$show_page_numbers = $settings["show_page_numbers"];
	
	$limit_pages = $settings["limit_pages"];
	$nextpage = $settings["nextpage"];
	$prevpage = $settings["prevpage"];
	$startspace = $settings["startspace"];
	$endspace = $settings["endspace"];
	
	if( $nextpage == "" ) { $nextpage = "&gt;"; }
	if( $prevpage == "" ) { $prevpage = "&lt;"; }
	if( $startspace == "" ) { $startspace = "..."; }
	if( $endspace == "" ) { $endspace = "..."; }
	
	if($limit_pages == "") { $limit_pages = "10"; }
	elseif ( $limit_pages == "0" ) { $limit_pages = $max_page; }
	
	if(wp_page_numbers_check_num($limit_pages) == true)
	{
		$limit_pages_left = ($limit_pages-1)/2;
		$limit_pages_right = ($limit_pages-1)/2;
	}
	else
	{
		$limit_pages_left = $limit_pages/2;
		$limit_pages_right = ($limit_pages/2)-1;
	}
	
	if( $max_page <= $limit_pages ) { $limit_pages = $max_page; }
	
	$pagingString = "<div class='pagebox'>\n";
	
	
	if($page_of_page != "no")
		$pagingString .= wp_page_numbers_page_of_page($max_page, $paged, $page_of_page_text, $page_of_of);
	
	if( ($paged) <= $limit_pages_left )
	{
		list ($value1, $value2, $page_check_min) = wp_page_numbers_left_side($max_page, $limit_pages, $paged, $pagingString);
		$pagingMiddleString .= $value1;
	}
	elseif( ($max_page+1 - $paged) <= $limit_pages_right )
	{
		list ($value1, $value2, $page_check_min) = wp_page_numbers_right_side($max_page, $limit_pages, $paged, $pagingString);
		$pagingMiddleString .= $value1;
	}
	else
	{
		list ($value1, $value2, $page_check_min) = wp_page_numbers_middle_side($max_page, $paged, $limit_pages_left, $limit_pages_right);
		$pagingMiddleString .= $value1;
	}
	if($next_prev_text != "no")
		$pagingString .= wp_page_numbers_prevpage($paged, $max_page, $prevpage);

		if ($page_check_min == false && $show_start_end_numbers != "no")
		{
			//$pagingString .= "<em class=\"nolink\">";
			$pagingString .= "<a href=\"" . yqxs_get_pagenum_link(1) . "\">首页</a>";
			$pagingString .= "\n<span class=\"space\">".$startspace."</span>\n";
		}
	
	if($show_page_numbers != "no")
		$pagingString .= $pagingMiddleString;
	
		if ($value2 == false && $show_start_end_numbers != "no")
		{
			$pagingString .= "<span class=\"space\">".$endspace."</span>\n";
			
			$pagingString .= "<a href=\"" . yqxs_get_pagenum_link($max_page) . "\">" . '尾页/'.$max_page . "</a>";
			$pagingString .= "\n";
		}
	
	if($next_prev_text != "no")
		$pagingString .= wp_page_numbers_nextpage($paged, $max_page, $nextpage);
	

	
	$pagingString .= "<div style='float: none; clear: both;'></div>\n";
	$pagingString .= "</div>\n";
	
	if($max_page > 1)
		echo $start . $pagingString . $end;
}
