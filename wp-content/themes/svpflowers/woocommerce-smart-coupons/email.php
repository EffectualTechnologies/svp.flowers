<?php if (!defined('ABSPATH')) exit; ?>

<?php
	if ( function_exists( 'wc_get_template' ) ) {
		wc_get_template('emails/email-header.php', array( 'email_heading' => $email_heading ));
	} else {
		woocommerce_get_template('emails/email-header.php', array( 'email_heading' => $email_heading ));
	}
?>

<style type="text/css">
		.coupon-container {
			margin: .2em;
			display: inline-table;
			text-align: center;
			cursor: pointer;
		}
		.coupon-container.blue { background-color: #101d61 }

		.coupon-container.medium {
			padding: .4em;
			line-height: 1.4em;
		}

		.coupon-content.small { padding: .2em 1.2em }
		.coupon-content.dashed { border: 2.3px dashed }
		.coupon-content.blue { border-color: #101d61 }
		.coupon-content .code {
			font-size: 1.2em;
			font-weight:normal;
		}

		.coupon-content .coupon-expire,
		.coupon-content .discount-info {
			font-family: Helvetica, Arial, sans-serif;
			font-size: 1em;
		}
		.coupon-content .discount-description {
		    font: .7em/1 Helvetica, Arial, sans-serif;
		    width: 250px;
		    margin: 10px inherit;
		    display: inline-block;
		}
		.discount-description, .discount-info, .coupon-expire {display:none;}
		img {width:600px !important;}
		.coupon-content.blue {background-color:#101d61;}
		.code {color:#ffffff;}
</style>

<?php

$coupon = new WC_Coupon( $coupon_code );

$coupon_post = get_post( $coupon->id );

$coupon_data = $this->get_coupon_meta_data( $coupon );

	$coupon_target = '';
	$wc_url_coupons_active_urls = get_option( 'wc_url_coupons_active_urls' );
	if ( !empty( $wc_url_coupons_active_urls ) ) {
		$coupon_target = ( !empty( $wc_url_coupons_active_urls[ $coupon->id ]['url'] ) ) ? $wc_url_coupons_active_urls[ $coupon->id ]['url'] : '';
	}
	if ( !empty( $coupon_target ) ) {
		$coupon_target = home_url( '/' . $coupon_target );
	} else {
		$coupon_target = home_url( '/?sc-page=shop&coupon-code=' . $coupon_code );
	}

	$coupon_target = apply_filters( 'sc_coupon_url_in_email', $coupon_target, $coupon );
?>

<?php echo nl2br($message_from_sender); ?>
<p>&nbsp;</p>
<?php 
	$num_bundles = $coupon->amount / 59.94;
	$num_months = $num_bundles / 2;

	printf("<p>Congratulations! You've received a SVP Flowers gift subscription: Bundle length: %s %s (%s bundles)</p>", $num_months, ($num_months > 1 ? "months" : "month"), $num_bundles);
?>	
<p>To redeem your SVP Flowers gift subscription, visit <a href="http://svp.flowers/redeem/">SVP Flowers</a> and use the following code during checkout:</p>

<div style="margin: 10px 0; text-align: center;" title="<?php echo __( 'Click to visit store. This coupon will be applied automatically.', WC_Smart_Coupons::$text_domain ); ?>">
	<!-- <a href="<?php echo $coupon_target; ?>" style="color: #444;"> -->

		<div class="coupon-container blue medium" style="cursor:pointer; text-align:center">
			<?php
				echo '<div class="coupon-content blue dashed small">
					<div class="discount-info">';

					if ( ! empty( $coupon_data['coupon_amount'] ) && $coupon->amount != 0 ) {
						echo $coupon_data['coupon_amount'] . ' ' . $coupon_data['coupon_type'];
						if ( $coupon->free_shipping == "yes" ) {
							echo __( ' &amp; ', WC_Smart_Coupons::$text_domain );
						}
					}

					echo '</div>';

					echo '<div class="code">'. $coupon->code .'</div>';

					$show_coupon_description = get_option( 'smart_coupons_show_coupon_description', 'no' );
					if ( ! empty( $coupon_post->post_excerpt ) && $show_coupon_description == 'yes' ) {
						echo '<div class="discount-description">' . $coupon_post->post_excerpt . '</div>';
					}

				echo '</div>';
			?>
		</div>
	<!-- </a> -->
</div>

<?php if ( !empty( $from ) ) { ?>
	<p><?php echo __( 'You got this gift card', WC_Smart_Coupons::$text_domain ) . ' ' . $from . $sender; ?></p>
<?php } ?>

<div style="clear:both;"></div>

<?php
	if ( function_exists( 'wc_get_template' ) ) {
		wc_get_template('emails/email-footer.php');
	} else {
		woocommerce_get_template('emails/email-footer.php');
	}
?>
