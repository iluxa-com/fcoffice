<?php

function muti_selector($config) {
    static $flag ;    
    if(is_array($config)===FALSE) return FALSE;
    if(!isset($config['data']) OR is_array($config['data'])===FALSE) return FALSE;
    
    $inputs = '';
    foreach($config['data'] as $data) {
        $inputs .= "\n<label style='display:block;' for=\"{$config['display']}_{$data['id']}\"><input class='groups' id=\"{$config['display']}_{$data['id']}\" type='checkbox'  value=\"{$data['id']}\">{$data['name']}</label>\n";
    }
    if(!isset($flag)) {
        $script_load = '<script type="text/javascript" src="muti-selector.js"></script>';
        $flag = true;
    }else $script_load = '';
    
    return <<<HEREDOC
        <div class="muti_selector_float_div" id="{$config['display']}_muti_div">
        {$inputs}
        <input type="hidden" name="{$config['hidden']}" id="{$config['hidden']}" value="">
        <div class="muti_selector_controller">
             <span class="controller_items  controller_items_cancel" id="{$config['display']}_cancel">取消</span>
            <span class="controller_items controller_items_ok" id="{$config['display']}_ok">确定<span>
        </div>
    </div>
    <label for="{$config['display']}">{$config['label']}<input type="text" readonly="true" class="muti_div_testarea" id="{$config['display']}" value=""></label>
    {$script_load}
    <script> $('#{$config['display']}').muti_selector("{$config['hidden']}");</script>\n
HEREDOC;
    
    }