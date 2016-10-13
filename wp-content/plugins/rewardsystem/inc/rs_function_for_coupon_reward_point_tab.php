<?php

class RSFunctionForCouponRewardPoints {
    
    public function __construct() {
        
        add_action('woocommerce_admin_field_rs_coupon_usage_points_dynamics', array($this, 'reward_add_coupon_usage_points_to_action'));
        
        add_action('woocommerce_update_options_rewardsystem_coupon_reward_points', array($this, 'save_data_for_dynamic_rule_coupon_points'));
        
        add_action('woocommerce_before_cart_table', array($this, 'display_message_coupon_reward_points'), 999);

        add_action('woocommerce_before_checkout_form', array($this, 'display_message_coupon_reward_points'), 999);

        
    }
    
    public static function reward_add_coupon_usage_points_to_action() {
        wp_nonce_field(plugin_basename(__FILE__), 'rsdynamicrulecreation_coupon_usage');
        global $woocommerce;
        ?>
        <style type="text/css">
            .coupon_code_points_selected{
                width: 60%!important;
            }
            .coupon_code_points{
                width: 60%!important;
            }
            .chosen-container-multi {
                position:absolute!important;
            }


        </style>
        <table class="widefat fixed rsdynamicrulecreation_coupon_usage" cellspacing="0">
            <thead>
                <tr>

                    <th class="manage-column column-columnname" scope="col"><?php _e('Coupon Codes', 'rewardsystem'); ?></th>
                    <th class="manage-column column-columnname" scope="col"><?php _e('Reward Points', 'rewardsystem'); ?></th>
                    <th class="manage-column column-columnname num" scope="col"><?php _e('Remove Linking', 'rewardsystem'); ?></th>
                </tr>
            </thead>
            <tbody id="here">
                <?php
                $rewards_dynamic_rulerule_coupon_points = get_option('rewards_dynamic_rule_couponpoints');

                $i = 0;
                if (is_array($rewards_dynamic_rulerule_coupon_points)) {
                    foreach ($rewards_dynamic_rulerule_coupon_points as $rewards_dynamic_rule) {                       
                        ?>
                        <tr>
                            <td class="column-columnname">
                                <p class="form-field">
                                    <select multiple="multiple" name="rewards_dynamic_rule_coupon_usage[<?php echo $i; ?>][coupon_codes][]" class="short coupon_code_points_selected">
                                        <?php
                                        if ($rewards_dynamic_rule["coupon_codes"] != "") {
                                            $coupons_list = $rewards_dynamic_rule["coupon_codes"];
                                            foreach ($coupons_list as $separate_coupons) {
                                                ?>
                                                <option value="<?php echo $separate_coupons; ?>" selected><?php echo $separate_coupons; ?></option>
                                                <?php
                                            }
                                            foreach (get_posts('post_type=shop_coupon') as $value) {
                                                $coupon_title = $value->post_title;
                                                $coupon_object = new WC_Coupon($coupon_title);
                                               if(empty($coupon_object->product_ids) && empty($coupon_object->exclude_product_ids)  && $coupon_object->usage_limit_per_user == '' && $coupon_object->expiry_date == ''  && empty($coupon_object->product_categories)&&  $coupon_object->maximum_amount == '' && $coupon_object->minimum_amount == '' && $coupon_object->usage_limit ==''){
        
                                                    if (!in_array($coupon_title, $coupons_list)) {
                                                        ?>
                                                        <option value="<?php echo $coupon_title; ?>"><?php echo $coupon_title; ?></option>
                                                        <?php
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                    </select>
                                </p>
                            </td>


                            <td class="column-columnname">
                                <p class="form-field">
                                    <input type="text" name="rewards_dynamic_rule_coupon_usage[<?php echo $i; ?>][reward_points]" value="<?php echo $rewards_dynamic_rule["reward_points"]; ?>" />
                                </p>
                            </td>
                            <td class="column-columnname num">
                                <span class="remove button-secondary"><?php _e('Remove Linking', 'rewardsystem'); ?></span>
                            </td>
                        </tr>


                        <?php
                        $i = $i + 1;
                    }
                }
                ?>
            </tbody>
            <tfoot>
                <tr>
                    <td></td>
                    <td></td>

                    <td class="manage-column column-columnname num" scope="col"> <span class="add button-primary"><?php _e('Add Linking', 'rewardsystem'); ?></span></td>
                </tr>
                <tr>

                    <th class="manage-column column-columnname" scope="col"><?php _e('Coupon Codes', 'rewardsystem'); ?></th>
                    <th class="manage-column column-columnname" scope="col"><?php _e('Reward Points', 'rewardsystem'); ?></th>
                    <th class="manage-column column-columnname num" scope="col"><?php _e('Add Linking', 'rewardsystem'); ?></th>

                </tr>
            </tfoot>
        </table>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('#afterclick').hide();
                var countrewards_dynamic_rule = <?php echo $i; ?>;
                jQuery(".add").click(function () {
                    jQuery('#afterclick').show();
                    countrewards_dynamic_rule = countrewards_dynamic_rule + 1;
                    jQuery('#here').append('<tr><td><p class="form-field"><select multiple="multiple" name="rewards_dynamic_rule_coupon_usage[' + countrewards_dynamic_rule + '][coupon_codes][]" class="short coupon_points coupon_code_points"><?php
        foreach (get_posts('post_type=shop_coupon') as $value) {
            $coupon_title = $value->post_title;
            $coupon_object = new WC_Coupon($coupon_title);

           if(empty($coupon_object->product_ids) && empty($coupon_object->exclude_product_ids)  && $coupon_object->usage_limit_per_user == '' && $coupon_object->expiry_date == ''  && empty($coupon_object->product_categories)&&  $coupon_object->maximum_amount == '' && $coupon_object->minimum_amount == '' && $coupon_object->usage_limit ==''){
        
                ?><option value="<?php echo $coupon_title; ?>"><?php echo $coupon_title; ?><?php
            }
        }
        ?></option></select></p></td>\n\
        \n\<td><p class="form-field"><input type = "text" name="rewards_dynamic_rule_coupon_usage[' + countrewards_dynamic_rule + '][reward_points]" class="short " /></p></td>\n\
        \n\\n\
        \n\
        <td class="num"><span class="remove button-secondary">Remove Linking</span></td></tr><hr>');
        <?php if ((float) $woocommerce->version > (float) ('2.2.0')) { ?>
                        jQuery('.coupon_code_points').select2();
        <?php } else { ?>
                        jQuery('.coupon_code_points').chosen();
        <?php } ?>
                });

                jQuery(document).on('click', '.remove', function () {
                    jQuery(this).parent().parent().remove();
                });
        <?php if ((float) $woocommerce->version > (float) ('2.2.0')) { ?>
                    jQuery('.coupon_code_points_selected').select2();
        <?php } else { ?>
                    jQuery('.coupon_code_points_selected').chosen();
        <?php } ?>
            });



        </script>

        <?php        
    }
    
    public static function save_data_for_dynamic_rule_coupon_points() {
        $rewards_dynamic_rulerule_couponpoints = array_values($_POST['rewards_dynamic_rule_coupon_usage']);
        update_option('rewards_dynamic_rule_couponpoints', $rewards_dynamic_rulerule_couponpoints);
        return false;
    }
    
    public static function multi_dimensional_descending_sort_coupon_points($arr, $index) {
        $b = array();
        $c = array();
        if (is_array($arr)) {
            foreach ($arr as $key => $value) {
                $b[$key] = $value[$index];
            }
            arsort($b);            
            foreach ($b as $key => $value) {             
                $c[] = $arr[$key];
            }

            return $c;
        }
    }
    
    
    public static function find_coupon_values($couponcode, $applied_coupons_cart) {

        if (is_array($applied_coupons_cart)) {
            if (in_array($couponcode, $applied_coupons_cart)) {
                return "1";
            }
        }
    }
    public static function apply_coupon_code_reward_points_user($order_id) {        
        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        $table_name2 = $wpdb->prefix . 'rsrecordpoints';
        $order = new WC_order($order_id);
        $coupons_applied_in_cart = '';
        if (get_option('rs_choose_priority_level_selection_coupon_points') == '1') {
            $coupons_for_points_rule_list = self::multi_dimensional_descending_sort_coupon_points(get_option('rewards_dynamic_rule_couponpoints'), 'reward_points');
        } else {
            $coupons_for_points_rule_list = RSMemberFunction::multi_dimensional_sort(get_option('rewards_dynamic_rule_couponpoints'), 'reward_points');
        }

        $getthedatas = array();
        if(is_array($coupons_for_points_rule_list)){
            if(!empty($coupons_for_points_rule_list)){           
                foreach ($coupons_for_points_rule_list as $key => $value) {            
                    if (!in_array($value['coupon_codes'], $getthedatas)) {
                        $getthedatas[$key] = $value['coupon_codes'];
                    }
                }
             
            }
        }
        $c = array();
        foreach ($getthedatas as $key => $mainvalue) {
            $c[] = $coupons_for_points_rule_list[$key];
        }

        $rewardpointscoupons = $order->get_items(array('coupon'));       
        foreach ($rewardpointscoupons as $applied_coupons) {
            $coupons_applied_in_cart[] = $applied_coupons['name'];           
        }

        foreach ($c as $coupons_for_points_each_rule) {
            global $woocommerce;
            $rule_created_coupons_list = $coupons_for_points_each_rule["coupon_codes"];

            $rule_created_coupons_points_list = $coupons_for_points_each_rule["reward_points"];           

            foreach ($rule_created_coupons_list as $separate_rule_coupons) {
                
                $coupon_name_shortcode_to_find = "[coupon_name]";
                $coupon_name_shortcode_to_replace = $separate_rule_coupons;
                
                $coupon_reward_points_to_update = $rule_created_coupons_points_list;

                $newfunctionchecker = self::find_coupon_values($separate_rule_coupons, $coupons_applied_in_cart);  
                
                $boolvalue = false;
                $noofdays = get_option('rs_point_to_be_expire');
                   
            if (($noofdays != '0') && ($noofdays != '')) {
                $date =   time() +($noofdays * 24 * 60 * 60);
            } else {
                $date = '999999999999';
            }
                if ((int) $newfunctionchecker == 1) {
                    $restrictuserpoints = get_option('rs_max_earning_points_for_user');
                    $enabledisablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
                    if($enabledisablemaxpoints == 'yes'){
                        if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                            if($getoldpoints <= $restrictuserpoints){
                                $totalpointss = $getoldpoints + $coupon_reward_points_to_update;
                                if($totalpointss <= $restrictuserpoints){
                                    $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id,$order->user_id);
                                    $pointsredeemed = RSPointExpiry::perform_calculation_with_expiry($redeempoints, $order->user_id);                    
                                    RSPointExpiry::insert_earning_points($order->user_id,$coupon_reward_points_to_update,$pointsredeemed,$date,'RPC',$order_id,'','','');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($coupon_reward_points_to_update);    
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                        
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                                    RSPointExpiry::record_the_points($order->user_id, $coupon_reward_points_to_update,$pointsredeemed,$date,'RPC',$equearnamt,$equredeemamt,$order_id,'0','0','0','',$totalpoints,'','0');
                                    $gettotalearnedpoints = $wpdb->get_results("SELECT SUM((earnedpoints)) as availablepoints FROM $table_name WHERE orderid = $order_id", ARRAY_A);
                                    $totalearnedpoints = $gettotalearnedpoints[0]['availablepoints'];
                                    $totalredeempoints = $redeempoints;
                                    $wpdb->query("UPDATE $table_name SET totalearnedpoints = $totalearnedpoints,totalredeempoints = $totalredeempoints WHERE orderid = $order_id");                                       
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($totalredeempoints);
                                    $wpdb->query("UPDATE $table_name2 SET redeempoints = $totalredeempoints,redeemequauivalentamount = $equredeemamt WHERE orderid = $order_id");               
                                    $boolvalue = true;
                                }else{
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($order->user_id, $insertpoints,$pointsredeemed,$date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints,'');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                    
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                                    RSPointExpiry::record_the_points($order->user_id, $insertpoints,$pointsredeemed,$date,'MREPFU',$equearnamt,$equredeemamt,$order_id,'0','0','0','',$totalpoints,'','0');                       
                                }
                            }else{
                                RSPointExpiry::insert_earning_points($order->user_id,'0','0',$date,'MREPFU',$order_id,'0','0','');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                                RSPointExpiry::record_the_points($order->user_id, '0','0',$date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                            }
                        }else{
                                    $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id,$order->user_id);
                                    $pointsredeemed = RSPointExpiry::perform_calculation_with_expiry($redeempoints, $order->user_id);                    
                                    RSPointExpiry::insert_earning_points($order->user_id,$coupon_reward_points_to_update,$pointsredeemed,$date,'RPC',$order_id,'','','');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($coupon_reward_points_to_update);    
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                        
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                                    RSPointExpiry::record_the_points($order->user_id, $coupon_reward_points_to_update,$pointsredeemed,$date,'RPC',$equearnamt,$equredeemamt,$order_id,'0','0','0','',$totalpoints,'','0');
                                    $gettotalearnedpoints = $wpdb->get_results("SELECT SUM((earnedpoints)) as availablepoints FROM $table_name WHERE orderid = $order_id", ARRAY_A);
                                    $totalearnedpoints = $gettotalearnedpoints[0]['availablepoints'];
                                    $totalredeempoints = $redeempoints;
                                    $wpdb->query("UPDATE $table_name SET totalearnedpoints = $totalearnedpoints,totalredeempoints = $totalredeempoints WHERE orderid = $order_id");                                       
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($totalredeempoints);
                                    $wpdb->query("UPDATE $table_name2 SET redeempoints = $totalredeempoints,redeemequauivalentamount = $equredeemamt WHERE orderid = $order_id");               
                                    $boolvalue = true;
                        }
                    }else{
                                    $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id,$order->user_id);
                                    $pointsredeemed = RSPointExpiry::perform_calculation_with_expiry($redeempoints, $order->user_id);                    
                                    RSPointExpiry::insert_earning_points($order->user_id,$coupon_reward_points_to_update,$pointsredeemed,$date,'RPC',$order_id,'','','');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($coupon_reward_points_to_update);    
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                        
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($order->user_id);
                                    RSPointExpiry::record_the_points($order->user_id, $coupon_reward_points_to_update,$pointsredeemed,$date,'RPC',$equearnamt,$equredeemamt,$order_id,'0','0','0','',$totalpoints,'','0');
                                    $gettotalearnedpoints = $wpdb->get_results("SELECT SUM((earnedpoints)) as availablepoints FROM $table_name WHERE orderid = $order_id", ARRAY_A);
                                    $totalearnedpoints = $gettotalearnedpoints[0]['availablepoints'];
                                    $totalredeempoints = $redeempoints;
                                    $wpdb->query("UPDATE $table_name SET totalearnedpoints = $totalearnedpoints,totalredeempoints = $totalredeempoints WHERE orderid = $order_id");                                       
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($totalredeempoints);
                                    $wpdb->query("UPDATE $table_name2 SET redeempoints = $totalredeempoints,redeemequauivalentamount = $equredeemamt WHERE orderid = $order_id");               
                                    $boolvalue = true;
                        }                    
                }
            }
        }
        do_action('fp_reward_point_for_using_coupons');
    }
    
