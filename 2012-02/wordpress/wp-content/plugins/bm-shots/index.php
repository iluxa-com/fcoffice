<?php
/*
Plugin Name: BM_Shots
Plugin URI: http://www.binarymoon.co.uk/projects/bm-shots-automated-screenshots-website/
Description: A plugin to take advantage of the mshots functionality on WordPress.com
Author: Ben Gillbanks
Version: 0.8
Author URI: http://www.binarymoon.co.uk/
*/


/**
 * Usage : [browsershot url="http://link-to-website" width="foo-value" target="anchor-target"]
 */
function bm_sc_mshot ($attributes, $content = '', $code = '') {

	extract(shortcode_atts(array(
		'url' => '',
		'target' => '',
		'width' => 250,
	), $attributes));

	if (empty($url)) {
		return;
	}

	$att = '';
	$width = (int) $width;

	if ($width <= 0) {
		$width = 250;
	}

	$imageUrl = bm_mshot ($url, $width);

	$anchor_attributes = array (
		'href' => $url,
	);

	if (!empty($target)) {
		$anchor_attributes[] = $target;
	}

	foreach ($anchor_attributes as $k => $v) {
		$att .= $k . '="' . $v . '" ';
	}

	if ($imageUrl == '') {
		return '';
	} else {
		$image = '<img src="' . $imageUrl . '" alt="' . $url . '" width="' . $width . '" />';
		return '<div class="browsershot mshot"><a ' . $att . '>' . $image . '</a></div>';
	}

}


/**
 *
 */
function bm_mshot ($url = '', $width = 250) {

	if ($url != '') {
		return 'http://s.wordpress.com/mshots/v1/' . urlencode(clean_url($url)) . '?w=' . $width;
	} else {
		return '';
	}

}

add_shortcode('browsershot', 'bm_sc_mshot');

?>