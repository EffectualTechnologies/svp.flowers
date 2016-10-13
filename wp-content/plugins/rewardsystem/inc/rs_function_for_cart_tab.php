<?php

class RSFunctionForCart {

    public function __construct() {

        add_action('admin_head', array($this, 'rs_redeeming_selected_products_categories'));

        add_action('woocommerce_cart_totals_before_order_total', array($this, 'display_total_earned_points'));

        add_action('woocommerce_update_options_exclude_product_selection', array($this, 'save_product_to_exclude'));

        add_action('woocommerce_update_options_include_product_selection', array($this, 'save_product_to_include'));

        add_filter('woocommerce_cart_totals_coupon_label', array($this, 'change_auto_coupon_label'), 1, 2);

        add_action('woocommerce_admin_field_exclude_product_selection', array($this, 'rs_select_product_to_exclude'));

        add_action('woocommerce_admin_field_include_product_selection', array($this, 'rs_select_product_to_include'));

        add_action('admin_head', array($this, 'rs_validation_of_input_field_in_cart'));

        add_action('wp_head', array($this, 'show_hide_coupon_code'), 1);
        add_action('wp_head', array($this, 'test_coupon'));

        if (get_option('rs_show_hide_redeem_field') == '1') {
            if (get_option('rs_reward_point_troubleshoot_after_cart') == '1') {
                add_action('woocommerce_after_cart_table', array($this, 'reward_system_add_message_after_cart_table'));
            } else {
                add_action('woocommerce_cart_coupon', array($this, 'reward_system_add_message_after_cart_table'));
            }
        }

        if (get_option('rs_reward_point_troubleshoot_before_cart') == '1') {
            add_action('woocommerce_before_cart', array($this, 'get_reward_points_to_display_msg_in_cart_and_checkout'));
        } else {
            add_action('woocommerce_before_cart_table', array($this, 'get_reward_points_to_display_msg_in_cart_and_checkout'));
        }
        add_action('woocommerce_before_checkout_form', array($this, 'get_reward_points_to_display_msg_in_cart_and_checkout'));

        if (get_option('rs_reward_point_troubleshoot_before_cart') == '1') {
            add_action('woocommerce_before_cart', array($this, 'display_msg_in_cart_page'));
        } else {
            add_action('woocommerce_before_cart_table', array($this, 'display_msg_in_cart_page'));
        }
        add_action('woocommerce_before_checkout_form', array($this, 'display_msg_in_checkout_page'));

        if (get_option('rs_reward_point_troubleshoot_before_cart') == '1') {
            add_action('woocommerce_before_cart', array($this, 'display_msg_in_cart_page_for_balance_reward_points'));
        } else {
            add_action('woocommerce_before_cart_table', array($this, 'display_msg_in_cart_page_for_balance_reward_points'));
        }
        add_action('woocommerce_before_checkout_form', array($this, 'display_msg_in_checkout_page_for_balance_reward_points'));

        add_shortcode('redeempoints', array($this, 'get_redeem_point_to_display_in_msg'));

        add_shortcode('rspoint', array($this, 'get_each_product_price_in_cart'));

        add_shortcode('titleofproduct', array($this, 'get_each_producttitle_in_cart'));

        add_shortcode('carteachvalue', array($this, 'get_each_product_points_value_in_cart'));

        add_shortcode('redeemeduserpoints', array($this, 'get_balance_redeem_points_to_display_in_msg'));

        add_action('wp_head', array($this, 'validation_in_my_cart'));


        add_action('woocommerce_before_cart', array($this, 'display_redeem_points_buttons_on_cart_page'));

        add_shortcode('rsminimumpoints', array($this, 'get_minimum_redeeming_points_value'));

        add_shortcode('rsmaximumpoints', array($this, 'get_maximum_redeeming_points_value'));

        add_shortcode('rsequalpoints', array($this, 'get_minimum_and_maximum_redeeming_points_value'));

        add_filter('woocommerce_cart_totals_coupon_label', array($this, 'change_coupon_label'), 1, 2);

        add_filter('woocommerce_add_to_cart_validation', array($this, 'sell_individually_functionality'), 10, 5);

        add_filter('woocommerce_cart_item_price', array($this, 'display_points_price'), 10, 3);

        add_filter('woocommerce_cart_item_subtotal', array($this, 'display_points_total'), 10, 3);

        add_action('woocommerce_cart_total', array($this, 'total_points_display_in_cart'));


        add_action('woocommerce_add_to_cart', array($this, 'set_point_price_for_products_in_session'), 1, 5);

        add_action('woocommerce_checkout_update_order_meta', array($this, 'save_point_price_info_in_order'));

        add_action('woocommerce_checkout_update_order_meta', array($this, 'remove_session'));

        add_action('woocommerce_removed_coupon', array($this, 'unset_session'));

        add_action('wp_head', array($this, 'rs_apply_coupon_automatically'), 10);

        add_action('woocommerce_checkout_update_order_meta', array($this, 'save_points_info_in_order'), 10, 2);

        add_filter('woocommerce_is_purchasable', array($this, 'is_purchasable_product'), 10, 2);

        add_filter('woocommerce_get_variation_price_html', array($this, 'check_variation_points'), 10, 2);

        add_filter('woocommerce_show_variation_price', array($this, 'change_variation_point_price_display'), 10, 3);

        add_filter('woocommerce_variable_free_price_html', array($this, 'hide_free_product_msg'), 10, 3);

        add_filter('woocommerce_calculated_total', array($this, 'alter_free_product_price'), 10, 2);

        add_filter('woocommerce_cart_subtotal', array($this, 'display_cart_suntotal'), 10, 3);

        add_filter('woocommerce_get_price_html', array($this, 'display_variation_price'), 10, 2);

        add_filter('woocommerce_checkout_coupon_message', array($this, 'hide_coupon'), 1);
    }

    public static function hide_coupon($message) {
        global $woocommerce;
        foreach ($woocommerce->cart->cart_contents as $item) {
            $product_id = $item['variation_id'] != 0 ? $item['variation_id'] : $item['product_id'];
            $type[] = self::check_display_price_type($product_id);
            $enable = self::calculate_point_price_for_products($product_id);
            if ($enable[$product_id] != '') {
                $cart_object[] = $enable[$product_id];
            }
        }

        if (in_array(2, $type)) {
            $message = 'You cannot apply any coupon for point price product';

            return $message;
        }
        if (!empty($cart_object)) {
            $message = 'You cannot apply any coupon for point price product';

            return $message;
        } else {
            return $message;
        }
    }

    public static function display_variation_price($cart_object, $compond) {
        if (is_product() || is_shop()) {
            $gettheproducts = get_product($compond->id);
            if (is_object($gettheproducts) && $gettheproducts->is_type('variable')) {
                foreach ($compond->get_available_variations() as $eachvariation) {
                    $productid = $eachvariation['variation_id'];
                    if (self::check_display_price_type($productid) == '2') {
                        if (get_option('rs_enable_disable_point_priceing') == '1') {
                            $enable = self::calculate_point_price_for_products($productid);
                            if ($enable[$productid] != '') {
                                $cart_object = '';
                            }
                        }
                    }
                }
            }
            return $cart_object;
        } else {
            return $cart_object;
        }
    }

    public static function display_cart_suntotal($cart_object, $compond, $product) {
        if (get_option('rs_enable_disable_point_priceing') == '1') {
            $array = array();
            foreach ($product->cart_contents as $key => $value) {
                $productid = $value['variation_id'] != '' ? $value['variation_id'] : $value['product_id'];

                if (self::check_display_price_type($productid) == '2') {
                    if (get_option('rs_enable_disable_point_priceing') == '1') {
                        $label = get_option('rs_label_for_point_value');
                        $replace = str_replace("/", "", $label);
                        $enable = self::calculate_point_price_for_products($productid);
                        if ($enable[$productid] != '') {
                            $cart_object = $enable[$productid] * $value['quantity'];
                            $array[] = $cart_object;
                        }
                    }
                } else {

                    return $cart_object;
                }
            }
            $amount = array_sum($array);
            return $replace . $amount;
        } else {
            return $cart_object;
        }
    }

    public static function alter_free_product_price($cart_object, $product) {

        if (get_option('rs_enable_disable_point_priceing') == '1') {

            foreach ($product->cart_contents as $key => $value) {

                $productid = $value['variation_id'] != '' ? $value['variation_id'] : $value['product_id'];
                if (self::check_display_price_type($productid) == '2') {
                    if (get_option('rs_enable_disable_point_priceing') == '1') {
                        $enable = self::calculate_point_price_for_products($productid);
                        if ($enable[$productid] != '') {
                            $cart_object = '1';
                        }
                    }
                }
            }
            return $cart_object;
        } else {
            return $cart_object;
        }
    }

    public static function is_purchasable_product($purchaseable, $product) {
        if (get_option('rs_enable_disable_point_priceing') == '1') {
            if (self::check_display_price_type($product->id) == '2') {
                $enable = self::calculate_point_price_for_products($product->id);
                if ($enable[$product->id] != '') {
                    $purchaseable = true;
                    return $purchaseable;
                } else {
                    return $purchaseable;
                }
            } else {
                return $purchaseable;
            }
        } else {
            return $purchaseable;
        }
    }

    public static function check_variation_points($product, $id) {

        if (get_option('rs_enable_disable_point_priceing') == '1') {
            if (self::check_display_price_type($id->variation_id) == '2') {
                $label = get_option('rs_label_for_point_value');
                $replace = str_replace("/", "", $label);
                $enable = self::calculate_point_price_for_products($id->variation_id);
                if ($enable[$id->variation_id] != '') {
                    $product = $enable[$id->variation_id];
                    return $replace . $product;
                } else {
                    return $product;
                }
            } else {
                return $product;
            }
        } else {
            return $product;
        }
    }

    public static function change_variation_point_price_display($product, $obj, $id) {

        if (get_option('rs_enable_disable_point_priceing') == '1') {
            if (self::check_display_price_type($id->variation_id) == '2') {
                $enable = self::calculate_point_price_for_products($id->variation_id);
                if ($enable[$id->variation_id] != '') {
                    $product = true;
                    return $product;
                } else {
                    return $product;
                }
            } else {
                return $product;
            }
        } else {
            return $product;
        }
    }

    public static function hide_free_product_msg($product, $obj) {
        if (get_option('rs_enable_disable_point_priceing') == '1') {
            $product = '';
            return $product;
        } else {
            return $product;
        }
    }

    public static function save_points_info_in_order($order_id, $orderuserid) {
        if (get_option('rs_enable_disable_reward_point_based_coupon_amount') == 'yes') {
            $points_info = self::moified_points_for_products_in_cart();
            update_post_meta($order_id, 'points_for_current_order', $points_info);
        } else {
            $points_info = self::original_points_for_product_in_cart();
            update_post_meta($order_id, 'points_for_current_order', $points_info);
        }
    }

    public static function moified_points_for_products_in_cart() {
        global $woocommerce;
        $modified_points_updated = array();
        $original_points_array = self::original_points_for_product_in_cart();
        if (!empty($original_points_array)) {
            foreach ($original_points_array as $product_id => $points) {
                $modified_points = self::coupon_points_conversion($product_id, $points);
                if ($modified_points != 0) {
                    $modified_points_updated[$product_id] = $modified_points;
                }
            }
        }
        return $modified_points_updated;
    }

    public static function coupon_included_products($product_ids, $coupon_code) {
        global $woocommerce;
        $coupon_product_ids = array();
        foreach ($woocommerce->cart->cart_contents as $cart_details) {
            $product_id = $cart_details['variation_id'] != '' ? $cart_details['variation_id'] : $cart_details['product_id'];
            if (in_array($product_id, $product_ids)) {
                $coupon_product_ids[] = $cart_details['line_subtotal'];
            }
        }
        $coupon_product_ids = array_sum($coupon_product_ids);

        return $coupon_product_ids;
    }

    public static function coupon_validator($product_id, $points) {
        global $woocommerce;
        $selected_products = '';
        $discount_coupon = $woocommerce->cart->coupon_discount_amounts;
        $newdiscount_amounts = $woocommerce->cart->coupon_discount_amounts;
        if ($newdiscount_amounts) {
            $discountss = array_sum(array_values($newdiscount_amounts));
            $c_amount = $discountss;
        }

        foreach ($woocommerce->cart->applied_coupons as $code) {
            $coupon = new WC_Coupon($code);
            $selectedproduct = $coupon->product_ids;
            $coupon_code = $coupon->code;
            $user_ID = get_current_user_id();
            $getinfousernickname = get_user_by('id', $user_ID);
            $couponcodeuserlogin = $getinfousernickname->user_login;
            if ($coupon_code == 'sumo_' . strtolower($couponcodeuserlogin)) {
                $selectedproduct = $coupon->product_ids;
                $coupon_amount = $coupon->coupon_amount;
                $selectedcategories = $coupon->product_categories;
                $discount_type = $coupon->discount_type;
                if ($discount_type == 'fixed_cart') {
                    $selectedproduct = $coupon->product_ids;
                    if (!empty($selectedproduct)) {
                        if (in_array($product_id, $selectedproduct)) {
                            $coupon_product_ids[$code][] = $product_id;
                            $count_of_products = 1;
                            $selected_products[$code][$product_id] = $count_of_products > 1 ? $discount_coupon[$code] / $count_of_products : $discount_coupon[$code];
                        }
                    } else {
                        $coupon_product_ids[$code][] = $product_id;
                        $count_of_products = 1;
                        $selected_products[$code][$product_id] = $c_amount;
                    }
                }
            } else {
                $selectedproduct = $coupon->product_ids;
                $coupon_amount = $coupon->coupon_amount;
                $selectedcategories = $coupon->product_categories;
                $discount_type = $coupon->discount_type;
                if ($discount_type == 'fixed_cart') {
                    if (!empty($selectedproduct)) {
                        if (in_array($product_id, $selectedproduct)) {
                            $coupon_product_ids[$code][] = $product_id;
                            $count_of_products = 1;
                            $selected_products[$code][$product_id] = $count_of_products > 1 ? $discount_coupon[$code] / $count_of_products : $discount_coupon[$code];
                        }
                    } else {

                        $coupon_product_ids[$code][] = $product_id;
                        $count_of_products = 1;
                        $selected_products[$code][$product_id] = $c_amount;
                    }
                } else if ($discount_type == 'percent_product') {
                    if (!empty($selectedproduct)) {
                        if (in_array($product_id, $selectedproduct)) {
                            $coupon_product_ids[$code][] = $product_id;
                            $count_of_products = 1;
                            $selected_products[$code][$product_id] = $count_of_products > 1 ? $discount_coupon[$code] / $count_of_products : $discount_coupon[$code];
                        }
                    } else {
                        $coupon_product_ids[$code][] = $product_id;
                        $count_of_products = 1;

                        $selected_products[$code][$product_id] = $c_amount;
                    }
                } else if ($discount_type == 'fixed_product') {
                    if (!empty($selectedproduct)) {
                        if (in_array($product_id, $selectedproduct)) {
                            $coupon_product_ids[$code][] = $product_id;
                            $count_of_products = 1;
                            $selected_products[$code][$product_id] = $count_of_products > 1 ? $discount_coupon[$code] / $count_of_products : $discount_coupon[$code];
                        }
                    } else {
                        $coupon_product_ids[$code][] = $product_id;
                        $count_of_products = 1;

                        $selected_products[$code][$product_id] = $c_amount;
                    }
                } else if ($discount_type = 'percent') {


                    if (!empty($selectedproduct)) {
                        if (in_array($product_id, $selectedproduct)) {
                            $coupon_product_ids[$code][] = $product_id;
                            $count_of_products = 1;

                            $selected_products[$code][$product_id] = $count_of_products > 1 ? $discount_coupon[$code] / $count_of_products : $discount_coupon[$code];
                        }
                    } else {
                        $coupon_product_ids[$code][] = $product_id;
                        $count_of_products = 1;

                        $selected_products[$code][$product_id] = $c_amount;
                    }
                }
            }
        }

        return $selected_products;
    }

