<?php
/**
 * Linkedin Button Template
 * 
 * Handles to load linkedin button template
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
		
		<a title="<?php _e( 'Connect with LinkedIn', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-linkedin woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-li-icon"></i>
			<?php echo !empty($button_text) ? $button_text : __( 'Sign in with LinkedIn', 'wooslg' ); ?>
		</a>
	<?php
	} else { ?>
	
		<a title="<?php _e( 'Connect with LinkedIn', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-linkedin">
		<img src="<?php echo $liimgurl;?>" alt="<?php _e( 'LinkedIn', 'wooslg');?>" />
	</a>
	<?php } ?>
</div>