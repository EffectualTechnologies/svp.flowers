<?php

class RSFunctionForCheckout {

    public function __construct() {

        add_action('woocommerce_review_order_after_order_total', array($this, 'display_earned_points_checkout'));

        add_action('woocommerce_admin_field_rs_product_for_purchase', array($this, 'rs_purchase_selected_product_using_points'));

        add_action('woocommerce_update_option_rs_product_for_purchase', array($this, 'save_purchase_selected_product_using_points'));

        add_action('admin_head', array($this, 'rs_purchase_product_using_point'));

        add_action('admin_head', array($this, 'rs_validation_of_input_field_in_checkout'));

        add_action('woocommerce_before_checkout_form', array($this, 'reward_system_checkout_page_redeeming'));

        add_action('woocommerce_removed_coupon', array($this, 'testing_checkout_coupon'));


        if (get_option('rs_reward_point_troubleshoot_before_cart') == '1') {

            add_action('woocommerce_before_cart', array($this, 'your_current_points_cart_page'));
        } else {

            add_action('woocommerce_before_cart_table', array($this, 'your_current_points_cart_page'));
        }

        add_action('woocommerce_before_checkout_form', array($this, 'your_current_points_checkout_page'));

        add_shortcode('userpoints', array($this, 'add_shortcode_for_user_points'));

        add_shortcode('userpoints_value', array($this, 'add_shortcode_for_user_points_value'));

        if (get_option('rs_reward_point_troubleshoot_before_cart') == '1') {

            add_action('woocommerce_before_cart', array($this, 'display_complete_message_cart_page'));
        } else {

            add_action('woocommerce_before_cart_table', array($this, 'display_complete_message_cart_page'));
        }

        add_action('woocommerce_before_checkout_form', array($this, 'display_complete_message_checkout_page'));

        add_shortcode('totalrewards', array($this, 'getshortcodetotal_rewards'));

        add_shortcode('totalrewardsvalue', array($this, 'getvalueshortcodetotal_rewards'));

        if (get_option('rs_reward_point_troubleshoot_before_cart') == '1') {

            add_action('woocommerce_before_cart', array($this, 'show_message_for_guest_cart_page'));
        } else {

            add_action('woocommerce_before_cart_table', array($this, 'show_message_for_guest_cart_page'));
        }

        add_action('woocommerce_before_checkout_form', array($this, 'show_message_for_guest_checkout_page'));

        add_shortcode('loginlink', array($this, 'get_my_account_url_link'));

        add_action('woocommerce_after_checkout_form', array($this, 'add_custom_message_to_payment_gateway_on_checkout'));

        add_action('wp_ajax_rs_order_payment_gateway_reward', array($this, 'payment_gateway_reward_points_process_ajax_request'));
        add_action('wp_head', array($this, 'show_hide_coupon_code'));
    }