    public static function display_message_coupon_reward_points() {
        global $woocommerce;        
        if (get_option('rs_choose_priority_level_selection_coupon_points') == '1') {
            $coupons_for_points_rule_list = self::multi_dimensional_descending_sort_coupon_points(get_option('rewards_dynamic_rule_couponpoints'), 'reward_points');
        } else {
            $coupons_for_points_rule_list = RSMemberFunction::multi_dimensional_sort(get_option('rewards_dynamic_rule_couponpoints'), 'reward_points');
        }
        $getthedatas = array();
        if (is_array($coupons_for_points_rule_list) && !empty($coupons_for_points_rule_list)) {
            foreach ($coupons_for_points_rule_list as $key => $value) {
                if(isset($value['coupon_codes'])){
                    if (!in_array($value['coupon_codes'], $getthedatas)) {
                        $getthedatas[$key] = $value['coupon_codes'];
                    }
                }
            }
        }
        $c = array();
        if(is_array($getthedatas) && !empty($getthedatas)){
            foreach ($getthedatas as $key => $mainvalue) {
                $c[] = $coupons_for_points_rule_list[$key];
            }
        }


        if(is_array($c) && !empty($c)){
            foreach ($c as $coupons_for_points_each_rule) {
                $rule_created_coupons_list = $coupons_for_points_each_rule["coupon_codes"];

                $rule_created_coupons_points_list = $coupons_for_points_each_rule["reward_points"];
                if(is_array($rule_created_coupons_list) && !empty($rule_created_coupons_list)){
                    foreach ($rule_created_coupons_list as $separate_rule_coupons) {                
                        $newfunctionchecker = self::find_coupon_values($separate_rule_coupons, $woocommerce->cart->applied_coupons);
                        if ($newfunctionchecker == '1') {
                            $coupon_name_shortcode_to_find = "[coupon_name]";
                            $coupon_name_shortcode_to_replace = $separate_rule_coupons;
                            $coupon_name_shortcode_replaced = str_replace($coupon_name_shortcode_to_find, $coupon_name_shortcode_to_replace, get_option('rs_coupon_applied_reward_success'));
                            $coupon_reward_points_shortcode_to_find = "[coupon_rewardpoints]";
                            $coupon_reward_points_shortcode_to_replace = $rule_created_coupons_points_list;
                            $coupon_reward_points_shortcode_replaced = str_replace($coupon_reward_points_shortcode_to_find, $coupon_reward_points_shortcode_to_replace, $coupon_name_shortcode_replaced);
                            ?>
                            <div class="woocommerce-message">
                                <?php echo $coupon_reward_points_shortcode_replaced; ?>
                            </div>
                            <?php
                        }
                    }
                }
            }
        }
    }
}
new RSFunctionForCouponRewardPoints();