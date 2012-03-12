<?php
    function yqxs_bind_list() {
        wp_enqueue_style( 'yqxs_admin_css' );
        wp_enqueue_script( 'yqxs_plugin_script' );
        echo <<<HEREDOC
<div class="wrap" style="-webkit-text-size-adjust:none;">
<div class="icon32" id="icon-options-general"><br></div>
    <h2>列表采集设置</h2><hr />
HEREDOC;
   
    if(!isset($_POST['yqxs_list_url']) OR empty($_POST['yqxs_list_url']))    {
        echo <<<HEREDOC
        <form action="" enctype="multipart/form-data" method="post">
                输入你要采集的列表url : <input name="yqxs_list_url" style="width:400px"type="text" value="http://www.yqxs.com/data/writer/writer223.html">
                <input type="submit" name="list_save" class="button-primary" value="确定" />
HEREDOC;
            wp_nonce_field( 'yqxs_list_action', 'yqxs_token', true, true );
        
            echo '</form>';
    }else {
        if(!wp_verify_nonce($_POST['yqxs_token'],'yqxs_list_action')) {
            wp_die('非法操作');
        } else {
            $url = esc_url ($_POST['yqxs_list_url']);
            echo "<div id='current_url' >指定列表链接: <a href='{$url}' target='_blank'>{$url}</a>";
            
            $list_info = yqxs_get_list_info($url);
            if(!is_array($list_info)) echo '<h2>没有找到匹配的小说</h2><hr /></div>';
            else {
                $total = count($list_info);
                echo '<input style="margin-left: 30px;" type="button" name="list_caiji" class="button-primary" value="采集以下文章('.$total.'篇)" /><hr /></div>';
                echo '<ul class="list_info" id="list_info">';
                foreach($list_info as $key => $info){
                    $key++;
                    echo "<li class='list_item' id='no_{$key}' rel='{$info[1]}'> {$key}.  {$info[2]} : <a href='{$info[1]}' target='_blank' >{$info[1]}</a></li>";
                }
                echo '</ul>' ;
               
            }
        }
        
    }        
    
    
    echo '</div>';
    }