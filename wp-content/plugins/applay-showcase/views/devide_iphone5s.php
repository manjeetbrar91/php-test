<?php global $global_devide_item ?>
<div class="ia-iphone5s ias-devide <?php echo @$global_devide_item['devide_color_iphone5s']?$global_devide_item['devide_color_iphone5s']:'silver'; echo @$global_devide_item['orientation']?' landscape':''; ?>">
    <div class="device">
        <div class="inner"></div>
        <div class="sleep"></div>
        <div class="volume"></div>
        <div class="camera"></div>
        <div class="top-bar"></div>
        <div class="sensor"></div>
        <div class="speaker"></div>
        <div class="screen">
        	<?php echo iAppShowcase::ias_devide_content($global_devide_item); ?>
        </div>
        <div class="bottom-bar"></div>
        <div class="home"></div>
    </div>
</div>