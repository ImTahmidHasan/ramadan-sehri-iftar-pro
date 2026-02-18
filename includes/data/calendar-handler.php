<?php
if (!defined('ABSPATH')) exit;

class RSIP_Data_Handler {
    public static function get_today_data($district) {
        $calendar = get_option("rsip_calendar_{$district}");
        $today = date('Y-m-d');
        
        // Check if manual data exists and has entries
        if (!empty($calendar) && is_array($calendar)) {
            foreach ($calendar as $index => $day) {
                if ($day['date'] === $today) {
                    $day['ramadan_day'] = $index + 1;
                    return $day;
                }
            }
        }
        
        // If no manual data for today, FORCE API fallback
        return RSIP_API::fetch_times($district);
    }

    public static function get_full_calendar($district) {
        return get_option("rsip_calendar_{$district}", []);
    }
}