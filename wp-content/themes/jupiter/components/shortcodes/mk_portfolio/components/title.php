<?php 

$link_after = $link_before = '';

if ($view_params['permalink_icon'] == 'true') {
	$link_before = '<a target="' . $view_params['target'] . '" href="' . mk_get_super_link(get_post_meta(get_the_ID(), '_portfolio_permalink', true)) . '">';
	$link_after = '</a>';
}
?>
<h2><?php echo the_title(); ?></h2><p><?php the_content(); ?></p><div class="clearboth"></div>