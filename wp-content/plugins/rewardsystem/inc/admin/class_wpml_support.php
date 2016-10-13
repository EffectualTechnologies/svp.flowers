<?php

class RSWPMLSupport {

    function __construct() {
        add_action('wp_head',array($this,'register_user_lang'));
        add_action('admin_init',array($this,'rs_register_template_for_wpml'));
    }

    function register_user_lang() {
        $user_id = get_current_user_id();
        $meta_key = 'rs_wpml_lang';
        if(function_exists('icl_register_string')){
        $currentuser_lang = isset($_SESSION['wpml_globalcart_language']) ? $_SESSION['wpml_globalcart_language'] : ICL_LANGUAGE_CODE;
        }else{
        $currentuser_lang = 'en';    
        }
        $meta_value = $currentuser_lang;
        update_user_meta( $user_id, $meta_key, $meta_value);
        
    }
    
    // registering mail templates strings
    function rs_register_template_for_wpml(){

    if(function_exists('icl_register_string')){     
    
    global $wpdb;
    $context = 'SUMO';
    $template_table = $wpdb->prefix.'rs_templates_email';
    $re = $wpdb->get_results("SELECT * FROM $template_table");
    foreach($re as $each_template){
        $name_msg = 'rs_template_'.$each_template->id.'_message';
        $value_msg = $each_template->message;
        icl_register_string($context, $name_msg, $value_msg);//for registering message
        $name_sub = 'rs_template_'.$each_template->id.'_subject';
        $value_sub = $each_template->subject;
        icl_register_string($context, $name_sub, $value_sub);//for registering subject
        
        
    }
    }
}
    // getting the registered strings from wpml table
    public static function fp_rs_get_wpml_text($option_name,$language,$message){
    if(function_exists('icl_register_string')){
        if($language == 'en'){
            return $message;
        }else{
    global $wpdb;
    $context = 'SUMO';

        $res = $wpdb->get_results($wpdb->prepare("
            SELECT s.name, s.value, t.value AS translation_value, t.status
            FROM  {$wpdb->prefix}icl_strings s
            LEFT JOIN {$wpdb->prefix}icl_string_translations t ON s.id = t.string_id
            WHERE s.context = %s
                AND (t.language = %s OR t.language IS NULL)
            ", $context, $language), ARRAY_A);
            foreach($res as $each_entry){
                if($each_entry['name'] == $option_name){
                    if($each_entry['status'] == '1'){
                        $translated = $each_entry['translation_value'];
                    }  else {
                        $translated = $each_entry['value'];    
                    }
                }
            }
            return $translated;
        }
           
    }else{
        return $message;
    }
}

}
new RSWPMLSupport();
?>