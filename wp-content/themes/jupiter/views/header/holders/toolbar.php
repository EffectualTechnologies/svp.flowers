<?php

/**
 * template part for header toolbar. views/header/holders
 *
 * @author 		Artbees
 * @package 	jupiter/views
 * @version     5.0.0
 */

global $mk_options;

if (is_header_toolbar_show() != 'true' || (isset($view_params['is_shortcode']) && $view_params['is_shortcode']) ) return false;
?>

<div class="mk-header-toolbar">

    <?php if($mk_options['header_grid'] == 'true') { ?>
        <div class="mk-grid header-grid">
            
    <?php } ?>

        <div class="mk-toolbar-holder">
        <?php

            do_action('header_toolbar_before');

            mk_get_header_view('toolbar', 'nav');

            mk_get_header_view('toolbar', 'date');

            mk_get_header_view('toolbar', 'contact');

            mk_get_header_view('toolbar', 'tagline');

            mk_get_header_view('toolbar', 'wpml-nav');

            mk_get_header_view('global', 'search', ['location' => 'toolbar']);

            mk_get_header_view('global', 'social', ['location' => 'toolbar']);

            mk_get_header_view('toolbar', 'login');

            mk_get_header_view('toolbar', 'subscribe');

            do_action('header_toolbar_after');
        ?>

        </div>   

    <?php if($mk_options['header_grid'] == 'true') { ?>
            </div>
    <?php } ?>
    
</div>
