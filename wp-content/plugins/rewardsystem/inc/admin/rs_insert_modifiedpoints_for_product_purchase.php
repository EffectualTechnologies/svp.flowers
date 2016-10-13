<?php
$getnomineeidinmyaccount = get_user_meta(get_current_user_id(),'rs_selected_nominee',true);
$getnomineeidincheckout = get_post_meta($order_id,'rs_selected_nominee_in_checkout',true);
        if(($getnomineeidinmyaccount == '') && ($getnomineeidincheckout == '')){        
            switch ($level) {
                case '1':
                    if ($productlevelrewardtype == '1') {                    
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){
                                if($enabledisablemaxpoints == 'yes'){                                  
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        if($getoldpoints <= $restrictuserpoints){                                            
                                            $totalpointss = $getoldpoints + $productlevelrewardpoints;
                                            if($totalpointss <= $restrictuserpoints){
                                                $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                                self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed, $date,'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                        self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed,  $date,'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                        
                                    }
                                }else{
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                    self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed,  $date,'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                    
                                }                                
                            }
                        }else{
                            if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $productlevelrewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                                self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed,  $date,'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                        self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed,  $date,'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                        
                                    }
                                }else{
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                    self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed, $date, 'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                                                        
                                }                                                            
                        }                    
                    } else {                    
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){                                
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $productlevelrewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                                self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed,  $date,'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                        self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed, $date, 'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                    }
                                }else{
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                    self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed, $date, 'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                    
                                }                                
                            }
                        }else{                            
                            if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $productlevelrewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                                self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed, $date, 'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                        self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed, $date, 'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                        
                                    }
                                }else{
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                    self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed, $date, 'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                    
                                }                            
                        }                    
                    }
                    break;
                case '2':
                    if ($categorylevelrewardtype == '1') {
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $categorylevelrewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                                self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed, $date, 'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                        self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed,  $date,'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                        
                                    }
                                }else{
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                    self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed, $date, 'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                    
                                }                                
                            }
                        }else{ 
                            
                            if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $categorylevelrewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                                self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed,  $date,'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                        self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed,  $date,'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                        
                                    }
                                }else{
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                    self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed, $date, 'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                    
                                }                            
                        }                    
                    } else {
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $categorylevelrewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                                self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed,  $date,'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                        self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed,  $date,'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                        
                                    }
                                }else{
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                    self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed, $date, 'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                    
                                }                               
                            }
                        }else{                            
                            if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $categorylevelrewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                                self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed, $date, 'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                        self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed,  $date,'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                        
                                    }
                                }else{
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                    self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed, $date, 'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                    
                                }
                        }
                    }
                    break;
                case '3':
                    if ($global_reward_type == '1') {
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){                                
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $global_rewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                                self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                        self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed,  $date,'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                        
                                    }
                                }else{
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                    self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed,  $date,'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                    
                                }                                
                            }
                        }else{                       
                            if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $global_rewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                                self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed,  $date,'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                        self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                        
                                    }
                                }else{
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                    self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                    
                                }
                        }                    
                    } else {
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){                                
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $global_rewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                                self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                        self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed,  $date,'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                        
                                    }
                                }else{
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                    self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                    
                                }                                
                            }
                        }else{
                           if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $global_rewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                                self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                        self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                        self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                        
                                    }
                                }else{
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                    self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed,  $date,'PPRP',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                    self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRP',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,'','0');                        
                                    
                                }
                        }                    
                    }
                    break;
            }
        }elseif(($getnomineeidinmyaccount != '') && ($getnomineeidincheckout != '')){
            $nomineeid = $orderuserid;
            $orderuserid = $getnomineeidincheckout;
            $pointsredeemed = 0;            
            switch ($level) {
                case '1':
                    if ($productlevelrewardtype == '1') {                    
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $productlevelrewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                                self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpointss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                        self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpointss);                        
                                        
                                    }
                                }else{
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                    self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpointss);                        
                                    
                                }                                
                            }
                        }else{
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $productlevelrewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                                self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpointss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                        self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpointss);                        
                                        
                                    }
                                }else{
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                    self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpointss);                        
                                    
                                }
                        }                    
                    } else {                    
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){ 
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $productlevelrewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                                self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpercentss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                        self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpercentss);                        
                                        
                                    }
                                }else{
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                    self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpercentss);                        
                                    
                                }                                
                            }
                        }else{
                           if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $productlevelrewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                                self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpercentss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                        self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpercentss);                        
                                        
                                    }
                                }else{
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                    self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpercentss);                        
                                    
                                }
                        }                    
                    }
                    break;
                case '2':
                    if ($categorylevelrewardtype == '1') {
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $categorylevelrewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                                self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpointss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                        self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpointss);                        
                                        
                                    }
                                }else{
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                    self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpointss);                        
                                    
                                }                                
                            }
                        }else{                       
                            if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $categorylevelrewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                                self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpointss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                        self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpointss);                        
                                        
                                    }
                                }else{
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                    self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpointss);                        
                                    
                                }
                        }                    
                    } else {
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $categorylevelrewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                                self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpercents);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                        self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpercents);                        
                                        
                                    }
                                }else{
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                    self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpercents);                        
                                    
                                }                                
                            }
                        }else{
                            if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $categorylevelrewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                                self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpercents);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                        self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpercents);                        
                                        
                                    }
                                }else{
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                    self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpercents);                        
                                    
                                }
                        }
                    }
                    break;
                case '3':
                    if ($global_reward_type == '1') {
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $global_rewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                                self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpointss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                        self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpointss);                        
                                        
                                    }
                                }else{
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                    self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpointss);                        
                                    
                                }                                                        
                            }
                        }else{                       
                           if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $global_rewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                                self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpointss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                        self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpointss);                        
                                        
                                    }
                                }else{
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                    self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpointss);                        
                                    
                                }
                        }                    
                    } else {
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){
                                
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $global_rewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                                self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpercents);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                        self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpercents);                        
                                        
                                    }
                                }else{
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                    self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpercents);                        
                                    
                                }                                
                            }
                        }else{
                           if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $global_rewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                                self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpercents);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                        self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpercents);                        
                                        
                                    }
                                }else{
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                    self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpercents);                        
                                    
                                }
                        }                    
                    }
                    break;
            }            
        }elseif(($getnomineeidinmyaccount != '') && ($getnomineeidincheckout == '')){
            $nomineeid = $orderuserid;
            $orderuserid = $getnomineeidinmyaccount;
            $pointsredeemed = 0;            
            switch ($level) {
                case '1':
                    if ($productlevelrewardtype == '1') {                    
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $productlevelrewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                                self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpointss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                        self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpointss);                        
                                        
                                    }
                                }else{
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                    self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpointss);                        
                                    
                                }                                
                            }
                        }else{
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $productlevelrewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                                self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpointss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                        self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpointss);                        
                                        
                                    }
                                }else{
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                    self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpointss);                        
                                    
                                }
                        }                    
                    } else {                    
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){ 
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $productlevelrewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                                self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpercentss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                        self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpercentss);                        
                                        
                                    }
                                }else{
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                    self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpercentss);                        
                                    
                                }                                
                            }
                        }else{
                           if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $productlevelrewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                                self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpercentss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                        self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpercentss);                        
                                        
                                    }
                                }else{
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                    self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpercentss);                        
                                    
                                }
                        }                    
                    }
                    break;
                case '2':
                    if ($categorylevelrewardtype == '1') {
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $categorylevelrewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                                self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpointss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                        self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpointss);                        
                                        
                                    }
                                }else{
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                    self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpointss);                        
                                    
                                }                                
                            }
                        }else{                       
                            if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $categorylevelrewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                                self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpointss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                        self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpointss);                        
                                        
                                    }
                                }else{
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                    self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpointss);                        
                                    
                                }
                        }                    
                    } else {
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $categorylevelrewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                                self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpercents);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                        self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpercents);                        
                                        
                                    }
                                }else{
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                    self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpercents);                        
                                    
                                }                                
                            }
                        }else{
                            if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $categorylevelrewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                                self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpercents);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                        self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpercents);                        
                                        
                                    }
                                }else{
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                    self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpercents);                        
                                    
                                }
                        }
                    }
                    break;
                case '3':
                    if ($global_reward_type == '1') {
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $global_rewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                                self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpointss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                        self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpointss);                        
                                        
                                    }
                                }else{
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                    self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpointss);                        
                                    
                                }                                                        
                            }
                        }else{                       
                           if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $global_rewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                                self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpointss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                        self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpointss);                        
                                        
                                    }
                                }else{
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                    self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpointss);                        
                                    
                                }
                        }                    
                    } else {
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){
                                
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $global_rewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                                self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpercents);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                        self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpercents);                        
                                        
                                    }
                                }else{
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                    self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpercents);                        
                                    
                                }                                
                            }
                        }else{
                           if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $global_rewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                                self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpercents);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                        self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpercents);                        
                                        
                                    }
                                }else{
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                    self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpercents);                        
                                    
                                }
                        }                    
                    }
                    break;
            }
        }elseif(($getnomineeidinmyaccount == '') && ($getnomineeidincheckout != '')){
            $nomineeid = $orderuserid;
            $orderuserid = $getnomineeidincheckout;
            $pointsredeemed = 0;            
            switch ($level) {
                case '1':
                    if ($productlevelrewardtype == '1') {                    
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $productlevelrewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                                self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpointss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                        self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpointss);                        
                                        
                                    }
                                }else{
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                    self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpointss);                        
                                    
                                }                                
                            }
                        }else{
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $productlevelrewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                                self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpointss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                        self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpointss);                        
                                        
                                    }
                                }else{
                                    $productlevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpoints);                    
                                    self::insert_earning_points($orderuserid, $productlevelrewardpointss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $productlevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpointss);                        
                                    
                                }
                        }                    
                    } else {                    
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){ 
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $productlevelrewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                                self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpercentss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                        self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpercentss);                        
                                        
                                    }
                                }else{
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                    self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpercentss);                        
                                    
                                }                                
                            }
                        }else{
                           if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $productlevelrewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                                self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpercentss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                        self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpercentss);                        
                                        
                                    }
                                }else{
                                    $productlevelrewardpercentss = RSMemberFunction::user_role_based_reward_points($orderuserid, $productlevelrewardpercent);                    
                                    self::insert_earning_points($orderuserid, $productlevelrewardpercentss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($productlevelrewardpercentss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $productlevelrewardpercentss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$productlevelrewardpercentss);                        
                                    
                                }
                        }                    
                    }
                    break;
                case '2':
                    if ($categorylevelrewardtype == '1') {
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $categorylevelrewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                                self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpointss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                        self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpointss);                        
                                        
                                    }
                                }else{
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                    self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpointss);                        
                                    
                                }                                
                            }
                        }else{                       
                            if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $categorylevelrewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                                self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpointss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                        self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpointss);                        
                                        
                                    }
                                }else{
                                    $categorylevelrewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpoints);                    
                                    self::insert_earning_points($orderuserid, $categorylevelrewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $categorylevelrewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpointss);                        
                                    
                                }
                        }                    
                    } else {
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $categorylevelrewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                                self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpercents);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                        self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpercents);                        
                                        
                                    }
                                }else{
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                    self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpercents);                        
                                    
                                }                                
                            }
                        }else{
                            if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $categorylevelrewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                                self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpercents);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                        self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed,  $date,'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpercents);                        
                                        
                                    }
                                }else{
                                    $categorylevelrewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $categorylevelrewardpercent);                    
                                    self::insert_earning_points($orderuserid, $categorylevelrewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($categorylevelrewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $categorylevelrewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$categorylevelrewardpercents);                        
                                    
                                }
                        }
                    }
                    break;
                case '3':
                    if ($global_reward_type == '1') {
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $global_rewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                                self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpointss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                        self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpointss);                        
                                        
                                    }
                                }else{
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                    self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpointss);                        
                                    
                                }                                                        
                            }
                        }else{                       
                           if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $global_rewardpoints;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                                self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpointss);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                        self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpointss);                        
                                        
                                    }
                                }else{
                                    $global_rewardpointss = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpoints);                    
                                    self::insert_earning_points($orderuserid, $global_rewardpointss,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpointss);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $global_rewardpointss,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpointss);                        
                                    
                                }
                        }                    
                    } else {
                        if($enableoption == 'yes'){
                            if($checkredeeming == false){
                                
                                if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $global_rewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                                self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpercents);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                        self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpercents);                        
                                        
                                    }
                                }else{
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                    self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpercents);                        
                                    
                                }                                
                            }
                        }else{
                           if($enabledisablemaxpoints == 'yes'){
                                    if(($restrictuserpoints != '') && ($restrictuserpoints != '0')){
                                        $getoldpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                            
                                        if($getoldpoints <= $restrictuserpoints){
                                            $totalpointss = $getoldpoints + $global_rewardpercent;                                            
                                            if($totalpointss <= $restrictuserpoints){
                                                $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                                self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                                self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                                $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                                self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpercents);                        
                                                
                                            }else{
                                                $insertpoints = $restrictuserpoints - $getoldpoints;
                                                RSPointExpiry::insert_earning_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$order_id,$totalearnedpoints,$totalredeempoints);
                                                $equearnamt = RSPointExpiry::earning_conversion_settings($insertpoints);
                                                $equredeemamt = RSPointExpiry::redeeming_conversion_settings($pointsredeemed);                                                
                                                $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                                RSPointExpiry::record_the_points($orderuserid, $insertpoints,$pointsredeemed, $date,'MREPFU',$equearnamt,$equredeemamt,$order_id,$productid,'0','0','',$totalpoints,'','0'); 
                                                
                                            }
                                        }else{
                                            RSPointExpiry::insert_earning_points($orderuserid,'0','0', $date,'MREPFU',$order_id,'0','0');
                                            $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);
                                            RSPointExpiry::record_the_points($orderuserid, '0','0', $date,'MREPFU','0','0',$order_id,'0','0','0','',$totalpoints,'','0');                    
                                        }
                                    }else{
                                        $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                        self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                        $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                        $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                        self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                        $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                        self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpercents);                        
                                        
                                    }
                                }else{
                                    $global_rewardpercents = RSMemberFunction::user_role_based_reward_points($orderuserid, $global_rewardpercent);                    
                                    self::insert_earning_points($orderuserid, $global_rewardpercents,$pointsredeemed, $date, 'PPRPFN',$order_id,$totalearnedpoints,$totalredeempoints);
                                    $equearnamt = self::earning_conversion_settings($global_rewardpercents);
                                    $totalpoints = RSPointExpiry::get_sum_of_total_earned_points($orderuserid);                                
                                    self::record_the_points($orderuserid, $global_rewardpercents,'0', $date,'PPRPFN',$equearnamt,$equredeemamt,$order_id,$productid,$variationid,$refuserid,'',$totalpoints,$nomineeid,'0');                        
                                    $totalpointss = RSPointExpiry::get_sum_of_total_earned_points($nomineeid);
                                    self::record_the_points($nomineeid, '0','0', $date,'PPRPFNP','0','0','0','0','0','','',$totalpointss,$orderuserid,$global_rewardpercents);                        
                                    
                                }
                        }                    
                    }
                    break;
            }            
        }