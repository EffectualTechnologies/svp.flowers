<?php

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly
}


class SVP_Form_Handler {
	
	public static function init() {
		
		//error_log("SVP_Form_Handler::init() called");
		add_action( 'template_redirect', array( __CLASS__, 'save_delivery_preferences' ) );
	}

   public static function save_delivery_preferences()
	{
		
		global $wp;
		
		if ( 'POST' !== strtoupper( $_SERVER[ 'REQUEST_METHOD' ] ) ) {
			return;
		}

		if ( empty( $_POST[ 'action' ] ) || 'save_delivery_preferences' !== $_POST[ 'action' ] || empty( $_POST['_wpnonce'] ) || ! wp_verify_nonce( $_POST['_wpnonce'], 'save_delivery_preferences' ) ) {
			return;
		}
		
	   //error_log("SVP_Form_Handler::save_delivery_preferences()() called");
 
		$subscription_id = $_POST['subscription_id'];
		$delivery_day = $_POST['delivery_day'];
		$delivery_time = $_POST['delivery_time'];
		$delivery_phone = $_POST['delivery_phone'];
		$redirect_url = $_POST['redirect_url'];
		
	    $delivery_day = update_post_meta( $subscription_id, "Delivery Day", $delivery_day );
		$delivery_time = update_post_meta( $subscription_id, "Delivery Time", $delivery_time );
		$delivery_phone = update_post_meta( $subscription_id, "Delivery Phone", $delivery_phone );
	
	    // $resubscribe_id = get_post_meta( $subscription_id, "_subscription_resubscribe", true );
		// $delivery_day = update_post_meta( $resubscribe_id, "Delivery Day", $delivery_day );
		// $delivery_time = update_post_meta( $resubscribe_id, "Delivery Time", $delivery_time );
		// $delivery_phone = update_post_meta( $resubscribe_id, "Delivery Phone", $delivery_phone );

		wc_add_notice( 'Delivery preferences changed successfully');
		wp_safe_redirect($redirect_url);
		exit;
	}

}