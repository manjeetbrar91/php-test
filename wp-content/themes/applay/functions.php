<?php
/*
 * Applay functions
 */
if ( ! isset( $content_width ) ) $content_width = 900;
/**
 * Load core
 */
require_once 'inc/starter/leaf-core.php';
require_once 'inc/option-tree-hook.php';
require_once 'inc/starter/twenty-core.php';
require_once 'inc/starter/widget_param.php';

/* Define list of recommended and required plugins */
global $__required_plugins;
$__required_plugins = array(
		array(
            'name'      => 'Applay - Member',
            'slug'      => 'applay-member',
            'required'  => false
        ),
		array(
            'name'      => 'Applay portfolio',
            'slug'      => 'applay-portfolio',
            'required'  => false
        ),
		array(
            'name'      => 'Applay - Shortcodes',
            'slug'      => 'applay-shortcodes',
            'required'  => false
        ),
		array(
            'name'      => 'Applay Showcase',
            'slug'      => 'applay-showcase',
            'required'  => false
        ),
		array(
            'name'      => 'WPBakery Visual Composer',
            'slug'      => 'js_composer',
            'required'  => false
        ),
		array(
            'name'      => 'WooCommerce',
            'slug'      => 'woocommerce',
            'required'  => false
        ),
		array(
            'name'      => 'Black Studio TinyMCE Widget',
            'slug'      => 'black-studio-tinymce-widget',
            'required'  => false
        ),
		array(
            'name'      => 'Contact Form 7',
            'slug'      => 'contact-form-7',
            'required'  => false
        ),
		array(
            'name'      => 'Flickr Badges Widget',
            'slug'      => 'flickr-badges-widget',
            'required'  => false
        ),
		array(
            'name'      => 'Custom Sidebars',
            'slug'      => 'custom-sidebars',
            'required'  => false
        ),
		array(
            'name'      => 'WP Pagenavi',
            'slug'      => 'wp-pagenavi',
            'required'  => false
        ),
    );
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
require_once locate_template('/inc/tgm/class-tgm-plugin-activation.php' );

if( !defined('PARENT_THEME') )
{
	define('PARENT_THEME','Applay');
}

function custom_excerpt_length( $length ) {
	return 35;
}
add_filter( 'excerpt_length', 'custom_excerpt_length', 999 );

/**
 * Registers the WordPress features
 */
function leafcolor_setup() {
	/*
	 * Makes theme available for translation.
	 */
	load_theme_textdomain( 'leafcolor', get_template_directory() . '/languages' );

	// This theme styles the visual editor with editor-style.css to match the theme style.
	add_editor_style();

	// Adds RSS feed links to <head> for posts and comments.
	add_theme_support( 'automatic-feed-links' );

	// Post formats.
	add_theme_support( 'post-formats', array( 'gallery', 'video', 'audio' ) );

	// Register menus.
	register_nav_menu( 'primary-menus', __( 'Primary Menus', 'leafcolor' ) );
	register_nav_menu( 'top-menus', __( 'Top Menus', 'leafcolor' ) );
	register_nav_menu( 'footer-menus', __( 'Footer Menus', 'leafcolor' ) );
	register_nav_menu( 'off-canvas-menus', __( 'Off Canvas Menus', 'leafcolor' ) );
	register_nav_menu( 'login-menus', __( 'Login Menus', 'leafcolor' ) );
	register_nav_menu( 'register-menus', __( 'Register Menus', 'leafcolor' ) );

	// Featured images.
	add_theme_support( 'post-thumbnails' );
	
	// Supports woocommerce.
	add_theme_support( 'woocommerce' );
}
add_action( 'after_setup_theme', 'leafcolor_setup' );

/**
 * Enqueues scripts and styles
 */
