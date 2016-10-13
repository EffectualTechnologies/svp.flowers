<?php

class RSVariableProduct {

    public function __construct() {

        add_action('woocommerce_product_after_variable_attributes', array($this, 'rs_admin_option_for_variable_product'), 10, 3);

        add_action('woocommerce_product_after_variable_attributes_js', array($this, 'rs_admin_option_for_variable_product_in_js'));

        add_action('woocommerce_save_product_variation', array($this, 'save_variable_product_fields'), 10, 2);

        add_action('woocommerce_process_product_meta_variable-subscription', array($this, 'save_variable_product_fields_for_subscription'), 10, 1);

        add_action('woocommerce_before_single_product', array($this, 'display_msg_for_variable_product'));

        add_shortcode('variationrewardpoints', array($this, 'add_variation_shortcode_div'));

        add_shortcode('variationpointprice', array($this, 'add_variation_shortcode'));
        add_action('wp_head', array($this, 'display_purchase_msg_for_variable_product'));

        add_shortcode('variationpointsvalue', array($this, 'add_variation_point_values_shortcode'));

        add_action('wp_ajax_nopriv_getvariationid', array($this, 'add_shortcode_for_rewardpoints_of_variation'));

        add_action('wp_ajax_getvariationid', array($this, 'add_shortcode_for_rewardpoints_of_variation'));

        add_action('wp_ajax_nopriv_getvariationid', array($this, 'add_shortcode_for_point_price_of_variation'));

        add_action('wp_ajax_getvariationid', array($this, 'add_shortcode_for_point_price_of_variation'));

        add_action('woocommerce_product_after_variable_attributes', array($this, 'rs_validation_for_input_field_in_variable_product'), 10, 3);

        add_filter('woocommerce_ajax_variation_threshold', array($this, 'rs_function_to_alert_the_variation_limit'), 999, 2);
    }

    public static function rs_admin_option_for_variable_product_in_js() {
        if (is_admin()) {
            ?>
            <table>
                <tr>
                    <td>
                        <?php
                        // Select
                        woocommerce_wp_select(
                                array(
                                    'id' => '_enable_reward_points_price[ + loop + ]',
                                    'label' => __('Enable SUMO Reward Points Price', 'rewardsystem'),
                                    'desc_tip' => 'true',
                                    'description' => __('Choose an Option.', 'rewardsystem'),
                                    'value' => $variation_data['_enable_reward_points_price'][0],
                                    'options' => array(
                                        '1' => __('Enable', 'rewardsystem'),
                                        '2' => __('Disable', 'rewardsystem'),
                                    )
                                )
                        );
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        <?php
                        // Select
                        woocommerce_wp_select(
                                array(
                                    'id' => '_enable_reward_points_pricing_type[ + loop + ]',
                                    'label' => __(' Precing Display Type', 'rewardsystem'),
                                    'desc_tip' => 'true',
                                    'description' => __('Choose an Option.', 'rewardsystem'),
                                    'value' => $variation_data['_enable_reward_points_pricing_type'][0],
                                    'options' => array(
                                        '1' => __('Currency & Points', 'rewardsystem'),
                                        '2' => __('Only Points', 'rewardsystem'),
                                    )
                                )
                        );
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        <?php
                        // Select
                        woocommerce_wp_select(
                                array(
                                    'id' => '_enable_reward_points_price_type[ + loop + ]',
                                    'label' => __(' Points Price Type', 'rewardsystem'),
                                    'desc_tip' => 'true',
                                    'description' => __('Choose an Option.', 'rewardsystem'),
                                    'value' => $variation_data['_enable_reward_points_price_type'][0],
                                    'options' => array(
                                        '1' => __('BY Fixed', 'rewardsystem'),
                                        '2' => __('Based on Conversion', 'rewardsystem'),
                                    )
                                )
                        );
                        ?>
                    </td>
                </tr>


                <tr>
                    <td>
                        <?php
                        // Text Field
                        woocommerce_wp_text_input(
                                array(
                                    'id' => 'price_points[ + loop + ]',
                                    'label' => __('By Fixed Points Price:', 'rewardsystem'),
                                    'placeholder' => '',
                                    'size' => '5',
                                    'desc_tip' => 'true',
                                    'description' => __('By Fixed Point Price', 'rewardsystem'),
                                    'value' => ''
                                )
                        );
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                        // Text Field
                        woocommerce_wp_text_input(
                                array(
                                    'id' => '_price_points_based_on_conversion[ + loop + ]',
                                    'label' => __(' Points Price Based on Conversion:', 'rewardsystem'),
                                    'placeholder' => '',
                                    'size' => '5',
                                    'class' => 'fp_point_price',
                                    'desc_tip' => 'true',
                                    'description' => __('Point Price Based on Conversion', 'rewardsystem'),
                                    'value' => ''
                                )
                        );
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                        // Select
                        woocommerce_wp_select(
                                array(
                                    'id' => '_enable_reward_points[ + loop + ]',
                                    'label' => __('Enable SUMO Reward Points', 'rewardsystem'),
                                    'desc_tip' => 'true',
                                    'description' => __('Choose an Option.', 'rewardsystem'),
                                    'value' => $variation_data['_enable_reward_points'][0],
                                    'options' => array(
                                        '1' => __('Enable', 'rewardsystem'),
                                        '2' => __('Disable', 'rewardsystem'),
                                    )
                                )
                        );
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        <?php
                        // Select

                        woocommerce_wp_select(
                                array(
                                    'id' => '_select_reward_rule[ + loop + ]',
                                    'label' => __('Reward Type', 'rewardsystem'),
                                    'class' => '_select_reward_rule',
                                    'description' => __('Select Reward Rule', 'rewardsystem'),
                                    'value' => '',
                                    'options' => array(
                                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                                    )
                                )
                        );
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                        // Text Field
                        woocommerce_wp_text_input(
                                array(
                                    'id' => '_reward_points[ + loop + ]',
                                    'label' => __('Reward Points', 'rewardsystem'),
                                    'placeholder' => '',
                                    'desc_tip' => 'true',
                                    'description' => __('This Value is applicable for "By Fixed Reward Points" Reward Type', 'rewardsystem'),
                                    'value' => ''
                                )
                        );
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                        woocommerce_wp_text_input(
                                array(
                                    'id' => '_reward_percent[ + loop + ]',
                                    'label' => __('Reward Percent', 'rewardsystem'),
                                    'placeholder' => '',
                                    'desc_tip' => 'true',
                                    'description' => __('This Value is applicable for "By Percentage of Product Price" Reward Type', 'rewardsystem'),
                                    'value' => ''
                                )
                        );
                        ?>
                    </td>
                </tr>

                <tr>
                    <td>
                        <?php
                        // Select

                        woocommerce_wp_select(
                                array(
                                    'id' => '_select_referral_reward_rule[ + loop + ]',
                                    'label' => __('Referral Reward Type', 'rewardsystem'),
                                    'class' => '_select_referral_reward_rule',
                                    'description' => __('Select Referral Reward Rule', 'rewardsystem'),
                                    'value' => '',
                                    'options' => array(
                                        '1' => __('By Fixed Reward Points', 'rewardsystem'),
                                        '2' => __('By Percentage of Product Price', 'rewardsystem'),
                                    )
                                )
                        );
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                        // Text Field
                        woocommerce_wp_text_input(
                                array(
                                    'id' => '_referral_reward_points[ + loop + ]',
                                    'label' => __('Referral Reward Points', 'rewardsystem'),
                                    'placeholder' => '',
                                    'desc_tip' => 'true',
                                    'description' => __('This Value is applicable for "By Fixed Reward Points" Referral Referral Reward Type', 'rewardsystem'),
                                    'value' => ''
                                )
                        );
                        ?>
                    </td>
                </tr>
                <tr>
                    <td>
                        <?php
                        woocommerce_wp_text_input(
                                array(
                                    'id' => '_referral_reward_percent[ + loop + ]',
                                    'label' => __('Referral Reward Percent', 'rewardsystem'),
                                    'placeholder' => '',
                                    'desc_tip' => 'true',
                                    'description' => __('This Value is applicable for "By Percentage of Product Price" Referral Reward Type', 'rewardsystem'),
                                    'value' => ''
                                )
                        );
                        ?>
                    </td>
                </tr>
            </table>
            <?php
        }
    }

