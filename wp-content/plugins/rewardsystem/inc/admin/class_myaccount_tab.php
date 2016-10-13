<?php

class RSMyaccount {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_myaccount', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_myaccount', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
        
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_myaccount'] = __('My Account','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
         global $woocommerce;       
         return apply_filters('woocommerce_rewardsystem_myaccount_settings', array(
            array(
                'name' => __('My Account', 'rewardsystem'),
                'type' => 'title',                
                'id' => 'rs_myaccount_setting',
            ),             
            array(
                'name' => __('[rs_generate_referral referralbutton="show" referraltable="show"] - Use this Shortcode for displaying Referral Link Generation and its Table', 'rewardsystem'),
                'type' => 'title',
                'desc' => '<h3>Shortcode Parameters are referralbutton and referraltable, make it as Show/Hide</h3>',
                'id' => '_rs_referraltable_shortcode',
            ),
            array(
                'name' => __(''),
                'type' => 'title',
                'desc' => '<h3>[rs_redeem_vouchercode] - Use this Shortcode for displaying the Redeeming Voucher Field <br><br></h3>'
                .'<h3>[rs_my_rewards_log] - Use this Shortcode for displaying the log of Reward Points <br><br></h3>'
                .'<h3>[rs_my_reward_points] - Use this Shortcode for displaying the Reward Points of Current User <br><br></h3>'
                .'<h3>[rs_generate_static_referral] - Use this Shortcode for displaying the Static URL Link<br><br></h3>'
                .'<h3>[rs_my_cashback_log] - Use this Shortcode for displaying the My CashBack Table<br><br></h3>'
                .'<h3>[rs_user_total_redeemed_points] - Use this Shortcode for displaying the total points Redeemed by a User<br><br></h3>'
                .'<h3>[rs_user_total_earned_points] - Use this Shortcode for displaying the total points Earned by a User<br><br></h3>'
                .'<h3>[rs_user_total_expired_points] - Use this Shortcode for displaying the total points Expired by a User</h3>'
            ),
            array('type'=>'sectionend', 'id'=>'_rs_referraltable_shortcode'),
            array(
                'name' => __('Generate Referral Link Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => '',
                'id' => '_rs_my_generate_referral_settings'
            ),
             
                                
            array(
                'name' => __('Show/Hide Generate Referral Link', 'rewardsystem'),                
                'id' => 'rs_show_hide_generate_referral',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'rs_show_hide_generate_referral',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),                
            ),
             array(
                'name' => __('Generate Referral Link Label', 'rewardsystem'),                
                'id' => 'rs_generate_link_label',
                'css' => 'min-width:550px',
                'std' => 'Generate Referral Link',
                'type' => 'text',
                'newids' => 'rs_generate_link_label',                
            ),
             array(
                'name' => __('S.No Label', 'rewardsystem'),                
                'id' => 'rs_generate_link_sno_label',
                'css' => 'min-width:550px',
                'std' => 'S.No',
                'type' => 'text',
                'newids' => 'rs_generate_link_sno_label',                
            ),
             array(
                'name' => __('Date Label', 'rewardsystem'),                
                'id' => 'rs_generate_link_date_label',
                'css' => 'min-width:550px',
                'std' => 'Date',
                'type' => 'text',
                'newids' => 'rs_generate_link_date_label',                
            ),
             array(
                'name' => __('Referral Link Label', 'rewardsystem'),                
                'id' => 'rs_generate_link_referrallink_label',
                'css' => 'min-width:550px',
                'std' => 'Referral Link',
                'type' => 'text',
                'newids' => 'rs_generate_link_referrallink_label',                
            ),
             array(
                'name' => __('Social Label', 'rewardsystem'),                
                'id' => 'rs_generate_link_social_label',
                'css' => 'min-width:550px',
                'std' => 'Social',
                'type' => 'text',
                'newids' => 'rs_generate_link_social_label',                
            ),
             array(
                'name' => __('Action Label', 'rewardsystem'),                
                'id' => 'rs_generate_link_action_label',
                'css' => 'min-width:550px',
                'std' => 'Action',
                'type' => 'text',
                'newids' => 'rs_generate_link_action_label',                
            ),
             array(
                'name' => __('Generate Referral Link button Label', 'rewardsystem'),                
                'id' => 'rs_generate_link_button_label',
                'css' => 'min-width:550px',
                'std' => 'Generate Referral Link',
                'type' => 'text',
                'newids' => 'rs_generate_link_button_label',                
            ),
            array(
                'name' => __('Generate Referral Link based on User Name/User ID', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_generate_referral_link_based_on_user',
                'std' => '1',
                'default' => '1',
                'newids' => 'rs_generate_referral_link_based_on_user',
                'type' => 'select',
                'options' => array(
                    '1' => __('User Name', 'rewardsystem'),
                    '2' => __('User ID', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),
            array(
                'name' => __('Type of Referral Link to be Displayed', 'rewardsystem'),                
                'id' => 'rs_show_hide_generate_referral_link_type',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'rs_show_hide_generate_referral_link_type',
                'type' => 'select',
                'options' => array(
                    '1' => __('Default', 'rewardsystem'),
                    '2' => __('Static Url', 'rewardsystem'),
                ),                
            ),
            array(
                'name' => __('Prefill Generate Referral Link', 'rewardsystem'),                
                'id' => 'rs_prefill_generate_link',
                'css' => 'min-width:550px',
                'std' => site_url(),
                'type' => 'text',
                'newids' => 'rs_prefill_generate_link',                
            ),
             array(
                'name' => __('My Referral Link Label', 'rewardsystem'),                
                'id' => 'rs_my_referral_link_button_label',
                'css' => 'min-width:550px',
                'std' => 'My Referral Link',
                'type' => 'text',
                'newids' => 'rs_my_referral_link_button_label',                
            ),
            array(
                'name' => __('Static Referral Link', 'rewardsystem'),                
                'id' => 'rs_static_generate_link',
                'css' => 'min-width:550px',
                'std' => site_url(),
                'type' => 'text',
                'newids' => 'rs_static_generate_link',                
            ),
            array('type' => 'sectionend', 'id' => '_rs_my_generate_referral_settings'),            
            array(
                'name' => __('My Account Gift Voucher Redeem Table', 'rewardsystem'),
                'type' => 'title',
                'desc' => '',
                'id' => '_rs_my_account_voucher_table_settings'
            ),
            array(
                'name' => __('Show/Hide Gift Voucher Field', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_redeem_voucher',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'rs_show_hide_redeem_voucher',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
             array(
                'name' => __('Redeem your Gift Voucher Label', 'rewardsystem'),
                'id' => 'rs_redeem_your_gift_voucher_label',
                'css' => 'min-width:350px;',
                'std' => 'Redeem your Gift Voucher',
                'default' => 'Redeem your Gift Voucher',
                'newids' => 'rs_redeem_your_gift_voucher_label',
                'type' => 'text',
            ),
             array(
                'name' => __('Redeem Gift Voucher Button Label', 'rewardsystem'),
                'id' => 'rs_redeem_gift_voucher_button_label',
                'css' => 'min-width:350px;',
                'std' => 'Redeem Gift Voucher',
                'default' => 'Redeem Gift Voucher',
                'newids' => 'rs_redeem_gift_voucher_button_label',
                'type' => 'text',
            ),
            array(
                'name' => __('Voucher Field Position', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_redeem_voucher_position',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'rs_redeem_voucher_position',
                'type' => 'select',
                'options' => array(
                    '1' => __('Before My Account', 'rewardsystem'),
                    '2' => __('After My Account', 'rewardsystem'),
                ),
            ),
            array('type' => 'sectionend', 'id' => '_rs_my_account_voucher_table_settings'),
            array(
                'name' => __('Show/Hide Your Subscribe Link Setting in My Account Page', 'rewardsystem'),
                'type' => 'title',
                'desc' => '',
                'id' => '_rs_my_account_subscribe_link_settings'
            ),
            array(
                'name'=>__('Show/Hide Your Subscribe Link', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_your_subscribe_link',
                'newids' => 'rs_show_hide_your_subscribe_link',
                'class' => 'rs_show_hide_your_subscribe_link',
                'std' => '1',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
                
            ),
            array(
		        'name' => __('Unsubscribe field Caption', 'rewardsystem'),
		        'desc' => __('Enter the text that will be displayed as the Unsubscribe field Caption', 'rewardsystem'),
		        'tip' => '',
		        'id' => 'rs_unsub_field_caption',
		        'css' => 'min-width:550px;',
		        'std' => 'Unsubscribe Here To Stop Receiving Reward Points Emails',
		        'type' => 'text',
		        'newids' => 'rs_unsub_field_caption',
		        'desc_tip' => true,
	    ),
            array('type' => 'sectionend', 'id' => '_rs_my_account_subscribe_link_settings'),
            array(
                'name' => __('My Account Reward Table Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => '',
                'id' => '_rs_my_account_rewards_table_settings'
            ),
            array(
                'name' => __('Points Log Sorting', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_points_log_sorting',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'rs_points_log_sorting',
                'type' => 'select',
                'options' => array(
                    '1' => __('Ascending Order', 'rewardsystem'),
                    '2' => __('Descending Order', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Show/Hide My CashBack Table', 'rewardsystem'),
                'desc' =>'',
                'id' => 'rs_my_cashback_table',
                'css' => 'min-width:150px;',
                'std' => '1',
                'desc_tip' => true,
                'default' => '1',
                'newids' => 'rs_my_cashback_table',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Show/Hide My Rewards Table', 'rewardsystem'),
                'desc' =>'',
                'id' => 'rs_my_reward_table',
                'css' => 'min-width:150px;',
                'std' => '1',
                'desc_tip' => true,
                'default' => '1',
                'newids' => 'rs_my_reward_table',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Show/Hide Search Box in My Rewards Table', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_search_box_in_my_rewards_table',
                'css' => 'min-width:150px;',
                'std' => '1',
                'desc_tip' => true,
                'default' => '1',
                'newids' => 'rs_show_hide_search_box_in_my_rewards_table',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
             
              array(
                'name' => __('Show/Hide Point Expire Coloumn', 'rewardsystem'),
                'desc' =>'',
                'id' => 'rs_my_reward_points_expire',
                'css' => 'min-width:150px;',
                'std' => '1',
                'desc_tip' => true,
                'default' => '1',
                'newids' => 'rs_my_reward_points_expire',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
             
            array(
                'name' => __('Show/Hide Page Size in My Rewards Table', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_show_hide_page_size_my_rewards',
                'css' => 'min-width:150px;',
                'std' => '1',
                'desc_tip' => true,
                'default' => '1',
                'newids' => 'rs_show_hide_page_size_my_rewards',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),
            array('type' => 'sectionend', 'id' => '_rs_my_account_rewards_table_settings'),
             
            array(
                'name' => __('My CashBack Table Label Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => '',
                'id' => '_rs_my_cashback_label_settings'
            ),
            array(
                'name' => __('My CashBack Label', 'rewardsystem'),
                'desc' => __('Enter the My CashBack Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_cashback_title',
                'css' => 'min-width:550px;',
                'std' => 'My CashBack',
                'type' => 'text',
                'newids' => 'rs_my_cashback_title',
                'desc_tip' => true,
            ),            
            array(
                'name' => __('S.No Label', 'rewardsystem'),
                'desc' => __('Enter the Serial Number Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_cashback_sno_label',
                'css' => 'min-width:550px;',
                'std' => 'S.No',
                'type' => 'text',
                'newids' => 'rs_my_cashback_sno_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('User Name Label', 'rewardsystem'),
                'desc' => __('Enter the User Name Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_cashback_userid_label',
                'css' => 'min-width:550px;',
                'std' => 'User Name',
                'type' => 'text',
                'newids' => 'rs_my_cashback_userid_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Requested for CashBack Label', 'rewardsystem'),
                'desc' => __('Enter the Requested for CashBack Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_cashback_requested_label',
                'css' => 'min-width:550px;',
                'std' => 'Requested for CashBack',
                'type' => 'text',
                'newids' => 'rs_my_cashback_requested_label',
                'desc_tip' => true,
            ),                                    
            array(
                'name' => __('Status Label', 'rewardsystem'),
                'desc' => __('Enter the Status On Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_cashback_status_label',
                'css' => 'min-width:550px;',
                'std' => 'Status',
                'type' => 'text',
                'newids' => 'rs_my_cashback_status_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Action Label', 'rewardsystem'),
                'desc' => __('Enter the Action On Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_cashback_action_label',
                'css' => 'min-width:550px;',
                'std' => 'Action',
                'type' => 'rs_action_for_cash_back',
                'newids' => 'rs_my_cashback_action_label',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_my_cashback_label_settings'),
              array(
                'name' => __('Referrer Label Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => '',
                'id' => '_rs_referrer_label_settings'
            ),
             array(
                'name' => __('Show/Hide To Display the message to referral person', 'rewardsystem'),                
                'id' => 'rs_show_hide_generate_referral_message',
                'std' => '1',
                'default' => '1',
                'newids' => 'rs_show_hide_generate_referral_message',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),                
            ),
             array(
                'name' => __('Message to display the referral person', 'rewardsystem'),                
                'id' => 'rs_show_hide_generate_referral_message_text',
                'css' => 'min-width:550px',
                'std' => 'You are being referred by [rs_referred_user_name]',
                'type' => 'text',
                'newids' => 'rs_show_hide_generate_referral_message_text',                
            ),
             array('type' => 'sectionend', 'id' => '_rs_referrer_label_settings'),
             
            array(
                'name' => __('My Reward Table Label Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => '',
                'id' => '_rs_my_reward_label_settings'
            ),
            array(
                'name' => __('Reward Table Postion', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_reward_table_position',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'newids' => 'rs_reward_table_position',
                'type' => 'select',
                'options' => array(
                    '1' => __('After My Account', 'rewardsystem'),
                    '2' => __('Before My Account', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('My Rewards Label', 'rewardsystem'),
                'desc' => __('Enter the My Rewards Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_rewards_title',
                'css' => 'min-width:550px;',
                'std' => 'My Rewards',
                'type' => 'text',
                'newids' => 'rs_my_rewards_title',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Total Points Label', 'rewardsystem'),
                'desc' => __('Enter the Total Points Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_rewards_total',
                'css' => 'min-width:550px;',
                'std' => 'Total Points: ',
                'type' => 'text',
                'newids' => 'rs_my_rewards_total',
                'desc_tip' => true,
            ),
            array(
                'name' => __('S.No Label', 'rewardsystem'),
                'desc' => __('Enter the Serial Number Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_rewards_sno_label',
                'css' => 'min-width:550px;',
                'std' => 'S.No',
                'type' => 'text',
                'newids' => 'rs_my_rewards_sno_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('User Name Label', 'rewardsystem'),
                'desc' => __('Enter the User Name Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_rewards_userid_label',
                'css' => 'min-width:550px;',
                'std' => 'User Name',
                'type' => 'text',
                'newids' => 'rs_my_rewards_userid_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Reward for Label', 'rewardsystem'),
                'desc' => __('Enter the Reward for Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_rewards_rewarder_label',
                'css' => 'min-width:550px;',
                'std' => 'Reward for',
                'type' => 'text',
                'newids' => 'rs_my_rewards_rewarder_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Earned Points Label', 'rewardsystem'),
                'desc' => __('Enter the Earned Points Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_rewards_points_earned_label',
                'css' => 'min-width:550px;',
                'std' => 'Earned Points',
                'type' => 'text',
                'newids' => 'rs_my_rewards_points_earned_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Redeemed Points Label', 'rewardsystem'),
                'desc' => __('Enter the Redeemed Points Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_rewards_redeem_points_label',
                'css' => 'min-width:550px;',
                'std' => 'Redeemed Points',
                'type' => 'text',
                'newids' => 'rs_my_rewards_redeem_points_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Total Points Label', 'rewardsystem'),
                'desc' => __('Enter the Total Points Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_rewards_total_points_label',
                'css' => 'min-width:550px;',
                'std' => 'Total Points',
                'type' => 'text',
                'newids' => 'rs_my_rewards_total_points_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Earned Date Label', 'rewardsystem'),
                'desc' => __('Enter the Date Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_rewards_date_label',
                'css' => 'min-width:550px;',
                'std' => 'Earned Date',
                'type' => 'text',
                'newids' => 'rs_my_rewards_date_label',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Points Expires On', 'rewardsystem'),
                'desc' => __('Enter the Point Expired On Label', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_my_rewards_points_expired_label',
                'css' => 'min-width:550px;',
                'std' => 'Points Expires On',
                'type' => 'text',
                'newids' => 'rs_my_rewards_points_expired_label',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_my_reward_label_settings'),
            array(
                'name' => __('Extra Class Name for Button', 'rewardsystem'),
                'type' => 'title',
                'desc' => '',
                'id' => '_rs_myaccount_custom_class_name',
            ),
            array(
                'name' => __('Extra Class Name for Generate Referral Link Button', 'rewardsystem'),
                'desc' => __('Add Extra Class Name to the My Account Generate Referral Link Button, Don\'t Enter dot(.) before Class Name', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_extra_class_name_generate_referral_link',
                'css' => 'min-width:550px;',
                'std' => '',
                'type' => 'text',
                'newids' => 'rs_extra_class_name_generate_referral_link',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Extra Class Name for Redeem Gift Voucher Button', 'rewardsystem'),
                'desc' => __('Add Extra Class Name to the My Account Redeem Gift Voucher Button, Don\'t Enter dot(.) before Class Name', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_extra_class_name_redeem_gift_voucher_button',
                'css' => 'min-width:550px;',
                'std' => '',
                'type' => 'text',
                'newids' => 'rs_extra_class_name_redeem_gift_voucher_button',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_myaccount_custom_class_name'),
            array(
                'name' => __('Custom CSS Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => 'Try !important if styles doesn\'t apply ',
                'id' => '_rs_my_reward_custom_css_settings'
            ),
            array(
                'name' => __('Custom CSS', 'rewardsystem'),
                'desc' => __('Enter the Custom CSS for My Account Page', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_myaccount_custom_css',
                'css' => 'min-width:350px;min-height:350px;',
                'std' => '#generate_referral_field { }  '
                . '#rs_redeem_voucher_code { }  '
                . '#ref_generate_now { } '
                . ' #rs_submit_redeem_voucher { }',
                'type' => 'textarea',
                'newids' => 'rs_myaccount_custom_css',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_my_reward_custom_css_settings'),
            array('type'=>'sectionend', 'id'=>'rs_myaccount_setting'),
        ));
    }
    
    /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSMyaccount::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSMyaccount::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSMyaccount::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
}

new RSMyaccount();
