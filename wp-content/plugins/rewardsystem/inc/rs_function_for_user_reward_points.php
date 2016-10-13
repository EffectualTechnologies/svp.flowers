<?php

class RSFunctionForUserRewardPoints {

    public function __construct() {
        add_action('woocommerce_admin_field_rs_wplist_for_user_reward_points', array($this, 'rs_list_of_user_reward_points_log'));
    }

    /*
     * Function to List all user reward points in User Reward Points.
     */

    public static function rs_list_of_user_reward_points_log() {

        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        ?>
        <style type="text/css">
            p.submit {
                display:none;
            }
            #mainforms {
                display:none;
            }
        </style>
        <?php
        if ((!isset($_GET['view'])) && (!isset($_GET['edit']))) {
            $newwp_list_table_for_users = new WP_List_Table_for_Users();
            $newwp_list_table_for_users->prepare_items();
            $newwp_list_table_for_users->search_box('Search Users', 'search_id');
            $newwp_list_table_for_users->display();
        } elseif (isset($_GET['view'])) {
            $newwp_list_table_for_users = new WP_List_Table_for_View_Log();
            $newwp_list_table_for_users->prepare_items();
            $newwp_list_table_for_users->search_box('Search', 'search_id');
            $newwp_list_table_for_users->display();
            ?>
            <a href="<?php echo remove_query_arg(array('view'), get_permalink()); ?>">Go Back</a>
            <?php
        } else {
            $user_ID = $_GET['edit'];
            $noofdays = get_option('rs_point_to_be_expire');
           
            if (($noofdays != '0') && ($noofdays != '')) {
                $date =   time() +($noofdays * 24 * 60 * 60);
            } else {
                $date = '999999999999';
            }
            if (isset($_POST['my_reward_points']) != '') {
                $earned_points = $_POST['my_reward_points'];
                if (isset($_POST['submitrs'])) {
                    $reason = $_POST['rs_reward_edit_reason'];
                    RSPointExpiry::insert_earning_points($user_ID, $earned_points, '0', $date, 'MAURP', '', $earned_points, '', $reason);
                    $equearnamt = RSPointExpiry::earning_conversion_settings($earned_points);
                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($user_ID);
                    RSPointExpiry::record_the_points($user_ID, $earned_points, '0', $date, 'MAURP', $equearnamt, '0', '0', '0', '0', '0', $reason, $totalpoints, '', '0');
                }
                if (isset($_POST['rs_remove_point'])) {

                    $getusermeta = $wpdb->get_results("SELECT SUM((earnedpoints-usedpoints)) as availablepoints FROM $table_name WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) and userid=$user_ID", ARRAY_A);
                    $currentpoints = $getusermeta[0]['availablepoints'];
                    if ($_POST['my_reward_points'] <= $currentpoints) {
                        $used_points = $_POST['my_reward_points'];
                        $userid = $user_ID;
                        $reason = $_POST['rs_reward_edit_reason'];
                        $pointsredeemed = RSPointExpiry::perform_calculation_with_expiry($used_points, $userid);
                        $equredeemamt = RSPointExpiry::earning_conversion_settings($used_points);
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($userid);
                        RSPointExpiry::record_the_points($userid, '0', $used_points, $date, 'MRURP', '0', $equredeemamt, '0', '0', '0', '0', $reason, $totalpoints, '', '0');
                        $newredirect = add_query_arg('saved', 'true', get_permalink());
                        wp_safe_redirect($newredirect);
                        exit();
                    } else {
                        echo '<span style="color: red;">You entered point is more than the current points</span>';
                    }
                }
            }
            $getusermeta = $wpdb->get_results("SELECT SUM((earnedpoints-usedpoints)) as availablepoints FROM $table_name WHERE earnedpoints-usedpoints NOT IN(0) and expiredpoints IN(0) and userid=$user_ID", ARRAY_A);
            $my_rewards = $getusermeta[0]['availablepoints'];
            ?>
            <style type = "text/css">
                p.submit {
                    display:none;
                }
                #mainforms {
                    display:none;
                }
            </style>
            <h3><?php _e('Update User Reward Points', 'rewardsystem'); ?></h3>
            <table class="form-table">
                <tr valign ="top">
                    <th class="titledesc" scope="row">
                        <label for="rs_reward_current_user_points"><?php _e('Current Points for User', 'rewardsystem'); ?></label>
                    </th>
                    <td class="forminp forminp-text">
                        <input type="text" class=""  style="min-width:150px;" readonly="readonly" id="my_current_reward_points" value="<?php
                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                        echo round($my_rewards, $roundofftype);
                        ?>"/>
                    </td>
                </tr>
                <tr valign="top">
                    <th class="titledesc" scope="row">
                        <label for="rs_reward_addremove_points"><?php _e('Enter Points', 'rewardsystem'); ?></label>
                    </th>
                    <td class="forminp forminp-text">
                        <input type="text" class="" value="" style="min-width:150px;" required='required' id="my_reward_points" name="my_reward_points">
                    </td>
                </tr>

                <tr valign="top">
                    <th class="titledesc" scope="row">
                        <label for="rs_reward_edit_reason"><?php _e('Reason in Detail'); ?></label>
                    </th>
                    <td class="forminp forminp-text">
                        <textarea cols='40' rows='5' name='rs_reward_edit_reason' required='required'></textarea>
                    </td>
                </tr>

                <tr valign="top">
                    <td>
                        <input type='submit' name='submitrs' id='submitrs'  class='button-primary' value='Add Points'/>

                    </td>
                    <td style="width:10px;">
                        <input type='submit' name='rs_remove_point' id='rs_remove_point' class='button-primary' value='Remove Points' />
                    </td>
                    <td>
                        <a href="<?php echo remove_query_arg(array('edit', 'saved'), get_permalink()); ?>"><input type='submit' name='rs_go_back' id='rs_go_back' class='button-primary' value='Go Back'/></a>
                    </td>

                </tr>
                <tr valign="top">

                </tr>
            </table>
            <?php
        }
    }

}

new RSFunctionForUserRewardPoints();
