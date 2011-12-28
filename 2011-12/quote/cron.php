<?php
    $run_file = (date('i')>15) ? 'get.php' : 'send.php';
    if(file_exists($run_file)) require($run_file);