<?php 
/*
 * Template Name: Demo Classic Style
 */
 ?>
<!DOCTYPE html>
<!--[if IE 7]>
<html class="ie ie7" <?php language_attributes(); ?>>
<![endif]-->
<!--[if IE 8]>
<html class="ie ie8" <?php language_attributes(); ?>>
<![endif]-->
<!--[if !(IE 7) | !(IE 8)  ]><!-->
<html <?php language_attributes(); ?>>
<!--<![endif]-->
<head>
<meta charset="<?php bloginfo( 'charset' ); ?>" />
<meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1.0">
<?php if(ot_get_option('favicon')):?>
<link rel="shortcut icon" type="ico" href="<?php echo esc_url(ot_get_option('favicon'));?>">
<?php endif;?>
<title><?php wp_title( '|', true, 'right' ); ?></title>
<link rel="profile" href="http://gmpg.org/xfn/11" />
<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
<?php if(ot_get_option('favicon')):?>
<link rel="shortcut icon" type="ico" href="<?php echo esc_url(ot_get_option('favicon'));?>">
<?php endif;?>
<?php // Loads HTML5 JavaScript file to add support for HTML5 elements in older IE versions. ?>
<!--[if lt IE 9]>
<script src="<?php echo esc_url(get_template_directory_uri()); ?>/js/html5.js" type="text/javascript"></script>
<![endif]-->
<!--[if lte IE 9]>
<link rel="stylesheet" type="text/css" href="<?php echo esc_url(get_template_directory_uri()); ?>/css/ie.css" />
<![endif]-->
<?php if(ot_get_option('retina_logo')):?>
<style type="text/css" >
	@media only screen and (-webkit-min-device-pixel-ratio: 2),(min-resolution: 192dpi) {
		/* Retina Logo */
		.logo{background:url(<?php echo esc_url(ot_get_option('retina_logo')); ?>) no-repeat center; display:inline-block !important; background-size:contain;}
		.logo img{ opacity:0; visibility:hidden}
		.logo *{display:inline-block}
	}
</style>
<?php endif;?>
<?php
add_action('wp_enqueue_scripts', 'my_remove_scripts');
function my_remove_scripts(){
	wp_dequeue_style( 'modern-style' );
}
wp_head(); ?>
</head>

<body <?php body_class() ?>>
<a name="top" style="height:0; position:absolute; top:0;" id="top-anchor"></a>
<?php if(ot_get_option('pre-loading',2)==1||(ot_get_option('pre-loading',2)==2&&(is_front_page()||is_page_template('page-templates/front-page.php')))){ ?>
<div id="pageloader" class="dark-div" style="position:fixed; top:0; left:0; width:100%; height:100%; z-index:99999; background:<?php echo esc_attr(ot_get_option('loading_bg','#111')) ?>;">   
    <div class="loader loader-2"><i></i><i></i><i></i><i></i></div>
</div>
<?php }?>

<?php
	//prepare page title
	global $page_title;
	$page_title = __('Demo Classic','leafcolor');
?>
<div id="body-wrap">
    <div id="wrap">
        <header>
            <?php
			$nav_style = false;
			$nav_des = 'on';
			?>
			<div id="main-nav" class="<?php if(ot_get_option('nav_schema',false)){echo esc_attr('light-nav');}else{ echo esc_attr('dark-div');} ?> <?php echo esc_attr('disable-description'); ?>" <?php if(ot_get_option('nav_sticky','on')=='on'){?>data-spy="affix" data-offset-top="280"<?php } ?>>
                <nav class="navbar navbar-inverse <?php if($nav_style){?> style-off-canvas <?php }?>" role="navigation">
                    <div class="container">
                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <?php if(ot_get_option('logo_image') == ''):?>
                            <a class="logo" href="<?php echo esc_url(home_url()); ?>"><img src="<?php echo esc_url(get_template_directory_uri()) ?>/images/web-logo.png" alt="logo"></a>
                            <?php else:?>
                            <a class="logo" href="<?php echo esc_url(get_home_url()); ?>" title="<?php wp_title( '|', true, 'right' ); ?>"><img src="<?php echo esc_url(ot_get_option('logo_image')); ?>" alt="<?php wp_title( '|', true, 'right' ); ?>"/></a>
                            <?php endif;?>
                        </div>
                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="main-menu hidden-xs <?php if($nav_style){?>  hidden <?php }?>">
                        	<?php if(ot_get_option('enable_search')!='off'){ ?>
                        	<ul class="nav navbar-nav navbar-right">
                            	<li><a href="#" class="search-toggle"><i class="fa fa-search"></i></a></li>
                            </ul>
                            <?php } ?>
                            <ul class="nav navbar-nav navbar-right">
                            	<?php
									if(has_nav_menu( 'primary-menus' )){
										wp_nav_menu(array(
											'theme_location'  => 'primary-menus',
											'container' => false,
											'items_wrap' => '%3$s',
											'walker'=> new custom_walker_nav_menu()
										));	
									}else{?>
										<li><a href="<?php echo esc_url(home_url()); ?>"><?php _e('Home','leafcolor') ?> <span class="menu-description"><?php _e('Home page','leafcolor') ?></span></a></li>
										<?php wp_list_pages('depth=1&number=4&title_li=' ); ?>
								<?php } ?>
                            </ul>
                        </div><!-- /.navbar-collapse -->
                        <button type="button" class="mobile-menu-toggle <?php if($nav_style){?> <?php }else{ ?> visible-xs <?php }?>">
                            <span class="sr-only"><?php _e('Menu','leafcolor') ?></span>
                            <i class="fa fa-bars"></i>
                        </button>
                    </div>
                </nav>
            </div><!-- #main-nav -->
            <?php get_template_part( 'templates/header/header', 'frontpage' ); ?>
        </header> 
        <?php
global $global_page_layout;
$global_page_layout = 'true-full';
$single_page_layout = get_post_meta(get_the_ID(),'sidebar_layout',true);
$content_padding = get_post_meta(get_the_ID(),'content_padding',true);
$layout = $single_page_layout ? $single_page_layout : ($global_page_layout ? $global_page_layout : ot_get_option('page_layout','right'));
$global_page_layout = $layout;
//get_header();
?>
	<?php //get_template_part( 'templates/header/header', 'heading' ); ?>    
    <div id="body">
    	<?php if($layout!='true-full'){ ?>
    	<div class="container">
        <?php }?>
        	<?php if($content_padding!='off'){ ?>
        	<div class="content-pad-4x">
            <?php }?>
                <div class="row">
                    <div id="content" class="<?php if($layout != 'full' && $layout != 'true-full'){ ?> col-md-9 <?php }else{ ?> col-md-12 <?php } if($layout == 'left'){?> revert-layout <?php }?>" role="main">
                        <article class="single-page-content">
                        	<?php
							// The Loop
							while ( have_posts() ) : the_post();
								the_content();
							endwhile;
							?>
                        </article>
                    </div><!--/content-->
                    <?php if($layout != 'full' && $layout != 'true-full'){get_sidebar();} ?>
                </div><!--/row-->
            <?php if($content_padding!='off'){ ?>
            </div><!--/content-pad-4x-->
            <?php }?>
        <?php if($layout!='true-full'){ ?>
        </div><!--/container-->
        <?php }?>
    </div><!--/body-->
<?php get_footer(); ?>