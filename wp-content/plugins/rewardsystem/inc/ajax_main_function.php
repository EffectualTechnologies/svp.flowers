<?php

class RSAjaxMainFunction {

    public static function update_earning_points_for_user($order_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        $table_name2 = $wpdb->prefix . 'rsrecordpoints';
        global $woocommerce;
        $termid = '';
        $order = new WC_Order($order_id);
        $restrictuserpoints = get_option('rs_max_earning_points_for_user');
        $enabledisablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
        $noofdays = get_option('rs_point_to_be_expire');

        if (($noofdays != '0') && ($noofdays != '')) {
            $date = time() + ($noofdays * 24 * 60 * 60);
        } else {
            $date = '999999999999';
        }
        $pointsredeemed = '';
        /* Reward Points For Using Payment Gateway Method */
        $banning_type = FPRewardSystem::check_banning_type($order->user_id);
        if ($banning_type != 'earningonly' && $banning_type != 'both') {
            $checkredeeming = false;
            $enableoption = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, 'rs_check_enable_option_for_redeeming');
            if ($enableoption == 'yes') {
                if ($checkredeeming == false) {
                    $getpaymentgatewayused = RSMemberFunction::user_role_based_reward_points($order->user_id, get_option('rs_reward_payment_gateways_' . $order->payment_method));
                    if ($getpaymentgatewayused != '') {
                        if ($enabledisablemaxpoints == 'yes') {
                            if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                                if ($getoldpoints <= $restrictuserpoints) {
                                    $totalpointss = $getoldpoints + $getpaymentgatewayused;
                                    if ($totalpointss <= $restrictuserpoints) {
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($getpaymentgatewayused);
                                        RSPointExpiry::insert_earning_points($order->user_id, $getpaymentgatewayused, $pointsredeemed, $date, 'RPG', $order_id, '0', '0', '');
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                                        RSPointExpiry::record_the_points($order->user_id, $getpaymentgatewayused, '0', $date, 'RPG', $equearnamt, '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                    } else {
                                        $insertpoints = $restrictuserpoints - $getoldpoints;
                                        RSPointExpiry::insert_earning_points($order->user_id, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                        $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                                        RSPointExpiry::record_the_points($order->user_id, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                    }
                                } else {
                                    RSPointExpiry::insert_earning_points($order->user_id, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                                    RSPointExpiry::record_the_points($order->user_id, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                $equearnamt = RSPointExpiry::earning_conversion_settings($getpaymentgatewayused);
                                RSPointExpiry::insert_earning_points($order->user_id, $getpaymentgatewayused, $pointsredeemed, $date, 'RPG', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                                RSPointExpiry::record_the_points($order->user_id, $getpaymentgatewayused, '0', $date, 'RPG', $equearnamt, '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $equearnamt = RSPointExpiry::earning_conversion_settings($getpaymentgatewayused);
                            RSPointExpiry::insert_earning_points($order->user_id, $getpaymentgatewayused, $pointsredeemed, 'RPG', $order_id, '0', '0', '');
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                            RSPointExpiry::record_the_points($order->user_id, $getpaymentgatewayused, '0', $date, 'RPG', $equearnamt, '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                        }
                    }
                }
            } else {
                $getpaymentgatewayused = RSMemberFunction::user_role_based_reward_points($order->user_id, get_option('rs_reward_payment_gateways_' . $order->payment_method));
                if ($getpaymentgatewayused != '') {
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $getpaymentgatewayused;
                                if ($totalpointss <= $restrictuserpoints) {
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($getpaymentgatewayused);
                                    RSPointExpiry::insert_earning_points($order->user_id, $getpaymentgatewayused, $pointsredeemed, $date, 'RPG', $order_id, '0', '0', '');
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                                    RSPointExpiry::record_the_points($order->user_id, $getpaymentgatewayused, '0', $date, 'RPG', $equearnamt, '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($order->user_id, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                                    RSPointExpiry::record_the_points($order->user_id, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($order->user_id, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                                RSPointExpiry::record_the_points($order->user_id, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            $equearnamt = RSPointExpiry::earning_conversion_settings($getpaymentgatewayused);
                            RSPointExpiry::insert_earning_points($order->user_id, $getpaymentgatewayused, $pointsredeemed, $date, 'RPG', $order_id, '0', '0', '');
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                            RSPointExpiry::record_the_points($order->user_id, $getpaymentgatewayused, '0', $date, 'RPG', $equearnamt, '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                        }
                    } else {
                        $equearnamt = RSPointExpiry::earning_conversion_settings($getpaymentgatewayused);
                        RSPointExpiry::insert_earning_points($order->user_id, $getpaymentgatewayused, $pointsredeemed, $date, 'RPG', $order_id, '0', '0', '');
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                        RSPointExpiry::record_the_points($order->user_id, $getpaymentgatewayused, '0', $date, 'RPG', $equearnamt, '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                    }
                }
            }
        }

        /* Reward Points For Purchasing the Product */
        foreach ($order->get_items() as $item) {
            $banning_type = FPRewardSystem::check_banning_type($order->user_id);
            if ($banning_type != 'earningonly' && $banning_type != 'both') {
                $productid = $item['product_id'];
                $variationid = $item['variation_id'] == '0' || '' ? '0' : $item['variation_id'];
                $itemquantity = $item['qty'];
                $orderuserid = $order->user_id;
                $term = get_the_terms($productid, 'product_cat');
                if (is_array($term)) {
                    foreach ($term as $terms) {
                        $termid = $terms->term_id;
                    }
                }
                //For Inserting Reward Points
                $checked_level_for_reward_points = RSPointExpiry::check_level_of_enable_reward_point($productid, $variationid, $termid);
                $equearnamt = '';
                $equredeemamt = '';
                self::rs_insert_the_selected_level_in_reward_points($pointsredeemed, $checked_level_for_reward_points, $productid, $variationid, $itemquantity, $orderuserid, $termid, $equearnamt, $equredeemamt, $order_id, $item);
                //$wpdb->query("UPDATE $table_name SET totalearnedpoints = $totalearnedpoints,totalredeempoints = $totalredeempoints WHERE orderid = $order_id");

                $referreduser = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, '_referrer_name');
                if ($referreduser != '') {
                    //For Inserting Referral Reward Points
                    $checked_level_for_referral_reward_points = RSPointExpiry::check_level_of_enable_referral_reward_point($productid, $variationid, $termid);
                    self::rs_insert_the_selected_level_in_referral_reward_points($pointsredeemed, $referreduser, $equearnamt, $equredeemamt, $checked_level_for_referral_reward_points, $productid, $variationid, $itemquantity, $orderuserid, $termid, $order_id, '', '', '', $item);
                } else {
                    $referrer_id = RSFunctionForManualReferralLink::rs_perform_manual_link_referer($order->user_id);
                    if ($referrer_id != false) {
                        $checked_level_for_referral_reward_points = RSPointExpiry::check_level_of_enable_referral_reward_point($productid, $variationid, $termid);
                        self::rs_insert_the_selected_level_in_referral_reward_points($pointsredeemed, $referrer_id, $equearnamt, $equredeemamt, $checked_level_for_referral_reward_points, $productid, $variationid, $itemquantity, $orderuserid, $termid, $order_id, '', '', '', $item);
                    }
                }
            }
        }
        update_user_meta($order->user_id, 'rsfirsttime_redeemed', 1);
    }

    /* Function For Checking in Which level Reward points is Awarded */

    public static function check_level_of_enable_reward_point($productid, $variationid, $termid) {
        global $post;

        //Product Level
        $productlevel = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_rewardsystemcheckboxvalue') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_enable_reward_points');
        $productlevelrewardtype = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_rewardsystem_options') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_select_reward_rule');
        $productlevelrewardpoints = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_rewardsystempoints') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_reward_points');
        $productlevelrewardpercent = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_rewardsystempercent') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_reward_percent');

        //Category Level
        $categorylist = wp_get_post_terms($productid, 'product_cat');
        $getcount = count($categorylist);
        $categorylevel = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'enable_reward_system_category');
        $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'enable_rs_rule');
        $categorylevelrewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'rs_category_points');
        $categorylevelrewardpercent = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'rs_category_percent');

        //Global Level
        $global_enable = get_option('rs_global_enable_disable_sumo_reward');
        $global_reward_type = get_option('rs_global_reward_type');
        $global_rewardpoints = get_option('rs_global_reward_points');
        $global_rewardpercent = get_option('rs_global_reward_percent');

        if (($productlevel == 'yes') || ($productlevel == '1')) {
            if ($productlevelrewardtype == '1') {
                if ($productlevelrewardpoints != '') {
                    return '1';
                } else {
                    if ($getcount >= '1') {
                        if (($categorylevel == 'yes') || ($categorylevel != '')) {
                            if (($categorylevelrewardtype == '1')) {
                                if ($categorylevelrewardpoints != '') {
                                    return '2';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_rewardpoints != '') {
                                                return '2';
                                            }
                                        } else {
                                            if ($global_rewardpercent != '') {
                                                return '2';
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($categorylevelrewardpercent != '') {
                                    return '2';
                                }
                            }
                        } else {
                            if ($global_enable == '1') {
                                if ($global_reward_type == '1') {
                                    if ($global_rewardpoints != '') {
                                        return '2';
                                    }
                                } else {
                                    if ($global_rewardpercent != '') {
                                        return '2';
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                if ($global_rewardpoints != '') {
                                    return '3';
                                }
                            } else {
                                if ($global_rewardpercent != '') {
                                    return '3';
                                }
                            }
                        }
                    }
                }
            } else {
                if ($productlevelrewardpercent != '') {
                    return '1';
                } else {
                    if ($getcount >= '1') {
                        if (($categorylevel == 'yes') || ($categorylevel != '')) {
                            if (($categorylevelrewardtype == '1')) {
                                if ($categorylevelrewardpoints != '') {
                                    return '2';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_rewardpoints != '') {
                                                return '3';
                                            }
                                        } else {
                                            if ($global_rewardpercent != '') {
                                                return '3';
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($categorylevelrewardpercent != '') {
                                    return '2';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_rewardpoints != '') {
                                                return '3';
                                            }
                                        } else {
                                            if ($global_rewardpercent != '') {
                                                return '3';
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($global_enable == '1') {
                                if ($global_reward_type == '1') {
                                    if ($global_rewardpoints != '') {
                                        return '3';
                                    }
                                } else {
                                    if ($global_rewardpercent != '') {
                                        return '3';
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                if ($global_rewardpoints != '') {
                                    return '3';
                                }
                            } else {
                                if ($global_rewardpercent != '') {
                                    return '3';
                                }
                            }
                        }
                    }
                }
            }
        } else {
            return '0';
        }
    }

    /* Function to insert the earned reward points to db */

    public static function rs_insert_the_selected_level_in_reward_points($pointsredeemed, $level, $productid, $variationid, $itemquantity, $orderuserid, $termid, $equearnamt, $equredeemamt, $order_id, $item) {

        if ($variationid != ('0' || '')) {
            $variable_product1 = new WC_Product_Variation($variationid);
            if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                $getregularprice = $variable_product1->regular_price == '' ? $variable_product1->price : $variable_product1->regular_price;
            } else {
                $getregularprice = $variable_product1->price == '' ? $variable_product1->regular_price : $variable_product1->price;
            }
            do_action_ref_array('rs_update_points_for_variable', array(&$getregularprice, &$item));
        } else {
            if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_regular_price');
                if ($getregularprice == '') {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_price');
                }
            } else {
                $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_price');
                if ($getregularprice == '') {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_regular_price');
                }
            }
            do_action_ref_array('rs_update_points_for_simple', array(&$getregularprice, &$item));
        }


        $noofdays = get_option('rs_point_to_be_expire');

        if (($noofdays != '0') && ($noofdays != '')) {
            $date = time() + ($noofdays * 24 * 60 * 60);
        } else {
            $date = '999999999999';
        }

        $productlevelrewardtype = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_rewardsystem_options') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_select_reward_rule');
        $productlevelrewardpoints = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_rewardsystempoints') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_reward_points');
        $rewardpercentforproductlevel = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_rewardsystempercent') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_reward_percent');
        $pointforconversion = RSFunctionofGeneralTab::earn_point_conversion();
        ;
        $pointforconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
        $rewardpercentforproductlevel = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_rewardsystempercent') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_reward_percent');
        $rewardpercentpl = $rewardpercentforproductlevel / 100;
        $getaveragepoints = $rewardpercentpl * $getregularprice;
        $pointswithvalue = $getaveragepoints * $pointforconversion;
        $productlevelrewardpercent = $pointswithvalue / $pointforconversionvalue;

        if (($productid != '') || ($variationid != '') || ($variationid != '0')) {
            $rewardpoints = array('0');
            $rewardpercent = array('0');
            $categorylist = wp_get_post_terms($productid, 'product_cat');
            $getcount = count($categorylist);
            $term = get_the_terms($productid, 'product_cat');
            if (is_array($term)) {
                foreach ($term as $terms) {
                    $termid = $terms->term_id;
                    if ($getcount > 1) {
                        $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'enable_rs_rule');
                        $rewardpointsforcategory = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'rs_category_points');
                        if ($rewardpointsforcategory == '') {
                            $rewardpoints[] = get_option('rs_global_reward_points');
                        } else {
                            $rewardpoints[] = $rewardpointsforcategory;
                        }
                        $rewardpercentforcategory = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'rs_category_percent');
                        if ($rewardpercentforcategory == '') {
                            $pointforconversion = RSFunctionofGeneralTab::earn_point_conversion();
                            ;
                            $pointforconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                            $get_global_rewardpercent = get_option('rs_global_reward_percent') / 100;
                            $getaveragepoints = $get_global_rewardpercent * $getregularprice;
                            $pointswithvalue = $getaveragepoints * $pointforconversion;
                            $rewardpercent[] = $pointswithvalue / $pointforconversionvalue;
                        } else {
                            $pointforconversion = RSFunctionofGeneralTab::earn_point_conversion();
                            ;
                            $pointforconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                            $rewardpercentforcategory = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'rs_category_percent') / 100;
                            $getaveragepoints = $rewardpercentforcategory * $getregularprice;
                            $pointswithvalue = $getaveragepoints * $pointforconversion;
                            $rewardpercent[] = $pointswithvalue / $pointforconversionvalue;
                        }
                    } else {
                        $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'enable_rs_rule');
                        $rewardpointsforcategory = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'rs_category_points');
                        if ($rewardpointsforcategory == '') {
                            $rewardpoints[] = get_option('rs_global_reward_points');
                        } else {
                            $rewardpoints[] = $rewardpointsforcategory;
                        }
                        $rewardpercentforcategory = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'rs_category_percent');
                        if ($rewardpercentforcategory == '') {
                            $pointforconversion = RSFunctionofGeneralTab::earn_point_conversion();
                            ;
                            $pointforconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                            $get_global_rewardpercent = get_option('rs_global_reward_percent') / 100;
                            $getaveragepoints = $get_global_rewardpercent * $getregularprice;
                            $pointswithvalue = $getaveragepoints * $pointforconversion;
                            $rewardpercent[] = $pointswithvalue / $pointforconversionvalue;
                        } else {
                            $pointforconversion = RSFunctionofGeneralTab::earn_point_conversion();
                            ;
                            $pointforconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                            $rewardpercentforcategory = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'rs_category_percent') / 100;
                            $getaveragepoints = $rewardpercentforcategory * $getregularprice;
                            $pointswithvalue = $getaveragepoints * $pointforconversion;
                            $rewardpercent[] = $pointswithvalue / $pointforconversionvalue;
                        }
                    }
                }
            }
            $categorylevelrewardpoints = max($rewardpoints);
            $categorylevelrewardpercent = max($rewardpercent);
        }
        $global_reward_type = get_option('rs_global_reward_type');
        $global_rewardpoints = get_option('rs_global_reward_points');
        $pointforconversion = RSFunctionofGeneralTab::earn_point_conversion();
        ;
        $pointforconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
        $get_global_rewardpercent = get_option('rs_global_reward_percent') / 100;
        $getaveragepoints = $get_global_rewardpercent * $getregularprice;
        $pointswithvalue = $getaveragepoints * $pointforconversion;
        $global_rewardpercent = $pointswithvalue / $pointforconversionvalue;
        $totalearnedpoints = '0';
        $totalredeempoints = '0';
        $refuserid = '';
        $refuserid = $refuserid != '' ? $refuserid : 0;
        $checkredeeming = false;
        $enableoption = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, 'rs_check_enable_option_for_redeeming');
        $restrictuserpoints = get_option('rs_max_earning_points_for_user');
        $enabledisablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
        $getnomineeid = get_user_meta(get_current_user_id(), 'rs_selected_nominee', true);
        if ($getnomineeid == '') {
            switch ($level) {
                case '1':
                    if ($productlevelrewardtype == '1') {
                        if ($enableoption == 'yes') {
                            if ($checkredeeming == false) {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $productlevelrewardpoints;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                                RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpointss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpointss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpointss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                }
                            }
                        } else {
                            if ($enabledisablemaxpoints == 'yes') {
                                if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                    $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    if ($getoldpoints <= $restrictuserpoints) {
                                        $totalpointss = $getoldpoints + $productlevelrewardpoints;
                                        if ($totalpointss <= $restrictuserpoints) {
                                            $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                            RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpointss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                        } else {
                                            $insertpoints = $restrictuserpoints - $getoldpoints;
                                            RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                            $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpointss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                }
                            } else {
                                $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpointss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                            }
                        }
                    } else {
                        if ($enableoption == 'yes') {
                            if ($checkredeeming == false) {

                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $productlevelrewardpercent;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                                RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpercentss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpercentss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpercentss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                }
                            }
                        } else {
                            if ($enabledisablemaxpoints == 'yes') {
                                if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                    $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    if ($getoldpoints <= $restrictuserpoints) {
                                        $totalpointss = $getoldpoints + $productlevelrewardpercent;
                                        if ($totalpointss <= $restrictuserpoints) {
                                            $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                            RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpercentss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                        } else {
                                            $insertpoints = $restrictuserpoints - $getoldpoints;
                                            RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                            $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpercentss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                }
                            } else {
                                $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpercentss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                            }
                        }
                    }
                    break;
                case '2':
                    if ($categorylevelrewardtype == '1') {
                        if ($enableoption == 'yes') {
                            if ($checkredeeming == false) {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $categorylevelrewardpoints;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                                RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpointss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpointss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpointss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                }
                            }
                        } else {

                            if ($enabledisablemaxpoints == 'yes') {
                                if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                    $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    if ($getoldpoints <= $restrictuserpoints) {
                                        $totalpointss = $getoldpoints + $categorylevelrewardpoints;
                                        if ($totalpointss <= $restrictuserpoints) {
                                            $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                            RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpointss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                        } else {
                                            $insertpoints = $restrictuserpoints - $getoldpoints;
                                            RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                            $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpointss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                }
                            } else {
                                $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpointss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                            }
                        }
                    } else {
                        if ($enableoption == 'yes') {
                            if ($checkredeeming == false) {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $categorylevelrewardpercent;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                                RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpercents, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpercents, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpercents, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                }
                            }
                        } else {
                            if ($enabledisablemaxpoints == 'yes') {
                                if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                    $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    if ($getoldpoints <= $restrictuserpoints) {
                                        $totalpointss = $getoldpoints + $categorylevelrewardpercent;
                                        if ($totalpointss <= $restrictuserpoints) {
                                            $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                            RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpercents, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                        } else {
                                            $insertpoints = $restrictuserpoints - $getoldpoints;
                                            RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                            $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpercents, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                }
                            } else {
                                $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpercents, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                            }
                        }
                    }
                    break;
                case '3':
                    if ($global_reward_type == '1') {
                        if ($enableoption == 'yes') {
                            if ($checkredeeming == false) {

                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $global_rewardpoints;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                                RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpointss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $global_rewardpointss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpointss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, $global_rewardpointss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpointss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $global_rewardpointss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                }
                            }
                        } else {
                            if ($enabledisablemaxpoints == 'yes') {
                                if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                    $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    if ($getoldpoints <= $restrictuserpoints) {
                                        $totalpointss = $getoldpoints + $global_rewardpoints;
                                        if ($totalpointss <= $restrictuserpoints) {
                                            $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                            RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpointss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $global_rewardpointss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                        } else {
                                            $insertpoints = $restrictuserpoints - $getoldpoints;
                                            RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                            $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpointss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $global_rewardpointss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                }
                            } else {
                                $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpointss, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                RSPointExpiry::record_the_points($orderuserid, $global_rewardpointss, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                            }
                        }
                    } else {
                        if ($enableoption == 'yes') {
                            if ($checkredeeming == false) {

                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $global_rewardpercent;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercents) * $itemquantity;
                                                RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpercents, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $global_rewardpercents, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercents) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpercents, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, $global_rewardpercents, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercents) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpercents, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $global_rewardpercents, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                }
                            }
                        } else {
                            if ($enabledisablemaxpoints == 'yes') {
                                if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                    $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    if ($getoldpoints <= $restrictuserpoints) {
                                        $totalpointss = $getoldpoints + $global_rewardpercent;
                                        if ($totalpointss <= $restrictuserpoints) {
                                            $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercents) * $itemquantity;
                                            RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpercents, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $global_rewardpercents, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                        } else {
                                            $insertpoints = $restrictuserpoints - $getoldpoints;
                                            RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                            $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercents) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpercents, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $global_rewardpercents, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                }
                            } else {
                                $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercents) * $itemquantity;
                                RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpercents, $pointsredeemed, $date, 'PPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                RSPointExpiry::record_the_points($orderuserid, $global_rewardpercents, '0', $date, 'PPRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                            }
                        }
                    }
                    break;
            }
        } else {
            $nomineeid = $orderuserid;
            $orderuserid = $getnomineeid;
            $pointsredeemed = 0;
            switch ($level) {
                case '1':
                    if ($productlevelrewardtype == '1') {
                        if ($enableoption == 'yes') {
                            if ($checkredeeming == false) {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $productlevelrewardpoints;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                                RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpointss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $productlevelrewardpointss);
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpointss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $productlevelrewardpointss);
                                    }
                                } else {
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpointss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $productlevelrewardpointss);
                                }
                            }
                        } else {
                            if ($enabledisablemaxpoints == 'yes') {
                                if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                    $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    if ($getoldpoints <= $restrictuserpoints) {
                                        $totalpointss = $getoldpoints + $productlevelrewardpoints;
                                        if ($totalpointss <= $restrictuserpoints) {
                                            $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                            RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpointss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                            $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                            RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $productlevelrewardpointss);
                                        } else {
                                            $insertpoints = $restrictuserpoints - $getoldpoints;
                                            RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                            $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpointss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $productlevelrewardpointss);
                                }
                            } else {
                                $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpointss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $productlevelrewardpointss);
                            }
                        }
                    } else {
                        if ($enableoption == 'yes') {
                            if ($checkredeeming == false) {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $productlevelrewardpercent;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                                RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpercentss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $productlevelrewardpercentss);
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpercentss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $productlevelrewardpercentss);
                                    }
                                } else {
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpercentss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $productlevelrewardpercentss);
                                }
                            }
                        } else {
                            if ($enabledisablemaxpoints == 'yes') {
                                if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                    $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    if ($getoldpoints <= $restrictuserpoints) {
                                        $totalpointss = $getoldpoints + $productlevelrewardpercent;
                                        if ($totalpointss <= $restrictuserpoints) {
                                            $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                            RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpercentss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                            $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                            RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $productlevelrewardpercentss);
                                        } else {
                                            $insertpoints = $restrictuserpoints - $getoldpoints;
                                            RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                            $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpercentss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $productlevelrewardpercentss);
                                }
                            } else {
                                $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                RSPointExpiry::insert_earning_points($orderuserid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                RSPointExpiry::record_the_points($orderuserid, $productlevelrewardpercentss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $productlevelrewardpercentss);
                            }
                        }
                    }
                    break;
                case '2':
                    if ($categorylevelrewardtype == '1') {
                        if ($enableoption == 'yes') {
                            if ($checkredeeming == false) {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $categorylevelrewardpoints;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                                RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpointss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $categorylevelrewardpointss);
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpointss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $categorylevelrewardpointss);
                                    }
                                } else {
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpointss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $categorylevelrewardpointss);
                                }
                            }
                        } else {
                            if ($enabledisablemaxpoints == 'yes') {
                                if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                    $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    if ($getoldpoints <= $restrictuserpoints) {
                                        $totalpointss = $getoldpoints + $categorylevelrewardpoints;
                                        if ($totalpointss <= $restrictuserpoints) {
                                            $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                            RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpointss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                            $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                            RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $categorylevelrewardpointss);
                                        } else {
                                            $insertpoints = $restrictuserpoints - $getoldpoints;
                                            RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                            $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpointss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $categorylevelrewardpointss);
                                }
                            } else {
                                $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpointss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $categorylevelrewardpointss);
                            }
                        }
                    } else {
                        if ($enableoption == 'yes') {
                            if ($checkredeeming == false) {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $categorylevelrewardpercent;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                                RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpercents, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $categorylevelrewardpercents);
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpercents, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $categorylevelrewardpercents);
                                    }
                                } else {
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpercents, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $categorylevelrewardpercents);
                                }
                            }
                        } else {
                            if ($enabledisablemaxpoints == 'yes') {
                                if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                    $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    if ($getoldpoints <= $restrictuserpoints) {
                                        $totalpointss = $getoldpoints + $categorylevelrewardpercent;
                                        if ($totalpointss <= $restrictuserpoints) {
                                            $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                            RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpercents, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                            $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                            RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $categorylevelrewardpercents);
                                        } else {
                                            $insertpoints = $restrictuserpoints - $getoldpoints;
                                            RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                            $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpercents, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $categorylevelrewardpercents);
                                }
                            } else {
                                $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                RSPointExpiry::insert_earning_points($orderuserid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                RSPointExpiry::record_the_points($orderuserid, $categorylevelrewardpercents, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $categorylevelrewardpercents);
                            }
                        }
                    }
                    break;
                case '3':
                    if ($global_reward_type == '1') {
                        if ($enableoption == 'yes') {
                            if ($checkredeeming == false) {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $global_rewardpoints;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                                RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpointss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $global_rewardpointss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $global_rewardpointss);
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpointss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, $global_rewardpointss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $global_rewardpointss);
                                    }
                                } else {
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpointss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $global_rewardpointss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $global_rewardpointss);
                                }
                            }
                        } else {
                            if ($enabledisablemaxpoints == 'yes') {
                                if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                    $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    if ($getoldpoints <= $restrictuserpoints) {
                                        $totalpointss = $getoldpoints + $global_rewardpoints;
                                        if ($totalpointss <= $restrictuserpoints) {
                                            $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                            RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpointss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $global_rewardpointss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                            $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                            RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $global_rewardpointss);
                                        } else {
                                            $insertpoints = $restrictuserpoints - $getoldpoints;
                                            RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                            $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpointss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $global_rewardpointss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $global_rewardpointss);
                                }
                            } else {
                                $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpointss, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                RSPointExpiry::record_the_points($orderuserid, $global_rewardpointss, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $global_rewardpointss);
                            }
                        }
                    } else {
                        if ($enableoption == 'yes') {
                            if ($checkredeeming == false) {

                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $global_rewardpercent;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                                RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpercents, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $global_rewardpercents, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $global_rewardpercents);
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpercents, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, $global_rewardpercents, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $global_rewardpercents);
                                    }
                                } else {
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpercents, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $global_rewardpercents, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $global_rewardpercents);
                                }
                            }
                        } else {
                            if ($enabledisablemaxpoints == 'yes') {
                                if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                    $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    if ($getoldpoints <= $restrictuserpoints) {
                                        $totalpointss = $getoldpoints + $global_rewardpercent;
                                        if ($totalpointss <= $restrictuserpoints) {
                                            $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                            RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpercents, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $global_rewardpercents, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                            $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                            RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $global_rewardpercents);
                                        } else {
                                            $insertpoints = $restrictuserpoints - $getoldpoints;
                                            RSPointExpiry::insert_earning_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                            $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        RSPointExpiry::insert_earning_points($orderuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($orderuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpercents, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($orderuserid, $global_rewardpercents, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $global_rewardpercents);
                                }
                            } else {
                                $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                RSPointExpiry::insert_earning_points($orderuserid, $global_rewardpercents, $pointsredeemed, $date, 'PPRPFN', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                RSPointExpiry::record_the_points($orderuserid, $global_rewardpercents, '0', $date, 'PPRPFN', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, $nomineeid, '0');
                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                RSPointExpiry::record_the_points($nomineeid, '0', '0', $date, 'PPRPFNP', '0', '0', '0', '0', '0', '', '', $totalpointss, $orderuserid, $global_rewardpercents);
                            }
                        }
                    }
                    break;
            }
        }
    }

    /* Function For Checking in Which level Reward points is Awarded */

    public static function check_level_of_enable_referral_reward_point($productid, $variationid, $termid) {
        global $post;
        //Product Level
        $productlevel = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_rewardsystemcheckboxvalue') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_enable_reward_points');
        $productlevelrewardtype = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_referral_rewardsystem_options') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_select_referral_reward_rule');
        $productlevelrewardpoints = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_referralrewardsystempoints') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_referral_reward_points');
        $productlevelrewardpercent = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_referralrewardsystempercent') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_referral_reward_percent');

        //Category Level
        $categorylist = wp_get_post_terms($productid, 'product_cat');
        $getcount = count($categorylist);
        $categorylevel = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'enable_reward_system_category');
        $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'referral_enable_rs_rule');
        $categorylevelrewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'referral_rs_category_points');
        $categorylevelrewardpercent = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'referral_rs_category_percent');

        //Global Level
        $global_enable = get_option('rs_global_enable_disable_sumo_reward');
        $global_reward_type = get_option('rs_global_referral_reward_type');
        $global_rewardpoints = get_option('rs_global_referral_reward_point');
        $global_rewardpercent = get_option('rs_global_referral_reward_percent');

        if (($productlevel == 'yes') || ($productlevel == '1')) {
            if ($productlevelrewardtype == '1') {
                if ($productlevelrewardpoints != '') {
                    return '1';
                } else {
                    if ($getcount >= '1') {
                        if (($categorylevel == 'yes') || ($categorylevel != '')) {
                            if (($categorylevelrewardtype == '1')) {
                                if ($categorylevelrewardpoints != '') {
                                    return '2';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_rewardpoints != '') {
                                                return '2';
                                            }
                                        } else {
                                            if ($global_rewardpercent != '') {
                                                return '2';
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($categorylevelrewardpercent != '') {
                                    return '2';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_rewardpoints != '') {
                                                return '2';
                                            }
                                        } else {
                                            if ($global_rewardpercent != '') {
                                                return '2';
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($global_enable == '1') {
                                if ($global_reward_type == '1') {
                                    if ($global_rewardpoints != '') {
                                        return '2';
                                    }
                                } else {
                                    if ($global_rewardpercent != '') {
                                        return '2';
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                if ($global_rewardpoints != '') {
                                    return '3';
                                }
                            } else {
                                if ($global_rewardpercent != '') {
                                    return '3';
                                }
                            }
                        }
                    }
                }
            } else {
                if ($productlevelrewardpercent != '') {
                    return '1';
                } else {
                    if ($getcount >= '1') {
                        if (($categorylevel == 'yes') || ($categorylevel != '')) {
                            if (($categorylevelrewardtype == '1')) {
                                if ($categorylevelrewardpoints != '') {
                                    return '2';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_rewardpoints != '') {
                                                return '3';
                                            }
                                        } else {
                                            if ($global_rewardpercent != '') {
                                                return '3';
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($categorylevelrewardpercent != '') {
                                    return '2';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_rewardpoints != '') {
                                                return '3';
                                            }
                                        } else {
                                            if ($global_rewardpercent != '') {
                                                return '3';
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($global_enable == '1') {
                                if ($global_reward_type == '1') {
                                    if ($global_rewardpoints != '') {
                                        return '3';
                                    }
                                } else {
                                    if ($global_rewardpercent != '') {
                                        return '3';
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                if ($global_rewardpoints != '') {
                                    return '3';
                                }
                            } else {
                                if ($global_rewardpercent != '') {
                                    return '3';
                                }
                            }
                        }
                    }
                }
            }
        } else {
            return '0';
        }
    }

    /* Function to insert referral reward points into db */

    public static function rs_insert_the_selected_level_in_referral_reward_points($pointsredeemed, $referreduser, $equearnamt, $equredeemamt, $level, $productid, $variationid, $itemquantity, $orderuserid, $termid, $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail, $item) {

        if ($variationid != ('0' || '')) {
            $variable_product1 = new WC_Product_Variation($variationid);
            if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                $getregularprice = $variable_product1->regular_price == '' ? $variable_product1->price : $variable_product1->regular_price;
            } else {
                $getregularprice = $variable_product1->price == '' ? $variable_product1->regular_price : $variable_product1->price;
            }
            do_action_ref_array('rs_update_points_for_variable', array(&$getregularprice, &$item));
        } else {
            if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_regular_price');
                if ($getregularprice == '') {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_price');
                }
            } else {
                $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_price');
                if ($getregularprice == '') {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_regular_price');
                }
            }
            do_action_ref_array('rs_update_points_for_referral_simple', array(&$getregularprice, &$item));
        }



        $noofdays = get_option('rs_point_to_be_expire');

        if (($noofdays != '0') && ($noofdays != '')) {
            $date = time() + ($noofdays * 24 * 60 * 60);
        } else {
            $date = '999999999999';
        }
        $user_info = new WP_User($orderuserid);
        $registered_date = $user_info->user_registered;
        $limitation = false;
        $modified_registered_date = date('Y-m-d h:i:sa', strtotime($registered_date));
        $delay_days = get_option('_rs_select_referral_points_referee_time_content');
        $checking_date = date('Y-m-d h:i:sa', strtotime($modified_registered_date . ' + ' . $delay_days . ' days '));
        $modified_checking_date = strtotime($checking_date);
        $current_date = date('Y-m-d h:i:sa');
        $modified_current_date = strtotime($current_date);
        //Is for Immediatly
        if (get_option('_rs_select_referral_points_referee_time') == '1') {
            $limitation = true;
        } else {
            // Is for Limited Time with Number of Days
            if ($modified_current_date > $modified_checking_date) {
                $limitation = true;
            } else {
                $limitation = false;
            }
        }

        if ($limitation == true) {
            $refuser = get_user_by('login', $referreduser);
            if ($refuser != false) {
                $myid = $refuser->ID;
            } else {
                $myid = $referreduser;
            }
            $banning_type = FPRewardSystem::check_banning_type($myid);
            if ($banning_type != 'earningonly' && $banning_type != 'both') {
                $productlevelrewardtype = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_referral_rewardsystem_options') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_select_referral_reward_rule');
                $productlevelrewardpoints = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_referralrewardsystempoints') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_referral_reward_points');
                $rewardpercentforproductlevel = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_referralrewardsystempercent') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_referral_reward_percent');
                $pointforconversion = RSFunctionofGeneralTab::earn_point_conversion();
                ;
                $pointforconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $rewardpercentforproductlevel = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_referralrewardsystempercent') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_referral_reward_percent');
                $rewardpercentpl = $rewardpercentforproductlevel / 100;
                $getaveragepoints = $rewardpercentpl * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointforconversion;
                $productlevelrewardpercent = $pointswithvalue / $pointforconversionvalue;


                if (($productid != '') || ($variationid != '') || ($variationid != '0')) {
                    $rewardpoints = array('0');
                    $rewardpercent = array('0');
                    $categorylist = wp_get_post_terms($productid, 'product_cat');
                    $getcount = count($categorylist);
                    $term = get_the_terms($productid, 'product_cat');
                    if (is_array($term)) {
                        foreach ($term as $terms) {
                            $termid = $terms->term_id;
                            if ($getcount > 1) {
                                $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'referral_enable_rs_rule');
                                $rewardpointsforcategory = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'referral_rs_category_points');
                                if ($rewardpointsforcategory == '') {
                                    $rewardpoints[] = get_option('rs_global_referral_reward_point');
                                } else {
                                    $rewardpoints[] = $rewardpointsforcategory;
                                }
                                $rewardpercentforcategory = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'referral_rs_category_percent');
                                if ($rewardpercentforcategory == '') {
                                    $pointforconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    ;
                                    $pointforconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $get_global_rewardpercent = get_option('rs_global_referral_reward_percent') / 100;
                                    $getaveragepoints = $get_global_rewardpercent * $getregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointforconversion;
                                    $rewardpercent[] = $pointswithvalue / $pointforconversionvalue;
                                } else {
                                    $pointforconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    ;
                                    $pointforconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $rewardpercentforcategory = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'referral_rs_category_percent') / 100;
                                    $getaveragepoints = $rewardpercentforcategory * $getregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointforconversion;
                                    $rewardpercent[] = $pointswithvalue / $pointforconversionvalue;
                                }
                            } else {
                                $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'referral_enable_rs_rule');
                                $rewardpointsforcategory = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'referral_rs_category_points');
                                if ($rewardpointsforcategory == '') {
                                    $rewardpoints[] = get_option('rs_global_referral_reward_point');
                                } else {
                                    $rewardpoints[] = $rewardpointsforcategory;
                                }
                                $rewardpercentforcategory = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'referral_rs_category_percent');
                                if ($rewardpercentforcategory == '') {
                                    $pointforconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    ;
                                    $pointforconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $get_global_rewardpercent = get_option('rs_global_referral_reward_percent') / 100;
                                    $getaveragepoints = $get_global_rewardpercent * $getregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointforconversion;
                                    $rewardpercent[] = $pointswithvalue / $pointforconversionvalue;
                                } else {
                                    $pointforconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    ;
                                    $pointforconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $rewardpercentforcategory = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'referral_rs_category_percent') / 100;
                                    $getaveragepoints = $rewardpercentforcategory * $getregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointforconversion;
                                    $rewardpercent[] = $pointswithvalue / $pointforconversionvalue;
                                }
                            }
                        }
                    }
                    $categorylevelrewardpoints = max($rewardpoints);
                    $categorylevelrewardpercent = max($rewardpercent);
                }
                $global_reward_type = get_option('rs_global_referral_reward_type');
                $global_rewardpoints = get_option('rs_global_referral_reward_point');
                $pointforconversion = RSFunctionofGeneralTab::earn_point_conversion();
                ;
                $pointforconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $get_global_rewardpercent = get_option('rs_global_referral_reward_percent') / 100;
                $getaveragepoints = $get_global_rewardpercent * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointforconversion;
                $global_rewardpercent = $pointswithvalue / $pointforconversionvalue;

                $checkredeeming = false;
                $enableoption = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, 'rs_check_enable_option_for_redeeming');
                $restrictuserpoints = get_option('rs_max_earning_points_for_user');
                $enabledisablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
                switch ($level) {
                    case '1':
                        if ($productlevelrewardtype == '1') {
                            if ($enableoption == 'yes') {
                                if ($checkredeeming == false) {
                                    if ($enabledisablemaxpoints == 'yes') {
                                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            if ($getoldpoints <= $restrictuserpoints) {
                                                $totalpointss = $getoldpoints + $productlevelrewardpoints;
                                                if ($totalpointss <= $restrictuserpoints) {
                                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                                    RSPointExpiry::insert_earning_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    RSPointExpiry::record_the_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                    $previouslog = get_option('rs_referral_log');
                                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpointss, array_filter((array) $previouslog));
                                                } else {
                                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                                    RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                                }
                                            } else {
                                                RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                            RSPointExpiry::insert_earning_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            RSPointExpiry::record_the_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                            $previouslog = get_option('rs_referral_log');
                                            RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpointss, array_filter((array) $previouslog));
                                        }
                                    } else {
                                        $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        RSPointExpiry::record_the_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpointss, array_filter((array) $previouslog));
                                    }
                                }
                            } else {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $productlevelrewardpoints;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                                RSPointExpiry::insert_earning_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                $previouslog = get_option('rs_referral_log');
                                                RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpointss, array_filter((array) $previouslog));
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        RSPointExpiry::record_the_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpointss, array_filter((array) $previouslog));
                                    }
                                } else {
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                    RSPointExpiry::record_the_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                    $previouslog = get_option('rs_referral_log');
                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpointss, array_filter((array) $previouslog));
                                }
                            }
                        } else {
                            if ($enableoption == 'yes') {
                                if ($checkredeeming == false) {

                                    if ($enabledisablemaxpoints == 'yes') {
                                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            if ($getoldpoints <= $restrictuserpoints) {
                                                $totalpointss = $getoldpoints + $productlevelrewardpercent;
                                                if ($totalpointss <= $restrictuserpoints) {
                                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                                    RSPointExpiry::insert_earning_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    RSPointExpiry::record_the_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                    $previouslog = get_option('rs_referral_log');
                                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpercentss, array_filter((array) $previouslog));
                                                } else {
                                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                                    RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                                }
                                            } else {
                                                RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                            RSPointExpiry::insert_earning_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            RSPointExpiry::record_the_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                            $previouslog = get_option('rs_referral_log');
                                            RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpercentss, array_filter((array) $previouslog));
                                        }
                                    } else {
                                        $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        RSPointExpiry::record_the_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpercentss, array_filter((array) $previouslog));
                                    }
                                }
                            } else {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $productlevelrewardpercent;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                                RSPointExpiry::insert_earning_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                $previouslog = get_option('rs_referral_log');
                                                RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpercentss, array_filter((array) $previouslog));
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        RSPointExpiry::record_the_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpercentss, array_filter((array) $previouslog));
                                    }
                                } else {
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                    RSPointExpiry::record_the_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                    $previouslog = get_option('rs_referral_log');
                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpercentss, array_filter((array) $previouslog));
                                }
                            }
                        }
                        break;
                    case '2':
                        if ($categorylevelrewardtype == '1') {
                            if ($enableoption == 'yes') {
                                if ($checkredeeming == false) {
                                    if ($enabledisablemaxpoints == 'yes') {
                                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            if ($getoldpoints <= $restrictuserpoints) {
                                                $totalpointss = $getoldpoints + $categorylevelrewardpoints;
                                                if ($totalpointss <= $restrictuserpoints) {
                                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                                    RSPointExpiry::insert_earning_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    RSPointExpiry::record_the_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                    $previouslog = get_option('rs_referral_log');
                                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpointss, array_filter((array) $previouslog));
                                                } else {
                                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                                    RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                                }
                                            } else {
                                                RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                            RSPointExpiry::insert_earning_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            RSPointExpiry::record_the_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                            $previouslog = get_option('rs_referral_log');
                                            RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpointss, array_filter((array) $previouslog));
                                        }
                                    } else {
                                        $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        RSPointExpiry::record_the_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpointss, array_filter((array) $previouslog));
                                    }
                                }
                            } else {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $categorylevelrewardpoints;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                                RSPointExpiry::insert_earning_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                $previouslog = get_option('rs_referral_log');
                                                RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpointss, array_filter((array) $previouslog));
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        RSPointExpiry::record_the_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpointss, array_filter((array) $previouslog));
                                    }
                                } else {
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                    RSPointExpiry::record_the_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                    $previouslog = get_option('rs_referral_log');
                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpointss, array_filter((array) $previouslog));
                                }
                            }
                        } else {
                            if ($enableoption == 'yes') {
                                if ($checkredeeming == false) {
                                    if ($enabledisablemaxpoints == 'yes') {
                                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            if ($getoldpoints <= $restrictuserpoints) {
                                                $totalpointss = $getoldpoints + $categorylevelrewardpercent;
                                                if ($totalpointss <= $restrictuserpoints) {
                                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                                    RSPointExpiry::insert_earning_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    RSPointExpiry::record_the_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                    $previouslog = get_option('rs_referral_log');
                                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpercents, array_filter((array) $previouslog));
                                                } else {
                                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                                    RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                                }
                                            } else {
                                                RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                            RSPointExpiry::insert_earning_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            RSPointExpiry::record_the_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                            $previouslog = get_option('rs_referral_log');
                                            RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpercents, array_filter((array) $previouslog));
                                        }
                                    } else {
                                        $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        RSPointExpiry::record_the_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpercents, array_filter((array) $previouslog));
                                    }
                                }
                            } else {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $categorylevelrewardpercent;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                                RSPointExpiry::insert_earning_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                $previouslog = get_option('rs_referral_log');
                                                RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpercents, array_filter((array) $previouslog));
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        RSPointExpiry::record_the_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpercents, array_filter((array) $previouslog));
                                    }
                                } else {
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                    RSPointExpiry::record_the_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                    $previouslog = get_option('rs_referral_log');
                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpercents, array_filter((array) $previouslog));
                                }
                            }
                        }
                        break;
                    case '3':
                        if ($global_reward_type == '1') {
                            if ($enableoption == 'yes') {
                                if ($checkredeeming == false) {
                                    if ($enabledisablemaxpoints == 'yes') {
                                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            if ($getoldpoints <= $restrictuserpoints) {
                                                $totalpointss = $getoldpoints + $global_rewardpoints;
                                                if ($totalpointss <= $restrictuserpoints) {
                                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                                    RSPointExpiry::insert_earning_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    RSPointExpiry::record_the_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                    $previouslog = get_option('rs_referral_log');
                                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpointss, array_filter((array) $previouslog));
                                                } else {
                                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                                    RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                                }
                                            } else {
                                                RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                            RSPointExpiry::insert_earning_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            RSPointExpiry::record_the_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                            $previouslog = get_option('rs_referral_log');
                                            RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpointss, array_filter((array) $previouslog));
                                        }
                                    } else {
                                        $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        RSPointExpiry::record_the_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpointss, array_filter((array) $previouslog));
                                    }
                                }
                            } else {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $global_rewardpoints;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                                RSPointExpiry::insert_earning_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                $previouslog = get_option('rs_referral_log');
                                                RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpointss, array_filter((array) $previouslog));
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        RSPointExpiry::record_the_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpointss, array_filter((array) $previouslog));
                                    }
                                } else {
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                    RSPointExpiry::record_the_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                    $previouslog = get_option('rs_referral_log');
                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpointss, array_filter((array) $previouslog));
                                }
                            }
                        } else {
                            if ($enableoption == 'yes') {
                                if ($checkredeeming == true) {
                                    if ($enabledisablemaxpoints == 'yes') {
                                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            if ($getoldpoints <= $restrictuserpoints) {
                                                $totalpointss = $getoldpoints + $global_rewardpercent;
                                                if ($totalpointss <= $restrictuserpoints) {
                                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                                    RSPointExpiry::insert_earning_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    RSPointExpiry::record_the_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                    $previouslog = get_option('rs_referral_log');
                                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpercents, array_filter((array) $previouslog));
                                                } else {
                                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                                    RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                                }
                                            } else {
                                                RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                            RSPointExpiry::insert_earning_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            RSPointExpiry::record_the_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                            $previouslog = get_option('rs_referral_log');
                                            RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpercents, array_filter((array) $previouslog));
                                        }
                                    } else {
                                        $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        RSPointExpiry::record_the_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpercents, array_filter((array) $previouslog));
                                    }
                                }
                            } else {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $global_rewardpercent;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                                RSPointExpiry::insert_earning_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                $previouslog = get_option('rs_referral_log');
                                                RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpercents, array_filter((array) $previouslog));
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                        RSPointExpiry::insert_earning_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        RSPointExpiry::record_the_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpercents, array_filter((array) $previouslog));
                                    }
                                } else {
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                    RSPointExpiry::insert_earning_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                    RSPointExpiry::record_the_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                    $previouslog = get_option('rs_referral_log');
                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpercents, array_filter((array) $previouslog));
                                }
                            }
                        }
                        break;
                }
            }
        }
    }

    public static function rs_insert_the_reward_points_for_manuall_referrer($pointsredeemed, $referrer_id, $equearnamt, $equredeemamt, $level, $productid, $variationid, $itemquantity, $orderuserid, $termid, $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail, $item) {

        if ($variationid != ('0' || '')) {
            $variable_product1 = new WC_Product_Variation($variationid);
            if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                $getregularprice = $variable_product1->regular_price == '' ? $variable_product1->price : $variable_product1->regular_price;
            } else {
                $getregularprice = $variable_product1->price == '' ? $variable_product1->regular_price : $variable_product1->price;
            }
            do_action_ref_array('rs_update_points_for_variable', array(&$getregularprice, &$item));
        } else {
            if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_regular_price');
                if ($getregularprice == '') {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_price');
                }
            } else {
                $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_price');
                if ($getregularprice == '') {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_regular_price');
                }
            }
            do_action_ref_array('rs_update_points_for_referral_simple', array(&$getregularprice, &$item));
        }


        $noofdays = get_option('rs_point_to_be_expire');

        if (($noofdays != '0') && ($noofdays != '')) {
            $date = time() + ($noofdays * 24 * 60 * 60);
        } else {
            $date = '999999999999';
        }
        $user_info = new WP_User($orderuserid);
        $registered_date = $user_info->user_registered;
        $limitation = false;
        $modified_registered_date = date('Y-m-d h:i:sa', strtotime($registered_date));
        $delay_days = get_option('_rs_select_referral_points_referee_time_content');
        $checking_date = date('Y-m-d h:i:sa', strtotime($modified_registered_date . ' + ' . $delay_days . ' days '));
        $modified_checking_date = strtotime($checking_date);
        $current_date = date('Y-m-d h:i:sa');
        $modified_current_date = strtotime($current_date);
        //Is for Immediatly
        if (get_option('_rs_select_referral_points_referee_time') == '1') {
            $limitation = true;
        } else {
            // Is for Limited Time with Number of Days
            if ($modified_current_date > $modified_checking_date) {
                $limitation = true;
            } else {
                $limitation = false;
            }
        }

        if ($limitation == true) {

            $myid = $referrer_id;

            $banning_type = FPRewardSystem::check_banning_type($myid);
            if ($banning_type != 'earningonly' && $banning_type != 'both') {
                $productlevelrewardtype = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_referral_rewardsystem_options') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_select_referral_reward_rule');
                $productlevelrewardpoints = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_referralrewardsystempoints') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_referral_reward_points');
                $rewardpercentforproductlevel = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_referralrewardsystempercent') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_referral_reward_percent');
                $pointforconversion = RSFunctionofGeneralTab::earn_point_conversion();
                ;
                $pointforconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $rewardpercentforproductlevel = $variationid == '0' || '' ? RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($productid, '_referralrewardsystempercent') : RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variationid, '_referral_reward_percent');
                $rewardpercentpl = $rewardpercentforproductlevel / 100;
                $getaveragepoints = $rewardpercentpl * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointforconversion;
                $productlevelrewardpercent = $pointswithvalue / $pointforconversionvalue;


                if (($productid != '') || ($variationid != '') || ($variationid != '0')) {
                    $rewardpoints = array('0');
                    $rewardpercent = array('0');
                    $categorylist = wp_get_post_terms($productid, 'product_cat');
                    $getcount = count($categorylist);
                    $term = get_the_terms($productid, 'product_cat');
                    if (is_array($term)) {
                        foreach ($term as $terms) {
                            $termid = $terms->term_id;
                            if ($getcount > 1) {
                                $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'referral_enable_rs_rule');
                                $rewardpointsforcategory = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'referral_rs_category_points');
                                if ($rewardpointsforcategory == '') {
                                    $rewardpoints[] = get_option('rs_global_referral_reward_point');
                                } else {
                                    $rewardpoints[] = $rewardpointsforcategory;
                                }
                                $rewardpercentforcategory = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'referral_rs_category_percent');
                                if ($rewardpercentforcategory == '') {
                                    $pointforconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    ;
                                    $pointforconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $get_global_rewardpercent = get_option('rs_global_referral_reward_percent') / 100;
                                    $getaveragepoints = $get_global_rewardpercent * $getregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointforconversion;
                                    $rewardpercent[] = $pointswithvalue / $pointforconversionvalue;
                                } else {
                                    $pointforconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    ;
                                    $pointforconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $rewardpercentforcategory = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'referral_rs_category_percent') / 100;
                                    $getaveragepoints = $rewardpercentforcategory * $getregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointforconversion;
                                    $rewardpercent[] = $pointswithvalue / $pointforconversionvalue;
                                }
                            } else {
                                $categorylevelrewardtype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'referral_enable_rs_rule');
                                $rewardpointsforcategory = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'referral_rs_category_points');
                                if ($rewardpointsforcategory == '') {
                                    $rewardpoints[] = get_option('rs_global_referral_reward_point');
                                } else {
                                    $rewardpoints[] = $rewardpointsforcategory;
                                }
                                $rewardpercentforcategory = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'referral_rs_category_percent');
                                if ($rewardpercentforcategory == '') {
                                    $pointforconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    ;
                                    $pointforconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $get_global_rewardpercent = get_option('rs_global_referral_reward_percent') / 100;
                                    $getaveragepoints = $get_global_rewardpercent * $getregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointforconversion;
                                    $rewardpercent[] = $pointswithvalue / $pointforconversionvalue;
                                } else {
                                    $pointforconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    ;
                                    $pointforconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $rewardpercentforcategory = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($termid, 'referral_rs_category_percent') / 100;
                                    $getaveragepoints = $rewardpercentforcategory * $getregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointforconversion;
                                    $rewardpercent[] = $pointswithvalue / $pointforconversionvalue;
                                }
                            }
                        }
                    }
                    $categorylevelrewardpoints = max($rewardpoints);
                    $categorylevelrewardpercent = max($rewardpercent);
                }
                $global_reward_type = get_option('rs_global_referral_reward_type');
                $global_rewardpoints = get_option('rs_global_referral_reward_point');
                $pointforconversion = RSFunctionofGeneralTab::earn_point_conversion();
                ;
                $pointforconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                $get_global_rewardpercent = get_option('rs_global_referral_reward_percent') / 100;
                $getaveragepoints = $get_global_rewardpercent * $getregularprice;
                $pointswithvalue = $getaveragepoints * $pointforconversion;
                $global_rewardpercent = $pointswithvalue / $pointforconversionvalue;


                $checkredeeming = false;
                $enableoption = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, 'rs_check_enable_option_for_redeeming');
                $restrictuserpoints = get_option('rs_max_earning_points_for_user');
                $enabledisablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
                switch ($level) {
                    case '1':
                        if ($productlevelrewardtype == '1') {
                            if ($enableoption == 'yes') {
                                if ($checkredeeming == false) {
                                    if ($enabledisablemaxpoints == 'yes') {
                                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            if ($getoldpoints <= $restrictuserpoints) {
                                                $totalpointss = $getoldpoints + $productlevelrewardpoints;
                                                if ($totalpointss <= $restrictuserpoints) {
                                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                                    self::insert_earning_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    self::record_the_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                    $previouslog = get_option('rs_referral_log');
                                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpointss, array_filter((array) $previouslog));
                                                } else {
                                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                                    RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                                }
                                            } else {
                                                RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                            self::insert_earning_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                            $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            self::record_the_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                            $previouslog = get_option('rs_referral_log');
                                            RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpointss, array_filter((array) $previouslog));
                                        }
                                    } else {
                                        $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                        self::insert_earning_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        self::record_the_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpointss, array_filter((array) $previouslog));
                                    }
                                }
                            } else {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $productlevelrewardpoints;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                                self::insert_earning_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                self::record_the_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                $previouslog = get_option('rs_referral_log');
                                                RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpointss, array_filter((array) $previouslog));
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                        self::insert_earning_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        self::record_the_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpointss, array_filter((array) $previouslog));
                                    }
                                } else {
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                    self::insert_earning_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                    self::record_the_points($myid, $productlevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                    $previouslog = get_option('rs_referral_log');
                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpointss, array_filter((array) $previouslog));
                                }
                            }
                        } else {
                            if ($enableoption == 'yes') {
                                if ($checkredeeming == false) {

                                    if ($enabledisablemaxpoints == 'yes') {
                                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            if ($getoldpoints <= $restrictuserpoints) {
                                                $totalpointss = $getoldpoints + $productlevelrewardpercent;
                                                if ($totalpointss <= $restrictuserpoints) {
                                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                                    self::insert_earning_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    self::record_the_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                    $previouslog = get_option('rs_referral_log');
                                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpercentss, array_filter((array) $previouslog));
                                                } else {
                                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                                    RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                                }
                                            } else {
                                                RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                            self::insert_earning_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                            $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            self::record_the_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                            $previouslog = get_option('rs_referral_log');
                                            RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpercentss, array_filter((array) $previouslog));
                                        }
                                    } else {
                                        $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                        self::insert_earning_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        self::record_the_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpercentss, array_filter((array) $previouslog));
                                    }
                                }
                            } else {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $productlevelrewardpercent;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                                self::insert_earning_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                self::record_the_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                $previouslog = get_option('rs_referral_log');
                                                RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpercentss, array_filter((array) $previouslog));
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                        self::insert_earning_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        self::record_the_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpercentss, array_filter((array) $previouslog));
                                    }
                                } else {
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                    self::insert_earning_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                    self::record_the_points($myid, $productlevelrewardpercentss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                    $previouslog = get_option('rs_referral_log');
                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $productlevelrewardpercentss, array_filter((array) $previouslog));
                                }
                            }
                        }
                        break;
                    case '2':
                        if ($categorylevelrewardtype == '1') {
                            if ($enableoption == 'yes') {
                                if ($checkredeeming == false) {
                                    if ($enabledisablemaxpoints == 'yes') {
                                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            if ($getoldpoints <= $restrictuserpoints) {
                                                $totalpointss = $getoldpoints + $categorylevelrewardpoints;
                                                if ($totalpointss <= $restrictuserpoints) {
                                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                                    self::insert_earning_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    self::record_the_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                    $previouslog = get_option('rs_referral_log');
                                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpointss, array_filter((array) $previouslog));
                                                } else {
                                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                                    RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                                }
                                            } else {
                                                RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                            self::insert_earning_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                            $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            self::record_the_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                            $previouslog = get_option('rs_referral_log');
                                            RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpointss, array_filter((array) $previouslog));
                                        }
                                    } else {
                                        $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                        self::insert_earning_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        self::record_the_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpointss, array_filter((array) $previouslog));
                                    }
                                }
                            } else {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $categorylevelrewardpoints;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                                self::insert_earning_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                self::record_the_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                $previouslog = get_option('rs_referral_log');
                                                RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpointss, array_filter((array) $previouslog));
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                        self::insert_earning_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        self::record_the_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpointss, array_filter((array) $previouslog));
                                    }
                                } else {
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                    self::insert_earning_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                    self::record_the_points($myid, $categorylevelrewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                    $previouslog = get_option('rs_referral_log');
                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpointss, array_filter((array) $previouslog));
                                }
                            }
                        } else {
                            if ($enableoption == 'yes') {
                                if ($checkredeeming == false) {
                                    if ($enabledisablemaxpoints == 'yes') {
                                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            if ($getoldpoints <= $restrictuserpoints) {
                                                $totalpointss = $getoldpoints + $categorylevelrewardpercent;
                                                if ($totalpointss <= $restrictuserpoints) {
                                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                                    self::insert_earning_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    self::record_the_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                    $previouslog = get_option('rs_referral_log');
                                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpercents, array_filter((array) $previouslog));
                                                } else {
                                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                                    RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                                }
                                            } else {
                                                RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                            self::insert_earning_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                            $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            self::record_the_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                            $previouslog = get_option('rs_referral_log');
                                            RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpercents, array_filter((array) $previouslog));
                                        }
                                    } else {
                                        $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                        self::insert_earning_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        self::record_the_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpercents, array_filter((array) $previouslog));
                                    }
                                }
                            } else {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $categorylevelrewardpercent;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                                self::insert_earning_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                self::record_the_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                $previouslog = get_option('rs_referral_log');
                                                RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpercents, array_filter((array) $previouslog));
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                        self::insert_earning_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        self::record_the_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpercents, array_filter((array) $previouslog));
                                    }
                                } else {
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                    self::insert_earning_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                    self::record_the_points($myid, $categorylevelrewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                    $previouslog = get_option('rs_referral_log');
                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $categorylevelrewardpercents, array_filter((array) $previouslog));
                                }
                            }
                        }
                        break;
                    case '3':
                        if ($global_reward_type == '1') {
                            if ($enableoption == 'yes') {
                                if ($checkredeeming == false) {
                                    if ($enabledisablemaxpoints == 'yes') {
                                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            if ($getoldpoints <= $restrictuserpoints) {
                                                $totalpointss = $getoldpoints + $global_rewardpoints;
                                                if ($totalpointss <= $restrictuserpoints) {
                                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                                    self::insert_earning_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                    $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    self::record_the_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                    $previouslog = get_option('rs_referral_log');
                                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpointss, array_filter((array) $previouslog));
                                                } else {
                                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                                    RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                                }
                                            } else {
                                                RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                            self::insert_earning_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                            $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            self::record_the_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                            $previouslog = get_option('rs_referral_log');
                                            RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpointss, array_filter((array) $previouslog));
                                        }
                                    } else {
                                        $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                        self::insert_earning_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        self::record_the_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpointss, array_filter((array) $previouslog));
                                    }
                                }
                            } else {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $global_rewardpoints;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                                self::insert_earning_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                self::record_the_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                $previouslog = get_option('rs_referral_log');
                                                RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpointss, array_filter((array) $previouslog));
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                        self::insert_earning_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        self::record_the_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpointss, array_filter((array) $previouslog));
                                    }
                                } else {
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                    self::insert_earning_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                    self::record_the_points($myid, $global_rewardpointss, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                    $previouslog = get_option('rs_referral_log');
                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpointss, array_filter((array) $previouslog));
                                }
                            }
                        } else {
                            if ($enableoption == 'yes') {
                                if ($checkredeeming == true) {
                                    if ($enabledisablemaxpoints == 'yes') {
                                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            if ($getoldpoints <= $restrictuserpoints) {
                                                $totalpointss = $getoldpoints + $global_rewardpercent;
                                                if ($totalpointss <= $restrictuserpoints) {
                                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                                    self::insert_earning_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                    $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    self::record_the_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                    $previouslog = get_option('rs_referral_log');
                                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpercents, array_filter((array) $previouslog));
                                                } else {
                                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                                    RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                    RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                                }
                                            } else {
                                                RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                            self::insert_earning_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                            $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            self::record_the_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                            $previouslog = get_option('rs_referral_log');
                                            RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpercents, array_filter((array) $previouslog));
                                        }
                                    } else {
                                        $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                        self::insert_earning_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        self::record_the_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpercents, array_filter((array) $previouslog));
                                    }
                                }
                            } else {
                                if ($enabledisablemaxpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $global_rewardpercent;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                                self::insert_earning_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                                $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                self::record_the_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                                $previouslog = get_option('rs_referral_log');
                                                RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpercents, array_filter((array) $previouslog));
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                                RSPointExpiry::record_the_points($myid, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($myid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                            RSPointExpiry::record_the_points($myid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                        self::insert_earning_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                        self::record_the_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                        $previouslog = get_option('rs_referral_log');
                                        RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpercents, array_filter((array) $previouslog));
                                    }
                                } else {
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                    self::insert_earning_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                    self::record_the_points($myid, $global_rewardpercents, $pointsredeemed, $date, 'PPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                    $previouslog = get_option('rs_referral_log');
                                    RS_Referral_Log::main_referral_log_function($myid, $orderuserid, $global_rewardpercents, array_filter((array) $previouslog));
                                }
                            }
                        }
                        break;
                }
            }
        }
    }

}

new RSAjaxMainFunction();
