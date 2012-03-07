<?php

add_action('admin_menu', 'featured_meta_box');
add_action('save_post', 'save_featureddata');
$featured_meta_boxes =
        array(
            "featured" => array(
                "name" => "featured",
                "std" => "",
                "title" => "推荐文章图标地址",
                "description" => "请在输入框内输入图标地址.")
);

function featured_meta_boxes() {
    global $post, $featured_meta_boxes;
    foreach ($featured_meta_boxes as $meta_box) {
        $meta_box_value = get_post_meta($post->ID, $meta_box['name'], true);
        if ($meta_box_value == "")
            $meta_box_value = $meta_box['std'];
        echo'<input type="hidden" name="' . $meta_box['name'] . '_noncename" id="' . $meta_box['name'] . '_noncename" value="' . wp_create_nonce(plugin_basename(__FILE__)) . '" />';
        echo'<input type="text" name="' . $meta_box['name'] . '" value="' . $meta_box_value . '" size="100" /><br />';
        echo'<p><label for="' . $meta_box['name'] . '">' . $meta_box['description'] . '</label></p>';
    }
}

function featured_meta_box() {
    global $theme_name;
    if (function_exists('add_meta_box')) {
        add_meta_box('featured-meta-boxes', '推荐文章图标地址', 'featured_meta_boxes', 'post', 'normal', 'high');
    }
}

function save_featureddata($post_id) {
    global $post, $featured_meta_boxes;
    foreach ($featured_meta_boxes as $meta_box) {
// Verify
        if (!wp_verify_nonce($_POST[$meta_box['name'] . '_noncename'], plugin_basename(__FILE__))) {
            return $post_id;
        }

        if ('page' == $_POST['post_type']) {
            if (!current_user_can('edit_page', $post_id))
                return $post_id;
        } else {
            if (!current_user_can('edit_post', $post_id))
                return $post_id;
        }
        $data = $_POST[$meta_box['name']];
        if (get_post_meta($post_id, $meta_box['name']) == "")
            add_post_meta($post_id, $meta_box['name'], $data, true);
        elseif ($data != get_post_meta($post_id, $meta_box['name'], true))
            update_post_meta($post_id, $meta_box['name'], $data);
        elseif ($data == "")
            delete_post_meta($post_id, $meta_box['name'], get_post_meta($post_id, $meta_box['name'], true));
    }
}

?>