function leafcolor_scripts_styles() {
	/*
	 * Loads js.
	 */	
	wp_enqueue_script( 'jquery');
	wp_enqueue_script( 'bootstrap', get_template_directory_uri() . '/js/bootstrap.min.js', array(), '', true );
	wp_enqueue_script( 'owl-carousel', get_template_directory_uri() . '/js/owl-carousel/owl.carousel.min.js', array('jquery'), '', true );
	wp_enqueue_script( 'template', get_template_directory_uri() . '/js/applay.js', array('jquery'), '', true );
	/*
	 * Loads css
	 */
	$all_font = array();
	if(ot_get_option('main_font','Open Sans') || ot_get_option( 'heading_font','Oswald:400' )){
		if(ot_get_option('main_font','Open Sans') && ot_get_option('main_font')!='custom-font-1' && ot_get_option('main_font')!='custom-font-2'){
			$all_font[] = ot_get_option( 'main_font','Open Sans' );
		}
		if(ot_get_option('heading_font','Oswald:400') && ot_get_option('heading_font')!='custom-font-1' && ot_get_option('heading_font')!='custom-font-2'){
			$all_font[] = ot_get_option( 'heading_font','Oswald:400' );
		}
		$all_font=implode('|',$all_font);
		if((ot_get_option('main_font','Open Sans') && ot_get_option('main_font')!='custom-font-1' && ot_get_option('main_font')!='custom-font-2') || (ot_get_option('heading_font','Oswald:400') && ot_get_option('heading_font')!='custom-font-1' && ot_get_option('heading_font')!='custom-font-2')){
			wp_enqueue_style( 'google-font', '//fonts.googleapis.com/css?family='.$all_font );
		}
	}
	wp_enqueue_style( 'bootstrap', get_template_directory_uri() . '/css/bootstrap.min.css');
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() .'/css/fa/css/font-awesome.min.css');
	wp_enqueue_style( 'owl-carousel', get_template_directory_uri() .'/js/owl-carousel/owl.carousel.css');
	wp_enqueue_style( 'owl-carousel-theme', get_template_directory_uri() .'/js/owl-carousel/owl.theme.css');
	wp_enqueue_style( 'lightbox2', get_template_directory_uri() . '/js/colorbox/colorbox.css');
	wp_enqueue_style( 'style', get_stylesheet_directory_uri() . '/style.css');
	if(is_plugin_active( 'bbpress/bbpress.php' )){
		wp_enqueue_style( 'app-bbpress', get_template_directory_uri() . '/css/app-bbpress.css');
	}
	if(ot_get_option( 'app-theme-style', 1)!=2){
		wp_enqueue_style( 'modern-style', get_stylesheet_directory_uri() . '/css/modern-style.css');
	}
	wp_enqueue_style( 'dynamic-css', get_template_directory_uri() . '/css/dynamic_css.php');
	wp_enqueue_style( 'okta-theme', get_template_directory_uri() . '/css/okta-theme.css');
	wp_enqueue_style( 'okta-sign-in.min', get_template_directory_uri() . '/css/okta-sign-in.min.css');
	if(ot_get_option( 'right_to_left', 0)){
		wp_enqueue_style( 'rtl', get_template_directory_uri() . '/rtl.css');
	}
	
	if(is_singular() ) wp_enqueue_script( 'comment-reply' );
}
add_action( 'wp_enqueue_scripts', 'leafcolor_scripts_styles' );

/* Enqueues for Admin */
function leafcolor_admin_scripts_styles() {
	wp_enqueue_style( 'font-awesome', get_template_directory_uri() .'/css/fa/css/font-awesome.min.css');
}
add_action( 'admin_enqueue_scripts', 'leafcolor_admin_scripts_styles' );

/**
 * Registers our main widget area and the front page widget areas.
 *
 * @since Twenty Twelve 1.0
 */
function leafcolor_widgets_init() {
	$rtl = ot_get_option( 'righttoleft', 0);

	register_sidebar( array(
		'name' => __( 'Main Sidebar', 'leafcolor' ),
		'id' => 'main_sidebar',
		'description' => __( 'Appears on posts and pages except the optional Front Page template, which has its own widgets', 'leafcolor' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-inner">',
		'after_widget' => '</div></div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Pathway Sidebar', 'leafcolor' ),
		'id' => 'pathway_sidebar',
		'description' => __( 'Replace Pathway (Breadcrumbs) with your widgets', 'leafcolor' ),
		'before_widget' => '<div id="%1$s" class="pathway-widget %2$s">',
		'after_widget' => '</div>',
		'before_title' => '<h3>',
		'after_title' => '</h3>',
	));
	register_sidebar( array(
		'name' => __( 'Front Page Sidebar ', 'leafcolor' ),
		'id' => 'frontpage_sidebar',
		'description' => __( 'Used in Front Page templates only', 'leafcolor' ),
		'before_widget' => '<div id="%1$s" class="widget frontpage-widget %2$s"><div class="widget-inner">',
		'after_widget' => '</div></div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Bottom Sidebar', 'leafcolor' ),
		'id' => 'bottom_sidebar',
		'description' => __( '', 'leafcolor' ),
		'before_widget' => '<div id="%1$s" class="col-md-3 widget %2$s"><div class="widget-inner">',
		'after_widget' => '</div></div>',
		'before_title' => '<h2 class="widget-title maincolor1">',
		'after_title' => '</h2>',
	));
	register_sidebar( array(
		'name' => __( 'Footer Sidebar', 'leafcolor' ),
		'id' => 'footer_sidebar',
		'description' => __( '', 'leafcolor' ),
		'before_widget' => '<div id="%1$s" class="col-md-3 widget %2$s"><div class="widget-inner">',
		'after_widget' => '</div></div>',
		'before_title' => '<h2 class="widget-title maincolor1">',
		'after_title' => '</h2>',
	));
	if (class_exists('Woocommerce')) {
	register_sidebar( array(
		'name' => __( 'Woocommerce Sidebar', 'leafcolor' ),
		'id' => 'woocommerce_sidebar',
		'description' => __( '', 'leafcolor' ),
		'before_widget' => '<div id="%1$s" class="widget %2$s"><div class="widget-inner">',
		'after_widget' => '</div></div>',
		'before_title' => $rtl ? '<h2 class="widget-title maincolor2">' : '<h2 class="widget-title maincolor2">',
		'after_title' => $rtl ? '</h2>' : '</h2>',
	));
	}
}
add_action( 'widgets_init', 'leafcolor_widgets_init' );

