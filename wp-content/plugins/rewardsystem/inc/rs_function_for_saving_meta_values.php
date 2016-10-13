<?php

class RSFunctionForSavingMetaValues {    
    
    /*
     * @ Reward Points points update product meta function
     * 
     */
    
    public static function  rewardsystem_update_post_meta($post_id,$meta_name,$value_to_update){
        $updated_post_meta_value = update_post_meta($post_id,$meta_name,$value_to_update);
        return $updated_post_meta_value;
    }
    
    /*
     * @ Reward Points points get product meta function
     * 
     */
    
    public static function  rewardsystem_get_post_meta($post_id,$meta_name){
        $get_post_meta_value = get_post_meta($post_id,$meta_name,true);
        return $get_post_meta_value;
    }
    
    
    /*
     * @ Reward Points points update user meta function
     * 
     */
    
    
    public static function rewardsystem_update_user_meta($user_id,$meta_name,$value_to_update){
        $updated_user_meta_value  = update_user_meta($user_id,$meta_name,$value_to_update);
        return $updated_user_meta_value;
    }
    
    /*
     * @ Reward Points points get user meta function
     * 
     */
    
    public static function  rewardsystem_get_user_meta($user_id,$meta_name){
        $get_user_meta_value = get_user_meta($user_id,$meta_name,true);
        return $get_user_meta_value;
    }
    
    /*
     * @ Reward Points points update woocommerce term meta function
     * 
     */
    
    public static function  rewardsystem_update_woocommerce_term_meta($post_id,$meta_name,$value_to_update){
        $updated_woocommerce_term_meta_value  = update_woocommerce_term_meta($post_id,$meta_name,$value_to_update);
        return $updated_woocommerce_term_meta_value;
    }
    
    /*
     * @ Reward Points points get woocommerce term meta function
     * 
     */
    
    public static function  rewardsystem_get_woocommerce_term_meta($post_id,$meta_name){
        $get_woocommerce_term_meta_value  = get_woocommerce_term_meta($post_id,$meta_name,true);
        return $get_woocommerce_term_meta_value;
    }
    
    /*
     * @ Reward Points points get term meta function
     * 
     */
    
    public static function  rewardsystem_get_the_term($post_id,$meta_name){
        $get_term_meta_value  = get_the_terms($post_id,$meta_name,true);
        return $get_term_meta_value;
    }
    
    
}
new RSFunctionForSavingMetaValues();