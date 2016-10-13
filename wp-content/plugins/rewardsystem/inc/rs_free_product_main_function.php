<?php

class FPRewardSystem_Free_Product {

    public function __construct() {
        
        add_action('wp_head', array($this, 'fp_main_function_add_to_cart'));
        
        add_action('woocommerce_checkout_update_order_meta', array($this, 'save_data_to_order'));
        
        add_filter('woocommerce_order_item_name', array($this, 'show_item_id_after_checkout'), 10, 2);
        
        add_action('woocommerce_before_calculate_totals', array($this, 'alter_free_product_price'));
        
        add_filter('woocommerce_cart_item_name', array($this, 'show_message_next_to_free_product'), 10, 3);
        
        add_action('woocommerce_after_cart_table', array($this, 'display_free_product_after_cart_table'));
        
        add_action('wp_ajax_delete_meta_current_key', array('RSMemberFunction', 'delete_saved_product_key_callback'));
        
        add_filter('woocommerce_cart_item_quantity', array($this, 'alter_quantity_in_free_product'), 10, 2);
        
            $order_status_control = array('cancelled','failed','refunded'); 
            foreach ($order_status_control as $order_status) {
               $orderstatuslist = array('pending ' ,'processing' , 'on-hold' ,'completed');
                if (is_array($orderstatuslist)) {
                    foreach ($orderstatuslist as $value) {
                        add_action('woocommerce_order_status_' . $value . '_to_' . $order_status, array($this, 'free_product_add_once'));
                    }
                }
            }
            $order_status_control = array('pending ' ,'processing' , 'on-hold' ,'completed'); 
            foreach ($order_status_control as $order_status) {
               $orderstatuslist =array('cancelled','failed','refunded');
                if (is_array($orderstatuslist)) {
                    foreach ($orderstatuslist as $value) {
                        add_action('woocommerce_order_status_' . $value . '_to_' . $order_status, array($this, 'free_product_add_once_complete_order'));
                    }
                }
            }    
        
       
        
    }
 public static function free_product_add_once_complete_order($order_id){
    $order = new WC_Order($order_id);  
    $order_user_id=$order->user_id;
    $get_datas = get_option('rewards_dynamic_rule');
    foreach($get_datas as $value){                    
           foreach ($order->get_items() as $item_id => $eachitem) {
            $productid = $eachitem['variation_id'] != '0' ? $eachitem['variation_id'] : $eachitem['product_id'];           
            $getproductid = get_post_meta($order_user_id, 'product_id_for_free_prduct');
            if (in_array($productid, (array)  $value['product_list'])) {
                 
                        $getproductid = get_post_meta($order_user_id, 'product_id_for_free_prduct',true);
                        if (($key = array_search($productid, $getproductid)) !== false) {
                           
                             update_post_meta($order_user_id, 'product_id_for_free_prduct', $getproductid);
                        }else{
                            $arraymerge = array_merge($getproductid, (array) $productid);
                            update_post_meta($order_user_id, 'product_id_for_free_prduct', $arraymerge);
                        }
                    
                
            }
        }
    }    
     
 }
    
public static function free_product_add_once($order_id){
    $order = new WC_Order($order_id);  
    $order_user_id=$order->user_id;
    $get_datas = get_option('rewards_dynamic_rule');
    foreach($get_datas as $value){                    
           foreach ($order->get_items() as $item_id => $eachitem) {
            $productid = $eachitem['variation_id'] != '0' ? $eachitem['variation_id'] : $eachitem['product_id'];           
            $getproductid = get_post_meta($order_user_id, 'product_id_for_free_prduct');
            if (in_array($productid, (array)  $value['product_list'])) {
                 
                        $getproductid = get_post_meta($order_user_id, 'product_id_for_free_prduct',true);
                        if (($key = array_search($productid, $getproductid)) !== false) {
                            unset($getproductid[$key]);
                             update_post_meta($order_user_id, 'product_id_for_free_prduct', $getproductid);
                        }
               
            }
        }
    }
       
}
    public static function fp_get_free_product_level_id($total_earned_points) {
        if (!is_admin()) {
            if (is_user_logged_in()) {
                global $woocommerce;
                $each_member_level = RSMemberFunction::multi_dimensional_sort(get_option('rewards_dynamic_rule'), 'rewardpoints');                
                if ($each_member_level != "") {
                    foreach ($each_member_level as $key => $value) {                      
                        $current_user_total_earned_points = $total_earned_points;                        
                        $current_level_earning_points_limit = $value["rewardpoints"];                        
                        if ($current_level_earning_points_limit >= $current_user_total_earned_points) {                                                        
                            return $key;
                        }
                    }
                }
            }
        }
    }

