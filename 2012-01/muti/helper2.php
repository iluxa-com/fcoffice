<?php

function muti_selector($config) {
    if(is_array($config)===FALSE) return FALSE;
    if(!isset($config['data']) OR is_array($config['data'])===FALSE) return FALSE;
    $inputs = '';
    foreach($config['data'] as $data) {
        $inputs .= "\n<label style='display:block;' for=\"{$data['id']}\"><input class='groups' id=\"{$data['id']}\" type='checkbox'  value=\"{$data['id']}\">{$data['name']}</label>\n";
    }
    return <<<HEREDOC
        <div id="muti_selector_float_div">
        {$inputs}
        <input type="hidden" name="{$config['hidden']}" id="{$config['hidden']}" value="">
        <div id="muti_selector_controller">
             <span class="controller_items" id="cancel">取消</span>
            <span class="controller_items" id="ok">确定<span>

        </div>
    </div>
    <label>{$config['label']}<input type="text" readonly="true" class="test" id="{$config['display']}" value=""></label>
    <script type="text/javascript" src="muti-selector.js"></script>
    <script> $('#{$config['display']}').muti_selector("{$config['hidden']}");</script>
HEREDOC;
    
    }