    public static function show_hide_coupon_code() {

        if (is_checkout()) {

            if (get_option('rs_show_hide_coupon_field_checkout') == 2) {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        jQuery('.showcoupon').parent().hide();
                    });
                </script>
                <?php
            }
        }
    }

    public static function testing_checkout_coupon($couponcode) {
        if (get_option('rs_enable_disable_reward_point_based_coupon_amount') == 'yes') {
            if (is_checkout()) {
                echo "<script type='text/javascript'> window.location.href=window.location.href;</script>";
            }
        }
    }

    public static function display_earned_points_checkout() {
        global $woocommerce;
        if (get_option('rs_show_hide_total_points_checkout_field') == '1') {
            $total_points = WC()->session->get('rewardpoints');
            if ($total_points != 0) {
                  $total = $woocommerce->cart->discount_cart;
                   if ($total != 0) {  
                        if (get_option('rs_enable_redeem_for_order') == 'no') {
                ?>
                <tr class="tax-total">
                    <th><?php echo get_option('rs_total_earned_point_caption_checkout'); ?></th>
                    <td><?php echo $total_points; ?></td>
                </tr>
                <?php
                        }
                   }else{
                        ?>
                <tr class="tax-total">
                    <th><?php echo get_option('rs_total_earned_point_caption_checkout'); ?></th>
                    <td><?php echo $total_points; ?></td>
                </tr>
                <?php
                   }
            }
        }
    }

    /*
     * Function to select the products which are going to be buy using Reward Points
     */

    public static function rs_purchase_selected_product_using_points() {

        global $woocommerce;
        if ((float) $woocommerce->version > (float) ('2.2.0')) {
            ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_select_product_for_purchase_using_points"><?php _e('Select Products for Purchase using Points', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    <input type="hidden" class="wc-product-search" style="width: 350px;" id="rs_select_product_for_purchase_using_points"  name="rs_select_product_for_purchase_using_points" data-placeholder="<?php _e('Search for a product&hellip;', 'rewardsystem'); ?>" data-action="woocommerce_json_search_products_and_variations" data-multiple="true" data-selected="<?php
                    $json_ids = array();
                    if (get_option('rs_select_product_for_purchase_using_points') != "") {
                        $list_of_produts = get_option('rs_select_product_for_purchase_using_points');
                        $product_ids = array_filter(array_map('absint', (array) explode(',', get_option('rs_select_product_for_purchase_using_points'))));
                        if ($product_ids != NULL) {
                            foreach ($product_ids as $product_id) {
                                $product = wc_get_product($product_id);
                                if (is_object($product)) {
                                    $json_ids[$product_id] = wp_kses_post($product->get_formatted_name());
                                }
                            } echo esc_attr(json_encode($json_ids));
                        }
                    }
                    ?>" value="<?php echo implode(',', array_keys($json_ids)); ?>" />
                </td>
            </tr>
        <?php } else { ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_select_product_for_purchase_using_points"><?php _e('Select Products for Purchase using Points', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    <?php
                    ?>
                    <select multiple name="rs_select_product_for_purchase_using_points" style='width:550px;' id='rs_select_product_for_purchase_using_points' class="rs_select_product_for_purchase_using_points">
                        <?php
                        $selected_products_purchase = array_filter((array) get_option('rs_select_product_for_purchase_using_points'));
                        if ($selected_products_purchase != "") {
                            if (!empty($selected_products_purchase)) {
                                $list_of_produts = (array) get_option('rs_select_product_for_purchase_using_points');
                                foreach ($list_of_produts as $rs_free_id) {
                                    ?>
                                    <option value="<?php echo $rs_free_id; ?>" selected="selected"><?php echo '#' . $rs_free_id . ' &ndash; ' . get_the_title($rs_free_id); ?></option>
                                    <?php
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
     * Function to save the selected the products which are going to be buy using Reward Points
     */

    public static function save_purchase_selected_product_using_points() {
        global $woocommerce;
        update_option('rs_select_product_for_purchase_using_points', $_POST['rs_select_product_for_purchase_using_points']);
    }

    /*
     * Function to show or hide the select product field
     */

    public static function rs_purchase_product_using_point() {
        global $woocommerce;
        if (isset($_GET['tab'])) {
            if ($_GET['tab'] == 'rewardsystem_checkout') {
                ?>
                <?php
                echo RSJQueryFunction::rs_common_ajax_function_to_select_products('rs_select_product_for_purchase_using_points')
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {

                        var enable_selected_product_for_purchase = jQuery('#rs_enable_selected_product_for_purchase_using_points').is(':checked') ? 'yes' : 'no';
                        if (enable_selected_product_for_purchase == 'yes') {
                            jQuery('#rs_select_product_for_purchase_using_points').parent().parent().show();
                        } else {
                            jQuery('#rs_select_product_for_purchase_using_points').parent().parent().hide();
                        }

                        jQuery('#rs_enable_selected_product_for_purchase_using_points').change(function () {
                            jQuery('#rs_select_product_for_purchase_using_points').parent().parent().toggle();
                        });

                        //Show or Hide For Redeem Button Type
                        if (jQuery('#rs_redeem_field_type_option_checkout').val() == '1') {
                            jQuery('#rs_percentage_cart_total_redeem_checkout').parent().parent().hide();
                        } else {
                            jQuery('#rs_percentage_cart_total_redeem_checkout').parent().parent().show();
                        }

                        jQuery('#rs_redeem_field_type_option_checkout').change(function () {
                            if (jQuery('#rs_redeem_field_type_option_checkout').val() == '1') {
                                jQuery('#rs_percentage_cart_total_redeem_checkout').parent().parent().hide();
                            } else {
                                jQuery('#rs_percentage_cart_total_redeem_checkout').parent().parent().show();
                            }
                        });
                    });
                </script>
                <?php
            }
        }
    }

    public static function rs_validation_of_input_field_in_checkout() {
        ?>

        <script type="text/javascript">

            jQuery(function () {
                jQuery('body').on('blur', '#rs_percentage_cart_total_redeem_checkout[type=text]', function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });

                    return this;
                });

                jQuery('body').on('keyup change', '#rs_percentage_cart_total_redeem_checkout[type=text]', function () {
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
            });
        </script>
        <?php
    }

    public static function reward_system_checkout_page_redeeming() {
        ?>
        <style type="text/css">
        <?php echo get_option('rs_checkout_page_custom_css'); ?>
        </style>
        <?php
        if (is_user_logged_in()) {
            global $woocommerce;

            $getinfousernickname = get_user_by('id', get_current_user_id());
            $couponcodeuserlogin = $getinfousernickname->user_login;
            $minimum_cart_total_redeem = get_option('rs_minimum_cart_total_points');
            $cart_subtotal_for_redeem = $woocommerce->cart->get_cart_subtotal();
            $cart_subtotal_redeem_amount = preg_replace('/[^0-9\.]+/', '', $cart_subtotal_for_redeem);
            $getinfousernickname = get_user_by('id', get_current_user_id());
            $couponcodeuserlogin = $getinfousernickname->user_login;
            $user_ID = get_current_user_id();
            $current_points_user = RSPointExpiry::get_sum_of_total_earned_points($user_ID);
            if ($current_points_user > '0') {
                $minimum_cart_total_redeem_checkout = get_option('rs_minimum_cart_total_points');
                $cart_subtotal_for_redeem_checkout = $woocommerce->cart->subtotal;
                $cart_subtotal_redeem_amount_checkout = $cart_subtotal_for_redeem_checkout;
                if (get_option('rs_show_hide_redeem_field_checkout') == '1') {
                    $user_ID = get_current_user_id();
                    $checkfirstimeredeem = RSFunctionForSavingMetaValues::rewardsystem_get_user_meta($user_ID, 'rsfirsttime_redeemed');
                    if ($checkfirstimeredeem != '1') {
                        $userid = get_current_user_id();
                        $banning_type = FPRewardSystem::check_banning_type($userid);
                        if ($banning_type != 'redeemingonly' && $banning_type != 'both') {
                            if (get_option('rs_show_hide_redeem_it_field_checkout') == '1') {
                                if (get_option('rs_redeem_field_type_option_checkout') == '1') {
                                    if ($current_points_user >= get_option("rs_first_time_minimum_user_points")) {

                                        if ($cart_subtotal_redeem_amount_checkout >= $minimum_cart_total_redeem_checkout) {
                                            foreach ($woocommerce->cart->cart_contents as $key) {
                                                $product_id = $key['product_id'];
                                                $type[] = RSFunctionForCart::check_display_price_type($product_id);
                                            }

                                            if (!in_array(2, $type)) {
                                                if (get_option('rs_redeem_field_type_option_checkout') == '1') {
                                                    $user_ID = get_current_user_id();
                                                    $getinfousernickname = get_user_by('id', $user_ID);
                                                    $couponcodeuserlogin = $getinfousernickname->user_login;
                                                    $auto_redeem_name = 'auto_redeem_' . strtolower($couponcodeuserlogin);
                                                    $array = $woocommerce->cart->get_applied_coupons();

                                                    if (!in_array($auto_redeem_name, $array)) {
                                                        ?>
                                                        <div class="woocommerce-info"><?php echo get_option('rs_reedming_field_label_checkout'); ?> <a href="javascript:void(0)" class="redeemit"> <?php echo get_option('rs_reedming_field_link_label_checkout'); ?></a></div>
                                                        <?php
                                                    }
                                                } else {

                                                    self::reward_checkout_redeeming_type_button($cart_subtotal_redeem_amount_checkout, $minimum_cart_total_redeem_checkout);
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    $getuserid = get_current_user_id();
                                    $current_carttotal_amount = $woocommerce->cart->subtotal;
                                    $tax_amount = $woocommerce->cart->tax_total;
                                    $current_carttotal_amount_updated = $current_carttotal_amount - $tax_amount;
                                    $redeem_conversion = get_option('rs_redeem_point');
                                    $current_carttotal_in_points = $current_carttotal_amount_updated * $redeem_conversion;
                                    $limitation_percentage_for_redeeming = get_option('rs_percentage_cart_total_redeem');
                                    $updated_points_step1 = $current_carttotal_in_points / 100;
                                    $updated_points_for_redeeming = $updated_points_step1 * $limitation_percentage_for_redeeming;
                                    $currency_symbol_string_to_find = "[currencysymbol]";
                                    $cartpoints_string_to_replace = "[cartredeempoints]";
                                    $currency_symbol_string_to_find = "[currencysymbol]";
                                    $cuurency_value_string_to_find = "[pointsvalue]";
                                    if ($current_points_user >= $updated_points_for_redeeming) {

                                        $points_for_redeeming = $updated_points_for_redeeming;
                                        $cuurency_value_string_to_find = "[pointsvalue]";
                                        $points_conversion_value = wc_format_decimal(get_option('rs_redeem_point_value'));
                                        $points_currency_value = $updated_points_for_redeeming / $redeem_conversion;
                                        $points_currency_amount_to_replace = $updated_points_for_redeeming;
                                        $points_for_redeeming = $updated_points_for_redeeming / $points_conversion_value;
                                        $redeem_button_message_more = get_option('rs_redeeming_button_option_message_checkout');

                                        $currency_symbol_string_to_replace = RSFunctionForCart::get_woocommerce_formatted_price($points_currency_value);
                                        $redeem_button_message_replaced_first = str_replace($cuurency_value_string_to_find, $currency_symbol_string_to_replace, $redeem_button_message_more);
                                        $redeem_button_message_replaced_second = str_replace($currency_symbol_string_to_find, "", $redeem_button_message_replaced_first);
                                        $redeem_button_message_replaced_third = str_replace($cartpoints_string_to_replace, $points_for_redeeming, $redeem_button_message_replaced_second);
                                    } else {
                                        $points_for_redeeming = $current_points_user;
                                        $redeem_button_message_more = get_option('rs_redeeming_button_option_message_checkout');
                                        $points_conversion_value = wc_format_decimal(get_option('rs_redeem_point_value'));
                                        $points_currency_value = $points_for_redeeming / $redeem_conversion;
                                        $points_currency_amount_to_replace = $points_currency_value * $points_conversion_value;
                                        $currency_symbol_string_to_replace = RSFunctionForCart::get_woocommerce_formatted_price($points_currency_value);
                                        $redeem_button_message_replaced_first = str_replace($currency_symbol_string_to_find, "", $redeem_button_message_more);
                                        $redeem_button_message_replaced_second = str_replace($cuurency_value_string_to_find, $currency_symbol_string_to_replace, $redeem_button_message_replaced_first);
                                        $redeem_button_message_replaced_third = str_replace($cartpoints_string_to_replace, $points_for_redeeming, $redeem_button_message_replaced_second);
                                    }
                                    if ($current_points_user >= get_option("rs_first_time_minimum_user_points")) {
                                        if ($cart_subtotal_redeem_amount_checkout >= $minimum_cart_total_redeem_checkout) {
                                            foreach ($woocommerce->cart->cart_contents as $item) {
                                                $product_id = $item['variation_id'] != 0 ? $item['variation_id'] : $item['product_id'];
                                                $type[] = RSFunctionForCart::check_display_price_type($product_id);
                                                $enable = RSFunctionForCart::calculate_point_price_for_products($product_id);
                                                if ($enable[$product_id] != '') {
                                                    $cart_object[] = $enable[$product_id];
                                                }
                                            }
                                            if (empty($cart_object)) {
                                                if (!in_array(2, $type)) {
                                                    $user_ID = get_current_user_id();
                                                    $getinfousernickname = get_user_by('id', $user_ID);
                                                    $couponcodeuserlogin = $getinfousernickname->user_login;
                                                    $auto_redeem_name = 'auto_redeem_' . strtolower($couponcodeuserlogin);
                                                    $array = $woocommerce->cart->get_applied_coupons();
                                                    if (!in_array($auto_redeem_name, $array)) {
                                                        ?>
                                                        <form method="post">
                                                            <div class="woocommerce-info sumo_reward_points_checkout_apply_discount"><?php echo $redeem_button_message_replaced_third; ?>
                                                                <input id="rs_apply_coupon_code_field" class="input-text" type="hidden"  value="<?php echo $points_for_redeeming; ?> " name="rs_apply_coupon_code_field">
                                                                <input class="button <?php echo get_option('rs_extra_class_name_apply_reward_points'); ?>" type="submit" id='mainsubmi' value="<?php echo get_option('rs_redeem_field_submit_button_caption'); ?>" name="rs_apply_coupon_code1">
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
                        ?>
                        <form name="checkout_redeeming" class="checkout_redeeming" method="post">
                            <?php
                            RSFunctionForCart::reward_system_add_message_after_cart_table();
                            ?>
                        </form>
                        <?php
                    } else {
                        if ($current_points_user >= get_option("rs_minimum_user_points_to_redeem")) {
                            $userid = get_current_user_id();
                            $banning_type = FPRewardSystem::check_banning_type($userid);
                            if ($banning_type != 'redeemingonly' && $banning_type != 'both') {
                                if (get_option('rs_show_hide_redeem_it_field_checkout') == '1') {
                                    if (get_option('rs_redeem_field_type_option_checkout') == '1') {
                                        if ($current_points_user >= get_option("rs_first_time_minimum_user_points")) {
                                            if ($cart_subtotal_redeem_amount >= $minimum_cart_total_redeem) {
                                                foreach ($woocommerce->cart->cart_contents as $key) {
                                                    $product_id = $key['variation_id'] != 0 ? $key['variation_id'] : $key['product_id'];
                                                    $type[] = RSFunctionForCart::check_display_price_type($product_id);
                                                }

                                                if (!in_array(2, $type)) {
                                                    if (get_option('rs_redeem_field_type_option_checkout') == '1') {

                                                        $user_ID = get_current_user_id();
                                                        $getinfousernickname = get_user_by('id', $user_ID);
                                                        $couponcodeuserlogin = $getinfousernickname->user_login;
                                                        $auto_redeem_name = 'auto_redeem_' . strtolower($couponcodeuserlogin);
                                                        $array = $woocommerce->cart->get_applied_coupons();
                                                        if (!in_array($auto_redeem_name, $array)) {
                                                            ?>
                                                            <div class="woocommerce-info"><?php echo get_option('rs_reedming_field_label_checkout'); ?> <a href="javascript:void(0)" class="redeemit"> <?php echo get_option('rs_reedming_field_link_label_checkout'); ?></a></div>
                                                            <?php
                                                        }
                                                    } else {
                                                        self::reward_checkout_redeeming_type_button($cart_subtotal_redeem_amount_checkout, $minimum_cart_total_redeem_checkout);
                                                    }
                                                }
                                            }
                                        }
                                    } else {

                                        $getuserid = get_current_user_id();
                                        $current_carttotal_amount = $woocommerce->cart->subtotal;
                                        $tax_amount = $woocommerce->cart->tax_total;
                                        $current_carttotal_amount_updated = $current_carttotal_amount - $tax_amount;
                                        $redeem_conversion = get_option('rs_redeem_point');
                                        $current_carttotal_in_points = $current_carttotal_amount_updated * $redeem_conversion;
                                        $limitation_percentage_for_redeeming = get_option('rs_percentage_cart_total_redeem');
                                        $updated_points_step1 = $current_carttotal_in_points / 100;
                                        $updated_points_for_redeeming = $updated_points_step1 * $limitation_percentage_for_redeeming;
                                        $currency_symbol_string_to_find = "[currencysymbol]";
                                        $cartpoints_string_to_replace = "[cartredeempoints]";
                                        $currency_symbol_string_to_find = "[currencysymbol]";
                                        $cuurency_value_string_to_find = "[pointsvalue]";
                                        if ($current_points_user >= $updated_points_for_redeeming) {

                                            $points_for_redeeming = $updated_points_for_redeeming;

                                            $cuurency_value_string_to_find = "[pointsvalue]";
                                            $points_conversion_value = wc_format_decimal(get_option('rs_redeem_point_value'));
                                            $points_currency_value = $updated_points_for_redeeming / $redeem_conversion;
                                            $points_currency_amount_to_replace = $updated_points_for_redeeming;
                                            $points_for_redeeming = $updated_points_for_redeeming / $points_conversion_value;
                                            $redeem_button_message_more = get_option('rs_redeeming_button_option_message_checkout');

                                            $currency_symbol_string_to_replace = RSFunctionForCart::get_woocommerce_formatted_price($points_currency_value);
                                            $redeem_button_message_replaced_first = str_replace($cuurency_value_string_to_find, $currency_symbol_string_to_replace, $redeem_button_message_more);
                                            $redeem_button_message_replaced_second = str_replace($currency_symbol_string_to_find, "", $redeem_button_message_replaced_first);
                                            $redeem_button_message_replaced_third = str_replace($cartpoints_string_to_replace, $points_for_redeeming, $redeem_button_message_replaced_second);
                                        } else {

                                            $points_for_redeeming = $current_points_user;
                                            $redeem_button_message_more = get_option('rs_redeeming_button_option_message_checkout');
                                            $points_conversion_value = wc_format_decimal(get_option('rs_redeem_point_value'));
                                            $points_currency_value = $points_for_redeeming / $redeem_conversion;
                                            $points_currency_amount_to_replace = $points_currency_value * $points_conversion_value;
                                            $currency_symbol_string_to_replace = RSFunctionForCart::get_woocommerce_formatted_price($points_currency_value);
                                            $redeem_button_message_replaced_first = str_replace($currency_symbol_string_to_find, "", $redeem_button_message_more);
                                            $redeem_button_message_replaced_second = str_replace($cuurency_value_string_to_find, $currency_symbol_string_to_replace, $redeem_button_message_replaced_first);
                                            $redeem_button_message_replaced_third = str_replace($cartpoints_string_to_replace, $points_for_redeeming, $redeem_button_message_replaced_second);
                                        }
                                        if ($cart_subtotal_redeem_amount >= $minimum_cart_total_redeem) {
                                            $user_ID = get_current_user_id();
                                            $getinfousernickname = get_user_by('id', $user_ID);
                                            $couponcodeuserlogin = $getinfousernickname->user_login;
                                            $auto_redeem_name = 'auto_redeem_' . strtolower($couponcodeuserlogin);
                                            $array = $woocommerce->cart->get_applied_coupons();
                                            foreach ($woocommerce->cart->cart_contents as $item) {
                                                $product_id = $item['variation_id'] != 0 ? $item['variation_id'] : $item['product_id'];
                                                $type[] = RSFunctionForCart::check_display_price_type($product_id);
                                                $enable = RSFunctionForCart::calculate_point_price_for_products($product_id);
                                                if ($enable[$product_id] != '') {
                                                    $cart_object[] = $enable[$product_id];
                                                }
                                            }
                                            if (empty($cart_object)) {

                                                if (!in_array(2, $type)) {
                                                    if (!in_array($auto_redeem_name, $array)) {
                                                        ?>
                                                        <form method="post">
                                                            <div class="woocommerce-info sumo_reward_points_checkout_apply_discount"><?php echo $redeem_button_message_replaced_third; ?>
                                                                <input id="rs_apply_coupon_code_field" clasiss="input-text" type="hidden"  value="<?php echo $points_for_redeeming; ?> " name="rs_apply_coupon_code_field">
                                                                <input class="button <?php echo get_option('rs_extra_class_name_apply_reward_points'); ?>" type="submit" id='mainsubmi' value="<?php echo get_option('rs_redeem_field_submit_button_caption'); ?>" name="rs_apply_coupon_code1">
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
                            ?>
                            <form name="checkout_redeeming" class="checkout_redeeming" method="post">
                                <?php
                                RSFunctionForCart::reward_system_add_message_after_cart_table();
                                ?>
                            </form>
                            <?php
                        } else {
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

                <script type = "text/javascript">
                <?php if (get_option('rs_show_hide_redeem_it_field_checkout') == '1') { ?>
                        jQuery('.fp_apply_reward').css("display", "none");
                        jQuery('.woocommerce-info a.redeemit').click(function () {
                            jQuery('.fp_apply_reward').toggle();
                        });
                <?php } ?>
                </script>
                <?php
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

    public static function reward_checkout_redeeming_type_button($cart_subtotal_redeem_amount_checkout, $minimum_cart_total_redeem_checkout) {
        global $woocommerce;
        $getuserid = get_current_user_id();
        $current_points_user = RSPointExpiry::get_sum_of_total_earned_points($getuserid);
        $current_carttotal_amount = $woocommerce->cart->subtotal;
        $redeem_conversion = get_option('rs_redeem_point');
        $current_carttotal_in_points = $current_carttotal_amount * $redeem_conversion;
        $limitation_percentage_for_redeeming = get_option('rs_percentage_cart_total_redeem');
        $updated_points_step1 = $current_carttotal_in_points / 100;
        $updated_points_for_redeeming = $updated_points_step1 * $limitation_percentage_for_redeeming;
        $currency_symbol_string_to_find = "[currencysymbol]";
        $cartpoints_string_to_replace = "[cartredeempoints]";
        $currency_symbol_string_to_find = "[currencysymbol]";
        $cuurency_value_string_to_find = "[pointsvalue]";
        if ($current_points_user >= $updated_points_for_redeeming) {
            $points_for_redeeming = $updated_points_for_redeeming;
            $cuurency_value_string_to_find = "[pointsvalue]";
            $points_conversion_value = wc_format_decimal(get_option('rs_redeem_point_value'));
            $points_currency_value = $updated_points_for_redeeming / $redeem_conversion;
            $points_currency_amount_to_replace = $updated_points_for_redeeming;
            $points_for_redeeming = $updated_points_for_redeeming / $points_conversion_value;
            $redeem_button_message_more = get_option('rs_redeeming_button_option_message_checkout');

            $currency_symbol_string_to_replace = RSFunctionForCart::get_woocommerce_formatted_price($points_currency_value);
            $redeem_button_message_replaced_first = str_replace($cuurency_value_string_to_find, $currency_symbol_string_to_replace, $redeem_button_message_more);
            $redeem_button_message_replaced_second = str_replace($currency_symbol_string_to_find, "", $redeem_button_message_replaced_first);
            $redeem_button_message_replaced_third = str_replace($cartpoints_string_to_replace, $points_for_redeeming, $redeem_button_message_replaced_second);
        } else {
            $points_for_redeeming = $current_points_user;
            $redeem_button_message_more = get_option('rs_redeeming_button_option_message_checkout');
            $points_conversion_value = wc_format_decimal(get_option('rs_redeem_point_value'));
            $points_currency_value = $points_for_redeeming / $redeem_conversion;
            $points_currency_amount_to_replace = $points_currency_value * $points_conversion_value;
            $currency_symbol_string_to_replace = RSFunctionForCart::get_woocommerce_formatted_price($points_currency_value);
            $redeem_button_message_replaced_first = str_replace($currency_symbol_string_to_find, "", $redeem_button_message_more);
            $redeem_button_message_replaced_second = str_replace($cuurency_value_string_to_find, $currency_symbol_string_to_replace, $redeem_button_message_replaced_first);
            $redeem_button_message_replaced_third = str_replace($cartpoints_string_to_replace, $points_for_redeeming, $redeem_button_message_replaced_second);
        }
        if ($current_points_user >= get_option("rs_first_time_minimum_user_points")) {
            if ($cart_subtotal_redeem_amount_checkout >= $minimum_cart_total_redeem_checkout) {
                $user_ID = get_current_user_id();
                $getinfousernickname = get_user_by('id', $user_ID);
                $couponcodeuserlogin = $getinfousernickname->user_login;
                $auto_redeem_name = 'auto_redeem_' . strtolower($couponcodeuserlogin);
                $array = $woocommerce->cart->get_applied_coupons();
                foreach ($woocommerce->cart->cart_contents as $item) {
                    $product_id = $item['variation_id'] != 0 ? $item['variation_id'] : $item['product_id'];
                    $type[] = RSFunctionForCart::check_display_price_type($product_id);
                    $enable = RSFunctionForCart::calculate_point_price_for_products($product_id);
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
                                    <input id="rs_apply_coupon_code_field" class="input-text" type="hidden" placeholder="<?php echo $placeholder; ?>" value="<?php echo $points_for_redeeming; ?> " name="rs_apply_coupon_code_field">                                            <input class="button <?php echo get_option('rs_extra_class_name_apply_reward_points'); ?>" type="submit" id='mainsubmi' value="<?php echo get_option('rs_redeem_field_submit_button_caption'); ?>" name="rs_apply_coupon_code">
                                </div>
                            </form>
                            <?php
                        }
                    }
                }
            }
        }
    }

    public static function your_current_points_cart_page() {
        if (get_option('rs_show_hide_message_for_my_rewards') == '1') {
            if (is_user_logged_in()) {
                $user_ID = get_current_user_id();
                $current_user_points = RSPointExpiry::get_sum_of_total_earned_points($user_ID);
                if ($current_user_points > 0) {
                    $userid = get_current_user_id();
                    $banning_type = FPRewardSystem::check_banning_type($userid);
                    if ($banning_type != 'earningonly' && $banning_type != 'both') {
                        ?>
                        <div class="woocommerce-info sumo_reward_points_current_points_message">
                            <?php
                            $user_ID = get_current_user_id();
                            echo do_shortcode(get_option('rs_message_user_points_in_cart'));
                            ?>
                        </div>
                        <?php
                    }
                }
            }
        }
    }

    public static function your_current_points_checkout_page() {
        if (get_option('rs_show_hide_message_for_my_rewards_checkout_page') == '1') {
            if (is_user_logged_in()) {
                $user_ID = get_current_user_id();
                $current_user_points = RSPointExpiry::get_sum_of_total_earned_points($user_ID);
                if ($current_user_points > 0) {
                    $userid = get_current_user_id();
                    $banning_type = FPRewardSystem::check_banning_type($userid);
                    if ($banning_type != 'earningonly' && $banning_type != 'both') {
                        ?>
                        <div class="woocommerce-info">
                            <?php
                            $user_ID = get_current_user_id();
                            echo do_shortcode(get_option('rs_message_user_points_in_checkout'));
                            ?>
                        </div>
                        <?php
                    }
                }
            }
        }
    }

    public static function add_shortcode_for_user_points() {
        if (is_user_logged_in()) {
            $user_ID = get_current_user_id();
            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
            return "<strong>" . round(RSPointExpiry::get_sum_of_total_earned_points($user_ID), $roundofftype) . "</strong>";
        }
    }

    public static function add_shortcode_for_user_points_value() {
        if (is_user_logged_in()) {
            $user_ID = get_current_user_id();
            $current_user_points = RSPointExpiry::get_sum_of_total_earned_points($user_ID);
            $pointconversion = wc_format_decimal(get_option('rs_redeem_point'));
            $pointconversionvalue = wc_format_decimal(get_option('rs_redeem_point_value'));
            $pointswithvalue = $current_user_points / $pointconversion;
            $rewardpoints_amount = $pointswithvalue * $pointconversionvalue;
            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
            return RSFunctionForCart::get_woocommerce_formatted_price(round($rewardpoints_amount, $roundofftype));
        } else {
            $rewardpoints_amount = 0;
            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
            return RSFunctionForCart::get_woocommerce_formatted_price(round($rewardpoints_amount, $roundofftype));
        }
    }

    public static function display_complete_message_cart_page() {
        global $totalrewardpointsnew;
        if (is_user_logged_in()) {
            $checkenableoption = RSFunctionForCart::check_the_applied_coupons();
            if (get_option('rs_show_hide_message_for_total_points') == '1') {
                if ($checkenableoption == false) {
                    if (is_array($totalrewardpointsnew)) {
                        if (array_sum($totalrewardpointsnew) > 0) {
                            $totalrewardpoints = do_shortcode('[totalrewards]');
                            if ($totalrewardpoints > 0) {
                                ?>
                                <div class="woocommerce-info sumo_reward_points_complete_message">
                                    <?php
                                    echo do_shortcode(get_option('rs_message_total_price_in_cart'));
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

    public static function display_complete_message_checkout_page() {
        global $totalrewardpointsnew;
        if (is_user_logged_in()) {
            $checkenableoption = RSFunctionForCart::check_the_applied_coupons();
            if (get_option('rs_show_hide_message_for_total_points_checkout_page') == '1') {
                if ($checkenableoption == false) {
                    if (is_array($totalrewardpointsnew)) {
                        if (array_sum($totalrewardpointsnew) > 0) {
                            $totalrewardpoints = do_shortcode('[totalrewards]');
                            if ($totalrewardpoints > 0) {
                                ?>
                                <div class="woocommerce-info">
                                    <?php
                                    echo do_shortcode(get_option('rs_message_total_price_in_checkout'));
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

    public static function getshortcodetotal_rewards() {
        global $totalrewardpointsnew;
        //var_dump($totalrewardpointsnew);
        if (is_array($totalrewardpointsnew)) {
            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
            return round(array_sum($totalrewardpointsnew), $roundofftype);
        } else {
            return "<strong>  </strong>";
        }
    }

    public static function getvalueshortcodetotal_rewards() {
        $getrstotal = do_shortcode('[totalrewards]');
        $getcals = $getrstotal / wc_format_decimal(get_option('rs_redeem_point'));
        $updatedvalue = $getcals * wc_format_decimal(get_option('rs_redeem_point_value'));
        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
        return RSFunctionForCart::get_woocommerce_formatted_price(round($updatedvalue, $roundofftype));
    }

    public static function add_custom_message_to_payment_gateway_on_checkout() {
        if (get_option('rs_show_hide_message_payment_gateway_reward_points') == '1') {
            if (is_user_logged_in()) {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {

                        jQuery('.subinfogateway').parent().hide();
                        jQuery('#order_review').on('click', '.payment_methods input.input-radio', function () {
                            var orderpaymentgateway = jQuery(this).val();
                            var paymentgatewaytitle = jQuery('.payment_method_' + orderpaymentgateway).find('label').html();
                            var dataparam = ({
                                action: 'rs_order_payment_gateway_reward',
                                getpaymentgatewayid: orderpaymentgateway,
                                getpaymenttitle: paymentgatewaytitle,
                                userid: "<?php echo get_current_user_id(); ?>",
                            });
                            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                    function (response) {
                                        console.log(response);
                                        jQuery('.subinfogateway').parent().show();
                                        jQuery('.rspgpoints').html(response.rewardpoints);
                                        var responsepoints = jQuery('.rspgpoints').html(response.rewardpoints);
                                        if ((response.rewardpoints == null) || (response.rewardpoints == '')) {
                                            jQuery('.rspgpoints').parent().css('display', 'none');
                                        } else {
                                            jQuery('.rspgpoints').parent().css('display', 'inline-block');
                                        }
                                        if (response.title !== null) {

                                            jQuery('.subinfogateway').html(response.title.replace(/\\/g, ''));
                                        }
                                    }, 'json');
                        });
                    });</script>
                <?php
                $getmessage = get_option('rs_message_payment_gateway_reward_points');
                $findarray = array('[paymentgatewaytitle]', '[paymentgatewaypoints]');
                $replacearray = array('<label class="subinfogateway">  </label>', '<span class="rspgpoints"></span>');
                $output = str_replace($findarray, $replacearray, $getmessage);
                ?>

                <div class="woocommerce-info"><?php echo $output; ?></div>


                <?php
            }
        }
    }

    public static function payment_gateway_reward_points_process_ajax_request() {
        if (isset($_POST['getpaymentgatewayid'])) {
            $gatewayid = $_POST['getpaymentgatewayid'];
            $getthevalue = RSMemberFunction::user_role_based_reward_points($_POST['userid'], get_option('rs_reward_payment_gateways_' . $gatewayid));
            $getthetitle = $_POST['getpaymenttitle'];
            echo json_encode(array('rewardpoints' => $getthevalue, 'title' => $getthetitle));
        }
        exit();
    }

    public static function show_message_for_guest_cart_page() {
        global $totalrewardpointsnew;

        if (!is_user_logged_in()) {
            $totalrewardpoints = do_shortcode('[totalrewards]');
            if (get_option('rs_show_hide_message_for_guest') == '1') {
                ?>
                <div class="woocommerce-info"><?php echo do_shortcode(get_option('rs_message_for_guest_in_cart')); ?></div>
                <?php
            }
        }
    }

    public static function show_message_for_guest_checkout_page() {
        if (!is_user_logged_in()) {
            if (get_option('rs_show_hide_message_for_guest_checkout_page') == '1') {
                ?>
                <div class="woocommerce-info"><?php echo do_shortcode(get_option('rs_message_for_guest_in_checkout')); ?></div>
                <?php
            }
        }
    }

    public static function get_my_account_url_link() {
        global $woocommerce;
        $myaccountlink = get_permalink(get_option('woocommerce_myaccount_page_id'));
        $myaccounttitle = get_the_title(get_option('woocommerce_myaccount_page_id'));
        return '<a href=' . $myaccountlink . '>' . $myaccounttitle . '</a>';
    }

}

new RSFunctionForCheckout();