add_image_size('thumb_139x89',139,89, true); //widget
add_image_size('thumb_80x80',80, 80, true); //widget
add_image_size('thumb_263x263',263,263, true); 
add_image_size('thumb_409x258',409,258, true); //blog listing
//Retina
add_image_size('thumb_278x178',278,178, true); //widget
add_image_size('thumb_500x500',500, 500, true); //GRID
add_image_size('thumb_800x400',800, 400, true); //GRID Featured
add_image_size('thumb_526x526',526,526, true); //shortcode blog

//Hook widget 'SEARCH'
function SearchFilter($query) {
if ($query->is_search) {
$query->set('post_type', 'post');
}
return $query;
}
add_filter('pre_get_posts','SearchFilter');


add_filter('get_search_form', 'leaf_search_form'); 
function leaf_search_form($text) {
	$text = str_replace('value=""', 'placeholder="'.__("SEARCH").'"', $text);
    return $text;
}
function leaf_global_title(){
	if(is_search()){
		$page_title = __('Search Result: ','leafcolor').(isset($_GET['s'])?$_GET['s']:'');
	}elseif(is_category()){
		$page_title = single_cat_title('',false);
	}elseif(is_tag()){
		$page_title = single_tag_title('',false);
	}elseif(is_tax()){
		$page_title = single_term_title('',false);
	}elseif(is_author()){
		$page_title = __("Author: ",'leafcolor') . get_the_author();
	}elseif(is_day()){
		$page_title = __("Archives for ",'leafcolor') . date_i18n(get_option('date_format') ,strtotime(get_the_date()));
	}elseif(is_month()){
		$page_title = __("Archives for ",'leafcolor') . get_the_date('F, Y');
	}elseif(is_year()){
		$page_title = __("Archives for ",'leafcolor') . get_the_date('Y');
	}elseif(is_home()){
		if(get_option('page_for_posts')){ $page_title = get_the_title(get_option('page_for_posts'));
		}else{
			$page_title = get_bloginfo('name');
		}
	}elseif(is_404()){
		$page_title = ot_get_option('page404_title','404 - Page Not Found');
	}else if(  function_exists ( "is_shop" ) && is_shop()){
			$page_title = woocommerce_page_title($echo = false);
    }else{
		global $post;
		if($post){$page_title = $post->post_title;}
	}
	return $page_title;
}
if(!function_exists('ia_breadcrumbs')){
	function ia_breadcrumbs(){
		/* === OPTIONS === */
		$text['home']     = __('Home','leafcolor'); // text for the 'Home' link
		$text['category'] = '%s'; // text for a category page
		$text['search']   = __('Search Results for','leafcolor').' "%s"'; // text for a search results page
		$text['tag']      = __('Tag','leafcolor').' "%s"'; // text for a tag page
		$text['author']   = __('Author','leafcolor').' %s'; // text for an author page
		$text['404']      = __('404','leafcolor'); // text for the 404 page

		$show_current   = 0; // 1 - show current post/page/category title in breadcrumbs, 0 - don't show
		$show_on_home   = 1; // 1 - show breadcrumbs on the homepage, 0 - don't show
		$show_home_link = 1; // 1 - show the 'Home' link, 0 - don't show
		$show_title     = 1; // 1 - show the title for the links, 0 - don't show
		$delimiter      = ' &rsaquo; '; // delimiter between crumbs
		$before         = '<span class="current">'; // tag before the current crumb
		$after          = '</span>'; // tag after the current crumb
		/* === END OF OPTIONS === */

		global $post;
		$home_link    = home_url('/');
		$link_before  = '<span typeof="v:Breadcrumb">';
		$link_after   = '</span>';
		$link_attr    = ' rel="v:url" property="v:title"';
		$link         = $link_before . '<a' . $link_attr . ' href="%1$s">%2$s</a>' . $link_after;
		$parent_id    = $parent_id_2 = ($post) ? $post->post_parent : 0;
		$frontpage_id = get_option('page_on_front');

		if (is_front_page()) {

			if ($show_on_home == 1) echo '<div class="breadcrumbs"><a href="' . esc_url($home_link) . '">' . $text['home'] . '</a></div>';

		}elseif(is_home()){
			$title = get_option('page_for_posts')?get_the_title(get_option('page_for_posts')):__('Blog','leafcolor');
			echo '<div class="breadcrumbs"><a href="' . esc_url($home_link) . '">' . $text['home'] . '</a> &rsaquo; '.$title.'</div>';
		} else {

			echo '<div class="breadcrumbs" xmlns:v="http://rdf.data-vocabulary.org/#">';
			if ($show_home_link == 1) {
				if(function_exists ( "is_shop" ) && is_shop()){
					
				}else{
					echo '<a href="' . esc_url($home_link) . '" rel="v:url" property="v:title">' . $text['home'] . '</a>';
					if ($frontpage_id == 0 || $parent_id != $frontpage_id) echo $delimiter;
				}
			}

			if ( is_category() ) {
				$this_cat = get_category(get_query_var('cat'), false);
				if ($this_cat->parent != 0) {
					$cats = get_category_parents($this_cat->parent, TRUE, $delimiter);
					if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
					$cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
					$cats = str_replace('</a>', '</a>' . $link_after, $cats);
					if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
					echo $cats;
				}
				if ($show_current == 1) echo $before . sprintf($text['category'], single_cat_title('', false)) . $after;

			} elseif ( is_search() ) {
				echo $before . sprintf($text['search'], get_search_query()) . $after;

			} elseif ( is_day() ) {
				echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
				echo sprintf($link, get_month_link(get_the_time('Y'),get_the_time('m')), get_the_time('F')) . $delimiter;
				echo $before . get_the_time('d') . $after;

			} elseif ( is_month() ) {
				echo sprintf($link, get_year_link(get_the_time('Y')), get_the_time('Y')) . $delimiter;
				echo $before . get_the_time('F') . $after;

			} elseif ( is_year() ) {
				echo $before . get_the_time('Y') . $after;

			} elseif ( is_single() && !is_attachment() ) {
				if ( get_post_type() != 'post' ) {
					$post_type = get_post_type_object(get_post_type());
					$slug = $post_type->rewrite;
					printf($link, $home_link . $slug['slug'] . '/', $post_type->labels->singular_name);
					if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;
				} else {
					$cat = get_the_category(); $cat = $cat[0];
					$cats = get_category_parents($cat, TRUE, $delimiter);
					if ($show_current == 0) $cats = preg_replace("#^(.+)$delimiter$#", "$1", $cats);
					$cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
					$cats = str_replace('</a>', '</a>' . $link_after, $cats);
					if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
					echo $cats;
					if ($show_current == 1) echo $before . get_the_title() . $after;
				}

			} elseif ( !is_single() && !is_page() && get_post_type() != 'post' && !is_404() ) {
				if(function_exists ( "is_shop" ) && is_shop()){
					do_action( 'woocommerce_before_main_content' );
					do_action( 'woocommerce_after_main_content' );
				}else{
					$post_type = get_post_type_object(get_post_type());
					echo $before . $post_type->labels->singular_name . $after;
				}

			} elseif ( is_attachment() ) {
				$parent = get_post($parent_id);
				$cat = get_the_category($parent->ID); $cat = isset($cat[0])?$cat[0]:'';
				if($cat){
					$cats = get_category_parents($cat, TRUE, $delimiter);
					$cats = str_replace('<a', $link_before . '<a' . $link_attr, $cats);
					$cats = str_replace('</a>', '</a>' . $link_after, $cats);
					if ($show_title == 0) $cats = preg_replace('/ title="(.*?)"/', '', $cats);
					echo $cats;
				}
				printf($link, get_permalink($parent), $parent->post_title);
				if ($show_current == 1) echo $delimiter . $before . get_the_title() . $after;

			} elseif ( is_page() && !$parent_id ) {
				if ($show_current == 1) echo $before . get_the_title() . $after;

			} elseif ( is_page() && $parent_id ) {
				if ($parent_id != $frontpage_id) {
					$breadcrumbs = array();
					while ($parent_id) {
						$page = get_page($parent_id);
						if ($parent_id != $frontpage_id) {
							$breadcrumbs[] = sprintf($link, get_permalink($page->ID), get_the_title($page->ID));
						}
						$parent_id = $page->post_parent;
					}
					$breadcrumbs = array_reverse($breadcrumbs);
					for ($i = 0; $i < count($breadcrumbs); $i++) {
						echo $breadcrumbs[$i];
						if ($i != count($breadcrumbs)-1) echo $delimiter;
					}
				}
				if ($show_current == 1) {
					if ($show_home_link == 1 || ($parent_id_2 != 0 && $parent_id_2 != $frontpage_id)) echo $delimiter;
					echo $before . get_the_title() . $after;
				}

			} elseif ( is_tag() ) {
				echo $before . sprintf($text['tag'], single_tag_title('', false)) . $after;

			} elseif ( is_author() ) {
				global $author;
				$userdata = get_userdata($author);
				echo $before . sprintf($text['author'], $userdata->display_name) . $after;

			} elseif ( is_404() ) {
				echo $before . $text['404'] . $after;
			}

			if ( get_query_var('paged') ) {
				if(function_exists ( "is_shop" ) && is_shop()){
				}else{
					if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_home() ) echo ' (';
					echo __('Page','leafcolor') . ' ' . get_query_var('paged');
					if ( is_category() || is_day() || is_month() || is_year() || is_search() || is_tag() || is_author() || is_home() ) echo ')';
				}
			}

			echo '</div>';

		}
	}
}
/* Display Icon Links to some social networks */
if(!function_exists('leafcolor_social_share')){
function leafcolor_social_share($id=false){
	if(!$id){ $id = get_the_ID(); }
	?>
	<?php if(ot_get_option('share_facebook','on')!='off'){ ?>
	<li><a class="btn btn-default btn-lighter social-icon" title="<?php _e('Share on Facebook','leafcolor');?>" href="#" target="_blank" rel="nofollow" onclick="window.open('https://www.facebook.com/sharer/sharer.php?u='+'<?php echo urlencode(get_permalink($id)); ?>','facebook-share-dialog','width=626,height=436');return false;"><i class="fa fa-facebook"></i></a></li>
    <?php } ?>
    <?php if(ot_get_option('share_twitter','on')!='off'){ ?>
    <li><a class="btn btn-default btn-lighter social-icon" href="#" title="<?php _e('Share on Twitter','leafcolor');?>" rel="nofollow" target="_blank" onclick="window.open('http://twitter.com/share?text=<?php echo urlencode(get_the_title($id)); ?>&url=<?php echo urlencode(get_permalink($id)); ?>','twitter-share-dialog','width=626,height=436');return false;"><i class="fa fa-twitter"></i></a></li>
    <?php } ?>
    <?php if(ot_get_option('share_linkedin','on')!='off'){ ?>
    <li><a class="btn btn-default btn-lighter social-icon" href="#" title="<?php _e('Share on LinkedIn','leafcolor');?>" rel="nofollow" target="_blank" onclick="window.open('http://www.linkedin.com/shareArticle?mini=true&url=<?php echo urlencode(get_permalink($id)); ?>&title=<?php echo urlencode(get_the_title($id)); ?>&source=<?php echo urlencode(get_bloginfo('name')); ?>','linkedin-share-dialog','width=626,height=436');return false;"><i class="fa fa-linkedin"></i></a></li>
    <?php } ?>
    <?php if(ot_get_option('share_tumblr','on')!='off'){ ?>
    <li><a class="btn btn-default btn-lighter social-icon" href="#" title="<?php _e('Share on Tumblr','leafcolor');?>" rel="nofollow" target="_blank" onclick="window.open('http://www.tumblr.com/share/link?url=<?php echo urlencode(get_permalink($id)); ?>&name=<?php echo urlencode(get_the_title($id)); ?>','tumblr-share-dialog','width=626,height=436');return false;"><i class="fa fa-tumblr"></i></a></li>
    <?php } ?>
    <?php if(ot_get_option('share_google_plus','on')!='off'){ ?>
    <li><a class="btn btn-default btn-lighter social-icon" href="#" title="<?php _e('Share on Google Plus','leafcolor');?>" rel="nofollow" target="_blank" onclick="window.open('https://plus.google.com/share?url=<?php echo urlencode(get_permalink($id)); ?>','googleplus-share-dialog','width=626,height=436');return false;"><i class="fa fa-google-plus"></i></a></li>
    <?php } ?>
    <?php if(ot_get_option('share_pinterest','on')!='off'){ ?>
    <li><a class="btn btn-default btn-lighter social-icon" href="#" title="<?php _e('Pin this','leafcolor');?>" rel="nofollow" target="_blank" onclick="window.open('//pinterest.com/pin/create/button/?url=<?php echo urlencode(get_permalink($id)) ?>&media=<?php echo urlencode(wp_get_attachment_url( get_post_thumbnail_id($id))); ?>&description=<?php echo urlencode(get_the_title($id)) ?>','pin-share-dialog','width=626,height=436');return false;"><i class="fa fa-pinterest"></i></a></li>
    <?php } ?>
    <?php if(ot_get_option('share_email','on')!='off'){ ?>
    <li><a class="btn btn-default btn-lighter social-icon" href="mailto:?subject=<?php echo esc_attr(get_the_title($id)) ?>&body=<?php echo urlencode(get_permalink($id)) ?>" title="<?php _e('Email this','leafcolor');?>"><i class="fa fa-envelope"></i></a></li>
    <?php } ?>
<?php }
}

