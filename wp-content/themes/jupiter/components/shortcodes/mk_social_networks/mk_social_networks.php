<?php
$path = pathinfo(__FILE__) ['dirname'];
include ($path . '/config.php');

$id = Mk_Static_Files::shortcode_id();

switch ($style) {
    case 'rounded':
        $icon_style = 'mk-jupiter-icon-square-';
        break;

    case 'simple':
        $icon_style = 'mk-jupiter-icon-simple-';
        break;

    case 'circle':
        $icon_style = 'mk-jupiter-icon-';
        break;

    default:
        $icon_style = 'mk-jupiter-icon-simple-';
}

$sites = array(
    'facebook' => $facebook,
    'twitter' => $twitter,
    'xing' => $xing,
    'rss' => $rss,
    'dribbble' => $dribbble,
    'instagram' => $instagram,
    'soundcloud' => $soundcloud,
    'digg' => $digg,
    'pinterest' => $pinterest,
    'flickr' => $flickr,
    'googleplus' => $google_plus,
    'skype' => $skype,
    'linkedin' => $linkedin,
    'blogger' => $blogger,
    'youtube' => $youtube,
    'lastfm' => $last_fm,
    'stumbleupon' => $stumble_upon,
    'tumblr' => $tumblr,
    'vimeo' => $vimeo,
    'wordpress' => $wordpress,
    'yelp' => $yelp,
    'reddit' => $reddit,
    'whatsapp' => $whatsapp,
    'weibo' => $weibo,
    'wechat' => $wechat,
    'vk' => $vk,
    'qzone' => $qzone,
    'imdb' => $imdb,
    'renren' => $renren
);

// group 3 style classes in 1
if(($style == "square-pointed")||($style == "square-rounded")||($style == "simple-rounded")){
    $class[] =  ' g_style ';
}

// convert align value to css atom
$alignToAtom = " ";
switch ($align) {
    case 'right':
        $class[] = 'a_align-right';
        break;

    case 'center':
        $class[] = 'a_align-center';
        break;

    case 'left':
        $class[] = 'a_align-left';
        break;

    default:
        $class[] = 'a_align-center';
}

// convert size value to css atom
$sizeToAtom = " ";
switch ($size) {
    case 'small':
        $class[] = 'a_font-16';
        break;

    case 'medium':
        $class[] = 'a_font-24';
        break;

    case 'large':
        $class[] = 'a_font-32';
        break;

    case 'x-large':
        $class[] = 'a_font-48';
        break;

    case 'xx-large':
        $class[] = 'a_font-64';
        break;

    default:
        $class[] = 'a_font-24';
}

?>

<div id="social-networks-<?php echo $id; ?>" class="mk-social-network-shortcode a_padding-0 a_margin-10-0 s_social a_m_list-reset <?php echo implode(' ', $class); ?> s_<?php echo $style; ?> social-align-<?php echo $align; ?> <?php echo $size; ?> <?php echo $el_class; ?>">
	<ul class="a_margin-0 a_padding-0 a_list-style-none">
		<?php
			foreach ($sites as $name => $link) {
			    echo !empty($link) ? '<li><a target="_blank" class="' . $name . '-hover c_" href="' . $link . '"><i class="' . $icon_style . $name . '  c_"></i></a></li>' : '';
			}
		?>
	</ul>
</div>



<?php


Mk_Static_Files::addCSS('
#social-networks-' . $id . ' a{
	margin: ' . $margin . 'px;
}	
#social-networks-' . $id . ' a i{
	color:' . $icon_color . ';
}
#social-networks-' . $id . ' a:hover i{
	color:' . $icon_hover_color . ';
}', $id);

if ($style == 'square-pointed' || $style == 'square-rounded' || $style == 'simple-rounded') {
    $bg_color = !empty($bg_color) ? ('background-color:' . $bg_color . ';') : ('background-color:rgba(255,255,255,0);');
    Mk_Static_Files::addCSS('
	#social-networks-' . $id . ' a {
		border-color: ' . $border_color . ';
		margin: ' . $margin . 'px;
		' . $bg_color . '
	}
	#social-networks-' . $id . ' a:hover {
		border-color: ' . $bg_hover_color . ';
		background-color: ' . $bg_hover_color . ';
	}', $id);
}

echo $output;