    public static function fp_get_corresponding_product($dynamicruleid, $total_earned_points) {
        global $woocommerce;
      
        $get_datas = get_option('rewards_dynamic_rule');
        if (isset($dynamicruleid)) {
            if ((float) $woocommerce->version <= (float) ('2.2.0')) {
                $free_products_list = $get_datas[$dynamicruleid]['product_list'];

                return $free_products_list;
            } else {
                if(!empty($get_datas[$dynamicruleid]['product_list'])){
                $free_products_list = $get_datas[$dynamicruleid]['product_list']['0'];
       
                $free_products_list = explode(',', $free_products_list);
             
                return $free_products_list;
            }
            }
        }
    }

    public static function fp_check_if_free_product_is_in_cart($productid, $dynamicruleid, $total_earned_points) {
        global $woocommerce;
        $productids = array();
        $soloproductids = array();
        foreach ($woocommerce->cart->cart_contents as $key => $values) {
            $getids = $values['variation_id'] != '' ? $values['variation_id'] : $values['product_id'];
 
            $productids[] = $getids;
            $getfreeproductids = self::fp_get_corresponding_product($dynamicruleid, $total_earned_points);
           
            if (!in_array($getids, (array) $getfreeproductids)) {
                $soloproductids[] = $getids;
               
            }
        }
        if (in_array($productid, (array) $productids)) {
            if (count($soloproductids) > 1) {
                return "true"; //Found
            } else {         
                return "true";
            }
        } else {
            return "false"; //Not Found
        }
    }

    public static function fp_add_free_product_to_cart($product_id, $quantity, $variation_id = '', $variation = '') {
        global $woocommerce;        
        $woocommerce->cart->add_to_cart($product_id, $quantity, $variation_id, $variation);
    }

