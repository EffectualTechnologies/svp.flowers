<?php

class RSFunctionofGeneralTab {

    public function __construct() {



        add_action('admin_head', array($this, 'rs_jquery_function_for_general_tab'));

        add_action('admin_head', array($this, 'rs_chosen_for_general_tab'));

        add_action('woocommerce_admin_field_rs_select_user_to_restrict_ban', array($this, 'rs_select_user_to_ban'));

        add_action('woocommerce_update_options_rewardsystem_general', array($this, 'save_data_for_rs_select_user_to_ban'));

        add_action('woocommerce_admin_field_uploader', array($this, 'rs_add_upload_your_gift_voucher'));

        add_action('admin_enqueue_scripts', array($this, 'wp_enqueqe_media'));

        add_action('woocommerce_admin_field_earning_conversion', array($this, 'reward_system_earning_points_conversion'));

        add_action('woocommerce_update_options_rewardsystem_general', array($this, 'save_reward_system_earning_point_conversion'));

        add_action('woocommerce_admin_field_redeeming_conversion', array($this, 'reward_system_redeeming_points_conversion'));

        add_action('woocommerce_admin_field_redeeming_conversion_for_cash_back', array($this, 'reward_system_redeeming_points_conversion_for_cash_back'));

        add_action('woocommerce_update_options_rewardsystem_general', array($this, 'save_reward_system_redeeming_point_conversion'));

        add_action('woocommerce_update_options_rewardsystem_general', array($this, 'save_reward_system_redeeming_point_conversion_for_cash_back'));

        add_action('admin_head', array($this, 'rs_validation_for_input_field_in_general'));

        add_action('admin_head', array($this, 'get_woocommerce_upload_field'));

        add_action('woocommerce_admin_field_rs_refresh_button', array($this, 'refresh_button_for_expired'));

        add_action('admin_head', array($this, 'rs_send_ajax_to_refresh_expired_points'));

        add_action('wp_ajax_nopriv_rsrefreshexpiredpoints', array($this, 'rs_process_ajax_to_get_all_user_id'));

        add_action('wp_ajax_rsrefreshexpiredpoints', array($this, 'rs_process_ajax_to_get_all_user_id'));

        add_action('wp_ajax_rssplitrefreshexpiredpoints', array($this, 'process_ajax_to_refresh_user_points'));
    }

    /*
     * Function for Jquery to show or hide in General Tab
     */

