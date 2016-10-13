<?php 

// include MailChimp
include_once('inc/MCAPI.class.php');
// Hook in the form handler for doing post backs for Delivery Preferences.
include_once( 'woocommerce/includes/class-svp-form-handler.php' );
SVP_Form_Handler::init();

	
// remove extraneous sections from WP backend	
add_action('admin_head', 'custom_styles');

function custom_styles() {
  echo '<style>
    .toplevel_page_Jupiter {display:none !important;} 
    #wp-admin-bar-theme_options {display: none !important;}
  </style>';
}	

// Add zip code lookup JS
function zip_custom_js() {
        if (is_page(8017)||is_page(8527)) {
            wp_register_script('zip_lookup_js', get_stylesheet_directory_uri() . '/js/zipcode_script.js', array('jquery'), null);
            wp_enqueue_script('zip_lookup_js');
        }
}
add_action('wp_enqueue_scripts', 'zip_custom_js', 50);
	
// Add miappi JS
function miappi_custom_js() {
        if (is_front_page()||is_page(7987)) {
            wp_register_script('miappi_js', get_stylesheet_directory_uri() . '/js/miappi_script.js', array('jquery'), null);
            wp_enqueue_script('miappi_js');
        }
}
add_action('wp_enqueue_scripts', 'miappi_custom_js', 50);

// remove SKU from WooCommerce Product pages
add_filter( 'wc_product_sku_enabled', '__return_false' );


// rename the "Have a Coupon?" message on the checkout page
function woocommerce_rename_coupon_message_on_checkout() {

//	return 'Have a gift subscription Code?' . ' <a href="#" class="showcoupon">' . __( 'Click here to enter your code', 'woocommerce' ) . '</a>';
}
add_filter( 'woocommerce_checkout_coupon_message', 'woocommerce_rename_coupon_message_on_checkout' );

// rename the coupon field on the checkout page
function woocommerce_rename_coupon_field_on_checkout( $translated_text, $text, $text_domain ) {

	// bail if not modifying frontend woocommerce text
	if ( is_admin() || 'woocommerce' !== $text_domain ) {
		return $translated_text;
	}

	if ( 'Coupon code' === $text ) {
		$translated_text = 'Gift subscription code';
	
	} elseif ( 'Apply Coupon' === $text ) {
		$translated_text = 'Apply code';
	}

	return $translated_text;
}
add_filter( 'gettext', 'woocommerce_rename_coupon_field_on_checkout', 10, 3 );



// hook in to add CSS to WooCommerce email header
add_action('woocommerce_email_header', 'add_css_to_email');
 
function add_css_to_email() {
echo '
<style type="text/css">
#wrapper {padding: 20px 0 20px 0 !important;}
#template_header_image img {width:600px;}
.discount-info, .coupon-expire {display: none !important;}
.generated_coupon_summary .coupon-content.blue {background-color:#101d61;border-color:#101d61 !important;border:0 solid transparent !important;padding: 1em;}
.code {color:#ffffff;}
.coupon-container.blue.medium {background-color: transparent;}
</style>
';
}

// Hook in to modify WooCommerce checkout fields
add_filter( 'woocommerce_checkout_fields' , 'custom_override_checkout_fields' );

function custom_override_checkout_fields( $fields ) {
	
	// modify label and placeholder text for Order Comments field
     $fields['order']['order_comments']['placeholder'] = 'Enter a message for your gift recipient.';
     $fields['order']['order_comments']['label'] = 'Gift Message';
     
    // remove Company field
     unset($fields['shipping']['shipping_company']);
     unset($fields['billing']['billing_company']);
     
    return $fields;     
     
}

// Hook in to add shipping day custom field to WooCommerce checkout
add_action( 'woocommerce_after_order_notes', 'deliveryday_custom_checkout_field' );

function deliveryday_custom_checkout_field( $checkout ) {

	if ( is_bundle_in_cart() ) {
		    echo '<div id="deliveryday_custom_checkout_field"><h3>' . __('Delivery preferences') . '</h3><p class="delivery-text">Your flowers arrive every other Thursday or Friday in a fabulous and sturdy box, where they will stay fresh and protected, even if they have to wait for you a few hours before you unwrap them. For your bundle delivery, we\'ve partnered with a great company called Deliv, which gives you the flexibility to control the delivery window. Choose your delivery day and time below.</p>';

			woocommerce_form_field( 'shipping_day', array(
	    		'type'          => 'select',
	        	'class'         => array('shipping-day form-row-wide'),
	        	'label'         => __('Delivery Day'),
	        	'required'    	=> true,
		    	'placeholder'   => __('Pick a date'),
		    	'options'     	=> array(
			   		'' => __('Select a delivery day'),
			   		'Thursday' => __('Thursday'),
			   		'Friday' 		=> __('Friday')
	        	)), $checkout->get_value( 'shipping_day' ));
    }
}

// validate the shipping day custom field when the checkout form is posted
add_action('woocommerce_checkout_process', 'deliveryday_checkout_field_process');

function deliveryday_checkout_field_process() {

    // Check if set, if its not set add an error.
    if ( is_bundle_in_cart() && ! $_POST['shipping_day'] )
        wc_add_notice( __( 'Please select a delivery day.' ), 'error' );
}

// Update the order meta with shipping day custom field value
add_action( 'woocommerce_checkout_update_order_meta', 'deliveryday_checkout_field_update_order_meta' );

function deliveryday_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['shipping_day'] ) ) {
        update_post_meta( $order_id, 'Delivery Day', sanitize_text_field( $_POST['shipping_day'] ) );
    }
}

// Display shipping day custom field value on the order edit page
add_action( 'woocommerce_admin_order_data_after_billing_address', 'deliveryday_checkout_field_display_admin_order_meta', 10, 1 );

function deliveryday_checkout_field_display_admin_order_meta($order){

	$delivery_day = get_post_meta( $order->id, 'Delivery Day', true );
	if ( ! empty( $delivery_day )) {
	    echo '<p><strong>'.__('Delivery Day').':</strong> ' . $delivery_day . '</p>';
	}
}

// add Delivery Day custom field to email templates
add_filter('woocommerce_email_order_meta_keys', 'deliveryday_meta_keys');

