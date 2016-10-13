<?php
/**
 * vk Button Template
 * 
 * Handles to load vk button template
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
		
		<a title="<?php _e( 'Connect with VK.com', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-vk woo-slg-social-btn">
			<i class="woo-slg-icon woo-slg-vk-icon"></i>
			<?php echo !empty($button_text) ? $button_text : __( 'Sign in with VK.com', 'wooslg' ); ?>
		</a>
	<?php
	} else { ?>
	
		<a title="<?php _e( 'Connect with VK.com', 'wooslg');?>" href="javascript:void(0);" class="woo-slg-social-login-vk">
			<img src="<?php echo $vkimgurl;?>" alt="<?php _e( 'vk', 'wooslg');?>" />
		</a>
	<?php } ?>
</div>