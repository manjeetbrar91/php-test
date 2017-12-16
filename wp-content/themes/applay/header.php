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
    	<title><?php wp_title( '|', true, 'right' ); ?></title>
        <meta charset="<?php bloginfo( 'charset' ); ?>" />
        <meta name="viewport" content="width=device-width, minimum-scale=1.0, initial-scale=1.0">
        <link rel="profile" href="http://gmpg.org/xfn/11" />
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
        <link rel='stylesheet'  href='<?php bloginfo('template_url'); ?>/linear-winery-elements/font/flaticon.css' type='text/css' media='all' />
        <?php if(ot_get_option('favicon')){?>
        	<link rel="shortcut icon" type="ico" href="<?php echo esc_url(ot_get_option('favicon'));?>">
        <?php }		
		wp_head(); ?>

<!-- Segment Script Below. Add Destinations to Google Tag Manager instead of adding Scripts to Header. Segment calls GTM -->

<script>
  !function(){var analytics=window.analytics=window.analytics||[];if(!analytics.initialize)if(analytics.invoked)window.console&&console.error&&console.error("Segment snippet included twice.");else{analytics.invoked=!0;analytics.methods=["trackSubmit","trackClick","trackLink","trackForm","pageview","identify","reset","group","track","ready","alias","debug","page","once","off","on"];analytics.factory=function(t){return function(){var e=Array.prototype.slice.call(arguments);e.unshift(t);analytics.push(e);return analytics}};for(var t=0;t<analytics.methods.length;t++){var e=analytics.methods[t];analytics[e]=analytics.factory(e)}analytics.load=function(t){var e=document.createElement("script");e.type="text/javascript";e.async=!0;e.src=("https:"===document.location.protocol?"https://":"http://")+"cdn.segment.com/analytics.js/v1/"+t+"/analytics.min.js";var n=document.getElementsByTagName("script")[0];n.parentNode.insertBefore(e,n)};analytics.SNIPPET_VERSION="4.0.0";
  analytics.load("jEssvE9eWKHUiAcD8TtaifBzfy4vQRZz");
  analytics.page();
  }}();
</script>
<!-- Google Tag Manager (noscript) -->
<noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-KPQ292K"
height="0" width="0" style="display:none;visibility:hidden"></iframe></noscript>
<!-- End Google Tag Manager (noscript) -->
<script src="https://ok1static.oktacdn.com/assets/js/sdk/okta-signin-widget/1.13.0/js/okta-sign-in.min.js" type="text/javascript"></script>
    </head>

    <body <?php body_class() ?>>
    	<a style="height:0; position:absolute; top:0;" id="top"></a>
    	<?php if(ot_get_option('pre-loading',2)==1||(ot_get_option('pre-loading',2)==2&&(is_front_page()||is_page_template('page-templates/front-page.php')))){ ?>
            <div id="pageloader" class="dark-div" style="position:fixed; top:0; left:0; width:100%; height:100%; z-index:99999; background:<?php echo esc_attr(ot_get_option('loading_bg','#111')) ?>;">
                <div class="loader loader-2"><i></i><i></i><i></i><i></i></div>
            </div>
            <script>
				setTimeout(function() {
					jQuery('#pageloader').fadeOut();
				}, 30000);
            </script>
    	<?php }
    
        global $page_title;
        $page_title = leaf_global_title();
    ?>
    <div id="body-wrap">
        <div id="wrap">
            <header>
                <?php
                $content_head = get_post_meta(get_the_ID(),'header_content',true);
                if(function_exists('is_shop') && is_shop()){
                    $content_head ='';
                    $id_ot = get_option('woocommerce_shop_page_id');
                    if($id_ot!=''){
                        $content_head = get_post_meta($id_ot,'header_content',true);
                    }
                }
                if( is_home()){
                    $content_head ='';
                    $id_ot = get_option('page_for_posts');
                    if($id_ot!=''){
                        $content_head = get_post_meta($id_ot,'header_content',true);
                    }
                }
                get_template_part( 'templates/header/header', 'navigation' );
                if($content_head !=''){
                   get_template_part( 'templates/header/header', 'frontpage' );
                }
                ?>
            </header>