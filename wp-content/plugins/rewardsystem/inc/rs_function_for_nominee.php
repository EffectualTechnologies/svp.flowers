<?php
class RSFunctionForNominee{
    
    public function __construct() {
        
        add_action('admin_head', array($this, 'rs_chosen_for_nominee_tab'));
        
        add_action('admin_head',array($this,'show_or_hide'));
        
        add_action('woocommerce_admin_field_rs_select_nominee_for_user', array($this, 'rs_select_user_as_nominee'));

        add_action('woocommerce_update_options_rewardsystem_nominee', array($this, 'save_data_for_rs_select_user_role_as_nominee'));
        
        add_action('woocommerce_update_options_rewardsystem_nominee', array($this, 'save_data_for_rs_select_user_as_nominee'));
        
        add_action('woocommerce_update_options_rewardsystem_nominee', array($this, 'save_data_for_rs_select_user_list_as_nominee'));        
        
        add_action('woocommerce_admin_field_rs_select_nominee_for_user_in_checkout', array($this, 'rs_select_user_as_nominee_in_checkout'));
        
        if (get_option('rs_show_hide_nominee_field_in_checkout') == '1') {                        

            add_action('woocommerce_after_order_notes', array($this, 'ajax_for_saving_nominee_in_checkout'));

            add_action('woocommerce_after_order_notes', array($this, 'display_nominee_field_in_checkout'));
        }
        
        add_action('woocommerce_checkout_update_order_meta', array($this, 'save_selected_nominee_in_checkout'));
        
    }
    /*
     * Function to Select user as Nominee
     */