    public static function fp_main_function_add_to_cart() {  
       
    global $woocommerce;  
        $getcountofpriceproduct = array();
         $get_product_for_user=array();
        $getcount = array();
        $listofcartitemkeys = array();
        $userid = get_current_user_id();
        $total_earned_points = RSPointExpiry::get_sum_of_total_earned_points($userid);        
        $dynamicruleid = self::fp_get_free_product_level_id($total_earned_points);        
        $getcorrespondingproducts = self::fp_get_corresponding_product($dynamicruleid, $total_earned_points); 
          
         
        if (get_option('rs_enable_earned_level_based_reward_points') == 'yes') {            
            if (!empty($getcorrespondingproducts)) {
                if (is_array($getcorrespondingproducts)) {
                    foreach ($getcorrespondingproducts as $eachproduct) {
                     $order_user_id=  get_current_user_id();
                      $get_product_for_user=array();
                       $get_product_for_user=get_post_meta($order_user_id,'product_id_for_free_prduct',true);       
                       
                       if(!in_array($eachproduct,(array)$get_product_for_user)){
                        if($eachproduct != ''){                            
                        $combinedproductids[] = $eachproduct;                         
                        $cartremovedlist = get_user_meta(get_current_user_id(), 'listsetofids', true);
                        $product_object = new WC_Product($eachproduct);
                         
                        if ($product_object->post->post_parent > 0) {
                            $parent_productid = $product_object->post->post_parent;                            
                            $variation_object = new WC_Product_Variation($eachproduct);
                            $variations = wc_get_formatted_variation($variation_object->get_variation_attributes(), true);
                            $variation_explode = explode(',', $variations);
                            $quantity = 1;                           
                            $main_variations = array();
                            foreach ($variation_explode as $eachvariation) {
                                $explode2 = explode(': ', $eachvariation);                               
                                foreach ($explode2 as $eachexplode => $eachvalue) {
                                    $main_variations[$explode2[0]] = $explode2[1];
                                }
                            }                            
                            $variations = $main_variations;
                            $getcurrentcartids = $woocommerce->cart->generate_cart_id($parent_productid, $eachproduct, $variations);  
                            $listofcartitemkeys[] = $getcurrentcartids;
                        } else {
                            $parent_productid = $eachproduct;
                            $variations = array();
                            $quantity = 1;                            
                            $getcurrentcartids = $woocommerce->cart->generate_cart_id($parent_productid, $eachproduct, $variations);
                            $listofcartitemkeys[] = $getcurrentcartids;
                        }                        
                        $getcartcount = $woocommerce->cart->cart_contents_count;                       
                        $cartcontent = $woocommerce->cart->cart_contents;
                        foreach($cartcontent as $key=>$val){                         
                            $productprice = $val['line_subtotal'];
                            $priceproduct[]= $val['line_subtotal'];
                            if($productprice > 0){
                                $getcountofpriceproduct[] = count($productprice);                            
                            }else{
                                $getcount[] = count($productprice);
                            }
                        }                          
                        $cartcount = array_sum($getcountofpriceproduct);                         
                        $getcounts = array_sum($getcount);                           
                        $found_or_not = self::fp_check_if_free_product_is_in_cart($eachproduct, $dynamicruleid, $total_earned_points);   
                     
                        if (!in_array($getcurrentcartids, (array) $cartremovedlist)) {
                            if(($cartcount > 0)){
                                if ($found_or_not == 'false') {                                
                                    $getcurrentcartids = $woocommerce->cart->generate_cart_id($eachproduct);
                                    $cartremovedlist = get_user_meta(get_current_user_id(), 'listsetofids', true);
                                    if(($getcartcount > 0)){                
                                            self::fp_add_free_product_to_cart($parent_productid, $quantity, $eachproduct, $variations);
                                    }                                
                                    WC()->session->set('setruleids', $dynamicruleid);
                                    WC()->session->set('excludedummyids', $dynamicruleid);
                                    WC()->session->set('dynamicruleproducts', $getcorrespondingproducts);
                                }
                            }else{
                                if($getcounts == 1){                                    
                                    $woocommerce->cart->remove_cart_item($getcurrentcartids);
                                }else{
                                    foreach($listofcartitemkeys as $listofcartitemkey){
                                        
                                    if (!in_array($listofcartitemkey, (array) $cartremovedlist)) {                                                                                     
                                         $woocommerce->cart->remove_cart_item($listofcartitemkey);   
                                    }                                        
                                    }
                                }
                                
                            }                            
                        }   
                        }
                    } 
                    }
                    
                    WC()->session->set('freeproductcartitemkeys', $listofcartitemkeys);   
                }
            }
        }
    }
    

    public static function save_data_to_order($orderid) {
        global $woocommerce;
        $getsavedsession = WC()->session->get('setruleids');        
        $listofcartitemkeys = WC()->session->get("freeproductcartitemkeys");
        $getfreeproductmsg = WC()->session->get('freeproductmsg');
        update_post_meta($orderid, 'listruleids', $getsavedsession);
        $getalldatas = get_option('rewards_dynamic_rule');
        $order = new WC_Order($orderid);
        $order_user_id=$order->user_id;      
         if (!empty($getalldatas)) {
            if ($getsavedsession != NULL) {
                update_post_meta($orderid, 'ruleidsdata', $getalldatas[$getsavedsession]);
                $order = new WC_Order($orderid);
                foreach ($order->get_items() as $item_id => $eachitem) {
                    $productid = $eachitem['variation_id'] != '0' ? $eachitem['variation_id'] : $eachitem['product_id'];
                    $getlistofproducts = self::fp_get_corresponding_product($getsavedsession, '');
                    if (in_array($productid, (array) $getlistofproducts)) {
                                          
                        $getproductid = (array) get_post_meta($order_user_id, 'product_id_for_free_prduct',true);
                        if ($getproductid == '') {
                            update_post_meta($order_user_id, 'product_id_for_free_prduct', $productid);
                        } else {
                            $arraymerge = array_merge($getproductid, (array) $productid);
                            update_post_meta($order_user_id, 'product_id_for_free_prduct', $arraymerge);
                        } 
                        if (get_option('rs_enable_earned_level_based_reward_points') == 'yes') {
                            wc_add_order_item_meta($item_id, '_ruleidsdata', $getalldatas[$getsavedsession]);
                            wc_add_order_item_meta($item_id, '_rsfreeproductmsg', $getfreeproductmsg);
                        }
                    }
                }
                WC()->session->__unset('setruleids');
                WC()->session->__unset('freeproductcartitemkeys');
                WC()->session->__unset('freeproductmsg');
            }
        }          
    }

