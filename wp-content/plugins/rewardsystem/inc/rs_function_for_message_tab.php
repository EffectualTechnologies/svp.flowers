<?php

class RSFunctionForMessage {
    
    public function __construct() {
        add_action('woocommerce_admin_field_rs_list_of_shortcodes',array($this,'rs_list_of_shortcodes_in_message_tab'));
        
        add_action('admin_head',array($this,'rs_show_or_hide_in_message'));
    }
    
     public static function rs_list_of_shortcodes_in_message_tab() {
        ?>
        
            <pre>
                <strong>
                       For Single Product Page
                       [rewardpoints] --> Single Product Reward Points
                       [equalamount] --> Value of Earning Points

                       For Variable Product Page
                       [variationrewardpoints] --> Variable Product Reward Points
                       [variationpointsvalue] --> Value of Earning Points

                       For Cart/Checkout Page
                       [loginlink] --> Login Link for Non-Members
                       [rspoint] --> Points for Each Product in Cart
                       [carteachvalue] --> Points Value of Each Product in Cart
                       [totalrewards] --> Total Reward Points in Cart
                       [totalrewardsvalue] --> Total Reward Points Value in Cart
                       [userpoints]  --> Show User Points
                       [userpoints_value]  --> Show Value of User Points
                       [redeempoints]  --> Redeem Points
                       [redeemeduserpoints] --> Remaining User Points after Redeeming
                       {rssitelinkwithid} --> Link to Unsubscribe from Emails
                </strong>
            </pre>        
        <?php
    }
    
