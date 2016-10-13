<?php
/**
 * foursquare Button Template
 * 
 * Handles to load facebook button template
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
		
		<a title="<?php _e( 'Connect with Foursquare', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-foursquare woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-fs-icon"></i>
			<?php echo !empty($button_text) ? $button_text : __( 'Sign in with Foursquare', 'wooslg' ); ?>
		</a>
	<?php
	} else { ?>
	
		<a title="<?php _e( 'Connect with Foursquare', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-foursquare">
			<img src="<?php echo $fsimgurl;?>" alt="<?php _e( 'Foursquare', 'wooslg');?>" />
		</a>
	<?php } ?>
</div>