    public static function alter_free_product_price($cart_object) {
       
        if (get_option('rs_enable_earned_level_based_reward_points') == 'yes') {
            $getsessiondata = WC()->session->get('setruleids');
            (array) $getcorrespondingproducts = self::fp_get_corresponding_product($getsessiondata, '');
            foreach ($cart_object->cart_contents as $key => $value) {
                $productid = $value['variation_id'] != '' ? $value['variation_id'] : $value['product_id'];
                if (in_array($productid, (array) $getcorrespondingproducts)) {
                    $order_user_id=  get_current_user_id();
                       $get_product_for_user=get_post_meta($order_user_id,'product_id_for_free_prduct',true);                   
                       if(!in_array($productid,(array)$get_product_for_user)){
                    $value['data']->price = '0';
                     }
                }
            }
        }
    }

    public static function display_free_product_after_cart_table() {
        global $woocommerce;
          $get_product_for_user=array();
        ?>
        <style type="text/css">
            .fp_rs_display_free_product h3 {
                display:none;
            }
        </style>
        <?php
        echo "<div class='fp_rs_display_free_product'>";
        echo "<h3>";
        echo get_option('rs_free_product_msg_caption');
        echo "</h3>";
        $getsessiondata = WC()->session->get('excludedummyids');        
        if ($getsessiondata != NULL) {
            (array) $getproductlists = self::fp_get_corresponding_product($getsessiondata, '');
            if (!empty($getproductlists)) {
                if (is_array($getproductlists)) {
                    foreach ($getproductlists as $eachproduct) {
                         $order_user_id=  get_current_user_id();
                       $get_product_for_user=get_post_meta($order_user_id,'product_id_for_free_prduct',true);
                     if(!in_array($eachproduct,(array)$get_product_for_user)){
                        if($eachproduct != ''){
                        $cartitemkey = $woocommerce->cart->generate_cart_id($eachproduct);
                        $product_object = new WC_Product($eachproduct);
                        if ($product_object->post->post_parent > 0) {
                            $parent_productid = $product_object->post->post_parent;                            
                            $variation_object = new WC_Product_Variation($eachproduct);
                            $variations = wc_get_formatted_variation($variation_object->get_variation_attributes(), true);
                            $variation_explode = explode(',', $variations);
                            $quantity = 1;                            
                            $main_variations = array();
                            foreach ($variation_explode as $eachvariation) {
                                $explode2 = explode(': ', $eachvariation);                                
                                foreach ($explode2 as $eachexplode => $eachvalue) {
                                    $main_variations[$explode2[0]] = $explode2[1];
                                }
                            }                            
                            $variations = $main_variations;
                            $cartitemkey = $woocommerce->cart->generate_cart_id($parent_productid, $eachproduct, $variations);
                        }else {
                            $parent_productid = $eachproduct;
                            $variations = array();
                            $quantity = 1;                            
                            $cartitemkey = $woocommerce->cart->generate_cart_id($parent_productid, $eachproduct, $variations);
                        }

                        $listofdeletedkeys = get_user_meta(get_current_user_id(), 'listsetofids', true);
                        if (in_array($cartitemkey, (array) $listofdeletedkeys)) {
                            $getthetitle = get_the_title($eachproduct);
                            ?>
                            <style type="text/css">
                                .fp_rs_display_free_product h3 {
                                    display:block;
                                }
                            </style>
                            <a href="javascript:void(0)" class="add_removed_free_product_to_cart" data-cartkey="<?php echo $cartitemkey; ?>"><?php echo $getthetitle; ?></a><br/>
                            <?php
                        }
                        }
                    }
                }
            }
            }
        }
        echo "</div>";
        ?>
        <script type="text/javascript">
            jQuery(document).ready(function () {
                jQuery('.add_removed_free_product_to_cart').click(function () {
                    var removed_key = jQuery(this).attr('data-cartkey');
                    var current_user_id = '<?php echo get_current_user_id(); ?>';
                    var removed_key_param = {
                        action: "delete_meta_current_key",
                        key_to_remove: removed_key,
                        current_user_id: current_user_id,
                    };
                    jQuery.post("<?php echo admin_url('admin-ajax.php'); ?>", removed_key_param, function (response) {
                        var newresponse = response.replace(/\s/g, '');
                        if (newresponse === '1') {                            
                            location.reload();
                        }
                        console.log('Success');
                    });
                    return false;
                });
                return false;
            });
        </script>
        <?php
    }

