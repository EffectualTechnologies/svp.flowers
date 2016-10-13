<?php

class FPRewardSystemMenus {

    public function __construct() {
        add_action('admin_menu', array($this, 'reward_system_as_a_submenu_of_woocommerce')); // Register Reward System Menu in a Admin Settings of WooCommerce
    }

    /*
     * Function to add Sumo Reward Points as Sub Menu of Woocommerce
     */

    public static function reward_system_as_a_submenu_of_woocommerce() {
        global $my_admin_page;
        $name = get_option('rs_brand_name');
        if ($name == '') {
            $name = 'Sumo Reward Points';
        }
        $my_admin_page = add_submenu_page('woocommerce', $name, $name, 'manage_woocommerce', 'rewardsystem_callback', array('FPRewardSystemMenus', 'reward_system_admin_tab_settings'));
    }

    /*
     * Function to Set General Tab as Default Tab and to Save and Reset the data's in all tab
     */

    public static function reward_system_admin_tab_settings() {
        global $woocommerce, $woocommerce_settings, $current_section, $current_tab;
        do_action('woocommerce_rs_settings_start');
        $current_tab = ( empty($_GET['tab']) ) ? 'rewardsystem_general' : sanitize_text_field(urldecode($_GET['tab']));
        $current_section = ( empty($_REQUEST['section']) ) ? '' : sanitize_text_field(urldecode($_REQUEST['section']));

        if (!empty($_POST['save'])) {
            if (empty($_REQUEST['_wpnonce']) || !wp_verify_nonce($_REQUEST['_wpnonce'], 'woocommerce-settings'))
                die(__('Action failed. Please refresh the page and retry.', 'rewardsystem'));

            if (!$current_section) {
                switch ($current_tab) {
                    default :
                        if (isset($woocommerce_settings[$current_tab]))
                            woocommerce_update_options($woocommerce_settings[$current_tab]);

// Trigger action for tab
                        do_action('woocommerce_update_options_' . $current_tab);
                        break;
                }

                do_action('woocommerce_update_options');
            } else {
// Save section onlys
                do_action('woocommerce_update_options_' . $current_tab . '_' . $current_section);
            }

// Clear any unwanted data
            delete_transient('woocommerce_cache_excluded_uris');
// Redirect back to the settings page
            $redirect = add_query_arg(array('saved' => 'true'));

            if (isset($_POST['subtab'])) {
                wp_safe_redirect($redirect);
                exit;
            }
        }
// Get any returned messages
        $error = ( empty($_GET['wc_error']) ) ? '' : urldecode(stripslashes($_GET['wc_error']));
        $message = ( empty($_GET['wc_message']) ) ? '' : urldecode(stripslashes($_GET['wc_message']));

        if ($error || $message) {

            if ($error) {
                echo '<div id="message" class="error fade"><p><strong>' . esc_html($error) . '</strong></p></div>';
            } else {
                echo '<div id="message" class="updated fade"><p><strong>' . esc_html($message) . '</strong></p></div>';
            }
        } elseif (!empty($_GET['saved'])) {

            echo '<div id="message" class="updated fade"><p><strong>' . __('Your settings have been saved.', 'rewardsystem') . '</strong></p></div>';
        }
        ?>
        <div class="wrap woocommerce">
            <form method="post" id="mainform" action="" enctype="multipart/form-data">
                <div class="icon32 icon32-woocommerce-settings" id="icon-woocommerce"><br /></div><h2 class="nav-tab-wrapper woo-nav-tab-wrapper">
                    <?php
                    $tabs = '';
                    $tabs = apply_filters('woocommerce_rs_settings_tabs_array', $tabs);

                    foreach ($tabs as $name => $label) {
                        // echo $current_tab;
                        echo '<a href="' . admin_url('admin.php?page=rewardsystem_callback&tab=' . $name) . '" class="nav-tab ';
                        if ($current_tab == $name)
                            echo 'nav-tab-active';
                        echo '">' . $label . '</a>';
                    }
                    do_action('woocommerce_rs_settings_tabs');
                    ?>
                </h2>

                <?php
                switch ($current_tab) :
                    default :
                        do_action('woocommerce_rs_settings_tabs_' . $current_tab);
                        break;
                endswitch;
                ?>
                <p class="submit">
                    <?php if (!isset($GLOBALS['hide_save_button'])) : ?>
                        <input name="save" class="button-primary" type="submit" value="<?php _e('Save changes', 'woocommerce'); ?>" />
                    <?php endif; ?>
                    <input type="hidden" name="subtab" id="last_tab" />
                    <?php wp_nonce_field('woocommerce-settings', '_wpnonce', true, true); ?>
                </p>
            </form>
            <form method="post" id="mainforms" action="" enctype="multipart/form-data" style="float: left; margin-top: -52px; margin-left: 159px;">
                <input name="reset" class="button-secondary" type="submit" value="<?php _e('Reset All', 'woocommerce'); ?>"/>
                <?php wp_nonce_field('woocommerce-reset_settings', '_wpnonce', true, true); ?>
                <?php
                ?>
            </form>
        </div>
        <?php
    }

}

new FPRewardSystemMenus();
