<?php

class RSRegistrationPoints {

    public function __construct() {

        add_action('user_register', array($this, 'rs_add_registration_rewards_points'), 10, 1);

        $orderstatuslist = get_option('rs_order_status_control');
        if (is_array($orderstatuslist)) {
            foreach ($orderstatuslist as $value) {
                add_action('woocommerce_order_status_' . $value, array($this, 'reward_points_after_first_purchase'));
            }
        }

        add_action('wp_head', array($this, 'reward_points_for_login'));
    }

    public static function rs_add_registration_rewards_points($user_id) {
        $get_registed_user = get_post_meta($user_id, 'rs_registered_user', true);
        if ($get_registed_user == '') {
          
            $enableoptforreg = get_option('rs_reward_signup_after_first_purchase');
            $enableoptforrefreg = get_option('rs_referral_reward_signup_after_first_purchase');

            if (($enableoptforreg == 'yes')) {
                // After First Purchase Registration Points
                self::rs_add_regpoints_to_user_after_first_purchase($user_id);
                if ($enableoptforrefreg == 'yes') {
                    // After First Purchase Referral Registration Points
                    self::rs_add_regpoints_to_refuser_only_after_first_purchase($user_id);
                } else {
                    // Instant Referral Registration Points
                    self::rs_add_regpoints_to_refuser_instantly($user_id);
                }
            } else {
                // Instant Registration Points
                self::rs_add_regpoints_to_user_instantly($user_id);
                if ($enableoptforrefreg == 'yes') {
                    // After First Purchase Referral Registration Points
                    self::rs_add_regpoints_to_refuser_only_after_first_purchase($user_id);
                } else {
                    // Instant Referral Registration Points
                    self::rs_add_regpoints_to_refuser_instantly($user_id);
                }
            }
            do_action('fp_reward_point_for_registration');
            update_post_meta($user_id, 'rs_registered_user', 1);
        }
    }

