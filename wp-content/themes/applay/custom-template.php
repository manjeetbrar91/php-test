<?php 
/*
Template Name: Custom Template
*/
$content_padding = get_post_meta(get_the_ID(),'content_padding',true);
$single_page_layout = get_post_meta(get_the_ID(),'sidebar_layout',true);
global $global_page_layout;
$layout = $single_page_layout ? $single_page_layout : ($global_page_layout ? $global_page_layout : ot_get_option('page_layout','right'));
$global_page_layout = $layout;
get_header();
?>
	<?php get_template_part( 'templates/header/header', 'heading' ); ?>    
    <div id="body">
    	<?php if($layout!='true-full'){ ?>
    	<div class="container">
        <?php }?>
        	<?php if($content_padding!='off'){ ?>
        	<div class="content-pad-4x">
            <?php }?>
                <div class="row">
                    <div id="content" class="col-md-9 " role="main">
                        <article class="single-page-content">
                        	<?php while ( have_posts() ) : the_post();
								the_content();
							endwhile; ?>
                        </article>
                    </div><!--/content-->
                  <?php get_sidebar(); ?>
                </div><!--/row-->
            <?php if($content_padding!='off'){ ?>
            </div><!--/content-pad-4x-->
            <?php }?>
        <?php if($layout!='true-full'){ ?>
        </div><!--/container-->
        <?php }?>
    </div><!--/body-->
<?php get_footer(); ?>