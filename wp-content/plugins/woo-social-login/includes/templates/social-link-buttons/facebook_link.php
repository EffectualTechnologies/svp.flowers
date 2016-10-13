<?php
/**
 * Facebook Link Button Template
 * 
 * Handles to load facebook link button template
 * 
 * Override this template by copying it to yourtheme/woo-social-login/social-link-buttons/facebook_link.php
 * 
 * @package WooCommerce - Social Login
 * @since 1.3.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<!-- show facebook button -->
<div class="woo-slg-login-wrapper">
	<?php
	if( $button_type == 1 ) { ?>
		
		<a title="<?php _e( 'Link your account with Facebook', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-facebook woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-fb-icon"></i>
			<?php echo !empty($button_text) ? $button_text : __( 'Link your account with Facebook', 'wooslg' ); ?>
		</a>
	<?php
	} else { ?>
	
		<a title="<?php _e( 'Link your account with Facebook', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-facebook">
			<img src="<?php echo $fblinkimgurl;?>" alt="<?php _e( 'Link your account to Facebook', 'wooslg');?>" />
		</a>
	<?php } ?>
</div>