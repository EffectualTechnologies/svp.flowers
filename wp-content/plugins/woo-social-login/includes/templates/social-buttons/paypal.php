<?php
/**
 * paypal Button Template
 * 
 * Handles to load paypal button template
 * 
 * Override this template by copying it to yourtheme/woo-social-login/social-buttons/paypal.php
 * 
 * @package WooCommerce - Social Login
 * @since 1.4.0
 */

if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
?>

<!-- show button -->
<div class="woo-slg-login-wrapper">
	<?php
	if( $button_type == 1 ) { ?>
		
		<a title="<?php _e( 'Connect with Paypal', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-paypal woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-paypal-icon"></i>
			<?php echo !empty($button_text) ? $button_text : __( 'Sign in with Paypal', 'wooslg' ); ?>
		</a>
	<?php
	} else { ?>
	
		<a title="<?php _e( 'Connect with Paypal', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-paypal">
			<img src="<?php echo $paypalimgurl;?>" alt="<?php _e( 'Paypal', 'wooslg');?>" />
		</a>
	<?php } ?>
</div>
