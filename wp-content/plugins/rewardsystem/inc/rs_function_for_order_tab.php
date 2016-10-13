<?php

class RSFunctionForOrder {

    public function __construct() {

        add_action('admin_head', array($this, 'rs_show_hide_in_order'));

        add_action('woocommerce_order_items_table', array($this, 'display_total_redem_points_order'));

        add_action('woocommerce_email_after_order_table', array($this, 'get_the_total_earned_points_for_order'));

        add_action('woocommerce_email_after_order_table', array($this, 'get_the_total_redeem_points_for_order'));

        add_action('add_meta_boxes', array($this, 'add_meta_box_for_earned'));

        add_filter('woocommerce_get_formatted_order_total', array($this, 'display_total_point_price'), 10, 2);

        add_filter('woocommerce_order_formatted_line_subtotal', array($this, 'display_line_total'), 8, 3);

        add_filter('woocommerce_order_subtotal_to_display', array($this, 'display_line_total1'), 8, 3);

        add_filter('woocommerce_get_order_item_totals', array($this, 'test'), 8, 2);
        add_filter('wcs_renewal_order_meta_query', array($this, 'renewal_order'), 8, 3);
    }

    public static function renewal_order($meta, $to_order, $from_order) {
        $exculde_referrer_user_name = $meta;
        $exculde_referrer_user_name .= " AND `meta_key` NOT LIKE '_referrer_name'";
        return $exculde_referrer_user_name;
    }

    public static function test($total_rows, $orderid) {
        $order = new WC_Order($orderid);
        $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($orderid->id, $order->user_id);
        $autoredeem = RSFunctionToApplyCoupon::update_auto_redeem_points($orderid->id, $order->user_id);

        if ($redeempoints > 0 || $autoredeem > 0) {
            $tax_display = '';
            $total_rows['discount'] = array(
                'label' => __(get_option('rs_coupon_label_message'), 'woocommerce'),
                'value' => '-' . $orderid->get_discount_to_display($tax_display)
            );
            return $total_rows;
        } else {
            return $total_rows;
        }
    }

    public function display_line_total1($line_total1, $id, $item) {
        $rewardgateway = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($item->id, '_payment_method');
        $array = array();
        $linetotal = array();
        $updatedvalue = array();
        if ($rewardgateway == 'reward_gateway') {
            $order = new WC_Order($item);
            foreach ($order->get_items()as $item) {
                $productid = $item['variation_id'] != 0 ? $item['variation_id'] : $item['product_id'];
                if (RSFunctionForCart::check_display_price_type($productid) == '2') {
                    if (get_option('rs_enable_disable_point_priceing') == '1') {
                        $label = get_option('rs_label_for_point_value');
                        $replace = str_replace("/", "", $label);
                        $enable = RSFunctionForCart::calculate_point_price_for_products($productid);
                        if ($enable[$productid] != '') {
                            $cart_object = $enable[$productid] * $item['qty'];
                            $array[] = $cart_object;
                        }
                    }
                } else {
                    $label = get_option('rs_label_for_point_value');
                    $replace = str_replace("/", "", $label);
                    $enable = RSFunctionForCart::calculate_point_price_for_products($productid);
                    if ($enable[$productid] != '') {
                        $cart_object = $enable[$productid] * $item['qty'];
                        $array[] = $cart_object;
                    } else {

                        $linetotal = $item['line_subtotal'];

                        $newvalue = $linetotal / wc_format_decimal(get_option('rs_redeem_point_value'));
                        $updatedvalue[] = $newvalue * wc_format_decimal(get_option('rs_redeem_point'));
                    }
                }
            }

            $amount = array_sum($array) + array_sum($updatedvalue);
            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
            $amount = round($amount, $roundofftype);
            return $replace . $amount;
        } else {
            return $line_total1;
        }
    }

