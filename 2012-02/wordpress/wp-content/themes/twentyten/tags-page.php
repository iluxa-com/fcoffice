<?php
/**
 * Template Name: tags, no sidebar
 *
 * A custom page template without sidebar.
 *
 * The "Template Name:" bit above allows this to be selectable
 * from a dropdown menu on the edit page screen.
 *
 * @package WordPress
 * @subpackage Twenty_Ten
 * @since Twenty Ten 1.0
 */

get_header(); ?>
<?php wp_tag_cloud('smallest=12&largest=32&unit=px&number=200');?>

<?php get_footer(); ?>