    public static function rs_admin_option_for_variable_product($loop, $variation_data, $variations) {

        global $post;
        global $woocommerce;
        $enable_reward_point = '';
        $reward_type = '';
        $reward_points = '';
        $reward_points_in_percent = '';
        $referral_reward_type = '';
        $referral_reward_points = '';
        $referral_reward_points_in_percent = '';
        $pointprice = '';
        $enablepointprice = '';
        $point_price_type = '';
        $pointprice_text = '';
        $precing_type = '';
        $variation_data = get_post_meta($variations->ID);
        if (is_admin()) {

            if (isset($variation_data['_enable_reward_points_price'][0]))
                $enablepointprice = $variation_data['_enable_reward_points_price'][0];


            woocommerce_wp_select(
                    array(
                        'id' => '_enable_reward_points_price[' . $loop . ']',
                        'label' => __('Enable Point Price:', 'rewardsystem'),
                        'desc_tip' => true,
                        'class' => '_enable_reward_points_price_variation',
                        'description' => __('Enable Point Price ', 'rewardsystem'),
                        'value' => $enablepointprice,
                        'default' => '1',
                        'options' => array(
                            '1' => __('Enable', 'rewardsystem'),
                            '2' => __('Disable', 'rewardsystem'),
                        )
                    )
            );


            if (isset($variation_data['_enable_reward_points_pricing_type'][0]))
                $precing_type = $variation_data['_enable_reward_points_pricing_type'][0];


            woocommerce_wp_select(
                    array(
                        'id' => '_enable_reward_points_pricing_type[' . $loop . ']',
                        'label' => __(' Pricing Display Type:', 'rewardsystem'),
                        'desc_tip' => true,
                        'description' => __(' Pricing Type ', 'rewardsystem'),
                        'value' => $precing_type,
                        'class' => 'fp_point_price',
                        'default' => '1',
                        'options' => array(
                            '1' => __('Currency & Points', 'rewardsystem'),
                            '2' => __('Points Only', 'rewardsystem'),
                        )
                    )
            );


            if (isset($variation_data['_enable_reward_points_price_type'][0]))
                $point_price_type = $variation_data['_enable_reward_points_price_type'][0];


            woocommerce_wp_select(
                    array(
                        'id' => '_enable_reward_points_price_type[' . $loop . ']',
                        'label' => __('Point Price Type:', 'rewardsystem'),
                        'desc_tip' => true,
                        'description' => __(' Point Price Type ', 'rewardsystem'),
                        'value' => $point_price_type,
                        'class' => 'fp_point_price_currency',
                        'default' => '1',
                        'options' => array(
                            '1' => __('By Fixed', 'rewardsystem'),
                            '2' => __('Based On Conversion', 'rewardsystem'),
                        )
                    )
            );
            if (isset($variation_data['_price_points_based_on_conversion'][0]))
                $pointprice_text = $variation_data['_price_points_based_on_conversion'][0];


            woocommerce_wp_text_input(
                    array(
                        'id' => '_price_points_based_on_conversion[' . $loop . ']',
                        'label' => __(' Point Price Based on Conversion:', 'rewardsystem'),
                        'class' => 'fp_variation_points_price',
                        'size' => '5',
                        'value' => $pointprice_text,
                    )
            );

            if (isset($variation_data['price_points'][0]))
                $pointprice = $variation_data['price_points'][0];


            woocommerce_wp_text_input(
                    array(
                        'id' => 'price_points[' . $loop . ']',
                        'label' => __('By Fixed PointPrice:', 'rewardsystem'),
                        'size' => '5',
                        'class' => 'fp_variation_points_price_field',
                        'value' => $pointprice,
                    )
            );


            woocommerce_wp_select(
                    array(
                        'id' => '_enable_reward_points[' . $loop . ']',
                        'label' => __('Enable SUMO Reward Points', 'rewardsystem'),
                        'default' => '2',
                        'desc_tip' => false,
                        'description' => __('Enable will Turn On Reward Points for Product Purchase and Category/Product Settings will be considered if it is available. '
                                . 'Disable will Turn Off Reward Points for Product Purchase and Category/Product Settings will be considered if it is available. ', 'rewardsystem'),
                        'value' => $enable_reward_point,
                        'options' => array(
                            '1' => __('Enable', 'rewardsystem'),
                            '2' => __('Disable', 'rewardsystem'),
                        )
                    )
            );
            if (isset($variation_data['_select_reward_rule'][0]))
                $reward_type = $variation_data['_select_reward_rule'][0];


            woocommerce_wp_select(
                    array(
                        'id' => '_select_reward_rule[' . $loop . ']',
                        'label' => __('Reward Type', 'rewardsystem'),
                        'default' => '2',
                        'value' => $reward_type,
                        'options' => array(
                            '1' => __('By Fixed Reward Points', 'rewardsystem'),
                            '2' => __('By Percentage of Product Price', 'rewardsystem'),
                        )
                    )
            );

            if (isset($variation_data['_reward_points'][0]))
                $reward_points = $variation_data['_reward_points'][0];

            woocommerce_wp_text_input(
                    array(
                        'id' => '_reward_points[' . $loop . ']',
                        'label' => __('Reward Points', 'rewardsystem'),
                        'description' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                        'desc_tip' => 'true',
                        'value' => $reward_points
                    )
            );

            if (isset($variation_data['_reward_percent'][0]))
                $reward_points_in_percent = $variation_data['_reward_percent'][0];

            woocommerce_wp_text_input(
                    array(
                        'id' => '_reward_percent[' . $loop . ']',
                        'label' => __('Reward Points in Percent %', 'rewardsystem'),
                        'description' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                        'desc_tip' => 'true',
                        'value' => $reward_points_in_percent
                    )
            );

            if (isset($variation_data['_select_referral_reward_rule'][0]))
                $referral_reward_type = $variation_data['_select_referral_reward_rule'][0];


            woocommerce_wp_select(
                    array(
                        'id' => '_select_referral_reward_rule[' . $loop . ']',
                        'label' => __('Referral Reward Type', 'rewardsystem'),
                        'default' => '2',
                        'value' => $referral_reward_type,
                        'options' => array(
                            '1' => __('By Fixed Reward Points', 'rewardsystem'),
                            '2' => __('By Percentage of Product Price', 'rewardsystem'),
                        )
                    )
            );

            if (isset($variation_data['_referral_reward_points'][0]))
                $referral_reward_points = $variation_data['_referral_reward_points'][0];

            woocommerce_wp_text_input(
                    array(
                        'id' => '_referral_reward_points[' . $loop . ']',
                        'label' => __('Referral Reward Points', 'rewardsystem'),
                        'description' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                        'desc_tip' => 'true',
                        'value' => $referral_reward_points
                    )
            );

            if (isset($variation_data['_referral_reward_percent'][0]))
                $referral_reward_points_in_percent = $variation_data['_referral_reward_percent'][0];

            woocommerce_wp_text_input(
                    array(
                        'id' => '_referral_reward_percent[' . $loop . ']',
                        'label' => __('Referral Reward Points in Percent %', 'rewardsystem'),
                        'description' => __('When left empty, Category and Product Settings will be considered in the same order and Current Settings (Global Settings) will be ignored. '
                                . 'When value greater than or equal to 0 is entered then Current Settings (Global Settings) will be considered and Category/Global Settings will be ignored.  ', 'rewardsystem'),
                        'desc_tip' => 'true',
                        'value' => $referral_reward_points_in_percent
                    )
            );
        }
    }

