<?php

class RSSimpleProduct {

    public function __construct() {

        add_action('woocommerce_product_options_general_product_data', array($this, 'rs_admin_option_for_simple_product'), 1);

        add_action('woocommerce_product_options_general_product_data', array($this, 'rs_common_admin_options_for_social_input_field'));

        add_action('admin_head', array($this, 'rs_show_or_hide_percent_and_point_field'));

        add_action('woocommerce_process_product_meta', array($this, 'save_reward_points_admin_fields_to_product_meta'));

        add_action('woocommerce_process_product_meta', array($this, 'save_social_reward_points_admin_fields_to_product_meta'));

        add_action('admin_head', array($this, 'rs_validation_for_input_field'));

        add_action('admin_head', array($this, 'rs_validation_for_input_field_for_social'));

        add_filter('woocommerce_get_price_html', array($this, 'display_reward_point_msg_for_product'), 99, 2);

        add_shortcode('rewardpoints', array($this, 'add_shortcode_function_for_rewardpoints_of_simple'));

        add_action('woocommerce_before_single_product', array($this, 'display_purchase_message_for_simple_in_single_product_page'));

        add_shortcode('equalamount', array($this, 'get_redeem_conversion_value'));
        
        add_filter('woocommerce_variation_sale_price_html', array($this, 'display_point_price_in_variable_product'), 99, 2);

        add_filter('woocommerce_variation_price_html', array($this, 'display_point_price_in_variable_product'), 99, 2);
    }

    public static function display_point_price_in_variable_product($price, $object) {
        global $post;

        if (get_option('rs_enable_disable_point_priceing') == '1') {


            if (get_option('rs_enable_disable_point_priceing') == '1') {
                $enabledpoints = RSFunctionForCart::calculate_point_price_for_products($object->variation_id);
                $point_price = $enabledpoints[$object->variation_id];
                $point_price_info = get_option('rs_label_for_point_value');
                $typeofprice = RSFunctionForCart ::check_display_price_type($object->variation_id);
                if ($typeofprice == '2') {
                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                    $point_price = round($point_price, $roundofftype);
                    $replace = str_replace("/", "", $point_price_info);
                    return $replace . $point_price;
                } else {
                    if ($point_price != '') {
                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                        $point_price = round($point_price, $roundofftype);
                        return $price . '<span class="point_price_label">' . $point_price_info . $point_price;
                    } else {
                        return $price;
                    }
                }
            } else {
                return $price;
            }
        } else {
            return $price;
        }
    }

