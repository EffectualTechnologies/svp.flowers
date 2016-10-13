<?php

function init_reward_gateway_class() {

    if (!class_exists('WC_Payment_Gateway'))
        return;

    class WC_Reward_Gateway extends WC_Payment_Gateway {

        public function __construct() {
            global $woocommerce;
            $this->id = 'reward_gateway';
            $this->method_title = __('SUMO Reward Points Gateway', 'woocommerce');
            $this->has_fields = false; //Load Form Fields
            $this->init_form_fields();
            $this->init_settings();
            $this->title = $this->get_option('gateway_titles');
            $this->description = $this->get_option('gateway_descriptions');
            add_action('woocommerce_update_options_payment_gateways_' . $this->id, array($this, 'process_admin_options'));
        }

        function init_form_fields() {
            $this->form_fields = array(
                'enabled' => array(
                    'title' => __('Enable/Disable', 'woocommerce'),
                    'type' => 'checkbox',
                    'label' => __('Enable Rewards Point Gateway', 'woowcommerce'),
                    'default' => 'no'
                ),
                'gateway_titles' => array(
                    'title' => __('Title', 'woocommerce'),
                    'type' => 'text',
                    'description' => __('This Controls the Title which the user sees during checkout', 'woocommerce'),
                    'default' => __('SUMO Reward Points', 'woocommerce'),
                    'desc_tip' => true,
                ),
                'gateway_descriptions' => array(
                    'title' => __('Description', 'woocommerce'),
                    'type' => 'textarea',
                    'description' => __('This controls the description which the user sees during checkout.', 'woocommerce'),
                    'default' => __('Pay with your SUMO Reward Points', 'woocommerce'),
                    'desc_tip' => true,
                ),
                'error_payment_gateway' => array(
                    'title' => 'Error Message',
                    'type' => 'textarea',
                    'description' => __('This Controls the errror message which is displayed during Checkout', 'woocommerce'),
                    'desc_tip' => true,
                    'default' => __('You need [needpoints] Points in your Account .But You have only [userpoints] Points.', 'rewardsystem'),
                ),
                'error_message_for_payment_gateway' => array(
                    'title' => 'Error Message for Payment Gateway',
                    'type' => 'textarea',
                    'description' => __('This Controls the error message which is displayed during Checkout', 'woocommerce'),
                    'desc_tip' => true,
                    'default' => __('Maximum Cart Total has been Limited to [maximum_cart_total]'),
                ),
            );
        }

        function process_payment($order_id) {
            global $woocommerce;
            $total1 = 0;
            $totalpoints1 = 0;
            $totalpoints2 = 0;
            $totalvariable = 0;
            $varpoints = array();
            $total11 = array();
            $points = array();
            $total = array();
            $couponamount1 = array();
            $order = new WC_Order($order_id);
            update_post_meta($order_id, 'pointsvalue', '1');
            foreach ($order->get_items()as $item) {
                $product_id = $item['variation_id'] != 0 ? $item['variation_id'] : $item['product_id'];
                $enable = RSFunctionForCart::calculate_point_price_for_products($product_id);
                if ($enable[$product_id] != '') {
                    $cart_object = $enable[$product_id] * $item['qty'];
                    $array[] = $cart_object;
                } else {
                    $linetotal[] = $item['line_subtotal'];
                }           
                 if (get_option('woocommerce_prices_include_tax') === 'yes') {
                      $shipping_total = $order->get_total_shipping();
                $tax_total = 0;
                 }else{
                      $shipping_total = $order->get_total_shipping();
                $tax_total = $order->get_total_tax();
                 }
            }
            $totalrewardpointprice = array_sum($array);
            $totalbalancepoints = array_sum($linetotal);
            $totalbalancepoints = $tax_total + $shipping_total + $totalbalancepoints;
            $newvalue = $totalbalancepoints / wc_format_decimal(get_option('rs_redeem_point_value'));
            $updatedvalue = $newvalue * wc_format_decimal(get_option('rs_redeem_point'));
            $ordertotal = $order->get_total();
            $getuserid = $order->user_id;
            $couponcodeuserid = get_userdata($getuserid);
            $couponcodeuserlogin = $couponcodeuserid->user_login;
            $usernickname = 'sumo_' . strtolower("$couponcodeuserlogin");
            $current_conversion = wc_format_decimal(get_option('rs_redeem_point'));
            $point_amount = wc_format_decimal(get_option('rs_redeem_point_value'));
            $getmyrewardpoints = RSPointExpiry::get_sum_of_total_earned_points($getuserid);
            $getmaxoption = get_option('rs_max_redeem_discount_for_sumo_reward_points');

            if (isset($woocommerce->cart->coupon_discount_amounts["$usernickname"])) {
                $total4 = $woocommerce->cart->coupon_discount_amounts[$usernickname];
                $total5 = $total4 * $current_conversion;
                $total6 = $total5 / $point_amount;
                $userpoints = $getmyrewardpoints - $total6;
            } else {
                $userpoints = $getmyrewardpoints != NULL ? $getmyrewardpoints : '0';
            }
            $rewardpointscoupons = $order->get_items(array('coupon'));
            $getuserdatabyid = get_user_by('id', $order->user_id);
            $getusernickname = $getuserdatabyid->user_login;

            $auto_redeem_name = 'auto_redeem_' . strtolower($getusernickname);
            $maincouponchecker = 'sumo_' . strtolower($getusernickname);
            foreach ($rewardpointscoupons as $coupon) {

                if ($auto_redeem_name == $coupon['name'] || $maincouponchecker == $coupon['name']) {
                    $couponamount1[] = $coupon['discount_amount'];
                }
            }

            $couponamount = array_sum($couponamount1);

            $redeemedpoints = $totalrewardpointprice + $updatedvalue;
            $redeemedpoints = $redeemedpoints - $couponamount;
            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
            update_post_meta($order_id, 'total_redeem_points_for_order_point_price', $redeemedpoints);
            if ($redeemedpoints > $getmyrewardpoints) {
                $error_msg = $this->get_option('error_payment_gateway');
                $find = array('[userpoints]', '[needpoints]');
                $roundvalueuserpoint = round($userpoints,$roundofftype);
                $roundvalueredeempoint = round($redeemedpoints,$roundofftype);
                $replace = array($roundvalueuserpoint, $roundvalueredeempoint);
                $finalreplace = str_replace($find, $replace, $error_msg);
                wc_add_notice(__($finalreplace, 'woocommerce'), 'error');

                return;
            } else {
                if ($getmaxoption != '') {
                    if ($getmaxoption > $ordertotal) {
                        $error_msg = $this->get_option('error_message_for_payment_gateway');
                        $find = array('[maximum_cart_total]');
                        $roundvaluemaxoption = round($getmaxoption,$roundofftype);
                        $replace = $roundvaluemaxoption;
                        $finalreplace = str_replace($find, get_woocommerce_currency_symbol() . $replace, $error_msg);
                        wc_add_notice(__($finalreplace, 'woocommerce'), 'error');
                        return;
                    }
                }
            }


            $order->payment_complete();
            $order_status = get_option('rs_order_status_after_gateway_purchase');

            $order->update_status($order_status);
            //Reduce Stock Levels
            $order->reduce_order_stock();

            //Remove Cart
            $woocommerce->cart->empty_cart();

            //Redirect the User
            return array(
                'result' => 'success',
                'redirect' => $this->get_return_url($order)
            );
            wc_add_notice(__('Payment error:', 'woothemes') . $error_message, 'error');
            return;
        }

    }

    add_filter('woocommerce_available_payment_gateways', 'filter_gateway', 10, 1);

    add_filter('woocommerce_available_payment_gateways', 'filter_product', 10, 1);

    add_filter('woocommerce_available_payment_gateways', 'filter_product_point_price', 10, 1);

    function filter_product_point_price($gateways) {
        global $woocommerce;
        foreach ($woocommerce->cart->cart_contents as $key => $values) {

            $productid = $values['variation_id'] != 0 ? $values['variation_id'] : $values['product_id'];
            $typeofprice = RSFunctionForCart::check_display_price_type($productid);
            if ($typeofprice == '2') {
                foreach (WC()->payment_gateways->payment_gateways() as $gateway) {
                    if ($gateway->id != 'reward_gateway') {
                        unset($gateways[$gateway->id]);
                    }
                }
            } else {

            }
        }



        return $gateways != 'NULL' ? $gateways : array();
    }

    function filter_product($gateways) {
        global $woocommerce;
        $enable = get_option('rs_exclude_products_for_redeeming');

        if ($enable == 'yes') {
            foreach ($woocommerce->cart->cart_contents as $key => $values) {
                $productid = $values['product_id'];

                if (get_option('rs_exclude_products_to_enable_redeeming') != '') {
//
                    if (!is_array(get_option('rs_exclude_products_to_enable_redeeming'))) {
                        if ((get_option('rs_exclude_products_to_enable_redeeming') != '' && (get_option('rs_exclude_products_to_enable_redeeming') != NULL))) {
                            $product_id = explode(',', get_option('rs_exclude_products_to_enable_redeeming'));
                        }
                    } else {
                        $product_id = get_option('rs_exclude_products_to_enable_redeeming');
                    }


                    if (in_array($productid, (array) $product_id)) {
                        foreach (WC()->payment_gateways->payment_gateways() as $gateway) {
                            if ($gateway->id == 'reward_gateway') {
                                unset($gateways[$gateway->id]);
                            }
                        }
                    }
                }
            }
        }
        return $gateways != 'NULL' ? $gateways : array();
    }

    function filter_gateway($gateways) {

        global $woocommerce;
        $enableproductpurchase = get_option('rs_enable_selected_product_for_purchase_using_points');
        if (($enableproductpurchase == 'yes')) {
            foreach ($woocommerce->cart->cart_contents as $key => $values) {
                $productid = $values['product_id'];
                if (get_option('rs_select_product_for_purchase_using_points') != '') {
                    if (!is_array(get_option('rs_select_product_for_purchase_using_points'))) {
                        if ((get_option('rs_select_product_for_purchase_using_points') != '' && (get_option('rs_select_product_for_purchase_using_points') != NULL))) {
                            $product_id = explode(',', get_option('rs_select_product_for_purchase_using_points'));
                        }
                    } else {
                        $product_id = get_option('rs_select_product_for_purchase_using_points');
                    }


                    if (in_array($productid, (array) $product_id)) {
                        foreach (WC()->payment_gateways->payment_gateways() as $gateway) {
                            if ($gateway->id != 'reward_gateway') {
                                unset($gateways[$gateway->id]);
                            }
                        }
                    }
                }
            }
        }
        return $gateways != 'NULL' ? $gateways : array();
    }

    add_filter('woocommerce_add_to_cart_validation', 'sell_individually_for_point_pricing', 10, 6);

    function sell_individually_for_point_pricing($passed, $product_id, $product_quantity, $variation_id = '', $variatins = array(), $cart_item_data = array()) {
        global $woocommerce;
        $productnametodisplay = '';
        $sellindividuallyproducts = array();
        $excludedproductids = array();
        $msgtoreplace = array();
        $current_strtofind = "[productname]";
        $getstrtodisplay = get_option('rs_errmsg_when_other_products_added_to_cart_page');
        if (!is_array(get_option('rs_select_product_for_purchase_using_points'))) {
            $strtodisplay = explode(',', get_option('rs_select_product_for_purchase_using_points'));
        } else {
            $strtodisplay = get_option('rs_select_product_for_purchase_using_points');
        }



        $sellindividuallyproducts = $strtodisplay;
        $sellindividuallyproductsids = array();
        $productid = array();
        $getcartcount = $woocommerce->cart->cart_contents_count;
        foreach ($woocommerce->cart->cart_contents as $key => $values) {
            $productorvarid = $values['variation_id'] > '0' ? $values['variation_id'] : $values['product_id'];
            $productid[] = $values['product_id'];
            if (in_array($productorvarid, $sellindividuallyproducts)) {
                $sellindividuallyproductsids[] = $productorvarid;
            } else {
                $excludedproductids[] = $productorvarid;
            }
        }
        $enableproductpurchase = get_option('rs_enable_selected_product_for_purchase_using_points');
        if ($enableproductpurchase == 'yes') {
            $varorproid = $variation_id == '' ? $product_id : $variation_id;
            if (in_array($varorproid, $strtodisplay)) {
                if (empty($excludedproductids) && in_array($varorproid, $strtodisplay)) {
                    $passed = true;
                } else {
                    $woocommerce->cart->empty_cart();
                    $woocommerce->cart->remove_coupons();
                    $passed = true;
                }
            } else {
                if (is_array($sellindividuallyproductsids)) {
                    if (!empty($sellindividuallyproductsids)) {
                        $woocommerce->cart->empty_cart();
                        $woocommerce->cart->remove_coupons();
                        foreach ($sellindividuallyproductsids as $product) {
                            $productnametodisplay = get_the_title($product);
                            $msgtoreplace = str_replace($current_strtofind, $productnametodisplay, $getstrtodisplay);
                            wc_add_notice(__($msgtoreplace), 'error');
                        }
                    }
                }
                $passed = true;
            }
        }

        return $passed;
    }

    function add_your_gateway_class($methods) {
        if (is_user_logged_in()) {
            $userid = get_current_user_id();
            $banning_type = FPRewardSystem::check_banning_type($userid);
            if ($banning_type != 'redeemingonly' && $banning_type != 'both') {
                $methods[] = 'WC_Reward_Gateway';
            }
        }
        return $methods;
    }

    add_filter('woocommerce_payment_gateways', 'add_your_gateway_class');
}
