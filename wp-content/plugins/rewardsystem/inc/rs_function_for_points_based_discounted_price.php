<?php

class RSDiscountedPointsCalculation {

    public function __construct() {
        add_action('wp_footer', array($this, 'modified_count'));
    }

    public static function coupon_validator($product_id) {

        global $woocommerce;
        $selected_products = '';
        $discount_coupon = $woocommerce->cart->coupon_discount_amounts;

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
                        $selected_products[$code][$product_id] = $count_of_products > 1 ? $discount_coupon[$code] / $count_of_products : $discount_coupon[$code];
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
                        $selected_products[$code][$product_id] = $count_of_products > 1 ? $discount_coupon[$code] / $count_of_products : $discount_coupon[$code];
                        WC()->session->set('current_count', $count_of_products);
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

                        $selected_products[$code][$product_id] = $count_of_products > 1 ? $discount_coupon[$code] / $count_of_products : $discount_coupon[$code];
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

                        $selected_products[$code][$product_id] = $count_of_products > 1 ? $discount_coupon[$code] / $count_of_products : $discount_coupon[$code];
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

                        $selected_products[$code][$product_id] = $count_of_products > 1 ? $discount_coupon[$code] / $count_of_products : $discount_coupon[$code];
                    }
                }
            }
            // }
        }
        return $selected_products;
    }

    public static function coupon_included_products($product_ids, $coupon_code) {
        global $woocommerce;
        foreach ($woocommerce->cart->cart_contents as $cart_details) {
            $product_id = $cart_details['product_id'] != '' ? $cart_details['product_id'] : $cart_details['variation_id'];
            if (in_array($product_id, $product_ids)) {

                $coupon_product_ids[] = $cart_details['line_subtotal'];
            }
        }
        $coupon_product_ids = array_sum($coupon_product_ids);

        return $coupon_product_ids;
    }

    public static function get_product_price_in_cart() {
        global $woocommerce;
        $price = array();
        foreach ($woocommerce->cart->cart_contents as $key => $value) {

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




            $user_ID = get_current_user_id();
            $checkproduct = get_product($value['product_id']);
            if (is_object($checkproduct) && $checkproduct->is_type('booking')) {
                $getregularprice = $value['data']->price;
            }

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
                                                        $pointconversion = get_option('rs_earn_point');
                                                        $pointconversionvalue = get_option('rs_earn_point_value');
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
                                            $pointconversion = get_option('rs_earn_point');
                                            $pointconversionvalue = get_option('rs_earn_point_value');
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
                                                        $pointconversion = get_option('rs_earn_point');
                                                        $pointconversionvalue = get_option('rs_earn_point_value');
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
                                                $pointconversion = get_option('rs_earn_point');
                                                $pointconversionvalue = get_option('rs_earn_point_value');
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
                                        $pointconversion = get_option('rs_earn_point');
                                        $pointconversionvalue = get_option('rs_earn_point_value');
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
                        $pointconversion = get_option('rs_earn_point');
                        $pointconversionvalue = get_option('rs_earn_point_value');
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
                                                        $pointconversion = get_option('rs_earn_point');
                                                        $pointconversionvalue = get_option('rs_earn_point_value');
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
                                            $pointconversion = get_option('rs_earn_point');
                                            $pointconversionvalue = get_option('rs_earn_point_value');
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
                                                        $pointconversion = get_option('rs_earn_point');
                                                        $pointconversionvalue = get_option('rs_earn_point_value');
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
                                                $pointconversion = get_option('rs_earn_point');
                                                $pointconversionvalue = get_option('rs_earn_point_value');
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
                                        $pointconversion = get_option('rs_earn_point');
                                        $pointconversionvalue = get_option('rs_earn_point_value');
                                        $getaverage = get_option('rs_global_reward_percent') / 100;
                                        $getaveragepoints = $getaverage * $getregularprice;
                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            }
                            $points = max($rewardpoints);
                        }

                        $getappliedcoupon = $woocommerce->cart->get_applied_coupons();


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
                if ($checkenablevariation == '1') {
                    $variable_product1 = new WC_Product_Variation($value['variation_id']);
                    if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                        $variationregularprice = $variable_product1->regular_price != '' ? $variable_product1->regular_price : $variable_product1->price;
                    } else {
                        $variationregularprice = $variable_product1->price != '' ? $variable_product1->price : $variable_product1->regular_price;
                        do_action_ref_array('rs_price_rule_checker_variant', array(&$variationregularprice, &$value));
                    }
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
                                                        $pointconversion = get_option('rs_earn_point');
                                                        $pointconversionvalue = get_option('rs_earn_point_value');
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
                                            $pointconversion = get_option('rs_earn_point');
                                            $pointconversionvalue = get_option('rs_earn_point_value');
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
                                                        $pointconversion = get_option('rs_earn_point');
                                                        $pointconversionvalue = get_option('rs_earn_point_value');
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
                                                $pointconversion = get_option('rs_earn_point');
                                                $pointconversionvalue = get_option('rs_earn_point_value');
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
                                        $pointconversion = get_option('rs_earn_point');
                                        $pointconversionvalue = get_option('rs_earn_point_value');
                                        $getaverage = get_option('rs_global_reward_percent') / 100;
                                        $getaveragepoints = $getaverage * $variationregularprice;
                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            }
                            $variablerewardpoints = max($rewardpoints);
                        }
                        $getappliedcoupon = $woocommerce->cart->get_applied_coupons();


                        $totalrewardpoints = RSMemberFunction::user_role_based_reward_points(get_current_user_id(), $variablerewardpoints) * $cartquantity;
                        $totalrewardpointsnew[$value['variation_id']] = $totalrewardpoints;
                    } else {
                        $pointconversion = get_option('rs_earn_point');
                        $pointconversionvalue = get_option('rs_earn_point_value');
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
                                                        $pointconversion = get_option('rs_earn_point');
                                                        $pointconversionvalue = get_option('rs_earn_point_value');
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
                                            $pointconversion = get_option('rs_earn_point');
                                            $pointconversionvalue = get_option('rs_earn_point_value');
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
                                                        $pointconversion = get_option('rs_earn_point');
                                                        $pointconversionvalue = get_option('rs_earn_point_value');
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
                                                $pointconversion = get_option('rs_earn_point');
                                                $pointconversionvalue = get_option('rs_earn_point_value');
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
                                        $pointconversion = get_option('rs_earn_point');
                                        $pointconversionvalue = get_option('rs_earn_point_value');
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

        $totalprice = array_sum($price);

        return $totalprice;
    }

    public static function coupon_points_conversion($product_id, $points) {
        $coupon_amounts = self::coupon_validator($product_id);

        $newpoints = $points;
        $conversions = array();
        if (!empty($coupon_amounts) && is_array($coupon_amounts)) {
            foreach ($coupon_amounts as $key => $value) {

                if ($newpoints > 0) {
                    $c_amount = $value[$product_id];
                    $coupon = new WC_Coupon($key);

                    $selectedproduct = $coupon->product_ids;

                    if (!empty($selectedproduct)) {
                        $conversion = $c_amount / self::coupon_included_products($selectedproduct, $key);
                    } else {
                        $conversion = $c_amount / self::get_product_price_in_cart();
                    }
                    $conversion = $conversion * $newpoints;
                    if ($newpoints > $conversion) {
                        $conversions[] = $newpoints - $conversion;
                    } else {
                        $conversions[] = 0;
                    }
                    if ($newpoints > $conversion) {
                        $newpoints = $newpoints - $conversion;
                    } else {
                        $newpoints = 0;
                    }
                }
            }

            return end($conversions);
        }

        return $newpoints;
    }

    public static function moified_points_for_products_in_cart() {
        global $woocommerce;

        $modified_points_updated = array();
        $original_points_array = RSFunctionForCart::original_points_for_product_in_cart();
        if (!empty($original_points_array)) {
            foreach ($original_points_array as $product_id => $points) {
                $modified_points = self::coupon_points_conversion($product_id, $points);

                if ($modified_points != 0) {
                    $modified_points_updated[$product_id] = $modified_points;
                }
            }
        }

        return $original_points_array;
    }

    public static function moified_points_count_in_cart() {
        $count = count(self::moified_points_for_products_in_cart());
        WC()->session->__unset('modified_count');

        return $count;
    }

    public static function modified_count() {
        global $woocommerce;
        $modified_count = self::moified_points_count_in_cart();

        if ($modified_count > 0) {
            $previous_value = WC()->session->get('modified_count');
            $original_points_count = count(RSFunctionForCart::original_points_for_product_in_cart());
            $updated_count = $previous_value != null ? $previous_value : $original_points_count;
            WC()->session->set('modified_count', $updated_count);
        }
    }

}

new RSDiscountedPointsCalculation();
