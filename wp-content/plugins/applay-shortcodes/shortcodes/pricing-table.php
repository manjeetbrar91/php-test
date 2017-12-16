<?php
function print_pricingtable($atts, $content)
{
    $id                 	= (isset($atts['id']) && $atts['id'] != '') ? $atts['id'] : '';
    $output_id              = ' id= "' . $id . '"';
    $class                 	= (isset($atts['class']) && $atts['class'] != '') ? $atts['class'] : '';
    $color 					= (isset($atts['color']) && $atts['color'] != '') ? 'color:' . $atts['color'] . ';' : '';
 
	$html = '
	<div ' . $output_id . ' class="container">
		<div class="row ' . $class . '">
			'.do_shortcode(str_replace('<br class="nc" />', '', $content)).'
		</div>
	</div>
	';

	$style = '';	
	$style .= '<style type="text/css">';
	$style .= '#' . $id . '{' . $color . '}';
	$style .= '</style>';

	return $html . $style;
}

function print_pricingtable_column($atts, $content)
{

 	$rand_ID              	=  rand(1, 9999);
    $id                 	= 'compare-table-colum-' . $rand_ID;
    $output_id              = ' id= "' . $id . '"';

    $class                 	= (isset($atts['class']) && $atts['class'] != '') ? $atts['class'] : '';
    $color 					= (isset($atts['color']) && $atts['color'] != '') ? 'color:' . $atts['color'] . ';' : '';
    $bg_color 				= (isset($atts['bg_color']) && $atts['bg_color'] != '') ? 'background:' . $atts['bg_color'] . ';' : '';
    $title 					= (isset($atts['title']) && $atts['title'] != '') ? $atts['title'] : 'Default Title';
	$price					= (isset($atts['price']) && $atts['price'] != '') ? $atts['price'] : '120';
	$price_text				= (isset($atts['price_text']) && $atts['price_text'] != '') ? $atts['price_text'] : 'per month';
	
	if((isset($atts['column']) && ($atts['column'] != '')))
	{
		if($atts['column'] == 1)
			$md_column = 12;
		else if($atts['column'] == 2)
			$md_column = 6;
		else if($atts['column'] == 3)
			$md_column = 4;
		else if($atts['column'] == 4)
			$md_column = 3;
		else
			$md_column = 4;
	}
	else
	{
		$md_column = 12;
	}
	
	$price_html = '<div class="compare-table-price first-column" style="'. $color . $bg_color.' "><span>' . $price . '</span> <span>' . $price_text . '</span></div>';

	$md_class = 'class="col-md-' . $md_column . ' ' . $class .' compare-table-wrapper"';

	$html = '
		<div ' . $md_class . '>
			<div class="table-pr">'.$price_html.'</div>
			<div class="compare-table" ' . $output_id . '>
				<div class="compare-table-border" >
					<div class="compare-table-title sc-column table-options"><span class="font-1">' . $title . '</span><span></span></div>
					'.do_shortcode(str_replace('<br class="nc" />', '', $content)).'
				</div>
			</div>
		</div> ';

	$style = '';
	$style .= '<style type="text/css">';
	$style .= '#' . $id . '{' . $color . $bg_color . '}';
	$style .= '#' . $id . ' *{' . $color.'}';
	$style .= '</style>';

	$html=str_replace("<p></p>","",$html);
	return $html . $style;
}

function print_pricingtable_row($atts, $content)
{
	$rand_ID              	=  rand(1, 9999);
    $id                 	= 'compare-table-row-' . $rand_ID;
    $output_id              = ' id= "' . $id . '"';

	$class                 	= (isset($atts['class']) && $atts['class'] != '') ? $atts['class'] : '';
	
	$html ='';
	$html .= '<div class="table-options' . $class . '" ' . $output_id . '>' .do_shortcode( $content) . '</div>';


	$html=str_replace("<p></p>","",$html);
	return $html;
}

add_shortcode( 'pricingtable', 'print_pricingtable' );
add_shortcode( 'c_column', 'print_pricingtable_column' );
add_shortcode( 'c_row', 'print_pricingtable_row' );

add_action( 'after_setup_theme', 'reg_leaf_pricingtable' );
function reg_leaf_pricingtable(){
	if(function_exists('vc_map')){
	vc_map( array(
			"name" => esc_html__("Pricing Table", "leafcolor"),
			"base" => "c_column",
			"content_element" => true,
			"as_parent" => array('only' => 'c_row'),
			"icon" => "icon-pricingtable",
			"params" => array(
				array(
					"type" => "textfield",
					"heading" => esc_html__("Column Title", "leafcolor"),
					"param_name" => "title",
					"value" => "Table Column",
					"description" => "",
					"admin_label" => true
				  ),
				array(
					"type" => "textfield",
					"heading" => esc_html__("CSS Class", "leafcolor"),
					"param_name" => "class",
					"value" => "",
					"description" => "",
				  ),
				  array(
					 "type" => "colorpicker",
					 "holder" => "div",
					 "class" => "",
					 "heading" => esc_html__("Background Color", 'leafcolor'),
					 "param_name" => "bg_color",
					 "value" => '',
					 "description" => '',
				  ),
				  array(
					"type" => "textfield",
					"heading" => esc_html__("Price", "leafcolor"),
					"param_name" => "price",
					"value" => "10$",
					"description" => "",
					"admin_label" => true
				  ),
				   array(
					"type" => "textfield",
					"heading" => esc_html__("Price Text", "leafcolor"),
					"param_name" => "price_text",
					"value" => "pm",
					"description" => "",
				  ),
				   array(
					 "type" => "colorpicker",
					 "holder" => "div",
					 "class" => "",
					 "heading" => esc_html__("Color", 'leafcolor'),
					 "param_name" => "color",
					 "value" => '',
					 "description" => '',
				  ),
				  
			),
			"js_view" => 'VcColumnView'
		) );
		vc_map( array(
			"name" => esc_html__("Row", "leafcolor"),
			"base" => "c_row",
			"content_element" => true,
			"as_child" => array('only' => 'c_row'), 
			"as_parent" => array('except' => 'comparetable'),
			"icon" => "icon-comparetable-row",
			"params" => array(
				array(
					"type" => "textarea_html",
					"heading" => esc_html__("Row Content", "leafcolor"),
					"param_name" => "content",
					"value" => "Content",
					"description" => "",
					"admin_label" => true
				  ),
				array(
					"type" => "textfield",
					"heading" => esc_html__("CSS Class", "leafcolor"),
					"param_name" => "class",
					"value" => "",
					"description" => "",
				  ),
			),
		) );
	}
	if(class_exists('WPBakeryShortCode') && class_exists('WPBakeryShortCodesContainer')){
		class WPBakeryShortCode_pricingtable extends WPBakeryShortCodesContainer{}
		class WPBakeryShortCode_c_column extends WPBakeryShortCodesContainer{}
		class WPBakeryShortCode_c_row extends WPBakeryShortCodesContainer{}
	}
}
