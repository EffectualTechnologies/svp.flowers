<?php

class RSPointURL {

    public function __construct() {

        add_action('init', array($this, 'reward_system_default_settings'), 103); // call the init function to update the default settings on page load

        add_filter('woocommerce_rs_settings_tabs_array', array($this, 'reward_system_tab_setting')); // Register a New Tab in a WooCommerce Reward System Settings        

        add_action('woocommerce_rs_settings_tabs_rs_points_url', array($this, 'reward_system_register_admin_settings')); // Call to register the admin settings in the Reward System Submenu with general Settings tab        

        add_action('woocommerce_update_options_rs_points_url', array($this, 'reward_system_update_settings')); // call the woocommerce_update_options_{slugname} to update the reward system                               

        add_action('woocommerce_admin_field_rs_generate_button_for_point_url', array($this, 'rs_generate_button_for_point_url'));

        add_action('wp_ajax_nopriv_rs_generate_pont_url', array($this, 'ajax_function_for_pointurl'));

        add_action('wp_ajax_rs_generate_pont_url', array($this, 'ajax_function_for_pointurl'));

        add_action('wp_ajax_nopriv_rs_delete_pointurl_data', array($this, 'rs_delete_current_point_url_data'));

        add_action('wp_ajax_rs_delete_pointurl_data', array($this, 'rs_delete_current_point_url_data'));

        add_action('admin_head', array($this, 'rs_function_to_show_expiry_field'));

        add_action('wp_head', array($this, 'rs_function_to_award_points_for_url_click'));
    }

    /*
     * Function to Define Name of the Tab
     */

    public static function reward_system_tab_setting($setting_tabs) {
        $setting_tabs['rs_points_url'] = __('Point URL', 'rewardsystem');
        return $setting_tabs;
    }

    /*
     * Function label settings to Member Level Tab
     */

