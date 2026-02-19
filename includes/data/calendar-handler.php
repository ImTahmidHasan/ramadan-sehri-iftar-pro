<?php
if (!defined('ABSPATH')) exit;

if ( ! class_exists( 'RSIP_Data_Handler' ) ) {
    class RSIP_Data_Handler {
        public static function get_today_data($district) {
            $calendar = get_option("rsip_calendar_{$district}");
            $today = date('Y-m-d'); 
            
            if (!empty($calendar) && is_array($calendar)) {
                foreach ($calendar as $index => $day) {
                    // Match the CSV date column to TODAY'S date
                    if (isset($day['date']) && trim($day['date']) === $today) {
                        return [
                            'date'        => $day['date'],
                            'sehri'       => $day['sehri'],
                            'iftar'       => $day['iftar'],
                            'ramadan_day' => $index + 1,
                            'source'      => 'Official'
                        ];
                    }
                }
            }
            
            // If no match found for today, fallback to API
            $fallback = RSIP_API::fetch_times($district);
            if($fallback) {
                $fallback['source'] = 'API Fallback';
                return $fallback;
            }

            return [ 'date' => $today, 'sehri' => '05:00', 'iftar' => '18:15', 'ramadan_day' => '—', 'source' => 'System Default' ];
        }

        public static function get_full_calendar($district) {
            return get_option("rsip_calendar_{$district}", []);
        }
    }
}