    public static function rs_jquery_function_for_general_tab() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {

                //To Show or hide Points or Percentage for Sumo Reward.

                if (jQuery('#rs_enable_disable_point_priceing').val() == '2') {
                    jQuery('#rs_local_price_points_for_product').parent().parent().hide();
                    jQuery('#rs_label_for_point_value').parent().parent().hide();
                    jQuery('#rs_local_enable_disable_point_price_for_product').parent().parent().hide();
                    jQuery('#rs_global_point_price_type').parent().parent().hide();

                } else {
                    jQuery('#rs_label_for_point_value').parent().parent().show();

                    jQuery('#rs_local_enable_disable_point_price_for_product').parent().parent().show();
                }

                jQuery('#rs_enable_disable_point_priceing').change(function () {
                    if (jQuery('#rs_enable_disable_point_priceing').val() == '2') {
                        jQuery('#rs_label_for_point_value').parent().parent().hide();
                        jQuery('#rs_local_price_points_for_product').parent().parent().hide();
                        jQuery('#rs_local_enable_disable_point_price_for_product').parent().parent().hide();
                        jQuery('#rs_global_point_price_type').parent().parent().hide();

                    } else {
                        jQuery('#rs_label_for_point_value').parent().parent().show();
                        jQuery('#rs_local_enable_disable_point_price_for_product').parent().parent().show();
                        if (jQuery('#rs_local_enable_disable_point_price_for_product').val() == '1') {
                            jQuery('#rs_global_point_price_type').parent().parent().show();
                        } else {
                            jQuery('#rs_global_point_price_type').parent().parent().hide();
                        }

                    }
                });
                if (jQuery('#rs_local_enable_disable_point_price_for_product').val() == '2') {
                    jQuery('#rs_global_point_price_type').parent().parent().hide();
                    jQuery('#rs_local_price_points_for_product').parent().parent().hide();
                } else {
                    jQuery('#rs_global_point_price_type').parent().parent().show();

                }
                jQuery('#rs_local_enable_disable_point_price_for_product').change(function () {
                    if (jQuery('#rs_local_enable_disable_point_price_for_product').val() == '2') {
                        jQuery('#rs_global_point_price_type').parent().parent().hide();
                        jQuery('#rs_local_price_points_for_product').parent().parent().hide();
                    } else {

                        jQuery('#rs_global_point_price_type').parent().parent().show();
                        if (jQuery('#rs_global_point_price_type').val() == '1') {
                            jQuery('#rs_local_price_points_for_product').parent().parent().show();
                        } else {
                            jQuery('#rs_local_price_points_for_product').parent().parent().hide();
                        }

                    }
                });
                if (jQuery('#rs_global_point_price_type').val() == '2') {

                    jQuery('#rs_local_price_points_for_product').parent().parent().hide();
                } else {

                }
                jQuery('#rs_global_point_price_type').change(function () {
                    if (jQuery('#rs_global_point_price_type').val() == '2') {

                        jQuery('#rs_local_price_points_for_product').parent().parent().hide();
                    } else {


                        jQuery('#rs_local_price_points_for_product').parent().parent().show();
                    }
                });

                if (jQuery('#rs_global_enable_disable_sumo_reward').val() == '2') {
                    jQuery('.show_if_enable_in_general').parent().parent().hide();
                } else {
                    jQuery('.show_if_enable_in_general').parent().parent().show();

                    if (jQuery('#rs_global_reward_type').val() == '1') {
                        jQuery('#rs_global_reward_points').parent().parent().show();
                        jQuery('#rs_global_reward_percent').parent().parent().hide();
                    } else {
                        jQuery('#rs_global_reward_points').parent().parent().hide();
                        jQuery('#rs_global_reward_percent').parent().parent().show();
                    }

                    jQuery('#rs_global_reward_type').change(function () {
                        if (jQuery('#rs_global_reward_type').val() == '1') {
                            jQuery('#rs_global_reward_points').parent().parent().show();
                            jQuery('#rs_global_reward_percent').parent().parent().hide();
                        } else {
                            jQuery('#rs_global_reward_points').parent().parent().hide();
                            jQuery('#rs_global_reward_percent').parent().parent().show();
                        }
                    });

                    //To Show or hide Referral Points or Percentage for Sumo Reward.
                    if (jQuery('#rs_global_referral_reward_type').val() == '1') {
                        jQuery('#rs_global_referral_reward_point').parent().parent().show();
                        jQuery('#rs_global_referral_reward_percent').parent().parent().hide();
                    } else {
                        jQuery('#rs_global_referral_reward_point').parent().parent().hide();
                        jQuery('#rs_global_referral_reward_percent').parent().parent().show();
                    }

                    jQuery('#rs_global_referral_reward_type').change(function () {
                        if (jQuery('#rs_global_referral_reward_type').val() == '1') {
                            jQuery('#rs_global_referral_reward_point').parent().parent().show();
                            jQuery('#rs_global_referral_reward_percent').parent().parent().hide();
                        } else {
                            jQuery('#rs_global_referral_reward_point').parent().parent().hide();
                            jQuery('#rs_global_referral_reward_percent').parent().parent().show();
                        }
                    });
                }


                jQuery('#rs_global_enable_disable_sumo_reward').change(function () {
                    if (jQuery(this).val() == '2') {
                        jQuery('.show_if_enable_in_general').parent().parent().hide();
                    } else {
                        jQuery('.show_if_enable_in_general').parent().parent().show();

                        if (jQuery('#rs_global_reward_type').val() == '1') {
                            jQuery('#rs_global_reward_points').parent().parent().show();
                            jQuery('#rs_global_reward_percent').parent().parent().hide();
                        } else {
                            jQuery('#rs_global_reward_points').parent().parent().hide();
                            jQuery('#rs_global_reward_percent').parent().parent().show();
                        }

                        jQuery('#rs_global_reward_type').change(function () {
                            if (jQuery('#rs_global_reward_type').val() == '1') {
                                jQuery('#rs_global_reward_points').parent().parent().show();
                                jQuery('#rs_global_reward_percent').parent().parent().hide();
                            } else {
                                jQuery('#rs_global_reward_points').parent().parent().hide();
                                jQuery('#rs_global_reward_percent').parent().parent().show();
                            }
                        });

                        //To Show or hide Referral Points or Percentage for Sumo Reward.
                        if (jQuery('#rs_global_referral_reward_type').val() == '1') {
                            jQuery('#rs_global_referral_reward_point').parent().parent().show();
                            jQuery('#rs_global_referral_reward_percent').parent().parent().hide();
                        } else {
                            jQuery('#rs_global_referral_reward_point').parent().parent().hide();
                            jQuery('#rs_global_referral_reward_percent').parent().parent().show();
                        }

                        jQuery('#rs_global_referral_reward_type').change(function () {
                            if (jQuery('#rs_global_referral_reward_type').val() == '1') {
                                jQuery('#rs_global_referral_reward_point').parent().parent().show();
                                jQuery('#rs_global_referral_reward_percent').parent().parent().hide();
                            } else {
                                jQuery('#rs_global_referral_reward_point').parent().parent().hide();
                                jQuery('#rs_global_referral_reward_percent').parent().parent().show();
                            }
                        });
                    }
                });

                //To Show or hide Maximum Discount Type as Fixed Value or Percentage.
                if (jQuery('#rs_max_redeem_discount').val() == '1') {
                    jQuery('#rs_fixed_max_redeem_discount').parent().parent().show();
                    jQuery('#rs_percent_max_redeem_discount').parent().parent().hide();
                } else {
                    jQuery('#rs_fixed_max_redeem_discount').parent().parent().hide();
                    jQuery('#rs_percent_max_redeem_discount').parent().parent().show();
                }

                jQuery('#rs_max_redeem_discount').change(function () {
                    if (jQuery('#rs_max_redeem_discount').val() == '1') {
                        jQuery('#rs_fixed_max_redeem_discount').parent().parent().show();
                        jQuery('#rs_percent_max_redeem_discount').parent().parent().hide();
                    } else {
                        jQuery('#rs_fixed_max_redeem_discount').parent().parent().hide();
                        jQuery('#rs_percent_max_redeem_discount').parent().parent().show();
                    }
                });

                //To Show or hide Referral Cookie Expiry as Minutes,Hours or Days.
                if (jQuery('#rs_referral_cookies_expiry').val() == '1') {
                    jQuery('#rs_referral_cookies_expiry_in_min').parent().parent().show();
                    jQuery('#rs_referral_cookies_expiry_in_hours').parent().parent().hide();
                    jQuery('#rs_referral_cookies_expiry_in_days').parent().parent().hide();
                } else if (jQuery('#rs_referral_cookies_expiry').val() == '2') {
                    jQuery('#rs_referral_cookies_expiry_in_min').parent().parent().hide();
                    jQuery('#rs_referral_cookies_expiry_in_hours').parent().parent().show();
                    jQuery('#rs_referral_cookies_expiry_in_days').parent().parent().hide();
                } else {
                    jQuery('#rs_referral_cookies_expiry_in_min').parent().parent().hide();
                    jQuery('#rs_referral_cookies_expiry_in_hours').parent().parent().hide();
                    jQuery('#rs_referral_cookies_expiry_in_days').parent().parent().show();
                }

                jQuery('#rs_referral_cookies_expiry').change(function () {
                    if (jQuery('#rs_referral_cookies_expiry').val() == '1') {
                        jQuery('#rs_referral_cookies_expiry_in_min').parent().parent().show();
                        jQuery('#rs_referral_cookies_expiry_in_hours').parent().parent().hide();
                        jQuery('#rs_referral_cookies_expiry_in_days').parent().parent().hide();
                    } else if (jQuery('#rs_referral_cookies_expiry').val() == '2') {
                        jQuery('#rs_referral_cookies_expiry_in_min').parent().parent().hide();
                        jQuery('#rs_referral_cookies_expiry_in_hours').parent().parent().show();
                        jQuery('#rs_referral_cookies_expiry_in_days').parent().parent().hide();
                    } else {
                        jQuery('#rs_referral_cookies_expiry_in_min').parent().parent().hide();
                        jQuery('#rs_referral_cookies_expiry_in_hours').parent().parent().hide();
                        jQuery('#rs_referral_cookies_expiry_in_days').parent().parent().show();
                    }
                });

                //To Show or hide Referral Should be applied for Unlimited or Limited.
                if (jQuery('#_rs_select_referral_points_referee_time').val() == '2') {
                    jQuery('#_rs_select_referral_points_referee_time_content').parent().parent().show();
                } else {
                    jQuery('#_rs_select_referral_points_referee_time_content').parent().parent().hide();
                }

                jQuery('#_rs_select_referral_points_referee_time').change(function () {
                    if (jQuery('#_rs_select_referral_points_referee_time').val() == '2') {
                        jQuery('#_rs_select_referral_points_referee_time_content').parent().parent().show();
                    } else {
                        jQuery('#_rs_select_referral_points_referee_time_content').parent().parent().hide();
                    }
                });

                //To Show or Hide Maximum Earning Point for each User
                if (jQuery('#rs_enable_disable_max_earning_points_for_user').is(":checked") == false) {
                    jQuery('#rs_max_earning_points_for_user').parent().parent().hide();
                } else {
                    jQuery('#rs_max_earning_points_for_user').parent().parent().show();
                }

                jQuery('#rs_enable_disable_max_earning_points_for_user').change(function () {
                    if (jQuery('#rs_enable_disable_max_earning_points_for_user').is(":checked") == false) {
                        jQuery('#rs_max_earning_points_for_user').parent().parent().hide();
                    } else {
                        jQuery('#rs_max_earning_points_for_user').parent().parent().show();
                    }
                });


                //To Show or Hide No of Purchase Field
                if (jQuery('#rs_enable_delete_referral_cookie_after_first_purchase').is(":checked") == false) {
                    jQuery('#rs_no_of_purchase').parent().parent().hide();
                } else {
                    jQuery('#rs_no_of_purchase').parent().parent().show();
                }

                jQuery('#rs_enable_delete_referral_cookie_after_first_purchase').change(function () {
                    if (jQuery('#rs_enable_delete_referral_cookie_after_first_purchase').is(":checked") == false) {
                        jQuery('#rs_no_of_purchase').parent().parent().hide();
                    } else {
                        jQuery('#rs_no_of_purchase').parent().parent().show();
                    }
                });



                //To show or hide gift icon
                if (jQuery('#_rs_enable_disable_gift_icon').val() == '2') {
                    jQuery('#rs_image_url_upload').parent().parent().hide();
                } else {
                    jQuery('#rs_image_url_upload').parent().parent().show();
                }

                jQuery('#_rs_enable_disable_gift_icon').change(function () {
                    if (jQuery('#_rs_enable_disable_gift_icon').val() == '2') {
                        jQuery('#rs_image_url_upload').parent().parent().hide();
                    } else {
                        jQuery('#rs_image_url_upload').parent().parent().show();
                    }
                });
            });
        </script>
        <?php
    }

    /*
     * Function for choosen in Select user role for banning
     */

    public static function rs_chosen_for_general_tab() {
        global $woocommerce;
        if (isset($_GET['page'])) {
            if ($_GET['page'] == 'rewardsystem_callback') {
                if ((float) $woocommerce->version > (float) ('2.2.0')) {
                    echo RSJQueryFunction::rs_common_select_function('#rs_banning_user_role');
                } else {
                    echo RSJQueryFunction::rs_common_chosen_function('#rs_banning_user_role');
                }
            }
        }
    }

    /*
     * Function to Select user for banning
     */

    public static function rs_select_user_to_ban() {
        global $woocommerce;
        ?>
        <style type="text/css">
            .chosen-container-single {
                position:absolute;
            }

        </style>
        <?php
        echo RSJQueryFunction::rs_common_ajax_function_to_select_user('rs_banned_users_list');
        ?>
        <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_banned_users_list"><?php _e('Select the Users to Restrict/Ban from Using Reward Points', 'rewardsystem'); ?></label>
                </th>
                <td>
                    <select name="rs_banned_users_list[]" style="width:343px;" multiple="multiple" id="rs_banned_users_list" class="short rs_banned_users_list">
                        <?php
                        $json_ids = array();
                        $getuser = get_option('rs_banned_users_list');
                        if ($getuser != "") {
                            $listofuser = $getuser;
                            if (!is_array($listofuser)) {
                                $userids = array_filter(array_map('absint', (array) explode(',', $listofuser)));
                            } else {
                                $userids = $listofuser;
                            }

                            foreach ($userids as $userid) {
                                $user = get_user_by('id', $userid);
                                ?>
                                <option value="<?php echo $userid; ?>" selected="selected"><?php echo esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')'; ?></option>
                                <?php
                            }
                        }
                        ?>
                    </select>
                </td>
            </tr>
        <?php } else { ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_banned_users_list"><?php _e('Select the Users to Restrict/Ban from Using Reward Points', 'rewardsystem'); ?></label>
                </th>
                <td>
                    <input type="hidden" class="wc-customer-search" name="rs_banned_users_list" id="rs_banned_users_list" data-multiple="true" data-placeholder="<?php _e('Search Users', 'rewardsystem'); ?>" data-selected="<?php
                    $json_ids = array();
                    $getuser = get_option('rs_banned_users_list');
                    if ($getuser != "") {
                        $listofuser = $getuser;
                        if (!is_array($listofuser)) {
                            $userids = array_filter(array_map('absint', (array) explode(',', $listofuser)));
                        } else {
                            $userids = $listofuser;
                        }

                        foreach ($userids as $userid) {
                            $user = get_user_by('id', $userid);
                            $json_ids[$user->ID] = esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')';
                        }echo esc_attr(json_encode($json_ids));
                    }
                    ?>" value="<?php echo implode(',', array_keys($json_ids)); ?>" data-allow_clear="true" />
                </td>
            </tr>
            <?php
        }
    }

    /*
     * Save the data for Select user for banning
     */

    public static function save_data_for_rs_select_user_to_ban() {

        $getpostvalue = $_POST['rs_banned_users_list'];

        update_option('rs_banned_users_list', $getpostvalue);
    }

    public static function get_woocommerce_upload_field() {
        if (isset($_REQUEST['rs_image_url_upload'])) {
            update_option('rs_image_url_upload', $_POST['rs_image_url_upload']);
        }
    }

    /*
     * Function For Upload Your own Gift
     */

    public static function rs_add_upload_your_gift_voucher() {
        ?>
        <table class="form-table">
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_image_url_upload"><?php _e('Upload your own Gift Icon', 'rewardsystem'); ?></label>
                </th>
                <td class="forminp forminp-select">
                    <input type="text" id="rs_image_url_upload" name="rs_image_url_upload" value="<?php echo get_option('rs_image_url_upload'); ?>"/>
                    <input type="submit" id="rs_image_upload_button" name="rs_image_upload_button" value="Upload Image"/>
                </td>
            </tr>
        </table>
        <?php
        RSJQueryFunction::rs_ajax_for_upload_your_gift_voucher();
    }

    /*
     * Function to Display Choose Image Column When Upload Image Button is Clicked
     */

    public static function wp_enqueqe_media() {
        if (function_exists('wp_enqueue_media')) {
            wp_enqueue_media();
        } else {
            wp_enqueue_style('thickbox');
            wp_enqueue_script('media-upload');
            wp_enqueue_script('thickbox');
        }
    }

    // Equal Info Message

    public static function reward_system_earning_points_conversion() {
        ?>

        <tr valign="top">

            <td class="forminp forminp-text">
                <input type="text" placeholder="" class="" value="<?php echo get_option('rs_earn_point'); ?>" style="max-width:50px;" id="rs_earn_point" name="rs_earn_point"> <?php _e('Earning Point(s)', 'rewardsystem'); ?>
                &nbsp;&nbsp;&nbsp;=&nbsp;&nbsp;&nbsp;
                <?php echo get_woocommerce_currency_symbol(); ?> <input type="text" placeholder="" class="" value="<?php echo get_option('rs_earn_point_value'); ?>" style="max-width:50px;" id="rs_earn_point_value" name="rs_earn_point_value">
            </td>
        </tr>

        <?php
    }

    public static function reward_system_redeeming_points_conversion() {
        ?>
        <tr valign="top">
            <td class="forminp forminp-text">
                <input type="text" placeholder="" class="" value="<?php echo get_option('rs_redeem_point'); ?>" style="max-width:50px;" id="rs_redeem_point" name="rs_redeem_point"> <?php _e('Redeeming Point(s)', 'rewardsystem'); ?>
                &nbsp;&nbsp;&nbsp;=&nbsp;&nbsp;&nbsp;
                <?php echo get_woocommerce_currency_symbol(); ?> 	<input type="text" placeholder="" class="" value="<?php echo get_option('rs_redeem_point_value'); ?>" style="max-width:50px;" id="rs_redeem_point_value" name="rs_redeem_point_value"></td>
        </td>
        </tr>
        <?php
    }

    public static function reward_system_redeeming_points_conversion_for_cash_back() {
        ?>
        <tr valign="top">
            <td class="forminp forminp-text">
                <input type="text" placeholder="" class="" value="<?php echo get_option('rs_redeem_point_for_cash_back'); ?>" style="max-width:50px;" id="rs_redeem_point_for_cash_back" name="rs_redeem_point_for_cash_back"> <?php _e('Redeeming Point(s)', 'rewardsystem'); ?>
                &nbsp;&nbsp;&nbsp;=&nbsp;&nbsp;&nbsp;
                <?php echo get_woocommerce_currency_symbol(); ?> 	<input type="text" placeholder="" class="" value="<?php echo get_option('rs_redeem_point_value_for_cash_back'); ?>" style="max-width:50px;" id="rs_redeem_point_value_for_cash_back" name="rs_redeem_point_value_for_cash_back"></td>
        </td>
        </tr>
        <?php
    }

    public static function save_reward_system_earning_point_conversion() {
        $get_earning_point = $_POST['rs_earn_point'];
        $get_earning_point_value = $_POST['rs_earn_point_value'];

        update_option('rs_earn_point', $get_earning_point);
        update_option('rs_earn_point_value', $get_earning_point_value);
    }

    public static function save_reward_system_redeeming_point_conversion() {
        $get_redeeming_point = $_POST['rs_redeem_point'];
        $get_redeeming_point_value = $_POST['rs_redeem_point_value'];

        update_option('rs_redeem_point', $get_redeeming_point);
        update_option('rs_redeem_point_value', $get_redeeming_point_value);
    }

    public static function earn_point_conversion(){
       return wc_format_decimal(get_option('rs_earn_point'));
    }
    public static function earn_point_conversion_value(){
      return wc_format_decimal(get_option('rs_earn_point_value'));  
    }
    public static function save_reward_system_redeeming_point_conversion_for_cash_back() {
        $get_redeeming_point = $_POST['rs_redeem_point_for_cash_back'];
        $get_redeeming_point_value = $_POST['rs_redeem_point_value_for_cash_back'];

        update_option('rs_redeem_point_for_cash_back', $get_redeeming_point);
        update_option('rs_redeem_point_value_for_cash_back', $get_redeeming_point_value);
    }

    public static function rs_validation_for_input_field_in_general() {
        ?>

        <script type="text/javascript">
            jQuery(function () {
                jQuery('body').on('blur', '#rs_max_earning_points_for_user[type=text],\n\
                                           #rs_earn_point[type=text],\n\
                                           #rs_earn_point_value[type=text],\n\
                                           #rs_redeem_point[type=text],\n\
                                           #rs_redeem_point_value[type=text],\n\
                                           #rs_fixed_max_redeem_discount[type=text],\n\
                                           #rs_global_reward_points[type=text],\n\
                                           #rs_global_referral_reward_point[type=text],\n\
                                           #rs_global_reward_percent[type=text],\n\
                                           #rs_global_referral_reward_percent[type=text],\n\
                                           #rs_referral_cookies_expiry_in_days[type=text],\n\
                                           #rs_referral_cookies_expiry_in_min[type=text],\n\
                                           #rs_referral_cookies_expiry_in_hours[type=text],\n\
                                           #_rs_select_referral_points_referee_time_content[type=text],\n\
                                           #rs_percent_max_redeem_discount[type=text],\n\
                                           #rs_point_to_be_expire[type=text],\n\
                                           #rs_fixed_max_earn_points[type=text],\n\
                                           #rs_percent_max_earn_points[type=text]', function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });

                    return this;
                });

                jQuery('body').on('keyup change', '#rs_max_earning_points_for_user[type=text],\n\
                                           #rs_earn_point[type=text],\n\
                                           #rs_earn_point_value[type=text],\n\
                                           #rs_redeem_point[type=text],\n\
                                           #rs_redeem_point_value[type=text],\n\
                                           #rs_fixed_max_redeem_discount[type=text],\n\
                                           #rs_global_reward_points[type=text],\n\
                                           #rs_global_referral_reward_point[type=text],\n\
                                           #rs_global_reward_percent[type=text],\n\
                                           #rs_global_referral_reward_percent[type=text],\n\
                                           #rs_referral_cookies_expiry_in_days[type=text],\n\
                                           #rs_referral_cookies_expiry_in_min[type=text],\n\
                                           #rs_referral_cookies_expiry_in_hours[type=text],\n\
                                           #_rs_select_referral_points_referee_time_content[type=text],\n\
                                           #rs_percent_max_redeem_discount[type=text],\n\
                                           #rs_point_to_be_expire[type=text],\n\
                                           #rs_fixed_max_earn_points[type=text],\n\
                                           #rs_percent_max_earn_points[type=text]', function () {
                    var value = jQuery(this).val();
                    console.log(woocommerce_admin.i18n_mon_decimal_error);
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
                jQuery('body').on('keyup change', '#rs_brand_name[type=text]', function (e) {

                    var value = jQuery(this).val();
                    var newvalue = /^[a-zA-Z]*$/.test(value);
                    var value = jQuery(this).val();
                    console.log(woocommerce_admin.i18n_mon_decimal_error);
                    var regex = new RegExp("[^\a-zA-Z0-9 \]", "gi");
                    var newvalue = value.replace(regex, '');
                    if (value !== newvalue) {
                        jQuery(this).val(newvalue);
                        if (jQuery(this).parent().find('.wc_error_tip').size() == 0) {
                            var offset = jQuery(this).position();
                            jQuery(this).after('<div class="wc_error_tip">' + " Please Enter Only Alphabets  " + '</div>');
                            jQuery('.wc_error_tip')
                                    .css('left', offset.left + jQuery(this).width() - (jQuery(this).width() / 2) - (jQuery('.wc_error_tip').width() / 2))
                                    .css('top', offset.top + jQuery(this).height())
                                    .fadeIn('100');
                        }
                        return this;
                    }



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

    public static function refresh_button_for_expired() {
        ?>
        <tr valign="top">
            <th>
                <label for="rs_refresh_button" style="font-size:14px;font-weight:600;"><?php _e('Click Here to Update the Expired Points for User', 'rewardsystem'); ?></label>
            </th>
            <td>
                <input type="button" class="rs_refresh_button" value="<?php _e('Refresh', 'rewardsystem'); ?>"  id="rs_refresh_button" name="rs_refresh_button"/>
                <img class="gif_rs_refresh_button" src="<?php echo WP_PLUGIN_URL; ?>/rewardsystem/img/update.gif" style="width:32px;height:32px;position:absolute"/>
            </td>
        </tr>
        <?php
    }

    public static function rs_send_ajax_to_refresh_expired_points() {
        if (isset($_GET['page'])) {
            if ($_GET['page'] == 'rewardsystem_callback') {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        jQuery('.gif_rs_refresh_button').css('display', 'none');
                        jQuery('.rs_refresh_button').click(function () {
                            jQuery('.gif_rs_refresh_button').css('display', 'inline-block');
                            jQuery(this).attr('data-clicked', '1');
                            var dataclicked = jQuery(this).attr('data-clicked');
                            var dataparam = ({
                                action: 'rsrefreshexpiredpoints',
                                proceedanyway: dataclicked,
                            });
                            function getDataforDate(id) {
                                return jQuery.ajax({
                                    type: 'POST',
                                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                    data: ({
                                        action: 'rssplitrefreshexpiredpoints',
                                        ids: id,
                                        proceedanyway: dataclicked,
                                    }),
                                    success: function (response) {
                                        console.log(response);
                                    },
                                    dataType: 'json',
                                    async: false
                                });
                            }
                            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                    function (response) {
                                        console.log(response);
                                        if (response != 'success') {
                                            var j = 1;
                                            var i, j, temparray, chunk = 10;
                                            for (i = 0, j = response.length; i < j; i += chunk) {
                                                temparray = response.slice(i, i + chunk);
                                                getDataforDate(temparray);
                                            }
                                            location.reload();
                                            console.log('Ajax Done Successfully');

                                        }
                                    }, 'json');
                            return false;
                        });
                    });
                </script>
                <?php
            }
        }
    }

    public static function rs_process_ajax_to_get_all_user_id() {
        if (isset($_POST['proceedanyway'])) {
            if ($_POST['proceedanyway'] == '1') {
                $args = array(
                    'fields' => 'ID',
                );
                $get_users = get_users($args);

                echo json_encode($get_users);
            }
        }
        exit();
    }

    public static function process_ajax_to_refresh_user_points() {
        if (isset($_POST['ids'])) {
            $userids = $_POST['ids'];
            foreach ($userids as $userid) {
                RSPointExpiry::check_if_expiry_on_admin($userid);
            }
        }
        exit();
    }

}

new RSFunctionofGeneralTab();
