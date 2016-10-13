<?php
/**
 * Instagram Button Template
 * 
 * Handles to load instagram button template
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
		
		<a title="<?php _e( 'Connect with Instagram', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-instagram woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-inst-icon"></i>
			<?php echo !empty($button_text) ? $button_text : __( 'Sign in with Instagram', 'wooslg' ); ?>
		</a>
	<?php
	} else { ?>
	
		<a title="<?php _e( 'Connect with Instagram', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-instagram">
			<img src="<?php echo $instimgurl;?>" alt="<?php _e( 'Instagram', 'wooslg');?>" />
		</a>
	<?php } ?>
</div>