    public static function rs_show_or_hide_in_message(){
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function(){
                
                //Show or Hide for Message for single product page
                if(jQuery('#rs_show_hide_message_for_single_product').val() == '1'){
                    jQuery('#rs_message_for_single_product_point_rule').parent().parent().show();
                }else {
                    jQuery('#rs_message_for_single_product_point_rule').parent().parent().hide();
                }
                
                jQuery('#rs_show_hide_message_for_single_product').change(function(){
                    if(jQuery('#rs_show_hide_message_for_single_product').val() == '1'){
                        jQuery('#rs_message_for_single_product_point_rule').parent().parent().show();
                    }else {
                        jQuery('#rs_message_for_single_product_point_rule').parent().parent().hide();
                    }
                });
                
                //Show or Hide Earn Point Message in Shop Page
                if(jQuery('#rs_show_hide_message_for_simple_in_shop').val() == '1'){
                    jQuery('#rs_message_in_shop_page_for_simple').parent().parent().show();
                    jQuery('#rs_message_position_for_simple_products_in_shop_page').parent().parent().show();                    
                }else {
                    jQuery('#rs_message_in_shop_page_for_simple').parent().parent().hide();
                    jQuery('#rs_message_position_for_simple_products_in_shop_page').parent().parent().hide();
                }
                
                jQuery('#rs_show_hide_message_for_simple_in_shop').change(function(){
                    if(jQuery('#rs_show_hide_message_for_simple_in_shop').val() == '1'){
                        jQuery('#rs_message_in_shop_page_for_simple').parent().parent().show();
                        jQuery('#rs_message_position_for_simple_products_in_shop_page').parent().parent().show();
                    }else {
                        jQuery('#rs_message_in_shop_page_for_simple').parent().parent().hide();
                        jQuery('#rs_message_position_for_simple_products_in_shop_page').parent().parent().hide();
                    }
                });
                
                //Show or Hide Earn Point Message in Single Product Page
                if(jQuery('#rs_show_hide_message_for_shop_archive_single').val() == '1'){
                    jQuery('#rs_message_in_single_product_page').parent().parent().show();
                }else {
                    jQuery('#rs_message_in_single_product_page').parent().parent().hide();
                }
                
                jQuery('#rs_show_hide_message_for_shop_archive_single').change(function(){
                    if(jQuery('#rs_show_hide_message_for_shop_archive_single').val() == '1'){
                        jQuery('#rs_message_in_single_product_page').parent().parent().show();
                    }else {
                        jQuery('#rs_message_in_single_product_page').parent().parent().hide();
                    }
                });
                
                //Show or Hide Earn Point Message in Single Product Page for Variable Products
                if(jQuery('#rs_show_hide_message_for_variable_in_single_product_page').val() == '1'){
                    jQuery('#rs_message_for_single_product_variation').parent().parent().show();
                    jQuery('#rs_message_position_in_single_product_page_for_simple_products').parent().parent().show();                    
                }else {
                    jQuery('#rs_message_for_single_product_variation').parent().parent().hide();
                    jQuery('#rs_message_position_in_single_product_page_for_simple_products').parent().parent().hide();
                }
                
                jQuery('#rs_show_hide_message_for_variable_in_single_product_page').change(function(){
                    if(jQuery('#rs_show_hide_message_for_variable_in_single_product_page').val() == '1'){
                        jQuery('#rs_message_for_single_product_variation').parent().parent().show();
                        jQuery('#rs_message_position_in_single_product_page_for_simple_products').parent().parent().show();
                    }else {
                        jQuery('#rs_message_for_single_product_variation').parent().parent().hide();
                        jQuery('#rs_message_position_in_single_product_page_for_simple_products').parent().parent().hide();
                    }
                });
                
                //Show or Hide Message for each Variant (Variable Product) in Single Product Page
                if(jQuery('#rs_show_hide_message_for_variable_product').val() == '1'){
                    jQuery('#rs_message_for_variation_products').parent().parent().show();
                }else {
                    jQuery('#rs_message_for_variation_products').parent().parent().hide();
                }
                
                jQuery('#rs_show_hide_message_for_variable_product').change(function(){
                    if(jQuery('#rs_show_hide_message_for_variable_product').val() == '1'){
                        jQuery('#rs_message_for_variation_products').parent().parent().show();
                    }else {
                        jQuery('#rs_message_for_variation_products').parent().parent().hide();
                    }
                });
                
                //Show or Hide Message for Guest in Cart Page
                if(jQuery('#rs_show_hide_message_for_guest').val() == '1'){
                    jQuery('#rs_message_for_guest_in_cart').parent().parent().show();
                }else {
                    jQuery('#rs_message_for_guest_in_cart').parent().parent().hide();
                }
                
                jQuery('#rs_show_hide_message_for_guest').change(function(){
                    if(jQuery('#rs_show_hide_message_for_guest').val() == '1'){
                        jQuery('#rs_message_for_guest_in_cart').parent().parent().show();
                    }else {
                        jQuery('#rs_message_for_guest_in_cart').parent().parent().hide();
                    }
                });
                
                //Show or Hide Message in Cart Page for each Products
                if(jQuery('#rs_show_hide_message_for_each_products').val() == '1'){
                    jQuery('#rs_message_product_in_cart').parent().parent().show();
                }else {
                    jQuery('#rs_message_product_in_cart').parent().parent().hide();
                }
                
                jQuery('#rs_show_hide_message_for_each_products').change(function(){
                    if(jQuery('#rs_show_hide_message_for_each_products').val() == '1'){
                        jQuery('#rs_message_product_in_cart').parent().parent().show();
                    }else {
                        jQuery('#rs_message_product_in_cart').parent().parent().hide();
                    }
                });
                
                //Show or Hide Message in Cart Page for Completing the Total Purchase
                if(jQuery('#rs_show_hide_message_for_total_points').val() == '1'){
                    jQuery('#rs_message_total_price_in_cart').parent().parent().show();
                }else {
                    jQuery('#rs_message_total_price_in_cart').parent().parent().hide();
                }
                
                jQuery('#rs_show_hide_message_for_total_points').change(function(){
                    if(jQuery('#rs_show_hide_message_for_total_points').val() == '1'){
                        jQuery('#rs_message_total_price_in_cart').parent().parent().show();
                    }else {
                        jQuery('#rs_message_total_price_in_cart').parent().parent().hide();
                    }
                });
                
                //Show or Hide Message in Cart Page that display Your Reward Points
                if(jQuery('#rs_show_hide_message_for_my_rewards').val() == '1'){
                    jQuery('#rs_message_user_points_in_cart').parent().parent().show();
                }else {
                    jQuery('#rs_message_user_points_in_cart').parent().parent().hide();
                }
                
                jQuery('#rs_show_hide_message_for_my_rewards').change(function(){
                    if(jQuery('#rs_show_hide_message_for_my_rewards').val() == '1'){
                        jQuery('#rs_message_user_points_in_cart').parent().parent().show();
                    }else {
                        jQuery('#rs_message_user_points_in_cart').parent().parent().hide();
                    }
                });
                
                //Show or Hide Message in Cart Page that Display Redeeming Your Points
                if(jQuery('#rs_show_hide_message_for_redeem_points').val() == '1'){
                    jQuery('#rs_message_user_points_redeemed_in_cart').parent().parent().show();
                }else {
                    jQuery('#rs_message_user_points_redeemed_in_cart').parent().parent().hide();
                }
                
                jQuery('#rs_show_hide_message_for_redeem_points').change(function(){
                    if(jQuery('#rs_show_hide_message_for_redeem_points').val() == '1'){
                        jQuery('#rs_message_user_points_redeemed_in_cart').parent().parent().show();
                    }else {
                        jQuery('#rs_message_user_points_redeemed_in_cart').parent().parent().hide();
                    }
                });
                                
                //Show or Hide Message for Guest in Checkout Page
                if(jQuery('#rs_show_hide_message_for_guest_checkout_page').val() == '1'){
                    jQuery('#rs_message_for_guest_in_checkout').parent().parent().show();
                }else {
                    jQuery('#rs_message_for_guest_in_checkout').parent().parent().hide();
                }
                
                jQuery('#rs_show_hide_message_for_guest_checkout_page').change(function(){
                    if(jQuery('#rs_show_hide_message_for_guest_checkout_page').val() == '1'){
                        jQuery('#rs_message_for_guest_in_checkout').parent().parent().show();
                    }else {
                        jQuery('#rs_message_for_guest_in_checkout').parent().parent().hide();
                    }
                });
                
                //Show or Hide Message in Checkout Page for each Products
                if(jQuery('#rs_show_hide_message_for_each_products_checkout_page').val() == '1'){
                    jQuery('#rs_message_product_in_checkout').parent().parent().show();
                }else {
                    jQuery('#rs_message_product_in_checkout').parent().parent().hide();
                }
                
                jQuery('#rs_show_hide_message_for_each_products_checkout_page').change(function(){
                    if(jQuery('#rs_show_hide_message_for_each_products_checkout_page').val() == '1'){
                        jQuery('#rs_message_product_in_checkout').parent().parent().show();
                    }else {
                        jQuery('#rs_message_product_in_checkout').parent().parent().hide();
                    }
                });
                
                //Show or Hide Message in Checkout Page for Completing the Total Purchase
                if(jQuery('#rs_show_hide_message_for_total_points_checkout_page').val() == '1'){
                    jQuery('#rs_message_total_price_in_checkout').parent().parent().show();
                }else {
                    jQuery('#rs_message_total_price_in_checkout').parent().parent().hide();
                }
                
                jQuery('#rs_show_hide_message_for_total_points_checkout_page').change(function(){
                    if(jQuery('#rs_show_hide_message_for_total_points_checkout_page').val() == '1'){
                        jQuery('#rs_message_total_price_in_checkout').parent().parent().show();
                    }else {
                        jQuery('#rs_message_total_price_in_checkout').parent().parent().hide();
                    }
                });
                
                //Show or Hide Message in Checkout Page that display Your Reward Points
                if(jQuery('#rs_show_hide_message_for_my_rewards_checkout_page').val() == '1'){
                    jQuery('#rs_message_user_points_in_checkout').parent().parent().show();
                }else {
                    jQuery('#rs_message_user_points_in_checkout').parent().parent().hide();
                }
                
                jQuery('#rs_show_hide_message_for_my_rewards_checkout_page').change(function(){
                    if(jQuery('#rs_show_hide_message_for_my_rewards_checkout_page').val() == '1'){
                        jQuery('#rs_message_user_points_in_checkout').parent().parent().show();
                    }else {
                        jQuery('#rs_message_user_points_in_checkout').parent().parent().hide();
                    }
                });
                
                //Show or Hide Message in Checkout Page that Display Redeeming Your Points
                if(jQuery('#rs_show_hide_message_for_redeem_points_checkout_page').val() == '1'){
                    jQuery('#rs_message_user_points_redeemed_in_checkout').parent().parent().show();
                }else {
                    jQuery('#rs_message_user_points_redeemed_in_checkout').parent().parent().hide();
                }
                
                jQuery('#rs_show_hide_message_for_redeem_points_checkout_page').change(function(){
                    if(jQuery('#rs_show_hide_message_for_redeem_points_checkout_page').val() == '1'){
                        jQuery('#rs_message_user_points_redeemed_in_checkout').parent().parent().show();
                    }else {
                        jQuery('#rs_message_user_points_redeemed_in_checkout').parent().parent().hide();
                    }
                });
                
                //Show or Hide Message for Payment Gateway Reward Points
                if(jQuery('#rs_show_hide_message_payment_gateway_reward_points').val() == '1'){
                    jQuery('#rs_message_payment_gateway_reward_points').parent().parent().show();
                }else {
                    jQuery('#rs_message_payment_gateway_reward_points').parent().parent().hide();
                }
                
                jQuery('#rs_show_hide_message_payment_gateway_reward_points').change(function(){
                    if(jQuery('#rs_show_hide_message_payment_gateway_reward_points').val() == '1'){
                        jQuery('#rs_message_payment_gateway_reward_points').parent().parent().show();
                    }else {
                        jQuery('#rs_message_payment_gateway_reward_points').parent().parent().hide();
                    }
                });
                
            });
        </script>
        <?php
    }
}

new RSFunctionForMessage();