<?php
/*
 * Plugin Name: SUMO Reward Points
 * Plugin URI:
 * Description: SUMO Reward Points is a WooCommerce Loyalty Reward System using which you can Reward your Customers using Reward Points for Purchasing Products, Writing Reviews, Sign up on your site etc
 * Version:14.2.2
 * Author: Fantastic Plugins
 * Author URI:
 */

class FPRewardSystem {
    /*
     * To Avoid Database Error
     */

    public static $dbversion = 1.2;

    /*
     * Initialize the Construct of Sumo Reward Points
     */

    public function __construct() {
        /* Include once will help to avoid fatal error by load the files when you call init hook */
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        /* To Avoid Header Already Sent Problem upon Activation or something */
        add_action('init', array($this, 'prevent_header_already_sent_problem'), 1);

        /* Hook to Initiate whether WooCommerce is Active */
        add_action('init', array($this, 'rs_check_if_woocommerce_is_active'), 1);

        define('FP_Reward_Points_Main_Path', __FILE__);

        register_activation_hook(__FILE__, array($this, 'create_table_for_point_expiry'));

        register_activation_hook(__FILE__, array($this, 'rs_update_null_value_to_zero'));

        register_activation_hook(__FILE__, array($this, 'create_table_to_record_earned_points_and_redeem_points'));

        register_activation_hook(__FILE__, array($this, 'create_table_for_email_temlpate_in_db'));

        register_activation_hook(__FILE__, array($this, 'encash_reward_points_submitted_data'));

        register_activation_hook(__FILE__, array($this, 'send_point_submitted_data'));

        register_activation_hook(__FILE__, array($this, 'rs_cron_job_setting'));

        if (is_plugin_active('woocommerce/woocommerce.php')) {

            add_action('init', array($this, 'rs_included_files'), 999);



            add_action('rscronjob', array($this, 'main_function_for_mail_sending'));
            add_filter('cron_schedules', array($this, 'rs_add_x_hourly'));

            /* Reset Reward System Admin Settings */
            add_action('init', array($this, 'reset_reward_system_admin_settings'), 9999);

            add_action('init', array($this, 'default_value_for_earning_and_redeem_points'));
        }


        /* Load WooCommerce Enqueue Script to Load the Script and Styles by filtering the WooCommerce Screen IDS */
        if (isset($_GET['page'])) {
            if (($_GET['page'] == 'rewardsystem_callback')) {
                add_filter('woocommerce_screen_ids', array($this, 'reward_system_load_default_enqueues'), 9, 1);
            }
        }

        include_once 'inc/class_reward_system_menus.php';

        add_action('wp_enqueue_scripts', array($this, 'rewardsystem_enqueue_script'));

        add_action('admin_enqueue_scripts', array($this, 'rewardsystem_enqueue_script'));

        add_action('admin_head', array($this, 'import_user_points_to_reward_system'));

        add_action('wp_ajax_get_user_list_of_ids', array($this, 'perform_ajax_scenario_getting_list_of_user_ids'));

        add_action('wp_ajax_user_points_split_option', array($this, 'perform_ajax_splitted_ids_for_user_ids'));

        add_action('plugins_loaded', array($this, 'rs_translate_file'));
    }