    public static function get_product_price_in_cart() {
        global $woocommerce;
        $price = array();
        foreach ($woocommerce->cart->cart_contents as $key => $value) {
            $checkproduct = get_product($value['product_id']);
            $cartquantity = $value['quantity'];
            $rewardspoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['product_id'], '_rewardsystempoints');
            $checkenable = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['product_id'], '_rewardsystemcheckboxvalue');
            $checkruleoption = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['product_id'], '_rewardsystem_options');
            $checkrewardpercent = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['product_id'], '_rewardsystempercent');
            if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['product_id'], '_regular_price');

                if ($getregularprice == '') {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['product_id'], '_price');
                }
            } else {
                $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['product_id'], '_price');
                if ($getregularprice == '') {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['product_id'], '_regular_price');
                }
                $getregularprice = apply_filters('fprs_alter_price', $getregularprice, $value);
                do_action_ref_array('rs_price_rule_checker_simple', array(&$getregularprice, &$value));
            }
            if (is_object($checkproduct) && $checkproduct->is_type('booking')) {
                $getregularprice = $value['data']->price;
            }
            $user_ID = get_current_user_id();

            $global_enable = get_option('rs_global_enable_disable_sumo_reward');
            $global_reward_type = get_option('rs_global_reward_type');
            if (is_object($checkproduct) && ($checkproduct->is_type('simple') || ($checkproduct->is_type('subscription')) || ($checkproduct->is_type('booking')))) {
                if ($checkenable == 'yes') {
                    if ($checkruleoption == '1') {
                        if ($rewardspoints == '') {
                            $term = get_the_terms($value['product_id'], 'product_cat');
                            $rewardpoints = array('0');
                            if (is_array($term)) {

                                foreach ($term as $term) {
                                    $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_reward_system_category', true);
                                    $display_type = get_woocommerce_term_meta($term->term_id, 'enable_rs_rule', true);
                                    if (($enablevalue == 'yes') && ($enablevalue != '')) {
                                        if ($display_type == '1') {
                                            $checktermpoints = get_woocommerce_term_meta($term->term_id, 'rs_category_points', true);
                                            if ($checktermpoints == '') {

                                                if ($global_enable == '1') {
                                                    if ($global_reward_type == '1') {
                                                        $rewardpoints[] = get_option('rs_global_reward_points');
                                                    } else {

                                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                        $getaverage = get_option('rs_global_reward_percent') / 100;
                                                        $getaveragepoints = $getaverage * $getregularprice;
                                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                    }
                                                }
                                            } else {
                                                $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'rs_category_points', true);
                                            }
                                        } else {
                                            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                            $getaverage = get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) / 100;
                                            $getaveragepoints = $getaverage * $getregularprice;
                                            $pointswithvalue = $getaveragepoints * $pointconversion;
                                            if (get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) == '') {
                                                $global_enable = get_option('rs_global_enable_disable_sumo_reward');
                                                $global_reward_type = get_option('rs_global_reward_type');
                                                if ($global_enable == '1') {
                                                    if ($global_reward_type == '1') {
                                                        $rewardpoints[] = get_option('rs_global_reward_points');
                                                    } else {
                                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                        $getaverage = get_option('rs_global_reward_percent') / 100;
                                                        $getaveragepoints = $getaverage * $getregularprice;
                                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                    }
                                                }
                                            } else {
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    }
                                }
                            } else {

                                if ($global_enable == '1') {
                                    if ($global_reward_type == '1') {
                                        $rewardpoints[] = get_option('rs_global_reward_points');
                                    } else {
                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                        $getaverage = get_option('rs_global_reward_percent') / 100;
                                        $getaveragepoints = $getaverage * $getregularprice;
                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                } else {
                                    
                                }
                            }
                            if (!empty($rewardpoints)) {
                                $rewardspoints = max($rewardpoints);
                            }
                        }

                        $totalrewardpoints = RSMemberFunction::user_role_based_reward_points(get_current_user_id(), $rewardspoints) * $cartquantity;
                        $totalrewardpointsnew[$value['product_id']] = $totalrewardpoints;
                    } else {
                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                        $getaverage = $checkrewardpercent / 100;
                        $getaveragepoints = $getaverage * $getregularprice;
                        $pointswithvalue = $getaveragepoints * $pointconversion;
                        $points = $pointswithvalue / $pointconversionvalue;
                        if ($checkrewardpercent == '') {
                            $term = get_the_terms($value['product_id'], 'product_cat');
                            if (is_array($term)) {
                                $rewardpoints = array('0');
                                foreach ($term as $term) {

                                    $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_reward_system_category', true);
                                    $display_type = get_woocommerce_term_meta($term->term_id, 'enable_rs_rule', true);
                                    if (($enablevalue == 'yes') && ($enablevalue != '')) {
                                        if ($display_type == '1') {
                                            if (get_woocommerce_term_meta($term->term_id, 'rs_category_points', true) == '') {
                                                $global_enable = get_option('rs_global_enable_disable_sumo_reward');
                                                $global_reward_type = get_option('rs_global_reward_type');
                                                if ($global_enable == '1') {
                                                    if ($global_reward_type == '1') {
                                                        $rewardpoints[] = get_option('rs_global_reward_points');
                                                    } else {
                                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                        $getaverage = get_option('rs_global_reward_percent') / 100;
                                                        $getaveragepoints = $getaverage * $getregularprice;
                                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                    }
                                                }
                                            } else {

                                                $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'rs_category_points', true);
                                            }
                                        } else {
                                            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                            $getaverage = get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) / 100;
                                            $getaveragepoints = $getaverage * $getregularprice;
                                            $pointswithvalue = $getaveragepoints * $pointconversion;
                                            if (get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) == '') {
                                                $global_enable = get_option('rs_global_enable_disable_sumo_reward');
                                                $global_reward_type = get_option('rs_global_reward_type');
                                                if ($global_enable == '1') {
                                                    if ($global_reward_type == '1') {
                                                        $rewardpoints[] = get_option('rs_global_reward_points');
                                                    } else {
                                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                        $getaverage = get_option('rs_global_reward_percent') / 100;
                                                        $getaveragepoints = $getaverage * $getregularprice;
                                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                    }
                                                }
                                            } else {
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($global_enable == '1') {
                                    if ($global_reward_type == '1') {
                                        $rewardpoints[] = get_option('rs_global_reward_points');
                                    } else {
                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                        $getaverage = get_option('rs_global_reward_percent') / 100;
                                        $getaveragepoints = $getaverage * $getregularprice;
                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            }
                            $points = max($rewardpoints);
                        }


                        update_post_meta($value['product_id'], 'linetotal1', $value['line_subtotal']);

                        $totalrewardpoints = RSMemberFunction::user_role_based_reward_points(get_current_user_id(), $points) * $cartquantity;

                        $totalrewardpointsnew[$value['product_id']] = $totalrewardpoints;
                    }

                    if ($totalrewardpointsnew[$value['product_id']] > 0) {
                        $price[] = $value['line_subtotal'];
                    }
                } else {
                    $totalrewardpointsnew[$value['product_id']] = '0';
                }
            } else {
                $checkenablevariation = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['variation_id'], '_enable_reward_points');
                $variablerewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['variation_id'], '_reward_points');
                $variationselectrule = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['variation_id'], '_select_reward_rule');
                $variationrewardpercent = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['variation_id'], '_reward_percent');
                if ($value['variation_id'] != 0 && $value['variation_id'] != '') {
                    $variable_product1 = new WC_Product_Variation($value['variation_id']);
                    if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                        $variationregularprice = $variable_product1->regular_price != '' ? $variable_product1->regular_price : $variable_product1->price;
                    } else {
                        $variationregularprice = $variable_product1->price != '' ? $variable_product1->price : $variable_product1->regular_price;
                        do_action_ref_array('rs_price_rule_checker_variant', array(&$variationregularprice, &$value));
                    }
                    if ($checkenablevariation == '1') {
                        if ($variationselectrule == '1') {
                            $parentvariationid = new WC_Product_Variation($value['variation_id']);
                            $newparentid = $parentvariationid->parent->id;
                            if ($variablerewardpoints == '') {
                                $term = get_the_terms($newparentid, 'product_cat');
                                if (is_array($term)) {
                                    $rewardpoints = array('0');
                                    foreach ($term as $term) {

                                        $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_reward_system_category', true);
                                        $display_type = get_woocommerce_term_meta($term->term_id, 'enable_rs_rule', true);
                                        if (($enablevalue == 'yes') && ($enablevalue != '')) {
                                            if ($display_type == '1') {
                                                if (get_woocommerce_term_meta($term->term_id, 'rs_category_points', true) == '') {
                                                    $global_enable = get_option('rs_global_enable_disable_sumo_reward');
                                                    $global_reward_type = get_option('rs_global_reward_type');
                                                    if ($global_enable == '1') {
                                                        if ($global_reward_type == '1') {
                                                            $rewardpoints[] = get_option('rs_global_reward_points');
                                                        } else {
                                                            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                            $getaverage = get_option('rs_global_reward_percent') / 100;
                                                            $getaveragepoints = $getaverage * $variationregularprice;
                                                            $pointswithvalue = $getaveragepoints * $pointconversion;
                                                            $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                        }
                                                    }
                                                } else {
                                                    $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'rs_category_points', true);
                                                }
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) / 100;
                                                $getaveragepoints = $getaverage * $variationregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                if (get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) == '') {
                                                    $global_enable = get_option('rs_global_enable_disable_sumo_reward');
                                                    $global_reward_type = get_option('rs_global_reward_type');
                                                    if ($global_enable == '1') {
                                                        if ($global_reward_type == '1') {
                                                            $rewardpoints[] = get_option('rs_global_reward_points');
                                                        } else {
                                                            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                            $getaverage = get_option('rs_global_reward_percent') / 100;
                                                            $getaveragepoints = $getaverage * $variationregularprice;
                                                            $pointswithvalue = $getaveragepoints * $pointconversion;
                                                            $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                        }
                                                    }
                                                } else {
                                                    $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                }
                                            }
                                        } else {
                                            if ($global_enable == '1') {
                                                if ($global_reward_type == '1') {
                                                    $rewardpoints[] = get_option('rs_global_reward_points');
                                                } else {
                                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                    $getaverage = get_option('rs_global_reward_percent') / 100;
                                                    $getaveragepoints = $getaverage * $variationregularprice;
                                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                                    $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            $rewardpoints[] = get_option('rs_global_reward_points');
                                        } else {
                                            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                            $getaverage = get_option('rs_global_reward_percent') / 100;
                                            $getaveragepoints = $getaverage * $variationregularprice;
                                            $pointswithvalue = $getaveragepoints * $pointconversion;
                                            $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                        }
                                    }
                                }
                                $variablerewardpoints = max($rewardpoints);
                            }

                            $totalrewardpoints = RSMemberFunction::user_role_based_reward_points(get_current_user_id(), $variablerewardpoints) * $cartquantity;
                            $totalrewardpointsnew[$value['variation_id']] = $totalrewardpoints;
                        } else {
                            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                            $getaverage = $variationrewardpercent / 100;
                            $getaveragepoints = $getaverage * $variationregularprice;
                            $getpointsvalue = $getaveragepoints * $pointconversion;
                            $points = $getpointsvalue / $pointconversionvalue;
                            $parentvariationid = new WC_Product_Variation($value['variation_id']);
                            $newparentid = $parentvariationid->parent->id;
                            if ($variationrewardpercent == '') {
                                $term = get_the_terms($newparentid, 'product_cat');
                                if (is_array($term)) {
                                    $rewardpoints = array('0');
                                    foreach ($term as $term) {
                                        $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_reward_system_category', true);
                                        $display_type = get_woocommerce_term_meta($term->term_id, 'enable_rs_rule', true);
                                        if (($enablevalue == 'yes') && ($enablevalue != '')) {
                                            if ($display_type == '1') {
                                                if (get_woocommerce_term_meta($term->term_id, 'rs_category_points', true) == '') {
                                                    $global_enable = get_option('rs_global_enable_disable_sumo_reward');
                                                    $global_reward_type = get_option('rs_global_reward_type');
                                                    if ($global_enable == '1') {
                                                        if ($global_reward_type == '1') {
                                                            $rewardpoints[] = get_option('rs_global_reward_points');
                                                        } else {
                                                            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                            $getaverage = get_option('rs_global_reward_percent') / 100;
                                                            $getaveragepoints = $getaverage * $variationregularprice;
                                                            $pointswithvalue = $getaveragepoints * $pointconversion;
                                                            $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                        }
                                                    }
                                                } else {
                                                    $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'rs_category_points', true);
                                                }
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) / 100;
                                                $getaveragepoints = $getaverage * $variationregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                if (get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) == '') {
                                                    $global_enable = get_option('rs_global_enable_disable_sumo_reward');
                                                    $global_reward_type = get_option('rs_global_reward_type');
                                                    if ($global_enable == '1') {
                                                        if ($global_reward_type == '1') {
                                                            $rewardpoints[] = get_option('rs_global_reward_points');
                                                        } else {
                                                            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                            $getaverage = get_option('rs_global_reward_percent') / 100;
                                                            $getaveragepoints = $getaverage * $variationregularprice;
                                                            $pointswithvalue = $getaveragepoints * $pointconversion;
                                                            $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                        }
                                                    }
                                                } else {
                                                    $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                }
                                            }
                                        } else {
                                            if ($global_enable == '1') {
                                                if ($global_reward_type == '1') {
                                                    $rewardpoints[] = get_option('rs_global_reward_points');
                                                } else {
                                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                    $getaverage = get_option('rs_global_reward_percent') / 100;
                                                    $getaveragepoints = $getaverage * $variationregularprice;
                                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                                    $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            $rewardpoints[] = get_option('rs_global_reward_points');
                                        } else {
                                            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                            $getaverage = get_option('rs_global_reward_percent') / 100;
                                            $getaveragepoints = $getaverage * $variationregularprice;
                                            $pointswithvalue = $getaveragepoints * $pointconversion;
                                            $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                        }
                                    }
                                }
                                $points = max($rewardpoints);
                            }



                            $totalrewardpoints = RSMemberFunction::user_role_based_reward_points(get_current_user_id(), $points) * $cartquantity;

                            $totalrewardpointsnew[$value['variation_id']] = $totalrewardpoints;
                        }
                    } else {
                        $totalrewardpointsnew[$value['variation_id']] = '0';
                    }
                    if ($totalrewardpointsnew[$value['variation_id']] > 0) {

                        $price[] = $value['line_subtotal'];
                    }
                }
            }
        }
        $totalprice = array_sum($price);
        return $totalprice;
    }

    public static function coupon_points_conversion($product_id, $points) {
        global $woocommerce;
        $coupon_amounts = self::coupon_validator($product_id, $points);
        $newpoints = $points;

        $conversions = array();
        if (!empty($coupon_amounts) && is_array($coupon_amounts)) {
            foreach ($coupon_amounts as $key1 => $value) {
                
            }
            if ($newpoints > 0) {
                $c_amount1 = $value[$product_id];
                $coupon = new WC_Coupon($key1);
                $newdiscount_amounts = $woocommerce->cart->coupon_discount_amounts;
                if ($newdiscount_amounts) {
                    $discountss = array_sum(array_values($newdiscount_amounts));
                    $c_amount = $discountss;
                }
                $selectedproduct = $coupon->product_ids;
                foreach ($woocommerce->cart->applied_coupons as $key1) {
                    $coupon = new WC_Coupon($key1);
                    $selectedproduct = $coupon->product_ids;
                    $rp = self::coupon_included_products($selectedproduct, $coupon->code);
                    if (!empty($selectedproduct)) {
                        $conversion = $c_amount1 / $rp;
                    } else {
                        $conversion = $c_amount / self::get_product_price_in_cart();
                    }
                }
                $newpoints1 = $newpoints;

                $conversion = $conversion * $newpoints1;
                if ($newpoints1 > $conversion) {
                    $conversions[] = $newpoints1 - $conversion;
                }
                $newpoints = $newpoints1 - $conversion;
            }


            return end($conversions);
        }

        return $newpoints;
    }

    public static function coupon($key, $product_id) {
        global $woocommerce;
        $discount_coupon = $woocommerce->cart->applied_coupons;

        $coupon = new WC_Coupon($key);
        if (count($discount_coupon) > 1) {
            $couponcode = get_post_meta($product_id, 'couponcode');
            if ($couponcode != $coupon->code) {
                $linetotal = get_post_meta($product_id, 'linetotal');
                $vd = $linetotal[0];
            }
        } else {
            $vd = self::get_product_price_in_cart();
            $vd1 = self::get_product_price_in_cart() - $coupon->coupon_amount;
            update_post_meta($product_id, 'linetotal', $vd1);
            update_post_meta($product_id, 'couponcode', $coupon->code);
        }

        return $vd;
    }

    public static function get_reward_points_to_display_msg_in_cart_and_checkout() {
        global $woocommerce;
        global $messageglobal;
        global $totalrewardpoints;
        global $checkproduct;
        global $value;
        global $totalrewardpointsnew;
        ?>
        <style>
            .cart_total_minimum:before{
                font-family:WooCommerce;content:"\e028";display:inline-block;position:absolute;top:1em;left:1.5em;color:#1e85be
            }
            .cart_total_minimum{
                font-size: 10pt;font-family:WooCommerce;padding:1em 2em 1em 3.5em!important;margin:0 0 2em!important;position:relative;background-color:#f7f6f7;color:#515151;border-top:3px solid #a46497;list-style:none!important;width:auto;word-wrap:break-word;border-top-color:#1e85be
            }
        </style>
        <?php
        $minimum_cart_total = get_option('rs_minimum_cart_total_for_earning');
        $cart_total = $woocommerce->cart->total;
        $error_message = get_option('rs_min_cart_total_for_earning_error_message');
        $replace = '[carttotal]';
        $error_message = str_replace($replace, $minimum_cart_total, $error_message);

        if (get_option('rs_enable_disable_reward_point_based_coupon_amount') == 'yes') {
            $points_info = self::moified_points_for_products_in_cart();
            if ($minimum_cart_total != '' && $minimum_cart_total != 0) {
                if ($cart_total < $minimum_cart_total) {
                    $totalrewardpointsnew = '';
                    ?>
                    <div class="cart_total_minimum" >  <?php echo $error_message; ?>  </div>
                    <?php
                } else {
                    $totalrewardpointsnew = $points_info;
                }
            } else {
                $totalrewardpointsnew = $points_info;
            }
        } else {
            $points_info = self::original_points_for_product_in_cart();
            if ($minimum_cart_total != '' && $minimum_cart_total != 0) {
                if (!empty($points_info)) {
                    if ($cart_total < $minimum_cart_total) {
                        $totalrewardpointsnew = '';
                        ?>
                        <div class="cart_total_minimum" >  <?php echo $error_message; ?>  </div>
                        <?php
                    } else {
                        $totalrewardpointsnew = $points_info;
                    }
                }
            } else {
                $totalrewardpointsnew = $points_info;
            }
        }
        if (is_user_logged_in()) {
            if (!empty($points_info)) {
                foreach ($points_info as $product_id => $points) {
                    if ($points != 0) {
                        $checkproduct = get_product($product_id);
                        $value = $product_id;
                        $totalrewardpoints = $points;
                        if (is_object($checkproduct) && !$checkproduct->is_type('booking')) {
                            if (is_cart()) {
                                $messageglobal[$product_id] = do_shortcode(get_option('rs_message_product_in_cart')) . "<br>";
                            } elseif (is_checkout()) {
                                $messageglobal[$product_id] = do_shortcode(get_option('rs_message_product_in_checkout')) . "<br>";
                            }
                        }
                    }
                }
            }
        }
        $totalrewardpoints = do_shortcode('[totalrewards]');
        WC()->session->set('rewardpoints', $totalrewardpoints);
    }

    public static function test_coupon() {

        if (is_cart()) {
            if (isset($_GET['remove_coupon'])) {
                wp_redirect(wc_get_page_permalink('cart'));
            }
        }
    }

    public static function remove_session() {
        WC()->session->set('auto_redeemcoupon', 'yes');
    }

    public static function unset_session() {
        global $woocommerce;
        WC()->session->set('auto_redeemcoupon', 'no');
    }

    public static function rs_apply_coupon_automatically() {


        global $woocommerce;
        $user_ID = get_current_user_id();
        if (is_user_logged_in()) {
            $getinfousernickname = get_user_by('id', $user_ID);
            $couponcodeuserlogin = $getinfousernickname->user_login;

            self::auto_redeeming();
        }
    }

    public static function auto_redeeming() {
        $type = array();
        global $woocommerce;
        $user_ID = get_current_user_id();
        $getinfousernickname = get_user_by('id', $user_ID);
        $couponcodeuserlogin = $getinfousernickname->user_login;
        $autoredeemenable = get_option('rs_enable_disable_auto_redeem_points');
        $checkfirstimeredeem = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($user_ID, 'rsfirsttime_redeemed');
        if ($woocommerce->cart->get_cart_contents_count() == 0) {
            WC()->session->set('auto_redeemcoupon', 'yes');
        }
        if (is_cart()) {
            if ($woocommerce->cart->get_cart_contents_count() == 0) {
                foreach ($woocommerce->cart->applied_coupons as $code) {
                    $coupon = new WC_Coupon($code);
                    $couponcode = $coupon->code;
                    $woocommerce->cart->remove_coupon($couponcode);
                }
            }
            foreach ($woocommerce->cart->cart_contents as $item) {
                $product_id = $item['variation_id'] != 0 ? $item['variation_id'] : $item['product_id'];
                $type[] = self::check_display_price_type($product_id);
                $enable = self::calculate_point_price_for_products($product_id);
                if ($enable[$product_id] != '') {
                    $cart_object[] = $enable[$product_id];
                }
            }
            if (empty($cart_object)) {
                if (!in_array(2, $type)) {
                    if ($woocommerce->cart->get_cart_contents_count() != 0) {
                        if ($autoredeemenable == 'yes') {
                            if (WC()->session->get('auto_redeemcoupon') != 'no') {
                                global $woocommerce;
                                $getuserid = get_current_user_id();
                                $user_current_points = RSPointExpiry::get_sum_of_total_earned_points($getuserid);
                                if ($user_current_points > 0) {
                                    if ($user_current_points >= get_option("rs_first_time_minimum_user_points")) {
                                        $redeem_conversion = get_option('rs_redeem_point');                                      
                                        if (get_option('woocommerce_prices_include_tax') == 'yes') {
                                        if (get_option('woocommerce_tax_display_cart') == 'incl') {
                                            $get_cart_total_for_redeem = $woocommerce->cart->subtotal;
                                        } else {
                                            $get_cart_total_for_redeem = $woocommerce->cart->subtotal_ex_tax;
                                        }
                                    } else {
                                        if (get_option('woocommerce_tax_display_cart') == 'incl') {
                                            $get_cart_total_for_redeem = $woocommerce->cart->subtotal;
                                        } else {
                                            $get_cart_total_for_redeem = $woocommerce->cart->subtotal_ex_tax;
                                        }
                                    }
                                        $point_control = wc_format_decimal(get_option('rs_redeem_point'));
                                        $point_control_price = wc_format_decimal(get_option('rs_redeem_point_value')); //i.e., 100 Points is equal to $1
                                        $cartpoints_string_to_replace = "[cartredeempoints]";
                                        $currency_symbol_string_to_find = "[currencysymbol]";
                                        $cuurency_value_string_to_find = "[pointsvalue]";

                                        $getmaxruleoption = get_option('rs_max_redeem_discount');
                                        $getfixedmaxoption = get_option('rs_fixed_max_redeem_discount');
                                        $getpercentmaxoption = get_option('rs_percent_max_redeem_discount');
                                        $errpercentagemsg = get_option('rs_errmsg_for_max_discount_type');
                                        $point_control = wc_format_decimal(get_option('rs_redeem_point'));
                                        $point_control_price = wc_format_decimal(get_option('rs_redeem_point_value'));

                                        $minimum_cart_total_redeem = get_option('rs_minimum_cart_total_points');


                                        if ($user_current_points >= get_option("rs_minimum_user_points_to_redeem")) {
                                            if ($get_cart_total_for_redeem >= $minimum_cart_total_redeem) {
                                                if (!is_array(get_option('rs_select_products_to_enable_redeeming'))) {
                                                    $allowproducts = explode(',', get_option('rs_select_products_to_enable_redeeming'));
                                                } else {
                                                    $allowproducts = get_option('rs_select_products_to_enable_redeeming');
                                                }

                                                if (!is_array(get_option('rs_exclude_products_to_enable_redeeming'))) {
                                                    $excludeproducts = explode(',', get_option('rs_exclude_products_to_enable_redeeming'));
                                                } else {
                                                    $excludeproducts = get_option('rs_exclude_products_to_enable_redeeming');
                                                }
                                                $allowcategory = get_option('rs_select_category_to_enable_redeeming');
                                                $excludecategory = get_option('rs_exclude_category_to_enable_redeeming');


                                                $coupon = array(
                                                    'post_title' => 'auto_redeem_' . strtolower($couponcodeuserlogin),
                                                    'post_content' => '',
                                                    'post_status' => 'publish',
                                                    'post_author' => get_current_user_id(),
                                                    'post_type' => 'shop_coupon',
                                                );
                                                $oldcouponid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($user_ID, 'auto_redeemcoupon_ids', true);
                                                wp_delete_post($oldcouponid, true);
                                                $new_coupon_id = wp_insert_post($coupon);
                                                update_user_meta($user_ID, 'auto_redeemcoupon_ids', $new_coupon_id);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'carttotal', $woocommerce->cart->cart_contents_total);
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'cartcontenttotal', $woocommerce->cart->cart_contents_count);

                                                //Redeeming only for Selected Products option start
                                                $enableproductredeeming = get_option('rs_enable_redeem_for_selected_products');
                                                if ($enableproductredeeming == 'yes') {
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'product_ids', implode(',', array_filter(array_map('intval', $allowproducts))));
                                                }
                                                $excludeproductredeeming = get_option('rs_exclude_products_for_redeeming');
                                                if ($excludeproductredeeming == 'yes') {
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'exclude_product_ids', implode(',', array_filter(array_map('intval', $excludeproducts))));
                                                    $product = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($new_coupon_id, 'exclude_product_ids');

                                                    foreach ($woocommerce->cart->cart_contents as $key => $value) {
                                                        $product_idsss = $value['product_id'];
                                                        if ($product_idsss == $product) {
                                                            WC()->session->set('auto_redeemcoupon', 'no');
                                                        }
                                                    }
                                                }
                                                $enablecategoryredeeming = get_option('rs_enable_redeem_for_selected_category');
                                                if ($enablecategoryredeeming == 'yes') {
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'product_categories', implode(',', array_filter(array_map('intval', $allowcategory))));
                                                }
                                                $excludecategoryredeeming = get_option('rs_exclude_category_for_redeeming');
                                                if ($excludecategoryredeeming == 'yes') {
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'exclude_product_categories', implode(',', array_filter(array_map('intval', $excludecategory))));
                                                }

                                                //Redeeming only for Selected Products option End
                                                if (get_option('rs_apply_redeem_basedon_cart_or_product_total') == '1') {

                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'carttotal', $woocommerce->cart->cart_contents_total);

                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'cartcontenttotal', $woocommerce->cart->cart_contents_count);

                                                    $limitation_percentage_for_redeeming_for_button = get_option('rs_percentage_cart_total_auto_redeem');
                                                    $reddem_value_in_amount_percent = $limitation_percentage_for_redeeming_for_button / 100;
                                                    $reddem_points_for_total = $reddem_value_in_amount_percent * $get_cart_total_for_redeem;
                                                    $coupon_value_in_amount = $reddem_points_for_total;
                                                    if ($getmaxruleoption == '1') {
                                                        if ($getfixedmaxoption != '') {
                                                            if ($reddem_points_for_total > $getfixedmaxoption) {
                                                                $coupon_value_in_amount = $getfixedmaxoption;
                                                                $errpercentagemsg1 = str_replace('[percentage] %', $getfixedmaxoption, $errpercentagemsg);
                                                                wc_add_notice(__($errpercentagemsg1), 'error');
                                                            } else {
                                                                $coupon_value_in_amount = $reddem_points_for_total;
                                                            }
                                                        }
                                                    } else {
                                                        if ($getmaxruleoption == '2') {
                                                            if ($getpercentmaxoption != '') {
                                                                $percentageproduct = $getpercentmaxoption / 100;
                                                                $getpricepercent = $percentageproduct * $get_cart_total_for_redeem;

                                                                if ($getpricepercent > $reddem_points_for_total) {
                                                                    $coupon_value_in_amount = $reddem_points_for_total;
                                                                } else {
                                                                    $coupon_value_in_amount = $getpricepercent;
                                                                    $errpercentagemsg1 = str_replace('[percentage] ', $getpercentmaxoption, $errpercentagemsg);
                                                                    wc_add_notice(__($errpercentagemsg1), 'error');
                                                                }
                                                            }
                                                        }
                                                    }

                                                    $getuserid = get_current_user_id();
                                                    $user_current_points = RSPointExpiry::get_sum_of_total_earned_points($getuserid);
                                                    $point_control = wc_format_decimal(get_option('rs_redeem_point'));
                                                    $point_control_price = wc_format_decimal(get_option('rs_redeem_point_value')); //i.e., 100 Points is equal to $1
                                                    $revised_amount = $coupon_value_in_amount * $point_control;
                                                    $coupon_value_in_points = $revised_amount / $point_control_price;
                                                    if ($coupon_value_in_points > $user_current_points) {
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'coupon_amount', $user_current_points);
                                                    } else {
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'coupon_amount', $coupon_value_in_amount);
                                                    }
                                                } else {
                                                    $getsumofselectedproduct = RSFunctionToApplyCoupon::get_sum_of_selected_products('auto', '', $user_current_points);

                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'carttotal', $getsumofselectedproduct);

                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'cartcontenttotal', $getsumofselectedproduct);
                                                    $limitation_percentage_for_redeeming_for_button = get_option('rs_percentage_cart_total_auto_redeem');
                                                    $reddem_value_in_amount_percent = $limitation_percentage_for_redeeming_for_button / 100;
                                                    $reddem_points_for_total = $reddem_value_in_amount_percent * $getsumofselectedproduct;
                                                    $coupon_value_in_amount = $reddem_points_for_total;
                                                    if ($reddem_points_for_total > $getsumofselectedproduct) {
                                                        $reddem_points_for_total = $getsumofselectedproduct;
                                                        $coupon_value_in_amount = $reddem_points_for_total;
                                                    }
                                                    if ($getmaxruleoption == '1') {
                                                        if ($getfixedmaxoption != '') {
                                                            if ($reddem_points_for_total > $getfixedmaxoption) {
                                                                $coupon_value_in_amount = $getfixedmaxoption;
                                                                $errpercentagemsg1 = str_replace('[percentage] %', $getfixedmaxoption, $errpercentagemsg);

                                                                wc_add_notice(__($errpercentagemsg1), 'error');
                                                            } else {
                                                                $coupon_value_in_amount = $reddem_points_for_total;
                                                            }
                                                        }
                                                    } else {
                                                        if ($getmaxruleoption == '2') {
                                                            if ($getpercentmaxoption != '') {
                                                                $percentageproduct = $getpercentmaxoption / 100;
                                                                $getpricepercent = $percentageproduct * $coupon_value_in_amount;

                                                                if ($getpricepercent > $reddem_points_for_total) {
                                                                    $coupon_value_in_amount = $reddem_points_for_total;
                                                                } else {
                                                                    $coupon_value_in_amount = $getpricepercent;
                                                                    $errpercentagemsg1 = str_replace('[percentage] ', $getpercentmaxoption, $errpercentagemsg);

                                                                    wc_add_notice(__($errpercentagemsg1), 'error');
                                                                }
                                                            }
                                                        }
                                                    }

                                                    $getuserid = get_current_user_id();
                                                    $user_current_points = RSPointExpiry::get_sum_of_total_earned_points($getuserid);
                                                    $point_control = wc_format_decimal(get_option('rs_redeem_point'));
                                                    $point_control_price = wc_format_decimal(get_option('rs_redeem_point_value')); //i.e., 100 Points is equal to $1
                                                    $revised_amount = $coupon_value_in_amount * $point_control;
                                                    $coupon_value_in_points = $revised_amount / $point_control_price;
                                                    if ($coupon_value_in_points > $user_current_points) {
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'coupon_amount', $user_current_points);
                                                    } else {
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($new_coupon_id, 'coupon_amount', $coupon_value_in_amount);
                                                    }
                                                }

                                                if ($woocommerce->cart->has_discount('auto_redeem_' . strtolower($couponcodeuserlogin)))
                                                    return;

                                                if (get_post_meta($new_coupon_id, 'coupon_amount', true) != 0) {
                                                    if (get_option('rs_minimum_redeeming_points') != '' && get_option('rs_maximum_redeeming_points') == '') {

                                                        if ($coupon_value_in_points > get_option('rs_minimum_redeeming_points')) {
                                                            $woocommerce->cart->add_discount('auto_redeem_' . strtolower($couponcodeuserlogin));
                                                        }
                                                    }

                                                    if (get_option('rs_maximum_redeeming_points') != '' && get_option('rs_minimum_redeeming_points') == '') {
                                                        if ($coupon_value_in_points < get_option('rs_maximum_redeeming_points')) {
                                                            $woocommerce->cart->add_discount('auto_redeem_' . strtolower($couponcodeuserlogin));
                                                        }
                                                    }

                                                    if (get_option('rs_minimum_redeeming_points') == get_option('rs_maximum_redeeming_points')) {
                                                        if (($coupon_value_in_points == get_option('rs_minimum_redeeming_points')) && ($coupon_value_in_points == get_option('rs_maximum_redeeming_points'))) {
                                                            $woocommerce->cart->add_discount('auto_redeem_' . strtolower($couponcodeuserlogin));
                                                        }
                                                    }

                                                    if (get_option('rs_minimum_redeeming_points') == '' && get_option('rs_maximum_redeeming_points') == '') {
                                                        $woocommerce->cart->add_discount('auto_redeem_' . strtolower($couponcodeuserlogin));
                                                    }

                                                    if (get_option('rs_minimum_redeeming_points') != '' && get_option('rs_maximum_redeeming_points') != '') {
                                                        if (($coupon_value_in_points >= get_option('rs_minimum_redeeming_points')) && ($coupon_value_in_points <= get_option('rs_maximum_redeeming_points'))) {
                                                            $woocommerce->cart->add_discount('auto_redeem_' . strtolower($couponcodeuserlogin));
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public static function set_point_price_for_products_in_session($cart_item_key, $product_id = null, $quantity = null, $variation_id = null, $variation = null) {
        $product_id = $variation_id != null ? $variation_id : $product_id;
        $point_price_for_product = self::calculate_point_price_for_products($product_id);
        WC()->session->set($cart_item_key . 'point_price_for_product', $point_price_for_product);
    }

    public static function save_point_price_info_in_order($orderid) {
        global $woocommerce;
        $current_cart_contents = $woocommerce->cart->cart_contents;
        foreach ($current_cart_contents as $key => $value) {
            if (WC()->session->get($key . 'point_price_for_product')) {
                $point_price_info[] = WC()->session->get($key . 'point_price_for_product');
                update_post_meta($orderid, 'point_price_for_product_in_order', $point_price_info);
            }
        }
    }

    public static function calculate_point_price_for_products($product_id) {
        if (get_option('rs_enable_disable_point_priceing') == '1') {
            $global_enable = get_option('rs_local_enable_disable_point_price_for_product');
            $points = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem__points');
            $enable = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price');
            $checkenablevariation = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_enable_reward_points_price');
            $variablerewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, 'price_points');
            $point_price_type = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_enable_reward_points_price_type');
            $point_based_on_conversion = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_price_points_based_on_conversion');
            $simple_product_type = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_point_price_type');
            $simple_product_price = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem__points_based_on_conversion');
            $price_display_type = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price_type');
            $global_reward_type = get_option('rs_global_point_price_type');
            $global_reward_display_type = get_option('rs_global_point_priceing_type');
            $checkproduct = get_product($product_id);

            if (is_object($checkproduct) && ($checkproduct->is_type('simple') || ($checkproduct->is_type('subscription')))) {
                if ($enable == 'yes') {
                    if ($price_display_type == '2') {

                        $data[$product_id] = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem__points');
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                    } else {
                        if ($simple_product_type == 1) {

                            if ($points == '') {
                                $term = get_the_terms($product_id, 'product_cat');
                                if (is_array($term)) {
                                    foreach ($term as $term) {
                                        $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_point_price_category', true);
                                        $display_type_price = get_woocommerce_term_meta($term->term_id, 'point_priceing_category_type', true);
                                        if (($enablevalue == 'yes') && ($enablevalue != '')) {

                                            $display_type = get_woocommerce_term_meta($term->term_id, 'point_price_category_type', true);
                                            if ($display_type == '1') {
                                                $checktermpoints = get_woocommerce_term_meta($term->term_id, 'rs_category_points_price', true);
                                                if ($checktermpoints == '') {
                                                    if ($global_enable == '1') {

                                                        if ($global_reward_type == '1') {
                                                            if (get_option('rs_local_price_points_for_product') != '') {
                                                                $data[$product_id] = get_option('rs_local_price_points_for_product');
                                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                                                            } else {
                                                                $data[$product_id] = '';
                                                            }
                                                        } else {
                                                            $product = new WC_Product($product_id);
                                                            $product_price = $product->sale_price ? $product->sale_price : $product->regular_price;
                                                            $newvalue = $product_price / wc_format_decimal(get_option('rs_redeem_point_value'));
                                                            $updatedvalue = $newvalue * wc_format_decimal(get_option('rs_redeem_point'));
                                                            $data[$product_id] = $updatedvalue;
                                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                                                        }
                                                    } else {
                                                        $data[$product_id] = '';
                                                    }
                                                } else {
                                                    $data[$product_id] = get_woocommerce_term_meta($term->term_id, 'rs_category_points_price', true);
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                                                }
                                            } else {
                                                $product = new WC_Product($product_id);
                                                $product_price = $product->sale_price ? $product->sale_price : $product->regular_price;
                                                $newvalue = $product_price / wc_format_decimal(get_option('rs_redeem_point_value'));
                                                $updatedvalue = $newvalue * wc_format_decimal(get_option('rs_redeem_point'));
                                                $data[$product_id] = $updatedvalue;
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                                            }
                                        } else {
                                            if ($global_enable == '1') {

                                                if ($global_reward_type == '1') {
                                                    if (get_option('rs_local_price_points_for_product') != '') {
                                                        $data[$product_id] = get_option('rs_local_price_points_for_product');
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                                                    } else {
                                                        $data[$product_id] = '';
                                                    }
                                                } else {
                                                    $product = new WC_Product($product_id);
                                                    $product_price = $product->sale_price ? $product->sale_price : $product->regular_price;
                                                    $newvalue = $product_price / wc_format_decimal(get_option('rs_redeem_point_value'));
                                                    $updatedvalue = $newvalue * wc_format_decimal(get_option('rs_redeem_point'));
                                                    $data[$product_id] = $updatedvalue;
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                                                }
                                            } else {
                                                $data[$product_id] = '';
                                            }
                                        }
                                    }
                                } else {
                                    $global_enable = get_option('rs_local_enable_disable_point_price_for_product');
                                    $global_reward_type = get_option('rs_global_point_price_type');
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if (get_option('rs_local_price_points_for_product') != '') {
                                                $data[$product_id] = get_option('rs_local_price_points_for_product');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                                            } else {
                                                $data[$product_id] = '';
                                            }
                                        } else {
                                            $product = new WC_Product($product_id);

                                            $product_price = $product->sale_price ? $product->sale_price : $product->regular_price;
                                            $newvalue = $product_price / wc_format_decimal(get_option('rs_redeem_point_value'));
                                            $updatedvalue = $newvalue * wc_format_decimal(get_option('rs_redeem_point'));
                                            $data[$product_id] = $updatedvalue;
                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                                        }
                                    } else {

                                        $data[$product_id] = '';
                                    }
                                }
                            } else {

                                $data[$product_id] = $points;
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                            }
                        } else {
                            $product = new WC_Product($product_id);

                            $product_price = $product->sale_price ? $product->sale_price : $product->regular_price;
                            $newvalue = $product_price / wc_format_decimal(get_option('rs_redeem_point_value'));
                            $updatedvalue = $newvalue * wc_format_decimal(get_option('rs_redeem_point'));

                            $data[$product_id] = $updatedvalue;
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                        }
                    }
                } else {
                    $data[$product_id] = '';
                }
            } else {

                if ($checkenablevariation == '1') {
                    $price_display_type = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_enable_reward_points_pricing_type', true);
                    if ($price_display_type == '2') {
                        $data[$product_id] = $variablerewardpoints;
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                    } else {
                        if ($point_price_type == 1) {
                            $parentvariationid = new WC_Product_Variation($product_id);
                            $newparentid = $parentvariationid->parent->id;
                            if ($variablerewardpoints == '') {
                                $term = get_the_terms($newparentid, 'product_cat');
                                if (is_array($term)) {

                                    foreach ($term as $term) {
                                        $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_point_price_category', true);

                                        if (($enablevalue == 'yes') && ($enablevalue != '')) {

                                            $display_type = get_woocommerce_term_meta($term->term_id, 'point_price_category_type', true);
                                            if ($display_type == '1') {
                                                $checktermpoints = get_woocommerce_term_meta($term->term_id, 'rs_category_points_price', true);
                                                if ($checktermpoints == '') {
                                                    if ($global_enable == '1') {
                                                        if ($global_reward_type == '1') {
                                                            if (get_option('rs_local_price_points_for_product') != '') {
                                                                $data[$product_id] = get_option('rs_local_price_points_for_product');
                                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                                                            } else {
                                                                $data[$product_id] = '';
                                                            }
                                                        } else {
                                                            $product = new WC_Product($product_id);
                                                            $product_price = $product->sale_price ? $product->sale_price : $product->regular_price;
                                                            $newvalue = $product_price / wc_format_decimal(get_option('rs_redeem_point_value'));
                                                            $updatedvalue = $newvalue * wc_format_decimal(get_option('rs_redeem_point'));
                                                            $data[$product_id] = $updatedvalue;
                                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                                                        }
                                                    } else {
                                                        $data[$product_id] = '';
                                                    }
                                                } else {
                                                    $data[$product_id] = get_woocommerce_term_meta($term->term_id, 'rs_category_points_price', true);
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                                                }
                                            } else {
                                                $product = new WC_Product($product_id);
                                                $product_price = $product->sale_price ? $product->sale_price : $product->regular_price;
                                                $newvalue = $product_price / wc_format_decimal(get_option('rs_redeem_point_value'));
                                                $updatedvalue = $newvalue * wc_format_decimal(get_option('rs_redeem_point'));
                                                $data[$product_id] = $updatedvalue;
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                                            }
                                        } else {
                                            if ($global_enable == '1') {


                                                if ($global_reward_type == '1') {
                                                    if (get_option('rs_local_price_points_for_product') != '') {
                                                        $data[$product_id] = get_option('rs_local_price_points_for_product');
                                                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                                                    } else {
                                                        $data[$product_id] = '';
                                                    }
                                                } else {
                                                    $product = new WC_Product($product_id);
                                                    $product_price = $product->sale_price ? $product->sale_price : $product->regular_price;
                                                    $newvalue = $product_price / wc_format_decimal(get_option('rs_redeem_point_value'));
                                                    $updatedvalue = $newvalue * wc_format_decimal(get_option('rs_redeem_point'));
                                                    $data[$product_id] = $updatedvalue;
                                                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                                                }
                                            } else {
                                                $data[$product_id] = '';
                                            }
                                        }
                                    }
                                } else {
                                    $global_enable = get_option('rs_local_enable_disable_point_price_for_product');
                                    $global_reward_type = get_option('rs_global_point_price_type');
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if (get_option('rs_local_price_points_for_product') != '') {
                                                $data[$product_id] = get_option('rs_local_price_points_for_product');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                                            } else {
                                                $data[$product_id] = '';
                                            }
                                        } else {
                                            $product = new WC_Product($product_id);

                                            $product_price = $product->sale_price ? $product->sale_price : $product->regular_price;
                                            $newvalue = $product_price / wc_format_decimal(get_option('rs_redeem_point_value'));
                                            $updatedvalue = $newvalue * wc_format_decimal(get_option('rs_redeem_point'));
                                            $data[$product_id] = $updatedvalue;
                                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                                        }
                                    } else {

                                        $data[$product_id] = '';
                                    }
                                }
                            } else {

                                $data[$product_id] = $variablerewardpoints;
                                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                            }
                        } else {

                            $product = new WC_Product($product_id);
                            $product_price = $product->sale_price ? $product->sale_price : $product->regular_price;

                            $newvalue = $product_price / wc_format_decimal(get_option('rs_redeem_point_value'));
                            $updatedvalue = $newvalue * wc_format_decimal(get_option('rs_redeem_point'));
                            $data[$product_id] = $updatedvalue;

                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($product_id, 'pointsvalueenable', '1');
                        }
                    }
                } else {
                    $data[$product_id] = '';
                }
            }
        } else {
            $data[$product_id] = '';
        }
        return $data;
    }

    public static function check_display_price_type($product_id) {
        if (get_option('rs_enable_disable_point_priceing') == '1') {
            $termid = '';
            //Product Level
            $points = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem__points');
            $enable = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price');
            $checkenablevariation = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_enable_reward_points_price');
            $variablerewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, 'price_points');
            $point_price_type = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_enable_reward_points_price_type');
            $point_based_on_conversion = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_price_points_based_on_conversion');
            $simple_product_type = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_point_price_type');
            $simple_product_price = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem__points_based_on_conversion');
            $typeofprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price_type');


            $productlevel = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price') != '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_enable_reward_points_price');
            $productlevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_point_price_type') != '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_point_price_type') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_enable_reward_points_price_type');
            $productlevelrewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem__points') != '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem__points') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, 'price_points');
            $productdispalytype = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price_type') != '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price_type') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_enable_reward_points_pricing_type');



            //Global Level
            $global_enable = get_option('rs_local_enable_disable_point_price_for_product');
            $global_reward_type = get_option('rs_global_point_price_type');

            $global_rewardpoints = get_option('rs_local_price_points_for_product');
            $global_display_typt = get_option('rs_global_point_priceing_type');

            if (($productlevel == 'yes') || ($productlevel == '1')) {

                if ($productlevelrewardpoints != '') {

                    if ($productdispalytype == '1') {

                        return '1';
                    } else {

                        return '2';
                    }
                } else {
                    
                }
            } else {
                return '0';
            }
        }
    }

    public static function total_points_display_in_cart($price) {

        global $woocommerce;
        $total1 = 0;
        $totalpoints1 = 0;
        $totalpoints2 = 0;
        $totalvariable = 0;
        $varpoints = array();
        $array = array();
        $points = array();
        $total = array();
        $linetotal = array();
        $labelpoint = get_option('rs_label_for_point_value');

        if (get_option('rs_enable_disable_point_priceing') == '1') {

            $shippingcost = $woocommerce->shipping->shipping_total;

            $shipping_tax = $woocommerce->shipping->shipping_taxes;

            $shipping_tax_total = array_sum($shipping_tax);
            $coupon_amount = $woocommerce->cart->get_cart_discount_total();

            $taxtotal = $woocommerce->cart->get_taxes();

            $taxtotal1 = array_sum($taxtotal);

            $shippingcost_total = $taxtotal1 + $shippingcost;

            foreach ($woocommerce->cart->cart_contents as $key) {

                $total22[] = $key['line_total'];

                $product_id = $key['variation_id'] != 0 ? $key['variation_id'] : $key['product_id'];
                $enablevariable = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_enable_reward_points_price');
                $typeofprice1[] = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_enable_reward_points_pricing_type', true);

                $typeofprice[] = self::check_display_price_type($product_id);
                $points_array = self::calculate_point_price_for_products($product_id);

                if ($points_array != NULL) {
                    $points = (float) implode(",", $points_array);

                    $opto1[] = $enablevariable;


                    $enable = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price');

                    $opto[] = $enable;
                }


                $enable = RSFunctionForCart::calculate_point_price_for_products($product_id);
                if ($enable[$product_id] != '') {
                    $cart_object = $enable[$product_id] * $key['quantity'];
                    $array[] = $cart_object;
                } else {

                    $linetotal[] = $key['line_subtotal'];
                }
            }


            $current_conversion1 = wc_format_decimal(get_option('rs_redeem_point'));
            $point_amount1 = wc_format_decimal(get_option('rs_redeem_point_value'));
            $redeemedamount1 = $shippingcost_total * $current_conversion1;
            $redeemedpoints2 = $redeemedamount1 / $point_amount1;



            $totalvariable = array_sum($linetotal);
            $newvalue = $totalvariable / wc_format_decimal(get_option('rs_redeem_point_value'));
            $updatedvalue = $newvalue * wc_format_decimal(get_option('rs_redeem_point'));

            $pointsproducttotal = array_sum($array) + $updatedvalue - $coupon_amount;

            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
            $pointsproducttotal = round($pointsproducttotal, $roundofftype);





            $update = $labelpoint . $pointsproducttotal;

            if (in_array("yes", $opto) || in_array("1", $opto1)) {

                if (in_array('2', $typeofprice)) {
                    $replace = str_replace("/", "", $update);
                    return $replace;
                } else {
                    if (array_sum($array) > 0) {
                        if ($pointsproducttotal > 0) {
                            $update = $update;
                        } else {
                            $update = 0;
                        }
                        return $price . $update;
                    } else {
                        return $price;
                    }
                }
            } else {
                return $price;
            }
        } else {
            return $price;
        }
    }

    public static function display_points_price($product_price, $item, $item_key) {

        $points_array = array();
        $product_id = $item['variation_id'] != 0 ? $item['variation_id'] : $item['product_id'];
        if (get_option('rs_enable_disable_point_priceing') == '1') {
            $enablevariable = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_enable_reward_points_price');
            $enable = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price');
            if ($enablevariable == '1' || $enable == 'yes') {
                $quantity = $item['quantity'];
                $labelpoint = get_option('rs_label_for_point_value');
                $points_array = self::calculate_point_price_for_products($product_id);

                $points = implode(",", $points_array);

                if ($points != '') {
                    $typeofprice = self::check_display_price_type($product_id);

                    if ($typeofprice == '2') {
                        $replace = str_replace("/", "", $labelpoint);
                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                        $points = round($points, $roundofftype);
                        $product_price = $replace . $points;

                        return $product_price;
                    } else {
                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                        $points = round($points, $roundofftype);
                        $product_price = wc_price($item['data']->price) . $labelpoint . $points;

                        return $product_price;
                    }
                } else {
                    return $product_price;
                }
            } else {
                return $product_price;
            }
        } else {
            return $product_price;
        }
    }

    public static function display_points_total($product_price, $item, $item_key) {
        $product_id = $item['variation_id'] != 0 ? $item['variation_id'] : $item['product_id'];
        if (get_option('rs_enable_disable_point_priceing') == '1') {
            $enablevariable = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_enable_reward_points_price');
            $enable = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price');
            if ($enablevariable == '1' || $enable == 'yes') {
                $quantity = $item['quantity'];
                $labelpoint = get_option('rs_label_for_point_value');
                $id = $item['product_id'];

                $points_array = self::calculate_point_price_for_products($product_id);
                $points = implode(",", $points_array);
                if ($points != '') {
                    $typeofprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price_type') != '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price_type') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_enable_reward_points_pricing_type');
                    $typeofprice = self::check_display_price_type($product_id);
                    if ($typeofprice == '2') {
                        $replace = str_replace("/", "", $labelpoint);
                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                        $points = round($points, $roundofftype);
                        $product_price = $replace . $points * $quantity;
                        return $product_price;
                    } else {
                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                        $points = round($points, $roundofftype);
                        $product_price = wc_price($item['line_subtotal']) . $labelpoint . $points * $quantity;
                        return $product_price;
                    }
                } else {
                    return $product_price;
                }
            } else {
                return $product_price;
            }
        } else {
            return $product_price;
        }
    }

    public static function sell_individually_functionality($valid, $product_id, $quantity, $variation_id = NULL, $variations = NULL) {
        if (get_option('rs_enable_disable_point_priceing') == '1') {
            if (function_exists('WC')) {
                global $woocommerce;
                $cart_content = WC()->cart->get_cart();

                if (!empty($cart_content)) {
                    $cart_contents_count = WC()->cart->cart_contents_count;
                    foreach ($cart_content as $key => $content) {
                        if ($cart_contents_count > 0 && 1 <= $cart_contents_count) {


                            if (isset($content['data']->variation_id)) {
                                $productid = $content['data']->variation_id;
                            } else {
                                $productid = $content['data']->id;
                            }


                            if (self::check_is_point_pricing_enable($productid)) {

                                if (isset($variation_id)) {
                                    $get_product_productid = $variation_id;
                                } else {
                                    $get_product_productid = $product_id;
                                }

                                if (self::check_is_point_pricing_enable($get_product_productid)) {
                                    if (isset($variation_id)) {

                                        if ($variation_id == $content['data']->variation_id) {

                                            $valid = false;
                                            wc_add_notice(get_option('rs_errmsg_for_point_price_product_with_same'), 'error');
                                            return $valid;
                                        }
                                    } else {
                                        if ($product_id == $content['data']->id) {

                                            $valid = false;
                                            wc_add_notice(get_option('rs_errmsg_for_point_price_product_with_same'), 'error');
                                            return $valid;
                                        }
                                    }
                                } else {


                                    $valid = false;
                                    wc_add_notice(get_option('rs_errmsg_for_normal_product_with_point_price'), 'error');
                                    return $valid;
                                }
                            } else {
                                if (isset($variation_id)) {
                                    $get_product_productid = $variation_id;
                                } else {
                                    $get_product_productid = $product_id;
                                }
                                if (self::check_is_point_pricing_enable($get_product_productid)) {

                                    $valid = false;
                                    wc_add_notice(get_option('rs_errmsg_for_point_price_product_with_normal'), 'error');
                                    return $valid;
                                } else {

                                    $valid = true;
                                    return $valid;
                                }
                            }
                        } else {
                            if (isset($variation_id)) {
                                $get_product_productid = $variation_id;
                            } else {
                                $get_product_productid = $product_id;
                            }




                            if (self::check_is_point_pricing_enable($get_product_productid)) {


                                if (self::check_cart_contain_subscription()) {
                                    $valid = false;
                                    wc_add_notice("you cannot add more than one product", 'error');
                                    return $valid;
                                } else {
                                    WC()->cart->empty_cart();
                                    $valid = true;
                                    wc_add_notice("cannot add normal product with point pricing product", 'error');
                                    return $valid;
                                }
                            } else {

                                $valid = true;
                                return $valid;
                            }
                        }
                    }
                }
                if (!is_user_logged_in()) {

                    if (self::check_is_point_pricing_enable($product_id)) {
                        $valid = false;
                        wc_add_notice("Please signup to purchase this product", 'error');
                        return $valid;
                    }
                }
            }
        }
        return $valid;
    }

    public static function check_is_point_pricing_enable($product_id) {
        if (get_option('rs_enable_disable_point_priceing') == '1') {

            $termid = '';
            //Product Level
            $points = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem__points');
            $enable = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price');
            $checkenablevariation = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_enable_reward_points_price');
            $variablerewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, 'price_points');
            $point_price_type = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_enable_reward_points_price_type');
            $point_based_on_conversion = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_price_points_based_on_conversion');
            $simple_product_type = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_point_price_type');
            $simple_product_price = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem__points_based_on_conversion');
            $typeofprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price_type');

            RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price_type');
            $productlevel = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price') != '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_enable_reward_points_price');
            $productlevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_point_price_type') != '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_point_price_type') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_enable_reward_points_price_type');
            $productlevelrewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem__points') != '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem__points') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, 'price_points');
            $productdispalytype = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price_type') != '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_rewardsystem_enable_point_price_type') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($product_id, '_enable_reward_points_pricing_type');

            if (($productlevel == 'yes') || ($productlevel == '1')) {
                if ($productdispalytype == '2') {
                    if ($productlevelrewardpoints != '') {

                        return true;
                    } else {

                        return false;
                    }
                }
            } else {
                return false;
            }
        }
    }

    /* Function for hiding the couon field */

    public static function show_hide_coupon_code() {
        global $woocommerce;
        $type = array();
        if (get_option('rs_show_hide_coupon_field') == 2) {
            ?>
            <style type="text/css">
                .coupon{
                    display: none;
                }
            </style>
            <?php
        }

        foreach ($woocommerce->cart->cart_contents as $item) {
            $product_id = $item['variation_id'] != 0 ? $item['variation_id'] : $item['product_id'];
            $type[] = self::check_display_price_type($product_id);
            $enable = self::calculate_point_price_for_products($product_id);
            if ($enable[$product_id] != '') {
                $cart_object[] = $enable[$product_id];
            }
        }
        if (!empty($cart_object)) {
            ?>
            <style type="text/css">
                .coupon{
                    display: none;

                }

                .showcoupon {


                    display: none;

                }

            </style>
            <?php
        }

        if (in_array(2, $type)) {
            ?>
            <style type="text/css">
                .coupon{
                    display: none;

                }
                .showcoupon {


                    display: none;

                }

            </style>
            <?php
        }
    }

    /*
     * Function to show when enable and hide when disable
     */

    public static function rs_redeeming_selected_products_categories() {
        global $woocommerce;
        if (isset($_GET['tab'])) {
            if ($_GET['tab'] == 'rewardsystem_cart') {

                echo RSJQueryFunction::rs_common_ajax_function_to_select_products('rs_ajax_chosen_select_products_redeem');
                if ((float) $woocommerce->version <= (float) ('2.2.0')) {
                    echo RSJQueryFunction::rs_common_chosen_function('#rs_select_category_to_enable_redeeming');
                    echo RSJQueryFunction::rs_common_chosen_function('#rs_exclude_category_to_enable_redeeming');
                } else {
                    echo RSJQueryFunction::rs_common_select_function('#rs_select_category_to_enable_redeeming');
                    echo RSJQueryFunction::rs_common_select_function('#rs_exclude_category_to_enable_redeeming');
                }
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {

                        var enable_auto_redeem_checkbox = jQuery('#rs_enable_disable_auto_redeem_points').is(':checked') ? 'yes' : 'no';
                        if (enable_auto_redeem_checkbox === 'yes') {
                            jQuery('#rs_percentage_cart_total_auto_redeem').parent().parent().show();
                        } else {
                            jQuery('#rs_percentage_cart_total_auto_redeem').parent().parent().hide();
                        }

                        jQuery('#rs_enable_disable_auto_redeem_points').click(function () {
                            var enable_auto_redeem_checkbox = jQuery('#rs_enable_disable_auto_redeem_points').is(':checked') ? 'yes' : 'no';
                            if (enable_auto_redeem_checkbox == 'yes') {
                                jQuery('#rs_percentage_cart_total_auto_redeem').parent().parent().show();
                            } else {
                                jQuery('#rs_percentage_cart_total_auto_redeem').parent().parent().hide();
                            }
                        });
                        var currentvalue = jQuery('#rs_show_hide_redeem_field').val();
                        if (currentvalue === '1') {
                            jQuery('#rs_enable_redeem_for_selected_products').parent().parent().parent().parent().show();
                            jQuery('#rs_exclude_products_for_redeeming').parent().parent().parent().parent().show();
                            jQuery('#rs_enable_redeem_for_selected_category').parent().parent().parent().parent().show();
                            jQuery('#rs_exclude_category_for_redeeming').parent().parent().parent().parent().show();
                            var enable_selected_product_checkbox = jQuery('#rs_enable_redeem_for_selected_products').is(':checked') ? 'yes' : 'no';
                            var enable_exclude_product_checkbox = jQuery('#rs_exclude_products_for_redeeming').is(':checked') ? 'yes' : 'no';
                            var enable_selected_category_checkbox = jQuery('#rs_enable_redeem_for_selected_category').is(':checked') ? 'yes' : 'no';
                            var enable_exclude_category_checkbox = jQuery('#rs_exclude_category_for_redeeming').is(':checked') ? 'yes' : 'no';
                            if (enable_selected_product_checkbox === 'yes') {
                                jQuery('#rs_select_products_to_enable_redeeming').parent().parent().show();
                            } else {
                                jQuery('#rs_select_products_to_enable_redeeming').parent().parent().hide();
                            }
                            if (enable_exclude_product_checkbox === 'yes') {
                                jQuery('#rs_exclude_products_to_enable_redeeming').parent().parent().show();
                            } else {
                                jQuery('#rs_exclude_products_to_enable_redeeming').parent().parent().hide();
                            }
                            if (enable_selected_category_checkbox === 'yes') {
                                jQuery('#rs_select_category_to_enable_redeeming').parent().parent().show();
                            } else {
                                jQuery('#rs_select_category_to_enable_redeeming').parent().parent().hide();
                            }
                            if (enable_exclude_category_checkbox === 'yes') {
                                jQuery('#rs_exclude_category_to_enable_redeeming').parent().parent().show();
                            } else {
                                jQuery('#rs_exclude_category_to_enable_redeeming').parent().parent().hide();
                            }


                            //When enabling the product and category
                            jQuery('#rs_enable_redeem_for_selected_products').click(function () {
                                var enable_redeem_for_selected_product = jQuery('#rs_enable_redeem_for_selected_products').is(':checked') ? 'yes' : 'no';
                                if (enable_redeem_for_selected_product == 'yes') {
                                    jQuery('#rs_select_products_to_enable_redeeming').parent().parent().show();
                                } else {
                                    jQuery('#rs_select_products_to_enable_redeeming').parent().parent().hide();
                                }
                            });
                            jQuery('#rs_exclude_products_for_redeeming').click(function () {
                                var enable_exclude_product_checkbox = jQuery('#rs_exclude_products_for_redeeming').is(':checked') ? 'yes' : 'no';
                                if (enable_exclude_product_checkbox == 'yes') {
                                    jQuery('#rs_exclude_products_to_enable_redeeming').parent().parent().show();
                                } else {
                                    jQuery('#rs_exclude_products_to_enable_redeeming').parent().parent().hide();
                                }
                            });
                            jQuery('#rs_enable_redeem_for_selected_category').click(function () {
                                var enable_selected_category_checkbox = jQuery('#rs_enable_redeem_for_selected_category').is(':checked') ? 'yes' : 'no';
                                if (enable_selected_category_checkbox == 'yes') {
                                    jQuery('#rs_select_category_to_enable_redeeming').parent().parent().show();
                                } else {
                                    jQuery('#rs_select_category_to_enable_redeeming').parent().parent().hide();
                                }
                            });
                            jQuery('#rs_exclude_category_for_redeeming').click(function () {
                                var enable_exclude_category_checkbox = jQuery('#rs_exclude_category_for_redeeming').is(':checked') ? 'yes' : 'no';
                                if (enable_exclude_category_checkbox == 'yes') {
                                    jQuery('#rs_exclude_category_to_enable_redeeming').parent().parent().show();
                                } else {
                                    jQuery('#rs_exclude_category_to_enable_redeeming').parent().parent().hide();
                                }
                            });
                        } else {
                            jQuery('#rs_enable_redeem_for_selected_products').parent().parent().parent().parent().hide();
                            jQuery('#rs_exclude_products_for_redeeming').parent().parent().parent().parent().hide();
                            jQuery('#rs_enable_redeem_for_selected_category').parent().parent().parent().parent().hide();
                            jQuery('#rs_exclude_category_for_redeeming').parent().parent().parent().parent().hide();
                            jQuery('#rs_select_products_to_enable_redeeming').parent().parent().hide();
                            jQuery('#rs_exclude_products_to_enable_redeeming').parent().parent().hide();
                            jQuery('#rs_select_category_to_enable_redeeming').parent().parent().hide();
                            jQuery('#rs_exclude_category_to_enable_redeeming').parent().parent().hide();
                        }

                        jQuery('#rs_show_hide_redeem_field').change(function () {
                            var currentvalue = jQuery(this).val();
                            if (currentvalue === '1') {
                                jQuery('#rs_enable_redeem_for_selected_products').parent().parent().parent().parent().show();
                                jQuery('#rs_exclude_products_for_redeeming').parent().parent().parent().parent().show();
                                jQuery('#rs_enable_redeem_for_selected_category').parent().parent().parent().parent().show();
                                jQuery('#rs_exclude_category_for_redeeming').parent().parent().parent().parent().show();
                                var enable_selected_product_checkbox = jQuery('#rs_enable_redeem_for_selected_products').is(':checked') ? 'yes' : 'no';
                                var enable_exclude_product_checkbox = jQuery('#rs_exclude_products_for_redeeming').is(':checked') ? 'yes' : 'no';
                                var enable_selected_category_checkbox = jQuery('#rs_enable_redeem_for_selected_category').is(':checked') ? 'yes' : 'no';
                                var enable_exclude_category_checkbox = jQuery('#rs_exclude_category_for_redeeming').is(':checked') ? 'yes' : 'no';
                                if (enable_selected_product_checkbox === 'yes') {
                                    jQuery('#rs_select_products_to_enable_redeeming').parent().parent().show();
                                } else {
                                    jQuery('#rs_select_products_to_enable_redeeming').parent().parent().hide();
                                }
                                if (enable_exclude_product_checkbox === 'yes') {
                                    jQuery('#rs_exclude_products_to_enable_redeeming').parent().parent().show();
                                } else {
                                    jQuery('#rs_exclude_products_to_enable_redeeming').parent().parent().hide();
                                }
                                if (enable_selected_category_checkbox === 'yes') {
                                    jQuery('#rs_select_category_to_enable_redeeming').parent().parent().show();
                                } else {
                                    jQuery('#rs_select_category_to_enable_redeeming').parent().parent().hide();
                                }
                                if (enable_exclude_category_checkbox === 'yes') {
                                    jQuery('#rs_exclude_category_to_enable_redeeming').parent().parent().show();
                                } else {
                                    jQuery('#rs_exclude_category_to_enable_redeeming').parent().parent().hide();
                                }


                                //When enabling the product and category
                                jQuery('#rs_enable_redeem_for_selected_products').click(function () {
                                    var enable_redeem_for_selected_product = jQuery('#rs_enable_redeem_for_selected_products').is(':checked') ? 'yes' : 'no';
                                    if (enable_redeem_for_selected_product == 'yes') {
                                        jQuery('#rs_select_products_to_enable_redeeming').parent().parent().show();
                                    } else {
                                        jQuery('#rs_select_products_to_enable_redeeming').parent().parent().hide();
                                    }
                                });
                                jQuery('#rs_exclude_products_for_redeeming').click(function () {
                                    var enable_exclude_product_checkbox = jQuery('#rs_exclude_products_for_redeeming').is(':checked') ? 'yes' : 'no';
                                    if (enable_exclude_product_checkbox == 'yes') {
                                        jQuery('#rs_exclude_products_to_enable_redeeming').parent().parent().show();
                                    } else {
                                        jQuery('#rs_exclude_products_to_enable_redeeming').parent().parent().hide();
                                    }
                                });
                                jQuery('#rs_enable_redeem_for_selected_category').click(function () {
                                    var enable_selected_category_checkbox = jQuery('#rs_enable_redeem_for_selected_category').is(':checked') ? 'yes' : 'no';
                                    if (enable_selected_category_checkbox == 'yes') {
                                        jQuery('#rs_select_category_to_enable_redeeming').parent().parent().show();
                                    } else {
                                        jQuery('#rs_select_category_to_enable_redeeming').parent().parent().hide();
                                    }
                                });
                                jQuery('#rs_exclude_category_for_redeeming').click(function () {
                                    var enable_exclude_category_checkbox = jQuery('#rs_exclude_category_for_redeeming').is(':checked') ? 'yes' : 'no';
                                    if (enable_exclude_category_checkbox == 'yes') {
                                        jQuery('#rs_exclude_category_to_enable_redeeming').parent().parent().show();
                                    } else {
                                        jQuery('#rs_exclude_category_to_enable_redeeming').parent().parent().hide();
                                    }
                                });
                            } else {
                                jQuery('#rs_enable_redeem_for_selected_products').parent().parent().parent().parent().hide();
                                jQuery('#rs_exclude_products_for_redeeming').parent().parent().parent().parent().hide();
                                jQuery('#rs_enable_redeem_for_selected_category').parent().parent().parent().parent().hide();
                                jQuery('#rs_exclude_category_for_redeeming').parent().parent().parent().parent().hide();
                                jQuery('#rs_select_products_to_enable_redeeming').parent().parent().hide();
                                jQuery('#rs_exclude_products_to_enable_redeeming').parent().parent().hide();
                                jQuery('#rs_select_category_to_enable_redeeming').parent().parent().hide();
                                jQuery('#rs_exclude_category_to_enable_redeeming').parent().parent().hide();
                            }
                        });
                        //Show or Hide Redeeming Field Caption
                        if (jQuery('#rs_show_hide_redeem_caption').val() == '1') {
                            jQuery('#rs_redeem_field_caption').parent().parent().show();
                        } else {
                            jQuery('#rs_redeem_field_caption').parent().parent().hide();
                        }

                        jQuery('#rs_show_hide_redeem_caption').change(function () {
                            if (jQuery('#rs_show_hide_redeem_caption').val() == '1') {
                                jQuery('#rs_redeem_field_caption').parent().parent().show();
                            } else {
                                jQuery('#rs_redeem_field_caption').parent().parent().hide();
                            }
                        });
                        //Show or Hide Redeeming Field Placeholder
                        if (jQuery('#rs_show_hide_redeem_placeholder').val() == '1') {
                            jQuery('#rs_redeem_field_placeholder').parent().parent().show();
                        } else {
                            jQuery('#rs_redeem_field_placeholder').parent().parent().hide();
                        }

                        jQuery('#rs_show_hide_redeem_placeholder').change(function () {
                            if (jQuery('#rs_show_hide_redeem_placeholder').val() == '1') {
                                jQuery('#rs_redeem_field_placeholder').parent().parent().show();
                            } else {
                                jQuery('#rs_redeem_field_placeholder').parent().parent().hide();
                            }
                        });
                        //Show or Hide Current User Points is Empty Error Message
                        if (jQuery('#rs_show_hide_points_empty_error_message').val() == '1') {
                            jQuery('#rs_current_points_empty_error_message').parent().parent().show();
                        } else {
                            jQuery('#rs_current_points_empty_error_message').parent().parent().hide();
                        }

                        jQuery('#rs_show_hide_points_empty_error_message').change(function () {
                            if (jQuery('#rs_show_hide_points_empty_error_message').val() == '1') {
                                jQuery('#rs_current_points_empty_error_message').parent().parent().show();
                            } else {
                                jQuery('#rs_current_points_empty_error_message').parent().parent().hide();
                            }
                        });
                        //Show or Hide Minimum Points for first time Redeeming Error Message
                        if (jQuery('#rs_show_hide_first_redeem_error_message').val() == '1') {
                            jQuery('#rs_min_points_first_redeem_error_message').parent().parent().show();
                        } else {
                            jQuery('#rs_min_points_first_redeem_error_message').parent().parent().hide();
                        }

                        jQuery('#rs_show_hide_first_redeem_error_message').change(function () {
                            if (jQuery('#rs_show_hide_first_redeem_error_message').val() == '1') {
                                jQuery('#rs_min_points_first_redeem_error_message').parent().parent().show();
                            } else {
                                jQuery('#rs_min_points_first_redeem_error_message').parent().parent().hide();
                            }
                        });
                        //Show or Hide Minimum Points After first time Redeeming Error Message
                        if (jQuery('#rs_show_hide_after_first_redeem_error_message').val() == '1') {
                            jQuery('#rs_min_points_after_first_error').parent().parent().show();
                        } else {
                            jQuery('#rs_min_points_after_first_error').parent().parent().hide();
                        }

                        jQuery('#rs_show_hide_after_first_redeem_error_message').change(function () {
                            if (jQuery('#rs_show_hide_after_first_redeem_error_message').val() == '1') {
                                jQuery('#rs_min_points_after_first_error').parent().parent().show();
                            } else {
                                jQuery('#rs_min_points_after_first_error').parent().parent().hide();
                            }
                        });
                        //Show or Hide Minimum Cart Total for Redeeming Error Message
                        if (jQuery('#rs_show_hide_minimum_cart_total_error_message').val() == '1') {
                            jQuery('#rs_min_cart_total_redeem_error').parent().parent().show();
                        } else {
                            jQuery('#rs_min_cart_total_redeem_error').parent().parent().hide();
                        }

                        jQuery('#rs_show_hide_minimum_cart_total_error_message').change(function () {
                            if (jQuery('#rs_show_hide_minimum_cart_total_error_message').val() == '1') {
                                jQuery('#rs_min_cart_total_redeem_error').parent().parent().show();
                            } else {
                                jQuery('#rs_min_cart_total_redeem_error').parent().parent().hide();
                            }
                        });
                        //Show or Hide For Redeem Button Type
                        if (jQuery('#rs_redeem_field_type_option').val() == '1') {
                            jQuery('#rs_percentage_cart_total_redeem').parent().parent().hide();
                        } else {
                            jQuery('#rs_percentage_cart_total_redeem').parent().parent().show();
                        }

                        jQuery('#rs_redeem_field_type_option').change(function () {
                            if (jQuery('#rs_redeem_field_type_option').val() == '1') {
                                jQuery('#rs_percentage_cart_total_redeem').parent().parent().hide();
                            } else {
                                jQuery('#rs_percentage_cart_total_redeem').parent().parent().show();
                            }
                        });
                    });</script>
                <?php
            }
        }
    }

    public static function display_total_earned_points($param) {
        global $woocommerce;


        if (get_option('rs_show_hide_total_points_cart_field') == '1') {
            $totalrewardpoints = do_shortcode('[totalrewards]');
            //var_dump($totalrewardpoints);

            if ($totalrewardpoints != 0) {

                $total = $woocommerce->cart->discount_cart;


                if ($total != 0) {
                    if (get_option('rs_enable_redeem_for_order') == 'no') {
                        ?>
                        <div class="points_total" >
                            <tr class="points-totalvalue">
                                <th><?php echo get_option('rs_total_earned_point_caption'); ?></th>
                                <td><?php echo $totalrewardpoints; ?></td>
                            </tr>
                        </div>
                        <?php
                    }
                } else {
                    ?>
                    <div class="points_total" >
                        <tr class="points-totalvalue">
                            <th><?php echo get_option('rs_total_earned_point_caption'); ?></th>
                            <td><?php echo $totalrewardpoints; ?></td>
                        </tr>
                    </div>
                    <?php
                }
            }
        }
    }

    /*
     * Function to select products to exclude
     */

    public static function rs_select_product_to_exclude() {

        global $woocommerce;
        if ((float) $woocommerce->version > (float) ('2.2.0')) {
            ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_exclude_products_to_enable_redeeming"><?php _e('Select Products', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    <input type="hidden" class="wc-product-search" style="width: 350%;" id="rs_exclude_products_to_enable_redeeming"  name="rs_exclude_products_to_enable_redeeming" data-placeholder="<?php _e('Search for a product&hellip;', 'rewardsystem'); ?>" data-action="woocommerce_json_search_products_and_variations" data-multiple="true" data-selected="<?php
                    $json_ids = array();
                    if (get_option('rs_exclude_products_to_enable_redeeming') != "") {
                        $list_of_produts = get_option('rs_exclude_products_to_enable_redeeming');
                        $product_ids = array_filter(array_map('absint', (array) explode(',', get_option('rs_exclude_products_to_enable_redeeming'))));

                        foreach ($product_ids as $product_id) {
                            $product = wc_get_product($product_id);
                            if (is_object($product)) {
                                $json_ids[$product_id] = wp_kses_post($product->get_formatted_name());
                            }
                        } echo esc_attr(json_encode($json_ids));
                    }
                    ?>" value="<?php echo implode(',', array_keys($json_ids)); ?>" />
                </td>
            </tr>
        <?php } else { ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_exclude_products_to_enable_redeeming"><?php _e('Select Products', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    <select multiple name="rs_exclude_products_to_enable_redeeming[]" style='width:350px;' id='rs_exclude_products_to_enable_redeeming' class="rs_exclude_products_to_enable_redeeming rs_ajax_chosen_select_products_redeem">
                        <?php
                        $selected_products_exclude = array_filter((array) get_option('rs_exclude_products_to_enable_redeeming'));
                        if ($selected_products_exclude != "") {
                            if (!empty($selected_products_exclude)) {
                                $list_of_produts = (array) get_option('rs_exclude_products_to_enable_redeeming');
                                foreach ($list_of_produts as $rs_free_id) {
                                    echo '<option value="' . $rs_free_id . '" ';
                                    selected(1, 1);
                                    echo '>' . ' #' . $rs_free_id . ' &ndash; ' . get_the_title($rs_free_id);
                                }
                            }
                        } else {
                            ?>
                            <option value=""></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php
        }
    }

    /*
     * Function to select products to include
     */

    public static function rs_select_product_to_include() {

        global $woocommerce;
        if ((float) $woocommerce->version > (float) ('2.2.0')) {
            ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_select_products_to_enable_redeeming"><?php _e('Select Products', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    <input type="hidden" class="wc-product-search" style="width: 350%;" id="rs_select_products_to_enable_redeeming"  name="rs_select_products_to_enable_redeeming" data-placeholder="<?php _e('Search for a product&hellip;', 'rewardsystem'); ?>" data-action="woocommerce_json_search_products_and_variations" data-multiple="true" data-selected="<?php
                    $json_ids = array();
                    if (get_option('rs_select_products_to_enable_redeeming') != "") {
                        $list_of_produts = get_option('rs_select_products_to_enable_redeeming');
                        $product_ids = array_filter(array_map('absint', (array) explode(',', get_option('rs_select_products_to_enable_redeeming'))));

                        foreach ($product_ids as $product_id) {
                            $product = wc_get_product($product_id);
                            if (is_object($product)) {
                                $json_ids[$product_id] = wp_kses_post($product->get_formatted_name());
                            }
                        } echo esc_attr(json_encode($json_ids));
                    }
                    ?>" value="<?php echo implode(',', array_keys($json_ids)); ?>" />
                </td>
            </tr>
        <?php } else { ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_select_products_to_enable_redeeming"><?php _e('Select Products', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    <select multiple name="rs_select_products_to_enable_redeeming[]" style='width:350px;' id='rs_select_products_to_enable_redeeming' class="rs_select_products_to_enable_redeeming rs_ajax_chosen_select_products_redeem">
                        <?php
                        $selected_products_include = array_filter((array) get_option('rs_select_products_to_enable_redeeming'));
                        if ($selected_products_include != "") {
                            if (!empty($selected_products_include)) {
                                $list_of_produts = (array) get_option('rs_select_products_to_enable_redeeming');
                                foreach ($list_of_produts as $rs_free_id) {
                                    echo '<option value="' . $rs_free_id . '" ';
                                    selected(1, 1);
                                    echo '>' . ' #' . $rs_free_id . ' &ndash; ' . get_the_title($rs_free_id);
                                }
                            }
                        } else {
                            ?>
                            <option value=""></option>
                            <?php
                        }
                        ?>
                    </select>
                </td>
            </tr>
            <?php
        }
    }

    /*
     * Function to save the selected products to exclude
     */

    public static function save_product_to_exclude() {
        update_option('rs_exclude_products_to_enable_redeeming', $_POST['rs_exclude_products_to_enable_redeeming']);
    }

    /*
     * Function to save select products to include
     */

    public static function save_product_to_include() {
        update_option('rs_select_products_to_enable_redeeming', $_POST['rs_select_products_to_enable_redeeming']);
    }

    public static function rs_validation_of_input_field_in_cart() {
        ?>

        <script type="text/javascript">
            jQuery(function () {
                jQuery('body').on('blur', '#rs_percentage_cart_total_redeem[type=text],\n\
                                           #rs_first_time_minimum_user_points[type=text],\n\
                                           #rs_minimum_user_points_to_redeem[type=text],\n\
                                           #rs_minimum_redeeming_points[type=text],\n\\n\
                                           #rs_maximum_redeeming_points[type=text],\n\
                                           #rs_minimum_cart_total_points[type=text]', function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });
                    return this;
                });
                jQuery('body').on('keyup change', '#rs_percentage_cart_total_redeem[type=text],\n\
                                           #rs_first_time_minimum_user_points[type=text],\n\
                                           #rs_minimum_user_points_to_redeem[type=text],\n\
                                           #rs_minimum_redeeming_points[type=text],\n\\n\
                                           #rs_maximum_redeeming_points[type=text],\n\
                                           #rs_minimum_cart_total_points[type=text]', function () {
                    var value = jQuery(this).val();
                    var regex = new RegExp("[^\+0-9\%.\\" + woocommerce_admin.mon_decimal_point + "]+", "gi");
                    var newvalue = value.replace(regex, '');
                    if (value !== newvalue) {
                        jQuery(this).val(newvalue);
                        if (jQuery(this).parent().find('.wc_error_tip').size() == 0) {
                            var offset = jQuery(this).position();
                            jQuery(this).after('<div class="wc_error_tip">' + woocommerce_admin.i18n_mon_decimal_error + " Negative Values are not allowed" + '</div>');
                            jQuery('.wc_error_tip')
                                    .css('left', offset.left + jQuery(this).width() - (jQuery(this).width() / 2) - (jQuery('.wc_error_tip').width() / 2))
                                    .css('top', offset.top + jQuery(this).height())
                                    .fadeIn('100');
                        }
                    }



                    return this;
                });
                jQuery("body").click(function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });
                });
            });</script>
        <?php
    }

    public static function reward_system_add_message_after_cart_table() {
        $type = array();
        $userid = get_current_user_id();
        $banning_type = FPRewardSystem::check_banning_type($userid);
        if ($banning_type != 'redeemingonly' && $banning_type != 'both') {
            ?>
            <style type="text/css">
            <?php echo get_option('rs_cart_page_custom_css'); ?>
            </style>
            <?php
            global $woocommerce;
            global $coupon_code;

            if (is_user_logged_in()) {
                $user_ID = get_current_user_id();
                $getinfousernickname = get_user_by('id', $user_ID);
                $couponcodeuserlogin = $getinfousernickname->user_login;
                $minimum_cart_total_redeem = get_option('rs_minimum_cart_total_points');
                $cart_subtotal_for_redeem = $woocommerce->cart->subtotal;



                $cart_subtotal_redeem_amount = $cart_subtotal_for_redeem;
                $getinfousernickname = get_user_by('id', $user_ID);
                $couponcodeuserlogin = $getinfousernickname->user_login;
                $get_old_points = RSPointExpiry::get_sum_of_total_earned_points($user_ID);
                if ($get_old_points > 0) {

                    $coupon_code = 'sumo_' . strtolower($couponcodeuserlogin); // Code
                    $coupon = new WC_Coupon($coupon_code);
                    $checkfirstimeredeem = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($user_ID, 'rsfirsttime_redeemed');
                    if ($checkfirstimeredeem != '1') {

                        if ($get_old_points >= get_option("rs_first_time_minimum_user_points")) {
                            if ($cart_subtotal_redeem_amount >= $minimum_cart_total_redeem) {

                                $user_ID = get_current_user_id();
                                $getinfousernickname = get_user_by('id', $user_ID);
                                $couponcodeuserlogin = $getinfousernickname->user_login;
                                $auto_redeem_name = 'auto_redeem_' . strtolower($couponcodeuserlogin);
                                $array = $woocommerce->cart->get_applied_coupons();
                                if (!in_array($auto_redeem_name, $array)) {
                                    foreach ($woocommerce->cart->cart_contents as $item) {
                                        $product_id = $item['variation_id'] != 0 ? $item['variation_id'] : $item['product_id'];
                                        $type[] = self::check_display_price_type($product_id);
                                        $enable = self::calculate_point_price_for_products($product_id);
                                        if ($enable[$product_id] != '') {
                                            $cart_object[] = $enable[$product_id];
                                        }
                                    }
                                    if (empty($cart_object)) {
                                        if (!in_array('2', $type)) {
                                            if (get_option('rs_redeem_field_type_option') == '1') {
                                                if (is_cart()) {
                                                    ?>
                                                    <div class="fp_apply_reward">
                                                        <?php if (get_option("rs_show_hide_redeem_caption") == '1') { ?>
                                                            <label for="rs_apply_coupon_code_field"><?php echo get_option('rs_redeem_field_caption'); ?></label>
                                                        <?php } ?>
                                                        <?php
                                                        if (get_option('rs_show_hide_redeem_placeholder') == '1') {
                                                            $placeholder = get_option('rs_redeem_field_placeholder');
                                                        }
                                                        ?>
                                                        <input id="rs_apply_coupon_code_field" class="input-text" type="text" placeholder="<?php echo $placeholder; ?>" value="" name="rs_apply_coupon_code_field">
                                                        <input class="button <?php echo get_option('rs_extra_class_name_apply_reward_points'); ?>" type="submit" id='mainsubmi' value="<?php echo get_option('rs_redeem_field_submit_button_caption'); ?>" name="rs_apply_coupon_code">
                                                    </div>
                                                    <div class='rs_warning_message' style='display:inline-block;color:red'></div>
                                                    <?php
                                                }
                                            }
                                            if (get_option('rs_redeem_field_type_option_checkout') == '1') {
                                                if (is_checkout()) {
                                                    ?>
                                                    <div class="fp_apply_reward">
                                                        <?php if (get_option("rs_show_hide_redeem_caption") == '1') { ?>
                                                            <label for="rs_apply_coupon_code_field"><?php echo get_option('rs_redeem_field_caption'); ?></label>
                                                        <?php } ?>
                                                        <?php
                                                        if (get_option('rs_show_hide_redeem_placeholder') == '1') {
                                                            $placeholder = get_option('rs_redeem_field_placeholder');
                                                        }
                                                        ?>
                                                        <input id="rs_apply_coupon_code_field" class="input-text" type="text" placeholder="<?php echo $placeholder; ?>" value="" name="rs_apply_coupon_code_field">
                                                        <input class="button <?php echo get_option('rs_extra_class_name_apply_reward_points'); ?>" type="submit" id='mainsubmi' value="<?php echo get_option('rs_redeem_field_submit_button_caption'); ?>" name="rs_apply_coupon_code">
                                                    </div>
                                                    <div class='rs_warning_message' style='display:inline-block;color:red'></div>
                                                    <?php
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if (get_option('rs_show_hide_minimum_cart_total_error_message') == '1') {
                                    $userid = get_current_user_id();
                                    $banning_type = FPRewardSystem::check_banning_type($userid);
                                    if ($banning_type != 'redeemingonly' && $banning_type != 'both') {
                                        $min_cart_total_redeeming = get_option('rs_min_cart_total_redeem_error');
                                        $min_cart_amount_to_find = "[carttotal]";
                                        $min_cart_total_currency_to_find = "[currencysymbol]";
                                        $min_cart_amount_to_replace = get_option('rs_minimum_cart_total_points');
                                        $min_cart_total_currency_to_replace = self::get_woocommerce_formatted_price($min_cart_amount_to_replace);
                                        $min_cart_total_msg1 = str_replace($min_cart_amount_to_find, $min_cart_total_currency_to_replace, $min_cart_total_redeeming);
                                        $min_cart_total_replaced = str_replace($min_cart_total_currency_to_find, "", $min_cart_total_msg1);
                                        ?>
                                        <div class="woocommerce-info"><?php echo $min_cart_total_replaced; ?></div>
                                        <?php
                                    }
                                }
                            }
                        } else {
                            if (get_option('rs_show_hide_first_redeem_error_message') == '1') {
                                $userid = get_current_user_id();
                                $banning_type = FPRewardSystem::check_banning_type($userid);
                                if ($banning_type != 'redeemingonly' && $banning_type != 'both') {
                                    $rs_first_redeem_message = get_option('rs_min_points_first_redeem_error_message');
                                    $rs_first_redeem_to_find = "[firstredeempoints]";
                                    $rs_first_redeem_to_replace = get_option('rs_first_time_minimum_user_points');
                                    $rs_first_redeem_replaced = str_replace($rs_first_redeem_to_find, $rs_first_redeem_to_replace, $rs_first_redeem_message);
                                    ?>

                                    <div class="woocommerce-info"><?php echo $rs_first_redeem_replaced; ?></div>
                                    <?php
                                }
                            }
                        }
                    } else {

                        if ($get_old_points >= get_option("rs_minimum_user_points_to_redeem")) {
                            if ($cart_subtotal_redeem_amount >= $minimum_cart_total_redeem) {

                                $user_ID = get_current_user_id();
                                $getinfousernickname = get_user_by('id', $user_ID);
                                $couponcodeuserlogin = $getinfousernickname->user_login;
                                $auto_redeem_name = 'auto_redeem_' . strtolower($couponcodeuserlogin);
                                $array = $woocommerce->cart->get_applied_coupons();
                                if (!in_array($auto_redeem_name, $array)) {
                                    foreach ($woocommerce->cart->cart_contents as $item) {
                                        $product_id = $item['variation_id'] != 0 ? $item['variation_id'] : $item['product_id'];
                                        $type[] = self::check_display_price_type($product_id);
                                        $enable = self::calculate_point_price_for_products($product_id);
                                        if ($enable[$product_id] != '') {
                                            $cart_object[] = $enable[$product_id];
                                        }
                                    }
                                    if (empty($cart_object)) {
                                        if (!in_array(2, $type)) {
                                            if (get_option('rs_redeem_field_type_option') == '1') {
                                                if (is_cart()) {
                                                    ?>
                                                    <div class="fp_apply_reward">
                                                        <?php if (get_option("rs_show_hide_redeem_caption") == '1') { ?>
                                                            <label for="rs_apply_coupon_code_field"><?php echo get_option('rs_redeem_field_caption'); ?></label>
                                                        <?php } ?>
                                                        <?php
                                                        if (get_option('rs_show_hide_redeem_placeholder') == '1') {
                                                            $placeholder = get_option('rs_redeem_field_placeholder');
                                                        }
                                                        ?>
                                                        <input id="rs_apply_coupon_code_field" class="input-text" type="text" placeholder="<?php echo $placeholder; ?>" value="" name="rs_apply_coupon_code_field">
                                                        <input class="button <?php echo get_option('rs_extra_class_name_apply_reward_points'); ?>" type="submit" id='mainsubmi' value="<?php echo get_option('rs_redeem_field_submit_button_caption'); ?>" name="rs_apply_coupon_code">
                                                    </div>
                                                    <div class='rs_warning_message' style='display:inline-block;color:red'></div>
                                                    <?php
                                                }
                                            }
                                            if (get_option('rs_redeem_field_type_option_checkout') == '1') {
                                                if (is_checkout()) {
                                                    ?>
                                                    <div class="fp_apply_reward">
                                                        <?php if (get_option("rs_show_hide_redeem_caption") == '1') { ?>
                                                            <label for="rs_apply_coupon_code_field"><?php echo get_option('rs_redeem_field_caption'); ?></label>
                                                        <?php } ?>
                                                        <?php
                                                        if (get_option('rs_show_hide_redeem_placeholder') == '1') {
                                                            $placeholder = get_option('rs_redeem_field_placeholder');
                                                        }
                                                        ?>
                                                        <input id="rs_apply_coupon_code_field" class="input-text" type="text" placeholder="<?php echo $placeholder; ?>" value="" name="rs_apply_coupon_code_field">
                                                        <input class="button <?php echo get_option('rs_extra_class_name_apply_reward_points'); ?>" type="submit" id='mainsubmi' value="<?php echo get_option('rs_redeem_field_submit_button_caption'); ?>" name="rs_apply_coupon_code">
                                                    </div>
                                                    <div class='rs_warning_message' style='display:inline-block;color:red'></div>
                                                    <?php
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if (get_option('rs_show_hide_minimum_cart_total_error_message') == '1') {
                                    $userid = get_current_user_id();
                                    $banning_type = FPRewardSystem::check_banning_type($userid);
                                    if ($banning_type != 'redeemingonly' && $banning_type != 'both') {
                                        $min_cart_total_redeeming = get_option('rs_min_cart_total_redeem_error');
                                        $min_cart_amount_to_find = "[carttotal]";
                                        $min_cart_total_currency_to_find = "[currencysymbol]";
                                        $min_cart_amount_to_replace = get_option('rs_minimum_cart_total_points');
                                        $min_cart_total_currency_to_replace = self::get_woocommerce_formatted_price($min_cart_amount_to_replace);
                                        $min_cart_total_msg1 = str_replace($min_cart_amount_to_find, $min_cart_total_currency_to_replace, $min_cart_total_redeeming);
                                        $min_cart_total_replaced = str_replace($min_cart_total_currency_to_find, "", $min_cart_total_msg1);
                                        ?>
                                        <div class="woocommerce-info"><?php echo $min_cart_total_replaced; ?></div>
                                        <?php
                                    }
                                }
                            }
                        } else {
                            if (get_option('rs_show_hide_after_first_redeem_error_message') == '1') {
                                $userid = get_current_user_id();
                                $banning_type = FPRewardSystem::check_banning_type($userid);
                                if ($banning_type != 'redeemingonly' && $banning_type != 'both') {
                                    $rs_minpoints_after_first_redeem = get_option('rs_min_points_after_first_error');
                                    $min_points_to_replace = get_option('rs_minimum_user_points_to_redeem');
                                    $min_points_to_find = "[points_after_first_redeem]";
                                    $min_points_after_first_replaced = str_replace($min_points_to_find, $min_points_to_replace, $rs_minpoints_after_first_redeem);
                                    ?>
                                    <div class="woocommerce-info"><?php echo $min_points_after_first_replaced; ?></div>
                                    <?php
                                }
                            }
                        }
                        ?>

                        <?php
                    }
                } else {
                    if (get_option('rs_show_hide_points_empty_error_message') == '1') {
                        $userid = get_current_user_id();
                        $banning_type = FPRewardSystem::check_banning_type($userid);
                        if ($banning_type != 'redeemingonly' && $banning_type != 'both') {
                            $user_points_empty_message = get_option('rs_current_points_empty_error_message');
                            ?>
                            <div class="woocommerce-info"><?php echo $user_points_empty_message; ?></div>
                            <?php
                        }
                    }
                }
            }
        }
    }

    public static function get_woocommerce_formatted_price($price) {
        if (function_exists('woocommerce_price')) {
            return woocommerce_price($price);
        } else {
            if (function_exists('wc_price')) {
                return wc_price($price);
            }
        }
    }

    /* Function to get the reward points to be displayed in message in cart and checkout */

    public static function original_points_for_product_in_cart() {
        global $checkproduct;
        $userid = get_current_user_id();
        $banning_type = FPRewardSystem::check_banning_type($userid);
        if ($banning_type != 'earningonly' && $banning_type != 'both') {
            global $messageglobal;

            global $totalrewardpointsnew;
            global $totalrewardpoints;
            $rewardpoints = array('0');
            $totalrewardpoints;
            global $woocommerce;
            global $value;
            $global_enable = get_option('rs_global_enable_disable_sumo_reward');
            $global_reward_type = get_option('rs_global_reward_type');

            foreach ($woocommerce->cart->cart_contents as $key => $value) {

                $row_price = $value['data']->get_price_including_tax($value['quantity']);


                $cartquantity = $value['quantity'];
                $rewardspoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['product_id'], '_rewardsystempoints');
                $checkenable = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['product_id'], '_rewardsystemcheckboxvalue');



                $checkruleoption = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['product_id'], '_rewardsystem_options');
                $checkrewardpercent = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['product_id'], '_rewardsystempercent');
                if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['product_id'], '_regular_price');
                    if ($getregularprice == '') {
                        $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['product_id'], '_price');
                    }
                } else {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['product_id'], '_price');
                    if ($getregularprice == '') {
                        $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['product_id'], '_regular_price');
                    }
                    do_action_ref_array('rs_price_rule_checker_simple', array(&$getregularprice, &$value));
                }



                $user_ID = get_current_user_id();
                $checkproduct = get_product($value['product_id']);
                if (is_object($checkproduct) && $checkproduct->is_type('booking')) {
                    $getregularprice = $value['data']->price;
                }
                $checkanotherproduct = get_product($value['variation_id']);
                if (is_object($checkproduct) && ($checkproduct->is_type('simple') || ($checkproduct->is_type('subscription')) || ($checkproduct->is_type('booking')))) {
                    if ($checkenable == 'yes') {
                        if ($checkruleoption == '1') {
                            if ($rewardspoints == '') {
                                $term = get_the_terms($value['product_id'], 'product_cat');
                                if (is_array($term)) {
                                    $rewardpoints = array('0');
                                    foreach ($term as $term) {
                                        $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_reward_system_category', true);
                                        $display_type = get_woocommerce_term_meta($term->term_id, 'enable_rs_rule', true);
                                        if (($enablevalue == 'yes') && ($enablevalue != '')) {
                                            if ($display_type == '1') {
                                                $checktermpoints = get_woocommerce_term_meta($term->term_id, 'rs_category_points', true);
                                                if ($checktermpoints == '') {

                                                    if ($global_enable == '1') {
                                                        if ($global_reward_type == '1') {
                                                            $rewardpoints[] = get_option('rs_global_reward_points');
                                                        } else {
                                                            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                            $getaverage = get_option('rs_global_reward_percent') / 100;
                                                            $getaveragepoints = $getaverage * $getregularprice;
                                                            $pointswithvalue = $getaveragepoints * $pointconversion;
                                                            $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                        }
                                                    }
                                                } else {
                                                    $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'rs_category_points', true);
                                                }
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                if (get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) == '') {
                                                    $global_enable = get_option('rs_global_enable_disable_sumo_reward');
                                                    $global_reward_type = get_option('rs_global_reward_type');
                                                    if ($global_enable == '1') {
                                                        if ($global_reward_type == '1') {
                                                            $rewardpoints[] = get_option('rs_global_reward_points');
                                                        } else {
                                                            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                            $getaverage = get_option('rs_global_reward_percent') / 100;
                                                            $getaveragepoints = $getaverage * $getregularprice;
                                                            $pointswithvalue = $getaveragepoints * $pointconversion;
                                                            $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                        }
                                                    }
                                                } else {
                                                    $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                }
                                            }
                                        } else {
                                            if ($global_enable == '1') {
                                                if ($global_reward_type == '1') {
                                                    $rewardpoints[] = get_option('rs_global_reward_points');
                                                } else {
                                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                    $getaverage = get_option('rs_global_reward_percent') / 100;
                                                    $getaveragepoints = $getaverage * $getregularprice;
                                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                                    $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            $rewardpoints[] = get_option('rs_global_reward_points');
                                        } else {
                                            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                            $getaverage = get_option('rs_global_reward_percent') / 100;
                                            $getaveragepoints = $getaverage * $getregularprice;
                                            $pointswithvalue = $getaveragepoints * $pointconversion;
                                            $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                        }
                                    }
                                }
                                if (!empty($rewardpoints)) {
                                    $rewardspoints = max($rewardpoints);
                                }
                            }
                            $totalrewardpoints = RSMemberFunction::user_role_based_reward_points(get_current_user_id(), $rewardspoints) * $cartquantity;

                            $totalrewardpointsnew[$value['product_id']] = $totalrewardpoints;
                        } else {
                            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                            $getaverage = $checkrewardpercent / 100;
                            $getaveragepoints = $getaverage * $getregularprice;
                            $pointswithvalue = $getaveragepoints * $pointconversion;
                            $points = $pointswithvalue / $pointconversionvalue;
                            if ($checkrewardpercent == '') {
                                $term = get_the_terms($value['product_id'], 'product_cat');
                                if (is_array($term)) {
                                    $rewardpoints = array('0');
                                    foreach ($term as $term) {

                                        $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_reward_system_category', true);
                                        $display_type = get_woocommerce_term_meta($term->term_id, 'enable_rs_rule', true);
                                        if (($enablevalue == 'yes') && ($enablevalue != '')) {
                                            if ($display_type == '1') {
                                                if (get_woocommerce_term_meta($term->term_id, 'rs_category_points', true) == '') {
                                                    $global_enable = get_option('rs_global_enable_disable_sumo_reward');
                                                    $global_reward_type = get_option('rs_global_reward_type');
                                                    if ($global_enable == '1') {
                                                        if ($global_reward_type == '1') {
                                                            $rewardpoints[] = get_option('rs_global_reward_points');
                                                        } else {
                                                            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                            $getaverage = get_option('rs_global_reward_percent') / 100;
                                                            $getaveragepoints = $getaverage * $getregularprice;
                                                            $pointswithvalue = $getaveragepoints * $pointconversion;
                                                            $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                        }
                                                    }
                                                } else {

                                                    $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'rs_category_points', true);
                                                }
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                if (get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) == '') {
                                                    $global_enable = get_option('rs_global_enable_disable_sumo_reward');
                                                    $global_reward_type = get_option('rs_global_reward_type');
                                                    if ($global_enable == '1') {
                                                        if ($global_reward_type == '1') {
                                                            $rewardpoints[] = get_option('rs_global_reward_points');
                                                        } else {
                                                            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                            $getaverage = get_option('rs_global_reward_percent') / 100;
                                                            $getaveragepoints = $getaverage * $getregularprice;
                                                            $pointswithvalue = $getaveragepoints * $pointconversion;
                                                            $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                        }
                                                    }
                                                } else {
                                                    $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                }
                                            }
                                        } else {
                                            if ($global_enable == '1') {
                                                if ($global_reward_type == '1') {
                                                    $rewardpoints[] = get_option('rs_global_reward_points');
                                                } else {
                                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                    $getaverage = get_option('rs_global_reward_percent') / 100;
                                                    $getaveragepoints = $getaverage * $getregularprice;
                                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                                    $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            $rewardpoints[] = get_option('rs_global_reward_points');
                                        } else {
                                            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                            $getaverage = get_option('rs_global_reward_percent') / 100;
                                            $getaveragepoints = $getaverage * $getregularprice;
                                            $pointswithvalue = $getaveragepoints * $pointconversion;
                                            $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                        }
                                    }
                                }
                                $points = max($rewardpoints);
                            }

                            $totalrewardpoints = RSMemberFunction::user_role_based_reward_points(get_current_user_id(), $points) * $cartquantity;
                            $totalrewardpointsnew[$value['product_id']] = $totalrewardpoints;
                        }
                    }
                } else {
                    $checkenablevariation = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['variation_id'], '_enable_reward_points');
                    $variablerewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['variation_id'], '_reward_points');
                    $variationselectrule = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['variation_id'], '_select_reward_rule');
                    $variationrewardpercent = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($value['variation_id'], '_reward_percent');
                    if ($value['variation_id'] != '' && $value['variation_id'] != '0') {
                        $variable_product1 = new WC_Product_Variation($value['variation_id']);


                        if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                            $variationregularprice = $variable_product1->regular_price != '' ? $variable_product1->regular_price : $variable_product1->price;
                        } else {
                            $variationregularprice = $variable_product1->price != '' ? $variable_product1->price : $variable_product1->regular_price;
                            do_action_ref_array('rs_price_rule_checker_variant', array(&$variationregularprice, &$value));
                        }
                        if ($checkenablevariation == '1') {
                            if ($variationselectrule == '1') {
                                $parentvariationid = new WC_Product_Variation($value['variation_id']);
                                $newparentid = $parentvariationid->parent->id;
                                if ($variablerewardpoints == '') {
                                    $term = get_the_terms($newparentid, 'product_cat');
                                    if (is_array($term)) {
                                        $rewardpoints = array('0');
                                        foreach ($term as $term) {

                                            $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_reward_system_category', true);
                                            $display_type = get_woocommerce_term_meta($term->term_id, 'enable_rs_rule', true);
                                            if (($enablevalue == 'yes') && ($enablevalue != '')) {
                                                if ($display_type == '1') {
                                                    if (get_woocommerce_term_meta($term->term_id, 'rs_category_points', true) == '') {
                                                        $global_enable = get_option('rs_global_enable_disable_sumo_reward');
                                                        $global_reward_type = get_option('rs_global_reward_type');
                                                        if ($global_enable == '1') {
                                                            if ($global_reward_type == '1') {
                                                                $rewardpoints[] = get_option('rs_global_reward_points');
                                                            } else {
                                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                                $getaverage = get_option('rs_global_reward_percent') / 100;
                                                                $getaveragepoints = $getaverage * $variationregularprice;
                                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                            }
                                                        }
                                                    } else {
                                                        $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'rs_category_points', true);
                                                    }
                                                } else {
                                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                    $getaverage = get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) / 100;
                                                    $getaveragepoints = $getaverage * $variationregularprice;
                                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                                    if (get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) == '') {
                                                        $global_enable = get_option('rs_global_enable_disable_sumo_reward');
                                                        $global_reward_type = get_option('rs_global_reward_type');
                                                        if ($global_enable == '1') {
                                                            if ($global_reward_type == '1') {
                                                                $rewardpoints[] = get_option('rs_global_reward_points');
                                                            } else {
                                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                                $getaverage = get_option('rs_global_reward_percent') / 100;
                                                                $getaveragepoints = $getaverage * $variationregularprice;
                                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                            }
                                                        }
                                                    } else {
                                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                    }
                                                }
                                            } else {
                                                if ($global_enable == '1') {
                                                    if ($global_reward_type == '1') {
                                                        $rewardpoints[] = get_option('rs_global_reward_points');
                                                    } else {
                                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                        $getaverage = get_option('rs_global_reward_percent') / 100;
                                                        $getaveragepoints = $getaverage * $variationregularprice;
                                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $variationregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    }
                                    $variablerewardpoints = max($rewardpoints);
                                }
                                $totalrewardpoints = RSMemberFunction::user_role_based_reward_points(get_current_user_id(), $variablerewardpoints) * $cartquantity;
                                $totalrewardpointsnew[$value['variation_id']] = $totalrewardpoints;
                            } else {
                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                $getaverage = $variationrewardpercent / 100;
                                $getaveragepoints = $getaverage * $variationregularprice;
                                $getpointsvalue = $getaveragepoints * $pointconversion;
                                $points = $getpointsvalue / $pointconversionvalue;
                                $parentvariationid = new WC_Product_Variation($value['variation_id']);
                                $newparentid = $parentvariationid->parent->id;
                                if ($variationrewardpercent == '') {
                                    $term = get_the_terms($newparentid, 'product_cat');
                                    if (is_array($term)) {
                                        $rewardpoints = array('0');
                                        foreach ($term as $term) {

                                            $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_reward_system_category', true);
                                            $display_type = get_woocommerce_term_meta($term->term_id, 'enable_rs_rule', true);

                                            if (($enablevalue == 'yes') && ($enablevalue != '')) {
                                                if ($display_type == '1') {
                                                    if (get_woocommerce_term_meta($term->term_id, 'rs_category_points', true) == '') {
                                                        $global_enable = get_option('rs_global_enable_disable_sumo_reward');
                                                        $global_reward_type = get_option('rs_global_reward_type');
                                                        if ($global_enable == '1') {
                                                            if ($global_reward_type == '1') {
                                                                $rewardpoints[] = get_option('rs_global_reward_points');
                                                            } else {
                                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                                $getaverage = get_option('rs_global_reward_percent') / 100;
                                                                $getaveragepoints = $getaverage * $variationregularprice;
                                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                            }
                                                        }
                                                    } else {
                                                        $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'rs_category_points', true);
                                                    }
                                                } else {
                                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                    $getaverage = get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) / 100;
                                                    $getaveragepoints = $getaverage * $variationregularprice;
                                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                                    if (get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) == '') {
                                                        $global_enable = get_option('rs_global_enable_disable_sumo_reward');
                                                        $global_reward_type = get_option('rs_global_reward_type');
                                                        if ($global_enable == '1') {
                                                            if ($global_reward_type == '1') {
                                                                $rewardpoints[] = get_option('rs_global_reward_points');
                                                            } else {
                                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                                $getaverage = get_option('rs_global_reward_percent') / 100;
                                                                $getaveragepoints = $getaverage * $variationregularprice;
                                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                            }
                                                        }
                                                    } else {
                                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                    }
                                                }
                                            } else {
                                                if ($global_enable == '1') {
                                                    if ($global_reward_type == '1') {
                                                        $rewardpoints[] = get_option('rs_global_reward_points');
                                                    } else {
                                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                        $getaverage = get_option('rs_global_reward_percent') / 100;
                                                        $getaveragepoints = $getaverage * $variationregularprice;
                                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                    }
                                                }
                                            }
                                        }
                                    } else {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_reward_points');
                                            } else {
                                                $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $variationregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    }
                                    $points = max($rewardpoints);
                                }

                                $totalrewardpoints = RSMemberFunction::user_role_based_reward_points(get_current_user_id(), $points) * $cartquantity;

                                $totalrewardpointsnew[$value['variation_id']] = $totalrewardpoints;
                            }
                        }
                    }
                }
            }
            return $totalrewardpointsnew;
        }
    }

    public static function check_the_applied_coupons() {
        global $woocommerce;
        if (get_option('rs_enable_redeem_for_order') == 'yes') {
            if (is_array($woocommerce->cart->get_applied_coupons())) {
                $getappliedcoupon = $woocommerce->cart->get_applied_coupons();
                if (!empty($getappliedcoupon)) {
                    $currentuserid = get_current_user_id();
                    $user_ID = get_current_user_id();
                    $getinfousernickname = get_user_by('id', $user_ID);
                    $couponcodeuserlogin = $getinfousernickname->user_login;
                    $usernickname = 'sumo_' . strtolower("$couponcodeuserlogin");
                    $auto_redeem_name = 'auto_redeem_' . strtolower($couponcodeuserlogin);
                    foreach ($woocommerce->cart->get_applied_coupons() as $coupons) {
                        if (strtolower($coupons) == $usernickname || strtolower($coupons) == $auto_redeem_name) {
                            return true;
                        } else {
                            return false;
                        }
                    }
                } else {
                    return false;
                }
            }
        } else {
            return false;
        }
    }

    public static function display_msg_in_cart_page() {
        global $woocommerce;
        global $value;
        global $totalrewardpointsnew;
        global $messageglobal;
        if (is_user_logged_in()) {
            $checkenableoption = self::check_the_applied_coupons();
            if (get_option('rs_show_hide_message_for_each_products') == '1') {
                if ($checkenableoption == false) {
                    if (is_array($totalrewardpointsnew)) {
                        if (!empty($totalrewardpointsnew)) {
                            if (array_sum($totalrewardpointsnew) > 0) {
                                if (is_array($messageglobal) && $messageglobal != NULL) {
                                    ?>
                                    <div class="woocommerce-info sumo_reward_points_info_message">
                                        <?php
                                        foreach ($messageglobal as $globalcommerce) {
                                            echo $globalcommerce;
                                        }
                                        ?>
                                    </div>
                                    <?php
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public static function display_msg_in_cart_page_for_balance_reward_points() {
        global $woocommerce;
        if (is_user_logged_in()) {
            if (get_option('rs_show_hide_message_for_redeem_points') == '1') {
                if (is_array($woocommerce->cart->get_applied_coupons())) {
                    $currentuserid = get_current_user_id();
                    $user_ID = get_current_user_id();
                    $getinfousernickname = get_user_by('id', $user_ID);
                    $couponcodeuserlogin = $getinfousernickname->user_login;
                    $usernickname = 'sumo_' . strtolower("$couponcodeuserlogin");
                    $auto_redeem_name = 'auto_redeem_' . strtolower("$couponcodeuserlogin");

                    if (isset($woocommerce->cart->coupon_discount_amounts["$auto_redeem_name"])) {
                        $total = $woocommerce->cart->coupon_discount_amounts["$auto_redeem_name"];

                        if ($total != 0) {
                            foreach ($woocommerce->cart->get_applied_coupons() as $coupons) {
                                if (strtolower($coupons) == $auto_redeem_name) {
                                    ?>
                                    <div class="woocommerce-message sumo_reward_points_auto_redeem_message">
                                        <?php echo do_shortcode(get_option('rs_message_user_points_redeemed_in_cart')); ?>
                                    </div>
                                    <?php
                                    if (get_option('rs_enable_redeem_for_order') == 'yes') {
                                        ?>
                                        <div class="woocommerce-info sumo_reward_points_auto_redeem_error_message">
                                            <?php echo get_option('rs_errmsg_for_redeeming_in_order'); ?>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                        }
                    }

                    if (isset($woocommerce->cart->coupon_discount_amounts["$usernickname"])) {
                        $total = $woocommerce->cart->coupon_discount_amounts["$usernickname"];
                        if ($total != 0) {
                            
                            foreach ($woocommerce->cart->get_applied_coupons() as $coupons) {
                                if (strtolower($coupons) == $usernickname || strtolower($coupons) == $auto_redeem_name) {
                                    $userid = get_current_user_id();
                                    $banning_type = FPRewardSystem::check_banning_type($userid);
                                    if ($banning_type != 'redeemingonly' && $banning_type != 'both') {
                                        ?>
                                        <div class="woocommerce-message sumo_reward_points_manual_redeem_message">
                                            <?php echo do_shortcode(get_option('rs_message_user_points_redeemed_in_cart')); ?>
                                        </div>
                                        <?php
                                        /* Error Message to be Displayed When the order contain only redeeming */
                                        if (get_option('rs_enable_redeem_for_order') == 'yes') {
                                            ?>
                                            <div class="woocommerce-info sumo_reward_points_manual_redeem_error_message">
                                                <?php echo get_option('rs_errmsg_for_redeeming_in_order'); ?>
                                            </div>
                                            <?php
                                        }
                                        if (get_option('rs_redeem_field_type_option') == '2') {
                                            ?>
                                            <div class="sumo_reward_point_hide_field_script" data-sumo_coupon="yes">
                                                <script type="text/javascript">
                                                    jQuery(document).ready(function () {
                                                        jQuery("#mainsubmi").parent().hide();
                                                    });
                                                </script>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <?php
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public static function display_msg_in_checkout_page() {
        global $woocommerce;
        global $value;
        global $totalrewardpointsnew;
        global $messageglobal;
        if (is_user_logged_in()) {
            $checkenableoption = self::check_the_applied_coupons();
            if (get_option('rs_show_hide_message_for_each_products_checkout_page') == '1') {
                if ($checkenableoption == false) {
                    if (is_array($totalrewardpointsnew)) {
                        if (array_sum($totalrewardpointsnew) > 0) {
                            if (is_array($messageglobal) && $messageglobal != NULL) {
                                ?>
                                <div class="woocommerce-info">
                                    <?php
                                    if (is_array($messageglobal)) {
                                        foreach ($messageglobal as $globalcommerce) {
                                            echo $globalcommerce;
                                        }
                                    }
                                    ?>
                                </div>
                                <?php
                            }
                        }
                    }
                }
            }
        }
    }

  public static function get_redeem_point_to_display_in_msg() {
        global $woocommerce;
        global $value;
        $user_ID = get_current_user_id();
        $getinfousernickname = get_user_by('id', $user_ID);
        $couponcodeuserlogin = $getinfousernickname->user_login;
        $user_current_points = RSPointExpiry::get_sum_of_total_earned_points($user_ID);
        $auto_redeem_name = 'auto_redeem_' . strtolower($couponcodeuserlogin);
        $usernickname = 'sumo_' . strtolower("$couponcodeuserlogin");
        if (isset($woocommerce->cart->coupon_discount_amounts["$usernickname"])) {
            $total = $woocommerce->cart->coupon_discount_amounts[$usernickname];
            $tax = $woocommerce->cart->coupon_discount_tax_amounts[$usernickname];
             if (get_option('woocommerce_tax_display_cart') == 'incl') {
                $total = $total + $tax;
            }
            $coupon = new WC_Coupon($usernickname);
            $couponcode = $coupon->code;
            $current_conversion = wc_format_decimal(get_option('rs_redeem_point'));
            $point_amount = wc_format_decimal(get_option('rs_redeem_point_value'));
            $newtotal = $total * $current_conversion;
            $newtotal = $newtotal / $point_amount;                     
            if ($newtotal > $user_current_points) {
                $newtotal = $user_current_points;
            }

            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
            return round($newtotal, $roundofftype);
        }

        if (isset($woocommerce->cart->coupon_discount_amounts["$auto_redeem_name"])) {
            $total = $woocommerce->cart->coupon_discount_amounts[$auto_redeem_name];
            $tax = $woocommerce->cart->coupon_discount_tax_amounts[$auto_redeem_name];
            $coupon = new WC_Coupon($auto_redeem_name);
            $couponcode = $coupon->coupon_amount;
if (get_option('woocommerce_tax_display_cart') == 'incl') {
                $total = $total + $tax;
            }
            $current_conversion = wc_format_decimal(get_option('rs_redeem_point'));
            $point_amount = wc_format_decimal(get_option('rs_redeem_point_value'));
            $newtotal = $total * $current_conversion;
            $newtotal = $newtotal / $point_amount;
            
            if ($newtotal > $user_current_points) {
                $newtotal = $user_current_points;
            }
            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
            return round($newtotal, $roundofftype);
        }
    }

    public static function display_msg_in_checkout_page_for_balance_reward_points() {
        global $woocommerce;
        if (get_option('rs_show_hide_message_for_redeem_points_checkout_page') == '1') {
            if (is_user_logged_in()) {
                if (is_array($woocommerce->cart->get_applied_coupons())) {
                    $currentuserid = get_current_user_id();

                    $user_ID = get_current_user_id();
                    $getinfousernickname = get_user_by('id', $user_ID);
                    $couponcodeuserlogin = $getinfousernickname->user_login;

                    $usernickname = 'sumo_' . strtolower("$couponcodeuserlogin");
                    $auto_redeem_name = 'auto_redeem_' . strtolower($couponcodeuserlogin);
                    if (isset($woocommerce->cart->coupon_discount_amounts["$auto_redeem_name"])) {
                        $total = $woocommerce->cart->coupon_discount_amounts["$auto_redeem_name"];
                        if ($total != 0) {
                            foreach ($woocommerce->cart->get_applied_coupons() as $coupons) {
                                if (strtolower($coupons) == $auto_redeem_name) {
                                    ?>
                                    <div class="woocommerce-message">
                                        <?php echo do_shortcode(get_option('rs_message_user_points_redeemed_in_checkout')); ?>
                                    </div>
                                    <?php
                                    if (get_option('rs_enable_redeem_for_order') == 'yes') {
                                        ?>
                                        <div class="woocommerce-info">
                                            <?php echo get_option('rs_errmsg_for_redeeming_in_order'); ?>
                                        </div>
                                        <?php
                                    }
                                }
                            }
                        }
                    }
                    if (isset($woocommerce->cart->coupon_discount_amounts["$usernickname"])) {
                        $total = $woocommerce->cart->coupon_discount_amounts["$usernickname"];
                        if ($total != 0) {
                            foreach ($woocommerce->cart->get_applied_coupons() as $coupons) {
                                if (strtolower($coupons) == $usernickname || strtolower($coupons) == $auto_redeem_name) {

                                    $userid = get_current_user_id();
                                    $banning_type = FPRewardSystem::check_banning_type($userid);
                                    if ($banning_type != 'redeemingonly' && $banning_type != 'both') {
                                        ?>
                                        <div class="woocommerce-message">
                                            <?php echo do_shortcode(get_option('rs_message_user_points_redeemed_in_checkout')); ?>
                                        </div>
                                        <?php
                                        /* Error Message to be Displayed When the order contain only redeeming */
                                        if (get_option('rs_enable_redeem_for_order') == 'yes') {
                                            ?>
                                            <div class="woocommerce-info">
                                                <?php echo get_option('rs_errmsg_for_redeeming_in_order'); ?>
                                            </div>
                                            <?php
                                        }

                                        if (get_option('rs_redeem_field_type_option') == '2') {
                                            ?>
                                            <script type="text/javascript">
                                                jQuery(document).ready(function () {
                                                    jQuery("#mainsubmi").parent().hide();
                                                });</script>
                                            <?php
                                        }
                                    }
                                    ?>
                                    <?php
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public static function get_each_product_price_in_cart() {
        global $totalrewardpoints;
        global $checkproduct;
        global $value;

        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
        return round($totalrewardpoints, $roundofftype);
    }

    public static function get_each_producttitle_in_cart() {
        global $checkproduct;
        global $value;

        $variation = get_product($value);

        if (is_object($checkproduct) && ($variation->is_type('simple') || ($variation->is_type('subscription')))) {

            return "<strong>" . get_the_title($value) . "</strong>";
        } else {
            $variation = $variation->get_variation_attributes();

            foreach ($variation as $key) {
                return "<strong>" . $checkproduct->get_title() . "\r" . $key . "</strong>";
            }
        }
    }

    public static function get_each_product_points_value_in_cart() {
        $getpoints = do_shortcode('[rspoint]');
        $redeemconver = $getpoints / wc_format_decimal(get_option('rs_redeem_point'));
        $updatedvalue = $redeemconver * wc_format_decimal(get_option('rs_redeem_point_value'));
        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
        return self::get_woocommerce_formatted_price(round($updatedvalue, $roundofftype));
    }

    public static function get_balance_redeem_points_to_display_in_msg() {
        global $woocommerce;

        $currentuserid = get_current_user_id();

        $user_ID = get_current_user_id();

        $getinfousernickname = get_user_by('id', $user_ID);
        $couponcodeuserlogin = $getinfousernickname->user_login;
        $usernickname = 'sumo_' . strtolower("$couponcodeuserlogin");
        $auto_redeem_name = 'auto_redeem_' . strtolower($couponcodeuserlogin);
        if (isset($woocommerce->cart->coupon_discount_amounts["$usernickname"])) {
            $total = $woocommerce->cart->coupon_discount_amounts[$usernickname];
            $tax = $woocommerce->cart->coupon_discount_tax_amounts[$usernickname];
            if (get_option('woocommerce_tax_display_cart') == 'incl') {
                $total = $total + $tax;
            }
            $current_conversion = wc_format_decimal(get_option('rs_redeem_point'));
            $point_amount = wc_format_decimal(get_option('rs_redeem_point_value'));
            $total = $total * $current_conversion;
            $total = $total / $point_amount;
            $user_ID = get_current_user_id();
            $myrewardpoint = RSPointExpiry::get_sum_of_total_earned_points($user_ID);
            $majorpoint = $myrewardpoint - $total;
            if ($majorpoint < 0) {
                $majorpoint = '0';
            }
            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
            return round($majorpoint, $roundofftype);
        }
        if (isset($woocommerce->cart->coupon_discount_amounts["$auto_redeem_name"])) {
            $total = $woocommerce->cart->coupon_discount_amounts[$auto_redeem_name];
            $tax = $woocommerce->cart->coupon_discount_tax_amounts[$auto_redeem_name];
            if (get_option('woocommerce_tax_display_cart') == 'incl') {
                $total = $total + $tax;
            }
            $coupon = new WC_Coupon($auto_redeem_name);
            $couponcode = $coupon->coupon_amount;
            $current_conversion = wc_format_decimal(get_option('rs_redeem_point'));
            $point_amount = wc_format_decimal(get_option('rs_redeem_point_value'));
            $total = $total * $current_conversion;
            $total = $total / $point_amount;
            
            $user_ID = get_current_user_id();
            $myrewardpoint = RSPointExpiry::get_sum_of_total_earned_points($user_ID);

            $majorpoint = $myrewardpoint - $total;
            if ($majorpoint < 0) {
                $majorpoint = '0';
            }
            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
            return round($majorpoint, $roundofftype);
        }
    }

    public static function validation_in_my_cart() {
        ?>
        <script type='text/javascript'>
            jQuery(document).ready(function () {
                jQuery('#mainsubmi').click(function () {
                    var float_value_current_points = parseFloat('<?php
        $currentuserpoints = RSPointExpiry::get_sum_of_total_earned_points(get_current_user_id());
        echo $currentuserpoints;
        ?>');
                    float_value_current_points = Math.round(float_value_current_points * 100) / 100;
                    var float_value_minimum_redeeming_points = parseFloat('<?php echo get_option("rs_minimum_redeeming_points"); ?>');
                    float_value_minimum_redeeming_points = Math.round(float_value_minimum_redeeming_points * 100) / 100;
                    var float_value_maximum_redeeming_points = parseFloat('<?php echo get_option("rs_maximum_redeeming_points"); ?>');
                    float_value_maximum_redeeming_points = Math.round(float_value_maximum_redeeming_points * 100) / 100;
        <?php if (get_option('rs_redeem_field_type_option') == '1') { ?>
                        var getvalue = jQuery('#rs_apply_coupon_code_field').val();
                        if (getvalue === '') {
                            jQuery('.rs_warning_message').html('<?php echo addslashes(get_option('rs_redeem_empty_error_message')); ?>');
                            return false;
                        } else if (jQuery.isNumeric(getvalue) == false) {
                            jQuery('.rs_warning_message').html('<?php echo addslashes(get_option('rs_redeem_character_error_message')); ?>');
                            return false;
                        } else if (getvalue > float_value_current_points) {
                            jQuery('.rs_warning_message').html('<?php echo addslashes(get_option('rs_redeem_max_error_message')); ?>');
                            return false;
                        } else if (jQuery.isNumeric(getvalue) == true) {
                            if (getvalue < 0) {
                                jQuery('.rs_warning_message').html('<?php echo addslashes(get_option('rs_redeem_character_error_message')); ?>');
                                return false;
                            }
                        }
            <?php if (get_option('rs_minimum_redeeming_points') == (get_option('rs_maximum_redeeming_points'))) { ?>
                            if (getvalue < float_value_minimum_redeeming_points) {
                                jQuery('.rs_warning_message').html('<?php echo do_shortcode(addslashes(get_option("rs_minimum_and_maximum_redeem_point_error_message"))); ?>');
                                return false;
                            } else if (getvalue > float_value_maximum_redeeming_points) {
                                jQuery('.rs_warning_message').html('<?php echo do_shortcode(addslashes(get_option("rs_minimum_and_maximum_redeem_point_error_message"))); ?>');
                                return false;
                            }
            <?php } ?>
            <?php if (get_option('rs_minimum_redeeming_points') != '') { ?>
                            if (getvalue < float_value_minimum_redeeming_points) {
                                jQuery('.rs_warning_message').html('<?php echo do_shortcode(addslashes(get_option("rs_minimum_redeem_point_error_message"))); ?>');
                                return false;
                            }
            <?php } ?>

            <?php if (get_option('rs_maximum_redeeming_points') != '') { ?>
                            if (getvalue > float_value_maximum_redeeming_points) {
                                jQuery('.rs_warning_message').html('<?php echo do_shortcode(addslashes(get_option("rs_maximum_redeem_point_error_message"))); ?>');
                                return false;
                            }
            <?php } ?>

        <?php } else { ?>


                        var getvalue = jQuery('#rs_apply_coupon_code_field').val();
            <?php if (get_option('rs_minimum_redeeming_points') == (get_option('rs_maximum_redeeming_points'))) { ?>
                            if (getvalue < float_value_minimum_redeeming_points) {
                                jQuery('.rs_warning_message').html('<?php echo do_shortcode(addslashes(get_option("rs_minimum_and_maximum_redeem_point_error_message_for_buttontype"))); ?>');
                                return false;
                            } else if (getvalue > float_value_maximum_redeeming_points) {
                                jQuery('.rs_warning_message').html('<?php echo do_shortcode(addslashes(get_option("rs_minimum_and_maximum_redeem_point_error_message_for_buttontype"))); ?>');
                                return false;
                            }
            <?php } ?>
            <?php if (get_option('rs_minimum_redeeming_points') != '') { ?>
                            if (getvalue < float_value_minimum_redeeming_points) {
                                jQuery('.rs_warning_message').html('<?php echo do_shortcode(addslashes(get_option("rs_minimum_redeem_point_error_message_for_button_type"))); ?>');
                                return false;
                            }
            <?php } ?>

            <?php if (get_option('rs_maximum_redeeming_points') != '') { ?>
                            if (getvalue > float_value_maximum_redeeming_points) {
                                jQuery('.rs_warning_message').html('<?php echo do_shortcode(addslashes(get_option("rs_maximum_redeem_point_error_message_for_button_type"))); ?>');
                                return false;
                            }
            <?php } ?>

        <?php } ?>
                });
            });
        </script>
        <?php
    }

    public static function get_minimum_redeeming_points_value() {
        return get_option('rs_minimum_redeeming_points');
    }

    public static function get_maximum_redeeming_points_value() {
        return get_option('rs_maximum_redeeming_points');
    }

    public static function get_minimum_and_maximum_redeeming_points_value() {
        return get_option('rs_minimum_redeeming_points');
    }

    public static function display_redeem_points_buttons_on_cart_page() {
        $totalselectedvalue = array();

        global $woocommerce;
        if (is_user_logged_in()) {
            $type = array();
            $points_for_include_product = '';
            $userid = get_current_user_id();
            $banning_type = FPRewardSystem::check_banning_type($userid);
            if ($banning_type != 'redeemingonly' && $banning_type != 'both') {
                $getuserid = get_current_user_id();
                $user_current_points = RSPointExpiry::get_sum_of_total_earned_points($getuserid);
                $points_conversion_value = wc_format_decimal(get_option('rs_redeem_point_value'));
                $redeem_conversion = wc_format_decimal(get_option('rs_redeem_point'));
                if (get_option('rs_apply_redeem_basedon_cart_or_product_total') == '2') {
                    $getsumofselectedproduct = RSFunctionToApplyCoupon::get_sum_of_selected_products('auto', '', $user_current_points);
                    foreach ($woocommerce->cart->cart_contents as $item) {
                        $productid = $item['variation_id'] != 0 ? $item['variation_id'] : $item['product_id'];
                        $includeproductid = get_option('rs_select_products_to_enable_redeeming');
                        if (is_array($includeproductid)) {
                            $include_productid = (array) $includeproductid;
                        } else {
                            $include_productid = (array) explode(',', $includeproductid);
                        }
                        if (get_option('rs_enable_redeem_for_selected_products') == 'yes') {
                            if (get_option('rs_select_products_to_enable_redeeming') != '') {
                                if (in_array($productid, $include_productid)) {
                                    $totalselectedvalue[] = $item['line_subtotal'];
                                }
                            }
                        }
                        $includecategory = get_option('rs_select_category_to_enable_redeeming');
                        if (is_array($includecategory)) {
                            $include_category = (array) $includecategory; // Compatible for Old WooCommerce Version            
                        } else {
                            $include_category = (array) explode(',', $includecategory); // Compatible with Latest Version            
                        }
                        $productcategorys = get_the_terms($productid, 'product_cat');
                        if ($productcategorys != false) {
                            $getcount = count($productcategorys);
                            if ($getcount >= '1') {
                                foreach ($productcategorys as $productcategory) {
                                    $termid = $productcategory->term_id;
                                    if (get_option('rs_enable_redeem_for_selected_category') == 'yes') {
                                        if (get_option('rs_select_category_to_enable_redeeming') != '') {
                                            if (in_array($termid, $include_category)) {
                                                $totalselectedvalue[$productid] = $item['line_subtotal'];
                                            }
                                        } else {
                                            $totalselectedvalue[] = $woocommerce->cart->subtotal;
                                        }
                                    }
                                }
                            } else {
                                @$termid = $productcategorys[0]->term_id;
                                if (get_option('rs_enable_redeem_for_selected_category') == 'yes') {
                                    if (get_option('rs_select_category_to_enable_redeeming') != '') {
                                        if (in_array($termid, $include_category)) {
                                            $totalselectedvalue[$productid] = $item['line_subtotal'];
                                        }
                                    } else {
                                        $totalselectedvalue[] = $woocommerce->cart->subtotal;
                                    }
                                }
                            }
                        }
                    }
                    $points_for_include_product_sum = array_sum($totalselectedvalue);
                    $points_for_include_product = $redeem_conversion * $points_for_include_product_sum;
                    $points_for_redeeming = $points_for_include_product / $points_conversion_value;
                }
                if ($user_current_points > 0) {
                    if (get_user_meta($getuserid, 'rsfirsttime_redeemed', true) != '1') {
                        if ($user_current_points >= get_option("rs_first_time_minimum_user_points")) {
                            if (get_option('rs_show_hide_redeem_field') == '1') {
                                if (get_option('rs_redeem_field_type_option') == '2') {
                                    $getuserid = get_current_user_id();
                                    $user_current_points = RSPointExpiry::get_sum_of_total_earned_points($getuserid);

                                   if (get_option('woocommerce_prices_include_tax') == 'yes') {
                                        if (get_option('woocommerce_tax_display_cart') == 'incl') {
                                            $current_carttotal_amount = $woocommerce->cart->subtotal;
                                        } else {
                                            $current_carttotal_amount = $woocommerce->cart->subtotal_ex_tax;
                                        }
                                    } else {
                                        if (get_option('woocommerce_tax_display_cart') == 'incl') {
                                            $current_carttotal_amount = $woocommerce->cart->subtotal;
                                        } else {
                                            $current_carttotal_amount = $woocommerce->cart->subtotal_ex_tax;
                                        }
                                    }
                                    $limitation_percentage_for_redeeming = get_option('rs_percentage_cart_total_redeem');
                                    $cart_total_in_amount = $limitation_percentage_for_redeeming / 100;
                                    $updated_cart_total_in_amount = $cart_total_in_amount * $current_carttotal_amount;
                                    $redeem_conversion = wc_format_decimal(get_option('rs_redeem_point'));
                                    $points_conversion_value = wc_format_decimal(get_option('rs_redeem_point_value'));
                                    $points_for_redeem_in_points = $updated_cart_total_in_amount * $redeem_conversion;
                                    $updated_points_for_redeeming = $points_for_redeem_in_points / $points_conversion_value;
                                    $cartpoints_string_to_replace = "[cartredeempoints]";
                                    $currency_symbol_string_to_find = "[currencysymbol]";
                                    $cuurency_value_string_to_find = "[pointsvalue]";
                                    if ($user_current_points >= $updated_points_for_redeeming) {
                                        $redeem_button_message_more = get_option('rs_redeeming_button_option_message');
                                        $percentage_string_to_replace = "[redeempercent]";
                                        $cuurency_value_string_to_find = "[pointsvalue]";
                                        $points_conversion_value = wc_format_decimal(get_option('rs_redeem_point_value'));
                                        $points_currency_value = $updated_cart_total_in_amount;
                                        $points_currency_amount_to_replace = $updated_points_for_redeeming;
                                        $points_for_redeeming = $updated_points_for_redeeming;
                                        if (get_option('rs_apply_redeem_basedon_cart_or_product_total') == '2') {
                                            if ($points_for_include_product != '') {
                                                $limitation_percentage_for_redeeming = get_option('rs_percentage_cart_total_redeem');
                                                $cart_total_in_amount = $limitation_percentage_for_redeeming / 100;
                                                $updated_cart_total_in_amount = $cart_total_in_amount * $points_for_include_product_sum;
                                                $points_for_include_product = $redeem_conversion * $updated_cart_total_in_amount;
                                                $points_for_redeeming = $points_for_include_product / $points_conversion_value;
                                                $points_currency_value = $updated_cart_total_in_amount;
                                            }
                                        }
                                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                        $points_for_redeeming = round($points_for_redeeming, $roundofftype);

                                        $redeem_button_message_more = get_option('rs_redeeming_button_option_message');
                                        $currency_symbol_string_to_replace = self::get_woocommerce_formatted_price($points_currency_value);
                                        $redeem_button_message_replaced_first = str_replace($cuurency_value_string_to_find, $currency_symbol_string_to_replace, $redeem_button_message_more);
                                        $redeem_button_message_replaced_second = str_replace($currency_symbol_string_to_find, "", $redeem_button_message_replaced_first);
                                        $redeem_button_message_replaced_third = str_replace($cartpoints_string_to_replace, $points_for_redeeming, $redeem_button_message_replaced_second);
                                    } else {

                                        $points_for_redeeming = $user_current_points;
                                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                        $points_for_redeeming = round($points_for_redeeming, $roundofftype);
                                        $redeem_button_message_more = get_option('rs_redeeming_button_option_message');
                                        $points_conversion_value = wc_format_decimal(get_option('rs_redeem_point_value'));
                                        $points_currency_value = $points_for_redeeming / $redeem_conversion;
                                        $points_currency_amount_to_replace = $points_currency_value * $points_conversion_value;
                                        if (get_option('rs_apply_redeem_basedon_cart_or_product_total') == '2') {
                                            if ($points_for_include_product != '') {
                                                $points_for_redeeming1 = $points_for_include_product / $points_conversion_value;
                                                if ($user_current_points > $points_for_redeeming1) {
                                                    $points_for_redeeming = $points_for_redeeming1;
                                                    $points_currency_value = $getsumofselectedproduct;
                                                    $limitation_percentage_for_redeeming = get_option('rs_percentage_cart_total_redeem');
                                                    $cart_total_in_amount = $limitation_percentage_for_redeeming / 100;
                                                    $updated_cart_total_in_amount = $cart_total_in_amount * $points_for_include_product_sum;
                                                    $points_for_include_product = $redeem_conversion * $updated_cart_total_in_amount;
                                                    $points_for_redeeming = $points_for_include_product / $points_conversion_value;
                                                    $points_currency_value = $updated_cart_total_in_amount;
                                                }
                                            }
                                        }
                                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                        $points_for_redeeming = round($points_for_redeeming, $roundofftype);

                                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                        $points_currency_amount_to_replace = round($points_currency_amount_to_replace, $roundofftype);
                                        $currency_symbol_string_to_replace = self::get_woocommerce_formatted_price($points_currency_value);
                                        $redeem_button_message_replaced_first = str_replace($currency_symbol_string_to_find, "", $redeem_button_message_more);
                                        $redeem_button_message_replaced_second = str_replace($cuurency_value_string_to_find, $currency_symbol_string_to_replace, $redeem_button_message_replaced_first);
                                        $redeem_button_message_replaced_third = str_replace($cartpoints_string_to_replace, $points_for_redeeming, $redeem_button_message_replaced_second);
                                    }
                                    $minimum_cart_total_redeem = get_option('rs_minimum_cart_total_points');

                                    if (get_option('woocommerce_prices_include_tax') === 'yes') {
                                        $cart_subtotal_redeem_amount = $woocommerce->cart->subtotal_ex_tax;
                                    } else {
                                        $cart_subtotal_redeem_amount = $woocommerce->cart->subtotal;
                                    }

                                    if ($cart_subtotal_redeem_amount >= $minimum_cart_total_redeem) {
                                        $user_ID = get_current_user_id();
                                        $getinfousernickname = get_user_by('id', $user_ID);
                                        $couponcodeuserlogin = $getinfousernickname->user_login;
                                        $array = $woocommerce->cart->get_applied_coupons();
                                        $auto_redeem_name = 'auto_redeem_' . strtolower($couponcodeuserlogin);
                                        foreach ($woocommerce->cart->cart_contents as $item) {
                                            $product_id = $item['variation_id'] != 0 ? $item['variation_id'] : $item['product_id'];
                                            $type[] = self::check_display_price_type($product_id);
                                            $enable = self::calculate_point_price_for_products($product_id);
                                            if ($enable[$product_id] != '') {
                                                $cart_object[] = $enable[$product_id];
                                            }
                                        }
                                        if (empty($cart_object)) {
                                            if (!in_array(2, $type)) {
                                                if (!in_array($auto_redeem_name, $array)) {
                                                    ?>

                                                    <form method="post">
                                                        <div class="woocommerce-info"><?php echo $redeem_button_message_replaced_third; ?>
                                                            <input id="rs_apply_coupon_code_field" class="input-text" type="hidden"  value="<?php echo $points_for_redeeming; ?> " name="rs_apply_coupon_code_field">
                                                            <input class="<?php echo get_option('rs_extra_class_name_apply_reward_points'); ?>" type="submit" id='mainsubmi' value="<?php echo get_option('rs_redeem_field_submit_button_caption'); ?>" name="rs_apply_coupon_code1">
                                                        </div>
                                                    </form>
                                                    <?php
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    } else {
                        if ($user_current_points >= get_option("rs_minimum_user_points_to_redeem")) {
                            if (get_option('rs_show_hide_redeem_field') == '1') {
                                if (get_option('rs_redeem_field_type_option') == '2') {
                                    $getuserid = get_current_user_id();
                                    $user_current_points = RSPointExpiry::get_sum_of_total_earned_points($getuserid);
                                   if (get_option('woocommerce_prices_include_tax') == 'yes') {
                                        if (get_option('woocommerce_tax_display_cart') == 'incl') {
                                            $current_carttotal_amount = $woocommerce->cart->subtotal;
                                        } else {
                                            $current_carttotal_amount = $woocommerce->cart->subtotal_ex_tax;
                                        }
                                    } else {
                                        if (get_option('woocommerce_tax_display_cart') == 'incl') {
                                            $current_carttotal_amount = $woocommerce->cart->subtotal;
                                        } else {
                                            $current_carttotal_amount = $woocommerce->cart->subtotal_ex_tax;
                                        }
                                    }
                                    $limitation_percentage_for_redeeming = get_option('rs_percentage_cart_total_redeem');
                                    $cart_total_in_amount = $limitation_percentage_for_redeeming / 100;
                                    $updated_cart_total_in_amount = $cart_total_in_amount * $current_carttotal_amount;
                                    $redeem_conversion = wc_format_decimal(get_option('rs_redeem_point'));
                                    $points_conversion_value = wc_format_decimal(get_option('rs_redeem_point_value'));
                                    $points_for_redeem_in_points = $updated_cart_total_in_amount * $redeem_conversion;
                                    $updated_points_for_redeeming = $points_for_redeem_in_points / $points_conversion_value;
                                    $cartpoints_string_to_replace = "[cartredeempoints]";
                                    $currency_symbol_string_to_find = "[currencysymbol]";
                                    $cuurency_value_string_to_find = "[pointsvalue]";
                                    if ($user_current_points >= $updated_points_for_redeeming) {
                                        $redeem_button_message_more = get_option('rs_redeeming_button_option_message');
                                        $cuurency_value_string_to_find = "[pointsvalue]";
                                        $points_conversion_value = wc_format_decimal(get_option('rs_redeem_point_value'));
                                        $points_currency_value = $updated_cart_total_in_amount;
                                        $points_currency_amount_to_replace = $updated_points_for_redeeming;
                                        $points_for_redeeming = $updated_points_for_redeeming;
                                        if (get_option('rs_apply_redeem_basedon_cart_or_product_total') == '2') {
                                            if ($points_for_include_product != '') {
                                                $limitation_percentage_for_redeeming = get_option('rs_percentage_cart_total_redeem');
                                                $cart_total_in_amount = $limitation_percentage_for_redeeming / 100;
                                                $updated_cart_total_in_amount = $cart_total_in_amount * $points_for_include_product_sum;
                                                $points_for_include_product = $redeem_conversion * $updated_cart_total_in_amount;
                                                $points_for_redeeming = $points_for_include_product / $points_conversion_value;
                                                $points_currency_value = $updated_cart_total_in_amount;
                                            }
                                        }
                                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                        $points_for_redeeming = round($points_for_redeeming, $roundofftype);
                                        $redeem_button_message_more = get_option('rs_redeeming_button_option_message');
                                        $currency_symbol_string_to_replace = self::get_woocommerce_formatted_price($points_currency_value);

                                        $redeem_button_message_replaced_first = str_replace($cuurency_value_string_to_find, $currency_symbol_string_to_replace, $redeem_button_message_more);
                                        $redeem_button_message_replaced_second = str_replace($currency_symbol_string_to_find, "", $redeem_button_message_replaced_first);
                                        $redeem_button_message_replaced_third = str_replace($cartpoints_string_to_replace, $points_for_redeeming, $redeem_button_message_replaced_second);
                                    } else {
                                        $points_for_redeeming = $user_current_points;
                                        if (get_option('rs_apply_redeem_basedon_cart_or_product_total') == '2') {
                                            if ($points_for_include_product != '') {
                                                $points_for_redeeming1 = $points_for_include_product / $points_conversion_value;
                                                if ($user_current_points > $points_for_redeeming1) {
                                                    $points_for_redeeming = $points_for_redeeming1;
                                                    $points_currency_value = $getsumofselectedproduct;
                                                    $limitation_percentage_for_redeeming = get_option('rs_percentage_cart_total_redeem');
                                                    $cart_total_in_amount = $limitation_percentage_for_redeeming / 100;
                                                    $updated_cart_total_in_amount = $cart_total_in_amount * $points_for_include_product_sum;

                                                    $points_for_include_product = $redeem_conversion * $updated_cart_total_in_amount;
                                                    $points_for_redeeming = $points_for_include_product / $points_conversion_value;
                                                    $points_currency_value = $updated_cart_total_in_amount;
                                                } else {
                                                    
                                                }
                                            }
                                        }
                                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                        $points_for_redeeming = round($points_for_redeeming, $roundofftype);

                                        $redeem_button_message_more = get_option('rs_redeeming_button_option_message_checkout');
                                        $points_conversion_value = wc_format_decimal(get_option('rs_redeem_point_value'));
                                        $points_currency_value = $points_for_redeeming / $redeem_conversion;
                                        $points_currency_amount_to_replace = $points_currency_value * $points_conversion_value;
                                        $currency_symbol_string_to_replace = self::get_woocommerce_formatted_price($points_currency_value);
                                        $redeem_button_message_replaced_first = str_replace($currency_symbol_string_to_find, "", $redeem_button_message_more);
                                        $redeem_button_message_replaced_second = str_replace($cuurency_value_string_to_find, $currency_symbol_string_to_replace, $redeem_button_message_replaced_first);
                                        $redeem_button_message_replaced_third = str_replace($cartpoints_string_to_replace, $points_for_redeeming, $redeem_button_message_replaced_second);
                                    }
                                    $minimum_cart_total_redeem = get_option('rs_minimum_cart_total_points');
                                    if (get_option('woocommerce_prices_include_tax') === 'yes') {
                                        $cart_subtotal_redeem_amount = $woocommerce->cart->subtotal_ex_tax;
                                    } else {
                                        $cart_subtotal_redeem_amount = $woocommerce->cart->subtotal;
                                    }
                                    if ($cart_subtotal_redeem_amount >= $minimum_cart_total_redeem) {
                                        $user_ID = get_current_user_id();
                                        $getinfousernickname = get_user_by('id', $user_ID);
                                        $couponcodeuserlogin = $getinfousernickname->user_login;
                                        $auto_redeem_name = 'auto_redeem_' . strtolower($couponcodeuserlogin);
                                        $array = $woocommerce->cart->get_applied_coupons();
                                        foreach ($woocommerce->cart->cart_contents as $item) {
                                            $product_id = $item['variation_id'] != 0 ? $item['variation_id'] : $item['product_id'];
                                            $type[] = self::check_display_price_type($product_id);
                                            $enable = self::calculate_point_price_for_products($product_id);
                                            if ($enable[$product_id] != '') {
                                                $cart_object[] = $enable[$product_id];
                                            }
                                        }
                                        if (get_option('woocommerce_prices_include_tax') == 'yes') {
                                            if (get_option('woocommerce_tax_display_cart') == 'excl') {
                                                $points_for_redeeming = $woocommerce->cart->subtotal;
                                            }
                                        }
                                        if (empty($cart_object)) {
                                            if (!in_array(2, $type)) {
                                                if (!in_array($auto_redeem_name, $array)) {
                                                   
                                                    ?>
                                                    <form method="post">                                                     
                                                        <div class="woocommerce-info sumo_reward_points_cart_apply_discount"><?php echo $redeem_button_message_replaced_third; ?>
                                                            <input id="rs_apply_coupon_code_field" class="input-text" type="hidden"  value="<?php echo $points_for_redeeming; ?> " name="rs_apply_coupon_code_field">
                                                            <input class="<?php echo get_option('rs_extra_class_name_apply_reward_points'); ?>" type="submit" id='mainsubmi' value="<?php echo get_option('rs_redeem_field_submit_button_caption'); ?>" name="rs_apply_coupon_code1" />
                                                            <div class='rs_warning_message' style='display:inline-block;color:red'></div>
                                                        </div>
                                                    </form>
                                                    <?php
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }
    }

    public static function change_coupon_label($link, $coupon) {
        $userid = get_current_user_id();
        $banning_type = FPRewardSystem::check_banning_type($userid);
        if ($banning_type != 'earningonly' && $banning_type != 'both') {
            $couponcode = $coupon->code;
            if (is_string($coupon))
                $coupon = new WC_Coupon($coupon);
            $user_ID = get_current_user_id();
            $getinfousernickname = get_user_by('id', $user_ID);
            $couponcodeuserlogin = $getinfousernickname->user_login;
            if (strtolower($couponcode) == 'sumo_' . strtolower($couponcodeuserlogin)) {
                $newcoupon = get_option('rs_coupon_label_message');
                $link = ' ' . $newcoupon;
            }
        }
        return $link;
    }

    public static function change_auto_coupon_label($link, $coupon) {
        $userid = get_current_user_id();


        $couponcode = $coupon->code;
        if (is_string($coupon))
            $coupon = new WC_Coupon($coupon);
        $user_ID = get_current_user_id();
        $getinfousernickname = get_user_by('id', $user_ID);
        $couponcodeuserlogin = $getinfousernickname->user_login;
        if (strtolower($couponcode) == 'auto_redeem_' . strtolower($couponcodeuserlogin)) {
            $newcoupon = get_option('rs_coupon_label_message');
            $link = ' ' . $newcoupon;
        }

        return $link;
    }

}

new RSFunctionForCart();
