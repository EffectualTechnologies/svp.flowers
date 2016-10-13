<?php

/**
 * template part for WooCommerce Checkout. views/header/master
 *
 * @author 		Evolution
 * @package 	svpflowers/views
 * @version     5.0.0
 */


global $woocommerce, $mk_options;

if (!$woocommerce || is_cart() || is_checkout() || $mk_options['woocommerce_catalog'] == 'true') return false;

if ($mk_options['shopping_cart'] == 'false') return false;

$height = ($view_params['header_style'] != 2) ? 'add-header-height' : '';

?>

<div class="shopping-cart-header <?php echo $height; ?> cart-count-<?php echo WC()->cart->cart_contents_count;?>">
	<a class="mk-shoping-cart-link" href="<?php echo $woocommerce->cart->get_cart_url();?>">
		<i class="mk-moon-cart-2"></i><span class="mk-header-cart-count"><?php echo WC()->cart->cart_contents_count;?></span>
	</a>
	<div class="mk-shopping-cart-box">
		<?php the_widget('WC_Widget_Cart');?>
		<div class="clearboth"></div>
	</div>
</div>
<style>.cart-count-0 { display: none; }</style>
