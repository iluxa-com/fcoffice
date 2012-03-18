<?php 
    require('../wp-load.php');
    //$result = yqxs_get_posts_by_char(45,array('a','B','C','D'));
    
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
	'number'                   => '18',
	'taxonomy'                 => 'category',
	'pad_counts'               => false );
     $categories = get_categories( $args ); 
     
     foreach ($categories as $k=>$cat) {
        $categories[$k]->link= get_category_link($cat->cat_ID);
        
        
      }
     
      var_dump($categories);
    
    