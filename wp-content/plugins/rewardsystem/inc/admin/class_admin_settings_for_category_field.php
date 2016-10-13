<?php

class RSCategoryField {

    public function __construct() {

        add_action('product_cat_add_form_fields', array($this, 'rs_admin_setting_for_category_page'));

        add_action('product_cat_edit_form_fields', array($this, 'rs_edit_admin_settings_for_category_page'), 10, 2);

        add_action('created_term', array($this, 'rs_save_admin_settings_for_category_page'), 10, 3);

        add_action('edit_term', array($this, 'rs_save_admin_settings_for_category_page'), 10, 3);
        
        add_action('admin_head',array($this,'rs_validation_for_input_field_in_category_page'));
    }

    public static function rs_admin_setting_for_category_page() {
        ?>

<h4><?php _e('Category Settings for Point Price', 'rewardsystem'); ?></h4>
<div class="form-field">
            <label for="enable_point_price_category"><?php _e('Enable Point Price for  Product ', 'rewardsystem'); ?></label>
            <select id="enable_point_price_category" name="enable_point_price_category" class="postform">
                <option value="yes"><?php _e('Enable', 'rewardsystem'); ?></option>
                <option value="no"><?php _e('Disable', 'rewardsystem'); ?></option>
            </select>
            <p><?php
                _e('Category Settings will be considered when Product Settings is Enabled and Values are Empty. '
                        . 'Priority Order is Product Settings, Category Settings and Global Settings in the Same Order. ', 'rewardsystem');
                ?></p>
        </div>

<div class="form-field">
            <label for="point_priceing_category_type"><?php _e(' Pricing Dispaly Type', 'rewardsystem'); ?></label>
            <select id="point_priceing_category_type" name="point_priceing_category_type" class="postform">
                <option value="1"><?php _e('Currency And Points', 'rewardsystem'); ?></option>
                <option value="2"><?php _e('Points Only', 'rewardsystem'); ?></option>
            </select>
            <p><?php
                _e('Category Settings will be considered when Product Settings is Enabled and Values are Empty. '
                        . 'Priority Order is Product Settings, Category Settings and Global Settings in the Same Order. ', 'rewardsystem');
                ?></p>
        </div>



 <div class="form-field">
            <label for="point_price_category_type"><?php _e(' Point Price Type', 'rewardsystem'); ?></label>
            <select id="point_price_category_type" name="point_price_category_type" class="postform">
                <option value="1"><?php _e('By Fixed', 'rewardsystem'); ?></option>
                <option value="2"><?php _e('Based On Conversion', 'rewardsystem'); ?></option>
            </select>
            <p><?php
                _e('Category Settings will be considered when Product Settings is Enabled and Values are Empty. '
                        . 'Priority Order is Product Settings, Category Settings and Global Settings in the Same Order. ', 'rewardsystem');
                ?></p>
        </div>

  <div class="form-field">
            <label for="rs_category_points_price"><?php _e(' By fixed Points Price', 'rewardsystem'); ?></label>
            <input type="text" name="rs_category_points_price" id="rs_category_points_price" value=""/>
            <p><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
        </div>

        <h3><?php _e('Category Settings for Reward Points', 'rewardsystem'); ?></h3>
        <div class="form-field">
            <label for="enable_reward_system_category"><?php _e('Enable SUMO Reward Points for Product Purchase', 'rewardsystem'); ?></label>
            <select id="enable_reward_system_category" name="enable_reward_system_category" class="postform">
                <option value="yes"><?php _e('Enable', 'rewardsystem'); ?></option>
                <option value="no"><?php _e('Disable', 'rewardsystem'); ?></option>
            </select>
            <p><?php
                _e('Category Settings will be considered when Product Settings is Enabled and Values are Empty. '
                        . 'Priority Order is Product Settings, Category Settings and Global Settings in the Same Order. ', 'rewardsystem');
                ?></p>
        </div>
        <div class="form-field">
            <label for="enable_rs_rule"><?php _e('Reward Type', 'rewardsystem'); ?></label>
            <select id="enable_rs_rule" name="enable_rs_rule" class="postform">
                <option value="1"><?php _e('By Fixed Reward Points', 'rewardsystem'); ?></option>
                <option value="2"><?php _e('By Percentage of Product Price', 'rewardsystem'); ?></option>
            </select>
        </div>
        <div class="form-field">
            <label for="rs_category_points"><?php _e('Reward Points', 'rewardsystem'); ?></label>
            <input type="text" name="rs_category_points" id="rs_category_points" value=""/>
            <p><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
        </div>
        <div class="form-field">
            <label for="rs_category_percent"><?php _e('Reward Percent in %', 'rewardsystem'); ?></label>
            <input type="text" name="rs_category_percent" id="rs_category_percent" value=""/>
            <p><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
        </div>
        <div class="form-field">
            <label for="referral_enable_rs_rule"><?php _e('Referral Reward Type', 'rewardsystem'); ?></label>
            <select id="referral_enable_rs_rule" name="referral_enable_rs_rule" class="postform">
                <option value="1"><?php _e('By Fixed Reward Points', 'rewardsystem'); ?></option>
                <option value="2"><?php _e('By Percentage of Product Price', 'rewardsystem'); ?></option>
            </select>
        </div>
        <div class="form-field">
            <label for="referral_rs_category_points"><?php _e('Referral Reward Points', 'rewardsystem'); ?></label>
            <input type="text" name="referral_rs_category_points" id="referral_rs_category_points" value=""/>
            <p><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
        </div>
        <div class="form-field">
            <label for="referral_rs_category_percent"><?php _e('Reward Percent in %', 'rewardsystem'); ?></label>
            <input type="text" name="referral_rs_category_percent" id="referral_rs_category_percent" value=""/>
            <p><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
        </div>

        <div class="form-field">
            <label for="enable_social_reward_system_category"><?php _e('Enable SUMO Reward Points for Social Promotion', 'rewardsystem'); ?></label>
            <select id="enable_social_reward_system_category" name="enable_social_reward_system_category" class="postform">
                <option value="yes"><?php _e('Enable', 'rewardsystem'); ?></option>
                <option value="no"><?php _e('Disable', 'rewardsystem'); ?></option>
            </select>
            <p>
                <?php
                _e('Category Settings will be considered when Product Settings is Enabled and Values are Empty. '
                        . 'Priority Order is Product Settings, Category Settings and Global Settings in the Same Order. ', 'rewardsystem');
                ?>
            </p>
        </div>
        <!-- Social Rewards Field for Facebook in Category Start -->
        <div class="form-field">
            <label for="social_facebook_enable_rs_rule"><?php _e('Facebook Like Reward Type', 'rewardsystem'); ?></label>
            <select id="social_facebook_enable_rs_rule" name="social_facebook_enable_rs_rule" class="postform">
                <option value="1"><?php _e('By Fixed Reward Points', 'rewardsystem'); ?></option>
                <option value="2"><?php _e('By Percentage of Product Price', 'rewardsystem'); ?></option>
            </select>
        </div>
        <div class="form-field">
            <label for="social_facebook_rs_category_points"><?php _e('Facebook Like Reward Points', 'rewardsystem'); ?></label>
            <input type="text" name="social_facebook_rs_category_points" id="social_facebook_rs_category_points" value=""/>
            <p><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
        <div class="form-field">
            <label for="social_facebook_rs_category_percent"><?php _e('Facebook Like Reward Points in Percent %'); ?></label>
            <input type="text" name="social_facebook_rs_category_percent" id="social_facebook_rs_category_percent" value=""/>
            <p><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
        </div>
        <!-- Social Rewards Field for Facebook in Category which is End -->

            <div class="form-field">
            <label for="social_facebook_share_enable_rs_rule"><?php _e('Facebook Share Reward Type', 'rewardsystem'); ?></label>
            <select id="social_facebook_share_enable_rs_rule" name="social_facebook_share_enable_rs_rule" class="postform">
                <option value="1"><?php _e('By Fixed Reward Points', 'rewardsystem'); ?></option>
                <option value="2"><?php _e('By Percentage of Product Price', 'rewardsystem'); ?></option>
            </select>
        </div>
        <div class="form-field">
            <label for="social_facebook_share_rs_category_points"><?php _e('Facebook Share Reward Points', 'rewardsystem'); ?></label>
            <input type="text" name="social_facebook_share_rs_category_points" id="social_facebook_share_rs_category_points" value=""/>
            <p><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
        <div class="form-field">
            <label for="social_facebook_share_rs_category_percent"><?php _e('Facebook Share Reward Points in Percent %'); ?></label>
            <input type="text" name="social_facebook_share_rs_category_percent" id="social_facebook_share_rs_category_percent" value=""/>
            <p><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
        </div>
        <!-- Social Rewards Field for Twitter in Category Start -->
        <div class="form-field">
            <label for="social_twitter_enable_rs_rule"><?php _e('Twitter Tweet Reward Type', 'rewardsystem'); ?></label>
            <select id="social_twitter_enable_rs_rule" name="social_twitter_enable_rs_rule" class="postform">
                <option value="1"><?php _e('By Fixed Reward Points', 'rewardsystem'); ?></option>
                <option value="2"><?php _e('By Percentage of Product Price', 'rewardsystem'); ?></option>
            </select>
        </div>
        <div class="form-field">
            <label for="social_twitter_rs_category_points"><?php _e('Twitter Tweet Reward Points', 'rewardsystem'); ?></label>
            <input type="text" name="social_twitter_rs_category_points" id="social_twitter_rs_category_points" value=""/>
            <p><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
        <div class="form-field">
            <label for="social_twitter_rs_category_percent"><?php _e('Twitter Tweet Reward Percent %'); ?></label>
            <input type="text" name="social_twitter_rs_category_percent" id="social_twitter_rs_category_percent" value=""/>
        </div>
        <!-- Social Rewards Field for Twitter in Category which is End -->

        <!-- Social Rewards Field for Google in Category Start -->
        <div class="form-field">
            <label for="social_google_enable_rs_rule"><?php _e('Google+1 Reward Type', 'rewardsystem'); ?></label>
            <select id="social_google_enable_rs_rule" name="social_google_enable_rs_rule" class="postform">
                <option value="1"><?php _e('By Fixed Reward Points', 'rewardsystem'); ?></option>
                <option value="2"><?php _e('By Percentage of Product Price', 'rewardsystem'); ?></option>
            </select>
        </div>
        <div class="form-field">
            <label for="social_google_rs_category_points"><?php _e('Google+1 Reward Points', 'rewardsystem'); ?></label>
            <input type="text" name="social_google_rs_category_points" id="social_google_rs_category_points" value=""/>
            <p><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
        <div class="form-field">
            <label for="social_google_rs_category_percent"><?php _e('Google+1 Reward Percent %'); ?></label>
            <input type="text" name="social_google_rs_category_percent" id="social_google_rs_category_percent" value=""/>
            <p><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
        <!-- Social Rewards Field for Google in Category which is End -->
        <!-- Social Rewards Field for VK in Category Start -->
        <div class="form-field">
            <label for="social_vk_enable_rs_rule"><?php _e('VK.com Like Reward Type', 'rewardsystem'); ?></label>
            <select id="social_vk_enable_rs_rule" name="social_vk_enable_rs_rule" class="postform">
                <option value="1"><?php _e('By Fixed Reward Points', 'rewardsystem'); ?></option>
                <option value="2"><?php _e('By Percentage of Product Price', 'rewardsystem'); ?></option>
            </select>
        </div>
        <div class="form-field">
            <label for="social_vk_rs_category_points"><?php _e('VK.com Like Reward Points', 'rewardsystem'); ?></label>
            <input type="text" name="social_vk_rs_category_points" id="social_vk_rs_category_points" value=""/>
            <p><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
        <div class="form-field">
            <label for="social_vk_rs_category_percent"><?php _e('VK.com Like Reward Percent %'); ?></label>
            <input type="text" name="social_vk_rs_category_percent" id="social_vk_rs_category_percent" value=""/>
            <p><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
        <!-- Social Rewards Field for VK in Category which is End -->

        <?php
    }

