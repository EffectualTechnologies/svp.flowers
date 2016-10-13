<?php

class RSLocalization {
    
    public function __construct() {
        
        add_action('init', array($this, 'reward_system_default_settings'),103);// call the init function to update the default settings on page load
        
        add_filter('woocommerce_rs_settings_tabs_array',array($this,'reward_system_tab_setting'));// Register a New Tab in a WooCommerce Reward System Settings        
        
        add_action('woocommerce_rs_settings_tabs_rewardsystem_localization', array($this, 'reward_system_register_admin_settings'));// Call to register the admin settings in the Reward System Submenu with general Settings tab        
        
        add_action('woocommerce_update_options_rewardsystem_localization', array($this, 'reward_system_update_settings'));// call the woocommerce_update_options_{slugname} to update the reward system                               
    }
    
    /*
     * Function to Define Name of the Tab
     */
    public static function reward_system_tab_setting($setting_tabs){
        $setting_tabs['rewardsystem_localization'] = __('Localization','rewardsystem');
        return $setting_tabs;
    }
    
     /*
     * Function label settings to Member Level Tab
     */
    public static function reward_system_admin_fields() {
        global $woocommerce;
        
        return apply_filters('woocommerce_rewardsystem_localization_settings', array(
            array(
                'name' => __('Localization Setting', 'rewardsystem'),
                'type' => 'title',                             
                'id' => '_rs_localization_setting'
            ),               
            array(
                'name' => __('Referral Log Localization', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Referral Log Information for the localization options', 'rewardsystem'),
                'id' => '_rs_referral_log_localization_settings',
            ),
            array(
                'name' => __('Referral Reward Points', 'rewardsystem'),
                'desc' => __('Localize your Referral Reward Points earned for Purchase', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_referral_reward_points_for_purchase',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Referral Reward Points earned for Purchase {itemproductid} by {purchasedusername}',
                'newids' => '_rs_localize_referral_reward_points_for_purchase',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_referral_log_localization_settings'),
            array(
                'name' => __('Reward Points Log for user login', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Reward Points Log Information for user login', 'rewardsystem'),
                'id' => '_rs_reward_points_log_for_login_settings',
            ),
            array(
                'name' => __('Points earned for login once per day ', 'rewardsystem'),
                'desc' => __('Localize Reward Points earned for login once per day ', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_reward_points_for_login',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Points Earned for today login',
                'newids' => '_rs_localize_reward_points_for_login',
                'desc_tip' => true,
            ),
            
            array('type' => 'sectionend', 'id' => '_rs_reward_points_log_for_login_settings'),
            array(
                'name' => __('Product Purchase Log Localization', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Product Purchase Log Information for localization options', 'rewardsystem'),
                'id' => '_rs_product_purchase_log_localization_settings',
            ),
            array(
                'name' => __('Points earned for Purchase ', 'rewardsystem'),
                'desc' => __('Localize Reward Points earned for purchase log', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_product_purchase_reward_points',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Points Earned for Purchasing the Product #{itemproductid} with Order {currentorderid}',
                'newids' => '_rs_localize_product_purchase_reward_points',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Points earned for Purchase --> Main', 'rewardsystem'),
                'desc' => __('Localize Points earned for Purchase -->Main', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_points_earned_for_purchase_main',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Points Earned for Purchasing the Product of Order {currentorderid}',
                'newids' => '_rs_localize_points_earned_for_purchase_main',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_product_purchase_settings'),
            array(
                'name' => __('Product Redeeming Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Product Redeeming Log Information for localization options', 'rewardsystem'),
                'id' => '_rs_product_redeeming_settings',
            ),
            array(
                'name' => __('Points Redeemed Towards Purchase', 'rewardsystem'),
                'desc' => __('Localize Redeeming Towards Purchase log Information', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_points_redeemed_towards_purchase',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Points Redeemed Towards Purchase for Order {currentorderid}',
                'newids' => '_rs_localize_points_redeemed_towards_purchase',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_product_redeeming_settings'),
            array(
                'name' => __('Registration Reward Points Log Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Registration Reward Points Log Settings', 'rewardsystem'),
                'id' => '_rs_log_registration_reward_points',
            ),
            array(
                'name' => __('Points Earned for Registration', 'rewardsystem'),
                'desc' => __('Localize the Points Earned for Registration Message', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_points_earned_for_registration',
                'css' => 'min-width:550px',
                'type' => 'textarea',
                'std' => 'Points Earned for Registration',
                'newids' => '_rs_localize_points_earned_for_registration',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Points Earned for Referral Registration', 'rewardsystem'),
                'desc' => __('Localize the Points Earned for Referral Registration Message', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_points_earned_for_referral_registration',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Points Earned for Referral Registration by {registereduser}',
                'newids' => '_rs_localize_points_earned_for_referral_registration',
                'desc_tip' => true,
            ),
            
            array('type' => 'sectionend', 'id' => '_rs_log_registration_reward_points'),
            array(
                'name' => __('Send Points Log Localization', 'rewardsystem'),
                'type' => 'title',
                'id' => 'rs_log_for_sendpoints',
            ),
            array(
                'name' => __('Log to be Displayed for Points Recived User in My Account Page', 'rewardsystem'),
                'desc' => __('Entered Log will be displayed in My Account Page of Reciver', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_log_for_reciver',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => '[name] Received [points] Points from [user]',
                'newids' => '_rs_localize_log_for_reciver',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Log to be Displayed for Point Send User in My Account Page', 'rewardsystem'),
                'desc' => __('Entered Log will be displayed in My Account Page of Sender', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_log_for_sender',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => '[name] [points] Points has been Send to [user]',
                'newids' => '_rs_localize_log_for_sender',
                'desc_tip' => true,
            ),   

             array(
                'name' => __('Log to be Displayed When User Request for Send Points Cancelled', 'rewardsystem'),
                'desc' => __('Revised Localize Points To Cash Back Log', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_points_to_send_log_revised',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Admin has been Cancelled your Request For Send Points.So Your Requested Send Points were revised to your account',
                'newids' => '_rs_localize_points_to_send_log_revised',
                'desc_tip' => true,
            ),         
            array('type' => 'sectionend', 'id' => 'rs_log_for_sendpoints'),

            array(
                'name' => __('Product Review Log Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Localize Product Review Log Settings', 'rewardsystem'),
                'id' => '_rs_review_localize_settings',
            ),
            array(
                'name' => __('Points Earned for Product Review', 'rewardsystem'),
                'desc' => __('Localize Points Earned for Product Review Message', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_points_earned_for_product_review',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Reward for Reviewing a Product {reviewproductid}',
                'newids' => '_rs_localize_points_earned_for_product_review',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_review_localize_settings'),
            
             array(
                'name' => __('Post Review Log Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Localize Post Review Log Settings', 'rewardsystem'),
                'id' => '_rs_post_review_localize_settings',
            ),
            array(
                'name' => __('Points Earned for Post Review', 'rewardsystem'),
                'desc' => __('Localize Points Earned for Post Review Message', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_points_earned_for_post_review',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Reward for Commenting a Post {postid}',
                'newids' => '_rs_localize_points_earned_for_post_review',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_post_review_localize_settings'),
            
            array(
                'name' => __('Page Review Log Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Localize Page Review Log Settings', 'rewardsystem'),
                'id' => '_rs_page_review_localize_settings',
            ),
            array(
                'name' => __('Points Earned for Page Review', 'rewardsystem'),
                'desc' => __('Localize Points Earned for Page Review Message', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_points_earned_for_page_review',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Reward for Commenting a Page {pagename}',
                'newids' => '_rs_localize_points_earned_for_page_review',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_page_review_localize_settings'),
            
            
            array(
                'name' => __('Blog Post Creation Log Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Localize Product Review Log Settings', 'rewardsystem'),
                'id' => '_rs_blogposts_localize_settings',
            ),
            array(
                'name' => __('Points Earned for Creating a Post', 'rewardsystem'),
                'desc' => __('Localize Points Earned for Post Message', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_points_earned_for_post',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Reward for Posting {postid}',
                'newids' => '_rs_localize_points_earned_for_post',
                'desc_tip' => true,
            ),

            array('type' => 'sectionend', 'id' => '_rs_blogposts_localize_settings'),
            array(
                'name' => __('Revise Referral Purchase Log Message', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Localize Revise Referral Purchased Log Message', 'rewardsystem'),
                'id' => '_rs_revise_referral_purchase_log_settings',
            ),
            array(
                'name' => __('Revise Referral Reward Points', 'rewardsystem'),
                'desc' => __('Localize Revise Referral Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_log_revise_referral_product_purchase',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Revised Referral Product Purchase {productid}',
                'newids' => '_rs_log_revise_referral_product_purchase',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_revise_referral_purchase_log_settings'),
            array(
                'name' => __('Revise Purchase Log Message', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Localize Revise Purchased Log Message', 'rewardsystem'),
                'id' => '_rs_revise_purchase_log_settings',
            ),
            array(
                'name' => __('Revise Product Purchase', 'rewardsystem'),
                'desc' => __('Localize Revise Product Purchase for Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_log_revise_product_purchase',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Revised Product Purchase {productid}',
                'newids' => '_rs_log_revise_product_purchase',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Revise Product Purchase --> Main', 'rewardsystem'),
                'desc' => __('Localize Revise Product Purchase for Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_log_revise_product_purchase_main',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Revised Product Purchase {currentorderid}',
                'newids' => '_rs_log_revise_product_purchase_main',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_revise_purchase_log_settings'),
            array(
                'name' => __('Revised Product Redeeming Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Localize Revised Product Redeeming Settings', 'rewardsystem'),
                'id' => '_rs_revise_product_redeeming_settings',
            ),
            array(
                'name' => __('Revised Points Redeemed Towards Purchase', 'rewardsystem'),
                'desc' => __('Localize your Revised Points Redeemed Towards Purchase', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_log_revise_points_redeemed_towards_purchase',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Revise Points Redeemed Towards Purchase',
                'newids' => '_rs_log_revise_points_redeemed_towards_purchase',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_revise_product_redeeming_settings'),
            array(
                'name' => __('Revised Points on Deleting Referral Registration Settings', 'rewardsystem'),
                'type' => 'title',
                'desc' => __('Localize Revised Points for Deleted Referral User Settings', 'rewardsystem'),
                'id' => '_rs_localize_revise_points_for_deleted_user',
            ),
            array(
                'name' => __('Referral Account Signup Points Revised', 'rewardsystem'),
                'desc' => __('Localize Referral Account Signup Points Revised Option', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_referral_account_signup_points_revised',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Referral Account Signup Points Revised with Referred User Deleted {usernickname}',
                'newids' => '_rs_localize_referral_account_signup_points_revised',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Revised Points for Referral Purchase', 'rewardsystem'),
                'desc' => __('Localize Revised Points for Referral Purchase Message', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_revise_points_for_referral_purchase',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Revised Referral Reward Points earned for Purchase {productid} by deleted user {usernickname}',
                'newids' => '_rs_localize_revise_points_for_referral_purchase',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_localize_revise_points_for_deleted_user'),
            array(
                'name' => __('Social Rewards Localization Settings', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_localize_social_reward_points',
            ),
            array(
                'name' => __('Reward for Social Facebook Like', 'rewardsystem'),
                'desc' => __('Localize Reward for Social Facebook Like', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_reward_for_facebook_like',
                'css' => 'min-width:550px',
                'type' => 'textarea',
                'std' => 'Reward for Social Facebook Like',
                'newids' => '_rs_localize_reward_for_facebook_like',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Reward for Social Facebook Share', 'rewardsystem'),
                'desc' => __('Localize Reward for Social Facebook Share', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_reward_for_facebook_share',
                'css' => 'min-width:550px',
                'type' => 'textarea',
                'std' => 'Reward for Social Facebook Share',
                'newids' => '_rs_localize_reward_for_facebook_share',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Reward for Social Twitter Tweet', 'rewardsystem'),
                'desc' => __('Localize Reward for Social Twitter Tweet', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_reward_for_twitter_tweet',
                'css' => 'min-width:550px',
                'type' => 'textarea',
                'std' => 'Reward for Social Twitter Tweet',
                'newids' => '_rs_localize_reward_for_twitter_tweet',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Reward for Social Google Plus', 'rewardsystem'),
                'desc' => __('Localize Reward for Social Google+', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_reward_for_google_plus',
                'css' => 'min-width:550px',
                'type' => 'textarea',
                'std' => 'Reward for Social Google Plus',
                'newids' => '_rs_localize_reward_for_google_plus',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Reward for Social VK.Com Like', 'rewardsystem'),
                'desc' => __('Localize Reward for Social VK.Com Like', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_reward_for_vk',
                'css' => 'min-width:550px',
                'type' => 'textarea',
                'std' => 'Reward for Social VK.Com Like',
                'newids' => '_rs_localize_reward_for_vk',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_localize_social_reward_points'),
            array(
                'name' => __('Revision of Social Rewards Localization', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_localize_social_redeeming',
            ),
            array(
                'name' => __('Reward for Social Facebook Like is Revised', 'rewardsystem'),
                'desc' => __('Localize Revised Reward for Social Facebook Like ', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_reward_for_facebook_like_revised',
                'css' => 'min-width:550px',
                'type' => 'textarea',
                'std' => 'Reward for Social Facebook Like is Revised',
                'newids' => '_rs_localize_reward_for_facebook_like_revised',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Reward for Social Google Plus is Revised', 'rewardsystem'),
                'desc' => __('Localize Revised Reward for Social Google Plus ', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_reward_for_google_plus_revised',
                'css' => 'min-width:550px',
                'type' => 'textarea',
                'std' => 'Reward for Social Google Plus is Revised',
                'newids' => '_rs_localize_reward_for_google_plus_revised',
                'desc_tip' => true,
            ),
            
            array(
                'name' => __('Reward for Social VK.Com Like is Revised', 'rewardsystem'),
                'desc' => __('Localize Revised Reward for Social VK.Com Like', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_reward_for_vk_like_revised',
                'css' => 'min-width:550px',
                'type' => 'textarea',
                'std' => 'Reward for Social VK.Com Like is Revised',
                'newids' => '_rs_localize_reward_for_vk_like_revised',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_localize_social_redeeming'),
            array(
                'name' => __('Payment Gateway Reward Points Message', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_payment_gateway_reward_points',
            ),
            array(
                'name' => __('Reward for Using Payment Gateway', 'rewardsystem'),
                'desc' => __('Localize Reward for Using Payment Gateway', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_reward_for_payment_gateway_message',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Reward Points for Using Payment Gateway {payment_title}',
                'newids' => '_rs_localize_reward_for_payment_gateway_message',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Revised Reward Points for Using Payment Gateway ', 'rewardsystem'),
                'desc' => __('Localize Revised Reward Points for Using Payment Gateway', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_revise_reward_for_payment_gateway_message',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Revised Reward Points for Using Payment Gateway {payment_title}',
                'newids' => '_rs_localize_revise_reward_for_payment_gateway_message',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_payment_gateways_reward_points'),
            array(
                'name' => __('Voucher Code Log Localization', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_voucher_code_log_localization',
            ),
            array(
                'name' => __('Voucher Code Log Localization', 'rewardsystem'),
                'desc' => __('Localize Voucher Code Log Message in SUMO Reward Points', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_voucher_code_usage_log_message',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Redeem Voucher Code {rsusedvouchercode}',
                'newids' => '_rs_localize_voucher_code_usage_log_message',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_voucher_code_log_localization'),
            array(
                'name' => __('Buying Reward Points Log Localization', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_buying_reward_points_localization',
            ),
            array(
                'name' => __('Buying Reward Points Log Localization', 'rewardsystem'),
                'desc' => __('Localize Buying Reward Points Log', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_buying_reward_points_log',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Bought Reward Points  {rsbuyiedrewardpoints}',
                'newids' => '_rs_localize_buying_reward_points_log',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_buying_reward_points_localization'),
            array(
                'name' => __('Coupon Reward Points Log Localization', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_coupon_reward_points_localization',
            ),
            array(
                'name' => __('Coupon Reward Points Log Localization', 'rewardsystem'),
                'desc' => __('Localize Coupon Reward Points Log', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_coupon_reward_points_log',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Points Earned for using Coupons',
                'newids' => '_rs_localize_coupon_reward_points_log',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => 'rs_log_for_max_earning'),            
            array(
                'name' => __('Maximum Earning Points For User Log Localization', 'rewardsystem'),
                'type' => 'title',
                'id' => 'rs_log_for_max_earning',
            ),
            array(
                'name' => __('Log to be Displayed When User Restricted to Maximum Earning Points', 'rewardsystem'),
                'desc' => __('Localize Maximum Earnin Points Log', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_max_earning_points_log',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'You Cannot Earn More than [rsmaxpoints]',
                'newids' => '_rs_localize_max_earning_points_log',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => 'rs_log_for_max_earning'),  
            array(
                'name' => __('Reward Points Gateway Log Localization', 'rewardsystem'),
                'type' => 'title',
                'id' => '_rs_reward_points_gateway_localization',
            ),
            array(
                'name' => __('Reward Points Gateway Log Localization Message', 'rewardsystem'),
                'desc' => __('Localize Reward Points Gateway Points Log', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_reward_points_gateway_log_localizaation',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Points Redeemed for using Reward Points Gateway',
                'newids' => '_rs_reward_points_gateway_log_localizaation',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => '_rs_reward_points_gateway_localization'),
            
            array(
                'name' => __('Point To Cash Back Log Localization', 'rewardsystem'),
                'type' => 'title',
                'id' => 'rs_log_for_points_to_cash',
            ),
            array(
                'name' => __('Log to be Displayed When User Request Cash Back for their Reward Points', 'rewardsystem'),
                'desc' => __('Localize Points To Cash Back Log', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_points_to_cash_log',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Points Requested For Cash Back',
                'newids' => '_rs_localize_points_to_cash_log',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Log to be Displayed When User Request Cash Back Cancelled', 'rewardsystem'),
                'desc' => __('Revised Localize Points To Cash Back Log', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_points_to_cash_log_revised',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'Admin has been Cancelled your Request For Cash Back.So Your Requested Cash Back Points were revised to your account',
                'newids' => '_rs_localize_points_to_cash_log_revised',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Log to be Displayed in My CashBack Table For User When they Request For Cash Back', 'rewardsystem'),
                'desc' => __('Localize Points To Cash Back Log', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_points_to_cash_log_in_my_cashback_table',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => 'You have Requested [pointstocashback] points for Cash Back ([cashbackamount])',
                'newids' => '_rs_localize_points_to_cash_log_in_my_cashback_table',
                'desc_tip' => true,
            ),
            array('type' => 'sectionend', 'id' => 'rs_log_for_points_to_cash'),
            
            array(
                'name' => __('Nominee Log Localization', 'rewardsystem'),
                'type' => 'title',
                'id' => 'rs_log_for_nominee',
            ),
            array(
                'name' => __('Log to be Displayed for Nominee in My Account Page', 'rewardsystem'),
                'desc' => __('Entered Log will be displayed in My Account Page of Nominee', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_log_for_nominee',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => '[name] Received [points] Points from [user]',
                'newids' => '_rs_localize_log_for_nominee',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Log to be Displayed for Nominated User in My Account Page', 'rewardsystem'),
                'desc' => __('Entered Log will be displayed in My Account Page of Nominated User', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_log_for_nominated_user',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => '[name] [points] Points has been nominated to [user]',
                'newids' => '_rs_localize_log_for_nominated_user',
                'desc_tip' => true,
            ),            
            array('type' => 'sectionend', 'id' => 'rs_log_for_nominee'),
            
            
            array(
                'name' => __('Import/Export Log Localization', 'rewardsystem'),
                'type' => 'title',
                'id' => 'rs_log_for_import_export',
            ),
            array(
                'name' => __('Log to be Displayed When Import Points Added with Existing Points', 'rewardsystem'),
                'desc' => __('Entered Log will be displayed in My Account Page of When Import Points Added with Existing Points', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_log_for_import_add',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => '[points] Points were added with existing points by importing',
                'newids' => '_rs_localize_log_for_import_add',
                'desc_tip' => true,
            ),
            array(
                'name' => __('Log to be Displayed When Import Points Override Existing Points', 'rewardsystem'),
                'desc' => __('Entered Log will be displayed in My Account Page of When Import Points Overrided', 'rewardsystem'),
                'tip' => '',
                'id' => '_rs_localize_log_for_import_override',
                'css' => 'min-width:550px;',
                'type' => 'textarea',
                'std' => '[points] Points were overrided by importing',
                'newids' => '_rs_localize_log_for_import_override',
                'desc_tip' => true,
            ),            
            array('type' => 'sectionend', 'id' => 'rs_log_for_import_export'),
            array('type'=>'sectionend', 'id'=>'_rs_localization_setting'),
           
        ));     
    }
    
        /**
     * Registering Custom Field Admin Settings of Sumo Reward Points in woocommerce admin fields funtion
     */
    public static function reward_system_register_admin_settings() {
        
        woocommerce_admin_fields(RSLocalization::reward_system_admin_fields());
    }

    /**
     * Update the Settings on Save Changes may happen in Sumo Reward Points
     */
    public static function reward_system_update_settings() {
        woocommerce_update_options(RSLocalization::reward_system_admin_fields());
    }

    /**
     * Initialize the Default Settings by looping this function
     */
    public static function reward_system_default_settings() {
        global $woocommerce;
        foreach (RSLocalization::reward_system_admin_fields() as $setting)
            if (isset($setting['newids']) && isset($setting['std'])) {
                add_option($setting['newids'], $setting['std']);
            }
    }
}
new RSLocalization();