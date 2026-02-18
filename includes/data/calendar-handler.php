<?php
if (!defined('ABSPATH')) exit;

class RSIP_Data_Handler {
    public static function get_today_data($district) {
        $calendar = get_option("rsip_calendar_{$district}");
        $today = date('Y-m-d');
        
        if ($calendar) {
            foreach ($calendar as $index => $day) {
                if ($day['date'] === $today) {
                    $day['ramadan_day'] = $index + 1;
                    return $day;
                }
            }
        }
        
        // Fallback to Aladhan API
        return RSIP_API::fetch_times($district);
    }

    public static function get_full_calendar($district) {
        return get_option("rsip_calendar_{$district}", []);
    }
}