    public static function show_message_next_to_free_product($product_name, $cart_item, $cart_item_key) {
        $productid = $cart_item['variation_id'] != '' ? $cart_item['variation_id'] : $cart_item['product_id'];
        $sessiondata = WC()->session->get('setruleids');
        (array) $getproductfromrule = self::fp_get_corresponding_product($sessiondata, '');
        $user_id = get_current_user_id();
        $total_earned_points = RSPointExpiry::get_sum_of_total_earned_points($user_id);
        $free_product_message_to_find = "[current_level_points]";
        $free_product_message_to_replace = $total_earned_points;
        $roundofftype = get_option('rs_round_off_type') == '1' ? '2' : '0';
        $roundvalueuserpoint = round($free_product_message_to_replace,$roundofftype);
        $free_product_message_replaced = str_replace($free_product_message_to_find, $roundvalueuserpoint, get_option('rs_free_product_message_info'));
        if (in_array($productid, (array) $getproductfromrule)) {
             $order_user_id=  get_current_user_id();
                       $get_product_for_user=get_post_meta($order_user_id,'product_id_for_free_prduct',true);                   
                       if(!in_array($productid,(array)$get_product_for_user)){
            if (get_option('rs_remove_msg_from_cart_order') == 'yes') {
                if (get_option('rs_enable_earned_level_based_reward_points') == 'yes') {
                    WC()->session->set('freeproductmsg', $free_product_message_replaced);
                    return $product_name . "<br>" . $free_product_message_replaced;
                } else {
                    return $product_name;
                }
           }
            } else {
                return $product_name;
            }
        } else {
            return $product_name;
        }
    }

    public static function alter_quantity_in_free_product($productquantity, $values) {        
        (array) $getcartitemkeys = WC()->session->get('freeproductcartitemkeys') == NULL ? array() : WC()->session->get('freeproductcartitemkeys');
       
        if (is_array($getcartitemkeys)) {
           
            if (in_array($values, (array) $getcartitemkeys)) {                
                if (get_option('rs_enable_earned_level_based_reward_points') == 'yes') {                    
                    echo sprintf('1 <input type="hidden" name="cart[%s][qty]" value="1" />', $values);
                    return ;
                } else {
                    return $productquantity;
                }
            } else {
                
                return $productquantity;
            }
        }else{
             
            return $productquantity;
        }
    }


    public static function show_item_id_after_checkout($item_name, $item) {        
        @$freeproductmsg = $item['rsfreeproductmsg'];
        if ($freeproductmsg != NULL && $freeproductmsg != '') {
            if (get_option('rs_remove_msg_from_cart_order') == 'yes') {
                return $item_name . "<br>" . $freeproductmsg;
            } else {
                return $item_name;
            }
        } else {
            return $item_name;
        }
    }

}

new FPRewardSystem_Free_Product();