function deliveryday_meta_keys( $keys ) {
     $keys[] = 'Delivery Day'; // This will look for a custom field called 'Delivery Day' and add it to emails
     echo '<br/>';
     return $keys;
}


// Hook in to add shipping time custom field to WooCommerce checkout
add_action( 'woocommerce_after_order_notes', 'deliverytime_custom_checkout_field' );

function deliverytime_custom_checkout_field( $checkout ) {

	if ( is_bundle_in_cart() ) {

	    woocommerce_form_field( 'shipping_time', array(
	        'type'          => 'select',
	        'class'         => array('shipping-time form-row-wide'),
	        'label'         => __('Delivery Time'),
	        'required'    	=> true,
		    'placeholder'   => __(''),
		    'options'     	=> array(
			    '' => __('Select a delivery time'),
			    'Anytime before 5:00 pm' => __('Anytime before 5:00 pm'),
			    'Anytime before 9:00 pm' 		=> __('Anytime before 9:00 pm'),
			    '12:00 pm - 3:00 pm' 	=> __('12:00 pm - 3:00 pm'),
			    '3:00 pm - 6:00 pm' 	=> __('3:00 pm - 6:00 pm'),
			    '6:00 pm - 9:00 pm' 	=> __('6:00 pm - 9:00 pm')
	        )), $checkout->get_value( 'shipping_time' ));

	    echo '<p class="delivery-text">You can change your delivery day and time preferences by the Tuesday 11pm before your selected delivery date.</p></div>';
	}
}

// validate the shipping time custom field when the checkout form is posted
add_action('woocommerce_checkout_process', 'deliverytime_checkout_field_process');

function deliverytime_checkout_field_process() {
    // Check if set, if its not set add an error.
    if ( is_bundle_in_cart() && ! $_POST['shipping_time'] )
        wc_add_notice( __( 'Please select a delivery time.' ), 'error' );
}

// Update the order meta with shipping time custom field value
add_action( 'woocommerce_checkout_update_order_meta', 'deliverytime_checkout_field_update_order_meta' );

function deliverytime_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['shipping_time'] ) ) {
        update_post_meta( $order_id, 'Delivery Time', sanitize_text_field( $_POST['shipping_time'] ) );
    }
}

// Display shipping time custom field value on the order edit page
add_action( 'woocommerce_admin_order_data_after_billing_address', 'deliverytime_checkout_field_display_admin_order_meta', 10, 1 );

function deliverytime_checkout_field_display_admin_order_meta($order){

	$delivery_time = get_post_meta( $order->id, 'Delivery Time', true );
	if ( ! empty( $delivery_time )) {
    		echo '<p><strong>'.__('Delivery Time').':</strong> ' . $delivery_time . '</p>';
	}
}

// add Delivery Time custom field to email templates
add_filter('woocommerce_email_order_meta_keys', 'deliverytime_meta_keys');

function deliverytime_meta_keys( $keys ) {
     $keys[] = 'Delivery Time'; // This will look for a custom field called 'Delivery Time' and add it to emails
     return $keys;
}


// add Delivery Phone custom field
add_action( 'woocommerce_after_order_notes', 'deliveryphone_checkout_field' );

function deliveryphone_checkout_field( $checkout ) {

    if ( is_bundle_in_cart() ) {

	    echo '<div id="deliveryphone_checkout_field">';

	    woocommerce_form_field( 'delivery_phone', array(
	        'type'          => 'text',
	        'class'         => array('delivery-phone-class form-row-wide'),
	        'label'         => __('Delivery Phone'),
	        'required'    	=> true,        
	        'placeholder'   => __('Phone number for delivery contact'),
	        ), $checkout->get_value( 'delivery_phone' ));

    	echo '</div>';
	}
}

// validate the shipping phone custom field when the checkout form is posted
add_action('woocommerce_checkout_process', 'deliveryphone_checkout_field_process');

function deliveryphone_checkout_field_process() {
    // Check if set, if its not set add an error.
    if ( is_bundle_in_cart() && ! $_POST['delivery_phone'] )
        wc_add_notice( __( 'Please select a delivery phone number.' ), 'error' );
}

// Update the order meta with shipping phone custom field value
add_action( 'woocommerce_checkout_update_order_meta', 'deliveryphone_checkout_field_update_order_meta' );

function deliveryphone_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['delivery_phone'] ) ) {
        update_post_meta( $order_id, 'Delivery Phone', sanitize_text_field( $_POST['delivery_phone'] ) );
    }
}

// Display shipping phone custom field value on the order edit page
add_action( 'woocommerce_admin_order_data_after_billing_address', 'deliveryphone_checkout_field_display_admin_order_meta', 10, 1 );

function deliveryphone_checkout_field_display_admin_order_meta($order){

	$delivery_phone = get_post_meta( $order->id, 'Delivery Phone', true );
	if ( ! empty( $delivery_phone )) {
		echo '<p><strong>'.__('Delivery Phone').':</strong> ' . $delivery_phone . '</p>';
	}
}

// add Delivery Phone custom field to email templates
add_filter('woocommerce_email_order_meta_keys', 'deliveryphone_meta_keys');

function deliveryphone_meta_keys( $keys ) {
     $keys[] = 'Delivery Phone'; // This will look for a custom field called 'Delivery Phone' and add it to emails
     return $keys;
     echo '<br/>';
}


// add Delivery delivery instructions custom field
add_action( 'woocommerce_after_order_notes', 'deliveryinstructions_checkout_field' );

function deliveryinstructions_checkout_field( $checkout ) {

    if ( is_bundle_in_cart() ) {

	    echo '<div id="deliveryinstructions_checkout_field">';

	    woocommerce_form_field( 'delivery_instructions', array(
	        'type'          => 'text',
	        'class'         => array('delivery-instructions-class form-row-wide'),
	        'label'         => __('Delivery Instructions (Optional)'),
	        'required'    	=> false,        
	        'placeholder'   => __('Optional instructions for deliverer'),
	        ), $checkout->get_value( 'delivery_instructions' ));

    	echo '</div>';
	}
}


// Update the order meta with delivery instructions custom field value
add_action( 'woocommerce_checkout_update_order_meta', 'deliveryinstructions_checkout_field_update_order_meta' );

function deliveryinstructions_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['delivery_instructions'] ) ) {
        update_post_meta( $order_id, 'Delivery Instructions', sanitize_text_field( $_POST['delivery_instructions'] ) );
    }
}

