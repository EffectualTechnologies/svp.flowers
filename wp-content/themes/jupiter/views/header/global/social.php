<?php
/**
 * template part for social in header and header toolbar. views/header/global
 *
 * @author 		Artbees
 * @package 	jupiter/views
 * @version     5.0.0
 */

global $mk_options;

$is_enabled = isset($mk_options['header_social_location']) ? $mk_options['header_social_location'] : 'toolbar';

if ($is_enabled == 'disable') {
    return false;
} 
else if ($is_enabled != $view_params['location']) {
    return false;
}

$icon_style_css = '';

switch ($mk_options['header_social_networks_style']) {
    case 'rounded':
        $icon_style = 'mk-jupiter-icon-square-';
        break;

    case 'simple':
        $icon_style = 'mk-jupiter-icon-simple-';
        break;

    case 'circle':
        $icon_style = 'mk-jupiter-icon-';
        break;

    case 'simple-rounded':
        $icon_style = 'mk-jupiter-icon-simple-';
        $icon_style_css = 'mk-simple-rounded ';
        break;

    case 'square-rounded':
        $icon_style_css = 'mk-square-rounded ';
        $icon_style = 'mk-jupiter-icon-simple-';
        break;

    case 'square-pointed':
        $icon_style_css = 'mk-square-pointed ';
        $icon_style = 'mk-jupiter-icon-simple-';
        break;

    default:
        $icon_style = 'mk-jupiter-icon-';
}
$names = explode(",", $mk_options['header_social_networks_site']);
$urls = explode(",", $mk_options['header_social_networks_url']);

$header_social_style = $mk_options['header_social_networks_style'];
if (($header_social_style == 'square-pointed' || $header_social_style == 'square-rounded' || $header_social_style == 'simple-rounded') && $is_enabled == 'header') {
    $header_icon_size = $mk_options['header_icon_size'];
} 
else {
    $header_icon_size = '';
}

$output = '';
if (strlen(implode('', $urls)) != 0) {
    $output = '<div class="mk-header-social ' . $is_enabled . '-section">';
    $output.= '<ul>';
    foreach (array_combine($names, $urls) as $name => $url) {
        $output.= '<li><a class="' . $icon_style_css . $name . '-hover ' . $header_icon_size . '" target="_blank" href="' . $url . '"><i class="' . $icon_style . $name . '" alt="' . $name . '" title="' . $name . '"></i></a></li>';
    }
    $output.= '</ul>';
    
    $output.= '<div class="clearboth"></div></div>';
}

echo $output;