    public static function rs_included_files() {

        /*
         * Including Setting file for all tab.
         */
        include_once 'inc/admin/class_general_tab_setting.php';

        include_once 'inc/admin/class_reward_points_for_action.php';

        include_once 'inc/admin/class_member_level_tab.php';

        include_once 'inc/admin/class_user_reward_points_tab.php';

        include_once 'inc/admin/class_add_remove_points_tab.php';

        include_once 'inc/admin/class_message_tab.php';

        include_once 'inc/admin/class_rs_shop_page_customization.php';

        include_once 'inc/admin/class_rs_single_product_page.php';

        include_once 'inc/admin/class_cart_tab.php';

        include_once 'inc/admin/class_checkout_tab.php';

        include_once 'inc/admin/class_myaccount_tab.php';

        include_once 'inc/admin/class_masterlog_tab.php';

        include_once 'inc/admin/class_referral_reward_tab.php';

        include_once 'inc/admin/class_update_tab.php';

        include_once 'inc/admin/class_status_tab.php';

        include_once 'inc/admin/class_refer_a_friend_tab.php';

        include_once 'inc/admin/class_social_rewards_tab.php';

        include_once 'inc/admin/class_email_template_tab.php';

        include_once 'inc/admin/class_mail_tab.php';

        include_once 'inc/admin/class_sms_tab.php';

        include_once 'inc/admin/class_order_tab.php';

        include_once 'inc/admin/class_gift_voucher_tab.php';

        include_once 'inc/admin/class_import_export_tab.php';

        include_once 'inc/admin/class_form_for_cash_back_tab.php';

        include_once 'inc/admin/class_request_for_cash_back_tab.php';

        include_once 'inc/admin/class_coupon_reward_points_tab.php';

        include_once 'inc/admin/class_manuall_referral_link_tab.php';

        include_once 'inc/admin/class_reports_in_csv_tab.php';

        include_once 'inc/admin/class_reset_tab.php';

        include_once 'inc/admin/class_troubleshoot_tab.php';

        include_once 'inc/admin/class_localization_tab.php';

        include_once 'inc/admin/class_buying_reward_points.php';

        include_once 'inc/admin/class_form_for_send_points_tab.php';

        include_once 'inc/admin/class_request_for_send_points_tab.php';

        include_once 'inc/admin/wc_class_send_point_wplist.php';

        include_once 'inc/rs_price_rule_checker_for_variant.php';

        include 'inc/rs_wc_booking_compatabilty.php';



        include 'inc/admin/class_nominee_tab.php';

        include 'inc/admin/class_point_url_tab.php';

        include 'inc/admin/class_support_tab.php';

        include 'inc/ajax_main_function.php';


        /*
         * Include file for Point Expiry
         */
        include 'inc/admin/main_functions_for_point_expiry.php';

        /*
         * Include file for Settings in Product Level
         */
        include_once 'inc/admin/class_admin_settings_for_simple_product.php';

        include_once 'inc/admin/class_admin_settings_for_variable_product.php';

        include_once 'inc/admin/class_admin_settings_for_category_field.php';

        /*
         * Include Function for saving meta values
         */
        include 'inc/rs_function_for_saving_meta_values.php';

        /*
         * Include Function for all tabs.
         */
        include('inc/rs_function_for_general_tab.php');

        include('inc/rs_function_for_reward_points_for_action.php');

        include('inc/rs_function_for_member_level.php');

        include('inc/rs_function_for_user_reward_points.php');

        include('inc/rs_function_for_add_remove_tab.php');

        include('inc/rs_function_for_message_tab.php');

        include('inc/rs_function_for_cart_tab.php');

        include('inc/rs_function_for_checkout.php');

        include('inc/rs_function_for_myaccount_tab.php');

        include('inc/rs_function_for_masterlog_tab.php');

        include('inc/rs_function_for_referral_reward_tab.php');

        include ('inc/rs_referral_log_count.php');

        include('inc/rs_function_for_update_tab.php');

        include('inc/rs_function_for_status_tab.php');

        include('inc/rs_function_for_refer_a_friend.php');

        include('inc/rs_function_for_social_reward_tab.php');

        include('inc/rs_function_for_email_template.php');

        include('inc/rs_function_for_mail_tab.php');

        include('inc/rs_function_for_sms_tab.php');

        include('inc/rs_function_for_order_tab.php');

        include('inc/rs_function_for_gift_voucher_tab.php');

        include('inc/rs_function_for_import_export.php');

        include('inc/rs_function_for_form_for_cash_back.php');

        include('inc/rs_function_for_request_for_cash_back.php');

        include('inc/rs_function_for_coupon_reward_point_tab.php');

        include('inc/rs_function_for_manual_referral_link_tab.php');

        include('inc/rs_function_for_reports_in_csv_tab.php');

        include('inc/rs_function_for_reset_tab.php');

        include('inc/rs_free_product_main_function.php');

        include('inc/rs_function_for_send_points.php');

        include('inc/rs_function_for_request_for_send_points.php');

        include('inc/rs_function_for_nominee.php');

        //Function for Jquery
        include_once 'inc/rs_jquery.php';

        // Include Files for List Table

        include 'inc/admin/class_wp_list_table_for_users.php';

        include 'inc/admin/class_wp_list_table_view_log_user.php';

        include 'inc/admin/class_wp_list_table_referral_table.php';

        include 'inc/admin/class_wp_list_table_view_referral_table.php';

        include 'inc/admin/class_wp_list_table_master_log.php';

        include_once 'inc/wc_class_encashing_wplist.php';

        //Include File to add Registration Points to Referror and Referral
        include 'inc/rs_function_to_add_registration_points.php';

        include 'inc/rs_function_to_apply_coupon.php';

        include_once 'inc/admin/class_wpml_support.php';

        include_once 'inc/compatibility/rewardpoints_wc2point6.php';
    }

    /*
     * Function for set cron time
     */

    public static function rs_cron_job_setting() {
        wp_clear_scheduled_hook('rscronjob');
        delete_option('rscheckcronsafter');
        if (wp_next_scheduled('rscronjob') == false) {
            wp_schedule_event(time(), 'rshourly', 'rscronjob');
        }
    }

    public static function rs_add_x_hourly($schedules) {

        $interval = get_option('rs_mail_cron_time');
        if (get_option('rs_mail_cron_type') == 'minutes') {
            $interval = $interval * 60;
        } else if (get_option('rs_mail_cron_type') == 'hours') {
            $interval = $interval * 3600;
        } else if (get_option('rs_mail_cron_type') == 'days') {
            $interval = $interval * 86400;
        }
        $schedules['rshourly'] = array(
            'interval' => $interval,
            'display' => 'RS Hourly'
        );
        return $schedules;
    }

    /*
     * Function for send mail based on cron time
     */