// Display delivery instructions custom field value on the order edit page
add_action( 'woocommerce_admin_order_data_after_billing_address', 'deliveryinstructions_checkout_field_display_admin_order_meta', 10, 1 );

function deliveryinstructions_checkout_field_display_admin_order_meta($order){

	$delivery_instructions = get_post_meta( $order->id, 'Delivery Instructions', true );
	if ( ! empty( $delivery_instructions )) {
		echo '<p><strong>'.__('Delivery Instructions').':</strong> ' . $delivery_instructions . '</p>';
	}
}

// add Delivery Instructions custom field to email templates
add_filter('woocommerce_email_order_meta_keys', 'deliveryinstructions_meta_keys');

function deliveryinstructions_meta_keys( $keys ) {
     $keys[] = 'Delivery Instructions'; // This will look for a custom field called 'Delivery Instructions' and add it to emails
     return $keys;
     echo '<br/>';
}




function is_product_in_cart( $ids ) {
 
    $cart_ids = array();
    foreach( WC()->cart->get_cart() as $cart_item_key => $values ) {
        $cart_product = $values['data'];
        $cart_ids[]   = $cart_product->id;
    }
    if ( ! empty( array_intersect( $ids, $cart_ids ) ) ) {
        return true;
    } else {
        return false;
    }
}

function is_bundle_in_cart() {
	return is_product_in_cart( array( '8014', '8520', '9441', '9695' ) );
}

function is_regular_bundle_in_cart() {
	return is_product_in_cart( array( '8014', '9441', '9695' ) );
}

function is_gift_bundle_in_cart() {
	return is_product_in_cart( array( '8276' ) );
}

function is_redeem_bundle_in_cart() {
	return is_product_in_cart( array( '8520' ) );
}

function update_checkout_fields( $fields ) {

    unset( $fields['order']['order_comments'] );

    return $fields;
}

add_filter( 'woocommerce_checkout_fields' , 'update_checkout_fields' );

function hide_coupon_field( $enabled ) {
	if ( is_gift_bundle_in_cart() || is_regular_bundle_in_cart() ) {
		$enabled = false;
	}
	
	return $enabled;
}

add_filter( 'woocommerce_coupons_enabled', 'hide_coupon_field' );

// add note to cart page for regular subscriptions only 
function notice_cart() {
	if ( is_regular_bundle_in_cart() ) {
echo '<p id="notice_cart">For SVP Flower bundle orders, orders placed after Tuesday 11pm will be delivered the following week and your credit card will be charged on the Wednesday before delivery.</p>';
	}
}
add_action( 'woocommerce_cart_totals_before_order_total', 'notice_cart' );

// add note to checkout page for regular subscriptions only
function notice_checkout() {
	if ( is_regular_bundle_in_cart() ) {
echo '<p id="notice_checkout">For SVP Flower bundle orders, orders placed after Tuesday 11pm will be delivered the following week and your credit card will be charged on the Wednesday before delivery.</p>';
	}
}
add_action( 'woocommerce_review_order_before_submit', 'notice_checkout' );


// add note to checkout/order-received page
function notice_thankyou() {
echo '<p class="woocommerce-thankyou-order-received">Thank you. Your order has been received.</p><div class="confirmation-social"><h3>Let everyone know about SVP Flower\'s loveliness.</h3><a target="_blank" class="facebook-hover c_" href="https://www.facebook.com/sharer/sharer.php?u=https%3A//svp.flowers/"><i class="mk-jupiter-icon-simple-facebook  c_"><svg class="mk-svg-icon" data-name="mk-jupiter-icon-simple-facebook" data-cacheid="4" style="height:64px; width: 64px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M192.191 92.743v60.485h-63.638v96.181h63.637v256.135h97.069v-256.135h84.168s6.674-51.322 9.885-96.508h-93.666v-42.921c0-8.807 11.565-20.661 23.01-20.661h71.791v-95.719h-83.57c-111.317 0-108.686 86.262-108.686 99.142z"></path></svg></i></a><a target="_blank" class="twitter-hover c_" href="https://twitter.com/home?status=Wow!%20Way%20cool%20if%20you%20love%20to%20receive%20or%20send%20%23flowers.%20Check%20it%20out%3A%20http%3A//svp.flowers%20%23svpflowers"><i class="mk-jupiter-icon-simple-twitter  c_"><svg class="mk-svg-icon" data-name="mk-jupiter-icon-simple-twitter" data-cacheid="5" style="height:64px; width: 64px" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512"><path d="M454.058 213.822c28.724-2.382 48.193-15.423 55.683-33.132-10.365 6.373-42.524 13.301-60.269 6.681-.877-4.162-1.835-8.132-2.792-11.706-13.527-49.679-59.846-89.698-108.382-84.865 3.916-1.589 7.914-3.053 11.885-4.388 5.325-1.923 36.678-7.003 31.749-18.079-4.176-9.728-42.471 7.352-49.672 9.597 9.501-3.581 25.26-9.735 26.93-20.667-14.569 1.991-28.901 8.885-39.937 18.908 3.998-4.293 7.01-9.536 7.666-15.171-38.91 24.85-61.624 74.932-80.025 123.523-14.438-13.972-27.239-25.008-38.712-31.114-32.209-17.285-70.722-35.303-131.156-57.736-1.862 19.996 9.899 46.591 43.723 64.273-7.325-.986-20.736 1.219-31.462 3.773 4.382 22.912 18.627 41.805 57.251 50.918-17.642 1.163-26.767 5.182-35.036 13.841 8.043 15.923 27.656 34.709 62.931 30.82-39.225 16.935-15.998 48.234 15.93 43.565-54.444 56.244-140.294 52.123-189.596 5.08 128.712 175.385 408.493 103.724 450.21-65.225 31.23.261 49.605-10.823 60.994-23.05-17.99 3.053-44.072-.095-57.914-5.846z"></path></svg></i></a></div><div class="confirmation-community"><h3>Check out how others are arranging their bundles</h3><a href="/community/" class="button view">View the Community</a></div>';
}
add_action( 'woocommerce_thankyou_order_received_text', 'notice_thankyou' );


