<?php 
extract( shortcode_atts( array(
	'title' => __( "Section", "mk_framework" ),
	'icon' 	=> '',
), $atts ) );

if(!empty( $icon )) {
    $icon = (strpos($icon, 'mk-') !== FALSE) ? ( '<i class="'.$icon.'"></i>' ) : ( '<i class="mk-'.$icon.'"></i>' );    
} else {
	$icon = '';
}
Mk_Static_Files::addAssets('vc_accordion_tab');