    public static function save_variable_product_fields_for_subscription($post_id) {
        if (isset($_POST['variable_sku'])) :
            $variable_sku = $_POST['variable_sku'];
            $variable_post_id = $_POST['variable_post_id'];

// Text Field
            $_text_field = $_POST['_reward_points'];
            for ($i = 0; $i < sizeof($variable_sku); $i++) :
                $variation_id = (int) $variable_post_id[$i];
                if (isset($_text_field[$i])) {
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_reward_points', stripslashes($_text_field[$i]));
                }
            endfor;
            $point_select = $_POST['_enable_reward_points_price'];
            for ($i = 0; $i < sizeof($variable_sku); $i++):
                $variation_id = (int) $variable_post_id[$i];
                if (isset($point_select[$i])) {
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_enable_reward_points_price', stripslashes($point_select[$i]));
                }
            endfor;

            $_text_field1 = $_POST['$_enable_reward_points_pricing_type'];
            for ($i = 0; $i < sizeof($variable_sku); $i++) :
                $variation_id = (int) $variable_post_id[$i];
                if (isset($_text_field1[$i])) {
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '$_enable_reward_points_pricing_type', stripslashes($_text_field1[$i]));
                }
            endfor;




            $point_text = $_POST['price_points'];
            for ($i = 0; $i < sizeof($variable_sku); $i++):
                $variation_id = (int) $variable_post_id[$i];
                if (isset($point_text[$i])) {
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, 'price_points', stripslashes($point_text[$i]));
                }
            endfor;

            $points_based_on_conversion = $_POST['variable_sale_price'];

            $point_price_type = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variation_id, '_enable_reward_points_price_type');
            if ($point_price_type == 2) {
                for ($i = 0; $i < sizeof($variable_sku); $i++):
                    $variation_id = (int) $variable_post_id[$i];

                    if (isset($points_based_on_conversion[$i])) {
                        $newvalue = $points_based_on_conversion[$i] / wc_format_decimal(get_option('rs_redeem_point_value'));
                        $points_based_on_conversion[$i] = $newvalue * wc_format_decimal(get_option('rs_redeem_point'));
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_price_points_based_on_conversion', stripslashes($points_based_on_conversion[$i]));
                    }
                endfor;
                for ($i = 0; $i < sizeof($variable_sku); $i++):
                    $variation_id = (int) $variable_post_id[$i];
                    $point_price_typeert = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variation_id, '_price_points_based_on_conversion', true);
                    if (empty($point_price_typeert)) {
                        $points_based_on_conversion = $_POST['variable_regular_price'];
                        if (isset($points_based_on_conversion[$i])) {
                            $newvalue = $points_based_on_conversion[$i] / wc_format_decimal(get_option('rs_redeem_point_value'));
                            $points_based_on_conversion[$i] = $newvalue * wc_format_decimal(get_option('rs_redeem_point'));
                            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_price_points_based_on_conversion', stripslashes($points_based_on_conversion[$i]));
                        }
                    }
                endfor;
            } else {
                for ($i = 0; $i < sizeof($variable_sku); $i++):
                    $variation_id = (int) $variable_post_id[$i];
                    if (isset($points_based_on_conversion[$i])) {
                        $points_based_on_conversion[$i] = '';
                        RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_price_points_based_on_conversion', stripslashes($points_based_on_conversion[$i]));
                    }
                endfor;
            }


            $percent_text_field = $_POST['_reward_percent'];
            for ($i = 0; $i < sizeof($variable_sku); $i++):
                $variation_id = (int) $variable_post_id[$i];
                if (isset($percent_text_field[$i])) {
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_reward_percent', stripslashes($percent_text_field[$i]));
                }
            endfor;
