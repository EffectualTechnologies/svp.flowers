<?php

class RSfunctionformforSendPoints {

    public function __construct() {

        add_action('woocommerce_admin_field_rs_select_user_for_send', array($this, 'rs_select_user_to_send_point'));
        add_action('wp_ajax_rs_send_form_value', array($this, 'save_selected_user'));
        add_action('wp_ajax_nopriv_rs_send_form_value', array($this, 'save_selected_user'));
        add_shortcode('rssendpoints', array($this, 'frontendformforsendpoints'));
        add_action('wp_ajax_rs_send_form_value', array($this, 'process_send_points_to_users'));
        add_action('admin_head',array($this,'script_function'));
    }
public static function script_function(){
   ?>
                    <script type ="text/javascript">
                        jQuery(document).ready(function () {
                             var restrict_point_enable= <?php echo get_option('rs_limit_for_send_point');?>;
                             
                             
                                   if(restrict_point_enable=='1'){
                                       
                                       jQuery('#rs_limit_send_points_request').parent().parent().hide();
                                   }else{
                                        jQuery('#rs_limit_send_points_request').parent().parent().hide();
                                   }
                                     jQuery('#rs_limit_for_send_point').change(function () {
                                       var currentvalue=jQuery(this).val();                                    
                                        if(currentvalue=='1'){                                      
                                       jQuery('#rs_limit_send_points_request').parent().parent().show();
                                   }else{
                                        jQuery('#rs_limit_send_points_request').parent().parent().hide();
                                   }
                                     });
                                   
                        });
                        </script>
                        <?php
                       
}
    public static function rs_select_user_to_send_point() {
        global $woocommerce;
        ?>
        <style type="text/css">
            .chosen-container-single {
                position:absolute;
            }

        </style>
        <?php
        echo RSJQueryFunction::rs_common_ajax_function_to_select_user('rs_select_users_list_for_send_point');
        ?>
        <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_select_users_list_for_send_point"><?php _e('Select the  User ', 'rewardsystem'); ?></label>
                </th>
                <td>
                    <select name="rs_select_users_list_for_send_point[]" style="width:343px;" multiple="multiple" id="rs_select_users_list_for_send_point" class="short rs_select_users_list_for_send_point">
                        <?php
                        $json_ids = array();
                        $getuser = get_option('rs_select_users_list_for_send_point');
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
                    <label for="rs_select_users_list_for_send_point"><?php _e('Select the  User', 'rewardsystem'); ?></label>
                </th>
                <td>
                    <input type="hidden" class="wc-customer-search" name="rs_select_users_list_for_send_point" id="rs_select_users_list_for_send_point" data-multiple="true" data-placeholder="<?php _e('Search Users', 'rewardsystem'); ?>" data-selected="<?php
                           $json_ids = array();
                           $getuser = get_option('rs_select_users_list_for_send_point');
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

    public static function frontendformforsendpoints() {
        global $woocommerce;
        global $wp_roles;

        if (get_option('rs_enable_msg_for_send_point') == '1') {
            if (is_user_logged_in()) {
                $user_ID = get_current_user_id();

                if (RSPointExpiry::get_sum_of_total_earned_points($user_ID) > 0) {
                    ob_start();
                    $userid = get_current_user_id();
                    $user_ID = get_current_user_id();
                    $currentuserpoints = RSPointExpiry::get_sum_of_total_earned_points($user_ID);
                    $getusers = get_option('rs_select_users_list_for_send_point');

                    echo '<form id="sendpoint_form" method="post" enctype="multipart/form-data">';
                    echo '<div class ="rs_total_reward_value"><p><label><b>' . get_option("rs_total_send_points_request") . '</b></label></p><p><input type = "text" id = "rs_total_send_points_request" name = "rs_total_send_points_request" readonly="readonly" value="' . $currentuserpoints . '"> </p></div>';

                    echo '<div class ="rs_total_reward_value_send"><p><label><b>' . get_option("rs_points_to_send_request") . '</b></label></p><p><input type = "text" id = "rs_total_reward_value_send" name = "rs_total_reward_value_send" value=""></p></div>';
                    echo '<div class = "error" for = "rs_total_reward_value_send" id ="points_number_error">' . addslashes(get_option("rs_err_when_point_is_not_number")) . '</div>';
                   $limitmessage= get_option("rs_err_when_point_greater_than_limit");
                   $value=get_option('rs_limit_send_points_request');
                   if($value!=''&&$value!=0){
                   $replace=str_replace('{limitpoints}',$value,$limitmessage);
                    echo '<div class = "error" for = "rs_total_reward_value_send" id ="points_limit_error">' . addslashes($replace) . '</div>';
                   }
                    echo '<div class = "error" for = "rs_total_reward_value_send" id ="points_empty_error">' . addslashes(get_option("rs_err_when_point_field_empty")) . '</div>';                    
                    echo '<div class ="rs_select_user"><p><label><b>' . addslashes(get_option("rs_select_user_label")) . '</b></label></p><p>  <select name="rs_select_user"  id="rs_select_user" class="short rs_select_user">  <option value=""> Choose User </option></p></div>';
                    $getusers = get_option('rs_select_users_list_for_send_point');
                    $currentuserid = get_current_user_id();
                    $usermeta = get_user_meta($currentuserid, 'rs_selected_user', true);
                    if ($getusers != '') {
                        if (!is_array($getusers)) {
                            $userids = array_filter(array_map('absint', (array) explode(',', $getusers)));
                            foreach ($userids as $userid) {
                                $user = get_user_by('id', $userid);
                                ?>
                                <option value="<?php echo $userid; ?>" <?php echo $usermeta == $userid ? "selected=selected" : '' ?>><?php echo esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')'; ?></option>
                                <?php
                            }
                        } else {
                            $userids = $getusers;
                            foreach ($userids as $userid) {
                                $user = get_user_by('id', $userid);
                                ?>
                                <option value="<?php echo $userid; ?>" <?php echo $usermeta == $userid ? "selected=selected" : '' ?>><?php echo esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')'; ?></option>
                                <?php
                            }
                        }
                    }
                    ?>
                    </select>  
                    <style type="text/css">
                        .error{
                            color: red;                            
                        }
                    </style>
                    <?php
                    echo '<div class = "error" for = "rs_total_reward_value_send" id ="user_empty_error">' . addslashes(get_option("rs_err_for_empty_user")) . '</div>';
                    echo '<div class ="rs_points_submit"><input type = "submit" name= "rs_send_points_submit_button" value="' . addslashes(get_option("rs_select_points_submit_label")) . '" id="rs_send_points_submit_button"></div>';
                    echo '<div class = "success_info" for = "rs_send_points_submit_button" id ="sendpoint_form_success_info"><b>' . addslashes(get_option("rs_message_send_point_request_submitted")) . '</b></div>';
                    echo '</form>';
                    ?>
                    <script type ="text/javascript">
                        jQuery(document).ready(function () {
                            var sendpoints_current_user_points = "<?php $user_ID = get_current_user_id();
                            echo $currentuserpoints = RSPointExpiry::get_sum_of_total_earned_points($user_ID); ?>";
                            jQuery(".error").hide();
                            jQuery(".success_info").hide();

                            jQuery("#rs_send_points_submit_button").click(function () {

                                var value = jQuery('#rs_select_user').val();

                                var send_points = jQuery("#rs_total_reward_value_send").val();
                                var send_points_validated = /^[0-9\b]+$/.test(send_points);
                                   var restrictpoint= <?php if( get_option('rs_limit_send_points_request')!=''){
                                       echo get_option('rs_limit_send_points_request');
                                   }else{
                                     echo '0';  
                                   }
?>;
                                  
                                   var restrict_point_enable= <?php echo get_option('rs_limit_for_send_point');?>;
                                   if(restrict_point_enable=='1'){
                                    if(restrictpoint!=''&&restrictpoint!='0'){
                                if(send_points>restrictpoint){
                                    jQuery("#points_limit_error").fadeIn().delay(5000).fadeOut();
                                    return false;
                                }
                                }
                            }
                                   
                                   
                                if (send_points == "") {
                                    jQuery("#points_empty_error").fadeIn().delay(5000).fadeOut();
                                    return false;
                                } else {

                                    jQuery("#points_empty_error").hide();
                                    if (send_points_validated == false) {
                                        jQuery("#points_number_error").fadeIn().delay(5000).fadeOut();
                                        return false;
                                    } else {

                                        jQuery("#points_number_error").hide();
                                    }

                                }
                                if(value == ''){
                                    jQuery("#user_empty_error").fadeIn().delay(5000).fadeOut();
                                    return false;
                                }
                                jQuery(".success_info").show();
                                jQuery(".success_info").fadeOut(3000);
                                jQuery("#sendpoint_form")[0].reset();
                                var send_request_user_id = <?php echo get_current_user_id(); ?>;
                    <?php
                    $user_details = get_user_by('id', get_current_user_id());
                    ?>

                                var send_request_user_name = "<?php echo $user_details->user_login; ?>";
                                var send_default_status = "Due";
                                var send_form_params = ({
                                    action: "rs_send_form_value",
                                    points_to_send: send_points,
                                    selecteduserforsend: value,
                                    userid_of_send_request: send_request_user_id,
                                    username_of_send_request: send_request_user_name,
                                    sender_current_points: sendpoints_current_user_points,
                                    send_default_status: send_default_status,
                                });
                                jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", send_form_params, function (response) {
                                    console.log('Got this from the server: ' + response);
                                });
                                return false;
                            });



                        });
                    </script>
                    <?php
                    $getcontent = ob_get_clean();
                    return $getcontent;
                }
            }
        }
    }

    public static function save_selected_user() {
        $getpostvalue = $_POST['selecteduserforsend'];
        $currentuserid = get_current_user_id();
        update_user_meta($currentuserid, 'rs_selected_user', $getpostvalue);
    }

    public static function process_send_points_to_users() {
        global $wpdb;

        if (isset($_POST['points_to_send']) && isset($_POST['selecteduserforsend']) && ($_POST['selecteduserforsend'] != '')) {

            $sender_userid = $_POST['userid_of_send_request'];
            $sender_username = $_POST['username_of_send_request'];
            $points_to_be_send = $_POST['points_to_send'];
            $current_points_for_user = $_POST['sender_current_points'];
            $selected_user = $_POST['selecteduserforsend'];
            $table_name = $wpdb->prefix . "sumo_reward_send_point_submitted_data";
            $user_id = get_current_user_id();
            $noofdays = get_option('rs_point_to_be_expire');
                   
            if (($noofdays != '0') && ($noofdays != '')) {
                $date =   time() +($noofdays * 24 * 60 * 60);
            } else {
                $date = '999999999999';
            }
            $default_status_of_send_request = $_POST['send_default_status'];
            $wpdb->insert($table_name, array('userid' => $sender_userid, 'userloginname' => $sender_username, 'pointstosend' => $points_to_be_send, 'sendercurrentpoints' => $current_points_for_user, 'status' => $default_status_of_send_request, 'selecteduser' => $selected_user, 'date' => date('Y-m-d H:i:s')));
            $redeempoints = RSPointExpiry::perform_calculation_with_expiry($points_to_be_send, $user_id);
            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($user_id);
            RSPointExpiry::record_the_points($user_id, '0', $points_to_be_send,$date, 'SENPM', '0', $equredeemamt, '0', '0', '0', '0', '', $totalpoints, $selected_user, '0');
        }
        exit();
    }

}

new RSfunctionformforSendPoints();
