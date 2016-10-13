<?php

/*
 * Reward Points Compatible with 2.6 of WooCommerce
 */

class FP_Reward_Points_WC_2P6 {

    function __construct() {
        add_action('wp_ajax_apply_sumo_reward_points', array($this, 'apply_redeeming_points'), 999);
        add_action('wp_ajax_sumo_updated_cart_total', array($this, 'recalculate_totals'), 999);
        add_action('wp_enqueue_scripts', array($this, 'enqueue_scripts'), 999);
        add_action('wp_ajax_sumo_remove_coupon', array($this, 'remove_coupon_from_cart'), 999);
    }

    // Reward Points Compatible with Version 2.6 of WooCommerce
    public static function apply_redeeming_points() {
        RSFunctionToApplyCoupon::apply_matched_coupons();
        wc_print_notices();
        die();
    }

    // Recalculate Totals
    public static function recalculate_totals() {

        if (!defined('WOOCOMMERCE_CART')) {
            define('WOOCOMMERCE_CART', true);
        }
        WC()->cart->calculate_totals();
        RSFunctionForCart::get_reward_points_to_display_msg_in_cart_and_checkout();
        RSFunctionForCart::display_msg_in_cart_page();
        RSFunctionForCheckout::display_complete_message_cart_page();
        RSFunctionForCheckout::your_current_points_cart_page();
        RSFunctionForCart::display_msg_in_cart_page_for_balance_reward_points();
        RSFunctionForCart::display_redeem_points_buttons_on_cart_page();

        woocommerce_cart_totals();

        die();
    }

    // Remove Coupon from Cart

    public static function remove_coupon_from_cart() {
        if (isset($_POST['coupon'])) {
            $coupon = wc_clean($_POST['coupon']);
            WC()->cart->remove_coupon($coupon);
            RSFunctionForCart::get_reward_points_to_display_msg_in_cart_and_checkout();
            RSFunctionForCart::display_msg_in_cart_page();
            RSFunctionForCheckout::display_complete_message_cart_page();
            RSFunctionForCheckout::your_current_points_cart_page();
            RSFunctionForCart::display_msg_in_cart_page_for_balance_reward_points();
            RSFunctionForCart::display_redeem_points_buttons_on_cart_page();

            woocommerce_cart_totals();
        }
        die();
    }

    //register enqueue script for to perform redeeming on cart FP_Reward_Points_Main_Path
    public function enqueue_scripts() {
        global $woocommerce;
        if ((float) $woocommerce->version >= (float) ('2.6.0')) {
            //echo "you are right";
            if (is_cart() && is_user_logged_in()) {
                wp_enqueue_script('jquery');
                wp_register_script('sumo_reward_points_wc2p6', plugins_url('/js/sumorewardpoints_wc2p6.js', FP_Reward_Points_Main_Path));
                $global_variable_for_js = array('wp_ajax_url' => admin_url('admin-ajax.php'), 'user_id' => get_current_user_id());
                wp_localize_script('sumo_reward_points_wc2p6', 'sumo_global_variable_js', $global_variable_for_js);
                wp_enqueue_script('sumo_reward_points_wc2p6', false, array(), '', true);
            }
        }
    }

}

new FP_Reward_Points_WC_2P6();