/*Default image*/
if(!function_exists('ia_print_default_thumbnail')){
	function ia_print_default_thumbnail($thumb = ''){
		return array(get_template_directory_uri().'/images/default-photo.png',500,500);
	}
}

/*Hook Row Visual Composer*/
function vc_theme_before_vc_row($atts, $content = null) {
	$style = isset($atts['ia_row_style'])?$atts['ia_row_style']:0; //style full width or not
	$paralax = isset($atts['ia_row_paralax'])?$atts['ia_row_paralax']:0;
	$scheme = isset($atts['ia_row_scheme'])?$atts['ia_row_scheme']:0;
	global $global_page_layout;
	ob_start(); 
	?>
		<div class="ia_row<?php if ($style || $global_page_layout=='true-full'){?> ia_full_row <?php } if($scheme){?> dark-div <?php } if($paralax){?> ia_paralax <?php }?>">
        <?php if(!$style && $global_page_layout=='true-full'){ ?>
        	<div class="container">
        <?php }?>
	<?php
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;
}
function vc_theme_after_vc_row($atts, $content = null) {
	$style = isset($atts['ia_row_style'])?$atts['ia_row_style']:0; //style full width or not
	global $global_page_layout;
	ob_start(); ?>
    	<?php if(!$style && $global_page_layout=='true-full'){ ?>
        	</div>
        <?php }?>
		</div><!--/ia_row-->
	<?php
	$output_string = ob_get_contents();
	ob_end_clean();
	return $output_string;
}


