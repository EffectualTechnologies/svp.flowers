<?php

class RSFunctionForFormForCashBack {

    public function __construct() {

        add_action('admin_head', array($this, 'rs_validation_of_input_field_in_form_for_cash_back'));

        add_shortcode('rsencashform', array($this, 'encashing_front_end_form'));

        add_action('wp_ajax_rs_encash_form_value', array($this, 'process_encashing_points_to_users'));
    }

    public static function rs_validation_of_input_field_in_form_for_cash_back() {
        ?>

        <script type="text/javascript">
            jQuery(function () {
                jQuery('body').on('blur', '#rs_minimum_points_encashing_request[type=text],\n\
                                           #rs_maximum_points_encashing_request[type=text]', function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });

                    return this;
                });

                jQuery('body').on('keyup change', '#rs_minimum_points_encashing_request[type=text],\n\
                                           #rs_maximum_points_encashing_request[type=text]', function () {
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

    public static function encashing_front_end_form() {
        if (get_option('rs_enable_disable_encashing') == '1') {
            if (is_user_logged_in()) {
                $user_ID = get_current_user_id();
                if (RSPointExpiry::get_sum_of_total_earned_points($user_ID) > 0) {
                    $userid = get_current_user_id();
                    $banning_type = FPRewardSystem::check_banning_type($userid);
                    if ($banning_type != 'earningonly' && $banning_type != 'both') {
                        ob_start();
                        $encash_form_style_option = get_option('rs_encash_form_inbuilt_design');
                        ?>

                        <style type="text/css">
                        <?php
                        if ($encash_form_style_option == '1') {
                            echo get_option('rs_encash_form_default_css');
                        } else {
                            echo get_option('rs_encash_form_custom_css');
                        }
                        ?>

                        </style>
                        <?php
                        $rs_minimum_points_for_encash = get_option('rs_minimum_points_encashing_request');
                        $rs_maximum_points_for_encash = get_option('rs_maximum_points_encashing_request');
                        $minimum_encash_to_find = "[minimum_encash_points]";
                        $maximum_encash_to_find = "[maximum_encash_points]";
                        $rs_error_mesage_minimum_encash = get_option('rs_error_message_points_lesser_than_minimum_points');

                        $rs_current_points_less_than_minimum_points = get_option('rs_error_message_currentpoints_less_than_minimum_points');
                        $rs_current_points_less_than_minimum_points_replaced = str_replace($minimum_encash_to_find, $rs_minimum_points_for_encash, $rs_current_points_less_than_minimum_points);
                        $user_ID = get_current_user_id();
                        $currentuserpoints = RSPointExpiry::get_sum_of_total_earned_points($user_ID);
                         $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                         $currentuserpoints = round($currentuserpoints, $roundofftype);
                        $rs_error_mesage_minimum_encash_replaced = str_replace($minimum_encash_to_find, $rs_minimum_points_for_encash != '' ? $rs_minimum_points_for_encash : '0', $rs_error_mesage_minimum_encash);
                        $rs_error_mesage_min_max_encash_replaced = str_replace($maximum_encash_to_find, $rs_maximum_points_for_encash != '' ? $rs_maximum_points_for_encash : $currentuserpoints, $rs_error_mesage_minimum_encash_replaced);

                        echo '<form id="encashing_form" method="post" enctype="multipart/form-data">';
                        echo '<div class ="rs_encash_points_value"><p><label><b>' . get_option("rs_encashing_points_label") . '</b></label></p><p><input type = "text" id = "rs_encash_points_value" name = "rs_encash_points_value" value=""></p></div>';
                        echo '<div class = "error" for = "rs_encash_points_value" id ="points_empty_error">' . addslashes(get_option("rs_error_message_points_empty_encash")) . '</div>';
                        echo '<div class = "error" for = "rs_encash_points_value" id ="points_number_error">' . addslashes(get_option("rs_error_message_points_number_val_encash")) . '</div>';
                        echo '<div class = "error" for = "rs_encash_points_value" id ="points_greater_than_earnpoints_error">' . addslashes(get_option("rs_error_message_points_greater_than_earnpoints")) . '</div>';
                        echo '<div class = "error" for = "rs_encash_points_value" id ="currentpoints_lesser_than_minimumpoints_error">' . addslashes($rs_current_points_less_than_minimum_points_replaced) . '</div>';
                        echo '<div class = "error" for = "rs_encash_points_value" id ="points_lesser_than_minpoints_error">' . addslashes($rs_error_mesage_min_max_encash_replaced) . '</div>';
                        echo '<div class ="rs_encash_points_reason"><p><label><b>' . addslashes(get_option("rs_encashing_reason_label")) . '</b></label></p><p><textarea name ="rs_encash_points_reason" id="rs_encash_points_reason" rows= "3" cols= "50"></textarea></p></div>';
                        echo '<div class = "error" for = "rs_encash_points_reason" id ="reason_empty_error">' . addslashes(get_option("rs_error_message_reason_encash_empty")) . '</div>';
                        echo '<div class ="rs_encash_payment_method"><p><label><b>' . addslashes(get_option("rs_encashing_payment_method_label")) . '</b></label></p><p><select id= "rs_encash_payment_method"><option value="encash_through_paypal_method">PayPal</option><option value="encash_through_custom_payment">Custom Payment</option></select></p></div>';
                        echo '<div class ="rs_encash_paypal_address"><p><label><b>' . addslashes(get_option("rs_encashing_payment_paypal_label")) . '</b></label></p><p><input type = "text" id = "rs_encash_paypal_address" name = "rs_encash_paypal_address" value=""></p></div>';
                        echo '<div class = "error" for = "rs_encash_paypal_address" id ="paypal_email_empty_error">' . addslashes(get_option("rs_error_message_paypal_email_empty")) . '</div>';
                        echo '<div class = "error" for = "rs_encash_paypal_address" id ="paypal_email_format_error">' . addslashes(get_option("rs_error_message_paypal_email_wrong")) . '</div>';
                        echo '<div class ="rs_encash_custom_payment_option_value"><p><label><b>' . addslashes(get_option("rs_encashing_payment_custom_label")) . '</b></label></p><p><textarea name ="rs_encash_custom_payment_option_value" id="rs_encash_custom_payment_option_value" rows= "3" cols= "50"></textarea></p></div>';
                        echo '<div class = "error" for = "rs_encash_custom_payment_option_value" id ="paypal_custom_option_empty_error">' . addslashes(get_option("rs_error_custom_payment_field_empty")) . '</div>';
                        echo '<div class ="rs_encash_submit"><input type = "submit" name= "rs_encash_submit_button" value="' . addslashes(get_option("rs_encashing_submit_button_label")) . '" id="rs_encash_submit_button"></div>';
                        echo '<div class = "success_info" for = "rs_encash_submit_button" id ="encash_form_success_info"><b>' . addslashes(get_option("rs_message_encashing_request_submitted")) . '</b></div>';
                        echo '</form>';
                        ?>
                        <script type ="text/javascript">
                            jQuery(document).ready(function () {
                                var encash_current_user_points = "<?php $user_ID = get_current_user_id();
                        echo $currentuserpoints = RSPointExpiry::get_sum_of_total_earned_points($user_ID); ?>";
                                var minimum_points_to_encash = "<?php echo $rs_minimum_points_for_encash != '' ? $rs_minimum_points_for_encash : '0'; ?>";
                                var maximum_points_to_encash = "<?php echo $rs_maximum_points_for_encash != '' ? $rs_maximum_points_for_encash : $currentuserpoints; ?>";
                                jQuery(".error").hide();
                                jQuery(".success_info").hide();
                                var name = "<?php echo get_option('rs_select_payment_method'); ?>";
                                if (name == '1') {
                                    jQuery(".rs_encash_payment_method").hide();
                                    jQuery(".rs_encash_custom_payment_option_value").hide();

                                }
                                if (name == '2') {
                                    jQuery(".rs_encash_paypal_address").hide();
                                    jQuery(".rs_encash_payment_method").hide();
                                }
                                //jQuery(".rs_encash_custom_payment_option_value").hide();
                                if (name == '3') {
                                    jQuery(".rs_encash_custom_payment_option_value").hide();
                                    jQuery(".rs_encash_payment_method").change(function () {
                                        jQuery(".rs_encash_paypal_address").toggle();
                                        jQuery(".rs_encash_custom_payment_option_value").toggle();
                                        jQuery("#paypal_email_empty_error").hide();
                                        jQuery("#paypal_custom_option_empty_error").hide();
                                    });
                                }
                                jQuery("#rs_encash_submit_button").click(function () {
                                    var encash_points = jQuery("#rs_encash_points_value").val();
                                    var encash_points_validated = /^[0-9\b]+$/.test(encash_points);
                                    if (encash_points == "") {
                                        jQuery("#points_empty_error").fadeIn().delay(5000).fadeOut();
                                        return false;
                                    } else {

                                        jQuery("#points_empty_error").hide();
                                        if (encash_points_validated == false) {
                                            jQuery("#points_number_error").fadeIn().delay(5000).fadeOut();
                                            return false;
                                        } else {

                                            jQuery("#points_number_error").hide();

                                            if (Number(encash_points) > Number(encash_current_user_points)) {
                                                jQuery("#points_greater_than_earnpoints_error").fadeIn().delay(5000).fadeOut();
                                                return false;
                                            } else {
                                                if ((Number(encash_points) >= Number(minimum_points_to_encash)) && (Number(encash_points) <= Number(maximum_points_to_encash))) {
                                                    jQuery("#points_greater_than_earnpoints_error").hide();
                                                    jQuery("#currentpoints_lesser_than_minimumpoints_error").hide();
                                                    jQuery("#points_lesser_than_minpoints_error").hide();
                                                    jQuery("#rs_error_message_points_lesser_than_minimum_points").hide();
                                                    jQuery("#points_greater_than_maxpoints_error").hide();
                                                    var points_value = <?php echo get_option('rs_redeem_point_for_cash_back'); ?>;
                                                    var amount_value = <?php echo get_option('rs_redeem_point_value_for_cash_back'); ?>;
                                                    var conversion_step1 = encash_points / points_value;
                                                    var currency_converted_value = conversion_step1 * amount_value;
                                                } else {
                                                    jQuery("#points_lesser_than_minpoints_error").fadeIn().delay(5000).fadeOut();
                                                    return false;
                                                }
                                            }
                                        }
                                    }
                                    var reason_to_encash = jQuery("#rs_encash_points_reason").val();
                                    if (reason_to_encash == "") {
                                        jQuery("#reason_empty_error").fadeIn().delay(5000).fadeOut();
                                        return false;
                                    } else {
                                        jQuery("#reason_empty_error").hide();
                                    }

                                    if (name === '2') {
                                        var encash_selected_option = 'encash_through_custom_payment';
                                    } else {
                                        var encash_selected_option = jQuery("#rs_encash_payment_method").val();
                                    }
                                    if (encash_selected_option == "encash_through_paypal_method") {
                                        if (name == '1' || name == '3') {
                                            var encash_paypal_email = jQuery("#rs_encash_paypal_address").val();
                                            var encash_paypal_email_validated = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/.test(encash_paypal_email);
                                            if (encash_paypal_email == "") {
                                                jQuery("#paypal_email_empty_error").fadeIn().delay(5000).fadeOut();
                                                return false;
                                            } else {
                                                jQuery("#paypal_email_empty_error").hide();
                                                if (encash_paypal_email_validated == false) {
                                                    jQuery("#paypal_email_format_error").fadeIn().delay(5000).fadeOut();
                                                    return false;
                                                } else {
                                                    jQuery("#paypal_email_format_error").hide();
                                                }
                                            }
                                        }
                                    } else {
                                        var encash_custom_option = jQuery("#rs_encash_custom_payment_option_value").val();
                                        if (name == '2' || name == '3') {
                                            if (encash_custom_option == "") {
                                                jQuery("#paypal_custom_option_empty_error").fadeIn().delay(5000).fadeOut();
                                                return false;
                                            } else {
                                                jQuery("#paypal_custom_option_empty_error").hide();
                                            }
                                        }
                                    }
                                    jQuery(".success_info").show();
                                    jQuery(".success_info").fadeOut(3000);
                                    jQuery("#encashing_form")[0].reset();
                                    jQuery(".rs_encash_custom_payment_option_value").hide();
                                    if (name == '1') {
                                        jQuery(".rs_encash_paypal_address").show();
                                    }
                                    if (name == '2') {
                                        jQuery(".rs_encash_custom_payment_option_value").show();
                                    }
                                    if (name == '3') {
                                        jQuery(".rs_encash_paypal_address").show();
                                    }

                                    var encash_request_user_id = <?php echo get_current_user_id(); ?>;
                        <?php
                        $user_details = get_user_by('id', get_current_user_id());
                        ?>
                                    var encash_request_user_name = "<?php echo $user_details->user_login; ?>";
                                    var encash_default_status = "Due";

                                    var encash_form_params = ({
                                        action: "rs_encash_form_value",
                                        points_to_encash: encash_points,
                                        reason_to_encash: reason_to_encash,
                                        payment_method: encash_selected_option,
                                        paypal_email_id: encash_paypal_email,
                                        custom_payment_details: encash_custom_option,
                                        userid_of_encash_request: encash_request_user_id,
                                        username_of_encash_request: encash_request_user_name,
                                        encasher_current_points: encash_current_user_points,
                                        converted_value_of_points: currency_converted_value,
                                        encash_default_status: encash_default_status,
                                    });
                                    jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", encash_form_params, function (response) {
                                        console.log('Got this from the server: ' + response);
                                    });
                                    return false;
                                });
                            });
                        </script>
                        <?php
                        $getcontent = ob_get_clean();
                        return $getcontent;
                    } else {
                        echo get_option("rs_message_for_banned_users_encashing");
                    }
                } else {
                    echo get_option("rs_message_users_nopoints_encashing");
                }
            } else {
                ?>
                <p><?php ob_start(); ?> <a href="<?php echo wp_login_url(); ?>" title="__('Login', 'rewardsystem')"><?php echo get_option("rs_encashing_login_link_label"); ?></a>
                    <?php
                    $message_for_guest = get_option("rs_message_for_guest_encashing");
                    $guest_encash_string_to_find = "[rssitelogin]";
                    $guest_encash_string_to_replace = ob_get_clean();
                    $guest_encash_replaced_content = str_replace($guest_encash_string_to_find, $guest_encash_string_to_replace, $message_for_guest);
                    echo $guest_encash_replaced_content;
                }
            }
        }

