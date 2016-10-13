<?php
global $mk_options;
$style = !empty($mk_options['secondary_menu']) ? $mk_options['secondary_menu'] : 'fullscreen';

$seondary_header_for_all = !empty($mk_options['seondary_header_for_all']) ? $mk_options['seondary_header_for_all'] : 'false';

if($seondary_header_for_all == 'false' && $view_params['header_style'] != 3) return false;

?>
<div class="svp-menu-wrap">
	
	<div class="mk-dashboard-trigger <?php echo $style; ?>-style add-header-height">
        <div class="mk-css-icon-menu icon-size-<?php echo $mk_options['header_burger_size']; ?>">
	        <div class="svp-menu-text">MENU</div>
	        <img src="/wp-content/uploads/svp-burger-icon.png" />
	        <img src="/wp-content/uploads/svp-burger-icon-pink.png" />
            <!-- <div class="mk-css-icon-menu-line-1"></div>
            <div class="mk-css-icon-menu-line-2"></div>
            <div class="mk-css-icon-menu-line-3"></div> -->
        </div>
	</div>

	 <?php if ( is_user_logged_in() ) { ?>
	 <div><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('My Account','woothemes'); ?>" class="svp-menu-button myaccount-button"><?php _e('MY ACCOUNT','woothemes'); ?></a></div>
	 <?php } 
	 else { ?>
	 <div><a href="<?php echo get_permalink( get_option('woocommerce_myaccount_page_id') ); ?>" title="<?php _e('Log in','woothemes'); ?>" class="svp-menu-button myaccount-button"><?php _e('LOG IN','woothemes'); ?></a></div>
	 <?php } ?>

	<div><a href="/redeem/" class="svp-menu-button join-button">REDEEM</a></div>
	<div><a href="/join/" class="svp-menu-button join-button">JOIN</a></div>	
	<div><a href="/" class="mk-icon-home"></a></div>

</div>