add_action( 'after_setup_theme', 'ia_extend_vc_row_param' );
function ia_extend_vc_row_param(){
	$attributes = array(
		'type' => 'dropdown',
		'heading' => "Row Style",
		'param_name' => 'ia_row_style',
		'value' => array(
			__('Default (In container)', 'leafcolor') => 0,
			__('Full-width (Side to side)', 'leafcolor') => 1,
		 ),
		'description' => __("Choose row width (In page template Front Page, this is used for row's content)", 'leafcolor')
	);
	if(function_exists('vc_add_param')){
		vc_add_param('vc_row', $attributes);
	}
}

add_action( 'after_setup_theme', 'ia_extend_vc_row_param2', 10, 15 );
function ia_extend_vc_row_param2(){
	$attributes = array(
		'type' => 'dropdown',
		'heading' => "Row Parallax Effect",
		'param_name' => 'ia_row_paralax',
		'value' => array(
			__('No', 'leafcolor') => 0,
			__('Yes', 'leafcolor') => 1,
		 ),
		'description' => __("Enable parallax effect for row's background", 'leafcolor')
	);
	if(function_exists('vc_add_param')){
		vc_add_param('vc_row', $attributes);
	}
}

add_action( 'after_setup_theme', 'ia_extend_vc_row_param3', 10, 20 );
function ia_extend_vc_row_param3(){
	$attributes = array(
		'type' => 'dropdown',
		'heading' => "Row Scheme",
		'param_name' => 'ia_row_scheme',
		'value' => array(
			__('Default', 'leafcolor') => 0,
			__('Dark', 'leafcolor') => 1,
		 ),
		'description' => __("Choose row scheme (in Dark, default text, buttons will have white color)", 'leafcolor')
	);
	if(function_exists('vc_add_param')){
		vc_add_param('vc_row', $attributes);
	}
}

