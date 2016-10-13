<?php

class RSFunctionForStatus {
   
    public function __construct() {
        
        add_action('admin_head', array($this, 'rs_select_status'));
        
    }
    
    public static function rs_select_status() {
        global $woocommerce;
        if (isset($_GET['tab'])) {
            if ($_GET['tab'] == 'rewardsystem_status') {
                if ((float) $woocommerce->version <= (float) ('2.2.0')) { 
                   echo RSJQueryFunction::rs_common_chosen_function('#rs_order_status_control');
                    echo RSJQueryFunction::rs_common_chosen_function('#rs_order_status_control_redeem');
                } else {
                   echo RSJQueryFunction::rs_common_select_function('#rs_order_status_control');
                   echo RSJQueryFunction::rs_common_select_function('#rs_order_status_control_redeem');
                }
            }
        }
    }
}

new RSFunctionForStatus();