//select
            $new_select = $_POST['_select_reward_rule'];
            for ($i = 0; $i < sizeof($variable_sku); $i++):
                $variation_id = (int) $variable_post_id[$i];
                if (isset($new_select[$i])) {
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_select_reward_rule', stripslashes($new_select[$i]));
                }
            endfor;


            $_text_fields = $_POST['_referral_reward_points'];
            for ($i = 0; $i < sizeof($variable_sku); $i++) :
                $variation_id = (int) $variable_post_id[$i];
                if (isset($_text_field[$i])) {
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_referral_reward_points', stripslashes($_text_fields[$i]));
                }
            endfor;

            $percent_text_fields = $_POST['_referral_reward_percent'];
            for ($i = 0; $i < sizeof($variable_sku); $i++):
                $variation_id = (int) $variable_post_id[$i];
                if (isset($percent_text_field[$i])) {
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_referral_reward_percent', stripslashes($percent_text_fields[$i]));
                }
            endfor;
//select
            $new_selects = $_POST['_select_referral_reward_rule'];
            for ($i = 0; $i < sizeof($variable_sku); $i++):
                $variation_id = (int) $variable_post_id[$i];
                if (isset($new_select[$i])) {
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_select_referral_reward_rule', stripslashes($new_selects[$i]));
                }
            endfor;