//mime type
function ia_upload_mimes( $existing_mimes=array() ) {
	$existing_mimes['webp'] = 'image/webp';
	$existing_mimes['apk'] = 'application/vnd.android.package-archive';
	return $existing_mimes;
}
add_filter('upload_mimes', 'ia_upload_mimes');

//image url to id
function ia_get_attachment_id_from_url( $attachment_url = '' ) {
	global $wpdb;
	$attachment_id = false;
	// If there is no url, return.
	if ( '' == $attachment_url )
		return;
	// Get the upload directory paths
	$upload_dir_paths = wp_upload_dir();
	// Make sure the upload path base directory exists in the attachment URL, to verify that we're working with a media library image
	if ( false !== strpos( $attachment_url, $upload_dir_paths['baseurl'] ) ) {
		// If this is the URL of an auto-generated thumbnail, get the URL of the original image
		$attachment_url = preg_replace( '/-\d+x\d+(?=\.(jpg|jpeg|png|gif)$)/i', '', $attachment_url );
		// Remove the upload path base directory from the attachment URL
		$attachment_url = str_replace( $upload_dir_paths['baseurl'] . '/', '', $attachment_url );
		// Finally, run a custom database query to get the attachment ID from the modified attachment URL
		$attachment_id = $wpdb->get_var( $wpdb->prepare( "SELECT wposts.ID FROM $wpdb->posts wposts, $wpdb->postmeta wpostmeta WHERE wposts.ID = wpostmeta.post_id AND wpostmeta.meta_key = '_wp_attached_file' AND wpostmeta.meta_value = '%s' AND wposts.post_type = 'attachment'", $attachment_url ) );
	}
	return $attachment_id;
}
function ia_woo_related_products() {
  global $product;
	
	$args['posts_per_page'] = 3;
	return $args;
}
add_filter( 'woocommerce_output_related_products_args', 'ia_woo_related_products' );
function ia_get_app_icon($post_id, $size = 'thumbnail'){
	$icon = get_post_meta($post_id,'app-icon',true);
	if($icon_id = ia_get_attachment_id_from_url($icon)){
		$thumbnail = wp_get_attachment_image_src($icon_id, $size, true);
		$icon = isset($thumbnail[0])?$thumbnail[0]:$icon;
	}
	return $icon;
}
//Submit contact form 7 hook
function leaf_contactform7_hook($WPCF7_ContactForm) {
	if(ot_get_option('user_submit',1)){
		$submission = WPCF7_Submission::get_instance();
		if($submission) {
			$posted_data = $submission->get_posted_data();
			//error_log(print_r($posted_data, true));
			if(isset($posted_data['store-link-apple']) || isset($posted_data['store-link-google']) || isset($posted_data['app-url'])){
				if(!isset($posted_data['app-url']) && isset($posted_data['store-link-apple'])){
					$app_url = $posted_data['store-link-apple'];
				}else{
					$app_url = $posted_data['app-url'];
					if(strpos($app_url, 'apple.com') !== false){
						$_POST['store-link-apple'] = $app_url;
					}elseif(strpos($app_url, 'google.com') !== false){
						$_POST['store-link-google'] = $app_url;
					}
				}
				$app_title = isset($posted_data['app-title'])?$posted_data['app-title']:'';
				$app_description = isset($posted_data['app-description'])?$posted_data['app-description']:'';
				$app_excerpt = isset($posted_data['app-excerpt'])?$posted_data['app-excerpt']:'';
				$app_user = isset($posted_data['your-email'])?$posted_data['your-email']:'';
				$app_cat = isset($posted_data['cat'])?$posted_data['cat']:'';
				$app_tag = isset($posted_data['tag'])?$posted_data['tag']:'';
				$app_status = ot_get_option('user_submit_status','pending');
				$app_tag = explode(",",$app_tag);
				$app_post = array(
				  'post_content'   => $app_description,
				  'post_excerpt'   => $app_excerpt,
				  'post_name' 	   => sanitize_title($app_title), //slug
				  'post_title'     => $app_title,
				  'post_status'    => $app_status,
				  'post_type'      => 'product'
				);
				if($new_ID = wp_insert_post( $app_post, $wp_error )){
					if(strpos($app_url, 'apple.com') !== false){
						add_post_meta( $new_ID, 'store-link-apple', $app_url );
					}elseif(strpos($app_url, 'google.com') !== false){
						add_post_meta( $new_ID, 'store-link-google', $app_url );
					}elseif(strpos($app_url, 'windowsphone.com') !== false){
						add_post_meta( $new_ID, 'store-link-windows', $app_url );
					}
					if(!ot_get_option('user_submit_fetch',0)){
						$_POST['fetch_data_itunes']='off';
						$_POST['set-post-thumb']='off';
					}
					add_post_meta( $new_ID, 'user_submit', $app_user );
					$app_post['ID'] = $new_ID;
					wp_set_object_terms( $new_ID, $app_cat, 'product_cat' );
					wp_set_object_terms( $new_ID, $app_tag, 'product_tag' );
					wp_update_post( $app_post );
				}
			}
		}//if submission
	}
}
add_action("wpcf7_before_send_mail", "leaf_contactform7_hook");