// add note to checkout page for gift subscriptions only
function notice_giftcheckout() {
	if ( is_gift_bundle_in_cart() ) {
echo '<p id="notice_giftcheckout">Your gift subscription of SVP Flower bundles includes the delivery costs ($4.94 per bundle)</p>';
	}
}
add_action( 'woocommerce_after_cart_table', 'notice_giftcheckout' );


// remove cart fields for redemption
function redeem_checkout() {
	if ( is_redeem_bundle_in_cart() ) {
// remove certain sections
	}
}
add_action( 'woocommerce_before_cart', 'redeem_checkout' );


// remove WooCommerce password strength checker
function remove_password_strength() {
	if ( wp_script_is( 'wc-password-strength-meter', 'enqueued' ) ) {
		wp_dequeue_script( 'wc-password-strength-meter' );
	}
}
add_action( 'wp_print_scripts', 'remove_password_strength', 100 );

// Ensure cart contents update when products are added to the cart via AJAX (place the following in functions.php)
add_filter( 'woocommerce_add_to_cart_fragments', 'woocommerce_header_add_to_cart_fragment' );

function woocommerce_header_add_to_cart_fragment( $fragments ) {
	ob_start();
	if(intval(WC()->cart->get_cart_contents_count())>0){
	?>
	<a class="cart-contents" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><?php echo sprintf (_n( '%d item', '%d items', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?> - <?php echo WC()->cart->get_cart_total(); ?></a> 
	<?php
	}else{
	?>
	<a class="cart-contents hidden" href="<?php echo WC()->cart->get_cart_url(); ?>" title="<?php _e( 'View your shopping cart' ); ?>"><?php echo sprintf (_n( '%d item', '%d items', WC()->cart->get_cart_contents_count() ), WC()->cart->get_cart_contents_count() ); ?> - <?php echo WC()->cart->get_cart_total(); ?></a> 
	<?php

	}
	$fragments['a.cart-contents'] = ob_get_clean();
	
	return $fragments;
}

//
// Deliv integration
// 

// Settings

// Set to true to test locally (i.e. svpflowers.local)
$local_debugging = false;

if ($local_debugging) {
	// Deliv sandbox API  parameters...
	$store_id = "19fc3813-b4d1-4930-b528-de37eac77142";
	$deliv_url = "https://sandbox.deliv.co/v2/";
	$deliv_api_key = "Api-Key: ae42cdacd35c5fb5fb995e88827202f7ce16";
}
else {
	// Deliv live API parameters
	$store_id = "071be613-d542-4cd4-8acb-7a2679af6cc7";
    $deliv_url = "https://api.deliv.co/v2/";
    $deliv_api_key = "Api-Key: 4549d026f70b3b0e103759a3e710f37a4429";
}


$local_timezone = new DateTimeZone('America/New_York');
$ready_time = "11:00:00";

function deliv_post( $method, $params ) {

	global $deliv_url, $deliv_api_key, $local_debugging;

 	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $deliv_url . $method);
	curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json', $deliv_api_key));
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

	// Local development - set $local_debugging to true
	if ($local_debugging) {
    	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
	}

	$result = curl_exec($ch);
	curl_close($ch);
	
	error_log("Results from Deliv:");
	error_log(print_r($result, true));

	return $result;
}

function get_delivery_estimate( $store_id, $zipcode, $ready_by ) {
	try{
		$params = array();
		$params["store_id"] = $store_id;
		$params["customer_zipcode"] = $zipcode; 
		$params["ready_by"] = $ready_by; 
	
		return deliv_post("delivery_estimates", $params);
		}
    catch(Exception $e)
    {
        error_log($e->getMessage());
    }
	return null;
}

function create_delivery( $store_id, $order_key, $customer, $ready_by, $packages, $delivery_instructions, $delivery_window_id ) {

	try {
		$params = array();
		$params["store_id"] = $store_id;
		$params["order_reference"] = $order_key; 
		$params["customer"] = $customer; 
		$params["ready_by"] = $ready_by; 
		$params["packages"] = $packages; 
		$params["destination_comments"] = $delivery_instructions;
		$params["delivery_window_id"] = $delivery_window_id; 


		return deliv_post("deliveries", $params);
	
	}
    catch(Exception $e)
    {
        error_log($e->getMessage());
    }
	
	return null;
	
}


function get_customer_data($subscription_id)
{
	  $customer_user_id =  get_post_meta( $subscription_id, "_customer_user", true );
      $customer_data = get_userdata((int)$customer_user_id);
	  return customer_data;
}

function create_customer( $subscription ) {
    
    try
    {
       
	    // Customer data is stored in wp_usermeta, except for "Delivery Phone"
	    $customer_user_id =  get_post_meta( $subscription->id, "_customer_user", true );
        $customer_data = get_userdata((int)$customer_user_id);
       
        $customer = array();
        $customer["first_name"] =  $customer_data->shipping_first_name;
        $customer["last_name"] =  $customer_data->shipping_last_name;
        $customer["email"] = $customer_data->billing_email;
        $customer["phone"] = get_post_meta( $subscription->id, "Delivery Phone", true );
        $customer["address_line_1"] = $customer_data->shipping_address_1;
        $customer["address_line_2"] =  $customer_data->shipping_address_2;
        $customer["address_city"] =  $customer_data->shipping_city;
        $customer["address_state"] =  $customer_data->shipping_state;
        $customer["address_zipcode"] =  $customer_data->shipping_postcode;
		
		return $customer;
    }
    catch(Exception $e)
    {
        error_log($e->getMessage());
    }
    
}

function create_packages( $order ) {

	$package = array();
	$package["name"] = "Flower bundle";
	$package["price"] = $order->order_total;

	return array($package);
}

function determine_delivery_window_id( $delivery_estimate, $delivery_day, $delivery_time ) {

	try 
	{
		$starts_at = calculate_iso8601($delivery_day, get_starts_at($delivery_time));
		$ends_at = calculate_iso8601($delivery_day, get_ends_at($delivery_time));

		foreach ($delivery_estimate["delivery_windows"] as $window)
		{
			if ($window["starts_at"] == $starts_at && $window["ends_at"] == $ends_at) 
			{
				return $window["id"];
			}
		}
	}
    catch(Exception $e)
    {
        error_log($e->getMessage());
    }
	return null;
}

function get_hour_from_iso8601( $time ) {

	global $local_timezone;

	$gmt_timezone = new DateTimeZone('GMT');
	$gmt_time = date_create($time, $gmt_timezone);
	$local_offset = $local_timezone->getOffset($gmt_time);

	return date('g', $gmt_time->format('U') + $local_offset);
}

function to_iso8601( DateTime $time_to_convert ) {

	// getOffset() should correctly handle Daylight Savings Time for the local timezone.
	$gmt_timezone = new DateTimeZone('GMT');
	$gmt_offset = $gmt_timezone->getOffset($time_to_convert);
	
	return date('Y-m-d\TH:i:s\Z', $time_to_convert->format('U') + $gmt_offset);
}

function calculate_iso8601( $delivery_day, $time ) {

	global $local_timezone;

	if (is_null($time)) return null;

	$delivery_date_local = date("Y-m-d", strtotime("next " . $delivery_day));
	$delivery_time_local = date_create($delivery_date_local . " " . $time, $local_timezone);
	
	return to_iso8601($delivery_time_local);
}

function calculate_ready_by( $delivery_day ) {

	global $ready_time;

	return calculate_iso8601($delivery_day, $ready_time);
}

function get_starts_at( $delivery_time ) {

	switch ($delivery_time) {

		case "12:00 pm - 3:00 pm":
			return "12:00";

		case "3:00 pm - 6:00 pm":
			return "15:00";

		case "6:00 pm - 9:00 pm":
			return "18:00";
	}
	return null;
}

function get_ends_at( $delivery_time ) {

	switch ($delivery_time) {

		case "12:00 pm - 3:00 pm":
			return "15:00";

		case "3:00 pm - 6:00 pm":
			return "18:00";

		case "6:00 pm - 9:00 pm":
			return "21:00";

		case "Anytime before 5:00 pm":
			return "17:00";

		case "Anytime before 9:00 pm":
			return "21:00";
	}
	return null;
}


function log_($message, $object_to_log){
	error_log($message);
	error_log(print_r($object_to_log, true));
}

/*
    This method is called when the subscription is first created by the customer and on subsequent charges to the credit card.
*/
add_action('woocommerce_subscription_payment_complete', 'schedule_subscription_delivery');
function schedule_subscription_delivery( $subscription ) {

	global $store_id;
		
	log_("subscription:", $subscription);
	
	set_mail_chimp_list_delivery_date($subscription);
	
	$order = $subscription->order;
	log_("order:", $order);
	
	$last_order = $subscription->get_last_order('all');
	log_("last order:", $last_order);
	
	
	// If this is the first time this is method is called for the subscription, 
	// the total for the order will be $0.00 and the last order's id will equal 
	// the id of the subscription.
	if ((double)$order->get_total() === 0.00 && $order->id === $last_order->id) {
		
		error_log("This is the first call to 'woocommerce_subscription_payment_complete' for this subscription. ");
		error_log("The delivery will *not* be scheduled with Deliv");
		return null;
	}
	
	error_log("Scheduling delivery with Deliv.");
		
	$delivery_day = get_post_meta( $subscription->id, "Delivery Day", true );
	$delivery_time = get_post_meta( $subscription->id, "Delivery Time", true );
	$delivery_instructions = get_post_meta( $subscription->id, "Delivery Instructions", true );
	$customer = create_customer( $subscription );
    
    $ready_by = calculate_ready_by( $delivery_day );
    $delivery_estimate = json_decode( get_delivery_estimate( $store_id, $subscription->shipping_postcode, $ready_by ), true );
	
    if (array_key_exists("error", $delivery_estimate))
	{
		send_deliv_schedule_error($customer, $subscription);
		return null;
	}
	
	log_("delivery estimate:",$delivery_estimate);
    $delivery_window_id = determine_delivery_window_id( $delivery_estimate, $delivery_day, $delivery_time );

    $packages = create_packages( $order );
    $delivery = create_delivery( $store_id, $order->order_key, $customer, $ready_by, $packages, $delivery_instructions, $delivery_window_id );
	
	
	
	log_("delivery details:", $delivery);
    return $delivery;
}

function send_deliv_schedule_error($customer, $order) {
	
	global $local_debugging;
	//Subject: non-Deliv delivery order [if possible, use order number and/or name]
    //Body: [full order details, please]
	
	$to = "receive@svp.flowers";
	
	  // write the email content
    //$header = "MIME-Version: 1.0\n";
    //$header .= "Content-Type: text/html; charset=utf-8\n";
    //$header .= "From:" . $from;

    $message = "Order: " . $order->id . "\n";
	$message .= "Customer Info:\n";
	$message .= $customer["first_name"] . " " . $customer["last_name"] . "\n";
	$message .=  $customer["email"] . "\n";
	$message .=  $customer["phone"] . "\n";
	$message .= $customer["address_line_1"] . "\n";
	$message .=  $customer["address_line_2"] . "\n";
	$message .=  $customer["address_city"] . "\n";
	$message .= $customer["address_state"]. "\n";
	$message .= $customer["address_zipcode"]  . "\n";
	
	
    $subject = "non-Deliv delivery order [" . $order->id ."]";
   	
	error_log($subject);
	error_log($message);


    // send the email using wp_mail()
   wp_mail($to, $subject, $message);
       
}

/* Customize the subscription thank you message. */
add_filter( 'woocommerce_subscriptions_thank_you_message', 'custom_subscription_thank_you');
function custom_subscription_thank_you( $order_id ){

    if( WC_Subscriptions_Order::order_contains_subscription( $order_id ) ) {
        $thank_you_message = 'Thank you for purchasing a subscription. Your bundle will be delivered on your preferred delivery day. Visit <a href="' . get_permalink( wc_get_page_id( 'myaccount' ) ) . '">your account page to see its status</a>.<p>';
        echo $thank_you_message;
    }
}


add_filter('wcs_view_subscription_actions', 'add_change_delivery_preferences_action', 100, 2);
function add_change_delivery_preferences_action( $actions, $subscription )
{
	/*echo $subscription->get_status().'<br>';
	echo wcs_get_subscription_status_name( $subscription->get_status() ).'<br>';
	echo '<pre>';print_r($actions);echo '</pre>';
	echo '<pre>';print_r($subscription);echo '</pre>';*/

	/*$svp_subscriber_data = get_transient( 'svp_subscriber_data' );

	if( isset( $svp_subscriber_data['subscr_action'][get_current_user_id()]['process'] )
		&& $svp_subscriber_data['subscr_action'][get_current_user_id()]['process'] == 'pending'
		&& isset( $svp_subscriber_data['subscr_action'][get_current_user_id()]['hold_time'] )
	) {
		//update cron time to run to cancel subscription
		$svp_cron_subscr_restart_time = get_transient( 'svp_cron_subscr_restart_time' );
		$svp_cron_subscr_restart_time[get_current_user_id()] = strtotime('+'.$svp_subscriber_data['subscr_action'][get_current_user_id()]['hold_time'].' month');;
		set_transient( 'svp_cron_subscr_restart_time', $svp_cron_subscr_restart_time );

		//update waiting pending process
		$svp_subscriber_data['subscr_action'][get_current_user_id()]['process'] = '';
		unset( $svp_subscriber_data['subscr_action'][get_current_user_id()]['hold_time'] );
		set_transient( 'svp_subscriber_data', $svp_subscriber_data );
	}*/

	$actions['my-delivery-details'] = array(
       'url' => wc_get_endpoint_url( 'my-delivery-details', $subscription->id, wc_get_page_permalink( 'myaccount' ) ),
       'name' => 'Delivery Preferences'
   );
    
    return $actions;
}


add_filter( 'wc_get_template', 'add_delivery_preferences_template', 10, 3 );
function add_delivery_preferences_template( $located, $template_name, $args ) {
    global $wp;

        if ( 'myaccount/my-account.php' == $template_name && ! empty( $wp->query_vars['my-delivery-details'] ) ) {
        $located = wc_locate_template( 'myaccount/my-delivery-details.php', '', plugin_dir_path( WC_Subscriptions::$plugin_file ) . 'templates/' );
    }

    return $located;
}

   
   
    
add_filter('query_vars', 'add_my_delivery_details_query_var', 10, 1);
function add_my_delivery_details_query_var($query_vars) {
    $query_vars[] = 'my-delivery-details';
    return $query_vars;
    
}


add_action('woocommerce_subscription_status_cancelled','update_mail_chip_merge_status_on_cancellation', 10, 1);
add_action('woocommerce_subscription_status_pending','update_mail_chip_merge_status_on_cancellation', 10, 1);
function update_mail_chip_merge_status_on_cancellation($subscription)
{
	$customer_user_id =  get_post_meta( $subscription->id, "_customer_user", true );
	if (wcs_user_has_subscription($customer_user_id, '', 'active'))
	{
		error_log($customer_user_id . " has active subscriptions, returning.");
		return;
	}
		
	set_mail_chimp_list_status($subscription, "Inactive");
}

add_action('woocommerce_subscription_status_expired', 'update_mail_chip_merge_status_on_expiration', 10, 1);
function update_mail_chip_merge_status_on_expiration($subscription) {
	
   //error_log("update_mail_chip_merge_status_on_expiration called");
	//error_log(print_r($subscription, true));
	$customer_user_id =  get_post_meta( $subscription->id, "_customer_user", true );	
	//error_log(print_r($customer_user_id , true));
	if (wcs_user_has_subscription($customer_user_id, '', 'active'))
	{
		error_log($customer_user_id . " has active subscriptions");
		return;
	}
	
	//the user does not have any active subscriptions so call MailChimp and 
	//set the merge statis to 'Inactive'
	set_mail_chimp_list_status($subscription, "Inactive");
}



add_action('woocommerce_subscription_status_active', 'update_mail_chip_merge_status_on_activation', 10, 1);
function update_mail_chip_merge_status_on_activation($subscription) {
	set_mail_chimp_list_status($subscription, "Active");
}

function set_mail_chimp_list_status($subscription, $status)
{
	 error_log("set_mail_chimp_list_status called");
	
	 $customer_user_id =  get_post_meta( $subscription->id, "_customer_user", true );
     $customer_data = get_userdata((int)$customer_user_id);
	 
	 $merge_vars = array("Status"=>$status);
	 $member_email = $customer_data->billing_email;
	 
	 $retval = update_mail_chimp_merge_vars($merge_vars, $member_email);
	 

}


function set_mail_chimp_list_delivery_date($subscription)
{
	error_log("set_mail_chimp_list_delivery_date");
	$delivery_date = date_create($subscription->last_payment_date);
	
	//error_log(print_r($subscription, true));
	//error_log("Last payment date");
	//error_log(print_r($subscription->last_payment_date, true));
	//error_log("Next payment date");
	//error_log(print_r($subscription->next_payment_date, true));
	
	$customer_user_id =  get_post_meta( $subscription->id, "_customer_user", true );
    $customer_data = get_userdata((int)$customer_user_id);
	 
	$merge_vars = array("Delivery"=>date_format($delivery_date, "m/d/Y"), "field_type"=>"date");
	$member_email = $customer_data->billing_email;
	 
	$retval = update_mail_chimp_merge_vars($merge_vars, $member_email);
}

function update_mail_chimp_merge_vars($merge_vars, $member_email)
{
    $apikey = "8dac6920f54bdb011eed492ed859d63c-us12";
	$api = new MCAPI($apikey);
	$listId = '1a49c92849';
	
	$retval = $api->listUpdateMember($listId, $member_email, $merge_vars, 'html', false);
	
	if ($api->errorCode){
		error_log( "Unable to update member info!\n");
		error_log( "\tCode=".$api->errorCode."\n");
		error_log("\tMsg=".$api->errorMessage."\n");
	} else {    
		error_log( "Returned: ".$retval."\n");
	}
	
	return $retval;
}

add_action('woocommerce_after_cart_table', 'update_redeem_ui');
add_action('woocommerce_review_order_after_payment', 'update_redeem_ui');
function update_redeem_ui() {
  if (is_redeem_bundle_in_cart() )
  {
 	echo "<style>#ship-to-different-address, .subscription-price, .product-quantity, .recurring-totals, .recurring-total {display: none;}</style>";
  }
}

add_filter('woocommerce_cart_totals_coupon_html', 'rename_free_shipping_label');
function rename_free_shipping_label( $value )
{
	$value = str_replace( 'Free shipping coupon', 'Gift Subscription', $value );
	return $value;
}

// add message to checkout page for regular subscriptions only
function message_checkout() {
 if ( is_regular_bundle_in_cart() ) {
echo '<h3 id="message_checkout">You\'re one page away from your bundle</h3>';
 }
}
add_action( 'woocommerce_before_checkout_form', 'message_checkout' );

add_filter('woocommerce_cart_needs_payment', 'gift_redemption_needs_payment');
function gift_redemption_needs_payment ( $needs_payment, $cart ) {

	if ( WC()->cart->get_cart_contents_count() == 1 && is_redeem_bundle_in_cart() ) {
		$needs_payment = false;
	}
	return $needs_payment;
}


// NB:  WIP:  Please leave until Gerfen removes it or unremarks.
// add_action( 'subscriptions_activated_for_order','adjust_subscription_dates', 50, 1 );
// function adjust_subscription_dates($order_id)
// {
// 	error_log('adjust_subscription_dates called');
// 		if ( wcs_order_contains_subscription( $order_id ) ) {

// 			$subscriptions   = wcs_get_subscriptions_for_order( $order_id );
// 				foreach ( $subscriptions as $subscription ) {
// 					error_log("....found subscriptions");
// 					if ( WC_Subscriptions_Synchroniser::subscription_contains_synced_product( $subscription ) ) {
					
// 						$first_payment_date = date('Y-m-d H:i:s', $subscription->get_time('next_payment'));
// 						$start_date = date('Y-m-d H:i:s',$subscription->get_time('start'));
// 						$first_payment_day_of_week = date_format(date_create($first_payment_date), 'l');
						
// 						log_("first payment date", $first_payment_date);
// 						log_("start date", $start_date);
// 						log_("first payment day of week", $first_payment_day_of_week);
						
// 						if ($first_payment_day_of_week === 'Wednesday' && 
// 							( $subscription->get_time('next_payment') < $subscription->get_time('start') ))
// 						{
// 							$dates = array();
// 							foreach ( array( 'start', 'next_payment', 'last_payment' ) as $date_type ) {
// 							{
								
// 								$date = date('Y-m-d H:i:s', $subscription->get_time($date_type));
// 								$future_date = date_add($date, date_interval_create_from_date_string('7 days'));
								
// 								error_log("updating ". $date_type . " from " . print_r($date, true) . " to " . print_r($future_date, true));
// 								$dates[$date_type] = $future_date->getTimestamp();
// 							}
							
// 							$subscription->update_dates($dates);
// 						}
// 					}
// 				}
// 		}
// 	}
// }

add_filter('woocommerce_subscriptions_synced_first_payment_date', 'calculate_subscription_first_payment_date', 10, 5);
// This is called when a subscription is synchronized and the first payment date needs to be determined.
// NB:  $type can be one of 'mysql' or 'timestamp'
function calculate_subscription_first_payment_date($first_payment, $product, $type, $from_date, $from_date_param) {
		
	$first_payment_date_string = ($type ==='timestamp') ? date('Y-m-d H:i:s', $first_payment) : $first_payment;
	$first_payment_date = date_create($first_payment_date_string);
	$first_payment_day_of_week = date_format($first_payment_date, 'l');
	
	
	// logging (can be removed)
	error_log("calculate_subscription_first_payment_date: begin");
	log_("first payment:", $first_payment);
	log_("first payment date:", $first_payment_date_string);
	log_("first payment day of week:", $first_payment_day_of_week);
	log_("type:", $type);
	log_("from_date:", $from_date);
	log_("from_date_param:", $from_date_param);
	//log_("product:", $product);
	error_log("calculate_subscription_first_payment_date: end");
	
	// if it's Wednesday and the precalculated first payment date is less than the 'from' date, then add seven days
	// to the first payment date.
	if ($first_payment_day_of_week === 'Wednesday' && ( $first_payment_date->getTimestamp() < strtotime($from_date) ) )
	{
		error_log("Correcting first payment date");
		/*$future_date = date_add($first_payment_date, date_interval_create_from_date_string('7 days'));
		$first_payment = $future_date->getTimestamp();*/
		
		// New code by prabhat for adjust time
		$dt = new DateTime($first_payment_date);
		$dt->modify('+7 days');
		$dt->setTime(8, 00, 00);
		$first_payment=$dt->getTimestamp();
		
		//For Testing email
		$to = "prabhat.thakur@effectualtech.com";

		$message = "Successfully Test \n";
		$message .= "Customer Info:\n";
		$subject = "Adjust Time".date("d-m-Y h:m:s");
		wp_mail($to, $subject, $message);
		
		log_("Corrected first payment date:", $first_payment);
	}
	
	return $first_payment;
		
}


// add Referral section to My Account
 function referral_myaccount() {
 echo '<div class="account-referrals"><h2>My Referrals</h2><p>Earn 1 point for each friend you refer to purchase any SVP Flowers bundle subscription. <a href="mailto:receive@svp.flowers">Let us know</a> when you get 4 points and we\'ll give you a free bundle!</p>';
 echo do_shortcode( '[vc_row_inner][vc_column_inner width="1/2"][vc_column_text][rs_generate_static_referral]<p>&nbsp;</p><h3>Send a Referral Email</h3>[rs_refer_a_friend][/vc_column_text][/vc_column_inner][vc_column_inner width="1/2"][vc_column_text]<h3 style="margin-top:20px;">My Current Referral Points</h3>[rs_my_reward_points][/vc_column_text][/vc_column_inner][/vc_row_inner]' );
 echo '</div>';
 }
 add_action( 'woocommerce_after_my_account', 'referral_myaccount' );

/**custom**/
 add_action( 'wp_ajax_add_sub_hold_time', function(){

	 if( !is_numeric( $_POST['hold_time'] )) return;

	 $new_url = add_query_arg( array(
		 'time' => $_POST['hold_time'],
		 'suspending_unit' => $_POST['suspending_unit']
	 ), $_POST['redirect_url'] );
	 echo $new_url;

	 /*$svp_subscriber_data = get_transient( 'svp_subscriber_data' );

	 $svp_subscriber_data['subscr_action'][get_current_user_id()]['process'] = 'pending';
	 $svp_subscriber_data['subscr_action'][get_current_user_id()]['hold_time'] = $_POST['hold_time'];
	 set_transient( 'svp_subscriber_data', $svp_subscriber_data );*/
	 exit;
 });

// if new status is 'suspend', trigger this
	add_action( 'woocommerce_customer_changed_subscription_to_on-hold' , function( $subscription ) {

		if( !isset( $_GET['time'] ) ) return;

		if( !isset( $_GET['suspending_unit'] ) ) return;

		if( is_numeric( $_GET['time'] ) ) {

			//update cron time to run to cancel subscription
			$svp_cron_subscr_restart_time = get_transient( 'svp_cron_subscr_restart_time' );

			$date_key = date( 'd-m-y', strtotime('+'.$_GET['time'].' '.$_GET['suspending_unit']) );
			$svp_cron_subscr_restart_time[$subscription->id] = $date_key;
			set_transient( 'svp_cron_subscr_restart_time', $svp_cron_subscr_restart_time );
		}

		if( isset( $_GET['profile_user_id'] ) && is_numeric( $_GET['profile_user_id'] ) ) {
			wp_redirect( get_edit_user_link( $_GET['profile_user_id'] ) );
		}

	}, 10, 1 );

//redirect depending on where the reques has come from
add_action( 'woocommerce_customer_changed_subscription_to_active', 'svp_redirect_page');
add_action( 'woocommerce_customer_changed_subscription_to_cancelled', 'svp_redirect_page');

function svp_redirect_page( $subscription ){
	if( isset( $_GET['profile_user_id'] ) && is_numeric( $_GET['profile_user_id'] ) ) {
		wp_redirect( admin_url().'user-edit.php?user_id='.$_GET['profile_user_id']);
		exit;
	}
}

add_action( 'wp_head', function(){

});



add_action( 'show_user_profile', 'svp_show_extra_profile_fields' );
add_action( 'edit_user_profile', 'svp_show_extra_profile_fields' );

function svp_show_extra_profile_fields( $user ) {

	$subscriptions = WC_Subscriptions_Manager::get_users_subscriptions( $user->ID );

	?>

	<h3>Subscription information</h3>

	<table class="form-table" style="text-align: center;">
		<tr>
			<th style="text-align: center;"><label for="twitter">Subscription ID</label></th>
			<th style="text-align: center;">Product Name</th>
			<th style="text-align: center;">Status</th>
		<?php if( current_user_can( 'manage_options' ) ) : ?>
			<th style="text-align: center;">Action</th>
		<?php endif; ?>
		</tr>
		<?php foreach( $subscriptions as $sub_prod_key => $data ) : ?>

			<?php
			$sub_prod_key = explode( '_', $sub_prod_key );

			if( !is_array( $sub_prod_key ) )continue;

			if( count($sub_prod_key) < 2 ) continue;

			?>
			<tr>
				<td><?php echo $sub_prod_key[0];?></td>
				<td><?php echo get_the_title( $sub_prod_key[1] );?></td>
				<td><?php echo $data['status']; ?>
					<?php
					if( $data['status'] == 'on-hold' ) {
						?>
						<br>
						Time : <?php echo $data['interval'].' '.$data['period']; ?>
						<?php
					}
					?>
				</td>
				<?php if( current_user_can( 'manage_options' ) ) : ?>
					<td style="padding: 15px 0px">
						<?php
						$subscription = wcs_get_subscription( $sub_prod_key[0] );
						remove_filter('woocommerce_available_payment_gateways', 'filter_gateway');
						remove_filter('woocommerce_available_payment_gateways', 'filter_product');
						remove_filter('woocommerce_available_payment_gateways', 'filter_product_point_price');

						$actions = wcs_get_all_user_actions_for_subscription( $subscription, $user->ID );
						?>
						<?php if ( ! empty( $actions ) ) : ?>
							<?php
							$is_suspend_btn = 0;
							?>
							<?php foreach ( $actions as $key => $action ) : ?>
								<?php
								$action['url'] = add_query_arg( array(
									'profile_user_id' => $user->ID
								), $action['url'] );
								if( $key == 'suspend') :
									?>
									<?php $suspension_url = $action['url'];?>
								<?php endif; ?>
								<a href="<?php echo $key != 'suspend' ? esc_url( $action['url'] ) : 'javascript:'; ?>" class="button <?php echo sanitize_html_class( $key ) ?>"><?php echo esc_html( $action['name'] ); ?></a>
								<?php
								if( $key == 'suspend' ){
									$is_suspend_btn = 1;
								}
								?>
							<?php endforeach; ?>
							<?php
							if( $is_suspend_btn == 1 ) :
								?>
									<div>
										<div style="display: none;"><?php _e( 'Suspension Duration : ' ) ; ?></div>
										<div style="display: none;">
											<select name="suspending_time" id="suspending_time" style="display: inline; ">
												<option value="">- Select Duration -</option>
												<option value="1">1</option>
												<option value="2">2</option>
												<option value="3">3</option>
											</select>
											<select name="suspending_unit" id="suspending_unit" style="display: inline; min-width: 100px ">
												<option value="day"><?php _e( 'Day' ); ?></option>
												<option value="month"><?php _e( 'Month' ); ?></option>
											</select>
											<a href="<?php echo esc_url( $suspension_url ); ?>" class="button <?php echo 'suspend_btn' ?>"><?php echo 'Go'; ?></a>
										</div>
									</div>
								<?php
							endif;
							?>
						<?php endif; ?>
						<script>
							(function($){
								$(document).ready(function(){
									$(document).on( 'click', 'a.button.suspend', function(){
										$('#suspending_time').parent().slideDown('fast').siblings().slideDown('fast');
									}).on( 'click', 'a.button.suspend_btn', function(){

										if( $('#suspending_time').val() ) {

											var redirect_url = $('.suspend_btn').attr('href');

											$.post(
												'<?php echo admin_url('admin-ajax.php');?>',
												{
													action: 'add_sub_hold_time',
													hold_time : $('#suspending_time').val(),
													suspending_unit : $('#suspending_unit').val(),
													redirect_url : redirect_url
												},
												function(data) {
													window.location = data ;
												}
											)
										}

										return false;
									} )
								})
							}(jQuery))
						</script>
					</td>
			<?php endif; ?>
			</tr>

		<?php endforeach; ?>
	</table>
<?php }

if( !function_exists( 'wc_add_notice' ) ) {
	function wc_add_notice( $message, $notice_type = 'success' ) {
	}
}