<?php

class RSBuyingRewardPoints {
    
    public function __construct() {
              
        add_action('woocommerce_process_product_meta', array($this, 'save_admin_field_buying_reward_points'));
        
        add_action('admin_head', array($this, 'rs_show_hide_buy_reward_points'));
        
        add_action('rs_perform_action_for_order', array($this, 'award_points_after_buyied'));   
        
         add_action( 'woocommerce_product_options_pricing',array($this,'point_price_setting') );   
           add_action('woocommerce_product_options_pricing', array($this, 'rs_admin_field_buying_reward_points'), 1);
                        
    }
    
    
    public static function point_price_setting(){
      
    }
    
       /* Add Admin Field Buying Reward Points */

    public static function rs_admin_field_buying_reward_points() {
        
        global $post;
        if(is_admin()){
        ?>
        <div class="options_group show_if_simple">
            <?php
              global $post;
        $gettheproducts = get_product($post->ID);
                    if (is_object($gettheproducts)&&$gettheproducts->is_type('simple')) {
        if(get_option('rs_enable_disable_point_priceing')=='1'){
          woocommerce_wp_select(array(
                'id' => '_rewardsystem_enable_point_price',
                'class' => '_rewardsystem_enable_point_price',
                'label' => __('Enable Point Pricing', 'rewardsystem'),                
                'options' => array(                   
                    'no' => __('Disable', 'rewardsystem'),
                    'yes' => __('Enable', 'rewardsystem'),                    
                )
            ));
          
          
                 woocommerce_wp_select(array(
                'id' => '_rewardsystem_enable_point_price_type',
                'class' => '_rewardsystem_enable_point_price_type',
                'label' => __(' Pricing Display Type', 'rewardsystem'),                
                'options' => array(                   
                    '1' => __('Currency & Point Price', 'rewardsystem'),
                    '2' => __('Only Point Price', 'rewardsystem'),                    
                ),
                      'std'=>'1'
            ));
        }
    }

              if(get_option('rs_enable_disable_point_priceing')=='1'){
                
                woocommerce_wp_select(array(
                'id' => '_rewardsystem_point_price_type',
                'class' => '_rewardsystem_point_price_type',
                'label' => __('Point Price Type', 'rewardsystem'),                
                'options' => array(                   
                    '1' => __('By Fixed', 'rewardsystem'),
                    '2' => __('Based On Conversion', 'rewardsystem'),                    
                ),
                     'std'=>'1'
            ));
              woocommerce_wp_text_input(
                    array(
                        'id' => '_rewardsystem__points',
                        'class' => '_rewardsystem__points',
                        'name' => '_rewardsystem__points',
                        'label' => __('Points to product', 'rewardsystem')
           )
                      );
               woocommerce_wp_text_input(
                    array(
                        'id' => '_rewardsystem__points_based_on_conversion',
                        'class' => '_rewardsystem__points_based_on_conversion',
                        'name' => '_rewardsystem__points_based_on_conversion',
                       'readonly'=>"readonly",
                        'label' => __('Points Based On Conversion', 'rewardsystem')
           ));
              }
            woocommerce_wp_select(array(
                'id' => '_rewardsystem_buying_reward_points',
                'class' => '_rewardsystem_buying_reward_points',
                'label' => __('Enable Buying of SUMO Reward Points', 'rewardsystem'),                
                'options' => array(                   
                    'no' => __('Disable', 'rewardsystem'),
                    'yes' => __('Enable', 'rewardsystem'),                    
                )
            ));
            woocommerce_wp_text_input(
                    array(
                        'id' => '_rewardsystem_assign_buying_points',
                        'class' => 'show_if_buy_reward_points_enable',
                        'name' => '_rewardsystem_assign_buying_points',
                        'label' => __('Buy Reward Points', 'rewardsystem')
            ));
            ?>
        </div>
        <?php
        }
    }

    /* Save Admin Field for Buying Reward Points */

