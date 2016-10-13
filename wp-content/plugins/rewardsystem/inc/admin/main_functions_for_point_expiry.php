<?php

class RSPointExpiry {

    public function __construct() {
        global $woocommerce;
        if (is_user_logged_in()) {

            $order_status = get_option('rs_order_status_after_gateway_purchase');
            add_action('woocommerce_order_status_' . $order_status, array($this, 'reddem_for_reward_gateway'), 1);

            $orderstatuslist = get_option('rs_order_status_control');
            if (is_array($orderstatuslist)) {
                foreach ($orderstatuslist as $value) {
                    add_action('woocommerce_order_status_' . $value, array($this, 'update_earning_points_for_user'), 1);
                }
            }

//            add_action('wp_head', array($this, 'update_earning_points_for_user'));

            $orderstatuslistforredeem = get_option('rs_order_status_control_redeem');
            if (is_array($orderstatuslistforredeem)) {
                foreach ($orderstatuslistforredeem as $value) {

                    add_action('woocommerce_thankyou', array($this, 'update_redeem_point_for_user_third_party_sites'), 1);

                    add_action('woocommerce_order_status_' . $value, array($this, 'update_redeem_point_for_user'), 1);
                }
            }


            $order_status_control = get_option('rs_list_other_status_for_redeem');
            if (get_option('rs_list_other_status_for_redeem') != '') {
                foreach ($order_status_control as $order_status) {
                    $orderstatuslist = get_option('rs_order_status_control_redeem');
                    if (is_array($orderstatuslist)) {
                        foreach ($orderstatuslist as $value) {
                            if ($value != 'pending') {
                                add_action('woocommerce_order_status_' . $value . '_to_' . $order_status, array($this, 'update_revised_redeem_points_for_user'));
                            }
                            if (in_array('pending', $orderstatuslist)) {
                                if (is_admin()) {
                                    add_action('woocommerce_order_status_pending' . '_to_' . $order_status, array($this, 'update_revised_redeem_points_for_user'));
                                }
                            }
                        }
                    }
                }
            }

            $order_status_control = get_option('rs_list_other_status');
            if (get_option('rs_list_other_status') != '') {
                foreach ($order_status_control as $order_status) {
                    $orderstatuslist = get_option('rs_order_status_control');
                    if (is_array($orderstatuslist)) {
                        foreach ($orderstatuslist as $value) {
                            add_action('woocommerce_order_status_' . $value . '_to_' . $order_status, array($this, 'update_revised_points_for_user'));
                        }
                    }
                }
            }

            add_action('wp_head', array($this, 'check_if_expiry'));

            add_action('wp_head', array($this, 'get_sum_of_total_earned_points'));

            add_action('wp_head', array($this, 'delete_if_used'));

            add_action('comment_post', array($this, 'get_reviewed_user_list'), 10, 2);

            if (get_option('rs_review_reward_status') == '1') {
                add_action('comment_unapproved_to_approved', array($this, 'getcommentstatus'), 10, 1);
            }
            if (get_option('rs_review_reward_status') == '2') {
                add_action('comment_unapproved', array($this, 'getcommentstatus'), 10, 1);
            }

            add_action('woocommerce_update_options_rewardsystem_status', array($this, 'rewards_rs_order_status_control'), 99);

            add_action('init', array($this, 'rewards_rs_order_status_control'), 9999);

            add_action('delete_user', array($this, 'delete_referral_registered_people'));

            add_shortcode('rs_my_reward_points', array($this, 'myrewardpoints_total_shortcode'));

            add_shortcode('rs_generate_referral', array($this, 'rs_fp_rewardsystem'));

            add_shortcode('rs_generate_static_referral', array($this, 'shortcode_for_static_referral_link'));

            add_action('woocommerce_checkout_update_order_meta', array($this, 'check_redeeming_in_order'), 10, 2);

            add_action('comment_unapproved_to_approved', array($this, 'getcommentstatus_post'), 10, 1);

            add_action('comment_unapproved', array($this, 'getcommentstatus_post'), 10, 1);

            add_action('comment_post', array($this, 'get_post_comment_user_list'), 10, 2);

            add_action('comment_post', array($this, 'get_page_comment_user_list'), 10, 2);

            add_action('woocommerce_checkout_update_order_meta', array($this, 'reward_points_for_product_review_after_purchase'), 10, 2);
        }
    }

