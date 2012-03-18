<?php 
    require('../wp-load.php');
    $rules = get_option('rewrite_rules');
    echo '<pre>';
    print_r($rules);
    