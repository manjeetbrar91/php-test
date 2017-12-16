<?php
/**
 * The sidebar containing the main widget area.
 */
?>
<div id="sidebar" class="col-md-3 normal-sidebar">
<div  class="widget widget_search">
		<div class=" widget-inner">
			<form action="<?php echo home_url() ?>/search" method="post" class="searchform">
				<div>
					<label class="screen-reader-text" for="s">Search for:</label>
					<input type="text" placeholder="SEARCH" name="search_post" style="width: 100%;">
					<input type="submit" id="searchsubmit" value="Search">
				</div>
			</form>
		</div>
	</div>

	

<?php 
if(is_front_page() && is_active_sidebar('frontpage_sidebar')){
	dynamic_sidebar( 'frontpage_sidebar' );
}elseif(is_active_sidebar('woocommerce_sidebar') && function_exists('is_woocommerce') && is_woocommerce()){
	dynamic_sidebar( 'woocommerce_sidebar' );
}elseif(is_active_sidebar('main_sidebar')){
	dynamic_sidebar( 'main_sidebar' );
}
?>

</div><!--#sidebar-->