// Select
            $_select = $_POST['_enable_reward_points'];
            for ($i = 0; $i < sizeof($variable_sku); $i++) :
                $variation_id = (int) $variable_post_id[$i];
                if (isset($_select[$i])) {
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_enable_reward_points', stripslashes($_select[$i]));
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_enable_reward_points', stripslashes($_select[$i]));
                }
            endfor;
        endif;
    }

    /*
     * @ Save the Reward Points custom fields value of Variable Product in the product meta
     *
     */

    public static function save_variable_product_fields($variation_id, $i) {


        $variable_sku = $_POST['variable_sku'];
        $variable_post_id = $_POST['variable_post_id'];
        $fff = get_post_meta($variation_id, '_regular_price');
        $conversion_type = $_POST['_enable_reward_points_price_type'];
        $regular_price = get_post_meta($variation_id, '_regular_price', true);
        if ($regular_price == '') {
            update_post_meta($variation_id, '_regular_price', 0);
            update_post_meta($variation_id, '_price', 0);
        }



        if (isset($conversion_type[$i])) {
            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_enable_reward_points_price_type', stripslashes($conversion_type[$i]));
        }
        $points_based_on_conversion = $_POST['variable_sale_price'];
        $point_price_type = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variation_id, '_enable_reward_points_price_type');
        if ($point_price_type == 2) {
            if (isset($points_based_on_conversion[$i])) {
                $newvalue = $points_based_on_conversion[$i] / wc_format_decimal(get_option('rs_redeem_point_value'));
                $points_based_on_conversion[$i] = $newvalue * wc_format_decimal(get_option('rs_redeem_point'));
                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_price_points_based_on_conversion', stripslashes($points_based_on_conversion[$i]));
                $point_price_typecv = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($variation_id, '_price_points_based_on_conversion');
                if (empty($point_price_typecv)) {
                    $points_based_on_conversion = $_POST['variable_regular_price'];
                    $newvalue = $points_based_on_conversion[$i] / wc_format_decimal(get_option('rs_redeem_point_value'));
                    $points_based_on_conversion[$i] = $newvalue * wc_format_decimal(get_option('rs_redeem_point'));
                    RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_price_points_based_on_conversion', stripslashes($points_based_on_conversion[$i]));
                }
            }
        } else {
            if (isset($points_based_on_conversion[$i])) {
                $points_based_on_conversion[$i] = '';
                RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_price_points_based_on_conversion', stripslashes($points_based_on_conversion[$i]));
            }
        }

        // Text Field
        $_text_field = $_POST['_reward_points'];

        if (isset($_text_field[$i])) {
            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_reward_points', stripslashes($_text_field[$i]));
        }

        $point_select = $_POST['_enable_reward_points_price'];

        if (isset($point_select[$i])) {
            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_enable_reward_points_price', stripslashes($point_select[$i]));
        }

        $point_text = $_POST['price_points'];
        if (isset($point_text[$i])) {
            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, 'price_points', stripslashes($point_text[$i]));
        }

        $_enable_reward_points_pricing_type = $_POST['_enable_reward_points_pricing_type'];

        if (isset($_enable_reward_points_pricing_type[$i])) {
            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_enable_reward_points_pricing_type', stripslashes($_enable_reward_points_pricing_type[$i]));
        }


        $percent_text_field = $_POST['_reward_percent'];
        if (isset($percent_text_field[$i])) {
            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_reward_percent', stripslashes($percent_text_field[$i]));
        }

        //select
        $new_select = $_POST['_select_reward_rule'];
        if (isset($new_select[$i])) {
            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_select_reward_rule', stripslashes($new_select[$i]));
        }

        $_text_fields = $_POST['_referral_reward_points'];
        if (isset($_text_field[$i])) {
            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_referral_reward_points', stripslashes($_text_fields[$i]));
        }

        $percent_text_fields = $_POST['_referral_reward_percent'];
        if (isset($percent_text_field[$i])) {
            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_referral_reward_percent', stripslashes($percent_text_fields[$i]));
        }

        //select
        $new_selects = $_POST['_select_referral_reward_rule'];
        if (isset($new_select[$i])) {
            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_select_referral_reward_rule', stripslashes($new_selects[$i]));
        }

        // Select
        $_select = $_POST['_enable_reward_points'];
        if (isset($_select[$i])) {
            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_enable_reward_points', stripslashes($_select[$i]));
            RSFunctionForSavingMetaValues::rewardsystem_update_post_meta($variation_id, '_enable_reward_points', stripslashes($_select[$i]));
        }
    }

    public static function rs_validation_for_input_field_in_variable_product($loop, $variation, $id) {
        ?>

        <script type="text/javascript">
            jQuery(document).ready(function () {

                jQuery('.fp_variation_points_price').attr('readonly', 'true');
                jQuery(document).on('change', '.fp_point_price', function () {

                    jQuery('.fp_variation_points_price').attr('readonly', 'true');

                });
                jQuery('#publish').click(function (e) {
                    if (jQuery("select[name='_enable_reward_points_pricing_type[<?php echo $loop ?>]']").val() == '2') {
                        if (jQuery("[name='price_points[<?php echo $loop ?>]']").val() == '') {
                            jQuery("[name='price_points[<?php echo $loop ?>]']").css({
                                "border": "1px solid red",
                                "background": "#FFCECE"
                            });

                            jQuery("[name='price_points[<?php echo $loop ?>]']").show();
                            jQuery("[name='price_points[<?php echo $loop ?>]']").focus();

                            e.preventDefault();
                        }
                    }
                });


            });
            jQuery("select[name='_enable_reward_points_price[<?php echo $loop ?>]']").change(function () {
                if (jQuery("select[name='_enable_reward_points_price[<?php echo $loop ?>]']").val() == '2') {
                    jQuery("select[name='_enable_reward_points_pricing_type[<?php echo $loop ?>]']").parent().hide();
                    jQuery("select[name='_enable_reward_points_price_type[<?php echo $loop ?>]']").parent().hide();
                    jQuery("[name='_price_points_based_on_conversion[<?php echo $loop ?>]']").parent().hide();
                    jQuery("[name='price_points[<?php echo $loop ?>]']").parent().hide();
                    jQuery("[name='variable_regular_price[<?php echo $loop ?>]']").parent().show();
                    jQuery("[name='variable_sale_price[<?php echo $loop ?>]']").parent().show();
                } else {
                    jQuery("select[name='_enable_reward_points_pricing_type[<?php echo $loop ?>]']").parent().show();
                    if (jQuery("select[name='_enable_reward_points_pricing_type[<?php echo $loop ?>]']").val() == '2') {
                        jQuery("[name='price_points[<?php echo $loop ?>]']").parent().show();
                        jQuery("select[name='_enable_reward_points_price_type[<?php echo $loop ?>]']").parent().hide();
                        jQuery("[name='_price_points_based_on_conversion[<?php echo $loop ?>]']").parent().hide();
                        jQuery("[name='variable_regular_price[<?php echo $loop ?>]']").parent().hide();
                        jQuery("[name='variable_sale_price[<?php echo $loop ?>]']").parent().hide();
                    } else {
                        jQuery("select[name='_enable_reward_points_price_type[<?php echo $loop ?>]']").parent().show();
                        jQuery("[name='variable_regular_price[<?php echo $loop ?>]']").parent().show();
                        jQuery("[name='variable_sale_price[<?php echo $loop ?>]']").parent().show();
                        if (jQuery("select[name='_enable_reward_points_price_type[<?php echo $loop ?>]']").val() == '2') {
                            jQuery("[name='_price_points_based_on_conversion[<?php echo $loop ?>]']").parent().show();
                            jQuery("[name='price_points[<?php echo $loop ?>]']").parent().hide();
                        } else {
                            jQuery("[name='price_points[<?php echo $loop ?>]']").parent().show();
                            jQuery("[name='_price_points_based_on_conversion[<?php echo $loop ?>]']").parent().hide();
                        }
                    }

                }
            });

            if (jQuery("select[name='_enable_reward_points_pricing_type[<?php echo $loop ?>]']").val() == '2') {
                jQuery("[name='price_points[<?php echo $loop ?>]']").parent().show();
                jQuery("select[name='_enable_reward_points_price_type[<?php echo $loop ?>]']").parent().hide();
                jQuery("[name='_price_points_based_on_conversion[<?php echo $loop ?>]']").parent().hide();
                jQuery("[name='variable_regular_price[<?php echo $loop ?>]']").parent().hide();
                jQuery("[name='variable_sale_price[<?php echo $loop ?>]']").parent().hide();
            } else {
                jQuery("select[name='_enable_reward_points_price_type[<?php echo $loop ?>]']").parent().show();
                jQuery("[name='variable_regular_price[<?php echo $loop ?>]']").parent().show();
                jQuery("[name='variable_sale_price[<?php echo $loop ?>]']").parent().show();
                if (jQuery("select[name='_enable_reward_points_price_type[<?php echo $loop ?>]']").val() == '2') {
                    jQuery("[name='_price_points_based_on_conversion[<?php echo $loop ?>]']").parent().show();
                    jQuery("[name='price_points[<?php echo $loop ?>]']").parent().hide();
                } else {
                    jQuery("[name='price_points[<?php echo $loop ?>]']").parent().show();
                    jQuery("[name='_price_points_based_on_conversion[<?php echo $loop ?>]']").parent().hide();
                }
            }


            jQuery("select[name='_enable_reward_points_pricing_type[<?php echo $loop ?>]']").change(function () {
                if (jQuery("select[name='_enable_reward_points_pricing_type[<?php echo $loop ?>]']").val() == '2') {
                    jQuery("[name='price_points[<?php echo $loop ?>]']").parent().show();
                    jQuery("select[name='_enable_reward_points_price_type[<?php echo $loop ?>]']").parent().hide();
                    jQuery("[name='_price_points_based_on_conversion[<?php echo $loop ?>]']").parent().hide();
                    jQuery("[name='variable_regular_price[<?php echo $loop ?>]']").parent().hide();
                    jQuery("[name='variable_sale_price[<?php echo $loop ?>]']").parent().hide();
                } else {
                    jQuery("select[name='_enable_reward_points_price_type[<?php echo $loop ?>]']").parent().show();
                    jQuery("[name='variable_regular_price[<?php echo $loop ?>]']").parent().show();
                    jQuery("[name='variable_sale_price[<?php echo $loop ?>]']").parent().show();
                    if (jQuery("select[name='_enable_reward_points_price_type[<?php echo $loop ?>]']").val() == '2') {
                        jQuery("[name='_price_points_based_on_conversion[<?php echo $loop ?>]']").parent().show();
                        jQuery("[name='price_points[<?php echo $loop ?>]']").parent().hide();
                    } else {
                        jQuery("[name='price_points[<?php echo $loop ?>]']").parent().show();
                        jQuery("[name='_price_points_based_on_conversion[<?php echo $loop ?>]']").parent().hide();
                    }
                }
            });
            jQuery("select[name='_enable_reward_points_price_type[<?php echo $loop ?>]']").change(function () {
                if (jQuery("select[name='_enable_reward_points_price_type[<?php echo $loop ?>]']").val() == '2') {
                    jQuery("[name='_price_points_based_on_conversion[<?php echo $loop ?>]']").parent().show();
                    jQuery("[name='price_points[<?php echo $loop ?>]']").parent().hide();
                } else {
                    jQuery("[name='price_points[<?php echo $loop ?>]']").parent().show();
                    jQuery("[name='_price_points_based_on_conversion[<?php echo $loop ?>]']").parent().hide();
                }
            });

            jQuery(function () {
                jQuery('body').on('blur', '#_reward_points[type=text],\n\
                                                   #_reward_percent[type=text],\n\
                                                   #_referral_reward_points[type=text],\n\
                                                   #_referral_reward_percent[type=text]', function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });

                    return this;
                });

                jQuery('body').on('keyup change', '#_reward_points[type=text],\n\
                                                   #_reward_percent[type=text],\n\
                                                   #_referral_reward_points[type=text],\n\
                                           #_referral_reward_percent[type=text]', function () {
                    var value = jQuery(this).val();
                    console.log(woocommerce_admin.i18n_mon_decimal_error);
                    var regex = new RegExp("[^\+1-9\%.\\" + woocommerce_admin.mon_decimal_point + "]+", "gi");
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

    public static function display_msg_for_variable_product() {
        global $post;
        if (get_option('rs_show_hide_message_for_variable_product') == '1') {
            ?>
            <div id='value_variable_product'></div>
            <?php
        }
    }

    public static function add_variation_shortcode_div() {
        return "<span class='variationrewardpoints' style='display:inline-block'></span>";
    }

    public static function add_variation_shortcode() {
        return "<span class='variationpoint_price' style='display:inline-block'></span>";
    }

    public static function display_purchase_msg_for_variable_product() {
        if (is_product() || is_page()) {
            ?>
            <style type="text/css">
                .variableshopmessage {
                    display:none;
                }
            </style>
            <script type='text/javascript'>
                jQuery(document).ready(function () {
                    jQuery('#value_variable_product').hide();
                    jQuery(document).on('change', 'select', function () {
                        var variationid = jQuery('input:hidden[name=variation_id]').val();
                        if (variationid === '' || variationid === undefined) {
                            jQuery('#value_variable_product').hide();
                            jQuery('.variableshopmessage').hide();
                            return false;
                        } else {
                            var dataparam = ({
                                action: 'getvariationid',
                                variationproductid: variationid,
                                userid: "<?php echo get_current_user_id(); ?>",
                            });
                            jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", dataparam, function (response) {
                                if (response !== '') {
            <?php
            $banned_user_list = get_option('rs_banned-users_list');
            if (is_user_logged_in()) {
                $userid = get_current_user_id();
                $banning_type = FPRewardSystem::check_banning_type($userid);
                if ($banning_type != 'earningonly' && $banning_type != 'both') {
                    ?>
                                            var splitresponse = response.split('_');
                                            if (splitresponse[0] > 0) {
                                                jQuery('.variableshopmessage').show();
                                                jQuery('#value_variable_product').addClass('woocommerce-info');
                                                jQuery('#value_variable_product').show();
                                                jQuery('#value_variable_product').html("<?php echo do_shortcode(get_option('rs_message_for_variation_products')); ?>");
                                                jQuery('.variationrewardpoints').html(splitresponse[0]);
                                                jQuery('.variationrewardpointsamount').html(splitresponse[1]);
                                                jQuery('.variationpoint_price').html(splitresponse[2]);
                                            } else {
                                                jQuery('#value_variable_product').hide();
                                                jQuery('.variableshopmessage').hide();
                                            }
                    <?php
                }
            } else {
                ?>
                                        var splitresponse = response.split('_');
                                        if (splitresponse[0] > 0) {
                                            jQuery('.variableshopmessage').show();
                                            jQuery('#value_variable_product').addClass('woocommerce-info');
                                            jQuery('#value_variable_product').show();
                                            jQuery('#value_variable_product').html("<?php echo do_shortcode(get_option('rs_message_for_variation_products')); ?>");
                                            jQuery('.variationrewardpoints').html(splitresponse[0]);
                                            jQuery('.variationrewardpointsamount').html(splitresponse[1]);
                                            jQuery('.variationpoint_price').html(splitresponse[2]);
                                        } else {
                                            jQuery('#value_variable_product').hide();
                                            jQuery('.variableshopmessage').hide();
                                        }
                <?php
            }
            ?>
                                }
                            });
                        }
                    });
                    jQuery(document).on('change', '.wcva_attribute_radio', function (e) {
                        e.preventDefault();
                        var variationid = jQuery('input:hidden[name=variation_id]').val();
                        if (variationid === '' || variationid === undefined) {
                            jQuery('#value_variable_product').hide();
                            jQuery('.variableshopmessage').hide();
                            return false;
                        } else {
                            var dataparam = ({
                                action: 'getvariationid',
                                variationproductid: variationid,
                                userid: "<?php echo get_current_user_id(); ?>",
                            });
                            jQuery.post("<?php echo admin_url('admin-ajax.php');
            ?>", dataparam,
                                    function (response) {
                                        if (response !== '') {
            <?php
            $banned_user_list = get_option('rs_banned-users_list');
            if (is_user_logged_in()) {
                $userid = get_current_user_id();
                $banning_type = FPRewardSystem::check_banning_type($userid);
                if ($banning_type != 'earningonly' && $banning_type != 'both') {
                    ?>
                                                    var splitresponse = response.split('_');
                                                    if (splitresponse[0] > 0) {
                                                        jQuery('.variableshopmessage').show();
                                                        jQuery('#value_variable_product').show();
                                                        jQuery('#value_variable_product').html("<?php echo do_shortcode(get_option('rs_message_for_variation_products')); ?>");
                                                        jQuery('.variationrewardpoints').html(splitresponse[0]);
                                                        jQuery('.variationrewardpointsamount').html(splitresponse[1]);
                                                        jQuery('.variationpoint_price').html(splitresponse[2]);
                                                    } else {
                                                        jQuery('#value_variable_product').hide();
                                                        jQuery('.variableshopmessage').hide();
                                                    }
                    <?php
                }
            } else {
                ?>
                                                var splitresponse = response.split('_');
                                                if (splitresponse[0] > 0) {
                                                    jQuery('.variableshopmessage').show();
                                                    jQuery('#value_variable_product').show();
                                                    jQuery('#value_variable_product').html("<?php echo do_shortcode(get_option('rs_message_for_variation_products')); ?>");
                                                    jQuery('.variationrewardpoints').html(splitresponse[0]);
                                                    jQuery('.variationrewardpointsamount').html(splitresponse[1]);
                                                    jQuery('.variationpoint_price').html(splitresponse[2]);
                                                } else {
                                                    jQuery('#value_variable_product').hide();
                                                    jQuery('.variableshopmessage').hide();
                                                }
                <?php
            }
            ?>
                                        }
                                    });
                        }
                    });
                });</script>
            <?php
        }
    }

    public static function add_variation_point_values_shortcode() {
        if (get_option('woocommerce_currency_pos') == 'right' || get_option('woocommerce_currency_pos') == 'right_space') {
            return "<div class='variationrewardpointsamount' style='display:inline-block'></div>" . get_woocommerce_currency_symbol();
        } elseif (get_option('woocommerce_currency_pos') == 'left' || get_option('woocommerce_currency_pos') == 'left_space') {
            return get_woocommerce_currency_symbol() . "<div class='variationrewardpointsamount' style='display:inline-block'></div>";
        }
    }

    public static function add_shortcode_for_point_price_of_variation() {
        
    }

    public static function add_shortcode_for_rewardpoints_of_variation() {
        if (isset($_POST['variationproductid'])) {
            update_option('variationproductids', $_POST['variationproductid']);
            $checkenable = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['variationproductid'], '_enable_reward_points');
            $checkrule = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['variationproductid'], '_select_reward_rule');
            $getpoints = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['variationproductid'], '_reward_points');
            $getpercent = RSFunctionForSavingMetaValues::rewardsystem_get_post_meta($_POST['variationproductid'], '_reward_percent');
            $global_enable = get_option('rs_global_enable_disable_sumo_reward');
            $global_reward_type = get_option('rs_global_reward_type');
            $rewardpoints = array('0');
            if ($checkenable == '1') {
                if ($checkrule == '1') {

                    $variable_product1 = new WC_Product_Variation($_POST['variationproductid']);
                    $newparentid = $variable_product1->parent->id;
                    if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                        $variationregularprice = $variable_product1->regular_price;
                        if ($variationregularprice == '') {
                            $variationregularprice = $variable_product1->price;
                        }
                    } else {
                        $variationregularprice = $variable_product1->price;
                        if ($variationregularprice == '') {
                            $variationregularprice = $variable_product1->regular_price;
                        }
                    }

                    if ($getpoints == '') {
                        $term = get_the_terms($newparentid, 'product_cat');
                        if (is_array($term)) {
                            $rewardpoints = array('0');
                            foreach ($term as $term) {
                                $enablevalue = get_woocommerce_term_meta($term->term_id, 'enable_reward_system_category', true);
                                $display_type = get_woocommerce_term_meta($term->term_id, 'enable_rs_rule', true);
                                if (($enablevalue == 'yes') && ($enablevalue != '')) {
                                    if ($display_type == '1') {
                                        if (get_woocommerce_term_meta($term->term_id, 'rs_category_points', true) == '') {
                                            if ($global_enable == '1') {
                                                if ($global_reward_type == '1') {
                                                    $rewardpoints[] = get_option('rs_global_reward_points');
                                                } else {
                                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                    ;
                                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                    $getaverage = get_option('rs_global_reward_percent') / 100;
                                                    $getaveragepoints = $getaverage * $variationregularprice;
                                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                                    $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                }
                                            }
                                        } else {
                                            $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'rs_category_points', true);
                                        }
                                    } else {
                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                        ;
                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                        $getaverage = get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) / 100;
                                        $getaveragepoints = $getaverage * $variationregularprice;
                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                        if (get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) == '') {
                                            $global_enable = get_option('rs_global_enable_disable_sumo_reward');
                                            $global_reward_type = get_option('rs_global_reward_type');
                                            if ($global_enable == '1') {
                                                if ($global_reward_type == '1') {
                                                    $rewardpoints[] = get_option('rs_global_reward_points');
                                                } else {
                                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                    ;
                                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                    $getaverage = get_option('rs_global_reward_percent') / 100;
                                                    $getaveragepoints = $getaverage * $variationregularprice;
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
                                            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                            ;
                                            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                            $getaverage = get_option('rs_global_reward_percent') / 100;
                                            $getaveragepoints = $getaverage * $variationregularprice;
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
                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    ;
                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $getaverage = get_option('rs_global_reward_percent') / 100;
                                    $getaveragepoints = $getaverage * $variationregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                    $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                }
                            }
                        }

                        $getpoints = max($rewardpoints);
                    }
                    if ($_POST['userid'] > 0) {
                        $getpoints = RSMemberFunction::user_role_based_reward_points($_POST['userid'], $getpoints);
                    } else {
                        $getpoints = $getpoints;
                    }
                    $redeemingrspoints = $getpoints / wc_format_decimal(get_option('rs_redeem_point'));
                    $updatedredeemingpoints = $redeemingrspoints * wc_format_decimal(get_option('rs_redeem_point_value'));
                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                    echo round($getpoints, $roundofftype) . '_' . round($updatedredeemingpoints, $roundofftype);
                    if (RSFunctionForCart::check_display_price_type($_POST['variationproductid']) == '2') {
                        echo "_" . '2';
                    } else {
                        echo "_" . '0';
                    }
                } else {
                    $getpercent = $getpercent / 100;
                    $variable_product1 = new WC_Product_Variation($_POST['variationproductid']);
                    $newparentid = $variable_product1->parent->id;
                    if (get_option('rs_set_price_to_calculate_rewardpoints_by_percentage') == '1') {
                        $variationregularprice = $variable_product1->regular_price;
                        if ($variationregularprice == '') {
                            $variationregularprice = $variable_product1->price;
                        }
                    } else {
                        $variationregularprice = $variable_product1->price;
                        if ($variationregularprice == '') {
                            $variationregularprice = $variable_product1->regular_price;
                        }
                    }

                    $getpercent = $getpercent * $variationregularprice;
                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                    ;
                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                    $pointswithvalue = $getpercent * $pointconversion;

                    $rsoutput = $pointswithvalue / $pointconversionvalue;
                    if ($getpercent == '') {
                        $term = get_the_terms($newparentid, 'product_cat');
                        if (is_array($term)) {
                            $rewardpoints = array('0');
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
                                                    ;
                                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                    $getaverage = get_option('rs_global_reward_percent') / 100;
                                                    $getaveragepoints = $getaverage * $variationregularprice;
                                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                                    $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                                }
                                            }
                                        } else {
                                            $rewardpoints[] = get_woocommerce_term_meta($term->term_id, 'rs_category_points', true);
                                        }
                                    } else {
                                        $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                        ;
                                        $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                        $getaverage = get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) / 100;
                                        $getaveragepoints = $getaverage * $variationregularprice;
                                        $pointswithvalue = $getaveragepoints * $pointconversion;
                                        if (get_woocommerce_term_meta($term->term_id, 'rs_category_percent', true) == '') {
                                            $global_enable = get_option('rs_global_enable_disable_sumo_reward');
                                            $global_reward_type = get_option('rs_global_reward_type');
                                            if ($global_enable == '1') {
                                                if ($global_reward_type == '1') {
                                                    $rewardpoints[] = get_option('rs_global_reward_points');
                                                } else {
                                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                                    ;
                                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                                    $getaverage = get_option('rs_global_reward_percent') / 100;
                                                    $getaveragepoints = $getaverage * $variationregularprice;
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
                                            $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                            ;
                                            $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                            $getaverage = get_option('rs_global_reward_percent') / 100;
                                            $getaveragepoints = $getaverage * $variationregularprice;
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
                                    $pointconversion = RSFunctionofGeneralTab::earn_point_conversion();
                                    ;
                                    $pointconversionvalue = RSFunctionofGeneralTab::earn_point_conversion_value();
                                    $getaverage = get_option('rs_global_reward_percent') / 100;
                                    $getaveragepoints = $getaverage * $variationregularprice;
                                    $pointswithvalue = $getaveragepoints * $pointconversion;
                                    $rewardpoints[] = $pointswithvalue / $pointconversionvalue;
                                }
                            }
                        }
                        $rsoutput = max($rewardpoints);
                    }

                    if ($_POST['userid'] > 0) {
                        $rsoutput = RSMemberFunction::user_role_based_reward_points($_POST['userid'], $rsoutput);
                    } else {
                        $rsoutput = $rsoutput;
                    }
                    $redeemingrspoints = $rsoutput / wc_format_decimal(get_option('rs_redeem_point'));
                    $updatedredeemingpoints = $redeemingrspoints * wc_format_decimal(get_option('rs_redeem_point_value'));
                    $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
                    echo round($rsoutput, $roundofftype) . '_' . round($updatedredeemingpoints, $roundofftype);
                    if (RSFunctionForCart::check_display_price_type($_POST['variationproductid']) == '2') {
                        echo "_" . '2';
                    } else {
                        echo "_" . '0';
                    }
                }
            } else {
                echo "0_0_";
                if (RSFunctionForCart::check_display_price_type($_POST['variationproductid']) == '2') {
                    echo "_" . '2';
                } else {
                    echo "_" . '0';
                }
            }
        }
        exit();
    }

    public static function rs_function_to_alert_the_variation_limit($variation_limit, $product) {
        foreach ($product->children as $variation_id) {
            foreach ($variation_id as $key => $value) {
                $enable_reward_point = get_post_meta($value, '_enable_reward_points', true);
                if ($enable_reward_point == '1') {
                    $variation_limit = 1000;
                    return $variation_limit;
                }
            }
        }
        return $variation_limit;
    }

}

new RSVariableProduct();