    public static function rs_admin_option_for_simple_product() {
        global $post;
        if (is_admin()) {
            ?>
            <div class="options_group show_if_simple show_if_subscription show_if_booking show_if_external">
                <?php
                woocommerce_wp_select(array(
                    'id' => '_rewardsystemcheckboxvalue',
                    'class' => 'rewardsystemcheckboxvalue',
                    'placeholder' => '',
                    'desc_tip' => 'true',
                    'description' => __('Enable will Turn On Reward Points for Product Purchase and Category/Product Settings will be considered if it is available. '
                            . 'Disable will Turn Off Reward Points for Product Purchase and Category/Product Settings will be considered if it is available. ', 'rewardsystem'),
                    'label' => __('Enable SUMO Reward Points for Product Purchase', 'rewardsystem'),
                    'options' => array(
                        'no' => __('Disable', 'rewardsystem'),
                        'yes' => __('Enable', 'rewardsystem'),
                    )
                        )
                );

                woocommerce_wp_select(array(
                    'id' => '_rewardsystem_options',
                    'class' => 'rewardsystem_options show_if_enable',
                    'label' => __('Reward Type', 'rewardsystem'),
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    )
                        )
                );
                woocommerce_wp_text_input(
                        array(
                            'id' => '_rewardsystempoints',
                            'class' => 'show_if_enable',
                            'name' => '_rewardsystempoints',
                            'placeholder' => '',
                            'desc_tip' => 'true',
                            'description' => __('When left empty, Category and Global Settings will be considered in the same order and Current Settings (Product Settings) will be ignored. '
                                    . 'When value greater than or equal to 0 is entered then Current Settings (Product Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                            'label' => __('Reward Points', 'rewardsystem'),
                        )
                );
                woocommerce_wp_text_input(
                        array(
                            'id' => '_rewardsystempercent',
                            'class' => 'show_if_enable',
                            'name' => '_rewardsystempercent',
                            'placeholder' => '',
                            'desc_tip' => 'true',
                            'description' => __('When left empty, Category and Global Settings will be considered in the same order and Current Settings (Product Settings) will be ignored. '
                                    . 'When value greater than or equal to 0 is entered then Current Settings (Product Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                            'label' => __('Reward Points in Percent %', 'rewardsystem')
                        )
                );
                woocommerce_wp_select(array(
                    'id' => '_referral_rewardsystem_options',
                    'class' => 'referral_rewardsystem_options show_if_enable',
                    'label' => __('Referral Reward Type', 'rewardsystem'),
                    'options' => array(
                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                    )
                        )
                );
                woocommerce_wp_text_input(
                        array(
                            'id' => '_referralrewardsystempoints',
                            'class' => 'show_if_enable',
                            'name' => '_referralrewardsystempoints',
                            'placeholder' => '',
                            'desc_tip' => 'true',
                            'description' => __('When left empty, Category and Global Settings will be considered in the same order and Current Settings (Product Settings) will be ignored. '
                                    . 'When value greater than or equal to 0 is entered then Current Settings (Product Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                            'label' => __('Referral Reward Points', 'rewardsystem')
                        )
                );
                woocommerce_wp_text_input(
                        array(
                            'id' => '_referralrewardsystempercent',
                            'class' => 'show_if_enable',
                            'name' => '_referralrewardsystempercent',
                            'placeholder' => '',
                            'desc_tip' => 'true',
                            'description' => __('When left empty, Category and Global Settings will be considered in the same order and Current Settings (Product Settings) will be ignored. '
                                    . 'When value greater than or equal to 0 is entered then Current Settings (Product Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                            'label' => __('Referral Reward Points in Percent %', 'rewardsystem')
                        )
                );
                ?>
            </div>
            <?php
        }
    }

    public static function rs_common_admin_options_for_social_input_field() {
        if (is_admin()) {
            woocommerce_wp_select(array(
                'id' => '_socialrewardsystemcheckboxvalue',
                'class' => 'socialrewardsystemcheckboxvalue',
                'placeholder' => '',
                'desc_tip' => 'true',
                'description' => __('Enable will Turn On Reward Points for Product Purchase and Category/Global Settings will be considered when applicable. '
                        . 'Disable will Turn Off Reward Points for Product Purchase and Category/Global Settings will not be considered. ', 'rewardsystem'),
                'label' => __('Enable SUMO Reward Points for Social Promotion', 'rewardsystem'),
                'options' => array(
                    'no' => __('Disable', 'rewardsystem'),
                    'yes' => __('Enable', 'rewardsystem'),
                )
                    )
            );

            woocommerce_wp_select(
                    array(
                        'id' => '_social_rewardsystem_options_facebook',
                        'class' => 'social_rewardsystem_options_facebook show_if_social_enable',
                        'label' => __('Facebook Like Reward Type', 'rewardsystem'),
                        'options' => array(
                            '1' => __('By Fixed Reward Points', 'rewardsystem'),
                            '2' => __('By Percentage of Product Price', 'rewardsystem')
                        )
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => '_socialrewardsystempoints_facebook',
                        'class' => 'show_if_social_enable',
                        'name' => '_socialrewardsystempoints_facebook',
                        'placeholder' => '',
                        'desc_tip' => 'true',
                        'description' => __('When left empty, Category and Global Settings will be considered in the same order and Current Settings (Product Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Product Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                        'label' => __('Facebook Like Reward Points', 'rewardsystem')
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => '_socialrewardsystempercent_facebook',
                        'class' => 'show_if_social_enable',
                        'name' => '_socialrewardsystempercent_facebook',
                        'placeholder' => '',
                        'desc_tip' => 'true',
                        'description' => __('When left empty, Category and Global Settings will be considered in the same order and Current Settings (Product Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Product Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                        'label' => __('Facebook Like Reward Points in Percent %', 'rewardsystem')
                    )
            );
            woocommerce_wp_select(
                    array(
                        'id' => '_social_rewardsystem_options_facebook_share',
                        'class' => ' _social_rewardsystem_options_facebook_share show_if_social_enable',
                        'label' => __('Facebook Share Reward Type', 'rewardsystem'),
                        'options' => array(
                            '1' => __('By Fixed Reward Points', 'rewardsystem'),
                            '2' => __('By Percentage of Product Price', 'rewardsystem')
                        )
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => '_socialrewardsystempoints_facebook_share',
                        'class' => 'show_if_social_enable',
                        'name' => '_socialrewardsystempoints_facebook_share',
                        'placeholder' => '',
                        'desc_tip' => 'true',
                        'description' => __('When left empty, Category and Global Settings will be considered in the same order and Current Settings (Product Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Product Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                        'label' => __('Facebook Share Reward Points', 'rewardsystem')
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => '_socialrewardsystempercent_facebook_share',
                        'class' => 'show_if_social_enable',
                        'name' => '_socialrewardsystempercent_facebook_share',
                        'placeholder' => '',
                        'desc_tip' => 'true',
                        'description' => __('When left empty, Category and Global Settings will be considered in the same order and Current Settings (Product Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Product Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                        'label' => __('Facebook Share Reward Points in Percent %', 'rewardsystem')
                    )
            );
            woocommerce_wp_select(
                    array(
                        'id' => '_social_rewardsystem_options_twitter',
                        'class' => 'social_rewardsystem_options_twitter show_if_social_enable',
                        'label' => __('Twitter Tweet Reward Type', 'rewardsystem'),
                        'options' => array(
                            '1' => __('By Fixed Reward Points', 'rewardsystem'),
                            '2' => __('By Percentage of Product Price', 'rewardsystem')
                        )
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => '_socialrewardsystempoints_twitter',
                        'class' => 'show_if_social_enable',
                        'name' => '_socialrewardsystempoints_twitter',
                        'placeholder' => '',
                        'placeholder' => '',
                        'desc_tip' => 'true',
                        'description' => __('When left empty, Category and Global Settings will be considered in the same order and Current Settings (Product Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Product Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                        'label' => __('Twitter Tweet Reward Points', 'rewardsystem')
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => '_socialrewardsystempercent_twitter',
                        'class' => 'show_if_social_enable',
                        'name' => '_socialrewardsystempercent_twitter',
                        'placeholder' => '',
                        'desc_tip' => 'true',
                        'description' => __('When left empty, Category and Global Settings will be considered in the same order and Current Settings (Product Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Product Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                        'label' => __('Twitter Tweet Reward Percent %', 'rewardsystem')
                    )
            );
            woocommerce_wp_select(
                    array(
                        'id' => '_social_rewardsystem_options_google',
                        'class' => 'social_rewardsystem_options_google show_if_social_enable',
                        'label' => __('Google+1 Reward Type', 'rewardsystem'),
                        'options' => array(
                            '1' => __('By Fixed Reward Points', 'rewardsystem'),
                            '2' => __('By Percentage of Product Price', 'rewardsystem')
                        )
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => '_socialrewardsystempoints_google',
                        'class' => 'show_if_social_enable',
                        'name' => '_socialrewardsystempoints_google',
                        'placeholder' => '',
                        'desc_tip' => 'true',
                        'description' => __('When left empty, Category and Global Settings will be considered in the same order and Current Settings (Product Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Product Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                        'label' => __('Google+1 Reward Points', 'rewardsystem')
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => '_socialrewardsystempercent_google',
                        'class' => 'show_if_social_enable',
                        'name' => '_socialrewardsystempercent_google',
                        'placeholder' => '',
                        'desc_tip' => 'true',
                        'description' => __('When left empty, Category and Global Settings will be considered in the same order and Current Settings (Product Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Product Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                        'label' => __('Google+1 Reward Percent %', 'rewardsystem')
                    )
            );
            woocommerce_wp_select(
                    array(
                        'id' => '_social_rewardsystem_options_vk',
                        'class' => 'social_rewardsystem_options_vk show_if_social_enable',
                        'label' => __('VK.com Like Reward Type', 'rewardsystem'),
                        'options' => array(
                            '1' => __('By Fixed Reward Points', 'rewardsystem'),
                            '2' => __('By Percentage of Product Price', 'rewardsystem')
                        )
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => '_socialrewardsystempoints_vk',
                        'class' => 'show_if_social_enable',
                        'name' => '_socialrewardsystempoints_vk',
                        'placeholder' => '',
                        'desc_tip' => 'true',
                        'description' => __('When left empty, Category and Global Settings will be considered in the same order and Current Settings (Product Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Product Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                        'label' => __('VK.com Like Reward Points ', 'rewardsystem')
                    )
            );
            woocommerce_wp_text_input(
                    array(
                        'id' => '_socialrewardsystempercent_vk',
                        'class' => 'show_if_social_enable',
                        'name' => '_socialrewardsystempercent_vk',
                        'placeholder' => '',
                        'desc_tip' => 'true',
                        'description' => __('When left empty, Category and Global Settings will be considered in the same order and Current Settings (Product Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Product Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                        'label' => __('VK.com Like Reward Percent %', 'rewardsystem')
                    )
            );
        }
    }

    public static function rs_show_or_hide_percent_and_point_field() {
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('#_rewardsystem__points_based_on_conversion').attr('readonly', 'true');
                if (jQuery('#_rewardsystemcheckboxvalue').val() == 'no') {
                    jQuery('.show_if_enable').parent().hide();
                } else {
                    jQuery('.show_if_enable').parent().show();
                    if (jQuery('.rewardsystem_options').val() === '') {
                        jQuery('._rewardsystempercent_field').css('display', 'none');
                        jQuery('._rewardsystempoints_field').css('display', 'none');
                    } else if (jQuery('.rewardsystem_options').val() === '1') {
                        jQuery('._rewardsystempercent_field').css('display', 'none');
                        jQuery('._rewardsystempoints_field').css('display', 'block');
                    } else {
                        jQuery('._rewardsystempercent_field').css('display', 'block');
                        jQuery('._rewardsystempoints_field').css('display', 'none');
                    }

                    jQuery('.rewardsystem_options').change(function () {
                        if (jQuery(this).val() === '') {
                            jQuery('._rewardsystempercent_field').css('display', 'none');
                            jQuery('._rewardsystempoints_field').css('display', 'none');
                        } else if (jQuery(this).val() === '1') {
                            jQuery('._rewardsystempercent_field').css('display', 'none');
                            jQuery('._rewardsystempoints_field').css('display', 'block');
                        } else {
                            jQuery('._rewardsystempercent_field').css('display', 'block');
                            jQuery('._rewardsystempoints_field').css('display', 'none');
                        }

                    });

                    if (jQuery('.referral_rewardsystem_options').val() === '') {
                        jQuery('._referralrewardsystempercent_field').css('display', 'none');
                        jQuery('._referralrewardsystempoints_field').css('display', 'none');
                    } else if (jQuery('.referral_rewardsystem_options').val() === '1') {
                        jQuery('._referralrewardsystempercent_field').css('display', 'none');
                        jQuery('._referralrewardsystempoints_field').css('display', 'block');
                    } else {
                        jQuery('._referralrewardsystempercent_field').css('display', 'block');
                        jQuery('._referralrewardsystempoints_field').css('display', 'none');
                    }


                    jQuery('.referral_rewardsystem_options').change(function () {
                        if (jQuery(this).val() === '') {
                            jQuery('._referralrewardsystempercent_field').css('display', 'none');
                            jQuery('._referralrewardsystempoints_field').css('display', 'none');
                        } else if (jQuery(this).val() === '1') {
                            jQuery('._referralrewardsystempercent_field').css('display', 'none');
                            jQuery('._referralrewardsystempoints_field').css('display', 'block');
                        } else {
                            jQuery('._referralrewardsystempercent_field').css('display', 'block');
                            jQuery('._referralrewardsystempoints_field').css('display', 'none');
                        }


                    });
                }

                jQuery('#_rewardsystemcheckboxvalue').change(function () {
                    if (jQuery(this).val() == 'no') {
                        jQuery('.show_if_enable').parent().hide();
                    } else {

                        jQuery('.show_if_enable').parent().show();
                        if (jQuery('.rewardsystem_options').val() === '') {
                            jQuery('._rewardsystempercent_field').css('display', 'none');
                            jQuery('._rewardsystempoints_field').css('display', 'none');
                        } else if (jQuery('.rewardsystem_options').val() === '1') {
                            jQuery('._rewardsystempercent_field').css('display', 'none');
                            jQuery('._rewardsystempoints_field').css('display', 'block');
                        } else {
                            jQuery('._rewardsystempercent_field').css('display', 'block');
                            jQuery('._rewardsystempoints_field').css('display', 'none');
                        }


                        jQuery('.rewardsystem_options').change(function () {
                            if (jQuery(this).val() === '') {
                                jQuery('._rewardsystempercent_field').css('display', 'none');
                                jQuery('._rewardsystempoints_field').css('display', 'none');
                            } else if (jQuery(this).val() === '1') {
                                jQuery('._rewardsystempercent_field').css('display', 'none');
                                jQuery('._rewardsystempoints_field').css('display', 'block');
                            } else {
                                jQuery('._rewardsystempercent_field').css('display', 'block');
                                jQuery('._rewardsystempoints_field').css('display', 'none');
                            }

                        });
                        if (jQuery('.referral_rewardsystem_options').val() === '') {
                            jQuery('._referralrewardsystempercent_field').css('display', 'none');
                            jQuery('._referralrewardsystempoints_field').css('display', 'none');
                        } else if (jQuery('.referral_rewardsystem_options').val() === '1') {
                            jQuery('._referralrewardsystempercent_field').css('display', 'none');
                            jQuery('._referralrewardsystempoints_field').css('display', 'block');
                        } else {
                            jQuery('._referralrewardsystempercent_field').css('display', 'block');
                            jQuery('._referralrewardsystempoints_field').css('display', 'none');
                        }

                        jQuery('.referral_rewardsystem_options').change(function () {
                            if (jQuery(this).val() === '') {
                                jQuery('._referralrewardsystempercent_field').css('display', 'none');
                                jQuery('._referralrewardsystempoints_field').css('display', 'none');
                            } else if (jQuery(this).val() === '1') {
                                jQuery('._referralrewardsystempercent_field').css('display', 'none');
                                jQuery('._referralrewardsystempoints_field').css('display', 'block');
                            } else {
                                jQuery('._referralrewardsystempercent_field').css('display', 'block');
                                jQuery('._referralrewardsystempoints_field').css('display', 'none');
                            }

                        });

                    }
                });

                // Social Reward Points Show/Hide


                if (jQuery('#_socialrewardsystemcheckboxvalue').val() === 'no') {
                    jQuery('.show_if_social_enable').parent().hide();
                } else {
                    jQuery('.show_if_social_enable').parent().show();

                    /* Social Reward System for facebook */
                    if (jQuery('.social_rewardsystem_options_facebook').val() === '') {
                        jQuery('._socialrewardsystempoints_facebook_field').css('display', 'none');
                        jQuery('._socialrewardsystempercent_facebook_field').css('display', 'none');
                    } else if (jQuery('.social_rewardsystem_options_facebook').val() === '1') {
                        jQuery('._socialrewardsystempercent_facebook_field').css('display', 'none');
                        jQuery('._socialrewardsystempoints_facebook_field').css('display', 'block');
                    } else {
                        jQuery('._socialrewardsystempercent_facebook_field').css('display', 'block');
                        jQuery('._socialrewardsystempoints_facebook_field').css('display', 'none');
                    }

                    /* On Change Event Triggering for Social Rewards Facebook */
                    jQuery('.social_rewardsystem_options_facebook').change(function () {
                        if (jQuery(this).val() === '') {
                            jQuery('._socialrewardsystempoints_facebook_field').css('display', 'none');
                            jQuery('._socialrewardsystempercent_facebook_field').css('display', 'none');
                        } else if (jQuery(this).val() === '1') {
                            jQuery('._socialrewardsystempercent_facebook_field').css('display', 'none');
                            jQuery('._socialrewardsystempoints_facebook_field').css('display', 'block');
                        } else {
                            jQuery('._socialrewardsystempercent_facebook_field').css('display', 'block');
                            jQuery('._socialrewardsystempoints_facebook_field').css('display', 'none');
                        }
                    });


                    /* Social Reward System for facebook */
                    if (jQuery('._social_rewardsystem_options_facebook_share').val() === '') {
                        jQuery('._socialrewardsystempoints_facebook_share_field').css('display', 'none');
                        jQuery('._socialrewardsystempercent_facebook_share_field').css('display', 'none');
                    } else if (jQuery('._social_rewardsystem_options_facebook_share').val() === '1') {
                        jQuery('._socialrewardsystempercent_facebook_share_field').css('display', 'none');
                        jQuery('._socialrewardsystempoints_facebook_share_field').css('display', 'block');
                    } else {
                        jQuery('._socialrewardsystempercent_facebook_share_field').css('display', 'block');
                        jQuery('._socialrewardsystempoints_facebook_share_field').css('display', 'none');
                    }

                    /* On Change Event Triggering for Social Rewards Facebook */
                    jQuery('._social_rewardsystem_options_facebook_share').change(function () {
                        if (jQuery(this).val() === '') {
                            jQuery('._socialrewardsystempoints_facebook_share_field').css('display', 'none');
                            jQuery('._socialrewardsystempercent_facebook_share_field').css('display', 'none');
                        } else if (jQuery(this).val() === '1') {
                            jQuery('._socialrewardsystempercent_facebook_share_field').css('display', 'none');
                            jQuery('._socialrewardsystempoints_facebook_share_field').css('display', 'block');
                        } else {
                            jQuery('._socialrewardsystempercent_facebook_share_field').css('display', 'block');
                            jQuery('._socialrewardsystempoints_facebook_share_field').css('display', 'none');
                        }
                    });


                    /* Social Reward System for twitter */
                    if (jQuery('.social_rewardsystem_options_twitter').val() === '') {
                        jQuery('._socialrewardsystempoints_twitter_field').css('display', 'none');
                        jQuery('._socialrewardsystempercent_twitter_field').css('display', 'none');
                    } else if (jQuery('.social_rewardsystem_options_twitter').val() === '1') {
                        jQuery('._socialrewardsystempercent_twitter_field').css('display', 'none');
                        jQuery('._socialrewardsystempoints_twitter_field').css('display', 'block');
                    } else {
                        jQuery('._socialrewardsystempercent_twitter_field').css('display', 'block');
                        jQuery('._socialrewardsystempoints_twitter_field').css('display', 'none');
                    }

                    /* On Change Event Triggering for Social Rewards twitter */
                    jQuery('.social_rewardsystem_options_twitter').change(function () {
                        if (jQuery(this).val() === '') {
                            jQuery('._socialrewardsystempoints_twitter_field').css('display', 'none');
                            jQuery('._socialrewardsystempercent_twitter_field').css('display', 'none');
                        } else if (jQuery(this).val() === '1') {
                            jQuery('._socialrewardsystempercent_twitter_field').css('display', 'none');
                            jQuery('._socialrewardsystempoints_twitter_field').css('display', 'block');
                        } else {
                            jQuery('._socialrewardsystempercent_twitter_field').css('display', 'block');
                            jQuery('._socialrewardsystempoints_twitter_field').css('display', 'none');
                        }
                    });
                    /* Social Reward System for Google+ */
                    if (jQuery('.social_rewardsystem_options_google').val() === '') {
                        jQuery('._socialrewardsystempoints_google_field').css('display', 'none');
                        jQuery('._socialrewardsystempercent_google_field').css('display', 'none');
                    } else if (jQuery('.social_rewardsystem_options_google').val() === '1') {
                        jQuery('._socialrewardsystempercent_google_field').css('display', 'none');
                        jQuery('._socialrewardsystempoints_google_field').css('display', 'block');
                    } else {
                        jQuery('._socialrewardsystempercent_google_field').css('display', 'block');
                        jQuery('._socialrewardsystempoints_google_field').css('display', 'none');
                    }

                    /* On Change Event Triggering for Social Rewards Google+ */
                    jQuery('.social_rewardsystem_options_google').change(function () {
                        if (jQuery(this).val() === '') {
                            jQuery('._socialrewardsystempoints_google_field').css('display', 'none');
                            jQuery('._socialrewardsystempercent_google_field').css('display', 'none');
                        } else if (jQuery(this).val() === '1') {
                            jQuery('._socialrewardsystempercent_google_field').css('display', 'none');
                            jQuery('._socialrewardsystempoints_google_field').css('display', 'block');
                        } else {
                            jQuery('._socialrewardsystempercent_google_field').css('display', 'block');
                            jQuery('._socialrewardsystempoints_google_field').css('display', 'none');
                        }
                    });

                    /* Social Reward System for VK */
                    if (jQuery('.social_rewardsystem_options_vk').val() === '') {
                        jQuery('._socialrewardsystempoints_vk_field').css('display', 'none');
                        jQuery('._socialrewardsystempercent_vk_field').css('display', 'none');
                    } else if (jQuery('.social_rewardsystem_options_vk').val() === '1') {
                        jQuery('._socialrewardsystempercent_vk_field').css('display', 'none');
                        jQuery('._socialrewardsystempoints_vk_field').css('display', 'block');
                    } else {
                        jQuery('._socialrewardsystempercent_vk_field').css('display', 'block');
                        jQuery('._socialrewardsystempoints_vk_field').css('display', 'none');
                    }

                    /* On Change Event Triggering for Social Rewards VK */
                    jQuery('.social_rewardsystem_options_vk').change(function () {
                        if (jQuery(this).val() === '') {
                            jQuery('._socialrewardsystempoints_vk_field').css('display', 'none');
                            jQuery('._socialrewardsystempercent_vk_field').css('display', 'none');
                        } else if (jQuery(this).val() === '1') {
                            jQuery('._socialrewardsystempercent_vk_field').css('display', 'none');
                            jQuery('._socialrewardsystempoints_vk_field').css('display', 'block');
                        } else {
                            jQuery('._socialrewardsystempercent_vk_field').css('display', 'block');
                            jQuery('._socialrewardsystempoints_vk_field').css('display', 'none');
                        }
                    });
                }
                jQuery('#_socialrewardsystemcheckboxvalue').change(function () {
                    if (jQuery(this).val() == 'no') {
                        jQuery('.show_if_social_enable').parent().hide();
                    } else {
                        jQuery('.show_if_social_enable').parent().show();

                        /* Social Reward System for facebook */
                        if (jQuery('.social_rewardsystem_options_facebook').val() === '') {
                            jQuery('._socialrewardsystempoints_facebook_field').css('display', 'none');
                            jQuery('._socialrewardsystempercent_facebook_field').css('display', 'none');
                        } else if (jQuery('.social_rewardsystem_options_facebook').val() === '1') {
                            jQuery('._socialrewardsystempercent_facebook_field').css('display', 'none');
                            jQuery('._socialrewardsystempoints_facebook_field').css('display', 'block');
                        } else {
                            jQuery('._socialrewardsystempercent_facebook_field').css('display', 'block');
                            jQuery('._socialrewardsystempoints_facebook_field').css('display', 'none');
                        }

                        /* On Change Event Triggering for Social Rewards Facebook */
                        jQuery('.social_rewardsystem_options_facebook').change(function () {
                            if (jQuery(this).val() === '') {
                                jQuery('._socialrewardsystempoints_facebook_field').css('display', 'none');
                                jQuery('._socialrewardsystempercent_facebook_field').css('display', 'none');
                            } else if (jQuery(this).val() === '1') {
                                jQuery('._socialrewardsystempercent_facebook_field').css('display', 'none');
                                jQuery('._socialrewardsystempoints_facebook_field').css('display', 'block');
                            } else {
                                jQuery('._socialrewardsystempercent_facebook_field').css('display', 'block');
                                jQuery('._socialrewardsystempoints_facebook_field').css('display', 'none');
                            }
                        });

                        if (jQuery('._social_rewardsystem_options_facebook_share').val() === '') {
                            alert(jQuery('._social_rewardsystem_options_facebook_share').val());
                            jQuery('._socialrewardsystempoints_facebook_share_field').css('display', 'none');
                            jQuery('._socialrewardsystempercent_facebook_share_field').css('display', 'none');
                        } else if (jQuery('._social_rewardsystem_options_facebook_share').val() === '1') {
                            jQuery('._socialrewardsystempercent_facebook_share_field').css('display', 'none');
                            jQuery('._socialrewardsystempoints_facebook_share_field').css('display', 'block');
                        } else {
                            jQuery('._socialrewardsystempercent_facebook_share_field').css('display', 'block');
                            jQuery('._socialrewardsystempoints_facebook_share_field').css('display', 'none');
                        }

                        /* On Change Event Triggering for Social Rewards Facebook */
                        jQuery('._social_rewardsystem_options_facebook_share').change(function () {
                            if (jQuery(this).val() === '') {
                                jQuery('._socialrewardsystempoints_facebook_share_field').css('display', 'none');
                                jQuery('._socialrewardsystempercent_facebook_share_field').css('display', 'none');
                            } else if (jQuery(this).val() === '1') {
                                jQuery('._socialrewardsystempercent_facebook_share_field').css('display', 'none');
                                jQuery('._socialrewardsystempoints_facebook_share_field').css('display', 'block');
                            } else {
                                jQuery('._socialrewardsystempercent_facebook_share_field').css('display', 'block');
                                jQuery('._socialrewardsystempoints_facebook_share_field').css('display', 'none');
                            }
                        });

                        /* Social Reward System for twitter */
                        if (jQuery('.social_rewardsystem_options_twitter').val() === '') {
                            jQuery('._socialrewardsystempoints_twitter_field').css('display', 'none');
                            jQuery('._socialrewardsystempercent_twitter_field').css('display', 'none');
                        } else if (jQuery('.social_rewardsystem_options_twitter').val() === '1') {
                            jQuery('._socialrewardsystempercent_twitter_field').css('display', 'none');
                            jQuery('._socialrewardsystempoints_twitter_field').css('display', 'block');
                        } else {
                            jQuery('._socialrewardsystempercent_twitter_field').css('display', 'block');
                            jQuery('._socialrewardsystempoints_twitter_field').css('display', 'none');
                        }

                        /* On Change Event Triggering for Social Rewards twitter */
                        jQuery('.social_rewardsystem_options_twitter').change(function () {
                            if (jQuery(this).val() === '') {
                                jQuery('._socialrewardsystempoints_twitter_field').css('display', 'none');
                                jQuery('._socialrewardsystempercent_twitter_field').css('display', 'none');
                            } else if (jQuery(this).val() === '1') {
                                jQuery('._socialrewardsystempercent_twitter_field').css('display', 'none');
                                jQuery('._socialrewardsystempoints_twitter_field').css('display', 'block');
                            } else {
                                jQuery('._socialrewardsystempercent_twitter_field').css('display', 'block');
                                jQuery('._socialrewardsystempoints_twitter_field').css('display', 'none');
                            }
                        });
                        /* Social Reward System for Google+ */
                        if (jQuery('.social_rewardsystem_options_google').val() === '') {
                            jQuery('._socialrewardsystempoints_google_field').css('display', 'none');
                            jQuery('._socialrewardsystempercent_google_field').css('display', 'none');
                        } else if (jQuery('.social_rewardsystem_options_google').val() === '1') {
                            jQuery('._socialrewardsystempercent_google_field').css('display', 'none');
                            jQuery('._socialrewardsystempoints_google_field').css('display', 'block');
                        } else {
                            jQuery('._socialrewardsystempercent_google_field').css('display', 'block');
                            jQuery('._socialrewardsystempoints_google_field').css('display', 'none');
                        }

                        /* On Change Event Triggering for Social Rewards Google+ */
                        jQuery('.social_rewardsystem_options_google').change(function () {
                            if (jQuery(this).val() === '') {
                                jQuery('._socialrewardsystempoints_google_field').css('display', 'none');
                                jQuery('._socialrewardsystempercent_google_field').css('display', 'none');
                            } else if (jQuery(this).val() === '1') {
                                jQuery('._socialrewardsystempercent_google_field').css('display', 'none');
                                jQuery('._socialrewardsystempoints_google_field').css('display', 'block');
                            } else {
                                jQuery('._socialrewardsystempercent_google_field').css('display', 'block');
                                jQuery('._socialrewardsystempoints_google_field').css('display', 'none');
                            }
                        });

                        /* Social Reward System for VK */
                        if (jQuery('.social_rewardsystem_options_vk').val() === '') {
                            jQuery('._socialrewardsystempoints_vk_field').css('display', 'none');
                            jQuery('._socialrewardsystempercent_vk_field').css('display', 'none');
                        } else if (jQuery('.social_rewardsystem_options_vk').val() === '1') {
                            jQuery('._socialrewardsystempercent_vk_field').css('display', 'none');
                            jQuery('._socialrewardsystempoints_vk_field').css('display', 'block');
                        } else {
                            jQuery('._socialrewardsystempercent_vk_field').css('display', 'block');
                            jQuery('._socialrewardsystempoints_vk_field').css('display', 'none');
                        }

                        /* On Change Event Triggering for Social Rewards VK */
                        jQuery('.social_rewardsystem_options_vk').change(function () {
                            if (jQuery(this).val() === '') {
                                jQuery('._socialrewardsystempoints_vk_field').css('display', 'none');
                                jQuery('._socialrewardsystempercent_vk_field').css('display', 'none');
                            } else if (jQuery(this).val() === '1') {
                                jQuery('._socialrewardsystempercent_vk_field').css('display', 'none');
                                jQuery('._socialrewardsystempoints_vk_field').css('display', 'block');
                            } else {
                                jQuery('._socialrewardsystempercent_vk_field').css('display', 'block');
                                jQuery('._socialrewardsystempoints_vk_field').css('display', 'none');
                            }
                        });

                    }
                });
            });

        </script>
        <?php
    }

    /*
     * @ Save the Reward Points custom fields value in the product meta
     *
     */

    public static function save_reward_points_admin_fields_to_product_meta($post_id) {

        $reward_system_enabled_value = $_POST['_rewardsystemcheckboxvalue'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_rewardsystemcheckboxvalue', $reward_system_enabled_value);

        /*
         * Saving Reward Points of Simple Product to prodcut meta
         */
        $reward_selection_type = $_POST['_rewardsystem_options'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_rewardsystem_options', $reward_selection_type);
        $fixed_reward_points = $_POST['_rewardsystempoints'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_rewardsystempoints', $fixed_reward_points);
        $percentage_reward_points = $_POST['_rewardsystempercent'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_rewardsystempercent', $percentage_reward_points);

        /*
         * Saving Referral Reward Points of Simple Product to prodcut meta
         */
        $referral_reward_selection_type = $_POST['_referral_rewardsystem_options'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_referral_rewardsystem_options', $referral_reward_selection_type);
        $fixed_referral_reward_points = $_POST['_referralrewardsystempoints'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_referralrewardsystempoints', $fixed_referral_reward_points);
        $percentage_referral_reward_points = $_POST['_referralrewardsystempercent'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_referralrewardsystempercent', $percentage_referral_reward_points);
    }

    /*
     * @ Save the Social Reward Points custom fields value in the product meta
     *
     */

    public static function save_social_reward_points_admin_fields_to_product_meta($post_id) {

        $social_reward_system_enabled_value = $_POST['_socialrewardsystemcheckboxvalue'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_socialrewardsystemcheckboxvalue', $social_reward_system_enabled_value);

        /*
         * Saving Social Reward Points for Facebook to prodcut meta
         */
        $social_reward_selection_type_for_facebook = $_POST['_social_rewardsystem_options_facebook'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_social_rewardsystem_options_facebook', $social_reward_selection_type_for_facebook);
        $social_fixed_reward_points_for_facebook = $_POST['_socialrewardsystempoints_facebook'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_socialrewardsystempoints_facebook', $social_fixed_reward_points_for_facebook);
        $social_percentage_reward_points_for_facebook = $_POST['_socialrewardsystempercent_facebook'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_socialrewardsystempercent_facebook', $social_percentage_reward_points_for_facebook);


        $social_reward_selection_type_for_facebook_share = $_POST['_social_rewardsystem_options_facebook_share'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_social_rewardsystem_options_facebook_share', $social_reward_selection_type_for_facebook_share);
        $social_fixed_reward_points_for_facebookshare = $_POST['_socialrewardsystempoints_facebook_share'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_socialrewardsystempoints_facebook_share', $social_fixed_reward_points_for_facebookshare);
        $social_percentage_reward_points_for_facebook_share = $_POST['_socialrewardsystempercent_facebook_share'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_socialrewardsystempercent_facebook_share', $social_percentage_reward_points_for_facebook_share);


        /*
         * Saving Social Reward Points for Twitter to prodcut meta
         */
        $social_reward_selection_type_for_twitter = $_POST['_social_rewardsystem_options_twitter'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_social_rewardsystem_options_twitter', $social_reward_selection_type_for_twitter);
        $social_fixed_reward_points_for_twitter = $_POST['_socialrewardsystempoints_twitter'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_socialrewardsystempoints_twitter', $social_fixed_reward_points_for_twitter);
        $social_percentage_reward_points_for_twitter = $_POST['_socialrewardsystempercent_twitter'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_socialrewardsystempercent_twitter', $social_percentage_reward_points_for_twitter);

        /*
         * Saving Social Reward Points for Google+ to prodcut meta
         */
        $social_reward_selection_type_for_googleplus = $_POST['_social_rewardsystem_options_google'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_social_rewardsystem_options_google', $social_reward_selection_type_for_googleplus);
        $social_fixed_reward_points_for_googleplus = $_POST['_socialrewardsystempoints_google'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_socialrewardsystempoints_google', $social_fixed_reward_points_for_googleplus);
        $social_percentage_reward_points_for_googleplus = $_POST['_socialrewardsystempercent_google'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_socialrewardsystempercent_google', $social_percentage_reward_points_for_googleplus);

        /*
         * Saving Social Reward Points for VK to prodcut meta
         */
        $social_reward_selection_type_for_vk = $_POST['_social_rewardsystem_options_vk'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_social_rewardsystem_options_vk', $social_reward_selection_type_for_vk);
        $social_fixed_reward_points_for_vk = $_POST['_socialrewardsystempoints_vk'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_socialrewardsystempoints_vk', $social_fixed_reward_points_for_vk);
        $social_percentage_reward_points_for_vk = $_POST['_socialrewardsystempercent_vk'];
        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($post_id, '_socialrewardsystempercent_vk', $social_percentage_reward_points_for_vk);
    }

    public static function rs_validation_for_input_field() {
        ?>

        <script type="text/javascript">
            jQuery(function () {
                jQuery('body').on('blur', '#_rewardsystempoints[type=text],#_rewardsystempercent[type=text],#_referralrewardsystempoints[type=text],#_referralrewardsystempercent[type=text]', function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });

                    return this;
                });

                jQuery('body').on('keyup change', '#_rewardsystem_assign_buying_points[type=text],#_rewardsystempoints[type=text],#_rewardsystempercent[type=text],#_referralrewardsystempoints[type=text],#_referralrewardsystempercent[type=text]', function () {
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

    public static function rs_validation_for_input_field_for_social() {
        ?>
        <script type="text/javascript">
            jQuery(function () {
                jQuery('body').on('blur', '#_socialrewardsystempoints_facebook[type=text],#_socialrewardsystempercent_facebook[type=text],#_socialrewardsystempoints_twitter[type=text],#_socialrewardsystempercent_twitter[type=text],#_socialrewardsystempoints_google[type=text],#_socialrewardsystempercent_google[type=text],#_socialrewardsystempoints_vk[type=text],#_socialrewardsystempercent_vk[type=text]', function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });
                    return this;
                });

                jQuery('body').on('keyup change', '#_socialrewardsystempoints_facebook[type=text],#_socialrewardsystempercent_facebook[type=text],#_socialrewardsystempoints_twitter[type=text],#_socialrewardsystempercent_twitter[type=text],#_socialrewardsystempoints_google[type=text],#_socialrewardsystempercent_google[type=text],#_socialrewardsystempoints_vk[type=text],#_socialrewardsystempercent_vk[type=text]', function () {
                    var value = jQuery(this).val();
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

    public static function display_reward_point_msg_for_product($price, $product) {
        global $post;
        $labelpoint = get_option('rs_label_for_point_value');
        $userid = get_current_user_id();
        $banning_type = FPRewardSystem::check_banning_type($userid);
        if ($banning_type != 'earningonly' && $banning_type != 'both') {
            if ((is_shop() || is_product() || is_page() || is_product_category()) && !is_admin()) {
                if (function_exists('get_product')) {
                    $gettheproducts = get_product($post->ID);

                    if (is_object($gettheproducts)&&$gettheproducts->is_type('variable')) {
                        if (is_product() || is_page()) {
                            if (get_option('rs_show_hide_message_for_variable_in_single_product_page') == '1') {
                                if (get_option('_rs_enable_disable_gift_icon') == '1') {
                                    if (get_option('rs_image_url_upload') != '') {
                                        if (get_option('rs_message_position_in_single_product_page_for_variable_products') == '1') {
                                            return "<span class='variableshopmessage'><img src=" . get_option('rs_image_url_upload') . " style='width:16px;height:16px;display:inline;' />&nbsp;" . do_shortcode(get_option('rs_message_for_single_product_variation')) . "</span><br>" . $price;
                                        } else {
                                            return $price ."<br><span class='variableshopmessage'><img src=" . get_option('rs_image_url_upload') . " style='width:16px;height:16px;display:inline;' />&nbsp;" . do_shortcode(get_option('rs_message_for_single_product_variation')) . "</span>";
                                        }
                                    } else {
                                        if (get_option('rs_message_position_in_single_product_page_for_variable_products') == '1') {
                                            return "<span class='variableshopmessage'>" . do_shortcode(get_option('rs_message_for_single_product_variation')) . "</span> <br>" . $price;
                                        } else {
                                            return $price . "<br><span class='variableshopmessage'>" . do_shortcode(get_option('rs_message_for_single_product_variation')) . "</span>";
                                        }
                                    }
                                } else {
                                    if (get_option('rs_message_position_in_single_product_page_for_variable_products') == '1') {
                                        return "<span class='variableshopmessage'>" . do_shortcode(get_option('rs_message_for_single_product_variation')) . "</span><br>" . $price;
                                    } else {
                                        return $price . "<br><span class='variableshopmessage'>" . do_shortcode(get_option('rs_message_for_single_product_variation')) . "</span>";
                                    }
                                }
                            }
                        }
                    } else {
                        $getpostpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($post->ID, '_rewardsystemcheckboxvalue');

                        $rewardpoint = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($post->ID, '_rewardsystempoints', true);
                        $getshortcodevalues = do_shortcode('[rewardpoints]');
                        if ($getshortcodevalues > 0) {

                            if (!is_admin() && (is_shop() || is_product_category())) {
                                if (get_option('_rs_enable_disable_gift_icon') == '1') {
                                    if (get_option('rs_image_url_upload') != '') {
                                        $stylerewardpoint = "<span class='simpleshopmessage'><img src=" . get_option('rs_image_url_upload') . " style='width:16px;height:16px;display:inline;' />&nbsp; " . do_shortcode(get_option("rs_message_for_single_products")) . "</span>";
                                    } else {
                                        $stylerewardpoint = "<span class='simpleshopmessage'>" . do_shortcode(get_option("rs_message_in_shop_page_for_simple")) . "</span>";
                                    }
                                } else {
                                    $stylerewardpoint = "<span class='simpleshopmessage'>" . do_shortcode(get_option("rs_message_in_shop_page_for_simple")) . "</span>";
                                }
                                if ($getpostpoints == 'yes') {

                                    if (is_product() || is_page()) {

                                        if (get_option('rs_show_hide_message_for_shop_archive_single') == '1') {
                                            if (get_option('rs_message_position_in_single_product_page_for_simple_products') == '1') {
                                                if (get_option('rs_enable_disable_point_priceing') == '1') {
                                                    $enabledpoints = RSFunctionForCart::calculate_point_price_for_products($post->ID);
                                                    $point_price = $enabledpoints[$post->ID];
                                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                                    $point_price = round($point_price, $roundofftype);
                                                    $point_price_info = get_option('rs_label_for_point_value');
                                                    $typeofprice = RSFunctionForCart ::check_display_price_type($post->ID);
                                                    if ($typeofprice == '2') {
                                                        $point_price_info = get_option('rs_label_for_point_value');
                                                        $replace = str_replace("/", "", $point_price_info);
                                                        return $stylerewardpoint . "<br>" . $replace . $point_price;
                                                    } else {
                                                        if ($point_price != '') {
                                                            return $stylerewardpoint . "<br>" . $price . '<span class="point_price_label">' . $point_price_info . $point_price;
                                                        } else {
                                                            return $stylerewardpoint . "<br>" . $price;
                                                        }
                                                    }
                                                } else {
                                                    return $stylerewardpoint . "<br>" . $price;
                                                }
                                            } else {

                                                if (get_option('rs_enable_disable_point_priceing') == '1') {
                                                    $enabledpoints = RSFunctionForCart::calculate_point_price_for_products($post->ID);
                                                    $point_price = $enabledpoints[$post->ID];
                                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                                    $point_price = round($point_price, $roundofftype);
                                                    $typeofprice = RSFunctionForCart ::check_display_price_type($post->ID);
                                                    $point_price_info = get_option('rs_label_for_point_value');
                                                    if ($typeofprice == '2') {
                                                        $replace = str_replace("/", "", $point_price_info);
                                                        return $replace . $point_price . "<br>" . $stylerewardpoint;
                                                    } else {
                                                        if ($point_price != '') {
                                                            return $price . '<span class="point_price_label">' . $point_price_info . $point_price . "<br>" . $stylerewardpoint;
                                                        } else {
                                                            return $price . "<br>" . $stylerewardpoint;
                                                        }
                                                    }
                                                } else {
                                                    return $price . "<br>" . $stylerewardpoint;
                                                }
                                            }
                                        }
                                    } else {

                                        if (get_option('rs_show_hide_message_for_simple_in_shop') == '1') {
                                            if (is_shop() || is_product_category()) {
                                                if (get_option('rs_message_position_for_simple_products_in_shop_page') == '1') {
                                                    if (get_option('rs_enable_disable_point_priceing') == '1') {
                                                        $enabledpoints = RSFunctionForCart::calculate_point_price_for_products($post->ID);
                                                        $point_price = $enabledpoints[$post->ID];
                                                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                                        $point_price = round($point_price, $roundofftype);
                                                        $typeofprice = RSFunctionForCart ::check_display_price_type($post->ID);
                                                        $point_price_info = get_option('rs_label_for_point_value');
                                                        if ($typeofprice == '2') {
                                                            $replace = str_replace("/", "", $point_price_info);
                                                            return "<small>" . $stylerewardpoint . "</small> <br>" . $replace . $point_price;
                                                        } else {
                                                            if ($point_price != '') {
                                                                return $stylerewardpoint . "<br>" . $price . '<span class="point_price_label">' . $point_price_info . $point_price;
                                                            } else {
                                                                return $stylerewardpoint . "<br>" . $price;
                                                            }
                                                        }
                                                    } else {
                                                        return $stylerewardpoint . "<br>" . $price;
                                                    }
                                                } else {
                                                    if (get_option('rs_enable_disable_point_priceing') == '1') {
                                                        $enabledpoints = RSFunctionForCart::calculate_point_price_for_products($post->ID);
                                                        $point_price = $enabledpoints[$post->ID];
                                                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                                        $point_price = round($point_price, $roundofftype);
                                                        $typeofprice = RSFunctionForCart ::check_display_price_type($post->ID);
                                                        $point_price_info = get_option('rs_label_for_point_value');
                                                        if ($typeofprice == '2') {

                                                            $replace = str_replace("/", "", $point_price_info);
                                                            return "<small>" . $replace . $point_price . "<br>" . $stylerewardpoint . "</small><br>";
                                                        } else {
                                                            if ($point_price != '') {
                                                                return $price . '<span class="point_price_label">' . $point_price_info . $point_price . "<br><small>" . $stylerewardpoint . "</small>";
                                                            } else {
                                                                return $price . "<br>" . $stylerewardpoint;
                                                            }
                                                        }
                                                    } else {
                                                        return $price . "<br>" . $stylerewardpoint;
                                                    }
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    if (get_option('rs_enable_disable_point_priceing') == '1') {
                                        $enabledpoints = RSFunctionForCart::calculate_point_price_for_products($post->ID);
                                        $point_price_info1 = $enabledpoints[$post->ID];
                                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                        $point_price = round($point_price_info1, $roundofftype);
                                        $typeofprice = RSFunctionForCart ::check_display_price_type($post->ID);
                                        if ($typeofprice == '2') {
                                            $point_price_info = get_option('rs_label_for_point_value');
                                            $replace = str_replace("/", "", $point_price_info);
                                            return "<small>" . $stylerewardpoint . "</small> <br>" . $replace . $point_price_info1;
                                        } else {
                                            return $price;
                                        }
                                    } else {
                                        return $price;
                                    }
                                }
                            } elseif (is_product() || is_page()) {
                                if (get_option('_rs_enable_disable_gift_icon') == '1') {
                                    if (get_option('rs_image_url_upload') != '') {
                                        $stylerewardpoint = "<span class='simpleshopmessage'><img src=" . get_option('rs_image_url_upload') . " style='width:16px;height:16px;display:inline;' />&nbsp; " . do_shortcode(get_option("rs_message_for_single_products")) . "</span>";
                                    } else {
                                        $stylerewardpoint = "<span class='simpleshopmessage'>" . do_shortcode(get_option("rs_message_in_single_product_page")) . "</span>";
                                    }
                                } else {
                                    $stylerewardpoint = "<span class='simpleshopmessage'>" . do_shortcode(get_option("rs_message_in_single_product_page")) . "</span>";
                                }
                                if ($getpostpoints == 'yes') {
                                    if (is_product() || is_page()) {
                                        if (get_option('rs_show_hide_message_for_shop_archive_single') == '1') {
                                            if (get_option('rs_message_position_in_single_product_page_for_simple_products') == '1') {
                                                if (get_option('rs_enable_disable_point_priceing') == '1') {

                                                    $enabledpoints = RSFunctionForCart::calculate_point_price_for_products($post->ID);

                                                    $point_price = $enabledpoints[$post->ID];
                                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                                    $point_price = round($point_price, $roundofftype);
                                                    $point_price_info = get_option('rs_label_for_point_value');
                                                    $typeofprice = RSFunctionForCart ::check_display_price_type($post->ID);
                                                    if ($typeofprice == '2') {

                                                        $replace = str_replace("/", "", $point_price_info);
                                                        return $stylerewardpoint . "<br>" . $replace . $point_price;
                                                    } else {
                                                        if ($point_price != '') {
                                                            return $stylerewardpoint . "<br>" . $price . '<span class="point_price_label">' . $point_price_info . $point_price;
                                                        } else {
                                                            return $stylerewardpoint . "<br>" . $price;
                                                        }
                                                    }
                                                } else {
                                                    return $stylerewardpoint . "<br>" . $price;
                                                }
                                            } else {

                                                if (get_option('rs_enable_disable_point_priceing') == '1') {

                                                    $enabledpoints = RSFunctionForCart::calculate_point_price_for_products($post->ID);

                                                    $point_price = $enabledpoints[$post->ID];
                                                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                                    $point_price = round($point_price, $roundofftype);
                                                    $point_price_info = get_option('rs_label_for_point_value');
                                                    $typeofprice = RSFunctionForCart ::check_display_price_type($post->ID);
                                                    if ($typeofprice == '2') {

                                                        $replace = str_replace("/", "", $point_price_info);
                                                        return $replace . "<br>" . $stylerewardpoint;
                                                    } else {
                                                        if ($point_price != '') {
                                                            return $price . '<span class="point_price_label">' . $point_price_info . $point_price . "<br><small>" . $stylerewardpoint . "</small>";
                                                        } else {
                                                            return $price . "<br>" . $stylerewardpoint;
                                                        }
                                                    }
                                                } else {

                                                    return $price . "<br>" . $stylerewardpoint;
                                                }
                                            }
                                        }
                                    } else {
                                        if (get_option('rs_show_hide_message_for_simple_in_shop') == '1') {
                                            if (is_shop() || is_product_category()) {
                                                if (get_option('rs_message_position_for_simple_products_in_shop_page') == '1') {
                                                    return "<small>" . $stylerewardpoint . "</small> <br>" . $price;
                                                } else {
                                                    return $price . "<br> <small>" . $stylerewardpoint . "</small>";
                                                }
                                            }
                                        }
                                    }
                                } else {
                                    
                                }
                            }
                        } else {
                            if (get_option('rs_enable_disable_point_priceing') == '1') {
                                $enabledpoints = RSFunctionForCart::calculate_point_price_for_products($post->ID);
                                $point_price = $enabledpoints[$post->ID];
                                $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                                $point_price = round($point_price, $roundofftype);
                                $typeofprice = RSFunctionForCart ::check_display_price_type($post->ID);
                                $point_price_info = get_option('rs_label_for_point_value');
                                if ($typeofprice == '2') {
                                    $replace = str_replace("/", "", $point_price_info);
                                    return $replace . $point_price;
                                } else {
                                    if ($point_price != '') {
                                        return $price . '<span class="point_price_label">' . $point_price_info . $point_price;
                                    } else {
                                        return $price;
                                    }
                                }
                            } else {
                                return $price;
                            }
                        }
                    }
                }
            }
        }
        return $price;
    }

    public static function add_shortcode_function_for_rewardpoints_of_simple() {      
        global $post;
        $checkproducttype = get_product($post->ID);
        $global_enable = get_option('rs_global_enable_disable_sumo_reward');
        $global_reward_type = get_option('rs_global_reward_type');
        if (is_shop() || is_product() || is_page() || is_product_category()) {
            if (is_object($checkproducttype)&&($checkproducttype->is_type('simple') || ($checkproducttype->is_type('subscription')))) {
                $enablerewards = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($post->ID, '_rewardsystemcheckboxvalue');
                $getaction = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($post->ID, '_rewardsystem_options');
                $getpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($post->ID, '_rewardsystempoints');
                $getpercent = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($post->ID, '_rewardsystempercent');
                if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($post->ID, '_regular_price');
                    if ($getregularprice == '') {
                        $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($post->ID, '_price');
                    }
                } else {
                    $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($post->ID, '_price');
                    if ($getregularprice == '') {
                        $getregularprice = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($post->ID, '_regular_price');
                    }
                }

                $rewardpoints = array('0');
                if ($enablerewards == 'yes') {
                    if ($getaction == '1') {
                        if ($getpoints == '') {
                            $term = get_the_terms($post->ID, 'product_cat');
                            if (is_array($term)) {
                                foreach ($term as $term) {
                                    $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_reward_system_category', true);
                                    $display_type = get_woocommerce_term_meta($term->term_id, 'enable_rs_rule', true);
                                    if (($enablevalue == 'yes') && ($enablevalue != '')) {
                                        if ($display_type == '1') {
                                            if (get_woocommerce_term_meta($term->term_id, 'rs_category_points', true) == '') {
                                                $global_enable = get_option('rs_global_enable_disable_sumo_reward');
                                                $global_reward_type = get_option('rs_global_reward_type');
                                                if ($global_enable == '1') {
                                                    if ($global_reward_type == '1') {
                                                        $rewardpoints[] = get_option('rs_global_reward_points');
                                                    } else {
                                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                        $getaverage = get_option('rs_global_reward_percent') / 100;
                                                        $getaveragepoints = $getaverage * $getregularprice;
                                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                    }
                                                }
                                            } else {
                                                $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'rs_category_points', true);
                                            }
                                        } else {
                                            $pointconversion =  RSFunctionofGeneralTab::earn_point_conversion();
                                            $pointconversionvalue =  RSFunctionofGeneralTab::earn_point_conversion_value();
                                                                                          
                                            $getaverage = get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) / 100;
                                            $getaveragepoints = $getaverage * $getregularprice;
                                            $pointswithvalue = $getaveragepoints * $pointconversion;
                                            if (get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) == '') {

                                                if ($global_enable == '1') {
                                                    if ($global_reward_type == '1') {
                                                        $rewardpoints[] = get_option('rs_global_reward_points');
                                                    } else {
                                                         $pointconversion =  RSFunctionofGeneralTab::earn_point_conversion();
                                            $pointconversionvalue =  RSFunctionofGeneralTab::earn_point_conversion_value();
                                                        $getaverage = get_option('rs_global_reward_percent') / 100;
                                                        $getaveragepoints = $getaverage * $getregularprice;
                                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                    }
                                                }
                                            } else {
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_reward_points');
                                            } else {
                                                 $pointconversion =  RSFunctionofGeneralTab::earn_point_conversion();
                                            $pointconversionvalue =  RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($global_enable == '1') {
                                    if ($global_reward_type == '1') {
                                        $rewardpoints[] = get_option('rs_global_reward_points');
                                    } else {
                                        $pointconversion =  RSFunctionofGeneralTab::earn_point_conversion();
                                            $pointconversionvalue =  RSFunctionofGeneralTab::earn_point_conversion_value();
                                        $getaverage = get_option('rs_global_reward_percent') / 100;
                                        $getaveragepoints = $getaverage * $getregularprice;
                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            }
                            if (!empty($rewardpoints)) {
                                $getpoints = max($rewardpoints);
                            }
                        }
                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                        if (get_current_user_id() > 0) {
                            return round(RSMemberFunction::user_role_based_reward_points(get_current_user_id(), $getpoints), $roundofftype);
                        } else {
                            return round($getpoints, $roundofftype);
                        }
                    } else {
                        $points =RSFunctionofGeneralTab::earn_point_conversion();
                        $pointsequalto = RSFunctionofGeneralTab::earn_point_conversion_value();
                        $takeaverage = $getpercent / 100;
                        $mainaveragevalue = $takeaverage * $getregularprice;
                        $addinpoint = $mainaveragevalue * $points;
                        $totalpoint = $addinpoint / $pointsequalto;
                        if ($getpercent === '') {
                            $term = get_the_terms($post->ID, 'product_cat');
                            if (is_array($term)) {
                                foreach ($term as $term) {
                                    $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_reward_system_category', true);
                                    $display_type = get_woocommerce_term_meta($term->term_id, 'enable_rs_rule', true);
                                    if (($enablevalue == 'yes') && ($enablevalue != '')) {
                                        if ($display_type == '1') {
                                            if (get_woocommerce_term_meta($term->term_id, 'rs_category_points', true) == '') {
                                                $global_enable = get_option('rs_global_enable_disable_sumo_reward');
                                                $global_reward_type = get_option('rs_global_reward_type');
                                                if ($global_enable == '1') {
                                                    if ($global_reward_type == '1') {
                                                        $rewardpoints[] = get_option('rs_global_reward_points');
                                                    } else {
                                                        $pointconversion =  RSFunctionofGeneralTab::earn_point_conversion();
                                            $pointconversionvalue =  RSFunctionofGeneralTab::earn_point_conversion_value();
                                                        $getaverage = get_option('rs_global_reward_percent') / 100;
                                                        $getaveragepoints = $getaverage * $getregularprice;
                                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                    }
                                                }
                                            } else {
                                                $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'rs_category_points', true);
                                            }
                                        } else {
                                            $pointconversion =  RSFunctionofGeneralTab::earn_point_conversion();
                                            $pointconversionvalue =  RSFunctionofGeneralTab::earn_point_conversion_value();
                                            $getaverage = get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) / 100;
                                            $getaveragepoints = $getaverage * $getregularprice;
                                            $pointswithvalue = $getaveragepoints * $pointconversion;
                                            if (get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) == '') {
                                                $global_enable = get_option('rs_global_enable_disable_sumo_reward');
                                                $global_reward_type = get_option('rs_global_reward_type');
                                                if ($global_enable == '1') {
                                                    if ($global_reward_type == '1') {
                                                        $rewardpoints[] = get_option('rs_global_reward_points');
                                                    } else {
                                                       $pointconversion =  RSFunctionofGeneralTab::earn_point_conversion();
                                            $pointconversionvalue =  RSFunctionofGeneralTab::earn_point_conversion_value();
                                                        $getaverage = get_option('rs_global_reward_percent') / 100;
                                                        $getaveragepoints = $getaverage * $getregularprice;
                                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                    }
                                                }
                                            } else {
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    } else {
                                        if ($global_enable == '1') {
                                            if ($global_reward_type == '1') {
                                                $rewardpoints[] = get_option('rs_global_reward_points');
                                            } else {
                                                $pointconversion =  RSFunctionofGeneralTab::earn_point_conversion();
                                            $pointconversionvalue =  RSFunctionofGeneralTab::earn_point_conversion_value();
                                                $getaverage = get_option('rs_global_reward_percent') / 100;
                                                $getaveragepoints = $getaverage * $getregularprice;
                                                $pointswithvalue = $getaveragepoints * $pointconversion;
                                                $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                            }
                                        }
                                    }
                                }
                            } else {
                                if ($global_enable == '1') {
                                    if ($global_reward_type == '1') {
                                        $rewardpoints[] = get_option('rs_global_reward_points');
                                    } else {
                                        $pointconversion =  RSFunctionofGeneralTab::earn_point_conversion();
                                            $pointconversionvalue =  RSFunctionofGeneralTab::earn_point_conversion_value();
                                        $getaverage = get_option('rs_global_reward_percent') / 100;
                                        $getaveragepoints = $getaverage * $getregularprice;
                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                        $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                    }
                                }
                            }
                            if (!empty($rewardpoints)) {
                                $totalpoint = max($rewardpoints);
                            } else {
                                $totalpoint = 0;
                            }
                        }
                        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                        if (get_current_user_id() > 0) {
                            return round(RSMemberFunction::user_role_based_reward_points(get_current_user_id(), $totalpoint), $roundofftype);
                        } else {
                            return round($totalpoint, $roundofftype);
                        }
                    }
                }
            } else {
                
            }
        }
    }

    public static function display_purchase_message_for_simple_in_single_product_page() {
        global $post;
        $order = '';
        if (is_user_logged_in()) {
            $userid = get_current_user_id();
            $banning_type = FPRewardSystem::check_banning_type($userid);
            if ($banning_type != 'earningonly' && $banning_type != 'both') {
                $checkproducttype = get_product($post->ID);
                if (get_option('rs_show_hide_message_for_single_product') == '1') {
                    if (is_object($checkproducttype)&&($checkproducttype->is_type('simple') || ($checkproducttype->is_type('subscription')))) {
                        if (RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($post->ID, '_rewardsystemcheckboxvalue') == 'yes') {
                            if (RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($post->ID, '_rewardsystem_options') == '1') {
                                $rewardpoints = do_shortcode('[rewardpoints]');
                                if ($rewardpoints > 0) {
                                    ?>
                                    <div class="woocommerce-info"><?php echo do_shortcode(get_option('rs_message_for_single_product_point_rule')); ?></div>
                                    <?php
                                }
                            } else {
                                $rewardpoints = do_shortcode('[rewardpoints]');
                                if ($rewardpoints > 0) {
                                    ?>
                                    <div class="woocommerce-info"><?php echo do_shortcode(get_option('rs_message_for_single_product_point_rule')); ?></div>
                                    <?php
                                }
                            }
                        }
                    } else {
                        global $woocommerce;
                        if (isset($woocommerce->cart->cart_contents)) {
                            ?>
                            <div class="header_cart">
                                <div class="cart_contents">
                                    <a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title=""><?php sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'rewardsystem'), $woocommerce->cart->cart_contents_count); ?></a>
                                </div>
                            </div>
                            <?php
                        }
                    }
                }
            }
        } else {
            if (get_option('rs_show_hide_message_for_single_product') == '1') {
                $checkproducttype = get_product($post->ID);
                if (is_object($checkproducttype)&&($checkproducttype->is_type('simple') || ($checkproducttype->is_type('subscription')))) {
                    if (RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($post->ID, '_rewardsystemcheckboxvalue') == 'yes') {
                        if (RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($post->ID, '_rewardsystem_options') == '1') {
                            $rewardpoints = do_shortcode('[rewardpoints]');
                            if ($rewardpoints > 0) {
                                ?>
                                <div class="woocommerce-info"><?php echo do_shortcode(get_option('rs_message_for_single_product_point_rule')); ?></div>
                                <?php
                            }
                        } else {
                            $rewardpoints = do_shortcode('[rewardpoints]');
                            if ($rewardpoints > 0) {
                                ?>
                                <div class="woocommerce-info"><?php echo do_shortcode(get_option('rs_message_for_single_product_point_rule')); ?></div>
                                <?php
                            }
                        }
                    }
                } else {
                    global $woocommerce;
                    if (isset($woocommerce->cart->cart_contents)) {
                        ?>
                        <div class="header_cart">
                            <div class="cart_contents">
                                <a class="cart-contents" href="<?php echo $woocommerce->cart->get_cart_url(); ?>" title=""><?php sprintf(_n('%d item', '%d items', $woocommerce->cart->cart_contents_count, 'rewardsystem'), $woocommerce->cart->cart_contents_count); ?> </a>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
        }
    }

    public static function get_redeem_conversion_value() {
        if (get_current_user_id() > 0) {
            $singleproductvalue = do_shortcode('[rewardpoints]');
        } else {
            $singleproductvalue = do_shortcode('[rewardpoints]');
        }
//        var_dump($singleproductvalue);
        $newvalue = $singleproductvalue / wc_format_decimal(get_option('rs_redeem_point'));
        $updatedvalue = $newvalue * wc_format_decimal(get_option('rs_redeem_point_value'));
        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
        $price = round($updatedvalue, $roundofftype);
        return RSFunctionForCart::get_woocommerce_formatted_price($price);
    }

}

new RSSimpleProduct();
