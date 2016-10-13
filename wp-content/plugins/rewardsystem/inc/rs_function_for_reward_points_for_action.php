<?php

class RSFunctionForRewardPointsForAction {

    public function __construct() {

        add_action('admin_head', array($this, 'rs_validation_for_input_field_in_reward_points_tab'));

        add_filter('woocommerce_rewardsystem_reward_points_for_action_settings', array($this, 'reward_system_add_settings_to_action'));

        add_action('admin_head', array($this, 'rs_show_or_hide_in_reward_points_for_action'));
        
        add_action('publish_post',array($this, 'on_post_publish' ),10,2);
    }

    public static function reward_system_add_settings_to_action($settings) {
        $updated_settings = array();
        $mainvariable = array();
        global $woocommerce;
        foreach ($settings as $section) {
            if (isset($section['id']) && '_rs_reward_point_for_payment_gateway' == $section['id'] &&
                    isset($section['type']) && 'sectionend' == $section['type']) {
                if (function_exists('WC')) {
                    foreach (WC()->payment_gateways->payment_gateways() as $gateway) {
                        $updated_settings[] = array(
                            'name' => __('Reward Points for Using ' . $gateway->title, 'rewardsystem'),
                            'desc' => __('Please Enter Reward Points for ' . $gateway->title, 'rewardsystem'),
                            'tip' => '',
                            'id' => 'rs_reward_payment_gateways_' . $gateway->id,
                            'css' => 'min-width:150px;',
                            'std' => '',
                            'type' => 'text',
                            'newids' => 'rs_reward_payment_gateways_' . $gateway->id,
                            'desc_tip' => true,
                        );
                        
                        ?>

        <script type="text/javascript">
            jQuery(function () {
                jQuery('body').on('blur', '#rs_reward_payment_gateways_<?php echo $gateway->id;?>', function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });
                    return this;
                });

                jQuery('body').on('keyup change', '#rs_reward_payment_gateways_<?php echo $gateway->id;?>', function () {
                    var value = jQuery(this).val();
                    console.log(woocommerce_admin.i18n_mon_decimal_error);
                    var regex = new RegExp("[^\+0-9\%.\\" + woocommerce_admin.mon_decimal_point + "]+", "gi");
                    var newvalue = value.replace(regex, '');

                    if (value !== newvalue) {
                        jQuery(this).val(newvalue);
                        if (jQuery(this).parent().find('.wc_error_tip').size() == 0) {
                            var offset = jQuery(this).position();
                            jQuery(this).after('<div class="wc_error_tip">' + woocommerce_admin.i18n_mon_decimal_error + " Negative Values are not allowed" + '</div>');
                            jQuery('.wc_error_tip')
                                    .css('left', offset.left + jQuery(this).width() - (jQuery(this).width() / 2) - (jQuery('.wc_error_tip').width() / 2))
                                    .css('top', offset.top + jQuery(this).height())
                                    .fadeIn('100');
                        }
                    }



                    return this;
                });



                jQuery("body").click(function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });

                });
            });
        </script>
        <?php
                    }
                } else {
                    if (class_exists('WC_Payment_Gateways')) {
                        $paymentgateway = new WC_Payment_Gateways();
                        foreach ($paymentgateway->payment_gateways()as $gateway) {
                            $updated_settings[] = array(
                                'name' => __('Reward Points for Using ' . $gateway->title, 'rewardsystem'),
                                'desc' => __('Please Enter Reward Points for ' . $gateway->title, 'rewardsystem'),
                                'tip' => '',
                                'id' => 'rs_reward_payment_gateways_' . $gateway->id,
                                'css' => 'min-width:150px;',
                                'std' => '',
                                'type' => 'text',
                                'newids' => 'rs_reward_payment_gateways_' . $gateway->id,
                                'desc_tip' => true,
                            );
                            ?>

        <script type="text/javascript">
            jQuery(function () {
                jQuery('body').on('blur', '#rs_reward_payment_gateways_<?php echo $gateway->id;?>', function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });

                    return this;
                });

                jQuery('body').on('keyup change', '#rs_reward_payment_gateways_<?php echo $gateway->id;?>', function () {
                    var value = jQuery(this).val();
                    console.log(woocommerce_admin.i18n_mon_decimal_error);
                    var regex = new RegExp("[^\+0-9\%.\\" + woocommerce_admin.mon_decimal_point + "]+", "gi");
                    var newvalue = value.replace(regex, '');

                    if (value !== newvalue) {
                        jQuery(this).val(newvalue);
                        if (jQuery(this).parent().find('.wc_error_tip').size() == 0) {
                            var offset = jQuery(this).position();
                            jQuery(this).after('<div class="wc_error_tip">' + woocommerce_admin.i18n_mon_decimal_error + " Negative Values are not allowed" + '</div>');
                            jQuery('.wc_error_tip')
                                    .css('left', offset.left + jQuery(this).width() - (jQuery(this).width() / 2) - (jQuery('.wc_error_tip').width() / 2))
                                    .css('top', offset.top + jQuery(this).height())
                                    .fadeIn('100');
                        }
                    }
                    return this;
                });



                jQuery("body").click(function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });

                });
            });
        </script>
        <?php
                        }
                    }
                }
                $updated_settings[] = array(
                    'type' => 'sectionend', 'id' => '_rs_reward_system_payment_gateway',
                );
            }


            $newsettings = array('type' => 'sectionend', 'id' => '_rs_reward_system_pg_end');
            $updated_settings[] = $section;
            
             
        }

        return $updated_settings;
    }

    public static function rs_validation_for_input_field_in_reward_points_tab() {
        ?>

        <script type="text/javascript">
            jQuery(function () {
                jQuery('body').on('blur', '#rs_reward_signup[type=text],\n\
                                           #rs_reward_product_review[type=text],\n\
                                           #rs_referral_reward_signup[type=text],\n\
                                           #rs_reward_points_for_login[type=text]', function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });
                    return this;
                });

                jQuery('body').on('keyup change', '#rs_reward_signup[type=text],\n\
                                           #rs_reward_product_review[type=text],\n\
                                           #rs_referral_reward_signup[type=text],\n\
                                           #rs_reward_points_for_login[type=text]', function () {
                    var value = jQuery(this).val();
                    console.log(woocommerce_admin.i18n_mon_decimal_error);
                    var regex = new RegExp("[^\+0-9\%.\\" + woocommerce_admin.mon_decimal_point + "]+", "gi");
                    var newvalue = value.replace(regex, '');

                    if (value !== newvalue) {
                        jQuery(this).val(newvalue);
                        if (jQuery(this).parent().find('.wc_error_tip').size() == 0) {
                            var offset = jQuery(this).position();
                            jQuery(this).after('<div class="wc_error_tip">' + woocommerce_admin.i18n_mon_decimal_error + " Negative Values are not allowed" + '</div>');
                            jQuery('.wc_error_tip')
                                    .css('left', offset.left + jQuery(this).width() - (jQuery(this).width() / 2) - (jQuery('.wc_error_tip').width() / 2))
                                    .css('top', offset.top + jQuery(this).height())
                                    .fadeIn('100');
                        }
                    }



                    return this;
                });



                jQuery("body").click(function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });

                });
            });
        </script>
        <?php

    }
    
    
    public static function on_post_publish( $ID, $post ) {
    // A function to perform actions when a post is published.  
        
        
        $user_ID = get_current_user_id();
        $post_id = $ID;
        //$title = $post_id->post_title;
       $earned_points = get_option('rs_reward_post');
        $noofdays = get_option('rs_point_to_be_expire');
                      
            if (($noofdays != '0') && ($noofdays != '')) {
                $date =   time() +($noofdays * 24 * 60 * 60);
            } else {
                $date = '999999999999';
            }
        $enableoptforpost = get_option('rs_reward_for_Creating_Post');
        $meta_value = get_post_meta($post_id,'rewardpointsforblogpost',true);
        
        if($enableoptforpost=='yes'){
         $retrived_value = get_option('fp_rs_list_blog_posts');
        if(!in_array($ID, $retrived_value)){
   
            if($earned_points!=""){
           
        if($meta_value!="yes"){
             
             RSPointExpiry::insert_earning_points($user_ID, $earned_points,'',$date,'RPFP','','','','');
            
             $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($user_ID);
             
            RSPointExpiry::record_the_points($user_ID, $earned_points,'',$date,'RPFP','','','',$post_id,'','','',$totalpoints,'','');
             
              update_post_meta($post_id,'rewardpointsforblogpost','yes');
         }
            }
            $previous_value = get_option('fp_rs_list_blog_posts');
            if($previous_value != ""){
                $current_id = $ID;
                $combined_id = array_merge($previous_value,$current_id);
                update_option('fp_rs_list_blog_posts',$ID);   
            } else {
                update_option('fp_rs_list_blog_posts',$ID); 
            }
            
           
        } 
        }
        
        $current_id[] = $ID;
        
        update_option('fp_rs_list_blog_posts',$current_id);
          
    
        }


    public static function rs_show_or_hide_in_reward_points_for_action() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {               

                //To Show or Hide Reward Points for login
                if (jQuery('#rs_enable_reward_points_for_login').is(":checked") == false) {
                    jQuery('#rs_reward_points_for_login').parent().parent().hide();
                } else {
                    jQuery('#rs_reward_points_for_login').parent().parent().show();
                }

                jQuery('#rs_enable_reward_points_for_login').change(function () {
                    if (jQuery('#rs_enable_reward_points_for_login').is(":checked") == false) {
                        jQuery('#rs_reward_points_for_login').parent().parent().hide();
                    } else {
                        jQuery('#rs_reward_points_for_login').parent().parent().show();
                    }
                });

            });
        </script>
        <?php

    }

}

new RSFunctionForRewardPointsForAction();