    public static function reward_system_admin_fields() {
        global $woocommerce;

        return apply_filters('woocommerce_rewardsystem_order_settings', array(
            array(
                'name' => __('Point URL Setting', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('It Works only for Logged in User', 'rewardsystem'),
                'id' => '_rs_pointurl_setting'
            ),
            array(
                'name' => __('Name for Point URL', 'rewardsystem'),
                'desc_tip' => false,
                'css' => 'min-width:350px',
                'id' => 'rs_label_for_site_url',
                'newids' => 'rs_label_for_site_url',
                'type' => 'text',
                'std' => '',
                'default' => '',
                'custom_attributes' => array(
                    'required' => 'required'
                )
            ),
            array(
                'name' => __('Enter Site URL', 'rewardsystem'),
                'desc' => __('(If it is empty,then we consider Site Url as Base URL)', 'rewardsystem'),
                'desc_tip' => false,
                'css' => 'min-width:350px',
                'id' => 'rs_site_url',
                'newids' => 'rs_site_url',
                'type' => 'text',
                'std' => site_url(),
                'default' => site_url(),
            ),
            array(
                'name' => __('Enter Points', 'rewardsystem'),
                'desc' => __('Points', 'rewardsystem'),
                'id' => 'rs_point_for_url',
                'newids' => 'rs_point_for_url',
                'type' => 'text',
                'std' => '',
                'default' => '',
                'custom_attributes' => array(
                    'required' => 'required'
                )
            ),
            array(
                'name' => __('Time Limit', 'rewardsystem'),
                'id' => 'rs_time_limit_for_pointurl',
                'newids' => 'rs_time_limit_for_pointurl',
                'type' => 'select',
                'std' => '1',
                'default' => '1',
                'options' => array(
                    '1' => __('Unlimited', 'rewardsystem'),
                    '2' => __('Limited', 'rewardsystem'),
                )
            ),
            array(
                'name' => __('Expiry Time for Point URL', 'rewardsystem'),
                'id' => 'rs_expiry_time_for_pointurl',
                'newids' => 'rs_expiry_time_for_pointurl',
                'type' => 'text',
                'std' => '',
                'default' => '',
            ),
            array(
                'name' => __('Count Limit', 'rewardsystem'),
                'id' => 'rs_count_limit_for_pointurl',
                'newids' => 'rs_count_limit_for_pointurl',
                'type' => 'select',
                'std' => '1',
                'default' => '1',
                'options' => array(
                    '1' => __('Unlimited', 'rewardsystem'),
                    '2' => __('Limited', 'rewardsystem'),
                )
            ),
            array(
                'name' => __('Count', 'rewardsystem'),
                'id' => 'rs_count_for_pointurl',
                'newids' => 'rs_count_for_pointurl',
                'type' => 'text',
                'std' => '',
                'default' => '',
            ),
            array(
                'type' => 'rs_generate_button_for_point_url',
            ),
            array('type' => 'sectionend', 'id' => '_rs_pointurl_setting'),
            array(
                'name' => __('Point URL Message Setting', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_pointurl_message_setting'
            ),
            array(
                'name' => __('Success Message for Point URL after User Clicked', 'rewardsystem'),
                'id' => 'rs_success_message_for_pointurl',
                'newids' => 'rs_success_message_for_pointurl',
                'css' => 'min-width:350px',
                'type' => 'text',
                'std' => '[points] Points added for [offer_name]',
                'default' => '[points] Points added for [offer_name]',
            ),
            array(
                'name' => __('Log to be Displayed in My Account and Master Log', 'rewardsystem'),
                'id' => 'rs_message_for_pointurl',
                'newids' => 'rs_message_for_pointurl',
                'css' => 'min-width:350px',
                'type' => 'text',
                'std' => '[points] Points added, from Visted Point URL',
                'default' => '[points] Points added, from Visted Point URL',
            ),
            array('type' => 'sectionend', 'id' => '_rs_pointurl_message_setting'),
            
            array(
                'name' => __('Point URL Failure Message Setting', 'rewardsystem'),
                'type' => 'title',
                'id' => '_sk_message_setting1'
            ),
             array(
                'name' => __('Failure Message for Coupon URL', 'massivecoupons'),
                'id' => 'sk_failure_message_for_couponurl_for_more_than_one1',
                'newids' => 'sk_failure_message_for_couponurl_for_more_than_one1',
                'css' => 'min-width:350px',
                'type' => 'textarea',
                'std' => 'You cannot get Points for this link because you have already claimed',
                'default' => 'You cannot get coupon for this link because you have already claimed',
            ),
            array(
                'name' => __('Failure Message for Coupon URL, If Time Limit has been reached', 'massivecoupons'),
                'id' => 'sk_failure_message_for_couponurl_for_time_limit1',
                'newids' => 'sk_failure_message_for_couponurl_for_time_limit1',
                'css' => 'min-width:350px',
                'type' => 'text',
                'std' => '[offer_name] has been Expired',
                'default' => '[offer_name] has been Expired',
            ),
            array(
                'name' => __('Failure Message for Points URL, If Count Limit has been reached', 'massivecoupons'),
                'id' => 'sk_failure_message_for_couponurl_for_count_limit1',
                'newids' => 'sk_failure_message_for_couponurl_for_count_limit1',
                'css' => 'min-width:350px',
                'type' => 'text',
                'std' => 'Usage of Link Limitation reached',
                'default' => 'Usage of Link Limitation reached',
            ),
            array('type' => 'sectionend', 'id' => '_sk_message_setting1'),
        ));
    }

    /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {

        woocommerce_admin_fields(RSPointURL::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSPointURL::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSPointURL::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }

    public static function rs_generate_button_for_point_url() {
        ?>        
        <style type="text/css">
            .rs_remove_point_url {
                border: 2px solid #a1a1a1;
                padding: 3px 9px;
                background: #dddddd;
                width: 5px;
                border-radius: 25px;
            }
            .rs_remove_point_url:hover {
                cursor: pointer;
                background:red;
                color:#fff;
                border: 2px solid #fff;
            }
        </style>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('#rs_table_for_point_url').footable().bind('footable_filtering', function (e) {
                    var selected = jQuery('.filter-status').find(':selected').text();
                    if (selected && selected.length > 0) {
                        e.filter += (e.filter && e.filter.length > 0) ? ' ' + selected : selected;
                        e.clear = !e.filter;
                    }
                });
                jQuery('#changepagesizers_for_url').change(function (e) {
                    e.preventDefault();
                    var pageSize = jQuery(this).val();
                    jQuery('.footable').data('page-size', pageSize);
                    jQuery('.footable').trigger('footable_initialized');
                });
            });
        </script>
        <tr>
            <td>
            </td>
            <td>
                <input type="submit" id="rs_button_for_point_url" value="<?php _e('Generate Point URL', 'rewardsystem'); ?>"/>
            </td>
        </tr>
        <tr>
        <table>        
            <tr valign="top">
                <td>
                    <?php
                    echo '<p> ' . __('Search:', 'rewardsystem') . '<input id="filterings_pointurl" type="text"/>  ' . __('Page Size:', 'rewardsystem') . '
                <select id="changepagesizers_for_url">
									<option value="5">5</option>
									<option value="10">10</option>
                    <option value="50">50</option>
                    <option value="100">100</option>
                </select></p>';
                    ?>

            </tr>
        </table>    
        <table id="rs_table_for_point_url" class="wp-list-table widefat fixed posts  rs_table_for_point_url" data-filter = "#filterings_pointurl" data-page-size="5" data-page-previous-text = "prev" data-filter-text-only = "true" data-page-next-text = "next">
            <thead>
                <tr>
                    <th><?php _e('S.No', 'rewardsystem'); ?></th>
                    <th><?php _e('Name for Point URL', 'rewardsystem'); ?></th>
                    <th><?php _e('URL', 'rewardsystem'); ?></th>                    
                    <th><?php _e('Point(s)', 'rewardsystem'); ?></th>
                    <th><?php _e('Date', 'rewardsystem'); ?></th>
                    <th><?php _e('Time Limit', 'rewardsystem'); ?></th>
                    <th><?php _e('Count Limit', 'rewardsystem'); ?></th>
                    <th><?php _e('Current Usage Count', 'rewardsystem'); ?></th>                    
                    <th><?php _e('Delete', 'rewardsystem'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $array = get_option('points_for_url_click');
                $i = 1;
                if (is_array($array)) {
                    foreach ($array as $key => $value) {
                        $pointurl = $value['url'];
                        $add_query = add_query_arg('rsid', $key, $pointurl);
                        ?>
                        <tr>
                            <td><?php echo $i; ?></td>
                            <td><?php echo $value['name']; ?></td>
                            <td><?php echo $add_query; ?></td>
                            <td><?php echo $value['points']; ?></td>
                            <td><?php echo $value['date']; ?></td>
                            <td><?php echo $value['time_limit'] == '1' ? __('Unlimited', 'rewardsystem') : __('Limited', 'rewardsystem'); ?></td>                            
                            <td><?php echo $value['count_limit'] == '1' ? __('Unlimited', 'rewardsystem') : __('Limited', 'rewardsystem'); ?></td>
                            <td><?php echo $value['current_usage_count']; ?></td>
                            <td><div data-uniqid="<?php echo $key; ?>" class="rs_remove_point_url">x</div></td>
                        </tr>    
                        <?php
                        $i++;
                    }
                }
                ?>
            </tbody>
        </table>
        <div style="clear:both;">
            <div class="pagination pagination-centered"></div>
        </div>
        </tr>        
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('#rs_button_for_point_url').click(function () {
                    var pointsurl_name = jQuery('#rs_label_for_site_url').val();
                    var point_url = jQuery('#rs_site_url').val();
                    var points = jQuery('#rs_point_for_url').val();
                    var time_limit = jQuery('#rs_time_limit_for_pointurl').val();
                    var expiry_time = jQuery('#rs_expiry_time_for_pointurl').val();
                    var count_limit = jQuery('#rs_count_limit_for_pointurl').val();
                    var count = jQuery('#rs_count_for_pointurl').val();
                    var current_usage_count = 0;
                    var used_by = '';
                    if (points != '' && pointsurl_name != '') {
                        var dataparam = ({
                            action: 'rs_generate_pont_url',
                            name: pointsurl_name,
                            url: point_url,
                            points: points,
                            time_limit: time_limit,
                            expiry_time: expiry_time,
                            count_limit: count_limit,
                            current_usage_count: current_usage_count,
                            count: count,
                            used_by: used_by,
                            date: '<?php echo date('Y-m-d'); ?>'
                        });
                        jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                                function (response) {
                                    jQuery(".rs_table_for_point_url").load(window.location + " .rs_table_for_point_url");
                                });
                        return false;
                    } else {

                    }
                });

                jQuery(document).on('click', '.rs_remove_point_url', function () {
                    var uniqueid = jQuery(this).attr('data-uniqid');
                    var dataparam = ({
                        action: 'rs_delete_pointurl_data',
                        uniqueid: uniqueid
                    });

                    jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam,
                            function (response) {
                                jQuery(".rs_table_for_point_url").load(window.location + " .rs_table_for_point_url");
                            });
                    return false;
                });
            });
        </script>
        <?php
    }

    public static function rs_function_to_show_expiry_field() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('#rs_expiry_time_for_pointurl').datepicker({dateFormat: 'yy-mm-dd', minDate: 0});
                if (jQuery('#rs_time_limit_for_pointurl').val() == '1') {
                    jQuery('#rs_expiry_time_for_pointurl').parent().parent().hide();
                } else {
                    jQuery('#rs_expiry_time_for_pointurl').parent().parent().show();
                }

                jQuery('#rs_time_limit_for_pointurl').change(function () {
                    if (jQuery('#rs_time_limit_for_pointurl').val() == '1') {
                        jQuery('#rs_expiry_time_for_pointurl').parent().parent().hide();
                    } else {
                        jQuery('#rs_expiry_time_for_pointurl').parent().parent().show();
                    }
                });

                if (jQuery('#rs_count_limit_for_pointurl').val() == '1') {
                    jQuery('#rs_count_for_pointurl').parent().parent().hide();
                } else {
                    jQuery('#rs_count_for_pointurl').parent().parent().show();
                }

                jQuery('#rs_count_limit_for_pointurl').change(function () {
                    if (jQuery('#rs_count_limit_for_pointurl').val() == '1') {
                        jQuery('#rs_count_for_pointurl').parent().parent().hide();
                    } else {
                        jQuery('#rs_count_for_pointurl').parent().parent().show();
                    }
                });
            });
        </script>
        <?php
    }

    public static function ajax_function_for_pointurl() {
        if (isset($_POST['points']) && isset($_POST['name'])) {
            $uniqid = uniqid();
            $new_array = array($uniqid => $_POST);
            $previousvalue = get_option('points_for_url_click');
            if (!empty($previousvalue)) {
                $array_merge = array_merge($previousvalue, $new_array);
                update_option('points_for_url_click', $array_merge);
            } else {
                update_option('points_for_url_click', $new_array);
            }
            echo 'success';
        }
        exit();
    }

    public static function rs_delete_current_point_url_data() {
        if (isset($_POST['uniqueid'])) {
            $pointurldata = get_option('points_for_url_click');
            if (is_array($pointurldata) && !empty($pointurldata)) {
                if (array_key_exists($_POST['uniqueid'], $pointurldata)) {
                    unset($pointurldata[$_POST['uniqueid']]);
                }
                $updatedarray = $pointurldata;
                $new_array = array_filter($updatedarray);
                update_option('points_for_url_click', $new_array);
            }
        }
    }

    public static function rs_common_function_for_awarding_points($uniqueid, $user_id) {
        $get_point_for_url = get_option('points_for_url_click');
        if (is_array($get_point_for_url) && !empty($get_point_for_url)) {
            if (array_key_exists($uniqueid, $get_point_for_url)) {
                $uniqvalue = $get_point_for_url[$uniqueid];
                $noofdays = get_option('rs_point_to_be_expire');
                 
            if (($noofdays != '0') && ($noofdays != '')) {
                $date =   time() +($noofdays * 24 * 60 * 60);
            } else {
                $date = '999999999999';
            }
                $current_userid = $user_id;
                $current_date = strtotime(date('y-m-d'));
                $time_limit = $uniqvalue['time_limit'];
                $expiry_date = strtotime($uniqvalue['expiry_time']);
                $count_limit = $uniqvalue['count_limit'];
                $count = $uniqvalue['count'];
                $current_usage_count = $uniqvalue['current_usage_count'];
                $earned_points = $uniqvalue['points'];
                $user_id = $uniqvalue['used_by'];
                $checkpoints = 'RPFURL';
                if ($time_limit == '2') {
                    if ($current_date <= $expiry_date) {
                        if ($count_limit == '1') {
                            if (!in_array($current_userid, (array) $user_id)) {
                                RSPointExpiry::insert_earning_points($current_userid, $earned_points, 0, $date, $checkpoints, 0, '', '', $reasonindetail = '');
                                $equearnamt = RSPointExpiry::earning_conversion_settings($earned_points);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($current_userid);
                                RSPointExpiry::record_the_points($current_userid, $earned_points, '0', $date, $checkpoints, $equearnamt, '0', '0', '0', '0', '0', '', $totalpoints, '', '0');
                                
                            }
                        } else {
                            if ($count != '') {
                                if ($current_usage_count <= $count) {
                                    if (!in_array($current_userid, (array) $user_id)) {
                                        RSPointExpiry::insert_earning_points($current_userid, $earned_points, 0, $date, $checkpoints, 0, '', '', $reasonindetail = '');
                                        $equearnamt = RSPointExpiry::earning_conversion_settings($earned_points);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($current_userid);
                                        RSPointExpiry::record_the_points($current_userid, $earned_points, '0', $date, $checkpoints, $equearnamt, '0', '0', '0', '0', '0', '', $totalpoints, '', '0');
                                        
                                    }
                                }
                            }
                        }
                    }
                } else {
                    if ($count_limit == '1') {
                        if (!in_array($current_userid, (array) $user_id)) {
                            RSPointExpiry::insert_earning_points($current_userid, $earned_points, 0, $date, $checkpoints, 0, '', '', $reasonindetail = '');
                            $equearnamt = RSPointExpiry::earning_conversion_settings($earned_points);
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($current_userid);
                            RSPointExpiry::record_the_points($current_userid, $earned_points, '0', $date, $checkpoints, $equearnamt, '0', '0', '0', '0', '0', '', $totalpoints, '', '0');
                            
                        }
                    } else {
                        if ($count != '') {
                            if ($current_usage_count <= $count) {
                                if (!in_array($current_userid, (array) $user_id)) {
                                    RSPointExpiry::insert_earning_points($current_userid, $earned_points, 0, $date, $checkpoints, 0, '', '', $reasonindetail = '');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($earned_points);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($current_userid);
                                    RSPointExpiry::record_the_points($current_userid, $earned_points, '0', $date, $checkpoints, $equearnamt, '0', '0', '0', '0', '0', '', $totalpoints, '', '0');
                                    
                                }
                            }
                        }
                    }
                }
            }
           // update_option('points_for_url_click', $get_point_for_url);
        }
    }

    public static function rs_function_to_award_points_for_url_click() {
        if (isset($_GET['rsid'])) {
            $uniqueid = $_GET['rsid'];
            if (is_user_logged_in()) {
                $user_id = get_current_user_id();
                self::rs_common_function_for_awarding_points($uniqueid, $user_id);
                $get_point_for_url = get_option('points_for_url_click');
                 $offer_name = $get_point_for_url[$uniqueid]['name'];
                $count_limit = $get_point_for_url[$uniqueid]['count_limit'];
                 $url_user_id = $get_point_for_url[$uniqueid]['used_by'];
                  $time_limit = $get_point_for_url[$uniqueid]['time_limit'];
                $current_count = $get_point_for_url[$uniqueid]['current_usage_count'];  
                $current_date = strtotime(date('y-m-d'));
                 $expiry_date = strtotime($get_point_for_url[$uniqueid]['expiry_time']);
                 $count = $get_point_for_url[$uniqueid]['count'];
                $get_point_to_display = $get_point_for_url[$uniqueid]['points'];
                $message_to_display = get_option('rs_success_message_for_pointurl');
                $replace_points = str_replace('[points]', $get_point_to_display, $message_to_display);
                $get_name_to_display = $get_point_for_url[$uniqueid]['name'];
                $replace_name = str_replace('[offer_name]', $get_name_to_display, $replace_points);                
                ?>
                <style type="text/css">
                    .rs_success_msg_for_pointurl {
                        width: 100%;                        
                        font-size: 20px;
                        font-weight: bold;
                        padding: 15px;
                        text-align: center;
                        background-color: black;
                        z-index: 999999;
                        position:fixed;
                        color: #fff;
                    }
                    
                    .sk_failure_msg_for_pointsurl {
                        width: 100%;                        
                        font-size: 20px;
                        font-weight: bold;
                        padding: 15px;
                        text-align: center;
                        background-color: black;
                        z-index: 999999;
                        position:fixed;
                        color: #fff;
                    }      
                </style>
                <?php
                
                if (!in_array($user_id, (array) $url_user_id)) {
                    if ($time_limit == '2') {
                        if ($current_date <= $expiry_date) {
                            if ($count_limit == '1') {
                                ?>                    
                                <div class="rs_success_msg_for_pointurl"><?php echo $replace_name; ?></div>
                                <?php
                                $updated_count = $current_count + 1;
                                $get_point_for_url[$uniqueid]['current_usage_count'] = $updated_count;
                                $get_point_for_url[$uniqueid]['used_by'][] = $user_id;
                            } else {
                                if ($current_count < $count) {
                                    ?>                            
                                    <div class="rs_success_msg_for_pointurl"><?php echo $replace_name; ?></div>
                                    <?php
                                    $updated_count = $current_count + 1;
                                    $get_point_for_url[$uniqueid]['current_usage_count'] = $updated_count;
                                    $get_point_for_url[$uniqueid]['used_by'][] = $user_id;
                                } else {
                                    $faliure_msg = get_option('sk_failure_message_for_couponurl_for_count_limit1');
                                    ?>                            
                                    <div class="sk_failure_msg_for_pointsurl"><?php echo $faliure_msg; ?></div>
                                    <?php
                                }
                            }
                        } else {
                            $faliure_msg = get_option('sk_failure_message_for_couponurl_for_time_limit1');
                            $replace_name = str_replace('[offer_name]', $offer_name, $faliure_msg)
                            ?>                            
                            <div class="sk_failure_msg_for_pointsurl"><?php echo $replace_name; ?></div>
                            <?php
                        }
                    } else {
                        if ($count_limit == '1') {
                            ?>                    
                            <div class="rs_success_msg_for_pointurl"><?php echo $replace_name; ?></div>
                            <?php
                            $updated_count = $current_count + 1;
                            $get_point_for_url[$uniqueid]['current_usage_count'] = $updated_count;
                            $get_point_for_url[$uniqueid]['used_by'][] = $user_id;
                        } else {
                            if ($current_count < $count) {
                                ?>                            
                                <div class="rs_success_msg_for_pointurl"><?php echo $replace_name; ?></div>
                                <?php
                                $updated_count = $current_count + 1;
                                $get_point_for_url[$uniqueid]['current_usage_count'] = $updated_count;
                                $get_point_for_url[$uniqueid]['used_by'][] = $user_id;
                            } else {
                                $faliure_msg = get_option('sk_failure_message_for_couponurl_for_count_limit1');
                                ?>                            
                                <div class="sk_failure_msg_for_pointsurl"><?php echo $faliure_msg; ?></div>
                                <?php
                            }
                        }
                    }
                } else {
                    $faliure_msg = get_option('sk_failure_message_for_couponurl_for_more_than_one1');
                    ?>                    
                    <div class="sk_failure_msg_for_pointsurl"><?php echo $faliure_msg; ?></div>
                    <?php
                }
                  update_option('points_for_url_click', $get_point_for_url);
            }
        }
    }

}

new RSPointURL();