        public static function process_encashing_points_to_users() {
            global $wpdb;

            if (isset($_POST['points_to_encash']) && isset($_POST['reason_to_encash']) && isset($_POST['payment_method']) && isset($_POST['converted_value_of_points']) && isset($_POST['username_of_encash_request']) && isset($_POST['encash_default_status'])) {
                $custom_option_details_for_encashing = '';
                $encasher_userid = $_POST['userid_of_encash_request'];
                $encasher_username = $_POST['username_of_encash_request'];
                $points_to_be_encashed = $_POST['points_to_encash'];
                $converted_value_of_encash_points = $_POST['converted_value_of_points'];
                $current_points_for_user = $_POST['encasher_current_points'];
                $reason_for_encashing = $_POST['reason_to_encash'];
                $payment_method_for_encashing = $_POST['payment_method'];
                $paypal_email_for_encashing = $_POST['paypal_email_id'];
                if (isset($_POST['custom_payment_details'])) {
                    $custom_option_details_for_encashing = $_POST['custom_payment_details'];
                }
                $table_name = $wpdb->prefix . "sumo_reward_encashing_submitted_data";
                $user_id = get_current_user_id();
                $noofdays = get_option('rs_point_to_be_expire');

                if (($noofdays != '0') && ($noofdays != '')) {
                    $date = time() + ($noofdays * 24 * 60 * 60);
                } else {
                    $date = '999999999999';
                }
                $default_status_of_encash_request = $_POST['encash_default_status'];
                $wpdb->insert($table_name, array('userid' => $encasher_userid, 'userloginname' => $encasher_username, 'pointstoencash' => $points_to_be_encashed, 'encashercurrentpoints' => $current_points_for_user, 'reasonforencash' => $reason_for_encashing, 'encashpaymentmethod' => $payment_method_for_encashing, 'paypalemailid' => $paypal_email_for_encashing, 'otherpaymentdetails' => $custom_option_details_for_encashing, 'status' => $default_status_of_encash_request, 'pointsconvertedvalue' => $converted_value_of_encash_points, 'date' => date('Y-m-d H:i:s')));
                $redeempoints = RSPointExpiry::perform_calculation_with_expiry($points_to_be_encashed, $user_id);
                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($user_id);
                RSPointExpiry::record_the_points($user_id, '0', $points_to_be_encashed, $date, 'CBRP', '0', $equredeemamt, '0', '0', '0', '0', '', $totalpoints, '', '0');
            }
            exit();
        }

    }

    new RSFunctionForFormForCashBack();
    