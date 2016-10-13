<?php

class RSReferAFriend {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_referfriend', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_referfriend', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_referfriend'] = __('Refer A Friend','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
        global $woocommerce;
        return apply_filters('woocommerce_rewardsystem_referfriend_settings', array(
            array(
                'name' => __('Use this [rs_refer_a_friend] Shortcode anywhere on Page/Post to Display Refer a Friend Form', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_reward_shortcode_status'
            ),
            array('type' => 'sectionend', 'id' => '_rs_reward_shortcode_status'),
            array(
                'name' => __('Refer a Friend Settings', 'rewardsystem'),
                'type' => 'title',               
                'id' => '_rs_reward_referfriend_status'
            ),
            array('type' => 'sectionend', 'id' => '_rs_reward_point_status'),
            array(
                'name' => __('Customization Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Refer a Friend Customization Settings', 'rewardsystem'),
                'id' => '_rs_reward_point_customization',
            ),
            array(
                'name' => __('Friend Name Label', 'rewardsystem'),
                'desc' => __('Enter Friend Name Label which will be available in Frontend when you use shortcode', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_rewards_friend_name_label',
                'css' => 'min-width:550px;',
                'std' => 'Your Friend Name',
                'type' => 'text',
                'newids' => 'rs_my_rewards_friend_name_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Friend Email Label', 'rewardsystem'),
                'desc' => __('Enter Friend Email Label which will be available in Frontend when you use shortcode', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_rewards_friend_email_label',
                'css' => 'min-width:550px;',
                'std' => 'Your Friend Email',
                'type' => 'text',
                'newids' => 'rs_my_rewards_friend_email_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Friend Email Subject', 'rewardsystem'),
                'desc' => __('Enter Friend Subject which will be appear in Frontend when you use shortcode', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_rewards_friend_subject_label',
                'css' => 'min-width:550px;',
                'std' => 'Your Subject',
                'type' => 'text',
                'newids' => 'rs_my_rewards_friend_subject_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Friend Email Message', 'rewardsystem'),
                'desc' => __('Enter Friend Email Message which will be appear in frontend when you use shortcode', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_rewards_friend_message_label',
                'css' => 'min-width:550px;',
                'std' => 'Your Message',
                'type' => 'text',
                'newids' => 'rs_my_rewards_friend_message_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Prefill Message for Refer a Friend', 'rewardsystem'),
                'desc' => __('This Message will be displayed in the Message field along with the Referral link', 'rewardsystem'),
                'id' => 'rs_friend_referral_link',
                'css' => 'min-width:550px',
                'std' => 'You can Customize your message here.[site_referral_url]',
                'type' => 'textarea',
                'newids' => 'rs_friend_referral_link',
                'desc_tip' => true,
            ),
             array(
                'name' => __('Show/Hide I agree to the Terms and Condition Field', 'rewardsystem'),                
                'id' => 'rs_show_hide_iagree_termsandcondition_field',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'rs_show_hide_iagree_termsandcondition_field',
                'type' => 'select',
                'options' => array(
                    '1' => __('Hide', 'rewardsystem'),
                    '2' => __('Show', 'rewardsystem'),
                ),                
            ),
            array(
                'name' => __('Caption for I Agree Field', 'rewardsystem'),
                'desc' => __('This Caption will be displayed for the I agree field in Refer a Friend Form', 'rewardsystem'),
                'id' => 'rs_refer_friend_iagreecaption_link',
                'css' => 'min-width:550px',
                'std' => 'I agree to the {termsandconditions}',
                'type' => 'textarea',
                'newids' => 'rs_refer_friend_iagreecaption_link',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Caption for Terms and Conditions', 'rewardsystem'),
                'desc' => __('This Caption will be displayed for terms and condition', 'rewardsystem'),
                'id' => 'rs_refer_friend_termscondition_caption',
                'css' => 'min-width:550px',
                'std' => 'Terms and Conditions',
                'type' => 'textarea',
                'newids' => 'rs_refer_friend_termscondition_caption',
                'desc_tip' => true,
            ),
            array(
                'name' => __('URL for Terms and Conditions', 'rewardsystem'),
                'desc' => __('Enter the URL for Terms and Conditions', 'rewardsystem'),
                'id' => 'rs_refer_friend_termscondition_url',
                'css' => 'min-width:550px',
                'std' => '',
                'type' => 'text',
                'newids' => 'rs_refer_friend_termscondition_url',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_reward_point_customization'),
            array(
                'name' => __('Field Placeholder Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Here you can customize the field Placeholder which will be appear in frontend when you use shortcode', 'rewardsystem'),
                'id' => '_rs_reward_field_placeholder_settings'
            ),
            array(
                'name' => __('Friend Name Field Placeholder', 'rewardsystem'),
                'desc' => __('Enter Friend Name Field Placeholder which will be appear in frontend when you use shortcode', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_rewards_friend_name_placeholder',
                'css' => 'min-width:550px;',
                'std' => 'Enter your Friend Name',
                'type' => 'text',
                'newids' => 'rs_my_rewards_friend_name_placeholder',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Friend Email Field Placeholder', 'rewardsystem'),
                'desc' => __('Enter Friend Email Field Placeholder which will be appear in frontend when you use shortcode', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_rewards_friend_email_placeholder',
                'css' => 'min-width:550px;',
                'std' => 'Enter your Friend Email',
                'type' => 'text',
                'newids' => 'rs_my_rewards_friend_email_placeholder',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Friend Email Subject Field Placeholder', 'rewardsystem'),
                'desc' => __('Enter Friend Email Subject Field Placeholder which will be appear in frontend when you use shortcode', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_rewards_friend_email_subject_placeholder',
                'css' => 'min-width:550px;',
                'std' => 'Enter your Subject',
                'type' => 'text',
                'newids' => 'rs_my_rewards_friend_email_subject_placeholder',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Friend Email Message Field Placeholder', 'rewardsystem'),
                'desc' => __('Enter Friend Email Message Field Placeholder which will be appear in frontend when you use shortcode', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_rewards_friend_email_message_placeholder',
                'css' => 'min-width:550px;',
                'std' => 'Enter your Message',
                'type' => 'text',
                'newids' => 'rs_my_rewards_friend_email_message_placeholder',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_reward_field_placeholder_settings'),
            array(
                'name' => __('Error Message Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Here you can customize the Label and Placeholders', 'rewardsystem'),
                'id' => '_rs_reward_referfriend_error_settings'
            ),
            array(
                'name' => __('Error Message if Friend Name is Empty', 'rewardsystem'),
                'desc' => __('Enter your Error Message which will be appear in frontend if the Friend Name is Empty'),
                'tip' => '',
                'id' => 'rs_my_rewards_friend_name_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Please Enter your Friend Name',
                'type' => 'text',
                'newids' => 'rs_my_rewards_friend_name_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Error Message if Friend Email is Empty', 'rewardsystem'),
                'desc' => __('Enter your Error Message which will be appear in frontend if the Friend Email is Empty'),
                'tip' => '',
                'id' => 'rs_my_rewards_friend_email_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Please Enter your Friend Email',
                'type' => 'text',
                'newids' => 'rs_my_rewards_friend_email_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Error Message if Friend Email is not Valid', 'rewardsystem'),
                'desc' => __('Enter your Error Message which will be appear in frontend if the Friend Email is not Valid'),
                'tip' => '',
                'id' => 'rs_my_rewards_friend_email_is_not_valid',
                'css' => 'min-width:550px;',
                'std' => 'Enter Email is not Valid',
                'type' => 'text',
                'newids' => 'rs_my_rewards_friend_email_is_not_valid',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Error Message if Email Subject is Empty', 'rewardsystem'),
                'desc' => __('Enter your Error Message which will be appear in frontend if the Email Subject is Empty'),
                'tip' => '',
                'id' => 'rs_my_rewards_email_subject_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Email Subject should not be left blank',
                'type' => 'text',
                'newids' => 'rs_my_rewards_email_subject_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Error Message if Email Message is Empty', 'rewardsystem'),
                'desc' => __('Enter your Error Message which will be appear in frontend if the Email Message is Empty'),
                'tip' => '',
                'id' => 'rs_my_rewards_email_message_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Please Enter your Message',
                'type' => 'text',
                'newids' => 'rs_my_rewards_email_message_error_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Error Message if i agree is unchecked', 'rewardsystem'),
                'desc' => __('Enter your Error Message which will be appear in frontend if i agree is unchecked'),
                'tip' => '',
                'id' => 'rs_iagree_error_message',
                'css' => 'min-width:550px;',
                'std' => 'Please Accept our Terms and Condition',
                'type' => 'text',
                'newids' => 'rs_iagree_error_message',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_reward_referfriend_error_settings'),
            array(
                'name' => __('Custom CSS Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => 'Try !important if styles doesn\'t apply ',
                'id' => '_rs_refer_a_friend_custom_css_settings'
            ),
            array(
                'name' => __('Custom CSS', 'rewardsystem'),
                'desc' => __('Enter the Custom CSS which will be applied on top of Refer a Friend Shortcode', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_refer_a_friend_custom_css',
                'css' => 'min-width:350px;min-height:350px;',
                'std' => '#rs_refer_a_friend_form { } #rs_friend_name { } #rs_friend_email { } #rs_friend_subject { } #rs_your_message { } #rs_refer_submit { }',
                'type' => 'textarea',
                'newids' => 'rs_refer_a_friend_custom_css',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_refer_a_friend_custom_css_settings'),
            array('type' => 'sectionend', 'id' => '_rs_reward_point_order_status'),
        ));
    }
    
    
    /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSReferAFriend::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSReferAFriend::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSReferAFriend::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
}

new RSReferAFriend();