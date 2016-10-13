<?php

class RSFormForCashBack {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_form_for_cash_back', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_form_for_cash_back', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_form_for_cash_back'] = __('Form For Cash Back','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
        global $woocommerce;
        
        return apply_filters('woocommerce_rewardsystem_form_for_cash_back_settings', array(
            array(
                'name' => __('Use the Shortcode [rsencashform] to display the Cash Back Form', 'rewardsystem'),
                'type' => 'title',                
                'id' => '_rs_form_for_cash_back_setting'
            ),                                   
           array('type'=>'sectionend', 'id'=>'_rs_form_for_cash_back_setting'),
           array(
                'name' => __('Cash Back Settings', 'rewardsystem'),
                'type' => 'title',               
                'id' => '_rs_reward_point_encashing_settings'
            ),
            array(
                'name' => __('Enable Cash Back for Reward Points', 'rewardsystem'),
                'desc' => __('Enable this option to provide the feature to Cash Back the Reward Points earned by the Users', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_enable_disable_encashing',
                'css' => '',
                'std' => '2',
                'type' => 'select',
                'newids' => 'rs_enable_disable_encashing',
                'options' => array(
                    '1' => __('Enable', 'rewardsystem'),
                    '2' => __('Disable', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Minimum Points for Cash Back of Reward Points', 'rewardsystem'),
                'desc' => __('Enter the Minimum points that the user should have in order to Submit the Cash Back Request', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_minimum_points_encashing_request',
                'css' => '',
                'std' => '',
                'type' => 'text',
                'newids' => 'rs_minimum_points_encashing_request',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Maximum Points for Cash Back of Reward Points', 'rewardsystem'),
                'desc' => __('Enter the Maximum points that the user should enter order to Submit the Cash Back Request', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_maximum_points_encashing_request',
                'css' => '',
                'std' => '',
                'type' => 'text',
                'newids' => 'rs_maximum_points_encashing_request',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Points for Cash Back Label', 'rewardsystem'),
                'desc' => __('Please Enter Points the Label for Cash Back', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_encashing_points_label',
                'css' => 'min-width:300px;',
                'std' => 'Points for Cash Back',
                'type' => 'text',
                'newids' => 'rs_encashing_points_label',
                'desc_tip' => true,
            ),            
            array(
                'name' => __('Payment Method Label', 'rewardsystem'),
                'desc' => __('Please Enter Payment Method Label for Cash Back', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_encashing_payment_method_label',
                'css' => 'min-width:300px;',
                'std' => 'Payment Method',
                'type' => 'text',
                'newids' => 'rs_encashing_payment_method_label',
                'desc_tip' => true,
            ),
             array(
                'name' => __('Display payment method ', 'rewardsystem'),
               
                'tip' => '',
                'id' => 'rs_select_payment_method',
                'css' => '',
                'std' => '3',
                'type' => 'select',
                'newids' => 'rs_select_payment_method',
                'options' => array(
                    '1' => __('PayPal', 'rewardsystem'),
                    '2' => __('Custom Payment', 'rewardsystem'),
                    '3' => __('Both','rewardsystem'),
                ),
                 ),
            array(
                'name' => __('Reason for Cash Back Label', 'rewardsystem'),
                'desc' => __('Please Enter label for Reason Cash Back', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_encashing_reason_label',
                'css' => 'min-width:300px;',
                'std' => 'Reason for Cash Back',
                'type' => 'text',
                'newids' => 'rs_encashing_reason_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Payment Method Label', 'rewardsystem'),
                'desc' => __('Please Enter Payment Method Label for Cash Back', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_encashing_payment_method_label',
                'css' => 'min-width:300px;',
                'std' => 'Payment Method',
                'type' => 'text',
                'newids' => 'rs_encashing_payment_method_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('PayPal Email Address Label', 'rewardsystem'),
                'desc' => __('Please Enter PayPal Email Address Label for Cash Back', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_encashing_payment_paypal_label',
                'css' => 'min-width:300px;',
                'std' => 'PayPal Email Address',
                'type' => 'text',
                'newids' => 'rs_encashing_payment_paypal_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Custom Payment Details Label', 'rewardsystem'),
                'desc' => __('Please Enter Custom Payment Details Label for Cash Back', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_encashing_payment_custom_label',
                'css' => 'min-width:300px;',
                'std' => 'Custom Payment Details',
                'type' => 'text',
                'newids' => 'rs_encashing_payment_custom_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Cash Back Form Submit Button Label', 'rewardsystem'),
                'desc' => __('Please Enter Cash Back Form Submit Button Label ', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_encashing_submit_button_label',
                'css' => 'min-width:200px;',
                'std' => 'Submit',
                'type' => 'text',
                'newids' => 'rs_encashing_submit_button_label',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_reward_point_checkout_settings'),
            array(
                'name' => __('Message Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => '',
                'id' => '_rs_message_settings_encashing'
            ),
            array(
                'name' => __('Message Displayed for Guest', 'rewardsystem'),
                'desc' => __('Please Enter Message Displayed for Guest', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_for_guest_encashing',
                'css' => 'min-width:500px;',
                'std' => 'Please [rssitelogin] to Cash Back your Reward Points.',
                'type' => 'textarea',
                'newids' => 'rs_message_for_guest_encashing',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Login link for Guest Label', 'rewardsystem'),
                'desc' => __('Please Enter Login link for Guest Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_encashing_login_link_label',
                'css' => 'min-width:200px;',
                'std' => 'Login',
                'type' => 'text',
                'newids' => 'rs_encashing_login_link_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Message Displayed for Banned Users', 'rewardsystem'),
                'desc' => __('Please Enter Message Displayed for Banned Users', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_for_banned_users_encashing',
                'css' => 'min-width:500px;',
                'std' => 'You cannot Cash Back Your points',
                'type' => 'textarea',
                'newids' => 'rs_message_for_banned_users_encashing',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Message Displayed when Users dont have Reward Points', 'rewardsystem'),
                'desc' => __('Please Enter Message to be Displayed when Users dont have Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_users_nopoints_encashing',
                'css' => 'min-width:500px;',
                'std' => 'You Don\'t have points for Cash back',
                'type' => 'textarea',
                'newids' => 'rs_message_users_nopoints_encashing',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Message Displayed when Cash Back Request is Submitted', 'rewardsystem'),
                'desc' => __('Please Enter Message to be Displayed when Cash Back Request is Submitted', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_message_encashing_request_submitted',
                'css' => 'min-width:500px;',
                'std' => 'Cash Back Request Submitted',
                'type' => 'textarea',
                'newids' => 'rs_message_encashing_request_submitted',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_message_settings_encashing'),
            array(
                'name' => __('CSV Settings (Export CSV for Paypal Mass Payment)', 'rewardsystem'),
                'type' => 'title',
                'desc' => '',
                'id' => '_rs_csv_message_settings_encashing'
            ),
            array(
                'name' => __('Custom Note for Paypal', 'rewardsystem'),
                'desc' => __('A Custom Note for Paypal', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_encashing_paypal_custom_notes',
                'css' => 'min-width:200px;',
                'std' => 'Thanks for your Business',
                'type' => 'textarea',
                'newids' => 'rs_encashing_paypal_custom_notes',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_csv_message_settings_encashing'),
            array(
                'name' => __('Error Message Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => '',
                'id' => '_rs_error_settings_encashing'
            ),
            array(
                'name' => __('Error Message Displayed when Points for Cash Back Field is Empty', 'rewardsystem'),
                'desc' => __('Please Enter Message to be Displayed when Points for Cash Back Field is Empty', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_error_message_points_empty_encash',
                'css' => 'min-width:500px;',
                'std' => 'Points for Cash Back Field cannot be empty',
                'type' => 'text',
                'newids' => 'rs_error_message_points_empty_encash',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Error Message Displayed when Points To Cash Back value is not a number', 'rewardsystem'),
                'desc' => __('Please Enter Message to be Displayed when Points To Cash Back Field value is not a number', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_error_message_points_number_val_encash',
                'css' => 'min-width:500px;',
                'std' => 'Please Enter only Numbers',
                'type' => 'text',
                'newids' => 'rs_error_message_points_number_val_encash',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Error Message Displayed when Points entered for Cash Back is more than the Points Earned', 'rewardsystem'),
                'desc' => __('Please Enter Message to be Displayed when Points entered for Cash Back is more than the Points Earned', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_error_message_points_greater_than_earnpoints',
                'css' => 'min-width:500px;',
                'std' => 'Points Entered for Cash Back is more than the Earned Points',
                'type' => 'text',
                'newids' => 'rs_error_message_points_greater_than_earnpoints',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Error Message Displayed when Current User Points is less than the Minimum points for Cash Back', 'rewardsystem'),
                'desc' => __('Please Enter Message to be Displayed when Points entered for Cash Back is more than the Maximum Points for Cash Back', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_error_message_currentpoints_less_than_minimum_points',
                'css' => 'min-width:500px;',
                'std' => 'You need a Minimum of [minimum_encash_points] points in order for Cash Back',
                'type' => 'textarea',
                'newids' => 'rs_error_message_currentpoints_less_than_minimum_points',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Error Message Displayed when Points entered to Cash Back is less than the Minimum Points and more than Maximum Points', 'rewardsystem'),
                'desc' => __('Please Enter Message to be Displayed when Points entered to Cash Back is less than the Minimum Points and more than Maximum Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_error_message_points_lesser_than_minimum_points',
                'css' => 'min-width:500px;',
                'std' => 'Please Enter Between [minimum_encash_points] and [maximum_encash_points] ',
                'type' => 'textarea',
                'newids' => 'rs_error_message_points_lesser_than_minimum_points',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Error Message Displayed when Reason To Cash Back Field is Empty', 'rewardsystem'),
                'desc' => __('Please Enter Message to be Displayed when Reason To Cash Back Field is Empty', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_error_message_reason_encash_empty',
                'css' => 'min-width:500px;',
                'std' => 'Reason to Encash Field cannot be empty',
                'type' => 'text',
                'newids' => 'rs_error_message_reason_encash_empty',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Error Message Displayed when PayPal Email Address is Empty', 'rewardsystem'),
                'desc' => __('Please Enter Message to be Displayed when PayPal Email Address is Empty', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_error_message_paypal_email_empty',
                'css' => 'min-width:500px;',
                'std' => 'Paypal Email Field cannot be empty',
                'type' => 'text',
                'newids' => 'rs_error_message_paypal_email_empty',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Error Message Displayed when PayPal Email Address format is wrong', 'rewardsystem'),
                'desc' => __('Please Enter Message to be Displayed when PayPal Email Address format is wrong', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_error_message_paypal_email_wrong',
                'css' => 'min-width:500px;',
                'std' => 'Enter a Correct Email Address',
                'type' => 'text',
                'newids' => 'rs_error_message_paypal_email_wrong',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Error Message Displayed when Custom Payment Details field is Empty', 'rewardsystem'),
                'desc' => __('Please Enter Message to be Displayed when Custom Payment Details field is Empty', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_error_custom_payment_field_empty',
                'css' => 'min-width:500px;',
                'std' => 'Custom Payment Details  Field cannot be empty',
                'type' => 'text',
                'newids' => 'rs_error_custom_payment_field_empty',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_error_settings_encashing'),
            array(
                'name' => __('Cash Back Form CSS Customization Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => '',
                'id' => '_rs_style_settings_encashing'
            ),
            array(
                'name' => __('Inbuilt Design', 'rewardsystem'),
                'desc' => __('Please Select you want to load the Inbuilt Design', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_encash_form_inbuilt_design',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('1' => 'Inbuilt Design'),
                'newids' => 'rs_encash_form_inbuilt_design',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Inbuilt CSS (Non Editable)', 'rewardsystem'),
                'desc' => __('These are element IDs in the Shop Page ', 'rewardsystem'),
                'tip' => '',
                'css' => 'min-width:550px;min-height:260px;margin-bottom:80px;',
                'id' => 'rs_encash_form_default_css',
                'std' => '#encashing_form{}
.rs_encash_points_value{}
.error{color:#ED0514;}
.rs_encash_points_reason{}
.rs_encash_payment_method{}
.rs_encash_paypal_address{}
.rs_encash_custom_payment_option_value{}
.rs_encash_submit{}
#rs_encash_submit_button{}
.success_info{}
#encash_form_success_info{}',
                'type' => 'textarea',
                'newids' => 'rs_encash_form_default_css',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Custom Design', 'rewardsystem'),
                'desc' => __('Please Select you want to load the Custom Design', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_encash_form_inbuilt_design',
                'css' => '',
                'std' => '1',
                'type' => 'radio',
                'options' => array('2' => 'Custom Design'),
                'newids' => 'rs_encash_form_inbuilt_design',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Custom CSS', 'rewardsystem'),
                'desc' => __('Customize the following element of Cash Back Request form', 'galaxyfunder'),
                'tip' => '',
                'css' => 'min-width:550px;min-height:260px;margin-bottom:80px;',
                'id' => 'rs_encash_form_custom_css',
                'std' => '',
                'type' => 'textarea',
                'newids' => 'rs_encash_form_custom_css',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_style_settings_encashing'),
        ));     
    }
    
        /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSFormForCashBack::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSFormForCashBack::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSFormForCashBack::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
    
}
new RSFormForCashBack();