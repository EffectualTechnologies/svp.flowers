<?php
/**
 * Window Live Button Template
 * 
 * Handles to load window live button template
 * 
 * @package WooCommerce - Social Login
 * @since 1.0.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<!-- show button -->
<div class="woo-slg-login-wrapper">
	<?php
	if( $button_type == 1 ) { ?>
		
		<a title="<?php _e( 'Connect with Windows Live', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-windowslive woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-wl-icon"></i>
			<?php echo !empty($button_text) ? $button_text : __( 'Sign in with Windows Live', 'wooslg' ); ?>
		</a>
	<?php
	} else { ?>
	
		<a title="<?php _e( 'Connect with Windows Live', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-windowslive">
			<img src="<?php echo $wlimgurl;?>" alt="<?php _e( 'Windows Live', 'wooslg');?>" />
		</a>
	<?php } ?>
</div>