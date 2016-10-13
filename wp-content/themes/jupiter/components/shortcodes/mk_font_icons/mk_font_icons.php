<?php
$phpinfo =  pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );

$html = file_get_contents( $path . '/template.php' );
$html = phpQuery::newDocument( $html );

$id = Mk_Static_Files::shortcode_id();

$font_icon_container = pq('.mk-font-icons');
$font_icon_container->attr('id', 'mk-font-icons-'.$id);
$font_icon = $font_icon_container->find('.font-icon');
$animation_css = $circle_style = '';

// Main logic here
$color = !empty( $color ) ? ( 'color:' . $color .';' ) : '';

if ( $circle == 'true' ) {
	$font_icon->addClass('circle-enabled');
	$font_icon->addClass('center-icon');
	$circle_style = '
		background-color:'.$circle_color.';
		border-width: '.$circle_border_width.'px;
		border-color: '.$circle_border_color.';
		border-style: '.$circle_border_style.';
	';
}


$font_icon_container->addClass('icon-align-'.$align);
$font_icon_container->addClass(get_viewport_animation_class($animation));
$font_icon_container->addClass($el_class);

if ( $link ) {
	$font_icon->wrap('<a target="'.$target.'" href="'.$link.'" class="js-smooth-scroll">');
}
if(!empty( $icon )) {
    $icon = (strpos($icon, 'mk-') !== FALSE) ? $icon : ( 'mk-'.$icon.'' );
    $font_icon->addClass($icon);
    $font_icon->addClass('mk-size-'.$size); 
}


/**
 * Custom CSS Output
 * ==================================================================================*/


if($color_style == 'gradient_color'){
	$gradients = mk_gradient_option_parser($grandient_color_style, $grandient_color_angle);
	Mk_Static_Files::addCSS('
		#mk-font-icons-'.$id.' .font-icon::before {
	    	background: '.$grandient_color_fallback.';
			background: -webkit-'.$gradients['type'].'-gradient('.$gradients['angle_1'].''.$grandient_color_from.' 0%, '.$grandient_color_to.' 100%);
			background: '.$gradients['type'].'-gradient('.$gradients['angle_2'].''.$grandient_color_from.' 0%, '.$grandient_color_to.' 100%);
			-webkit-background-clip: text;
     		-webkit-text-fill-color: transparent;
	    }
	    @-moz-document url-prefix() {
			#mk-font-icons-'.$id.' .font-icon::before {
				background: transparent;
				color: '.$grandient_color_fallback.';
		  	}
		}
	', $id);

} else if ($color_style == 'single_color') {
	Mk_Static_Files::addCSS('
		#mk-font-icons-'.$id.' .font-icon {
			'.$color.'
		}
    ', $id);
}

Mk_Static_Files::addCSS('
	#mk-font-icons-'.$id.' {
		margin: '.$margin_vertical.'px '.$margin_horizental.'px;
	}
	#mk-font-icons-'.$id.' .font-icon {
		'.$circle_style.'
	}
', $id);

print $html;
