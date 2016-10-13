<?php

class RS_Referral_Log {

    public function __construct() {

    }

    public static function get_corresponding_users_log($referrer) {
        $sample_data = get_option('rs_referral_log');
        $alluserslog = $sample_data[$referrer];
        return $alluserslog;
    }

    public static function get_count_of_corresponding_users($referrer) {
        $getdatas = get_option('rs_referral_log');

        @$getcounts = $getdatas[$referrer];        
        if (is_array($getcounts)) {
            return count($getcounts);
        } else {
            return "0";
        }
    }

    public static function get_totals_of_referral_persons($referrer) {
        $getdatas = get_option('rs_referral_log');
        @$gettotals = $getdatas[$referrer];
        if (is_array($gettotals)) {
            foreach ($gettotals as $values) {
                @$maintotals += $values;
            }
        }
        return @$maintotals;
    }

    public static function main_referral_log_function($referrer, $referral, $points, $sample_data) {


        (int) $checkreferral = self::check_is_referral_referrer($referrer, $referral, $sample_data);
        update_option("testingfu", $checkreferral);
        $main_array = array();        
        if ((int) $checkreferral == 0) {
            $newdata = array($referrer => array($referral => $points));
            if (is_array($sample_data)) {
                if (!empty($sample_data)) {
                    foreach ($sample_data as $key => $value) {
                        arsort($newdata);
                        $newdata[$key] = $value;
                    }
                }
            }

            update_option('rs_referral_log', $newdata);


            // Newly Inserting Datas
        } elseif ((int) $checkreferral == 1) {

            // Parent Key with Referral Person also there

            $mainarray = array();
            foreach ($sample_data as $key => $value) {
                foreach ($value as $subkey => $eachvalue) {
                    if ($subkey == $referral) {
                        $previousdata = $points + $eachvalue;
                        $mainarray[$key][$subkey] = $previousdata;
                    } else {
                        $mainarray[$key][$subkey] = $eachvalue;
                    }
                }
            }
            update_option('testering', $mainarray);
            update_option('rs_referral_log', $mainarray);
        } else {
            if ((int) $checkreferral == 2) {
                // Parent Key Found but Referral Person is not available
                $main_array = array();
                $subarray = array();
                $subdatas = array($referral => $points);
                foreach ($sample_data as $key => $value) {
                    foreach ($value as $subkey => $eachvalue) {
                        $subdatas[$subkey] = $eachvalue;
                        arsort($subdatas);
                    }

                    $main_array[$key] = $subdatas;
                    arsort($main_array);
                }
                update_option('rs_referral_log', $main_array);
            }
        }
    }

    public static function check_is_referral_referrer($referrer, $referral, $sample_data) {
        $listofkeys = array();
        $sublistofkeys = array();
        if (is_array($sample_data)) {
            if (!empty($sample_data)) {
                foreach ($sample_data as $key => $value) {
                    $listofkeys[] = $key;
                    foreach ($value as $eachkey => $value) {
                        $sublistofkeys[] = $eachkey;
                    }
                }

                if (in_array($referrer, array_filter($listofkeys))) {
                    if (in_array($referral, array_filter($sublistofkeys))) {
                        return "1";  // Parent with Child also found
                    } else {
                        return "2"; // Parent is found but subchild is not found
                    }
                } else {
                    return "0"; // None of them found
                }
            } else {
                return "0"; // None of them found
            }
        }
    }

}

new RS_Referral_Log();
