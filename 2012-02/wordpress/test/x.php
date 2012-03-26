<?php
require('../wp-load.php');
$message = 'fuck';

wp_die( $message, $title, array( 'response' => 404,'back_link'=>true ) );