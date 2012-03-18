<?php
    function yqxs_bind_list() {
        wp_enqueue_style( 'yqxs_admin_css' );
        wp_enqueue_script( 'yqxs_plugin_script' );
        
        
        echo <<<HEREDOC
<div class="wrap" style="-webkit-text-size-adjust:none;">
<div class="icon32" id="icon-options-general"><br></div>
    <h2>列表采集设置</h2><hr />
HEREDOC;
   //http://www.yqxs.com/data/writer/writer223.html
        echo <<<HEREDOC
        <form action="" enctype="multipart/form-data" method="post">
                输入你要采集的列表url : <input name="yqxs_list_url" style="width:400px"type="text" value="http://www.yqxs.com/data/top/new.html" />
                
                <input type="submit" name="list_save" class="button-primary" value="确定" /><hr/>
HEREDOC;
            wp_nonce_field( 'yqxs_list_action', 'yqxs_token', true, true );
        
            echo '</form>';
            
    if(!isset($_REQUEST['yqxs_list_url']) OR empty($_REQUEST['yqxs_list_url']))    {
        //http://www.yqxs.com/data/writer/writer223.html
        /*
        echo <<<HEREDOC
        <form action="" enctype="multipart/form-data" method="post">
                输入你要采集的列表url : <input name="yqxs_list_url" style="width:400px"type="text" value="http://www.yqxs.com/data/writer/writer3775.html" />
                
                <input type="submit" name="list_save" class="button-primary" value="确定" />
HEREDOC;
            wp_nonce_field( 'yqxs_list_action', 'yqxs_token', true, true );
        
            echo '</form>';
            */
    }else {
        //测试时暂时关掉这个检测
        if( !wp_verify_nonce($_REQUEST['yqxs_token'],'yqxs_list_action')) {
            wp_die('非法操作');
        } else {
            $menu_page_url2 = yqxs_menu_page_url('yqxs_chapter2db');
            $url = esc_url ($_REQUEST['yqxs_list_url']);
            echo "<div id='current_url' >指定列表链接: <a href='{$url}' target='_blank'>{$url}</a>";
            echo '<form action="" enctype="multipart/form-data" method="post">';
            wp_nonce_field( 'yqxs_list_action', 'yqxs_token', true, true );
            $list_info = yqxs_get_list_info($url);
            if(!is_array($list_info)) echo '<h2>没有找到匹配的小说</h2><hr /></div>';
            else {
                $list_info =array_reverse($list_info);
                $total = count($list_info);
                echo '
                <div id="yqxs_list_buttons">
                <input style="margin-left: 10px;" type="button" name="multi_thread" class="button-primary" value="当前多线程状态:关闭" />
                <input style="margin-left: 10px;" type="button" name="list_caiji" class="button-primary" value="开始采集('.$total.'篇)" />
                <input style="margin-left: 30px;" type="button" name="content_caiji" class="button-primary" value="进入章节采集" onclick="location.href={$menu_page_url2}" />
                </div>
                <hr />
                </div>';
                echo '<ul class="list_info" id="list_info">';
                foreach($list_info as $key => $info){
                    $key++;
                    echo "<li class='yqxs_list_item' id='no_{$key}' rel='{$info[1]}'> {$key}.  {$info[2]} : <a href='{$info[1]}' target='_blank' >{$info[1]}</a></li>";
                }
                echo '</ul>' ;
               
               echo'</form>';
            }
        }
        
    }        
    
    
    echo '</div>';
    }