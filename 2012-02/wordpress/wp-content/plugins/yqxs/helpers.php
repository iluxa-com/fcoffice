<?php

//加载插件目录下的js
function yqxs_loadJs($js) {
    $jq_src = YQXS_URL . '/js/' . $js . '?yqxs=1.0';
    echo '<script type="text/javascript" src="' . $jq_src . '"></script>';
}

//切割系列的字符
function split_words($str) {
    if (preg_match('#(.+?)(\w+)$#', $str, $matches)) {
        array_shift($matches);
        return array_map('trim', $matches);
    } else {
        return array($str,);
    }
}

//设置post的，如果存在不会添加而是更新原meta的值
function yqxs_set_post_meta($post_id, $key, $value) {
    add_post_meta($post_id, $key, $value, true) or update_post_meta($post_id, $key, $value);
}

//忽略不用
function yqxs_insert_cover($post_id, $filename) {

    $path = YQXS_COVER_DIR . '/' . $filename;
    $url = YQXS_COVER_URL . '/' . $filename;

    $image_type = wp_check_filetype_and_ext($path, $filename, null);
    $attachment = array(
        'post_mime_type' => $image_type['type'],
        'guid' => $url,
        'post_parent' => $post_id,
        'post_title' => '',
        'post_content' => '',
    );
    $id = wp_insert_attachment($attachment, $file, $post_id);

    if (!is_wp_error($id)) {
        wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $file));
    }
}

//生成封面目录下的图像附件的meta信息,忽略不用。
function yqxs_generate_attachment_metadata($filename) {

    $path = YQXS_COVER_DIR . '/' . $filename;
    $imagesize = getimagesize($path);
    $metadata['width'] = $imagesize[0];
    $metadata['height'] = $imagesize[1];
    list($uwidth, $uheight) = wp_constrain_dimensions($metadata['width'], $metadata['height'], 128, 96);
    $metadata['hwstring_small'] = "height='$uheight' width='$uwidth'";
    $metadata['file'] = _wp_relative_upload_path($path);
    $pathinfo = pathinfo($path);

    $sizes['thumbnail'] = array(
        "file" => $pathinfo['filename'] . '-80x125.' . $pathinfo['extension'],
        "width" => 80,
        "height" => 125,
    );
    $sizes['post-thumbnail'] = array(
        "file" => $pathinfo['filename'] . '-150x240.' . $pathinfo['extension'],
        "width" => 150,
        "height" => 240,
    );
    $metadata['sizes'] = $sizes;
}

//设置文章附件图片
function yqxs_set_cover($filename, $post_id, $img_title='本文图片', $also_thumbnail=true) {

    $path = YQXS_COVER_DIR . '/' . $filename;
    $url = YQXS_COVER_URL . '/' . $filename;

    $image_type = wp_check_filetype_and_ext($path, $filename, null);
    $attachment = array(
        'post_mime_type' => $image_type['type'],
        'guid' => $url,
        'post_parent' => $post_id,
        'post_title' => $img_title,
        'post_content' => '',
    );
    $id = wp_insert_attachment($attachment, $path, $post_id);

    if (!is_wp_error($id)) {
        wp_update_attachment_metadata($id, wp_generate_attachment_metadata($id, $path));
    }
    //设置特色图像

    if (true === $also_thumbnail) {
        if (false === set_post_thumbnail($post_id, $id))
            return false;
    }

    return $id;
}

function yqxs_file_get_contents($url) {
    $content = file_get_contents($url);
    if (strpos($content, 'charset=gb2312') !== FALSE) {
        $content = iconv('gbk', 'utf-8//IGNORE', $content);
    }
    if($content=='') return False;
    return $content;
}

//采集章节
function yqxs_ch2db($post_id, $ch_url) {
    $content = yqxs_file_get_contents($ch_url);
    preg_match('#<div align=center>(.*?)</div>#is', $content, $matches);
    preg_match_all('#<a href=(.*?)>(.*?)</a>#is', $matches[1], $items, PREG_SET_ORDER);

    $dir_url = substr($ch_url, 0, strrpos($ch_url, '/') + 1);
    $cid_arr = array();

    $record_arr = array();

    global $wpdb;
    foreach ($items as $key => $item) {
        $url = $dir_url . $item[1];
        $data_arr = array(
            'post_id' => $post_id,
            'chapter_order' => $key + 1,
            'content_url' => $url,
            'chapter_title' => $item[2],
            'create_time' => date('Y-m-d H:i:s')
        );

        $id = $wpdb->get_var(
                  $wpdb->prepare("SELECT id FROM $wpdb->chapters WHERE `content_url` =%s;", $url)
        );

        if ($id !== NULL) {
            $wpdb->update(
                    $wpdb->chapters,
                    $data_arr,
                    array('id' => $id),
                    array('%d', '%d', '%s', '%s', '%s'),
                    array('%d')
            );
            $cid = $id;
        } else {
            $wpdb->insert(
                            $wpdb->chapters,
                            $data_arr,
                            array('%d', '%d', '%s', '%s', '%s')
            );
            $cid = $wpdb->insert_id;
        }
        $data_arr['id'] = $cid;
        $record_arr[$key] = $data_arr;
    }
    return $record_arr;
}

//检查指定的文章名在posts表中是否存在,存在返回id号，否则返回false
function yqxs_post_exists($post_title) {
    global $wpdb;
    $sql = $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_status = 'publish' AND post_title=%s ORDER BY ID DESC", $post_title ) ;
     
     $id = $wpdb->get_var($sql);
     return ($id===null) ? false : (int)$id;

}
