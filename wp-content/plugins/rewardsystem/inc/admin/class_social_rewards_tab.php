<?php

class RSSocialReward {
    
     public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_socialrewards', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_socialrewards', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_socialrewards'] = __('Social Rewards','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
        global $woocommerce;
        return apply_filters('woocommerce_rewardsystem_social_reward_settings', array(
            array(
                'name' => __('Social Reward Settings', 'rewardsystem'),
                'type' => 'title',                
                'id' => '_rs_social_reward_setting'
            ),
            array(
                'name' => __('Facebook Application ID', 'rewardsystem'),
                'desc' => __('Please Enter Application ID of your Facebook', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_facebook_application_id',
                'css' => 'min-width:150px;',
                'std' => '',
                'type' => 'text',
                'newids' => 'rs_facebook_application_id',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Language Selection for Social Icons', 'rewardsystem'),                
                'id' => 'rs_language_selection_for_button',                             
                'std' => '1',
                'type' => 'radio',
                'options'=>array(
                    '1'=>__('English(US)','rewardsystem'),
                    '2'=>__('Default Site Language','rewardsystem'),
                ),
                'newids' => 'rs_language_selection_for_button',                 
            ),
            array(
                'name' => __('VK Application ID', 'rewardsystem'),
                'desc' => __('Please Enter Application ID of your VK', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_vk_application_id',
                'css' => 'min-width:150px;',
                'std' => '',
                'type' => 'text',
                'newids' => 'rs_vk_application_id',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_reward_point_facebook_application_settings'),
            array(
                'name' => __('Social Button Position Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Here you can position the social buttons', 'rewardsystem'),
                'id' => '_rs_reward_point_socialrewards_position_settings'
            ),
            array(
                'name' => __('Social Buttons Position', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_global_position_sumo_social_buttons',
                'css' => 'min-width:150px;',
                'std' => '5',
                'default' => '5',
                'desc' => __('Some  Theme Do Not Support the All Positions.If the Position not support then it might result in a JQuery Conflict.', 'rewardsystem'),
              
                'newids' => 'rs_global_position_sumo_social_buttons',
                'type' => 'select',
                'options' => array(
                    '1' => __('Before Single Product', 'rewardsystem'),
                    '2' => __('Before Single Product Summary', 'rewardsystem'),
                    '3' => __('Single Product Summary', 'rewardsystem'),
                    '4' => __('After Single Product', 'rewardsystem'),
                    '5' => __('After Single Product Summary', 'rewardsystem'),
                    '6' => __('After Product Meta End', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Social Button Position Type', 'rewardsystem'),
                'desc' =>'',
                'tip' => '',
                'id' => 'rs_social_button_position_troubleshoot',
                'newids' => 'rs_social_button_position_troubleshoot',
                'css' => 'min-width:150px;',
                'std' => 'inline',
                'default' => 'inline',
                'type' => 'select',
                'options' => array(
                    'inline' => __('Inline', 'rewardsystem'),
                    'inline-block' => __('Inline Block', 'rewardsystem'),
                    'inline-flex' => __('Inline Flex', 'rewardsystem'),
                    'inline-table' => __('Inline Table', 'rewardsystem'),
                    'table' => __('Table', 'rewardsystem'),
                    'block' => __('Block', 'rewardsystem'),
                    'flex' => __('Flex', 'rewardsystem'),
                ),
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_reward_point_socialrewards_position_settings'),
            array(
                'name' => __('Social Button URL Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Here you can Select the type of URL for Social Buttons', 'rewardsystem'),
                'id' => '_rs_reward_point_socialrewards_url_settings'
            ),
            array(
                'name' => __('Facebook URL Selection', 'rewardsystem'),
                'desc' =>'',
                'id' => 'rs_global_social_facebook_url',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_global_social_facebook_url',
                'type' => 'select',
                'options' => array(
                    '1' => __('Default', 'rewardsystem'),
                    '2' => __('Custom', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Facebook Custom URL', 'rewardsystem'),
                'desc' => __('Enter the Custom URL that you wish to enable for Facebook', 'rewardsystem'),
                'type' => 'text',
                'id' => 'rs_global_social_facebook_url_custom',
                'css' => 'min-width:150px;',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Twitter URL Selection', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_global_social_twitter_url',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_global_social_twitter_url',
                'type' => 'select',
                'options' => array(
                    '1' => __('Default', 'rewardsystem'),
                    '2' => __('Custom', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Twitter Custom URL', 'rewardsystem'),
                'desc' => __('Enter the Custom URL that you wish to enable for Twitter', 'rewardsystem'),
                'type' => 'text',
                'id' => 'rs_global_social_twitter_url_custom',
                'css' => 'min-width:150px;',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Google URL Selection', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_global_social_google_url',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_global_social_google_url',
                'type' => 'select',
                'options' => array(
                    '1' => __('Default', 'rewardsystem'),
                    '2' => __('Custom', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Google Custom URL', 'rewardsystem'),
                'desc' => __('Enter the Custom URL that you wish to enable for Google', 'rewardsystem'),
                'type' => 'text',
                'id' => 'rs_global_social_google_url_custom',
                'css' => 'min-width:150px;',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_reward_point_socialrewards_url_settings'),
            array(
                'name' => __('Social Reward Points Global', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_global_social_reward_points'
            ),
            array(
                'name' => __('Enable SUMO Reward Points for Social Promotion', 'rewardsystem'),
                'desc' => __('This helps to Enable Social Reward Points in Global Level', 'rewardsystem'),
                'id' => 'rs_global_social_enable_disable_reward',                
                'css' => 'min-width:150px;',
                'std' => '2',
                'default' => '2',
                'desc_tip' => true,
                'newids' => 'rs_global_social_enable_disable_reward',
                'type' => 'select',
                'options' => array(
                    '1' => __('Enable', 'rewardsystem'),
                    '2' => __('Disable', 'rewardsystem'),
                ),
            ),            
            array(
                'name' => __('Facebook Like Reward Type', 'rewardsystem'),
                'desc' =>'',
                'id' => 'rs_global_social_reward_type_facebook',
                'class' => 'show_if_social_tab_enable',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_global_social_reward_type_facebook',
                'type' => 'select',
                'options' => array(
                    '1' => __('By Fixed Reward Points', 'rewardsystem'),
                    '2' => __('By Percentage of Product Price', 'rewardsystem'),
                ),
            ),
            
            array(
                'name' => __('Facebook Like Reward Points', 'rewardsystem'),
                'desc' => __('Please Enter Reward Points for facebook', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_global_social_facebook_reward_points',
                'class' => 'show_if_social_tab_enable',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_global_social_facebook_reward_points',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Facebook Like Reward Percent %', 'rewardsystem'),
                'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_global_social_facebook_reward_percent',
                'class' => 'show_if_social_tab_enable',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_global_social_facebook_reward_percent',
                'desc_tip' => true,
            ),  
             array(
                'name' => __('Facebook Share Reward Type', 'rewardsystem'),
                'desc' =>'',
                'id' => 'rs_global_social_reward_type_facebook_share',
                'class' => 'show_if_social_tab_enable',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_global_social_reward_type_facebook_share',
                'type' => 'select',
                'options' => array(
                    '1' => __('By Fixed Reward Points', 'rewardsystem'),
                    '2' => __('By Percentage of Product Price', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Facebook Share Reward Points', 'rewardsystem'),
                'desc' => __('Please Enter Reward Points for facebook share', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_global_social_facebook_share_reward_points',
                'class' => 'show_if_social_tab_enable',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_global_social_facebook_share_reward_points',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Facebook share Reward Percent %', 'rewardsystem'),
                'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_global_social_facebook_share_reward_percent',
                'class' => 'show_if_social_tab_enable',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_global_social_facebook_share_reward_percent',
                'desc_tip' => true,
            ),  
            array(
                'name' => __('Twitter Tweet Reward Type', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_global_social_reward_type_twitter',
                'class' => 'show_if_social_tab_enable',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_global_social_reward_type_twitter',
                'type' => 'select',
                'options' => array(
                    '1' => __('By Fixed Reward Points', 'rewardsystem'),
                    '2' => __('By Percentage of Product Price', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Twitter Tweet Reward Points', 'rewardsystem'),
                'desc' => __('Please Enter Reward Points for Twitter', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_global_social_twitter_reward_points',
                'class' => 'show_if_social_tab_enable',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_global_social_twitter_reward_points',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Twitter Tweet Reward Percent %', 'rewardsystem'),
                'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_global_social_twitter_reward_percent',
                'class' => 'show_if_social_tab_enable',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_global_social_twitter_reward_percent',
                'desc_tip' => true,
            ),            
            array(
                'name' => __('Google+1 Reward Type', 'rewardsystem'),
                'desc' => __('Select Social Reward Type for Google by Points/Percentage', 'rewardsystem'),
                'id' => 'rs_global_social_reward_type_google',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_global_social_reward_type_google',
                'class' => 'show_if_social_tab_enable',
                'type' => 'select',
                'options' => array(
                    '1' => __('By Fixed Reward Points', 'rewardsystem'),
                    '2' => __('By Percentage of Product Price', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('Google+1 Reward Points', 'rewardsystem'),
                'desc' => __('Please Enter Reward Points for Google', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_global_social_google_reward_points',
                'class' => 'show_if_social_tab_enable',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_global_social_google_reward_points',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Google+1 Reward Percent %', 'rewardsystem'),
                'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_global_social_google_reward_percent',
                'class' => 'show_if_social_tab_enable',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_global_social_google_reward_percent',
                'desc_tip' => true,
            ),           
            array(
                'name' => __('VK.com Like Reward Type', 'rewardsystem'),
                'desc' => __('Select Social Reward Type for VK by Points/Percentage', 'rewardsystem'),
                'id' => 'rs_global_social_reward_type_vk',
                'css' => 'min-width:150px;',
                'class' => 'show_if_social_tab_enable',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_global_social_reward_type_vk',
                'type' => 'select',
                'options' => array(
                    '1' => __('By Fixed Reward Points', 'rewardsystem'),
                    '2' => __('By Percentage of Product Price', 'rewardsystem'),
                ),
            ),
            array(
                'name' => __('VK.com Like Reward Points', 'rewardsystem'),
                'desc' => __('Please Enter Reward Points for VK', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_global_social_vk_reward_points',
                'class' => 'show_if_social_tab_enable',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_global_social_vk_reward_points',
                'desc_tip' => true,
            ),
            array(
                'name' => __('VK.com Like Reward Percent %', 'rewardsystem'),
                'desc' => __('Please Enter Percentage value of Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_global_social_vk_reward_percent',
                'class' => 'show_if_social_tab_enable',
                'css' => 'min-width:150px;',
                'std' => ' ',
                'type' => 'text',
                'newids' => 'rs_global_social_vk_reward_percent',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_global_social_reward_points'),
            array('name'=>__('Individual Setting for Social Reward Point'),'type' => 'title', 'id' => '_rs_global_social_reward_points_show_hide'),
            array(
                'name' => __('Show/Hide Facebook Like Button', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_global_show_hide_facebook_like_button',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_global_show_hide_facebook_like_button',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),    
             array(
                'name' => __('Show/Hide Facebook Share Button', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_global_show_hide_facebook_share_button',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_global_show_hide_facebook_share_button',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),    
            array(
                'name' => __('Show/Hide Twitter Tweet Button', 'rewardsystem'),
                'desc' =>'',
                'id' => 'rs_global_show_hide_twitter_tweet_button',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_global_show_hide_twitter_tweet_button',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),         
            array(
                'name' => __('Show/Hide Google+1 Button', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_global_show_hide_google_plus_button',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_global_show_hide_google_plus_button',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),            
            array(
                'name' => __('Show/Hide VK.com Like Button', 'rewardsystem'),
                'desc' => '',
                'id' => 'rs_global_show_hide_vk_button',
                'css' => 'min-width:150px;',
                'std' => '1',
                'default' => '1',
                'desc_tip' => true,
                'newids' => 'rs_global_show_hide_vk_button',
                'type' => 'select',
                'options' => array(
                    '1' => __('Show', 'rewardsystem'),
                    '2' => __('Hide', 'rewardsystem'),
                ),
            ),         
            array('type' => 'sectionend', 'id' => '_rs_global_social_reward_points_show_hide'),
            array(
                'name' => __('Social Message Settings', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_global_social_message_settings'
            ),
            array(
                'name' => __('Show/Hide Social ToolTip for Facebook', 'rewardsystem'),
                'desc' =>'',
                'tip' => '',
                'id' => 'rs_global_show_hide_social_tooltip_for_facebook',
                'css' => 'min-width:150px;',
                'std' => '1',
                'type' => 'select',
                'options' => array(
                    '1' => 'Show',
                    '2' => 'Hide'
                ),
                'newids' => 'rs_global_show_hide_social_tooltip_for_facebook',
                'desc_tip' => true,
            ),
            
            array(
                'name' => __('ToolTip Facebook Like Message', 'rewardsystem'),
                'desc' => __('Enter ToolTip Message for Facebook Like', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_social_message_for_facebook',
                'css' => 'min-width:550px;',
                'std' => 'Facebook Like will fetch you [facebook_like_reward_points] Reward Points',
                'type' => 'textarea',
                'newids' => 'rs_social_message_for_facebook',
                'desc_tip' => true,
            ),
            
             array(
                'name' => __('Show/Hide Social ToolTip for Facebook Share', 'rewardsystem'),
                'desc' =>'',
                'tip' => '',
                'id' => 'rs_global_show_hide_social_tooltip_for_facebook_share',
                'css' => 'min-width:150px;',
                'std' => '1',
                'type' => 'select',
                'options' => array(
                    '1' => 'Show',
                    '2' => 'Hide'
                ),
                'newids' => 'rs_global_show_hide_social_tooltip_for_facebook_share',
                'desc_tip' => true,
            ),
            
              array(
                'name' => __('ToolTip Facebook Share Message', 'rewardsystem'),
                'desc' => __('Enter ToolTip Message for Facebook Share', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_social_message_for_facebook_share',
                'css' => 'min-width:550px;',
                'std' => 'Facebook Share will fetch you [facebook_share_reward_points] Reward Points',
                'type' => 'textarea',
                'newids' => 'rs_social_message_for_facebook_share',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Social ToolTip for Twitter', 'rewardsystem'),
                'desc' =>'',
                'tip' => '',
                'id' => 'rs_global_show_hide_social_tooltip_for_twitter',
                'css' => 'min-width:150px;',
                'std' => '1',
                'type' => 'select',
                'options' => array(
                    '1' => 'Show',
                    '2' => 'Hide'
                ),
                'newids' => 'rs_global_show_hide_social_tooltip_for_twitter',
                'desc_tip' => true,
            ),
            array(
                'name' => __('ToolTip Twitter Tweet Message ', 'rewardsystem'),
                'desc' => __('Enter ToolTip Message for Twitter', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_social_message_for_twitter',
                'css' => 'min-width:550px;',
                'std' => 'Twitter Tweet will fetch you [twitter_tweet_reward_points] Reward Points',
                'type' => 'textarea',
                'newids' => 'rs_social_message_for_twitter',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Social ToolTip for Google+1', 'rewardsystem'),
                'desc' =>'',
                'tip' => '',
                'id' => 'rs_global_show_hide_social_tooltip_for_google',
                'css' => 'min-width:150px;',
                'std' => '1',
                'type' => 'select',
                'options' => array(
                    '1' => 'Show',
                    '2' => 'Hide'
                ),
                'newids' => 'rs_global_show_hide_social_tooltip_for_google',
                'desc_tip' => true,
            ),
            array(
                'name' => __('ToolTip Google+1 Message ', 'rewardsystem'),
                'desc' => __('Enter ToolTip Message for Google+ Share', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_social_message_for_google_plus',
                'css' => 'min-width:550px;',
                'std' => 'Google+ Share will fetch you [google_share_reward_points] Reward Points',
                'type' => 'textarea',
                'newids' => 'rs_social_message_for_google_plus',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Show/Hide Social ToolTip for VK.com', 'rewardsystem'),
                'desc' =>'',
                'tip' => '',
                'id' => 'rs_global_show_hide_social_tooltip_for_vk',
                'css' => 'min-width:150px;',
                'std' => '1',
                'type' => 'select',
                'options' => array(
                    '1' => 'Show',
                    '2' => 'Hide'
                ),
                'newids' => 'rs_global_show_hide_social_tooltip_for_vk',
                'desc_tip' => true,
            ),
            array(
                'name' => __('ToolTip VK.com Like Message ', 'rewardsystem'),
                'desc' => __('Enter ToolTip Message for VK.com Like', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_social_message_for_vk',
                'css' => 'min-width:550px;',
                'std' => 'VK Share will fetch you [vk_reward_points] Reward Points',
                'type' => 'textarea',
                'newids' => 'rs_social_message_for_vk',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Facebook Like Success Message', 'rewardsystem'),
                'desc' => __('Enter the Message that will be displayed upon successful Facebook like', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_succcess_message_for_facebook_like',
                'css' => 'min-width:550px;',
                'std' => 'Thanks for liking the Product.  [facebook_like_reward_points] Reward Points has been added to your Account.',
                'type' => 'textarea',
                'newids' => 'rs_succcess_message_for_facebook_like',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Facebook Unlike Message', 'rewardsystem'),
                'desc' => __('Enter the Message that will be displayed upon when Facebook unlike', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_unsucccess_message_for_facebook_unlike',
                'css' => 'min-width:550px;',
                'std' => 'You have already Unliked this product on Facebook.You cannot earn points again',
                'type' => 'textarea',
                'newids' => 'rs_unsucccess_message_for_facebook_unlike',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Google+ Share Success Message', 'rewardsystem'),
                'desc' => __('Enter the Message that will be displayed upon successful Google+ Share', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_succcess_message_for_google_share',
                'css' => 'min-width:550px;',
                'std' => 'Thanks for Sharing the Product on Google+ . [google_share_reward_points] Reward Points has been added to your Account',
                'type' => 'textarea',
                'newids' => 'rs_succcess_message_for_google_share',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Google+ UnShare Message', 'rewardsystem'),
                'desc' => __('Enter the Message that will be displayed upon when unshare Google+', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_unsucccess_message_for_google_unshare',
                'css' => 'min-width:550px;',
                'std' => 'You have already Unshared this product on Google +.You cannot earn points again',
                'type' => 'textarea',
                'newids' => 'rs_unsucccess_message_for_google_unshare',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Twitter tweet Success Message', 'rewardsystem'),
                'desc' => __('Enter the Message that will be displayed upon successful Twitter Tweet', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_succcess_message_for_twitter_share',
                'css' => 'min-width:550px;',
                'std' => 'Thanks for the tweet . [twitter_tweet_reward_points] Reward Points has been added to your Account',
                'type' => 'textarea',
                'newids' => 'rs_succcess_message_for_twitter_share',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Twitter tweet UnSuccess Message', 'rewardsystem'),
                'desc' => __('Enter the Message that will be displayed when tweet deleted in Twitter', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_unsucccess_message_for_twitter_unshare',
                'css' => 'min-width:550px;',
                'std' => 'You have already Unshared this product on Twitter.You cannot earn points again',
                'type' => 'textarea',
                'newids' => 'rs_unsucccess_message_for_twitter_unshare',
                'desc_tip' => true,
            ),
            array(
                'name' => __('VK Like Success Message', 'rewardsystem'),
                'desc' => __('Enter the Message that will be displayed upon successful VK Like', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_succcess_message_for_vk',
                'css' => 'min-width:550px;',
                'std' => 'Thanks for the like on VK. [vk_reward_points] Reward Points has been added to your Account',
                'type' => 'textarea',
                'newids' => 'rs_succcess_message_for_vk',
                'desc_tip' => true,
            ),
            array(
                'name' => __('VK Unlike Message', 'rewardsystem'),
                'desc' => __('Enter the Message that will be displayed Unlike VK', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_unsucccess_message_for_vk',
                'css' => 'min-width:550px;',
                'std' => 'You have already Unlike this product on VK.You cannot earn points again',
                'type' => 'textarea',
                'newids' => 'rs_unsucccess_message_for_vk',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_global_social_message_settings'),
            array(
                'name' => __('ToolTip Customization', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_global_social_color_customization'
            ),
            array(
                'name' => __('ToolTip Background Color', 'rewardsystem'),
                'desc' => __('Enter ToolTip Background Color', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_social_tooltip_bg_color',
                'css' => 'min-width:150px;',
                'std' => '000',
                'type' => 'text',
                'class' => 'color',
                'newids' => 'rs_social_tooltip_bg_color',
                'desc_tip' => true,
            ),
            array(
                'name' => __('ToolTip Text Color', 'rewardsystem'),
                'desc' => __('Enter ToolTip Text Color', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_social_tooltip_text_color',
                'css' => 'min-width:150px;',
                'std' => 'fff',
                'type' => 'text',
                'class' => 'color',
                'newids' => 'rs_social_tooltip_text_color',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_global_social_color_customization'),
            array(
                'name' => __('Custom CSS', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_global_social_troubleshoot'
            ),
            array(
                'name' => __('Custom CSS', 'rewardsystem'),
                'desc' => __('Choose your Custom CSS Style for Social Sharing Buttons', 'rewardsystem'),
                'tip' => '',
                'id' => 'rs_social_custom_css',
                'css' => 'min-width:350px;min-height:250px;',
                'std' => '.rs_social_sharing_buttons{};'
                . '.rs_social_sharing_success_message',
                'newids' => 'rs_social_custom_css',
                'type' => 'textarea',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_global_social_troubleshoot'),
            array('type' => 'sectionend', 'id' => '_rs_social_reward_setting'),
        ));     
    }
    
        /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSSocialReward::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSSocialReward::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSSocialReward::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
}

new RSSocialReward();