    public static function reddem_for_reward_gateway($order_id) {
        $getmaxoption = get_option('rs_max_redeem_discount_for_sumo_reward_points');

        $order = new WC_Order($order_id);
        $ordertotal = $order->get_total();
        if ($ordertotal > $getmaxoption) {
            $rewardgateway = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, '_payment_method', true);

            if ($rewardgateway == 'reward_gateway') {
                $gateway_used = get_post_meta($order_id, 'sumo_gateway_used', true);
                $date = '999999999999';
                $total_redeem = get_post_meta($order_id, 'total_redeem_points_for_order_point_price', true);
                self::perform_calculation_with_expiry($total_redeem, $order->user_id);
                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($total_redeem);
                $reasonindetail = '';
                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                if ($totalpoints >= 0) {
                    RSPointExpiry::record_the_points($order->user_id, '0', $total_redeem, $date, 'RPFGW', '0', $equredeemamt, $order_id, '0', '0', '0', '', $totalpoints, '', '0');

                    update_post_meta($order_id, 'sumo_gateway_used', 1);
                }
            }
        }
    }

    public static function reward_points_for_product_review_after_purchase($orderid, $order_user_id) {
        $order = new WC_Order($orderid);
        $userid = $order->user_id;
        foreach ($order->get_items() as $eachitem) {
            $product_id = $eachitem['variation_id'] != '0' ? $eachitem['variation_id'] : $eachitem['product_id'];
            ;
            $getproductid = (array) get_post_meta($userid, 'product_id_for_product_review_meta1', true);
            if ($getproductid == '') {
                update_post_meta($userid, 'product_id_for_product_review_meta1', $product_id);
            } else {
                $arraymerge = array_merge((array) $getproductid, (array) $product_id);
                update_post_meta($userid, 'product_id_for_product_review_meta1', $arraymerge);
            }
        }
    }

    public static function update_redeem_point_for_user($order_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        $table_name2 = $wpdb->prefix . 'rsrecordpoints';
        global $woocommerce;
        $termid = '';
        $order = new WC_Order($order_id);
        $order_status = $order->post_status;
        $order_status = str_replace('wc-', '', $order_status);
        $selected_order_status = get_option('rs_order_status_control_redeem');
        $fp_earned_points_sms = false;

        $getreddemfororder = get_post_meta($order_id, 'redeem_point_once', true);
        if ($getreddemfororder != 1) {
            $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $order->user_id);
            $autoredeem = RSFunctionToApplyCoupon::update_auto_redeem_points($order_id, $order->user_id);
            if ($redeempoints != 0) {
                $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $order->user_id);
            } else {
                $pointsredeemed = self::perform_calculation_with_expiry($autoredeem, $order->user_id);
            }

            $date = '999999999999';

            /* Reward Points For Using Payment Gateway Method */
            //if ($points_awarded_for_this_order != 'yes') {
            if ($redeempoints != 0) {
                $equredeemamt = self::redeeming_conversion_settings($redeempoints);
                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                if ($totalpoints > 0) {
                    self::record_the_points($order->user_id, '0', $redeempoints, $date, 'RP', '0', $equredeemamt, $order_id, '', '', '', '', $totalpoints, '', '0');
                    self::insert_earning_points($order->user_id, '0', $pointsredeemed, 'RP', $order_id, '0', '0', '');
                }
            }
            if ($autoredeem != 0) {
                $equredeemamt1 = self::redeeming_conversion_settings($autoredeem);
                $totalpoints1 = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                if ($totalpoints1 > 0) {
                    self::record_the_points($order->user_id, '0', $autoredeem, $date, 'RP', '0', $equredeemamt1, $order_id, '', '', '', '', $totalpoints1, '', '0');
                    self::insert_earning_points($order->user_id, '0', $pointsredeemed, 'RP', $order_id, '0', '0', '');
                }
            }
            update_post_meta($order_id, 'redeem_point_once', 1);
        }
    }

    /* Check Point is Valid to Redeeming
     * param1: $userid,
     * Function used for Redeemin when user uses third party payment gateways like PayPal
     * return: null, it just perform the query for mysql if the point is expired.
     */

    public static function update_redeem_point_for_user_third_party_sites($order_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        $table_name2 = $wpdb->prefix . 'rsrecordpoints';
        global $woocommerce;
        $termid = '';
        $order = new WC_Order($order_id);
        $order_status = $order->post_status;
        $order_status = 'pending';
        $selected_order_status = get_option('rs_order_status_control_redeem');
        $fp_earned_points_sms = false;
        $payment_method = $order->payment_method;
        if (in_array('pending', $selected_order_status)) {
            $getreddemfororder = get_post_meta($order_id, 'redeem_point_once', true);
            if ($getreddemfororder != 1) {
                $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $order->user_id);
                $autoredeem = RSFunctionToApplyCoupon::update_auto_redeem_points($order_id, $order->user_id);
                if ($redeempoints != 0) {
                    $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $order->user_id);
                } else {
                    $pointsredeemed = self::perform_calculation_with_expiry($autoredeem, $order->user_id);
                }
                $date = '999999999999';

                /* Reward Points For Using Payment Gateway Method */
                //if ($points_awarded_for_this_order != 'yes') {
                if ($redeempoints != 0) {
                    $equredeemamt = self::redeeming_conversion_settings($redeempoints);
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                    self::record_the_points($order->user_id, '0', $redeempoints, $date, 'RP', '0', $equredeemamt, $order_id, '', '', '', '', $totalpoints, '', '0');
                    self::insert_earning_points($order->user_id, '0', $pointsredeemed, 'RP', $order_id, '0', '0', '');
                }
                if ($autoredeem != 0) {
                    $equredeemamt1 = self::redeeming_conversion_settings($autoredeem);
                    $totalpoints1 = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                    self::record_the_points($order->user_id, '0', $autoredeem, $date, 'RP', '0', $equredeemamt1, $order_id, '', '', '', '', $totalpoints1, '', '0');
                    self::insert_earning_points($order->user_id, '0', $pointsredeemed, 'RP', $order_id, '0', '0', '');
                }
                update_post_meta($order_id, 'redeem_point_once', 1);
            }
        }
    }

    public static function update_revised_redeem_points_for_user($order_id) {
        global $woocommerce;
        $termid = '';
        $order = new WC_Order($order_id);
        $redeempoints = self::update_revised_reward_points_to_user($order_id, $order->user_id);
        $noofdays = get_option('rs_point_to_be_expire');
        if ($redeempoints != 0) {
            $equredeemamt = self::redeeming_conversion_settings($redeempoints);

            if (($noofdays != '0') && ($noofdays != '')) {
                $date = time() + ($noofdays * 24 * 60 * 60);
            } else {
                $date = '999999999999';
            }

            self::insert_earning_points($order->user_id, $redeempoints, '0', $date, 'RVPFRP', $order_id, $redeempoints, '0', '');
            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
            self::record_the_points($order->user_id, $redeempoints, '0', $date, 'RVPFRP', '0', $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
            update_post_meta($order_id, 'redeem_point_once', 2);
        }

        $equredeemamt = self::redeeming_conversion_settings($redeempoints);

        if (($noofdays != '0') && ($noofdays != '')) {
            $date = time() + ($noofdays * 24 * 60 * 60);
        } else {
            $date = '999999999999';
        }
        $rewardgateway = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, '_payment_method', true);

        if ($rewardgateway == 'reward_gateway') {
            $total_redeem = get_post_meta($order_id, 'total_redeem_points_for_order_point_price', true);
            if ($total_redeem != '' || $total_redeem != '0') {
                self::insert_earning_points($order->user_id, $total_redeem, '0', $date, 'RVPFRPG', $order_id, $total_redeem, '0', '');
                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                self::record_the_points($order->user_id, $total_redeem, '0', $date, 'RVPFRPG', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
            }
        }
    }

    public static function getcommentstatus_post($id) {

        self::get_post_comment_user_list($id, true);
    }

    public static function get_page_comment_user_list($commentid, $approved) {
        if (get_option('rs_reward_for_comment_Page') == 'yes') {

            global $post;
            $mycomment = get_comment($commentid);
            $get_comment_post_type = get_post_type($mycomment->comment_post_ID);
            $postid = $mycomment->comment_post_ID;
            // self::rs_function_to_display_log($csvmasterlog, $user_deleted, $order_status_changed, $earnpoints, $order, $checkpoints, $postid, $orderid, $variationid, $userid, $refuserid, $reasonindetail, $redeempoints, $masterlog, $nomineeid, $usernickname, $nominatedpoints) ;
            $orderuserid = $mycomment->user_id;
            $noofdays = get_option('rs_point_to_be_expire');

            if (($noofdays != '0') && ($noofdays != '')) {
                $date = time() + ($noofdays * 24 * 60 * 60);
            } else {
                $date = '999999999999';
            }
            if ($get_comment_post_type == 'page') {

                $getuserreview = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($mycomment->user_id, 'usercommentpage' . $mycomment->comment_post_ID);
                if ($getuserreview != '1') {
                    if (($approved == true)) {
                        $getreviewpoints = RSMemberFunction::user_role_based_reward_points($mycomment->user_id, get_option("rs_reward_page_review"));
                        $restrictuserpoints = get_option('rs_max_earning_points_for_user');
                        $enabledisablemaximumpoints = get_option('rs_enable_disable_max_earning_points_for_user');
                        $currentregistrationpoints = $getreviewpoints;
                        if ($enabledisablemaximumpoints == 'yes') {
                            if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                if ($getoldpoints <= $restrictuserpoints) {
                                    $totalpointss = $getoldpoints + $currentregistrationpoints;
                                    if ($totalpointss <= $restrictuserpoints) {
                                        $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $orderuserid);
                                        $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $orderuserid);
                                        self::insert_earning_points($mycomment->user_id, $currentregistrationpoints, 0, $date, 'RPCPAR', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = self::earning_conversion_settings($currentregistrationpoints);
                                        $productid = $mycomment->comment_post_ID;
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($mycomment->user_id);
                                        self::record_the_points($mycomment->user_id, $currentregistrationpoints, '0', $date, 'RPCPAR', $equearnamt, '0', $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($mycomment->user_id, 'usercommentpage' . $mycomment->comment_post_ID, '1');
                                    } else {
                                        $insertpoints = $restrictuserpoints - $getoldpoints;
                                        self::insert_earning_points($mycomment->user_id, $insertpoints, 0, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                        $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                        $productid = $mycomment->comment_post_ID;
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        self::record_the_points($mycomment->user_id, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                    }
                                } else {
                                    RSPointExpiry::insert_earning_points($mycomment->user_id, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($mycomment->user_id, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $orderuserid);
                                $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $orderuserid);
                                self::insert_earning_points($mycomment->user_id, $currentregistrationpoints, 0, $date, 'RPCPAR', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                $equearnamt = self::earning_conversion_settings($currentregistrationpoints);
                                $productid = $mycomment->comment_post_ID;
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($mycomment->user_id);
                                self::record_the_points($mycomment->user_id, $currentregistrationpoints, '0', $date, 'RPCPAR', $equearnamt, '0', $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($mycomment->user_id, 'usercommentpage' . $mycomment->comment_post_ID, '1');
                            }
                        } else {
                            $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $orderuserid);
                            $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $orderuserid);
                            self::insert_earning_points($mycomment->user_id, $currentregistrationpoints, 0, $date, 'RPCPAR', $order_id, $totalearnedpoints, $totalredeempoints, '');
                            $equearnamt = self::earning_conversion_settings($currentregistrationpoints);
                            $productid = $mycomment->comment_post_ID;
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($mycomment->user_id);
                            self::record_the_points($mycomment->user_id, $currentregistrationpoints, '0', $date, 'RPCPAR', $equearnamt, '0', $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                            RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($mycomment->user_id, 'usercommentpage' . $mycomment->comment_post_ID, '1');
                        }
                    }
                }
            }
        }
    }

    public static function get_post_comment_user_list($commentid, $approved) {
        if (get_option('rs_reward_for_comment_Post') == 'yes') {

            global $post;
            $mycomment = get_comment($commentid);
            $get_comment_post_type = get_post_type($mycomment->comment_post_ID);
            $postid = $mycomment->comment_post_ID;
            //   self::rs_function_to_display_log($csvmasterlog, $user_deleted, $order_status_changed, $earnpoints, $order, $checkpoints, $postid, $orderid, $variationid, $userid, $refuserid, $reasonindetail, $redeempoints, $masterlog, $nomineeid, $usernickname, $nominatedpoints) ;
            $orderuserid = $mycomment->user_id;
            $noofdays = get_option('rs_point_to_be_expire');
            if (($noofdays != '0') && ($noofdays != '')) {
                $date = time() + ($noofdays * 24 * 60 * 60);
            } else {
                $date = '999999999999';
            }
            if ($get_comment_post_type == 'post') {

                $getuserreview = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($mycomment->user_id, 'usercommentpost' . $mycomment->comment_post_ID);
                if ($getuserreview != '1') {
                    if (($approved == true)) {
                        $getreviewpoints = RSMemberFunction::user_role_based_reward_points($mycomment->user_id, get_option("rs_reward_post_review"));
                        $restrictuserpoints = get_option('rs_max_earning_points_for_user');
                        $enabledisablemaximumpoints = get_option('rs_enable_disable_max_earning_points_for_user');
                        $currentregistrationpoints = $getreviewpoints;
                        if ($enabledisablemaximumpoints == 'yes') {
                            if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                if ($getoldpoints <= $restrictuserpoints) {
                                    $totalpointss = $getoldpoints + $currentregistrationpoints;
                                    if ($totalpointss <= $restrictuserpoints) {
                                        $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $orderuserid);
                                        $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $orderuserid);
                                        self::insert_earning_points($mycomment->user_id, $currentregistrationpoints, 0, $date, 'RPCPR', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = self::earning_conversion_settings($currentregistrationpoints);
                                        $productid = $mycomment->comment_post_ID;
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($mycomment->user_id);
                                        self::record_the_points($mycomment->user_id, $currentregistrationpoints, '0', $date, 'RPCPR', $equearnamt, '0', $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($mycomment->user_id, 'usercommentpost' . $mycomment->comment_post_ID, '1');
                                    } else {
                                        $insertpoints = $restrictuserpoints - $getoldpoints;
                                        self::insert_earning_points($mycomment->user_id, $insertpoints, 0, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                        $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                        $productid = $mycomment->comment_post_ID;
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        self::record_the_points($mycomment->user_id, $insertpoints, $pointsredeemed, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                    }
                                } else {
                                    RSPointExpiry::insert_earning_points($mycomment->user_id, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($mycomment->user_id, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $orderuserid);
                                $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $orderuserid);
                                self::insert_earning_points($mycomment->user_id, $currentregistrationpoints, 0, $date, 'RPCPR', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                $equearnamt = self::earning_conversion_settings($currentregistrationpoints);
                                $productid = $mycomment->comment_post_ID;
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($mycomment->user_id);
                                self::record_the_points($mycomment->user_id, $currentregistrationpoints, '0', $date, 'RPCPR', $equearnamt, '0', $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($mycomment->user_id, 'usercommentpost' . $mycomment->comment_post_ID, '1');
                            }
                        } else {
                            $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $orderuserid);
                            $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $orderuserid);
                            self::insert_earning_points($mycomment->user_id, $currentregistrationpoints, 0, $date, 'RPCPR', $order_id, $totalearnedpoints, $totalredeempoints, '');
                            $equearnamt = self::earning_conversion_settings($currentregistrationpoints);
                            $productid = $mycomment->comment_post_ID;
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($mycomment->user_id);
                            self::record_the_points($mycomment->user_id, $currentregistrationpoints, '0', $date, 'RPCPR', $equearnamt, '0', $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                            RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($mycomment->user_id, 'usercommentpost' . $mycomment->comment_post_ID, '1');
                        }
                    }
                }
            }
        }
        //  do_action('fp_reward_point_for_product_review');
    }

    public static function check_if_expiry() {

        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        $userid = get_current_user_id();
        $currentdate = time();
        $getarraystructure = $wpdb->get_results("SELECT * FROM $table_name WHERE expirydate < $currentdate and expirydate NOT IN(999999999999) and expiredpoints IN(0) and userid=$userid", ARRAY_A);
        if (!empty($getarraystructure)) {
            foreach ($getarraystructure as $key => $eacharray) {
                $wpdb->update($table_name, array('expiredpoints' => $eacharray['earnedpoints'] - $eacharray['usedpoints']), array('id' => $eacharray['id']));
            }
        }
    }

    public static function check_if_expiry_on_admin($user_id) {

        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        $userid = $user_id;
        $currentdate = time();
        $getarraystructure = $wpdb->get_results("SELECT * FROM $table_name WHERE expirydate < $currentdate and expirydate NOT IN(999999999999) and expiredpoints IN(0) and userid=$userid", ARRAY_A);
        if (!empty($getarraystructure)) {
            foreach ($getarraystructure as $key => $eacharray) {
                $wpdb->update($table_name, array('expiredpoints' => $eacharray['earnedpoints'] - $eacharray['usedpoints']), array('id' => $eacharray['id']));
            }
        }
    }

    public static function delete_if_used() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        $userid = get_current_user_id();
        $currentdate = time();
        $totalearnpoints = '';
        $totalredeempoints = '';
        $getarraystructure = $wpdb->get_results("SELECT * FROM $table_name WHERE earnedpoints=usedpoints and expiredpoints IN(0) and userid=$userid", ARRAY_A);
        if (!empty($getarraystructure)) {
            foreach ($getarraystructure as $eacharray) {
                $totalearnpoints += $eacharray['earnedpoints'];
                $totalredeempoints += $eacharray['usedpoints'];
                update_user_meta($userid, 'rs_earned_points_before_delete', $totalearnpoints);
                update_user_meta($userid, 'rs_redeem_points_before_delete', $totalredeempoints);
                $wpdb->delete($table_name, array('id' => $eacharray['id']));
            }
        }

        $getdata = $wpdb->get_results("SELECT * FROM $table_name WHERE earnedpoints=(usedpoints+expiredpoints) and expiredpoints NOT IN(0) and userid=$userid", ARRAY_A);
        $totalexpiredpoints = '';
        if (!empty($getdata)) {
            foreach ($getdata as $array) {
                $totalexpiredpoints += $array['expiredpoints'];
                update_user_meta($userid, 'rs_expired_points_before_delete', $totalexpiredpoints);
                $wpdb->delete($table_name, array('id' => $array['id']));
            }
        }
    }

    /* Get the SUM of available Points after performing few more audits */

    public static function get_sum_of_earned_points($userid) {

        global $wpdb;
        $table_name = $wpdb->prefix . "rspointexpiry";
        $getcurrentuserid = $userid;
        $current_user_points_log = $wpdb->get_results("SELECT SUM(earnedpoints) as availablepoints FROM $table_name WHERE earnedpoints NOT IN(0) and userid=$getcurrentuserid", ARRAY_A);
        $total_points_earned = "";
        $totaloldearnedpoints="";
        foreach ($current_user_points_log as $separate_points) {
            $deletedearnedpoints = get_user_meta($getcurrentuserid, 'rs_earned_points_before_delete', true);
            $total_earned_points = get_user_meta($getcurrentuserid, 'rs_user_total_earned_points', true);
            $oldearnedpoints = get_user_meta($getcurrentuserid, '_my_reward_points', true);
            if ($total_earned_points > $oldearnedpoints) {
                $totaloldearnedpoints = $total_earned_points - $oldearnedpoints;
            }
            $total_points_earned = $separate_points['availablepoints'] + $deletedearnedpoints + $totaloldearnedpoints;
        }
        return $total_points_earned;
    }

    /* Get the SUM of available Points with order id */

    public static function get_sum_of_total_earned_points($userid) {

        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        $getcurrentuserid = $userid;
        if ($getcurrentuserid != '') {
            $usedpoints = $wpdb->get_results("SELECT usedpoints FROM $table_name WHERE usedpoints IS NULL", ARRAY_A);
            $checkresults = $wpdb->get_results("SELECT SUM((earnedpoints-usedpoints)) as availablepoints FROM $table_name WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) and userid = $getcurrentuserid", ARRAY_A);
            foreach ($checkresults as $checkresultss) {
                $checkresult = $checkresultss['availablepoints'] != NULL ? $checkresultss['availablepoints'] : 0;
            }
            return $checkresult;
        }
    }

    /* Insert the Data based on Point Expiry */

    public static function insert_earning_points($user_id, $earned_points, $usedpoints, $date, $checkpoints, $orderid, $totalearnedpoints, $totalredeempoints, $reasonindetail = '') {

        global $wpdb;
        $table_name = $wpdb->prefix . "rspointexpiry";
        $currentdate = time();
        $noofday = get_option('rs_point_to_be_expire');
        $expirydate = 999999999999;
        if (($noofday == '') || ($noofday == '0')) {
            $query = $wpdb->get_row("SELECT * FROM $table_name WHERE userid = $user_id and expirydate = $expirydate", ARRAY_A);
            if (!empty($query)) {
                $id = $query['id'];
                $oldearnedpoints = $query['earnedpoints'];
                $oldearnedpoints = $oldearnedpoints + $earned_points;
                $usedpoints = $usedpoints + $query['usedpoints'];
                $wpdb->update($table_name, array('earnedpoints' => $oldearnedpoints, 'usedpoints' => $usedpoints), array('id' => $id));
            } else {
                $wpdb->insert(
                        $table_name, array(
                    'earnedpoints' => $earned_points,
                    'usedpoints' => $usedpoints,
                    'expiredpoints' => '0',
                    'userid' => $user_id,
                    'earneddate' => $currentdate,
                    'expirydate' => $date,
                    'checkpoints' => $checkpoints,
                    'orderid' => $orderid,
                    'totalearnedpoints' => $totalearnedpoints,
                    'totalredeempoints' => $totalredeempoints,
                    'reasonindetail' => $reasonindetail
                ));
            }
        } else {
            $wpdb->insert(
                    $table_name, array(
                'earnedpoints' => $earned_points,
                'usedpoints' => $usedpoints,
                'expiredpoints' => '0',
                'userid' => $user_id,
                'earneddate' => $currentdate,
                'expirydate' => $date,
                'checkpoints' => $checkpoints,
                'orderid' => $orderid,
                'totalearnedpoints' => $totalearnedpoints,
                'totalredeempoints' => $totalredeempoints,
                'reasonindetail' => $reasonindetail
            ));
        }
    }

    public static function record_the_points($user_id, $earned_points, $usedpoints, $date, $checkpoints, $equearnamt, $equredeemamt, $orderid, $productid, $variationid, $refuserid, $reasonindetail, $totalpoints, $nomineeid, $nomineepoints) {

        global $wpdb;
        $table_name = $wpdb->prefix . "rsrecordpoints";
        $timeformat = get_option('time_format');
        $dateformat = get_option('date_format') . ' ' . $timeformat;
        $currentdate = date_i18n($dateformat);
        $wpdb->insert(
                $table_name, array(
            'earnedpoints' => $earned_points,
            'redeempoints' => $usedpoints,
            'userid' => $user_id,
            'earneddate' => $currentdate,
            'expirydate' => $date,
            'checkpoints' => $checkpoints,
            'earnedequauivalentamount' => $equearnamt,
            'redeemequauivalentamount' => $equredeemamt,
            'productid' => $productid,
            'variationid' => $variationid,
            'orderid' => $orderid,
            'refuserid' => $refuserid,
            'reasonindetail' => $reasonindetail,
            'totalpoints' => $totalpoints,
            'showmasterlog' => "false",
            'showuserlog' => "false",
            'nomineeid' => $nomineeid,
            'nomineepoints' => $nomineepoints
        ));
    }

    public static function perform_calculation_with_expiry($redeempoints, $getcurrentuserid) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        $getarraystructure = $wpdb->get_results("SELECT * FROM $table_name WHERE earnedpoints-usedpoints NOT IN(0) and  expiredpoints IN(0) and userid=$getcurrentuserid ORDER BY expirydate ASC", ARRAY_A);

        if (is_array($getarraystructure)) {
            foreach ($getarraystructure as $key => $eachrow) {
                $getactualpoints = $eachrow['earnedpoints'] - $eachrow['usedpoints'];
                if ($redeempoints >= $getactualpoints) {
                    $getusedpoints = $getactualpoints;
                    $usedpoints = $eachrow['usedpoints'] + $getusedpoints;
                    $id = $eachrow['id'];
                    $redeempoints = $redeempoints - $getactualpoints;

                    $wpdb->query("UPDATE $table_name SET usedpoints = $usedpoints WHERE id = $id");
                    if ($redeempoints == 0) {
                        break;
                    }
                } else {
                    $getusedpoints = $redeempoints;
                    $usedpoints = $eachrow['usedpoints'] + $getusedpoints;
                    $id = $eachrow['id'];

                    $wpdb->query("UPDATE $table_name SET usedpoints = $usedpoints  WHERE id = $id");
                    break;
                }
            }
        }
        return;
    }

    public static function update_revised_points_for_user($order_id) {
        //$points_awarded_for_this_order = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, 'rs_revised_points');
        //if($points_awarded_for_this_order != 'yes'){

        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        $table_name2 = $wpdb->prefix . 'rsrecordpoints';
        global $woocommerce;
        $termid = '';
        $order = new WC_Order($order_id);
        $redeempoints = '0';
        $noofdays = get_option('rs_point_to_be_expire');



        $restrictuserpoints = get_option('rs_max_earning_points_for_user');
        $enabledisablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
        /* Reward Points For Using Payment Gateway Method */
        if (($noofdays != '0') && ($noofdays != '')) {
            $date = time() + ($noofdays * 24 * 60 * 60);
        } else {
            $date = '999999999999';
        }
        $checkredeeming = self::check_redeeming_in_order($order_id, $order->user_id);
        $enableoption = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, 'rs_check_enable_option_for_redeeming');
        if ($enableoption == 'yes') {
            if ($checkredeeming == false) {
                $getpaymentgatewayused = RSMemberFunction::user_role_based_reward_points($order->user_id, get_option('rs_reward_payment_gateways_' . $order->payment_method));
                if ($getpaymentgatewayused != '') {
                    $totalearnedpoints = '0';
                    $totalredeempoints = '0';
                    $check_points_payment = self::get_sum_of_total_earned_points($order->user_id);
                    $totalpoints = $check_points_payment - $getpaymentgatewayused;
                    $equredeemamt = self::redeeming_conversion_settings($getpaymentgatewayused);
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                    self::record_the_points($order->user_id, '0', $getpaymentgatewayused, $date, 'RVPFRPG', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                    self::insert_earning_points($order->user_id, '0', $getpaymentgatewayused, $date, 'RVPFRPG', $order_id, $totalearnedpoints, $totalredeempoints, '');
                    $gettotalearnedpoints = $wpdb->get_results("SELECT SUM((earnedpoints)) as availablepoints FROM $table_name WHERE orderid = $order_id", ARRAY_A);
                    $totalearnedpoints = ($gettotalearnedpoints[0]['availablepoints'] != NULL) ? $gettotalearnedpoints[0]['availablepoints'] : 0;
                    $totalredeempoints = $redeempoints;
                    $wpdb->query("UPDATE $table_name SET totalearnedpoints = $totalearnedpoints,totalredeempoints = $totalredeempoints WHERE orderid = $order_id");
                }
            }
        } else {
            $getpaymentgatewayused = RSMemberFunction::user_role_based_reward_points($order->user_id, get_option('rs_reward_payment_gateways_' . $order->payment_method));
            if ($getpaymentgatewayused != '') {
                $totalearnedpoints = '0';
                $totalredeempoints = '0';
                $check_points_payment = self::get_sum_of_total_earned_points($order->user_id);
                $totalpoints = $check_points_payment - $getpaymentgatewayused;
                $equredeemamt = self::redeeming_conversion_settings($getpaymentgatewayused);
                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                self::record_the_points($order->user_id, '0', $getpaymentgatewayused, $date, 'RVPFRPG', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                self::insert_earning_points($order->user_id, '0', $getpaymentgatewayused, $date, 'RVPFRPG', $order_id, $totalearnedpoints, $totalredeempoints, '');
                $gettotalearnedpoints = $wpdb->get_results("SELECT SUM((earnedpoints)) as availablepoints FROM $table_name WHERE orderid = $order_id", ARRAY_A);
                $totalearnedpoints = ($gettotalearnedpoints[0]['availablepoints'] != NULL) ? $gettotalearnedpoints[0]['availablepoints'] : 0;
                $totalredeempoints = $redeempoints;
                $wpdb->query("UPDATE $table_name SET totalearnedpoints = $totalearnedpoints,totalredeempoints = $totalredeempoints WHERE orderid = $order_id");
            }
        }

        $product_ids = get_post_meta($order_id, 'points_for_current_order', true);
        /* Reward Points For Purchasing the Product */
        foreach ($order->get_items() as $item) {
            $value = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, 'rs_revised_points_once', true);
            if ($value != '1') {
                $productid = $item['product_id'];
                $variationid = $item['variation_id'] == '0' || '' ? $item['product_id'] : $item['variation_id'];
                $itemquantity = $item['qty'];
                $orderuserid = $order->user_id;
                $term = get_the_terms($productid, 'product_cat');
                if (is_array($term)) {
                    foreach ($term as $terms) {
                        $termid = $terms->term_id;
                    }
                }
                //For Inserting Reward Points
                $checked_level_for_reward_points = self::check_level_of_enable_reward_point($productid, $variationid, $termid);
                $equearnamt = '';
                $equredeemamt = '';
                if (array_key_exists($variationid, $product_ids)) {
                    self::rs_insert_the_selected_level_revised_reward_points($redeempoints, $checked_level_for_reward_points, $variationid, $variationid, $itemquantity, $orderuserid, $termid, $equearnamt, $equredeemamt, $order_id, $product_ids, $item);
                }
                $gettotalearnedpoints = $wpdb->get_results("SELECT SUM((earnedpoints)) as availablepoints FROM $table_name WHERE orderid = $order_id", ARRAY_A);
                $totalearnedpoints = ($gettotalearnedpoints[0]['availablepoints'] != NULL) ? $gettotalearnedpoints[0]['availablepoints'] : 0;
                $totalredeempoints = $redeempoints;
                $wpdb->query("UPDATE $table_name SET totalearnedpoints = $totalearnedpoints,totalredeempoints = $totalredeempoints WHERE orderid = $order_id");

                $referreduser = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, '_referrer_name');
                if ($referreduser != '') {
                    //For Inserting Referral Reward Points
                    $checked_level_for_referral_reward_points = self::check_level_of_enable_referral_reward_point($productid, $variationid, $termid);
                    self::rs_insert_the_selected_level_revised_referral_reward_points($redeempoints, $referreduser, $equearnamt, $equredeemamt, $checked_level_for_referral_reward_points, $productid, $variationid, $itemquantity, $orderuserid, $termid, $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                    $gettotalearnedpoints = $wpdb->get_results("SELECT SUM((earnedpoints)) as availablepoints FROM $table_name WHERE orderid = $order_id", ARRAY_A);
                    $totalearnedpoints = ($gettotalearnedpoints[0]['availablepoints'] != NULL) ? $gettotalearnedpoints[0]['availablepoints'] : 0;
                    $totalredeempoints = $redeempoints;
                    $equredeemamt = self::redeeming_conversion_settings($totalredeempoints);
                    $wpdb->query("UPDATE $table_name SET totalearnedpoints = $totalearnedpoints,totalredeempoints = $totalredeempoints WHERE orderid = $order_id");
                }
                //self::update_revised_reward_points_to_user($order_id,$orderuserid);
            }
            update_post_meta($order_id, 'earning_point_once', 2);
            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($order_id, 'rs_revised_points_once', 1);
        }
        //}
    }

    public static function update_revised_reward_points_to_user($order_id, $orderuserid) {
        // Inside Loop
        $order = new WC_Order($order_id);
        $rewardpointscoupons = $order->get_items(array('coupon'));
        $getuserdatabyid = get_user_by('id', $orderuserid);
        $getusernickname = $getuserdatabyid->user_login;
        $maincouponchecker = 'sumo_' . strtolower($getusernickname);
        $auto_redeem_name = 'auto_redeem_' . strtolower($getusernickname);
        foreach ($rewardpointscoupons as $couponcode => $value) {
            if ($maincouponchecker == $value['name']) {
                if (get_option('rewardsystem_looped_over_coupon' . $order_id) != '1') {
                    $getuserdatabyid = get_user_by('id', $orderuserid);
                    $getusernickname = $getuserdatabyid->user_login;
                    $getcouponid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($orderuserid, 'redeemcouponids', true);
                    $currentamount = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($getcouponid, 'coupon_amount');
                    if ($currentamount >= $value['discount_amount']) {
                        $current_conversion = wc_format_decimal(get_option('rs_redeem_point'));
                        $point_amount = wc_format_decimal(get_option('rs_redeem_point_value'));
                        $redeemedamount = $value['discount_amount'] * $current_conversion;
                        $redeemedpoints = $redeemedamount / $point_amount;
                    }
                    return $redeemedpoints;
                    update_option('rewardsystem_looped_over_coupon' . $order_id, '1');
                }
            }
            if ($auto_redeem_name == $value['name']) {
                if (get_option('rewardsystem_looped_over_coupon' . $order_id) != '1') {
                    $getuserdatabyid = get_user_by('id', $orderuserid);
                    $getusernickname = $getuserdatabyid->user_login;
                    $getcouponid = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($orderuserid, 'auto_redeemcoupon_ids', true);
                    $currentamount = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($getcouponid, 'coupon_amount');
                    if ($currentamount >= $value['discount_amount']) {
                        $current_conversion = wc_format_decimal(get_option('rs_redeem_point'));
                        $point_amount = wc_format_decimal(get_option('rs_redeem_point_value'));
                        $redeemedamount = $value['discount_amount'] * $current_conversion;
                        $redeemedpoints = $redeemedamount / $point_amount;
                    }
                    return $redeemedpoints;
                    update_option('rewardsystem_looped_over_coupon' . $order_id, '1');
                }
            }
        }
    }

    public static function rs_insert_the_selected_level_revised_reward_points($pointsredeemed, $level, $productid, $variationid, $itemquantity, $orderuserid, $termid, $equearnamt, $equredeemamt, $order_id, $product_ids, $item) {
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

        $checkredeeming = self::check_redeeming_in_order($order_id, $orderuserid);
        $enableoption = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, 'rs_check_enable_option_for_redeeming');
        $noofdays = get_option('rs_point_to_be_expire');

        if (($noofdays != '0') && ($noofdays != '')) {
            $date = time() + ($noofdays * 24 * 60 * 60);
        } else {
            $date = '999999999999';
        }
        if (!empty($product_ids)) {
            foreach ($product_ids as $key => $value) {
                $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                self::insert_earning_points($orderuserid, $pointsredeemed, $value, $date, 'RVPFPPRP', $order_id, $totalearnedpoints, $totalredeempoints, '');
                $equearnamt = self::earning_conversion_settings($pointsredeemed);
                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                if ($totalpoints > 0) {
                    self::record_the_points($orderuserid, '0', $value, $date, 'RVPFPPRP', $equearnamt, $equredeemamt, $order_id, $key, $key, '', '', $totalpoints, '', '0');
                }
            }
        }
    }

    public static function rs_insert_the_selected_level_revised_referral_reward_points($pointsredeemed, $referreduser, $equearnamt, $equredeemamt, $level, $productid, $variationid, $itemquantity, $orderuserid, $termid, $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail, $item) {
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

                $checkredeeming = self::check_redeeming_in_order($order_id, $orderuserid);
                $enableoption = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, 'rs_check_enable_option_for_redeeming');

                $noofdays = get_option('rs_point_to_be_expire');

                if (($noofdays != '0') && ($noofdays != '')) {
                    $date = time() + ($noofdays * 24 * 60 * 60);
                } else {
                    $date = '999999999999';
                }
                switch ($level) {
                    case '1':
                        if ($productlevelrewardtype == '1') {
                            if ($enableoption == 'yes') {
                                if ($checkredeeming == false) {
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                    self::insert_earning_points($myid, $pointsredeemed, $productlevelrewardpointss, $date, 'RVPFPPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                    self::record_the_points($myid, $pointsredeemed, $productlevelrewardpointss, $date, 'RVPFPPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                }
                            } else {
                                $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints) * $itemquantity;
                                self::insert_earning_points($myid, $pointsredeemed, $productlevelrewardpointss, $date, 'RVPFPPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                self::record_the_points($myid, $pointsredeemed, $productlevelrewardpointss, $date, 'RVPFPPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                            }
                        } else {
                            if ($enableoption == 'yes') {
                                if ($checkredeeming == false) {
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                    self::insert_earning_points($myid, $pointsredeemed, $productlevelrewardpercentss, $date, 'RVPFPPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                    self::record_the_points($myid, $pointsredeemed, $productlevelrewardpercentss, $date, 'RVPFPPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                }
                            } else {
                                $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent) * $itemquantity;
                                self::insert_earning_points($myid, $pointsredeemed, $productlevelrewardpercentss, $date, 'RVPFPPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                self::record_the_points($myid, $pointsredeemed, $productlevelrewardpercentss, $date, 'RVPFPPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                            }
                        }
                        break;
                    case '2':
                        if ($categorylevelrewardtype == '1') {
                            if ($enableoption == 'yes') {
                                if ($checkredeeming == false) {
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                    self::insert_earning_points($myid, $pointsredeemed, $categorylevelrewardpointss, $date, 'RVPFPPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                    self::record_the_points($myid, $pointsredeemed, $categorylevelrewardpointss, $date, 'RVPFPPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                }
                            } else {
                                $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints) * $itemquantity;
                                self::insert_earning_points($myid, $pointsredeemed, $categorylevelrewardpointss, $date, 'RVPFPPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                self::record_the_points($myid, $pointsredeemed, $categorylevelrewardpointss, $date, 'RVPFPPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                            }
                        } else {
                            if ($enableoption == 'yes') {
                                if ($checkredeeming == false) {
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                    self::insert_earning_points($myid, $pointsredeemed, $categorylevelrewardpercents, $date, 'RVPFPPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                    self::record_the_points($myid, $pointsredeemed, $categorylevelrewardpercents, $date, 'RVPFPPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                }
                            } else {
                                $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent) * $itemquantity;
                                self::insert_earning_points($myid, $pointsredeemed, $categorylevelrewardpercents, $date, 'RVPFPPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                self::record_the_points($myid, $pointsredeemed, $categorylevelrewardpercents, $date, 'RVPFPPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                            }
                        }
                        break;
                    case '3':
                        if ($global_reward_type == '1') {
                            if ($enableoption == 'yes') {
                                if ($checkredeeming == false) {
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                    self::insert_earning_points($myid, $pointsredeemed, $global_rewardpointss, $date, 'RVPFPPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                    self::record_the_points($myid, $pointsredeemed, $global_rewardpointss, $date, 'RVPFPPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                }
                            } else {
                                $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints) * $itemquantity;
                                self::insert_earning_points($myid, $pointsredeemed, $global_rewardpointss, $date, 'RVPFPPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                self::record_the_points($myid, $pointsredeemed, $global_rewardpointss, $date, 'RVPFPPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                            }
                        } else {
                            if ($enableoption == 'yes') {
                                if ($checkredeeming == false) {
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                    self::insert_earning_points($referreduser, $pointsredeemed, $global_rewardpercents, $date, 'RVPFPPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                    self::record_the_points($myid, $pointsredeemed, $global_rewardpercents, $date, 'RVPFPPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                                }
                            } else {
                                $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent) * $itemquantity;
                                self::insert_earning_points($referreduser, $pointsredeemed, $global_rewardpercents, $date, 'RVPFPPRRP', $order_id, $totalearnedpoints, $totalredeempoints, $reasonindetail);
                                $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($myid);
                                self::record_the_points($myid, $pointsredeemed, $global_rewardpercents, $date, 'RVPFPPRRP', $equearnamt, $equredeemamt, $order_id, $productid, $variationid, $orderuserid, '', $totalpoints, '', '0');
                            }
                        }
                        break;
                }
            }
        }
    }

    public static function rs_function_to_total_earned_point_for_order($order_id, $earned_points) {
        //$total_points = $earned_points;
    }

    public static function rs_function_to_total_redeem_point_for_order($order_id, $redeemed_points) {
        
    }

    public static function rs_function_to_provide_points_for_renewal_order($order_id) {
        if (get_option('rs_award_point_for_renewal_order') == 'yes' && get_post_meta($order_id, 'sumo_renewal_order_date', true) != '') {
            return false;
        } else {
            return true;
        }
    }

    /*
     * @ updates earning points for user in db
     *
     */

    public static function update_earning_points_for_user($order_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        $table_name2 = $wpdb->prefix . 'rsrecordpoints';
        global $woocommerce;
        $termid = '';
        $order = new WC_Order($order_id);
        $order_status = $order->post_status;

        $order_status = str_replace('wc-', '', $order_status);

        $earningpointonce = get_post_meta($order_id, 'earning_point_once', true);
        if ($earningpointonce != '1') {
            $fp_earned_points_sms = false;
            do_action('rs_perform_action_for_order', $order_id);
            $redeempoints = '0';
            $pointsredeemed = '0';

            //$points_awarded_for_this_order = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, 'reward_points_awarded');
            $restrictuserpoints = get_option('rs_max_earning_points_for_user');
            $enabledisablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
            $noofdays = get_option('rs_point_to_be_expire');

            if (($noofdays != '0') && ($noofdays != '')) {
                $date = time() + ($noofdays * 24 * 60 * 60);
            } else {
                $date = '999999999999';
            }
            /* Reward Points For Using Payment Gateway Method */
            //if ($points_awarded_for_this_order != 'yes') {

            $banning_type = FPRewardSystem::check_banning_type($order->user_id);
            if ($banning_type != 'earningonly' && $banning_type != 'both') {
                $checkredeeming = self::check_redeeming_in_order($order_id, $order->user_id);
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
                                            $equearnamt = self::earning_conversion_settings($getpaymentgatewayused);
                                            self::insert_earning_points($order->user_id, $getpaymentgatewayused, $pointsredeemed, $date, 'RPG', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                                            self::record_the_points($order->user_id, $getpaymentgatewayused, '0', $date, 'RPG', $equearnamt, '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                            $gettotalearnedpoints = $wpdb->get_results("SELECT SUM((earnedpoints)) as availablepoints FROM $table_name WHERE orderid = $order_id", ARRAY_A);
                                            $totalearnedpoints = ($gettotalearnedpoints[0]['availablepoints'] != NULL) ? $gettotalearnedpoints[0]['availablepoints'] : 0;
                                            $totalredeempoints = '0';
                                            $wpdb->query("UPDATE $table_name SET totalearnedpoints = $totalearnedpoints,totalredeempoints = $totalredeempoints WHERE orderid = $order_id");
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
                                    $equearnamt = self::earning_conversion_settings($getpaymentgatewayused);
                                    self::insert_earning_points($order->user_id, $getpaymentgatewayused, $pointsredeemed, $date, 'RPG', $order_id, '0', '0', '');
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                                    self::record_the_points($order->user_id, $getpaymentgatewayused, '0', $date, 'RPG', $equearnamt, '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                    $gettotalearnedpoints = $wpdb->get_results("SELECT SUM((earnedpoints)) as availablepoints FROM $table_name WHERE orderid = $order_id", ARRAY_A);
                                    $totalearnedpoints = ($gettotalearnedpoints[0]['availablepoints'] != NULL) ? $gettotalearnedpoints[0]['availablepoints'] : 0;
                                    $totalredeempoints = '0';
                                    $wpdb->query("UPDATE $table_name SET totalearnedpoints = $totalearnedpoints,totalredeempoints = $totalredeempoints WHERE orderid = $order_id");
                                }
                            } else {
                                $equearnamt = self::earning_conversion_settings($getpaymentgatewayused);
                                self::insert_earning_points($order->user_id, $getpaymentgatewayused, $pointsredeemed, 'RPG', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                                self::record_the_points($order->user_id, $getpaymentgatewayused, '0', $date, 'RPG', $equearnamt, '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                $gettotalearnedpoints = $wpdb->get_results("SELECT SUM((earnedpoints)) as availablepoints FROM $table_name WHERE orderid = $order_id", ARRAY_A);
                                $totalearnedpoints = ($gettotalearnedpoints[0]['availablepoints'] != NULL) ? $gettotalearnedpoints[0]['availablepoints'] : 0;
                                $totalredeempoints = '0';
                                $wpdb->query("UPDATE $table_name SET totalearnedpoints = $totalearnedpoints,totalredeempoints = $totalredeempoints WHERE orderid = $order_id");
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
                                        $equearnamt = self::earning_conversion_settings($getpaymentgatewayused);
                                        self::insert_earning_points($order->user_id, $getpaymentgatewayused, $pointsredeemed, $date, 'RPG', $order_id, '0', '0', '');
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                                        self::record_the_points($order->user_id, $getpaymentgatewayused, '0', $date, 'RPG', $equearnamt, '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        $gettotalearnedpoints = $wpdb->get_results("SELECT SUM((earnedpoints)) as availablepoints FROM $table_name WHERE orderid = $order_id", ARRAY_A);
                                        $totalearnedpoints = ($gettotalearnedpoints[0]['availablepoints'] != NULL) ? $gettotalearnedpoints[0]['availablepoints'] : 0;
                                        $totalredeempoints = '0';
                                        $wpdb->query("UPDATE $table_name SET totalearnedpoints = $totalearnedpoints,totalredeempoints = $totalredeempoints WHERE orderid = $order_id");
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
                                $equearnamt = self::earning_conversion_settings($getpaymentgatewayused);
                                self::insert_earning_points($order->user_id, $getpaymentgatewayused, $pointsredeemed, $date, 'RPG', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                                self::record_the_points($order->user_id, $getpaymentgatewayused, '0', $date, 'RPG', $equearnamt, '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                $gettotalearnedpoints = $wpdb->get_results("SELECT SUM((earnedpoints)) as availablepoints FROM $table_name WHERE orderid = $order_id", ARRAY_A);
                                $totalearnedpoints = ($gettotalearnedpoints[0]['availablepoints'] != NULL) ? $gettotalearnedpoints[0]['availablepoints'] : 0;
                                $totalredeempoints = '0';
                                $wpdb->query("UPDATE $table_name SET totalearnedpoints = $totalearnedpoints,totalredeempoints = $totalredeempoints WHERE orderid = $order_id");
                            }
                        } else {
                            $equearnamt = self::earning_conversion_settings($getpaymentgatewayused);
                            self::insert_earning_points($order->user_id, $getpaymentgatewayused, $pointsredeemed, $date, 'RPG', $order_id, '0', '0', '');
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                            self::record_the_points($order->user_id, $getpaymentgatewayused, '0', $date, 'RPG', $equearnamt, '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            $gettotalearnedpoints = $wpdb->get_results("SELECT SUM((earnedpoints)) as availablepoints FROM $table_name WHERE orderid = $order_id", ARRAY_A);
                            $totalearnedpoints = ($gettotalearnedpoints[0]['availablepoints'] != NULL) ? $gettotalearnedpoints[0]['availablepoints'] : 0;
                            $totalredeempoints = '0';
                            $wpdb->query("UPDATE $table_name SET totalearnedpoints = $totalearnedpoints,totalredeempoints = $totalredeempoints WHERE orderid = $order_id");
                        }
                    }
                }
            }
            do_action('fp_reward_point_for_using_gateways');
            //}

            /* Reward Points For Purchasing the Product */
            $award_points_for_renewal_order = self::rs_function_to_provide_points_for_renewal_order($order_id);
            if ($award_points_for_renewal_order == true) {
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
                        $checked_level_for_reward_points = self::check_level_of_enable_reward_point($productid, $variationid, $termid);
                        $equearnamt = '';
                        $equredeemamt = '';
                        self::rs_insert_the_selected_level_in_reward_points($pointsredeemed, $checked_level_for_reward_points, $productid, $variationid, $itemquantity, $orderuserid, $termid, $equearnamt, $equredeemamt, $order_id, $item);
                        $gettotalearnedpoints = $wpdb->get_results("SELECT SUM((earnedpoints)) as availablepoints FROM $table_name WHERE orderid = $order_id", ARRAY_A);
                        $totalearnedpoints = ($gettotalearnedpoints[0]['availablepoints'] != NULL) ? $gettotalearnedpoints[0]['availablepoints'] : 0;
                        $totalredeempoints = ($redeempoints != null) ? $redeempoints : 0;
                        $wpdb->query("UPDATE $table_name SET totalearnedpoints = $totalearnedpoints WHERE orderid = $order_id");
                        $wpdb->query("UPDATE $table_name SET totalredeempoints = $totalredeempoints WHERE orderid = $order_id");

                        $referreduser = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, '_referrer_name');
                        if ($referreduser != '') {
                            //For Inserting Referral Reward Points
                            $checked_level_for_referral_reward_points = self::check_level_of_enable_referral_reward_point($productid, $variationid, $termid);
                            self::rs_insert_the_selected_level_in_referral_reward_points($pointsredeemed, $referreduser, $equearnamt, $equredeemamt, $checked_level_for_referral_reward_points, $productid, $variationid, $itemquantity, $orderuserid, $termid, $order_id, $totalearnedpoints, $totalredeempoints, '', $item);
                            $gettotalearnedpoints = $wpdb->get_results("SELECT SUM((earnedpoints)) as availablepoints FROM $table_name WHERE orderid = $order_id", ARRAY_A);
                            $totalearnedpoints = ($gettotalearnedpoints[0]['availablepoints'] != NULL) ? $gettotalearnedpoints[0]['availablepoints'] : 0;
                            $totalredeempoints = ($redeempoints != null) ? $redeempoints : 0;
                            $equredeemamt = self::redeeming_conversion_settings($totalredeempoints);
                            $wpdb->query("UPDATE $table_name SET totalearnedpoints = $totalearnedpoints WHERE orderid = $order_id");
                            $wpdb->query("UPDATE $table_name SET totalredeempoints = $totalredeempoints WHERE orderid = $order_id");
                        } else {
                            $referrer_id = RSFunctionForManualReferralLink::rs_perform_manual_link_referer($order->user_id);
                            if ($referrer_id != false) {
                                $checked_level_for_referral_reward_points = self::check_level_of_enable_referral_reward_point($productid, $variationid, $termid);
                                self::rs_insert_the_reward_points_for_manuall_referrer($pointsredeemed, $referrer_id, $equearnamt, $equredeemamt, $checked_level_for_referral_reward_points, $productid, $variationid, $itemquantity, $orderuserid, $termid, $order_id, $totalearnedpoints, $totalredeempoints, '', $item);
                                $gettotalearnedpoints = $wpdb->get_results("SELECT SUM((earnedpoints)) as availablepoints FROM $table_name WHERE orderid = $order_id", ARRAY_A);
                                $totalearnedpoints = ($gettotalearnedpoints[0]['availablepoints'] != NULL) ? $gettotalearnedpoints[0]['availablepoints'] : 0;
                                $totalredeempoints = $redeempoints;
                                $equredeemamt = self::redeeming_conversion_settings($totalredeempoints);
                                $wpdb->query("UPDATE $table_name SET totalearnedpoints = $totalearnedpoints,totalredeempoints = $totalredeempoints WHERE orderid = $order_id");
                            }
                        }

                        RSFunctionForCouponRewardPoints::apply_coupon_code_reward_points_user($order_id);
                        RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $orderuserid);
                        RSFunctionForEmailTemplate::rsmail_sending_on_custom_rule($orderuserid, $order_id);
                        $gettotalearnedpoints = $wpdb->get_results("SELECT SUM((earnedpoints)) as availablepoints FROM $table_name WHERE orderid = $order_id", ARRAY_A);
                        $totalearnedpoints = ($gettotalearnedpoints[0]['availablepoints'] != NULL) ? $gettotalearnedpoints[0]['availablepoints'] : 0;
                        if ($totalearnedpoints != 0 && $totalearnedpoints != '') {
                            if (get_option('rs_send_sms_earning_points') == 'yes') {
                                $fp_earned_points_sms = true;
                            }
                        }
                        if ($fp_earned_points_sms == true) {
                            if (get_option('rs_enable_send_sms_to_user') == 'yes') {
                                if (get_option('rs_send_sms_earning_points') == 'yes') {
                                    if (get_option('rs_sms_sending_api_option') == '1') {
                                        RSFunctionForSms::send_sms_twilio_api($order_id);
                                    } else {
                                        RSFunctionForSms::send_sms_nexmo_api($order_id);
                                    }
                                }
                            }
                        }
                    }
                }
                update_user_meta($order->user_id, 'rsfirsttime_redeemed', 1);
                $return = self::check_weather_the_points_is_awarded_for_order($order_id);
                if (is_array($return)) {
                    if (in_array(1, $return)) {
                        add_post_meta($order_id, 'reward_points_awarded', 'yes');
                    }
                }
                do_action('fp_reward_point_for_product_purchase');
            }

            $oldorderid = get_user_meta($orderuserid, 'rs_no_of_purchase_for_user', true);
            $getorderid = (array) $order_id;
            if ($oldorderid == '') {
                update_user_meta($orderuserid, 'rs_no_of_purchase_for_user', $getorderid);
            } else {
                $mergearray = array_merge($oldorderid, (array) $getorderid);
                update_user_meta($orderuserid, 'rs_no_of_purchase_for_user', $mergearray);
            }
            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($order_id, 'rs_revised_points_once', 2);
            update_post_meta($order_id, 'earning_point_once', 1);
        }
    }

    public static function check_weather_the_points_is_awarded_for_order($order_id) {
        $order = new WC_Order($order_id);
        foreach ($order->get_items() as $item) {
            $termid = '';
            $productid = $item['product_id'];
            $variationid = $item['variation_id'] == '0' || '' ? '0' : $item['variation_id'];
            $term = get_the_terms($productid, 'product_cat');
            if (is_array($term)) {
                foreach ($term as $terms) {
                    $termid = $terms->term_id;
                }
            }
            $checked_level_for_reward_points = self::check_reward_point_for_order($productid, $variationid, $termid);
            $array[] = $checked_level_for_reward_points;
        }
        return $array;
    }

    public static function check_reward_point_for_order($productid, $variationid, $termid) {
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
                                    return '1';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_rewardpoints != '') {
                                                return '1';
                                            }
                                        } else {
                                            if ($global_rewardpercent != '') {
                                                return '1';
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($categorylevelrewardpercent != '') {
                                    return '1';
                                }
                            }
                        } else {
                            if ($global_enable == '1') {
                                if ($global_reward_type == '1') {
                                    if ($global_rewardpoints != '') {
                                        return '1';
                                    }
                                } else {
                                    if ($global_rewardpercent != '') {
                                        return '1';
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                if ($global_rewardpoints != '') {
                                    return '1';
                                }
                            } else {
                                if ($global_rewardpercent != '') {
                                    return '1';
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
                                    return '1';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_rewardpoints != '') {
                                                return '1';
                                            }
                                        } else {
                                            if ($global_rewardpercent != '') {
                                                return '1';
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($categorylevelrewardpercent != '') {
                                    return '1';
                                } else {
                                    if ($global_enable == '1') {
                                        if ($global_reward_type == '1') {
                                            if ($global_rewardpoints != '') {
                                                return '1';
                                            }
                                        } else {
                                            if ($global_rewardpercent != '') {
                                                return '1';
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($global_enable == '1') {
                                if ($global_reward_type == '1') {
                                    if ($global_rewardpoints != '') {
                                        return '1';
                                    }
                                } else {
                                    if ($global_rewardpercent != '') {
                                        return '1';
                                    }
                                }
                            }
                        }
                    } else {
                        if ($global_enable == '1') {
                            if ($global_reward_type == '1') {
                                if ($global_rewardpoints != '') {
                                    return '1';
                                }
                            } else {
                                if ($global_rewardpercent != '') {
                                    return '1';
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

    public static function check_redeeming_in_order($order_id, $orderuserid) {
        $order = new WC_Order($order_id);
        if (get_post_meta($order_id, 'fp_rs_redeemed_points_value_for_revision', true) != 'yes') {
            if (isset(WC()->session)) {
                $redeem_amount = WC()->session->get('fp_rs_redeem_amount') != NULL ? WC()->session->get('fp_rs_redeem_amount') : '0';
                update_post_meta($order_id, 'fp_rs_redeemed_points_value', $redeem_amount);
                WC()->session->__unset('fp_rs_redeem_amount');
            }
            add_post_meta($order_id, 'fp_rs_redeemed_points_value_for_revision', 'yes');
        }
        //$points_awarded_for_this_order = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, 'reward_points_awarded');
        //if($points_awarded_for_this_order != 'yes'){
        $rewardpointscoupons = $order->get_items(array('coupon'));
        $getuserdatabyid = get_user_by('id', $orderuserid);
        $getusernickname = isset($getuserdatabyid->user_login) ? $getuserdatabyid->user_login : "";
        $maincouponchecker = 'sumo_' . strtolower($getusernickname);
        $auto_redeem_name = 'auto_redeem_' . strtolower($getusernickname);
        foreach ($rewardpointscoupons as $array) {
            if (in_array($maincouponchecker, $array)) {
                return true;
            } else {
                return false;
            }
            if (in_array($auto_redeem_name, $array)) {
                return true;
            } else {
                return false;
            }
        }
        $currentuserid = get_current_user_id();
        $getpostvalue = get_user_meta($currentuserid, 'rs_selected_nominee', true);
        update_user_meta($currentuserid, 'rs_selected_nominee', $getpostvalue);
        //}
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

        $mainproductdatabooking = get_product($productid);
        if (is_object($mainproductdatabooking) && $mainproductdatabooking->is_type('booking')) {
            $getregularprice = $item['line_total'];
        } else {
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
        $checkredeeming = self::check_redeeming_in_order($order_id, $orderuserid);
        $enableoption = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, 'rs_check_enable_option_for_redeeming');
        $restrictuserpoints = get_option('rs_max_earning_points_for_user');
        $enabledisablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
        $getnomineeid = get_user_meta(get_current_user_id(), 'rs_selected_nominee', true);

        $enable = get_option('rs_enable_disable_reward_point_based_coupon_amount');

        if ($enable == 'yes') {
            $coupon_used = array();
            $order_object = new WC_Order($order_id);
            $coupon_used = $order_object->get_used_coupons();
            if (is_array($coupon_used)) {
                if (!empty($coupon_used)) {
                    $productid = $variationid == '0' || '' ? $productid : $variationid;
                    if ($productlevelrewardpoints != '') {
                        $productlevelrewardpoints = self::order_coupon_validator($order_id, $productid);
                    }
                    if ($productlevelrewardpercent != '') {
                        $productlevelrewardpercent = self::order_coupon_validator($order_id, $productid);
                    }
                    if ($categorylevelrewardpoints != '') {
                        $categorylevelrewardpoints = self::order_coupon_validator($order_id, $productid);
                    }
                    if ($categorylevelrewardpercent != '') {
                        $categorylevelrewardpercent = self::order_coupon_validator($order_id, $productid);
                    }
                    if ($global_rewardpoints != '') {
                        $global_rewardpoints = self::order_coupon_validator($order_id, $productid);
                    }
                    if ($global_rewardpercent != '') {
                        $global_rewardpercent = self::order_coupon_validator($order_id, $productid);
                    }

                    $order = new WC_Order($order_id);
                    $order_total = $order->get_total();
                    $minimum_cart_total = get_option('rs_minimum_cart_total_for_earning');
                    if ($minimum_cart_total != '' && $minimum_cart_total != 0) {
                        if ($order_total < $minimum_cart_total) {

                            $productlevelrewardpoints = 0;
                            $global_rewardpercent = 0;
                            $global_rewardpoints = 0;
                            $categorylevelrewardpercent = 0;
                            $categorylevelrewardpoints = 0;
                            $productlevelrewardpercent = 0;
                        }
                    }

                    include 'rs_insert_modifiedpoints_for_product_purchase.php';
                } else {
                    $order = new WC_Order($order_id);
                    $order_total = $order->get_total();
                    $minimum_cart_total = get_option('rs_minimum_cart_total_for_earning');
                    if ($minimum_cart_total != '' && $minimum_cart_total != 0) {
                        if ($order_total < $minimum_cart_total) {

                            $productlevelrewardpoints = 0;
                            $global_rewardpercent = 0;
                            $global_rewardpoints = 0;
                            $categorylevelrewardpercent = 0;
                            $categorylevelrewardpoints = 0;
                            $productlevelrewardpercent = 0;
                        }
                    }
                    include 'rs_insert_points_for_product_purchase.php';
                }
            }
        } else {
            $order = new WC_Order($order_id);
            $order_total = $order->get_total();
            $minimum_cart_total = get_option('rs_minimum_cart_total_for_earning');
            if ($minimum_cart_total != '' && $minimum_cart_total != 0) {
                if ($order_total < $minimum_cart_total) {
                    $productlevelrewardpoints = 0;
                    $global_rewardpercent = 0;
                    $global_rewardpoints = 0;
                    $categorylevelrewardpercent = 0;
                    $categorylevelrewardpoints = 0;
                    $productlevelrewardpercent = 0;
                }
            }

            include 'rs_insert_points_for_product_purchase.php';
        }
    }

    public static function order_coupon_validator($order_id, $product_id) {
        $modified_point_list = get_post_meta($order_id, 'points_for_current_order', true);
        foreach ($modified_point_list as $key => $value) {
            if ($product_id == $key) {
                $totalrewardpointsnew = $value;
            }
        }

        return $totalrewardpointsnew;
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

                $checkredeeming = self::check_redeeming_in_order($order_id, $orderuserid);
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


                $checkredeeming = self::check_redeeming_in_order($order_id, $orderuserid);
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

    public static function delete_referral_registered_people($user_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        $table_name2 = $wpdb->prefix . 'rsrecordpoints';

        $registration_points = get_option('rs_reward_signup');

        $referral_registration_points = RSMemberFunction::user_role_based_reward_points($user_id, get_option('rs_referral_reward_signup'));
        $getreferredusermeta = get_user_meta($user_id, '_rs_i_referred_by', true);
        $refuserid = $getreferredusermeta;
        $getregisteredcount = get_user_meta($refuserid, 'rsreferreduserregisteredcount', true);
        $currentregistration = $getregisteredcount - 1;
        update_user_meta($refuserid, 'rsreferreduserregisteredcount', $currentregistration);
        $noofdays = get_option('rs_point_to_be_expire');

        if (($noofdays != '0') && ($noofdays != '')) {
            $date = time() + ($noofdays * 24 * 60 * 60);
        } else {
            $date = '999999999999';
        }
        /*
         * Update the Referred Person Registration Count End
         */

        /* Below Code is for Removing Referral Point Registration when Deleting User */
        if ($getreferredusermeta != '') {
            $oldpointss = self::get_sum_of_total_earned_points($refuserid);
            $currentregistrationpointss = $oldpointss - $referral_registration_points;
            self::insert_earning_points($refuserid, '0', $referral_registration_points, $date, 'RVPFRRRP', '0', '0', '0', '');
            $equredeemamt = self::redeeming_conversion_settings($referral_registration_points);
            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($refuserid);
            self::record_the_points($refuserid, '0', $referral_registration_points, $date, 'RVPFRRRP', '0', $equredeemamt, '0', '0', '0', $user_id, '', $totalpoints, '', '0');
            update_user_meta($user_id, '_rs_i_referred_by', $refuserid);
        }
        $getlistoforder = get_user_meta($user_id, '_update_user_order', true);
        if (is_array($getlistoforder)) {
            foreach ($getlistoforder as $order_id) {
                $order = new WC_Order($order_id);
                if ($order->status == 'completed') {
                    $pointslog = array();
                    $usernickname = get_user_meta($order->user_id, 'nickname', true);
                    foreach ($order->get_items() as $item) {
                        if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                            $getregularprice = get_post_meta($item['product_id'], '_regular_price', true);

                            if ($getregularprice == '') {
                                $getregularprice = get_post_meta($item['product_id'], '_price', true);
                            }
                        } else {
                            $getregularprice = get_post_meta($item['product_id'], '_price', true);
                            if ($getregularprice == '') {
                                $getregularprice = get_post_meta($item['product_id'], '_regular_price', true);
                            }
                        }

                        do_action_ref_array('rs_delete_points_for_referral_simple', array(&$getregularprice, &$item));

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


                        $referreduser = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, '_referrer_name');
                        if ($referreduser != '') {
                            //For Inserting Referral Reward Points
                            $checked_level_for_referral_reward_points = self::check_level_of_enable_referral_reward_point($productid, $variationid, $termid);
                            self::rs_insert_the_selected_level_revised_referral_reward_points('0', $referreduser, '0', '0', $checked_level_for_referral_reward_points, $productid, $variationid, $itemquantity, $orderuserid, $termid, $order_id, '0', '0', '');
                            $gettotalearnedpoints = $wpdb->get_results("SELECT SUM((earnedpoints)) as availablepoints FROM $table_name WHERE orderid = $order_id", ARRAY_A);
                            $totalearnedpoints = ($gettotalearnedpoints[0]['availablepoints'] != NULL) ? $gettotalearnedpoints[0]['availablepoints'] : 0;
                            $totalredeempoints = '0';
                            $equredeemamt = self::redeeming_conversion_settings($totalredeempoints);
                            $wpdb->query("UPDATE $table_name SET totalearnedpoints = $totalearnedpoints,totalredeempoints = $totalredeempoints WHERE orderid = $order_id");
                        }
                        self::update_revised_reward_points_to_user($order_id, $orderuserid);
                    }
                }
            }
        }
    }

    /*
     *
     * @ Redeeming Conversion settings
     * @returns equivalent currency  value for current points
     */

    public static function redeeming_conversion_settings($points_to_redeem) {
        $user_entered_points = $points_to_redeem; //Ex:10points
        $conversion_rate_points = wc_format_decimal(get_option('rs_redeem_point')); //Conversion Points
        $conversion_rate_points_value = wc_format_decimal(get_option('rs_redeem_point_value')); //Value for the Conversion Points (i.e)  1 points is equal to $.2
        $conversion_step1 = $user_entered_points / $conversion_rate_points; //Ex: 10/1=10
        $converted_value = $conversion_step1 * $conversion_rate_points_value; //Ex:10 * 2 = 20
        return $converted_value; // $.20
    }

    /*
     *
     * @ Earning Conversion settings
     * @returns equivalent currency  value for current points
     */

    public static function earning_conversion_settings($earnpoints) {
        $user_entered_points = $earnpoints; //Ex:10points
        $conversion_rate_points = RSFunctionofGeneralTab::earn_point_conversion(); //Conversion Points
        $conversion_rate_points_value = RSFunctionofGeneralTab::earn_point_conversion_value(); //Value for the Conversion Points (i.e)  1 points is equal to $.2
        $conversion_step1 = $user_entered_points / $conversion_rate_points; //Ex: 10/1=10
        $converted_value = $conversion_step1 * $conversion_rate_points_value; //Ex:10 * 2 = 20
        return $converted_value; // $.20
    }

    public static function check_if_the_customer_purchased_this_product_already($user_id, $emails, $product_id, $variation_id) {
        global $wpdb;
        $results = $wpdb->get_results(
                $wpdb->prepare("
			SELECT DISTINCT order_items.order_item_id
			FROM {$wpdb->prefix}woocommerce_order_items as order_items
			LEFT JOIN {$wpdb->prefix}woocommerce_order_itemmeta AS itemmeta ON order_items.order_item_id = itemmeta.order_item_id
                        LEFT JOIN {$wpdb->postmeta} AS postmeta ON order_items.order_id = postmeta.post_id
			LEFT JOIN {$wpdb->posts} AS posts ON order_items.order_id = posts.ID
			WHERE
				posts.post_status IN ( 'wc-completed', 'wc-processing' ) AND
				itemmeta.meta_value  = %s AND
				itemmeta.meta_key    IN ( '_variation_id', '_product_id' ) AND
				postmeta.meta_key    IN ( '_billing_email', '_customer_user' ) AND
				(
					postmeta.meta_value  IN ( '" . implode("','", array_map('esc_sql', array_unique((array) $emails))) . "' ) OR
					(
						postmeta.meta_value = %s
					)
				)
			", $variation_id == '' ? $product_id : $variation_id, $user_id
                )
        );

        $array_results = array();
        if (!empty($results)) {
            foreach ($results as $each_results) {
                $array_results[] = $each_results->order_item_id;
            }
            $new = $wpdb->get_results("SELECT SUM(meta_value) as totalqty FROM {$wpdb->prefix}woocommerce_order_itemmeta WHERE order_item_id IN(" . implode(',', $array_results) . ") and meta_key='_qty'");

            return $new[0]->totalqty;
        } else {
            return 0;
        }
    }

    public static function get_reviewed_user_list($commentid, $approved) {

        global $post;
        $mycomment = get_comment($commentid);
        $get_comment_post_type = get_post_type($mycomment->comment_post_ID);
        $orderuserid = $mycomment->user_id;
        $noofdays = get_option('rs_point_to_be_expire');
        if (($noofdays != '0') && ($noofdays != '')) {
            $date = time() + ($noofdays * 24 * 60 * 60);
        } else {
            $date = '999999999999';
        }



        if ($get_comment_post_type == 'product') {
            if (get_option('rs_reward_for_comment_product_review') == 'yes') {
                $userid = get_current_user_id();
                $product_id = $mycomment->comment_post_ID;
                $user_info = get_user_by('id', $userid);
                $emails = $user_info->user_email;
                $get_all_review_product_id = self::check_if_the_customer_purchased_this_product_already($userid, $emails, $product_id, '');
                if ($get_all_review_product_id > 0) {
                    if (get_option('rs_restrict_reward_product_review') == 'yes') {
                        $getuserreview = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($mycomment->user_id, 'userreviewed' . $mycomment->comment_post_ID);
                        if ($getuserreview != '1') {
                            if (($approved == true)) {
                                $getreviewpoints = RSMemberFunction::user_role_based_reward_points($mycomment->user_id, get_option("rs_reward_product_review"));
                                $restrictuserpoints = get_option('rs_max_earning_points_for_user');
                                $enabledisablemaximumpoints = get_option('rs_enable_disable_max_earning_points_for_user');
                                $currentregistrationpoints = $getreviewpoints;
                                if ($enabledisablemaximumpoints == 'yes') {
                                    if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        if ($getoldpoints <= $restrictuserpoints) {
                                            $totalpointss = $getoldpoints + $currentregistrationpoints;
                                            if ($totalpointss <= $restrictuserpoints) {
                                                $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $orderuserid);
                                                $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $orderuserid);
                                                self::insert_earning_points($mycomment->user_id, $currentregistrationpoints, 0, $date, 'RPPR', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = self::earning_conversion_settings($currentregistrationpoints);
                                                $productid = $mycomment->comment_post_ID;
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($mycomment->user_id);
                                                self::record_the_points($mycomment->user_id, $currentregistrationpoints, '0', $date, 'RPPR', $equearnamt, '0', $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                                RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($mycomment->user_id, 'userreviewed' . $mycomment->comment_post_ID, '1');
                                            } else {
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                self::insert_earning_points($mycomment->user_id, $insertpoints, 0, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings(0);
                                                $productid = $mycomment->comment_post_ID;
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                self::record_the_points($mycomment->user_id, $insertpoints, 0, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                            }
                                        } else {
                                            RSPointExpiry::insert_earning_points($mycomment->user_id, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($mycomment->user_id, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $orderuserid);
                                        $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $orderuserid);
                                        self::insert_earning_points($mycomment->user_id, $currentregistrationpoints, 0, $date, 'RPPR', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = self::earning_conversion_settings($currentregistrationpoints);
                                        $productid = $mycomment->comment_post_ID;
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($mycomment->user_id);
                                        self::record_the_points($mycomment->user_id, $currentregistrationpoints, '0', $date, 'RPPR', $equearnamt, '0', $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($mycomment->user_id, 'userreviewed' . $mycomment->comment_post_ID, '1');
                                    }
                                } else {
                                    $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $orderuserid);
                                    $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $orderuserid);
                                    self::insert_earning_points($mycomment->user_id, $currentregistrationpoints, 0, $date, 'RPPR', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = self::earning_conversion_settings($currentregistrationpoints);
                                    $productid = $mycomment->comment_post_ID;
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($mycomment->user_id);
                                    self::record_the_points($mycomment->user_id, $currentregistrationpoints, '0', $date, 'RPPR', $equearnamt, '0', $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                    RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($mycomment->user_id, 'userreviewed' . $mycomment->comment_post_ID, '1');
                                }
                            }
                        }
                    } else {
                        if (($approved == true)) {
                            $getreviewpoints = RSMemberFunction::user_role_based_reward_points($mycomment->user_id, get_option("rs_reward_product_review"));
                            $restrictuserpoints = get_option('rs_max_earning_points_for_user');
                            $enabledisablemaximumpoints = get_option('rs_enable_disable_max_earning_points_for_user');
                            $currentregistrationpoints = $getreviewpoints;
                            if ($enabledisablemaximumpoints == 'yes') {
                                if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                    $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    if ($getoldpoints <= $restrictuserpoints) {
                                        $totalpointss = $getoldpoints + $currentregistrationpoints;
                                        if ($totalpointss <= $restrictuserpoints) {
                                            $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $orderuserid);
                                            $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $orderuserid);
                                            self::insert_earning_points($mycomment->user_id, $currentregistrationpoints, 0, $date, 'RPPR', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = self::earning_conversion_settings($currentregistrationpoints);
                                            $productid = $mycomment->comment_post_ID;
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($mycomment->user_id);
                                            self::record_the_points($mycomment->user_id, $currentregistrationpoints, '0', $date, 'RPPR', $equearnamt, '0', $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                            RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($mycomment->user_id, 'userreviewed' . $mycomment->comment_post_ID, '1');
                                        } else {
                                            $insertpoints = $restrictuserpoints - $getoldpoints;
                                            self::insert_earning_points($mycomment->user_id, $insertpoints, 0, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                            $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                            $productid = $mycomment->comment_post_ID;
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            self::record_the_points($mycomment->user_id, $insertpoints, 0, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        RSPointExpiry::insert_earning_points($mycomment->user_id, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($mycomment->user_id, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $orderuserid);
                                    $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $orderuserid);
                                    self::insert_earning_points($mycomment->user_id, $currentregistrationpoints, 0, $date, 'RPPR', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = self::earning_conversion_settings($currentregistrationpoints);
                                    $productid = $mycomment->comment_post_ID;
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($mycomment->user_id);
                                    self::record_the_points($mycomment->user_id, $currentregistrationpoints, '0', $date, 'RPPR', $equearnamt, '0', $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                    RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($mycomment->user_id, 'userreviewed' . $mycomment->comment_post_ID, '1');
                                }
                            } else {
                                $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $orderuserid);
                                $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $orderuserid);
                                self::insert_earning_points($mycomment->user_id, $currentregistrationpoints, 0, $date, 'RPPR', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                $equearnamt = self::earning_conversion_settings($currentregistrationpoints);
                                $productid = $mycomment->comment_post_ID;
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($mycomment->user_id);
                                self::record_the_points($mycomment->user_id, $currentregistrationpoints, '0', $date, 'RPPR', $equearnamt, '0', $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($mycomment->user_id, 'userreviewed' . $mycomment->comment_post_ID, '1');
                            }
                        }
                    }
                }
            } else {
                if (get_option('rs_restrict_reward_product_review') == 'yes') {
                    $getuserreview = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($mycomment->user_id, 'userreviewed' . $mycomment->comment_post_ID);
                    if ($getuserreview != '1') {
                        if (($approved == true)) {
                            $getreviewpoints = RSMemberFunction::user_role_based_reward_points($mycomment->user_id, get_option("rs_reward_product_review"));
                            $restrictuserpoints = get_option('rs_max_earning_points_for_user');
                            $enabledisablemaximumpoints = get_option('rs_enable_disable_max_earning_points_for_user');
                            $currentregistrationpoints = $getreviewpoints;
                            if ($enabledisablemaximumpoints == 'yes') {
                                if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                    $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    if ($getoldpoints <= $restrictuserpoints) {
                                        $totalpointss = $getoldpoints + $currentregistrationpoints;
                                        if ($totalpointss <= $restrictuserpoints) {
                                            $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $orderuserid);
                                            $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $orderuserid);
                                            self::insert_earning_points($mycomment->user_id, $currentregistrationpoints, 0, $date, 'RPPR', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = self::earning_conversion_settings($currentregistrationpoints);
                                            $productid = $mycomment->comment_post_ID;
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($mycomment->user_id);
                                            self::record_the_points($mycomment->user_id, $currentregistrationpoints, '0', $date, 'RPPR', $equearnamt, '0', $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                            RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($mycomment->user_id, 'userreviewed' . $mycomment->comment_post_ID, '1');
                                        } else {
                                            $insertpoints = $restrictuserpoints - $getoldpoints;
                                            self::insert_earning_points($mycomment->user_id, $insertpoints, 0, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                            $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                            $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                            $productid = $mycomment->comment_post_ID;
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            self::record_the_points($mycomment->user_id, $insertpoints, 0, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                        }
                                    } else {
                                        RSPointExpiry::insert_earning_points($mycomment->user_id, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        RSPointExpiry::record_the_points($mycomment->user_id, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                    }
                                } else {
                                    $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $orderuserid);
                                    $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $orderuserid);
                                    self::insert_earning_points($mycomment->user_id, $currentregistrationpoints, 0, $date, 'RPPR', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = self::earning_conversion_settings($currentregistrationpoints);
                                    $productid = $mycomment->comment_post_ID;
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($mycomment->user_id);
                                    self::record_the_points($mycomment->user_id, $currentregistrationpoints, '0', $date, 'RPPR', $equearnamt, '0', $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                    RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($mycomment->user_id, 'userreviewed' . $mycomment->comment_post_ID, '1');
                                }
                            } else {
                                $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $orderuserid);
                                $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $orderuserid);
                                self::insert_earning_points($mycomment->user_id, $currentregistrationpoints, 0, $date, 'RPPR', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                $equearnamt = self::earning_conversion_settings($currentregistrationpoints);
                                $productid = $mycomment->comment_post_ID;
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($mycomment->user_id);
                                self::record_the_points($mycomment->user_id, $currentregistrationpoints, '0', $date, 'RPPR', $equearnamt, '0', $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($mycomment->user_id, 'userreviewed' . $mycomment->comment_post_ID, '1');
                            }
                        }
                    }
                } else {
                    if (($approved == true)) {
                        $getreviewpoints = RSMemberFunction::user_role_based_reward_points($mycomment->user_id, get_option("rs_reward_product_review"));
                        $restrictuserpoints = get_option('rs_max_earning_points_for_user');
                        $enabledisablemaximumpoints = get_option('rs_enable_disable_max_earning_points_for_user');
                        $currentregistrationpoints = $getreviewpoints;
                        if ($enabledisablemaximumpoints == 'yes') {
                            if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                if ($getoldpoints <= $restrictuserpoints) {
                                    $totalpointss = $getoldpoints + $currentregistrationpoints;
                                    if ($totalpointss <= $restrictuserpoints) {
                                        $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $orderuserid);
                                        $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $orderuserid);
                                        self::insert_earning_points($mycomment->user_id, $currentregistrationpoints, 0, $date, 'RPPR', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = self::earning_conversion_settings($currentregistrationpoints);
                                        $productid = $mycomment->comment_post_ID;
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($mycomment->user_id);
                                        self::record_the_points($mycomment->user_id, $currentregistrationpoints, '0', $date, 'RPPR', $equearnamt, '0', $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                        RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($mycomment->user_id, 'userreviewed' . $mycomment->comment_post_ID, '1');
                                    } else {
                                        $insertpoints = $restrictuserpoints - $getoldpoints;
                                        self::insert_earning_points($mycomment->user_id, $insertpoints, 0, $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                        $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                        $productid = $mycomment->comment_post_ID;
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        self::record_the_points($mycomment->user_id, $insertpoints, 0, $date, 'MREPFU', $equearnamt, $equredeemamt, $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                    }
                                } else {
                                    RSPointExpiry::insert_earning_points($mycomment->user_id, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($mycomment->user_id, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $orderuserid);
                                $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $orderuserid);
                                self::insert_earning_points($mycomment->user_id, $currentregistrationpoints, 0, $date, 'RPPR', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                $equearnamt = self::earning_conversion_settings($currentregistrationpoints);
                                $productid = $mycomment->comment_post_ID;
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($mycomment->user_id);
                                self::record_the_points($mycomment->user_id, $currentregistrationpoints, '0', $date, 'RPPR', $equearnamt, '0', $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                                RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($mycomment->user_id, 'userreviewed' . $mycomment->comment_post_ID, '1');
                            }
                        } else {
                            $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id, $orderuserid);
                            $pointsredeemed = self::perform_calculation_with_expiry($redeempoints, $orderuserid);
                            self::insert_earning_points($mycomment->user_id, $currentregistrationpoints, 0, $date, 'RPPR', $order_id, $totalearnedpoints, $totalredeempoints, '');
                            $equearnamt = self::earning_conversion_settings($currentregistrationpoints);
                            $productid = $mycomment->comment_post_ID;
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($mycomment->user_id);
                            self::record_the_points($mycomment->user_id, $currentregistrationpoints, '0', $date, 'RPPR', $equearnamt, '0', $order_id, $productid, $variationid, $refuserid, '', $totalpoints, '', '0');
                            RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($mycomment->user_id, 'userreviewed' . $mycomment->comment_post_ID, '1');
                        }
                    }
                }
            }
        }
        do_action('fp_reward_point_for_product_review');
    }

    public static function getcommentstatus($id) {
        if (get_option('rs_review_reward_status') == '1') {
            self::get_reviewed_user_list($id, true);
        } else {
            self::get_reviewed_user_list($id, false);
        }
    }

    public static function rs_function_to_display_log($csvmasterlog, $user_deleted, $order_status_changed, $earnpoints, $order, $checkpoints, $productid, $orderid, $variationid, $userid, $refuserid, $reasonindetail, $redeempoints, $masterlog, $nomineeid, $usernickname, $nominatedpoints) {
        $getmsgrpg = '';
        $post_url = admin_url('post.php?post=' . $orderid) . '&action=edit';
        $myaccountlink = get_permalink(get_option('woocommerce_myaccount_page_id'));
        $vieworderlink = esc_url_raw(add_query_arg('view-order', $orderid, $myaccountlink));
        $vieworderlinkforfront = '<a href="' . $vieworderlink . '">#' . $orderid . '</a>';
        switch ($checkpoints) {
            case 'RPG' :
                $getmsgrpg = get_option('_rs_localize_reward_for_payment_gateway_message');
                $replacepaymenttitle = str_replace('{payment_title}', $order->payment_method_title, $getmsgrpg);
                return $replacepaymenttitle;
                break;
            case 'PPRP':
                if ($masterlog == false) {
                    $getmsgrpg = get_option('_rs_localize_points_earned_for_purchase_main');
                    $replaceorderid = str_replace('{currentorderid}', $vieworderlinkforfront, $getmsgrpg);
                    return $replaceorderid;
                    break;
                } else {
                    if ($csvmasterlog == false) {
                        $getmsgrpg = get_option('_rs_localize_product_purchase_reward_points');
                        $replaceproductid = str_replace('{itemproductid}', $productid, $getmsgrpg);
                        $replaceorderid = str_replace('{currentorderid}', $vieworderlinkforfront, $replaceproductid);
                        return $replaceorderid;
                    } else {
                        $getmsgrpg = get_option('_rs_localize_product_purchase_reward_points');
                        $replaceproductid = str_replace('{itemproductid}', $productid, $getmsgrpg);
                        $replaceorderid = str_replace('{currentorderid}', '#' . $orderid, $replaceproductid);
                        return $replaceorderid;
                    }
                }
            case 'PPRRP':
                $getmsgrpg = get_option('_rs_localize_referral_reward_points_for_purchase');
                $replaceproductid = str_replace('{itemproductid}', $productid, $getmsgrpg);
                $replaceusername = str_replace('{purchasedusername}', $refuserid != '' ? $refuserid : __('Guest', 'rewardsystem'), $replaceproductid);
                return $replaceusername;
                break;
            case 'RRP':
                $getmsgrpg = get_option('_rs_localize_points_earned_for_registration');
                return $getmsgrpg;
                break;
            case 'RRRP':
                $getmsgrpg = get_option('_rs_localize_points_earned_for_referral_registration');
                $replaceusername = str_replace('{registereduser}', $refuserid, $getmsgrpg);
                return $replaceusername;
                break;
            case 'LRP':
                $getmsgrpg = get_option('_rs_localize_reward_points_for_login');
                return $getmsgrpg;
                break;
            case 'RPC':
                $getmsgrpg = get_option('_rs_localize_coupon_reward_points_log');
                return $getmsgrpg;
                break;
            case 'RPFL':
                $getmsgrpg = get_option('_rs_localize_reward_for_facebook_like');
                return $getmsgrpg;
                break;
            case 'RPFS':
                $getmsgrpg = get_option('_rs_localize_reward_for_facebook_share');
                return $getmsgrpg;
                break;

            case 'RPTT':
                $getmsgrpg = get_option('_rs_localize_reward_for_twitter_tweet');
                return $getmsgrpg;
                break;
            case 'RPGPOS':
                $getmsgrpg = get_option('_rs_localize_reward_for_google_plus');
                return $getmsgrpg;
                break;
            case 'RPVL':
                $getmsgrpg = get_option('_rs_localize_reward_for_vk');
                return $getmsgrpg;
                break;
            case 'RPPR':
                $getmsgrpg = get_option('_rs_localize_points_earned_for_product_review');
                $replaceproductid = str_replace('{reviewproductid}', $productid, $getmsgrpg);
                return $replaceproductid;
                break;
            case 'RP':
                if ($csvmasterlog == false) {
                    $getmsgrpg = get_option('_rs_localize_points_redeemed_towards_purchase');
                    $replaceproductid = str_replace('{currentorderid}', $vieworderlinkforfront, $getmsgrpg);
                    return $replaceproductid;
                    break;
                } else {
                    $getmsgrpg = get_option('_rs_localize_points_redeemed_towards_purchase');
                    $replaceproductid = str_replace('{currentorderid}', '#' . $orderid, $getmsgrpg);
                    return $replaceproductid;
                    break;
                }
            case 'MAP':
                $getmsgrpg = $reasonindetail;
                return $getmsgrpg;
                break;
            case 'MRP':
                $getmsgrpg = $reasonindetail;
                return $getmsgrpg;
                break;
            case 'CBRP':
                $getmsgrpg = get_option('_rs_localize_points_to_cash_log');
                return $getmsgrpg;
                break;
            case 'RCBRP':
                $getmsgrpg = get_option('_rs_localize_points_to_cash_log_revised');
                return $getmsgrpg;
                break;
            case 'RPGV':
                $getmsgrpg = get_option('_rs_localize_voucher_code_usage_log_message');
                $replaceproductid = str_replace('{rsusedvouchercode}', $reasonindetail, $getmsgrpg);
                return $replaceproductid;
                break;
            case 'RPBSRP':
                $getmsgrpg = get_option('_rs_localize_buying_reward_points_log');
                $replaceproductid = str_replace('{rsbuyiedrewardpoints}', $earnpoints, $getmsgrpg);
                return $replaceproductid;
                break;
            case 'MAURP':
                $getmsgrpg = $reasonindetail;
                return $getmsgrpg;
                break;
            case 'MRURP':
                $getmsgrpg = $reasonindetail;
                return $getmsgrpg;
                break;
            case 'RPCPR':
                $getmsgrpg = get_option('_rs_localize_points_earned_for_post_review');
                $postname = get_the_title($productid);
                $replaceproductid = str_replace('{postid}', $postname, $getmsgrpg);
                return $replaceproductid;
                return $getmsgrpg;
                break;
            case 'MREPFU':
                $getmsgrpg = get_option('_rs_localize_max_earning_points_log');
                $replacepoints = get_option('rs_max_earning_points_for_user');
                $replace = str_replace('[rsmaxpoints]', $replacepoints, $getmsgrpg);
                return $replace;
                break;
            case 'RPFGW':
                $getmsgrpg = get_option('_rs_reward_points_gateway_log_localizaation');
                return $getmsgrpg;
                break;
            case 'RVPFRPG':
                $getmsgrpg = get_option('_rs_localize_revise_reward_for_payment_gateway_message');
                $replaceproductid = str_replace('{payment_title}', $order->payment_method_title, $getmsgrpg);
                return $replaceproductid;
                break;
            case 'RVPFPPRP':
                if ($masterlog == false) {
                    if ($csvmasterlog == false) {
                        $getmsgrpg = get_option('_rs_log_revise_product_purchase_main');
                        $replaceproductid = str_replace('{currentorderid}', $vieworderlinkforfront, $getmsgrpg);
                        return $replaceproductid;
                        break;
                    } else {
                        $getmsgrpg = get_option('_rs_log_revise_product_purchase_main');
                        $replaceproductid = str_replace('{currentorderid}', '#' . $orderid, $getmsgrpg);
                        return $replaceproductid;
                        break;
                    }
                } else {
                    $getmsgrpg = get_option('_rs_log_revise_product_purchase');
                    $replaceproductid = str_replace('{productid}', $productid, $getmsgrpg);
                    return $replaceproductid;
                    break;
                }
            case 'RVPFPPRRP':
                if ($order_status_changed == true) {
                    $getmsgrpg = get_option('_rs_log_revise_referral_product_purchase');
                    $replaceproductid = str_replace('{productid}', $productid, $getmsgrpg);
                    return $replaceproductid;
                    break;
                } elseif ($user_deleted == true) {
                    $getmsgrpg = get_option('_rs_localize_revise_points_for_referral_purchase');
                    $replaceproductid = str_replace('{productid}', $productid, $getmsgrpg);
                    $replaceusername = str_replace('{usernickname}', $refuserid, $replaceproductid);
                    return $replaceusername;
                    break;
                }
            case 'RVPFRP':
                $getmsgrpg = get_option('_rs_log_revise_points_redeemed_towards_purchase');
                return $getmsgrpg;
                break;
            case 'RVPFRRRP':
                $getmsgrpg = get_option('_rs_localize_referral_account_signup_points_revised');
                $replaceproductid = str_replace('{usernickname}', $refuserid, $getmsgrpg);
                return $replaceproductid;
                break;
            case 'RVPFRPVL':
                $getmsgrpg = get_option('_rs_localize_reward_for_vk_like_revised');
                return $getmsgrpg;
                break;
            case 'RVPFRPGPOS':
                $getmsgrpg = get_option('_rs_localize_reward_for_google_plus_revised');
                return $getmsgrpg;
                break;
            case 'RVPFRPFL':
                $getmsgrpg = get_option('_rs_localize_reward_for_facebook_like_revised');
                return $getmsgrpg;
                break;
            case 'PPRPFN':
                if ($masterlog == true) {
                    $getmsgrpg = get_option('_rs_localize_log_for_nominee');
                    $replaceproductid = str_replace('[points]', $earnpoints, $getmsgrpg);
                    $replaceproductid1 = str_replace('[user]', $nomineeid, $replaceproductid);
                    $replaceproductid2 = str_replace('[name]', $usernickname, $replaceproductid1);
                    return $replaceproductid2;
                    break;
                } else {
                    $getmsgrpg = get_option('_rs_localize_log_for_nominee');
                    $replaceproductid = str_replace('[points]', $earnpoints, $getmsgrpg);
                    $replaceproductid1 = str_replace('[user]', $nomineeid, $replaceproductid);
                    $replaceproductid2 = str_replace('[name]', "You", $replaceproductid1);
                    return $replaceproductid2;
                    break;
                }
            case 'PPRPFNP':
                if ($masterlog == true) {
                    $getmsgrpg = get_option('_rs_localize_log_for_nominated_user');
                    $replaceproductid1 = str_replace('[user]', $nomineeid, $getmsgrpg);
                    $replaceproductid2 = str_replace('[points]', $nominatedpoints, $replaceproductid1);
                    $replaceproductid3 = str_replace('[name]', $usernickname, $replaceproductid2);
                    return $replaceproductid3;
                    break;
                } else {
                    $getmsgrpg = get_option('_rs_localize_log_for_nominated_user');
                    $replaceproductid1 = str_replace('[user]', $nomineeid, $getmsgrpg);
                    $replaceproductid2 = str_replace('[points]', $nominatedpoints, $replaceproductid1);
                    $replaceproductid3 = str_replace('[name]', "Your", $replaceproductid2);
                    return $replaceproductid3;
                    break;
                }
            case 'IMPADD':
                $getmsgrpg = get_option('_rs_localize_log_for_import_add');
                $replaceproductid2 = str_replace('[points]', $earnpoints, $getmsgrpg);
                return $replaceproductid2;
                break;
            case 'IMPOVR':
                if ($masterlog == true) {
                    $getmsgrpg = get_option('_rs_localize_log_for_import_override');
                    $replaceproductid2 = str_replace('[points]', $earnpoints, $getmsgrpg);
                    return $replaceproductid2;
                    break;
                } else {
                    $getmsgrpg = get_option('_rs_localize_log_for_import_override');
                    $replaceproductid2 = str_replace('[points]', "Your", $getmsgrpg);
                    return $replaceproductid2;
                    break;
                }
            case 'RPFP':
                $getmsgrpg = get_option('_rs_localize_points_earned_for_post');
                $postname = get_the_title($productid);
                $replacepostid = str_replace('{postid}', $postname, $getmsgrpg);
                return $replacepostid;
                break;
            case 'SP':
                if ($masterlog == true) {
                    $getmsgrpg = get_option('_rs_localize_log_for_reciver');
                    $replaceproductid = str_replace('[points]', $earnpoints, $getmsgrpg);
                    $replaceproductid1 = str_replace('[user]', $nomineeid, $replaceproductid);
                    $replaceproductid2 = str_replace('[name]', $usernickname, $replaceproductid1);
                    return $replaceproductid2;
                    break;
                } else {
                    $getmsgrpg = get_option('_rs_localize_log_for_reciver');
                    $replaceproductid = str_replace('[points]', $earnpoints, $getmsgrpg);
                    $replaceproductid1 = str_replace('[user]', $nomineeid, $replaceproductid);
                    $replaceproductid2 = str_replace('[name]', "You", $replaceproductid1);
                    return $replaceproductid2;
                    break;
                }
            case 'RPCPAR':
                $getmsg = get_option('_rs_localize_points_earned_for_page_review');
                $postname = get_the_title($productid);
                $replaceproductid = str_replace('{pagename}', $postname, $getmsg);
                return $replaceproductid;
                break;

            case 'SENPM':
                if ($masterlog == true) {
                    $getmsgrpg = get_option('_rs_localize_log_for_sender');
                    $replaceproductid1 = str_replace('[user]', $nomineeid, $getmsgrpg);
                    $replaceproductid2 = str_replace('[points]', $redeempoints, $replaceproductid1);
                    $replaceproductid3 = str_replace('[name]', $usernickname, $replaceproductid2);
                    return $replaceproductid3;
                    break;
                } else {
                    $getmsgrpg = get_option('_rs_localize_log_for_sender');
                    $replaceproductid1 = str_replace('[user]', $nomineeid, $getmsgrpg);
                    $replaceproductid2 = str_replace('[points]', $redeempoints, $replaceproductid1);
                    $replaceproductid3 = str_replace('[name]', "Your", $replaceproductid2);
                    return $replaceproductid3;
                    break;
                }
            case 'SEP':
                $getmsgrpg = get_option('_rs_localize_points_to_send_log_revised');
                return $getmsgrpg;
                break;
            case 'RPFURL':
                $getmsgrpg = get_option('rs_message_for_pointurl');
                $replacepoints = str_replace('[points]', $earnpoints, $getmsgrpg);
                return $replacepoints;
                break;
        }
    }

    public static function rewards_rs_order_status_control() {
        global $woocommerce;
        $orderslugs = array();
        $orderslugs1 = array();
        if (function_exists('wc_get_order_statuses')) {
            $orderslugss = str_replace('wc-', '', array_keys(wc_get_order_statuses()));
            foreach ($orderslugss as $value) {
                if (is_array(get_option('rs_order_status_control'))) {
                    if (!in_array($value, get_option('rs_order_status_control'))) {
                        $orderslugs[] = $value;
                    }
                }

                if (is_array(get_option('rs_order_status_control_redeem'))) {
                    if (!in_array($value, get_option('rs_order_status_control_redeem'))) {
                        $orderslugs1[] = $value;
                    }
                }
            }
        } else {
            $taxonomy = 'shop_order_status';
            $orderstatus = '';
            $term_args = array(
                'hide_empty' => false,
                'orderby' => 'date',
            );
            $tax_terms = get_terms($taxonomy, $term_args);
            foreach ($tax_terms as $getterms) {
                if (is_array(get_option('rs_order_status_control'))) {
                    if (!in_array($getterms->slug, get_option('rs_order_status_control'))) {
                        $orderslugs[] = $getterms->slug;
                    }
                }

                if (is_array(get_option('rs_order_status_control_redeem'))) {
                    if (!in_array($getterms->slug, get_option('rs_order_status_control_redeem'))) {
                        $orderslugs1[] = $getterms->slug;
                    }
                }
            }
        }
        update_option('rs_list_other_status_for_redeem', $orderslugs1);
        update_option('rs_list_other_status', $orderslugs);
    }

    public static function myrewardpoints_total_shortcode($content) {
        if (is_user_logged_in()) {
            ob_start();
            $userid = get_current_user_id();
            $getusermeta = self::get_sum_of_total_earned_points($userid);
            if ($getusermeta != '' && $getusermeta > 0) {
                $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                echo get_option('rs_my_rewards_total') . " " . round(number_format((float) $getusermeta, 2, '.', ''), $roundofftype) . "</h4><br>";
            } else {
                echo get_option('rs_my_rewards_total') . " " . " 0</h4><br>";
            }
            $content = ob_get_clean();
            return $content;
        }
    }

    public static function rs_fp_rewardsystem($atts) {
        ob_start();
        extract(shortcode_atts(array(
            'referralbutton' => 'show',
            'referraltable' => 'show',
                        ), $atts));
        if ($referralbutton == 'show') {
            RSFunctionForMyAccount::generate_referral_key();
        }
        if ($referraltable == 'show') {
            RSFunctionForMyAccount::list_table_array();
        }
        $maincontent = ob_get_clean();
        return $maincontent;
    }

    public static function shortcode_for_static_referral_link() {
        ob_start();
        $currentuserid = get_current_user_id();
        $objectcurrentuser = get_userdata($currentuserid);
        if (get_option('rs_generate_referral_link_based_on_user') == '1') {
            $referralperson = $objectcurrentuser->user_login;
        } else {
            $referralperson = $currentuserid;
        }

        $refurl = add_query_arg('ref', $referralperson, get_option('rs_static_generate_link'));
        ?><h3><?php echo get_option('rs_my_referral_link_button_label'); ?></h3><?php
        echo $refurl;
        $maincontent = ob_get_clean();
        return $maincontent;
    }

    public static function delete_cookie_after_some_purchase($cookievalue) {
        $countnoofpurchase = '';
        $getnoofpurchase = get_user_meta(get_current_user_id(), 'rs_no_of_purchase_for_user', true);
        if ($getnoofpurchase != false) {
            $countnoofpurchase = count($getnoofpurchase);
        }
        $checkenable = get_option('rs_enable_delete_referral_cookie_after_first_purchase');
        $noofpurchase = get_option('rs_no_of_purchase');
        if ($checkenable == 'yes') {
            if (($noofpurchase != '') && ($noofpurchase != 0)) {
                if ($countnoofpurchase >= $noofpurchase) {
                    setcookie('rsreferredusername', $cookievalue, time() - 3600, '/');
                }
            }
        }
    }

}

new RSPointExpiry();