    public static function save_admin_field_buying_reward_points($post_id) {
        $woocommerce_buying_reward_select = $_POST['_rewardsystem_buying_reward_points'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_rewardsystem_buying_reward_points', $woocommerce_buying_reward_select);
        $woocommerce_rewardpoints = $_POST['_rewardsystem_assign_buying_points'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_rewardsystem_assign_buying_points', $woocommerce_rewardpoints);
        $woocommerce_rewardpoints_enable = $_POST['_rewardsystem_enable_point_price'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_rewardsystem_enable_point_price', $woocommerce_rewardpoints_enable);
        $woocommerce_points_reward_select = $_POST['_rewardsystem__points'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_rewardsystem__points', $woocommerce_points_reward_select);
        $woocommerce_points_reward_type_select = $_POST['_rewardsystem_point_price_type'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_rewardsystem_point_price_type', $woocommerce_points_reward_type_select);  
             
         $woocommerce_rewardpoints_enable_type = $_POST['_rewardsystem_enable_point_price_type'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_rewardsystem_enable_point_price_type', $woocommerce_rewardpoints_enable_type);
       
         $woocommerce_points_based_on_conversion = $_POST['_sale_price'];
         if($woocommerce_points_based_on_conversion == ''){
             $woocommerce_points_based_on_conversion=$_POST['_regular_price'];
         }
		//this line has to be checked
         $newvalue = $woocommerce_points_based_on_conversion * wc_format_decimal(get_option('rs_redeem_point'));
         $points_based_on_conversion = $newvalue / wc_format_decimal(get_option('rs_redeem_point_value'));
         
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_rewardsystem__points_based_on_conversion', $points_based_on_conversion);
        
    }
    
    public static function rs_show_hide_buy_reward_points() {
        ?>
                <script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery('#product-type').change(function(){
                 if(jQuery(this).val()=='variable'){
                    jQuery('#_rewardsystem_enable_point_price').parent().hide();
                     jQuery('#_rewardsystem_enable_point_price_type').parent().hide();
                 }else{
                     jQuery('#_rewardsystem_enable_point_price').parent().show();
                        jQuery('#_rewardsystem_enable_point_price_type').parent().show();
                 }
                });
                
                   jQuery('#publish').click(function (e) {
                         if(jQuery('._rewardsystem_enable_point_price_type').val() == '2') {
                             if(jQuery('#_rewardsystem__points').val()== '') {
                                 jQuery('#_rewardsystem__points').css({
                            "border": "1px solid red",
                            "background": "#FFCECE"
                        });
                       // jQuery("#_rewardsystem__points").parent().after("<div class='validation' style='color:red;margin-bottom: 20px;'>Please enter points</div>");
          if (jQuery('#_rewardsystem__points').parent().find('.wc_error_tip').size() == 0) {
                            var offset = jQuery('#_rewardsystem__points').position();
                            jQuery('#_rewardsystem__points').after('<div class="wc_error_tip">'  + " Please Enter Points" + '</div>');
                            jQuery('.wc_error_tip')
                                    .css('left', offset.left + jQuery(this).width() - (jQuery(this).width() / 2) - (jQuery('.wc_error_tip').width() / 2))
                                    .css('top', offset.top + jQuery(this).height())
                                  .fadeIn('100000000');
                        }
                         
                           e.preventDefault();
                             }
                             
                         }
                    
                });
                
                if(jQuery('#_rewardsystem_buying_reward_points').val() == 'no') {
                    jQuery('.show_if_buy_reward_points_enable').parent().hide();
                }else {
                    jQuery('.show_if_buy_reward_points_enable').parent().show();                                                      
                }
                                       
                jQuery('#_rewardsystem_buying_reward_points').change(function(){
                    if(jQuery(this).val()=='no') {
                        jQuery('.show_if_buy_reward_points_enable').parent().hide();
                    }else {
                        jQuery('.show_if_buy_reward_points_enable').parent().show();
                    }
                });
                                        
                if(jQuery('#_rewardsystem_enable_point_price').val() == 'no') {
                    jQuery('#_rewardsystem__points').parent().hide();
                    jQuery('#_rewardsystem_point_price_type').parent().hide();
                    jQuery('#_rewardsystem__points_based_on_conversion').parent().hide();
                    jQuery('#_rewardsystem_enable_point_price_type').parent().hide(); 
                    jQuery('#_regular_price').parent().show(); 
                    jQuery('#_sale_price').parent().show(); 
               }else {
                     jQuery('._rewardsystem_enable_point_price_type').parent().show();                                     
                      if(jQuery('._rewardsystem_enable_point_price_type').val() == '2') {
                         jQuery('#_rewardsystem__points_based_on_conversion').parent().hide();
                         jQuery('#_rewardsystem_point_price_type').parent().hide(); 
                         jQuery('#_regular_price').parent().hide(); 
                         jQuery('#_sale_price').parent().hide(); 
                                                     
                      }else{
                             jQuery('#_rewardsystem_point_price_type').parent().show();
                          jQuery('#_regular_price').parent().show(); 
                          jQuery('#_sale_price').parent().show(); 
                      }
                      if(jQuery('#_rewardsystem_point_price_type').val() == '1') {
                          jQuery('#_rewardsystem__points').parent().show(); 
               }else {
                                                  
                          jQuery('._rewardsystem__points_based_on_conversion').parent().show();
                }  
                }                             
                        jQuery('#_rewardsystem_enable_point_price').change(function(){
                    if(jQuery(this).val()=='no') {
                        jQuery('#_rewardsystem__points').parent().hide();
                        jQuery('#_rewardsystem_point_price_type').parent().hide();
                        jQuery('#_rewardsystem__points_based_on_conversion').parent().hide();
                        jQuery('#_rewardsystem_enable_point_price_type').parent().hide(); 
                        jQuery('#_regular_price').parent().show(); 
                        jQuery('#_sale_price').parent().show(); 
                                                
                    }else {
                        jQuery('#_rewardsystem_enable_point_price_type').parent().show();                                                                              
                         if(jQuery('#_rewardsystem_enable_point_price_type').val() == '2') {
                         jQuery('#_rewardsystem__points_based_on_conversion').parent().hide();
                         jQuery('#_rewardsystem_point_price_type').parent().hide(); 
                         jQuery('#_regular_price').parent().hide(); 
                         jQuery('#_sale_price').parent().hide(); 
                         jQuery('#_rewardsystem__points').parent().show(); 
                                                     
                      }else{
                          jQuery('#_regular_price').parent().show(); 
                          jQuery('#_sale_price').parent().show(); 
                            jQuery('#_rewardsystem_point_price_type').parent().show();
                           if(jQuery('#_rewardsystem_point_price_type').val() == '1') {
                            jQuery('#_rewardsystem__points').parent().show(); 
                                              
                         }else {
                            jQuery('#_rewardsystem__points_based_on_conversion').parent().show();
                         }
                      }
                         }
                         });
                                                 
                        if(jQuery('._rewardsystem_enable_point_price_type').val() == '2') {
                           jQuery('#_rewardsystem__points_based_on_conversion').parent().hide();
                           jQuery('#_rewardsystem_point_price_type').parent().hide(); 
                      }
                            jQuery('._rewardsystem_enable_point_price_type').change(function(){
                               if(jQuery('._rewardsystem_enable_point_price_type').val() == '2') {
                             jQuery('#_rewardsystem__points').parent().show(); 
                             jQuery('#_rewardsystem_point_price_type').parent().hide(); 
                             jQuery('#_rewardsystem__points_based_on_conversion').parent().hide();
                             jQuery('#_rewardsystem_point_price_type').parent().hide(); 
                             jQuery('#_regular_price').parent().hide(); 
                        jQuery('#_sale_price').parent().hide();
                    } else {
                        jQuery('#_rewardsystem_point_price_type').parent().show();
                        jQuery('#_regular_price').parent().show();
                        jQuery('#_sale_price').parent().show();
                        if (jQuery('#_rewardsystem_point_price_type').val() == '1') {
                            jQuery('#_rewardsystem__points').parent().show();
                            jQuery('#_rewardsystem__points_based_on_conversion').parent().hide();
                        } else {
                            jQuery('#_rewardsystem__points_based_on_conversion').parent().show();
                            jQuery('#_rewardsystem__points').parent().hide();
                        }
                    }
                });


                if (jQuery('#_rewardsystem_point_price_type').val() == '1') {

                    jQuery('#_rewardsystem__points_based_on_conversion').parent().hide();

                } else {
                    jQuery('#_rewardsystem__points').parent().hide();

                }
                jQuery('#_rewardsystem_point_price_type').change(function () {
                    if (jQuery(this).val() == '1') {
                        jQuery('#_rewardsystem__points').parent().show();
                        jQuery('#_rewardsystem__points_based_on_conversion').parent().hide();

                    } else {
                        jQuery('#_rewardsystem__points_based_on_conversion').parent().show();
                        jQuery('#_rewardsystem__points').parent().hide();
                    }
                });
            });
        </script>
        <?php
    }

    public static function award_points_after_buyied($order_id) {
        $order = new WC_Order($order_id);
        global $wpdb;
        $table_name = $wpdb->prefix . 'rspointexpiry';
        foreach ($order->get_items() as $item) {
            $productobject = get_product($item['product_id']);
            if ($productobject->product_type == 'simple') {
                $checkbuyingpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($item['product_id'], '_rewardsystem_buying_reward_points');
                $getpointstobuy = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($item['product_id'], '_rewardsystem_assign_buying_points');
                $getpointstobuys = $getpointstobuy * $item['qty'];
                $orderuserid = $order->user_id;
                if ($checkbuyingpoints == 'yes') {
                    $redeempoints = RSFunctionToApplyCoupon::update_redeem_reward_points_to_user($order_id,$orderuserid);
                    $pointsredeemed = 0;  
                    $totalearnedpoints = '';
                    $totalredeempoints = '';
                     $noofdays = get_option('rs_point_to_be_expire');
                    
            
            if (($noofdays != '0') && ($noofdays != '')) {
                $date =   time() +($noofdays * 24 * 60 * 60);
            } else {
                $date = '999999999999';
            }
                    $restrictuserpoints = get_option('rs_max_earning_points_for_user');
                    $enabledisablemaxpoints = get_option('rs_enable_disable_max_earning_points_for_user');
                    if($enabledisablemaxpoints == 'yes'){
                        if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                            $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                            
                            if($getoldpoints <= $restrictuserpoints){
                                $totalpointss = $getoldpoints + $getpointstobuys;
                                if($totalpointss <= $restrictuserpoints){                                    
                                    RSPointExpiry::insert_earning_points($order->user_id, $getpointstobuys,$pointsredeemed,$date,'RPBSRP',$order_id,$totalearnedpoints,$totalredeempoints,'');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($getpointstobuys);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $productid = $item['product_id'];
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($order->user_id, $getpointstobuys,$pointsredeemed,$date,'RPBSRP',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0');                       
                                }else{
                                    $insertpoints = $restrictuserpoints - $getoldpoints;
                                    RSPointExpiry::insert_earning_points($order->user_id, $insertpoints,$pointsredeemed,$date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints,'');
                                    $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                    $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                                    $productid = $item['product_id'];
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    RSPointExpiry::record_the_points($order->user_id, $insertpoints,$pointsredeemed,$date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0');                       
                                }                                
                            }else{
                                RSPointExpiry::insert_earning_points($order->user_id,'0','0',$date,'MREPFU',$order_id,'0','0','');
                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                RSPointExpiry::record_the_points($order->user_id, '0','0',$date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                            }
                        }else{
                            RSPointExpiry::insert_earning_points($order->user_id, $getpointstobuys,$pointsredeemed,$date,'RPBSRP',$order_id,$totalearnedpoints,$totalredeempoints,'');
                            $equearnamt = RSPointExpiry::earning_conversion_settings($getpointstobuys);
                            $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                            $productid = $item['product_id'];
                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                            RSPointExpiry::record_the_points($order->user_id, $getpointstobuys,$pointsredeemed,$date,'RPBSRP',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0');                    
                        }
                    }else{
                        RSPointExpiry::insert_earning_points($order->user_id, $getpointstobuys,$pointsredeemed,$date,'RPBSRP',$order_id,$totalearnedpoints,$totalredeempoints,'');
                        $equearnamt = RSPointExpiry::earning_conversion_settings($getpointstobuys);
                        $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);
                        $productid = $item['product_id'];
                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                        RSPointExpiry::record_the_points($order->user_id, $getpointstobuys,$pointsredeemed,$date,'RPBSRP',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0');                    
                    }                    
                    $gettotalearnedpoints = $wpdb->get_results("SELECT SUM((earnedpoints)) as availablepoints FROM $table_name WHERE orderid = $order_id", ARRAY_A);
                    $totalearnedpoints = ($gettotalearnedpoints[0]['availablepoints'] != NULL)?$gettotalearnedpoints[0]['availablepoints']:0;
                    $wpdb->query("UPDATE $table_name SET totalearnedpoints = $totalearnedpoints WHERE orderid = $order_id");
                }
            }
        }
        do_action('fp_reward_point_for_buying_sumo_reward_points');
    }            
}
new RSBuyingRewardPoints();