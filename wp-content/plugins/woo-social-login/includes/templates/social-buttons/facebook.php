<?php
/**
 * Facebook Button Template
 * 
 * Handles to load facebook button template
 * 
 * Override this template by copying it to yourtheme/woo-social-login/social-buttons/facebook.php
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
		
		<a title="<?php _e( 'Connect with Facebook', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-facebook woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-fb-icon"></i>
			<?php echo !empty($button_text) ? $button_text : __( 'Sign in with Facebook', 'wooslg' ); ?>
		</a>
	<?php
	} else { ?>
	
		<a title="<?php _e( 'Connect with Facebook', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-facebook">
			<img src="<?php echo $fbimgurl;?>" alt="<?php _e( 'Facebook', 'wooslg');?>" />
		</a>
	<?php } ?>
</div>