    public function display_line_total($line_total1, $id, $item) {

        $rewardgateway = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($item->id, '_payment_method');

        if ($rewardgateway == 'reward_gateway') {

            $labelpoint = get_option('rs_label_for_point_value');


            $product_id = $id['variation_id'] != 0 ? $id['variation_id'] : $id['product_id'];

            $label = get_option('rs_label_for_point_value');
            $replace = str_replace("/", "", $label);
            $enable = RSFunctionForCart::calculate_point_price_for_products($product_id);
            if ($enable[$product_id] != '') {
                $cart_object = $enable[$product_id] * $id['qty'];
                $array = $cart_object;
                $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                $array = round($array, $roundofftype);
                return $replace . $array;
            } else {
                $linetotal = $id['line_subtotal'];
                $newvalue = $linetotal / wc_format_decimal(get_option('rs_redeem_point_value'));
                $updatedvalue = $newvalue * wc_format_decimal(get_option('rs_redeem_point'));
                $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                $updatedvalue = round($updatedvalue, $roundofftype);
                return $replace . $updatedvalue;
            }
        } else {
            return $line_total1;
        }
    }

    public function display_total_point_price($order1, $orderids) {
        $rewardgateway = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($orderids->id, '_payment_method');

        if ($rewardgateway == 'reward_gateway') {
            $updatedvalue = array();
            $replace = '';
            global $woocommerce;
            $shipping_total = '';
            $updatedvalue1 = '';
            $couponamount = '';
            $couponamount1 = array();
            $updatedvalue = array();
            $array = array();
            $order = new WC_Order($orderids);
            $rewardpointscoupons = $order->get_items(array('coupon'));

            foreach ($rewardpointscoupons as $coupon) {
                $couponamount1[] = $coupon['discount_amount'];
            }

            $couponamount = array_sum($couponamount1);

            foreach ($order->get_items()as $item) {

                if (get_option('woocommerce_prices_include_tax') === 'yes') {
                    $shipping_total = $order->get_total_shipping();
                    $tax_total = 0;
                } else {
                    $shipping_total = $order->get_total_shipping();
                    $tax_total = $order->get_total_tax();
                }
                $shipping_total = $shipping_total + $tax_total;
                $newvalue1 = $shipping_total / wc_format_decimal(get_option('rs_redeem_point_value'));
                $updatedvalue1 = $newvalue1 * wc_format_decimal(get_option('rs_redeem_point'));
                $product_id = $item['variation_id'] != 0 ? $item['variation_id'] : $item['product_id'];
                $label = get_option('rs_label_for_point_value');
                $replace = str_replace("/", "", $label);
                $enable = RSFunctionForCart::calculate_point_price_for_products($product_id);
                if ($enable[$product_id] != '') {
                    $cart_object = $enable[$product_id] * $item['qty'];
                    $array[] = $cart_object;
                } else {

                    $linetotal = $item['line_subtotal'];
                    $newvalue = $linetotal / wc_format_decimal(get_option('rs_redeem_point_value'));
                    $updatedvalue[] = $newvalue * wc_format_decimal(get_option('rs_redeem_point'));
                }
            }


            $amount = array_sum($array) + array_sum($updatedvalue) - $couponamount;

            $amount = $amount + $updatedvalue1;
            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
            $amount = round($amount, $roundofftype);
            return $replace . $amount;
        } else {

            return $order1;
        }
    }