    public static function reward_points_after_first_purchase($order_id) {

        global $wpdb;
        $order = new WC_Order($order_id);
        $user_id = $order->user_id;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        if (RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($user_id, 'rs_after_first_purchase') != 'yes') {
            $fetchdata = array();
            $fetchdata = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($user_id, 'rs_get_data_for_reward_points');
            if (is_array($fetchdata)) {
                $curregpoints = $fetchdata[$user_id]['points'];
                $refregpoints = $fetchdata[$user_id]['refpoints'];
                $userid = $fetchdata[$user_id]['userid'];
                $refuserid = $fetchdata[$user_id]['refuserid'];

                $checkredeeming = RSPointExpiry::check_redeeming_in_order($order_id, $user_id);
                $enableoption = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($order_id, 'rs_check_enable_option_for_redeeming');
                $restrictuserpoints = get_option('rs_max_earning_points_for_user');
                $enabledisablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
                $noofdays = get_option('rs_point_to_be_expire');
              
                if (($noofdays != '0') && ($noofdays != '')) {
                    $date = time() + ($noofdays * 24 * 60 * 60);
                } else {
                    $date = '999999999999';
                }
                if ($user_id) {

                    if (RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($userid, '_points_awarded') != '1') {
                        $oldpoints = $wpdb->get_results("SELECT SUM((earnedpoints-usedpoints)) as availablepoints FROM $table_name WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) and userid=$userid", ARRAY_A);
                        $totaloldpoints = $oldpoints[0]['availablepoints'];
                        $currentregistrationpoints = $totaloldpoints + $curregpoints;



                        if ($enableoption == 'yes') {
                            if ($checkredeeming == false) {
                                RSPointExpiry::insert_earning_points($user_id, $curregpoints, '0', $date, 'RRP', '', '', '', '');
                                $equearnamt = RSPointExpiry::earning_conversion_settings($curregpoints);

                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($user_id);
                                RSPointExpiry::record_the_points($user_id, $curregpoints, '0', $date, 'RRP', $equearnamt, '0', $order_id, $productid, $variationid, '0', '', $totalpoints, '', '0');
                            }
                        } else {
                            RSPointExpiry::insert_earning_points($user_id, $curregpoints, '0', $date, 'RRP', '', '', '', '');
                            $equearnamt = RSPointExpiry::earning_conversion_settings($curregpoints);

                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($user_id);
                            RSPointExpiry::record_the_points($user_id, $curregpoints, '0', $date, 'RRP', $equearnamt, '0', $order_id, $productid, $variationid, '0', '', $totalpoints, '', '0');
                        }
                        add_user_meta($user_id, '_points_awarded', '1');
                    }

                    if ($refuserid) {
                        if ($enabledisablemaxpoints == 'yes') {
                            if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                                $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($refuserid);
                                if ($getoldpoints <= $restrictuserpoints) {
                                    $totalpointss = $getoldpoints + $refregpoints;
                                    if ($totalpointss <= $restrictuserpoints) {
                                        $oldpoints = $wpdb->get_results("SELECT SUM((earnedpoints-usedpoints)) as availablepoints FROM $table_name WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) and userid=$refuserid", ARRAY_A);
                                        $totaloldpoints = $oldpoints[0]['availablepoints'];
                                        $currentregistrationpointss = $totaloldpoints + $refregpoints;
                                        RSPointExpiry::insert_earning_points($refuserid, $refregpoints, '0', $date, 'RRRP', '', '', '', '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($refregpoints);

                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($refuserid);
                                        RSPointExpiry::record_the_points($refuserid, $refregpoints, '0', $date, 'RRRP', $equearnamt, '0', $order_id, $productid, $variationid, $user_id, '', $totalpoints, '', '0');
                                    } else {
                                        $insertpoints = $restrictuserpoints - $getoldpoints;
                                        RSPointExpiry::insert_earning_points($refuserid, $insertpoints, '0', $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);

                                        $productid = $item['product_id'];
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($refuserid);
                                        RSPointExpiry::record_the_points($refuserid, $insertpoints, '0', $date, 'MREPFU', $equearnamt, '0', $order_id, $productid, '0', $user_id, '', $totalpoints, '', '0');
                                    }
                                } else {
                                    RSPointExpiry::insert_earning_points($refuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($refuserid);
                                    RSPointExpiry::record_the_points($refuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', $user_id, '', $totalpoints, '', '0');
                                }
                            } else {
                                $oldpoints = $wpdb->get_results("SELECT SUM((earnedpoints-usedpoints)) as availablepoints FROM $table_name WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) and userid=$refuserid", ARRAY_A);
                                $totaloldpoints = $oldpoints[0]['availablepoints'];
                                $currentregistrationpointss = $totaloldpoints + $refregpoints;

                                RSPointExpiry::insert_earning_points($refuserid, $refregpoints, '0', $date, 'RRRP', '', '', '', '');
                                $equearnamt = RSPointExpiry::earning_conversion_settings($refregpoints);

                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($refuserid);
                                RSPointExpiry::record_the_points($refuserid, $refregpoints, '0', $date, 'RRRP', $equearnamt, '0', $order_id, $productid, $variationid, $user_id, '', $currentregistrationpointss, '', '0');
                            }
                        } else {
                            $oldpoints = $wpdb->get_results("SELECT SUM((earnedpoints-usedpoints)) as availablepoints FROM $table_name WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) and userid=$refuserid", ARRAY_A);
                            $totaloldpoints = $oldpoints[0]['availablepoints'];
                            $currentregistrationpointss = $totaloldpoints + $refregpoints;

                            RSPointExpiry::insert_earning_points($refuserid, $refregpoints, '0', $date, 'RRRP', '', '', '', '');
                            $equearnamt = RSPointExpiry::earning_conversion_settings($refregpoints);

                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($refuserid);
                            RSPointExpiry::record_the_points($refuserid, $refregpoints, '0', $date, 'RRRP', $equearnamt, '0', $order_id, $productid, $variationid, $user_id, '', $totalpoints, '', '0');
                        }
                    }
                    add_user_meta($user_id, 'rs_after_first_purchase', 'yes');
                }
            }
        }
    }

    /* After First Purchase Referral Registration Points */

    public static function rs_add_regpoints_to_refuser_only_after_first_purchase($user_id) {
        $referral_registration_points = get_option('rs_referral_reward_signup');
        $registration_points = get_option('rs_reward_signup');
        if (isset($_COOKIE['rsreferredusername'])) {
            /*
             * Update the Referred Person Registration Count
             */

            $user_info = new WP_User($user_id);
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
                $referreduser = get_user_by('login', $_COOKIE['rsreferredusername']);
                if ($referreduser != false) {
                    $refuserid = $referreduser->ID;
                } else {
                    $refuserid = $_COOKIE['rsreferredusername'];
                }

                $banning_type = FPRewardSystem::check_banning_type($refuserid);
                if ($banning_type != 'earningonly' && $banning_type != 'both') {

                    $referral_registration_points = RSMemberFunction::user_role_based_reward_points($refuserid, $referral_registration_points);
                    $registration_points = RSMemberFunction::user_role_based_reward_points($user_id, $registration_points);
                    $mainpoints = array();

                    $mainpoints[$user_id] = array('userid' => $user_id, 'points' => $registration_points, 'refuserid' => $refuserid, 'refpoints' => $referral_registration_points);

                    RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($user_id, 'rs_get_data_for_reward_points', $mainpoints);

                    $previouslog = get_option('rs_referral_log');

                    RS_Referral_Log::main_referral_log_function($refuserid, $user_id, $referral_registration_points, array_filter((array) $previouslog));
                    update_user_meta($user_id, '_rs_i_referred_by', $refuserid);
                }
            }
        }
    }

    /* Instant Registration Points */

    public static function rs_add_regpoints_to_user_instantly($user_id) {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        $registration_points = RSMemberFunction::user_role_based_reward_points($user_id, get_option('rs_reward_signup'));
        $restrictuserpoints = get_option('rs_max_earning_points_for_user');
        $enabledisablepoints = get_option('rs_enable_disable_max_earning_points_for_user');

        $oldpoints = $wpdb->get_results("SELECT SUM((earnedpoints-usedpoints)) as availablepoints FROM $table_name WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) and userid=$user_id", ARRAY_A);
        $totaloldpoints = $oldpoints[0]['availablepoints'];
        $currentregistrationpoints = $totaloldpoints + $registration_points;

        if ($enabledisablepoints == 'yes') {
            if (($currentregistrationpoints <= $restrictuserpoints) || ($restrictuserpoints == '')) {
                $currentregistrationpoints = $currentregistrationpoints;
            } else {
                $currentregistrationpoints = $restrictuserpoints;
            }
        }
        $noofdays = get_option('rs_point_to_be_expire');
       
        if (($noofdays != '0') && ($noofdays != '')) {
            $date = time() + ($noofdays * 24 * 60 * 60);
        } else {
            $date = '999999999999';
        }

        RSPointExpiry::insert_earning_points($user_id, $currentregistrationpoints, '0', $date, 'RRP', '', '', '', '');
        $equearnamt = RSPointExpiry::earning_conversion_settings($currentregistrationpoints);
        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($user_id);
        RSPointExpiry::record_the_points($user_id, $currentregistrationpoints, '0', $date, 'RRP', $equearnamt, '0', '0', '0', '0', '0', '', $totalpoints, '', '0');
        add_user_meta($user_id, '_points_awarded', '1');
    }

    /* After First Purchase Registration Points */

    public static function rs_add_regpoints_to_user_after_first_purchase($user_id) {

        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        $banning_type = FPRewardSystem::check_banning_type($user_id);
        if ($banning_type != 'earningonly' && $banning_type != 'both') {
            $registration_points = RSMemberFunction::user_role_based_reward_points($user_id, get_option('rs_reward_signup'));
            $restrictuserpoints = get_option('rs_max_earning_points_for_user');
            $enabledisablepoints = get_option('rs_enable_disable_max_earning_points_for_user');

            $oldpoints = $wpdb->get_results("SELECT SUM((earnedpoints-usedpoints)) as availablepoints FROM $table_name WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) and userid=$user_id", ARRAY_A);
            $totaloldpoints = $oldpoints[0]['availablepoints'];
            $currentregistrationpoints = $totaloldpoints + $registration_points;

            if ($enabledisablepoints == 'yes') {
                if (($currentregistrationpoints <= $restrictuserpoints) || ($restrictuserpoints == '')) {
                    $currentregistrationpoints = $currentregistrationpoints;
                } else {
                    $currentregistrationpoints = $restrictuserpoints;
                }
            }

            $mainpoints = array();

            $mainpoints[$user_id] = array('userid' => $user_id, 'points' => $registration_points, 'refuserid' => '', 'refpoints' => '');

            RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($user_id, 'rs_get_data_for_reward_points', $mainpoints);
        }
    }

    /* Instant Referral Registration Points */

    public static function rs_add_regpoints_to_refuser_instantly($user_id) {
        if (isset($_COOKIE['rsreferredusername'])) {
            /*
             * Update the Referred Person Registration Count
             */

            $user_info = new WP_User($user_id);
            $registered_date = $user_info->user_registered;
            $limitation = false;
            $modified_registered_date = date('Y-m-d h:i:sa', strtotime($registered_date));
            $delay_days = get_option('_rs_select_referral_points_referee_time_content');
            $checking_date = date('Y-m-d h:i:sa', strtotime($modified_registered_date . ' + ' . $delay_days . ' days '));
            $modified_checking_date = strtotime($checking_date);
            $current_date = date('Y-m-d h:i:sa');
            $modified_current_date = strtotime($current_date);
            $restrictuserpoints = get_option('rs_max_earning_points_for_user');
            $enabledisablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
            $noofdays = get_option('rs_point_to_be_expire');
           
            if (($noofdays != '0') && ($noofdays != '')) {
                $date = time() + ($noofdays * 24 * 60 * 60);
            } else {
                $date = '999999999999';
            }
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
                $referreduser = get_user_by('login', $_COOKIE['rsreferredusername']);
                if ($referreduser != false) {
                    $refuserid = $referreduser->ID;
                } else {
                    $refuserid = $_COOKIE['rsreferredusername'];
                }
                $banning_type = FPRewardSystem::check_banning_type($refuserid);
                if ($banning_type != 'earningonly' && $banning_type != 'both') {

                    $referral_registration_points = get_option('rs_referral_reward_signup');
                    $referral_registration_points = RSMemberFunction::user_role_based_reward_points($refuserid, $referral_registration_points);
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($refuserid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $referral_registration_points;
                                if ($totalpointss <= $restrictuserpoints) {

                                    $totalearnedpoints = $referral_registration_points;
                                    RSPointExpiry::insert_earning_points($refuserid, $referral_registration_points, '0', $date, 'RRRP', '0', $totalearnedpoints, '0', '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($referral_registration_points);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($refuserid);
                                    RSPointExpiry::record_the_points($refuserid, $referral_registration_points, '0', $date, 'RRRP', $equearnamt, '0', '0', '0', '0', $user_id, '', $totalpoints, '', '0');
                                    $previouslog = get_option('rs_referral_log');
                                    RS_Referral_Log::main_referral_log_function($refuserid, $user_id, $referral_registration_points, array_filter((array) $previouslog));
                                    update_user_meta($user_id, '_rs_i_referred_by', $refuserid);
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($refuserid, $insertpoints, '0', $date, 'MREPFU', $order_id, $totalearnedpoints, $totalredeempoints, '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);

                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($refuserid);
                                    RSPointExpiry::record_the_points($refuserid, $insertpoints, '0', $date, 'MREPFU', $equearnamt, '0', $order_id, $productid, '0', '0', '', $totalpoints, '', '0');
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($refuserid, '0', '0', $date, 'MREPFU', $order_id, '0', '0', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($refuserid);
                                RSPointExpiry::record_the_points($refuserid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                            }
                        } else {


                            $totalearnedpoints = $referral_registration_points;
                            RSPointExpiry::insert_earning_points($refuserid, $referral_registration_points, '0', $date, 'RRRP', '0', $totalearnedpoints, '0', '');
                            $equearnamt = RSPointExpiry::earning_conversion_settings($referral_registration_points);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($refuserid);
                            RSPointExpiry::record_the_points($refuserid, $referral_registration_points, '0', $date, 'RRRP', $equearnamt, '0', '0', '0', '0', $user_id, '', $totalpoints, '', '0');
                            $previouslog = get_option('rs_referral_log');
                            RS_Referral_Log::main_referral_log_function($refuserid, $user_id, $referral_registration_points, array_filter((array) $previouslog));
                            update_user_meta($user_id, '_rs_i_referred_by', $refuserid);
                        }
                    } else {


                        $totalearnedpoints = $referral_registration_points;
                        RSPointExpiry::insert_earning_points($refuserid, $referral_registration_points, '0', $date, 'RRRP', '0', $totalearnedpoints, '0', '');
                        $equearnamt = RSPointExpiry::earning_conversion_settings($referral_registration_points);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($refuserid);
                        RSPointExpiry::record_the_points($refuserid, $referral_registration_points, '0', $date, 'RRRP', $equearnamt, '0', '0', '0', '0', $user_id, '', $totalpoints, '', '0');
                        $previouslog = get_option('rs_referral_log');
                        RS_Referral_Log::main_referral_log_function($refuserid, $user_id, $referral_registration_points, array_filter((array) $previouslog));
                        update_user_meta($user_id, '_rs_i_referred_by', $refuserid);
                    }
                }
            }
        }
    }

    public static function reward_points_for_login() {

        $strtotime = array();
        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        if (is_user_logged_in()) {
            if (get_option('rs_enable_reward_points_for_login') == 'yes') {
                $userid = get_current_user_id();
                $date = date('y-m-d');
                $strtotime = strtotime($date);
                $getusermeta = (array) RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($userid, 'rs_login_date');
                if (!in_array($strtotime, $getusermeta)) {
                    $restrictuserpoints = get_option('rs_max_earning_points_for_user');
                    $enabledisablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
                    $pointsforlogin = get_option('rs_reward_points_for_login');
                    $oldpoints = $wpdb->get_results("SELECT SUM((earnedpoints-usedpoints)) as availablepoints FROM $table_name WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) and userid=$userid", ARRAY_A);
                    $totaloldpoints = $oldpoints[0]['availablepoints'];
                    $totalpoints = $totaloldpoints + $pointsforlogin;
                    $noofdays = get_option('rs_point_to_be_expire');
                  
                    if (($noofdays != '0') && ($noofdays != '')) {
                        $date = time() + ($noofdays * 24 * 60 * 60);
                    } else {
                        $date = '999999999999';
                    }
                    if ($enabledisablemaxpoints == 'yes') {
                        if (($restrictuserpoints != '') && ($restrictuserpoints != '0')) {
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($userid);
                            if ($getoldpoints <= $restrictuserpoints) {
                                $totalpointss = $getoldpoints + $pointsforlogin;
                                if ($totalpointss <= $restrictuserpoints) {
                                    RSPointExpiry::insert_earning_points($userid, $pointsforlogin, '0', $date, 'LRP', '', '', '', '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($pointsforlogin);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($userid);
                                    RSPointExpiry::record_the_points($userid, $pointsforlogin, '0', $date, 'LRP', $equearnamt, '0', '0', '0', '0', '0', '', $totalpoints, '', '0');
                                    $oldlogindata = (array) RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($userid, 'rs_login_date');
                                    $newdata = (array) $strtotime;
                                    $mergedata = array_merge($oldlogindata, $newdata);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($userid, 'rs_login_date', $mergedata);
                                } else {
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($userid, $insertpoints, '0', $date, 'MREPFU', '', '', '', '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($userid);
                                    RSPointExpiry::record_the_points($userid, $insertpoints, '0', $date, 'MREPFU', $equearnamt, '0', '0', '0', '0', '0', '', $totalpoints, '', '0');
                                    $oldlogindata = (array) RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($userid, 'rs_login_date');
                                    $newdata = (array) $strtotime;
                                    $mergedata = array_merge($oldlogindata, $newdata);
                                    RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($userid, 'rs_login_date', $mergedata);
                                }
                            } else {
                                RSPointExpiry::insert_earning_points($userid, '0', '0', $date, 'MREPFU', $order_id, '', '', '');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($userid);
                                RSPointExpiry::record_the_points($userid, '0', '0', $date, 'MREPFU', '0', '0', $order_id, '0', '0', '0', '', $totalpoints, '', '0');
                                $oldlogindata = (array) RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($userid, 'rs_login_date');
                                $newdata = (array) $strtotime;
                                $mergedata = array_merge($oldlogindata, $newdata);
                                RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($userid, 'rs_login_date', $mergedata);
                            }
                        } else {
                            RSPointExpiry::insert_earning_points($userid, $pointsforlogin, '0', $date, 'LRP', '', '', '', '');
                            $equearnamt = RSPointExpiry::earning_conversion_settings($pointsforlogin);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($userid);
                            RSPointExpiry::record_the_points($userid, $pointsforlogin, '0', $date, 'LRP', $equearnamt, '0', '0', '0', '0', '0', '', $totalpoints, '', '0');
                            $oldlogindata = (array) RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($userid, 'rs_login_date');
                            $newdata = (array) $strtotime;
                            $mergedata = array_merge($oldlogindata, $newdata);
                            RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($userid, 'rs_login_date', $mergedata);
                        }
                    } else {
                        RSPointExpiry::insert_earning_points($userid, $pointsforlogin, '0', $date, 'LRP', '', '', '', '');
                        $equearnamt = RSPointExpiry::earning_conversion_settings($pointsforlogin);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($userid);
                        RSPointExpiry::record_the_points($userid, $pointsforlogin, '0', $date, 'LRP', $equearnamt, '0', '0', '0', '0', '0', '', $totalpoints, '', '0');
                        $oldlogindata = (array) RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($userid, 'rs_login_date');
                        $newdata = (array) $strtotime;
                        $mergedata = array_merge($oldlogindata, $newdata);
                        RSFunctionForSavingMetaValues::rewardsystem_update_user_meta($userid, 'rs_login_date', $mergedata);
                    }
                }
            }
        }
        do_action('fp_reward_point_for_login');
    }

}

new RSRegistrationPoints();
