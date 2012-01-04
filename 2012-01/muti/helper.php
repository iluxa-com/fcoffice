<?php

function muti_selector($config) {
    if (is_array($config) === FALSE)
        return FALSE;
    if (!isset($config['data']) OR is_array($config['data']) === FALSE)
        return FALSE;
    $inputs = '';
    foreach ($config['data'] as $data) {
        $inputs .= "\n<label style='display:block;' for=\"{$data['id']}\"><input class='groups' id=\"{$data['id']}\" type='checkbox'  value=\"{$data['id']}\">{$data['name']}</label>\n";
    }
    //$html = link_tag(css_path('jquery.ui.theme.css'));
    //$html .= javascript_tag(javascript_path('jquery.ui.core.js'));
    $html = <<<HEREDOC
        <div id="muti_selector_float_div">
            {$inputs}
        <input type="hidden" name="{$config['hidden']}" id="{$config['hidden']}" value="">
        <div id="muti_selector_controller">
            <span class="controller_items" id="cancel">取消</span>
            <span class="controller_items" id="ok">确定<span>
        </div>
    </div>
    <label for = "select_members">{$config['label']}<input type="text" readonly=“true" class="test" id="{$config['display']}" value=""></label>
HEREDOC;
    $html .= javascript_tag(javascript_path('muti-selector.js'));
    $html.=js_open();
    $html.="$('#{$config['display']}').muti_selector(\"{$config['hidden']}\");";
    $html.=js_close();
    return $html;
}