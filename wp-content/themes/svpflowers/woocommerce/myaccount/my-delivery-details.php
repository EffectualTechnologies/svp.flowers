<?php
if ( ! defined( 'ABSPATH' ) ) {
    exit;
}

global $woocommerce, $current_user, $wp_query;

$page_title =  __( 'Delivery Preferences', 'mk_framework' ) ; 


if (!isset($_POST['action'])) { 

	$subscription_id = get_query_var('my-delivery-details');
	
	$delivery_day = get_post_meta( $subscription_id, "Delivery Day", true );
	$delivery_time = get_post_meta( $subscription_id, "Delivery Time", true );
	$delivery_phone = get_post_meta( $subscription_id, "Delivery Phone", true );
	
	
	//$resubscribe_id = get_post_meta( $subscription_id, "_subscription_resubscribe", true );
	// $delivery_day = get_post_meta( $resubscribe_id, "Delivery Day", true );
	// $delivery_time = get_post_meta( $resubscribe_id, "Delivery Time", true );
	// $delivery_phone = get_post_meta( $resubscribe_id, "Delivery Phone", true );
	
	$redirect_url = wc_get_endpoint_url( 'view-subscription', $subscription_id, wc_get_page_permalink( 'myaccount' ) );
 
} 
else {
 
    // $subscription_id = $_POST['subscription_id'];
   	// $delivery_day = $_POST['delivery_day'];
	// $delivery_time = $_POST['delivery_time'];
	// $delivery_phone = $_POST['delivery_phone'];
	// $redirect_url = $_POST['redirect_url'];
   
   	// $delivery_day = update_post_meta( $subscription_id, "Delivery Day", $delivery_day );
	// $delivery_time = update_post_meta( $subscription_id, "Delivery Time", $delivery_time );
	// $delivery_phone = update_post_meta( $subscription_id, "Delivery Phone", $delivery_phone );
	
	// //header("Location: " . $redirect_url);
	
	// wp_safe_redirect($redirect_url);
    exit;

}


?>
<?php wc_print_notices(); ?>

<h3><?php echo $page_title?></h3>
<form class="edit-account" action="" method="post">
	
	
		<?php  woocommerce_form_field( 'delivery_day', array(
	    		'type'          => 'select',
	        	'class'         => array('shipping-day form-row-wide'),
	        	'label'         => __('Delivery Day'),
	        	'required'    	=> true,
		    	'placeholder'   => __('Pick a date'),
		    	'options'     	=> array(
			   		'Thursday' => __('Thursday'),
			   		'Friday' 		=> __('Friday')
	        	)), $delivery_day);
				?>

	
		<?php  woocommerce_form_field( 'delivery_time', array(
	        'type'          => 'select',
	        'class'         => array('shipping-time form-row-wide'),
	        'label'         => __('Delivery Time'),
	        'required'    	=> true,
		    'placeholder'   => __(''),
		    'options'     	=> array(
			    'Anytime before 5:00 pm' => __('Anytime before 5:00 pm'),
			    'Anytime before 9:00 pm' 		=> __('Anytime before 9:00 pm'),
			    '12:00 pm - 3:00 pm' 	=> __('12:00 pm - 3:00 pm'),
			    '3:00 pm - 6:00 pm' 	=> __('3:00 pm - 6:00 pm'),
			    '6:00 pm - 9:00 pm' 	=> __('6:00 pm - 9:00 pm')
	        )), $delivery_time);
				?>
	
	<p class="form-row form-row-wide">
		<label for="delivery_phone"><?php _e( 'Delivery Phone', 'mk_framework' ); ?> <span class="required">*</span></label>
		<input type="text" class="input-text" name="delivery_phone" id="delivery_phone" value="<?php echo esc_attr( $delivery_phone ); ?>" />
	</p>
	<p>
		<input type="submit" class="shop-flat-btn shop-skin-btn" name="save_delivery_preferences" value="<?php _e( 'Save', 'mk_framework' ); ?>" />
		<?php wp_nonce_field( 'save_delivery_preferences' ); ?>
		<input type="hidden" name="action" value="save_delivery_preferences" />
		<input type="hidden" name="subscription_id" value="<?php echo $subscription_id; ?>" />
		<input type="hidden" name="redirect_url" value="<?php echo $redirect_url; ?>"
		</p>
	</form>