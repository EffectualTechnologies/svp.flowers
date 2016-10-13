<?php

/**
 * template part for WooCommerce Checkout. views/header/master
 *
 * @author 		Artbees
 * @package 	jupiter/views
 * @version     5.0.0
 */


global $woocommerce, $mk_options;

if (!$woocommerce || is_cart() || is_checkout() || $mk_options['woocommerce_catalog'] == 'true') return false;

if ($mk_options['shopping_cart'] == 'false') return false;

$height = ($view_params['header_style'] != 2) ? 'add-header-height' : '';

?>

<div class="shopping-cart-header <?php echo $height; ?>">
	
	<a class="mk-shoping-cart-link" href="<?php echo WC()->cart->get_cart_url();?>">
		<i class="mk-moon-cart-2">
        	<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" class="mk-svg-icon" style="height: 16px; width: 16px;">
			<path d="M423.609 288c17.6 0 35.956-13.846 40.791-30.769l46.418-162.463c4.835-16.922-5.609-30.768-23.209-30.768h-327.609c0-35.346-28.654-64-64-64h-96v64h96v272c0 26.51 21.49 48 48 48h304c17.673 0 32-14.327 32-32s-14.327-32-32-32h-288v-32h263.609zm-263.609-160h289.403l-27.429 96h-261.974v-96zm32 344c0 22-18 40-40 40h-16c-22 0-40-18-40-40v-16c0-22 18-40 40-40h16c22 0 40 18 40 40v16zm288 0c0 22-18 40-40 40h-16c-22 0-40-18-40-40v-16c0-22 18-40 40-40h16c22 0 40 18 40 40v16z"></path>
			</svg>	
        </i>
        <span class="mk-header-cart-count"><?php echo WC()->cart->cart_contents_count;?></span>
	</a>

	<div class="mk-shopping-cart-box">
		<?php the_widget('WC_Widget_Cart');?>
		<div class="clearboth"></div>
	</div>

</div>