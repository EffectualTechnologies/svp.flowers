<?php
/**
 * Twitter Button Template
 * 
 * Handles to load twitter button template
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
		
		<a title="<?php _e( 'Connect with Twitter', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-twitter woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-tw-icon"></i>
			<?php echo !empty($button_text) ? $button_text : __( 'Sign in with Twitter', 'wooslg' ); ?>
		</a>
	<?php
	} else { ?>
	
		<a title="<?php _e( 'Link your account with Twitter', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-twitter">
			<img src="<?php echo $twimgurl;?>" alt="<?php _e( 'Link your account with Twitter', 'wooslg');?>" />
		</a>
	<?php } ?>
</div>