    public static function rs_select_user_as_nominee() {
        global $woocommerce;
        ?>
        <style type="text/css">
            .chosen-container-single {
                position:absolute;
            }

        </style>
        <?php
        echo RSJQueryFunction::rs_common_ajax_function_to_select_user('rs_select_users_list_for_nominee');
        ?>
        <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_select_users_list_for_nominee"><?php _e('Select the Users as Nominee', 'rewardsystem'); ?></label>
                </th>
                <td>
                    <select name="rs_select_users_list_for_nominee[]" style="width:343px;" multiple="multiple" id="rs_select_users_list_for_nominee" class="short rs_select_users_list_for_nominee">
                        <?php
                        $json_ids = array();
                        $getuser = get_option('rs_select_users_list_for_nominee');
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
                                <option value="<?php echo $userid; ?>" selected="selected"><?php echo esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email).')'; ?></option>
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
                    <label for="rs_select_users_list_for_nominee"><?php _e('Select the Users as Nominee', 'rewardsystem'); ?></label>
                </th>
                <td>
                    <input type="hidden" class="wc-customer-search" name="rs_select_users_list_for_nominee" id="rs_select_users_list_for_nominee" data-multiple="true" data-placeholder="<?php _e('Search Users', 'rewardsystem'); ?>" data-selected="<?php
                    $json_ids = array();
                    $getuser = get_option('rs_select_users_list_for_nominee');
                    if ($getuser != "") {
                        $listofuser = $getuser;
                        if (!is_array($listofuser)) {
                            $userids = array_filter(array_map('absint', (array) explode(',', $listofuser)));
                        } else {
                            $userids = $listofuser;
                        }

                        foreach ($userids as $userid) {
                            $user = get_user_by('id', $userid);
                            $json_ids[$user->ID] = esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email).')';
                        }echo esc_attr(json_encode($json_ids));
                    }
                    ?>" value="<?php echo implode(',', array_keys($json_ids)); ?>" data-allow_clear="true" />
                </td>
            </tr>
            <?php
        }
    }
    
    /*
     * Function for choosen in Select user role as Nominee
     */

    public static function rs_chosen_for_nominee_tab() {
        global $woocommerce;
        if (isset($_GET['page'])) {   
            if (isset($_GET['tab'])) {
                    if ($_GET['tab'] == 'rewardsystem_nominee') {
                        if ((float) $woocommerce->version > (float) ('2.2.0')) {
                            echo RSJQueryFunction::rs_common_select_function('#rs_select_users_role_for_nominee');
                             echo RSJQueryFunction::rs_common_select_function('#rs_select_users_role_for_nominee_checkout');
                        } else {
                            echo RSJQueryFunction::rs_common_chosen_function('#rs_select_users_role_for_nominee');
                               echo RSJQueryFunction::rs_common_chosen_function('#rs_select_users_role_for_nominee_checkout');
                        }
                    }
            }                           
        }
    }
    
    /*
     * Save the data for Select user as Nominee
     */

    public static function save_data_for_rs_select_user_role_as_nominee() {

        $getpostvalue = $_POST['rs_select_users_role_for_nominee'];

        update_option('rs_select_users_role_for_nominee', $getpostvalue);
    }
    
    public static function save_data_for_rs_select_user_list_as_nominee() {

        $getpostvalue = $_POST['rs_select_users_list_for_nominee'];

        update_option('rs_select_users_list_for_nominee', $getpostvalue);
        $getpostvalue1 = $_POST['rs_select_users_role_for_nominee_checkout'];

        update_option('rs_select_users_role_for_nominee_checkout', $getpostvalue1);
    }
    
    
    
        /*
     * Save the data for Select user as Nominee
     */

    public static function save_data_for_rs_select_user_as_nominee() {

        $getpostvalue = $_POST['rs_select_users_list_for_nominee_in_checkout'];

        update_option('rs_select_users_list_for_nominee_in_checkout', $getpostvalue);
    }
    
    
    
    public static function show_or_hide(){
        ?>
            <script type="text/javascript">
                jQuery(document).ready(function(){
                    
                   var currentvalue = jQuery('#rs_show_hide_nominee_field').val() ;
                   if(currentvalue == '1'){
                       jQuery('#rs_my_nominee_title').parent().parent().show();
                       jQuery('#rs_select_type_of_user_for_nominee').parent().parent().show();
                       
                       if(jQuery('#rs_select_type_of_user_for_nominee').val() == '1') {
                            jQuery('#rs_select_users_list_for_nominee').parent().parent().show();
                            jQuery('#rs_select_users_role_for_nominee').parent().parent().hide();
                        }else{
                            jQuery('#rs_select_users_list_for_nominee').parent().parent().hide();
                            jQuery('#rs_select_users_role_for_nominee').parent().parent().show();
                        }

                        jQuery('#rs_select_type_of_user_for_nominee').change(function(){
                         if(jQuery('#rs_select_type_of_user_for_nominee').val() == '1') {
                             jQuery('#rs_select_users_list_for_nominee').parent().parent().show();
                             jQuery('#rs_select_users_role_for_nominee').parent().parent().hide();
                         }else{
                             jQuery('#rs_select_users_list_for_nominee').parent().parent().hide();
                             jQuery('#rs_select_users_role_for_nominee').parent().parent().show();
                         } 
                        });
                   }else{
                       jQuery('#rs_my_nominee_title').parent().parent().hide();
                       jQuery('#rs_select_type_of_user_for_nominee').parent().parent().hide();
                       jQuery('#rs_select_users_list_for_nominee').parent().parent().hide();
                       jQuery('#rs_select_users_role_for_nominee').parent().parent().hide();
                   }
                   
                   jQuery('#rs_show_hide_nominee_field').change(function(){
                        var currentvalue = jQuery('#rs_show_hide_nominee_field').val() ;
                        if(currentvalue == '1'){
                            jQuery('#rs_my_nominee_title').parent().parent().show();
                            jQuery('#rs_select_type_of_user_for_nominee').parent().parent().show();

                            if(jQuery('#rs_select_type_of_user_for_nominee').val() == '1') {
                                 jQuery('#rs_select_users_list_for_nominee').parent().parent().show();
                                 jQuery('#rs_select_users_role_for_nominee').parent().parent().hide();
                             }else{
                                 jQuery('#rs_select_users_list_for_nominee').parent().parent().hide();
                                 jQuery('#rs_select_users_role_for_nominee').parent().parent().show();
                             }

                             jQuery('#rs_select_type_of_user_for_nominee').change(function(){
                              if(jQuery('#rs_select_type_of_user_for_nominee').val() == '1') {
                                  jQuery('#rs_select_users_list_for_nominee').parent().parent().show();
                                  jQuery('#rs_select_users_role_for_nominee').parent().parent().hide();
                              }else{
                                  jQuery('#rs_select_users_list_for_nominee').parent().parent().hide();
                                  jQuery('#rs_select_users_role_for_nominee').parent().parent().show();
                              } 
                             });
                        }else{
                            jQuery('#rs_my_nominee_title').parent().parent().hide();
                            jQuery('#rs_select_type_of_user_for_nominee').parent().parent().hide();
                            jQuery('#rs_select_users_list_for_nominee').parent().parent().hide();
                            jQuery('#rs_select_users_role_for_nominee').parent().parent().hide();
                        }
                   });
                   
                   
                  var currentvalues = jQuery('#rs_show_hide_nominee_field_in_checkout').val() ;
                   if(currentvalues == '1'){
                        jQuery('#rs_my_nominee_title_in_checkout').parent().parent().show();                                                                      
                        jQuery('#rs_select_type_of_user_for_nominee_checkout').parent().parent().show();
                        
                        if(jQuery('#rs_select_type_of_user_for_nominee_checkout').val() == '1') {
                            jQuery('#rs_select_users_list_for_nominee_in_checkout').parent().parent().show();
                            jQuery('#rs_select_users_role_for_nominee_checkout').parent().parent().hide();
                        }else{
                            jQuery('#rs_select_users_list_for_nominee_in_checkout').parent().parent().hide();
                            jQuery('#rs_select_users_role_for_nominee_checkout').parent().parent().show();
                        }
                        
                        jQuery('#rs_select_type_of_user_for_nominee_checkout').change(function(){
                         if(jQuery('#rs_select_type_of_user_for_nominee_checkout').val() == '1') {
                             jQuery('#rs_select_users_list_for_nominee_in_checkout').parent().parent().show();
                            jQuery('#rs_select_users_role_for_nominee_checkout').parent().parent().hide();
                        }else{
                            jQuery('#rs_select_users_list_for_nominee_in_checkout').parent().parent().hide();
                            jQuery('#rs_select_users_role_for_nominee_checkout').parent().parent().show();
                        }
                        });  
                   }else{
                       jQuery('#rs_my_nominee_title_in_checkout').parent().parent().hide();                                                                      
                       jQuery('#rs_select_users_list_for_nominee_in_checkout').parent().parent().hide();
                       jQuery('#rs_select_users_role_for_nominee_checkout').parent().parent().hide(); 
                       jQuery('#rs_select_type_of_user_for_nominee_checkout').parent().parent().hide();  
                   }
                   
                   jQuery('#rs_show_hide_nominee_field_in_checkout').change(function(){
                        var currentvalues = jQuery('#rs_show_hide_nominee_field_in_checkout').val() ;
                        if(currentvalues == '1'){
                             jQuery('#rs_my_nominee_title_in_checkout').parent().parent().show();                                                                      
                        jQuery('#rs_select_type_of_user_for_nominee_checkout').parent().parent().show();
                        
                        if(jQuery('#rs_select_type_of_user_for_nominee_checkout').val() == '1') {
                            jQuery('#rs_select_users_list_for_nominee_in_checkout').parent().parent().show();
                            jQuery('#rs_select_users_role_for_nominee_checkout').parent().parent().hide();
                        }else{
                            jQuery('#rs_select_users_list_for_nominee_in_checkout').parent().parent().hide();
                            jQuery('#rs_select_users_role_for_nominee_checkout').parent().parent().show();
                        }
                        
                        jQuery('#rs_select_type_of_user_for_nominee_checkout').change(function(){
                         if(jQuery('#rs_select_type_of_user_for_nominee_checkout').val() == '1') {
                             jQuery('#rs_select_users_list_for_nominee_in_checkout').parent().parent().show();
                            jQuery('#rs_select_users_role_for_nominee_checkout').parent().parent().hide();
                        }else{
                            jQuery('#rs_select_users_list_for_nominee_in_checkout').parent().parent().hide();
                            jQuery('#rs_select_users_role_for_nominee_checkout').parent().parent().show();
                        }
                        });  
                   }else{
                       jQuery('#rs_my_nominee_title_in_checkout').parent().parent().hide();                                                                      
                       jQuery('#rs_select_users_list_for_nominee_in_checkout').parent().parent().hide();
                       jQuery('#rs_select_users_role_for_nominee_checkout').parent().parent().hide(); 
                       jQuery('#rs_select_type_of_user_for_nominee_checkout').parent().parent().hide(); 
                        }
                   });
                   

                });
            </script>
            
        <?php
    }                 
    
    public static function display_nominee_field_in_checkout() {
          global $woocommerce;
        global $wp_roles;
        ?>
        <style type="text/css">
            .chosen-container-single {
                position:absolute;
            }

        </style>        
        <?php
        $getnomineetype = get_option('rs_select_type_of_user_for_nominee_checkout');
        if ($getnomineetype == '1') {
            
            $getusers = get_option('rs_select_users_list_for_nominee_in_checkout');
            echo "<h2>" . get_option('rs_my_nominee_title_in_checkout') . "</h2>";
            if ($getusers != '') {
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <td style="width:150px;">
                            <label for="rs_select_nominee_in_checkout" style="font-size:16px;font-weight: bold;"><?php _e('Select Nominee for Product Purchase', 'rewardsystem'); ?></label>
                        </td>
                    </tr>
                    <tr valign="top">
                        <td style="width:300px;">
                            <select name="rs_select_nominee_in_checkout" style="width:300px;" id="rs_select_nominee_in_checkout" class="short rs_select_nominee_in_checkout">
                                <option value=""><?php _e('Choose Nominee', 'rewardsystem'); ?></option>
                                <?php
                                $getusers = get_option('rs_select_users_list_for_nominee_in_checkout');
                                $currentuserid = get_current_user_id();
                                $usermeta = get_user_meta($currentuserid, 'rs_selected_nominee_in_checkout', true);
                                if ($getusers != '') {
                                    if (!is_array($getusers)) {
                                        $userids = array_filter(array_map('absint', (array) explode(',', $getusers)));
                                        foreach ($userids as $userid) {
                                            $user = get_user_by('id', $userid);
                                            ?>
                                            <option value="<?php echo $userid; ?>" <?php echo $usermeta == $userid ? "selected=selected" : '' ?>>
                                                 <?php  if(get_option('rs_select_type_of_user_for_nominee_name_checkout')=='1'){?>
                                                <?php echo esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')'; ?></option>
                                            <?php }else{
                                                  echo esc_html($user->display_name);
                                              } ?>
                                            <?php
                                        }
                                    }else{
                                        $userids = $getusers;
                                        foreach ($userids as $userid) {
                                            $user = get_user_by('id', $userid);
                                            ?>
                                            <option value="<?php echo $userid; ?>" <?php echo $usermeta == $userid ? "selected=selected" : '' ?>>
                                               <?php  if(get_option('rs_select_type_of_user_for_nominee_name_checkout')=='1'){?>
                                                <?php echo esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')'; ?></option>
                                            <?php }else{
                                                  echo esc_html($user->display_name);
                                              } ?>
                                             <?php
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </td> 
                        
                    </tr>
                </table>
                <?php
            } else {
                _e('You have no Nominee', 'rewardsystem');
            }
        
        } else {
            $getuserrole = get_option('rs_select_users_role_for_nominee_checkout');
            echo "<h2>" . get_option('rs_my_nominee_title_in_checkout') . "</h2>";
            if ($getuserrole != '') {
                ?>
                <table class="form-table">
                    <tr valign="top">
                        <td style="width:150px;">
                            <label for="rs_select_nominee_in_checkout" style="font-size:20px;font-weight:bold;"><?php _e('Select Nominee', 'rewardsystem'); ?></label>
                        </td>
                        <td style="width:300px;">
                            <select name="rs_select_nominee_in_checkout" style="width:300px;" id="rs_select_nominee_in_checkout" class="short rs_select_nominee_in_checkout">
                                <option value=""><?php _e('Choose Nominee', 'rewardsystem'); ?></option>
                                <?php
                                $getusers = get_option('rs_select_users_role_for_nominee_checkout');
                                $currentuserid = get_current_user_id();
                                $usermeta = get_user_meta($currentuserid, 'rs_selected_nominee_in_checkout', true);
                                if ($getusers != '') {
                                    if (is_array($getusers)) {
                                        foreach ($getusers as $userrole) {
                                            $args['role'] = $userrole;
                                            $users = get_users($args);
                                            foreach ($users as $user) {
                                                $userid = $user->ID;
                                                ?>
                                                <option value="<?php echo $userid; ?>" <?php echo $usermeta == $userid ? "selected=selected" : '' ?>>
                                               <?php  if(get_option('rs_select_type_of_user_for_nominee_name_checkout')=='1'){?>
                                                <?php echo esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email) . ')'; ?></option> ?></option>
                                            <?php }else{
                                                  echo esc_html($user->display_name);
                                              } ?>
                                                    <?php
                                            }
                                        }
                                    }
                                }
                                ?>
                            </select>
                        </td>
                        
                    </tr>
                </table>
                <?php
            } else {
                _e('You have no Nominee', 'rewardsystem');
            }
        }
    }
    public static function ajax_for_saving_nominee_in_checkout() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('#rs_select_nominee_in_checkout').change(function () {
                    var value = jQuery('#rs_select_nominee_in_checkout').val();                      
                    var Value = {
                        action: "rs_save_nominee_in_checkout",
                        selectedvalue: value,
                    };
                    jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", Value, function (response) {                        
                        console.log('Success');
                    });
                    return false;
                });
                return false;
            });
        </script>
        <?php
    }   
    
    public static function save_selected_nominee_in_checkout($order_id) {
        $getpostvalue = isset($_POST['rs_select_nominee_in_checkout'])?$_POST['rs_select_nominee_in_checkout']:'';        
        update_post_meta($order_id, 'rs_selected_nominee_in_checkout', $getpostvalue);            
    }
    
     public static function rs_select_user_as_nominee_in_checkout() {
        global $woocommerce;
        ?>
        <style type="text/css">
            .chosen-container-single {
                position:absolute;
            }

        </style>
        <?php
        echo RSJQueryFunction::rs_common_ajax_function_to_select_user('rs_select_users_list_for_nominee_in_checkout');
        ?>
        <?php if ((float) $woocommerce->version <= (float) ('2.2.0')) { ?>
            <tr valign="top">
                <th class="titledesc" scope="row">
                    <label for="rs_select_users_list_for_nominee_in_checkout"><?php _e('Select the Users as Nominee for Product Purchase', 'rewardsystem'); ?></label>
                </th>
                <td>
                    <select name="rs_select_users_list_for_nominee_in_checkout[]" style="width:343px;" multiple="multiple" id="rs_select_users_list_for_nominee_in_checkout" class="short rs_select_users_list_for_nominee_in_checkout">
                        <?php
                        $json_ids = array();
                        $getuser = get_option('rs_select_users_list_for_nominee_in_checkout');
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
                                <option value="<?php echo $userid; ?>" selected="selected"><?php echo esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email).')'; ?></option>
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
                    <label for="rs_select_users_list_for_nominee_in_checkout"><?php _e('Select the Users as Nominee for Product Purchase', 'rewardsystem'); ?></label>
                </th>
                <td>
                    <input type="hidden" class="wc-customer-search" name="rs_select_users_list_for_nominee_in_checkout" id="rs_select_users_list_for_nominee_in_checkout" data-multiple="true" data-placeholder="<?php _e('Search Users', 'rewardsystem'); ?>" data-selected="<?php
                    $json_ids = array();
                    $getuser = get_option('rs_select_users_list_for_nominee_in_checkout');
                    if ($getuser != "") {
                        $listofuser = $getuser;
                        if (!is_array($listofuser)) {
                            $userids = array_filter(array_map('absint', (array) explode(',', $listofuser)));
                        } else {
                            $userids = $listofuser;
                        }

                        foreach ($userids as $userid) {
                            $user = get_user_by('id', $userid);
                            $json_ids[$user->ID] = esc_html($user->display_name) . ' (#' . absint($user->ID) . ' &ndash; ' . esc_html($user->user_email).')';
                        }echo esc_attr(json_encode($json_ids));
                    }
                    ?>" value="<?php echo implode(',', array_keys($json_ids)); ?>" data-allow_clear="true" />
                </td>
            </tr>
            <?php
        }
    }


}

new RSFunctionForNominee();