    public static function rs_show_hide_in_order() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {

                if (jQuery('#rs_enable_msg_for_earned_points').is(":checked")) {
                    jQuery('#rs_msg_for_earned_points').parent().parent().show();
                } else {
                    jQuery('#rs_msg_for_earned_points').parent().parent().hide();
                }

                jQuery('#rs_enable_msg_for_earned_points').change(function () {
                    if (jQuery('#rs_enable_msg_for_earned_points').is(":checked")) {
                        jQuery('#rs_msg_for_earned_points').parent().parent().show();
                    } else {
                        jQuery('#rs_msg_for_earned_points').parent().parent().hide();
                    }
                });

                if (jQuery('#rs_enable_msg_for_redeem_points').is(":checked")) {
                    jQuery('#rs_msg_for_redeem_points').parent().parent().show();
                } else {
                    jQuery('#rs_msg_for_redeem_points').parent().parent().hide();
                }

                jQuery('#rs_enable_msg_for_redeem_points').change(function () {
                    if (jQuery('#rs_enable_msg_for_redeem_points').is(":checked")) {
                        jQuery('#rs_msg_for_redeem_points').parent().parent().show();
                    } else {
                        jQuery('#rs_msg_for_redeem_points').parent().parent().hide();
                    }
                });
            });
        </script>
        <?php
    }

    public static function display_total_redem_points_order($order) {
        $totalpoints = array(0);

        if (get_option('rs_show_hide_total_points_order_field') == '1') {
            $totalpoints = get_post_meta($order->id, 'points_for_current_order', true);
            $redeem_check = get_post_meta($order->id, 'rs_check_enable_option_for_redeeming');
            if ($redeem_check == 'no') {
                if ($totalpoints != '') {
                    $total_points = array_sum($totalpoints);
                    if ($total_points != 0) {
                        ?>
                        <tfoot>
                            <tr class="cart-total">
                                <th><?php echo get_option('rs_total_earned_point_caption_checkout'); ?></th>
                                <td><?php echo $total_points; ?></td>
                            </tr>
                        </tfoot>


                        <?php
                    }
                }
            }
        }
    }

    /* To get the total earned for order */

    public static function get_the_total_earned_points_for_order($order) {
        $status = get_option('rs_order_status_control');
        global $wpdb;
        $table_name = $wpdb->prefix . 'rsrecordpoints';
        $orderid = $order->id;
        $gettotalearnpoints = $wpdb->get_results("SELECT earnedpoints FROM $table_name WHERE orderid = $orderid", ARRAY_A);
        $orderstatus = $order->post_status;
        if (is_array($status)) {
            foreach ($status as $statuses) {
                $statusstr = $statuses;
            }
        }
        $replacestatus = str_replace('wc-completed', $statusstr, $orderstatus);
        if (get_option('rs_enable_msg_for_earned_points') == 'yes') {
            if (in_array($replacestatus, $status)) {
                $totalearnedvalue = "";
                $earned_total = $gettotalearnpoints;
                if (is_array($earned_total)) {
                    foreach ($earned_total as $key => $value) {
                        $totalearnedvalue += $value['earnedpoints'];
                    }
                    $msgforearnedpoints = get_option('rs_msg_for_earned_points');
                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                    $replacemsgforearnedpoints = str_replace('[earnedpoints]', $totalearnedvalue != "" ? round($totalearnedvalue, $roundofftype) : "0", $msgforearnedpoints);
                    echo '<br><br>' . '<b>' . $replacemsgforearnedpoints . '<b>' . '<br><br>';
                }
            }
        }
    }

    /* To get the total redeem for order */

    public static function get_the_total_redeem_points_for_order($order) {
        $status = get_option('rs_order_status_control');
        global $wpdb;
        $table_name = $wpdb->prefix . 'rsrecordpoints';
        $orderid = $order->id;
        $gettotalredeempoints = $wpdb->get_results("SELECT redeempoints FROM $table_name WHERE orderid=$orderid", ARRAY_A);
        $orderstatus = $order->post_status;
        if (is_array($status)) {
            foreach ($status as $statuses) {
                $statusstr = $statuses;
            }
        }
        $replacestatus = str_replace('wc-completed', $statusstr, $orderstatus);
        if (get_option('rs_enable_msg_for_redeem_points') == 'yes') {
            if (in_array($replacestatus, $status)) {
                $totalredeemvalue = "";
                $redeem_total = $gettotalredeempoints;
                if (is_array($redeem_total)) {
                    foreach ($redeem_total as $key => $value) {
                        $totalredeemvalue+=$value['redeempoints'];
                    }
                    $msgforredeempoints = get_option('rs_msg_for_redeem_points');
                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                    $replacemsgforredeempoints = str_replace('[redeempoints]', $totalredeemvalue != "" ? round($totalredeemvalue, $roundofftype) : "0", $msgforredeempoints);
                    echo '<b>' . $replacemsgforredeempoints . '</b>';
                }
            }
        }
    }

    public static function add_meta_box_for_earned() {
        add_meta_box('order_earned_points', 'Earned Point and Redeem Points For Current Order', array('RSFunctionForOrder', 'add_meta_box_to_earned_and_redeem_points'), 'shop_order', 'normal', 'low');
    }

    public static function add_meta_box_to_earned_and_redeem_points($order) {

        $order = $_GET['post'];
        global $wpdb;
        $earned_totals = array();
        $overall_earned_totals = array();
        $overall_redeem_totals = array();
        $redeem_totals = array();
        $revised_earned_totals = array();
        $revised_redeem_totals = array();
        $totalearnedvalue = "";
        $totalredeemvalue = '';
        $table_name = $wpdb->prefix . 'rsrecordpoints';
        $orderid = $order;
        $order_obj = new WC_Order($orderid);
        $order_status = str_replace('wc-', '', $order_obj->post_status);
        $getoverallearnpoints = $wpdb->get_results("SELECT earnedpoints FROM $table_name WHERE orderid = $orderid and checkpoints != 'RVPFRP'and  checkpoints != 'RVPFRPG'", ARRAY_A);
        foreach ($getoverallearnpoints as $getoverallearnpointss) {
            $overall_earned_totals[] = $getoverallearnpointss['earnedpoints'];
        }
        $getoverallredeempoints = $wpdb->get_results("SELECT redeempoints FROM $table_name WHERE orderid = $orderid and checkpoints != 'RVPFPPRP'", ARRAY_A);
        foreach ($getoverallredeempoints as $getoverallredeempointss) {
            $overall_redeem_totals[] = $getoverallredeempointss['redeempoints'];
        }
        $gettotalearnpoints = $wpdb->get_results("SELECT earnedpoints FROM $table_name WHERE checkpoints = 'PPRP' and orderid = $orderid", ARRAY_A);
        foreach ($gettotalearnpoints as $gettotalearnpointss) {
            $earned_totals[] = $gettotalearnpointss['earnedpoints'];
        }
        $getrevisedearnedpoint = $wpdb->get_results("SELECT redeempoints FROM $table_name WHERE checkpoints = 'RVPFPPRP' and orderid = $orderid", ARRAY_A);
        foreach ($getrevisedearnedpoint as $getrevisedearnedpoints) {
            $revised_earned_totals[] = $getrevisedearnedpoints['redeempoints'];
        }
        $gettotalredeempoints = $wpdb->get_results("SELECT redeempoints FROM $table_name WHERE checkpoints = 'RP' and checkpoints ='RPFGW' and orderid = $orderid", ARRAY_A);
        foreach ($gettotalredeempoints as $gettotalredeempointss) {
            $redeem_totals[] = $gettotalredeempointss['redeempoints'];
        }
        $getrevisedredeempoints = $wpdb->get_results("SELECT earnedpoints FROM $table_name WHERE orderid = $orderid and checkpoints != 'PPRP'  ", ARRAY_A);
        foreach ($getrevisedredeempoints as $getrevisedredeempointss) {
            $revised_redeem_totals[] = $getrevisedredeempointss['earnedpoints'];
        }
        $orderstatuslistforredeem = get_option('rs_order_status_control_redeem');
        if (in_array($order_status, $orderstatuslistforredeem)) {
            RSPointExpiry::update_redeem_point_for_user($orderid);
        }
        if (get_option('rs_enable_msg_for_earned_points') == 'yes') {
            if (get_option('rs_enable_msg_for_redeem_points') == 'yes') {
                $totalearnedvalue = array_sum($overall_earned_totals) - array_sum($revised_earned_totals);
                $totalredeemvalue = array_sum($overall_redeem_totals) - array_sum($revised_redeem_totals);

                $msgforearnedpoints = get_option('rs_msg_for_earned_points');
                $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                $replacemsgforearnedpoints = str_replace('[earnedpoints]', $totalearnedvalue != "" ? round($totalearnedvalue, $roundofftype) : "0", $msgforearnedpoints);

                $msgforredeempoints = get_option('rs_msg_for_redeem_points');
                $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                $replacemsgforredeempoints = str_replace('[redeempoints]', $totalredeemvalue != "" ? round($totalredeemvalue, $roundofftype) : "0", $msgforredeempoints);
            } else {
                $totalearnedvalue = array_sum($overall_earned_totals) - array_sum($revised_earned_totals);

                $msgforearnedpoints = get_option('rs_msg_for_earned_points');
                $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                $replacemsgforearnedpoints = str_replace('[earnedpoints]', $totalearnedvalue != "" ? round($totalearnedvalue, $roundofftype) : "0", $msgforearnedpoints);

                $msgforredeempoints = get_option('rs_msg_for_redeem_points');
                $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                $replacemsgforredeempoints = str_replace('[redeempoints]', $totalredeemvalue != "" ? round($totalredeemvalue, $roundofftype) : "0", $msgforredeempoints);
            }
        } else {

            $msgforearnedpoints = get_option('rs_msg_for_earned_points');
            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
            $replacemsgforearnedpoints = str_replace('[earnedpoints]', $totalearnedvalue != "" ? round($totalearnedvalue, $roundofftype) : "0", $msgforearnedpoints);

            if (get_option('rs_enable_msg_for_redeem_points') == 'yes') {

                $totalredeemvalue = array_sum($overall_redeem_totals) - array_sum($revised_redeem_totals);

                $msgforredeempoints = get_option('rs_msg_for_redeem_points');
                $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                $replacemsgforredeempoints = str_replace('[redeempoints]', $totalredeemvalue != "" ? round($totalredeemvalue, $roundofftype) : "0", $msgforredeempoints);
            } else {
                $msgforredeempoints = get_option('rs_msg_for_redeem_points');
                $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                $replacemsgforredeempoints = str_replace('[redeempoints]', $totalredeemvalue != "" ? round($totalredeemvalue, $roundofftype) : "0", $msgforredeempoints);
            }
        }

        if (get_option('rs_enable_msg_for_earned_points') == 'yes') {
            if (get_option('rs_enable_msg_for_redeem_points') == 'yes') {
                ?>
                <table width="100%" style=" border-radius: 10px; border-style: solid; border-color: #dfdfdf;">
                    <tr><td style="text-align:center; background-color:#F1F1F1"><h3>Earned Points</h3></td><td style="text-align:center;background-color:#F1F1F1"><h3>Redeem Points</h3></td></tr>
                    <tr><td style="text-align:center"><?php echo $replacemsgforearnedpoints; ?></td><td style="text-align:center"><?php echo $replacemsgforredeempoints; ?></td></tr>
                </table>

                <?php
            } else {
                ?>
                <table width="100%" style=" border-radius: 10px; border-style: solid; border-color: #dfdfdf;">
                    <tr><td style="text-align:center; background-color:#F1F1F1"><h3>Earned Points</h3></td></tr>
                    <tr><td style="text-align:center"><?php echo $replacemsgforearnedpoints; ?></td></tr>
                </table>

                <?php
            }
        } else {
            if (get_option('rs_enable_msg_for_redeem_points') == 'yes') {
                ?>
                <table width="100%" style=" border-radius: 10px; border-style: solid; border-color: #dfdfdf;">
                    <tr><td style="text-align:center;background-color:#F1F1F1"><h3>Redeem Points</h3></td></tr>
                    <tr><td style="text-align:center"><?php echo $replacemsgforredeempoints; ?></td></tr>
                </table>

                <?php
            }
        }
    }

}

new RSFunctionForOrder();
