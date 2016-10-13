<?php
/**
 * Googleplus Button Template
 * 
 * Handles to load Googleplus button template
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
		
		<a title="<?php _e( 'Connect with Google+', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-googleplus woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-gp-icon"></i>
			<?php echo !empty($button_text) ? $button_text : __( 'Sign in with Google+', 'wooslg' ); ?>
		</a>
	<?php
	} else { ?>
	
		<a title="<?php _e( 'Connect with Google+', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-googleplus">
			<img src="<?php echo $gpimgurl;?>" alt="<?php _e( 'Google+', 'wooslg');?>" />
		</a>
	<?php } ?>
</div>