<?php
/**
 * Social Button Template
 * 
 * Handles to load social button template
 * 
 * Override this template by copying it to yourtheme/woo-social-login/social-buttons.php
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly ?>

<div class="woo-slg-social-wrap"><?php 
	//do action to add social login buttons		
	do_action( 'woo_slg_checkout_social_login' );?>
	
	<div class="woo-slg-clear"></div>
</div><!--.woo-slg-social-wrap-->

<div class="woo-slg-login-error"></div><!--woo-slg-login-error-->

<div class="woo-slg-login-loader">
	<img src="<?php echo WOO_SLG_IMG_URL;?>/social-loader.gif" alt="<?php _e( 'Social Loader', 'wooslg');?>"/>
</div><!--.woo-slg-login-loader-->

<!-- After Login Redirect To This URL -->
<input type="hidden" class="woo-slg-redirect-url" id="woo_slg_redirect_url" value="<?php echo $login_redirect_url;?>" />