function leaf_wpcf7_add_shortcode(){
	if(function_exists('wpcf7_add_shortcode')){
		wpcf7_add_form_tag(array('category','category*'), 'leaf_catdropdown', true);
	}
}
function leaf_catdropdown($tag){
	$class = '';
	$is_required = 0;
	if(class_exists('WPCF7_Shortcode')){
		$tag = new WPCF7_Shortcode( $tag );
		if ( $tag->is_required() ){
			$is_required = 1;
			$class .= ' required-cat';
		}
	}
	$cargs = array(
		'hide_empty'    => false, 
		'exclude'       => explode(",",ot_get_option('user_submit_cat_exclude',''))
	); 
	$cats = get_terms( 'product_cat', $cargs );
	if($cats){
		$output = '<div class="wpcf7-form-control-wrap cat"><div class="row wpcf7-form-control wpcf7-checkbox wpcf7-validates-as-required'.$class.'">';
		foreach ($cats as $acat){
			$output .= '<label class="col-md-4 wpcf7-list-item"><input type="checkbox" name="cat[]" value="'.$acat->slug.'" /> '.$acat->name.'</label>';
		}
		$output .= '</div></div>';
	}
	ob_start();
	if($is_required){
	?>
    <script>
	jQuery(document).ready(function(e) {
		jQuery("form.wpcf7-form").submit(function (e) {
			var checked = 0;
			jQuery.each(jQuery("input[name='cat[]']:checked"), function() {
				checked = jQuery(this).val();
			});
			if(checked == 0){
				if(jQuery('.cat-alert').length==0){
					jQuery('.wpcf7-form-control-wrap.cat').append('<span role="alert" class="wpcf7-not-valid-tip cat-alert"><?php _e('Please choose a category','leafcolor') ?>.</span>');
				}
				return false;
			}else{
				return true;
			}
		});
	});
	</script>
	<?php
	}
	$js_string = ob_get_contents();
	ob_end_clean();
	return $output.$js_string;
}
add_action( 'init', 'leaf_wpcf7_add_shortcode' );

