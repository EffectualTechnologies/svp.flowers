<?php

/**
 * template part for Search form located beside main navigation. views/header/global
 *
 * @author 		Artbees
 * @package 	jupiter/views
 * @version     5.0.0
 */

global $mk_options;

$icon_height = ($view_params['header_style'] != 2) ? 'add-header-height' : '';

if ($mk_options['header_search_location'] == 'beside_nav') { ?>

<div class="main-nav-side-search">
	
	<a class="mk-search-trigger <?php echo $icon_height; ?> mk-toggle-trigger" href="#"><i class="mk-icon-search"></i></a>

	<div id="mk-nav-search-wrapper" class="mk-box-to-trigger">
		<form method="get" id="mk-header-navside-searchform" action="<?php echo home_url(); ?>">
			<input type="text" name="s" id="mk-ajax-search-input" />
			<?php wp_nonce_field('mk-ajax-search-form', 'security'); ?>
			<i class="mk-moon-search-3 nav-side-search-icon"><input type="submit" value=""/></i>
		</form>
	</div>

</div>

<?php } elseif ($mk_options['header_search_location'] == 'fullscreen_search') { ?>

	<div class="main-nav-side-search">
		<a class="mk-search-trigger <?php echo $icon_height; ?> mk-fullscreen-trigger" href="#"><i class="mk-icon-search"></i></a>
	</div>

<?php
}
