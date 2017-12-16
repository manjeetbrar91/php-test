<?php
function print_ia_dropcap($atts, $content){
	$html = '<span class="dropcap">'.$content.'</span>';
	return $html;
}
add_shortcode( 'dropcap', 'print_ia_dropcap' );




















