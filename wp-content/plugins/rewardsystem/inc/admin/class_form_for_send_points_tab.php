<?php
class RSFormForSendPoints {
    public function __construct(){
      
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_form_for_send_points_tab', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_form_for_send_points_tab', array($this, 'reward_system_update_settings'));
        
    }
     public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_form_for_send_points_tab'] = __('Form For Send Points','rewardsystem');
        return $setting_tabs;
    }
    
    public static function reward_system_admin_fields() {
         global $woocommerce;
        return apply_filters('woocommerce_rewardsystem_form_for_send_points_settings', array(
            array(
                'name' => __('Use the Shortcode [rssendpoints] to display the Send Points Form', 'rewardsystem'),
                'type' => 'title',                
                'id' => '_rs_form_for_send_points_setting'
            ),  
             array('type'=>'sectionend', 'id'=>'_rs__form_for_send_points_setting'),
            array(
                'name' => __('Send Point Settings', 'rewardsystem'),
                'type' => 'title',               
                'id' => '_rs_send_point_settings'
            ),
             array(
                'name' => __('Enable Send points', 'rewardsystem'),
                'tip' => '',
                'id'=>'rs_enable_msg_for_send_point',
                'newids'=>'rs_enable_msg_for_send_point',
                 'css' => '',
                'std' => '2',
                'class'=>'rs_enable_msg_for_send_point',
                'type'=>'select',
                 'options' => array(
                    '1' => __('Enable', 'rewardsystem'),
                    '2' => __('Disable', 'rewardsystem'),
                ),
                'desc_tip'=>true,
            ),
            array(
                'name' => __('Current Reward Points Caption', 'rewardsystem'),
               'tip' => '',
                'id' => 'rs_total_send_points_request',
                'css' => '',
                'std' => 'Current Reward Points',
                'type' => 'text',
                'newids' => 'rs_total_send_points_request',
                'desc_tip' => true,
            ),
            
             array(
                'name' => __('Limit Send points', 'rewardsystem'),
                'tip' => '',
                'id'=>'rs_limit_for_send_point',
                'newids'=>'rs_limit_for_send_point',
                 'css' => '',
                'std' => '2',
                'class'=>'rs_limit_for_send_point',
                'type'=>'select',
                 'options' => array(
                    '1' => __('Enable', 'rewardsystem'),
                    '2' => __('Disable', 'rewardsystem'),
                ),
                'desc_tip'=>true,
            ),
            array(
                'name' => __('Restrict User to Send Limit Number Of Points', 'rewardsystem'),
               'tip' => '',
                'id' => 'rs_limit_send_points_request',
                'css' => '',
                'std' => '',
                'type' => 'text',
                'newids' => 'rs_limit_send_points_request',
                'desc_tip' => true,
            ),
            
            array(
                'name' => __('Error Message when  Send point greater than limit points ', 'rewardsystem'),
               'tip' => '',
                'id' => 'rs_err_when_point_greater_than_limit',
                'css' => 'min-width:500px;',
                'std' => 'Please Enter Points less than {limitpoints}',
                'type' => 'text',
                'newids' => 'rs_err_when_point_greater_than_limit',
                'desc_tip' => true,
            ),
             array(
                'name' => __('Points to Send Caption  ', 'rewardsystem'),
               'tip' => '',
                'id' => 'rs_points_to_send_request',
                'css' => '',
                'std' => 'Points to Send',
                'type' => 'text',
                'newids' => 'rs_points_to_send_request',
                'desc_tip' => true,
            ),
             array(
                'name' => __('Select the User to send', 'rewardsystem'),
                'desc' => __('Here you can select the user whom you wish send points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_select_users_list_for_send_point',
                'css' => 'min-width:400px;',
                'std' => '',
                'type' => 'rs_select_user_for_send',
                'newids' => 'rs_select_users_list_for_send_point',
                'desc_tip' => true,
            ),
            
           array(
                'name' => __(' Select the user to send label', 'rewardsystem'),
                
              
                'id' => 'rs_select_user_label',
                'css' => 'min-width:400px;',
                'std' => 'Select the user to send',
                'type' => 'text',
                'newids' => 'rs_select_user_label',
                'desc_tip' => true,
            ),
             array(
                'name' => __(' Send Point Form Submit Button Label', 'rewardsystem'),
                
              
                'id' => 'rs_select_points_submit_label',
                'css' => 'min-width:400px;',
                'std' => 'Submit',
                'type' => 'text',
                'newids' => 'rs_select_points_submit_label',
                'desc_tip' => true,
            ),
             array(
                'name' => __('Message Displayed when Send Point Request is Submitted', 'rewardsystem'),
          
                'tip' => '',
                'id' => 'rs_message_send_point_request_submitted',
                'css' => 'min-width:500px;',
                'std' => 'Send Point Request Submitted',
                'type' => 'textarea',
                'newids' => 'rs_message_send_point_request_submitted',
                'desc_tip' => true,
            ),
             array(
                'name' => __('Error Message when Points for Send point Field is Empty ', 'rewardsystem'),
               'tip' => '',
                'id' => 'rs_err_when_point_field_empty',
                'css' => 'min-width:500px;',
                'std' => 'Please Enter the Points to Send',
                'type' => 'text',
                'newids' => 'rs_err_when_point_field_empty',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Error Message when Select the User Field is Empty ', 'rewardsystem'),
               'tip' => '',
                'id' => 'rs_err_for_empty_user',
                'css' => 'min-width:500px;',
                'std' => 'Please Select the User to Send Points',
                'type' => 'text',
                'newids' => 'rs_err_for_empty_user',
                'desc_tip' => true,
            ),
             array(
                'name' => __('Error Message when Points To Send Value is not a number', 'rewardsystem'),
               'tip' => '',
                'id' => 'rs_err_when_point_is_not_number',
                'css' => 'min-width:500px;',
                'std' => 'Please Enter only the Number',
                'type' => 'text',
                'newids' => 'rs_err_when_point_is_not_number',
                'desc_tip' => true,
            ),
             array('type'=>'sectionend', 'id'=>'_rs_send_point_setting'),
             ));   
        
    }
     public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSFormForSendPoints::reward_system_admin_fields());
    }
    
    
     public static function reward_system_update_settings() {
        woocommerce_update_options(RSFormForSendPoints::reward_system_admin_fields());
    }
    
    
    
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSFormForSendPoints::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
    
}
new RSFormForSendPoints();