    public static function rs_edit_admin_settings_for_category_page($term, $taxonomy) {

        $enablevalue = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'enable_reward_system_category');
        $enablesocialvalue = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'enable_social_reward_system_category');
       $enablevalueforpoint = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'enable_point_price_category');
       $pointprice = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'rs_category_points_price');
       $pointpricetype= RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'point_price_category_type');
       
        $display_type = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'enable_rs_rule');
        $rewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'rs_category_points');
        $rewardpercent = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'rs_category_percent');
        $referralrewardpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'referral_rs_category_points');
        $referralrewardpercent = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'referral_rs_category_percent');
        $referralrewardrule = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'referral_enable_rs_rule');

        $socialfacebooktype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'social_facebook_enable_rs_rule');
        $socialfacebookpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'social_facebook_rs_category_points');
        $socialfacebookpercent = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'social_facebook_rs_category_percent');

          $socialfacebooktype_share = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'social_facebook_share_enable_rs_rule');
        $socialfacebookpoints_share = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'social_facebook_share_rs_category_points');
        $socialfacebookpercent_share = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'social_facebook_share_rs_category_percent');

        
        
        $socialtwittertype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'social_twitter_enable_rs_rule');
        $socialtwitterpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'social_twitter_rs_category_points');
        $socialtwitterpercent = get_woocommerce_term_meta($term->term_id, 'social_twitter_rs_category_percent', true);

        $socialgoogletype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'social_google_enable_rs_rule');
        $socialgooglepoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'social_google_rs_category_points');
        $socialgooglepercent = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'social_google_rs_category_percent');

        $socialvktype = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'social_vk_enable_rs_rule');
        $socialvkpoints = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'social_vk_rs_category_points');
        $socialvkpercent = RSFunctionForSavingMetaValues::rewardsystem_get_woocommerce_term_meta($term->term_id, 'social_vk_rs_category_percent');
        ?>
         <tr class="form-field">
            <th scope="row" valign="top"><label> <?php _e('Enable Point Purchase for  Product ', 'rewardsystem'); ?></label></th>
            <td>
            <select id="enable_point_price_category" name="enable_point_price_category" class="postform">
                <option value="yes"<?php selected('yes',$enablevalueforpoint); ?>><?php _e('Enable', 'rewardsystem');?></option>
                <option value="no"<?php selected('no',  $enablevalueforpoint); ?>><?php _e('Disable', 'rewardsystem'); ?> </option>
            </select>
            <p><?php
                _e('Category Settings will be considered when Product Settings is Enabled and Values are Empty. '
                        . 'Priority Order is Product Settings, Category Settings and Global Settings in the Same Order. ', 'rewardsystem');
                ?></p>
        </td>
        </tr>
        
       

  <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Point Price Type', 'rewardsystem'); ?></label></th>
            <td>
                <select id="point_price_category_type" name="point_price_category_type" class="postform">
                    <option value="1" <?php selected('1', $pointpricetype); ?>><?php _e('By Fixed', 'rewardsystem'); ?></option>
                    <option value="2" <?php selected('2', $pointpricetype); ?>><?php _e('Based on conversion', 'rewardsystem'); ?></option>
                </select>
                <p class="description"><?php
                _e('Category Settings will be considered when Product Settings is Enabled and Values are Empty. '
                        . 'Priority Order is Product Settings, Category Settings and Global Settings in the Same Order. ', 'rewardsystem');
                ?></p>
            </td>
        </tr>
        

  <tr class="form-field">
               <th scope="row" valign="top"><?php _e(' By Fixed Point', 'rewardsystem'); ?></label></th>
                 <td>
            <input type="text" name="rs_category_points_price" id="rs_category_points_price" value="<?php echo $pointprice; ?>"/>
            <p><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
          </td>
        </tr>
        
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Enable SUMO Reward Points for Product Purchase', 'rewardsystem'); ?></label></th>
            <td>
                <select id="enable_reward_system_category" name="enable_reward_system_category" class="postform">
                    <option value="yes" <?php selected('yes', $enablevalue); ?>><?php _e('Enable', 'rewardsystem'); ?></option>
                    <option value="no" <?php selected('no', $enablevalue); ?>><?php _e('Disable', 'rewardsystem'); ?></option>
                </select>
                <p class="description"><?php
                _e('Category Settings will be considered when Product Settings is Enabled and Values are Empty. '
                        . 'Priority Order is Product Settings, Category Settings and Global Settings in the Same Order. ', 'rewardsystem');
                ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Reward Type', 'rewardsystem'); ?></label></th>
            <td>
                <select id="enable_rs_rule" name="enable_rs_rule" class="postform">
                    <option value="1" <?php selected('1', $display_type); ?>><?php _e('By Fixed Reward Points', 'rewardsystem'); ?></option>
                    <option value="2" <?php selected('2', $display_type); ?>><?php _e('By Percentage of Product Price', 'rewardsystem'); ?></option>
                </select>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Reward Points', 'rewardsystem'); ?></label></th>
            <td>
                <input type="text" name="rs_category_points" id="rs_category_points" value="<?php echo $rewardpoints; ?>"/>
                <p class="description"><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Reward Percent', 'rewardsystem'); ?></label></th>
            <td>
                <input type="text" name="rs_category_percent" id="rs_category_percent" value="<?php echo $rewardpercent; ?>"/>
                <p class="description"><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
            </td>
        </tr>

        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Referral Reward Type', 'rewardsystem'); ?></label></th>
            <td>
                <select id="enable_rs_rule" name="referral_enable_rs_rule" class="postform">
                    <option value="1" <?php selected('1', $referralrewardrule); ?>><?php _e('By Fixed Reward Points', 'rewardsystem'); ?></option>
                    <option value="2" <?php selected('2', $referralrewardrule); ?>><?php _e('By Percentage of Product Price', 'rewardsystem'); ?></option>
                </select>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Referral Reward Points', 'rewardsystem'); ?></label></th>
            <td>
                <input type="text" name="referral_rs_category_points" id="referral_rs_category_points" value="<?php echo $referralrewardpoints; ?>"/>
                <p class="description"><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Referral Reward Percent', 'rewardsystem'); ?></label></th>
            <td>
                <input type="text" name="referral_rs_category_percent" id="referral_rs_category_percent" value="<?php echo $referralrewardpercent; ?>"/>
                <p class="description"><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
            </td>
        </tr>



        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Enable SUMO Reward Points for Social Promotion', 'rewardsystem'); ?></label></th>
            <td>
                <select id="enable_social_reward_system_category" name="enable_social_reward_system_category" class="postform">
                    <option value="yes" <?php selected('yes', $enablesocialvalue); ?>><?php _e('Enable', 'rewardsystem'); ?></option>
                    <option value="no" <?php selected('no', $enablesocialvalue); ?>><?php _e('Disable', 'rewardsystem'); ?></option>
                </select>
                <p class="description"><?php
                _e('Category Settings will be considered when Product Settings is Enabled and Values are Empty. '
                        . 'Priority Order is Product Settings, Category Settings and Global Settings in the Same Order. ', 'rewardsystem');
                ?></p>
            </td>
        </tr>
        <!-- Below Field is for Facebook Social Rewards in Category Level Start-->
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Social Reward Type for Facebook', 'rewardsystem'); ?></label></th>
            <td>
                <select id="social_facebook_enable_rs_rule" name="social_facebook_enable_rs_rule" class="postform">
                    <option value="1" <?php selected('1', $socialfacebooktype); ?>><?php _e('By Fixed Reward Points', 'rewardsystem'); ?></option>
                    <option value="2" <?php selected('2', $socialfacebooktype); ?>><?php _e('By Percentage of Product Price', 'rewardsystem'); ?></option>
                </select>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Social Facebook Reward Points', 'rewardsystem'); ?></label></th>
            <td>
                <input type="text" name="social_facebook_rs_category_points" id="social_facebook_rs_category_points" value="<?php echo $socialfacebookpoints; ?>"/>
                <p class="description"><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Social Facebook Reward in Percent %', 'rewardsystem'); ?></label></th>
            <td>
                <input type="text" name="social_facebook_rs_category_percent" id="social_facebook_rs_category_percent" value="<?php echo $socialfacebookpercent; ?>"/>
                <p class="description"><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
            </td>
        </tr>
        <!-- Below Field is for Facebook Social Rewards in Category Level Ends -->
<tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Social Reward Type for Facebook Share', 'rewardsystem'); ?></label></th>
            <td>
                <select id="social_facebook_share_enable_rs_rule" name="social_facebook_share_enable_rs_rule" class="postform">
                    <option value="1" <?php selected('1', $socialfacebooktype_share); ?>><?php _e('By Fixed Reward Points', 'rewardsystem'); ?></option>
                    <option value="2" <?php selected('2', $socialfacebooktype_share); ?>><?php _e('By Percentage of Product Price', 'rewardsystem'); ?></option>
                </select>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Social Facebook  Share Reward Points', 'rewardsystem'); ?></label></th>
            <td>
                <input type="text" name="social_facebook_share_rs_category_points" id="social_facebook_share_rs_category_points" value="<?php echo $socialfacebookpoints_share; ?>"/>
                <p class="description"><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Social Facebook Share Reward in Percent %', 'rewardsystem'); ?></label></th>
            <td>
                <input type="text" name="social_facebook_share_rs_category_percent" id="social_facebook_share_rs_category_percent" value="<?php echo $socialfacebookpercent_share; ?>"/>
                <p class="description"><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
            </td>
        </tr>
        <!-- Below Field is for Twitter Social Rewards in Category Level Start-->
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Social Reward Type for Twitter', 'rewardsystem'); ?></label></th>
            <td>
                <select id="social_twitter_enable_rs_rule" name="social_twitter_enable_rs_rule" class="postform">
                    <option value="1" <?php selected('1', $socialtwittertype); ?>><?php _e('By Fixed Reward Points', 'rewardsystem'); ?></option>
                    <option value="2" <?php selected('2', $socialtwittertype); ?>><?php _e('By Percentage of Product Price', 'rewardsystem'); ?></option>
                </select>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Social Twitter Reward Points', 'rewardsystem'); ?></label></th>
            <td>
                <input type="text" name="social_twitter_rs_category_points" id="social_twitter_rs_category_points" value="<?php echo $socialtwitterpoints; ?>"/>
                <p class="description"><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Social Twitter Reward in Percent %', 'rewardsystem'); ?></label></th>
            <td>
                <input type="text" name="social_twitter_rs_category_percent" id="social_twitter_rs_category_percent" value="<?php echo $socialtwitterpercent; ?>"/>
                <p class="description"><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
            </td>
        </tr>
        <!-- Below Field is for Twitter Social Rewards in Category Level Ends -->

        <!-- Below Field is for Google Social Rewards in Category Level Start-->
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Social Reward Type for Google', 'rewardsystem'); ?></label></th>
            <td>
                <select id="social_google_enable_rs_rule" name="social_google_enable_rs_rule" class="postform">
                    <option value="1" <?php selected('1', $socialgoogletype); ?>><?php _e('By Fixed Reward Points', 'rewardsystem'); ?></option>
                    <option value="2" <?php selected('2', $socialgoogletype); ?>><?php _e('By Percentage of Product Price', 'rewardsystem'); ?></option>
                </select>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Social Google Reward Points', 'rewardsystem'); ?></label></th>
            <td>
                <input type="text" name="social_google_rs_category_points" id="social_twitter_rs_category_points" value="<?php echo $socialgooglepoints; ?>"/>
                <p class="description"><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Social Google Reward in Percent %', 'rewardsystem'); ?></label></th>
            <td>
                <input type="text" name="social_google_rs_category_percent" id="social_google_rs_category_percent" value="<?php echo $socialgooglepercent; ?>"/>
                <p class="description"><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
            </td>
        </tr>
        <!-- Below Field is for Google Social Rewards in Category Level Ends -->

        <!-- Below Field is for VK Social Rewards in Category Level Start-->
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Social Reward Type for VK', 'rewardsystem'); ?></label></th>
            <td>
                <select id="social_vk_enable_rs_rule" name="social_vk_enable_rs_rule" class="postform">
                    <option value="1" <?php selected('1', $socialvktype); ?>><?php _e('By Fixed Reward Points', 'rewardsystem'); ?></option>
                    <option value="2" <?php selected('2', $socialvktype); ?>><?php _e('By Percentage of Product Price', 'rewardsystem'); ?></option>
                </select>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Social VK Reward Points', 'rewardsystem'); ?></label></th>
            <td>
                <input type="text" name="social_vk_rs_category_points" id="social_vk_rs_category_points" value="<?php echo $socialvkpoints; ?>"/>
                <p class="description"><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
            </td>
        </tr>
        <tr class="form-field">
            <th scope="row" valign="top"><label><?php _e('Social VK Reward in Percent %', 'rewardsystem'); ?></label></th>
            <td>
                <input type="text" name="social_vk_rs_category_percent" id="social_vk_rs_category_percent" value="<?php echo $socialvkpercent; ?>"/>
                <p class="description"><?php _e('When left empty, Product and Global Settings will be considered in the same order and Current Settings (Category Settings) will be ignored. When value greater than or equal to 0 is entered then Current Settings (Category Settings) will be considered and Product/Global Settings will be ignored','rewardsystem')
               ?></p>
            </td>
        </tr>
        <!-- Below Field is for VK Social Rewards in Category Level Ends -->


        <?php
    }

    public static function rs_save_admin_settings_for_category_page($term_id, $tt_id, $taxonomy) {
        if (isset($_POST['enable_rs_rule']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'enable_rs_rule', $_POST['enable_rs_rule']);
        
   
           if (isset($_POST['enable_point_price_category']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'enable_point_price_category', $_POST['enable_point_price_category']);
  
           if (isset($_POST['rs_category_points_price']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'rs_category_points_price', $_POST['rs_category_points_price']);
        
        if (isset($_POST['point_price_category_type']))
          RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'point_price_category_type',$_POST['point_price_category_type']);
         
        
        
        if (isset($_POST['referral_enable_rs_rule']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'referral_enable_rs_rule', $_POST['referral_enable_rs_rule']);

        if (isset($_POST['enable_reward_system_category']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'enable_reward_system_category', $_POST['enable_reward_system_category']);

        if (isset($_POST['rs_category_points']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'rs_category_points', $_POST['rs_category_points']);
        if (isset($_POST['rs_category_percent']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'rs_category_percent', $_POST['rs_category_percent']);

        if (isset($_POST['referral_rs_category_points']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'referral_rs_category_points', $_POST['referral_rs_category_points']);
        if (isset($_POST['referral_rs_category_percent']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'referral_rs_category_percent', $_POST['referral_rs_category_percent']);

//social updation for facebook,twitter,google
        if (isset($_POST['enable_social_reward_system_category']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'enable_social_reward_system_category', $_POST['enable_social_reward_system_category']);

        /* Facebook Rule and its Points Start */
        if (isset($_POST['social_facebook_enable_rs_rule']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'social_facebook_enable_rs_rule', $_POST['social_facebook_enable_rs_rule']);
        if (isset($_POST['social_facebook_rs_category_points']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'social_facebook_rs_category_points', $_POST['social_facebook_rs_category_points']);
        if (isset($_POST['social_facebook_rs_category_percent']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'social_facebook_rs_category_percent', $_POST['social_facebook_rs_category_percent']);
        
        /* Facebook Rule and its Points End */
            if (isset($_POST['social_facebook_share_enable_rs_rule']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'social_facebook_share_enable_rs_rule', $_POST['social_facebook_share_enable_rs_rule']);
        if (isset($_POST['social_facebook_share_rs_category_points']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'social_facebook_share_rs_category_points', $_POST['social_facebook_share_rs_category_points']);
        if (isset($_POST['social_facebook_share_rs_category_percent']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'social_facebook_share_rs_category_percent', $_POST['social_facebook_share_rs_category_percent']);
        
        /* Twitter Rule and Its Points updation Start */
        if (isset($_POST['social_twitter_enable_rs_rule']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'social_twitter_enable_rs_rule', $_POST['social_twitter_enable_rs_rule']);
        if (isset($_POST['social_twitter_rs_category_points']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'social_twitter_rs_category_points', $_POST['social_twitter_rs_category_points']);
        if (isset($_POST['social_twitter_rs_category_percent']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'social_twitter_rs_category_percent', $_POST['social_twitter_rs_category_percent']);
        /* Twitter Rule and Its Points Updation End */


        /* Google Rule and Its Points updation Start */
        if (isset($_POST['social_google_enable_rs_rule']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'social_google_enable_rs_rule', $_POST['social_google_enable_rs_rule']);
        if (isset($_POST['social_google_rs_category_points']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'social_google_rs_category_points', $_POST['social_google_rs_category_points']);
        if (isset($_POST['social_google_rs_category_percent']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'social_google_rs_category_percent', $_POST['social_google_rs_category_percent']);
        /* Google Rule and Its Points Updation End */

        /* VK Rule and Its Points updation Start */
        if (isset($_POST['social_vk_enable_rs_rule']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'social_vk_enable_rs_rule', $_POST['social_vk_enable_rs_rule']);
        if (isset($_POST['social_vk_rs_category_points']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'social_vk_rs_category_points', $_POST['social_vk_rs_category_points']);
        if (isset($_POST['social_vk_rs_category_percent']))
            RSFunctionForSavingMetaValues::rewardsystem_update_woocommerce_term_meta($term_id, 'social_vk_rs_category_percent', $_POST['social_vk_rs_category_percent']);
        /* VK Rule and Its Points Updation End */


        delete_transient('wc_term_counts');
    }
    
    public static function rs_validation_for_input_field_in_category_page() {
        ?>

        <script type="text/javascript">
            jQuery(function () {
                jQuery('body').on('blur', '#rs_category_points[type=text],\n\
                                           #rs_category_percent[type=text],\n\
                                           #referral_rs_category_points[type=text],\n\
                                           #referral_rs_category_percent[type=text],\n\
                                           #social_facebook_rs_category_points[type=text],\n\
                                           #social_facebook_rs_category_percent[type=text],\n\
                                           #social_twitter_rs_category_points[type=text],\n\
                                           #social_twitter_rs_category_percent[type=text],\n\
                                           #social_google_rs_category_points[type=text],\n\
                                           #social_google_rs_category_percent[type=text],\n\
                                           #social_vk_rs_category_points[type=text],\n\
                                           #social_vk_rs_category_percent[type=text]', function () {
                    jQuery('.wc_error_tip').fadeOut('100', function () {
                        jQuery(this).remove();
                    });

                    return this;
                });

                jQuery('body').on('keyup change', '#rs_category_points[type=text],\n\
                                           #rs_category_percent[type=text],\n\
                                           #referral_rs_category_points[type=text],\n\
                                           #referral_rs_category_percent[type=text],\n\
                                           #social_facebook_rs_category_points[type=text],\n\
                                           #social_facebook_rs_category_percent[type=text],\n\
                                           #social_twitter_rs_category_points[type=text],\n\
                                           #social_twitter_rs_category_percent[type=text],\n\
                                           #social_google_rs_category_points[type=text],\n\
                                           #social_google_rs_category_percent[type=text],\n\
                                           #social_vk_rs_category_points[type=text],\n\
                                           #social_vk_rs_category_percent[type=text]', function () {
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

new RSCategoryField();
