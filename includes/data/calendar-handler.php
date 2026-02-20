<?php
if (!defined('ABSPATH')) exit;

class RSIP_Data_Handler {
    public static function get_today_data($district) {
        $calendar = get_option("rsip_calendar_{$district}");
        $today = date('Y-m-d');
        
        if (!empty($calendar) && is_array($calendar)) {
            foreach ($calendar as $index => $day) {
                if (isset($day['date']) && trim($day['date']) === $today) {
                    return array_merge($day, ['ramadan_day' => $index + 1, 'source' => 'Official']);
                }
            }
        }
        
        $api = RSIP_API::fetch_times($district);
        return ($api) ? array_merge($api, ['source' => 'API']) : self::emergency_fallback();
    }

    private static function emergency_fallback() {
        return ['sehri' => '05:00', 'iftar' => '18:15', 'ramadan_day' => '—', 'source' => 'Default'];
    }

    public static function get_full_calendar($district) {
        return get_option("rsip_calendar_{$district}", []);
    }
}