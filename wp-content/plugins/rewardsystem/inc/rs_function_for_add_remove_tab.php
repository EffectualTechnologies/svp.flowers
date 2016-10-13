<?php

class RSFunctionForAddorRemovePoints {

    public function __construct() {

        add_action('admin_head', array($this, 'rs_jquery_function_for_add_remove_tab'));

        add_action('woocommerce_admin_field_rs_add_remove_remove_reward_points', array($this, 'rs_getting_list_for_add_remove_option'));

        add_action('admin_head', array($this, 'rs_validation_for_input_field_in_add_remove_points'));

        add_action('admin_head', array($this, 'ajax_function_for_add'));

        add_action('wp_ajax_no_priv_rsaddpointforuser', array($this, 'process_ajax_to_split_users_for_add'));

        add_action('wp_ajax_rsaddpointforuser', array($this, 'process_ajax_to_split_users_for_add'));

        add_action('wp_ajax_no_priv_rssplitusertoaddpoints', array($this, 'process_ajax_to_add'));

        add_action('wp_ajax_rssplitusertoaddpoints', array($this, 'process_ajax_to_add'));

        add_action('admin_head', array($this, 'ajax_function_for_remove'));

        add_action('wp_ajax_no_priv_rsremovepointforuser', array($this, 'process_ajax_to_split_users_for_remove'));

        add_action('wp_ajax_rsremovepointforuser', array($this, 'process_ajax_to_split_users_for_remove'));

        add_action('wp_ajax_no_priv_rssplitusertoremovepoints', array($this, 'process_ajax_to_remove'));

        add_action('wp_ajax_rssplitusertoremovepoints', array($this, 'process_ajax_to_remove'));
    }

    /*
     * Function to add label setting in Add/Remove Reward Points
     */

