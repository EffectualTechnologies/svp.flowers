<?php

class RSFunctionForReportsInCsv {

    public function __construct() {


        add_action('admin_enqueue_scripts', array($this, 'wp_enqueqe_for_datepicker'));

        add_action('enqueuescriptforadmin', array($this, 'datepickerenqueue_report'));

        add_action('woocommerce_admin_field_rs_select_users_report_in_csv', array($this, 'selected_users_report_in_csv'));

        add_action('admin_footer', array($this, 'select_custom_date_reports'));

        add_action('admin_head', array($this, 'export_user_reports_selection'));

        add_action('woocommerce_admin_field_export_reports', array($this, 'reward_system_page_customization_reports'));

        add_action('admin_head', array($this, 'export_report_points_selection'));

        add_action('wp_ajax_rs_export_report_start_date', array($this, 'export_report_start_date_callback'));

        add_action('wp_ajax_rs_export_report_end_date', array($this, 'export_report_end_date_callback'));

        add_action('wp_ajax_rs_export_report_option', array($this, 'export_option_report_selected_callback'));

        add_action('wp_ajax_rs_list_of_users_to_export_report', array($this, 'selected_users_for_exporting_csv_report_callback'));

        add_action('wp_ajax_rs_selected_date_option_report', array($this, 'export_option_selected_date_report_callback'));

        add_action('wp_ajax_rs_selected_options_for_export_report', array($this, 'export_points_points_based_report_callback'));

        add_action('admin_head', array($this, 'ajax_to_export_user_data'));

        add_action('wp_ajax_rs_export_report_in_csv', array($this, 'process_ajax_to_split_user'));

        add_action('wp_ajax_rssplitusertoexportreport', array($this, 'process_ajax_to_export_report'));
    }