//mail after publish
add_action( 'save_post', 'notify_user_submit');
function notify_user_submit( $post_id ) {
	if ( wp_is_post_revision( $post_id ) || !ot_get_option('user_submit_notify',1) )
		return;
	$notified = get_post_meta($post_id,'notified',true);
	$email = get_post_meta($post_id,'tm_user_submit',true);
	if(!$notified && $email && get_post_status($post_id)=='publish'){
		$subject = __('Your app submission has been approved','leafcolor');
		$message = __('Your app ','leafcolor').' '.__('has been approved. You can see it here','leafcolor').' '.get_permalink($post_id);
		wp_mail( $email, $subject, $message );
		update_post_meta( $post_id, 'notified', 1);
	}
}

/* Functions, Hooks, Filters and Registers in Admin */
require_once 'inc/starter/functions-admin.php';

if(!function_exists('leaf_add_query_ct')) {
	add_action( 'pre_get_posts', 'leaf_add_query_ct' );
	/**
	 * add custom post type to main cat query
	 */
	function leaf_add_query_ct( $query ) {
		if ($query->is_main_query() && is_category()) {
			$query->set( 'post_type', array( 'post', 'app_portfolio' ) );
		}
		return $query;
	}
}

add_action( 'wp_login_failed', 'my_front_end_login_fail' );  // hook failed login

function my_front_end_login_fail( $username ) {
   $referrer = $_SERVER['HTTP_REFERER'];  // where did the post submission come from?
   // if there's a valid referrer, and it's not the default log-in screen
   if ( !empty($referrer) && !strstr($referrer,'wp-login') && !strstr($referrer,'wp-admin') ) {
      wp_redirect( $referrer . '?login=failed' );  // let's append some information (login=failed) to the URL for the theme to use
	  
	  // wp_redirect(home_url( 'partner-portal' ) . "&login_error" );  
	  exit;
   }
}


add_action('after_setup_theme', 'remove_admin_bar');
 
function remove_admin_bar() {
if (!current_user_can('administrator') && !is_admin()) {
  show_admin_bar(false);
}
}
// add_filter('show_admin_bar', '__return_false');   // for all users

function register_my_session()
{
  if( !session_id() )
  {
    session_start();
  }
}

add_action('init', 'register_my_session');



function zendesk_data ($url,$method,$data) 
{
	
	// $apiKey = "e2784ddd417026dac58e28b7c3a1c35f94f8979fd0c575a44cbfd6c236b1e4b4";
	// $baseUrl = "https://agehelp.zendesk.com";
	
	$apiKey = "445302580d23ffc9c4f4d8c905b632f28cd94574163e71ecf4a4675a0322ca5a";
	$baseUrl = "https://wine-oh.zendesk.com";

	$headers = array(
		'Authorization: Bearer ' . $apiKey,
		'Content-Type: application/json'      
	);

	$curl_url = $baseUrl. $url;

	$curl = curl_init();

	curl_setopt($curl, CURLOPT_URL, $curl_url);
	curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($curl, CURLOPT_HEADER, 0);
	curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
	curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
	curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);
   
	if ($method == "POST") 
	{      
		curl_setopt($curl, CURLOPT_POST, 1);
	}      
   
	if ($method == "GET") 
	{      
		curl_setopt($curl, CURLOPT_HTTPGET, 1);
	}
   
	if (!empty($data)) 
	{               
		curl_setopt($curl, CURLOPT_POSTFIELDS, json_encode($data));
	}
	
	if (($output = curl_exec($curl)) === FALSE) 
	{
		die("Curl Failed: " . curl_error($curl));
	}
	
	curl_close($curl);
	return json_decode($output);
}


function get_posts_data($str) 
	{
		$ch = curl_init();
		$timeout = 200;
		$url='http://wine-oh.io/wp-json/wp/v2/posts?search='.$str;
		//$url='http://localhost/wineoh/wp-json/wp/v2/posts?search='.$str;
		

		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST,false);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER,false);
		curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
		$data = curl_exec($ch);
		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		curl_close($ch);
		$data_array = json_decode($data, TRUE);
		return $data_array;
	}