    Public static function rs_jquery_function_for_add_remove_tab() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                // To Show the option of User Type       
                if (jQuery('#rs_select_user_type').val() == '1') {
                    jQuery('#rs_select_to_include_customers').parent().parent().hide();
                    jQuery('#rs_select_to_exclude_customers').parent().parent().hide();
                } else if (jQuery('#rs_select_user_type').val() == '2') {
                    jQuery('#rs_select_to_include_customers').parent().parent().show();
                    jQuery('#rs_select_to_exclude_customers').parent().parent().hide();
                } else {
                    jQuery('#rs_select_to_include_customers').parent().parent().hide();
                    jQuery('#rs_select_to_exclude_customers').parent().parent().show();
                }
                jQuery('#rs_select_user_type').change(function () {
                    if (jQuery('#rs_select_user_type').val() == '1') {
                        jQuery('#rs_select_to_include_customers').parent().parent().hide();
                        jQuery('#rs_select_to_exclude_customers').parent().parent().hide();
                        jQuery('#rs_reward_addremove_points').val("");
                        jQuery('#rs_reward_addremove_reason').val("");
                    } else if (jQuery('#rs_select_user_type').val() == '2') {
                        jQuery('#rs_select_to_include_customers').parent().parent().show();
                        jQuery('#rs_select_to_exclude_customers').parent().parent().hide();                     
                        jQuery('#rs_reward_addremove_points').val("");
                        jQuery('#rs_reward_addremove_reason').val("");
                    } else {
                        jQuery('#rs_select_to_include_customers').parent().parent().hide();
                        jQuery('#rs_select_to_exclude_customers').parent().parent().show();                       
                        jQuery('#rs_reward_addremove_points').val("");
                        jQuery('#rs_reward_addremove_reason').val("");
                    }
                });
            });
        </script>
        <?php
    }

    public static function rs_getting_list_for_add_remove_option() {

        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        global $woocommerce;
        
        $enablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');

        echo RSJQueryFunction::rs_common_ajax_function_to_select_user('rs_select_to_include_customers');
        echo RSJQueryFunction::rs_common_ajax_function_to_select_user('rs_select_to_exclude_customers');
        ?>
        <style type="text/css">
            p.submit {
                display:none;
            }
            #mainforms {
                display:none;
            }

        </style>        
        <form name="rs_addremove" method="post">
            <table class="form-table">
                <tbody>
                    <tr valign="top">
                        <th class="titledesc" scope="row">
                            <label for="rs_select_user_type"><?php _e('User Type', 'rewardsystem'); ?> </label>
                        </th>
                        <td>
                            <select name="rs_select_user_type"  id="rs_select_user_type" class="short rs_select_user_type">
                                <option value="1"><?php echo __('All User', 'rewardsystem'); ?></option>
                                <option value="2"><?php echo __('Include User', 'rewardsystem'); ?></option>
                                <option value="3"><?php echo __('Exclude User', 'rewardsystem'); ?></option>
                            </select>  
                        </td>
                    </tr>
                    <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>

                        <tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="rs_select_to_include_customers"><?php _e('Select to Include Username/Email', 'rewardsystem'); ?> </label>
                            </th>
                            <td>
                                <select name="rs_select_to_include_customers" multiple="multiple" id="rs_select_to_include_customers" class="short rs_select_to_include_customers">
                                    <?php
                                    $json_ids = array();

                                    $getuser = get_option('rs_select_to_include_customers');
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
                                <label for="rs_select_to_include_customers"><?php _e('Select to Include Username/Email', 'rewardsystem'); ?> </label>
                            </th>
                            <td>
                                <input type="hidden" class="wc-customer-search" name="rs_select_to_include_customers" id="rs_select_to_include_customers" data-multiple="true" data-placeholder="<?php _e('Search Users', 'rewardsystem'); ?>" data-selected="<?php
                                $json_ids = array();
                                $getuser = get_option('rs_select_to_include_customers');
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
                                    }
                                    echo esc_attr(json_encode($json_ids));
                                }
                                ?>" value="<?php echo implode(',', array_keys($json_ids)); ?>" data-allow_clear="true" />
                            </td>
                        </tr>
                        <?php
                    }
                    ?>



                    <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
                        <tr valign="top">
                            <th class="titledesc" scope="row">
                                <label for="rs_select_to_exclude_customers"><?php _e('Select to Exclude Username/Email', 'rewardsystem'); ?> </label>
                            </th>
                            <td>
                                <select name="rs_select_to_exclude_customers" multiple="multiple" id="rs_select_to_exclude_customers" class="short rs_select_to_exclude_customers">
                                    <?php
                                    $json_ids = array();
                                    $getuser = get_option('rs_select_to_exclude_customers');
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
                                <label for="rs_select_to_exclude_customers"><?php _e('Select to Exclude Username/Email', 'rewardsystem'); ?> </label>
                            </th>
                            <td>
                                <input type="hidden" class="wc-customer-search" name="rs_select_to_exclude_customers" id="rs_select_to_exclude_customers" data-multiple="true" data-placeholder="<?php _e('Search Users', 'rewardsystem'); ?>" data-selected="<?php
                                $json_ids = array();
                                $getuser = get_option('rs_select_to_exclude_customers');
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
                                    }
                                    echo esc_attr(json_encode($json_ids));
                                }
                                ?>" value="<?php echo implode(',', array_keys($json_ids)); ?>" data-allow_clear="true" />
                            </td>
                        </tr>
                        <?php
                    }
                    ?>


                    <tr valign="top">
                        <th class="titledesc" scope="row">
                            <label for="rs_reward_addremove_points"><?php _e('Enter Points', 'rewardsystem'); ?></label>
                        </th>
                        <td class="forminp forminp-text">
                            <input type="text" class="" value="" style="min-width:150px;" required='required' id="rs_reward_addremove_points" name="rs_reward_addremove_points"> 	                    
                            <div class='rs_add_remove_points_error' style="color: red;font-size:14px;"></div>
                        </td>
                    </tr>
                    <tr valign="top">
                        <th class="titledesc" scope="row">
                            <label for="rs_reward_addremove_reason"><?php _e('Reason in Detail'); ?></label>
                        </th>
                        <td class="forminp forminp-text">                          
                            <textarea cols='40' rows='5' name='rs_reward_addremove_reason' id="rs_reward_addremove_reason" required='required'></textarea>
                            <div class='rs_add_remove_points_reason_error' style="color: red;font-size:14px;"></div>
                        </td>
                    </tr>
                    <tr valign='top'>
                        <td>
                            <input type='button' name='rs_remove_points' id='rs_remove_points'  class='button-primary' value='Remove Points'/>                            
                            <img class="gif_rs_sumo_reward_button_for_remove" src="<?php echo WP_PLUGIN_URL; ?>/rewardsystem/img/update.gif" style="width:32px;height:32px;position:absolute"/>
                        </td>
                        <td>
                            <input type='button' name='rs_add_points' id='rs_add_points' class='button-primary' value='Add Points'/>
                            <img class="gif_rs_sumo_reward_button_for_add" src="<?php echo WP_PLUGIN_URL; ?>/rewardsystem/img/update.gif" style="width:32px;height:32px;position:absolute"/><br>
                        </td>
                    </tr>
                    <tr valign='top'>
                        <td colspan="2">
                            <div class='rs_add_remove_points' style="color: green;font-size:18px;"></div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </form>   
        <?php
    }

    public static function ajax_function_for_add() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('.gif_rs_sumo_reward_button_for_add').css('display', 'none');
                jQuery('#rs_add_points').click(function () {

                    var enteredpoints = jQuery('#rs_reward_addremove_points').val();
                    var reason = jQuery('#rs_reward_addremove_reason').val();
                    var usertype = jQuery('#rs_select_user_type').val();
                    var includeuser = jQuery('#rs_select_to_include_customers').val();
                    var excludeuser = jQuery('#rs_select_to_exclude_customers').val();
                    if (enteredpoints == '' && reason == '') {
                        jQuery('.rs_add_remove_points_error').fadeIn();
                        jQuery('.rs_add_remove_points_error').html('Please Enter Points');
                        jQuery('.rs_add_remove_points_error').fadeOut(5000);
                        jQuery('.rs_add_remove_points_reason_error').fadeIn();
                        jQuery('.rs_add_remove_points_reason_error').html('Please Enter Reason');
                        jQuery('.rs_add_remove_points_reason_error').fadeOut(5000);
                        jQuery('.gif_rs_sumo_reward_button_for_add').css('display', 'none');
                        return false;
                    } else if (enteredpoints == '') {
                        jQuery('.rs_add_remove_points_error').fadeIn();
                        jQuery('.rs_add_remove_points_error').html('Please Enter Points');
                        jQuery('.rs_add_remove_points_error').fadeOut(5000);
                        jQuery('.gif_rs_sumo_reward_button_for_add').css('display', 'none');
                        return false;
                    } else if (reason == '') {
                        jQuery('.rs_add_remove_points_reason_error').fadeIn();
                        jQuery('.rs_add_remove_points_reason_error').html('Please Enter Reason');
                        jQuery('.rs_add_remove_points_reason_error').fadeOut(5000);
                        jQuery('.gif_rs_sumo_reward_button_for_add').css('display', 'none');
                        return false;
                    } else {
                        jQuery('.gif_rs_sumo_reward_button_for_add').css('display', 'inline-block');
                        if (jQuery('#rs_select_user_type').val() === '1') {
                            jQuery(this).attr('data-clicked', '1');
                            var dataclicked = jQuery(this).attr('data-clicked');
                            var data = ({
                                action: 'rsaddpointforuser',
                                proceed: dataclicked,
                                usertype: usertype
                            });
                            function getData(id) {
                                return jQuery.ajax({
                                    type: 'POST',
                                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                    data: ({
                                        action: 'rssplitusertoaddpoints',
                                        ids: id,
                                        points: enteredpoints,
                                        reason: reason,
                                        //proceedanyway: dataclicked
                                    }),
                                    success: function (response) {
                                        if (response) {
                                            if (response.success) {
                                                jQuery('.rs_add_remove_points').fadeIn();
                                                jQuery('.rs_add_remove_points').html('Points Successfully added to ' + response.success + ' users');
                                                jQuery('.rs_add_remove_points').fadeOut(15000);
                                                jQuery('#rs_reward_addremove_points').val("");
                                                jQuery('#rs_reward_addremove_reason').val("");
                                            }
                                            if (response.failure) {
                                                if (response.failure > 0) {
                                                    jQuery('.rs_add_remove_points').fadeIn();
                                                    jQuery('.rs_add_remove_points').html('Points failed to add ' + response.failure + ' user');
                                                    jQuery('.rs_add_remove_points').fadeOut(15000);
                                                }
                                            }
                                            jQuery('.gif_rs_sumo_reward_button_for_add').css('display', 'none');
                                        }
                                    },
                                    dataType: 'json',
                                    async: false
                                });
                            }
                            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data,
                                    function (response) {
                                        //console.log(response);
                                        if (response != 'success') {
                                            var j = 1;
                                            var i, j, temparray, chunk = 10;
                                            for (i = 0, j = response.length; i < j; i += chunk) {
                                                temparray = response.slice(i, i + chunk);
                                                getData(temparray);
                                            }
                                            jQuery.when(getData()).done(function (a1) {
                                                console.log('Ajax Done Successfully');
                                            });
                                        }
                                    }, 'json');
                        } else if (jQuery('#rs_select_user_type').val() === '2') {
                            jQuery(this).attr('data-clicked', '1');
                            var dataclicked = jQuery(this).attr('data-clicked');
                            var data = ({
                                action: 'rsaddpointforuser',
                                proceed: dataclicked,
                                usertype: usertype,
                                includeuser: includeuser
                            });
                            function getDataforinclude(id) {
                                return jQuery.ajax({
                                    type: 'POST',
                                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                    data: ({
                                        action: 'rssplitusertoaddpoints',
                                        ids: id,
                                        points: enteredpoints,
                                        reason: reason,
                                        //proceedanyway: dataclicked
                                    }),
                                    success: function (response) {
                                        console.log(response);
                                        if (response) {
                                            if (response.success) {
                                                jQuery('.rs_add_remove_points').fadeIn();
                                                jQuery('.rs_add_remove_points').html('Points Successfully added to ' + response.success + ' users');
                                                jQuery('.rs_add_remove_points').fadeOut(15000);
                                                jQuery('#rs_reward_addremove_points').val("");
                                                jQuery('#rs_reward_addremove_reason').val("");
                                            }
                                            if (response.failure) {
                                                if (response.failure > 0) {
                                                    jQuery('.rs_add_remove_points').fadeIn();
                                                    jQuery('.rs_add_remove_points').html('Points failed to add ' + response.failure + ' user');
                                                    jQuery('.rs_add_remove_points').fadeOut(15000);
                                                }
                                            }
                                            jQuery('.gif_rs_sumo_reward_button_for_add').css('display', 'none');
                                        }
                                    },
                                    dataType: 'json',
                                    async: false
                                });
                            }
                            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data,
                                    function (response) {
                                        if (response != 'success') {
                                            var j = 1;
                                            var i, j, temparray, chunk = 10;
                                            for (i = 0, j = response.length; i < j; i += chunk) {
                                                temparray = response.slice(i, i + chunk);
                                                getDataforinclude(temparray);
                                                //console.log(temparray);
                                            }
                                            jQuery.when(getDataforinclude()).done(function (a1) {
                                                console.log('Ajax Done Successfully');
                                            });
                                        }
                                    }, 'json');
                        } else {
                            jQuery(this).attr('data-clicked', '1');
                            var dataclicked = jQuery(this).attr('data-clicked');
                            var data = ({
                                action: 'rsaddpointforuser',
                                proceed: dataclicked,
                                usertype: usertype,
                                excludeuser: excludeuser
                            });
                            function getDataforexclude(id) {
                                return jQuery.ajax({
                                    type: 'POST',
                                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                    data: ({
                                        action: 'rssplitusertoaddpoints',
                                        ids: id,
                                        points: enteredpoints,
                                        reason: reason,
                                        //proceedanyway: dataclicked
                                    }),
                                    success: function (response) {
                                        if (response) {
                                            if (response.success) {
                                                jQuery('.rs_add_remove_points').fadeIn();
                                                jQuery('.rs_add_remove_points').html('Points Successfully added to ' + response.success + ' users');
                                                jQuery('.rs_add_remove_points').fadeOut(15000);
                                                jQuery('#rs_reward_addremove_points').val("");
                                                jQuery('#rs_reward_addremove_reason').val("");
                                            }
                                            if (response.failure) {
                                                if (response.failure > 0) {
                                                    jQuery('.rs_add_remove_points').fadeIn();
                                                    jQuery('.rs_add_remove_points').html('Points failed to add ' + response.failure + ' user');
                                                    jQuery('.rs_add_remove_points').fadeOut(15000);
                                                }
                                            }
                                            jQuery('.gif_rs_sumo_reward_button_for_add').css('display', 'none');
                                        }
                                    },
                                    dataType: 'json',
                                    async: false
                                });
                            }
                            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data,
                                    function (response) {
                                        //console.log(response);
                                        if (response != 'success') {
                                            var j = 1;
                                            var i, j, temparray, chunk = 10;
                                            for (i = 0, j = response.length; i < j; i += chunk) {
                                                temparray = response.slice(i, i + chunk);
                                                getDataforexclude(temparray);
                                                //console.log(temparray);
                                            }
                                            jQuery.when(getDataforexclude()).done(function (a1) {
                                                console.log('Ajax Done Successfully');
                                            });
                                        }
                                    }, 'json');
                        }

                    }
                });
            });
        </script>
        <?php
    }

    public static function process_ajax_to_split_users_for_add() {
        delete_option('fp_successfull_users_to_add');
        delete_option('fp_failed_users_to_add');
        if (isset($_POST['proceed'])) {

            if ($_POST['proceed'] == '1') {
                if ($_POST['usertype'] == '1') {
                    $array = get_users();
                    foreach ($array as $arrays) {
                        $userid[] = $arrays->ID;
                    }
                    echo json_encode($userid);
                } else if ($_POST['usertype'] == '2') {
                    $array = explode(',', $_POST['includeuser']);
                    foreach ($array as $arrays) {
                        $userid[] = $arrays;
                    }
                    echo json_encode($userid);
                } else if ($_POST['usertype'] == '3') {
                    $array = explode(',', $_POST['excludeuser']);
                    $alluser = get_users();
                    foreach ($alluser as $users) {
                        $id = $users->ID;
                        if (!in_array($id, $array)) {
                            $userid[] = $id;
                        }
                    }
                    echo json_encode($userid);
                }
            }
        }
        exit();
    }

    public static function process_ajax_to_add() {
        if (isset($_POST['ids'])) {
            $array = $_POST['ids'];
            global $woocommerce;
            global $wpdb;
            $table_name = $wpdb->prefix . 'rspointexpiry';
            $enablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
            foreach ($array as $arrays) {
                $noofdays = get_option('rs_point_to_be_expire');
                
                if (($noofdays != '0') && ($noofdays != '')) {
                    $date = time() + ($noofdays * 24 * 60 * 60);
                } else {
                    $date = '999999999999';
                }
                $restrictuserpoints = get_option('rs_max_earning_points_for_user');
                $user_id = $arrays;
                $my_rewards = $wpdb->get_results("SELECT SUM((earnedpoints-usedpoints)) as availablepoints FROM $table_name WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) and userid = $user_id", ARRAY_A);
                $userpoints = $my_rewards[0]['availablepoints'];
                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($user_id);
                $updatedpoints = $_POST['points'] + $totalpoints;
                $rs_new_points_to_add = $_POST['points'];
                if ($enablemaxpoints == 'yes') {
                    if ($restrictuserpoints != '' && $restrictuserpoints != 0) {
                        if (($totalpoints <= $restrictuserpoints)) {
                            if (($updatedpoints <= $restrictuserpoints)) {
                                $reasonindetail = $_POST['reason'];
                                $addedpoints = $_POST['points'];
                                $totalearnedpoints = $addedpoints;
                                RSPointExpiry::insert_earning_points($user_id, $rs_new_points_to_add, '0', $date, 'MAP', '', $totalearnedpoints, '0', $reasonindetail);
                                $equearnamt = RSPointExpiry::earning_conversion_settings($addedpoints);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($user_id);
                                RSPointExpiry::record_the_points($user_id, $rs_new_points_to_add, '0', $date, 'MAP', $equearnamt, '0', '', '', '', '', $reasonindetail, $totalpoints, '', '0');
                                $list_user_id[] = $user_id;
                            } else {
                                $insertpoints = $restrictuserpoints - $totalpoints;
                                $totalearnedpoints = $insertpoints;
                                RSPointExpiry::insert_earning_points($user_id, $insertpoints, '0', $date, 'MREPFU', '', $totalearnedpoints, '0', $reasonindetail);
                                $equearnamt = RSPointExpiry::earning_conversion_settings($addedpoints);
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($user_id);
                                RSPointExpiry::record_the_points($user_id, $insertpoints, '0', $date, 'MREPFU', $equearnamt, '0', '', '', '', '', $reasonindetail, $totalpoints, '', '0');
                                $list_user_id[] = $user_id;
                            }
                        }
                    } else {
                        $reasonindetail = $_POST['reason'];
                        $addedpoints = $_POST['points'];
                        $totalearnedpoints = $addedpoints;
                        RSPointExpiry::insert_earning_points($user_id, $addedpoints, '0', $date, 'MREPFU', '', $totalearnedpoints, '0', $reasonindetail);
                        $equearnamt = RSPointExpiry::earning_conversion_settings($addedpoints);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($user_id);
                        RSPointExpiry::record_the_points($user_id, $addedpoints, '0', $date, 'MREPFU', $equearnamt, '0', '', '', '', '', $reasonindetail, $totalpoints, '', '0');
                        $list_user_id[] = $user_id;
                    }
                } else {
                    $reasonindetail = $_POST['reason'];
                    $addedpoints = $_POST['points'];
                    $totalearnedpoints = $addedpoints;
                    RSPointExpiry::insert_earning_points($user_id, $addedpoints, '0', $date, 'MAP', '', $totalearnedpoints, '0', $reasonindetail);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($addedpoints);
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($user_id);
                    RSPointExpiry::record_the_points($user_id, $addedpoints, '0', $date, 'MAP', $equearnamt, '0', '', '', '', '', $reasonindetail, $totalpoints, '', '0');
                    $list_user_id[] = $user_id;
                }
            }
            $countuser = count($list_user_id);
            $oldcount = get_option('fp_successfull_users_to_add');
            $countusers = $countuser + $oldcount;
            update_option('fp_successfull_users_to_add', $countusers);
        } else {
            $array_response = array('success' => get_option('fp_successfull_users_to_add'), 'failure' => get_option('fp_failed_users_to_add'));
            echo json_encode($array_response);
        }
        exit();
    }

    public static function ajax_function_for_remove() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('.gif_rs_sumo_reward_button_for_remove').css('display', 'none');
                jQuery('#rs_remove_points').click(function () {

                    var enteredpoints = jQuery('#rs_reward_addremove_points').val();
                    var reason = jQuery('#rs_reward_addremove_reason').val();
                    var usertype = jQuery('#rs_select_user_type').val();
                    var includeuser = jQuery('#rs_select_to_include_customers').val();
                    var excludeuser = jQuery('#rs_select_to_exclude_customers').val();
                    if (enteredpoints == '' && reason == '') {
                        jQuery('.rs_add_remove_points_error').fadeIn();
                        jQuery('.rs_add_remove_points_error').html('Please Enter Points');
                        jQuery('.rs_add_remove_points_error').fadeOut(5000);
                        jQuery('.rs_add_remove_points_reason_error').fadeIn();
                        jQuery('.rs_add_remove_points_reason_error').html('Please Enter Reason');
                        jQuery('.rs_add_remove_points_reason_error').fadeOut(5000);
                        jQuery('.gif_rs_sumo_reward_button_for_remove').css('display', 'none');
                        return false;
                    } else if (enteredpoints == '') {
                        jQuery('.rs_add_remove_points_error').fadeIn();
                        jQuery('.rs_add_remove_points_error').html('Please Enter Points');
                        jQuery('.rs_add_remove_points_error').fadeOut(5000);
                        jQuery('.gif_rs_sumo_reward_button_for_remove').css('display', 'none');
                        return false;
                    } else if (reason == '') {
                        jQuery('.rs_add_remove_points_reason_error').fadeIn();
                        jQuery('.rs_add_remove_points_reason_error').html('Please Enter Reason');
                        jQuery('.rs_add_remove_points_reason_error').fadeOut(5000);
                        jQuery('.gif_rs_sumo_reward_button_for_remove').css('display', 'none');
                        return false;
                    } else {
                        jQuery('.gif_rs_sumo_reward_button_for_remove').css('display', 'inline-block');
                        if (jQuery('#rs_select_user_type').val() === '1') {
                            jQuery(this).attr('data-clicked', '1');
                            var dataclicked = jQuery(this).attr('data-clicked');
                            var data = ({
                                action: 'rsremovepointforuser',
                                proceed: dataclicked,
                                usertype: usertype
                            });
                            function getData(id) {
                                return jQuery.ajax({
                                    type: 'POST',
                                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                    data: ({
                                        action: 'rssplitusertoremovepoints',
                                        ids: id,
                                        points: enteredpoints,
                                        usertype: usertype,
                                        reason: reason,
                                        //proceedanyway: dataclicked
                                    }),
                                    success: function (response) {
                                        console.log(response);
                                        if (response) {
                                            if ((response.success > 0) && (response.failure == 0)) {
                                                jQuery('.rs_add_remove_points').fadeIn();
                                                if (response.success > 1) {
                                                    jQuery('.rs_add_remove_points').html('Points Successfully removed to ' + response.success + ' users');
                                                } else {
                                                    jQuery('.rs_add_remove_points').html('Points Successfully removed to ' + response.success + ' user');
                                                }
                                                jQuery('.rs_add_remove_points').fadeOut(15000);
                                                jQuery('#rs_reward_addremove_points').val("");
                                                jQuery('#rs_reward_addremove_reason').val("");
                                            }
                                            if ((response.success > 0) && (response.failure > 0)) {
                                                jQuery('.rs_add_remove_points').fadeIn();
                                                if ((response.success > 1) && (response.failure > 1)) {
                                                    jQuery('.rs_add_remove_points').html('Points Successfully removed to ' + response.success + ' users.Points failed to remove ' + response.failure + ' users');
                                                } else if ((response.success == 1) && (response.failure == 1)) {
                                                    jQuery('.rs_add_remove_points').html('Points Successfully removed to ' + response.success + ' user.Points failed to remove ' + response.failure + ' user');
                                                } else if ((response.success == 1) && (response.failure > 1)) {
                                                    jQuery('.rs_add_remove_points').html('Points Successfully removed to ' + response.success + ' user.Points failed to remove ' + response.failure + ' users');
                                                } else if ((response.success > 1) && (response.failure == 1)) {
                                                    jQuery('.rs_add_remove_points').html('Points Successfully removed to ' + response.success + ' users.Points failed to remove ' + response.failure + ' user');
                                                }
                                                jQuery('.rs_add_remove_points').fadeOut(15000);
                                            }
                                            if ((response.success == 0) && (response.failure > 0)) {
                                                jQuery('.rs_add_remove_points').fadeIn();
                                                if (response.failure > 1) {
                                                    jQuery('.rs_add_remove_points').html('Points failed to remove ' + response.failure + ' users');
                                                } else {
                                                    jQuery('.rs_add_remove_points').html('Points failed to remove ' + response.failure + ' user');
                                                }
                                                jQuery('.rs_add_remove_points').fadeOut(15000);
                                                jQuery('#rs_reward_addremove_points').val("");
                                                jQuery('#rs_reward_addremove_reason').val("");
                                            }
                                            jQuery('.gif_rs_sumo_reward_button_for_remove').css('display', 'none');
                                        }
                                    },
                                    dataType: 'json',
                                    async: false
                                });
                            }
                            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data,
                                    function (response) {
                                        console.log(response);
                                        if (response != 'success') {
                                            var j = 1;
                                            var i, j, temparray, chunk = 10;
                                            for (i = 0, j = response.length; i < j; i += chunk) {
                                                temparray = response.slice(i, i + chunk);
                                                getData(temparray);
                                                console.log(temparray);
                                            }
                                            jQuery.when(getData()).done(function (a1) {
                                                console.log('Ajax Done Successfully');
                                                //location.reload();
                                            });
                                        }
                                    }, 'json');
                        } else if (jQuery('#rs_select_user_type').val() === '2') {
                            jQuery(this).attr('data-clicked', '1');
                            var dataclicked = jQuery(this).attr('data-clicked');
                            var data = ({
                                action: 'rsremovepointforuser',
                                proceed: dataclicked,
                                usertype: usertype,
                                includeuser: includeuser
                            });
                            function getDataforinclude(id) {
                                return jQuery.ajax({
                                    type: 'POST',
                                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                    data: ({
                                        action: 'rssplitusertoremovepoints',
                                        ids: id,
                                        points: enteredpoints,
                                        reason: reason,
                                        usertype: usertype,
                                        //proceedanyway: dataclicked
                                    }),
                                    success: function (response) {
                                        console.log(response);
                                        if (response) {
                                            if ((response.success > 0) && (response.failure == 0)) {
                                                jQuery('.rs_add_remove_points').fadeIn();
                                                if (response.success > 1) {
                                                    jQuery('.rs_add_remove_points').html('Points Successfully removed to ' + response.success + ' users');
                                                } else {
                                                    jQuery('.rs_add_remove_points').html('Points Successfully removed to ' + response.success + ' user');
                                                }
                                                jQuery('.rs_add_remove_points').fadeOut(15000);
                                                jQuery('#rs_reward_addremove_points').val("");
                                                jQuery('#rs_reward_addremove_reason').val("");
                                            }
                                            if ((response.success > 0) && (response.failure > 0)) {
                                                jQuery('.rs_add_remove_points').fadeIn();
                                                if ((response.success > 1) && (response.failure > 1)) {
                                                    jQuery('.rs_add_remove_points').html('Points Successfully removed to ' + response.success + ' users.Points failed to remove ' + response.failure + ' users');
                                                } else if ((response.success == 1) && (response.failure == 1)) {
                                                    jQuery('.rs_add_remove_points').html('Points Successfully removed to ' + response.success + ' user.Points failed to remove ' + response.failure + ' user');
                                                } else if ((response.success == 1) && (response.failure > 1)) {
                                                    jQuery('.rs_add_remove_points').html('Points Successfully removed to ' + response.success + ' user.Points failed to remove ' + response.failure + ' users');
                                                } else if ((response.success > 1) && (response.failure == 1)) {
                                                    jQuery('.rs_add_remove_points').html('Points Successfully removed to ' + response.success + ' users.Points failed to remove ' + response.failure + ' user');
                                                }
                                                jQuery('.rs_add_remove_points').fadeOut(15000);
                                            }
                                            if ((response.success == 0) && (response.failure > 0)) {
                                                jQuery('.rs_add_remove_points').fadeIn();
                                                if (response.failure > 1) {
                                                    jQuery('.rs_add_remove_points').html('Points failed to remove ' + response.failure + ' users');
                                                } else {
                                                    jQuery('.rs_add_remove_points').html('Points failed to remove ' + response.failure + ' user');
                                                }
                                                jQuery('.rs_add_remove_points').fadeOut(15000);
                                                jQuery('#rs_reward_addremove_points').val("");
                                                jQuery('#rs_reward_addremove_reason').val("");
                                            }
                                            jQuery('.gif_rs_sumo_reward_button_for_remove').css('display', 'none');
                                        }
                                    },
                                    dataType: 'json',
                                    async: false
                                });
                            }
                            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data,
                                    function (response) {
                                        console.log(response);
                                        if (response != 'success') {
                                            var j = 1;
                                            var i, j, temparray, chunk = 10;
                                            for (i = 0, j = response.length; i < j; i += chunk) {
                                                temparray = response.slice(i, i + chunk);
                                                getDataforinclude(temparray);
                                                console.log(temparray);
                                            }
                                            jQuery.when(getDataforinclude()).done(function (a1) {
                                                console.log('Ajax Done Successfully');
                                                //location.reload();
                                            });
                                        }
                                    }, 'json');
                        } else {
                            jQuery(this).attr('data-clicked', '1');
                            var dataclicked = jQuery(this).attr('data-clicked');
                            var data = ({
                                action: 'rsremovepointforuser',
                                proceed: dataclicked,
                                usertype: usertype,
                                excludeuser: excludeuser
                            });
                            function getDataforexclude(id) {
                                return jQuery.ajax({
                                    type: 'POST',
                                    url: "<?php echo admin_url('admin-ajax.php'); ?>",
                                    data: ({
                                        action: 'rssplitusertoremovepoints',
                                        ids: id,
                                        points: enteredpoints,
                                        reason: reason,
                                        usertype: usertype,
                                        //proceedanyway: dataclicked
                                    }),
                                    success: function (response) {
                                        console.log(response);
                                        if (response) {
                                            if ((response.success > 0) && (response.failure == 0)) {
                                                jQuery('.rs_add_remove_points').fadeIn();
                                                if (response.success > 1) {
                                                    jQuery('.rs_add_remove_points').html('Points Successfully removed to ' + response.success + ' users');
                                                } else {
                                                    jQuery('.rs_add_remove_points').html('Points Successfully removed to ' + response.success + ' user');
                                                }
                                                jQuery('.rs_add_remove_points').fadeOut(15000);
                                                jQuery('#rs_reward_addremove_points').val("");
                                                jQuery('#rs_reward_addremove_reason').val("");
                                            }
                                            if ((response.success > 0) && (response.failure > 0)) {
                                                jQuery('.rs_add_remove_points').fadeIn();
                                                if ((response.success > 1) && (response.failure > 1)) {
                                                    jQuery('.rs_add_remove_points').html('Points Successfully removed to ' + response.success + ' users.Points failed to remove ' + response.failure + ' users');
                                                } else if ((response.success == 1) && (response.failure == 1)) {
                                                    jQuery('.rs_add_remove_points').html('Points Successfully removed to ' + response.success + ' user.Points failed to remove ' + response.failure + ' user');
                                                } else if ((response.success == 1) && (response.failure > 1)) {
                                                    jQuery('.rs_add_remove_points').html('Points Successfully removed to ' + response.success + ' user.Points failed to remove ' + response.failure + ' users');
                                                } else if ((response.success > 1) && (response.failure == 1)) {
                                                    jQuery('.rs_add_remove_points').html('Points Successfully removed to ' + response.success + ' users.Points failed to remove ' + response.failure + ' user');
                                                }
                                                jQuery('.rs_add_remove_points').fadeOut(15000);
                                            }
                                            if ((response.success == 0) && (response.failure > 0)) {
                                                jQuery('.rs_add_remove_points').fadeIn();
                                                if (response.failure > 1) {
                                                    jQuery('.rs_add_remove_points').html('Points failed to remove ' + response.failure + ' users');
                                                } else {
                                                    jQuery('.rs_add_remove_points').html('Points failed to remove ' + response.failure + ' user');
                                                }
                                                jQuery('.rs_add_remove_points').fadeOut(15000);
                                                jQuery('#rs_reward_addremove_points').val("");
                                                jQuery('#rs_reward_addremove_reason').val("");
                                            }
                                            jQuery('.gif_rs_sumo_reward_button_for_remove').css('display', 'none');
                                        }
                                    },
                                    dataType: 'json',
                                    async: false
                                });
                            }
                            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", data,
                                    function (response) {
                                        console.log(response);
                                        if (response != 'success') {
                                            var j = 1;
                                            var i, j, temparray, chunk = 10;
                                            for (i = 0, j = response.length; i < j; i += chunk) {
                                                temparray = response.slice(i, i + chunk);
                                                getDataforexclude(temparray);
                                                console.log(temparray);
                                            }
                                            jQuery.when(getDataforexclude()).done(function (a1) {
                                                console.log('Ajax Done Successfully');
                                                //location.reload();
                                            });
                                        }
                                    }, 'json');
                        }
                    }
                });
            });
        </script>
        <?php
    }

    public static function process_ajax_to_split_users_for_remove() {
        delete_option('fp_successfull_users_to_remove');
        delete_option('fp_failed_users_to_add_to_remove');
        if (isset($_POST['proceed'])) {
            if ($_POST['proceed'] == '1') {
                if ($_POST['usertype'] == '1') {
                    $array = get_users();
                    foreach ($array as $arrays) {
                        $userid[] = $arrays->ID;
                    }
                    echo json_encode($userid);
                } else if ($_POST['usertype'] == '2') {
                    $array = explode(',', $_POST['includeuser']);
                    foreach ($array as $arrays) {
                        $userid[] = $arrays;
                    }
                    echo json_encode($userid);
                } else if ($_POST['usertype'] == '3') {
                    $array = explode(',', $_POST['excludeuser']);
                    $alluser = get_users();
                    foreach ($alluser as $users) {
                        $id = $users->ID;
                        if (!in_array($id, $array)) {
                            $userid[] = $id;
                        }
                    }
                    echo json_encode($userid);
                }
            }
        }
        exit();
    }

    public static function process_ajax_to_remove() {
        if (isset($_POST['ids'])) {
            if (isset($_POST['usertype'])) {
                $array = $_POST['ids'];
                global $woocommerce;
                global $wpdb;
                $table_name = $wpdb->prefix . 'rspointexpiry';
                $enablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
                foreach ($array as $arrays) {
                    $noofdays = get_option('rs_point_to_be_expire');
                   

                    $date = '999999999999';

                    $user_id = $arrays;
                    $my_rewards = $wpdb->get_results("SELECT SUM((earnedpoints-usedpoints)) as availablepoints FROM $table_name WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) and userid = $user_id", ARRAY_A);
                    $userpoints = $my_rewards[0]['availablepoints'];
                    $updatedpoints = $userpoints - $_POST['points'];
                    $reasonindetail = $_POST['reason'];
                    $removedpoints = $_POST['points'];
                    if ($removedpoints <= $userpoints) {
                        $pointsredeemed = RSPointExpiry::perform_calculation_with_expiry($removedpoints, $user_id);
                        $equredeemamt = RSPointExpiry::earning_conversion_settings($removedpoints);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($user_id);
                        RSPointExpiry::record_the_points($user_id, '0', $removedpoints, $date, 'MRP', '', $equredeemamt, '', '', '', '', $reasonindetail, $totalpoints, '', '0');
                        $list_user_id[] = $user_id;
                    }
                }
                if ($_POST['usertype'] == '1') {
                    $alluser = count_users();
                    $countuser = $alluser['total_users'];
                    $successcount = isset($list_user_id) ? count($list_user_id) : 0;
                    $failurecount = $countuser - $successcount;
                    $oldcount = get_option('fp_successfull_users_to_remove');
                    $countusers = $countuser + $oldcount;
                    update_option('fp_successfull_users_to_remove', $successcount);
                    update_option('fp_failed_users_to_add_to_remove', $failurecount);
                } else if ($_POST['usertype'] == '2') {
                    $countuser = count($array);
                    $successcount = isset($list_user_id) ? count($list_user_id) : 0;
                    $failurecount = $countuser - $successcount;
                    $oldcount = get_option('fp_successfull_users_to_remove');
                    $countusers = $countuser + $oldcount;
                    update_option('fp_successfull_users_to_remove', $successcount);
                    update_option('fp_failed_users_to_add_to_remove', $failurecount);
                } else if ($_POST['usertype'] == '3') {
                    $alluser = count_users();
                    $countuser = $alluser['total_users'];
                    $exccountuser = count($array);
                    $updatedcount = $countuser - $exccountuser;
                    $successcount = isset($list_user_id) ? count($list_user_id) : 0;
                    $failurecount = $updatedcount - $successcount;
                    $oldcount = get_option('fp_successfull_users_to_remove');
                    $countusers = $countuser + $oldcount;
                    update_option('fp_successfull_users_to_remove', $successcount);
                    update_option('fp_failed_users_to_add_to_remove', $failurecount);
                }
            }
        } else {
            $array_response = array('success' => get_option('fp_successfull_users_to_remove'), 'failure' => get_option('fp_failed_users_to_add_to_remove'));
            echo json_encode($array_response);
        }
        exit();
    }

    public static function rs_validation_for_input_field_in_add_remove_points() {
        ?>

        <script type="text/javascript">
            jQuery(function () {
                jQuery('body').on('blur', '#rs_reward_addremove_points[type=text]', function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });

                    return this;
                });

                jQuery('body').on('keyup change', '#rs_reward_addremove_points[type=text]', function () {
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



                jQuery("body").click(function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });

                });
            });
        </script>
        <?php
    }

}

new RSFunctionForAddorRemovePoints();
