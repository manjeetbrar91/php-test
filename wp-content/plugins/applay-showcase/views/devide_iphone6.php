<?php global $global_devide_item ?>
<div class="cd-iphone-6 cd-fill-parent cd-center <?php echo @$global_devide_item['devide_color_iphone6']?'cd-'.$global_devide_item['devide_color_iphone6']:'cd-silver'; echo @$global_devide_item['orientation']?' cd-landscape-left':''; ?>">
  <div class="cd-body">
    <div class="cd-sound"></div>
    <div class="cd-sleep"></div>
    <div class="cd-camera"></div>
    <div class="cd-ear"></div>
    <div class="cd-home"></div>
    <div class="cd-screen">
      <?php echo iAppShowcase::ias_devide_content($global_devide_item); ?>
    </div>
  </div>
</div>