    public static function selected_users_report_in_csv() {
        global $woocommerce;
        if ((float) $woocommerce->version <= (float) ('2.2.0')) {
            ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_export_users_report_list"><?php _e('Select the users that you wish to Export Reward Points', 'rewardsystem'); ?></label>
                </th>
                <td>
                    <select name="rs_export_users_report_list" id="rs_export_users_report_list" style="width:550px;" multiple="multiple" class="short rs_export_users_report_list">
                        <?php
                        $json_ids = array();
                        $getuser = get_option('rs_export_users_report_list');
                        if ($getuser != "") {
                            $listofuser = $getuser;
                            if (!is_array($listofuser)) {
                                $userids = array_filter(array_map('absint', (array) explode(',', $listofuser)));
                            } else {
                                $userids = $listofuser;
                            }

                            foreach ($userids as $userid) {
                                $user = get_user_by('id', $userid);
                                $json_ids[$user->ID] = esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email);
                            }
                            echo esc_attr(json_encode($json_ids));
                        }
                        ?>
                    </select>
                </td>
            </tr>
        <?php } else { ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_export_users_report_list"><?php _e('Select the users that you wish to Export Reward Points', 'rewardsystem'); ?></label>
                </th>
                <td>
                    <input type="hidden" class="wc-customer-search" name="rs_export_users_report_list" id="rs_export_users_report_list" data-multiple="true" data-placeholder="<?php _e('Search for a customer&hellip;', 'rewardsystem'); ?>" data-selected="<?php
                    $json_ids = array();
                    $getuser = get_option('rs_export_users_report_list');
                    if ($getuser != "") {
                        $listofuser = $getuser;
                        if (!is_array($listofuser)) {
                            $userids = array_filter(array_map('absint', (array) explode(',', $listofuser)));
                        } else {
                            $userids = $listofuser;
                        }

                        foreach ($userids as $userid) {
                            $user = get_user_by('id', $userid);
                            $json_ids[$user->ID] = esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email);
                        }echo esc_attr(json_encode($json_ids));
                    }
                    ?>" value="<?php echo implode(',', array_keys($json_ids)); ?>" data-allow_clear="true" />
                </td>
            </tr>
            <?php
        }
    }

    public static function select_custom_date_reports() {

        global $woocommerce;
        if (isset($_GET['tab'])) {
            if ($_GET['tab'] == 'rewardsystem_reports_in_csv') {
                echo RSJQueryFunction::rs_common_ajax_function_to_select_user('rs_export_users_report_list');
                ?>                
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        jQuery('#rs_point_export_report_start_date').datepicker({dateFormat: 'yy-mm-dd'});
                        jQuery('#rs_point_export_report_end_date').datepicker({dateFormat: 'yy-mm-dd'});
                        jQuery('#rs_point_export_report_start_date').change(function () {
                            var export_report_start_date = jQuery('#rs_point_export_report_start_date').val();
                            var export_report_param_start_date = {
                                action: "rs_export_report_start_date",
                                export_report_startdate: export_report_start_date,
                            };
                            jQuery.post('<?php echo admin_url('admin-ajax.php') ?>', export_report_param_start_date, function (response) {
                                console.log('Got this from the server: ' + response);
                            });
                        });
                        jQuery('#rs_point_export_report_end_date').change(function () {
                            var export_report_end_date = jQuery('#rs_point_export_report_end_date').val();
                            var export_report_param_end_date = {
                                action: "rs_export_report_end_date",
                                export_report_enddate: export_report_end_date,
                            };
                            jQuery.post('<?php echo admin_url('admin-ajax.php') ?>', export_report_param_end_date, function (response) {
                                console.log('Got this from the server: ' + response);
                            });
                        });
                    });
                </script>
                <?php
            }
        }
    }

    public static function export_user_reports_selection() {
        global $woocommerce;
        if (isset($_GET['page'])) {
            if (($_GET['page'] == 'rewardsystem_callback')) {
                if (isset($_GET['tab'])) {
                    if (($_GET['tab'] == 'rewardsystem_reports_in_csv')) {
                        ?>
                        <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                            <script type="text/javascript">
                                jQuery(document).ready(function () {
                                    jQuery('#rs_export_users_report_list').chosen();
                                });
                            </script>
                            <?php
                        }
                    }
                }
            }
        }
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                if ((jQuery('input[name=rs_export_user_report_option]:checked').val()) === '2') {
                    jQuery('#rs_export_users_report_list').parent().parent().show();
                } else {
                    jQuery('#rs_export_users_report_list').parent().parent().hide();
                }
                jQuery('input[name=rs_export_user_report_option]:radio').change(function () {
                    jQuery('#rs_export_users_report_list').parent().parent().toggle();
                });
            });
            jQuery(document).ready(function () {
                if ((jQuery('input[name=rs_export_report_date_option]:checked').val()) === '2') {
                    jQuery('#rs_point_export_report_start_date').parent().parent().show();
                    jQuery('#rs_point_export_report_end_date').parent().parent().show();
                } else {
                    jQuery('#rs_point_export_report_start_date').parent().parent().hide();
                    jQuery('#rs_point_export_report_end_date').parent().parent().hide();
                }
                jQuery('input[name=rs_export_report_date_option]:radio').change(function () {
                    jQuery('#rs_point_export_report_start_date').parent().parent().toggle();
                    jQuery('#rs_point_export_report_end_date').parent().parent().toggle();
                });
            });
        </script>
        <?php
    }

    public static function ajax_to_export_user_data() {
        if (isset($_GET['tab'])) {
            if ($_GET['tab'] == 'rewardsystem_reports_in_csv') {
                ?>
                <script type="text/javascript">
                    jQuery(document).ready(function () {
                        jQuery('#rs_export_user_points_report_csv1').hide();
                        jQuery('.gif_rs_sumo_reward_button_social_report_csv').css('display', 'none');
                        jQuery('#rs_export_user_points_report_csv').click(function () {
                            jQuery('.gif_rs_sumo_reward_button_social_report_csv').css('display', 'inline-block');
                            var usertype = jQuery("input:radio[name=rs_export_user_report_option]:checked").val();
                            var selecteduser = jQuery("#rs_export_users_report_list").val();
                            var dataparam = ({
                                action: 'rs_export_report_in_csv',
                                usertype: usertype,
                                selecteduser: selecteduser
                            });
                            function getDataforreport(id) {
                                return jQuery.ajax({
                                    type: 'POST',
                                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                    data: ({
                                        action: "rssplitusertoexportreport",
                                        ids: id,
                                        usertype: usertype,
                                    }),
                                    success: function (response) {
                                        response = jQuery.trim(response);
                                        if (response === 'success') {
                                            jQuery('.gif_rs_sumo_reward_button_social_report_csv').css('display', 'none');
                                            jQuery('#rs_export_user_points_report_csv1').trigger('click');
                                        }
                                    },
                                    dataType: 'json',
                                    async: false
                                })
                            }
                            jQuery.post('<?php echo admin_url('admin-ajax.php') ?>', dataparam,
                                    function (response) {
                                        console.log(response);
                                        if (response != 'success') {
                                            var j = 1;
                                            var i, j, temparray, chunk = 10;
                                            for (i = 0, j = response.length; i < j; i += chunk) {
                                                temparray = response.slice(i, i + chunk);
                                                getDataforreport(temparray);
                                                console.log(temparray);
                                            }
                                            jQuery.when(getDataforreport("")).done(function (a1) {
                                                console.log('Ajax Done Successfully');
                                            });
                                        }
                                    }, 'json');
                        });
                    });
                </script>
                <?php
            }
        }
    }

    public static function process_ajax_to_split_user() {
        if (isset($_POST['usertype'])) {
            delete_option('rs_export_report');
            delete_option('heading');
            if ($_POST['usertype'] == '1') {
                $alluser = get_users();
                foreach ($alluser as $users) {
                    $userid[] = $users->ID;
                }
                echo json_encode($userid);
            } else if ($_POST['usertype'] == '2') {
                $selecteduser = $_POST['selecteduser'];
                if (is_array($selecteduser)) {
                    $selecteduser = $selecteduser;
                } else {
                    $selecteduser = explode(',', $selecteduser);
                }
                foreach ($selecteduser as $users) {
                    $userid[] = $users;
                }
                echo json_encode($userid);
            }
        }
        exit();
    }

    public static function process_ajax_to_export_report() {
        if (isset($_POST['ids']) && !empty($_POST['ids'])) {
            global $wpdb;
            $table_name2 = $wpdb->prefix . 'rsrecordpoints';
            $export_type_selection = $_POST['usertype'];
            $export_date_selection = get_option('selected_date_type_report');
            $export_all_points_as_csv = get_option('export_all_points_report_to_csv');
            $export_earned_points_as_csv = get_option('export_earning_points_report_to_csv');
            $export_redeemed_points_as_csv = get_option('export_redeeming_points_report_to_csv');
            $import_export_points_heading = '';
            $arraylist = '';
            $current_user_earned_points_list = '';
            $current_user_redeemed_points_list = '';
            $export_total_points = '';
            $export_earned_points = '';
            $export_redeemed_points = '';
            if ($export_type_selection == '1') {
                if ($export_date_selection == '1') {
                    $userid = $_POST['ids'];
                    foreach ($userid as $users) {
                        $user_id = $users;
                        $overall_log_dummy = array(
                            array(
                                'orderid' => '',
                                'userid' => $user_id,
                                'points_earned_order' => '0',
                                'points_redeemed' => '0',
                                'points_value' => '0',
                                'before_order_points' => '0',
                                'totalpoints' => RSPointExpiry::get_sum_of_total_earned_points($user_id) != '' ? RSPointExpiry::get_sum_of_total_earned_points($user_id) : '0',
                                'date' => '0',
                                'rewarder_for' => '',
                                'rewarder_for_frontend' => ''
                        ));
                        $overall_log = $wpdb->get_results("SELECT * FROM $table_name2 WHERE userid = $user_id", ARRAY_A);
                        $overall_log = $overall_log + (array) get_user_meta($user_id, '_my_points_log', true);

                        ksort($overall_log, SORT_NUMERIC);

                        $overall_log = is_array($overall_log) == true ? $overall_log : $overall_log_dummy;

                        $overall_log = array_values(array_filter($overall_log));
                        if (is_array($overall_log)) {
                            foreach ($overall_log as $separate_log) {
                                if (isset($separate_log['earnedpoints'])) {
                                    if (isset($separate_log['earnedpoints'])) {
                                        $loguserid = $separate_log['userid'];
                                        @$current_user_earned_points_list[$loguserid] += $separate_log['earnedpoints'];
                                        $current_user_earned_points_username[$separate_log['userid']] = $separate_log['userid'];
                                    }
                                    if (isset($separate_log['redeempoints'])) {
                                        @$current_user_redeemed_points_list[$separate_log['userid']] += $separate_log['redeempoints'];
                                    }
                                    if (isset($separate_log['totalpoints'])) {
                                        @$current_user_total_points[$separate_log['userid']] = $separate_log['totalpoints'];
                                    }
                                    if (isset($separate_log['userid'])) {
                                        @$overall_user_points[$separate_log['userid']] = array($current_user_earned_points_list[$separate_log['userid']], $current_user_redeemed_points_list[$separate_log['userid']], $current_user_total_points[$separate_log['userid']]);
                                    }
                                } else {
                                    if (isset($separate_log['points_earned_order'])) {
                                        //  if(isset($separate_log['userid'])) {
                                        $loguserid = $separate_log['userid'];
                                        @$current_user_earned_points_list[$loguserid] += $separate_log['points_earned_order'];
                                        $current_user_earned_points_username[$separate_log['userid']] = $separate_log['userid'];
                                    }
                                    if (isset($separate_log['points_redeemed'])) {
                                        @$current_user_redeemed_points_list[$separate_log['userid']] += $separate_log['points_redeemed'];
                                    }
                                    if (isset($separate_log['totalpoints'])) {
                                        @$current_user_total_points[$separate_log['userid']] = $separate_log['totalpoints'];
                                    }
                                    if (isset($separate_log['userid'])) {
                                        @$overall_user_points[$separate_log['userid']] = array($current_user_earned_points_list[$separate_log['userid']], $current_user_redeemed_points_list[$separate_log['userid']], $current_user_total_points[$separate_log['userid']]);
                                    }
                                }
                            }
                        }
                    }

                    if ((is_array($current_user_earned_points_list)) && (is_array($current_user_redeemed_points_list)) && (is_array($current_user_total_points))) {
                        if (is_array($overall_user_points)) {

                            foreach ($overall_user_points as $points_user_id => $users_all_points) {


                                $export_earned_points = $users_all_points[0];
                                $export_redeemed_points = $users_all_points[1];
                                $export_total_points = $users_all_points[2];


                                $allpoints_username_selection = get_user_by('id', $points_user_id);
                                $allpoints_username = $allpoints_username_selection->user_login;
                                if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '0')) {
                                    $import_export_points_heading = "User Name,Earned Points,Redeemed Points,Total Points" . "\n";
                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                    $earning_points_new = round($export_earned_points, $roundofftype);
                                    $redeeming_points_new = round($export_redeemed_points, $roundofftype);
                                    $total_points_new = round($export_total_points, $roundofftype);
                                    $arraylist[] = array($allpoints_username, $earning_points_new, $redeeming_points_new, $total_points_new);
                                    update_option('heading', $import_export_points_heading);
                                }
                                if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '1')) {
                                    $import_export_points_heading = "User Name, Redeemed Points" . "\n";
                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                    $redeeming_points_new = round($export_redeemed_points, $roundofftype);
                                    $arraylist[] = array($allpoints_username, $redeeming_points_new);
                                    update_option('heading', $import_export_points_heading);
                                }
                                if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '0')) {
                                    $import_export_points_heading = "User Name,Earned Points" . "\n";
                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                    $earning_points_new = round($export_earned_points, $roundofftype);
                                    $arraylist[] = array($allpoints_username, $earning_points_new);
                                    update_option('heading', $import_export_points_heading);
                                }
                                if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '1')) {
                                    $import_export_points_heading = "User Name, Earned Points, Redeemed Points" . "\n";
                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                    $earning_points_new = round($export_earned_points, $roundofftype);
                                    $redeeming_points_new = round($export_redeemed_points, $roundofftype);
                                    $arraylist[] = array($allpoints_username, $earning_points_new, $redeeming_points_new);
                                    update_option('heading', $import_export_points_heading);
                                }
                                if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '0')) {
                                    $import_export_points_heading = "User Name, Total Points" . "\n";
                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                    $total_points_new = round($export_total_points, $roundofftype);
                                    $arraylist[] = array($allpoints_username, $total_points_new);
                                    update_option('heading', $import_export_points_heading);
                                }
                                if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '1')) {
                                    $import_export_points_heading = "User Name,Redeemed Points, Total Points" . "\n";
                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                    $redeeming_points_new = round($export_redeemed_points, $roundofftype);
                                    $total_points_new = round($export_total_points, $roundofftype);
                                    $arraylist[] = array($allpoints_username, $redeeming_points_new, $total_points_new);
                                    update_option('heading', $import_export_points_heading);
                                }
                                if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '0')) {
                                    $import_export_points_heading = "User Name,Earned Points,Total Points" . "\n";
                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                    $earning_points_new = round($export_earned_points, $roundofftype);
                                    $total_points_new = round($export_total_points, $roundofftype);
                                    $arraylist[] = array($allpoints_username, $earning_points_new, $total_points_new);
                                    update_option('heading', $import_export_points_heading);
                                }
                                if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '1')) {
                                    $import_export_points_heading = "User Name,Earned Points,Redeemed Points,Total Points" . "\n";
                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                    $earning_points_new = round($export_earned_points, $roundofftype);
                                    $redeeming_points_new = round($export_redeemed_points, $roundofftype);
                                    $total_points_new = round($export_total_points, $roundofftype);
                                    $arraylist[] = array($allpoints_username, $earning_points_new, $redeeming_points_new, $total_points_new);
                                    update_option('heading', $import_export_points_heading);
                                }
                            }
                            $olddata = (array) get_option('rs_export_report');
                            $currentdata = $arraylist;
                            $mergedata = array_merge($olddata, $currentdata);
                            update_option('rs_export_report', $mergedata);
                        }
                    }
                } else {
                    $userid = $_POST['ids'];
                    foreach ($userid as $users) {
                        $user_id = $users;
                        $overall_log_dummy = array(
                            array(
                                'orderid' => '',
                                'userid' => $user_id,
                                'points_earned_order' => '0',
                                'points_redeemed' => '0',
                                'points_value' => '0',
                                'before_order_points' => '0',
                                'totalpoints' => RSPointExpiry::get_sum_of_total_earned_points($user_id) != '' ? RSPointExpiry::get_sum_of_total_earned_points($user_id) : '0',
                                'date' => date('Y-m-d'),
                                'rewarder_for' => '',
                                'rewarder_for_frontend' => ''
                        ));
                        $overall_log = $wpdb->get_results("SELECT * FROM $table_name2 WHERE userid = $user_id", ARRAY_A);
                        $overall_log = $overall_log + (array) get_user_meta($user_id, '_my_points_log', true);

                        ksort($overall_log, SORT_NUMERIC);
                        $overall_log = is_array($overall_log) == true ? $overall_log : $overall_log_dummy;

                        if (is_array($overall_log)) {
                            foreach ($overall_log as $separate_log) {
                                if (isset($separate_log['earnedpoints'])) {
                                    if (isset($separate_log['earneddate'])) {
                                        $maindatecreate = date_create($separate_log['earneddate']);
                                        $formated_date = date_format($maindatecreate, "Y-m-d");
                                    }
                                    if (isset($separate_log['earnedpoints'])) {
                                        @$current_user_earned_points_list[$separate_log['userid']][$formated_date] += $separate_log['earnedpoints'];
                                        @$current_user_earned_points_username[$separate_log['userid']] = $separate_log['userid'];
                                    }
                                    if (isset($separate_log['redeempoints'])) {
                                        @$current_user_redeemed_points_list[$separate_log['userid']][$formated_date] += $separate_log['redeempoints'];
                                    }
                                    if (isset($separate_log['totalpoints'])) {
                                        $current_user_total_points[$separate_log['userid']][$formated_date] = $separate_log['totalpoints'];
                                    }
                                    if (isset($separate_log['userid'])) {
                                        @$overall_user_points[$separate_log['userid']][$formated_date] = array($current_user_earned_points_list[$separate_log['userid']][$formated_date], $current_user_redeemed_points_list[$separate_log['userid']][$formated_date], $current_user_total_points[$separate_log['userid']][$formated_date]);
                                    }
                                } else {
                                    if (isset($separate_log['date'])) {
                                        $maindatecreate = date_create($separate_log['date']);
                                        $formated_date = date_format($maindatecreate, "Y-m-d");
                                    }
                                    if (isset($separate_log['points_earned_order'])) {
                                        @$current_user_earned_points_list[$separate_log['userid']][$formated_date] += $separate_log['points_earned_order'];
                                        @$current_user_earned_points_username[$separate_log['userid']] = $separate_log['userid'];
                                    }
                                    if (isset($separate_log['points_redeemed'])) {
                                        @$current_user_redeemed_points_list[$separate_log['userid']][$formated_date] += $separate_log['points_redeemed'];
                                    }
                                    if (isset($separate_log['totalpoints'])) {
                                        $current_user_total_points[$separate_log['userid']][$formated_date] = $separate_log['totalpoints'];
                                    }
                                    if (isset($separate_log['userid'])) {
                                        @$overall_user_points[$separate_log['userid']][$formated_date] = array($current_user_earned_points_list[$separate_log['userid']][$formated_date], $current_user_redeemed_points_list[$separate_log['userid']][$formated_date], $current_user_total_points[$separate_log['userid']][$formated_date]);
                                    }
                                }
                            }
                        }
                    }
                    if ((is_array($current_user_earned_points_list)) && (is_array($current_user_redeemed_points_list)) && (is_array($current_user_total_points))) {
                        if (is_array($overall_user_points)) {

                            foreach ($overall_user_points as $points_user_id => $users_all_points) {

                                $allpoints_username_selection = get_user_by('id', $points_user_id);
                                $allpoints_username[$points_user_id] = $allpoints_username_selection->user_login;
                                foreach ($users_all_points as $pointsdate => $pointsnewvalue) {
                                    $date_from_log = $pointsdate;
                                    $converted_time = strtotime($date_from_log);
                                    $selected_start_date = get_option('selected_report_start_date');
                                    $selected_start_time = '00:00:00';
                                    $selected_start_date_time = $selected_start_date . ' ' . $selected_start_time;
                                    $converted_start_time = strtotime($selected_start_date_time);
                                    $selected_end_date = get_option('selected_report_end_date');
                                    $selected_end_time = '23:59:00';
                                    $selected_end_date_time = $selected_end_date . ' ' . $selected_end_time;
                                    $converted_end_time = strtotime($selected_end_date_time);
                                    if ($converted_start_time <= $converted_time && $converted_end_time >= $converted_time) {
                                        @$export_earned_points[$points_user_id] += $pointsnewvalue[0];
                                        @$export_redeemed_points[$points_user_id] += $pointsnewvalue[1];
                                        $export_total_points[$points_user_id] = $pointsnewvalue[2];

                                        if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '0')) {
                                            $import_export_points_heading = "User Name,Earned Points,Redeemed Points,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            $total_points_new = round($export_total_points[$points_user_id], $roundofftype);
                                            $arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new, $redeeming_points_new, $total_points_new);
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '1')) {
                                            $import_export_points_heading = "User Name,Redeemed Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            $arraylist[] = array($allpoints_username[$points_user_id], $redeeming_points_new);
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '0')) {
                                            $import_export_points_heading = "User Name,Earned Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            $arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new);
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '1')) {
                                            $import_export_points_heading = "User Name,Earned Points,Redeemed Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            $arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new, $redeeming_points_new);
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '0')) {
                                            $import_export_points_heading = "User Name,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $total_points_new = round($export_total_points[$points_user_id], $roundofftype);
                                            $arraylist[] = array($allpoints_username[$points_user_id], $total_points_new);
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '1')) {
                                            $import_export_points_heading = "User Name,Redeemed Points,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            $total_points_new = round($export_total_points[$points_user_id], $roundofftype);
                                            $arraylist[] = array($allpoints_username[$points_user_id], $redeeming_points_new, $total_points_new);
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '0')) {
                                            $import_export_points_heading = "User Name,Earned Points,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            $total_points_new = round($export_total_points[$points_user_id], $roundofftype);
                                            $arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new, $total_points_new);
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '1')) {
                                            $import_export_points_heading = "User Name,Earned Points,Redeemed Points,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            $total_points_new = round($export_total_points[$points_user_id], $roundofftype);
                                            $arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new, $redeeming_points_new, $total_points_new);
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                    } else {
                                        if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '0')) {
                                            $import_export_points_heading = "User Name,Earned Points,Redeemed Points,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            $total_points_new = round($export_total_points[$points_user_id], $roundofftype);
                                            @$arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new != '' ? $earning_points_new : '0', $redeeming_points_new != '' ? $redeeming_points_new : '0', $total_points_new != '' ? $total_points_new : '0');
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '1')) {
                                            $import_export_points_heading = "User Name,Redeemed Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            @$arraylist[] = array($allpoints_username[$points_user_id], $redeeming_points_new != '' ? $redeeming_points_new : '0');
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '0')) {
                                            $import_export_points_heading = "User Name,Earned Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            @$arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new != '' ? $earning_points_new : '0');
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '1')) {
                                            $import_export_points_heading = "User Name,Earned Points,Redeemed Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            @$arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new != '' ? $earning_points_new : '0', $redeeming_points_new != '' ? $redeeming_points_new : '0');
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '0')) {
                                            $import_export_points_heading = "User Name,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $total_points_new = round($export_total_points[$points_user_id], $roundofftype);
                                            @$arraylist[] = array($allpoints_username[$points_user_id], $total_points_new != '' ? $total_points_new : '0');
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '1')) {
                                            $import_export_points_heading = "User Name,Redeemed Points,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            $total_points_new = round($export_total_points[$points_user_id], $roundofftype);
                                            @$arraylist[] = array($allpoints_username[$points_user_id], $redeeming_points_new != '' ? $redeeming_points_new : '0', $total_points_new != '' ? $total_points_new : '0');
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '0')) {
                                            $import_export_points_heading = "User Name,Earned Points,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            $total_points_new = round($export_total_points[$points_user_id], $roundofftype);
                                            @$arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new != '' ? $earning_points_new : '0', $total_points_new != '' ? $total_points_new : '0');
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '1')) {
                                            $import_export_points_heading = "User Name,Earned Points,Redeemed Points,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            $total_points_new = round($export_total_points[$points_user_id], $roundofftype);
                                            @$arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new != '' ? $earning_points_new : '0', $redeeming_points_new != '' ? $redeeming_points_new : '0', $total_points_new != '' ? $total_points_new : '0');
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                    }
                                }
                            }
                            $arraylist = array_values($temp_array);
                            $olddata = (array) get_option('rs_export_report');
                            $currentdata = $arraylist;
                            $mergedata = array_merge($olddata, $currentdata);
                            update_option('rs_export_report', $mergedata);
                        }
                    }
                }
            } else {
                if ($export_date_selection == '1') {
                    $list_of_users_to_export_csv = get_option('rs_selected_user_list_export_report');
                    $userid = $_POST['ids'];
                    $implodeid = implode(',', $userid);
                    foreach ($userid as $users) {
                        $user_id = $users;
                        $collectionuserid[] = $users;
                        $overall_log_dummy = array(
                            array(
                                'orderid' => '',
                                'userid' => $user_id,
                                'points_earned_order' => '0',
                                'points_redeemed' => '0',
                                'points_value' => '0',
                                'before_order_points' => '0',
                                'totalpoints' => RSPointExpiry::get_sum_of_total_earned_points($user_id) != '' ? RSPointExpiry::get_sum_of_total_earned_points($user_id) : '0',
                                'date' => @date(),
                                'rewarder_for' => '',
                                'rewarder_for_frontend' => ''
                        ));
                        $overall_log = $wpdb->get_results("SELECT * FROM $table_name2 WHERE userid = $user_id", ARRAY_A);
                        $overall_log = $overall_log + (array) get_user_meta($user_id, '_my_points_log', true);
                        ksort($overall_log, SORT_NUMERIC);
                        $overall_log = is_array($overall_log) == true ? $overall_log : $overall_log_dummy;
                        if (is_array($overall_log)) {
                            foreach ($overall_log as $separate_log) {
                                if (isset($separate_log['earnedpoints'])) {
                                    if (isset($separate_log['earnedpoints'])) {
                                        @$current_user_earned_points_list[$separate_log['userid']] += $separate_log['earnedpoints'];
                                        $current_user_earned_points_username[$separate_log['userid']] = $separate_log['userid'];
                                    }
                                    if (isset($separate_log['redeempoints'])) {
                                        @$current_user_redeemed_points_list[$separate_log['userid']] += $separate_log['redeempoints'];
                                    }
                                    if (isset($separate_log['totalpoints'])) {
                                        $current_user_total_points[$separate_log['userid']] = $separate_log['totalpoints'];
                                    }
                                    if (isset($separate_log['userid'])) {
                                        $overall_user_points[$separate_log['userid']] = array($current_user_earned_points_list[$separate_log['userid']], $current_user_redeemed_points_list[$separate_log['userid']], $current_user_total_points[$separate_log['userid']]);
                                    }
                                } else {
                                    if (isset($separate_log['points_earned_order'])) {
                                        @$current_user_earned_points_list[$separate_log['userid']] += $separate_log['points_earned_order'];
                                        $current_user_earned_points_username[$separate_log['userid']] = $separate_log['userid'];
                                    }
                                    if (isset($separate_log['points_redeemed'])) {
                                        @$current_user_redeemed_points_list[$separate_log['userid']] += $separate_log['points_redeemed'];
                                    }
                                    if (isset($separate_log['totalpoints'])) {
                                        $current_user_total_points[$separate_log['userid']] = $separate_log['totalpoints'];
                                    }
                                    if (isset($separate_log['userid'])) {
                                        $overall_user_points[$separate_log['userid']] = array($current_user_earned_points_list[$separate_log['userid']], $current_user_redeemed_points_list[$separate_log['userid']], $current_user_total_points[$separate_log['userid']]);
                                    }
                                }
                            }
                        }
                    }
                    if ((is_array($current_user_earned_points_list)) && (is_array($current_user_redeemed_points_list)) && (is_array($current_user_total_points))) {
                        if (is_array($overall_user_points)) {
                            foreach ($overall_user_points as $points_user_id => $users_all_points) {
                                $export_earned_points = $users_all_points[0];
                                $export_redeemed_points = $users_all_points[1];
                                $export_total_points = $users_all_points[2];
                                $allpoints_username_selection = get_user_by('id', $points_user_id);
                                $allpoints_username = $allpoints_username_selection->user_login;
                                if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '0')) {
                                    $import_export_points_heading = "User Name,Earned Points,Redeemed Points,Total Points" . "\n";
                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                    $earning_points_new = round($export_earned_points, $roundofftype);
                                    $redeeming_points_new = round($export_redeemed_points, $roundofftype);
                                    $total_points_new = round($export_total_points, $roundofftype);
                                    $arraylist[] = array($allpoints_username, $earning_points_new, $redeeming_points_new, $total_points_new);
                                    update_option('heading', $import_export_points_heading);
                                }
                                if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '1')) {
                                    $import_export_points_heading = "User Name, Redeemed Points" . "\n";
                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                    $redeeming_points_new = round($export_redeemed_points, $roundofftype);
                                    $arraylist[] = array($allpoints_username, $redeeming_points_new);
                                    update_option('heading', $import_export_points_heading);
                                }
                                if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '0')) {
                                    $import_export_points_heading = "User Name, Earned Points" . "\n";
                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                    $earning_points_new = round($export_earned_points, $roundofftype);
                                    $arraylist[] = array($allpoints_username, $earning_points_new);
                                    update_option('heading', $import_export_points_heading);
                                }
                                if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '1')) {
                                    $import_export_points_heading = "User Name, Earned Points, Redeemed Points" . "\n";
                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                    $earning_points_new = round($export_earned_points, $roundofftype);
                                    $redeeming_points_new = round($export_redeemed_points, $roundofftype);
                                    $arraylist[] = array($allpoints_username, $earning_points_new, $redeeming_points_new);
                                    update_option('heading', $import_export_points_heading);
                                }
                                if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '0')) {
                                    $import_export_points_heading = "User Name, Total Points" . "\n";
                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                    $total_points_new = round($export_total_points, $roundofftype);
                                    $arraylist[] = array($allpoints_username, $total_points_new);
                                    update_option('heading', $import_export_points_heading);
                                }
                                if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '1')) {
                                    $import_export_points_heading = "User Name,Redeemed Points, Total Points" . "\n";
                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                    $redeeming_points_new = round($export_redeemed_points, $roundofftype);
                                    $total_points_new = round($export_total_points, $roundofftype);
                                    $arraylist[] = array($allpoints_username, $redeeming_points_new, $total_points_new);
                                    update_option('heading', $import_export_points_heading);
                                }
                                if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '0')) {
                                    $import_export_points_heading = "User Name,Earned Points,Total Points" . "\n";
                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                    $earning_points_new = round($export_earned_points, $roundofftype);
                                    $total_points_new = round($export_total_points, $roundofftype);
                                    $arraylist[] = array($allpoints_username, $earning_points_new, $total_points_new);
                                    update_option('heading', $import_export_points_heading);
                                }
                                if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '1')) {
                                    $import_export_points_heading = "User Name,Earned Points,Redeemed Points,Total Points" . "\n";
                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                    $earning_points_new = round($export_earned_points, $roundofftype);
                                    $redeeming_points_new = round($export_redeemed_points, $roundofftype);
                                    $total_points_new = round($export_total_points, $roundofftype);
                                    $arraylist[] = array($allpoints_username, $earning_points_new, $redeeming_points_new, $total_points_new);
                                    update_option('heading', $import_export_points_heading);
                                }
                            }
                            $olddata = (array) get_option('rs_export_report');
                            $currentdata = $arraylist;
                            $mergedata = array_merge($olddata, $currentdata);
                            update_option('rs_export_report', $mergedata);
                        }
                    }
                } else {
                    $list_of_users_to_export_csv = get_option('rs_selected_user_list_export_report');
                    $userid = $_POST['ids'];
                    foreach ($userid as $users) {
                        $user_id = $users;
                        $collectionuserid[] = $users;
                        //if (in_array($user_id, $list_of_users_to_export_csv != '' ? $list_of_users_to_export_csv : $collectionuserid)) {
                        $overall_log_dummy = array(
                            array(
                                'orderid' => '',
                                'userid' => $user_id,
                                'points_earned_order' => '0',
                                'points_redeemed' => '0',
                                'points_value' => '0',
                                'before_order_points' => '0',
                                'totalpoints' => '0',
                                'date' => date('Y-m-d'),
                                'rewarder_for' => '',
                                'rewarder_for_frontend' => ''
                        ));
                        $overall_log = $wpdb->get_results("SELECT * FROM $table_name2 WHERE userid = $user_id", ARRAY_A);
                        $overall_log = $overall_log + (array) get_user_meta($user_id, '_my_points_log', true);

                        ksort($overall_log, SORT_NUMERIC);
                        $overall_log = is_array($overall_log) == true ? $overall_log : $overall_log_dummy;
                        if (is_array($overall_log)) {
                            foreach ($overall_log as $separate_log) {
                                if (isset($separate_log['earnedpoints'])) {
                                    if (isset($separate_log['earneddate'])) {
                                        $maindatecreate = date_create($separate_log['earneddate']);
                                        $formated_date = date_format($maindatecreate, "Y-m-d");
                                    }

                                    if (isset($separate_log['earnedpoints'])) {
                                        @$current_user_earned_points_list[$separate_log['userid']][$formated_date] += $separate_log['earnedpoints'];
                                        @$current_user_earned_points_username[$separate_log['userid']] = $separate_log['userid'];
                                    }
                                    if (isset($separate_log['redeempoints'])) {
                                        @$current_user_redeemed_points_list[$separate_log['userid']][$formated_date] += $separate_log['redeempoints'];
                                    }
                                    if (isset($separate_log['totalpoints'])) {
                                        $current_user_total_points[$separate_log['userid']][$formated_date] = $separate_log['totalpoints'];
                                    }
                                    if (isset($separate_log['userid'])) {
                                        @$overall_user_points[$separate_log['userid']][$formated_date] = array($current_user_earned_points_list[$separate_log['userid']][$formated_date], $current_user_redeemed_points_list[$separate_log['userid']][$formated_date], $current_user_total_points[$separate_log['userid']][$formated_date]);
                                    }
                                } else {
                                    if (isset($separate_log['date'])) {
                                        $maindatecreate = date_create($separate_log['date']);
                                        $formated_date = date_format($maindatecreate, "Y-m-d");
                                    }

                                    if (isset($separate_log['points_earned_order'])) {
                                        @$current_user_earned_points_list[$separate_log['userid']][$formated_date] += $separate_log['points_earned_order'];
                                        @$current_user_earned_points_username[$separate_log['userid']] = $separate_log['userid'];
                                    }
                                    if (isset($separate_log['points_redeemed'])) {
                                        @$current_user_redeemed_points_list[$separate_log['userid']][$formated_date] += $separate_log['points_redeemed'];
                                    }
                                    if (isset($separate_log['totalpoints'])) {
                                        $current_user_total_points[$separate_log['userid']][$formated_date] = $separate_log['totalpoints'];
                                    }
                                    if (isset($separate_log['userid'])) {
                                        @$overall_user_points[$separate_log['userid']][$formated_date] = array($current_user_earned_points_list[$separate_log['userid']][$formated_date], $current_user_redeemed_points_list[$separate_log['userid']][$formated_date], $current_user_total_points[$separate_log['userid']][$formated_date]);
                                    }
                                }
                            }
                        }
                        //}
                    }
                    if ((is_array($current_user_earned_points_list)) && (is_array($current_user_redeemed_points_list)) && (is_array($current_user_total_points))) {
                        if (is_array($overall_user_points)) {

                            foreach ($overall_user_points as $points_user_id => $users_all_points) {
                                $allpoints_username_selection = get_user_by('id', $points_user_id);
                                $allpoints_username[$points_user_id] = $allpoints_username_selection->user_login;
                                foreach ($users_all_points as $pointsdate => $pointsnewvalue) {
                                    $date_from_log = $pointsdate;
                                    $converted_time = strtotime($date_from_log);
                                    $selected_start_date = get_option('selected_report_start_date');
                                    $selected_start_time = '00:00:00';
                                    $selected_start_date_time = $selected_start_date . ' ' . $selected_start_time;
                                    $converted_start_time = strtotime($selected_start_date_time);
                                    $selected_end_date = get_option('selected_report_end_date');
                                    $selected_end_time = '23:59:00';
                                    $selected_end_date_time = $selected_end_date . ' ' . $selected_end_time;
                                    $converted_end_time = strtotime($selected_end_date_time);
                                    if ($converted_start_time <= $converted_time && $converted_end_time >= $converted_time) {
                                        @$export_earned_points[$points_user_id] += $pointsnewvalue[0];
                                        @$export_redeemed_points[$points_user_id] += $pointsnewvalue[1];
                                        $export_total_points[$points_user_id] = $pointsnewvalue[2];

                                        if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '0')) {
                                            $import_export_points_heading = "User Name,Earned Points,Redeemed Points,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            $total_points_new = round($export_total_points[$points_user_id], $roundofftype);
                                            $arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new, $redeeming_points_new, $total_points_new);
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '1')) {
                                            $import_export_points_heading = "User Name,Redeemed Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            $arraylist[] = array($allpoints_username[$points_user_id], $redeeming_points_new);
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '0')) {
                                            $import_export_points_heading = "User Name,Earned Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            $arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new);
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '1')) {
                                            $import_export_points_heading = "User Name,Earned Points,Redeemed Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            $arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new, $redeeming_points_new);
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '0')) {
                                            $import_export_points_heading = "User Name,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $total_points_new = round($export_total_points[$points_user_id], $roundofftype);
                                            $arraylist[] = array($allpoints_username[$points_user_id], $total_points_new);
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '1')) {
                                            $import_export_points_heading = "User Name,Redeemed Points,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            $total_points_new = round($export_total_points[$points_user_id], $roundofftype);
                                            $arraylist[] = array($allpoints_username[$points_user_id], $redeeming_points_new, $total_points_new);
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '0')) {
                                            $import_export_points_heading = "User Name,Earned Points,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            $total_points_new = round($export_total_points[$points_user_id], $roundofftype);
                                            $arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new, $total_points_new);
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '1')) {
                                            $import_export_points_heading = "User Name,Earned Points,Redeemed Points,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            $total_points_new = round($export_total_points[$points_user_id], $roundofftype);
                                            $arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new, $redeeming_points_new, $total_points_new);
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                    } else {
                                        if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '0')) {

                                            $import_export_points_heading = "User Name,Earned Points,Redeemed Points,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            $total_points_new = round($export_total_points[$points_user_id], $roundofftype);
                                            @$arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new != '' ? $earning_points_new : '0', $redeeming_points_new != '' ? $redeeming_points_new : '0', $total_points_new != '' ? $total_points_new : '0');
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '1')) {
                                            $import_export_points_heading = "User Name,Redeemed Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            @$arraylist[] = array($allpoints_username[$points_user_id], $redeeming_points_new != '' ? $redeeming_points_new : '0');
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '0')) {
                                            $import_export_points_heading = "User Name,Earned Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            @$arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new != '' ? $earning_points_new : '0');
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '0') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '1')) {
                                            $import_export_points_heading = "User Name,Earned Points,Redeemed Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            @$arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new != '' ? $earning_points_new : '0', $redeeming_points_new != '' ? $redeeming_points_new : '0');
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '0')) {
                                            $import_export_points_heading = "User Name,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $total_points_new = round(@$export_total_points[$points_user_id], $roundofftype);
                                            @$arraylist[] = array($allpoints_username[$points_user_id], $total_points_new != '' ? $total_points_new : '0');
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '0') && ($export_redeemed_points_as_csv == '1')) {
                                            $import_export_points_heading = "User Name,Redeemed Points,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            $total_points_new = round($export_total_points[$points_user_id], $roundofftype);
                                            @$arraylist[] = array($allpoints_username[$points_user_id], $redeeming_points_new != '' ? $redeeming_points_new : '0', $total_points_new != '' ? $total_points_new : '0');
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '0')) {
                                            $import_export_points_heading = "User Name,Earned Points,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            $total_points_new = round($export_total_points[$points_user_id], $roundofftype);
                                            @$arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new != '' ? $earning_points_new : '0', $total_points_new != '' ? $total_points_new : '0');
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                        if (($export_all_points_as_csv == '1') && ($export_earned_points_as_csv == '1') && ($export_redeemed_points_as_csv == '1')) {
                                            $import_export_points_heading = "User Name,Earned Points,Redeemed Points,Total Points" . "\n";
                                            $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                            $earning_points_new = round($export_earned_points[$points_user_id], $roundofftype);
                                            $redeeming_points_new = round($export_redeemed_points[$points_user_id], $roundofftype);
                                            $total_points_new = round($export_total_points[$points_user_id], $roundofftype);
                                            @$arraylist[] = array($allpoints_username[$points_user_id], $earning_points_new != '' ? $earning_points_new : '0', $redeeming_points_new != '' ? $redeeming_points_new : '0', $total_points_new != '' ? $total_points_new : '0');
                                            $temp_array = array();
                                            foreach ($arraylist as &$v) {
                                                $temp_array[$v[0]] = $v;
                                            }
                                            update_option('heading', $import_export_points_heading);
                                        }
                                    }
                                }
                            }
                            $arraylist = array_values($temp_array);
                            $olddata = (array) get_option('rs_export_report');
                            $currentdata = $arraylist;
                            $mergedata = array_merge($olddata, $currentdata);
                            update_option('rs_export_report', $mergedata);
                        }
                    }
                }
            }
        } else {
            echo json_encode(array("success"));
        }
        exit();
    }

    public static function reward_system_page_customization_reports() {
        ?>
        <style type="text/css">
            p.submit {
                display:none;
            }
            #mainforms {
                display:none;
            }
        </style>
        <tr valign="top">
            <th class="titledesc" scope="row">
                <label for="rs_point_export_report_start_date"><?php _e('Start Date', 'rewardsystem'); ?></label>
            </th>
            <td class="forminp forminp-select">
                <input type="text" class="rs_point_export_report_start_date" value="" name="rs_point_export_report_start_date" id="rs_point_export_report_start_date" />
            </td>
        </tr>
        <tr valign="top">
            <th class="titledesc" scope="row">
                <label for="rs_point_export_report_end_date"><?php _e('End Date', 'rewardsystem'); ?></label>
            </th>
            <td class="forminp forminp-select">
                <input type="text" class="rs_point_export_report_end_date" value="" name="rs_point_export_report_end_date" id="rs_point_export_report_end_date" />
            </td>
        </tr>
        <tr valign="top">
            <th class="titledesc" scope="row">
                <label for="rs_export_report_pointtype_option"><?php _e('Export User Points based on', 'rewardsystem'); ?></label>
            </th>
            <td class="forminp forminp-select">
                <input type="checkbox" class="rs_export_report_pointtype_option" value="1" name="rs_export_report_pointtype_option_earning" id="rs_export_report_pointtype_option_earning" >Earning Points</input>
                <input type="checkbox" class="rs_export_report_pointtype_option" value="1" name="rs_export_report_pointtype_option_redeeming" id="rs_export_report_pointtype_option_redeeming" >Redeeming Points</input>
                <input type="checkbox" class="rs_export_report_pointtype_option" value="1" name="rs_export_report_pointtype_option_total" id="rs_export_report_pointtype_option_total" checked="checked">Total Points</input>
            </td>
        </tr>
        <tr valign ="top">
            <th class="titledesc" scope="row">
                <label for="rs_export_user_points_report_csv"><?php _e('Export User Points Report as CSV', 'rewardsystem'); ?></label>
            </th>
            <td class="forminp forminp-select">
                <input type="button" id="rs_export_user_points_report_csv" name="rs_export_user_points_report_csv" value="Export User Points Report"/>
                <img class="gif_rs_sumo_reward_button_social_report_csv" src="<?php echo WP_PLUGIN_URL; ?>/rewardsystem/img/update.gif" style="width:32px;height:32px;position:absolute"/>          
                <input type="submit" id="rs_export_user_points_report_csv1" name="rs_export_user_points_report_csv1" value="Export User Points Report1"/>
            </td>
        </tr>
        <?php
        if (isset($_POST['rs_export_user_points_report_csv1'])) {
            ob_end_clean();
            header("Content-type: text/csv");
            $dateformat = get_option('date_format');
            header("Content-Disposition: attachment; filename=reward_points_report" . date_i18n('Y-m-d')  . ".csv");
            header("Pragma: no-cache");
            header("Expires: 0");
            $import_export_points_heading = get_option('heading');
            echo $import_export_points_heading;
            $arraylist = get_option('rs_export_report');
            self::output_CSV_report($arraylist);
            exit();
        }
    }

    public static function output_CSV_report($data) {
        $output = fopen("php://output", "w");
        if (is_array($data) && !empty($data)) {
            foreach ($data as $row) {
                if ($row != false) {
                    fputcsv($output, $row); // here you can change delimiter/enclosure
                }
            }
        }
        fclose($output);
    }

    public static function datepickerenqueue_report(&$enqueuescript_report) {

        if ($_GET['tab'] == 'rewardsystem_reports_in_csv') {
            wp_enqueue_script('wp_reward_jquery_ui');
            wp_enqueue_style('wp_reward_jquery_ui_css');
        }
    }

    public static function wp_enqueqe_for_datepicker() {

        wp_register_style('wp_reward_jquery_ui_css', plugins_url('rewardsystem/css/jquery-ui.css'));
        wp_register_script('wp_reward_jquery_ui', plugins_url('rewardsystem/js/jquery-ui.js'));

        if (isset($_GET['tab'])) {
            if ($_GET['tab'] == 'rewardsystem_reports_in_csv') {
                wp_enqueue_script('wp_reward_jquery_ui');
                wp_enqueue_style('wp_reward_jquery_ui_css');
            }
            if ($_GET['tab'] == 'rewardsystem_update') {
                wp_enqueue_script('wp_reward_jquery_ui');
                wp_enqueue_style('wp_reward_jquery_ui_css');
            }
            do_action_ref_array('enqueuescriptforadmin', array(&$enqueuescript));
        }
    }

    public static function export_option_report_selected_callback() {
        global $wpdb; // this is how you get access to the database             

        if (isset($_POST['exporttype_report'])) {
            $export_user_type_report_value = $_POST['exporttype_report'];
            update_option('selected_user_type_report', $export_user_type_report_value);
        }
        exit();
    }

    public static function selected_users_for_exporting_csv_report_callback() {
        global $wpdb; // this is how you get access to the database
        if (isset($_POST['exportlist_report'])) {
            $rs_selected_list_export_report = $_POST['exportlist_report'];
            if (!is_array($rs_selected_list_export_report)) {
                $rs_selected_list_export_report = explode(',', $rs_selected_list_export_report);
            }
            update_option('rs_selected_user_list_export_report', $rs_selected_list_export_report);
        }
    }

    public static function export_option_selected_date_report_callback() {
        global $wpdb; // this is how you get access to the database
        if (isset($_POST['dateoption_report'])) {
            $export_selected_date_option_report = $_POST['dateoption_report'];
            delete_option('selected_date_type_report');
            update_option('selected_date_type_report', $export_selected_date_option_report);
        }
    }

    public static function export_report_start_date_callback() {
        global $wpdb; // this is how you get access to the database
        if (isset($_POST['export_report_startdate'])) {
            $export_report_start_date = $_POST['export_report_startdate'];
            delete_option('selected_report_start_date');
            update_option('selected_report_start_date', $export_report_start_date);
        }
    }

    public static function export_report_end_date_callback() {
        global $wpdb; // this is how you get access to the database
        if (isset($_POST['export_report_enddate'])) {
            $export_report_end_date = $_POST['export_report_enddate'];
            delete_option('selected_report_end_date');
            update_option('selected_report_end_date', $export_report_end_date);
        }
    }

    public static function export_points_points_based_report_callback() {
        global $wpdb; // this is how you get access to the database
        if (isset($_POST['export_all_points_report'])) {
            $export_for_all_points_report = $_POST['export_all_points_report'];
            delete_option('export_all_points_report_to_csv');
            update_option('export_all_points_report_to_csv', $export_for_all_points_report);
        }
        if (isset($_POST['export_earning_points_report'])) {
            $export_for_earning_points_post = $_POST['export_earning_points_report'];
            delete_option('export_earning_points_report_to_csv');
            update_option('export_earning_points_report_to_csv', $export_for_earning_points_post);
        }
        if (isset($_POST['export_redeemed_points_report'])) {
            $export_for_redeeming_points_report = $_POST['export_redeemed_points_report'];
            delete_option('export_redeeming_points_report_to_csv');
            update_option('export_redeeming_points_report_to_csv', $export_for_redeeming_points_report);
        }
    }

    public static function export_report_points_selection() {
        if (isset($_GET['tab'])) {
            if ($_GET['tab'] == 'rewardsystem_reports_in_csv') {
                ?>
                <script type="text/javascript">
                    jQuery(function () {
                        var selected_option_report = jQuery('input[name="rs_export_user_report_option"]:checked').val();
                        // alert(selected_option_report);
                        var data = {
                            action: "rs_export_report_option",
                            exporttype_report: selected_option_report,
                        };
                        jQuery.post('<?php echo admin_url('admin-ajax.php') ?>', data, function (response) {
                            console.log('Got this from the server: ' + response);
                        });
                        jQuery('input[name="rs_export_user_report_option"]').change(function () {
                            var selected_option_report = jQuery(this).val();
                            var data = {
                                action: "rs_export_report_option",
                                exporttype_report: selected_option_report,
                            };
                            jQuery.post('<?php echo admin_url('admin-ajax.php') ?>', data, function (response) {
                                console.log('Got this from the server: ' + response);
                            });
                        });
                    });
                    jQuery(function () {
                        var selected_users_for_export_report;
                        selected_users_for_export_report = jQuery('#rs_export_users_report_list').val();
                        var selected_users_data = {
                            action: "rs_list_of_users_to_export_report",
                            exportlist_report: selected_users_for_export_report,
                        };
                        jQuery.post('<?php echo admin_url('admin-ajax.php') ?>', selected_users_for_export_report, function (response) {
                            console.log('Got this from the server: ' + response);
                        });
                        jQuery('#rs_export_users_report_list').change(function () {
                            selected_users_for_export_report = jQuery('#rs_export_users_report_list').val();
                            var selected_users_data = {
                                action: "rs_list_of_users_to_export_report",
                                exportlist_report: selected_users_for_export_report,
                            };
                            jQuery.post('<?php echo admin_url('admin-ajax.php') ?>', selected_users_data, function (response) {
                                console.log('Got this from the server: ' + response);
                            });
                        });
                    });
                    jQuery(function () {
                        var selected_option_date_report = jQuery('input[name="rs_export_report_date_option"]').val();
                        var selected_date_option_report_param = {
                            action: "rs_selected_date_option_report",
                            dateoption_report: selected_option_date_report,
                        };
                        jQuery.post('<?php echo admin_url('admin-ajax.php') ?>', selected_date_option_report_param, function (response) {
                            console.log('Got this from the server: ' + response);
                        });
                        jQuery('input[name="rs_export_report_date_option"]').change(function () {
                            var selected_option_date_report = jQuery(this).val();
                            var selected_date_option_report_param = {
                                action: "rs_selected_date_option_report",
                                dateoption_report: selected_option_date_report,
                            };
                            jQuery.post('<?php echo admin_url('admin-ajax.php') ?>', selected_date_option_report_param, function (response) {
                                console.log('Got this from the server: ' + response);
                            });
                        });
                    });
                    jQuery(function () {
                        var export_earning_points_report = jQuery('#rs_export_report_pointtype_option_earning').is(':checked') ? 1 : 0;
                        var export_redeemed_points_report = jQuery('#rs_export_report_pointtype_option_redeeming').is(':checked') ? 1 : 0;
                        var export_all_points_report = jQuery('#rs_export_report_pointtype_option_total').is(':checked') ? 1 : 0;
                        var selected_options_for_export_report = {
                            action: "rs_selected_options_for_export_report",
                            export_earning_points_report: export_earning_points_report,
                            export_redeemed_points_report: export_redeemed_points_report,
                            export_all_points_report: export_all_points_report,
                        };
                        jQuery.post('<?php echo admin_url('admin-ajax.php') ?>', selected_options_for_export_report, function (response) {
                            console.log('Got this from the server: ' + response);
                        });
                        jQuery('#rs_export_report_pointtype_option_earning').change(function () {
                            var export_earning_points_report = jQuery('#rs_export_report_pointtype_option_earning').is(':checked') ? 1 : 0;
                            var export_redeemed_points_report = jQuery('#rs_export_report_pointtype_option_redeeming').is(':checked') ? 1 : 0;
                            var export_all_points_report = jQuery('#rs_export_report_pointtype_option_total').is(':checked') ? 1 : 0;
                            var selected_options_for_export_report = {
                                action: "rs_selected_options_for_export_report",
                                export_earning_points_report: export_earning_points_report,
                                export_redeemed_points_report: export_redeemed_points_report,
                                export_all_points_report: export_all_points_report,
                            };
                            jQuery.post('<?php echo admin_url('admin-ajax.php') ?>', selected_options_for_export_report, function (response) {
                                console.log('Got this from the server: ' + response);
                            });
                        });
                        jQuery('#rs_export_report_pointtype_option_redeeming').change(function () {
                            var export_earning_points_report = jQuery('#rs_export_report_pointtype_option_earning').is(':checked') ? 1 : 0;
                            var export_redeemed_points_report = jQuery('#rs_export_report_pointtype_option_redeeming').is(':checked') ? 1 : 0;
                            var export_all_points_report = jQuery('#rs_export_report_pointtype_option_total').is(':checked') ? 1 : 0;
                            var selected_options_for_export_report = {
                                action: "rs_selected_options_for_export_report",
                                export_earning_points_report: export_earning_points_report,
                                export_redeemed_points_report: export_redeemed_points_report,
                                export_all_points_report: export_all_points_report,
                            };
                            jQuery.post('<?php echo admin_url('admin-ajax.php') ?>', selected_options_for_export_report, function (response) {
                                console.log('Got this from the server: ' + response);
                            });
                        });
                        jQuery('#rs_export_report_pointtype_option_total').change(function () {
                            var export_earning_points_report = jQuery('#rs_export_report_pointtype_option_earning').is(':checked') ? 1 : 0;
                            var export_redeemed_points_report = jQuery('#rs_export_report_pointtype_option_redeeming').is(':checked') ? 1 : 0;
                            var export_all_points_report = jQuery('#rs_export_report_pointtype_option_total').is(':checked') ? 1 : 0;
                            var selected_options_for_export_report = {
                                action: "rs_selected_options_for_export_report",
                                export_earning_points_report: export_earning_points_report,
                                export_redeemed_points_report: export_redeemed_points_report,
                                export_all_points_report: export_all_points_report,
                            };
                            jQuery.post('<?php echo admin_url('admin-ajax.php') ?>', selected_options_for_export_report, function (response) {
                                console.log('Got this from the server: ' + response);
                            });
                        });

                    });
                </script>
                <?php
            }
        }
    }

}

new RSFunctionForReportsInCsv();
