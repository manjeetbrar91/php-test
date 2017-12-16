<?php
if( $_SESSION == null)
  {
    echo '<script>location.href="'.home_url().'/login/";</script>';
  }
?>