    public static function main_function_for_mail_sending() {
        global $wpdb;
        global $woocommerce;
        $emailtemplate_table_name = $wpdb->prefix . 'rs_templates_email';
        $email_templates = $wpdb->get_results("SELECT * FROM $emailtemplate_table_name"); //all email templates
        if (is_array($email_templates)) {
            foreach ($email_templates as $emails) {
                if ($emails->rs_status == "ACTIVE") {
                    if ($emails->mailsendingoptions == '1') {
                        if (get_option('rsemailtemplates' . $emails->id) != '1') {
                            if ($emails->sendmail_options == '1') {
                                if ($emails->rsmailsendingoptions == '3') {
                                    $checksendingmailoptions = 1;
                                    $maindta = $checksendingmailoptions + get_option('rscheckcronsafter');
                                    $newdatavalues = update_option('rscheckcronsafter', $maindta);

                                    if (get_option('rscheckcronsafter') > 1) {
                                        //if()
                                        foreach (get_users() as $myuser) {
                                            $user = get_userdata($myuser->ID);
                                            $user_wmpl_lang = get_user_meta($myuser->ID, 'rs_wpml_lang', true);
                                            if (empty($user_wmpl_lang)) {
                                                $user_wmpl_lang = 'en';
                                            }
                                            $to = $user->user_email;
                                            $subject = RSWPMLSupport::fp_rs_get_wpml_text('rs_template_' . $emails->id . '_subject', $user_wmpl_lang, $emails->subject);
                                            $firstname = $user->user_firstname;
                                            $lastname = $user->user_lastname;
                                            $url_to_click = "<a href=" . site_url() . ">" . site_url() . "</a>";
                                            $userpoint = RSPointExpiry::get_sum_of_total_earned_points($myuser->ID);
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $userpoint = round($userpoint, $roundofftype);
                                            $minimumuserpoints = $emails->minimum_userpoints;
                                            if ($minimumuserpoints == '') {
                                                $minimumuserpoints = 0;
                                            } else {
                                                $minimumuserpoints = $emails->minimum_userpoints;
                                            }
                                            if ($minimumuserpoints < $userpoint) {

                                                $message = RSWPMLSupport::fp_rs_get_wpml_text('rs_template_' . $emails->id . '_message', $user_wmpl_lang, $emails->message);
                                                $message = str_replace('{rssitelink}', $url_to_click, $message);
                                                $message = str_replace('{rsfirstname}', $firstname, $message);
                                                $message = str_replace('{rslastname}', $lastname, $message);
                                                $message = str_replace('{rspoints}', $userpoint, $message);
                                                $message = do_shortcode($message); //shortcode feature
                                                ob_start();
                                                wc_get_template('emails/email-header.php', array('email_heading' => $subject));
                                                echo $message;
                                                wc_get_template('emails/email-footer.php');
                                                $woo_temp_msg = ob_get_clean();
                                                $headers = "MIME-Version: 1.0\r\n";
                                                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                                                if ($emails->sender_opt == 'local') {
                                                    $headers .= "From: " . $emails->from_name . " <" . $emails->from_email . ">\r\n";
                                                } else {
                                                    $headers .= "From: " . get_option('woocommerce_email_from_name') . " <" . get_option('woocommerce_email_from_address') . ">\r\n";
                                                }
                                                //wp_mail($to, $subject, $woo_temp_msg, $headers='');
                                                if ('2' == get_option('rs_select_mail_function')) {
                                                    $mailer = WC()->mailer();

                                                    if ($mailer->send($to, $subject, $woo_temp_msg, $headers, '-fwebmaster@' . $_SERVER['SERVER_NAME'])) {
                                                        
                                                    }
                                                } elseif ('1' == get_option('rs_select_mail_function')) {
                                                    if (mail($to, $subject, $woo_temp_msg, $headers, '-fwebmaster@' . $_SERVER['SERVER_NAME'])) {
                                                        
                                                    }
                                                } else {
                                                    $mailer = WC()->mailer();
                                                    if ($mailer->send($to, $subject, $woo_temp_msg, $headers = '')) {
                                                        
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($emails->rsmailsendingoptions == '3') {
                                    $emailusers = unserialize($emails->sendmail_to);
                                    $checksendingmailoptions = 1;
                                    $maindta = $checksendingmailoptions + get_option('rscheckcronsafter');
                                    $newdatavalues = update_option('rscheckcronsafter', $maindta);

                                    if (get_option('rscheckcronsafter') > 1) {
                                        foreach ($emailusers as $myuser) {
                                            $user = get_userdata($myuser);
                                            $user_wmpl_lang = get_user_meta($myuser, 'rs_wpml_lang', true);
                                            if (empty($user_wmpl_lang)) {
                                                $user_wmpl_lang = 'en';
                                            }
                                            $to = $user->user_email;
                                            $subject = RSWPMLSupport::fp_rs_get_wpml_text('rs_template_' . $emails->id . '_subject', $user_wmpl_lang, $emails->subject);
                                            $firstname = $user->user_firstname;
                                            $lastname = $user->user_lastname;
                                            $url_to_click = site_url();
                                            $userpoint = RSPointExpiry::get_sum_of_total_earned_points($myuser->ID);
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $userpoint = round($userpoint, $roundofftype);
                                            $minimumuserpoints = $emails->minimum_userpoints;
                                            if ($minimumuserpoints == '') {
                                                $minimumuserpoints = 0;
                                            } else {
                                                $minimumuserpoints = $emails->minimum_userpoints;
                                            }
                                            if ($minimumuserpoints < $userpoint) {
                                                $message = RSWPMLSupport::fp_rs_get_wpml_text('rs_template_' . $emails->id . '_message', $user_wmpl_lang, $emails->message);
                                                $message = str_replace('{rssitelink}', $url_to_click, $message);
                                                $message = str_replace('{rsfirstname}', $firstname, $message);
                                                $message = str_replace('{rslastname}', $lastname, $message);
                                                $message = str_replace('{rspoints}', $userpoint, $message);
                                                $message = do_shortcode($message); //shortcode feature
                                                ob_start();
                                                wc_get_template('emails/email-header.php', array('email_heading' => $subject));
                                                echo $message;
                                                wc_get_template('emails/email-footer.php');
                                                $woo_temp_msg = ob_get_clean();
                                                $headers = "MIME-Version: 1.0\r\n";
                                                $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                                                if ($emails->sender_opt == 'local') {
                                                    $headers .= "From: " . $emails->from_name . " <" . $emails->from_email . ">\r\n";
                                                } else {
                                                    $headers .= "From: " . get_option('woocommerce_email_from_name') . " <" . get_option('woocommerce_email_from_address') . ">\r\n";
                                                }
                                                if ('2' == get_option('rs_select_mail_function')) {
                                                    if ($mailer->send($to, $subject, $woo_temp_msg, $headers, '-fwebmaster@' . $_SERVER['SERVER_NAME'])) {
                                                        
                                                    }
                                                } elseif ('1' == get_option('rs_select_mail_function')) {
                                                    if (mail($to, $subject, $woo_temp_msg, $headers, '-fwebmaster@' . $_SERVER['SERVER_NAME'])) {
                                                        
                                                    }
                                                } else {
                                                    $mailer = WC()->mailer();
                                                    if ($mailer->send($to, $subject, $woo_temp_msg, $headers = '')) {
                                                        
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                            update_option('rsemailtemplates' . $emails->id, '1');
                        }
                    } else {
                        if ($emails->sendmail_options == '1') {
                            if ($emails->rsmailsendingoptions == '3') {
                                $checksendingmailoptions = 1;
                                $maindta = $checksendingmailoptions + get_option('rscheckcronsafter');
                                $newdatavalues = update_option('rscheckcronsafter', $maindta);

                                if (get_option('rscheckcronsafter') > 1) {
                                    foreach (get_users() as $myuser) {
                                        $user = get_userdata($myuser->ID);
                                        $user_wmpl_lang = get_user_meta($myuser->ID, 'rs_wpml_lang', true);
                                        if (empty($user_wmpl_lang)) {
                                            $user_wmpl_lang = 'en';
                                        }
                                        $to = $user->user_email;
                                        $subject = RSWPMLSupport::fp_rs_get_wpml_text('rs_template_' . $emails->id . '_subject', $user_wmpl_lang, $emails->subject);
                                        $firstname = $user->user_firstname;
                                        $lastname = $user->user_lastname;
                                        $url_to_click = "<a href=" . site_url() . ">" . site_url() . "</a>";
                                        $userpoint = RSPointExpiry::get_sum_of_total_earned_points($myuser->ID);
                                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                        $userpoint = round($userpoint, $roundofftype);
                                        $minimumuserpoints = $emails->minimum_userpoints;
                                        if ($minimumuserpoints == '') {
                                            $minimumuserpoints = 0;
                                        } else {
                                            $minimumuserpoints = $emails->minimum_userpoints;
                                        }
                                        if ($minimumuserpoints < $userpoint) {


                                            $message = RSWPMLSupport::fp_rs_get_wpml_text('rs_template_' . $emails->id . '_message', $user_wmpl_lang, $emails->message);
                                            $message = str_replace('{rssitelink}', $url_to_click, $message);
                                            $message = str_replace('{rsfirstname}', $firstname, $message);
                                            $message = str_replace('{rslastname}', $lastname, $message);
                                            $message = str_replace('{rspoints}', $userpoint, $message);
                                            $message = do_shortcode($message); //shortcode feature
                                            ob_start();
                                            wc_get_template('emails/email-header.php', array('email_heading' => $subject));
                                            echo $message;
                                            wc_get_template('emails/email-footer.php');
                                            $woo_temp_msg = ob_get_clean();
                                            $headers = "MIME-Version: 1.0\r\n";
                                            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                                            if ($emails->sender_opt == 'local') {
                                                $headers .= "From: " . $emails->from_name . " <" . $emails->from_email . ">\r\n";
                                            } else {
                                                $headers .= "From: " . get_option('woocommerce_email_from_name') . " <" . get_option('woocommerce_email_from_address') . ">\r\n";
                                            }
                                            //wp_mail($to, $subject, $woo_temp_msg, $headers='');
                                            if ('2' == get_option('rs_select_mail_function')) {
                                                $mailer = WC()->mailer();
                                                if ($mailer->send($to, $subject, $woo_temp_msg, $headers, '-fwebmaster@' . $_SERVER['SERVER_NAME'])) {
                                                    
                                                }
                                            } elseif ('1' == get_option('rs_select_mail_function')) {
                                                if (mail($to, $subject, $woo_temp_msg, $headers, '-fwebmaster@' . $_SERVER['SERVER_NAME'])) {
                                                    
                                                }
                                            } else {
                                                $mailer = WC()->mailer();
                                                if ($mailer->send($to, $subject, $woo_temp_msg, $headers = '')) {
                                                    
                                                }
                                            }
                                        }
                                    }
                                }
                            }
                        } else {
                            if ($emails->rsmailsendingoptions == '3') {
                                $emailusers = unserialize($emails->sendmail_to);
                                $checksendingmailoptions = 1;
                                $maindta = $checksendingmailoptions + get_option('rscheckcronsafter');
                                $newdatavalues = update_option('rscheckcronsafter', $maindta);

                                if (get_option('rscheckcronsafter') > 1) {
                                    foreach ($emailusers as $myuser) {
                                        $user = get_userdata($myuser);
                                        $user_wmpl_lang = get_user_meta($myuser, 'rs_wpml_lang', true);
                                        if (empty($user_wmpl_lang)) {
                                            $user_wmpl_lang = 'en';
                                        }
                                        $to = $user->user_email;
                                        $subject = RSWPMLSupport::fp_rs_get_wpml_text('rs_template_' . $emails->id . '_subject', $user_wmpl_lang, $emails->subject);
                                        $firstname = $user->user_firstname;
                                        $lastname = $user->user_lastname;
                                        $url_to_click = site_url();
                                        $userpoint = RSPointExpiry::get_sum_of_total_earned_points($myuser->ID);
                                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                        $userpoint = round($userpoint, $roundofftype);
                                        $minimumuserpoints = $emails->minimum_userpoints;
                                        if ($minimumuserpoints == '') {
                                            $minimumuserpoints = 0;
                                        } else {
                                            $minimumuserpoints = $emails->minimum_userpoints;
                                        }
                                        if ($minimumuserpoints < $userpoint) {
                                            $message = RSWPMLSupport::fp_rs_get_wpml_text('rs_template_' . $emails->id . '_message', $user_wmpl_lang, $emails->message);
                                            $message = str_replace('{rssitelink}', $url_to_click, $message);
                                            $message = str_replace('{rsfirstname}', $firstname, $message);
                                            $message = str_replace('{rslastname}', $lastname, $message);
                                            $message = str_replace('{rspoints}', $userpoint, $message);
                                            $message = do_shortcode($message); //shortcode feature
                                            ob_start();
                                            wc_get_template('emails/email-header.php', array('email_heading' => $subject));
                                            echo $message;
                                            wc_get_template('emails/email-footer.php');
                                            $woo_temp_msg = ob_get_clean();
                                            $headers = "MIME-Version: 1.0\r\n";
                                            $headers .= "Content-Type: text/html; charset=UTF-8\r\n";
                                            if ($emails->sender_opt == 'local') {
                                                $headers .= "From: " . $emails->from_name . " <" . $emails->from_email . ">\r\n";
                                            } else {
                                                $headers .= "From: " . get_option('woocommerce_email_from_name') . " <" . get_option('woocommerce_email_from_address') . ">\r\n";
                                            }
                                            if ('2' == get_option('rs_select_mail_function')) {
                                                $mailer = WC()->mailer();
                                                if ($mailer->send($to, $subject, $woo_temp_msg, $headers, '-fwebmaster@' . $_SERVER['SERVER_NAME'])) {
                                                    
                                                }
                                            } elseif ('1' == get_option('rs_select_mail_function')) {
                                                if (mail($to, $subject, $woo_temp_msg, $headers, '-fwebmaster@' . $_SERVER['SERVER_NAME'])) {
                                                    
                                                }
                                            } else {
                                                $mailer = WC()->mailer();
                                                if ($mailer->send($to, $subject, $woo_temp_msg, $headers = '')) {
                                                    
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

    /*
     * Function to check wheather Woocommerce is active or not
     */

    public static function rs_check_if_woocommerce_is_active() {

        if (is_multisite()) {
            if (!is_plugin_active_for_network('woocommerce/woocommerce.php') && (!is_plugin_active('woocommerce/woocommerce.php'))) {
                if (is_admin()) {
                    $variable = "<div class='error'><p> SUMO Reward Points will not work until WooCommerce Plugin is Activated. Please Activate the WooCommerce Plugin. </p></div>";
                    echo $variable;
                }
                return;
            }
        } else {
            if (!is_plugin_active('woocommerce/woocommerce.php')) {
                if (is_admin()) {
                    $variable = "<div class='error'><p> SUMO Reward Points will not work until WooCommerce Plugin is Activated. Please Activate the WooCommerce Plugin. </p></div>";
                    echo $variable;
                }
                return;
            }
        }
    }

    /*  */

    /*
     * Load the Default JAVASCRIPT and CSS
     */

    public static function reward_system_load_default_enqueues() {
        global $my_admin_page;
        $newscreenids = get_current_screen();
        if (isset($_GET['page'])) {
            if (($_GET['page'] == 'rewardsystem_callback')) {
                $array[] = $newscreenids->id;
                return $array;
            } else {
                $array[] = '';
                return $array;
            }
        }
    }

    public static function rs_update_null_value_to_zero() {
        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        $querys = $wpdb->get_results("SELECT id,usedpoints FROM $table_name WHERE usedpoints IS NULL", ARRAY_A);
        foreach ($querys as $query) {
            $wpdb->update($table_name, array('usedpoints' => 0), array('id' => $query['id']));
        }
    }

    /*
     * Function to Prevent Header Error that says You have already sent the header.
     */

    public static function prevent_header_already_sent_problem() {
        ob_start();
    }

    // Import User Reward Points from Old Version to Latest Version
    public static function import_user_points_to_reward_system() {
        wp_enqueue_script('jquery');
        ?>
        <script type="text/javascript">
            jQuery(function () {
                jQuery('.gif_rs_sumo_reward_button').css('display', 'none');
            });
            jQuery(document).ready(function () {
                jQuery('#rs_add_old_points').click(function () {
                    if (confirm("Are you sure you want to Add the Existing points?")) {
                        jQuery('.gif_rs_sumo_reward_button').css('display', 'inline-block');
                        var dataparam = ({
                            action: 'get_user_list_of_ids'
                        });
                        function getData(id) {
                            console.log(id);
                            return jQuery.ajax({
                                type: 'POST',
                                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                data: ({
                                    action: 'user_points_split_option',
                                    ids: id
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
                                    if (response !== 'success') {
                                        var j = 1;
                                        var i, j, temparray, chunk = 10;
                                        for (i = 0, j = response.length; i < j; i += chunk) {
                                            temparray = response.slice(i, i + chunk);
                                            getData(temparray);
                                        }
                                        jQuery.when(getData()).done(function (a1) {
                                            console.log('Ajax Done Successfully');
                                            location.reload();
                                        });
                                    } else {
                                        var newresponse = response.replace(/\s/g, '');
                                        if (newresponse === 'success') {
                                            //jQuery('.submit .button-primary').trigger('click');
                                        }
                                    }
                                }, 'json');
                        return false;
                    }
                });
            });

        </script>
        <?php
    }

    //Perform Ajax Scenario for Updating User Points
    public static function perform_ajax_scenario_getting_list_of_user_ids() {
        $args = array(
            'fields' => 'ID',
            'meta_key' => '_my_reward_points',
            'meta_value' => '',
            'meta_compare' => '!='
        );
        $get_users = get_users($args);

        echo json_encode($get_users);

        exit();
    }

    // Perform Splitted User IDs in Reward System Function
    public static function perform_ajax_splitted_ids_for_user_ids() {
        if (isset($_POST['ids'])) {
            foreach ($_POST['ids'] as $eachid) {
                self::insert_user_points_in_database($eachid);
            }
        }

        exit();
    }

    // Insert User Points in Database

    public static function insert_user_points_in_database($user_id) {
        global $wpdb;
        $user_points = get_user_meta($user_id, '_my_reward_points', true);
        $table_name = $wpdb->prefix . "rspointexpiry";
        $currentdate = time();
        $query = $wpdb->get_row("SELECT * FROM $table_name WHERE userid = $user_id and expirydate = 999999999999", ARRAY_A);
        if (!empty($query)) {
            $id = $query['id'];
            $oldearnedpoints = $query['earnedpoints'];
            $oldearnedpoints = $oldearnedpoints + $user_points;
            $wpdb->update($table_name, array('earnedpoints' => $oldearnedpoints), array('id' => $id));
        } else {
            $wpdb->insert($table_name, array(
                'earnedpoints' => $user_points,
                'usedpoints' => '',
                'expiredpoints' => '0',
                'userid' => $user_id,
                'earneddate' => $currentdate,
                'expirydate' => '999999999999',
                'checkpoints' => 'OUP',
                'orderid' => '',
                'totalearnedpoints' => '',
                'totalredeempoints' => '',
                'reasonindetail' => ''
            ));
        }
    }

    /* Create the rspointexpiry Table structure to perform few more audits */

    public static function create_table_for_point_expiry() {
        // Create Table for Point Expiry

        global $wpdb;
        $getdbversiondata = get_option("rs_point_expiry") != 'false' ? get_option('rs_point_expiry') : "0";
        $table_name = $wpdb->prefix . 'rspointexpiry';
        if ($getdbversiondata != self::$dbversion) {
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		earnedpoints FLOAT,
                usedpoints FLOAT,
                expiredpoints FLOAT,
                userid INT(99),
                earneddate VARCHAR(999) NOT NULL,
                expirydate VARCHAR(999) NOT NULL,
                checkpoints VARCHAR(999) NOT NULL,
                orderid INT(99),
                totalearnedpoints INT(99),
                totalredeempoints INT(99),
                reasonindetail VARCHAR(999),
         	UNIQUE KEY id (id)
	) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($sql);
            add_option('rs_point_expiry', self::$dbversion);
        }
    }

    public static function create_table_to_record_earned_points_and_redeem_points() {

        global $wpdb;
        $getdbversiondata = get_option("rs_record_points") != 'false' ? get_option('rs_record_points') : "0";
        $table_name = $wpdb->prefix . 'rsrecordpoints';
        if ($getdbversiondata != self::$dbversion) {
            $charset_collate = $wpdb->get_charset_collate();

            $sql = "CREATE TABLE IF NOT EXISTS $table_name (
		id mediumint(9) NOT NULL AUTO_INCREMENT,
		earnedpoints FLOAT,
                redeempoints FLOAT,
                userid INT(99),
                earneddate VARCHAR(999) NOT NULL,
                expirydate VARCHAR(999) NOT NULL,
                checkpoints VARCHAR(999) NOT NULL,
                earnedequauivalentamount INT(99),
                redeemequauivalentamount INT(99),
                orderid INT(99),
                productid INT(99),
                variationid INT(99),
                refuserid INT(99),
                reasonindetail VARCHAR(999),
                totalpoints INT(99),
                showmasterlog VARCHAR(999),
                showuserlog VARCHAR(999),
                nomineeid INT(99),
                nomineepoints INT(99),
         	UNIQUE KEY id (id)
	) $charset_collate;";

            require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
            dbDelta($sql);
            add_option('rs_record_points', self::$dbversion);
        }
    }

    public static function create_table_for_email_temlpate_in_db() {
//Email Template Table
        global $wpdb;
        $table_name_email = $wpdb->prefix . 'rs_templates_email';
        $sql = "CREATE TABLE $table_name_email (
                id mediumint(9) NOT NULL AUTO_INCREMENT,
                template_name LONGTEXT NOT NULL,
                sender_opt VARCHAR(10) NOT NULL DEFAULT 'woo',
                from_name LONGTEXT NOT NULL,
                from_email LONGTEXT NOT NULL,
                subject LONGTEXT NOT NULL,
                message LONGTEXT NOT NULL,
                earningpoints LONGTEXT NOT NULL,
                redeemingpoints LONGTEXT NOT NULL,
                mailsendingoptions LONGTEXT NOT NULL,
                rsmailsendingoptions LONGTEXT NOT NULL,
                minimum_userpoints LONGTEXT NOT NULL,
                sendmail_options VARCHAR(10) NOT NULL DEFAULT '1',
                sendmail_to LONGTEXT NOT NULL,
                sending_type VARCHAR(20) NOT NULL,
                rs_status VARCHAR(10) NOT NULL DEFAULT 'DEACTIVATE',
                UNIQUE KEY id (id)
              )DEFAULT CHARACTER SET utf8;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
        $email_temp_check = $wpdb->get_results("SELECT * FROM $table_name_email", OBJECT);
        if (empty($email_temp_check)) {
            $wpdb->insert($table_name_email, array('template_name' => 'Default',
                'sender_opt' => 'woo',
                'from_name' => 'Admin',
                'from_email' => get_option('admin_email'),
                'subject' => 'SUMO Rewards Point',
                'message' => 'Hi {rsfirstname} {rslastname}, <br><br> You have Earned Reward Points: {rspoints} on {rssitelink}  <br><br> You can use this Reward Points to make discounted purchases on {rssitelink} <br><br> Thanks',
                'minimum_userpoints' => '0',
                'mailsendingoptions' => '2',
                'rsmailsendingoptions' => '3',
                'rs_status' => 'DEACTIVATE',
            ));
        }

        $wpdb->query("ALTER TABLE $table_name_email ADD rs_status VARCHAR(10) NOT NULL DEFAULT 'DEACTIVATE'");
    }

    public static function encash_reward_points_submitted_data() {
        global $wpdb;
        $charset_collate = '';
        $table_name = $wpdb->prefix . "sumo_reward_encashing_submitted_data";
        if (!empty($wpdb->charset)) {
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }

        if (!empty($wpdb->collate)) {
            $charset_collate .= " COLLATE {$wpdb->collate}";
        }
        $sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  userid INT(225),
  userloginname VARCHAR(200),
  pointstoencash VARCHAR(200),
  pointsconvertedvalue VARCHAR(200),
  encashercurrentpoints VARCHAR(200),
  reasonforencash LONGTEXT,
  encashpaymentmethod VARCHAR(200),
  paypalemailid VARCHAR(200),
  otherpaymentdetails LONGTEXT,
  status VARCHAR(200),
  date VARCHAR(300),
  UNIQUE KEY id (id)
) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }

    public static function send_point_submitted_data() {
        global $wpdb;
        $charset_collate = '';
        $table_name = $wpdb->prefix . "sumo_reward_send_point_submitted_data";
        if (!empty($wpdb->charset)) {
            $charset_collate = "DEFAULT CHARACTER SET {$wpdb->charset}";
        }

        if (!empty($wpdb->collate)) {
            $charset_collate .= " COLLATE {$wpdb->collate}";
        }
        $sql = "CREATE TABLE $table_name (
  id mediumint(9) NOT NULL AUTO_INCREMENT,
  userid INT(225),
  userloginname VARCHAR(200),
  pointstosend VARCHAR(200),
  sendercurrentpoints VARCHAR(200),
  status VARCHAR(200),
  selecteduser LONGTEXT NOT NULL,
  date VARCHAR(300),
  UNIQUE KEY id (id)
) $charset_collate;";
        require_once( ABSPATH . 'wp-admin/includes/upgrade.php' );
        dbDelta($sql);
    }

    public function rewardgateway() {

        include 'inc/admin/class_rewardgateway.php';

        add_action('plugins_loaded', 'init_reward_gateway_class');
    }

    public static function rewardsystem_enqueue_script() {
        wp_register_script('wp_reward_footable', plugins_url('rewardsystem/js/footable.js'));
        wp_register_script('wp_reward_footable_sort', plugins_url('rewardsystem/js/footable.sort.js'));
        wp_register_script('wp_reward_footable_paging', plugins_url('rewardsystem/js/footable.paginate.js'));
        wp_register_script('wp_reward_footable_filter', plugins_url('rewardsystem/js/footable.filter.js'));
        wp_register_style('wp_reward_footable_css', plugins_url('rewardsystem/css/footable.core.css'));
        wp_register_style('wp_reward_bootstrap_css', plugins_url('rewardsystem/css/bootstrap.css'));
        wp_register_style('wp_reward_facebook', plugins_url('rewardsystem/css/style.css'));
        wp_enqueue_script('jquery');
        wp_enqueue_script('wp_reward_facebook');
        wp_enqueue_script('wp_reward_footable');
        wp_enqueue_script('wp_reward_footable_sort');
        wp_enqueue_script('wp_reward_footable_paging');
        wp_enqueue_script('wp_reward_footable_filter');
        wp_enqueue_style('wp_reward_footable_css');
        wp_enqueue_style('wp_reward_bootstrap_css');
    }

    public static function reset_reward_system_admin_settings() {
        if (!empty($_POST['reset'])) {
            if (empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'woocommerce-reset_settings'))
                die(__('Action failed. Please refresh the page and retry.', 'rewardsystem'));

            foreach (RSGeneralTabSetting::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                    update_option('rs_earn_point', '1');
                    update_option('rs_earn_point_value', '1');
                    update_option('rs_redeem_point', '1');
                    update_option('rs_redeem_point_value', '1');
                    update_option('rs_redeem_point_for_cash_back', '1');
                    update_option('rs_redeem_point_value_for_cash_back', '1');
                }
            }
            foreach (RSRewardPointsForAction::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }
            foreach (RSMessage::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }
            foreach (RSMyaccount::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }
            foreach (RSCart::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }

            foreach (RSCheckout::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }

            foreach (RSReferAFriend::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }

            foreach (RSMemberLevel::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }

            foreach (RSTroubleshoot::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }

            foreach (RSSocialReward::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }
            foreach (RSMail::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }

            foreach (RSStatus::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }

            foreach (RSLocalization::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }

            foreach (RSFormForCashBack::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }
            foreach (RSGiftVoucher::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }
            foreach (RSCouponRewardPoints::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }
            delete_option('rewards_dynamic_rule_couponpoints');
            foreach (RSSms::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }

            foreach (RSUpdate::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }

            foreach (RSNominee::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }

            foreach (RSFormForSendPoints::reward_system_admin_fields() as $setting) {
                if (isset($setting['newids']) && isset($setting['std'])) {
                    delete_option($setting['newids']);
                    add_option($setting['newids'], $setting['std']);
                }
            }

            delete_option('rewards_dynamic_rule_manual');

            delete_transient('woocommerce_cache_excluded_uris');

            $redirect = esc_url_raw(add_query_arg(array('saved' => 'true')));
            if (isset($_POST['reset'])) {
                wp_safe_redirect($redirect);
                exit;
            }
        }
    }

    public static function rs_translate_file() {
        load_plugin_textdomain('rewardsystem', false, dirname(plugin_basename(__FILE__)) . '/languages');
    }

    public static function default_value_for_earning_and_redeem_points() {
        add_option('rs_earn_point', '1');
        add_option('rs_earn_point_value', '1');
        add_option('rs_redeem_point', '1');
        add_option('rs_redeem_point_value', '1');
        add_option('rs_redeem_point_for_cash_back', '1');
        add_option('rs_redeem_point_value_for_cash_back', '1');
    }

    public static function check_banning_type($userid) {
        $earning = get_option('rs_enable_banning_users_earning_points');
        $redeeming = get_option('rs_enable_banning_users_redeeming_points');


        $banned_user_list = get_option('rs_banned_users_list');
        if (is_array($banned_user_list)) {
            $banned_user_list = $banned_user_list;
        } else {
            $banned_user_list = explode(',', $banned_user_list);
        }

        if (in_array($userid, (array) $banned_user_list)) {
            if ($earning == 'no' && $redeeming == 'no') {
                return "no_banning";
            }

            if ($earning == 'no' && $redeeming == 'yes') {

                return 'redeemingonly';
            }
            if ($earning == 'yes' && $redeeming == 'no') {
                return 'earningonly';
            }
            if ($earning == 'yes' && $redeeming == 'yes') {
                return 'both';
            }
        } else {
            $getarrayofuserdata = get_userdata(get_current_user_id());
            $banninguserrole = get_option('rs_banning_user_role');
            if (in_array(isset($getarrayofuserdata->roles[0]) ? $getarrayofuserdata->roles[0] : '0', (array) $banninguserrole)) {
                if ($earning == 'no' && $redeeming == 'no') {
                    return "no_banning";
                }

                if ($earning == 'no' && $redeeming == 'yes') {

                    return 'redeemingonly';
                }
                if ($earning == 'yes' && $redeeming == 'no') {
                    $banned_user_list = get_option('rs_banned_users_list');
                    return 'earningonly';
                }
                if ($earning == 'yes' && $redeeming == 'yes') {
                    $banned_user_list = get_option('rs_banned_users_list');
                    return 'both';
                }
            }
        }
    }

}

$obj = new FPRewardSystem();

$obj->rewardgateway();
