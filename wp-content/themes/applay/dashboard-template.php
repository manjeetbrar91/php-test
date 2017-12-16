<?php 
/*
Template Name: Dashboard Template
*/

$content_padding = get_post_meta(get_the_ID(),'content_padding',true);
$single_page_layout = get_post_meta(get_the_ID(),'sidebar_layout',true);
global $global_page_layout;
$layout = $single_page_layout ? $single_page_layout : ($global_page_layout ? $global_page_layout : ot_get_option('page_layout','right'));
$global_page_layout = $layout;
get_header();
?>
<?php 
	while ( have_posts() ) : the_post();
		the_content();
	endwhile; 
?>
<?php get_footer(); ?>