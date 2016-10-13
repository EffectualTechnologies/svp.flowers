<?php

class RSFunctionForReferralReward {
    
    public function __construct() {
        
        add_action('woocommerce_admin_field_display_referral_reward_log', array($this, 'rs_list_referral_rewards_log'));
        
    }
    
    public static function rs_list_referral_rewards_log() {
        ?>
        <style type="text/css">
            p.submit {
                display:none;
            }
            #mainforms {
                display:none;
            }
        </style>
        <?php
        if ((!isset($_GET['view']))) {
            $newwp_list_table_for_users = new WP_List_Table_for_Referral_Table();
            $newwp_list_table_for_users->prepare_items();
            $newwp_list_table_for_users->search_box('Search Users', 'search_id');
            $newwp_list_table_for_users->display();
        } else {

            $newwp_list_table_for_users = new WP_List_Table_for_View_Referral_Table();
            $newwp_list_table_for_users->prepare_items();
            $newwp_list_table_for_users->search_box('Search', 'search_id');
            $newwp_list_table_for_users->display();
            ?>


            <a href="<?php echo remove_query_arg(array('view'), get_permalink()); ?>">Go Back</a>
            <?php
        }
    }
}

new RSFunctionForReferralReward();
