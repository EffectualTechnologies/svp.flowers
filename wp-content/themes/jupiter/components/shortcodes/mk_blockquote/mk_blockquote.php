<?php
$phpinfo =  pathinfo( __FILE__ );
$path = $phpinfo['dirname'];
include( $path . '/config.php' );

$html = file_get_contents( $path . '/template.php' );
$html = phpQuery::newDocument( $html );
$id = Mk_Static_Files::shortcode_id();

$container = pq('.mk-blockquote');
$container->attr('id', 'mk-blockquote-'.$id);
$container->addClass($style);
$container->addClass($el_class);
if ( $animation != '' ) {
	$container->addClass(get_viewport_animation_class($animation));
}

echo mk_get_fontfamily( "#mk-blockquote-", $id, $font_family, $font_type );

$container->html(wpb_js_remove_wpautop($content, true));

if($font_size_combat == 'true') {
	$app_styles = '
		#mk-blockquote-'.$id.' p {
			font-size: '.$text_size.'px;
		}
	';

	Mk_Static_Files::addCSS($app_styles, $id);
}

print $html;
