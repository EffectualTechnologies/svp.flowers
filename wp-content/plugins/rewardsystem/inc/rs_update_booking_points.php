<?php

class RewardSystem_Booking_System {

    public function __construct() {
        add_action('rs_update_points_for_simple', array($this, 'rewardsystem_price_rule_for_order_simple_product'), 100, 2);

        add_action('rs_update_points_for_referral_simple', array($this, 'rewardsystem_price_rule_for_order_referral_simple'), 100, 2);
        add_action('rs_update_points_for_referral_simples', array($this, 'rewardsystem_price_rule_for_order_referral_simple'), 100, 2);

        add_action('rs_delete_points_for_referral_simple', array($this, 'rewardsystem_price_rule_for_deleting_referral_points_simple'), 100, 2);
        add_action('rs_delete_points_for_referral_simples', array($this, 'rewardsystem_price_rule_for_deleting_referral_points_simples'), 100, 2);


        add_action('rs_redo_points_for_referral_simple', array($this, 'rewardsystem_price_rule_for_redo_referral_points_simple'), 100, 2);
        add_action('rs_redo_points_for_referral_simples', array($this, 'rewardsystem_price_rule_for_redo_referral_points_simples'), 100, 2);

        add_action('rs_redo_points_for_simple', array($this, 'rewardsystem_price_rule_for_redo_simple_products'), 100, 2);
    }

    /* Update Points for Simple Product Type on Order Status */

    public static function rewardsystem_price_rule_for_order_simple_product(&$getregularprice, &$item) {
        if (class_exists('WC_Bookings_Cart')) {
            $mainproductdatabooking = get_product($item['product_id']);
            if (is_object($mainproductdatabooking)&&$mainproductdatabooking->is_type('booking')) {
                $getregularprice = $item['line_total'];
            }
        }
    }

    /* Update Points for Referral Points for Simple Products */

    public static function rewardsystem_price_rule_for_order_referral_simple(&$getregularprice, &$item) {
        if (class_exists('WC_Bookings_Cart')) {
            $mainproductdatabooking = get_product($item['product_id']);
            if (is_object($mainproductdatabooking)&&$mainproductdatabooking->is_type('booking')) {
                $getregularprice = $item['line_total'];                
            }
        }
    }

    public static function rewardsystem_price_rule_for_order_referral_simples(&$getregularprices, &$item) {
        if (class_exists('WC_Bookings_Cart')) {
            $mainproductdatabooking = get_product($item['product_id']);
            if (is_object($mainproductdatabooking)&&$mainproductdatabooking->is_type('booking')) {
                $getregularprices = $item['line_total'];
            }
        }
    }

    /* Update Points for Referral Points for Variable Products */


    /* Delete Points for Referral Rewards Simple Product */

    public static function rewardsystem_price_rule_for_deleting_referral_points_simple(&$getregularprice, &$item) {
        if (class_exists('WC_Bookings_Cart')) {
            $mainproductdatabooking = get_product($item['product_id']);
            if (is_object($mainproductdatabooking)&&$mainproductdatabooking->is_type('booking')) {
                $getregularprice = $item['line_total'];
            }
        }
    }

    public static function rewardsystem_price_rule_for_deleting_referral_points_simples(&$getregularprices, &$item) {
        if (class_exists('WC_Bookings_Cart')) {
            $mainproductdatabooking = get_product($item['product_id']);
            if (is_object($mainproductdatabooking)&&$mainproductdatabooking->is_type('booking')) {
                $getregularprices = $item['line_total'];
            }
        }
    }

    public static function rewardsystem_price_rule_for_redo_referral_points_simple(&$getregularprice, &$item) {
        if (class_exists('WC_Bookings_Cart')) {
            $mainproductdatabooking = get_product($item['product_id']);
            if (is_object($mainproductdatabooking)&&$mainproductdatabooking->is_type('booking')) {
                $getregularprice = $item['line_total'];
            }
        }
    }

    public static function rewardsystem_price_rule_for_redo_referral_points_simples(&$getregularprices, &$item) {
        if (class_exists('WC_Bookings_Cart')) {
            $mainproductdatabooking = get_product($item['product_id']);
            if (is_object($mainproductdatabooking)&&$mainproductdatabooking->is_type('booking')) {
                $getregularprices = $item['line_total'];
            }
        }
    }

    public static function rewardsystem_price_rule_for_redo_simple_products(&$getregularprice, &$item) {
        if (class_exists('WC_Bookings_Cart')) {
            $mainproductdatabooking = get_product($item['product_id']);
            if (is_object($mainproductdatabooking)&&$mainproductdatabooking->is_type('booking')) {
                $getregularprice = $item['line_total'];
            }
        }
    }

}

new RewardSystem_Booking_System();
